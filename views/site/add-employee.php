<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить сотрудника</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles/style.css">
</head>
<body>
<div class="form-container">
    <h1>Добавить сотрудника</h1>
    <?php include __DIR__ . '/../errors/validation-errors.php'; ?><br>
    <form method="post" action="<?= app()->route->getUrl('/control/add-employee') ?>">
        <div class="form-group">
            <label for="lastname">Фамилия</label>
            <input type="text" id="lastname" name="lastname" placeholder="Введите фамилию" required>
        </div>
        <div class="form-group">
            <label for="firstname">Имя</label>
            <input type="text" id="firstname" name="firstname" placeholder="Введите имя" required>
        </div>
        <div class="form-group">
            <label for="middlename">Отчество</label>
            <input type="text" id="middlename" name="middlename" placeholder="Введите отчество">
        </div>
        <div class="form-group">
            <label for="dob">Дата рождения</label>
            <input type="date" id="dob" name="dob" required>
        </div>
        <div class="form-group">
            <label for="address">Адрес прописки</label>
            <input type="text" id="address" name="address" placeholder="Введите адрес">
        </div>
        <div class="form-group">
            <label for="gender">Пол</label>
            <select id="gender" name="Gender">
                <option value="Male">Мужской</option>
                <option value="Female">Женский</option>
            </select>
        </div>
        <div class="form-group">
            <label for="position">Должность</label>
            <input type="text" id="position" name="position" placeholder="Введите должность">
        </div>
        <div class="form-group">
            <label for="role">Роль пользователя</label>
            <select id="role" name="role">
                <?php if (app()->auth->user()->Role === 'admin'): ?>
                    <option value="dean">Сотрудник деканата</option>
                    <option value="teacher">Педагогический сотрудник</option>
                <?php elseif (app()->auth->user()->Role === 'dean'): ?>
                    <option value="teacher">Педагогический сотрудник</option>
                <?php endif; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="department">Кафедра</label>
            <select id="department" name="department">
                <?php if (!empty($departments)): ?>
                    <?php foreach ($departments as $department): ?>
                        <option value="<?= htmlspecialchars($department->DepartmentID) ?>">
                            <?= htmlspecialchars($department->Name) ?>
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="">Нет доступных кафедр</option>
                <?php endif; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="username">Логин</label>
            <input type="text" id="username" name="Username" placeholder="Введите логин" required>
        </div>
        <div class="form-group">
            <label for="password">Пароль</label>
            <input type="password" id="password" name="PasswordHash" placeholder="Введите пароль" required>
        </div>
        <div class="form-actions">
            <button type="submit">Сохранить</button>
            <button type="button" class="cancel" onclick="window.history.back();">Отмена</button>
        </div>
    </form>
</div>
</body>
</html>