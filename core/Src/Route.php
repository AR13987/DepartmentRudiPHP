<?php

namespace Src;

use Error;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;
use FastRoute\DataGenerator\MarkBased;
use FastRoute\Dispatcher\MarkBased as Dispatcher;
use Src\Traits\SingletonTrait;

class Route
{
    // Используем методы трейта SingletonTrait
    use SingletonTrait;

    // Свойство для хранения текущего маршрута
    private string $currentRoute = '';
    private $currentHttpMethod;

    // Свойство для префикса для всех маршрутов
    private string $prefix = '';

    // Класс для использования внешнего маршрутизатора
    private RouteCollector $routeCollector;

    // Добавляет маршрут, устанавливает его текущим и возвращает объект
    public static function add($httpMethod, string $route, array $action): self
    {
        self::single()->routeCollector->addRoute($httpMethod, $route, $action);
        self::single()->currentHttpMethod = $httpMethod;
        self::single()->currentRoute = $route;
        return self::single();
    }

    // Добавляет префикс для групп маршрутов
    public static function group(string $prefix, callable $callback): void
    {
        self::single()->routeCollector->addGroup($prefix, $callback);
        Middleware::single()->group($prefix, $callback);
    }

    // Конструктор скрыт. Вызывается только один раз.
    private function __construct()
    {
        $this->routeCollector = new RouteCollector(new Std(), new MarkBased());
    }

    public function setPrefix(string $value = ''): self
    {
        $this->prefix = $value;
        return $this;
    }

    public function redirect(string $url): void
    {
        header('Location: ' . $this->getUrl($url));
    }

    public function getUrl(string $url): string
    {
        return $this->prefix . $url;
    }

    // Добавление middleware для текущего маршрута
    public function middleware(...$middlewares): self
    {
        Middleware::single()->add($this->currentHttpMethod, $this->currentRoute, $middlewares);
        return self::single();
    }

    public function start(): void
    {
        // Получаем метод и URI из серверных переменных
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        // Отсекаем query-string, если он присутствует, и декодируем URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        // Отсекаем префикс из URI
        $uri = substr($uri, strlen($this->prefix));

        // Если URI пустой, задаём базовый путь "/"
        if ($uri === '') {
            $uri = '/';
        }

        // **** Логирование для отладки ****
        error_log("HTTP Method: $httpMethod");
        error_log("Original URI: " . $_SERVER['REQUEST_URI']);
        error_log("Prefix: " . $this->prefix);
        error_log("Processed URI: " . $uri);
        // ***********************************

        $dispatcher = new Dispatcher($this->routeCollector->getData());
        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                throw new Error('NOT_FOUND');
            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new Error('METHOD_NOT_ALLOWED');
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = array_values($routeInfo[2]);

                error_log("Route FOUND - Handler: " . print_r($handler, true));
                error_log("Route FOUND - Vars: " . print_r($vars, true));

                // Запускаем middleware для данного маршрута
                $vars[] = Middleware::single()->runMiddlewares($httpMethod, $uri);
                $class = $handler[0];
                $action = $handler[1];

                call_user_func([new $class, $action], ...$vars);
                break;
        }
    }
}
