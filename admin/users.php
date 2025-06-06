<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../db.php';
require_login();

$user = current_user();

$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            <li class="breadcrumb-item active">Пользователи</li>
          </ol>
          <div class="card mb-4">
            <div class="card-header">
              <i class="fas fa-table me-1"></i>
              Пользователи
            </div>
            <div class="card-body">
              <a class="btn btn-primary mb-2" href="user_create.php">Добавить пользователя</a>
              <?php if (count($users) === 0): ?>
                <p class="mb-0">Нет пользователей.</p>
              <?php else: ?>
                <table id="datatablesSimple">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Имя</th>
                      <th>Email</th>
                      <th>Роль</th>
                      <th>Действия</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>ID</th>
                      <th>Имя</th>
                      <th>Email</th>
                      <th>Роль</th>
                      <th>Действия</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <?php foreach ($users as $user): ?>
                      <tr>
                        <td><?= e($user['id']) ?></td>
                        <td><?= e($user['name']) ?></td>
                        <td><?= e($user['email']) ?></td>
                        <td><?= e($user['role']) ?></td>
                        <td>
                          <a href="user_edit.php?id=<?= $user['id'] ?>">Редактировать</a> |
                          <a href="user_delete.php?id=<?= $user['id'] ?>" onclick="return confirm('Удалить проект?')">Удалить</a> |
                          <a href="reports_by_user.php?user_id=<?= $user['id'] ?>">Отчеты</a>
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