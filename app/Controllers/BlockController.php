<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Middleware\AuthMiddleware;
use App\Repositories\BlockRepository;
use App\Repositories\BuildingRepository;
use App\Support\Csrf;
use App\Support\Session;
use App\Support\View;
use Throwable;

final class BlockController
{
    public function __construct(
        private readonly View $view,
        private readonly BlockRepository $blocks,
        private readonly BuildingRepository $buildings,
        private readonly AuthMiddleware $auth,
    ) {
    }

    public function index(): string
    {
        $this->auth->handle();
        $buildingId = isset($_GET['building_id']) ? (int) $_GET['building_id'] : null;
        $search = trim((string) ($_GET['q'] ?? ''));
        return $this->view->render('blocks/index', [
            'blocks' => $this->blocks->all($buildingId, $search),
            'buildings' => $this->buildings->all(),
            'building_id' => $buildingId,
            'search' => $search,
            'csrf_token' => Csrf::token(),
            'error' => Session::get('block.error'),
        ]);
    }

    public function create(): string
    {
        $this->auth->handle();
        return $this->view->render('blocks/form', [
            'block' => null,
            'buildings' => $this->buildings->all(),
            'action' => '/blocks/store',
            'csrf_token' => Csrf::token(),
            'error' => Session::get('block.error'),
        ]);
    }

    public function store(): string
    {
        $this->auth->handle();
        if (!Csrf::validate($_POST['_token'] ?? null)) return $this->invalidRequest();
        $data = $this->validatedData();
        if ($data['error'] !== null) {
            Session::put('block.error', $data['error']);
            header('Location: /blocks/create', true, 302);
            return '';
        }
        try {
            $this->blocks->create($data['values']);
            Session::forget('block.error');
            header('Location: /blocks', true, 302);
        } catch (Throwable) {
            Session::put('block.error', 'شماره بلوک در این ساختمان قبلاً ثبت شده است.');
            header('Location: /blocks/create', true, 302);
        }
        return '';
    }

    public function edit(): string
    {
        $this->auth->handle();
        $id = (int) ($_GET['id'] ?? 0);
        $block = $id > 0 ? $this->blocks->find($id) : null;
        if ($block === null) { http_response_code(404); return 'بلوک پیدا نشد.'; }
        return $this->view->render('blocks/form', [
            'block' => $block,
            'buildings' => $this->buildings->all(),
            'action' => '/blocks/update?id=' . $id,
            'csrf_token' => Csrf::token(),
            'error' => Session::get('block.error'),
        ]);
    }

    public function update(): string
    {
        $this->auth->handle();
        if (!Csrf::validate($_POST['_token'] ?? null)) return $this->invalidRequest();
        $id = (int) ($_GET['id'] ?? 0);
        if ($id < 1 || $this->blocks->find($id) === null) { http_response_code(404); return 'بلوک پیدا نشد.'; }
        $data = $this->validatedData();
        if ($data['error'] !== null) {
            Session::put('block.error', $data['error']);
            header('Location: /blocks/edit?id=' . $id, true, 302);
            return '';
        }
        try {
            $this->blocks->update($id, $data['values']);
            Session::forget('block.error');
            header('Location: /blocks', true, 302);
        } catch (Throwable) {
            Session::put('block.error', 'شماره بلوک در این ساختمان قبلاً ثبت شده است.');
            header('Location: /blocks/edit?id=' . $id, true, 302);
        }
        return '';
    }

    public function delete(): string
    {
        $this->auth->handle();
        if (!Csrf::validate($_POST['_token'] ?? null)) return $this->invalidRequest();
        $id = (int) ($_POST['id'] ?? 0);
        if ($id < 1) { http_response_code(422); return 'شناسه بلوک نامعتبر است.'; }
        try {
            $this->blocks->delete($id);
            Session::forget('block.error');
        } catch (Throwable) {
            Session::put('block.error', 'بلوک به دلیل داشتن واحدهای وابسته قابل حذف نیست.');
        }
        header('Location: /blocks', true, 302);
        return '';
    }

    private function validatedData(): array
    {
        $buildingId = (int) ($_POST['building_id'] ?? 0);
        $number = (int) ($_POST['block_number'] ?? 0);
        $name = trim((string) ($_POST['name'] ?? ''));
        $floors = (int) ($_POST['floor_count'] ?? 0);
        if ($buildingId < 1 || $this->buildings->find($buildingId) === null) return ['values' => [], 'error' => 'ساختمان انتخاب‌شده معتبر نیست.'];
        if ($number < 1) return ['values' => [], 'error' => 'شماره بلوک باید حداقل ۱ باشد.'];
        if ($floors < 1) return ['values' => [], 'error' => 'تعداد طبقات باید حداقل ۱ باشد.'];
        if (mb_strlen($name) > 150) return ['values' => [], 'error' => 'نام بلوک حداکثر ۱۵۰ کاراکتر دارد.'];
        return ['values' => ['building_id' => $buildingId, 'block_number' => $number, 'name' => $name !== '' ? $name : null, 'floor_count' => $floors], 'error' => null];
    }

    private function invalidRequest(): string
    {
        http_response_code(419);
        return 'درخواست نامعتبر است.';
    }
}
