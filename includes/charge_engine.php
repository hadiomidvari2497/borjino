<?php
function charge_methods(): array {
    return [
        1 => 'واحدی',
        2 => 'متراژی',
        3 => 'نفری',
        4 => 'نفر-متراژی',
        5 => 'واحدی + متراژی',
        6 => 'واحدی + نفری',
        7 => 'واحدی + نفر-متراژی',
        8 => 'نفری + متراژی',
        9 => 'واحدی + متراژی + نفری',
        10 => 'هزینه‌محور',
    ];
}

function get_charge_settings(PDO $pdo): array {
    $row = $pdo->query('SELECT * FROM charge_settings WHERE id=1')->fetch();
    if (!$row) {
        $pdo->exec('INSERT INTO charge_settings(id) VALUES(1)');
        $row = $pdo->query('SELECT * FROM charge_settings WHERE id=1')->fetch();
    }
    return $row;
}

function charge_period_bounds(string $period): array {
    if (!preg_match('/^(\d{4})-(\d{2})$/', $period, $m)) {
        throw new InvalidArgumentException('دوره شارژ باید به شکل YYYY-MM باشد.');
    }
    $year = (int)$m[1];
    $month = (int)$m[2];
    if ($month < 1 || $month > 12) {
        throw new InvalidArgumentException('ماه دوره شارژ نامعتبر است.');
    }
    $start = sprintf('%04d-%02d-01', $year, $month);
    return [$start, date('Y-m-t', strtotime($start))];
}

function charge_period_parts(string $period): array {
    [$start, $end] = charge_period_bounds($period);
    return [(int)date('Y', strtotime($start)), (int)date('m', strtotime($start))];
}

function charge_resident_count(PDO $pdo, int $unitId, string $period): int {
    [$start, $end] = charge_period_bounds($period);
    $st = $pdo->prepare(
        'SELECT COUNT(*) FROM memberships
         WHERE unit_id=?
           AND (start_date IS NULL OR start_date<=?)
           AND (end_date IS NULL OR end_date>=?)'
    );
    $st->execute([$unitId, $end, $start]);
    return (int)$st->fetchColumn();
}

function charge_money($value): string {
    return number_format(round((float)$value, 2, PHP_ROUND_HALF_UP), 2, '.', '');
}

function charge_calculate_standard(
    int $method,
    float $area,
    int $persons,
    float $U,
    float $rm,
    float $rn,
    float $areaPercent,
    float $personPercent,
    float $parking,
    float $storage
): array {
    if ($method < 1 || $method > 9) {
        throw new InvalidArgumentException('روش استاندارد محاسبه شارژ نامعتبر است.');
    }
    if ($method === 4 || $method === 7) {
        if (abs(($areaPercent + $personPercent) - 100) > 0.0001) {
            throw new InvalidArgumentException('مجموع سهم متراژ و نفر باید 100 درصد باشد.');
        }
    }

    $areaPart = $area * $rm;
    $personPart = $persons * $rn;

    switch ($method) {
        case 1: $base = $U; break;
        case 2: $base = $areaPart; break;
        case 3: $base = $personPart; break;
        case 4:
            $base = ($areaPart * $areaPercent / 100) + ($personPart * $personPercent / 100);
            break;
        case 5: $base = $U + $areaPart; break;
        case 6: $base = $U + $personPart; break;
        case 7:
            $base = $U + ($areaPart * $areaPercent / 100) + ($personPart * $personPercent / 100);
            break;
        case 8: $base = $personPart + $areaPart; break;
        case 9: $base = $U + $areaPart + $personPart; break;
        default: $base = 0;
    }

    $extra = $method === 1 ? ($parking + $storage) : 0;
    $total = $base + $extra;

    return [
        'amount' => charge_money($total),
        'base' => $base,
        'area_part' => $areaPart,
        'person_part' => $personPart,
        'parking' => $parking,
        'storage' => $storage,
        'total' => $total,
    ];
}

function charge_cost_share(
    float $amount,
    string $allocation,
    float $unitArea,
    float $totalArea,
    int $unitPersons,
    int $totalPersons,
    int $unitCount,
    float $areaPercent = 0,
    float $personPercent = 0
): float {
    switch ($allocation) {
        case 'equal':
            return $unitCount > 0 ? $amount / $unitCount : 0.0;
        case 'area':
            return $totalArea > 0 ? $amount * $unitArea / $totalArea : 0.0;
        case 'person':
            return $totalPersons > 0 ? $amount * $unitPersons / $totalPersons : 0.0;
        case 'combination':
            if (abs(($areaPercent + $personPercent) - 100) > 0.0001) {
                throw new InvalidArgumentException('مجموع سهم متراژ و نفر در هزینه ترکیبی باید 100 درصد باشد.');
            }
            $areaShare = $totalArea > 0 ? $amount * ($areaPercent / 100) * $unitArea / $totalArea : 0.0;
            $personShare = $totalPersons > 0 ? $amount * ($personPercent / 100) * $unitPersons / $totalPersons : 0.0;
            return $areaShare + $personShare;
        default:
            throw new InvalidArgumentException('معیار تخصیص هزینه نامعتبر است.');
    }
}

function calculate_unit_charge(PDO $pdo, array $unit, string $period, ?array $settings = null): array {
    $settings = $settings ?: get_charge_settings($pdo);
    $method = (int)$settings['calculation_method'];
    if ($method < 1 || $method > 10) {
        throw new RuntimeException('روش محاسبه شارژ نامعتبر است.');
    }

    $area = (float)$unit['area'];
    $persons = charge_resident_count($pdo, (int)$unit['id'], $period);
    $U = (float)$settings['fixed_unit_amount'];
    $rm = (float)$settings['area_rate'];
    $rn = (float)$settings['person_rate'];
    $parking = (float)$unit['parking_count'] * (float)$settings['parking_rate'];
    $storage = (float)$unit['storage_count'] * (float)$settings['storage_rate'];

    if ($method <= 9) {
        $result = charge_calculate_standard(
            $method, $area, $persons, $U, $rm, $rn,
            (float)$settings['area_percent'], (float)$settings['person_percent'],
            $parking, $storage
        );
        $result['method'] = $method;
        $result['details'] = [
            'method' => $method,
            'area' => $area,
            'persons' => $persons,
            'U' => $U,
            'rm' => $rm,
            'rn' => $rn,
            'area_part' => $result['area_part'],
            'person_part' => $result['person_part'],
            'parking' => $result['parking'],
            'storage' => $result['storage'],
            'base' => $result['base'],
            'total' => $result['total'],
        ];
        return $result;
    }

    $st = $pdo->prepare(
        'SELECT * FROM costs
         WHERE building_id=? AND cost_type IN ('fixed','variable')
           AND (period IS NULL OR period=?)
         ORDER BY id'
    );
    $st->execute([(int)$unit['building_id'], $period]);
    $costs = $st->fetchAll();

    $stA = $pdo->prepare('SELECT COALESCE(SUM(area),0) FROM units WHERE building_id=?');
    $stA->execute([(int)$unit['building_id']]);
    $totalArea = (float)$stA->fetchColumn();

    [$periodStart, $periodEnd] = charge_period_bounds($period);
    $stP = $pdo->prepare(
        'SELECT COUNT(*) FROM memberships m
         JOIN units u ON u.id=m.unit_id
         WHERE u.building_id=?
           AND (m.start_date IS NULL OR m.start_date<=?)
           AND (m.end_date IS NULL OR m.end_date>=?)'
    );
    $stP->execute([(int)$unit['building_id'], $periodEnd, $periodStart]);
    $totalPersons = (int)$stP->fetchColumn();

    $stU = $pdo->prepare('SELECT COUNT(*) FROM units WHERE building_id=?');
    $stU->execute([(int)$unit['building_id']]);
    $unitCount = (int)$stU->fetchColumn();

    $base = 0.0;
    $costDetails = [];
    foreach ($costs as $cost) {
        $share = charge_cost_share(
            (float)$cost['amount'],
            (string)$cost['allocation_method'],
            $area,
            $totalArea,
            $persons,
            $totalPersons,
            $unitCount,
            (float)$cost['allocation_area_percent'],
            (float)$cost['allocation_person_percent']
        );
        $base += $share;
        $costDetails[] = [
            'id' => (int)$cost['id'],
            'title' => $cost['title'],
            'amount' => charge_money($cost['amount']),
            'allocation' => $cost['allocation_method'],
            'share' => charge_money($share),
        ];
    }

    return [
        'amount' => charge_money($base),
        'method' => 10,
        'details' => [
            'method' => 10,
            'area' => $area,
            'persons' => $persons,
            'costs' => $costDetails,
            'total' => $base,
        ],
    ];
}
