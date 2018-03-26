<?php
	defined('BASEPATH') OR exit('此文件不可被直接访问');

	/**
	 * Coupon_combo 优惠券包类
	 *
	 * @version 1.0.0
	 * @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	 * @copyright ICBG <www.bingshankeji.com>
	 */
	class Coupon_combo extends MY_Controller
	{
		/**
		 * 可作为列表筛选条件的字段名；可在具体方法中根据需要删除不需要的字段并转换为字符串进行应用，下同
		 */
		protected $names_to_sort = array(
			'biz_id', 'name', 'description', 'template_ids', 'max_amount', 'time_start', 'time_end',
			'time_create', 'time_delete', 'time_edit', 'creator_id', 'operator_id',
		);

		/**
		 * 可被编辑的字段名
		 */
		protected $names_edit_allowed = array(
			'name', 'description', 'template_ids', 'max_amount', 'time_start', 'time_end',
		);

		/**
		 * 完整编辑单行时必要的字段名
		 */
		protected $names_edit_required = array(
			'id', 'name', 'template_ids',
		);

		public function __construct()
		{
			parent::__construct();

			// 未登录用户转到登录页
			($this->session->time_expire_login > time()) OR redirect( base_url('login') );

			// 向类属性赋值
			$this->class_name = strtolower(__CLASS__);
			$this->class_name_cn = '优惠券包'; // 改这里……
			$this->table_name = 'coupon_combo'; // 和这里……
			$this->id_name = 'combo_id'; // 还有这里，OK，这就可以了
			$this->view_root = $this->class_name; // 视图文件所在目录
			$this->media_root = MEDIA_URL. $this->class_name.'/'; // 媒体文件所在目录

			// 设置需要自动在视图文件中生成显示的字段
			$this->data_to_display = array(
				'name' => '名称',
				'description' => '说明',
			);
		} // end __construct

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

			// 从API服务器获取相应详情信息
			$url = api_url($this->class_name. '/detail');
			$result = $this->curl->go($url, $params, 'array');
			if ($result['status'] === 200):
				$data['item'] = $result['content'];

                if ( !empty($data['item']['template_ids']) ):
                    unset($params['id']);
                    $params['ids'] = $data['item']['template_ids'];
                    $url = api_url('coupon_template/index');
                    $result = $this->curl->go($url, $params, 'array');
                    if ($result['status'] === 200):
                        $data['templates'] = $result['content'];
                    else:
                        $data['templates'] = NULL;
                    endif;
                endif;

                // 页面信息
                $data['title'] = $this->class_name_cn. $data['item'][$this->id_name];
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
			);

            // 获取当前商家所有优惠券数据
            $data['coupon_templates'] = $this->list_coupon_template();

			// 待验证的表单项
			$this->form_validation->set_error_delimiters('', '；');
			// 验证规则 https://www.codeigniter.com/user_guide/libraries/form_validation.html#rule-reference
			$this->form_validation->set_rules('name', '名称', 'trim|required|max_length[20]');
			$this->form_validation->set_rules('description', '说明', 'trim|max_length[30]');
			$this->form_validation->set_rules('template_ids[]', '所含优惠券', 'trim|required');
			$this->form_validation->set_rules('max_amount', '总限量', 'trim|greater_than_equal_to[0]|less_than_equal_to[999999]');
			$this->form_validation->set_rules('time_start', '领取开始时间', 'trim|exact_length[16]|callback_time_start');
			$this->form_validation->set_rules('time_end', '领取结束时间', 'trim|exact_length[16]|callback_time_end');
			$this->form_validation->set_message('time_start', '领取开始时间需详细到分，且晚于当前时间1分钟后');
			$this->form_validation->set_message('time_end', '领取结束时间需详细到分，且晚于当前时间1分钟后，亦不可早于开始时间（若有）');

			// 若表单提交不成功
			if ($this->form_validation->run() === FALSE):
				$data['error'] = validation_errors();

				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/create', $data);
				$this->load->view('templates/footer', $data);

			else:
				// 需要创建的数据；逐一赋值需特别处理的字段
				$data_to_create = array(
					'user_id' => $this->session->user_id,
                    'template_ids' => implode(',', $this->input->post('template_ids')),
                    'time_start' => empty($this->input->post('time_start'))? NULL: $this->strto_minute($this->input->post('time_start')), // 时间仅保留到分钟，下同
                    'time_end' => empty($this->input->post('time_end'))? NULL: $this->strto_minute($this->input->post('time_end')),
				);
				// 自动生成无需特别处理的数据
				$data_need_no_prepare = array(
					'name', 'description', 'max_amount',
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
		 * 编辑单行
		 */
		public function edit()
		{
			// 检查是否已传入必要参数
			$id = $this->input->get_post('id')? $this->input->get_post('id'): NULL;
			if ( !empty($id) ):
				$params['id'] = $id;
			else:
				redirect( base_url('error/code_400') ); // 若缺少参数，转到错误提示页
			endif;

			// 操作可能需要检查操作权限
			// $role_allowed = array('管理员', '经理'); // 角色要求
// 			$min_level = 30; // 级别要求
// 			$this->basic->permission_check($role_allowed, $min_level);

			// 页面信息
			$data = array(
				'title' => '修改'.$this->class_name_cn,
				'class' => $this->class_name.' edit',
			);

            // 获取当前商家所有优惠券数据
            $data['coupon_templates'] = $this->list_coupon_template();

			// 待验证的表单项
			$this->form_validation->set_error_delimiters('', '；');
			$this->form_validation->set_rules('name', '名称', 'trim|required|max_length[20]');
			$this->form_validation->set_rules('description', '说明', 'trim|max_length[30]');
			$this->form_validation->set_rules('template_ids[]', '所含优惠券', 'trim|required');
			$this->form_validation->set_rules('max_amount', '总限量', 'trim|greater_than_equal_to[0]|less_than_equal_to[999999]');
			$this->form_validation->set_rules('time_start', '领取开始时间', 'trim|exact_length[16]|callback_time_start');
			$this->form_validation->set_rules('time_end', '领取结束时间', 'trim|exact_length[16]|callback_time_end');
            $this->form_validation->set_message('time_start', '领取开始时间需详细到分，且晚于当前时间1分钟后');
            $this->form_validation->set_message('time_end', '领取结束时间需详细到分，且晚于当前时间1分钟后，亦不可早于开始时间（若有）');

			// 若表单提交不成功
			if ($this->form_validation->run() === FALSE):
				$data['error'] = validation_errors();

				// 从API服务器获取相应详情信息
				$params['id'] = $id;
				$url = api_url($this->class_name. '/detail');
				$result = $this->curl->go($url, $params, 'array');
				if ($result['status'] === 200):
					$data['item'] = $result['content'];
				else:
                    $data['item'] = array();
					redirect( base_url('error/code_404') ); // 若未成功获取信息，则转到错误页
				endif;

				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/edit', $data);
				$this->load->view('templates/footer', $data);

			else:
				// 需要编辑的数据；逐一赋值需特别处理的字段
				$data_to_edit = array(
					'user_id' => $this->session->user_id,
					'id' => $id,
                    'template_ids' => implode(',', $this->input->post('template_ids')),
                    'time_start' => empty($this->input->post('time_start'))? NULL: $this->strto_minute($this->input->post('time_start')), // 时间仅保留到分钟，下同
                    'time_end' => empty($this->input->post('time_end'))? NULL: $this->strto_minute($this->input->post('time_end')),

                );
				// 自动生成无需特别处理的数据
				$data_need_no_prepare = array(
					'name', 'description', 'max_amount',
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
					$data['error'] = $result['content']['error']['message'];

					$this->load->view('templates/header', $data);
					$this->load->view($this->view_root.'/edit', $data);
					$this->load->view('templates/footer', $data);

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
            exit('商家不可删除用户的'.$this->class_name_cn.'；您意图违规操作的记录已被发送到安全中心。');
        } // end delete

        /**
         * 找回
         *
         * 商家不可找回
         */
        public function restore()
        {
            exit('商家不可找回用户的'.$this->class_name_cn.'；您意图违规操作的记录已被发送到安全中心。');
        } // end restore
		
		/**
		 * 以下为工具类方法
		 */
		
		// 检查起始时间
		public function time_start($value)
		{
			if ( empty($value) ):
				return true;

			elseif (strlen($value) !== 16):
				return false;

			else:
				// 将精确到分的输入值拼合上秒值
				$time_to_check = strtotime($value.':00');

				// 该时间不可早于当前时间一分钟以内
				if ($time_to_check <= time() + 60):
					return false;
				else:
					return true;
				endif;

			endif;
		} // end time_start

		// 检查结束时间
		public function time_end($value)
		{
			if ( empty($value) ):
				return true;

			elseif (strlen($value) !== 16):
				return false;

			else:
				// 将精确到分的输入值拼合上秒值
				$time_to_check = strtotime($value.':00');

				// 该时间不可早于当前时间一分钟以内
				if ($time_to_check <= time() + 60):
					return false;

				// 若已设置开始时间，不可早于开始时间一分钟以内
				elseif ( !empty($this->input->post('time_start')) && $time_to_check < strtotime($this->input->post('time_start')) + 60):
					return false;

				else:
					return true;

				endif;

			endif;
		} // end time_end

	} // end class Coupon_combo

/* End of file Coupon_combo.php */
/* Location: ./application/controllers/Coupon_combo.php */
