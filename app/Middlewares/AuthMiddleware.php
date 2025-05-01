<?php
namespace Middlewares;

use Src\Auth\Auth;
use Src\Request;
use Src\Route;

class AuthMiddleware
{
    private array $allowedRoles;

    public function __construct(array $allowedRoles = [])
    {
        $this->allowedRoles = $allowedRoles;
    }

    // Второй аргумент необязательный – для совместимости, если передадут параметр
    public function handle(Request $request, $param = null)
    {
        // Если пользователь не аутентифицирован – редирект
        if (!Auth::check()) {
            // Используем синглтон маршрутизатора вместо app()->route
            Route::single()->redirect('/login');
        }

        // Если указаны разрешённые роли, проверяем роль пользователя
        if (!empty($this->allowedRoles)) {
            $user = Auth::user();
            $userRole = $user->Role ?? null;
            if (!in_array($userRole, $this->allowedRoles)) {
                die('Ошибка: У вас нет доступа к этому ресурсу.');
            }
        }
    }
}
