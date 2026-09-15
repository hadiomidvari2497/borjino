<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Middleware\AuthMiddleware;
use App\Repositories\BuildingPersonnelRepository;
use App\Repositories\BuildingRepository;
use App\Repositories\PersonRepository;
use App\Support\Csrf;
use App\Support\Session;
use App\Support\View;
use Throwable;

final class BuildingPersonnelController
{
    public function __construct(
        private readonly View $view,
        private readonly BuildingPersonnelRepository $personnel,
        private readonly BuildingRepository $buildings,
        private readonly PersonRepository $persons,
        private readonly AuthMiddleware $auth,
    ) {
    }

    public function index(): string
    {
        $this->auth->handle();
        $buildingId = isset($_GET['building_id']) ? (int) $_GET['building_id'] : null;
        $roleId = isset($_GET['role_id']) ? (int) $_GET['role_id'] : null;
        $search = trim((string) ($_GET['q'] ?? ''));

        return $this->view->render('building-personnel/index', [
            'personnel' => $this->personnel->all($buildingId, $roleId, $search),
            'buildings' => $this->buildings->all(),
            'roles' => $this->personnel->roles(),
            'building_id' => $buildingId,
            'role_id' => $roleId,
            'search' => $search,
            'csrf_token' => Csrf::token(),
            'error' => Session::get('personnel.error'),
        ]);
    }

    public function create(): string
    {
        $this->auth->handle();
        return $this->view->render('building-personnel/form', [
            'item' => null,
            'buildings' => $this->buildings->all(),
            'persons' => $this->persons->all(),
            'roles' => $this->personnel->roles(),
            'action' => '/building-personnel/store',
            'csrf_token' => Csrf::token(),
            'error' => Session::get('personnel.error'),
        ]);
    }

    public function store(): string
    {
        $this->auth->handle();
        if (!Csrf::validate($_POST['_token'] ?? null)) return $this->invalidRequest();
        $data = $this->validatedData();
        if ($data['error'] !== null) {
            Session::put('personnel.error', $data['error']);
            header('Location: /building-personnel/create', true, 302);
            return '';
        }
        try {
            $this->personnel->create($data['values']);
            Session::forget('personnel.error');
            header('Location: /building-personnel', true, 302);
        } catch (Throwable) {
            Session::put('personnel.error', 'این شخص با این نقش قبلاً برای ساختمان ثبت شده است.');
            header('Location: /building-personnel/create', true, 302);
        }
        return '';
    }

    public function edit(): string
    {
        $this->auth->handle();
        $id = (int) ($_GET['id'] ?? 0);
        $item = $id > 0 ? $this->personnel->find($id) : null;
        if ($item === null) { http_response_code(404); return 'رکورد پرسنل پیدا نشد.'; }

        return $this->view->render('building-personnel/form', [
            'item' => $item,
            'buildings' => $this->buildings->all(),
            'persons' => $this->persons->all(),
            'roles' => $this->personnel->roles(),
            'action' => '/building-personnel/update?id=' . $id,
            'csrf_token' => Csrf::token(),
            'error' => Session::get('personnel.error'),
        ]);
    }

    public function update(): string
    {
        $this->auth->handle();
        if (!Csrf::validate($_POST['_token'] ?? null)) return $this->invalidRequest();
        $id = (int) ($_GET['id'] ?? 0);
        if ($id < 1 || $this->personnel->find($id) === null) { http_response_code(404); return 'رکورد پرسنل پیدا نشد.'; }
        $data = $this->validatedData();
        if ($data['error'] !== null) {
            Session::put('personnel.error', $data['error']);
            header('Location: /building-personnel/edit?id=' . $id, true, 302);
            return '';
        }
        try {
            $this->personnel->update($id, $data['values']);
            Session::forget('personnel.error');
            header('Location: /building-personnel', true, 302);
        } catch (Throwable) {
            Session::put('personnel.error', 'این شخص با این نقش قبلاً برای ساختمان ثبت شده است.');
            header('Location: /building-personnel/edit?id=' . $id, true, 302);
        }
        return '';
    }

    public function delete(): string
    {
        $this->auth->handle();
        if (!Csrf::validate($_POST['_token'] ?? null)) return $this->invalidRequest();
        $id = (int) ($_POST['id'] ?? 0);
        if ($id < 1) { http_response_code(422); return 'شناسه نامعتبر است.'; }
        try {
            $this->personnel->delete($id);
            Session::forget('personnel.error');
        } catch (Throwable) {
            Session::put('personnel.error', 'حذف این رکورد امکان‌پذیر نیست.');
        }
        header('Location: /building-personnel', true, 302);
        return '';
    }

    private function validatedData(): array
    {
        $buildingId = (int) ($_POST['building_id'] ?? 0);
        $personId = (int) ($_POST['person_id'] ?? 0);
        $roleId = (int) ($_POST['role_id'] ?? 0);
        if ($buildingId < 1 || $this->buildings->find($buildingId) === null) return ['values' => [], 'error' => 'ساختمان انتخاب‌شده معتبر نیست.'];
        if ($personId < 1 || $this->persons->find($personId) === null) return ['values' => [], 'error' => 'شخص انتخاب‌شده معتبر نیست.'];
        $roles = array_column($this->personnel->roles(), 'id');
        if ($roleId < 1 || !in_array($roleId, array_map('intval', $roles), true)) return ['values' => [], 'error' => 'نقش انتخاب‌شده معتبر نیست.'];
        return ['values' => ['building_id' => $buildingId, 'person_id' => $personId, 'role_id' => $roleId], 'error' => null];
    }

    private function invalidRequest(): string
    {
        http_response_code(419);
        return 'درخواست نامعتبر است.';
    }
}
