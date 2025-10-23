<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Courses</title>
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
        .course-card {
            transition: transform 0.2s;
            height: 100%;
        }
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
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
        <h1 class="mb-4">🔍 Available Courses</h1>

        <div id="alertContainer"></div>

        <?php if (empty($available_courses)): ?>
            <div class="card">
                <div class="card-body text-center py-5">
                    <h3 class="text-muted">✅ All Courses Enrolled</h3>
                    <p class="text-muted">You've enrolled in all available courses!</p>
                    <a href="<?= base_url('courses') ?>" class="btn btn-primary mt-3">View My Courses</a>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($available_courses as $course): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card course-card">
                            <div class="card-body">
                                <h5 class="card-title"><?= esc($course['title']) ?></h5>
                                <p class="card-text"><?= esc($course['description']) ?></p>
                                <p class="text-muted mb-3">
                                    <small>📅 Created: <?= date('M d, Y', strtotime($course['created_at'])) ?></small>
                                </p>
                                <div class="d-grid">
                                    <button 
                                        class="btn btn-success enroll-btn" 
                                        data-course-id="<?= $course['id'] ?>"
                                        data-course-title="<?= esc($course['title']) ?>">
                                        Enroll Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.enroll-btn').on('click', function() {
                const btn = $(this);
                const courseId = btn.data('course-id');
                const courseTitle = btn.data('course-title');
                
                // Disable button and show loading
                btn.prop('disabled', true);
                btn.html('<span class="spinner-border spinner-border-sm me-2"></span>Enrolling...');

                $.ajax({
                    url: '<?= base_url('student/enroll-ajax') ?>',
                    method: 'POST',
                    data: {
                        course_id: courseId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Show success message
                            showAlert('success', response.message);
                            
                            // Remove the card after a delay
                            setTimeout(function() {
                                btn.closest('.col-md-6').fadeOut(400, function() {
                                    $(this).remove();
                                    
                                    // Check if no more courses
                                    if ($('.course-card').length === 0) {
                                        location.reload();
                                    }
                                });
                            }, 1500);
                        } else {
                            showAlert('danger', response.message);
                            btn.prop('disabled', false);
                            btn.html('Enroll Now');
                        }
                    },
                    error: function() {
                        showAlert('danger', 'An error occurred. Please try again.');
                        btn.prop('disabled', false);
                        btn.html('Enroll Now');
                    }
                });
            });

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
