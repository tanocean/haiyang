<?php

namespace app\common\model;

use think\Model;

class User extends Model
{
   protected $table = 'shop_users';
   protected $pk    =  'uid';


   	public function getXxooAttr()
   	{
   		$arr = explode('/', $this->gpic);
   		return implode('/sm_', $arr);
   	}
}
