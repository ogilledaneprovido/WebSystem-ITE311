<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .custom-navbar {
            background-color: #4285f4 !important;
            padding: 8px 0;
            min-height: 50px;
        }
        .navbar-brand {
            font-weight: 600;
            font-size: 18px;
            color: white !important;
            margin-right: 40px;
        }
        .navbar-nav .nav-link {
            color: white !important;
            font-size: 14px;
            padding: 8px 20px !important;
            margin: 0;
        }
        .navbar-nav .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <!-- Uniform Navbar -->
    <nav class="navbar navbar-expand-lg custom-navbar">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="<?= base_url('teacher/dashboard') ?>">ITE311</a>
            
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="<?= base_url('teacher/dashboard') ?>">Dashboard</a>
                <a class="nav-link" href="<?= base_url('teacher/courses') ?>">My Courses</a>
                <a class="nav-link" href="<?= base_url('teacher/materials') ?>">Materials</a>
                <a class="nav-link" href="<?= base_url('logout') ?>">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Welcome, Teacher!</h1>
        <p>Logged in as: <?= esc(session()->get('username')) ?></p>
        <p>Role: <?= esc(session()->get('role')) ?></p>
    </div>
</body>
</html>
