<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\captcha\Captcha;
use app\common\model\User;

class LoginController extends Controller
{   
    // 显示是一个登录表单
    public function login()
    {
        return view('login/login');
    }

    // 接受登录表单数据 进行验证
    public function dologin(Request $req)
    {
        $uname = $req->post('uname');
        $upwd = $req->post('upwd','','md5');
        $code = $req->post('code');

        // 判断验证码
        $obj = new Captcha();
        if ( !$obj->check($code) ) {
            return $this->error('验证码错误','/admin/login');
        }
        
        // 查找用户名和密码
        $user = User::where('uname','=',$uname)->where('upwd','=',$upwd)->find();

        // 判断用户名
        if (empty($uname)) {
            return $this->error('用户名错误');
        } 

        if ($user) { // 如果返回一个对象 表示登录成功
            
            // 设置标志位,表明登录成功
            session('adminFlag',true);
            // 把登陆成功的用户信息保存在超全局数组session中
            session('adminUserInfo', $user);
            return $this->success('后台登录成功','/admin');
        } else {
            return $this->error('后台登录失败','/admin/login');
        }
    }

    // 退出登录
    public function logout()
    {   
        // 设置标志位,表明退出
        session('adminFlag',null);
        return $this->success('正在退出,请等待','/admin/login');
    }

    // 生成验证码图片
    public function verify()
    {
        $config = [
            'useCurve' => false,
            'useNoise' => false,
            'length'   => 2
        ];
        $obj = new Captcha( $config );
        return $obj->entry();
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
