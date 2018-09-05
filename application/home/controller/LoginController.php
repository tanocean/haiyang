<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use app\common\model\User;

class LoginController extends Controller
{
    // 前台登录页面
    public function login()
    {
        return view('login/login');
    }

    // 接收表单数据,进行验证
    public function dologin(Request $req)
    {
        $uname = $req->post('uname');
        $upwd = $req->post('upwd','','md5');

        $user = User::where('uname','=',$uname)->where('upwd','=',$upwd)->find();

        if ($user) {
            session('homeFlag',true);  //登录成功标志
            session('homeUserInfo',$user); // 保存登录成功的用户信息

            $uri = empty( session('back') ) ? '/' : session('back');
            session('back',null);

            return $this->success('登录成功', $uri);
        } else {
            return $this->error('登录失败','/login');
        }
    }

    // 退出登录
    public function logout()
    {
        session('homeFlag',null);
        return $this->success('正在退出','/login');
    }

    
}
