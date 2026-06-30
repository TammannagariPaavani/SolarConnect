<?php
require_once __DIR__ . '/../includes/init.php';

if (is_admin_logged_in()) {
    redirect(site_url('admin/dashboard.php'));
}

$pageTitle = SITE_NAME . ' - Admin Login';
$bodyClass = 'auth-page';
$pageDescription = 'Secure admin login for SolarConnect enquiries.';
$activeNav = '';
$showAdminLink = false;

$successMessage = get_flash('success');
$errorMessage = get_flash('error');
$loginError = get_flash('login_error');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $loginError = 'Security check failed. Please try again.';
    } else {
        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        if ($username === ADMIN_USERNAME && password_verify($password, ADMIN_PASSWORD_HASH)) {
            session_regenerate_id(true);
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = ADMIN_USERNAME;
            set_flash('success', 'Welcome back, admin.');
            redirect(site_url('admin/dashboard.php'));
        }

        $loginError = 'Invalid username or password.';
    }
}

include __DIR__ . '/../includes/header.php';
?>

<main class="auth-shell">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">
                <div class="auth-card row g-0 overflow-hidden">
                    <div class="col-lg-5 auth-panel p-4 p-lg-5 d-flex flex-column justify-content-between">
                        <div>
                            <img src="<?php echo e(site_url('assets/images/logo.svg')); ?>" alt="SolarConnect" class="brand-mark mb-4">
                            <h1 class="h2 fw-bold mb-3">Admin Panel</h1>
                            <p class="mb-0">Review enquiries, search leads, and manage customer requests securely using a session-protected dashboard.</p>
                        </div>
                        <p class="small mt-4 mb-0">Default login: <strong>admin</strong> / <strong>Admin@12345</strong></p>
                    </div>
                    <div class="col-lg-7 bg-white p-4 p-lg-5">
                        <h2 class="h3 fw-bold mb-2">Sign In</h2>
                        <p class="text-secondary mb-4">Access SolarConnect enquiries securely.</p>

                        <?php if ($successMessage): ?>
                            <div class="alert alert-success"><?php echo e($successMessage); ?></div>
                        <?php endif; ?>

                        <?php if ($errorMessage): ?>
                            <div class="alert alert-danger"><?php echo e($errorMessage); ?></div>
                        <?php endif; ?>

                        <?php if ($loginError): ?>
                            <div class="alert alert-danger"><?php echo e($loginError); ?></div>
                        <?php endif; ?>

                        <form method="post" novalidate>
                            <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold" for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-solar btn-lg px-4">Login</button>
                            <a href="<?php echo e(site_url('index.php')); ?>" class="btn btn-link text-decoration-none">Back to website</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
