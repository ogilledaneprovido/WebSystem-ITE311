<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">

<div class="container">
  <h2 class="mb-4">Available Courses</h2>

  <?php if (!empty($courses)): ?>
    <div class="row">
      <?php foreach ($courses as $course): ?>
        <div class="col-md-4">
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="card-title"><?= esc($course['title']) ?></h5>
              <p class="card-text"><?= esc($course['description']) ?></p>
              <form method="post" action="<?= base_url('course/enroll') ?>">
                <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                <button type="submit" class="btn btn-primary">Enroll</button>
              </form>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p>No available courses.</p>
  <?php endif; ?>
</div>

</body>
</html>
