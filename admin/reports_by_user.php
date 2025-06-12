<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../db.php';
require_login();

$user_id = $_GET['user_id'];

$stmt = $pdo->prepare("SELECT * FROM reports WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$user_name = $user['name'] ?? '';
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
            <li class="breadcrumb-item"><a href="users.php">Пользователи</a></li>
            <li class="breadcrumb-item active"><?= 'Отчеты от ' . $user_name  ?></li>
          </ol>
          <div class="card mb-4">
            <div class="card-header">
              <i class="fas fa-table me-1"></i>
              Отчеты пользователя <?= e($user_name) ?>
            </div>
            <div class="card-body">
              <?php if (empty($reports)): ?>
                <p>Этот пользователь не создавал еще отчетов.</p>
              <?php else: ?>
                <table id="datatablesSimple">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Проект</th>
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
                      <th>Проект</th>
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
                        <td>
                          <?php
                          $stmt = $pdo->prepare("SELECT name FROM projects WHERE id = ?");
                          $stmt->execute([$r['project_id']]);
                          $project = $stmt->fetch();
                          echo e($project['name'] ?? '-');
                          ?>
                        </td>
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
