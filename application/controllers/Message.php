<?php
	defined('BASEPATH') OR exit('此文件不可被直接访问');

	/**
	 * Message/MSG 聊天消息类
	 *
	 * @version 1.0.0
	 * @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	 * @copyright ICBG <www.bingshankeji.com>
	 */
	class Message extends MY_Controller
	{	
		/**
		 * 可作为列表筛选条件的字段名；可在具体方法中根据需要删除不需要的字段并转换为字符串进行应用，下同
		 */
		protected $names_to_sort = array(
			'user_id', 'stuff_id', 'sender_type', 'receiver_type', 'type', 'ids', 'longitude', 'latitude', 'time_create', 'time_delete', 'time_revoke', 'creator_id',  'time_create_min', 'time_create_max',
		);

		public function __construct()
		{
			parent::__construct();

			// 未登录用户转到登录页
			($this->session->time_expire_login > time()) OR redirect( base_url('login') );

			// 向类属性赋值
			$this->class_name = strtolower(__CLASS__);
			$this->class_name_cn = '聊天消息'; // 改这里……
			$this->table_name = 'message'; // 和这里……
			$this->id_name = 'message_id'; // 还有这里，OK，这就可以了
			$this->view_root = $this->class_name; // 视图文件所在目录
			$this->media_root = MEDIA_URL. $this->class_name.'/'; // 媒体文件所在目录

			// 设置需要自动在视图文件中生成显示的字段
			$this->data_to_display = array(
                'sender_type' => '发信端类型',
                'receiver_type' => '收信端类型',
				'type' => '类型',
			);
		} // end __construct

		/**
		 * 列表页
		 */
		public function index()
		{
            parent::index();

            // 检查是否已传入必要参数
            $id = $this->input->get_post('user_id');
            if ( empty($id) )
                redirect( base_url('error/code_400') ); // 若缺少参数，转到错误提示页

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

            // 获取聊天用户、商家信息
            $data['user'] = $this->get_user($id);
            $data['biz'] = $this->get_biz($this->session->biz_id);

            // 页面信息
            $data = array_merge(
                $data,
                array(
                    'title' => '对话'. $data['user']['nickname'],
                    'class' => $this->class_name.' index',
                )
            );

			// 从API服务器获取相应列表信息
			/*$params = $condition;
			$url = api_url($this->class_name. '/index');
			$result = $this->curl->go($url, $params, 'array');
			if ($result['status'] === 200):
				$data['items'] = $result['content'];
			else:
				$data['items'] = array();
				$data['error'] = $result['content']['error']['message'];
			endif;*/

			// 输出视图
			$this->load->view('templates/header', $data);
			$this->load->view($this->view_root.'/index', $data);
			$this->load->view('templates/footer', $data);
		} // end index

		/**
		 * 创建
		 */
		public function create()
		{
			// 操作可能需要检查操作权限
			// $role_allowed = array('管理员', '经理'); // 角色要求
// 			$min_level = 30; // 级别要求
// 			$this->basic->permission_check($role_allowed, $min_level);

			// 页面信息
			$data = array(
				'title' => '创建'.$this->class_name_cn,
				'class' => $this->class_name.' create',
				'error' => '', // 预设错误提示
			);

			// 待验证的表单项
			$this->form_validation->set_error_delimiters('', '；');
			// 验证规则 https://www.codeigniter.com/user_guide/libraries/form_validation.html#rule-reference
            $this->form_validation->set_rules('user_id', '收信用户ID', 'trim|is_natural_no_zero');
            $this->form_validation->set_rules('biz_id', '收信商家ID', 'trim|is_natural_no_zero');
            $this->form_validation->set_rules('stuff_id', '收信员工ID', 'trim|is_natural_no_zero');
            $this->form_validation->set_rules('sender_type', '发信端类型', 'trim|in_list[admin,biz,client]');
            $this->form_validation->set_rules('receiver_type', '收信端类型', 'trim|required|in_list[admin,biz,client]');
            $this->form_validation->set_rules('type', '类型', 'trim|required');
            $this->form_validation->set_rules('ids', '内容ID们', 'trim|max_length[255]');
            $this->form_validation->set_rules('title', '标题', 'trim|max_length[30]');
            $this->form_validation->set_rules('excerpt', '摘要', 'trim|max_length[100]');
            $this->form_validation->set_rules('url_image', '形象图', 'trim|max_length[255]');
            $this->form_validation->set_rules('content', '内容', 'trim|max_length[5000]');
            $this->form_validation->set_rules('longitude', '经度', 'trim|max_length[10]');
            $this->form_validation->set_rules('latitude', '纬度', 'trim|max_length[10]');

			// 若表单提交不成功
			if ($this->form_validation->run() === FALSE):
				$data['error'] = validation_errors();

				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/create', $data);
				$this->load->view('templates/footer', $data);

			else:
				// 需要创建的数据；逐一赋值需特别处理的字段
				$data_to_create = array(
					'creator_id' => $this->session->user_id,

                    'sender_type' => $this->app_type,
                    'receiver_type' => empty($this->input->post('receiver_type'))? 'biz': $this->input->post('receiver_type'),

                    'type' => empty($this->input->post('type'))? 'text': $this->input->post('type'),
				);
				// 自动生成无需特别处理的数据
				$data_need_no_prepare = array(
                    'user_id', 'biz_id', 'stuff_id', 'ids', 'title', 'excerpt', 'url_image', 'content', 'longitude', 'latitude',
				);
				foreach ($data_need_no_prepare as $name)
					$data_to_create[$name] = $this->input->post($name);

				// 向API服务器发送待创建数据
				$params = $data_to_create;
				$url = api_url($this->class_name. '/create');
				$result = $this->curl->go($url, $params, 'array');
				if ($result['status'] === 200):
					$data['title'] = $this->class_name_cn. '创建成功';
					$data['class'] = 'success';
					$data['content'] = $result['content']['message'];
					$data['operation'] = 'create';
					$data['id'] = $result['content']['id']; // 创建后的信息ID

					$this->load->view('templates/header', $data);
					$this->load->view($this->view_root.'/result', $data);
					$this->load->view('templates/footer', $data);

				else:
					// 若创建失败，则进行提示
					$data['error'] = $result['content']['error']['message'];

					$this->load->view('templates/header', $data);
					$this->load->view($this->view_root.'/create', $data);
					$this->load->view('templates/footer', $data);

				endif;
				
			endif;
		} // end create

		/**
         * 删除
         *
         * 不可删除
         */
        public function delete()
        {
            exit('不可删除'.$this->class_name_cn);
        } // end delete

        /**
         * 找回
         *
         * 不可找回
         */
        public function restore()
        {
            exit('不可恢复'.$this->class_name_cn);
        } // end restore

        /**
         * TODO 撤回
         */
        public function revoke()
        {

        } // end revoke
		
		/**
		 * 以下为工具类方法
		 */

	} // end class Message

/* End of file Message.php */
/* Location: ./application/controllers/Message.php */
