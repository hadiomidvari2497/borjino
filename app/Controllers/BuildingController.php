<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Support\View;

final class BuildingController
{
    public function __construct(private readonly View $view)
    {
    }

    public function index(): string
    {
        $buildings = [
            [
                'id' => 1,
                'name' => 'ساختمان سپهر',
                'type' => 'residential',
                'province' => 'تهران',
                'city' => 'تهران',
                'blocks_count' => 4,
                'units_count' => 48,
                'construction_date' => '1398-03-15',
                'status' => 'active'
            ],
            [
                'id' => 2,
                'name' => 'مجتمع تجاری پارسیان',
                'type' => 'commercial',
                'province' => 'تهران',
                'city' => 'شهریار',
                'blocks_count' => 2,
                'units_count' => 32,
                'construction_date' => '1400-06-20',
                'status' => 'active'
            ],
            [
                'id' => 3,
                'name' => 'ساختمان اداری آفتاب',
                'type' => 'office',
                'province' => 'البرز',
                'city' => 'کرج',
                'blocks_count' => 1,
                'units_count' => 15,
                'construction_date' => '1399-11-10',
                'status' => 'under_construction'
            ],
            [
                'id' => 4,
                'name' => 'مدرسه شهید بهشتی',
                'type' => 'educational',
                'province' => 'تهران',
                'city' => 'اسلامشهر',
                'blocks_count' => 3,
                'units_count' => 24,
                'construction_date' => '1395-01-01',
                'status' => 'active'
            ],
            [
                'id' => 5,
                'name' => 'ساختمان مسکونی گلسار',
                'type' => 'residential',
                'province' => 'مازندران',
                'city' => 'ساری',
                'blocks_count' => 2,
                'units_count' => 18,
                'construction_date' => '1401-04-12',
                'status' => 'inactive'
            ],
        ];

        return $this->view->render('buildings/index', [
            'buildings' => $buildings,
        ]);
    }

    public function create(): string
    {
        return $this->view->render('buildings/create');
    }

    public function store(): void
    {
        // TODO: Implement store logic
        header('Location: /buildings');
        exit;
    }

    public function show(int $id): string
    {
        $building = [
            'id' => $id,
            'name' => 'ساختمان سپهر',
            'type' => 'residential',
            'postal_code' => '1234567890',
            'province' => 'تهران',
            'city' => 'تهران',
            'address' => 'تهران، خیابان ولیعصر، پلاک ۱۲۳',
            'construction_date' => '1398-03-15',
            'total_parking_count' => 50,
            'total_storage_count' => 48,
            'blocks_count' => 4,
            'units_count' => 48,
            'status' => 'active'
        ];

        return $this->view->render('buildings/show', [
            'building' => $building,
        ]);
    }

    public function edit(int $id): string
    {
        $building = [
            'id' => $id,
            'name' => 'ساختمان سپهر',
            'type' => 'residential',
            'postal_code' => '1234567890',
            'province' => 'تهران',
            'city' => 'تهران',
            'address' => 'تهران، خیابان ولیعصر، پلاک ۱۲۳',
            'construction_date' => '1398-03-15',
            'total_parking_count' => 50,
            'total_storage_count' => 48,
            'status' => 'active'
        ];

        return $this->view->render('buildings/edit', [
            'building' => $building,
        ]);
    }

    public function update(int $id): void
    {
        // TODO: Implement update logic
        header('Location: /buildings/'.$id);
        exit;
    }

    public function destroy(int $id): void
    {
        // TODO: Implement delete logic
        header('Location: /buildings');
        exit;
    }
}