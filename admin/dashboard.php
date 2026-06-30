<?php
require_once __DIR__ . '/../includes/init.php';
require_admin_login();

$pageTitle = SITE_NAME . ' - Admin Dashboard';
$pageDescription = 'View, search, and manage SolarConnect enquiries.';
$bodyClass = 'admin-page';
$showAdminLink = false;
$activeNav = '';

$search = trim((string) ($_GET['search'] ?? ''));
$enquiries = [];
$totalCount = 0;
$todayCount = 0;
$weekCount = 0;
$latestSubmittedAt = null;
$packageCounts = array_fill_keys(array_keys(get_packages()), 0);
$topPackageKey = null;
$topPackageCount = 0;
$successMessage = get_flash('success');
$errorMessage = get_flash('error');

try {
    $db = db_connect();

    if ($search !== '') {
        $like = '%' . $search . '%';
        $stmt = $db->prepare('SELECT id, full_name, phone_number, monthly_bill, package_selected, created_at FROM enquiries WHERE full_name LIKE ? OR phone_number LIKE ? ORDER BY created_at DESC');
        if (!$stmt) {
            throw new RuntimeException('Unable to prepare search query.');
        }

        $stmt->bind_param('ss', $like, $like);
    } else {
        $stmt = $db->prepare('SELECT id, full_name, phone_number, monthly_bill, package_selected, created_at FROM enquiries ORDER BY created_at DESC');
        if (!$stmt) {
            throw new RuntimeException('Unable to prepare list query.');
        }
    }

    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $enquiries[] = $row;
    }
    $stmt->close();

    $countResult = $db->query('SELECT COUNT(*) AS total_count FROM enquiries');
    if ($countResult instanceof mysqli_result) {
        $countRow = $countResult->fetch_assoc();
        $totalCount = (int) ($countRow['total_count'] ?? 0);
        $countResult->free();
    }

    $todayResult = $db->query('SELECT COUNT(*) AS total_count FROM enquiries WHERE created_at >= CURDATE()');
    if ($todayResult instanceof mysqli_result) {
        $todayRow = $todayResult->fetch_assoc();
        $todayCount = (int) ($todayRow['total_count'] ?? 0);
        $todayResult->free();
    }

    $weekResult = $db->query('SELECT COUNT(*) AS total_count FROM enquiries WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)');
    if ($weekResult instanceof mysqli_result) {
        $weekRow = $weekResult->fetch_assoc();
        $weekCount = (int) ($weekRow['total_count'] ?? 0);
        $weekResult->free();
    }

    $packageResult = $db->query('SELECT package_selected, COUNT(*) AS total_count FROM enquiries GROUP BY package_selected');
    if ($packageResult instanceof mysqli_result) {
        while ($row = $packageResult->fetch_assoc()) {
            $key = (string) ($row['package_selected'] ?? '');
            if (array_key_exists($key, $packageCounts)) {
                $packageCounts[$key] = (int) ($row['total_count'] ?? 0);
            }
        }
        $packageResult->free();
    }

    foreach ($packageCounts as $key => $count) {
        if ($count > $topPackageCount) {
            $topPackageCount = $count;
            $topPackageKey = $key;
        }
    }

    $latestResult = $db->query('SELECT created_at FROM enquiries ORDER BY created_at DESC LIMIT 1');
    if ($latestResult instanceof mysqli_result) {
        $latestRow = $latestResult->fetch_assoc();
        $latestSubmittedAt = $latestRow['created_at'] ?? null;
        $latestResult->free();
    }
} catch (Throwable $exception) {
    $errorMessage = 'Unable to load enquiries right now.';
}

$filteredCount = count($enquiries);
$topPackageLabel = $topPackageKey ? ($topPackageKey . ' Package') : 'No leads yet';
$topPackageInfo = $topPackageKey ? ($packageCounts[$topPackageKey] . ' enquiries') : 'Start receiving enquiries';

include __DIR__ . '/../includes/header.php';
?>

<main class="admin-shell section-shell pt-4 pt-lg-5">
    <div class="container">
        <div class="admin-hero p-4 p-lg-5 mb-4">
            <div class="row g-4 align-items-center">
                <div class="col-lg-7">
                    <span class="admin-kicker">Admin Panel</span>
                    <h1 class="admin-title mb-3">Enquiries Dashboard</h1>
                    <p class="admin-copy mb-4">Search by customer name or phone number, review fresh leads, and keep incoming enquiries organized in one place.</p>
                    <div class="admin-chip-row">
                        <span class="admin-chip">Live inbox: <?php echo e((string) $totalCount); ?></span>
                        <span class="admin-chip">Today: <?php echo e((string) $todayCount); ?></span>
                        <span class="admin-chip">Last 7 days: <?php echo e((string) $weekCount); ?></span>
                        <?php if ($latestSubmittedAt): ?>
                            <span class="admin-chip">Last update: <?php echo e(date('d M, h:i A', strtotime((string) $latestSubmittedAt))); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="admin-search-card p-4 p-lg-4">
                        <form method="get">
                            <label for="search" class="form-label fw-semibold text-white mb-2">Search enquiries</label>
                            <div class="input-group input-group-lg">
                                <input type="text" class="form-control admin-search-input" id="search" name="search" value="<?php echo e($search); ?>" placeholder="Name or phone number">
                                <button class="btn btn-solar" type="submit">Search</button>
                            </div>
                        </form>
                        <div class="admin-search-meta mt-3">
                            <span>Showing <?php echo e((string) $filteredCount); ?> result<?php echo $filteredCount === 1 ? '' : 's'; ?></span>
                            <span><?php echo e($topPackageLabel); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="admin-action-stack mt-4">
                <a href="<?php echo e(site_url('index.php')); ?>" class="btn btn-outline-light btn-lg admin-action-btn">View Site</a>
                <form action="<?php echo e(site_url('admin/logout.php')); ?>" method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
                    <button type="submit" class="btn btn-outline-danger btn-lg admin-action-btn">Logout</button>
                </form>
            </div>
        </div>

        <?php if ($successMessage): ?>
            <div class="alert alert-success"><?php echo e($successMessage); ?></div>
        <?php endif; ?>

        <?php if ($errorMessage): ?>
            <div class="alert alert-danger"><?php echo e($errorMessage); ?></div>
        <?php endif; ?>

        <div class="row g-4 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="metric-card p-4">
                    <span class="metric-label">Total enquiries</span>
                    <strong class="metric-value"><?php echo e((string) $totalCount); ?></strong>
                    <span class="metric-note">All-time leads in the system</span>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="metric-card p-4">
                    <span class="metric-label">Today's enquiries</span>
                    <strong class="metric-value"><?php echo e((string) $todayCount); ?></strong>
                    <span class="metric-note">Captured since midnight</span>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="metric-card p-4">
                    <span class="metric-label">This week</span>
                    <strong class="metric-value"><?php echo e((string) $weekCount); ?></strong>
                    <span class="metric-note">New enquiries in 7 days</span>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="metric-card p-4">
                    <span class="metric-label">Top package</span>
                    <strong class="metric-value"><?php echo e($topPackageKey ?? '--'); ?></strong>
                    <span class="metric-note"><?php echo e($topPackageInfo); ?></span>
                </div>
            </div>
        </div>

        <div class="table-card p-3 p-lg-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-3">
                <div>
                    <span class="section-kicker">Enquiry List</span>
                    <h2 class="h4 fw-bold mb-1">Customer inbox</h2>
                    <p class="section-text mb-0">Manage incoming leads and remove spam or duplicate enquiries quickly.</p>
                </div>
                <div class="admin-package-strip">
                    <?php foreach ($packageCounts as $key => $count): ?>
                        <span class="admin-package-pill"><?php echo e($key); ?>: <?php echo e((string) $count); ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th class="d-none d-md-table-cell">Monthly Bill</th>
                            <th>Package</th>
                            <th class="d-none d-lg-table-cell">Submitted</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($enquiries)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-secondary">
                                    No enquiries found.
                                    <?php if ($search !== ''): ?>
                                        <div class="mt-2">
                                            <a href="<?php echo e(site_url('admin/dashboard.php')); ?>" class="btn btn-sm btn-outline-secondary">Clear search</a>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($enquiries as $enquiry): ?>
                                <tr>
                                    <td>#<?php echo e((string) $enquiry['id']); ?></td>
                                    <td class="fw-semibold"><?php echo e($enquiry['full_name']); ?></td>
                                    <td><?php echo e($enquiry['phone_number']); ?></td>
                                    <td class="d-none d-md-table-cell">Rs. <?php echo e(number_format((float) $enquiry['monthly_bill'], 0)); ?></td>
                                    <td><span class="badge badge-package"><?php echo e($enquiry['package_selected']); ?></span></td>
                                    <td class="d-none d-lg-table-cell"><?php echo e(date('d M Y, h:i A', strtotime($enquiry['created_at']))); ?></td>
                                    <td class="text-end">
                                        <form action="<?php echo e(site_url('php/admin-delete.php')); ?>" method="post" class="d-inline" onsubmit="return confirm('Delete this enquiry?');">
                                            <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
                                            <input type="hidden" name="id" value="<?php echo e((string) $enquiry['id']); ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
