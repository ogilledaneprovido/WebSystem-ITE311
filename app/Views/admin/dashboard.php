<?= view('templates/header') ?>

<div class="container mt-4">
    <h2>Admin Dashboard</h2>
    
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h4><?= $total_courses ?></h4>
                    <p>Total Courses</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h4><?= $total_students ?></h4>
                    <p>Total Students</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h4><?= $total_materials ?></h4>
                    <p>Total Materials</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Courses -->
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5>Recent Courses</h5>
            <a href="<?= base_url('/admin/courses') ?>" class="btn btn-primary">View All Courses</a>
        </div>
        <div class="card-body">
            <?php if (empty($recent_courses)): ?>
                <p>No courses available.</p>
            <?php else: ?>
                <div class="list-group">
                    <?php foreach ($recent_courses as $course): ?>
                        <div class="list-group-item d-flex justify-content-between">
                            <div>
                                <h6><?= esc($course['title']) ?></h6>
                                <small><?= esc($course['description']) ?></small>
                            </div>
                            <div>
                                <a href="<?= base_url('/admin/course/' . $course['id']) ?>" class="btn btn-sm btn-outline-primary">View</a>
                                <a href="<?= base_url('/admin/course/' . $course['id'] . '/upload') ?>" class="btn btn-sm btn-outline-success">Upload Material</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= view('templates/footer') ?>
