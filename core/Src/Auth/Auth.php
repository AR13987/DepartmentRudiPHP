<?php

namespace Src\Auth;

use Src\Session;

interface IdentityInterface
{
    public function getId(): int;
    public function findIdentity(int $id);
    public function attemptIdentity(array $credentials);
}

class Auth
{
    // Разрешаем значение null до инициализации
    private static ?IdentityInterface $user = null;

    // Инициализация пользователя: вызовите этот метод ДО любых обращений к Auth::user()
    public static function init(IdentityInterface $user): void
    {
        self::$user = $user;
        if (self::user()) {
            self::login(self::user());
        }
    }

    public static function login(IdentityInterface $user): void
    {
        self::$user = $user;
        Session::set('id', self::$user->getId());
    }

    public static function attempt(array $credentials): bool
    {
        if ($user = self::$user->attemptIdentity($credentials)) {
            self::login($user);
            return true;
        }
        return false;
    }

    public static function user()
    {
        if (is_null(self::$user)) {
            return null;
        }
        $id = Session::get('id') ?? 0;
        return self::$user->findIdentity($id);
    }

    public static function check(): bool
    {
        return !is_null(self::user());
    }

    public static function logout(): bool
    {
        Session::clear('id');
        self::$user = null;
        return true;
    }
}