<?php

namespace Src\Traits;

//Позволяет для любого класса организовать паттерн Одиночка
trait SingletonTrait
{
    private static self $instance;

    public static function single(): self
    {
        if (empty(self::$instance)) self::$instance = new static();
        return self::$instance;
    }

    private function __construct()
    {
    }
}