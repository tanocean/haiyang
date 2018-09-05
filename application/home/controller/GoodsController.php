<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use app\common\model\Cate;
use app\common\model\Goods;

class GoodsController extends Controller
{
 
    // 根据条件显示商品信息
    public function index($id)
    {
        // 条件数组
        $condition = [];
        if ( !empty($_GET['gname']) ) { // 如果有搜索条件
            $condition[] = ['gname','like', "%{$_GET['gname']}%"];
        }

        // 如果有类别ID的条件
        if ( !empty($id) ) {
            $arr_cid = Cate::where('path','like',"%,$id,%")->column('cid');
           array_push($arr_cid, (int)$id);
        $condition[] = ['cate_cid','in',$arr_cid];
        }

        $goods = Goods::where( $condition )->paginate(8)->appends( $_GET );
        return view('goods/index', ['goods'=>$goods]);
    }

    // 商品详情页
    public function read($id)
    {
        $goods = Goods::get($id); // 获取指定ID的商品信息
        return view('goods/read',['goods'=>$goods]);
    }
    
}