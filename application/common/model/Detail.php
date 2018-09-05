<?php

namespace app\common\model;

use think\Model;

class Detail extends Model
{
    protected $table = 'shop_detail';
    protected $pk = 'did';

    // 详情表与主表建立关联
    public function orders()
    {
    	return $this->belongsTo('Orders','orders_oid','oid');
    }

    // 详情表与商品表建立关联
    public function goods()
    {
    	return $this->belongsTo('Goods','gid','gid');
    }
    // 利用事件修改库存和销量
    static public function init()
    {
        self::event('after_insert',function($detail){  

            Goods::get($detail->gid)->setDec('stock', $detail->cnt); // 库存

            Goods::get($detail->gid)->setInc('salecnt', $detail->cnt); // 销量

        });
    }

}