<?php
require_once __DIR__ . '/includes/init.php';

$pageTitle = SITE_NAME . ' - Rooftop Solar Installation';
$pageDescription = 'SolarConnect helps homeowners compare 3kW, 5kW, and 10kW rooftop solar packages and send secure enquiries online.';
$activeNav = 'home';

$packages = get_packages();
$formState = get_form_state();
$formErrors = $formState['errors'] ?? [];
$old = $formState['old'] ?? [];
$successMessage = get_flash('success');
$errorMessage = get_flash('error');

include __DIR__ . '/includes/header.php';
?>

<main id="home">
    <section class="hero-section">
        <div class="container position-relative">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <span class="hero-badge mb-3">Clean energy. Lower bills. Quick responses.</span>
                    <h1 class="hero-title mb-3">Power your rooftop with <span class="accent">SolarConnect</span></h1>
                    <p class="hero-copy mb-4">
                        Explore our 3kW, 5kW, and 10kW solar packages, compare transparent pricing, and send a secure enquiry that reaches our team instantly.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="#packages" class="btn btn-solar btn-lg px-4">View Packages</a>
                        <a href="#enquiry" class="btn btn-outline-light btn-lg px-4">Send Enquiry</a>
                    </div>
                    <div class="hero-stats">
                        <div class="stat-card">
                            <strong>3 Packages</strong>
                            <span>Simple choices for every home size</span>
                        </div>
                        <div class="stat-card">
                            <strong>Verified</strong>
                            <span>10-digit phone enquiry workflow</span>
                        </div>
                        <div class="stat-card">
                            <strong>Secure</strong>
                            <span>Protected form submission and admin access</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 text-center">
                    <img src="<?php echo e(site_url('assets/images/hero-solar.svg')); ?>" class="img-fluid" alt="Solar rooftop illustration">
                </div>
            </div>
        </div>
    </section>

    <section class="section-shell" id="packages">
        <div class="container">
            <div class="section-heading">
                <span class="section-kicker">Packages</span>
                <h2 class="section-title">Choose the system size that fits your electricity usage</h2>
                <p class="section-text">Each package includes a short description, key features, and a clear starting price so customers can compare options quickly.</p>
            </div>

            <div class="row g-4">
                <?php foreach ($packages as $key => $package): ?>
                    <div class="col-md-6 col-xl-4">
                        <div class="solar-card p-4">
                            <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                <div>
                                    <span class="solar-tag mb-2"><?php echo e($key); ?></span>
                                    <h3 class="h4 mb-1"><?php echo e($package['name']); ?></h3>
                                </div>
                                <span class="solar-price"><?php echo e($package['price']); ?></span>
                            </div>
                            <p class="text-secondary mb-4"><?php echo e($package['description']); ?></p>
                            <ul class="feature-list ps-3 mb-4">
                                <?php foreach ($package['features'] as $feature): ?>
                                    <li><?php echo e($feature); ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn btn-solar w-100" data-package-select="<?php echo e($key); ?>">Enquire Now</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section-shell enquiry-wrap" id="enquiry">
        <div class="container">
            <div class="section-heading">
                <span class="section-kicker">Enquiry Form</span>
                <h2 class="section-title">Send a quick enquiry and let us recommend the right solar system</h2>
                <p class="section-text">All fields are validated in JavaScript and again in PHP before secure database storage.</p>
            </div>

            <?php if ($successMessage): ?>
                <div class="alert alert-success"><?php echo e($successMessage); ?></div>
            <?php endif; ?>

            <?php if ($errorMessage): ?>
                <div class="alert alert-danger"><?php echo e($errorMessage); ?></div>
            <?php endif; ?>

            <div class="row g-4 align-items-stretch">
                <div class="col-lg-7">
                    <div class="form-card p-4 p-lg-5">
                        <form id="enquiryForm" action="<?php echo e(site_url('php/submit-enquiry.php')); ?>" method="post" novalidate>
                            <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
                            <div class="row g-3">
                                <div class="col-md-6 mb-3">
                                    <label for="full_name" class="form-label fw-semibold">Full Name</label>
                                    <input
                                        type="text"
                                        class="form-control <?php echo isset($formErrors['full_name']) ? 'is-invalid' : ''; ?>"
                                        id="full_name"
                                        name="full_name"
                                        placeholder="Enter full name"
                                        value="<?php echo e(old_value($old, 'full_name')); ?>"
                                        required
                                    >
                                    <div class="invalid-feedback"><?php echo e($formErrors['full_name'] ?? 'Please enter your full name.'); ?></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone_number" class="form-label fw-semibold">Phone Number</label>
                                    <input
                                        type="text"
                                        class="form-control <?php echo isset($formErrors['phone_number']) ? 'is-invalid' : ''; ?>"
                                        id="phone_number"
                                        name="phone_number"
                                        placeholder="10-digit mobile number"
                                        maxlength="10"
                                        inputmode="numeric"
                                        pattern="\d{10}"
                                        value="<?php echo e(old_value($old, 'phone_number')); ?>"
                                        required
                                    >
                                    <div class="invalid-feedback"><?php echo e($formErrors['phone_number'] ?? 'Phone number must be exactly 10 digits.'); ?></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="monthly_bill" class="form-label fw-semibold">Monthly Electricity Bill</label>
                                    <input
                                        type="number"
                                        class="form-control <?php echo isset($formErrors['monthly_bill']) ? 'is-invalid' : ''; ?>"
                                        id="monthly_bill"
                                        name="monthly_bill"
                                        placeholder="e.g. 3500"
                                        min="1"
                                        step="1"
                                        value="<?php echo e(old_value($old, 'monthly_bill')); ?>"
                                        required
                                    >
                                    <div class="invalid-feedback"><?php echo e($formErrors['monthly_bill'] ?? 'Please enter a valid monthly electricity bill amount.'); ?></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="package_selected" class="form-label fw-semibold">Package Selection</label>
                                    <select
                                        class="form-select <?php echo isset($formErrors['package_selected']) ? 'is-invalid' : ''; ?>"
                                        id="package_selected"
                                        name="package_selected"
                                        required
                                    >
                                        <option value="">Choose a package</option>
                                        <?php foreach (array_keys($packages) as $pkgKey): ?>
                                            <option value="<?php echo e($pkgKey); ?>" <?php echo old_value($old, 'package_selected') === $pkgKey ? 'selected' : ''; ?>>
                                                <?php echo e($pkgKey); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback"><?php echo e($formErrors['package_selected'] ?? 'Please select a package.'); ?></div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-solar btn-lg mt-2 px-4">Submit Enquiry</button>
                        </form>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="info-card enquiry-side enquiry-side-minimal p-4 p-lg-4 h-100">
                        <img
                            src="<?php echo e(site_url('assets/images/hero-solar.svg')); ?>"
                            class="img-fluid enquiry-illustration mb-3"
                            alt="Solar rooftop illustration"
                        >
                        <span class="section-kicker">Need Help Choosing?</span>
                        <h3 class="h5 fw-bold mb-2">A quick, simple recommendation</h3>
                        <p class="text-secondary mb-3">
                            Share your monthly bill and we will suggest the most suitable solar package.
                        </p>
                        <ul class="mini-list ps-3 mb-0">
                            <li>Quick package comparison</li>
                            <li>Secure form submission</li>
                            <li>Admin team gets the enquiry instantly</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
