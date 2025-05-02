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
    <?php if (isset($message) && $message): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?><br>
    <form method="post" action="<?= app()->route->getUrl('/control/attach-employee') ?>">
        <div class="form-group">
            <label for="employee">Сотрудник</label>
            <select id="employee" name="employee">
                <?php if (!empty($employees)): ?>
                    <?php foreach ($employees as $employee): ?>
                        <option value="<?= htmlspecialchars($employee->EmployeeID) ?>">
                            <?= htmlspecialchars($employee->FirstName . ' ' . $employee->LastName) ?>
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="">Нет сотрудников</option>
                <?php endif; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="discipline">Дисциплина</label>
            <select id="discipline" name="discipline">
                <?php if (!empty($disciplines)): ?>
                    <?php foreach ($disciplines as $discipline): ?>
                        <option value="<?= htmlspecialchars($discipline->DisciplineID) ?>">
                            <?= htmlspecialchars($discipline->Name) ?>
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="">Нет дисциплин</option>
                <?php endif; ?>
            </select>
        </div>
        <div class="form-actions">
            <button type="submit">Прикрепить</button>
            <button type="button" class="cancel" onclick="window.history.back();">Отмена</button>
        </div>
    </form>
</div>
</body>
</html>