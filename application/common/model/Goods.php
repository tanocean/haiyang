<?php

namespace app\common\model;

use think\Model;

class Goods extends Model
{
   	protected $table = 'shop_goods';
   	protected $pk    = 'gid';


   	public function getSmgpicAttr()
   	{
   		$arr = explode('/', $this->gpic);
   		return implode('/sm_', $arr);
   	}

   	// 当访问什么方法时会触发模型中的方法(固定格式 get+属性+Attr)
   	// public function getPriceAttr($value)
   	// {
   	// 	return $value+5;
   	// }

   	// 商品类别显示
   	public function cate()
   	{
		// 属于 1)关联的模型  2)外键名 3) 关联模型的主键
   		return $this->belongsTo('Cate','cate_cid','cid');
   	}

}
