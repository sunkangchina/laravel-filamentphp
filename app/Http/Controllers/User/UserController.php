<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Post;
use hg\apidoc\annotation as Apidoc;
use Spatie\RouteAttributes\Attributes\Middleware;
use App\Http\Middleware\Auth;
use Illuminate\Support\Facades\Auth as Author;

/**
 * @Apidoc\Title("用户信息")
 */
#[Prefix('api/v1/user')]
#[Middleware(Auth::class)]
class UserController extends \App\Http\Controllers\Controller
{
    /**
     * @Apidoc\Title("获取用户信息")
     * @Apidoc\Tag("用户")
     * @Apidoc\Method ("POST")
     * @Apidoc\Url ("/api/v1/user/info")
     * @Apidoc\Query("type", type="string",require=true, desc="来源，如 weixin")
     *
     * @Apidoc\Returned("id", type="string", desc="用户ID")
     * @Apidoc\Returned("phone", type="string", desc="手机号")
     * @Apidoc\Returned("oauth",type="object",desc="oauth",children={
     *     @Apidoc\Param ("openid",type="string",desc=""),
     *     @Apidoc\Param ("country_code",type="string",desc=""),
     *     @Apidoc\Param ("phone",type="string",desc=""),
     *     @Apidoc\Param ("pure_phone",type="string",desc=""),
     *     @Apidoc\Param ("unionid",type="string",desc=""),
     *     @Apidoc\Param ("expires_in",type="string",desc=""),
     * })
     *
     */
    #[Post('info')]
    public function applet(Request $request)
    {
        $user = Author::user();
        $oauth = $user->oauth;
        $data['id'] = $user->id;
        $data['email'] = $user->email;
        $data['phone'] = $user->phone;
        $data['oauth'] = $oauth;
        return $this->success('请求成功', $data);
    }
}
