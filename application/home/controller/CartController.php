<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use app\common\model\Goods;

class CartController extends Controller
{
    // 减1操作
    public function dec($id)
    {
        $goods = session("cart.$id");

        if (--$goods->cnt < 1) {
            $goods->cnt = 1;
        }
        return redirect('/cart/index');
    }

    // 加1操作
    public function inc($id)
    {
        session("cart.$id")->cnt++;
        return redirect('/cart/index');
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        // 获取数据
        $carts =session('cart');

        // 求总金额  总数量
        $sum = 0; 
        $cnt = 0;
        foreach ($carts as $k=>$v) {
            $sum += $v->price*$v->cnt;
            $cnt += $v->cnt;
        }

        // 把结果再次保存在session中(临时保存)
        session('orders.sum',$sum);
        session('orders.cnt',$cnt);

        // 显示数据
        return view('cart/index',['carts'=>$carts, 'sum'=>$sum, 'cnt'=>$cnt]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create(Request $req)
    {
        // 保存数据
        $gid = $req->post('gid');
        $cnt = $req->post('cnt');

        $goods = Goods::get($gid);
        $goods->cnt = $cnt;
        session("cart.$gid",$goods);  // 获取商品信息

        // 显示页面
        return view('cart/create',['goods'=>$goods]);
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
        session("cart.$id",null);
        return redirect('/cart/index');
    }
}
