<?php

$pdo = new PDO(
    'mysql:host=127.0.0.1;dbname=jocko',
    'root',
    '123'
);

$start = hrtime(true);
$pdo->exec('DROP TABLE IF EXISTS bench_php');
echo "DROP: " . ((hrtime(true) - $start) / 1e6) . " ms\n";

$start = hrtime(true);
$pdo->exec('CREATE TABLE bench_php (id BIGINT PRIMARY KEY AUTO_INCREMENT)');
echo "CREATE: " . ((hrtime(true) - $start) / 1e6) . " ms\n";

