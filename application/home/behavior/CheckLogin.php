<?php

namespace app\home\behavior;

class CheckLogin
{
	use \traits\controller\Jump;
	public function run()
	{
		// 判断是否登录 如果没有登录就跳转到登录去
        if ( empty(session ('homeFlag') ) ) {
            session('back', $_SERVER['REQUEST_URI']);  // 服务器发出请求的当前地址
            return $this->error('尚未登录,请登录..','/login');
        }
	}
}