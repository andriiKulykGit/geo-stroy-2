<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../db.php';
require_login();

$user = current_user();

$stmt = $pdo->query("SELECT * FROM reports ORDER BY created_at DESC");
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            <li class="breadcrumb-item active">Отчеты</li>
          </ol>
          <div class="card mb-4">
            <div class="card-header">
              <i class="fas fa-table me-1"></i>
              Отчеты
            </div>
            <div class="card-body">
              <?php if (count($projects) === 0): ?>
                <p class="mb-0">Нет отчетов.</p>
              <?php else: ?>
                <table id="datatablesSimple">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Название</th>
                      <th>Стадия</th>
                      <th>Cейсмика</th>
                      <th>Действия</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>ID</th>
                      <th>Название</th>
                      <th>Стадия</th>
                      <th>Cейсмика</th>
                      <th>Действия</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <?php foreach ($projects as $project): ?>
                      <tr>
                        <td><?= e($project['id']) ?></td>
                        <td><?= e($project['name']) ?></td>
                        <td><?= e($project['state']) ?></td>
                        <td><?= e($project['seismic']) ?></td>
                        <td>
                          <a href="project_edit.php?id=<?= $project['id'] ?>">Редактировать</a> |
                          <a href="project_delete.php?id=<?= $project['id'] ?>" onclick="return confirm('Удалить проект?')">Удалить</a> |
                          <a href="reports_by_project.php?project_id=<?= $project['id'] ?>">Отчеты</a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
  <?php require_once __DIR__ . '/parts/scripts.php' ?>
</body>

</html>