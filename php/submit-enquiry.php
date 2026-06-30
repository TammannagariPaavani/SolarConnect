<?php
require_once __DIR__ . '/../includes/init.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(site_url('index.php#enquiry'));
}

if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
    set_flash('error', 'Security check failed. Please try again.');
    redirect(site_url('index.php#enquiry'));
}

$input = [
    'full_name' => trim((string) ($_POST['full_name'] ?? '')),
    'phone_number' => trim((string) ($_POST['phone_number'] ?? '')),
    'monthly_bill' => trim((string) ($_POST['monthly_bill'] ?? '')),
    'package_selected' => trim((string) ($_POST['package_selected'] ?? '')),
];

$errors = validate_enquiry_input($input);

if ($errors) {
    set_form_state($errors, $input);
    set_flash('error', 'Please correct the highlighted fields and submit again.');
    redirect(site_url('index.php#enquiry'));
}

try {
    $db = db_connect();
    $stmt = $db->prepare('INSERT INTO enquiries (full_name, phone_number, monthly_bill, package_selected) VALUES (?, ?, ?, ?)');

    if (!$stmt) {
        throw new RuntimeException('Unable to prepare enquiry statement.');
    }

    $monthlyBill = (float) $input['monthly_bill'];
    $stmt->bind_param('ssds', $input['full_name'], $input['phone_number'], $monthlyBill, $input['package_selected']);
    $stmt->execute();
    $stmt->close();

    set_flash('success', 'Thank you! Your enquiry has been submitted successfully.');
    redirect(site_url('index.php#enquiry'));
} catch (Throwable $exception) {
    set_flash('error', 'Could not save your enquiry right now. Please try again later.');
    redirect(site_url('index.php#enquiry'));
}
