<?php
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    echo "CLI only.\n";
    exit(1);
}

require dirname(__DIR__) . '/bootstrap.php';

$pdo = db();
migrate($pdo);

echo sprintf("[%s] Migration completed successfully.\n", nowIso());
exit(0);
