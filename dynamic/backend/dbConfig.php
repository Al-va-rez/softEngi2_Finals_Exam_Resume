<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/api_error.log');

$host = 'localhost';
$db = 'year4_finals_exam_resume';
$dsn = "mysql:host={$host};dbname={$db};charset=utf8mb4";
$user = 'root';
$pass = '';

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Let the calling script handle the error
    http_response_code(500);
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed']));
}
?>