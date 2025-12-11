<?php
header('Content-Type: application/json');
session_start();
require_once 'dbConfig.php';

$response = ['status' => 'error', 'message' => 'Invalid action'];

// Detect multipart form data (image upload)
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $id = $_POST['id'] ?? null;
    $title = $_POST['title'] ?? '';
    $category = $_POST['category'] ?? '';
    $description = $_POST['description'] ?? '';
    $github_link = $_POST['github_link'] ?? '';
    $img_src = null;

    // Handle image upload if present
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = __DIR__ . '/../../images/';

        $ext       = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $img_src   = uniqid('proj_', true) . '.' . $ext;
        $target    = $uploadDir . $img_src;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            echo json_encode(['status' => 'error', 'message' => 'Image upload failed']);
            exit;
        }
    }

    try {
        switch ($action) {
            case 'create':
                $stmt = $pdo->prepare("INSERT INTO projects (title, category, description, github_link, img_src) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$title, $category, $description, $github_link, $img_src]);
                $response = ['status' => 'success', 'message' => 'Project created'];
                break;

            case 'update':
                if ($img_src) {
                    $stmt = $pdo->prepare("UPDATE projects SET title=?, category=?, description=?, github_link=?, img_src=? WHERE id=?");
                    $stmt->execute([$title, $category, $description, $github_link, $img_src, $id]);
                } else {
                    $stmt = $pdo->prepare("UPDATE projects SET title=?, category=?, description=?, github_link=? WHERE id=?");
                    $stmt->execute([$title, $category, $description, $github_link, $id]);
                }
                $response = ['status' => 'success', 'message' => 'Project updated'];
                break;
        }
    } catch (PDOException $e) {
        $response = ['status' => 'error', 'message' => $e->getMessage()];
    }

} else {
    // JSON body requests (read, delete)
    $input = json_decode(file_get_contents("php://input"), true);
    if (!is_array($input)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid JSON input']);
        exit;
    }

    $action = $input['action'] ?? null;

    try {
        switch ($action) {
            case 'read':
                $stmt = $pdo->query("SELECT * FROM projects ORDER BY date_added DESC");
                $data = $stmt->fetchAll();
                $response = $data ?: [];
                break;

            case 'update':
                $stmt = $pdo->prepare("UPDATE projects SET title=?, category=?, description=?, github_link=? WHERE id=?");
                $stmt->execute([
                    $input['title'] ?? '',
                    $input['category'] ?? '',
                    $input['description'] ?? '',
                    $input['github_link'] ?? '',
                    $input['id'] ?? null
                ]);
                $response = ['status' => 'success', 'message' => 'Project updated'];
                break;

            case 'delete':
                $stmt = $pdo->prepare("DELETE FROM projects WHERE id=?");
                $stmt->execute([$input['id']]);
                $response = ['status' => 'success', 'message' => 'Project deleted'];
                break;
        }
    } catch (PDOException $e) {
        $response = ['status' => 'error', 'message' => $e->getMessage()];
    }
}

echo json_encode($response);
?>