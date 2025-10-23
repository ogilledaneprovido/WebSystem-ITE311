<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin</title>
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
        .badge-role {
            font-size: 12px;
            padding: 5px 10px;
        }
        .stats-card {
            border-left: 4px solid;
            margin-bottom: 20px;
        }
        .stats-card.students {
            border-left-color: #28a745;
        }
        .stats-card.teachers {
            border-left-color: #17a2b8;
        }
        .stats-card.admins {
            border-left-color: #dc3545;
        }
        .stats-card.total {
            border-left-color: #4285f4;
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
        <h2 class="mb-4">Manage Users</h2>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= esc(session()->getFlashdata('success')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= esc(session()->getFlashdata('error')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card stats-card total">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <h2 class="text-primary"><?= $total_users ?></h2>
                        <a href="<?= base_url('admin/users') ?>" class="btn btn-sm btn-primary">View All</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card students">
                    <div class="card-body">
                        <h5 class="card-title">Students</h5>
                        <h2 class="text-success"><?= $total_students ?></h2>
                        <a href="<?= base_url('admin/users?role=student') ?>" class="btn btn-sm btn-success">View Students</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card teachers">
                    <div class="card-body">
                        <h5 class="card-title">Teachers</h5>
                        <h2 class="text-info"><?= $total_teachers ?></h2>
                        <a href="<?= base_url('admin/users?role=teacher') ?>" class="btn btn-sm btn-info">View Teachers</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card admins">
                    <div class="card-body">
                        <h5 class="card-title">Admins</h5>
                        <h2 class="text-danger"><?= $total_admins ?></h2>
                        <a href="<?= base_url('admin/users?role=admin') ?>" class="btn btn-sm btn-danger">View Admins</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- User List -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <?php if ($filter_role != 'all'): ?>
                        <?= ucfirst($filter_role) ?>s List
                    <?php else: ?>
                        All Users
                    <?php endif; ?>
                </h5>
                <?php if ($filter_role != 'all'): ?>
                    <a href="<?= base_url('admin/users') ?>" class="btn btn-sm btn-secondary">Show All Users</a>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <?php if (empty($users)): ?>
                    <p class="text-muted text-center py-4">No users found.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Joined Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?= $user['id'] ?></td>
                                        <td><?= esc($user['name']) ?></td>
                                        <td><?= esc($user['email']) ?></td>
                                        <td>
                                            <?php
                                            $badgeClass = 'secondary';
                                            if ($user['role'] == 'admin') $badgeClass = 'danger';
                                            elseif ($user['role'] == 'teacher') $badgeClass = 'info';
                                            elseif ($user['role'] == 'student') $badgeClass = 'success';
                                            ?>
                                            <span class="badge bg-<?= $badgeClass ?> badge-role"><?= ucfirst($user['role']) ?></span>
                                        </td>
                                        <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                                        <td>
                                            <?php if ($user['id'] != session()->get('id')): ?>
                                                <!-- Change Role Button -->
                                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#roleModal<?= $user['id'] ?>">
                                                    Change Role
                                                </button>
                                                <!-- Delete Button -->
                                                <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $user['id'] ?>, '<?= esc($user['name']) ?>')">
                                                    Delete
                                                </button>

                                                <!-- Role Change Modal -->
                                                <div class="modal fade" id="roleModal<?= $user['id'] ?>" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Change Role for <?= esc($user['name']) ?></h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <form action="<?= base_url('admin/user/' . $user['id'] . '/update-role') ?>" method="post">
                                                                <?= csrf_field() ?>
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Select New Role:</label>
                                                                        <select name="role" class="form-select" required>
                                                                            <option value="student" <?= $user['role'] == 'student' ? 'selected' : '' ?>>Student</option>
                                                                            <option value="teacher" <?= $user['role'] == 'teacher' ? 'selected' : '' ?>>Teacher</option>
                                                                            <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-primary">Update Role</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Current User</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete(userId, userName) {
            if (confirm('Are you sure you want to delete user "' + userName + '"? This action cannot be undone.')) {
                window.location.href = '<?= base_url('admin/user/') ?>' + userId + '/delete';
            }
        }

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
