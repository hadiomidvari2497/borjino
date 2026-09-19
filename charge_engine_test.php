<?php
require_once __DIR__ . '/includes/charge_engine.php';

function assert_amount(string $name, float $expected, array $result): void {
    $actual = (float)$result['amount'];
    if (abs($actual - $expected) > 0.01) {
        throw new RuntimeException(sprintf('%s failed: expected %.2f, got %.2f', $name, $expected, $actual));
    }
    echo "PASS: {$name}\n";
}

$common = [100.0, 4, 1000.0, 10.0, 50.0, 80.0, 20.0, 0.0, 0.0];

assert_amount('method 1', 1000.00, charge_calculate_standard(1, ...$common));
assert_amount('method 2', 1000.00, charge_calculate_standard(2, ...$common));
assert_amount('method 3', 200.00, charge_calculate_standard(3, ...$common));
assert_amount('method 4 (80/20)', 840.00, charge_calculate_standard(4, ...$common));
assert_amount('method 5', 2000.00, charge_calculate_standard(5, ...$common));
assert_amount('method 6', 1200.00, charge_calculate_standard(6, ...$common));
assert_amount('method 7 (80/20)', 1840.00, charge_calculate_standard(7, ...$common));
assert_amount('method 8', 1200.00, charge_calculate_standard(8, ...$common));
assert_amount('method 9', 2200.00, charge_calculate_standard(9, ...$common));
assert_amount('method 1 parking/storage', 1250.00, charge_calculate_standard(1, 100, 4, 1000, 10, 50, 80, 20, 150, 100));

if (abs(charge_cost_share(1000, 'equal', 100, 400, 2, 8, 4) - 250) > 0.01) {
    throw new RuntimeException('equal allocation failed');
}
if (abs(charge_cost_share(1000, 'area', 100, 400, 2, 8, 4) - 250) > 0.01) {
    throw new RuntimeException('area allocation failed');
}
if (abs(charge_cost_share(1000, 'person', 100, 400, 2, 8, 4) - 250) > 0.01) {
    throw new RuntimeException('person allocation failed');
}
if (abs(charge_cost_share(1000, 'combination', 100, 400, 2, 8, 4, 80, 20) - 250) > 0.01) {
    throw new RuntimeException('combination allocation failed');
}
echo "PASS: cost allocations\n";
echo "All charge formula tests passed.\n";
