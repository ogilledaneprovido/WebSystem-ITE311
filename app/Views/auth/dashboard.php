<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Main Content -->
<div class="container mt-5">

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <h2>Welcome to the Dashboard</h2>
    <p class="lead">Hello, <strong><?= session()->get('name') ?></strong>!</p>
    <p>Your role is: <strong><?= session()->get('role') ?></strong></p>

    <!-- Announcements Section -->
    <?php if (!empty($announcements)): ?>
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">📢 Recent Announcements</h5>
        </div>
        <div class="card-body">
            <?php foreach ($announcements as $announcement): ?>
                <div class="alert alert-info">
                    <h6 class="alert-heading"><?= esc($announcement['title']) ?></h6>
                    <p class="mb-1"><?= esc($announcement['content']) ?></p>
                    <small class="text-muted">Posted: <?= date('F j, Y', strtotime($announcement['created_at'])) ?></small>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
   
    <?php if (session()->get('role') == 'admin'): ?>
        <div class="alert alert-info mt-3">
            <h4>Admin Dashboard</h4>
            <p>You can manage users, view reports, and control system settings here.</p>
            <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-primary">Go to Admin Dashboard</a>
        </div>

    <?php elseif (session()->get('role') == 'teacher'): ?>
        <div class="alert alert-warning mt-3">
            <h4>Teacher Dashboard</h4>
            <p>You can manage your classes, assignments, and student grades here.</p>
            <a href="<?= base_url('teacher/dashboard') ?>" class="btn btn-primary">Go to Teacher Dashboard</a>
        </div>

    <?php elseif (session()->get('role') == 'student'): ?>
        <div class="alert alert-success mt-3">
            <h4>Student Dashboard</h4>
            <p>You can view your enrolled subjects, assignments, and grades here.</p>
            <a href="<?= base_url('courses') ?>" class="btn btn-primary">Go to My Courses</a>
        </div>

    <?php elseif (session()->get('role') == 'newrole'): ?> 
        <div class="alert alert-primary mt-3">
            <h4>New Role Dashboard</h4>
            <p>This section is for users with the new role. You can add special features here later.</p>
        </div>
    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
