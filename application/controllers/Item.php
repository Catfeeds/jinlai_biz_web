<?php
	defined('BASEPATH') OR exit('此文件不可被直接访问');

	/**
	 * Item/ITM 商品类
	 *
	 * @version 1.0.0
	 * @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	 * @copyright ICBG <www.bingshankeji.com>
	 */
	class Item extends MY_Controller
	{
		/**
		 * 可作为列表筛选条件的字段名；可在具体方法中根据需要删除不需要的字段并转换为字符串进行应用，下同
		 */
		protected $names_to_sort = array(
			'category_id', 'brand_id', 'category_biz_id', 'code_biz', 'barcode', 'tag_price', 'price', 'unit_name', 'weight_net', 'weight_gross', 'weight_volume', 'stocks', 'quantity_max', 'quantity_min', 'coupon_allowed', 'discount_credit', 'commission_rate', 'time_to_publish', 'time_to_suspend', 'promotion_id', 'status',
			'time_create', 'time_delete', 'time_publish', 'time_suspend', 'time_edit', 'creator_id', 'operator_id',
		);

		/**
		 * 可被编辑的字段名
		 */
		protected $names_edit_allowed = array(
			'category_biz_id', 'code_biz', 'barcode', 'url_image_main', 'figure_image_urls', 'figure_video_urls', 'name', 'slogan', 'description', 'tag_price', 'price', 'unit_name', 'weight_net', 'weight_gross', 'weight_volume', 'stocks', 'quantity_max', 'quantity_min', 'coupon_allowed', 'discount_credit', 'commission_rate', 'time_to_publish', 'time_to_suspend', 'promotion_id',  'freight_template_id',
		);

		/**
		 * 完整编辑单行时必要的字段名
		 */
		protected $names_edit_required = array(
			'id', 'url_image_main', 'name', 'price', 'stocks',
		);

		public function __construct()
		{
			parent::__construct();

			// 未登录用户转到登录页
			($this->session->time_expire_login > time()) OR redirect( base_url('login') );

			// 向类属性赋值
			$this->class_name = strtolower(__CLASS__);
			$this->class_name_cn = '商品'; // 改这里……
			$this->table_name = 'item'; // 和这里……
			$this->id_name = 'item_id'; // 还有这里，OK，这就可以了
			$this->view_root = $this->class_name; // 视图文件所在目录
			$this->media_root = MEDIA_URL. $this->class_name.'/'; // 媒体文件所在目录

			// 设置需要自动在视图文件中生成显示的字段
			$this->data_to_display = array(
				'name' => '名称',
				'price' => '商城价/现价',
				'status' => '状态',
			);
		} // end __construct

		/**
		 * 列表页
		 */
		public function index()
		{
            parent::index();
            
			// 页面信息
			$data = array(
				'title' => $this->class_name_cn. '列表',
				'class' => $this->class_name.' index',
			);
			
			// 获取信息计数
			$data['count'] = array(
				'item' => $this->count_table('item'),
			);

			// 若存在商品，则获取商品列表
			if ($data['count']['item'] === 0):
                $data['items'] = array();

            else:
				// 筛选条件
				$condition['time_delete'] = 'NULL';
				// （可选）遍历筛选条件
				foreach ($this->names_to_sort as $sorter):
					if ( !empty($this->input->get_post($sorter)) ):
                        if ($sorter === 'status' && $this->input->get_post($sorter) === 'publish'):
                            $condition['time_publish'] = 'IS NOT NULL';
                        elseif ($sorter === 'status' && $this->input->get_post($sorter) === 'suspend'):
                            $condition['time_suspend'] = 'IS NOT NULL';
                        else:
                            $condition[$sorter] = $this->input->get_post($sorter);
                        endif;
				    endif;
				endforeach;

				// 排序条件
				$condition['orderby_time_create'] = 'DESC';
				$condition['limit'] = $this->limit;
                $condition['offset'] = $this->offset;

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
			endif;

			// 输出视图
			$this->load->view('templates/header', $data);
			$this->load->view($this->view_root.'/index', $data);
			$this->load->view('templates/nav-main', $data);
			$this->load->view('templates/footer', $data);
		} // end index

		/**
		 * 详情页
		 */
		public function detail()
		{
			// 检查是否已传入必要参数
			$id = $this->input->get_post('id')? $this->input->get_post('id'): NULL;
            $barcode = $this->input->get_post('barcode')? $this->input->get_post('barcode'): NULL;
			if ( ! empty($id) ):
				$params['id'] = $id;
			elseif ( ! empty($barcode)):
                $params['barcode'] = $barcode;
			else:
				redirect( base_url('error/code_400') ); // 若缺少参数，转到错误提示页
			endif;

			// 从API服务器获取相应详情信息
			$url = api_url($this->class_name. '/detail');
			$result = $this->curl->go($url, $params, 'array');
			if ($result['status'] === 200):
				$data['item'] = $result['content'];
				
				// 获取商品规格信息
			    $data['skus'] = $this->list_sku(NULL, $data['item']['item_id']);

				// 获取系统商品分类信息
				$data['category'] = $this->get_category($data['item']['category_id']);
				
				// 获取商家商品分类信息
				if ( !empty($data['item']['category_biz_id']) ):
					$data['category_biz'] = $this->get_category_biz($data['item']['category_biz_id']);
				endif;
				
				// 若参与店内活动，获取店内活动详情
				if ( !empty($data['item']['promotion_id']) ):
					$data['promotion'] = $this->get_promotion_biz($data['item']['promotion_id']);
				endif;

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
         * 快速创建
         */
		public function create_quick()
		{
			// 操作可能需要检查操作权限
			// $role_allowed = array('管理员', '经理'); // 角色要求
// 			$min_level = 30; // 级别要求
// 			$this->basic->permission_check($role_allowed, $min_level);

			// 页面信息
			$data = array(
				'title' => '快速创建'.$this->class_name_cn,
				'class' => $this->class_name.' create',
			);

			// 待验证的表单项
			$this->form_validation->set_error_delimiters('', '；');
			// 验证规则 https://www.codeigniter.com/user_guide/libraries/form_validation.html#rule-reference
            $this->form_validation->set_rules('brand_id', '品牌', 'trim|is_natural_no_zero');
			$this->form_validation->set_rules('category_id', '系统分类', 'trim|required|is_natural_no_zero');
			$this->form_validation->set_rules('category_biz_id', '店内分类', 'trim|is_natural_no_zero');
			$this->form_validation->set_rules('url_image_main', '主图', 'trim|required|max_length[255]');
			$this->form_validation->set_rules('name', '商品名称', 'trim|required|max_length[40]');
            $this->form_validation->set_rules('price', '商城价/现价（元）', 'trim|required|greater_than_equal_to[1]|less_than_equal_to[99999.99]');
			$this->form_validation->set_rules('stocks', '库存量（单位）', 'trim|greater_than_equal_to[0]|less_than_equal_to[65535]');

			// 若表单提交不成功
			if ($this->form_validation->run() === FALSE):
                // 获取品牌
                $data['brands'] = $this->list_brand();

                // 获取平台商品分类
                $data['categories'] = $this->list_category();

                // 获取店内商品分类
                $data['biz_categories'] = $this->list_category_biz();

				$data['error'] = validation_errors();

				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/create_quick', $data);
				$this->load->view('templates/footer', $data);

			else:
				// 需要创建的数据；逐一赋值需特别处理的字段
				$data_to_create = array(
					'user_id' => $this->session->user_id,

                    'stocks' => empty($this->input->post('time_to_publish'))? 10: $this->input->post('time_to_publish'),
				);
				// 自动生成无需特别处理的数据
				$data_need_no_prepare = array(
                    'brand_id', 'category_id', 'category_biz_id', 'url_image_main', 'name', 'price',
				);
				foreach ($data_need_no_prepare as $name)
					$data_to_create[$name] = $this->input->post($name);

				// 向API服务器发送待创建数据
				$params = $data_to_create;
				$url = api_url($this->class_name. '/create');
				$result = $this->curl->go($url, $params, 'array');
				if ($result['status'] === 200):
					$data['title'] = $this->class_name_cn. '快速创建成功';
					$data['class'] = 'success';
					$data['content'] = $result['content']['message']. '；您可在修改该商品时添加更多信息。';
					$data['operation'] = 'create_quick';
					$data['id'] = $result['content']['id']; // 创建后的信息ID

					$this->load->view('templates/header', $data);
					$this->load->view($this->view_root.'/result', $data);
					$this->load->view('templates/footer', $data);

				else:
					// 若创建失败，则进行提示
					$data['error'] = $result['content']['error']['message'];

					$this->load->view('templates/header', $data);
					$this->load->view($this->view_root.'/create_quick', $data);
					$this->load->view('templates/footer', $data);

				endif;

			endif;
		} // end create_quick

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

			// 待验证的表单项
			$this->form_validation->set_error_delimiters('', '；');
			// 验证规则 https://www.codeigniter.com/user_guide/libraries/form_validation.html#rule-reference
            $this->form_validation->set_rules('brand_id', '品牌', 'trim|is_natural_no_zero');
            $this->form_validation->set_rules('category_id', '系统分类', 'trim|required|is_natural_no_zero');
            $this->form_validation->set_rules('category_biz_id', '店内分类', 'trim|is_natural_no_zero');
            $this->form_validation->set_rules('code_biz', '商家商品编码', 'trim|max_length[20]');
            $this->form_validation->set_rules('barcode', '商品条形码', 'trim|is_natural_no_zero|exact_length[13]');
			$this->form_validation->set_rules('url_image_main', '主图', 'trim|required|max_length[255]');
			$this->form_validation->set_rules('figure_image_urls', '形象图', 'trim|max_length[255]');
			$this->form_validation->set_rules('figure_video_urls', '形象视频', 'trim|max_length[255]');
			$this->form_validation->set_rules('name', '商品名称', 'trim|required|max_length[40]');
			$this->form_validation->set_rules('slogan', '商品宣传语/卖点', 'trim|max_length[30]');
			$this->form_validation->set_rules('description', '商品描述', 'trim|max_length[20000]');
			$this->form_validation->set_rules('tag_price', '标签价/原价（元）', 'trim|greater_than_equal_to[0]|less_than_equal_to[99999.99]');
            $this->form_validation->set_rules('price', '商城价/现价（元）', 'trim|required|greater_than_equal_to[1]|less_than_equal_to[99999.99]');
            $this->form_validation->set_rules('stocks', '库存量（单位）', 'trim|greater_than_equal_to[0]|less_than_equal_to[65535]');
			$this->form_validation->set_rules('unit_name', '销售单位', 'trim|max_length[10]');
			$this->form_validation->set_rules('weight_net', '净重（KG）', 'trim|greater_than_equal_to[0]|less_than_equal_to[999.99]');
			$this->form_validation->set_rules('weight_gross', '毛重（KG）', 'trim|greater_than_equal_to[0]|less_than_equal_to[999.99]');
			$this->form_validation->set_rules('weight_volume', '体积重（KG）', 'trim|greater_than_equal_to[0]|less_than_equal_to[999.99]');
			$this->form_validation->set_rules('quantity_max', '每单最高限量（份）', 'trim|greater_than_equal_to[0]|less_than_equal_to[50]');
			$this->form_validation->set_rules('quantity_min', '每单最低限量（份）', 'trim|greater_than_equal_to[0]|less_than_equal_to[50]');
			$this->form_validation->set_rules('discount_credit', '积分抵扣率', 'trim|less_than_equal_to[0.5]');
			$this->form_validation->set_rules('commission_rate', '佣金比例/提成率', 'trim|less_than_equal_to[0.5]');
            $this->form_validation->set_rules('time_to_publish', '预定上架时间', 'trim|exact_length[16]|callback_time_start');
            $this->form_validation->set_rules('time_to_suspend', '预定下架时间', 'trim|exact_length[16]|callback_time_end');
            $this->form_validation->set_message('time_start', '预定上架时间需详细到分，且不可晚于预订下架时间');
            $this->form_validation->set_message('time_end', '预定下架时间需详细到分，且不可早于预订上架时间');
            $this->form_validation->set_rules('coupon_allowed', '是否可用优惠券', 'trim|in_list[0,1]');
            $this->form_validation->set_rules('promotion_id', '店内活动', 'trim|is_natural_no_zero');

			// 若表单提交不成功
			if ($this->form_validation->run() === FALSE):
                // 获取品牌
                $data['brands'] = $this->list_brand();

                // 获取平台商品分类
                $data['categories'] = $this->list_category();

                // 获取店内商品分类
                $data['biz_categories'] = $this->list_category_biz();

                // 获取店内营销活动
                $data['biz_promotions'] = $this->list_promotion_biz();

				$data['error'] = validation_errors();

				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/create', $data);
				$this->load->view('templates/footer', $data);

			else:
				// 需要创建的数据；逐一赋值需特别处理的字段
				$data_to_create = array(
					'user_id' => $this->session->user_id,

                    'stocks' => empty($this->input->post('time_to_publish'))? 10: $this->input->post('time_to_publish'),
					'time_to_publish' => empty($this->input->post('time_to_publish'))? NULL: $this->strto_minute($this->input->post('time_to_publish')), // 时间仅保留到分钟，下同
					'time_to_suspend' => empty($this->input->post('time_to_suspend'))? NULL: $this->strto_minute($this->input->post('time_to_suspend')),
				);
				// 自动生成无需特别处理的数据
				$data_need_no_prepare = array(
                    'brand_id', 'category_id', 'category_biz_id', 'code_biz', 'barcode', 'url_image_main', 'figure_image_urls', 'figure_video_urls', 'name', 'slogan', 'description', 'tag_price', 'price', 'unit_name', 'weight_net', 'weight_gross', 'weight_volume', 'quantity_max', 'quantity_min', 'discount_credit', 'commission_rate', 'coupon_allowed', 'promotion_id',
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
         * 导入并创建
         */
        public function create_import()
        {
            // 页面信息
            $data['title'] = $this->class_name_cn. '导入';
            $data['class'] = $this->class_name.' create_import';
            $data['error'] = ''; // 初始化错误提示

            // 待验证的表单项
            $this->form_validation->set_error_delimiters('', '；');
            // 动态设置待验证字段名及字段值
            $data_to_validate['file_to_upload'] = empty($_FILES['file_to_upload'])? NULL: $_FILES['file_to_upload']['tmp_name'];
            $this->form_validation->set_data($data_to_validate);
            $this->form_validation->set_rules('file_to_upload', '待导入文件', 'trim|required');

            // 若表单提交不成功
            if ($this->form_validation->run() === FALSE):
                $data['error'] .= validation_errors();

                $this->load->view('templates/header', $data);
                $this->load->view($this->view_root.'/create_import', $data);
                $this->load->view('templates/footer', $data);

            else:
                //var_dump($_FILES['file_to_upload']);

                // 文件大小限制(M)
                $max_size = 4; // 4M
                if ($_FILES['file_to_upload']['size'] > ($max_size*1024*1024)):
                    $data['error'] = '文件需小于'.$max_size.'M';

                    $this->load->view('templates/header', $data);
                    $this->load->view($this->view_root.'/create_import', $data);
                    $this->load->view('templates/footer', $data);

                // TODO 文件格式限制
                //elseif ():

                else:
                    // 当前用户ID
                    $this->user_id = $this->session->user_id;
                    // 获取上传的文件，不永久保存
//                    $file = fopen($_FILES['file_to_upload']['tmp_name'], 'r');
//                    $file_content = fread($file,filesize($_FILES['file_to_upload']['tmp_name']));
//                    fclose($file);

                    // 可导入的字段名
                    $names_to_import = 'category_id,biz_id,category_biz_id,code_biz,barcode,name,slogan,tag_price,price,unit_name,weight_net,weight_gross,weight_volume,stocks,quantity_max,quantity_min';

                    // 配置并初始化PHPExcel类库
                    $this->load->library('Excel');
                    $this->excel->import($_FILES['file_to_upload']['tmp_name'], $names_to_import, 2);
                    //var_dump($result);

                    // 错误提示
                    $error = empty($this->result['content']['error']['message'])? NULL: $this->result['content']['error']['message'];
                    // 导入
                    if ( ! empty($this->result['content'])):
                        $data['title'] = $this->class_name_cn. '导入成功';
                        $data['class'] = 'success';
                        $data['content'] = $this->result['content']['message'];
                        $data['error'] = $error;
                        $data['operation'] = 'create_import';

                        $this->load->view('templates/header', $data);
                        $this->load->view($this->view_root.'/result', $data);
                        $this->load->view('templates/footer', $data);

                    else:
                        // 若创建失败，则进行提示
                        $data['error'] = $error;

                        $this->load->view('templates/header', $data);
                        $this->load->view($this->view_root.'/create_import', $data);
                        $this->load->view('templates/footer', $data);

                    endif;
                endif;

            endif;
        } // end create_import

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

			// 待验证的表单项
			$this->form_validation->set_error_delimiters('', '；');
			$this->form_validation->set_rules('category_biz_id', '商家分类', 'trim|is_natural_no_zero');
            $this->form_validation->set_rules('code_biz', '商家商品编码', 'trim|max_length[20]');
            $this->form_validation->set_rules('barcode', '商品条形码', 'trim|is_natural_no_zero|exact_length[13]');
			$this->form_validation->set_rules('url_image_main', '主图', 'trim|required|max_length[255]');
			$this->form_validation->set_rules('figure_image_urls', '形象图', 'trim|max_length[255]');
			$this->form_validation->set_rules('figure_video_urls', '形象视频', 'trim|max_length[255]');
			$this->form_validation->set_rules('name', '商品名称', 'trim|required|max_length[40]');
			$this->form_validation->set_rules('slogan', '商品宣传语/卖点', 'trim|max_length[30]');
			$this->form_validation->set_rules('description', '商品描述', 'trim|max_length[20000]');
			$this->form_validation->set_rules('tag_price', '标签价/原价（元）', 'trim|greater_than_equal_to[0]|less_than_equal_to[99999.99]');
            $this->form_validation->set_rules('price', '商城价/现价（元）', 'trim|required|greater_than_equal_to[1]|less_than_equal_to[99999.99]');
            $this->form_validation->set_rules('stocks', '库存量（单位）', 'trim|greater_than_equal_to[0]|less_than_equal_to[65535]');
			$this->form_validation->set_rules('unit_name', '销售单位', 'trim|max_length[10]');
			$this->form_validation->set_rules('weight_net', '净重（KG）', 'trim|greater_than_equal_to[0]|less_than_equal_to[999.99]');
			$this->form_validation->set_rules('weight_gross', '毛重（KG）', 'trim|greater_than_equal_to[0]|less_than_equal_to[999.99]');
			$this->form_validation->set_rules('weight_volume', '体积重（KG）', 'trim|greater_than_equal_to[0]|less_than_equal_to[999.99]');
            $this->form_validation->set_rules('quantity_max', '每单最高限量（份）', 'trim|greater_than_equal_to[0]|less_than_equal_to[50]');
            $this->form_validation->set_rules('quantity_min', '每单最低限量（份）', 'trim|greater_than_equal_to[0]|less_than_equal_to[50]');
			$this->form_validation->set_rules('discount_credit', '积分抵扣率', 'trim|less_than_equal_to[0.5]');
			$this->form_validation->set_rules('commission_rate', '佣金比例/提成率', 'trim|less_than_equal_to[0.5]');
			$this->form_validation->set_rules('time_to_publish', '预定上架时间', 'trim|exact_length[16]|callback_time_start');
			$this->form_validation->set_rules('time_to_suspend', '预定下架时间', 'trim|exact_length[16]|callback_time_end');
            $this->form_validation->set_message('time_start', '预定上架时间需详细到分，且不可晚于预订下架时间');
            $this->form_validation->set_message('time_end', '预定下架时间需详细到分，且不可早于预订上架时间');
            $this->form_validation->set_rules('coupon_allowed', '是否可用优惠券', 'trim|in_list[0,1]');
			$this->form_validation->set_rules('promotion_id', '店内活动', 'trim|is_natural_no_zero');

			// 从API服务器获取相应详情信息
			$params['id'] = $id;
			$url = api_url($this->class_name. '/detail');
			$result = $this->curl->go($url, $params, 'array');
			if ($result['status'] === 200):
				$data['item'] = $result['content'];

				// 获取系统商品分类信息
				$data['category'] = $this->get_category($data['item']['category_id']);

				// 若参与店内活动，获取店内活动详情
				if ( !empty($data['item']['promotion_id']) ):
					$data['promotion'] = $this->get_promotion_biz($data['item']['promotion_id']);
				endif;

			else:
				redirect( base_url('error/code_404') ); // 若未成功获取信息，则转到错误页

			endif;

			// 若表单提交不成功
			if ($this->form_validation->run() === FALSE):
				$data['error'] .= validation_errors();

                // 获取商家商品分类列表
                $data['biz_categories'] = $this->list_category_biz();

                // 获取店内活动列表
                $data['biz_promotions'] = $this->list_promotion_biz();

				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/edit', $data);
				$this->load->view('templates/footer', $data);

			else:
				// 需要编辑的数据；逐一赋值需特别处理的字段
				$data_to_edit = array(
					'user_id' => $this->session->user_id,
					'id' => $id,

                    'stocks' => empty($this->input->post('time_to_publish'))? 10: $this->input->post('time_to_publish'),
                    'time_to_publish' => empty($this->input->post('time_to_publish'))? NULL: $this->strto_minute($this->input->post('time_to_publish')), // 时间仅保留到分钟，下同
                    'time_to_suspend' => empty($this->input->post('time_to_suspend'))? NULL: $this->strto_minute($this->input->post('time_to_suspend')),
				);
				// 自动生成无需特别处理的数据
				$data_need_no_prepare = array(
					'category_biz_id', 'code_biz', 'barcode', 'url_image_main', 'figure_image_urls', 'figure_video_urls', 'name', 'slogan', 'description', 'tag_price', 'price', 'unit_name', 'weight_net', 'weight_gross', 'weight_volume', 'quantity_max', 'quantity_min', 'coupon_allowed', 'discount_credit', 'commission_rate', 'promotion_id',
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
                    $data['id'] = $result['content']['id']; // 修改后的信息ID

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
         * 编辑单行
         */
        public function edit_description()
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
                'title' => '修改'.$this->class_name_cn.'商品描述',
                'class' => $this->class_name.' edit',
                'error' => '',
            );

            // 待验证的表单项
            $this->form_validation->set_error_delimiters('', '；');
            $this->form_validation->set_rules('description', '商品描述', 'trim|max_length[20000]');

            // 从API服务器获取相应详情信息
            $params['id'] = $id;
            $url = api_url($this->class_name. '/detail');
            $result = $this->curl->go($url, $params, 'array');
            if ($result['status'] === 200):
                $data['item'] = $result['content'];

            else:
                redirect( base_url('error/code_404') ); // 若未成功获取信息，则转到错误页

            endif;

            // 若表单提交不成功
            if ($this->form_validation->run() === FALSE):
                $data['error'] .= validation_errors();

                $this->load->view('templates/header', $data);
                $this->load->view($this->view_root.'/edit_description', $data);
                $this->load->view('templates/footer', $data);

            else:
                // 需要编辑的数据；逐一赋值需特别处理的字段
                $data_to_edit = array(
                    'user_id' => $this->session->user_id,
                    'id' => $id,

                    'name' => 'description',
                    'value' => empty($this->input->post('description'))? NULL: $this->input->post('description'),
                );

                // 向API服务器发送待创建数据
                $params = $data_to_edit;
                $url = api_url($this->class_name. '/edit_certain');
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
                    // 若创建失败，则进行提示
                    $data['error'] = $result['content']['error']['message'];

                    $this->load->view('templates/header', $data);
                    $this->load->view($this->view_root.'/edit_description', $data);
                    $this->load->view('templates/footer', $data);

                endif;

            endif;
        } // end edit_description

		/**
		 * 上架单行或多行项目
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

            $op_name = '上架'; // 操作的名称
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
			$this->form_validation->set_rules('ids', '待操作数据', 'trim|required|regex_match[/^(\d|\d,?)+$/]'); // 仅允许非零整数和半角逗号
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
		 * 下架单行或多行项目
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

            $op_name = '下架'; // 操作的名称
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
			$this->form_validation->set_rules('ids', '待操作数据', 'trim|required|regex_match[/^(\d|\d,?)+$/]'); // 仅允许非零整数和半角逗号
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

                // 若已设置结束时间，不可晚于结束时间
                $time_end = $this->input->post('time_to_suspend');
                if (
                    !empty($time_end)
                    && ($time_to_check > strtotime($time_end.':00'))
                ):
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

				// 若已设置开始时间，不可早于开始时间
                $time_start = $this->input->post('time_to_publish');
				if (
				    !empty($time_start)
                    && ($time_to_check < strtotime($time_start.':00'))
                ):
					return false;

				else:
					return true;

				endif;

			endif;
		} // end time_end

	} // end class Item

/* End of file Item.php */
/* Location: ./application/controllers/Item.php */
