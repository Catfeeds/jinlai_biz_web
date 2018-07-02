<?php
	defined('BASEPATH') OR exit('此文件不可被直接访问');

	/**
	 * Page/PGE 平台页面类
	 *
	 * 以我的XX列表、列表、详情、创建、单行编辑、单/多行编辑（删除、恢复）等功能提供了常见功能的APP示例代码
	 * CodeIgniter官方网站 https://www.codeigniter.com/user_guide/
	 *
	 * @version 1.0.0
	 * @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	 * @copyright ICBG <www.bingshankeji.com>
	 */
	class Page extends MY_Controller
	{	
		/**
		 * 可作为列表筛选条件的字段名；可在具体方法中根据需要删除不需要的字段并转换为字符串进行应用，下同
		 */
		protected $names_to_sort = array(
			'page_id', 'name', 'url_name', 'description', 'content_type', 'content_html', 'content_file', 'time_create', 'time_delete', 'time_edit', 'creator_id', 'operator_id', 'item_ids',  'sth_min', 'sth_max',
		);

		/**
		 * 可被编辑的字段名
		 */
		protected $names_edit_allowed = array(
			'page_id', 'name', 'url_name', 'description', 'content_type', 'content_html', 'content_file', 'time_create', 'time_delete', 'time_edit', 'creator_id', 'operator_id', 'item_ids', 
		);

		/**
		 * 完整编辑单行时必要的字段名
		 */
		protected $names_edit_required = array(
			'id',
			'page_id', 'name', 'url_name', 'description', 'content_type', 'content_html', 'content_file', 'time_create', 'time_delete', 'time_edit', 'creator_id', 'operator_id', 'item_ids', 
		);

		public function __construct()
		{
			parent::__construct();

			// 未登录用户转到登录页
			//($this->session->time_expire_login > time()) OR redirect( base_url('login') );

			// 向类属性赋值
			$this->class_name = strtolower(__CLASS__);
			$this->class_name_cn = '平台页面'; // 改这里……
			$this->table_name = 'page'; // 和这里……
			$this->id_name = 'page_id'; // 还有这里，OK，这就可以了
			$this->view_root = $this->class_name; // 视图文件所在目录
			$this->media_root = MEDIA_URL. $this->class_name.'/'; // 媒体文件所在目录

			// 设置需要自动在视图文件中生成显示的字段
			$this->data_to_display = array(
				'name' => '名称',
				'description' => '描述',
			);
		} // end __construct

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
				$data['items'] = array();
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
				
				// 输出视图
				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/detail', $data);
				$this->load->view('templates/footer', $data);

			else:
                redirect( base_url('error/code_404') ); // 若缺少参数，转到错误提示页

			endif;
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
				'error' => '', // 预设错误提示
			);

			// 待验证的表单项
			$this->form_validation->set_error_delimiters('', '；');
			// 验证规则 https://www.codeigniter.com/user_guide/libraries/form_validation.html#rule-reference
			$this->form_validation->set_rules('name', '名称', 'trim|required|max_length[30]');
			$this->form_validation->set_rules('url_name', '页面URL', 'trim|max_length[30]');
			$this->form_validation->set_rules('description', '说明', 'trim|max_length[30]');
			$this->form_validation->set_rules('content_type', '内容形式', 'trim|in_list[HTML,文件]');
			$this->form_validation->set_rules('content_html', '页面内容（HTML格式）', 'trim|max_length[20000]');
			$this->form_validation->set_rules('content_file', '页面文件', 'trim|max_length[30]');
			$this->form_validation->set_rules('item_ids', '相关商品ID，半角逗号拼接，CSV', 'trim|max_length[255]');

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
					'time_create' => date('Y-m-d H:i:s'),
					'time_edit'   => date('Y-m-d H:i:s'),
					'creator_id'  => $this->session->user_id,
					// 'operator_id'  => $this->session->user_id,
                    //'name' => empty($this->input->post('name'))? NULL: $this->input->post('name'),
				);
				// 自动生成无需特别处理的数据
				$data_need_no_prepare = array(
					'name', 'url_name', 'description', 'content_type', 'content_html', 'content_file', 'time_create', 'time_delete', 'time_edit', 'creator_id', 'operator_id', 'item_ids', 
				);
				foreach ($data_need_no_prepare as $name)
                    $data_to_create[$name] = empty($this->input->post($name))? NULL: $this->input->post($name);

				// 向API服务器发送待创建数据
				$params = $data_to_create;
				$this->app_type = 'admin';
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
				'error' => ''
			);

			// 从API服务器获取相应详情信息
			$url = api_url($this->class_name. '/detail');
			$result = $this->curl->go($url, $params, 'array');
			if ($result['status'] === 200):
				$data['item'] = $result['content'];
			else:
				redirect( base_url('error/code_404') ); // 若未成功获取信息，则转到错误页
			endif;

			// 待验证的表单项
			$this->form_validation->set_error_delimiters('', '；');
			$this->form_validation->set_rules('name', '名称', 'trim|required|max_length[30]');
			$this->form_validation->set_rules('url_name', '页面URL', 'trim|max_length[30]');
			$this->form_validation->set_rules('description', '说明', 'trim|max_length[30]');
			$this->form_validation->set_rules('content_type', '内容形式', 'trim|in_list[HTML,文件]');
			$this->form_validation->set_rules('content_html', '页面内容（HTML格式）', 'trim|max_length[20000]');
			$this->form_validation->set_rules('content_file', '页面文件', 'trim|max_length[30]');
			$this->form_validation->set_rules('item_ids', '相关商品ID，半角逗号拼接，CSV', 'trim|max_length[255]');


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
					'time_create' => date('Y-m-d H:i:s'),
					'time_edit'   => date('Y-m-d H:i:s'),
					'creator_id'  => $this->session->user_id,
					'operator_id' => $this->session->user_id,
                    //'name' => empty($this->input->post('name'))? NULL: $this->input->post('name'),
				);
				// 自动生成无需特别处理的数据
				$data_need_no_prepare = array(
					'page_id', 'name', 'url_name', 'description', 'content_type', 'content_html', 'content_file', 'time_create', 'time_delete', 'time_edit', 'creator_id', 'operator_id', 'item_ids', 
				);
				foreach ($data_need_no_prepare as $name)
                    $data_to_edit[$name] = empty($this->input->post($name))? NULL: $this->input->post($name);

				// 向API服务器发送待创建数据
				$params = $data_to_edit;
				$this->app_type = 'admin';
				$url = api_url($this->class_name. '/edit');
				$result = $this->curl->go($url, $params, 'array');
				if ($result['status'] === 200):
					$data['title'] = $this->class_name_cn. '修改成功';
					$data['class'] = 'success';
					$data['content'] = $result['content']['message'];
					$data['operation'] = 'edit';
					$data['id'] = $result['content']['id']; // 修改后的信息ID

					$this->load->view('templates/header', $data);
					$this->load->view($this->view_root.'/result', $data);
					$this->load->view('templates/footer', $data);

				else:
					// 若修改失败，则进行提示
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
			// 动态设置待验证字段名及字段值
			$data_to_validate["{$name}"] = $value;
			$this->form_validation->set_data($data_to_validate);
			$this->form_validation->set_rules('id', '待修改项ID', 'trim|required|is_natural_no_zero');
						$this->form_validation->set_rules('page_id', '页面ID', 'trim|required');
			$this->form_validation->set_rules('name', '名称', 'trim|required');
			$this->form_validation->set_rules('url_name', '页面URL', 'trim|');
			$this->form_validation->set_rules('description', '说明', 'trim|');
			$this->form_validation->set_rules('content_type', '内容形式', 'trim|');
			$this->form_validation->set_rules('content_html', '页面内容（HTML格式）', 'trim|');
			$this->form_validation->set_rules('content_file', '页面文件', 'trim|');
			$this->form_validation->set_rules('time_create', '创建时间', 'trim|required');
			$this->form_validation->set_rules('time_delete', '删除时间', 'trim|');
			$this->form_validation->set_rules('time_edit', '最后操作时间', 'trim|required');
			$this->form_validation->set_rules('creator_id', '创建者ID', 'trim|');
			$this->form_validation->set_rules('operator_id', '最后操作者ID', 'trim|');
			$this->form_validation->set_rules('item_ids', '相关商品ID，半角逗号拼接，CSV', 'trim|');


			// 若表单提交不成功
			if ($this->form_validation->run() === FALSE):
				$data['error'] .= validation_errors();

				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/edit_certain', $data);
				$this->load->view('templates/footer', $data);

			else:
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
					$data['id'] = $id;

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
		 * 以下为工具类方法
		 */

	} // end class Page

/* End of file Page.php */
/* Location: ./application/controllers/Page.php */
