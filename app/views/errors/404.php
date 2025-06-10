<?php
$title = 'Page Not Found';
ob_start();
?>

<div class="row justify-content-center mt-5">
    <div class="col-md-8 text-center">
        <h1 class="display-1">404</h1>
        <h2 class="mb-4">Page Not Found</h2>
        <p class="lead">The page you are looking for does not exist or has been moved.</p>
        <a href="/" class="btn btn-primary">Return to Dashboard</a>
    </div>
</div>

<?php
$content = ob_get_clean();
include APP_ROOT . '/app/views/layouts/main.php';
?>
