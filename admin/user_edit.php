<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../db.php';
require_login();

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $role = $_POST['role'];

  $stmt = $pdo->prepare("UPDATE users SET name = ?, username = ?, email = ?,  password = ?, role = ? WHERE id = ?");
  $stmt->execute([$name, $username, $email, $password, $role, $id]);

  header("Location: users.php");
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
                  <h3 class="text-center font-weight-light my-4">Редактирование пользователя</h3>
                </div>
                <div class="card-body">
                  <?php if ($msg = get_flash('error')): ?>
                    <div class="mb-2 text-danger"><?= e($msg) ?></div>
                  <?php endif; ?>

                  <form method="POST">
                    <div class="mb-3">
                      <label class="mb-1" for="inputName">Имя</label>
                      <input class="form-control" id="inputName" type="text" value="<?= $user['name'] ?>" name="name" required>
                    </div>
                    <div class="mb-3">
                      <label class="mb-1" for="inputUsername">Username</label>
                      <input class="form-control" id="inputUsername" type="text" value="<?= $user['name'] ?>" name="username" required>
                    </div>
                    <div class="mb-3">
                      <label class="mb-1" for="inputEmail">Email</label>
                      <input class="form-control" id="inputEmail" type="email" value="<?= $user['email'] ?>" name="email" required>
                    </div>
                    <div class="mb-3">
                      <label class="mb-1" for="inputPassword">Пароль</label>
                      <input class="form-control" id="inputPassword" type="text" value="<?= $user['password'] ?>" name="password" required>
                    </div>
                    <label class="mb-1" for="inputRole">Роль</label>
                    <div class="mb-3">
                      <select class="form-select" name="role" id="inputRole">
                        <?php
                        $roles = ['user', 'admin', 'viewer'];
                        foreach ($roles as $r) {
                          $selected = $r === $user['role'] ? 'selected' : '';
                          echo "<option $selected>$r</option>";
                        }
                        ?>
                      </select>
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
