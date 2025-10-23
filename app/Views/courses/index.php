<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses</title>
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
        .course-card {
            transition: transform 0.2s;
            height: 100%;
        }
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body>
    <!-- Uniform Navbar -->
    <nav class="navbar navbar-expand-lg custom-navbar">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="<?= base_url('student/dashboard') ?>">ITE311</a>
            
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="<?= base_url('student/dashboard') ?>">Dashboard</a>
                <a class="nav-link" href="<?= base_url('courses') ?>">My Courses</a>
                <a class="nav-link" href="<?= base_url('student/available-courses') ?>">Available Courses</a>
                <a class="nav-link" href="<?= base_url('student/assignments') ?>">Assignments</a>
                <a class="nav-link" href="<?= base_url('student/grades') ?>">Grades</a>
                <a class="nav-link" href="<?= base_url('announcements') ?>">Announcements</a>
                <a class="nav-link" href="<?= base_url('logout') ?>">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="mb-4">📚 My Enrolled Courses</h1>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (!isset($courses) || empty($courses)): ?>
            <div class="card">
                <div class="card-body text-center py-5">
                    <h3 class="text-muted">📖 No Enrolled Courses</h3>
                    <p class="text-muted">You haven't enrolled in any courses yet.</p>
                    <a href="<?= base_url('student/available-courses') ?>" class="btn btn-primary mt-3">Browse Available Courses</a>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($courses as $course): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card course-card">
                            <div class="card-body">
                                <h5 class="card-title"><?= esc($course['title']) ?></h5>
                                <p class="card-text"><?= esc($course['description']) ?></p>
                                <div class="d-grid gap-2">
                                    <a href="<?= base_url('course/' . $course['id'] . '/materials') ?>" class="btn btn-primary">
                                        View Materials
                                    </a>
                                    <a href="<?= base_url('course/' . $course['id']) ?>" class="btn btn-outline-secondary">
                                        Course Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
