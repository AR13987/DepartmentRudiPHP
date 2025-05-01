<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация в системе</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles/style.css">
</head>
<body>
<div class="form-container small">
    <h2>Регистрация в системе</h2><br>
    <h3><?= $message ?? ''; ?></h3>
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
            <button type="submit">Зарегистрироваться</button>
        </div>
    </form>
</div>
</body>
</html>