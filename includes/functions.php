<?php
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function site_url(string $path = ''): string
{
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));

    if (preg_match('#/(admin|php)$#', $scriptDir)) {
        $scriptDir = preg_replace('#/(admin|php)$#', '', $scriptDir);
    }

    $scriptDir = rtrim($scriptDir, '/');
    $path = ltrim($path, '/');

    if ($scriptDir === '' || $scriptDir === '.') {
        return '/' . $path;
    }

    return $scriptDir . '/' . $path;
}

function is_admin_logged_in(): bool
{
    return !empty($_SESSION['admin_logged_in']);
}

function require_admin_login(): void
{
    if (!is_admin_logged_in()) {
        redirect(site_url('admin/login.php'));
    }
}

function set_flash(string $type, string $message): void
{
    if (!isset($_SESSION['flash']) || !is_array($_SESSION['flash'])) {
        $_SESSION['flash'] = [];
    }

    $_SESSION['flash'][$type] = $message;
}

function get_flash(string $type): ?string
{
    if (!isset($_SESSION['flash'][$type])) {
        return null;
    }

    $message = $_SESSION['flash'][$type];
    unset($_SESSION['flash'][$type]);

    return $message;
}

function set_form_state(array $errors, array $old): void
{
    $_SESSION['form_state'] = [
        'errors' => $errors,
        'old' => $old,
    ];
}

function get_form_state(): array
{
    $state = $_SESSION['form_state'] ?? ['errors' => [], 'old' => []];
    unset($_SESSION['form_state']);

    return $state;
}

function old_value(array $old, string $key, string $default = ''): string
{
    return isset($old[$key]) ? (string) $old[$key] : $default;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verify_csrf_token(?string $token): bool
{
    return isset($_SESSION['csrf_token']) && is_string($token) && hash_equals($_SESSION['csrf_token'], $token);
}

function get_packages(): array
{
    return [
        '3kW' => [
            'name' => '3kW Solar Package',
            'description' => 'Ideal for small homes and low-to-moderate electricity usage.',
            'features' => [
                'High-efficiency solar panels',
                'Net-metering ready setup',
                'Basic installation support',
            ],
            'price' => 'Rs. 1,75,000',
        ],
        '5kW' => [
            'name' => '5kW Solar Package',
            'description' => 'A balanced solution for families that want stronger savings.',
            'features' => [
                'Premium mono PERC panels',
                'Inverter and mounting structure',
                'Monitoring and maintenance guidance',
            ],
            'price' => 'Rs. 2,85,000',
        ],
        '10kW' => [
            'name' => '10kW Solar Package',
            'description' => 'Best for larger homes, villas, and high electricity bills.',
            'features' => [
                'Commercial-grade components',
                'Robust safety and wiring setup',
                'Higher generation capacity',
            ],
            'price' => 'Rs. 5,25,000',
        ],
    ];
}

function validate_enquiry_input(array $input): array
{
    $errors = [];

    $fullName = trim((string) ($input['full_name'] ?? ''));
    $phone = trim((string) ($input['phone_number'] ?? ''));
    $monthlyBill = trim((string) ($input['monthly_bill'] ?? ''));
    $package = trim((string) ($input['package_selected'] ?? ''));

    if ($fullName === '' || strlen($fullName) < 3) {
        $errors['full_name'] = 'Please enter your full name.';
    }

    if (!preg_match('/^\d{10}$/', $phone)) {
        $errors['phone_number'] = 'Phone number must be exactly 10 digits.';
    }

    if ($monthlyBill === '' || !is_numeric($monthlyBill) || (float) $monthlyBill <= 0) {
        $errors['monthly_bill'] = 'Please enter a valid monthly electricity bill amount.';
    }

    $allowedPackages = array_keys(get_packages());
    if (!in_array($package, $allowedPackages, true)) {
        $errors['package_selected'] = 'Please choose a valid package.';
    }

    return $errors;
}
