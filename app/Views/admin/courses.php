<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses - Admin</title>
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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Courses</h2>
        <a href="<?= base_url('admin/courses/create') ?>" class="btn btn-primary">Add New Course</a>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>

            <?php if (empty($courses)): ?>
                <p class="text-muted">No courses available.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Course Title</th>
                                <th>Description</th>
                                <th>Created Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($courses as $course): ?>
                                <tr>
                                    <td><?= $course['id'] ?></td>
                                    <td><?= esc($course['title']) ?></td>
                                    <td><?= esc(substr($course['description'], 0, 50)) ?>...</td>
                                    <td><?= date('M d, Y', strtotime($course['created_at'])) ?></td>
                                    <td>
                                        <a href="<?= base_url('/admin/course/' . $course['id']) ?>" class="btn btn-sm btn-info">View</a>
                                        <a href="<?= base_url('/admin/course/' . $course['id'] . '/upload') ?>" class="btn btn-sm btn-success">Upload Material</a>
                                        <a href="<?= base_url('/course/' . $course['id'] . '/materials') ?>" class="btn btn-sm btn-warning">Materials</a>
                                        <button class="btn btn-sm btn-danger" onclick="confirmDeleteCourse(<?= $course['id'] ?>, '<?= esc($course['title']) ?>')">Delete</button>
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
// Confirm course deletion
function confirmDeleteCourse(courseId, courseTitle) {
    if (confirm('Are you sure you want to delete the course "' + courseTitle + '"? This action cannot be undone and will also delete all associated materials.')) {
        window.location.href = '<?= base_url('admin/course/') ?>' + courseId + '/delete';
    }
}

// Load notifications
function loadNotifications() {
    fetch('<?= base_url('notifications/list') ?>')
        .then(response => response.json())
        .then(data => {
            const notificationList = document.getElementById('notificationList');
            const notificationBadge = document.getElementById('notificationBadge');
            
            // Clear loading state
            notificationList.innerHTML = '<li class="dropdown-header">Notifications</li><li><hr class="dropdown-divider"></li>';
            
            if (data.notifications && data.notifications.length > 0) {
                // Update badge count
                const unreadCount = data.notifications.filter(n => n.is_read == 0).length;
                if (unreadCount > 0) {
                    notificationBadge.textContent = unreadCount;
                    notificationBadge.style.display = 'block';
                } else {
                    notificationBadge.style.display = 'none';
                }
                
                // Add notifications
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
                
                // Add "View All" link
                const viewAllLi = document.createElement('li');
                viewAllLi.innerHTML = '<hr class="dropdown-divider"><li class="text-center p-2"><a href="<?= base_url('student/notifications') ?>" class="btn btn-sm btn-primary">View All</a></li>';
                notificationList.appendChild(viewAllLi);
            } else {
                notificationBadge.style.display = 'none';
                const li = document.createElement('li');
                li.className = 'text-center p-3';
                li.innerHTML = '<span class="text-muted">No notifications</span>';
                notificationList.appendChild(li);
            }
        })
        .catch(error => {
            console.error('Error loading notifications:', error);
        });
}

function markAsRead(notificationId) {
    fetch('<?= base_url('notifications/mark-read/') ?>' + notificationId, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadNotifications();
        }
    });
}

// Load notifications on page load
document.addEventListener('DOMContentLoaded', function() {
    loadNotifications();
    // Refresh notifications every 30 seconds
    setInterval(loadNotifications, 30000);
});
</script>
</body>
</html>
