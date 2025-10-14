<?= view('templates/header') ?>

<div class="container mt-4">
    <h3>Upload Material for Course ID: <?= esc($course_id) ?></h3>

    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('message')) ?></div>
    <?php endif; ?>

    <form action="<?= base_url('/admin/course/' . $course_id . '/upload') ?>" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="material_file" class="form-label">Select File</label>
            <input type="file" name="material_file" id="material_file" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
        <a href="<?= base_url('/admin/course/' . $course_id) ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?= view('templates/footer') ?>
