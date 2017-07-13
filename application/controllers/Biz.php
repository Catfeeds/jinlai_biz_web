<?php
	defined('BASEPATH') OR exit('此文件不可被直接访问');

	/**
	 * Biz 商家类
	 *
	 * 以我的XX列表、列表、详情、创建、单行编辑、单/多行编辑（删除、恢复）等功能提供了常见功能的APP示例代码
	 * CodeIgniter官方网站 https://www.codeigniter.com/user_guide/
	 *
	 * @version 1.0.0
	 * @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	 * @copyright ICBG <www.bingshankeji.com>
	 */
	class Biz extends CI_Controller
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
		
		/* 需要显示的字段 */
		public $data_to_display;
		
		/**
		 * 可作为列表筛选条件的字段名；可在具体方法中根据需要删除不需要的字段并转换为字符串进行应用，下同
		 */
		protected $names_to_sort = array(
			'freight', 'freight_free_subtotal', 'freight_free_count', 'min_order_subtotal', 'delivery_time_start', 'delivery_time_end', 'longitude', 'latitude', 'country', 'province', 'city', 'county',
			'time_create', 'time_delete', 'time_edit', 'creator_id', 'operator_id', 'status',
		);
		
		/**
		 * 创建时必要的字段名
		 */
		protected $names_create_required = array(
			'name', 'brief_name', 'tel_public',
			'code_license', 'code_ssn_owner',
			'url_image_license', 'url_image_owner_id',
		);

		/**
		 * 可被编辑的字段名
		 */
		protected $names_edit_allowed = array(
			'name', 'brief_name', 'url_name', 'url_logo', 'slogan', 'description', 'notification',
			'tel_public', 'tel_protected_biz', 'tel_protected_fiscal', 'tel_protected_order',
			'code_license', 'code_ssn_owner', 'code_ssn_auth',
			'bank_name', 'bank_account', 'url_image_license', 'url_image_owner_id', 'url_image_auth_id', 'url_image_auth_doc', 'url_image_product', 'url_image_produce', 'url_image_retail',
			'freight', 'freight_free_subtotal', 'freight_free_count', 'min_order_subtotal', 'delivery_time_start', 'delivery_time_end', 'longitude', 'latitude', 'country', 'province', 'city', 'county', 'detail', 'url_web', 'url_weibo', 'url_taobao', 'url_wechat',
		);

		/**
		 * 完整编辑单行时必要的字段名
		 */
		protected $names_edit_required = array(
			'id',
			'tel_public', 'tel_protected_biz', 'code_license', 'code_ssn_owner', 'url_image_license', 'url_image_owner_id',
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
			'ids', 'operation', 'password',
		);

		public function __construct()
		{
			parent::__construct();

			// 未登录用户转到登录页
			($this->session->time_expire_login > time()) OR redirect( base_url('login') );

			// 向类属性赋值
			$this->class_name = strtolower(__CLASS__);
			$this->class_name_cn = '商家'; // 改这里……
			$this->table_name = 'biz'; // 和这里……
			$this->id_name = 'biz_id'; // 还有这里，OK，这就可以了
			$this->view_root = $this->class_name;

			// 设置需要自动在视图文件中生成显示的字段
			$this->data_to_display = array(
				'name' => '全称',
				'brief_name' => '简称',
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

			// 将需要显示的数据传到视图以备使用
			$data['data_to_display'] = $this->data_to_display;

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
			$condition = NULL;
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
			$this->basic->permission_check($role_allowed, $min_level);

			// 页面信息
			$data = array(
				'title' => $this->class_name_cn. '回收站',
				'class' => $this->class_name.' trash',
			);

			// 筛选条件
			$condition['time_delete'] = 'IS NOT NULL';
			//$condition['name'] = 'value';
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
	
			// 若为其它商家的员工，不允许创建商家
			if ( !empty($this->session->biz_id) ):
				$data['title'] = $this->class_name_cn. '创建失败';
				$data['class'] = 'fail';
				$data['content'] = '您目前是其它商家的成员，不可创建商家；请与当前所属商家解除关系后再尝试。';

				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/result', $data);
				$this->load->view('templates/footer', $data);

			else:

				// 页面信息
				$data = array(
					'title' => '创建'.$this->class_name_cn,
					'class' => $this->class_name.' create',
				);

				// 待验证的表单项
				$this->form_validation->set_error_delimiters('', '');
				// 验证规则 https://www.codeigniter.com/user_guide/libraries/form_validation.html#rule-reference
				$this->form_validation->set_rules('name', '全称', 'trim|required|min_length[7]|max_length[30]');
				$this->form_validation->set_rules('brief_name', '简称', 'trim|required|max_length[10]');
				$this->form_validation->set_rules('description', '简介', 'trim|max_length[200]');
				$this->form_validation->set_rules('tel_public', '消费者联系电话', 'trim|required|min_length[10]|max_length[13]');

				$this->form_validation->set_rules('code_license', '统一社会信用代码', 'trim|required|exact_length[18]');
				$this->form_validation->set_rules('code_ssn_owner', '法人身份证号', 'trim|required|exact_length[18]');
				$this->form_validation->set_rules('code_ssn_auth', '经办人身份证号', 'trim|exact_length[18]');
			
				$this->form_validation->set_rules('url_image_license', '营业执照', 'trim|required|max_length[255]');
				$this->form_validation->set_rules('url_image_owner_id', '法人身份证', 'trim|required|max_length[255]');
				$this->form_validation->set_rules('url_image_auth_id', '经办人身份证', 'trim|max_length[255]');
				$this->form_validation->set_rules('url_image_auth_doc', '授权书', 'trim|max_length[255]');
			
				$this->form_validation->set_rules('bank_name', '开户行名称', 'trim|min_length[3]|max_length[20]');
				$this->form_validation->set_rules('bank_account', '开户行账号', 'trim|max_length[30]');

				$this->form_validation->set_rules('url_image_product', '产品', 'trim|max_length[255]');
				$this->form_validation->set_rules('url_image_produce', '工厂/产地', 'trim|max_length[255]');
				$this->form_validation->set_rules('url_image_retail', '门店/柜台', 'trim|max_length[255]');

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
						'tel_protected_biz' => $this->session->mobile,
						//'name' => $this->input->post('name')),
					);
					// 自动生成无需特别处理的数据
					$data_need_no_prepare = array(
						'name', 'brief_name', 'tel_public',
						'description', 'bank_name', 'bank_account', 'code_license', 'code_ssn_owner', 'code_ssn_auth',
						'url_image_license', 'url_image_auth_id', 'url_image_auth_doc', 'url_image_produce', 'url_image_retail',
						'url_image_owner_id', 'url_image_product',
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
						$data['id'] = $result['content'][$this->id_name]; // 创建后的信息ID

						// 更新本地商家信息
						$this->session->biz_id = $data['id'];

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
				'title' => '编辑'.$this->class_name_cn,
				'class' => $this->class_name.' edit',
			);

			// 待验证的表单项
			$this->form_validation->set_error_delimiters('', '');
			$this->form_validation->set_rules('url_logo', 'LOGO/图标', 'trim|max_length[255]');
			$this->form_validation->set_rules('slogan', '说明', 'trim|max_length[20]');
			$this->form_validation->set_rules('description', '简介', 'trim|max_length[200]');
			$this->form_validation->set_rules('notification', '公告', 'trim|max_length[100]');
			$this->form_validation->set_rules('url_web', '官方网站', 'trim|max_length[255]|valid_url');
			$this->form_validation->set_rules('url_weibo', '官方微博', 'trim|max_length[255]|valid_url');
			$this->form_validation->set_rules('url_taobao', '淘宝/天猫店铺', 'trim|max_length[255]|valid_url');
			$this->form_validation->set_rules('url_wechat', '微信二维码', 'trim|max_length[255]');

			$this->form_validation->set_rules('tel_public', '消费者联系电话', 'trim|required|min_length[10]|max_length[13]');
			$this->form_validation->set_rules('tel_protected_fiscal', '财务联系手机号', 'trim|exact_length[11]|is_natural');
			$this->form_validation->set_rules('tel_protected_order', '订单通知手机号', 'trim|exact_length[11]|is_natural');

			$this->form_validation->set_rules('code_license', '统一社会信用代码', 'trim|required|exact_length[18]');
			$this->form_validation->set_rules('code_ssn_owner', '法人身份证号', 'trim|required|exact_length[18]');
			$this->form_validation->set_rules('code_ssn_auth', '经办人身份证号', 'trim|exact_length[18]');

			$this->form_validation->set_rules('url_image_license', '营业执照正/副本', 'trim|required|max_length[255]');
			$this->form_validation->set_rules('url_image_owner_id', '法人身份证照片', 'trim|required|max_length[255]');
			$this->form_validation->set_rules('url_image_auth_id', '经办人身份证', 'trim|max_length[255]');
			$this->form_validation->set_rules('url_image_auth_doc', '授权书', 'trim|max_length[255]');
			$this->form_validation->set_rules('url_image_product', '产品', 'trim|max_length[255]');
			$this->form_validation->set_rules('url_image_produce', '工厂/产地', 'trim|max_length[255]');
			$this->form_validation->set_rules('url_image_retail', '门店/柜台', 'trim|max_length[255]');

			$this->form_validation->set_rules('bank_name', '开户行名称', 'trim|min_length[3]|max_length[20]');
			$this->form_validation->set_rules('bank_account', '开户行账号', 'trim|max_length[30]');

			$this->form_validation->set_rules('freight', '每笔订单运费', 'trim|decimal|greater_than_equal_to[0.00]|less_than_equal_to[99.99]');
			$this->form_validation->set_rules('freight_free_subtotal', '免邮费起始金额', 'trim|decimal|greater_than_equal_to[0.00]|less_than_equal_to[99.99]');
			$this->form_validation->set_rules('freight_free_count', '免邮费起始份数', 'trim|is_natural|greater_than_equal_to[0]|less_than_equal_to[100]');
			$this->form_validation->set_rules('min_order_subtotal', '订单最低金额', 'trim|decimal|greater_than_equal_to[1.00]|less_than_equal_to[99.99]');
			$this->form_validation->set_rules('delivery_time_start', '配送起始时间', 'trim|is_natural|greater_than_equal_to[0]|less_than_equal_to[23]');
			$this->form_validation->set_rules('delivery_time_end', '配送结束时间', 'trim|is_natural_no_zero|greater_than_equal_to[0]|less_than_equal_to[23]');

			$this->form_validation->set_rules('country', '国家', 'trim|max_length[10]');
			$this->form_validation->set_rules('province', '省级行政区', 'trim|max_length[10]');
			$this->form_validation->set_rules('city', '地市级行政区', 'trim|max_length[10]');
			$this->form_validation->set_rules('county', '区县级行政区', 'trim|max_length[10]');
			$this->form_validation->set_rules('detail', '详细地址；小区名、路名、门牌号等', 'trim|max_length[50]');
			$this->form_validation->set_rules('longitude', '经度', 'trim|min_length[7]|decimal');
			$this->form_validation->set_rules('latitude', '纬度', 'trim|min_length[7]|decimal');

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
					'id' => $this->input->post('id'),
					'user_id' => $this->session->user_id,
					//'name' => $this->input->post('name')),
				);
				// 自动生成无需特别处理的数据
				$data_need_no_prepare = array(
					'name', 'brief_name', 'url_name', 'url_logo', 'slogan', 'description', 'notification',
					'tel_public', 'tel_protected_biz', 'tel_protected_fiscal', 'tel_protected_order',
					'code_license', 'code_ssn_owner',  'code_ssn_auth',
					'bank_name', 'bank_account', 'url_image_license', 'url_image_owner_id', 'url_image_auth_id', 'url_image_auth_doc', 'url_image_product', 'url_image_produce', 'url_image_retail',
					'freight', 'freight_free_subtotal', 'freight_free_count', 'min_order_subtotal', 'delivery_time_start', 'delivery_time_end', 'longitude', 'latitude', 'country', 'province', 'city', 'county', 'detail', 'url_web', 'url_weibo', 'url_taobao', 'url_wechat',
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
					$data['id'] = $result['content'][$this->id_name]; // 创建后的信息ID

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
		 * 编辑单项
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
			);

			// 待验证的表单项
			$this->form_validation->set_error_delimiters('', '');
			// 动态设置待验证字段名及字段值
			$data_to_validate["{$name}"] = $value;
			$this->form_validation->set_data($data_to_validate);
			$this->form_validation->set_rules('url_logo', 'LOGO/图标', 'trim|max_length[255]');
			$this->form_validation->set_rules('slogan', '说明', 'trim|max_length[20]');
			$this->form_validation->set_rules('description', '简介', 'trim|max_length[200]');
			$this->form_validation->set_rules('notification', '公告', 'trim|max_length[100]');
			$this->form_validation->set_rules('url_web', '官方网站', 'trim|max_length[255]|valid_url');
			$this->form_validation->set_rules('url_weibo', '官方微博', 'trim|max_length[255]|valid_url');
			$this->form_validation->set_rules('url_taobao', '淘宝/天猫店铺', 'trim|max_length[255]|valid_url');
			$this->form_validation->set_rules('url_wechat', '微信二维码', 'trim|max_length[255]');

			$this->form_validation->set_rules('tel_public', '消费者联系电话', 'trim|min_length[10]|max_length[13]');
			$this->form_validation->set_rules('tel_protected_fiscal', '财务联系手机号', 'trim|exact_length[11]|is_natural');
			$this->form_validation->set_rules('tel_protected_order', '订单通知手机号', 'trim|exact_length[11]|is_natural');

			$this->form_validation->set_rules('code_license', '统一社会信用代码', 'trim|exact_length[18]|is_unique[biz.code_license]');
			$this->form_validation->set_rules('code_ssn_owner', '法人身份证号', 'trim|exact_length[18]|is_unique[biz.code_ssn_owner]');
			$this->form_validation->set_rules('code_ssn_auth', '经办人身份证号', 'trim|exact_length[18]|is_unique[biz.code_ssn_auth]');

			$this->form_validation->set_rules('url_image_license', '营业执照正/副本', 'trim|max_length[255]');
			$this->form_validation->set_rules('url_image_owner_id', '法人身份证照片', 'trim|max_length[255]');
			$this->form_validation->set_rules('url_image_auth_id', '经办人身份证', 'trim|max_length[255]');
			$this->form_validation->set_rules('url_image_auth_doc', '授权书', 'trim|max_length[255]');
			$this->form_validation->set_rules('url_image_product', '产品', 'trim|max_length[255]');
			$this->form_validation->set_rules('url_image_produce', '工厂/产地', 'trim|max_length[255]');
			$this->form_validation->set_rules('url_image_retail', '门店/柜台', 'trim|max_length[255]');

			$this->form_validation->set_rules('bank_name', '开户行名称', 'trim|min_length[3]|max_length[20]');
			$this->form_validation->set_rules('bank_account', '开户行账号', 'trim|max_length[30]');

			$this->form_validation->set_rules('freight', '每笔订单运费', 'trim|decimal|greater_than_equal_to[0.00]|less_than_equal_to[99.99]');
			$this->form_validation->set_rules('freight_free_subtotal', '免邮费起始金额', 'trim|decimal|greater_than_equal_to[0.00]|less_than_equal_to[99.99]');
			$this->form_validation->set_rules('freight_free_count', '免邮费起始份数', 'trim|is_natural|greater_than_equal_to[0]|less_than_equal_to[100]');
			$this->form_validation->set_rules('min_order_subtotal', '订单最低金额', 'trim|decimal|greater_than_equal_to[1.00]|less_than_equal_to[99.99]');
			$this->form_validation->set_rules('delivery_time_start', '配送起始时间', 'trim|is_natural|greater_than_equal_to[0]|less_than_equal_to[23]');
			$this->form_validation->set_rules('delivery_time_end', '配送结束时间', 'trim|is_natural_no_zero|greater_than_equal_to[0]|less_than_equal_to[23]');

			$this->form_validation->set_rules('country', '国家', 'trim|max_length[10]');
			$this->form_validation->set_rules('province', '省级行政区', 'trim|max_length[10]');
			$this->form_validation->set_rules('city', '地市级行政区', 'trim|max_length[10]');
			$this->form_validation->set_rules('county', '区县级行政区', 'trim|max_length[10]');
			$this->form_validation->set_rules('detail', '详细地址；小区名、路名、门牌号等', 'trim|max_length[50]');
			$this->form_validation->set_rules('longitude', '经度', 'trim|min_length[7]|decimal');
			$this->form_validation->set_rules('latitude', '纬度', 'trim|min_length[7]|decimal');

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
					$data['id'] = $result['content'][$this->id_name]; // 创建后的信息ID

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
			);

			// 将需要显示的数据传到视图以备使用
			$data['data_to_display'] = $this->data_to_display;

			// 待验证的表单项
			$this->form_validation->set_error_delimiters('', '');
			$this->form_validation->set_rules('ids', '待操作数据ID们', 'trim|required|regex_match[/^(\d|\d,?)+$/]'); // 仅允许非零整数和半角逗号
			$this->form_validation->set_rules('operation', '待执行操作', 'trim|required|in_list[delete,restore]');
			$this->form_validation->set_rules('password', '密码', 'trim|required|min_length[6]|max_length[20]');
			$this->form_validation->set_rules('user_id', '操作者ID', 'trim|required|is_natural_no_zero');

			// 若表单提交不成功
			if ($this->form_validation->run() === FALSE):
				$data['error'] = validation_errors();

				$ids = $this->input->get('ids')? $this->input->get('ids'): implode(',', $this->input->post('ids'));
				$item['ids'] = $ids;

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
					'ids' => $ids,
					'user_id' => $this->session->user_id,
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
					$data['id'] = $result['content'][$this->id_name]; // 创建后的信息ID

					$this->load->view('templates/header', $data);
					$this->load->view($this->view_root.'/result', $data);
					$this->load->view('templates/footer', $data);

				else:
					// 若创建失败，则进行提示
					$data['error'] = $result['content']['error']['message'];

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
			);

			// 将需要显示的数据传到视图以备使用
			$data['data_to_display'] = $this->data_to_display;

			// 待验证的表单项
			$this->form_validation->set_error_delimiters('', '');
			$this->form_validation->set_rules('ids', '待操作数据ID们', 'trim|required|regex_match[/^(\d|\d,?)+$/]'); // 仅允许非零整数和半角逗号
			$this->form_validation->set_rules('operation', '待执行操作', 'trim|required|in_list[delete,restore]');
			$this->form_validation->set_rules('password', '密码', 'trim|required|min_length[6]|max_length[20]');
			$this->form_validation->set_rules('user_id', '操作者ID', 'trim|required|is_natural_no_zero');

			// 若表单提交不成功
			if ($this->form_validation->run() === FALSE):
				$data['error'] = validation_errors();
				
				$ids = $this->input->get('ids')? $this->input->get('ids'): implode(',', $this->input->post('ids'));
				$item['ids'] = $ids;

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
					'ids' => $ids,
					'user_id' => $this->session->user_id,
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
					$data['id'] = $result['content'][$this->id_name]; // 创建后的信息ID

					$this->load->view('templates/header', $data);
					$this->load->view($this->view_root.'/result', $data);
					$this->load->view('templates/footer', $data);

				else:
					// 若创建失败，则进行提示
					$data['error'] = $result['content']['error']['message'];

					$this->load->view('templates/header', $data);
					$this->load->view($this->view_root.'/'.$op_view, $data);
					$this->load->view('templates/footer', $data);
				endif;

			endif;
		} // end restore

	} // end class Biz

/* End of file Biz.php */
/* Location: ./application/controllers/Biz.php */
