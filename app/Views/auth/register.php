<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
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
        <li class="nav-item"><a class="nav-link text-white" href="contact">Contact</a></li>
      </ul>
      <ul class="navbar-nav">
        <?php if (session()->get('isLoggedIn')): ?>
          <li class="nav-item"><a class="nav-link text-white" href="dashboard">Dashboard</a></li>
          <li class="nav-item"><span class="nav-link text-white">Hello, <?= session()->get('name') ?></span></li>
          <li class="nav-item"><a class="nav-link text-white" href="logout">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link text-white" href="login">Login</a></li>
          <li class="nav-item"><a class="nav-link active text-white fw-bold" href="register">Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
  </div>
</nav>

<div class="container mt-5">
    <h2>Register</h2>
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    
    
    <?php if(session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach(session()->getFlashdata('errors') as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="register" method="post">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Confirm Password</label>
            <input type="password" name="password_confirm" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
        <a href="login" class="btn btn-link">Already have an account? Login</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
