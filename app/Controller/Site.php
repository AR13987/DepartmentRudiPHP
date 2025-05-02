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
            if (isset($data['PasswordHash']) && !empty($data['PasswordHash'])) {
                $data['PasswordHash'] = password_hash($data['PasswordHash'], PASSWORD_BCRYPT);
            }
            if (User::create($data)) {
                app()->route->redirect('/login');
            }
        }
        return (new View('site.signup'))->render();
    }

    public function addDiscipline(Request $request = null): string
    {
        if ($request === null) {
            $request = new Request();
        }

        $message = '';

        if ($request->method === 'POST') {
            $disciplineName = $request->post('disciplineName');

            if (!$disciplineName) {
                $message = 'Название дисциплины не может быть пустым.';
            } else {
                $discipline = new \Model\Discipline();
                $discipline->Name = $disciplineName;

                if ($discipline->save()) {
                    $message = 'Дисциплина успешно добавлена!';
                } else {
                    $message = 'Ошибка при сохранении дисциплины.';
                }
            }
        }

        return (new View('site.add-discipline', ['message' => $message]))->render();
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
