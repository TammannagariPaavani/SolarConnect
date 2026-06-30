<?php
/** @var string $pageTitle */
$pageTitle = $pageTitle ?? SITE_NAME;
$pageDescription = $pageDescription ?? 'Professional rooftop solar installation solutions with fast enquiries and secure follow-up.';
$bodyClass = $bodyClass ?? '';
$showAdminLink = $showAdminLink ?? true;
$activeNav = $activeNav ?? 'home';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo e($pageDescription); ?>">
    <title><?php echo e($pageTitle); ?></title>
    <link rel="icon" href="<?php echo e(site_url('assets/images/logo.svg')); ?>" type="image/svg+xml">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(site_url('assets/css/style.css')); ?>">
</head>
<body class="<?php echo e($bodyClass); ?>">
<nav class="navbar navbar-expand-lg navbar-dark site-navbar sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="<?php echo e(site_url('index.php')); ?>">
            <img src="<?php echo e(site_url('assets/images/logo.svg')); ?>" alt="SolarConnect logo" class="brand-mark">
            <span><?php echo e(SITE_NAME); ?></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#siteNavbar" aria-controls="siteNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="siteNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center gap-lg-2">
                <li class="nav-item"><a class="nav-link <?php echo $activeNav === 'home' ? 'active' : ''; ?>" href="<?php echo e(site_url('index.php#home')); ?>">Home</a></li>
                <li class="nav-item"><a class="nav-link <?php echo $activeNav === 'packages' ? 'active' : ''; ?>" href="<?php echo e(site_url('index.php#packages')); ?>">Packages</a></li>
                <li class="nav-item"><a class="nav-link <?php echo $activeNav === 'enquiry' ? 'active' : ''; ?>" href="<?php echo e(site_url('index.php#enquiry')); ?>">Enquiry</a></li>
                <?php if ($showAdminLink): ?>
                    <li class="nav-item"><a class="nav-link" href="<?php echo e(site_url('admin/login.php')); ?>">Admin Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
