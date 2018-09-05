<?php

namespace app\admin\behavior;

class CheckLogin
{
   use \traits\controller\Jump; // 使用了Jump Trait
	public function run()
	{
		if ( empty( session('adminFlag') ) ){
			return $this->error('请先登录~','/admin/login');
		}
	}
} 


