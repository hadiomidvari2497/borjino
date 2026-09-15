<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Middleware\AuthMiddleware;
use App\Repositories\PersonRepository;
use App\Support\Csrf;
use App\Support\Session;
use App\Support\View;
use Throwable;

final class PersonController
{
    public function __construct(
        private readonly View $view,
        private readonly PersonRepository $persons,
        private readonly AuthMiddleware $auth,
    ) {
    }

    public function index(): string
    {
        $this->auth->handle();
        $search = trim((string) ($_GET['q'] ?? ''));
        $type = isset($_GET['type']) ? (string) $_GET['type'] : null;

        return $this->view->render('persons/index', [
            'persons' => $this->persons->all($search, $type),
            'search' => $search,
            'type' => $type,
            'csrf_token' => Csrf::token(),
            'error' => Session::get('person.error'),
        ]);
    }

    public function create(): string
    {
        $this->auth->handle();
        return $this->view->render('persons/form', [
            'person' => null,
            'action' => '/persons/store',
            'csrf_token' => Csrf::token(),
            'error' => Session::get('person.error'),
        ]);
    }

    public function store(): string
    {
        $this->auth->handle();
        if (!Csrf::validate($_POST['_token'] ?? null)) return $this->invalidRequest();

        $data = $this->validatedData();
        if ($data['error'] !== null) {
            Session::put('person.error', $data['error']);
            header('Location: /persons/create', true, 302);
            return '';
        }

        try {
            $this->persons->create($data['values']);
            Session::forget('person.error');
            header('Location: /persons', true, 302);
        } catch (Throwable) {
            Session::put('person.error', 'کد ملی/شناسه ثبت‌شده برای شخص دیگری است.');
            header('Location: /persons/create', true, 302);
        }
        return '';
    }

    public function edit(): string
    {
        $this->auth->handle();
        $id = (int) ($_GET['id'] ?? 0);
        $person = $id > 0 ? $this->persons->find($id) : null;
        if ($person === null) { http_response_code(404); return 'شخص پیدا نشد.'; }

        return $this->view->render('persons/form', [
            'person' => $person,
            'action' => '/persons/update?id=' . $id,
            'csrf_token' => Csrf::token(),
            'error' => Session::get('person.error'),
        ]);
    }

    public function update(): string
    {
        $this->auth->handle();
        if (!Csrf::validate($_POST['_token'] ?? null)) return $this->invalidRequest();
        $id = (int) ($_GET['id'] ?? 0);
        if ($id < 1 || $this->persons->find($id) === null) { http_response_code(404); return 'شخص پیدا نشد.'; }

        $data = $this->validatedData();
        if ($data['error'] !== null) {
            Session::put('person.error', $data['error']);
            header('Location: /persons/edit?id=' . $id, true, 302);
            return '';
        }

        try {
            $this->persons->update($id, $data['values']);
            Session::forget('person.error');
            header('Location: /persons', true, 302);
        } catch (Throwable) {
            Session::put('person.error', 'کد ملی/شناسه ثبت‌شده برای شخص دیگری است.');
            header('Location: /persons/edit?id=' . $id, true, 302);
        }
        return '';
    }

    public function delete(): string
    {
        $this->auth->handle();
        if (!Csrf::validate($_POST['_token'] ?? null)) return $this->invalidRequest();
        $id = (int) ($_POST['id'] ?? 0);
        if ($id < 1) { http_response_code(422); return 'شناسه شخص نامعتبر است.'; }

        try {
            $this->persons->delete($id);
            Session::forget('person.error');
        } catch (Throwable) {
            Session::put('person.error', 'این شخص دارای ارتباط یا سابقه وابسته است و قابل حذف نیست.');
        }
        header('Location: /persons', true, 302);
        return '';
    }

    private function validatedData(): array
    {
        $type = (string) ($_POST['person_type'] ?? 'individual');
        if (!in_array($type, ['individual', 'legal'], true)) {
            return ['values' => [], 'error' => 'نوع شخص معتبر نیست.'];
        }

        $firstName = trim((string) ($_POST['first_name'] ?? ''));
        $lastName = trim((string) ($_POST['last_name'] ?? ''));
        $legalName = trim((string) ($_POST['legal_name'] ?? ''));
        $nationalId = trim((string) ($_POST['national_id'] ?? ''));
        $phone = trim((string) ($_POST['phone'] ?? ''));
        $birthDate = trim((string) ($_POST['birth_or_establishment_date'] ?? ''));
        $address = trim((string) ($_POST['address'] ?? ''));
        $secondaryAddress = trim((string) ($_POST['secondary_address'] ?? ''));

        if ($type === 'individual' && ($firstName === '' || $lastName === '')) {
            return ['values' => [], 'error' => 'نام و نام خانوادگی برای شخص حقیقی الزامی است.'];
        }
        if ($type === 'legal' && $legalName === '') {
            return ['values' => [], 'error' => 'نام شخص حقوقی الزامی است.'];
        }
        if (mb_strlen($firstName) > 100 || mb_strlen($lastName) > 100 || mb_strlen($legalName) > 200) {
            return ['values' => [], 'error' => 'نام واردشده بیش از حد مجاز است.'];
        }
        if (mb_strlen($nationalId) > 30 || mb_strlen($phone) > 30) {
            return ['values' => [], 'error' => 'کد ملی/شناسه یا شماره تلفن بیش از حد مجاز است.'];
        }
        if ($birthDate !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $birthDate)) {
            return ['values' => [], 'error' => 'تاریخ باید با قالب YYYY-MM-DD وارد شود.'];
        }

        return ['values' => [
            'first_name' => $type === 'individual' ? $firstName : null,
            'last_name' => $type === 'individual' ? $lastName : null,
            'legal_name' => $type === 'legal' ? $legalName : null,
            'person_type' => $type,
            'national_id' => $nationalId !== '' ? $nationalId : null,
            'phone' => $phone !== '' ? $phone : null,
            'birth_or_establishment_date' => $birthDate !== '' ? $birthDate : null,
            'address' => $address !== '' ? $address : null,
            'secondary_address' => $secondaryAddress !== '' ? $secondaryAddress : null,
        ], 'error' => null];
    }

    private function invalidRequest(): string
    {
        http_response_code(419);
        return 'درخواست نامعتبر است.';
    }
}
