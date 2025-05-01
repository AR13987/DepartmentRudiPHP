<?php

namespace Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Src\Auth\IdentityInterface;

class User extends Model implements IdentityInterface
{
    use HasFactory;

    // Указываем первичный ключ, который соответствует нашей базе данных
    protected $primaryKey = 'UserID';
    public $incrementing = true;
    protected $keyType = 'int';

    // Отключаем временные метки
    public $timestamps = false;

    // Разрешённые поля для массового заполнения
    protected $fillable = [
        'Username',
        'PasswordHash',
        'Role',
        'EmployeeID'
    ];

    // Поиск пользователя по первичному ключу (UserID)
    public function findIdentity(int $id)
    {
        return self::where('UserID', $id)->first();
    }

    // Возврат первичного ключа
    public function getId(): int
    {
        return $this->UserID;
    }

    // Аутентификация пользователя по логину и паролю.
    public function attemptIdentity(array $credentials)
    {
        // Проверяем наличие ключей: в форме логин передаётся как "Username",
        // а пароль — как "password" (это важно: форма должна отправлять поле "password")
        if (!isset($credentials['Username']) || !isset($credentials['PasswordHash'])) {
            return null;
        }

        // Находим пользователя по логину
        $user = self::where('Username', $credentials['Username'])->first();
        if ($user) {
            if (
                substr($user->PasswordHash, 0, 4) === '$2y$' ||
                substr($user->PasswordHash, 0, 4) === '$2a$' ||
                substr($user->PasswordHash, 0, 4) === '$2b$'
            ) {
                if (password_verify($credentials['PasswordHash'], $user->PasswordHash)) {
                    return $user;
                }
            } else {
                if ($user->PasswordHash === md5($credentials['PasswordHash'])) {
                    return $user;
                }
            }
        }
        return null;
    }
}
