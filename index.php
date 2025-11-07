<?php
require_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0;
            margin-bottom: 40px;
        }
        .card {
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }
        .card-icon {
            font-size: 3rem;
            color: #667eea;
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">HR Management System</h1>
            <p class="lead">Manage your human resources database efficiently</p>
        </div>
    </div>

    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-globe card-icon mb-3"></i>
                        <h5 class="card-title">Regions</h5>
                        <p class="card-text">Manage geographical regions</p>
                        <a href="regions.php" class="btn btn-primary">View Regions</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-flag card-icon mb-3"></i>
                        <h5 class="card-title">Countries</h5>
                        <p class="card-text">Manage country information</p>
                        <a href="countries.php" class="btn btn-primary">View Countries</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-map-marker-alt card-icon mb-3"></i>
                        <h5 class="card-title">Locations</h5>
                        <p class="card-text">Manage office locations</p>
                        <a href="locations.php" class="btn btn-primary">View Locations</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-building card-icon mb-3"></i>
                        <h5 class="card-title">Departments</h5>
                        <p class="card-text">Manage company departments</p>
                        <a href="departments.php" class="btn btn-primary">View Departments</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-briefcase card-icon mb-3"></i>
                        <h5 class="card-title">Jobs</h5>
                        <p class="card-text">Manage job positions</p>
                        <a href="jobs.php" class="btn btn-primary">View Jobs</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-users card-icon mb-3"></i>
                        <h5 class="card-title">Employees</h5>
                        <p class="card-text">Manage employee records</p>
                        <a href="employees.php" class="btn btn-primary">View Employees</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-history card-icon mb-3"></i>
                        <h5 class="card-title">Job History</h5>
                        <p class="card-text">View job history records</p>
                        <a href="job_history.php" class="btn btn-primary">View Job History</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

