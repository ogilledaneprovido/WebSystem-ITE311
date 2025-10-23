<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Notifications</title>
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
        .dashboard-card {
            transition: transform 0.2s;
            height: 100%;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
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
                <a class="nav-link" href="<?= base_url('teacher/courses') ?>">Courses</a>
                <a class="nav-link" href="<?= base_url('teacher/students') ?>">Students</a>
                <a class="nav-link" href="<?= base_url('teacher/assignments') ?>">Assignments</a>
                <a class="nav-link" href="<?= base_url('teacher/materials') ?>">Materials</a>
                <a class="nav-link" href="<?= base_url('teacher/grades') ?>">Grades</a>
                <a class="nav-link active" href="<?= base_url('teacher/notifications') ?>">Notifications</a>
                <a class="nav-link" href="<?= base_url('logout') ?>">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <h1 class="card-title">Notifications</h1>
                        <p>Total Unread: <span class="badge bg-danger"><?= $unread_count ?></span></p>
                        <?php if (!empty($notifications)): ?>
                            <ul class="list-group">
                                <?php foreach ($notifications as $notification): ?>
                                    <li class="list-group-item<?= $notification['is_read'] ? '' : ' list-group-item-info' ?>">
                                        <?= esc($notification['message']) ?>
                                        <span class="float-end text-muted small">
                                            <?= date('M d, Y H:i', strtotime($notification['created_at'])) ?>
                                            <?php if (!$notification['is_read']): ?>
                                                <span class="badge bg-primary ms-2">Unread</span>
                                            <?php endif; ?>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <div class="alert alert-info mt-3">No notifications found.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
