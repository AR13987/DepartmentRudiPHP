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
    <form>
        <div class="form-group">
            <label for="lastname">Фамилия</label>
            <input type="text" id="lastname" placeholder="Введите фамилию" required>
        </div>
        <div class="form-group">
            <label for="firstname">Имя</label>
            <input type="text" id="firstname" placeholder="Введите имя" required>
        </div>
        <div class="form-group">
            <label for="middlename">Отчество</label>
            <input type="text" id="middlename" placeholder="Введите отчество">
        </div>
        <div class="form-group">
            <label for="dob">Дата рождения</label>
            <input type="date" id="dob" required>
        </div>
        <div class="form-group">
            <label for="address">Адрес прописки</label>
            <input type="text" id="address" placeholder="Введите адрес">
        </div>
        <div class="form-group">
            <label for="position">Должность</label>
            <input type="text" id="position" placeholder="Введите должность">
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
            <select id="department">
                <option>Кафедра 1</option>
                <option>Кафедра 2</option>
                <option>Кафедра 3</option>
            </select>
        </div>
        <div class="form-actions">
            <button type="submit">Сохранить</button>
            <button type="button" class="cancel">Отмена</button>
        </div>
    </form>
</div>
</body>

</html>