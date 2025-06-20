<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../db.php';
require_login();

$user = current_user();

$stage = isset($_GET['stage']) ? $_GET['stage'] : '';
$seismic = isset($_GET['seismic']) ? $_GET['seismic'] : '';

$start_year = isset($_GET['start_year']) ? $_GET['start_year'] : '';
$end_year = isset($_GET['end_year']) ? $_GET['end_year'] : '';

$query = "SELECT * FROM projects WHERE 1=1";
$params = [];

if (!empty($stage)) {
    $query .= " AND state = :stage";
    $params[':stage'] = $stage;
}

if (!empty($seismic)) {
    $query .= " AND seismic = :seismic";
    $params[':seismic'] = $seismic;
}

if (!empty($start_year)) {
    $query .= " AND start_year = :start_year";
    $params[':start_year'] = $start_year;
}

if (!empty($end_year)) {
    $query .= " AND end_year = :end_year";
    $params[':end_year'] = $end_year;
}

$query .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stagesQuery = $pdo->query("SELECT DISTINCT state FROM projects WHERE state IS NOT NULL AND state != '' ORDER BY state");
$stages = $stagesQuery->fetchAll(PDO::FETCH_COLUMN);

$seismicQuery = $pdo->query("SELECT DISTINCT seismic FROM projects WHERE seismic IS NOT NULL AND seismic != '' ORDER BY seismic");
$seismics = $seismicQuery->fetchAll(PDO::FETCH_COLUMN);

$startYearsQuery = $pdo->query("SELECT DISTINCT start_year FROM projects WHERE start_year IS NOT NULL AND start_year != '' ORDER BY start_year");
$startYears = $startYearsQuery->fetchAll(PDO::FETCH_COLUMN);

$endYearsQuery = $pdo->query("SELECT DISTINCT end_year FROM projects WHERE end_year IS NOT NULL AND end_year != '' ORDER BY end_year");
$endYears = $endYearsQuery->fetchAll(PDO::FETCH_COLUMN);
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
            <li class="breadcrumb-item active">Проекты</li>
          </ol>
          <div class="card mb-4">
            <div class="card-header">
              <i class="fas fa-table me-1"></i>
              Проекты
            </div>
            <div class="card-body">
              <div class="row mb-4">
                <div class="col-12">
                  <div class="card">
                    <div class="card-header">
                      <i class="fas fa-filter me-1"></i>
                      Фильтры
                    </div>
                    <div class="card-body">
                      <form method="GET" action="projects.php" class="row g-3">
                        <div class="col-md-3">
                          <label for="stage" class="form-label">Стадия</label>
                          <select class="form-select" id="stage" name="stage">
                            <option value="">Все стадии</option>
                            <?php foreach ($stages as $stageOption): ?>
                              <option value="<?= e($stageOption) ?>" <?= $stage === $stageOption ? 'selected' : '' ?>>
                                <?= e($stageOption) ?>
                              </option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                        <div class="col-md-3">
                          <label for="seismic" class="form-label">Сейсмика</label>
                          <select class="form-select" id="seismic" name="seismic">
                            <option value="">Все</option>
                            <?php foreach ($seismics as $seismicOption): ?>
                              <option value="<?= e($seismicOption) ?>" <?= $seismic === $seismicOption ? 'selected' : '' ?>>
                                <?= e($seismicOption) ?>
                              </option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                        <div class="col-md-3">
                          <label for="start_year" class="form-label">Год начала</label>
                          <select class="form-select" id="start_year" name="start_year">
                            <option value="">Все годы</option>
                            <?php foreach ($startYears as $yearOption): ?>
                              <option value="<?= e($yearOption) ?>" <?= $start_year == $yearOption ? 'selected' : '' ?>>
                                <?= e($yearOption) ?>
                              </option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                        <div class="col-md-3">
                          <label for="end_year" class="form-label">Год окончания</label>
                          <select class="form-select" id="end_year" name="end_year">
                            <option value="">Все годы</option>
                            <?php foreach ($endYears as $yearOption): ?>
                              <option value="<?= e($yearOption) ?>" <?= $end_year == $yearOption ? 'selected' : '' ?>>
                                <?= e($yearOption) ?>
                              </option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                        <div class="col-md-12 d-flex align-items-end">
                          <button type="submit" class="btn btn-primary me-2">Применить фильтры</button>
                          <a href="projects.php" class="btn btn-secondary">Сбросить</a>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <a class="btn btn-primary mb-2" href="project_create.php">Создать проект</a>
              <?php if (count($projects) === 0): ?>
                <p class="mb-0">Нет проектов.</p>
              <?php else: ?>
                <table id="datatablesSimple">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Название</th>
                      <th>Стадия</th>
                      <th>Cейсмика</th>
                      <th>Начало</th>
                      <th>Конец</th>
                      <th>Действия</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>ID</th>
                      <th>Название</th>
                      <th>Стадия</th>
                      <th>Cейсмика</th>
                      <th>Начало</th>
                      <th>Конец</th>
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
                        <td><?= e($project['start_year']) ?></td>
                        <td><?= e($project['end_year']) ?></td>
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
