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
            'category_ids', 'longitude', 'latitude', 'nation', 'province', 'city', 'county',
			'time_create', 'time_delete', 'time_edit', 'creator_id', 'operator_id', 'status',
		);

		/**
		 * 可被编辑的字段名
		 */
		protected $names_edit_allowed = array(
            'category_ids', 'name', 'brief_name', 'url_name', 'url_logo', 'slogan', 'description', 'notification',
			'tel_public', 'tel_protected_biz', 'tel_protected_fiscal', 'tel_protected_order',
			'fullname_owner', 'fullname_auth',
			'code_license', 'code_ssn_owner', 'code_ssn_auth',
			'bank_name', 'bank_account',
			'url_image_license', 'url_image_owner_id', 'url_image_auth_id', 'url_image_auth_doc', 'url_image_product', 'url_image_produce', 'url_image_retail',
			'longitude', 'latitude', 'province', 'city', 'county', 'street',
		);

		/**
		 * 完整编辑单行时必要的字段名
		 */
		protected $names_edit_required = array(
			'id', 'tel_public', 'fullname_owner', 'code_license', 'code_ssn_owner',
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
				'name' => '商家全称',
				'brief_name' => '店铺名称',
			);
		} // end __construct

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

                // 获取商家运费模板详情
                $data['freight_template'] = $this->get_freight_template_biz($data['item']['freight_template_id']);

                // 页面信息
                $data['title'] = $this->class_name_cn. $data['item']['brief_name'];
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
		 * 创建
		 */
		public function create()
		{
			// 若为其它商家的员工，不允许创建商家
			if ( !empty($this->session->biz_id) ):
				$data['title'] = $this->class_name_cn. '创建失败';
				$data['class'] = 'fail';
				$data['content'] = '您已是其它商家的成员，不可再次创建商家；与当前所属商家解除关系后再尝试。';

				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/result', $data);
				$this->load->view('templates/footer', $data);

			else:
				// 页面信息
				$data = array(
					'title' => '创建'.$this->class_name_cn,
					'class' => $this->class_name.' create',
				);

                // 从API服务器获取顶级商品分类列表信息
                $params = array(
                    'level' => 1,
                    'time_delete' => 'NULL',
                );
                $url = api_url('item_category/index');
                $result = $this->curl->go($url, $params, 'array');
                if ($result['status'] === 200):
                    $data['item_categories'] = $result['content'];
                else:
                    $data['item_categories'] = NULL;
                endif;

				// 待验证的表单项
				$this->form_validation->set_error_delimiters('', '；');
				// 验证规则 https://www.codeigniter.com/user_guide/libraries/form_validation.html#rule-reference
                $this->form_validation->set_rules('category_ids[]', '主营商品类目', 'trim|required|max_length[255]');
                $this->form_validation->set_rules('url_logo', '店铺LOGO', 'trim|max_length[255]');
                $this->form_validation->set_rules('name', '商家全称', 'trim|required|min_length[5]|max_length[35]|is_unique[biz.name]');
				$this->form_validation->set_rules('brief_name', '店铺名称', 'trim|required|max_length[20]|is_unique[biz.brief_name]');
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
                        'category_ids' => !empty($this->input->post('category_ids'))? implode(',', $this->input->post('category_ids')): NULL,
                        'tel_public' => empty($this->input->post('tel_public'))? $this->session->mobile: $this->input->post('tel_public'),
                        'tel_protected_biz' => $this->session->mobile,
                        'tel_protected_fiscal' => $this->session->mobile,
                        'tel_protected_order' => $this->session->mobile,
					);
					// 自动生成无需特别处理的数据
					$data_need_no_prepare = array(
                        'url_logo', 'name', 'brief_name',
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
         * 创建
         */
        public function create_quick()
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

                // 从API服务器获取顶级商品分类列表信息
                $params = array(
                    'level' => 1,
                    'time_delete' => 'NULL',
                );
                $url = api_url('item_category/index');
                $result = $this->curl->go($url, $params, 'array');
                if ($result['status'] === 200):
                    $data['item_categories'] = $result['content'];
                else:
                    $data['item_categories'] = NULL;
                endif;

                // 待验证的表单项
                $this->form_validation->set_error_delimiters('', '；');
                // 验证规则 https://www.codeigniter.com/user_guide/libraries/form_validation.html#rule-reference
                $this->form_validation->set_rules('url_logo', '店铺LOGO', 'trim|max_length[255]');
                $this->form_validation->set_rules('brief_name', '店铺名称', 'trim|required|max_length[20]|is_unique[biz.brief_name]');
                $this->form_validation->set_rules('category_ids[]', '主营商品类目', 'trim|required|max_length[255]');

                // 若表单提交不成功
                if ($this->form_validation->run() === FALSE):
                    $data['error'] = validation_errors();

                    $this->load->view('templates/header', $data);
                    $this->load->view($this->view_root.'/create_quick', $data);
                    $this->load->view('templates/footer', $data);

                else:
                    // 需要创建的数据；逐一赋值需特别处理的字段
                    $data_to_create = array(
                        'user_id' => $this->session->user_id,
                        'tel_public' => $this->session->mobile,
                        'category_ids' => !empty($this->input->post('category_ids'))? implode(',', $this->input->post('category_ids')): NULL,
                    );
                    // 自动生成无需特别处理的数据
                    $data_need_no_prepare = array(
                        'url_logo', 'brief_name',
                    );
                    foreach ($data_need_no_prepare as $name)
                        $data_to_create[$name] = $this->input->post($name);

                    // 向API服务器发送待创建数据
                    $params = $data_to_create;
                    $url = api_url($this->class_name. '/create_quick');
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
                        $this->load->view($this->view_root.'/create_quick', $data);
                        $this->load->view('templates/footer', $data);

                    endif;

                endif;

            endif;
        } // end create_quick

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

            // 从API服务器获取顶级商品分类列表信息
            $params = array(
                'level' => 1,
                'time_delete' => 'NULL',
            );
            $url = api_url('item_category/index');
            $result = $this->curl->go($url, $params, 'array');
            if ($result['status'] === 200):
                $data['item_categories'] = $result['content'];
            else:
                $data['item_categories'] = NULL;
            endif;

            // 获取商家运费模板列表
            $data['biz_freight_templates'] = $this->list_freight_template_biz();

			// 获取当前商家有效店铺装修
            $params['biz_id'] = $this->session->biz_id;
            $url = api_url('ornament_biz/index');
            $result = $this->curl->go($url, $params, 'array');
            if ($result['status'] === 200):
                $data['ornaments'] = $result['content'];
            else:
                $data['ornaments'] = NULL;
            endif;

			// 待验证的表单项
			$this->form_validation->set_error_delimiters('', '；');
            if ($this->app_type === 'admin'):
                $this->form_validation->set_rules('category_ids', '主营商品类目', 'trim|required|max_length[255]');
                $this->form_validation->set_rules('name', '商家全称', 'trim|required|min_length[5]|max_length[35]');
                $this->form_validation->set_rules('brief_name', '店铺名称', 'trim|required|max_length[20]');
                $this->form_validation->set_rules('url_name', '店铺域名', 'trim|max_length[20]|alpha_dash');
                $this->form_validation->set_rules('tel_protected_biz', '商务联系手机号', 'trim|required|exact_length[11]|is_natural');
            endif;
			$this->form_validation->set_rules('url_logo', '店铺LOGO', 'trim|max_length[255]');
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

            $this->form_validation->set_rules('ornament_id', '店铺装修', 'trim');

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
                    'category_ids' => !empty($this->input->post('category_ids'))? implode(',', $this->input->post('category_ids')): NULL,
				);
				// 自动生成无需特别处理的数据
				$data_need_no_prepare = array(
					'url_logo', 'slogan', 'description', 'notification',
					'tel_public', 'tel_protected_fiscal', 'tel_protected_order',
					'fullname_owner', 'fullname_auth', 
					'code_license', 'code_ssn_owner',  'code_ssn_auth',
					'bank_name', 'bank_account',
					'url_image_license', 'url_image_owner_id', 'url_image_auth_id', 'url_image_auth_doc', 'url_image_product', 'url_image_produce', 'url_image_retail',
					'longitude', 'latitude', 'province', 'city', 'county', 'street',
                    'ornament_id',
                );
				foreach ($data_need_no_prepare as $name)
					$data_to_edit[$name] = $this->input->post($name);

                // 根据客户端类型等条件筛选可操作的字段名
                if ($this->app_type !== 'admin'):
                    unset($data_to_edit['category_ids']);
                    unset($data_to_edit['name']);
                    unset($data_to_edit['brief_name']);
                    unset($data_to_edit['url_name']);
                    unset($data_to_edit['tel_protected_biz']);
                endif;

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
            $this->form_validation->set_rules('category_ids[]', '主营商品类目', 'trim|required|max_length[255]');
			$this->form_validation->set_rules('url_logo', '店铺LOGO', 'trim|max_length[255]');
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

            $this->form_validation->set_rules('ornament_id', '默认店铺装修方案', 'trim');

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
