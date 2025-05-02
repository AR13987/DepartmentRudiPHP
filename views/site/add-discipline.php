<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Добавить дисциплину</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles/style.css">
</head>

<body>
<div class="form-container small">
    <h1>Добавить дисциплину</h1>
    <form method="post" action="<?= app()->route->getUrl('/control/add-discipline') ?>">
        <div class="form-group">
            <input type="text" name="disciplineName" placeholder="Название дисциплины" required>
        </div>
        <div class="form-actions">
            <button type="submit">Сохранить</button>
            <button type="button" class="cancel" onclick="window.history.back();">Отмена</button>
        </div>
    </form>
</div>
</body>

</html>