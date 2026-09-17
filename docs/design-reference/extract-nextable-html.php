<?php
/**
 * Extract the HTML reference files from the original nextable.zip.
 *
 * Usage from the project root:
 *   php docs/design-reference/extract-nextable-html.php /path/to/nextable.zip
 *
 * The script extracts only .html/.htm files and preserves the paths from the
 * source archive under docs/design-reference/nextable-html/.
 */

declare(strict_types=1);

if ($argc < 2) {
    fwrite(STDERR, "Usage: php docs/design-reference/extract-nextable-html.php /path/to/nextable.zip\n");
    exit(1);
}

$zipPath = $argv[1];
if (!is_file($zipPath)) {
    fwrite(STDERR, "ZIP not found: {$zipPath}\n");
    exit(1);
}

$zip = new ZipArchive();
if ($zip->open($zipPath) !== true) {
    fwrite(STDERR, "Unable to open ZIP: {$zipPath}\n");
    exit(1);
}

$root = dirname(__DIR__) . '/design-reference/nextable-html';
$count = 0;

for ($i = 0; $i < $zip->numFiles; $i++) {
    $name = $zip->getNameIndex($i);
    if ($name === false || !preg_match('/\\.(html?|HTML?)$/', $name)) {
        continue;
    }

    // Normalize archive separators and reject path traversal.
    $name = str_replace('\\', '/', $name);
    if (str_contains($name, '../') || str_starts_with($name, '/')) {
        continue;
    }

    $target = $root . '/' . $name;
    $dir = dirname($target);
    if (!is_dir($dir) && !mkdir($dir, 0777, true) && !is_dir($dir)) {
        throw new RuntimeException("Unable to create directory: {$dir}");
    }

    $contents = $zip->getFromIndex($i);
    if ($contents === false) {
        throw new RuntimeException("Unable to read archive entry: {$name}");
    }

    file_put_contents($target, $contents);
    $count++;
}

$zip->close();

echo "Extracted {$count} HTML files to {$root}\n";
