<?php
require_once __DIR__ . '/../includes/init.php';
require_admin_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(site_url('admin/dashboard.php'));
}

if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
    set_flash('error', 'Security check failed. Delete action was blocked.');
    redirect(site_url('admin/dashboard.php'));
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    set_flash('error', 'Invalid enquiry selected.');
    redirect(site_url('admin/dashboard.php'));
}

try {
    $db = db_connect();
    $stmt = $db->prepare('DELETE FROM enquiries WHERE id = ?');

    if (!$stmt) {
        throw new RuntimeException('Unable to prepare delete query.');
    }

    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();

    set_flash('success', 'Enquiry deleted successfully.');
} catch (Throwable $exception) {
    set_flash('error', 'Could not delete the enquiry at the moment.');
}

redirect(site_url('admin/dashboard.php'));

