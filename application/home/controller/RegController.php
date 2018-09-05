<?php


namespace app\home\controller;


use think\Controller;
use think\Request;
use think\captcha\Captcha;
use app\common\model\User;


class RegController extends Controller
{
    //显示页面
    public function reg()
    {   
        return view('/reg/reg');
    }
    


    public function doreg(Request $request)
    {
        $data = $request->post();
        $code = $request->post('code');


        //判断验证码
        $obj = new Captcha();
        if(!$obj->check($code)){
         return $this->error('验证码错误','/reg');
        }
        //判断账号
        if(empty($data['uname'])){
            return $this->error('账号不能为空','/reg');
        }
        //判断密码
        if(empty($data['upwd']) || empty($data['reupwd'])){
            return $this->error('密码不能为空','/reg');
        }
        //判断手机号
        if(empty($data['tel'])){
            return $this->error('手机号不能为空','/reg');
        }
        //密码验证
        if($data['upwd'] !== $data['reupwd']){
            return $this->error('两次密码不一致','/reg');
        }


        
        $data['upwd'] = md5($data['upwd']);
        $data['create_at'] = time();


        try{
            User::create($data,true);
        }catch(\Exception $e){
            return $this->error('用户名已存在','/reg');
        }
        return $this->success('注册成功','/login');


        
    }


    public function verify()
    {
        //生成验证码图片


            $config = [
                'useCurve' => false,
                'useNoise' => false,
                'length' => 2,
            ];  


           $obj = new Captcha($config);
           return $obj->entry();




        
        


    }
}










