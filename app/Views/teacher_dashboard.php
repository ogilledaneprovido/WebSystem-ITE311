<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
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
        .dashboard-card {
            transition: transform 0.2s;
            height: 100%;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .notification-badge {
            position: relative;
            display: inline-block;
        }
        .notification-badge .badge {
            position: absolute;
            top: -8px;
            right: -10px;
            padding: 3px 6px;
            border-radius: 10px;
            background: #dc3545;
            color: white;
            font-size: 10px;
        }
        .notification-dropdown {
            min-width: 350px;
            max-height: 400px;
            overflow-y: auto;
        }
        .notification-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .notification-item:hover {
            background-color: #f8f9fa;
        }
        .notification-item.unread {
            background-color: #e7f3ff;
        }
        .dropdown-menu {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body>
    <!-- Uniform Navbar -->
    <nav class="navbar navbar-expand-lg custom-navbar">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="<?= base_url('teacher/dashboard') ?>">ITE311</a>
            
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="<?= base_url('teacher/dashboard') ?>">Dashboard</a>
                <a class="nav-link" href="<?= base_url('teacher/courses') ?>">Courses</a>
                <a class="nav-link" href="<?= base_url('teacher/students') ?>">Students</a>
                <a class="nav-link" href="<?= base_url('teacher/assignments') ?>">Assignments</a>
                <a class="nav-link" href="<?= base_url('teacher/grades') ?>">Grades</a>
                <a class="nav-link" href="<?= base_url('announcements') ?>">Announcements</a>
                
                <!-- Notification Dropdown -->
                <div class="nav-item dropdown">
                    <a class="nav-link notification-badge dropdown-toggle" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        🔔
                        <span class="badge" id="notificationBadge" style="display: none;">0</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationDropdown" id="notificationList">
                        <li class="dropdown-header">Notifications</li>
                        <li><hr class="dropdown-divider"></li>
                        <li class="text-center p-3"><span class="text-muted">Loading...</span></li>
                    </ul>
                </div>
                
                <a class="nav-link" href="<?= base_url('logout') ?>">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h1 class="card-title">Welcome, Teacher!</h1>
                        <p class="card-text mb-1">You are logged in as: <strong><?= esc(session()->get('name')) ?></strong></p>
                        <p class="card-text">Your role: <strong><?= esc(session()->get('role')) ?></strong></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Teacher Management Cards -->
        <div class="row g-4">
            <!-- Statistics Cards -->
            <div class="col-md-4">
                <div class="card dashboard-card border-primary h-100">
                    <div class="card-body text-center">
                        <h3 class="text-primary">📚</h3>
                        <h2 class="text-primary mb-2"><?= $total_courses ?></h2>
                        <h6 class="card-title mb-3">Total Courses</h6>
                        <a href="<?= base_url('teacher/courses') ?>" class="btn btn-primary btn-sm w-100">View Courses</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card dashboard-card border-success h-100">
                    <div class="card-body text-center">
                        <h3 class="text-success">�</h3>
                        <h2 class="text-success mb-2"><?= $total_students ?></h2>
                        <h6 class="card-title mb-3">Total Students</h6>
                        <a href="<?= base_url('teacher/students') ?>" class="btn btn-success btn-sm w-100">View Students</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card dashboard-card border-warning h-100">
                    <div class="card-body text-center">
                        <h3 class="text-warning">✏️</h3>
                        <h2 class="text-warning mb-2"><?= $pending_assignments ?></h2>
                        <h6 class="card-title mb-3">Pending Grading</h6>
                        <a href="<?= base_url('teacher/assignments') ?>" class="btn btn-warning btn-sm w-100">Grade Assignments</a>
                    </div>
                </div>
            </div>

            <!-- Recent Courses -->
            <div class="col-md-6">
                <div class="card dashboard-card border-primary h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-3">📚 Recent Courses</h5>
                        <?php if (!empty($recent_courses)): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($recent_courses as $course): ?>
                                    <div class="list-group-item px-0">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1"><?= esc($course['title']) ?></h6>
                                                <small class="text-muted">Created: <?= date('M d, Y', strtotime($course['created_at'])) ?></small>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <a href="<?= base_url('teacher/courses') ?>" class="btn btn-primary btn-sm mt-3 w-100">View All Courses</a>
                        <?php else: ?>
                            <p class="text-muted text-center py-3">No courses assigned</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Recent Announcements -->
            <div class="col-md-6">
                <div class="card dashboard-card border-info h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-3">📢 Recent Announcements</h5>
                        <?php if (!empty($recent_announcements)): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($recent_announcements as $announcement): ?>
                                    <div class="list-group-item px-0">
                                        <h6 class="mb-1"><?= esc($announcement['title']) ?></h6>
                                        <small class="text-muted"><?= date('M d, Y', strtotime($announcement['created_at'])) ?></small>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <a href="<?= base_url('announcements') ?>" class="btn btn-info btn-sm mt-3 w-100">View All</a>
                        <?php else: ?>
                            <p class="text-muted text-center py-3">No announcements</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="col-md-12">
                <div class="card dashboard-card border-secondary">
                    <div class="card-body">
                        <h5 class="card-title mb-3">⚡ Quick Actions</h5>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <a href="<?= base_url('teacher/assignments') ?>" class="btn btn-outline-primary w-100">
                                    <h5>✏️</h5>
                                    <small>Create Assignment</small>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="<?= base_url('teacher/materials') ?>" class="btn btn-outline-success w-100">
                                    <h5>📝</h5>
                                    <small>Upload Materials</small>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="<?= base_url('teacher/grades') ?>" class="btn btn-outline-info w-100">
                                    <h5>📊</h5>
                                    <small>Grade Students</small>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="<?= base_url('announcements') ?>" class="btn btn-outline-warning w-100">
                                    <h5>📢</h5>
                                    <small>Post Announcement</small>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            loadNotifications();
            setInterval(loadNotifications, 30000);

            function loadNotifications() {
                $.get('<?= base_url('notifications') ?>', function(response) {
                    if (response.success) {
                        updateNotificationBadge(response.unread_count);
                        updateNotificationList(response.notifications);
                    }
                });
            }

            function updateNotificationBadge(count) {
                const badge = $('#notificationBadge');
                if (count > 0) {
                    badge.text(count).show();
                } else {
                    badge.hide();
                }
            }

            function updateNotificationList(notifications) {
                const list = $('#notificationList');
                list.empty();
                list.append('<li class="dropdown-header">Notifications</li>');
                list.append('<li><hr class="dropdown-divider"></li>');

                if (notifications.length === 0) {
                    list.append('<li class="text-center p-3"><span class="text-muted">No notifications</span></li>');
                } else {
                    notifications.forEach(function(notification) {
                        const unreadClass = notification.is_read == 0 ? 'unread' : '';
                        const notificationItem = `
                            <li class="notification-item ${unreadClass}" data-id="${notification.id}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <p class="mb-1 small">${notification.message}</p>
                                        <small class="text-muted">${formatDate(notification.created_at)}</small>
                                    </div>
                                    ${notification.is_read == 0 ? `
                                        <button class="btn btn-sm btn-link text-primary mark-read" data-id="${notification.id}">
                                            Mark Read
                                        </button>
                                    ` : ''}
                                </div>
                            </li>
                        `;
                        list.append(notificationItem);
                    });
                    list.append('<li><hr class="dropdown-divider"></li>');
                    list.append(`<li class="text-center p-2"><a href="<?= base_url('teacher/notifications') ?>" class="text-decoration-none">View All Notifications</a></li>`);
                }
            }

            $(document).on('click', '.mark-read', function(e) {
                e.stopPropagation();
                const notificationId = $(this).data('id');
                const notificationItem = $(this).closest('.notification-item');

                $.post('<?= base_url('notifications/mark_read/') ?>' + notificationId, function(response) {
                    if (response.success) {
                        notificationItem.removeClass('unread');
                        $(e.target).remove();
                        loadNotifications();
                    }
                });
            });

            function formatDate(dateString) {
                const date = new Date(dateString);
                const now = new Date();
                const diffMs = now - date;
                const diffMins = Math.floor(diffMs / 60000);
                const diffHours = Math.floor(diffMs / 3600000);
                const diffDays = Math.floor(diffMs / 86400000);

                if (diffMins < 1) return 'Just now';
                if (diffMins < 60) return diffMins + ' min ago';
                if (diffHours < 24) return diffHours + ' hour' + (diffHours > 1 ? 's' : '') + ' ago';
                if (diffDays < 7) return diffDays + ' day' + (diffDays > 1 ? 's' : '') + ' ago';
                
                return date.toLocaleDateString();
            }
        });
    </script>
</body>
</html>
