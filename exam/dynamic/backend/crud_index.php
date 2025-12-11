<?php
header('Content-Type: application/json');
session_start();
require_once 'dbConfig.php';

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? null;

$response = ['status' => 'error', 'message' => 'Invalid action'];



try {
    switch ($action) {
        case 'register':
            $username = trim($input['username'] ?? '');
            $firstname = trim($input['firstname'] ?? '');
            $lastname = trim($input['lastname'] ?? '');
            $password = trim($input['password'] ?? '');
            $confirm_password = trim($input['confirm_password'] ?? '');

            if ($username === '' || $password === '' || $firstname === '' || $lastname === '') {
                $response['message'] = 'All fields are required';
                break;
            }

            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                $response['message'] = 'Username already exists';
                break;
            }

            if ($password !== $confirm_password) {
                $response['message'] = 'Passwords not the same';
                break;
            }

            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, firstname, lastname, password) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$username, $firstname, $lastname, $hash])) {
                $response = ['status' => 'success', 'message' => 'Registration successful'];
            } else {
                $response['message'] = 'Registration failed';
            }
            break;

        case 'login':
            $username = trim($input['username'] ?? '');
            $password = trim($input['password'] ?? '');

            if ($username === '' || $password === '') {
                $response['message'] = 'All fields are required';
                break;
            }

            $stmt = $pdo->prepare("SELECT id, password, firstname, lastname FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                $response['message'] = 'User not yet registered';
                break;
            }

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $username;
                $_SESSION['firstname'] = $user['firstname'];
                $_SESSION['lastname'] = $user['lastname'];
                $response = ['status' => 'success', 'message' => 'Login successful'];
            } else {
                $response['message'] = 'Invalid username or password';
            }
            break;

        case 'logout':
            session_unset();
            session_destroy();
            $response = ['status' => 'success', 'message' => 'Logged out'];
            break;

        default:
            $response['message'] = 'Unknown action';
    }
} catch (Exception $e) {
  $response = ['status' => 'error', 'message' => $e->getMessage()];
}
echo json_encode($response);

?>