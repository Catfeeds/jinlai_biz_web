<?php
	defined('BASEPATH') OR exit('此文件不可被直接访问');

	/**
	 * Coupon_template 优惠券模板类
	 *
	 * 以我的XX列表、列表、详情、创建、单行编辑、单/多行编辑（删除、恢复）等功能提供了常见功能的APP示例代码
	 * CodeIgniter官方网站 https://www.codeigniter.com/user_guide/
	 *
	 * @version 1.0.0
	 * @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	 * @copyright ICBG <www.bingshankeji.com>
	 */
	class Coupon_template extends MY_Controller
	{	
		/**
		 * 可作为列表筛选条件的字段名；可在具体方法中根据需要删除不需要的字段并转换为字符串进行应用，下同
		 */
		protected $names_to_sort = array(
			'template_id', 'biz_id', 'category_id', 'category_biz_id', 'item_id', 'name', 'description', 'max_amount', 'max_amount_user', 'min_subtotal', 'amount', 'period', 'time_start', 'time_end', 
			'time_create', 'time_delete', 'time_edit', 'creator_id', 'operator_id',
		);

		/**
		 * 可被编辑的字段名
		 */
		protected $names_edit_allowed = array(
			'category_id', 'category_biz_id', 'item_id', 'name', 'description', 'max_amount', 'max_amount_user', 'min_subtotal', 'amount', 'period', 'time_start', 'time_end',
		);

		/**
		 * 完整编辑单行时必要的字段名
		 */
		protected $names_edit_required = array(
			'id',
			'name', 'amount',
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

			// 未登录用户转到登录页
			($this->session->time_expire_login > time()) OR redirect( base_url('login') );

			// 向类属性赋值
			$this->class_name = strtolower(__CLASS__);
			$this->class_name_cn = '优惠券模板'; // 改这里……
			$this->table_name = 'coupon_template'; // 和这里……
			$this->id_name = 'template_id'; // 还有这里，OK，这就可以了
			$this->view_root = $this->class_name;

			// 设置需要自动在视图文件中生成显示的字段
			$this->data_to_display = array(
				'name' => '名称',
				'amount' => '面值',
				'min_subtotal' => '起用金额',
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
				
				// 获取系统商品分类信息
				if ( !empty($data['item']['category_id']) ):
					$data['category'] = $this->get_category($data['item']['category_id']);
				endif;
				
				// 获取商家商品分类信息
				if ( !empty($data['item']['category_biz_id']) ):
					$data['category_biz'] = $this->get_category_biz($data['item']['category_biz_id']);
				endif;

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
			);

			// 获取品牌
			$data['brands'] = $this->list_brand();

			// 获取系统级商品分类
			$data['categories'] = $this->list_category();

			// 获取商家级商品分类
			$data['biz_categories'] = $this->list_category_biz();

			// 待验证的表单项
			$this->form_validation->set_error_delimiters('', '；');
			// 验证规则 https://www.codeigniter.com/user_guide/libraries/form_validation.html#rule-reference
			$this->form_validation->set_rules('category_id', '限用系统商品分类', 'trim');
			$this->form_validation->set_rules('category_biz_id', '限用商家商品分类', 'trim');
			$this->form_validation->set_rules('item_id', '限用商品', 'trim');
			$this->form_validation->set_rules('name', '名称', 'trim|required|max_length[20]');
			$this->form_validation->set_rules('description', '说明', 'trim|max_length[30]');
			$this->form_validation->set_rules('amount', '面值（元）', 'trim|required|greater_than_equal_to[1]|less_than_equal_to[999]');
			$this->form_validation->set_rules('min_subtotal', '起用金额（元）', 'trim|greater_than_equal_to[0]|less_than_equal_to[9999]');
			$this->form_validation->set_rules('max_amount', '总限量', 'trim|greater_than_equal_to[0]|less_than_equal_to[999999]');
			$this->form_validation->set_rules('max_amount_user', '单个用户限量', 'trim|greater_than_equal_to[0]|less_than_equal_to[99]');
			$this->form_validation->set_rules('period', '有效期', 'trim');
			$this->form_validation->set_rules('time_start', '开始时间', 'trim|exact_length[16]|callback_time_start');
			$this->form_validation->set_rules('time_end', '结束时间', 'trim|exact_length[16]|callback_time_end');
			$this->form_validation->set_message('time_start', '开始时间需详细到分，且晚于当前时间1分钟后');
			$this->form_validation->set_message('time_end', '结束时间需详细到分，且晚于当前时间1分钟后，亦不可早于开始时间（若有）');

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
					'time_start' => strtotime( $this->input->post('time_start') ),
					'time_end' => strtotime( $this->input->post('time_end') ),
				);
				// 自动生成无需特别处理的数据
				$data_need_no_prepare = array(
					'category_id', 'category_biz_id', 'item_id', 'name', 'description', 'max_amount', 'max_amount_user', 'min_subtotal', 'amount', 'period',
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
			);
			
			// 获取品牌
			$data['brands'] = $this->list_brand();

			// 获取系统级商品分类
			$data['categories'] = $this->list_category();

			// 获取商家级商品分类
			$data['biz_categories'] = $this->list_category_biz();

			// 待验证的表单项
			$this->form_validation->set_error_delimiters('', '；');
			$this->form_validation->set_rules('category_id', '限用系统商品分类', 'trim');
			$this->form_validation->set_rules('category_biz_id', '限用商家商品分类', 'trim');
			$this->form_validation->set_rules('item_id', '限用商品', 'trim');
			$this->form_validation->set_rules('name', '名称', 'trim|required|max_length[20]');
			$this->form_validation->set_rules('description', '说明', 'trim|max_length[30]');
			$this->form_validation->set_rules('amount', '面值（元）', 'trim|required|greater_than_equal_to[1]|less_than_equal_to[999]');
			$this->form_validation->set_rules('min_subtotal', '起用金额（元）', 'trim|greater_than_equal_to[0]|less_than_equal_to[9999]');
			$this->form_validation->set_rules('max_amount', '总限量', 'trim|greater_than_equal_to[0]|less_than_equal_to[999999]');
			$this->form_validation->set_rules('max_amount_user', '单个用户限量', 'trim|greater_than_equal_to[0]|less_than_equal_to[99]');
			$this->form_validation->set_rules('period', '有效期', 'trim');
			$this->form_validation->set_rules('time_start', '开始时间', 'trim|exact_length[16]|callback_time_start');
			$this->form_validation->set_rules('time_end', '结束时间', 'trim|exact_length[16]|callback_time_end');
			$this->form_validation->set_message('time_start', '开始时间需详细到分，且晚于当前时间1分钟后');
			$this->form_validation->set_message('time_end', '结束时间需详细到分，且晚于当前时间1分钟后，亦不可早于开始时间（若有）');

			// 若表单提交不成功
			if ($this->form_validation->run() === FALSE):
				$data['error'] = validation_errors();

				// 从API服务器获取相应详情信息
				$params['id'] = $this->input->get_post('id');
				$url = api_url($this->class_name. '/detail');
				$result = $this->curl->go($url, $params, 'array');
				if ($result['status'] === 200):
					$data['item'] = $result['content'];
				else:
					$data['error'] .= $result['content']['error']['message']; // 若未成功获取信息，则转到错误页
				endif;

				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/edit', $data);
				$this->load->view('templates/footer', $data);

			else:
				// 需要编辑的数据；逐一赋值需特别处理的字段
				$data_to_edit = array(
					'user_id' => $this->session->user_id,
					'id' => $this->input->post('id'),
					'time_start' => strtotime( $this->input->post('time_start') ),
					'time_end' => strtotime( $this->input->post('time_end') ),
				);
				// 自动生成无需特别处理的数据
				$data_need_no_prepare = array(
					'category_id', 'category_biz_id', 'item_id', 'name', 'description', 'max_amount', 'max_amount_user', 'min_subtotal', 'amount', 'period',
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
				elseif ( !empty($this->input->post('time_start')) && $time_to_check <= strtotime($this->input->post('time_start')) + 60):
					return false;

				else:
					return true;

				endif;

			endif;
		} // end time_end

	} // end class Coupon_template

/* End of file Coupon_template.php */
/* Location: ./application/controllers/Coupon_template.php */
