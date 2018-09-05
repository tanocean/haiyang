<?php

namespace app\common\model;

use think\Model;

class Orders extends Model
{
    protected $table = 'shop_orders';
    protected $pk    = 'oid';

    // 主表和详情表建立关联
    public function detail()
    {
    	return $this->hasMany('Detail','orders_oid','oid');
    }
}
