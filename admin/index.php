<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../db.php';
require_login();

$user = current_user();

$stmt = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC");
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                        <li class="breadcrumb-item active">Главная</li>
                    </ol>
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <p class="card-title">Проекты</p>
                                    <a href="projects.php" class="btn btn-primary">Перейти</a>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <p class="card-title">Отчеты</p>
                                    <a href="reports.php" class="btn btn-primary">Перейти</a>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <p class="card-title">Пользователи</p>
                                    <a href="users.php" class="btn btn-primary">Перейти</a>
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