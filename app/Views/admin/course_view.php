<?= view('templates/header') ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><?= esc($course['title']) ?></h2>
        <div>
            <a href="<?= base_url('/admin/course/' . $course['id'] . '/upload') ?>" class="btn btn-success">Upload Material</a>
            <a href="<?= base_url('/admin/courses') ?>" class="btn btn-secondary">Back to Courses</a>
        </div>
    </div>

    <!-- Course Details -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Course Details</h5>
        </div>
        <div class="card-body">
            <p><strong>Description:</strong> <?= esc($course['description']) ?></p>
            <p><strong>Created:</strong> <?= date('M d, Y', strtotime($course['created_at'])) ?></p>
        </div>
    </div>

    <!-- Course Materials -->
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5>Course Materials</h5>
            <span class="badge bg-primary"><?= count($materials) ?> Materials</span>
        </div>
        <div class="card-body">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>

            <?php if (empty($materials)): ?>
                <p class="text-muted">No materials uploaded for this course yet.</p>
                <a href="<?= base_url('/admin/course/' . $course['id'] . '/upload') ?>" class="btn btn-primary">Upload First Material</a>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>File Name</th>
                                <th>Upload Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($materials as $material): ?>
                                <tr>
                                    <td>
                                        <i class="fas fa-file"></i>
                                        <?= esc($material['file_name']) ?>
                                    </td>
                                    <td><?= date('M d, Y H:i', strtotime($material['created_at'])) ?></td>
                                    <td>
                                        <a href="<?= base_url('/materials/download/' . $material['id']) ?>" class="btn btn-sm btn-success">Download</a>
                                        <a href="<?= base_url('/materials/delete/' . $material['id']) ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Are you sure you want to delete this material?')">Delete</a>
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

<!-- Success/Error Toast Notifications -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-check-circle me-2"></i>
                <span id="successMessage"></span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if (session()->getFlashdata('success')): ?>
        showSuccessToast('<?= addslashes(session()->getFlashdata('success')) ?>');
    <?php endif; ?>
});

function showSuccessToast(message) {
    document.getElementById('successMessage').textContent = message;
    const successToast = new bootstrap.Toast(document.getElementById('successToast'));
    successToast.show();
}
</script>

<?= view('templates/footer') ?>
