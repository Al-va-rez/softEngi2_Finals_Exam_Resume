<?php
header('Content-Type: application/json');
session_start();
require_once 'dbConfig.php';

$response = ['status' => 'error', 'message' => 'Invalid action'];

// If multipart form data (for certificates with image upload)
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $id = $_POST['id'] ?? null;
    $title = $_POST['title'] ?? '';
    $year_obtained = $_POST['year_obtained'] ?? '';
    $img_src = null;

    // Handle image upload if present
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = __DIR__ . '/../../images/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $img_src = uniqid('cert_', true) . '.' . $ext;
        $target = $uploadDir . $img_src;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            echo json_encode(['status' => 'error', 'message' => 'Image upload failed']);
            exit;
        }
    }

    try {
        switch ($action) {
            case 'createCert':
                $stmt = $pdo->prepare("INSERT INTO certificates (title, year_obtained, img_src) VALUES (?, ?, ?)");
                $stmt->execute([$title, $year_obtained, $img_src]);
                $response = ['status' => 'success', 'message' => 'Certificate created'];
                break;

            case 'updateCert':
                if ($img_src) {
                    $stmt = $pdo->prepare("UPDATE certificates SET title=?, year_obtained=?, img_src=? WHERE id=?");
                    $stmt->execute([$title, $year_obtained, $img_src, $id]);
                } else {
                    $stmt = $pdo->prepare("UPDATE certificates SET title=?, year_obtained=? WHERE id=?");
                    $stmt->execute([$title, $year_obtained, $id]);
                }
                $response = ['status' => 'success', 'message' => 'Certificate updated'];
                break;
        }
    } catch (PDOException $e) {
        $response = ['status' => 'error', 'message' => $e->getMessage()];
    }

} else {
    // JSON body requests (education CRUD + read + deleteCert)
    $input = json_decode(file_get_contents("php://input"), true);
    if (!is_array($input)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid JSON input']);
        exit;
    }

    $action = $input['action'] ?? null;

    try {
        switch ($action) {
            case 'read':
                $eduStmt = $pdo->query("SELECT * FROM education ORDER BY date_added DESC");
                $education = $eduStmt->fetchAll();

                $certStmt = $pdo->query("SELECT * FROM certificates ORDER BY date_added DESC");
                $certificates = $certStmt->fetchAll();

                $response = ['education' => $education, 'certificates' => $certificates];
                break;

            case 'createEdu':
                $stmt = $pdo->prepare("INSERT INTO education (school_name, year_start, year_end, description) VALUES (?, ?, ?, ?)");
                $stmt->execute([$input['school_name'], $input['year_start'], $input['year_end'], $input['description']]);
                $response = ['status' => 'success', 'message' => 'Education record created'];
                break;

            case 'updateEdu':
                $stmt = $pdo->prepare("UPDATE education SET school_name=?, year_start=?, year_end=?, description=? WHERE id=?");
                $stmt->execute([$input['school_name'], $input['year_start'], $input['year_end'], $input['description'], $input['id']]);
                $response = ['status' => 'success', 'message' => 'Education record updated'];
                break;
            
            case 'updateCert':
                $stmt = $pdo->prepare("UPDATE certificates SET title=?, year_obtained=?, img_src=? WHERE id=?");
                $stmt->execute([
                    $input['title'],
                    $input['year_obtained'],
                    $input['img_src'],   // keep existing filename if no new upload
                    $input['id']
                ]);
                $response = ['status' => 'success', 'message' => 'Certificate updated'];
                break;

            case 'deleteEdu':
                $stmt = $pdo->prepare("DELETE FROM education WHERE id=?");
                $stmt->execute([$input['id']]);
                $response = ['status' => 'success', 'message' => 'Education record deleted'];
                break;

            case 'deleteCert':
                $stmt = $pdo->prepare("DELETE FROM certificates WHERE id=?");
                $stmt->execute([$input['id']]);
                $response = ['status' => 'success', 'message' => 'Certificate deleted'];
                break;
        }
    } catch (PDOException $e) {
        $response = ['status' => 'error', 'message' => $e->getMessage()];
    }
}

echo json_encode($response);
?>