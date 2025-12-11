<?php
header('Content-Type: application/json');
session_start();
require_once 'dbConfig.php';

$input = json_decode(file_get_contents("php://input"), true);
if (!is_array($input)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid JSON input']);
    exit;
}

$action = $input['action'] ?? null;
$response = ['status' => 'error', 'message' => 'Invalid action'];

try {
    switch ($action) {
        case 'create':
            $stmt = $pdo->prepare("INSERT INTO aboutme (interests, inspiration, life_motto, bucket_list, strengths, weaknesses, talents, greatest_fear) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $input['interests'],
                $input['inspiration'],
                $input['life_motto'],
                $input['bucket_list'],
                $input['strengths'],
                $input['weaknesses'],
                $input['talents'],
                $input['greatest_fear']
            ]);
            $response = ['status' => 'success', 'message' => 'About Me created'];
            break;

        case 'read':
            $stmt = $pdo->query("SELECT * FROM aboutme ORDER BY date_added DESC LIMIT 1");
            $data = $stmt->fetch();
            $response = $data ?: ['status' => 'error', 'message' => 'No record found'];
            break;

        case 'update':
            $stmt = $pdo->prepare("UPDATE aboutme SET interests=?, inspiration=?, life_motto=?, bucket_list=?, strengths=?, weaknesses=?, talents=?, greatest_fear=? WHERE id=?");
            $stmt->execute([
                $input['interests'],
                $input['inspiration'],
                $input['life_motto'],
                $input['bucket_list'],
                $input['strengths'],
                $input['weaknesses'],
                $input['talents'],
                $input['greatest_fear'],
                $input['id']
            ]);
            $response = ['status' => 'success', 'message' => 'About Me updated'];
            break;

        case 'delete':
            $stmt = $pdo->prepare("DELETE FROM aboutme WHERE id=?");
            $stmt->execute([$input['id']]);
            $response = ['status' => 'success', 'message' => 'About Me deleted'];
            break;
    }
} catch (PDOException $e) {
    $response = ['status' => 'error', 'message' => $e->getMessage()];
}

echo json_encode($response);
?>