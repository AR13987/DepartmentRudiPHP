<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Кафедрист - Главная страница</title>
    <link rel="stylesheet" href="/styles/style.css">

</head>
<body class="dashboard-page">
<header class="top-header">
    <div class="logo">УМУ "Кафедрист"</div>
    <nav>
        <a href="<?= app()->route->getUrl('/') ?>">Главная</a>
        <?php if (!app()->auth::check()): ?>
            <a href="<?= app()->route->getUrl('/login') ?>">Вход</a>
            <a href="<?= app()->route->getUrl('/signup') ?>">Регистрация</a>
        <?php else: ?>
            <a href="<?= app()->route->getUrl('/logout') ?>">Выход (<?= htmlspecialchars(app()->auth->user()->Username) ?>)</a>
        <?php endif; ?>
    </nav>
</header>

<div class="dashboard-container">
    <aside class="sidebar">
        <ul>
            <li><a href="<?= app()->route->getUrl('/control/add-employee') ?>">Добавить сотрудника</a></li>
            <li><a href="<?= app()->route->getUrl('/control/add-department') ?>">Добавить кафедру</a></li>
            <li><a href="<?= app()->route->getUrl('/control/add-discipline') ?>">Добавить дисциплину</a></li>
            <li><a href="<?= app()->route->getUrl('/control/attach-employee') ?>">Прикрепить сотрудника</a></li>
            <li>Поиск дисциплин</li>
            <li>Список сотрудников</li>
        </ul>
    </aside>

    <main class="main-content">
        <h1>Добро пожаловать, <?= htmlspecialchars(app()->auth->user()->Username ?? 'Гость') ?>!</h1>
        <p>Выберите действие из бокового меню.</p>
    </main>
</div>
</body>
</html>
