<?php
	defined('BASEPATH') OR exit('此文件不可被直接访问');

	/**
	 * Freight_template_biz 商家运费模板类
	 *
	 * 以我的XX列表、列表、详情、创建、单行编辑、单/多行编辑（删除、恢复）等功能提供了常见功能的APP示例代码
	 * CodeIgniter官方网站 https://www.codeigniter.com/user_guide/
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
			'template_id', 'biz_id', 'name', 'type', 'time_valid_from', 'time_valid_end', 'period_valid', 'expire_refund_rate', 'type_actual', 'time_latest_deliver', 'max_count', 'max_net', 'max_gross', 'max_volume', 'fee_count_start', 'fee_net_start', 'fee_gross_start', 'fee_volumn_start', 'fee_count_amount', 'fee_net_amount', 'fee_gross_amount', 'fee_volumn_amount', 'fee_count', 'fee_net', 'fee_gross', 'fee_volume', 'time_create', 'time_delete', 'time_edit', 'creator_id', 'operator_id',
		);

		/**
		 * 可被编辑的字段名
		 */
		protected $names_edit_allowed = array(
			'name', 'time_valid_from', 'time_valid_end', 'period_valid', 'expire_refund_rate', 'type_actual', 'time_latest_deliver', 'max_count', 'max_net', 'max_gross', 'max_volume', 'fee_count_start', 'fee_net_start', 'fee_gross_start', 'fee_volumn_start', 'fee_count_amount', 'fee_net_amount', 'fee_gross_amount', 'fee_volumn_amount', 'fee_count', 'fee_net', 'fee_gross', 'fee_volume',
		);

		/**
		 * 完整编辑单行时必要的字段名
		 */
		protected $names_edit_required = array(
			'id',
			'name',
		);
		
		/**
		 * 编辑单行特定字段时必要的字段名
		 */
		protected $names_edit_certain_required = array(
			'id', 'name', 'value',
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
			//($this->session->time_expire_login > time()) OR redirect( base_url('login') );

			// 向类属性赋值
			$this->class_name = strtolower(__CLASS__);
			$this->class_name_cn = '商家运费模板'; // 改这里……
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
		 * 截止3.1.3为止，CI_Controller类无析构函数，所以无需继承相应方法
		 */
		public function __destruct()
		{
			// 调试信息输出开关
			// $this->output->enable_profiler(TRUE);
		}

		/**
		 * 我的
		 *
		 * 限定获取的行的user_id（示例为通过session传入的user_id值），一般用于前台
		 */
		public function mine()
		{
			// 页面信息
			$data = array(
				'title' => '我的'. $this->class_name_cn, // 页面标题
				'class' => $this->class_name.' mine', // 页面body标签的class属性值
				
				'keywords' => '关键词一,关键词二,关键词三', // （可选，后台功能可删除此行）页面关键词；每个关键词之间必须用半角逗号","分隔才能保证搜索引擎兼容性
				'description' => '这个页面的主要内容', // （可选，后台功能可删除此行）页面内容描述
				// 对于后台功能，一般不需要特别指定具体页面的keywords和description
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
				$data['error'] = $result['content']['error']['message'];
			endif;

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
			$condition['biz_id'] = $this->session->biz_id;
			$condition['time_delete'] = 'NULL';
			//$condition['name'] = 'value';
			// （可选）遍历筛选条件
			foreach ($this->names_to_sort as $sorter):
				if ( !empty($this->input->post($sorter)) )
					$condition[$sorter] = $this->input->post($sorter);
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
			$condition['biz_id'] = $this->session->biz_id;
			$condition['time_delete'] = 'IS NOT NULL';
			// （可选）遍历筛选条件
			foreach ($this->names_to_sort as $sorter):
				if ( !empty($this->input->post($sorter)) )
					$condition[$sorter] = $this->input->post($sorter);
			endforeach;

			// 排序条件
			$order_by['time_delete'] = 'DESC';
			//$order_by['name'] = 'value';

			// 从API服务器获取相应列表信息
			$params = $condition;
			$url = api_url($this->class_name. '/index');
			$result = $this->curl->go($url, $params, 'array');
			if ($result['status'] === 200):
				$data['items'] = $result['content'];
			else:
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
			$this->form_validation->set_rules('period_valid', '有效期', 'trim');
			$this->form_validation->set_rules('expire_refund_rate', '过期退款比例', 'trim');
			$this->form_validation->set_rules('type_actual', '物流配送类型', 'trim');
			$this->form_validation->set_rules('time_latest_deliver', '最晚发货时间', 'trim');
			$this->form_validation->set_rules('max_count', '每单最高件数（件）', 'trim');
			$this->form_validation->set_rules('max_net', '每单最高净重（KG）', 'trim');
			$this->form_validation->set_rules('max_gross', '每单最高毛重（KG）', 'trim');
			$this->form_validation->set_rules('max_volume', '每单最高体积重（KG）', 'trim');
			$this->form_validation->set_rules('fee_count_start', '计件起始运费（元）', 'trim');
			$this->form_validation->set_rules('fee_net_start', '净重起始运费（元）', 'trim');
			$this->form_validation->set_rules('fee_gross_start', '毛重起始运费（元）', 'trim');
			$this->form_validation->set_rules('fee_volumn_start', '体积重起始运费（元）', 'trim');
			$this->form_validation->set_rules('fee_count_amount', '计件起始量（KG）', 'trim');
			$this->form_validation->set_rules('fee_net_amount', '净重起始量（KG）', 'trim');
			$this->form_validation->set_rules('fee_gross_amount', '毛重起始量（KG）', 'trim');
			$this->form_validation->set_rules('fee_volumn_amount', '体积重起始量（KG）', 'trim');
			$this->form_validation->set_rules('fee_count', '每件运费（元）', 'trim');
			$this->form_validation->set_rules('fee_net', '每KG净重运费（元）', 'trim');
			$this->form_validation->set_rules('fee_gross', '每KG毛重运费（元）', 'trim');
			$this->form_validation->set_rules('fee_volume', '每KG体积重运费（元）', 'trim');

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
					'biz_id' => $this->session->biz_id,
					'time_valid_from' => strtotime( $this->input->post('time_valid_from') ),
					'time_valid_end' => strtotime( $this->input->post('time_valid_end') ),
				);
				// 自动生成无需特别处理的数据
				$data_need_no_prepare = array(
					'name', 'type', 'period_valid', 'expire_refund_rate', 'type_actual', 'time_latest_deliver', 'max_count', 'max_net', 'max_gross', 'max_volume', 'fee_count_start', 'fee_net_start', 'fee_gross_start', 'fee_volumn_start', 'fee_count_amount', 'fee_net_amount', 'fee_gross_amount', 'fee_volumn_amount', 'fee_count', 'fee_net', 'fee_gross', 'fee_volume',
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
			$params['id'] = $this->input->get_post('id');
			$url = api_url($this->class_name. '/detail');
			$result = $this->curl->go($url, $params, 'array');
			if ($result['status'] === 200):
				$data['item'] = $result['content'];
			else:
				$data['error'] .= $result['content']['error']['message']; // 若未成功获取信息，则转到错误页
			endif;

			// 待验证的表单项
			$this->form_validation->set_error_delimiters('', '；');
			$this->form_validation->set_rules('name', '名称', 'trim|required');
			$this->form_validation->set_rules('time_valid_from', '有效期起始时间', 'trim');
			$this->form_validation->set_rules('time_valid_end', '有效期结束时间', 'trim');
			$this->form_validation->set_rules('period_valid', '有效期', 'trim');
			$this->form_validation->set_rules('expire_refund_rate', '过期退款比例', 'trim');
			$this->form_validation->set_rules('type_actual', '物流配送类型', 'trim');
			$this->form_validation->set_rules('time_latest_deliver', '最晚发货时间', 'trim');
			$this->form_validation->set_rules('max_count', '每单最高件数（件）', 'trim');
			$this->form_validation->set_rules('max_net', '每单最高净重（KG）', 'trim');
			$this->form_validation->set_rules('max_gross', '每单最高毛重（KG）', 'trim');
			$this->form_validation->set_rules('max_volume', '每单最高体积重（KG）', 'trim');
			$this->form_validation->set_rules('fee_count_start', '计件起始运费（元）', 'trim');
			$this->form_validation->set_rules('fee_net_start', '净重起始运费（元）', 'trim');
			$this->form_validation->set_rules('fee_gross_start', '毛重起始运费（元）', 'trim');
			$this->form_validation->set_rules('fee_volumn_start', '体积重起始运费（元）', 'trim');
			$this->form_validation->set_rules('fee_count_amount', '计件起始量（KG）', 'trim');
			$this->form_validation->set_rules('fee_net_amount', '净重起始量（KG）', 'trim');
			$this->form_validation->set_rules('fee_gross_amount', '毛重起始量（KG）', 'trim');
			$this->form_validation->set_rules('fee_volumn_amount', '体积重起始量（KG）', 'trim');
			$this->form_validation->set_rules('fee_count', '每件运费（元）', 'trim');
			$this->form_validation->set_rules('fee_net', '每KG净重运费（元）', 'trim');
			$this->form_validation->set_rules('fee_gross', '每KG毛重运费（元）', 'trim');
			$this->form_validation->set_rules('fee_volume', '每KG体积重运费（元）', 'trim');

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
					'id' => $this->input->post('id'),
					'time_valid_from' => strtotime( $this->input->post('time_valid_from') ),
					'time_valid_end' => strtotime( $this->input->post('time_valid_end') ),
				);
				// 自动生成无需特别处理的数据
				$data_need_no_prepare = array(
					'name', 'period_valid', 'expire_refund_rate', 'type_actual', 'time_latest_deliver', 'max_count', 'max_net', 'max_gross', 'max_volume', 'fee_count_start', 'fee_net_start', 'fee_gross_start', 'fee_volumn_start', 'fee_count_amount', 'fee_net_amount', 'fee_gross_amount', 'fee_volumn_amount', 'fee_count', 'fee_net', 'fee_gross', 'fee_volume', 'time_create', 'time_delete', 'time_edit', 'creator_id', 'operator_id',
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
					$data['id'] = $this->input->post('id');

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
		 * 修改单项
		 */
		public function edit_certain()
		{
			// 操作可能需要检查操作权限
			// $role_allowed = array('管理员', '经理'); // 角色要求
// 			$min_level = 30; // 级别要求
// 			$this->basic->permission_check($role_allowed, $min_level);

			// 页面信息
			$data = array(
				'title' => '修改'.$this->class_name_cn. $name,
				'class' => $this->class_name.' edit-certain',
				'error' => '', // 预设错误提示
			);
			
			// 从API服务器获取相应详情信息
			$params['id'] = $this->input->get_post('id');
			$url = api_url($this->class_name. '/detail');
			$result = $this->curl->go($url, $params, 'array');
			if ($result['status'] === 200):
				$data['item'] = $result['content'];
			else:
				$data['error'] .= $result['content']['error']['message']; // 若未成功获取信息，则转到错误页
			endif;

			// 待验证的表单项
			$this->form_validation->set_error_delimiters('', '；');
			// 动态设置待验证字段名及字段值
			$data_to_validate["{$name}"] = $value;
			$this->form_validation->set_data($data_to_validate);
			$this->form_validation->set_rules('name', '名称', 'trim');
			$this->form_validation->set_rules('time_valid_from', '有效期起始时间', 'trim');
			$this->form_validation->set_rules('time_valid_end', '有效期结束时间', 'trim');
			$this->form_validation->set_rules('period_valid', '有效期', 'trim');
			$this->form_validation->set_rules('expire_refund_rate', '过期退款比例', 'trim');
			$this->form_validation->set_rules('type_actual', '物流配送类型', 'trim');
			$this->form_validation->set_rules('time_latest_deliver', '最晚发货时间', 'trim');
			$this->form_validation->set_rules('max_count', '每单最高件数（件）', 'trim');
			$this->form_validation->set_rules('max_net', '每单最高净重（KG）', 'trim');
			$this->form_validation->set_rules('max_gross', '每单最高毛重（KG）', 'trim');
			$this->form_validation->set_rules('max_volume', '每单最高体积重（KG）', 'trim');
			$this->form_validation->set_rules('fee_count_start', '计件起始运费（元）', 'trim');
			$this->form_validation->set_rules('fee_net_start', '净重起始运费（元）', 'trim');
			$this->form_validation->set_rules('fee_gross_start', '毛重起始运费（元）', 'trim');
			$this->form_validation->set_rules('fee_volumn_start', '体积重起始运费（元）', 'trim');
			$this->form_validation->set_rules('fee_count_amount', '计件起始量（KG）', 'trim');
			$this->form_validation->set_rules('fee_net_amount', '净重起始量（KG）', 'trim');
			$this->form_validation->set_rules('fee_gross_amount', '毛重起始量（KG）', 'trim');
			$this->form_validation->set_rules('fee_volumn_amount', '体积重起始量（KG）', 'trim');
			$this->form_validation->set_rules('fee_count', '每件运费（元）', 'trim');
			$this->form_validation->set_rules('fee_net', '每KG净重运费（元）', 'trim');
			$this->form_validation->set_rules('fee_gross', '每KG毛重运费（元）', 'trim');
			$this->form_validation->set_rules('fee_volume', '每KG体积重运费（元）', 'trim');

			// 若表单提交不成功
			if ($this->form_validation->run() === FALSE):
				$data['error'] .= validation_errors();

				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/edit_certain', $data);
				$this->load->view('templates/footer', $data);

			else:
				// 检查必要参数是否已传入
				$required_params = $this->names_edit_certain_required;
				foreach ($required_params as $param):
					${$param} = $this->input->post($param);
					if ( $param !== 'value' && empty( ${$param} ) ): // value 可以为空；必要字段会在字段验证中另行检查
						$data['error'] = '必要的请求参数未全部传入';
						$this->load->view('templates/header', $data);
						$this->load->view($this->view_root.'/'.$op_view, $data);
						$this->load->view('templates/footer', $data);
						exit();
					endif;
				endforeach;

				// 需要编辑的信息
				$data_to_edit = array(
					'user_id' => $this->session->user_id,
					'id' => $id,
					'name' => $name,
					'value' => $value,
				);

				// 向API服务器发送待创建数据
				$params = $data_to_edit;
				$url = api_url($this->class_name. '/edit_certain');
				$result = $this->curl->go($url, $params, 'array');
				if ($result['status'] === 200):
					$data['title'] = $this->class_name_cn. '修改成功';
					$data['class'] = 'success';
					$data['content'] = $result['content']['message'];
					$data['operation'] = 'edit_certain';
					$data['id'] = $this->input->post('id');

					$this->load->view('templates/header', $data);
					$this->load->view($this->view_root.'/result', $data);
					$this->load->view('templates/footer', $data);

				else:
					// 若修改失败，则进行提示
					$data['error'] = $result['content']['error']['message'];

					$this->load->view('templates/header', $data);
					$this->load->view($this->view_root.'/edit_certain', $data);
					$this->load->view('templates/footer', $data);

				endif;

			endif;
		} // end edit_certain

		/**
		 * 删除单行或多行项目
		 *
		 * 一般用于发货、退款、存为草稿、上架、下架、删除、恢复等状态变化，请根据需要修改方法名，例如deliver、refund、delete、restore、draft等
		 */
		public function delete()
		{
			// 操作可能需要检查操作权限
			// $role_allowed = array('管理员', '经理'); // 角色要求
// 			$min_level = 30; // 级别要求
// 			$this->basic->permission_check($role_allowed, $min_level);

			$op_name = '删除'; // 操作的名称
			$op_view = 'delete'; // 视图文件名

			// 页面信息
			$data = array(
				'title' => $op_name. $this->class_name_cn,
				'class' => $this->class_name. ' '. $op_view,
				'error' => '', // 预设错误提示
			);

			// 检查是否已传入必要参数
			if ( !empty($this->input->get_post('ids')) ):
				$ids = $this->input->get_post('ids');

				// 将字符串格式转换为数组格式
				if ( !is_array($ids) ):
					$ids = explode(',', $ids);
				endif;

			elseif ( !empty($this->input->post('ids[]')) ):
				$ids = $this->input->post('ids[]');

			else:
				redirect( base_url('error/code_400') ); // 若缺少参数，转到错误提示页

			endif;
			
			// 赋值视图中需要用到的待操作项数据
			$data['ids'] = $ids;
			
			// 获取待操作项数据
			$data['items'] = array();
			foreach ($ids as $id):
				// 从API服务器获取相应详情信息
				$params['id'] = $id;
				$url = api_url($this->class_name. '/detail');
				$result = $this->curl->go($url, $params, 'array');
				if ($result['status'] === 200):
					$data['items'][] = $result['content'];
				else:
					$data['error'] .= 'ID'.$id.'项不可操作，“'.$result['content']['error']['message'].'”';
				endif;
			endforeach;

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
		} // end delete

		/**
		 * 恢复单行或多行项目
		 *
		 * 一般用于存为草稿、上架、下架、删除、恢复等状态变化，请根据需要修改方法名，例如delete、restore、draft等
		 */
		public function restore()
		{
			// 操作可能需要检查操作权限
			// $role_allowed = array('管理员', '经理'); // 角色要求
// 			$min_level = 30; // 级别要求
// 			$this->basic->permission_check($role_allowed, $min_level);

			$op_name = '恢复'; // 操作的名称
			$op_view = 'restore'; // 视图文件名

			// 页面信息
			$data = array(
				'title' => $op_name. $this->class_name_cn,
				'class' => $this->class_name. ' '. $op_view,
				'error' => '', // 预设错误提示
			);

			// 检查是否已传入必要参数
			if ( !empty($this->input->get_post('ids')) ):
				$ids = $this->input->get_post('ids');

				// 将字符串格式转换为数组格式
				if ( !is_array($ids) ):
					$ids = explode(',', $ids);
				endif;

			elseif ( !empty($this->input->post('ids[]')) ):
				$ids = $this->input->post('ids[]');

			else:
				redirect( base_url('error/code_400') ); // 若缺少参数，转到错误提示页

			endif;
			
			// 赋值视图中需要用到的待操作项数据
			$data['ids'] = $ids;
			
			// 获取待操作项数据
			$data['items'] = array();
			foreach ($ids as $id):
				// 从API服务器获取相应详情信息
				$params['id'] = $id;
				$url = api_url($this->class_name. '/detail');
				$result = $this->curl->go($url, $params, 'array');
				if ($result['status'] === 200):
					$data['items'][] = $result['content'];
				else:
					$data['error'] .= 'ID'.$id.'项不可操作，“'.$result['content']['error']['message'].'”';
				endif;
			endforeach;

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
		} // end restore

	} // end class Freight_template_biz

/* End of file Freight_template_biz.php */
/* Location: ./application/controllers/Freight_template_biz.php */
