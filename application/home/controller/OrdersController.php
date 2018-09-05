<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use app\common\model\Orders;
use app\common\model\Detail;
use think\Db;
use app\common\model\User;

class OrdersController extends Controller
{
    // 我的京东
    public function userinfo()
    {
        $user = session('homeUserInfo');

        return view('/orders/userinfo',['user'=>$user]);
    }


    // 收集订单信息:收货人,联系电话,地址,买家留言
    public function getinfo()
    {
        
        return view('orders/getinfo'); 
    }   


    public function jsy(Request $req)
    {
        // 保存订单收货信息
        session( 'orders.rec',$req->post('rec') ); // 收货人
        session( 'orders.tel',$req->post('tel') ); // 联系电话
        session( 'orders.addr',$req->post('addr') ); // 收货地址
        session( 'orders.umsg',$req->post('umsg') ); // 买家留言
        $cart = session('cart');
        // 显示结算页
        return view('orders/jsy',['cart'=>$cart]);
    }
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        
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
        session( 'orders.oid',date('YmdHis').mt_rand(1000,9999) ); // 订单号
        session( 'orders.user_uid', session('homeUserInfo.uid') ); //下单人ID号
        session('orders.status', 1);  // 订单状态
        session( 'orders.create_at', time() );  // 下单时间

        //$data = session('orders');
        Db::startTrans(); // 启动事物
        try{
            // 向订单主表写入数据,返回一个订单对象
            $orders = Orders::create( session('orders'), true );
        
            // 向详情表写入数据
            $orders->detail()->saveAll( session('cart') );

        }catch(\Exception $e){
            Db::rollback(); // 回滚事务
            return $this->error('生成订单失败','/cart/index');
        }

        Db::commit(); // 提交事务

        $oid = session('orders.oid');
        $sum = session('orders.sum');

        // 清空购物车和订单
        // session('orders',null);
        // session('cart',null);


        return view('orders/save',['oid'=>$oid, 'sum'=>$sum,]);
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
        $data = $request->post();
        $file = $request->file('gpic');
        if($file){
        $info = $file->move('config(app.save_path)');
        $fileName = $info->getSaveName();
        $arr = explode('/',$fileName);
        $thumb_name = implode('/sm_', $arr);
        Image::open($file)->thumb(150,150)->save(config('app.save_path').$thumb_name);
        $data['gpic'] = $fileName;
        }
        
        try{
            Orders::update($data,['uid'=>$id], true);
        }catch(\Exception $e){
            return $this->error('修改失败');
        }
        return $this->success('修改成功','/orders/userinfo');
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

    // 我的订单
    public function myorders()
    {
        // 获取当登录的用户ID
        $uid = session('homeUserInfo.uid');

        //根据用户ID查询所有订单
        $orders = Orders::where('user_uid', '=' ,$uid)->select();


        // 显示到页面
        return view('orders/myorders',['orders'=>$orders]);
    }
}

