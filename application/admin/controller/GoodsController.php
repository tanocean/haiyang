<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\Goods;
use app\common\model\Cate;
use think\Image;

class GoodsController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {

        $condition = [];

        // 如果有状态条件
            if (!empty($_GET['status']) ){
                $condition[] = ['status', '=', $_GET['status'] ];
            }
        // 如果有姓名条件
            if (!empty($_GET['gname']) ){
             $condition[] = ['gname', 'like', "%{$_GET['gname']}%"];
             }
        // 获取数据
        $goods = Goods::where( $condition )->paginate(3)->appends( $_GET );


        // 显示到页面
        return view('goods/index',['goods'=>$goods]);

    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {   //获取类别信息
        $cates = Cate::order('concat(path,cid)')->select();
        return view('goods/create',['cates'=>$cates]);
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
        $data['create_at'] = time();

        // 处理上传文件
        $file = $req->file('gpic');
        if (empty($file)){
            return $this->error('请上传图片');
        }
        // 把上传到临时目录的文件移动到指定位置
        $info = $file->move( config('app.save_path') );

        // 获取上传文件名称(移动过后新生成的文件名)
        $fileName = $info->getSaveName();
       
  
        // 生成一个缩略图
        $arr = explode('/', $fileName);
        $thumb_name = implode('/sm_', $arr);
        Image::open( $file )->thumb(150,150)->save( config('save_path').$thumb_name);
        
        
        $data['gpic'] = $fileName;
        
        

        try{
            Goods::create($data,true);
        }catch(\Exception $e){
            return $this->error('添加商品失败');
        }
        return $this->success('添加商品成功','/goods/create');
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
        $cates = Cate::order('concat(path,cid)')->select();
        // 获取数据
        $goods = Goods::get($id);

        // 显示数据
        return view('goods/edit',['goods'=>$goods,'cates'=>$cates,'id'=>$id]);
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
        $data=$req->post();
         
        //处理上传文件
        $file=$req->file('gpic');
        if($_FILES['gpic']['error']!=4){
        //把上传到临时目录的文件移动到指定位置
        $info=$file->move( config('app.save_path'));
        //获取上传文件名称(移动之后新生成的文件名);
        $fileName=$info->getSaveName();


        $arr=explode('/',$fileName);
        $thumb_name=implode('/sm_', $arr);
                // 生成一个小的缩放图
        Image::open($file)->thumb(150,150)->save( config('save_path').$thumb_name);
           $data['gpic']=$fileName; 
        }
         
        try{
            Goods::update($data,['gid'=>$id],true);
            Goods::update($req->post(),['gid'=>$id],true);
       }catch(\Exception $e){
            return $this->success('修改失败',"/goods/{$id}/edit");
       }
       return $this->success('修改成功','/goods/index');
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        
        $goods = Goods::where('gid','=',$id)->find(); 

        
            $row = Goods::destroy($id);

            if ($row){
                return $this->success('删除成功','/goods/index');
            }
            return $this->error('删除失败');
    }

    // 上架
    public function up($id, $status=2)
    {
        $data['status']=$status;
        Goods::update($data, ['gid'=>$id], true);
        return redirect('/goods/index');
    }


    // 下架
    public function down($id)
    {
       //return $this->up($id,3);
        return action('admin/GoodsController/up',['id'=>$id,'status'=>3]);
    }
}
