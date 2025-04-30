<?php
//Включаем запрет на неявное преобразование типов
declare(strict_types=1);
//Включаем сессии на все страницы
session_start();

try {
    //Создаем экземпляр приложения и запускаем его
    $app = require_once __DIR__ . '/../core/bootstrap.php';
    $app->run();
} catch (\Throwable $exception) {
    echo '<pre>';
    print_r($exception);
    echo '</pre>';
}