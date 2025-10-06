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

   
    <?php if (session()->get('role') == 'admin'): ?>
        <div class="alert alert-info mt-3">
            <h4>Admin Dashboard</h4>
            <p>You can manage users, view reports, and control system settings here.</p>
        </div>

    <?php elseif (session()->get('role') == 'teacher'): ?>
        <div class="alert alert-warning mt-3">
            <h4>Teacher Dashboard</h4>
            <p>You can manage your classes, assignments, and student grades here.</p>
        </div>

    <?php elseif (session()->get('role') == 'student'): ?>
        <div class="alert alert-success mt-3">
            <h4>Student Dashboard</h4>
            <p>You can view your enrolled subjects, assignments, and grades here.</p>
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
