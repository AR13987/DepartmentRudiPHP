<?php

namespace Src;

class Session
{
    public static function set($name, $value): void
    {
        $_SESSION[$name] = $value;
    }

    public static function get($name)
    {
        return $_SESSION[$name] ?? null;
    }

    public static function clear($name)
    {
        unset($_SESSION[$name]);
    }
}