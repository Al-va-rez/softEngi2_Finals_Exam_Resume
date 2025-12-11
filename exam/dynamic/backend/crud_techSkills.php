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
            $stmt = $pdo->prepare("INSERT INTO techskills (name, level) VALUES (?, ?)");
            $stmt->execute([$input['name'], $input['level']]);
            $response = ['status' => 'success', 'message' => 'Skill added'];
            break;

        case 'read':
            $stmt = $pdo->query("SELECT * FROM techskills ORDER BY id DESC");
            $data = $stmt->fetchAll();
            $response = $data ?: [];
            break;

        case 'update':
            $stmt = $pdo->prepare("UPDATE techskills SET name=?, level=? WHERE id=?");
            $stmt->execute([$input['name'], $input['level'], $input['id']]);
            $response = ['status' => 'success', 'message' => 'Skill updated'];
            break;

        case 'delete':
            $stmt = $pdo->prepare("DELETE FROM techskills WHERE id=?");
            $stmt->execute([$input['id']]);
            $response = ['status' => 'success', 'message' => 'Skill deleted'];
            break;
    }
} catch (PDOException $e) {
    $response = ['status' => 'error', 'message' => $e->getMessage()];
}

echo json_encode($response);