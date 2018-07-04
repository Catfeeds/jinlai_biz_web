<?php
	defined('BASEPATH') OR exit('此文件不可被直接访问');

	/**
	 * Order 商品订单类
	 *
	 * @version 1.0.0
	 * @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	 * @copyright ICBG <www.bingshankeji.com>
	 */
	class Order extends MY_Controller
	{	
		/**
		 * 可作为列表筛选条件的字段名；可在具体方法中根据需要删除不需要的字段并转换为字符串进行应用，下同
		 */
		protected $names_to_sort = array(
			'user_id', 'user_ip', 'subtotal', 'promotion_id', 'discount_promotion', 'coupon_id', 'discount_coupon', 'credit_id', 'discount_credit', 'freight', 'total', 'discount_teller', 'teller_id', 'total_payed', 'total_refund', 'addressee_fullname', 'addressee_mobile', 'addressee_province', 'addressee_city', 'addressee_county', 'addressee_address', 'payment_type', 'payment_account', 'payment_id', 'note_user', 'note_stuff', 'commission_rate', 'commission', 'promoter_id', 'time_create', 'time_cancel', 'time_expire', 'time_pay', 'time_refuse', 'time_deliver', 'time_confirm', 'time_confirm_auto', 'time_comment', 'time_refund',
            'time_delete', 'time_edit', 'operator_id', 'invoice_status', 'status',
		);

        /**
         * 编辑多行特定字段时必要的字段名
         */
        protected $names_edit_bulk_required = array(
            'ids',
        );

		public function __construct()
		{
			parent::__construct();

			// 未登录用户转到登录页
			($this->session->time_expire_login > time()) OR redirect( base_url('login') );

			// 向类属性赋值
			$this->class_name = strtolower(__CLASS__);
			$this->class_name_cn = '商品订单'; // 改这里……
			$this->table_name = 'order'; // 和这里……
			$this->id_name = 'order_id'; // 还有这里，OK，这就可以了
			$this->view_root = $this->class_name; // 视图文件所在目录
			$this->media_root = MEDIA_URL. 'item/'; // 媒体文件所在目录，默认为商品信息

			// 设置需要自动在视图文件中生成显示的字段
			$this->data_to_display = array(
				'subtotal' => '商品小计',
				'total' => '应支付',
				'total_payed' => '已支付',
				'status' => '状态',
			);
		} // end __construct

		/**
		 * 列表页
		 */
		public function index()
		{
			// 页面信息
			$data = array(
				'title' => '所有'. $this->class_name_cn,
				'class' => $this->class_name.' index',
			);

			// 筛选条件
            $condition = array();
			// （可选）遍历筛选条件
			foreach ($this->names_to_sort as $sorter):
				if ( !empty($this->input->get_post($sorter)) )
					$condition[$sorter] = $this->input->get_post($sorter);
			endforeach;

			// 排序条件
			$order_by = NULL;

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
			
			// 根据状态筛选值确定页面标题
			if ( !empty($condition['status'] ) )
			    $data['title'] = $condition['status']. '订单';

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
			if ( !empty($id) ):
				$params['id'] = $id;
			else:
				redirect( base_url('error/code_400') ); // 若缺少参数，转到错误提示页
			endif;

			// 从API服务器获取相应详情信息
			$url = api_url($this->class_name. '/detail');
			$result = $this->curl->go($url, $params, 'array');
			if ($result['status'] === 200):
				$data['item'] = $result['content']; // 清除空元素

                // 获取相关用户信息
                $data['user'] = $this->get_user($data['item']['user_id']);

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
		 * 商家备注
		 */
		public function note()
		{
            // 检查必要参数是否已传入
            if ( empty($this->input->post_get('ids')))
                redirect( base_url('error/code_400') ); // 若缺少参数，转到错误提示页

            // 操作可能需要检查操作权限
            // $role_allowed = array('管理员', '经理'); // 角色要求
// 			$min_level = 30; // 级别要求
// 			$this->basic->permission_check($role_allowed, $min_level);

            $op_name = '备注'; // 操作的名称
            $op_view = 'note'; // 操作名、视图文件名

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
			$this->form_validation->set_rules('note_stuff', '员工备注', 'trim|required');

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

					'note_stuff' => $this->input->post('note_stuff'),
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
		} // end note
		
		/**
		 * 改价待付款订单
		 */
		public function reprice()
		{
            // 检查必要参数是否已传入
            if ( empty($this->input->post_get('ids')))
                redirect( base_url('error/code_400') ); // 若缺少参数，转到错误提示页

            // 操作可能需要检查操作权限
            // $role_allowed = array('管理员', '经理'); // 角色要求
// 			$min_level = 30; // 级别要求
// 			$this->basic->permission_check($role_allowed, $min_level);

            $op_name = '改价'; // 操作的名称
            $op_view = 'reprice'; // 操作名、视图文件名

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
			$this->form_validation->set_rules('discount_reprice', '改价折扣金额', 'trim|required|greater_than[0.01]|less_than_equal_to[99999.99]');
            $this->form_validation->set_rules('note_stuff', '员工备注', 'trim');

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

					'discount_reprice' => $this->input->post('discount_reprice'),
                    'note_stuff' => $this->input->post('note_stuff'),
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
		} // end reprice
		
		/**
		 * 拒绝已付款订单
		 */
		public function refuse()
		{
            // 检查必要参数是否已传入
            if ( empty($this->input->post_get('ids')))
                redirect( base_url('error/code_400') ); // 若缺少参数，转到错误提示页

            // 操作可能需要检查操作权限
            // $role_allowed = array('管理员', '经理'); // 角色要求
// 			$min_level = 30; // 级别要求
// 			$this->basic->permission_check($role_allowed, $min_level);

            $op_name = '拒单'; // 操作的名称
            $op_view = 'refuse'; // 操作名、视图文件名

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
            $this->form_validation->set_rules('note_stuff', '员工备注', 'trim');

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

                    'note_stuff' => $this->input->post('note_stuff'),
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
		} // end refuse
		
		/**
		 * 接受已付款订单
		 */
		public function accept()
		{
            // 检查必要参数是否已传入
            if ( empty($this->input->post_get('ids')))
                redirect( base_url('error/code_400') ); // 若缺少参数，转到错误提示页

            // 操作可能需要检查操作权限
            // $role_allowed = array('管理员', '经理'); // 角色要求
// 			$min_level = 30; // 级别要求
// 			$this->basic->permission_check($role_allowed, $min_level);

            $op_name = '接单'; // 操作的名称
            $op_view = 'accept'; // 操作名、视图文件名

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
		} // end accept

        /**
         * 发货已付款订单
         */
		public function deliver()
		{
            // 检查必要参数是否已传入
            if ( empty($this->input->post_get('ids')))
                redirect( base_url('error/code_400') ); // 若缺少参数，转到错误提示页

            // 操作可能需要检查操作权限
            // $role_allowed = array('管理员', '经理'); // 角色要求
// 			$min_level = 30; // 级别要求
// 			$this->basic->permission_check($role_allowed, $min_level);

            $op_name = '发货'; // 操作的名称
            $op_view = 'deliver'; // 操作名、视图文件名

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
			//$this->form_validation->set_rules('password', '密码', 'trim|required|min_length[6]|max_length[20]');
			$this->form_validation->set_rules('deliver_method', '发货方式', 'trim|required|max_length[30]');

            // 若用户自提，不需要填写服务商
            if ($this->input->post('deliver_method') === '用户自提'):
                $this->form_validation->set_rules('deliver_biz', '物流服务商', 'trim|max_length[30]');
            else:
                $this->form_validation->set_rules('deliver_biz', '物流服务商', 'trim|required|max_length[30]');
            endif;

			// 用户自提，或同城配送的服务商选择自营时，不需要填写运单号
			if ($this->input->post('deliver_method') === '用户自提' || $this->input->post('deliver_biz') === '自营'):
                $this->form_validation->set_rules('waybill_id', '物流运单号', 'trim|max_length[30]');
            else:
                $this->form_validation->set_rules('waybill_id', '物流运单号', 'trim|required|max_length[30]');
            endif;

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
					//'password' => $password,
					'operation' => $op_view, // 操作名称

					'deliver_method' => $this->input->post('deliver_method'),
					'deliver_biz' => $this->input->post('deliver_biz'),
					'waybill_id' => $this->input->post('waybill_id'),
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
		} // end deliver


		/**
		 * TODO 根据条件导出订单信息为excel
		 *
		 * 起止时间、字段等
		 */
		public function export(){
			// 页面信息
            $data = [
                'title' => '订单导出'. $this->class_name_cn,
                'class' => $this->class_name. ' export',
                'error' => '', // 预设错误提示
                'order_status' => ['待付款','待接单','待发货','待收货','待评价','已完成','已退款','已拒绝','已取消','已关闭'],
                'payment_type' => ['现金','银行转账','微信支付','支付宝','余额','待支付']
            ];
			// 待验证的表单项
			$this->form_validation->set_error_delimiters('', '；');
            $this->form_validation->set_rules('time_create_min', '开始时间', 'trim|required');
            $this->form_validation->set_rules('time_create_max', '结束时间', 'trim|required');
            $this->form_validation->set_rules('user_id', '用户id', 'trim|integer');
            $this->form_validation->set_rules('order_id', '起始订单id', 'trim|integer|max_length[11]');
            $this->form_validation->set_rules('limit', '总量', 'trim|integer|max_length[4]');
            $this->form_validation->set_rules('mobile', '手机号', 'trim|exact_length[11]');
            $this->form_validation->set_rules('payment_type', '支付方式', 'trim|in_list[现金,银行转账,微信支付,支付宝,余额,待支付]');
            $this->form_validation->set_rules('status', '订单状态', 'trim|in_list[待付款,待接单,待发货,待收货,待评价,已完成,已退款,已拒绝,已取消,已关闭]');

            // 若表单提交不成功
			if ($this->form_validation->run() === FALSE):
				$data['error'] .= validation_errors();
				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/export', $data);
				$this->load->view('templates/footer', $data);

			else:
				//起始订单id 
				$order_id = intval($this->input->post('order_id'));
				// 筛选参数；逐一赋值需特别处理的字段
				$data_to_send = array(
					'time_create_min' => strtotime($this->input->post('time_create_min') . ':00'),
                    'time_create_max' => strtotime($this->input->post('time_create_max') . ':00'),
                    'client_type'     => 'biz',
                    'biz_id'          => $this->session->biz_id
				);
				// 自动生成无需特别处理的数据
				$data_need_no_prepare = array(
                    'status','user_id','mobile','payment_type','limit'
				);
				foreach ($data_need_no_prepare as $name)
					$data_to_send[$name] = $this->input->post($name);

				// 查找是否存在文件缓存
				$new_condition = sha1(implode('-', $data_to_send));
				if (isset($_COOKIE[$new_condition]) && file_exists($_COOKIE[$new_condition])) :
					redirect('/' . $_COOKIE[$new_condition]);
					exit;
				endif;

                // 向API服务器发送待创建数据
				$params = $data_to_send;
				$url    = api_url($this->class_name. '/index');
				$result = $this->curl->go($url, $params, 'array');
				//api返回成功
				if ($result['status'] == 200):
					$this->user_id = $this->session->user_id;
					$data_list = [];
					$data_filterd = [];

					//增加一步 ，字段过滤,处理订单的item
					$data_order_show = ['order_id'=>'订单ID','user_id'=>'用户ID','subtotal'=>'小计','freight'=>'运费 0包邮','total'=>'应支付金额','total_payed'=>'实际支付金额','fullname'=>'收件人姓名','code_ssn'=>'身份证号','mobile'=>'收件人手机号','province'=>'省份','city'=>'城市','county'=>'区/县','street'=>'街道','full_address'=>'收货地址','note_user'=>'用户留言','note_stuff'=>'员工留言','payment_type'=>'支付方式','payment_account'=>'付款账号','payment_id'=>'付款流水号','time_create'=>'用户下单时间','time_cancel'=>'用户取消时间','time_expire'=>'自动过期时间；创建后未付款','time_pay'=>'用户付款时间','time_refuse'=>'商家拒绝时间；系统自动发起退款','time_accept'=>'商家接单时间', 'status'=>'订单状态'];
					foreach ($result['content'] as  $order) :
						//从哪开始
						if ($order['order_id'] < $order_id) :
							continue;
						endif;
						$data_filterd = [];
						foreach ($order as $key => $value) :
							if ( !is_array($value) && array_key_exists($key, $data_order_show) ):
								$data_filterd[$data_order_show[$key]] = $value;
								//拼接完整收货地址
								if( $key == 'street') :
									$data_filterd[$data_order_show['full_address']] = $order['province'] . $order['city'] . $order['county'] . '，' . $order['street'];
								endif;
							elseif ( is_array($value) ) :
								foreach ($value as $itemcount => $items) :
									$orderitem = $items['item_id'] . ' ' . $items['name'] . ($items['sku_id'] ? '(【' . $items['sku_id'] . $items['sku_name'] . '】)' : '');
									$orderitem .= ' x ' . $items['count'];
									$data_filterd['订单商品' . ($itemcount + 1)] = $orderitem;
								endforeach;
							endif;
						endforeach;
						$data_list[] = $data_filterd;
					endforeach;
					//导出
					$this->load->library('Excel');
					$this->excel->export($data_list, $data_to_send['time_create_min'] . '-' . $data_to_send['time_create_max'] . '订单导出', 'save');
					if ($this->result['status'] == 200) :
						//文件生成 后 保存 cookie
						$cookie_condition = sha1(implode('-', $params));
						setcookie($cookie_condition,  $this->result['content'], time() + 180);
						redirect('/' . $this->result['content']);
						exit;
					else:
						$data['error'] = $this->result['content']['error']['message'];
                        $this->load->view('templates/header', $data);
                        $this->load->view($this->view_root.'/export', $data);
                        $this->load->view('templates/footer', $data);
					endif;

				else:
					if (isset($result['content']['error'])) :
						$data['error'] = $result['content']['error']['message'];
					else:
						$data['error'] = '导出错误，稍后重试';
					endif;

                    $this->load->view('templates/header', $data);
                    $this->load->view($this->view_root.'/export', $data);
                    $this->load->view('templates/footer', $data);
				endif;
			endif;
		}
		/**
		 * TODO 商家验证
		 *
		 * 对卡券验证码的有效性进行验证，并将相应的订单标记为待评价状态（即视为已收货）
		 */
		public function valid()
		{

		} // end valid

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

	} // end class Order

/* End of file Order.php */
/* Location: ./application/controllers/Order.php */
