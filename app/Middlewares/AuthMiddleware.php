namespace Middlewares;

use Src\Auth\Auth;
use Src\Request;

class AuthMiddleware
{
private array $allowedRoles;

public function __construct(array $allowedRoles = [])
{
$this->allowedRoles = $allowedRoles;
}

public function handle(Request $request)
{
// Проверка авторизации
if (!Auth::check()) {
app()->route->redirect('/login');
}

// Если переданы роли, проверяем их
if (!empty($this->allowedRoles)) {
$userRole = Auth::user()->Role ?? null;
if (!in_array($userRole, $this->allowedRoles)) {
die('Ошибка: У вас нет доступа к этому ресурсу.'); // Можно сделать редирект на страницу ошибки
}
}
}
}