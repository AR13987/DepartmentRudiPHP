<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Список сотрудников</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles/style-employees-list.css">
</head>
<body class="employees-list-page">
<div class="container-employees">
    <h1>Список сотрудников</h1>

    <form class="filter-form" method="get" action="<?= app()->route->getUrl('/control/employees-list') ?>">
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
        <button type="submit">Фильтровать</button>
    </form>

    <table class="employees-table">
        <thead>
        <tr>
            <th>Фамилия</th>
            <th>Имя</th>
            <th>Отчество</th>
            <th>Пол</th>
            <th>Дата рождения</th>
            <th>Должность</th>
            <th>Кафедра</th>
            <th>Дисциплины</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($employees as $employee): ?>
            <tr>
                <td><?= htmlspecialchars($employee->LastName) ?></td>
                <td><?= htmlspecialchars($employee->FirstName) ?></td>
                <td><?= htmlspecialchars($employee->MiddleName) ?></td>
                <td><?= htmlspecialchars($employee->Gender) ?></td>
                <td><?= htmlspecialchars($employee->BirthDate) ?></td>
                <td><?= htmlspecialchars($employee->JobTitle) ?></td>
                <td><?= htmlspecialchars($employee->DepartmentID) ?></td>
                <td class="disciplines-list">
                    <?php if ($employee->disciplines): ?>
                        <?php foreach ($employee->disciplines as $discipline): ?>
                            <?= htmlspecialchars($discipline->Name) ?><br>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>