<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
        .announcement-card {
            border-left: 4px solid #007bff;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .announcement-title {
            color: #333;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .announcement-date {
            color: #6c757d;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <!-- Uniform Navbar -->
    <nav class="navbar navbar-expand-lg custom-navbar">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="<?= base_url('announcements') ?>">ITE311</a>
            
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="<?= base_url('announcements') ?>">Announcements</a>
                <?php if (session()->get('role') === 'student'): ?>
                    <a class="nav-link" href="<?= base_url('student/dashboard') ?>">Dashboard</a>
                    <a class="nav-link" href="<?= base_url('student/available-courses') ?>">Available Courses</a>
                <?php elseif (session()->get('role') === 'teacher'): ?>
                    <a class="nav-link" href="<?= base_url('teacher/dashboard') ?>">Dashboard</a>
                <?php elseif (session()->get('role') === 'admin'): ?>
                    <a class="nav-link" href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
                <?php endif; ?>
                <a class="nav-link" href="<?= base_url('logout') ?>">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-bullhorn"></i> Announcements</h1>
            <p class="text-muted mb-0">Stay updated with the latest news and information</p>
        </div>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> <?= esc($error) ?>
            </div>
        <?php endif; ?>
        
        <div class="row">
            <div class="col-lg-8">
                <?php if (empty($announcements)): ?>
                    <div class="card announcement-card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">No Announcements</h4>
                            <p class="text-muted">There are currently no announcements to display. Please check back later for updates.</p>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($announcements as $announcement): ?>
                        <div class="card announcement-card">
                            <div class="card-body">
                                <h5 class="announcement-title"><?= esc($announcement['title']) ?></h5>
                                <div class="announcement-content mb-3">
                                    <?= nl2br(esc($announcement['content'])) ?>
                                </div>
                                <div class="announcement-date">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    Posted: <?= date('F j, Y g:i A', strtotime($announcement['created_at'])) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="fas fa-user"></i> User Information</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Name:</strong> <?= esc(session()->get('username')) ?></p>
                        <p><strong>Role:</strong> 
                            <span class="badge bg-<?= session()->get('role') === 'admin' ? 'danger' : (session()->get('role') === 'teacher' ? 'warning' : 'primary') ?>">
                                <?= esc(ucfirst(session()->get('role'))) ?>
                            </span>
                        </p>
                        <p class="small text-muted mb-0">
                            <i class="fas fa-info-circle"></i> 
                            You are viewing the announcements page. Use the navigation menu to access other features available to your role.
                        </p>
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-clock"></i> Quick Links</h6>
                    </div>
                    <div class="card-body">
                        <?php if (session()->get('role') === 'student'): ?>
                            <a href="<?= base_url('student/dashboard') ?>" class="btn btn-outline-primary btn-sm w-100 mb-2">
                                <i class="fas fa-tachometer-alt"></i> My Dashboard
                            </a>
                            <a href="<?= base_url('student/available-courses') ?>" class="btn btn-outline-success btn-sm w-100">
                                <i class="fas fa-book"></i> Available Courses
                            </a>
                        <?php elseif (session()->get('role') === 'teacher'): ?>
                            <a href="<?= base_url('teacher/dashboard') ?>" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-chalkboard-teacher"></i> Teacher Dashboard
                            </a>
                        <?php elseif (session()->get('role') === 'admin'): ?>
                            <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-outline-danger btn-sm w-100 mb-2">
                                <i class="fas fa-cog"></i> Admin Dashboard
                            </a>
                            <a href="<?= base_url('admin/courses') ?>" class="btn btn-outline-info btn-sm w-100">
                                <i class="fas fa-list"></i> Manage Courses
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
