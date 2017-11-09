<?php
	defined('BASEPATH') OR exit('此文件不可被直接访问');

	/**
	 * Biz 商家类
	 *
	 * @version 1.0.0
	 * @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	 * @copyright ICBG <www.bingshankeji.com>
	 */
	class Biz extends MY_Controller
	{	
		/**
		 * 可作为列表筛选条件的字段名；可在具体方法中根据需要删除不需要的字段并转换为字符串进行应用，下同
		 */
		protected $names_to_sort = array(
			'longitude', 'latitude', 'nation', 'province', 'city', 'county',
			'time_create', 'time_delete', 'time_edit', 'creator_id', 'operator_id', 'status',
		);

		/**
		 * 可被编辑的字段名
		 */
		protected $names_edit_allowed = array(
			'name', 'brief_name', 'url_name', 'url_logo', 'slogan', 'description', 'notification',
			'tel_public', 'tel_protected_biz', 'tel_protected_fiscal', 'tel_protected_order',
			'fullname_owner', 'fullname_auth',
			'code_license', 'code_ssn_owner', 'code_ssn_auth',
			'bank_name', 'bank_account',
			'url_image_license', 'url_image_owner_id', 'url_image_auth_id', 'url_image_auth_doc', 'url_image_product', 'url_image_produce', 'url_image_retail',
			'longitude', 'latitude', 'province', 'city', 'county', 'street',

            'm1figure_url', 'm1ace_id', 'm1ids',
		);

		/**
		 * 完整编辑单行时必要的字段名
		 */
		protected $names_edit_required = array(
			'id', 'tel_public', 'fullname_owner', 'code_license', 'code_ssn_owner',
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
			$this->class_name_cn = '商家'; // 改这里……
			$this->table_name = 'biz'; // 和这里……
			$this->id_name = 'biz_id'; // 还有这里，OK，这就可以了
			$this->view_root = $this->class_name; // 视图文件所在目录
			$this->media_root = MEDIA_URL. $this->class_name.'/'; // 媒体文件所在目录

			// 设置需要自动在视图文件中生成显示的字段
			$this->data_to_display = array(
				'name' => '全称',
				'brief_name' => '简称',
			);
		}

		public function __destruct()
		{
			parent::__destruct();
			// 调试信息输出开关
			// $this->output->enable_profiler(TRUE);
		}

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
			$data['title'] = isset($data['item'])? $data['item']['brief_name']: '商家详情';
			$data['class'] = $this->class_name.' detail';
			//$data['keywords'] = $this->class_name.','. $data['item']['name'];

			// 输出视图
			$this->load->view('templates/header', $data);
			$this->load->view($this->view_root.'/detail', $data);
			$this->load->view('templates/footer', $data);
		} // end detail

		/**
		 * 创建
		 */
		public function create()
		{
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
				$this->form_validation->set_error_delimiters('', '；');
				// 验证规则 https://www.codeigniter.com/user_guide/libraries/form_validation.html#rule-reference
				$this->form_validation->set_rules('name', '商家全称', 'trim|required|min_length[5]|max_length[35]|is_unique[biz.name]');
				$this->form_validation->set_rules('brief_name', '简称', 'trim|required|max_length[15]|is_unique[biz.brief_name]');
				$this->form_validation->set_rules('description', '简介', 'trim|max_length[255]');
				$this->form_validation->set_rules('tel_public', '消费者联系电话', 'trim|required|min_length[10]|max_length[13]|is_unique[biz.tel_public]');

				$this->form_validation->set_rules('fullname_owner', '法人姓名', 'trim|required|max_length[15]');
				$this->form_validation->set_rules('fullname_auth', '经办人姓名', 'trim|max_length[15]');

				$this->form_validation->set_rules('code_license', '工商注册号', 'trim|required|min_length[15]|max_length[18]|is_unique[biz.code_license]');
				$this->form_validation->set_rules('code_ssn_owner', '法人身份证号', 'trim|required|exact_length[18]|is_unique[biz.code_ssn_owner]');
				$this->form_validation->set_rules('code_ssn_auth', '经办人身份证号', 'trim|exact_length[18]|is_unique[biz.code_ssn_auth]');

				$this->form_validation->set_rules('url_image_license', '营业执照', 'trim|max_length[255]');
				$this->form_validation->set_rules('url_image_owner_id', '法人身份证', 'trim|max_length[255]');
				$this->form_validation->set_rules('url_image_auth_id', '经办人身份证', 'trim|max_length[255]');
				$this->form_validation->set_rules('url_image_auth_doc', '经办人授权书', 'trim|max_length[255]');

				$this->form_validation->set_rules('tel_protected_fiscal', '财务联系手机号', 'trim|exact_length[11]|is_natural');
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
					);
					// 自动生成无需特别处理的数据
					$data_need_no_prepare = array(
						'name', 'brief_name', 'tel_public',
						'description', 'bank_name', 'bank_account',
						'fullname_owner', 'fullname_auth',
						'code_license', 'code_ssn_owner', 'code_ssn_auth',
						'url_image_license', 'url_image_owner_id', 'url_image_auth_id', 'url_image_auth_doc',
						'url_image_produce', 'url_image_retail', 'url_image_product',
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

						// 更新本地商家信息
						$this->session->biz_id = $data['id'];
						$this->session->role = '管理员';
						$this->session->level = '100';

						$this->load->view('templates/header', $data);
						$this->load->view($this->view_root.'/result_create', $data);
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
			// 检查是否已传入必要参数
			$id = $this->input->get_post('id')? $this->input->get_post('id'): NULL;
			if ( !empty($id) ):
				$params['id'] = $id;
			else:
				redirect( base_url('error/code_400') ); // 若缺少参数，转到错误提示页
			endif;

			// 若不是当前商家所属，转到相应提示页
			if ( $id != $this->session->biz_id ):
				redirect( base_url('error/not_yours') );
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

			// 获取当前商家所有商品数据
            $data['comodities'] = $this->list_item();

			// 待验证的表单项
			$this->form_validation->set_error_delimiters('', '；');
			$this->form_validation->set_rules('url_logo', 'LOGO', 'trim|max_length[255]');
			$this->form_validation->set_rules('slogan', '宣传语', 'trim|max_length[30]');
			$this->form_validation->set_rules('description', '简介', 'trim|max_length[255]');
			$this->form_validation->set_rules('notification', '公告', 'trim|max_length[255]');

			$this->form_validation->set_rules('tel_public', '消费者联系电话', 'trim|required|min_length[10]|max_length[13]');
			$this->form_validation->set_rules('tel_protected_fiscal', '财务联系手机号', 'trim|exact_length[11]|is_natural');
			$this->form_validation->set_rules('tel_protected_order', '订单通知手机号', 'trim|exact_length[11]|is_natural');

			$this->form_validation->set_rules('fullname_owner', '法人姓名', 'trim|required|max_length[15]');
			$this->form_validation->set_rules('fullname_auth', '经办人姓名', 'trim|max_length[15]');
	
			$this->form_validation->set_rules('code_license', '工商注册号', 'trim|required|min_length[15]|max_length[18]');
			$this->form_validation->set_rules('code_ssn_owner', '法人身份证号', 'trim|required|exact_length[18]');
			$this->form_validation->set_rules('code_ssn_auth', '经办人身份证号', 'trim|exact_length[18]');

			$this->form_validation->set_rules('url_image_license', '营业执照正/副本', 'trim|max_length[255]');
			$this->form_validation->set_rules('url_image_owner_id', '法人身份证', 'trim|max_length[255]');
			$this->form_validation->set_rules('url_image_auth_id', '经办人身份证', 'trim|max_length[255]');
			$this->form_validation->set_rules('url_image_auth_doc', '经办人授权书', 'trim|max_length[255]');
			$this->form_validation->set_rules('url_image_product', '产品', 'trim|max_length[255]');
			$this->form_validation->set_rules('url_image_produce', '工厂/产地', 'trim|max_length[255]');
			$this->form_validation->set_rules('url_image_retail', '门店/柜台', 'trim|max_length[255]');

			$this->form_validation->set_rules('bank_name', '开户行名称', 'trim|min_length[3]|max_length[20]');
			$this->form_validation->set_rules('bank_account', '开户行账号', 'trim|max_length[30]');

			$this->form_validation->set_rules('nation', '国家', 'trim|max_length[10]');
			$this->form_validation->set_rules('province', '省', 'trim|max_length[10]');
			$this->form_validation->set_rules('city', '市', 'trim|max_length[10]');
			$this->form_validation->set_rules('county', '区/县', 'trim|max_length[10]');
			$this->form_validation->set_rules('street', '具体地址；小区名、路名、门牌号等', 'trim|max_length[50]');
			$this->form_validation->set_rules('longitude', '经度', 'trim|min_length[7]|max_length[10]|decimal');
			$this->form_validation->set_rules('latitude', '纬度', 'trim|min_length[7]|max_length[10]|decimal');

			// TODO 页面装修，临时放于此处
            $this->form_validation->set_rules('m1figure_url', '店铺模块1形象图', 'trim|max_length[255]');
            $this->form_validation->set_rules('m1ace_id', '店铺模块1首推商品', 'trim|max_length[11]');
            $this->form_validation->set_rules('m1ids', '店铺模块1商品们', 'trim|max_length[255]');

			// 若表单提交不成功
			if ($this->form_validation->run() === FALSE):
				$data['error'] = validation_errors();

				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/edit', $data);
				$this->load->view('templates/footer', $data);

			else:
				// 需要编辑的数据；逐一赋值需特别处理的字段
				$data_to_edit = array(
					'id' => $id,
					'user_id' => $this->session->user_id,
                    'm1ids' => implode(',', $this->input->post('m1ids')),
				);
				// 自动生成无需特别处理的数据
				$data_need_no_prepare = array(
					'url_logo', 'slogan', 'description', 'notification',
					'tel_public', 'tel_protected_fiscal', 'tel_protected_order',
					'fullname_owner', 'fullname_auth', 
					'code_license', 'code_ssn_owner',  'code_ssn_auth',
					'bank_name', 'bank_account',
					'url_image_license', 'url_image_owner_id', 'url_image_auth_id', 'url_image_auth_doc', 'url_image_product', 'url_image_produce', 'url_image_retail',
					'min_order_subtotal', 'delivery_time_start', 'delivery_time_end',
					'longitude', 'latitude', 'province', 'city', 'county', 'street',

                    'm1figure_url', 'm1ace_id',
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
			
			// 若不是当前商家所属，转到相应提示页
			if ( $id != $this->session->biz_id ):
				redirect( base_url('error/not_yours') );
			endif;

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
			$this->form_validation->set_error_delimiters('', '；');
			// 动态设置待验证字段名及字段值
			$data_to_validate["{$name}"] = $value;
			$this->form_validation->set_data($data_to_validate);
			$this->form_validation->set_rules('url_logo', 'LOGO', 'trim|max_length[255]');
			$this->form_validation->set_rules('slogan', '宣传语', 'trim|max_length[30]');
			$this->form_validation->set_rules('description', '简介', 'trim|max_length[255]');
			$this->form_validation->set_rules('notification', '公告', 'trim|max_length[255]');
			
			$this->form_validation->set_rules('tel_public', '消费者联系电话', 'trim|min_length[10]|max_length[13]');
			$this->form_validation->set_rules('tel_protected_fiscal', '财务联系手机号', 'trim|exact_length[11]|is_natural');
			$this->form_validation->set_rules('tel_protected_order', '订单通知手机号', 'trim|exact_length[11]|is_natural');
			
			$this->form_validation->set_rules('fullname_owner', '法人姓名', 'trim|required|max_length[15]');
			$this->form_validation->set_rules('fullname_auth', '经办人姓名', 'trim|max_length[15]');

			$this->form_validation->set_rules('code_license', '工商注册号', 'trim|min_length[15]|max_length[18]');
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

			$this->form_validation->set_rules('nation', '国家', 'trim|max_length[10]');
			$this->form_validation->set_rules('province', '省', 'trim|max_length[10]');
			$this->form_validation->set_rules('city', '市', 'trim|max_length[10]');
			$this->form_validation->set_rules('county', '区/县', 'trim|max_length[10]');
			$this->form_validation->set_rules('street', '具体地址；小区名、路名、门牌号等', 'trim|max_length[50]');
			$this->form_validation->set_rules('longitude', '经度', 'trim|min_length[7]|max_length[10]|decimal');
			$this->form_validation->set_rules('latitude', '纬度', 'trim|min_length[7]|max_length[10]|decimal');

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
					redirect( base_url('error/code_404') ); // 若未成功获取信息，则转到错误页
				endif;

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

	} // end class Biz

/* End of file Biz.php */
/* Location: ./application/controllers/Biz.php */
