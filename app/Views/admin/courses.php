<?= view('templates/header') ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Courses</h2>
        <a href="#" class="btn btn-primary">Add New Course</a>
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

<?= view('templates/footer') ?>
