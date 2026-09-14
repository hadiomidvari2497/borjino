<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Middleware\AuthMiddleware;
use App\Repositories\BuildingRepository;
use App\Support\Csrf;
use App\Support\Session;
use App\Support\View;
use Throwable;

final class BuildingController
{
    public function __construct(
        private readonly View $view,
        private readonly BuildingRepository $buildings,
        private readonly AuthMiddleware $auth,
    ) {
    }

    public function index(): string
    {
        $this->auth->handle();

        $search = trim((string) ($_GET['q'] ?? ''));

        return $this->view->render('buildings/index', [
            'buildings' => $this->buildings->all($search),
            'search' => $search,
            'csrf_token' => Csrf::token(),
            'error' => Session::get('building.error'),
        ]);
    }

    public function create(): string
    {
        $this->auth->handle();

        return $this->view->render('buildings/form', [
            'building' => null,
            'action' => '/buildings/store',
            'csrf_token' => Csrf::token(),
            'error' => Session::get('building.error'),
        ]);
    }

    public function store(): string
    {
        $this->auth->handle();
        if (!Csrf::validate($_POST['_token'] ?? null)) {
            return $this->invalidRequest();
        }

        $data = $this->validatedData();
        if ($data['error'] !== null) {
            Session::put('building.error', $data['error']);
            header('Location: /buildings/create', true, 302);
            return '';
        }

        $this->buildings->create($data['values']);
        Session::forget('building.error');
        header('Location: /buildings', true, 302);
        return '';
    }

    public function edit(): string
    {
        $this->auth->handle();
        $id = (int) ($_GET['id'] ?? 0);
        $building = $id > 0 ? $this->buildings->find($id) : null;

        if ($building === null) {
            http_response_code(404);
            return 'ساختمان پیدا نشد.';
        }

        return $this->view->render('buildings/form', [
            'building' => $building,
            'action' => '/buildings/update?id=' . $id,
            'csrf_token' => Csrf::token(),
            'error' => Session::get('building.error'),
        ]);
    }

    public function update(): string
    {
        $this->auth->handle();
        if (!Csrf::validate($_POST['_token'] ?? null)) {
            return $this->invalidRequest();
        }

        $id = (int) ($_GET['id'] ?? 0);
        if ($id < 1 || $this->buildings->find($id) === null) {
            http_response_code(404);
            return 'ساختمان پیدا نشد.';
        }

        $data = $this->validatedData();
        if ($data['error'] !== null) {
            Session::put('building.error', $data['error']);
            header('Location: /buildings/edit?id=' . $id, true, 302);
            return '';
        }

        $this->buildings->update($id, $data['values']);
        Session::forget('building.error');
        header('Location: /buildings', true, 302);
        return '';
    }

    public function delete(): string
    {
        $this->auth->handle();
        if (!Csrf::validate($_POST['_token'] ?? null)) {
            return $this->invalidRequest();
        }

        $id = (int) ($_POST['id'] ?? 0);
        if ($id < 1) {
            http_response_code(422);
            return 'شناسه ساختمان نامعتبر است.';
        }

        try {
            $this->buildings->delete($id);
        } catch (Throwable) {
            Session::put('building.error', 'ساختمان به دلیل داشتن اطلاعات وابسته قابل حذف نیست.');
        }

        header('Location: /buildings', true, 302);
        return '';
    }

    private function validatedData(): array
    {
        $name = trim((string) ($_POST['name'] ?? ''));
        if ($name === '' || mb_strlen($name) > 150) {
            return ['values' => [], 'error' => 'نام ساختمان الزامی است و حداکثر ۱۵۰ کاراکتر دارد.'];
        }

        $parking = max(0, (int) ($_POST['parking_count'] ?? 0));
        $storage = max(0, (int) ($_POST['storage_count'] ?? 0));
        $date = trim((string) ($_POST['construction_date'] ?? ''));

        return [
            'values' => [
                'name' => $name,
                'postal_code' => trim((string) ($_POST['postal_code'] ?? '')) ?: null,
                'building_type' => trim((string) ($_POST['building_type'] ?? '')) ?: null,
                'construction_date' => $date !== '' ? $date : null,
                'parking_count' => $parking,
                'storage_count' => $storage,
                'province' => trim((string) ($_POST['province'] ?? '')) ?: null,
                'city' => trim((string) ($_POST['city'] ?? '')) ?: null,
                'address' => trim((string) ($_POST['address'] ?? '')) ?: null,
            ],
            'error' => null,
        ];
    }

    private function invalidRequest(): string
    {
        http_response_code(419);
        return 'درخواست نامعتبر است.';
    }
}
