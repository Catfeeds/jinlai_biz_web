<?php
	defined('BASEPATH') OR exit('此文件不可被直接访问');

	/**
	 * User 用户类
	 *
	 * @version 1.0.0
	 * @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	 * @copyright ICBG <www.bingshankeji.com>
	 */
	class User extends MY_Controller
	{
		/**
		 * 可作为列表筛选条件的字段名；可在具体方法中根据需要删除不需要的字段并转换为字符串进行应用，下同
		 */
		protected $names_to_sort = array(
			'nickname', 'lastname', 'firstname', 'gender', 'dob', 'avatar', 'mobile', 'email', 'wechat_union_id', 'address_id', 'last_login_timestamp', 'last_login_ip',
            'time_create', 'time_delete', 'time_edit', 'creator_id', 'operator_id',
		);

		/**
		 * 可被编辑的字段名
		 */
		protected $names_edit_allowed = array(
			'nickname', 'lastname', 'firstname', 'code_ssn', 'url_image_id', 'gender', 'dob', 'avatar', 'email', 'address_id', 'bank_name', 'bank_account',
		);

		/**
		 * 完整编辑单行时必要的字段名
		 */
		protected $names_edit_required = array(
			'id',
		);

		public function __construct()
		{
			parent::__construct();

			// 未登录用户转到登录页
			($this->session->time_expire_login > time()) OR redirect( base_url('login') );

			// 向类属性赋值
			$this->class_name = strtolower(__CLASS__);
			$this->class_name_cn = '用户'; // 改这里……
			$this->table_name = 'user'; // 和这里……
			$this->id_name = 'user_id'; // 还有这里，OK，这就可以了
			$this->view_root = $this->class_name; // 视图文件所在目录
			$this->media_root = MEDIA_URL. $this->class_name.'/'; // 媒体文件所在目录

			// 设置需要自动在视图文件中生成显示的字段
			$this->data_to_display = array(
				'mobile' => '手机号',
				'nickname' => '昵称',
			);
		} // end __construct

		/**
		 * 我的
		 *
		 * 限定获取的行的user_id（示例为通过session传入的user_id值），一般用于前台
		 */
		public function mine()
		{
		    // 获取当前用户信息
            $params['id'] = $this->session->user_id;

			// 从API服务器获取相应详情信息
			$url = api_url($this->class_name. '/detail');
			$result = $this->curl->go($url, $params, 'array');
			if ($result['status'] === 200):
				$data['item'] = $result['content'];
			else:
                $data['item'] = array();
				$data['error'] = $result['content']['error']['message'];
			endif;

			// 页面信息
            $data['title'] = $data['item']['nickname'];
			$data['class'] = $this->class_name.' mine';

			// 输出视图
			$this->load->view('templates/header', $data);
			$this->load->view($this->view_root.'/mine', $data);
			$this->load->view('templates/footer', $data);
		} // end mine

		/**
		 * 列表页
		 */
		public function index()
		{
			// 页面信息
			$data = array(
				'title' => $this->class_name_cn. '列表',
				'class' => $this->class_name.' index',
			);

			// 筛选条件
			$condition['time_delete'] = 'NULL';
			// （可选）遍历筛选条件
			foreach ($this->names_to_sort as $sorter):
				if ( !empty($this->input->get_post($sorter)) )
					$condition[$sorter] = $this->input->get_post($sorter);
			endforeach;

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
                $data['items'] = array();
				$data['error'] = $result['content']['error']['message'];
			endif;

			// 将需要显示的数据传到视图以备使用
			$data['data_to_display'] = $this->data_to_display;

			// 输出视图
			$this->load->view('templates/header', $data);
			$this->load->view($this->view_root.'/index', $data);
			$this->load->view('templates/footer', $data);
		} // end index

		/**
		 * 详情页
		 */
		public function detail()
		{
			// 检查是否已传入必要参数
			$id = $this->input->get_post('id')? $this->input->get_post('id'): NULL;
			if ( !empty($id) ):
				$params['id'] = $id;
			else:
				redirect( base_url('error/code_400') ); // 若缺少参数，转到错误提示页
			endif;

			// 若为当前用户，则转到我的页面
			if ($id == $this->session->user_id) redirect(base_url('user/mine'));

            // 从API服务器获取相应详情信息
            $url = api_url($this->class_name. '/detail');
            $result = $this->curl->go($url, $params, 'array');
            if ($result['status'] === 200):
                $data['item'] = $result['content'];

                // 页面信息
                $data['title'] = $this->class_name_cn. ' "'.$data['item']['nickname']. '"';
                $data['class'] = $this->class_name.' detail';

            else:
                redirect( base_url('error/code_404') ); // 若缺少参数，转到错误提示页

            endif;

			// 输出视图
			$this->load->view('templates/header', $data);
			$this->load->view($this->view_root.'/detail', $data);
			$this->load->view('templates/footer', $data);
		} // end detail

		/**
		 * 回收站
		 */
		public function trash()
		{
			// 操作可能需要检查操作权限
			$role_allowed = array('管理员', '经理'); // 角色要求
			$min_level = 30; // 级别要求
			$this->permission_check($role_allowed, $min_level);

			// 页面信息
			$data = array(
				'title' => $this->class_name_cn. '回收站',
				'class' => $this->class_name.' trash',
			);

			// 筛选条件
			$condition['time_delete'] = 'IS NOT NULL';
			// （可选）遍历筛选条件
            foreach ($this->names_to_sort as $sorter):
                if ( !empty($this->input->get_post($sorter)) )
                    $condition[$sorter] = $this->input->get_post($sorter);
            endforeach;

			// 排序条件
			$order_by['time_delete'] = 'DESC';

			// 从API服务器获取相应列表信息
			$params = $condition;
			$url = api_url($this->class_name. '/index');
			$result = $this->curl->go($url, $params, 'array');
			if ($result['status'] === 200):
				$data['items'] = $result['content'];
			else:
                $data['items'] = array();
				$data['error'] = $result['content']['error']['message'];
			endif;

			// 将需要显示的数据传到视图以备使用
			$data['data_to_display'] = $this->data_to_display;

			// 输出视图
			$this->load->view('templates/header', $data);
			$this->load->view($this->view_root.'/trash', $data);
			$this->load->view('templates/footer', $data);
		} // end trash

		/**
		 * 编辑单行
		 */
		public function edit()
		{
			// 检查是否已传入必要参数
			$id = $this->input->get_post('id')? $this->input->get_post('id'): $this->session->user_id;
			if ( !empty($id) ):
				$params['id'] = $id;
			else:
				redirect( base_url('error/code_400') ); // 若缺少参数，转到错误提示页
			endif;

			// 页面信息
			$data = array(
				'title' => '修改'.$this->class_name_cn,
				'class' => $this->class_name.' edit',
				'error' => '',
			);

			// 用户仅可修改自己的资料
			if ( $this->session->user_id != $id ):
				$data['error'] .= '仅可修改自己的资料';

				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/edit', $data);
				$this->load->view('templates/footer', $data);

			else:
				// 从API服务器获取相应详情信息
				$params['id'] = $id;
				$url = api_url($this->class_name. '/detail');
				$result = $this->curl->go($url, $params, 'array');
				if ($result['status'] === 200):
					$data['item'] = $result['content'];
				else:
					redirect( base_url('error/code_404') ); // 若未成功获取信息，则转到错误页
				endif;

				// 待验证的表单项
				$this->form_validation->set_error_delimiters('', '；');
                $this->form_validation->set_rules('avatar', '头像', 'trim|max_length[255]');
				$this->form_validation->set_rules('nickname', '昵称', 'trim|max_length[12]');
				$this->form_validation->set_rules('lastname', '姓氏', 'trim|max_length[9]');
				$this->form_validation->set_rules('firstname', '名', 'trim|max_length[6]');
				$this->form_validation->set_rules('gender', '性别', 'trim|in_list[男,女]');
				$this->form_validation->set_rules('dob', '生日（公历/阳历）', 'trim|exact_length[10]|callback_time_dob');
                $this->form_validation->set_message('time_dob', SITE_NAME.'目前仅面向14-120岁之间的用户进行服务');

				// 若表单提交不成功
				if ($this->form_validation->run() === FALSE):
					$data['error'] .= validation_errors();

					$this->load->view('templates/header', $data);
					$this->load->view($this->view_root.'/edit', $data);
					$this->load->view('templates/footer', $data);

				else:
					// 需要编辑的数据；逐一赋值需特别处理的字段
					$data_to_edit = array(
						'user_id' => $this->session->user_id,
						'id' => $id,
					);
					// 自动生成无需特别处理的数据
					$data_need_no_prepare = array(
						'nickname', 'lastname', 'firstname', 'gender', 'dob', 'avatar',
					);
					foreach ($data_need_no_prepare as $name)
						$data_to_edit[$name] = $this->input->post($name);

					// 向API服务器发送待创建数据
					$params = $data_to_edit;
					$url = api_url($this->class_name. '/edit');
					$result = $this->curl->go($url, $params, 'array');
					if ($result['status'] === 200):
						$data['title'] = $this->class_name_cn. '修改成功';
						$data['class'] = 'success';
						$data['content'] = $result['content']['message'];
						$data['operation'] = 'edit';
						$data['id'] = $id;

						$this->load->view('templates/header', $data);
						$this->load->view($this->view_root.'/result', $data);
						$this->load->view('templates/footer', $data);

					else:
						// 若创建失败，则进行提示
						$data['error'] .= $result['content']['error']['message'];

						$this->load->view('templates/header', $data);
						$this->load->view($this->view_root.'/edit', $data);
						$this->load->view('templates/footer', $data);

					endif;

				endif;

			endif;
		} // end edit

        /**
         * 删除
         *
         * 商家不可删除
         */
        public function delete()
        {
            exit('不可删除用户；您意图违规操作的记录已被发送到安全中心。');
        } // end delete

        /**
         * 找回
         *
         * 商家不可找回
         */
        public function restore()
        {
            exit('不可找回用户；您意图违规操作的记录已被发送到安全中心。');
        } // end restore
		
		/**
		 * 以下为工具类方法
		 */

	} // end class User

/* End of file User.php */
/* Location: ./application/controllers/User.php */
