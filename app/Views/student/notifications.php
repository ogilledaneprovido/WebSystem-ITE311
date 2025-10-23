<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
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
        .notification-item {
            transition: transform 0.2s;
            cursor: pointer;
        }
        .notification-item:hover {
            transform: translateX(5px);
            background-color: #f8f9fa;
        }
        .notification-item.unread {
            background-color: #e7f3ff;
            border-left: 4px solid #4285f4;
        }
        .notification-item.read {
            opacity: 0.7;
        }
        .notification-icon {
            font-size: 24px;
            margin-right: 15px;
        }
        .notification-badge {
            font-size: 12px;
            padding: 2px 8px;
        }
    </style>
</head>
<body>
    <!-- Uniform Navbar -->
    <nav class="navbar navbar-expand-lg custom-navbar">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="<?= base_url('student/dashboard') ?>">ITE311</a>
            
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="<?= base_url('student/dashboard') ?>">Dashboard</a>
                <a class="nav-link" href="<?= base_url('courses') ?>">My Courses</a>
                <a class="nav-link" href="<?= base_url('student/available-courses') ?>">Available Courses</a>
                <a class="nav-link" href="<?= base_url('student/assignments') ?>">Assignments</a>
                <a class="nav-link" href="<?= base_url('student/grades') ?>">Grades</a>
                <a class="nav-link" href="<?= base_url('announcements') ?>">Announcements</a>
                <a class="nav-link" href="<?= base_url('logout') ?>">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>🔔 Notifications</h1>
            <?php if ($unread_count > 0): ?>
                <button class="btn btn-primary" id="markAllReadBtn">
                    Mark All as Read (<?= $unread_count ?>)
                </button>
            <?php endif; ?>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div id="alertContainer"></div>

        <?php if (empty($notifications)): ?>
            <div class="card">
                <div class="card-body text-center py-5">
                    <h3 class="text-muted">📭 No Notifications</h3>
                    <p class="text-muted">You don't have any notifications yet.</p>
                    <a href="<?= base_url('student/dashboard') ?>" class="btn btn-primary mt-3">Back to Dashboard</a>
                </div>
            </div>
        <?php else: ?>
            <div class="card">
                <div class="list-group list-group-flush" id="notificationsList">
                    <?php foreach ($notifications as $notification): ?>
                        <div class="list-group-item notification-item <?= $notification['is_read'] ? 'read' : 'unread' ?>" 
                             data-notification-id="<?= $notification['id'] ?>"
                             data-is-read="<?= $notification['is_read'] ?>">
                            <div class="d-flex w-100 align-items-start">
                                <span class="notification-icon">
                                    <?php if (!$notification['is_read']): ?>
                                        🔵
                                    <?php else: ?>
                                        ✅
                                    <?php endif; ?>
                                </span>
                                <div class="flex-grow-1">
                                    <div class="d-flex w-100 justify-content-between">
                                        <p class="mb-1 fw-bold">
                                            <?= esc($notification['message']) ?>
                                            <?php if (!$notification['is_read']): ?>
                                                <span class="badge bg-primary notification-badge">New</span>
                                            <?php endif; ?>
                                        </p>
                                        <small class="text-muted">
                                            <?= date('M d, Y h:i A', strtotime($notification['created_at'])) ?>
                                        </small>
                                    </div>
                                    <?php if (!$notification['is_read']): ?>
                                        <button class="btn btn-sm btn-outline-primary mark-read-btn" 
                                                data-notification-id="<?= $notification['id'] ?>">
                                            Mark as Read
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Pagination Info -->
            <div class="mt-3 text-center text-muted">
                <small>Showing <?= count($notifications) ?> notification<?= count($notifications) != 1 ? 's' : '' ?></small>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Mark single notification as read
            $('.mark-read-btn').on('click', function(e) {
                e.stopPropagation();
                const notificationId = $(this).data('notification-id');
                markAsRead(notificationId);
            });

            // Mark all notifications as read
            $('#markAllReadBtn').on('click', function() {
                markAllAsRead();
            });

            function markAsRead(notificationId) {
                $.ajax({
                    url: '<?= base_url('notifications/mark_read/') ?>' + notificationId,
                    method: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Update the UI
                            const item = $('[data-notification-id="' + notificationId + '"]');
                            item.removeClass('unread').addClass('read');
                            item.find('.notification-icon').html('✅');
                            item.find('.badge').remove();
                            item.find('.mark-read-btn').remove();
                            
                            showAlert('success', 'Notification marked as read');
                            
                            // Update unread count
                            updateUnreadCount();
                        } else {
                            showAlert('danger', response.message);
                        }
                    },
                    error: function() {
                        showAlert('danger', 'Failed to update notification');
                    }
                });
            }

            function markAllAsRead() {
                $.ajax({
                    url: '<?= base_url('notifications/mark_all_read') ?>',
                    method: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Update all notifications in the UI
                            $('.notification-item.unread').each(function() {
                                $(this).removeClass('unread').addClass('read');
                                $(this).find('.notification-icon').html('✅');
                                $(this).find('.badge').remove();
                                $(this).find('.mark-read-btn').remove();
                            });
                            
                            $('#markAllReadBtn').remove();
                            showAlert('success', 'All notifications marked as read');
                        } else {
                            showAlert('danger', response.message);
                        }
                    },
                    error: function() {
                        showAlert('danger', 'Failed to update notifications');
                    }
                });
            }

            function updateUnreadCount() {
                const unreadCount = $('.notification-item.unread').length;
                if (unreadCount === 0) {
                    $('#markAllReadBtn').remove();
                } else {
                    $('#markAllReadBtn').text('Mark All as Read (' + unreadCount + ')');
                }
            }

            function showAlert(type, message) {
                const alertHtml = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                $('#alertContainer').html(alertHtml);
                
                // Auto dismiss after 5 seconds
                setTimeout(function() {
                    $('.alert').fadeOut();
                }, 5000);
            }
        });
    </script>
</body>
</html>
