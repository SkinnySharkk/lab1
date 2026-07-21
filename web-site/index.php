<?php
$db_host = getenv('DB_HOST') ?: 'db';      // имя сервиса базы данных из docker-compose
$db_name = getenv('MYSQL_DATABASE') ?: 'testdb';
$db_user = getenv('MYSQL_USER') ?: 'user';
$db_pass = getenv('MYSQL_PASSWORD') ?: 'password';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db_status = '✅ Связь с MariaDB: Успешна!';
} catch (PDOException $e) {
    $db_status = '❌ Ошибка БД: ' . $e->getMessage();
}

#$server_name = gethostname(); // имя контейнера (web1 или web2)
if (!empty($_SERVER['HTTP_X_SERVER_NAME'])) {
    $server_name = $_SERVER['HTTP_X_SERVER_NAME'] . ' (через php-fpm: ' . gethostname() . ')';
} else {
    $server_name = gethostname();
}
?>
<!DOCTYPE html>
<html>
<head><title>v2.0 – High Availability</title></head>
<body>
    <h1>Привет! Запрос обработан на сервере: <strong><?= $server_name ?></strong></h1>
    <p><?= $db_status ?></p>
    <p><small>Балансировка round-robin между web и web2 и web3</small></p>
</body>
</html>
