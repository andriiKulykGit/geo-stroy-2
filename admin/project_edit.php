<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../db.php';
require_login();

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
$stmt->execute([$id]);
$project = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $state = $_POST['state'];
  $seismic = $_POST['seismic'];

  $stmt = $pdo->prepare("UPDATE projects SET name = ?, seismic = ?, state = ? WHERE id = ?");
  $stmt->execute([$name, $seismic, $state, $id]);

  header("Location: projects.php");
  exit;
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
                          $selected = $s === $project['state'] ? 'selected' : '';
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
                    <div class="d-flex mt-4 mb-0">
                      <button type="submit" class="btn btn-primary ms-auto">Создать</button>
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