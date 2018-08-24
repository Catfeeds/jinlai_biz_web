<?php
	defined('BASEPATH') OR exit('此文件不可被直接访问');

	/**
	 * 添加员工
	 *

	 */
	class Newuser extends MY_Controller {



		public function __construct()
		{
			parent::__construct();

			// 向类属性赋值
			$this->class_name = strtolower(__CLASS__);
			$this->class_name_cn = '收货地址'; // 改这里……
			$this->table_name = 'user'; // 和这里……
			$this->id_name = 'user_id'; // 还有这里，OK，这就可以了
			$this->view_root = $this->class_name;
			$this->basic_model->table_name = $this->table_name;
		} // end __construct


		public function create(){
			$startCount = 0;
			$user = ['password'=>'d78718f4005626e9115a4f20e33ab4c21b8f4532', 'mobile'=>'', 'last_login_ip'=>'218.201.110.203','status'=>'正常'];

			while ($startCount < 100) {
				$user['mobile'] =  '1811666'. sprintf('%03d', $startCount++);
				echo $this->basic_model->create($user);
				echo PHP_EOL;
			}
			echo 'done';

		}

		public function relate(){
			$this->table_name = 'stuff'; // 和这里……
			$this->id_name = 'stuff_id'; // 还有这里，OK，这就可以了
			$this->view_root = $this->table_name;

			$this->db->reset_query(); // 重置查询
			$this->basic_model->table_name = 'stuff';
			$this->basic_model->id_name = 'stuff_id';

			$startCount = 0;
			$user_id = 1975;
			$stuff = ['user_id'=>'', 'mobile'=>'', 'role'=>'管理员', 'level'=>100, 'status'=>'正常', 'user_id'=>1975];
			while ($startCount < 100) {
				$user['mobile'] =  '1811666'. sprintf('%03d', $startCount++) . '0';
				$user['user_id'] = $user_id++;
				echo $this->basic_model->create($user);
				echo PHP_EOL;
			}
			echo 'done';
		}
	}