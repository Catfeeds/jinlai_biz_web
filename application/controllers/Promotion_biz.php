<?php
	defined('BASEPATH') OR exit('此文件不可被直接访问');

	/**
	 * Promotion_biz 店内活动类
	 *
	 * @version 1.0.0
	 * @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	 * @copyright ICBG <www.bingshankeji.com>
	 */
	class Promotion_biz extends MY_Controller
	{	
		/**
		 * 可作为列表筛选条件的字段名；可在具体方法中根据需要删除不需要的字段并转换为字符串进行应用，下同
		 */
		protected $names_to_sort = array(
			'time_start', 'time_end', 'fold_allowed', 'type', 'discount', 'present_trigger_amount', 'present', 'reduction_trigger_amount', 'reduction_trigger_count', 'reduction_amount', 'reduction_amount_time', 'reduction_discount', 'coupon_id', 'coupon_combo_id', 'deposit', 'balance', 'time_book_start', 'time_book_end', 'time_complete_start', 'time_complete_end', 'groupbuy_order_amount', 'groupbuy_quantity_max',
			'time_create', 'time_delete', 'time_edit', 'creator_id', 'operator_id',
		);

		/*
		 * 根据活动类型获取创建及编辑时的必要字段
		 */
		protected $names_required_by_type = array(
			'单品折扣' => array('', '',),
			'单品满赠' => array('', '',),
			'单品满减' => array('', '',),
			'单品赠券' => array('', '',),
			'单品预购' => array('', '',),
			'单品团购' => array('', '',),
			'订单折扣' => array('', '',),
			'订单满赠' => array('', '',),
			'订单满减' => array('', '',),
			'订单赠券' => array('', '',),
		);

		/**
		 * 可被编辑的字段名
		 */
		protected $names_edit_allowed = array(
			'name', 'description', 'time_start', 'time_end', 'fold_allowed', 'discount', 'present_trigger_amount', 'present', 'reduction_trigger_amount', 'reduction_trigger_count', 'reduction_amount', 'reduction_amount_time', 'reduction_discount', 'coupon_id', 'coupon_combo_id', 'deposit', 'balance', 'time_book_start', 'time_book_end', 'time_complete_start', 'time_complete_end', 'groupbuy_order_amount', 'groupbuy_quantity_max',
		);

		/**
		 * 完整编辑单行时必要的字段名
		 */
		protected $names_edit_required = array(
			'id', 'name', 'time_start', 'time_end',
		);

		public function __construct()
		{
			parent::__construct();

			// 未登录用户转到登录页
			($this->session->time_expire_login > time()) OR redirect( base_url('login') );

			// 向类属性赋值
			$this->class_name = strtolower(__CLASS__);
			$this->class_name_cn = '店内活动'; // 改这里……
			$this->table_name = 'promotion_biz'; // 和这里……
			$this->id_name = 'promotion_id'; // 还有这里，OK，这就可以了
			$this->view_root = $this->class_name; // 视图文件所在目录
			$this->media_root = MEDIA_URL. $this->class_name.'/'; // 媒体文件所在目录

			// 设置需要自动在视图文件中生成显示的字段
			$this->data_to_display = array(
				'name' => '名称',
				'type' => '类型',
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

                // 页面信息
                $data['title'] = $this->class_name_cn. ' "'.$data['item']['name']. '"';
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

            // 获取当前商家所有优惠券模板数据
            $data['coupon_templates'] = $this->list_coupon_template();

			// 待验证的表单项
			$this->form_validation->set_error_delimiters('', '；');
			// 验证规则 https://www.codeigniter.com/user_guide/libraries/form_validation.html#rule-reference
			$this->form_validation->set_rules('type', '活动类型', 'trim|required');
			$this->form_validation->set_rules('name', '名称', 'trim|required|max_length[20]');
			$this->form_validation->set_rules('time_start', '开始时间', 'trim|exact_length[16]|callback_time_start');
			$this->form_validation->set_rules('time_end', '结束时间', 'trim|exact_length[16]|callback_time_end');
			$this->form_validation->set_message('time_start', '开始时间需详细到分，且晚于当前时间1分钟后');
			$this->form_validation->set_message('time_end', '结束时间需详细到分，且晚于当前时间1分钟后');
			$this->form_validation->set_rules('description', '说明', 'trim');
			$this->form_validation->set_rules('url_image', '形象图', 'trim');
			$this->form_validation->set_rules('url_image_wide', '宽屏形象图', 'trim');
			$this->form_validation->set_rules('fold_allowed', '是否允许折上折', 'trim|required');
			$this->form_validation->set_rules('discount', '折扣率', 'trim');
			$this->form_validation->set_rules('present_trigger_amount', '赠品触发金额（元）', 'trim');
			$this->form_validation->set_rules('present_trigger_count', '赠品触发份数（份）', 'trim');
			$this->form_validation->set_rules('present', '赠品', 'trim');
			$this->form_validation->set_rules('reduction_trigger_amount', '满减触发金额（元）', 'trim');
			$this->form_validation->set_rules('reduction_trigger_count', '满减触发件数（件）', 'trim');
			$this->form_validation->set_rules('reduction_amount', '减免金额（元）', 'trim');
			$this->form_validation->set_rules('reduction_amount_time', '最高减免次数（次）', 'trim');
			$this->form_validation->set_rules('reduction_discount', '减免比例', 'trim');
			$this->form_validation->set_rules('coupon_id', '赠送优惠券模板', 'trim');
			$this->form_validation->set_rules('coupon_combo_id', '赠送优惠券套餐', 'trim');
			$this->form_validation->set_rules('deposit', '订金/预付款（元）', 'trim');
			$this->form_validation->set_rules('balance', '尾款（元）', 'trim');
			$this->form_validation->set_rules('time_book_start', '支付预付款开始时间', 'trim');
			$this->form_validation->set_rules('time_book_end', '支付预付款结束时间', 'trim');
			$this->form_validation->set_rules('time_complete_start', '支付尾款开始时间', 'trim');
			$this->form_validation->set_rules('time_complete_end', '支付尾款结束时间', 'trim');
			$this->form_validation->set_rules('groupbuy_order_amount', '团购成团订单数（单）', 'trim');
			$this->form_validation->set_rules('groupbuy_quantity_max', '团购个人最高限量（份/位）', 'trim');

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
                    'time_start' => empty($this->input->post('time_start'))? time(): $this->strto_minute($this->input->post('time_start')),
                    'time_end' => empty($this->input->post('time_end'))? time() + 2592000: $this->strto_minute($this->input->post('time_end')),
                    'time_book_start' => empty($this->input->post('time_book_start'))? NULL: $this->strto_minute($this->input->post('time_book_start')), // 时间仅保留到分钟，下同
                    'time_book_end' => empty($this->input->post('time_book_end'))? NULL: $this->strto_minute($this->input->post('time_book_end')),
                    'time_complete_start' => empty($this->input->post('time_complete_start'))? NULL: $this->strto_minute($this->input->post('time_complete_start')),
                    'time_complete_end' => empty($this->input->post('time_complete_end'))? NULL: $this->strto_minute($this->input->post('time_complete_end')),
                );
				// 自动生成无需特别处理的数据
				$data_need_no_prepare = array(
					'name', 'description', 'fold_allowed', 'type', 'discount', 'present_trigger_amount', 'present', 'reduction_trigger_amount', 'reduction_trigger_count', 'reduction_amount', 'reduction_amount_time', 'reduction_discount', 'coupon_id', 'coupon_combo_id', 'deposit', 'balance', 'groupbuy_order_amount', 'groupbuy_quantity_max',
				);
				foreach ($data_need_no_prepare as $name)
					$data_to_create[$name] = $this->input->post($name);

				// 向API服务器发送待创建数据
				$params = $data_to_create;
				$url = api_url($this->class_name. '/create');
				$result = $this->curl->go($url, $params, 'array');
				//var_dump($result);
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
				'error' => '',
			);
			
			// 从API服务器获取相应详情信息
			$params['id'] = $id;
			$url = api_url($this->class_name. '/detail');
			$result = $this->curl->go($url, $params, 'array');
			if ($result['status'] === 200):
				$data['item'] = $result['content'];
			else:
				redirect( base_url('error/code_404') ); // 若未成功获取信息，则转到错误页
			endif;

            // 获取当前商家所有优惠券模板数据
            $data['coupon_templates'] = $this->list_coupon_template();

			// 待验证的表单项
			$this->form_validation->set_error_delimiters('', '；');
			$this->form_validation->set_rules('name', '名称', 'trim|required|max_length[20]');
			$this->form_validation->set_rules('time_start', '开始时间', 'trim|exact_length[16]|callback_time_start');
			$this->form_validation->set_rules('time_end', '结束时间', 'trim|exact_length[16]|callback_time_end');
			$this->form_validation->set_message('time_start', '开始时间需详细到分，且晚于当前时间1分钟后');
			$this->form_validation->set_message('time_end', '结束时间需详细到分，且晚于当前时间1分钟后');
			$this->form_validation->set_rules('description', '说明', 'trim');
			$this->form_validation->set_rules('url_image', '形象图', 'trim');
			$this->form_validation->set_rules('url_image_wide', '宽屏形象图', 'trim');
			$this->form_validation->set_rules('fold_allowed', '是否允许折上折', 'trim|required');
			$this->form_validation->set_rules('discount', '折扣率', 'trim');
			$this->form_validation->set_rules('present_trigger_amount', '赠品触发金额（元）', 'trim');
			$this->form_validation->set_rules('present_trigger_count', '赠品触发份数（份）', 'trim');
			$this->form_validation->set_rules('present', '赠品', 'trim');
			$this->form_validation->set_rules('reduction_trigger_amount', '满减触发金额（元）', 'trim');
			$this->form_validation->set_rules('reduction_trigger_count', '满减触发件数（件）', 'trim');
			$this->form_validation->set_rules('reduction_amount', '减免金额（元）', 'trim');
			$this->form_validation->set_rules('reduction_amount_time', '最高减免次数（次）', 'trim');
			$this->form_validation->set_rules('reduction_discount', '减免比例', 'trim');
			$this->form_validation->set_rules('coupon_id', '赠送优惠券模板', 'trim');
			$this->form_validation->set_rules('coupon_combo_id', '赠送优惠券套餐', 'trim');
			$this->form_validation->set_rules('deposit', '订金/预付款（元）', 'trim');
			$this->form_validation->set_rules('balance', '尾款（元）', 'trim');
			$this->form_validation->set_rules('groupbuy_order_amount', '团购成团订单数（单）', 'trim');
			$this->form_validation->set_rules('groupbuy_quantity_max', '团购个人最高限量（份/位）', 'trim');
			
			$this->form_validation->set_rules('time_book_start', '支付预付款开始时间', 'trim|exact_length[16]|callback_time_book_start');
			$this->form_validation->set_rules('time_book_end', '支付预付款结束时间', 'trim|exact_length[16]|callback_time_book_end');
			$this->form_validation->set_message('time_book_start', '开始时间需详细到分，且晚于当前时间1分钟后');
			$this->form_validation->set_message('time_book_end', '结束时间需详细到分，且晚于当前时间1分钟后');
			
			$this->form_validation->set_rules('time_complete_start', '支付尾款开始时间', 'trim|exact_length[16]|callback_time_complete_start');
			$this->form_validation->set_rules('time_complete_end', '支付尾款结束时间', 'trim|exact_length[16]|callback_time_complete_end');
			$this->form_validation->set_message('time_complete_start', '开始时间需详细到分，且晚于当前时间1分钟后');
			$this->form_validation->set_message('time_complete_end', '结束时间需详细到分，且晚于当前时间1分钟后');

			// 若表单提交不成功
			if ($this->form_validation->run() === FALSE):
				$data['error'] = validation_errors();

				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/edit', $data);
				$this->load->view('templates/footer', $data);

			else:
				// 需要编辑的数据；逐一赋值需特别处理的字段
				$data_to_edit = array(
					'user_id' => $this->session->user_id,
					'id' => $id,
                    'time_start' => empty($this->input->post('time_start'))? time(): $this->strto_minute($this->input->post('time_start')),
                    'time_end' => empty($this->input->post('time_end'))? time() + 2592000: $this->strto_minute($this->input->post('time_end')),
                    'time_book_start' => empty($this->input->post('time_book_start'))? NULL: $this->strto_minute($this->input->post('time_book_start')), // 时间仅保留到分钟，下同
                    'time_book_end' => empty($this->input->post('time_book_end'))? NULL: $this->strto_minute($this->input->post('time_book_end')),
                    'time_complete_start' => empty($this->input->post('time_complete_start'))? NULL: $this->strto_minute($this->input->post('time_complete_start')),
                    'time_complete_end' => empty($this->input->post('time_complete_end'))? NULL: $this->strto_minute($this->input->post('time_complete_end')),
				);
				// 自动生成无需特别处理的数据
				$data_need_no_prepare = array(
					'name', 'description', 'fold_allowed', 'discount', 'present_trigger_amount', 'present', 'reduction_trigger_amount', 'reduction_trigger_count', 'reduction_amount', 'reduction_amount_time', 'reduction_discount', 'coupon_id', 'coupon_combo_id', 'deposit', 'balance', 'groupbuy_order_amount', 'groupbuy_quantity_max',
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
		 * 开始
		 */
		public function publish()
		{
            // 检查必要参数是否已传入
            if ( empty($this->input->post_get('ids')))
                redirect( base_url('error/code_400') ); // 若缺少参数，转到错误提示页

            // 操作可能需要检查操作权限
            // $role_allowed = array('管理员', '经理'); // 角色要求
// 			$min_level = 30; // 级别要求
// 			$this->basic->permission_check($role_allowed, $min_level);

            $op_name = '开始'; // 操作的名称
            $op_view = 'publish'; // 操作名、视图文件名

            // 赋值视图中需要用到的待操作项数据
            $ids = $this->parse_ids_array(); // 数组格式，已去掉重复项及空项
            $ids_string = implode(',', $ids); // 字符串格式

            // 页面信息
            $data = array(
                'title' => $op_name. $this->class_name_cn,
                'class' => $this->class_name. ' '. $op_view,
                'error' => '', // 预设错误提示

                'op_name' => $op_view,
                'ids' => $ids_string,
            );

            // 获取待操作项数据
            $params = array('ids' => $ids_string);
            $url = api_url($this->class_name.'/index');
            $data['items'] = $this->curl->go($url, $params, 'array')['content'];

			// 将需要显示的数据传到视图以备使用
			$data['data_to_display'] = $this->data_to_display;

			// 待验证的表单项
			$this->form_validation->set_error_delimiters('', '；');
			$this->form_validation->set_rules('ids', '待操作数据ID们', 'trim|required|regex_match[/^(\d|\d,?)+$/]'); // 仅允许非零整数和半角逗号
			$this->form_validation->set_rules('password', '密码', 'trim|required|min_length[6]|max_length[20]');

			// 若表单提交不成功
			if ($this->form_validation->run() === FALSE):
				$data['error'] .= validation_errors();

				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/'.$op_view, $data);
				$this->load->view('templates/footer', $data);

			else:
				// 检查必要参数是否已传入
				$required_params = $this->names_edit_bulk_required;
				foreach ($required_params as $param):
					${$param} = $this->input->post($param);
					if ( empty( ${$param} ) ):
						$data['error'] = '必要的请求参数未全部传入';
						$this->load->view('templates/header', $data);
						$this->load->view($this->view_root.'/'.$op_view, $data);
						$this->load->view('templates/footer', $data);
						exit();
					endif;
				endforeach;

				// 需要存入数据库的信息
				$data_to_edit = array(
					'user_id' => $this->session->user_id,
					'ids' => $ids,
					'password' => $password,
					'operation' => $op_view, // 操作名称
				);

				// 向API服务器发送待创建数据
				$params = $data_to_edit;
				$url = api_url($this->class_name. '/edit_bulk');
				$result = $this->curl->go($url, $params, 'array');
				if ($result['status'] === 200):
					$data['title'] = $this->class_name_cn.$op_name. '成功';
					$data['class'] = 'success';
					$data['content'] = $result['content']['message'];
					$data['operation'] = 'bulk';
					$data['ids'] = $ids;

					$this->load->view('templates/header', $data);
					$this->load->view($this->view_root.'/result', $data);
					$this->load->view('templates/footer', $data);

				else:
					// 若创建失败，则进行提示
					$data['error'] .= $result['content']['error']['message'];

					$this->load->view('templates/header', $data);
					$this->load->view($this->view_root.'/'.$op_view, $data);
					$this->load->view('templates/footer', $data);
				endif;

			endif;
		} // end publish
		
		/**
		 * 结束
		 */
		public function suspend()
		{
            // 检查必要参数是否已传入
            if ( empty($this->input->post_get('ids')))
                redirect( base_url('error/code_400') ); // 若缺少参数，转到错误提示页

            // 操作可能需要检查操作权限
            // $role_allowed = array('管理员', '经理'); // 角色要求
// 			$min_level = 30; // 级别要求
// 			$this->basic->permission_check($role_allowed, $min_level);

            $op_name = '结束'; // 操作的名称
            $op_view = 'suspend'; // 操作名、视图文件名

            // 赋值视图中需要用到的待操作项数据
            $ids = $this->parse_ids_array(); // 数组格式，已去掉重复项及空项
            $ids_string = implode(',', $ids); // 字符串格式

            // 页面信息
            $data = array(
                'title' => $op_name. $this->class_name_cn,
                'class' => $this->class_name. ' '. $op_view,
                'error' => '', // 预设错误提示

                'op_name' => $op_view,
                'ids' => $ids_string,
            );

            // 获取待操作项数据
            $params = array('ids' => $ids_string);
            $url = api_url($this->class_name.'/index');
            $data['items'] = $this->curl->go($url, $params, 'array')['content'];

			// 将需要显示的数据传到视图以备使用
			$data['data_to_display'] = $this->data_to_display;

			// 待验证的表单项
			$this->form_validation->set_error_delimiters('', '；');
			$this->form_validation->set_rules('ids', '待操作数据ID们', 'trim|required|regex_match[/^(\d|\d,?)+$/]'); // 仅允许非零整数和半角逗号
			$this->form_validation->set_rules('password', '密码', 'trim|required|min_length[6]|max_length[20]');

			// 若表单提交不成功
			if ($this->form_validation->run() === FALSE):
				$data['error'] .= validation_errors();

				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/'.$op_view, $data);
				$this->load->view('templates/footer', $data);

			else:
				// 检查必要参数是否已传入
				$required_params = $this->names_edit_bulk_required;
				foreach ($required_params as $param):
					${$param} = $this->input->post($param);
					if ( empty( ${$param} ) ):
						$data['error'] = '必要的请求参数未全部传入';
						$this->load->view('templates/header', $data);
						$this->load->view($this->view_root.'/'.$op_view, $data);
						$this->load->view('templates/footer', $data);
						exit();
					endif;
				endforeach;

				// 需要存入数据库的信息
				$data_to_edit = array(
					'user_id' => $this->session->user_id,
					'ids' => $ids,
					'password' => $password,
					'operation' => $op_view, // 操作名称
				);

				// 向API服务器发送待创建数据
				$params = $data_to_edit;
				$url = api_url($this->class_name. '/edit_bulk');
				$result = $this->curl->go($url, $params, 'array');
				if ($result['status'] === 200):
					$data['title'] = $this->class_name_cn.$op_name. '成功';
					$data['class'] = 'success';
					$data['content'] = $result['content']['message'];
					$data['operation'] = 'bulk';
					$data['ids'] = $ids;

					$this->load->view('templates/header', $data);
					$this->load->view($this->view_root.'/result', $data);
					$this->load->view('templates/footer', $data);

				else:
					// 若创建失败，则进行提示
					$data['error'] .= $result['content']['error']['message'];

					$this->load->view('templates/header', $data);
					$this->load->view($this->view_root.'/'.$op_view, $data);
					$this->load->view('templates/footer', $data);
				endif;

			endif;
		} // end suspend
		
		/**
		 * 以下为工具类方法
		 */

        /**
         * 检查起始时间
         *
         * @param string $value
         * @param bool $later_than_now 是否允许早于当前时间，默认不允许
         * @return bool
         */
		public function time_start($value, $later_than_now = FALSE)
		{
			if ( empty($value) ):
				return true;

			elseif (strlen($value) !== 16):
				return false;

			else:
				// 将精确到分的输入值拼合上秒值
				$time_to_check = strtotime($value.':00');

				// 该时间不可早于当前时间一分钟以内
				if ($later_than_now === FALSE && $time_to_check <= time() + 60):
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
		
		// 检查起始时间
		public function time_book_start($value)
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
		} // end time_book_start

		// 检查结束时间
		public function time_book_end($value)
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
				elseif ( !empty($this->input->post('time_book_start')) && $time_to_check < strtotime($this->input->post('time_book_start')) + 60):
					return false;

				else:
					return true;

				endif;

			endif;
		} // end time_book_end
		
		// 检查起始时间
		public function time_complete_start($value)
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
		} // end time_complete_start

		// 检查结束时间
		public function time_complete_end($value)
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
				elseif ( !empty($this->input->post('time_complete_start')) && $time_to_check < strtotime($this->input->post('time_complete_start')) + 60):
					return false;

				else:
					return true;

				endif;

			endif;
		} // end time_complete_end

	} // end class Promotion_biz

/* End of file Promotion_biz.php */
/* Location: ./application/controllers/Promotion_biz.php */
