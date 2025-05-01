<?php
// Путь до директории с конфигурационными файлами
const DIR_CONFIG = '/../config';

// Подключение автозагрузчика composer
require_once __DIR__ . '/../vendor/autoload.php';

// Функция для получения настроек
function getConfigs(string $path = DIR_CONFIG): array
{
    $settings = [];
    foreach (scandir(__DIR__ . $path) as $file) {
        $name = explode('.', $file)[0];
        if (!empty($name)) {
            $settings[$name] = include __DIR__ . "$path/$file";
        }
    }
    return $settings;
}

require_once __DIR__ . '/../routes/web.php';

$app = new \Src\Application(new \Src\Settings(getConfigs()));
// Устанавливаем глобальное свойство route, чтобы app()->route работало в AuthMiddleware
$app->route = \Src\Route::single();

function app()
{
    global $app;
    return $app;
}

return $app;
