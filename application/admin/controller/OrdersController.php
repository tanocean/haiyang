<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\Orders;
use app\common\model\Detail;


class OrdersController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $req)
    {
         $condition = [];
         // 如果有状态条件
            if (!empty( $_GET['status'] ) ){
                $condition[] = ['status', '=', $_GET['status'] ];
            }
        // 如果有收货人名称条件
            if (!empty($_GET['rec'] ) ){
             $condition[] = ['rec', 'like', "%{$_GET['rec']}%"];
             }
        
        $orders = Orders::where( $condition )->paginate(3)->appends( $_GET );
        return view('/orders/index',['orders'=>$orders]); // 显示到页面
    }

    // 订单详情
    public function details($id)
    {
        $details = Detail::with('goods')->where('orders_oid','=',$id)->select();  // 获取数据

        return view('/orders/details',['details'=>$details]); // 显示到页面
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        
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
        // 获取数据

        $orders = Orders::get($id);
        //halt($orders);

        // 显示数据
        return view('orders/edit',['orders'=>$orders]);
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
            Orders::update($req->post(),['oid'=>$id],true);
        }catch(\Exception $e){
            return $this->error('修改失败');
        }

        return $this->success('修改成功','/admin/orders');
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $orders = orders::where('oid','=',$id)->find(); 

        $row = orders::destroy($id);  // 销毁
        
        if($row){
            return $this->success('删除成功','/admin/orders');
        }

        return $this->error('删除失败','/admin/orders');
    }

    // 发货
    public function start($id, $status=2)
    {
        $data['status']= $status;
        Orders::update($data, ['oid'=>$id], true);
        return redirect('/admin/orders');
    }


    // 交易完成
    public function end($id)
    {
       //return $this->up($id,3);
        return action('admin/OrdersController/start',['id'=>$id,'status'=>3]);
    }
}
