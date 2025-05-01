<?php

namespace Controller;

use Model\Employee;
use Model\Post;
use Model\User;
use Src\View;
use Src\Request;
use Src\Auth\Auth;

class Site
{
    public function index(Request $request): string
    {
        $posts = Post::where('id', $request->id)->get();
        return (new View())->render('site.post', ['posts' => $posts]);
    }

    public function login(Request $request): string
    {
        //Если просто обращение к странице, то отобразить форму
        if ($request->method === 'GET') {
            return new View('site.login');
        }
        //Если удалось аутентифицировать пользователя, то редирект
        if (Auth::attempt($request->all())) {
            app()->route->redirect('/');
        }
        //Если аутентификация не удалась, то сообщение об ошибке
        return new View('site.login', ['message' => 'Неправильные логин или пароль']);
    }

    public function logout(): void
    {

        Auth::logout();
        app()->route->redirect('/');
    }

    public function main(): string
    {
        $view = new View('layouts.main', ['message' => 'main working']);
        return $view->render();
    }

    public function signup(Request $request): string
    {
        if ($request->method === 'POST' && User::create($request->all())) {
            app()->route->redirect('/login');
        }
        return new View('site.signup');
    }

    public function addDiscipline(): string
    {
        $view = new View('site.add-discipline', ['message' => 'add-discipline working']);
        return $view->render();
    }

    public function addEmployee(Request $request)
    {
        if ($request->isPost()) {
            $employeeData = [
                'LastName' => $request->post('lastname'),
                'FirstName' => $request->post('firstname'),
                'MiddleName' => $request->post('middlename'),
                'BirthDate' => $request->post('dob'),
                'Address' => $request->post('address'),
                'JobTitle' => $request->post('position'),
                'DepartmentID' => $request->post('department'),
            ];

            $employee = new Employee();
            $employee->fill($employeeData);
            if ($employee->save()) {
                $userData = [
                    'Username' => $request->post('username'),
                    'PasswordHash' => password_hash($request->post('password'), PASSWORD_BCRYPT),
                    'Role' => $request->post('role'),
                    'EmployeeID' => $employee->EmployeeID
                ];

                $user = new User();
                $user->fill($userData);
                if ($user->save()) {
                    $message = 'Сотрудник и пользователь успешно добавлены!';
                } else {
                    $message = 'Ошибка при добавлении пользователя.';
                }
            } else {
                $message = 'Ошибка при добавлении сотрудника.';
            }
        }

        include_once 'views/site/add-employee.php';
    }

    public function addDepartment(): string
    {
        $view = new View('site.add-department', ['message' => 'add-department working']);
        return $view->render();
    }

    public function attachEmployee(): string
    {
        $view = new View('site.attach-employee', ['message' => 'add-employee working']);
        return $view->render();
    }
}