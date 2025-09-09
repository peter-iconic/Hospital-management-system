<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/includes/functions.php';

$page = $_GET['page'] ?? 'home';
$u = current_user();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>SmartHealth</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: #f8f9fa;
        }

        .hero {
            background: url("assets/health-bg.jpg") center/cover no-repeat;
            color: white;
            padding: 80px 20px;
            border-radius: 10px;
            text-align: center;
        }

        .card img {
            height: 160px;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="index.php">
                <img src="assets/logo.png" alt="Logo" width="35" height="35" class="me-2">
                SmartHealth
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <?php if (!empty($u)) { ?>
                        <li class="nav-item"><a class="nav-link" href="?page=patients">Patients</a></li>
                        <?php if (in_array($u['role'], ['Admin', 'Receptionist'])) { ?>
                            <li class="nav-item"><a class="nav-link" href="?page=appointments">Appointments</a></li>
                        <?php } ?>
                        <?php if (in_array($u['role'], ['Admin', 'LabTech'])) { ?>
                            <li class="nav-item"><a class="nav-link" href="?page=lab">Lab Results</a></li>
                        <?php } ?>
                        <?php if (in_array($u['role'], ['Admin', 'Cashier'])) { ?>
                            <li class="nav-item"><a class="nav-link" href="?page=billing">Billing</a></li>
                        <?php } ?>
                    <?php } ?>
                </ul>
                <div class="d-flex">
                    <?php if (!empty($u)) { ?>
                        <span class="navbar-text me-2">
                            Hello, <?= h($u['full_name'] ?? 'Guest') ?> (<?= h($u['role'] ?? '') ?>)
                        </span>
                        <a class="btn btn-outline-light btn-sm" href="logout.php">Logout</a>
                    <?php } else { ?>
                        <a class="btn btn-outline-light btn-sm" href="?page=login">Login</a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php
        if ($page === 'patients') {
            include __DIR__ . '/patients.php';
        } elseif ($page === 'appointments') {
            include __DIR__ . '/appointments.php';
        } elseif ($page === 'lab') {
            include __DIR__ . '/lab_results.php';
        } elseif ($page === 'billing') {
            include __DIR__ . '/billing.php';
        } elseif ($page === 'login') {
            include __DIR__ . '/login.php';
        } else {
            // Home page with hero + cards
            ?>
            <div class="hero mb-5">
                <h1 class="display-5 fw-bold">Welcome to SmartHealth</h1>
                <p class="lead">Your all-in-one hospital management system.</p>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="card shadow">
                        <img src="assets/patients.jpg" class="card-img-top" alt="Patients">
                        <div class="card-body text-center">
                            <h5 class="card-title">Patients</h5>
                            <a href="?page=patients" class="btn btn-primary">Manage Patients</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow">
                        <img src="assets/appointments.jpg" class="card-img-top" alt="Appointments">
                        <div class="card-body text-center">
                            <h5 class="card-title">Appointments</h5>
                            <a href="?page=appointments" class="btn btn-primary">View Appointments</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow">
                        <img src="assets/lab.jpg" class="card-img-top" alt="Lab">
                        <div class="card-body text-center">
                            <h5 class="card-title">Lab Results</h5>
                            <a href="?page=lab" class="btn btn-primary">Check Lab Reports</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow">
                        <img src="assets/billing.jpg" class="card-img-top" alt="Billing">
                        <div class="card-body text-center">
                            <h5 class="card-title">Billing</h5>
                            <a href="?page=billing" class="btn btn-primary">Manage Billing</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        } // end else home
        ?>
    </div>
</body>

</html>