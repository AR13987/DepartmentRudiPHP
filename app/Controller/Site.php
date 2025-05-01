<?php

namespace Controller;

use Model\Employee;
use Model\User;
use Src\View;
use Src\Request;
use Src\Auth\Auth;
use Model\Department;

class Site
{
    public function index(Request $request): string
    {
        $posts = Post::where('id', $request->id)->get();
        return (new View())->render('site.post', ['posts' => $posts]);
    }

    // Если объект Request не передан, создаём новый
    public function login(Request $request = null): string
    {
        if ($request === null) {
            $request = new Request();
        }

        if ($request->method === 'GET') {
            return (new View('site.login'))->render();
        }

        if (Auth::attempt($request->all())) {
            app()->route->redirect('/');
        }

        return (new View('site.login', ['message' => 'Неправильные логин или пароль']))->render();
    }

    public function logout(): void
    {
        Auth::logout();
        app()->route->redirect('/');
    }

    public function main(): string
    {
        return (new View('layouts.main', ['message' => 'main working']))->render();
    }

    public function signup(Request $request = null): string
    {
        if ($request === null) {
            $request = new Request();
        }
        if ($request->method === 'POST') {
            $data = $request->all();
            // Хэшируем пароль перед сохранением
            if (isset($data['PasswordHash']) && !empty($data['PasswordHash'])) {
                $data['PasswordHash'] = password_hash($data['PasswordHash'], PASSWORD_BCRYPT);
            }
            if (User::create($data)) {
                app()->route->redirect('/login');
            }
        }
        return (new View('site.signup'))->render();
    }

    public function addDiscipline(): string
    {
        return (new View('site.add-discipline', ['message' => 'add-discipline working']))->render();
    }

    public function addEmployee(Request $request = null): string
    {
        if ($request === null) {
            $request = new Request();
        }

        $message = '';

        if ($request->method === 'POST') {
            $employeeData = [
                'LastName'    => $request->post('lastname'),
                'FirstName'   => $request->post('firstname'),
                'MiddleName'  => $request->post('middlename'),
                'Gender'      => $request->post('Gender'),
                'BirthDate'   => $request->post('dob'),
                'Address'     => $request->post('address'),
                'JobTitle'    => $request->post('position'),
                'DepartmentID'=> $request->post('department'),
            ];

            $employee = new Employee();
            $employee->fill($employeeData);
            if ($employee->save()) {
                $userData = [
                    'Username'     => $request->post('Username'),
                    'PasswordHash' => password_hash($request->post('PasswordHash'), PASSWORD_BCRYPT),
                    'Role'         => $request->post('role'),
                    'EmployeeID'   => $employee->EmployeeID
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

        // Получаем список кафедр из базы
        $departments = Department::all();

        // Передаем кафедры в шаблон через массив данных
        return (new View('site.add-employee', [
            'message'     => $message,
            'departments' => $departments
        ]))->render();
    }

    public function addDepartment(\Src\Request $request = null): string
    {
        if ($request === null) {
            $request = new \Src\Request();
        }

        $message = '';
        if ($request->method === 'POST') {
            $deptName = $request->post('Name');

            if (!$deptName) {
                $message = 'Название кафедры не может быть пустым.';
            } else {
                $department = new \Model\Department();
                $department->Name = $deptName;

                if ($department->save()) {
                    $message = 'Кафедра успешно добавлена!';
                } else {
                    $message = 'Ошибка при сохранении кафедры.';
                }
            }
        }

        return (new \Src\View('site.add-department', ['message' => $message]))->render();
    }

    public function attachEmployee(): string
    {
        return (new View('site.attach-employee', ['message' => 'add-employee working']))->render();
    }
}
