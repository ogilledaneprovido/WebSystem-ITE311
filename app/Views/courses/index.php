<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses</title>
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
                <a class="nav-link active" href="<?= base_url('courses') ?>">My Courses</a>
                <a class="nav-link" href="<?= base_url('student/available-courses') ?>">Available Courses</a>
                <a class="nav-link" href="<?= base_url('student/assignments') ?>">Assignments</a>
                <a class="nav-link" href="<?= base_url('student/grades') ?>">Grades</a>
                <a class="nav-link" href="<?= base_url('announcements') ?>">Announcements</a>
                <a class="nav-link" href="<?= base_url('logout') ?>">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="mb-4">📚 My Enrolled Courses</h1>

        <!-- Search Form -->
        <form id="searchForm" class="mb-4" method="get" action="<?= base_url('courses/search') ?>">
            <div class="input-group">
                <input id="searchInput" name="term" value="<?= isset($search_term) ? esc($search_term) : '' ?>" type="search" class="form-control" placeholder="Search courses by title or description..." aria-label="Search courses">
                <button id="searchBtn" class="btn btn-primary" type="submit">Search</button>
                <button id="clearBtn" class="btn btn-outline-secondary" type="button" title="Clear search">Clear</button>
            </div>
        </form>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <!-- Results container -->
        <div id="courseList" class="row">
            <?php if (!isset($courses) || empty($courses)): ?>
                <div id="noCourses" class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <h3 class="text-muted">📖 No Enrolled Courses</h3>
                            <p class="text-muted">You haven't enrolled in any courses yet.</p>
                            <a href="<?= base_url('student/available-courses') ?>" class="btn btn-primary mt-3">Browse Available Courses</a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($courses as $course): ?>
                    <div class="col-md-6 col-lg-4 mb-4 course-item">
                        <div class="card course-card">
                            <div class="card-body">
                                <h5 class="card-title course-title"><?= esc($course['title']) ?></h5>
                                <p class="card-text course-desc"><?= esc($course['description']) ?></p>
                                <div class="d-grid gap-2">
                                    <a href="<?= base_url('course/' . $course['id'] . '/materials') ?>" class="btn btn-primary">
                                        View Materials
                                    </a>
                                    <a href="<?= base_url('course/' . $course['id']) ?>" class="btn btn-outline-secondary">
                                        Course Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(function() {
            const $searchInput = $('#searchInput');
            const $courseList = $('#courseList');
            const $clearBtn = $('#clearBtn');
            let clientSideDebounce;

            // Client-side instant filtering (from already-loaded DOM items)
            $searchInput.on('input', function() {
                clearTimeout(clientSideDebounce);
                const q = $(this).val().trim().toLowerCase();

                // if there's a non-empty query, perform client-side filter immediately
                clientSideDebounce = setTimeout(function() {
                    if (q === '') {
                        // show all
                        $('.course-item').show();
                        $('#noCourses').toggle($('.course-item:visible').length === 0);
                    } else {
                        $('.course-item').each(function() {
                            const title = $(this).find('.course-title').text().toLowerCase();
                            const desc = $(this).find('.course-desc').text().toLowerCase();
                            const match = title.indexOf(q) !== -1 || desc.indexOf(q) !== -1;
                            $(this).toggle(match);
                        });
                        $('#noCourses').toggle($('.course-item:visible').length === 0);
                    }
                }, 150);
            });

            // Clear button
            $clearBtn.on('click', function() {
                $searchInput.val('');
                $searchInput.trigger('input');
            });

            // Server-side search via AJAX on form submit (prevents full page refresh)
            $('#searchForm').on('submit', function(e) {
                e.preventDefault();
                const term = $searchInput.val().trim();

                $.ajax({
                    url: '<?= base_url('courses/search') ?>',
                    type: 'GET',
                    dataType: 'json',
                    data: { term: term },
                    beforeSend() {
                        // optional: show loader
                        $courseList.html('<div class="col-12 text-center py-4"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                    },
                    success(res) {
                        if (!res || !res.success) {
                            $courseList.html('<div class="col-12"><div class="alert alert-danger">Server returned invalid response.</div></div>');
                            return;
                        }

                        const courses = res.results || [];
                        if (courses.length === 0) {
                            $courseList.html(`
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body text-center py-5">
                                            <h3 class="text-muted">🔍 No courses found</h3>
                                            <p class="text-muted">No courses match "<strong>${term}</strong>". Try a different keyword.</p>
                                        </div>
                                    </div>
                                </div>
                            `);
                            return;
                        }

                        let html = '';
                        courses.forEach(function(course) {
                            const title = $('<div>').text(course.title).html();
                            const desc = $('<div>').text(course.description).html();
                            html += `
                                <div class="col-md-6 col-lg-4 mb-4 course-item">
                                    <div class="card course-card">
                                        <div class="card-body">
                                            <h5 class="card-title course-title">${title}</h5>
                                            <p class="card-text course-desc">${desc}</p>
                                            <div class="d-grid gap-2">
                                                <a href="<?= base_url('course/') ?>${course.id}/materials" class="btn btn-primary">View Materials</a>
                                                <a href="<?= base_url('course/') ?>${course.id}" class="btn btn-outline-secondary">Course Details</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                        $courseList.html(html);
                    },
                    error() {
                        $courseList.html('<div class="col-12"><div class="alert alert-danger">Failed to fetch search results. Check your network or server logs.</div></div>');
                    }
                });
            });
        });
    </script>
</body>
</html>
