<?php
	defined('BASEPATH') OR exit('此文件不可被直接访问');

	/**
	 * Freight_template_biz 商家运费模板类
	 *
	 * @version 1.0.0
	 * @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	 * @copyright ICBG <www.bingshankeji.com>
	 */
	class Freight_template_biz extends MY_Controller
	{
		/**
		 * 可作为列表筛选条件的字段名；可在具体方法中根据需要删除不需要的字段并转换为字符串进行应用，下同
		 */
        protected $names_to_sort = array(
            'biz_id', 'name', 'type', 'time_valid_from', 'time_valid_end', 'period_valid', 'expire_refund_rate', 'nation', 'province', 'city', 'county', 'longitude', 'latitude', 'time_latest_deliver', 'type_actual', 'max_amount', 'start_amount', 'unit_amount', 'fee_start', 'fee_unit', 'exempt_amount', 'exempt_subtotal', 'time_create', 'time_delete', 'time_edit', 'creator_id', 'operator_id',
        );

        /**
         * 可被编辑的字段名
         */
        protected $names_edit_allowed = array(
            'name', 'time_valid_from', 'time_valid_end', 'period_valid', 'expire_refund_rate', 'nation', 'province', 'city', 'county', 'longitude', 'latitude', 'time_latest_deliver', 'type_actual', 'max_amount', 'start_amount', 'unit_amount', 'fee_start', 'fee_unit', 'exempt_amount', 'exempt_subtotal',
        );

		/**
		 * 完整编辑单行时必要的字段名
		 */
		protected $names_edit_required = array(
			'id', 'name', 'type',
		);

		/**
		 * 编辑多行特定字段时必要的字段名
		 */
		protected $names_edit_bulk_required = array(
			'ids', 'password',
		);

		public function __construct()
		{
			parent::__construct();

			// （可选）未登录用户转到登录页
			($this->session->time_expire_login > time()) OR redirect( base_url('login') );

			// 向类属性赋值
			$this->class_name = strtolower(__CLASS__);
			$this->class_name_cn = '运费模板'; // 改这里……
			$this->table_name = 'freight_template_biz'; // 和这里……
			$this->id_name = 'template_id'; // 还有这里，OK，这就可以了
			$this->view_root = $this->class_name;

			// 设置需要自动在视图文件中生成显示的字段
			$this->data_to_display = array(
				'name' => '名称',
				'type' => '类型',
			);
		}

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
			else:
                $data['item'] = array();
				$data['error'] = $result['content']['error']['message'];
			endif;

			// 页面信息
			$data['title'] = $data['item']['name'];
			$data['class'] = $this->class_name.' detail';
			//$data['keywords'] = $this->class_name.','. $data['item']['name'];

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
				if ( !empty($this->input->post($sorter)) )
					$condition[$sorter] = $this->input->post($sorter);
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
				'error' => '', // 预设错误提示
			);

			// 待验证的表单项
			$this->form_validation->set_error_delimiters('', '；');
			// 验证规则 https://www.codeigniter.com/user_guide/libraries/form_validation.html#rule-reference
            $this->form_validation->set_rules('name', '名称', 'trim|required');
            $this->form_validation->set_rules('type', '类型', 'trim|required');
            $this->form_validation->set_rules('time_valid_from', '有效期起始时间', 'trim');
            $this->form_validation->set_rules('time_valid_end', '有效期结束时间', 'trim');
            $this->form_validation->set_rules('period_valid', '有效期（天）', 'trim');
            $this->form_validation->set_rules('expire_refund_rate', '过期退款比例', 'trim');
            $this->form_validation->set_rules('nation', '国别', 'trim');
            $this->form_validation->set_rules('province', '省', 'trim|required|max_length[10]');
            $this->form_validation->set_rules('city', '市', 'trim|required|max_length[10]');
            $this->form_validation->set_rules('county', '区/县', 'trim|required|max_length[10]');
            $this->form_validation->set_rules('longitude', '经度', 'trim|min_length[7]|max_length[10]|decimal');
            $this->form_validation->set_rules('latitude', '纬度', 'trim|min_length[7]|max_length[10]|decimal');
            $this->form_validation->set_rules('time_latest_deliver', '发货时间', 'trim');
            $this->form_validation->set_rules('type_actual', '运费计算方式', 'trim|in_list[计件,净重,毛重,体积重]');
            $this->form_validation->set_rules('max_amount', '每单最高配送量', 'trim|greater_than_equal_to[0]|less_than_equal_to[9999]');
            $this->form_validation->set_rules('start_amount', '首量', 'trim|greater_than_equal_to[0]|less_than_equal_to[9999]');
            $this->form_validation->set_rules('unit_amount', '续量', 'trim|greater_than_equal_to[0]|less_than_equal_to[9999]');
            $this->form_validation->set_rules('fee_start', '首量运费', 'trim|greater_than_equal_to[0]|less_than_equal_to[999]');
            $this->form_validation->set_rules('fee_unit', '续量运费', 'trim|greater_than_equal_to[0]|less_than_equal_to[999]');
            $this->form_validation->set_rules('exempt_amount', '包邮量', 'trim|greater_than_equal_to[0]|less_than_equal_to[9999]');
            $this->form_validation->set_rules('exempt_subtotal', '包邮订单小计', 'trim|greater_than_equal_to[0]|less_than_equal_to[9999]');

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
					'time_valid_from' => strtotime( $this->input->post('time_valid_from') ),
					'time_valid_end' => strtotime( $this->input->post('time_valid_end') ),
					'period_valid' => !empty('period_valid')? $this->input->post('period_valid') * 86400: 1,
                    'time_latest_deliver' => !empty('time_latest_deliver')? $this->input->post('time_latest_deliver'): 259200, // 默认3自然日
				);
				// 自动生成无需特别处理的数据
                $data_need_no_prepare = array(
                    'name', 'type', 'expire_refund_rate', 'nation', 'province', 'city', 'county', 'longitude', 'latitude', 'type_actual', 'max_amount', 'start_amount', 'unit_amount', 'fee_start', 'fee_unit', 'exempt_amount', 'exempt_subtotal',
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
				'error' => '', // 预设错误提示
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

			// 待验证的表单项
			$this->form_validation->set_error_delimiters('', '；');
            $this->form_validation->set_rules('name', '名称', 'trim|required');
            $this->form_validation->set_rules('time_valid_from', '有效期起始时间', 'trim');
            $this->form_validation->set_rules('time_valid_end', '有效期结束时间', 'trim');
            $this->form_validation->set_rules('period_valid', '有效期（天）', 'trim');
            $this->form_validation->set_rules('expire_refund_rate', '过期退款比例', 'trim');
            $this->form_validation->set_rules('nation', '国别', 'trim');
            $this->form_validation->set_rules('province', '省', 'trim|required|max_length[10]');
            $this->form_validation->set_rules('city', '市', 'trim|required|max_length[10]');
            $this->form_validation->set_rules('county', '区/县', 'trim|required|max_length[10]');
            $this->form_validation->set_rules('longitude', '经度', 'trim|min_length[7]|max_length[10]|decimal');
            $this->form_validation->set_rules('latitude', '纬度', 'trim|min_length[7]|max_length[10]|decimal');
            $this->form_validation->set_rules('time_latest_deliver', '发货时间', 'trim');
            $this->form_validation->set_rules('type_actual', '运费计算方式', 'trim|in_list[计件,净重,毛重,体积重]');
            $this->form_validation->set_rules('max_amount', '每单最高配送量', 'trim|greater_than_equal_to[0]|less_than_equal_to[9999]');
            $this->form_validation->set_rules('start_amount', '首量', 'trim|greater_than_equal_to[0]|less_than_equal_to[9999]');
            $this->form_validation->set_rules('unit_amount', '续量', 'trim|greater_than_equal_to[0]|less_than_equal_to[9999]');
            $this->form_validation->set_rules('fee_start', '首量运费', 'trim|greater_than_equal_to[0]|less_than_equal_to[999]');
            $this->form_validation->set_rules('fee_unit', '续量运费', 'trim|greater_than_equal_to[0]|less_than_equal_to[999]');
            $this->form_validation->set_rules('exempt_amount', '包邮量', 'trim|greater_than_equal_to[0]|less_than_equal_to[9999]');
            $this->form_validation->set_rules('exempt_subtotal', '包邮订单小计', 'trim|greater_than_equal_to[0]|less_than_equal_to[9999]');

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
					'time_valid_from' => strtotime( $this->input->post('time_valid_from') ),
					'time_valid_end' => strtotime( $this->input->post('time_valid_end') ),
					'period_valid' => !empty('period_valid')? $this->input->post('period_valid') * 86400: 1,
                    'time_latest_deliver' => !empty('time_latest_deliver')? $this->input->post('time_latest_deliver'): 259200, // 默认3自然日
				);
				// 自动生成无需特别处理的数据
				$data_need_no_prepare = array(
					'name', 'expire_refund_rate', 'nation', 'province', 'city', 'county', 'longitude', 'latitude', 'type_actual', 'max_amount', 'start_amount', 'unit_amount', 'fee_start', 'fee_unit', 'exempt_amount', 'exempt_subtotal',
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

	} // end class Freight_template_biz

/* End of file Freight_template_biz.php */
/* Location: ./application/controllers/Freight_template_biz.php */
