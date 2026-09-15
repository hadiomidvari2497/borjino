<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Middleware\AuthMiddleware;
use App\Repositories\ContractRepository;
use App\Repositories\PersonRepository;
use App\Repositories\UnitRepository;
use App\Support\Csrf;
use App\Support\Session;
use App\Support\View;
use Throwable;

final class ContractController
{
    public function __construct(
        private readonly View $view,
        private readonly ContractRepository $contracts,
        private readonly PersonRepository $persons,
        private readonly UnitRepository $units,
        private readonly AuthMiddleware $auth,
    ) {}

    public function index(): string
    {
        $this->auth->handle();
        $unitId = (int) ($_GET['unit_id'] ?? 0) ?: null;
        $type = ($_GET['type'] ?? '') ?: null;
        $search = trim((string) ($_GET['q'] ?? ''));
        return $this->view->render('contracts/index', [
            'contracts' => $this->contracts->all($unitId, $type, $search),
            'unit_id' => $unitId, 'type' => $type, 'search' => $search,
            'csrf_token' => Csrf::token(), 'error' => Session::get('contract.error'),
        ]);
    }

    public function create(): string
    {
        $this->auth->handle();
        $unitId = (int) ($_GET['unit_id'] ?? 0);
        $unit = $unitId > 0 ? $this->units->find($unitId) : null;
        return $this->view->render('contracts/form', [
            'contract' => null, 'unit' => $unit, 'units' => $this->units->all(),
            'persons' => $this->persons->all(), 'action' => '/contracts/store',
            'csrf_token' => Csrf::token(), 'error' => Session::get('contract.error'),
        ]);
    }

    public function store(): string
    {
        $this->auth->handle();
        if (!Csrf::validate($_POST['_token'] ?? null)) return $this->invalidRequest();
        $data = $this->validatedData();
        if ($data['error'] !== null) return $this->redirectError('/contracts/create', $data['error']);
        try { $this->contracts->create($data['values']); Session::forget('contract.error'); header('Location: /contracts', true, 302); }
        catch (Throwable) { return $this->redirectError('/contracts/create', 'ثبت قرارداد انجام نشد.'); }
        return '';
    }

    public function edit(): string
    {
        $this->auth->handle();
        $id = (int) ($_GET['id'] ?? 0);
        $contract = $id > 0 ? $this->contracts->find($id) : null;
        if ($contract === null) { http_response_code(404); return 'قرارداد پیدا نشد.'; }
        return $this->view->render('contracts/form', [
            'contract' => $contract, 'unit' => $this->units->find((int) $contract['unit_id']),
            'units' => $this->units->all(), 'persons' => $this->persons->all(),
            'action' => '/contracts/update?id=' . $id, 'csrf_token' => Csrf::token(),
            'error' => Session::get('contract.error'),
        ]);
    }

    public function update(): string
    {
        $this->auth->handle();
        if (!Csrf::validate($_POST['_token'] ?? null)) return $this->invalidRequest();
        $id = (int) ($_GET['id'] ?? 0);
        if ($id < 1 || $this->contracts->find($id) === null) { http_response_code(404); return 'قرارداد پیدا نشد.'; }
        $data = $this->validatedData();
        if ($data['error'] !== null) return $this->redirectError('/contracts/edit?id=' . $id, $data['error']);
        try { $this->contracts->update($id, $data['values']); Session::forget('contract.error'); header('Location: /contracts', true, 302); }
        catch (Throwable) { return $this->redirectError('/contracts/edit?id=' . $id, 'ویرایش قرارداد انجام نشد.'); }
        return '';
    }

    public function delete(): string
    {
        $this->auth->handle();
        if (!Csrf::validate($_POST['_token'] ?? null)) return $this->invalidRequest();
        $id = (int) ($_POST['id'] ?? 0);
        $contract = $id > 0 ? $this->contracts->find($id) : null;
        if ($contract === null) { http_response_code(404); return 'قرارداد پیدا نشد.'; }
        try { $this->contracts->delete($id); Session::forget('contract.error'); }
        catch (Throwable) { Session::put('contract.error', 'قرارداد به دلیل داشتن اطلاعات وابسته قابل حذف نیست.'); }
        header('Location: /contracts', true, 302);
        return '';
    }

    private function validatedData(): array
    {
        $unitId = (int) ($_POST['unit_id'] ?? 0);
        $personId = (int) ($_POST['party_person_id'] ?? 0);
        $type = (string) ($_POST['contract_type'] ?? '');
        $contractDate = trim((string) ($_POST['contract_date'] ?? ''));
        $endDate = trim((string) ($_POST['end_date'] ?? ''));
        $deposit = trim((string) ($_POST['deposit_amount'] ?? ''));
        $rent = trim((string) ($_POST['monthly_rent'] ?? ''));
        $sale = trim((string) ($_POST['sale_amount'] ?? ''));
        if ($this->units->find($unitId) === null) return ['values'=>[],'error'=>'واحد انتخاب‌شده معتبر نیست.'];
        if ($this->persons->find($personId) === null) return ['values'=>[],'error'=>'شخص قرارداد معتبر نیست.'];
        if (!in_array($type, ['rental','sale'], true)) return ['values'=>[],'error'=>'نوع قرارداد معتبر نیست.'];
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $contractDate)) return ['values'=>[],'error'=>'تاریخ قرارداد نامعتبر است.'];
        if ($endDate !== '' && (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $endDate) || $endDate < $contractDate)) return ['values'=>[],'error'=>'تاریخ پایان نامعتبر است.'];
        foreach ([['مبلغ ودیعه',$deposit],['اجاره ماهانه',$rent],['مبلغ فروش',$sale]] as [$label,$value]) {
            if ($value !== '' && (!preg_match('/^\d+(\.\d{1,2})?$/', $value) || strlen($value) > 20)) return ['values'=>[],'error'=>"$label نامعتبر است."];
        }
        if ($type === 'rental' && $rent === '') return ['values'=>[],'error'=>'برای قرارداد اجاره، مبلغ اجاره ماهانه الزامی است.'];
        if ($type === 'sale' && $sale === '') return ['values'=>[],'error'=>'برای قرارداد فروش، مبلغ فروش الزامی است.'];
        return ['values'=>[
            'unit_id'=>$unitId, 'contract_type'=>$type, 'party_person_id'=>$personId,
            'deposit_amount'=>$deposit !== '' ? $deposit : null, 'monthly_rent'=>$rent !== '' ? $rent : null,
            'sale_amount'=>$sale !== '' ? $sale : null, 'contract_date'=>$contractDate,
            'end_date'=>$endDate !== '' ? $endDate : null,
        ],'error'=>null];
    }

    private function redirectError(string $path, string $error): string
    { Session::put('contract.error', $error); header('Location: ' . $path, true, 302); return ''; }

    private function invalidRequest(): string
    { http_response_code(419); return 'درخواست نامعتبر است.'; }
}
