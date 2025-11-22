<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Course Materials</title>
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
<!-- Uniform Navbar (copied from admin_dashboard.php) -->
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
<!-- ...existing code for upload form... -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Load notifications on page load
        loadNotifications();

        // Refresh notifications every 30 seconds
        setInterval(loadNotifications, 30000);

        // Load notifications function
        function loadNotifications() {
            $.get('<?= base_url('notifications') ?>', function(response) {
                if (response.success) {
                    updateNotificationBadge(response.unread_count);
                    updateNotificationList(response.notifications);
                }
            }).fail(function() {
                console.error('Failed to load notifications');
            });
        }

        // Update badge count
        function updateNotificationBadge(count) {
            const badge = $('#notificationBadge');
            if (count > 0) {
                badge.text(count).show();
            } else {
                badge.hide();
            }
        }

        // Update notification list
        function updateNotificationList(notifications) {
            const list = $('#notificationList');
            list.empty();
            
            // Add header
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

                // Add "View All" link
                list.append('<li><hr class="dropdown-divider"></li>');
                list.append(`<li class="text-center p-2"><a href="<?= base_url('admin/notifications') ?>" class="text-decoration-none">View All Notifications</a></li>`);
            }
        }

        // Mark notification as read
        $(document).on('click', '.mark-read', function(e) {
            e.stopPropagation();
            const notificationId = $(this).data('id');
            const notificationItem = $(this).closest('.notification-item');

            $.post('<?= base_url('notifications/mark_read/') ?>' + notificationId, function(response) {
                if (response.success) {
                    notificationItem.removeClass('unread');
                    $(e.target).remove();
                    loadNotifications(); // Reload to update badge count
                }
            }).fail(function() {
                alert('Failed to mark notification as read');
            });
        });

        // Format date
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
<!-- ...existing code... -->
</body>
</html>
