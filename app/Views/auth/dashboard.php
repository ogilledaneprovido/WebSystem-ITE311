<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold text-white" href="/ITE311-PROVIDO/">ITE311</a>
    <div class="collapse navbar-collapse" id="navbarNav">
      <!-- Home, Contact, and About navigation removed -->
      <ul class="navbar-nav">
        <?php if (session()->get('isLoggedIn')): ?>
          <li class="nav-item"><a class="nav-link active text-white fw-bold" href="/ITE311-PROVIDO/dashboard">Dashboard</a></li>
          <li class="nav-item"><span class="nav-link text-white">Hello, <?= session()->get('name') ?></span></li>
          <li class="nav-item"><a class="nav-link text-white" href="/ITE311-PROVIDO/logout">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link text-white" href="/ITE311-PROVIDO/login">Login</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="/ITE311-PROVIDO/register">Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
  </div>
</nav>

<div class="container mt-5">
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    
    <h2>Welcome to the Dashboard</h2>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
