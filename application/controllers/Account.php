<?php
	defined('BASEPATH') OR exit('此文件不可被直接访问');

	/**
	 * Account Class
	 *
	 * @version 1.0.0
	 * @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	 * @copyright ICBG <www.bingshankeji.com>
	 */
	class Account extends CI_Controller
	{
		/* 类名称小写，应用于多处动态生成内容 */
		public $class_name;

		/* 类名称中文，应用于多处动态生成内容 */
		public $class_name_cn;

		/* 主要相关表名 */
		public $table_name;

		/* 主要相关表的主键名*/
		public $id_name;

		/* 视图文件所在目录名 */
		public $view_root;

		public function __construct()
		{
			parent::__construct();

			// 向类属性赋值
			$this->class_name = strtolower(__CLASS__);
			$this->class_name_cn = '账户'; // 改这里……
			$this->table_name = 'user'; // 和这里……
			$this->id_name = 'user_id';  // 还有这里，OK，这就可以了
			$this->view_root = $this->class_name;
		}

		/**
		 * TODO 我的
		 *
		 * 个人中心页
		 */
		public function mine()
		{
			// 若未登录，转到密码登录页
			($this->session->time_expire_login > time()) OR redirect( base_url('login') );

			// 若当前用户未设置密码，转到密码设置页
			if ( empty($this->session->password) )
				redirect( base_url('password_set') );

			// 页面信息
			$data = array(
				'title' => '我的', // 页面标题
				'class' => $this->class_name.' mine', // 页面body标签的class属性值
			);

			// 筛选条件
			$condition['user_id'] = $this->session->user_id;

			// 排序条件
			$order_by = NULL;
			//$order_by['name'] = 'value';

			// 从API服务器获取相应列表信息
			$params = $condition;
			$url = api_url($this->class_name. '/index');
			$result = $this->curl->go($url, $params, 'array');
			if ($result['status'] === 200):
				$data['items'] = $result['content'];
			else:
				//TODO redirect( base_url('error/code_404') ); // 若未成功获取信息，则转到错误页
			endif;

			// 输出视图
			$this->load->view('templates/header', $data);
			$this->load->view($this->view_root.'/mine', $data);
			$this->load->view('templates/nav-main', $data);
			$this->load->view('templates/footer', $data);
		} // end mine

		/**
		 * 密码登录
		 *
		 * 使用手机号及密码进行账户登录
		 *
		 * @return void
		 */
		public function login()
		{
			// 若已登录，转到首页
			!isset($this->session->time_expire_login) OR redirect( base_url() );

			// 页面信息
			$data = array(
				'title' => '密码登录',
				'class' => $this->class_name.' login',
			);
			
			$this->form_validation->set_rules('captcha_verify', '图片验证码', 'trim|required|exact_length[4]|callback_verify_captcha');
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
				$url = api_url($this->class_name. '/login');
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
					$user_data['time_expire_login'] = time() + 60*60*24 *30; // 默认登录状态保持30天
					$this->session->set_userdata($user_data);

					// 将用户手机号写入cookie并保存30天
					$this->input->set_cookie('mobile', $data['item']['mobile'], 60*60*24 *30, COOKIE_DOMAIN);
					
					// 若用户已设置密码则转到首页，否则转到密码设置页
					if ( !empty($data['item']['password']) ):
						redirect( base_url() );
					else:
						redirect( base_url('password_set') );
					endif;

				endif;

			endif;

			// 载入视图
			$this->load->view('templates/header', $data);
			$this->load->view($this->view_root.'/login', $data);
			$this->load->view('templates/footer', $data);
		} // end login

		/**
		 * 短信登录/注册
         *
         * @return void
		 */
		public function login_sms()
		{
			// 若已登录，转到首页
			!isset($this->session->time_expire_login) OR redirect( base_url() );

			// 页面信息
			$data = array(
				'title' => '短信登录/注册',
				'class' => $this->class_name.' login-sms',
			);

			$this->form_validation->set_rules('captcha_verify', '图片验证码', 'trim|required|exact_length[4]|callback_verify_captcha');
			$this->form_validation->set_rules('mobile', '手机号', 'trim|required|exact_length[11]');
			$this->form_validation->set_rules('sms_id', '短信ID', 'trim|required|is_natural_no_zero');
			$this->form_validation->set_rules('captcha', '短信验证码', 'trim|required|exact_length[6]|is_natural_no_zero');

			if ($this->form_validation->run() === FALSE):
				$data['error'] = validation_errors();

			else:
				$data_to_search = array(
					'mobile' => $this->input->post('mobile'),
					'sms_id' => $this->input->post('sms_id'),
					'captcha' => $this->input->post('captcha'),
					'user_ip' => $this->input->ip_address(),
				);

				// 从API服务器获取相应详情信息
				$params = $data_to_search;
				$url = api_url($this->class_name. '/login_sms');
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
					$user_data['time_expire_login'] = time() + 60*60*24 *30; // 默认登录状态保持30天
					$this->session->set_userdata($user_data);

					// 将用户手机号写入cookie并保存30天
					$this->input->set_cookie('mobile', $data['item']['mobile'], 60*60*24 *30, COOKIE_DOMAIN);

					// 若用户已设置密码则转到首页，否则转到密码设置页
					if ( !empty($data['item']['password']) ):
						redirect( base_url() );
					else:
						redirect( base_url('password_set') );
					endif;

				endif;

			endif;

			// 载入视图
			$this->load->view('templates/header', $data);
			$this->load->view($this->view_root.'/login_sms', $data);
			$this->load->view('templates/footer', $data);
		} // end login_sms

		/**
		 * 密码设置
		 *
		 * 未设置密码的用户可以设置密码
         *
         * @return void
		 */
		public function password_set()
		{
			// 若未登录，转到密码重置页
			( $this->session->time_expire_login > time() ) OR redirect( base_url('password_reset') );

			// 若当前用户已设置密码，转到密码修改页
			if ( !empty($this->session->password) )
				redirect( base_url('password_change') );

			// 页面信息
			$data = array(
				'title' => '密码设置',
				'class' => $this->class_name.' password-set',
			);

			$this->form_validation->set_rules('password', '密码', 'trim|required|min_length[6]|max_length[20]');
			$this->form_validation->set_rules('password_confirm', '确认密码', 'trim|required|matches[password]');

			if ($this->form_validation->run() === FALSE):
				$data['error'] = validation_errors();
				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/password_set', $data);
				$this->load->view('templates/footer', $data);

			else:
				$data_to_search = array(
					'user_id' => $this->session->user_id,
					'password' => $this->input->post('password'),
					'password_confirm' => $this->input->post('password_confirm'),
				);

				// 从API服务器获取相应详情信息
				$params = $data_to_search;
				$url = api_url($this->class_name. '/password_set');
				$result = $this->curl->go($url, $params, 'array');

				if ($result['status'] !== 200):
					$data['error'] = $result['content']['error']['message'];
					$this->load->view('templates/header', $data);
					$this->load->view($this->view_root.'/password_set', $data);
					$this->load->view('templates/footer', $data);

				else:
					// 更新本地用户密码字段
					$this->session->password = 'set';

					$data['title'] = '密码操作结果';
					$data['class'] = 'success';
					$data['content'] = '成功设置密码';
					
					$this->load->view('templates/header', $data);
					$this->load->view($this->view_root.'/result', $data);
					$this->load->view('templates/footer', $data);

				endif;

			endif;
		} // end password_set

		/**
		 * 密码修改
		 *
		 * 用户登录后可修改密码
		 *
		 * @return void
		 */
		public function password_change()
		{
			// 若未登录，转到密码重置页
			($this->session->time_expire_login > time()) OR redirect( base_url('password_reset') );

			// 若当前用户未设置密码，转到密码设置页
			if ( empty($this->session->password) )
				redirect( base_url('password_set') );

			// 页面信息
			$data = array(
				'title' => '修改密码',
				'class' => $this->class_name.' password-change',
				'id' => $this->session->user_id,
			);

			// 待验证的表单项
			$this->form_validation->set_rules('password_current', '原密码', 'trim|required|min_length[6]|max_length[20]');
			$this->form_validation->set_rules('password', '新密码', 'trim|required|min_length[6]|max_length[20]');
			$this->form_validation->set_rules('password_confirm', '确认密码', 'trim|required|matches[password]');
			
			if ($this->form_validation->run() === FALSE):
				$data['error'] = validation_errors();
				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/password_change', $data);
				$this->load->view('templates/footer', $data);

			// 新密码需要不同于原密码
			elseif ($this->input->post('password_current') === $this->input->post('password')):
				$data['error'] = '请设置不同于原密码的新密码';

				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/password_change', $data);
				$this->load->view('templates/footer', $data);

			else:
				// 需要存入数据库的信息
				$data_to_edit = array(
					'user_id' => $this->session->user_id,
					'password_current' => $this->input->post('password_current'),
					'password' => $this->input->post('password'),
					'password_confirm' => $this->input->post('password_confirm'),
				);

				// 从API服务器获取相应详情信息
				$params = $data_to_edit;
				$url = api_url($this->class_name. '/password_change');
				$result = $this->curl->go($url, $params, 'array');

				if ($result['status'] !== 200):
					$data['error'] = $result['content']['error']['message'];
					$this->load->view('templates/header', $data);
					$this->load->view($this->view_root.'/password_change', $data);
					$this->load->view('templates/footer', $data);

				else:
					$data['title'] = '密码操作结果';
					$data['class'] = 'success';
					$data['content'] = '成功修改密码';
					
					$this->load->view('templates/header', $data);
					$this->load->view($this->view_root.'/result', $data);
					$this->load->view('templates/footer', $data);

				endif;

			endif;
		} // end password_change

		/**
		 * 密码重置
		 *
		 * 用户使用短信验证码重置密码
		 *
		 * @return void
		 */
		public function password_reset()
		{
			//DECREPTAED 若已登录，转到密码修改页
			//!isset($this->session->time_expire_login) OR redirect( base_url('password_change') );

			// 清除当前SESSION
			$this->session->sess_destroy();

			// 页面信息
			$data = array(
				'title' => '密码重置',
				'class' => $this->class_name.' password-reset',
			);

			// 待验证的表单项
			$this->form_validation->set_rules('captcha_verify', '图片验证码', 'trim|required|exact_length[4]|callback_verify_captcha');
			$this->form_validation->set_rules('mobile', '手机号', 'trim|required|exact_length[11]');
			$this->form_validation->set_rules('sms_id', '短信ID', 'trim|required|is_natural_no_zero');
			$this->form_validation->set_rules('captcha', '短信验证码', 'trim|required|exact_length[6]|is_natural_no_zero');
			$this->form_validation->set_rules('password', '密码', 'trim|required|min_length[6]|max_length[20]');
			$this->form_validation->set_rules('password_confirm', '确认密码', 'trim|required|matches[password]');

			if ($this->form_validation->run() === FALSE):
				$data['error'] = validation_errors();
				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/password_reset', $data);
				$this->load->view('templates/footer', $data);

			else:
				$data_to_edit = array(
					'user_id' => $this->session->user_id,
					'mobile' => $this->input->post('mobile'),
					'sms_id' => $this->input->post('sms_id'),
					'captcha' => $this->input->post('captcha'),
					'password' => $this->input->post('password'),
					'password_confirm' => $this->input->post('password_confirm'),
				);

				// 从API服务器获取相应详情信息
				$params = $data_to_edit;
				$url = api_url($this->class_name. '/password_reset');
				$result = $this->curl->go($url, $params, 'array');

				if ($result['status'] !== 200):
					$data['error'] = $result['content']['error']['message'];
					$this->load->view('templates/header', $data);
					$this->load->view($this->view_root.'/password_reset', $data);
					$this->load->view('templates/footer', $data);

				else:
					// 转到密码登录页
					redirect( base_url('login') );

				endif;

			endif;

		} // end password_reset

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
			redirect( base_url('login') );
		} // end logout

		/**
		 * 验证图片验证码是否有效
		 *
		 * @params string $captcha 图片验证码内容
         * @return boolean
		 */
		public function verify_captcha($captcha)
		{
			// 依次验证是否存在有效期之内的图片验证码、验证码是否正确
			if (time() > $this->session->captcha_time_expire):
				$this->form_validation->set_message('verify_captcha', '验证码已失效');
				return FALSE;
			elseif ($captcha !== $this->session->captcha):
				$this->form_validation->set_message('verify_captcha', '验证码错误');
				return FALSE;
			else:
				return TRUE;
			endif;
		}

		/**
		 * TODO 邮箱注册；暂不开放此功能
		 *
		 * 使用邮箱及密码进行注册
		 *
		 * @return void
		 */
		public function register()
		{
			// 若已登录，转到首页
			!isset($this->session->time_expire_login) OR redirect( base_url() );

			// 页面信息
			$data = array(
				'title' => '邮箱注册',
				'class' => $this->class_name.' register',
			);

			$this->form_validation->set_rules('email', 'Email', 'trim|required|max_length[50]|valid_email');
			$this->form_validation->set_rules('password', '密码', 'trim|required|min_length[6]|max_length[20]');
			$this->form_validation->set_rules('password2', '确认密码', 'trim|required|matches[password]');

			if ($this->form_validation->run() === FALSE):
				$data['error'] = validation_errors();

			else:
				$data_to_create = array(
					'mobile' => $this->input->post('mobile'),
					'password' => sha1($this->input->post('password')),
				);

				// 从API服务器获取相应详情信息
				$params = $data_to_create;
				$url = api_url($this->class_name. '/register');
				$result = $this->curl->go($url, $params, 'array');

				if ($result['status'] !== 200):
					$data['error'] = $result['content']['error']['message'];
				else:
				endif;

			endif;
		} // end register
	}

/* End of file Account.php */
/* Location: ./application/controllers/Account.php */
