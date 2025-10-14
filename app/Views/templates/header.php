<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ITE311 Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
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
