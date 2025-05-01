<?php

use Src\Route;
use Middlewares\AuthMiddleware;

// Маршруты для неавторизованных пользователей
Route::add(['GET', 'POST'], '/signup', [Controller\Site::class, 'signup']);
Route::add(['GET', 'POST'], '/login', [Controller\Site::class, 'login']);
Route::add('GET', '/', [Controller\Site::class, 'main']);

// Маршруты для авторизованных пользователей
Route::group(['middleware' => new AuthMiddleware()], function () {
    Route::add('GET', '/logout', [Controller\Site::class, 'logout']);
});

// Маршруты для администраторов и сотрудников деканата
Route::group(['prefix' => '/control', 'middleware' => new AuthMiddleware(['admin', 'dean'])], function () {
    Route::add(['GET', 'POST'], '/add-discipline', [Controller\Site::class, 'addDiscipline']);
    Route::add(['GET', 'POST'], '/add-employee', [Controller\Site::class, 'addEmployee']);
    Route::add(['GET', 'POST'], '/add-department', [Controller\Site::class, 'addDepartment']);
    Route::add(['GET', 'POST'], '/attach-employee', [Controller\Site::class, 'attachEmployee']);
});
