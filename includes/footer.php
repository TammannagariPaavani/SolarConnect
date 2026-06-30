<?php
?>
<footer class="site-footer">
    <div class="container py-4 py-lg-5">
        <div class="row g-4 align-items-center">
            <div class="col-lg-7">
                <h5 class="mb-2"><?php echo e(SITE_NAME); ?></h5>
                <p class="mb-0 text-white-50">Reliable rooftop solar installation, simple enquiries, and a secure admin workflow for fast follow-up.</p>
            </div>
            <div class="col-lg-5 text-lg-end">
                <a href="<?php echo e(site_url('index.php#packages')); ?>" class="footer-link me-3">Packages</a>
                <a href="<?php echo e(site_url('index.php#enquiry')); ?>" class="footer-link me-3">Enquiry</a>
                <a href="<?php echo e(site_url('admin/login.php')); ?>" class="footer-link">Admin</a>
            </div>
        </div>
        <hr class="footer-divider my-4">
        <p class="mb-0 small text-white-50">© <?php echo date('Y'); ?> <?php echo e(SITE_NAME); ?>. All rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/main.js"></script>
</body>
</html>
