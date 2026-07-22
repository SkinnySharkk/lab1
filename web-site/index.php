<?php
// =====================================================
// 1. Проверка загрузки расширения Redis
// =====================================================
if (!extension_loaded('redis')) {
    die('<h1>Ошибка: расширение Redis не загружено в PHP</h1>');
}

// =====================================================
// 2. Настройка сессий через Redis (stateless)
// =====================================================
$redisHost = getenv('REDIS_HOST') ?: 'redis';
ini_set('session.save_handler', 'redis');
ini_set('session.save_path', "tcp://{$redisHost}:6379");
session_start();

// Счётчик посещений
if (!isset($_SESSION['counter'])) {
    $_SESSION['counter'] = 1;
} else {
    $_SESSION['counter']++;
}
$counter = $_SESSION['counter'];

// =====================================================
// 3. Подключение к MariaDB через PDO
// =====================================================
$dbHost = getenv('DB_HOST') ?: 'db';
$dbName = getenv('MARIADB_DATABASE') ?: 'testdb';
$dbUser = getenv('MARIADB_USER') ?: 'user';
$dbPass = getenv('MARIADB_PASSWORD') ?: 'password';

try {
    $pdo = new PDO("mysql:host={$dbHost};dbname={$dbName}", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbStatus = '✅ Связь с MariaDB: Успешна!';
} catch (PDOException $e) {
    $dbStatus = '❌ Ошибка БД: ' . $e->getMessage();
}

// =====================================================
// 4. Имя сервера (PHP-контейнера)
// =====================================================
$serverName = gethostname();

// =====================================================
// 5. Вывод HTML
// =====================================================
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>v3.0 – Stateless + Redis</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f4f4f9; }
        .container { max-width: 800px; margin: auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 2px solid #4CAF50; padding-bottom: 10px; }
        .info { background: #e8f5e9; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .db-status { font-weight: bold; }
        .counter { font-size: 1.2em; color: #2c3e50; }
        .footer { margin-top: 30px; font-size: 0.9em; color: #777; border-top: 1px solid #ddd; padding-top: 15px; }
    </style>
</head>
<body>
<div class="container">
    <h1>🚀 Привет! Запрос обработан</h1>
    <div class="info">
        <p><strong>Сервер (PHP‑контейнер):</strong> <code><?= htmlspecialchars($serverName) ?></code></p>
        <p><strong>Счётчик посещений (сессия Redis):</strong> <span class="counter"><?= $counter ?></span></p>
        <p><strong>Статус БД:</strong> <span class="db-status"><?= $dbStatus ?></span></p>
        <p><small>Балансировка round‑robin между web1, web2, web3 (HAProxy)</small></p>
    </div>
    <div class="footer">
        PHP версия: <?= phpversion() ?> | Redis сессии активны
    </div>
</div>
</body>
</html>