<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../db.php';
require_login();

$user = current_user();

$author_id = isset($_GET['author_id']) ? $_GET['author_id'] : '';
$project_id = isset($_GET['project_id']) ? $_GET['project_id'] : '';
$state = isset($_GET['state']) ? $_GET['state'] : '';

$query = "SELECT * FROM reports WHERE 1=1";
$params = [];

if (!empty($author_id)) {
    $query .= " AND user_id = :author_id";
    $params[':author_id'] = $author_id;
}

if (!empty($project_id)) {
    $query .= " AND project_id = :project_id";
    $params[':project_id'] = $project_id;
}

if (!empty($state)) {
    $query .= " AND state = :state";
    $params[':state'] = $state;
}

$query .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

$authorsQuery = $pdo->query("SELECT DISTINCT u.id, u.name FROM users u JOIN reports r ON u.id = r.user_id ORDER BY u.name");
$authors = $authorsQuery->fetchAll(PDO::FETCH_ASSOC);

$projectsQuery = $pdo->query("SELECT DISTINCT p.id, p.name FROM projects p JOIN reports r ON p.id = r.project_id ORDER BY p.name");
$projects = $projectsQuery->fetchAll(PDO::FETCH_ASSOC);

$statesQuery = $pdo->query("SELECT DISTINCT state FROM reports WHERE state IS NOT NULL AND state != '' ORDER BY state");
$states = $statesQuery->fetchAll(PDO::FETCH_COLUMN);
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
              <!-- Добавляем блок с фильтрами -->
              <div class="row mb-4">
                <div class="col-12">
                  <div class="card">
                    <div class="card-header">
                      <i class="fas fa-filter me-1"></i>
                      Фильтры
                    </div>
                    <div class="card-body">
                      <form method="GET" action="reports.php" class="row g-3">
                        <div class="col-md-4">
                          <label for="author_id" class="form-label">Автор</label>
                          <select class="form-select" id="author_id" name="author_id">
                            <option value="">Все авторы</option>
                            <?php foreach ($authors as $authorOption): ?>
                              <option value="<?= e($authorOption['id']) ?>" <?= $author_id == $authorOption['id'] ? 'selected' : '' ?>>
                                <?= e($authorOption['name']) ?>
                              </option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                        <div class="col-md-4">
                          <label for="project_id" class="form-label">Название проекта</label>
                          <select class="form-select" id="project_id" name="project_id">
                            <option value="">Все проекты</option>
                            <?php foreach ($projects as $projectOption): ?>
                              <option value="<?= e($projectOption['id']) ?>" <?= $project_id == $projectOption['id'] ? 'selected' : '' ?>>
                                <?= e($projectOption['name']) ?>
                              </option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                        <div class="col-md-4">
                          <label for="state" class="form-label">Стадия</label>
                          <select class="form-select" id="state" name="state">
                            <option value="">Все стадии</option>
                            <?php foreach ($states as $stateOption): ?>
                              <option value="<?= e($stateOption) ?>" <?= $state === $stateOption ? 'selected' : '' ?>>
                                <?= e($stateOption) ?>
                              </option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                        <div class="col-md-12 d-flex align-items-end">
                          <button type="submit" class="btn btn-primary me-2">Применить фильтры</button>
                          <a href="reports.php" class="btn btn-secondary">Сбросить</a>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Конец блока с фильтрами -->

              <?php if (count($reports) === 0): ?>
                <p class="mb-0">Нет отчетов.</p>
              <?php else: ?>
                <table id="datatablesSimple">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Автор</th>
                      <th>Название проекта</th>
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
                      <th>Автор</th>
                      <th>Название проекта</th>
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
                            $stmt = $pdo->prepare("SELECT name FROM users WHERE id = ?");
                            $stmt->execute([$r['user_id']]);
                            $user = $stmt->fetch();
                            echo e($user['name'] ?? '-');
                          ?>
                        </td>
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
