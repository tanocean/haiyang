<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\Cate;

class CateController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $condition = []; // 条件数组

        // 如果有path条件
        if ( !empty($_GET['path']) ){
            $condition[] = ['path', 'like' , "%{$_GET['path']}%" ];
        }
        // 如果有姓名条件
        if ( !empty($_GET['cname']) ){
            $condition[] = ['cname', 'like' , "%{$_GET['cname']}%"];
        }
        // 获取数据
        $cates = Cate::order('concat(path,cid)')->where( $condition )->paginate(7)->appends( $_GET );
        // 显示数据
        return view('cate/index',['cates'=>$cates]);

    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create($id)
    {   
        // 获取已有的类别信息
        $cates = Cate::order('concat(path,cid)')->select();
        //dump($cates);
        return view('cate/create',['cates'=>$cates,'id'=>$id]);
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $req)
    {
        $data = $req->post(); // 获取表单所有数据
        $pid = $req->post('pid'); // 获取父类ID

        if ($pid==0) {
            $data['path'] = '0,'; // 如果父类ID是0 那么path就是'0,'
        } else {
            $data['path'] = Cate::get($pid)->path."$pid,"; //如果父类ID不是0 那么path就是 父类path连接上父类ID逗号
        }
       // halt($data);
        try{
            Cate::create($data,true);
        }catch(\Exception $e){
            return $this->error('添加失败','/cate/create');
        }
        return $this->success('添加类别成功','/cate/index');
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
        // 获取数据
        $cate = Cate::get($id);

        //显示数据
        return view('cate/edit',['cate'=>$cate]);
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
            Cate::update($req->post(),['cid'=>$id],true);
        }catch(\Exception $e){
            return $this->error('修改失败',"/cate/{$id}/edit");
        }

        return $this->success('修改成功','/cate/index');
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $cate = Cate::where('pid','=',$id)->find();
        if ($cate){
            return $this->error('该类别下面有子分类,不能删除');
        }

        // 如果该分类下面有对应的商品,那么也不能删除 (参考) 

         $row = Cate::destroy($id);
        if ($row) {
            return $this->success('删除成功','/cate/index');
        }
        return $this->error('删除失败','/cate/index');
    }
}
