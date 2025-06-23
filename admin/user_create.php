<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../db.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $role = $_POST['role'];

  $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?,?,?,?)");
  $stmt->execute([$name, $email, $password, $role]);

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
                  <h3 class="text-center font-weight-light my-4">Добавление пользователя</h3>
                </div>
                <div class="card-body">
                  <?php if ($msg = get_flash('error')): ?>
                    <div class="mb-2 text-danger"><?= e($msg) ?></div>
                  <?php endif; ?>

                  <form method="POST">
                    <div class="mb-3">
                      <label class="mb-1" for="inputName">Имя</label>
                      <input class="form-control" id="inputName" type="text" placeholder=" " name="name" required>
                    </div>
                    <div class="mb-3">
                      <label class="mb-1" for="inputEmail">Email</label>
                      <input class="form-control" id="inputEmail" type="text" placeholder=" " name="email" required>
                    </div>
                    <div class="mb-3">
                      <label class="mb-1" for="inputPassword">Пароль</label>
                      <input class="form-control" id="inputPassword" type="text" placeholder=" " name="password" required>
                    </div>
                    <label class="mb-1" for="inputRole">Роль</label>
                    <div class="mb-3">
                      <select class="form-select" id="inputRole" name="role">
                        <option value="user">Пользователь</option>
                        <option value="admin">Администратор</option>
                        <option value="viewer">Просмотрщик</option>
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
