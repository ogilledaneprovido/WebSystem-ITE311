<?= view('templates/header') ?>

<div class="container mt-4">
    <h2>Course Materials</h2>
    
    <div class="card">
        <div class="card-body">
            <?php if (empty($materials)): ?>
                <p class="text-muted">No materials available for this course.</p>
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
                                    <td><?= esc($material['file_name']) ?></td>
                                    <td><?= date('M d, Y', strtotime($material['created_at'])) ?></td>
                                    <td>
                                        <a href="<?= base_url('/materials/download/' . $material['id']) ?>" 
                                           class="btn btn-sm btn-success">Download</a>
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
