<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Course - Admin</title>
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
            <a class="navbar-brand" href="<?= base_url('admin/dashboard') ?>">ITE311</a>
            
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
                <a class="nav-link" href="<?= base_url('admin/users') ?>">Manage Users</a>
                <a class="nav-link" href="<?= base_url('admin/courses') ?>">Manage Courses</a>
                <a class="nav-link" href="<?= base_url('admin/announcements') ?>">Announcements</a>
                
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

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h4>Create New Course</h4>
                    </div>
                    <div class="card-body">
                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= esc(session()->getFlashdata('error')) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form action="<?= base_url('admin/courses/create') ?>" method="post">
                            <?= csrf_field() ?>
                            
                            <div class="mb-3">
                                <label for="title" class="form-label">Course Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" required placeholder="Enter course title">
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Course Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="description" name="description" rows="5" required placeholder="Enter course description"></textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="<?= base_url('admin/courses') ?>" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Create Course</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Load notifications
        function loadNotifications() {
            fetch('<?= base_url('notifications/list') ?>')
                .then(response => response.json())
                .then(data => {
                    const notificationList = document.getElementById('notificationList');
                    const notificationBadge = document.getElementById('notificationBadge');
                    
                    notificationList.innerHTML = '<li class="dropdown-header">Notifications</li><li><hr class="dropdown-divider"></li>';
                    
                    if (data.notifications && data.notifications.length > 0) {
                        const unreadCount = data.notifications.filter(n => n.is_read == 0).length;
                        if (unreadCount > 0) {
                            notificationBadge.textContent = unreadCount;
                            notificationBadge.style.display = 'block';
                        } else {
                            notificationBadge.style.display = 'none';
                        }
                        
                        data.notifications.forEach(notification => {
                            const li = document.createElement('li');
                            li.className = 'notification-item' + (notification.is_read == 0 ? ' unread' : '');
                            li.innerHTML = `
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>${notification.title}</strong>
                                        <p class="mb-1 small">${notification.message}</p>
                                        <small class="text-muted">${new Date(notification.created_at).toLocaleDateString()}</small>
                                    </div>
                                </div>
                            `;
                            li.onclick = () => markAsRead(notification.id);
                            notificationList.appendChild(li);
                        });
                        
                        const viewAllLi = document.createElement('li');
                        viewAllLi.innerHTML = '<hr class="dropdown-divider"><li class="text-center p-2"><a href="<?= base_url('admin/notifications') ?>" class="btn btn-sm btn-primary">View All</a></li>';
                        notificationList.appendChild(viewAllLi);
                    } else {
                        notificationBadge.style.display = 'none';
                        const li = document.createElement('li');
                        li.className = 'text-center p-3';
                        li.innerHTML = '<span class="text-muted">No notifications</span>';
                        notificationList.appendChild(li);
                    }
                })
                .catch(error => console.error('Error loading notifications:', error));
        }

        function markAsRead(notificationId) {
            fetch('<?= base_url('notifications/mark-read/') ?>' + notificationId, {
                method: 'POST',
                headers: {'X-Requested-With': 'XMLHttpRequest'}
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) loadNotifications();
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadNotifications();
            setInterval(loadNotifications, 30000);
        });
    </script>
</body>
</html>
