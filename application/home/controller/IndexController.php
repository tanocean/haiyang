<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use app\common\model\Cate;

class IndexController extends Controller
{
    
    public function index()
    {
    	//$cates = Cate::getCates();
    	//dump($cates);die;
        return view('index/index',['show'=>true]);
    }

    
}
