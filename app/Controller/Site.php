<?php

namespace Controller;

use Src\View;
use Src\Request;
use Src\Auth\Auth;
use Validation\Validator;

class Site
{
    public function index(Request $request): string
    {
        $posts = Post::where('id', $request->id)->get();
        return (new View())->render('site.post', ['posts' => $posts]);
    }

    public function login(Request $request = null): string
    {
        if ($request === null) {
            $request = new Request();
        }

        if ($request->method === 'GET') {
            return (new \Src\View('site.login'))->render();
        }

        $data = $request->all();
        $errors = [];

        if (empty($data['Username'])) {
            $errors['Username'][] = 'Логин обязателен.';
        }
        if (empty($data['PasswordHash'])) {
            $errors['PasswordHash'][] = 'Пароль обязателен.';
        }

        if (!empty($errors)) {
            return (new \Src\View('site.login', [
                'errors' => $errors,
                'old'    => $data
            ]))->render();
        }

        if (\Src\Auth\Auth::attempt($data)) {
            app()->route->redirect('/');
        }

        return (new \Src\View('site.login', [
            'message' => 'Неправильные логин или пароль',
            'old'     => $data
        ]))->render();
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

        $message = '';
        $errors  = [];
        $old     = $request->all();

        if ($request->method === 'POST') {
            $data = [
                'Username'     => $request->post('Username'),
                'PasswordHash' => $request->post('PasswordHash'),
            ];

            // Задаем правила для валидации
            $rules = [
                'Username'     => 'required|min:3|max:255',
                'PasswordHash' => 'required|min:6'
            ];

            $validator = new Validator($data, $rules);
            if ($validator->fails()) {
                $errors = $validator->errors();
            } else {
                // Хэшируем пароль и сохраняем пользователя
                $data['PasswordHash'] = password_hash($data['PasswordHash'], PASSWORD_BCRYPT);
                if (\Model\User::create($data)) {
                    app()->route->redirect('/login');
                } else {
                    $message = 'Ошибка при регистрации.';
                }
            }
        }

        return (new \Src\View('site.signup', [
            'message' => $message,
            'errors'  => $errors,
            'old'     => $old
        ]))->render();
    }

    public function addDiscipline(Request $request = null): string
    {
        if ($request === null) {
            $request = new Request();
        }

        $message = '';
        $errors  = [];
        $old     = $request->all();

        if ($request->method === 'POST') {
            $data = [
                'Name' => $request->post('Name'),
            ];

            $rules = [
                'Name' => 'required|min:2|max:255'
            ];

            $validator = new Validator($data, $rules);
            if ($validator->fails()) {
                $errors = $validator->errors();
            } else {
                $discipline = new \Model\Discipline();
                $discipline->Name = $data['Name'];
                if ($discipline->save()) {
                    $message = 'Дисциплина успешно добавлена!';
                } else {
                    $message = 'Ошибка при сохранении дисциплины.';
                }
            }
        }

        return (new \Src\View('site.add-discipline', [
            'message' => $message,
            'errors'  => $errors,
            'old'     => $old
        ]))->render();
    }

    public function addEmployee(Request $request = null): string
    {
        if ($request === null) {
            $request = new Request();
        }

        $message = '';
        $errors  = [];
        $old     = $request->all();

        if ($request->method === 'POST') {
            $employeeData = [
                'LastName'    => $request->post('LastName'),
                'FirstName'   => $request->post('FirstName'),
                'MiddleName'  => $request->post('MiddleName'),
                'Gender'      => $request->post('Gender'),
                'BirthDate'   => $request->post('BirthDate'),
                'Address'     => $request->post('Address'),
                'JobTitle'    => $request->post('JobTitle'),
                'DepartmentID'=> $request->post('DepartmentID'),
                'Username'    => $request->post('Username'),
                'PasswordHash'=> $request->post('PasswordHash'),
            ];

            $rules = [
                'LastName'     => 'required|min:2|max:255',
                'FirstName'    => 'required|min:2|max:255',
                'BirthDate'    => 'required',
                'JobTitle'     => 'required|max:255',
                'DepartmentID' => 'required|numeric',
                'Username'     => 'required|min:3|max:255',
                'PasswordHash' => 'required|min:6'
            ];

            $validator = new Validator($employeeData, $rules);
            if ($validator->fails()) {
                $errors = $validator->errors();
            } else {
                $employee = new \Model\Employee();
                $employee->fill($employeeData);
                if ($employee->save()) {
                    $userData = [
                        'Username'     => $request->post('Username'),
                        'PasswordHash' => password_hash($request->post('PasswordHash'), PASSWORD_BCRYPT),
                        'Role'         => $request->post('Role'),
                        'EmployeeID'   => $employee->EmployeeID
                    ];

                    $user = new \Model\User();
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
        }

        // Получаем список кафедр для формы
        $departments = \Model\Department::all();

        return (new \Src\View('site.add-employee', [
            'message'     => $message,
            'departments' => $departments,
            'errors'      => $errors
        ]))->render();
    }

    public function addDepartment(Request $request = null): string
    {
        if ($request === null) {
            $request = new Request();
        }

        $message = '';
        $errors  = [];
        $old     = $request->all();

        if ($request->method === 'POST') {
            $data = [
                'Name' => $request->post('Name'),
            ];

            $rules = [
                'Name' => 'required|min:2|max:255'
            ];

            $validator = new Validator($data, $rules);
            if ($validator->fails()) {
                $errors = $validator->errors();
            } else {
                $department = new \Model\Department();
                $department->Name = $data['Name'];
                if ($department->save()) {
                    $message = 'Кафедра успешно добавлена!';
                } else {
                    $message = 'Ошибка при сохранении кафедры.';
                }
            }
        }

        return (new \Src\View('site.add-department', [
            'message' => $message,
            'errors'  => $errors,
            'old'     => $old
        ]))->render();
    }

    public function attachEmployee(Request $request = null): string
    {
        if ($request === null) {
            $request = new Request();
        }

        $message = '';

        if ($request->method === 'POST') {
            $employeeId = $request->post('employee');
            $disciplineId = $request->post('discipline');

            if (!$employeeId || !$disciplineId) {
                $message = 'Не выбран сотрудник или дисциплина.';
            } else {
                $employee = \Model\Employee::find($employeeId);
                if (!$employee) {
                    $message = 'Сотрудник не найден.';
                } else {
                    try {
                        $employee->disciplines()->attach($disciplineId);
                        $message = 'Сотрудник успешно прикреплён к дисциплине!';
                    } catch (\Exception $e) {
                        $message = 'Ошибка при прикреплении сотрудника: ' . $e->getMessage();
                    }
                }
            }
        }

        // Для отображения в форме получаем список сотрудников и дисциплин
        $employees = \Model\Employee::all();
        $disciplines = \Model\Discipline::all();

        return (new \Src\View('site.attach-employee', [
            'message'     => $message,
            'employees'   => $employees,
            'disciplines' => $disciplines
        ]))->render();
    }

    public function employeesList(Request $request = null): string
    {
        if ($request === null) {
            $request = new Request();
        }

        // Получаем выбранное значение кафедры из GET-параметров (например, ?department=3)
        $departmentId = $request->get('department');

        if ($departmentId) {
            $employees = \Model\Employee::where('DepartmentID', $departmentId)->get();
        } else {
            $employees = \Model\Employee::all();
        }

        // Получаем список всех кафедр для фильтра
        $departments = \Model\Department::all();

        return (new \Src\View('site.employees-list', [
            'employees'         => $employees,
            'departments'       => $departments,
            'selectedDepartment'=> $departmentId
        ]))->render();
    }

    public function searchDisciplines(Request $request = null): string
    {
        if ($request === null) {
            $request = new Request();
        }

        // Получаем параметры фильтрации
        $selectedDepartment = $request->get('department'); // Фильтрация по кафедре
        $selectedEmployee   = $request->get('employee');   // Фильтрация по конкретному сотруднику

        $disciplines = collect();

        if ($selectedEmployee) {
            // Если выбран конкретный сотрудник, получаем дисциплины через связь
            $employee = \Model\Employee::find($selectedEmployee);
            if ($employee) {
                $disciplines = $employee->disciplines;
            }
        } elseif ($selectedDepartment) {
            // Если выбрана кафедра, фильтруем сотрудников по кафедре и объединяем их дисциплины
            $employees = \Model\Employee::where('DepartmentID', $selectedDepartment)->get();
            $allDisciplines = collect();
            foreach ($employees as $employee) {
                $allDisciplines = $allDisciplines->merge($employee->disciplines);
            }
            // Убираем дублирование по полю DisciplineID
            $disciplines = $allDisciplines->unique('DisciplineID');
        } else {
            // Если фильтр не применён, выводим дисциплины, прикрепленные хотя бы к одному сотруднику
            $disciplines = \Model\Discipline::has('employees')->get();
        }

        // Для фильтрационной формы получаем все кафедры и сотрудников
        $departments = \Model\Department::all();
        $employees   = \Model\Employee::all();

        return (new \Src\View('site.search-disciplines', [
            'disciplines'       => $disciplines,
            'departments'       => $departments,
            'employees'         => $employees,
            'selectedDepartment'=> $selectedDepartment,
            'selectedEmployee'  => $selectedEmployee
        ]))->render();
    }

}
