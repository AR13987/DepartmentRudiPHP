<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход в систему</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles/style.css">
</head>
<body class="login-page">
<div class="login-container">
    <h1>УМУ "Кафедрист"</h1>
    <h2>Авторизация</h2><br>
    <?php include __DIR__ . '/../errors/validation-errors.php'; ?><br>
    <?php if (isset($message) && $message): ?>
        <h3><?= htmlspecialchars($message) ?></h3>
    <?php endif; ?>

    <?php if (app()->auth::check()): ?>
        <h3>Добро пожаловать, <?= htmlspecialchars(app()->auth->user()->Username ?? 'Пользователь') ?></h3>
    <?php else: ?>
        <form method="post">
            <div class="form-group">
                <label>
                    <input type="text" name="Username" placeholder="Введите логин" required>
                </label>
            </div>
            <div class="form-group">
                <label>
                    <input type="password" name="PasswordHash" placeholder="Введите пароль" required>
                </label>
            </div>
            <div class="form-actions">
                <button type="submit">Войти</button>
            </div>
        </form>
    <?php endif; ?>
    <a href="#">Забыли пароль?</a>
</div>
</body>
</html>