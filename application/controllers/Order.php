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
			'user_id', 'user_ip', 'subtotal', 'promotion_id', 'discount_promotion', 'coupon_id', 'discount_coupon', 'credit_id', 'discount_credit', 'freight', 'total', 'discount_teller', 'teller_id', 'total_payed', 'total_refund', 'addressee_fullname', 'addressee_mobile', 'addressee_province', 'addressee_city', 'addressee_county', 'addressee_address', 'payment_type', 'payment_account', 'payment_id', 'note_user', 'note_stuff', 'commission_rate', 'commission', 'promoter_id', 'time_create', 'time_cancel', 'time_expire', 'time_pay', 'time_refuse', 'time_deliver', 'time_confirm', 'time_confirm_auto', 'time_comment', 'time_refund', 'time_delete', 'time_edit', 'operator_id', 'invoice_status', 'status',
			'time_create', 'time_delete', 'time_edit', 'creator_id', 'operator_id',
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
			$this->class_name_cn = '商品订单'; // 改这里……
			$this->table_name = 'order'; // 和这里……
			$this->id_name = 'order_id'; // 还有这里，OK，这就可以了
			$this->view_root = $this->class_name; // 视图文件所在目录
			$this->media_root = MEDIA_URL. 'item/'; // 媒体文件所在目录

			// 设置需要自动在视图文件中生成显示的字段
			$this->data_to_display = array(
				'subtotal' => '小计',
				'total' => '应支付',
				'total_payed' => '已支付',
				'status' => '状态',
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
				'title' => '所有'. $this->class_name_cn,
				'class' => $this->class_name.' index',
			);

			// 筛选条件
			$condition['biz_id'] = $this->session->biz_id;
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
				$data['error'] = $result['content']['error']['message'];
			endif;
			
			// 根据状态筛选值确定页面标题
			if ( !empty($condition['status'] ) ):
				$data['title'] = $condition['status']. '订单';
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
			if ( !empty($id) ):
				$params['id'] = $id;
				$params['biz_id'] = $this->session->biz_id;
			else:
				redirect( base_url('error/code_400') ); // 若缺少参数，转到错误提示页
			endif;

			// 从API服务器获取相应详情信息
			$url = api_url($this->class_name. '/detail');
			$result = $this->curl->go($url, $params, 'array');
			if ($result['status'] === 200):
				$data['item'] = $result['content']; // 清除空元素
			else:
				$data['error'] = $result['content']['error']['message'];
			endif;

			// 页面信息
			$data['title'] = '商品订单'. $data['item']['order_id'];
			$data['class'] = $this->class_name.' detail';

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
            // 操作可能需要检查操作权限
            // $role_allowed = array('管理员', '经理'); // 角色要求
// 			$min_level = 30; // 级别要求
// 			$this->basic->permission_check($role_allowed, $min_level);

            $op_name = '备注'; // 操作的名称
            $op_view = 'note'; // 视图文件名

			// 页面信息
			$data = array(
				'title' => $op_name,
				'class' => $this->class_name. ' '. $op_view,
				'error' => '', // 预设错误提示
			);

			// 赋值视图中需要用到的待操作项数据
			$data['ids'] = $ids = $this->parse_ids_array();

			// 获取待操作项数据
			$data['items'] = array();
			foreach ($ids as $id):
				// 从API服务器获取相应详情信息
				$params['id'] = $id;
                $params['biz_id'] = $this->session->biz_id;
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
			$this->form_validation->set_rules('note_stuff', '员工留言', 'trim|required');

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
                    'biz_id' => $this->session->biz_id,
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
            // 操作可能需要检查操作权限
            // $role_allowed = array('管理员', '经理'); // 角色要求
// 			$min_level = 30; // 级别要求
// 			$this->basic->permission_check($role_allowed, $min_level);

            $op_name = '改价'; // 操作的名称
            $op_view = 'reprice'; // 视图文件名

			// 页面信息
			$data = array(
				'title' => $op_name,
				'class' => $this->class_name. ' '. $op_view,
				'error' => '', // 预设错误提示
			);

			// 赋值视图中需要用到的待操作项数据
			$data['ids'] = $ids = $this->parse_ids_array();
			
			// 获取待操作项数据
			$data['items'] = array();
			foreach ($ids as $id):
				// 从API服务器获取相应详情信息
				$params['id'] = $id;
                $params['biz_id'] = $this->session->biz_id;
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
			$this->form_validation->set_rules('discount_reprice', '改价折扣金额', 'trim|required|greater_than[0.01]|less_than_equal_to[99999.99]');

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
                    'biz_id' => $this->session->biz_id,
					'user_id' => $this->session->user_id,
					'ids' => $ids,
					'password' => $password,
					'operation' => $op_view, // 操作名称

					'discount_reprice' => $this->input->post('discount_reprice'),
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
            // 操作可能需要检查操作权限
            // $role_allowed = array('管理员', '经理'); // 角色要求
// 			$min_level = 30; // 级别要求
// 			$this->basic->permission_check($role_allowed, $min_level);

            $op_name = '退单'; // 操作的名称
            $op_view = 'refuse'; // 视图文件名

			// 页面信息
			$data = array(
				'title' => $op_name,
				'class' => $this->class_name. ' '. $op_view,
				'error' => '', // 预设错误提示
			);

			// 赋值视图中需要用到的待操作项数据
			$data['ids'] = $ids = $this->parse_ids_array();

			// 获取待操作项数据
			$data['items'] = array();
			foreach ($ids as $id):
				// 从API服务器获取相应详情信息
				$params['id'] = $id;
                $params['biz_id'] = $this->session->biz_id;
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
                    'biz_id' => $this->session->biz_id,
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
		} // end refuse
		
		/**
		 * 接受已付款订单
		 */
		public function accept()
		{
            // 操作可能需要检查操作权限
            // $role_allowed = array('管理员', '经理'); // 角色要求
// 			$min_level = 30; // 级别要求
// 			$this->basic->permission_check($role_allowed, $min_level);

            $op_name = '接单'; // 操作的名称
            $op_view = 'accept'; // 视图文件名

			// 页面信息
			$data = array(
				'title' => $op_name,
				'class' => $this->class_name. ' '. $op_view,
				'error' => '', // 预设错误提示
			);

			// 赋值视图中需要用到的待操作项数据
			$data['ids'] = $ids = $this->parse_ids_array();
			
			// 获取待操作项数据
			$data['items'] = array();
			foreach ($ids as $id):
				// 从API服务器获取相应详情信息
				$params['id'] = $id;
                $params['biz_id'] = $this->session->biz_id;
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
                    'biz_id' => $this->session->biz_id,
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
		 * TODO 发货已接单订单
		 *
		 * 需添加特有字段
		 */
		public function deliver()
		{
            // 操作可能需要检查操作权限
            // $role_allowed = array('管理员', '经理'); // 角色要求
// 			$min_level = 30; // 级别要求
// 			$this->basic->permission_check($role_allowed, $min_level);

            $op_name = '发货'; // 操作的名称
            $op_view = 'deliver'; // 视图文件名

			// 页面信息
			$data = array(
				'title' => $op_name,
				'class' => $this->class_name. ' '. $op_view,
				'error' => '', // 预设错误提示
			);

			// 赋值视图中需要用到的待操作项数据
			$data['ids'] = $ids = $this->parse_ids_array();
			
			// 获取待操作项数据
			$data['items'] = array();
			foreach ($ids as $id):
				// 从API服务器获取相应详情信息
				$params['id'] = $id;
                $params['biz_id'] = $this->session->biz_id;
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
                    'biz_id' => $this->session->biz_id,
					'user_id' => $this->session->user_id,
					'ids' => $ids,
					'password' => $password,
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
		 * TODO 商家验证
		 *
		 * 对卡券验证码的有效性进行验证，并将相应的订单标记为待评价状态（即视为已收货）
		 */
		public function valid()
		{

		} // end valid

        /**
         * 删除订单
         *
         * 商家不可删除订单
         */
        public function delete()
        {
            exit('商家不可删除用户的订单；您意图删除用户订单的操作记录已被发送到安全中心。');
        } // end valid

        /**
         * 找回订单
         *
         * 商家不可找回订单
         */
        public function restore()
        {
            exit('商家不可恢复用户的订单；您意图删除用户订单的操作记录已被发送到安全中心。');
        } // end valid

	} // end class Order

/* End of file Order.php */
/* Location: ./application/controllers/Order.php */
