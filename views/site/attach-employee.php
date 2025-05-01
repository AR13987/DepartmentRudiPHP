<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Прикрепить сотрудника к дисциплине</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles/style.css">
</head>

<body>
<div class="form-container">
    <h1>Прикрепить сотрудника к дисциплине</h1>
    <form>
        <div class="form-group">
            <label for="employee">Сотрудник</label>
            <select id="employee">
                <option>Сотрудник 1</option>
                <option>Сотрудник 2</option>
                <option>Сотрудник 3</option>
            </select>
        </div>
        <div class="form-group">
            <label for="discipline">Дисциплина</label>
            <select id="discipline">
                <option>Дисциплина 1</option>
                <option>Дисциплина 2</option>
                <option>Дисциплина 3</option>
            </select>
        </div>
        <div class="form-actions">
            <button type="submit">Прикрепить</button>
        </div>
    </form>
</div>
</body>

</html>