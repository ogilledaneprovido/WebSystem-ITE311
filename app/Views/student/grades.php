<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Grades</title>
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
        .grade-card {
            transition: transform 0.2s;
        }
        .grade-card:hover {
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
        <h1 class="mb-4">📊 My Grades</h1>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <?php if (empty($grades)): ?>
            <div class="card">
                <div class="card-body text-center py-5">
                    <h3 class="text-muted">📈 No Grades Yet</h3>
                    <p class="text-muted">You don't have any grades to display at the moment.</p>
                    <p class="text-muted">Grades will appear here once your instructors have graded your work.</p>
                    <a href="<?= base_url('student/dashboard') ?>" class="btn btn-primary mt-3">Back to Dashboard</a>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($grades as $grade): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card grade-card">
                            <div class="card-body">
                                <h5 class="card-title"><?= esc($grade['course_name']) ?></h5>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Grade:</span>
                                    <h3 class="mb-0 text-primary"><?= esc($grade['grade']) ?></h3>
                                </div>
                                <hr>
                                <p class="card-text text-muted mb-1">
                                    <small>Assignment: <?= esc($grade['assignment_name']) ?></small>
                                </p>
                                <p class="card-text text-muted">
                                    <small>Date: <?= esc($grade['graded_at']) ?></small>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Overall Performance Summary -->
            <div class="card mt-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Performance Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <h3 class="text-primary">--</h3>
                            <p class="text-muted">Overall Average</p>
                        </div>
                        <div class="col-md-4">
                            <h3 class="text-success">--</h3>
                            <p class="text-muted">Completed Courses</p>
                        </div>
                        <div class="col-md-4">
                            <h3 class="text-info">--</h3>
                            <p class="text-muted">In Progress</p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
