<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Middleware\AuthMiddleware;
use App\Repositories\BlockRepository;
use App\Repositories\BuildingRepository;
use App\Repositories\UnitRepository;
use App\Support\Csrf;
use App\Support\Session;
use App\Support\View;
use Throwable;

final class UnitController
{
    public function __construct(
        private readonly View $view,
        private readonly UnitRepository $units,
        private readonly BuildingRepository $buildings,
        private readonly BlockRepository $blocks,
        private readonly AuthMiddleware $auth,
    ) {}

    public function index(): string
    {
        $this->auth->handle();
        $buildingId = (int) ($_GET['building_id'] ?? 0) ?: null;
        $blockId = (int) ($_GET['block_id'] ?? 0) ?: null;
        $search = trim((string) ($_GET['q'] ?? ''));
        $status = ($_GET['status'] ?? '') ?: null;
        $financialStatus = ($_GET['financial_status'] ?? '') ?: null;
        return $this->view->render('units/index', [
            'units' => $this->units->all($buildingId, $blockId, $search, $status, $financialStatus),
            'buildings' => $this->buildings->all(),
            'blocks' => $buildingId ? $this->blocks->all($buildingId) : [],
            'building_id' => $buildingId,
            'block_id' => $blockId,
            'search' => $search,
            'status' => $status,
            'financial_status' => $financialStatus,
            'csrf_token' => Csrf::token(),
            'error' => Session::get('unit.error'),
        ]);
    }

    public function create(): string
    {
        $this->auth->handle();
        $buildingId = (int) ($_GET['building_id'] ?? 0) ?: null;
        return $this->view->render('units/form', [
            'unit' => null,
            'buildings' => $this->buildings->all(),
            'blocks' => $buildingId ? $this->blocks->all($buildingId) : [],
            'action' => '/units/store',
            'csrf_token' => Csrf::token(),
            'error' => Session::get('unit.error'),
        ]);
    }

    public function store(): string
    {
        $this->auth->handle();
        if (!Csrf::validate($_POST['_token'] ?? null)) return $this->invalidRequest();
        $data = $this->validatedData();
        if ($data['error'] !== null) return $this->redirectError('/units/create?building_id=' . (int)($data['values']['building_id'] ?? 0), $data['error']);
        try { $this->units->create($data['values']); Session::forget('unit.error'); header('Location: /units', true, 302); }
        catch (Throwable) { return $this->redirectError('/units/create', 'شماره واحد در این بلوک قبلاً ثبت شده است.'); }
        return '';
    }

    public function edit(): string
    {
        $this->auth->handle();
        $id = (int) ($_GET['id'] ?? 0);
        $unit = $id > 0 ? $this->units->find($id) : null;
        if ($unit === null) { http_response_code(404); return 'واحد پیدا نشد.'; }
        return $this->view->render('units/form', [
            'unit' => $unit,
            'buildings' => $this->buildings->all(),
            'blocks' => $this->blocks->all((int)$unit['building_id']),
            'action' => '/units/update?id=' . $id,
            'csrf_token' => Csrf::token(),
            'error' => Session::get('unit.error'),
        ]);
    }

    public function update(): string
    {
        $this->auth->handle();
        if (!Csrf::validate($_POST['_token'] ?? null)) return $this->invalidRequest();
        $id = (int) ($_GET['id'] ?? 0);
        if ($id < 1 || $this->units->find($id) === null) { http_response_code(404); return 'واحد پیدا نشد.'; }
        $data = $this->validatedData();
        if ($data['error'] !== null) return $this->redirectError('/units/edit?id=' . $id, $data['error']);
        try { $this->units->update($id, $data['values']); Session::forget('unit.error'); header('Location: /units', true, 302); }
        catch (Throwable) { return $this->redirectError('/units/edit?id=' . $id, 'شماره واحد در این بلوک قبلاً ثبت شده است.'); }
        return '';
    }

    public function delete(): string
    {
        $this->auth->handle();
        if (!Csrf::validate($_POST['_token'] ?? null)) return $this->invalidRequest();
        $id = (int) ($_POST['id'] ?? 0);
        try { $this->units->delete($id); Session::forget('unit.error'); }
        catch (Throwable) { Session::put('unit.error', 'واحد به دلیل داشتن اطلاعات وابسته قابل حذف نیست.'); }
        header('Location: /units', true, 302);
        return '';
    }

    public function generate(): string
    {
        $this->auth->handle();
        $blockId = (int) ($_GET['block_id'] ?? 0);
        $block = $blockId > 0 ? $this->blocks->find($blockId) : null;
        if ($block === null) { http_response_code(404); return 'بلوک پیدا نشد.'; }
        return $this->view->render('units/generate', [
            'block' => $block,
            'csrf_token' => Csrf::token(),
            'error' => Session::get('unit.error'),
        ]);
    }

    public function generateStore(): string
    {
        $this->auth->handle();
        if (!Csrf::validate($_POST['_token'] ?? null)) return $this->invalidRequest();
        $blockId = (int) ($_POST['block_id'] ?? 0);
        $unitsPerFloor = (int) ($_POST['units_per_floor'] ?? 0);
        $block = $blockId > 0 ? $this->blocks->find($blockId) : null;
        if ($block === null || $unitsPerFloor < 1 || $unitsPerFloor > 50) return $this->redirectError('/blocks', 'تعداد واحد در هر طبقه باید بین ۱ تا ۵۰ باشد.');
        try {
            $created = $this->units->generateForBlock((int)$block['building_id'], $blockId, $unitsPerFloor);
            Session::forget('unit.error');
            Session::put('unit.success', $created . ' واحد جدید تولید شد.');
        } catch (Throwable) { return $this->redirectError('/blocks', 'تولید واحدها انجام نشد.'); }
        header('Location: /units?block_id=' . $blockId, true, 302);
        return '';
    }

    private function validatedData(): array
    {
        $buildingId = (int) ($_POST['building_id'] ?? 0);
        $blockId = (int) ($_POST['block_id'] ?? 0);
        $unitNumber = trim((string) ($_POST['unit_number'] ?? ''));
        $postalCode = trim((string) ($_POST['postal_code'] ?? ''));
        $floor = (int) ($_POST['floor_number'] ?? 0);
        $area = (float) ($_POST['area_sqm'] ?? 0);
        $status = (string) ($_POST['status'] ?? 'vacant');
        $financial = (string) ($_POST['financial_status'] ?? 'settled');
        $direction = (string) ($_POST['direction'] ?? '');
        $notes = trim((string) ($_POST['notes'] ?? ''));
        $block = $blockId > 0 ? $this->blocks->find($blockId) : null;
        if ($buildingId < 1 || $this->buildings->find($buildingId) === null) return ['values' => ['building_id' => $buildingId], 'error' => 'ساختمان انتخاب‌شده معتبر نیست.'];
        if ($block === null || (int)$block['building_id'] !== $buildingId) return ['values' => ['building_id' => $buildingId], 'error' => 'بلوک انتخاب‌شده متعلق به این ساختمان نیست.'];
        if ($unitNumber === '' || mb_strlen($unitNumber) > 50) return ['values' => ['building_id' => $buildingId], 'error' => 'شماره واحد الزامی و حداکثر ۵۰ کاراکتر است.'];
        if ($floor < 1 || $floor > (int)$block['floor_count']) return ['values' => ['building_id' => $buildingId], 'error' => 'طبقه باید داخل محدوده طبقات بلوک باشد.'];
        if ($area < 0) return ['values' => ['building_id' => $buildingId], 'error' => 'متراژ نمی‌تواند منفی باشد.'];
        if (!in_array($status, ['sold','rented','vacant','under_repair'], true)) return ['values' => ['building_id' => $buildingId], 'error' => 'وضعیت واحد نامعتبر است.'];
        if (!in_array($financial, ['debtor','creditor','settled'], true)) return ['values' => ['building_id' => $buildingId], 'error' => 'وضعیت مالی نامعتبر است.'];
        if ($direction !== '' && !in_array($direction, ['north','south','east','west'], true)) return ['values' => ['building_id' => $buildingId], 'error' => 'جهت واحد نامعتبر است.'];
        return ['values' => ['building_id'=>$buildingId,'block_id'=>$blockId,'unit_number'=>$unitNumber,'postal_code'=>$postalCode !== '' ? $postalCode : null,'floor_number'=>$floor,'area_sqm'=>$area,'status'=>$status,'financial_status'=>$financial,'direction'=>$direction !== '' ? $direction : null,'notes'=>$notes !== '' ? $notes : null], 'error'=>null];
    }

    private function redirectError(string $path, string $error): string
    {
        Session::put('unit.error', $error);
        header('Location: ' . $path, true, 302);
        return '';
    }

    private function invalidRequest(): string
    {
        http_response_code(419);
        return 'درخواست نامعتبر است.';
    }
}
