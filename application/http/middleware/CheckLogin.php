<?php

namespace app\http\middleware;
use think\facade\Session;
class CheckLogin
{
    public function handle($request, \Closure $next)
    {
        if (!Session::has('UserName')){
            return redirect('/admin/Login');
        }
        return $next($request);
    }
}
