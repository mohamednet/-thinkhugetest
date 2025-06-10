<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Finance Management App' ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            padding-top: 4.5rem;
        }
        .alert-float {
            position: fixed;
            top: 60px;
            right: 20px;
            z-index: 9999;
        }
    </style>
</head>
<body>
    <?php if (isset($_SESSION['user_id'])): ?>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="<?= url('/') ?>">Finance App</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    <li class="nav-item">
                        <a class="nav-link <?= ($active_page ?? '') === 'dashboard' ? 'active' : '' ?>" href="<?= url('/') ?>">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($active_page ?? '') === 'clients' ? 'active' : '' ?>" href="<?= url('clients') ?>">Clients</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($active_page ?? '') === 'reports' ? 'active' : '' ?>" href="<?= url('reports') ?>">Reports</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <span class="navbar-text me-3">
                        Welcome, <?= htmlspecialchars($_SESSION['username'] ?? 'User') ?>
                    </span>
                    <a href="<?= url('logout') ?>" class="btn btn-outline-light">Logout</a>
                </div>
            </div>
        </div>
    </nav>
    <?php endif; ?>

    <main class="container">
        <?php if (has_flash('success')): ?>
            <div class="alert alert-success alert-float" role="alert">
                <?= htmlspecialchars(get_flash('success')) ?>
            </div>
        <?php endif; ?>
        
        <?php if (has_flash('error')): ?>
            <div class="alert alert-danger alert-float" role="alert">
                <?= htmlspecialchars(get_flash('error')) ?>
            </div>
        <?php endif; ?>

        <?= $content ?? '' ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert-float');
                alerts.forEach(function(alert) {
                    alert.style.opacity = '0';
                    alert.style.transition = 'opacity 1s';
                    setTimeout(function() {
                        alert.remove();
                    }, 1000);
                });
            }, 5000);
        });
    </script>
</body>
</html>
