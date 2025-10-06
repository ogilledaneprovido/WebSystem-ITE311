<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ITE311 Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- ✅ Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="/ITE311-PROVIDO/">ITE311</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">

        <?php if (session()->get('isLoggedIn')): ?>
          <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('dashboard') ?>">Dashboard</a></li>

          <!-- ✅ Role-based navigation -->
          <?php if (session()->get('role') == 'admin'): ?>
            <li class="nav-item"><a class="nav-link text-white" href="#">Manage Users</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">System Settings</a></li>

          <?php elseif (session()->get('role') == 'teacher'): ?>
            <li class="nav-item"><a class="nav-link text-white" href="#">My Classes</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">Assignments</a></li>
        
          <?php elseif (session()->get('role') == 'student'): ?>
            <li class="nav-item"><a class="nav-link text-white" href="#">My Subjects</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">Grades</a></li>

          <?php elseif (session()->get('role') == 'newrole'): ?>
            <li class="nav-item"><a class="nav-link text-white" href="#">New Role Panel</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">Reports</a></li>
          <?php endif; ?>

          <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('logout') ?>">Logout</a></li>

        <?php else: ?>
          <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('login') ?>">Login</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('register') ?>">Register</a></li>
        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>

<!-- ✅ Main Dashboard Content -->
<div class="container mt-5">

  <?php if(session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>

  <h2>Welcome to the Dashboard</h2>
  <p class="lead">Hello, <strong><?= session()->get('name') ?></strong>!</p>
  <p>Your role is: <strong><?= session()->get('role') ?></strong></p>

  <!-- ✅ Role-based dashboard content -->
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
          <p>This section is for the new user role.</p>
      </div>
  <?php endif; ?>
</div>

<!-- ✅ Footer -->
<footer class="bg-primary text-white text-center py-3 mt-5">
  <p class="mb-0">© <?= date('Y') ?> ITE311 - Unified Dashboard</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
