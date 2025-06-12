<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../db.php';
require_login();

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
$stmt->execute([$id]);
$project = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT id, name, email FROM users WHERE role = 'user' ORDER BY name");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT user_id FROM project_users WHERE project_id = ?");
$stmt->execute([$id]);
$assignedUsers = $stmt->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $state = $_POST['state'];
  $seismic = $_POST['seismic'];
  $startYear = $_POST['start_year'];
  $endYear = $_POST['end_year'];
  $selectedUsers = $_POST['users'] ?? [];

  $pdo->beginTransaction();

  try {
    $stmt = $pdo->prepare("UPDATE projects SET name = ?, seismic = ?, state = ?, start_year = ?, end_year = ? WHERE id = ?");
    $stmt->execute([$name, $seismic, $state, $startYear, $endYear, $id]);

    $stmt = $pdo->prepare("DELETE FROM project_users WHERE project_id = ?");
    $stmt->execute([$id]);

    if (!empty($selectedUsers)) {
      $insertValues = [];
      $placeholders = [];

      foreach ($selectedUsers as $userId) {
        $insertValues[] = $id;
        $insertValues[] = $userId;
        $placeholders[] = "(?, ?)";
      }

      $placeholdersStr = implode(", ", $placeholders);
      $stmt = $pdo->prepare("INSERT INTO project_users (project_id, user_id) VALUES " . $placeholdersStr);
      $stmt->execute($insertValues);
    }

    $pdo->commit();

    header("Location: projects.php");
    exit;
  } catch (Exception $e) {
    $pdo->rollBack();
    set_flash('error', 'Ошибка при обновлении проекта: ' . $e->getMessage());
  }
}
?>

<?php require_once __DIR__ . '/parts/head.php'; ?>

<body class="sb-nav-fixed">
  <?php require_once __DIR__ . '/parts/header.php' ?>
  <div id="layoutSidenav">
    <?php require_once __DIR__ . '/parts/sideNav.php' ?>
    <div id="layoutSidenav_content">
      <main>
        <div class="container-fluid px-4">
          <div class="row justify-content-center">
            <div class="col-lg-5">
              <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header">
                  <h3 class="text-center font-weight-light my-4">Редактирование проекта</h3>
                </div>
                <div class="card-body">
                  <?php if ($msg = get_flash('error')): ?>
                    <div class="mb-2 text-danger"><?= e($msg) ?></div>
                  <?php endif; ?>

                  <form method="POST">
                    <div class="mb-3">
                      <label class="mb-1" for="inputName">Название</label>
                      <input class="form-control" id="inputName" type="text" value="<?= $project['name'] ?>" name="name" required>
                    </div>
                    <label class="mb-1">Cейсмика:</label>
                    <div class="mb-3">
                      <select class="form-select" name="seismic">
                        <?php
                        $seismics = ['2D', '3D'];
                        foreach ($seismics as $s) {
                          $selected = $s === $project['seismic'] ? 'selected' : '';
                          echo "<option $selected>$s</option>";
                        }
                        ?>
                      </select>
                    </div>
                    <label class="mb-1">Стадия:</label>
                    <div class="mb-3">
                      <select class="form-select" name="state">
                        <?php
                        $states = ['Разработка тех. проекта', 'Разработка проекта ОВОС', 'Проведение общ. слушаний', 'Мобилизация', 'Полевые работы', 'Завершено'];
                        foreach ($states as $s) {
                          $selected = $s === $project['state'] ? 'selected' : '';
                          echo "<option $selected>$s</option>";
                        }
                        ?>
                      </select>
                    </div>
                    <div class="mb-3">
                      <label class="mb-1" for="inputStart">Год начала</label>
                      <input class="form-control" id="inputStart" type="number" value="<?= $project['start_year'] ?>" placeholder=" " name="start_year" required>
                    </div>
                    <div class="mb-3">
                      <label class="mb-1" for="inputEnd">Год окончания</label>
                      <input class="form-control" id="inputEnd" type="number" value="<?= $project['end_year'] ?>" placeholder=" " name="end_year" required>
                    </div>

                    <div class="mb-3">
                      <label class="mb-1">Назначить пользователей</label>
                      <div class="form-control" style="height: auto; max-height: 200px; overflow-y: auto;">
                        <?php foreach ($users as $user): ?>
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="users[]" value="<?= $user['id'] ?>" id="user<?= $user['id'] ?>"
                              <?= in_array($user['id'], $assignedUsers) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="user<?= $user['id'] ?>">
                              <?= e($user['name']) ?> (<?= e($user['email']) ?>)
                            </label>
                          </div>
                        <?php endforeach; ?>
                      </div>
                    </div>

                    <div class="d-flex mt-4 mb-0">
                      <button type="submit" class="btn btn-primary ms-auto">Сохранить</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
  <?php require_once __DIR__ . '/parts/scripts.php' ?>
</body>

</html>
