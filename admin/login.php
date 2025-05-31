<?php
require_once __DIR__ . '/../includes/functions.php';
?>

<?php require_once __DIR__ . '/parts/head.php'; ?>

<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">Вход в админку</h3>
                                </div>
                                <div class="card-body">
                                    <?php if ($msg = get_flash('error')): ?>
                                        <div class="mb-2 text-danger"><?= e($msg) ?></div>
                                    <?php endif; ?>

                                    <form method="POST" action="login_process.php">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="inputEmail" type="email" placeholder="name@example.com" name="email" required>
                                            <label for="inputEmail">Email</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="inputPassword" type="password" placeholder="Пароль" name="password" required>
                                            <label for="inputPassword">Пароль</label>
                                        </div>
                                        <!-- <div class="form-check mb-3">
                                            <input class="form-check-input" id="inputRememberPassword" type="checkbox">
                                            <label class="form-check-label" for="inputRememberPassword">Remember Password</label>
                                        </div> -->
                                        <div class="d-flex mt-4 mb-0">
                                            <button type="submit" class="btn btn-primary ms-auto">Войти</button>
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