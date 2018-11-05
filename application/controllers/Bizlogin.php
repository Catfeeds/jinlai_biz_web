<?php
	defined('BASEPATH') OR exit('此文件不可被直接访问');

	/**
	 * Salor
	 *
	 * @version 1.0.0
	 * @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	 * @copyright ICBG <www.bingshankeji.com>
	 */
	class Bizlogin extends MY_Controller
	{
		

		public function __construct(){
			parent::__construct();


			// 向类属性赋值
			$this->class_name = strtolower(__CLASS__);
			$this->class_name_cn = '商家后台'; // 改这里……
			$this->table_name = 'order_items'; // 和这里……
			$this->id_name = 'record_id'; // 还有这里，OK，这就可以了
			$this->view_root = $this->class_name; // 视图文件所在目录
			$this->media_root = MEDIA_URL. $this->class_name.'/'; // 媒体文件所在目录

			// 设置需要自动在视图文件中生成显示的字段
			$this->data_to_display = array(
				'name' => '商家后台',
				'description' => '描述',
			);
		} // end __construct

		public function index(){
			// 若已登录，转到首页
			
			($this->session->time_expire_login < time()) OR redirect( base_url("salor/index") );

			// 页面信息
			$data = array(
				'title' => '密码登录',
				'class' => $this->class_name.' login',
			);
			
			$this->form_validation->set_rules('mobile', '手机号', 'trim|required|exact_length[11]|is_natural_no_zero');
			$this->form_validation->set_rules('password', '密码', 'trim|required|min_length[6]|max_length[20]');

			if ($this->form_validation->run() === FALSE):
				$data['error'] = validation_errors();

			else:
				$data_to_search = array(
					'mobile' => $this->input->post('mobile'),
					'password' => $this->input->post('password'),
					'user_ip' => $this->input->ip_address(),
				);

				// 从API服务器获取相应详情信息
				$params = $data_to_search;
				$url = api_url('account/login');
				$result = $this->curl->go($url, $params, 'array');

				if ($result['status'] !== 200):
					$data['error'] = $result['content']['error']['message'];
				else:

					// 获取用户信息
					$data['item'] = $result['content'];
					// 将信息键值对写入session
					foreach ($data['item'] as $key => $value):
						$user_data[$key] = $value;
					endforeach;

					$url = api_url('biz/detail');
					$bizdata = $this->curl->go($url, ['app_type'=>'client','id'=>$result['content']['biz_id']], 'array');
					if ($bizdata['status'] == 200) {
						$user_data['brief_name'] = $bizdata['content']['brief_name'];
					}

					$user_data['hideorigin'] = 'yes'; //默认登录时不再跳转到原来到后台
					$user_data['time_expire_login'] = time() + 60*60*24 *30; // 默认登录状态保持30天
					$this->session->set_userdata($user_data);

					// 将用户手机号写入cookie并保存30天
					$this->input->set_cookie('mobile', $data['item']['mobile'], 60*60*24 *30, COOKIE_DOMAIN);
					
					// 若用户已设置密码则转到首页，否则转到密码设置页
					if ( !empty($data['item']['password']) ):
						redirect( base_url("salor/index") );
					endif;
				endif;
			endif;
			$this->load->view($this->view_root.'/login', $data);
		}

		/**
		 * 退出账户
		 *
		 * @param void
		 * @return void
		 */
		public function logout()
		{
			// 清除当前SESSION
			$this->session->sess_destroy();

			// 转到密码登录页
			redirect( base_url('bizlogin/index') );
		} // end logout
	}