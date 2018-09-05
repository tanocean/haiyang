<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
/* 前台注册 */
Route::rule('/reg', 'home/RegController/reg');  // 获取
Route::rule('/doreg', 'home/RegController/doreg'); // 显示


/* 前台登录 */
Route::rule('/login','home/LoginController/login');
Route::rule('/dologin','home/LoginController/dologin');
Route::rule('/logout','home/LoginController/logout');


/* 前台路由 */
Route::rule('/','home/IndexController/index');  // 首页
Route::rule('/goodslist/[:id]','home/GoodsController/index');  // 商品列表
Route::rule('/goods/read/:id','home/GoodsController/read');   // 商品详情


/* 购物车 */
Route::rule('/cart/create','home/CartController/create');
Route::rule('/cart/index','home/CartController/index');

Route::rule('/cart/dec/:id','home/CartController/dec');       // 减1操作
Route::rule('/cart/inc/:id','home/CartController/inc');      // 加1操作
Route::rule('/cart/del/:id','home/CartController/delete');  // 删除操作


/* 订单 */
Route::group([],function(){
	Route::rule('/orders/getinfo','home/OrdersController/getinfo');      // 收集订单信息
	Route::rule('/orders/jsy','home/OrdersController/jsy');             // 结算页(最后一次确认)
	Route::rule('/orders/save','home/OrdersController/save');          // 生成订单
	Route::rule('/orders/myorders','home/OrdersController/myorders'); // 浏览订单

})->after(['\app\home\behavior\CheckLogin']);

/* 我的京东 */
Route::group([],function(){
	Route::rule('/orders/userinfo','home/OrdersController/userinfo');

})->after(['\app\home\behavior\CheckLogin']);



/* 用户管理 */
Route::group([],function(){
	Route::rule('/user/index', 'admin/UsersController/index');
	Route::rule('/user/save', 'admin/UsersController/save');
	Route::rule('/user/create', 'admin/UsersController/create');
	Route::rule('/user/del/:id', 'admin/UsersController/delete');
	Route::rule('/user/:id/edit', 'admin/UsersController/edit');
	Route::rule('/user/update/:id', 'admin/UsersController/update');

})->after(['\app\admin\behavior\CheckLogin']);

/* 类别管理 */
Route::group(['name'=>'cate','prefix'=>'admin/CateController/'],function(){
	Route::rule('create/[:id]', 'create' ,'get');
	Route::rule('save', 'save' , 'post');
	Route::rule('index', 'index' , 'get');
	Route::rule('del/:id', 'delete' , 'get');
	Route::rule(':id/edit', 'edit' , 'get');
	Route::rule('update/:id', 'update' , 'post');

})->after(['\app\admin\behavior\CheckLogin']);

/* 商品管理 */
Route::group(['name'=>'goods','prefix'=>'admin/GoodsController/'],function(){
	Route::rule('create', 'create' ,'get');
	Route::rule('save', 'save' ,'post');
	Route::rule('index', 'index' ,'get');
	Route::rule(':id/up', 'up' ,'get');
	Route::rule(':id/down', 'down' ,'get');
	Route::rule('del/:id', 'delete' ,'get');
	Route::rule(':id/edit', 'edit' , 'get');
	Route::rule('update/:id', 'update' , 'post');
	
})->after(['\app\admin\behavior\CheckLogin']);

/* 后台浏览订单管理 */
Route::group(['name'=>'admin','prefix'=>'admin/OrdersController/'],function(){
	Route::rule('orders','index','get');            // 浏览订单
	Route::rule('details/:id','details','get');    // 订单详情
	Route::rule(':id/edit','edit','get');         // 修改订单

	Route::rule('update/:id', 'update' , 'post');
	Route::rule('delete/:id','delete','get');    // 删除订单
	Route::rule(':id/start', 'start' ,'get');    // 发货
	Route::rule(':id/end', 'end' ,'get');       // 交易完成

})->after(['\app\admin\behavior\CheckLogin']);


/* 登录模块 */
Route::rule('/admin/login', 'admin/LoginController/login'); // 显示登录
Route::rule('/code', 'admin/LoginController/verify'); // 生成验证码
Route::rule('/admin/dologin', 'admin/LoginController/dologin'); // 接收表单数据,进行验证
Route::rule('/admin/logout', 'admin/LoginController/logout'); // 退出登录


// Route::get('/',function(){echo '这里是首页';});

Route::view('/admin','admin@common/default');

Route::get('/xxoo', function (){echo'第一天写项目';});


Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

Route::get('hello/:name', 'index/hello');

return [

];
