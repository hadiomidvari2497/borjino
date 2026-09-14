<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap/autoload.php';

use App\Support\Csrf;
use App\Support\Session;

Session::start();
$token = Csrf::token();

if (strlen($token) !== 64) {
    fwrite(STDERR, "CSRF token length check failed.\n");
    exit(1);
}

if (!Csrf::validate($token)) {
    fwrite(STDERR, "Valid CSRF token was rejected.\n");
    exit(1);
}

if (Csrf::validate($token . 'x')) {
    fwrite(STDERR, "Invalid CSRF token was accepted.\n");
    exit(1);
}

Session::destroy();
echo "CSRF smoke test passed.\n";
