<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\User;
use think\Image;



class UsersController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {   
        // 条件数组
        $condition = [];
        // 如果有性别条件
        if (!empty($_GET['sex']) ){
            $condition[] = ['sex', '=', $_GET['sex'] ];
        }

        // 如果有姓名条件
        if (!empty($_GET['uname']) ){
            $condition[] = ['uname', 'like', "%{$_GET['uname']}%"];
        }
        // echo 1111;
        // 获取数据
        $users = User::where( $condition )->paginate(3)->appends( $_GET );

        //  echo '<pre>';
        // dump($users);

        // 显示页面
        return view('user/index',['users'=>$users]);

    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        return view('user/create');
        //echo 1111;
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $req)
    {

        $data = $req->post(); 

        // 判断是否为空
        if (empty($data['upwd']) || empty($data['reupwd']) ){
            return $this->error('密码不能为空','/user/create');
        }

        // 判断是否一致
        if ($data['upwd']!==$data['reupwd']){
            return $this->error('两次密码不一致','/user/create');
        }

        $data['create_at'] = time(); // 创建时间
        $data['upwd'] = md5($data['upwd']); // 密码进行md5加密

        // 处理上传文件
        $file = $req->file('gpic');
        if ($file){
            // 把上传到临时目录的文件移动到指定位置
            $info = $file->move( config('app.save_path') );

            // 获取上传文件名称(移动过后新生成的文件名)
            $fileName = $info->getSaveName();
       
            // 生成一个缩略图
            $arr = explode('/', $fileName);
            $thumb_name = implode('/sm_', $arr);
            Image::open( $file )->thumb(150,150)->save( config('save_path').$thumb_name);
            
            $data['gpic'] = $fileName;
        }

        // dump($data);die;
        User::create($data,true); // 写入数据库 不属于数据表中的字段自动忽略

        return $this->success('添加成功','/user/create');
    
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
        // 获取信息
        $user = User::get($id);
        // 显示到页面
        return view('user/edit',['user'=>$user]);
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $req, $id)
    {   
        

        try{
            User::update($req->post(),['uid'=>$id], true);
        }catch(\Exception $e){
            return $this->error('修改失败',"/user/{$id}/edit");
        }
            return $this->success('修改成功','/user/index');
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $row = User::destroy($id);
        if($row) {
            return $this->success('删除成功','/user/index');
        }
        return $this->error('删除失败','/user/index');
    }
}
