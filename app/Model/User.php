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

    protected static function booted()
    {
        // Автоматическое хэширование пароля при создании пользователя
        static::creating(function ($user) {
            $user->PasswordHash = md5($user->PasswordHash);
        });
    }

    // Поиск пользователя по первичному ключу (UserID)
    public function findIdentity(int $id)
    {
        return self::where('UserID', $id)->first(); // Используем поле UserID вместо id
    }

    // Возврат первичного ключа
    public function getId(): int
    {
        return $this->UserID; // Возвращаем значение поля UserID
    }

    // Аутентификация пользователя по логину и паролю
    public function attemptIdentity(array $credentials)
    {
        return self::where([
            'Username' => $credentials['Username'],
            'PasswordHash' => md5($credentials['PasswordHash'])
        ])->first(); // Поиск пользователя
    }
}
