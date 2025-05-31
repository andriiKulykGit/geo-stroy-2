<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../db.php';
require_login();

$project_id = $_GET['project_id'];

$stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
$stmt->execute([$project_id]);
$project = $stmt->fetch(PDO::FETCH_ASSOC);
$project_name = $project['name'];

$stmt = $pdo->prepare("SELECT * FROM reports WHERE project_id = ?");
$stmt->execute([$project_id]);
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require_once __DIR__ . '/parts/head.php'; ?>

<body class="sb-nav-fixed">
  <?php require_once __DIR__ . '/parts/header.php' ?>
  <div id="layoutSidenav">
    <?php require_once __DIR__ . '/parts/sideNav.php' ?>
    <div id="layoutSidenav_content">
      <main>
        <div class="container-fluid px-4">
          <ol class="breadcrumb mb-4 mt-2">
            <li class="breadcrumb-item"><a href="index.php">Главная</a></li>
            <li class="breadcrumb-item"><a href="projects.php">Проекты</a></li>
            <li class="breadcrumb-item active"><?= $project_name ?></li>
          </ol>
          <div class="card mb-4">
            <div class="card-header">
              <i class="fas fa-table me-1"></i>
              Отчеты по проекту <?= $project_name ?>
            </div>
            <div class="card-body">
              <table id="datatablesSimple">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Комментарий</th>
                    <th>Стадия</th>
                    <th>Файлы</th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th>ID</th>
                    <th>Комментарий</th>
                    <th>Стадия</th>
                    <th>Файлы</th>
                  </tr>
                </tfoot>
                <tbody>
                  <?php foreach ($reports as $report): ?>
                    <tr>
                      <td><?= e($report['id']) ?></td>
                      <td><?= e($report['comment']) ?></td>
                      <td><?= e($report['state']) ?></td>
                      <td>
                        <?php foreach (json_decode($report['files'] ?? '[]') as $file): ?>
                          <a href="/uploads/<?= e($file) ?>" target="_blank"><?= e($file) ?></a><br>
                        <?php endforeach; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
  <?php require_once __DIR__ . '/parts/scripts.php' ?>
</body>

</html>