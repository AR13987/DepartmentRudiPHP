<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Поиск дисциплин</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles/style-search-disciplines.css">
</head>
<body class="search-disciplines-page">
<div class="container">
    <h1>Поиск дисциплин</h1>

    <form method="get" action="<?= app()->route->getUrl('/control/search-disciplines') ?>">
        <label for="department">Фильтр по кафедре:</label>
        <select name="department" id="department">
            <option value="">Все кафедры</option>
            <?php foreach ($departments as $dept): ?>
                <option value="<?= htmlspecialchars($dept->DepartmentID) ?>"
                    <?= (isset($selectedDepartment) && $selectedDepartment == $dept->DepartmentID) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($dept->Name) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="employee">Или по сотруднику:</label>
        <select name="employee" id="employee">
            <option value="">Все сотрудники</option>
            <?php foreach ($employees as $emp): ?>
                <option value="<?= htmlspecialchars($emp->EmployeeID) ?>"
                    <?= (isset($selectedEmployee) && $selectedEmployee == $emp->EmployeeID) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($emp->FirstName . ' ' . $emp->LastName) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Поиск</button>
    </form>

    <div class="results">
        <h2>Результаты поиска дисциплин</h2>
        <?php if (empty($disciplines) || (!is_object($disciplines) && count($disciplines) === 0) || (method_exists($disciplines, 'isEmpty') && $disciplines->isEmpty())): ?>
            <p>Нет дисциплин для выбранных фильтров.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($disciplines as $discipline): ?>
                    <li><?= htmlspecialchars($discipline->Name) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>
</body>
</html>