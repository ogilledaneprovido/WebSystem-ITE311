<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">

<?= view('templates/header') ?>

<div class="container mt-4">
    <h2>Student Dashboard</h2>
    <p>Welcome, <?= esc(session()->get('name')) ?>!</p>
    
    <!-- Student Stats -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h4><?= $total_enrollments ?></h4>
                    <p>Enrolled Courses</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Enrolled Courses -->
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5>My Courses</h5>
            <a href="<?= base_url('/courses') ?>" class="btn btn-primary">Browse Courses</a>
        </div>
        <div class="card-body">
            <?php if (empty($enrolled_courses)): ?>
                <p class="text-muted">You are not enrolled in any courses yet.</p>
                <a href="<?= base_url('/courses') ?>" class="btn btn-primary">Browse Available Courses</a>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($enrolled_courses as $course): ?>
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title"><?= esc($course['title']) ?></h5>
                                    <p class="card-text"><?= esc($course['description']) ?></p>
                                    <p class="text-muted">
                                        <i class="fas fa-file"></i> 
                                        <?= $course['materials_count'] ?> Materials Available
                                    </p>
                                    <a href="<?= base_url('/course/' . $course['id'] . '/materials') ?>" class="btn btn-success">
                                        View Materials
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= view('templates/footer') ?>

</body>
</html>
