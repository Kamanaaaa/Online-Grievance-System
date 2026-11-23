<?php
declare(strict_types=1);

define('APP_NAME', 'Public Grievance Portal');
define('BASE_URL', '/online-grievance-system'); // Change when the htdocs folder has another name.
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'online_grievance_system');
define('DB_USER', 'root');
define('DB_PASS', '');
define('UPLOAD_DIR', dirname(__DIR__) . '/uploads/grievances');
define('MAX_UPLOAD_BYTES', 5 * 1024 * 1024);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params(['httponly' => true, 'secure' => !empty($_SERVER['HTTPS']), 'samesite' => 'Lax']);
    session_start();
}

function db(): PDO
{
    static $pdo;
    if (!$pdo) {
        $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
    return $pdo;
}
