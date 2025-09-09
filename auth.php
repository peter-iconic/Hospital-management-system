<?php
require_once __DIR__ . '/db.php'; // Make sure $pdo is defined here
session_start();

// User login function
function user_login($username, $password)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT u.user_id, u.username, u.password, u.full_name, r.role_name
                           FROM users u
                           JOIN roles r ON u.role_id=r.role_id
                           WHERE u.username = ? LIMIT 1");
    $stmt->execute([$username]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row && password_verify($password, $row['password'])) {
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => (int) $row['user_id'],
            'username' => $row['username'],
            'full_name' => $row['full_name'],
            'role' => $row['role_name']
        ];
        return true;
    }
    return false;
}

// Logout function
function user_logout()
{
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }
    session_destroy();
}

// Get current logged-in user
function current_user()
{
    $u = $_SESSION['user'] ?? [];
    return is_array($u) ? $u : [];
}

// Role-based access
function require_role($allowed = [])
{
    $u = current_user();
    if (!$u || !in_array($u['role'], (array) $allowed)) {
        http_response_code(403);
        echo '<div class="alert alert-danger">Access denied. You do not have permission to view this page.</div>';
        exit;
    }
}
