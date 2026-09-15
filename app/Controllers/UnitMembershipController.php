<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Middleware\AuthMiddleware;
use App\Repositories\PersonRepository;
use App\Repositories\UnitMembershipRepository;
use App\Repositories\UnitRepository;
use App\Support\Csrf;
use App\Support\Session;
use App\Support\View;
use Throwable;

final class UnitMembershipController
{
    public function __construct(
        private readonly View $view,
        private readonly UnitMembershipRepository $memberships,
        private readonly PersonRepository $persons,
        private readonly UnitRepository $units,
        private readonly AuthMiddleware $auth,
    ) {
    }

    public function index(): string
    {
        $this->auth->handle();
        $unitId = (int) ($_GET['unit_id'] ?? 0);
        $unit = $unitId > 0 ? $this->units->find($unitId) : null;
        if ($unit === null) { http_response_code(404); return 'واحد پیدا نشد.'; }

        return $this->view->render('unit_memberships/index', [
            'unit' => $unit,
            'memberships' => $this->memberships->allForUnit($unitId),
            'csrf_token' => Csrf::token(),
            'error' => Session::get('membership.error'),
        ]);
    }

    public function create(): string
    {
        $this->auth->handle();
        $unitId = (int) ($_GET['unit_id'] ?? 0);
        $unit = $unitId > 0 ? $this->units->find($unitId) : null;
        if ($unit === null) { http_response_code(404); return 'واحد پیدا نشد.'; }

        return $this->view->render('unit_memberships/form', [
            'unit' => $unit,
            'membership' => null,
            'persons' => $this->persons->all(),
            'action' => '/unit-memberships/store?unit_id=' . $unitId,
            'csrf_token' => Csrf::token(),
            'error' => Session::get('membership.error'),
        ]);
    }

    public function store(): string
    {
        $this->auth->handle();
        if (!Csrf::validate($_POST['_token'] ?? null)) return $this->invalidRequest();
        $unitId = (int) ($_GET['unit_id'] ?? 0);
        if ($this->units->find($unitId) === null) { http_response_code(404); return 'واحد پیدا نشد.'; }
        $data = $this->validatedData($unitId);
        if ($data['error'] !== null) {
            Session::put('membership.error', $data['error']);
            header('Location: /unit-memberships/create?unit_id=' . $unitId, true, 302);
            return '';
        }
        try {
            $this->memberships->create($data['values']);
            Session::forget('membership.error');
            header('Location: /unit-memberships?unit_id=' . $unitId, true, 302);
        } catch (Throwable) {
            Session::put('membership.error', 'ثبت ارتباط شخص با واحد انجام نشد.');
            header('Location: /unit-memberships/create?unit_id=' . $unitId, true, 302);
        }
        return '';
    }

    public function edit(): string
    {
        $this->auth->handle();
        $id = (int) ($_GET['id'] ?? 0);
        $membership = $id > 0 ? $this->memberships->find($id) : null;
        if ($membership === null) { http_response_code(404); return 'ارتباط پیدا نشد.'; }
        $unit = $this->units->find((int) $membership['unit_id']);
        if ($unit === null) { http_response_code(404); return 'واحد پیدا نشد.'; }

        return $this->view->render('unit_memberships/form', [
            'unit' => $unit,
            'membership' => $membership,
            'persons' => $this->persons->all(),
            'action' => '/unit-memberships/update?id=' . $id,
            'csrf_token' => Csrf::token(),
            'error' => Session::get('membership.error'),
        ]);
    }

    public function update(): string
    {
        $this->auth->handle();
        if (!Csrf::validate($_POST['_token'] ?? null)) return $this->invalidRequest();
        $id = (int) ($_GET['id'] ?? 0);
        $membership = $id > 0 ? $this->memberships->find($id) : null;
        if ($membership === null) { http_response_code(404); return 'ارتباط پیدا نشد.'; }
        $unitId = (int) $membership['unit_id'];
        $data = $this->validatedData($unitId);
        if ($data['error'] !== null) {
            Session::put('membership.error', $data['error']);
            header('Location: /unit-memberships/edit?id=' . $id, true, 302);
            return '';
        }
        try {
            $this->memberships->update($id, $data['values']);
            Session::forget('membership.error');
            header('Location: /unit-memberships?unit_id=' . $unitId, true, 302);
        } catch (Throwable) {
            Session::put('membership.error', 'ویرایش ارتباط انجام نشد.');
            header('Location: /unit-memberships/edit?id=' . $id, true, 302);
        }
        return '';
    }

    public function delete(): string
    {
        $this->auth->handle();
        if (!Csrf::validate($_POST['_token'] ?? null)) return $this->invalidRequest();
        $id = (int) ($_POST['id'] ?? 0);
        $membership = $id > 0 ? $this->memberships->find($id) : null;
        if ($membership === null) { http_response_code(404); return 'ارتباط پیدا نشد.'; }
        $unitId = (int) $membership['unit_id'];
        try {
            $this->memberships->delete($id);
            Session::forget('membership.error');
        } catch (Throwable) {
            Session::put('membership.error', 'این ارتباط قابل حذف نیست.');
        }
        header('Location: /unit-memberships?unit_id=' . $unitId, true, 302);
        return '';
    }

    private function validatedData(int $unitId): array
    {
        $personId = (int) ($_POST['person_id'] ?? 0);
        $type = (string) ($_POST['membership_type'] ?? '');
        $start = trim((string) ($_POST['start_date'] ?? ''));
        $end = trim((string) ($_POST['end_date'] ?? ''));
        $isCurrent = isset($_POST['is_current']) ? 1 : 0;
        $notes = trim((string) ($_POST['notes'] ?? ''));

        if ($personId < 1 || $this->persons->find($personId) === null) return ['values' => [], 'error' => 'شخص انتخاب‌شده معتبر نیست.'];
        if (!in_array($type, ['owner', 'tenant'], true)) return ['values' => [], 'error' => 'نوع ارتباط باید مالک یا مستأجر باشد.'];
        if ($start !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $start)) return ['values' => [], 'error' => 'تاریخ شروع نامعتبر است.'];
        if ($end !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $end)) return ['values' => [], 'error' => 'تاریخ پایان نامعتبر است.'];
        if ($start !== '' && $end !== '' && $end < $start) return ['values' => [], 'error' => 'تاریخ پایان نمی‌تواند قبل از شروع باشد.'];
        if (mb_strlen($notes) > 2000) return ['values' => [], 'error' => 'توضیحات بیش از حد مجاز است.'];

        return ['values' => [
            'unit_id' => $unitId,
            'person_id' => $personId,
            'membership_type' => $type,
            'start_date' => $start !== '' ? $start : null,
            'end_date' => $end !== '' ? $end : null,
            'is_current' => $isCurrent,
            'notes' => $notes !== '' ? $notes : null,
        ], 'error' => null];
    }

    private function invalidRequest(): string
    {
        http_response_code(419);
        return 'درخواست نامعتبر است.';
    }
}
