<?php

namespace app\common\model;

use think\Model;

class Cate extends Model
{
    protected $table = 'shop_cate';
    protected $pk    = 'cid';

    // 获取类别的树形结构
    static public function getCates($cates=[],$pid=0)
    {
    	if (empty($cates)) {
    		$cates = self::select();
    	}

    	$tmp = [];
    	foreach($cates as $k=>$v){
    		if ($v->pid==$pid) {
    			$v->sub = self::getCates($cates,$v->cid);
    			$tmp[] = $v;
    		}
    	}

    	return $tmp;
    } 
}
