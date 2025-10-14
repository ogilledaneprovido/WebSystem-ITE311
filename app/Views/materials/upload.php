<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Course Material</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?= view('templates/header') ?>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Upload Course Material</h4>
                    </div>
                    <div class="card-body">
                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success">
                                ✅ <?= esc(session()->getFlashdata('success')) ?>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger">
                                ❌ <?= esc(session()->getFlashdata('error')) ?>
                            </div>
                        <?php endif; ?>

                        <form action="<?= current_url() ?>" method="POST" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            
                            <div class="mb-3">
                                <label for="material_file" class="form-label">Select File</label>
                                <input type="file" 
                                       class="form-control" 
                                       id="material_file" 
                                       name="material_file" 
                                       required>
                                <div class="form-text">
                                    Allowed: PDF, DOC, DOCX, PPT, PPTX, TXT (Max: 10MB)
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">📤 Upload Material</button>
                                <a href="<?= base_url('/admin/courses') ?>" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                        
                        <!-- Debug info -->
                        <div class="mt-3 small text-muted">
                            Course ID: <?= $course_id ?><br>
                            Form Action: <?= current_url() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?= view('templates/footer') ?>
</body>
</html>
