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
                    <th>Стадия</th>
                    <th>Комментарий</th>
                    <th>Файлы</th>
                    <th>Дата публикации</th>
                    <th>Действия</th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th>ID</th>
                    <th>Стадия</th>
                    <th>Комментарий</th>
                    <th>Файлы</th>
                    <th>Дата публикации</th>
                    <th>Действия</th>
                  </tr>
                </tfoot>
                <tbody>
                  <?php foreach ($reports as $r): ?>
                    <tr>
                    <td><?= e($r['id']) ?></td>
                        <td><?= e($r['state']) ?></td>
                        <td><?= e($r['comment']) ?></td>
                        <td>
                          <?php
                            $files = json_decode($r['files']);

                            if ($files) {
                              foreach ($files as $f) {
                                echo '<a href="/uploads/' . $f . '" target="_blank">' . $f . '</a><br>';
                              }
                            } else {
                              echo '-';
                            }
                            ?>
                        </td>
                        <td><?= e($r['created_at']) ?></td>
                        <td>
                          <a href="report_delete.php?id=<?= $r['id'] ?>" onclick="return confirm('Удалить отчет?')">Удалить</a>
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
