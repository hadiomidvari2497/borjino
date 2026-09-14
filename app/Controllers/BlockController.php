<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Support\View;

final class BlockController
{
    public function __construct(private readonly View $view)
    {
    }

    public function index(): string
    {
        $blocks = [
            ['id' => 1, 'building_id' => 1, 'building_name' => 'ساختمان سپهر', 'block_number' => 1, 'name' => 'بلوک الف', 'floor_count' => 8, 'units_count' => 16, 'created_at' => '1401-03-15'],
            ['id' => 2, 'building_id' => 1, 'building_name' => 'ساختمان سپهر', 'block_number' => 2, 'name' => 'بلوک ب', 'floor_count' => 8, 'units_count' => 16, 'created_at' => '1401-03-15'],
            ['id' => 3, 'building_id' => 1, 'building_name' => 'ساختمان سپهر', 'block_number' => 3, 'name' => 'بلوک ج', 'floor_count' => 6, 'units_count' => 12, 'created_at' => '1401-04-20'],
            ['id' => 4, 'building_id' => 2, 'building_name' => 'مجتمع تجاری پارسیان', 'block_number' => 1, 'name' => 'بلوک تجاری ۱', 'floor_count' => 4, 'units_count' => 20, 'created_at' => '1402-01-10'],
            ['id' => 5, 'building_id' => 2, 'building_name' => 'مجتمع تجاری پارسیان', 'block_number' => 2, 'name' => 'بلوک تجاری ۲', 'floor_count' => 4, 'units_count' => 12, 'created_at' => '1402-01-10'],
        ];

        return $this->view->render('blocks/index', [
            'blocks' => $blocks,
        ]);
    }

    public function create(): string
    {
        return $this->view->render('blocks/create');
    }

    public function store(): void
    {
        header('Location: /blocks');
        exit;
    }

    public function show(int $id): string
    {
        return $this->view->render('blocks/show', ['block' => ['id' => $id, 'name' => 'بلوک الف']]);
    }

    public function edit(int $id): string
    {
        $block = [
            'id' => $id,
            'building_id' => 1,
            'block_number' => 1,
            'name' => 'بلوک الف',
            'floor_count' => 8,
        ];

        return $this->view->render('blocks/create', [
            'block' => $block,
        ]);
    }

    public function update(int $id): void
    {
        header('Location: /blocks');
        exit;
    }

    public function destroy(int $id): void
    {
        header('Location: /blocks');
        exit;
    }
}