<?php
/**
 * Authorization : Bearer token
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth as Author;
use Laravel\Sanctum\Sanctum;

class Auth
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = request()->header('Authorization');
        $bearer = null;
        if (strpos($token, 'Bearer ') !== false) {
            $bearer = substr($token, 7);
        }
        if (!$bearer) {
            throw new \Exception("未登录", 250);
        }
        $user = Author::guard('sanctum')->user();
        if (!$user) {
            throw new \Exception("登录失败", 250);
        }
        if (!$user->tokenCan('server:update')) {
            throw new \Exception("登录异常", 250);
        }
        Author::loginUsingId($user->id);
        return $next($request);
    }
}
