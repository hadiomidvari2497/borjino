<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Support\View;

final class UnitController
{
    public function __construct(private readonly View $view)
    {
    }

    public function index(): string
    {
        $units = [
            ['id' => 1, 'building_id' => 1, 'building_name' => 'ساختمان سپهر', 'block_id' => 1, 'block_name' => 'بلوک الف', 'unit_number' => '۱۰۱', 'floor_number' => 1, 'area_sqm' => 120, 'status' => 'sold', 'financial_status' => 'settled', 'direction' => 'north', 'created_at' => '1401-05-10'],
            ['id' => 2, 'building_id' => 1, 'building_name' => 'ساختمان سپهر', 'block_id' => 1, 'block_name' => 'بلوک الف', 'unit_number' => '۱۰۲', 'floor_number' => 1, 'area_sqm' => 95, 'status' => 'rented', 'financial_status' => 'debtor', 'direction' => 'south', 'created_at' => '1401-05-10'],
            ['id' => 3, 'building_id' => 1, 'building_name' => 'ساختمان سپهر', 'block_id' => 1, 'block_name' => 'بلوک الف', 'unit_number' => '۲۰۱', 'floor_number' => 2, 'area_sqm' => 140, 'status' => 'vacant', 'financial_status' => 'settled', 'direction' => 'north', 'created_at' => '1401-05-10'],
            ['id' => 4, 'building_id' => 1, 'building_name' => 'ساختمان سپهر', 'block_id' => 2, 'block_name' => 'بلوک ب', 'unit_number' => '۱۰۱', 'floor_number' => 1, 'area_sqm' => 110, 'status' => 'sold', 'financial_status' => 'creditor', 'direction' => 'east', 'created_at' => '1401-06-15'],
            ['id' => 5, 'building_id' => 2, 'building_name' => 'مجتمع تجاری پارسیان', 'block_id' => 4, 'block_name' => 'بلوک تجاری ۱', 'unit_number' => 'م-۱', 'floor_number' => 1, 'area_sqm' => 80, 'status' => 'rented', 'financial_status' => 'settled', 'direction' => 'west', 'created_at' => '1402-02-01'],
        ];

        return $this->view->render('units/index', [
            'units' => $units,
        ]);
    }

    public function create(): string
    {
        return $this->view->render('units/create');
    }

    public function store(): void
    {
        header('Location: /units');
        exit;
    }

    public function show(int $id): string
    {
        return $this->view->render('units/show', ['unit' => ['id' => $id, 'unit_number' => '۱۰۱']]);
    }

    public function edit(int $id): string
    {
        $unit = [
            'id' => $id,
            'building_id' => 1,
            'block_id' => 1,
            'unit_number' => '۱۰۱',
            'floor_number' => 1,
            'area_sqm' => 120,
            'status' => 'sold',
            'financial_status' => 'settled',
            'direction' => 'north',
            'postal_code' => '',
            'notes' => '',
        ];

        return $this->view->render('units/create', [
            'unit' => $unit,
        ]);
    }

    public function update(int $id): void
    {
        header('Location: /units');
        exit;
    }

    public function destroy(int $id): void
    {
        header('Location: /units');
        exit;
    }
}