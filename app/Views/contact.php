  <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold text-white" href="/">ITE311</a>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link text-white" href="/">Home</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="about">About</a></li>
        <li class="nav-item"><a class="nav-link active text-white fw-bold" href="contact">Contact</a></li>
      </ul>
      <ul class="navbar-nav">
        <?php if (session()->get('isLoggedIn')): ?>
          <li class="nav-item"><a class="nav-link text-white" href="dashboard">Dashboard</a></li>
          <li class="nav-item"><span class="nav-link text-white">Hello, <?= session()->get('name') ?></span></li>
          <li class="nav-item"><a class="nav-link text-white" href="logout">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link text-white" href="login">Login</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="register">Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
  </div>
</nav>

<!-- Main Content -->
<div class="container mt-5 text-center">
    <h1 class="text-primary">Contact Page</h1>
    <p class="text-muted">Contact us at: <a href="mailto:info@example.com" class="text-decoration-none">info@example.com</a></p>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
  