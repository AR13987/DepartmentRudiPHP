<?php

namespace Src;

use Exception;

class View
{
    private string $view = '';
    private array $data = [];
    private string $root = '';
    private string $layout = '/layouts/main.php';

    public function __construct(string $view = '', array $data = [])
    {
        $this->root = $this->getRoot();
        $this->view = $view;
        $this->data = $data;
    }

    // Полный путь до директории с представлениями
    private function getRoot(): string
    {
        global $app;
        $documentRoot = $_SERVER['DOCUMENT_ROOT'];
        $rootPath = str_replace('/public', '', $documentRoot);
        $path = $app->settings->getViewsPath();

        return $rootPath . $path;
    }

    // Путь до основного файла с шаблоном сайта
    private function getPathToMain(): string
    {
        return $this->root . $this->layout;
    }

    // Путь до текущего шаблона
    private function getPathToView(string $view = ''): string
    {
        $view = str_replace('.', '/', $view);
        return $this->getRoot() . "/$view.php";
    }

    public function render(string $view = '', array $data = []): string
    {
        $view = $view ?: $this->view;

        if (empty($view)) {
            throw new Exception('Имя представления не задано');
        }

        $path = $this->getPathToView($view);

        // Проверяем наличие главного шаблона
        if (!file_exists($this->getPathToMain())) {
            throw new Exception('Главный шаблон не найден: ' . $this->getPathToMain());
        }

        // Проверяем наличие текущего шаблона
        if (!file_exists($path)) {
            throw new Exception('Файл представления не найден: ' . $path);
        }

        // Объединяем данные из конструктора и локальные данные
        $data = array_merge($this->data, $data);

        // Импортируем переменные из массива в текущую таблицу символов
        extract($data, EXTR_PREFIX_SAME, '');

        // Включение буферизации вывода для представления
        ob_start();
        require $path;
        $content = ob_get_clean();

        echo $content;

        // Включение буферизации вывода для основного шаблона
        ob_start();
        require $this->getPathToMain();
        return ob_get_clean();
    }

    public function __toString(): string
    {
        return $this->render($this->view, $this->data);
    }
}
