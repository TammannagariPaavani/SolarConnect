<?php
require_once __DIR__ . '/../includes/init.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf_token($_POST['csrf_token'] ?? null)) {
    set_flash('error', 'Invalid logout request.');
    redirect(site_url(is_admin_logged_in() ? 'admin/dashboard.php' : 'admin/login.php'));
}

$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}

session_destroy();

session_start();
set_flash('success', 'You have been logged out securely.');
redirect(site_url('admin/login.php'));
