<?php
	defined('BASEPATH') OR exit('此文件不可被直接访问');

	/**
	 * Refund 退款/售后类
     *
     * 仅退款类型，当商家同意后进行退款；退货退款类型，当商家收货后进行退款
     * 若货款未结算，则直接从商家余额中扣除相应商家余额到平台余额，平台向用户原路退款
     * 若货款已结算，商家余额足够时扣除相应商家余额到平台余额，平台向用户原路退款；余额不足时创建对商家的待收款项和对用户的待付款项
	 *
	 * @version 1.0.0
	 * @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	 * @copyright ICBG <www.bingshankeji.com>
	 */
	class Refund extends MY_Controller
	{	
		/**
		 * 可作为列表筛选条件的字段名；可在具体方法中根据需要删除不需要的字段并转换为字符串进行应用，下同
		 */
		protected $names_to_sort = array(
			'order_id', 'biz_id', 'user_id', 'record_id', 'type', 'cargo_status', 'reason', 'description', 'url_images', 'total_applied', 'total_approved', 'deliver_method', 'deliver_biz', 'waybill_id', 'time_create', 'time_cancel', 'time_close', 'time_refuse', 'time_accept', 'time_refund', 'time_edit', 'operator_id', 'status',
		);

		/**
		 * 可被编辑的字段名
		 */
		protected $names_edit_allowed = array(
			'type', 'cargo_status', 'reason', 'description', 'url_images', 'total_applied', 'total_approved', 'deliver_method', 'deliver_biz', 'waybill_id',
		);

		/**
		 * 完整编辑单行时必要的字段名
		 */
		protected $names_edit_required = array(
			'id',
			'type', 'cargo_status', 'reason', 'description', 'url_images', 'total_applied', 'total_approved', 'deliver_method', 'deliver_biz', 'waybill_id',
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
			($this->session->time_expire_login > time()) OR redirect( base_url('login') );

			// 向类属性赋值
			$this->class_name = strtolower(__CLASS__);
			$this->class_name_cn = '退款/售后'; // 改这里……
			$this->table_name = 'refund'; // 和这里……
			$this->id_name = 'refund_id'; // 还有这里，OK，这就可以了
			$this->view_root = $this->class_name; // 视图文件所在目录
            $this->media_root = MEDIA_URL. 'item/'; // 媒体文件所在目录，默认为商品信息

			// 设置需要自动在视图文件中生成显示的字段
			$this->data_to_display = array(
				'order_id' => '订单ID',
				'record_id' => '订单商品ID',
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
			$url = api_url($this->class_name. '/detail');
			$result = $this->curl->go($url, $params, 'array');
			if ($result['status'] === 200):
				$data['item'] = $result['content'];
			else:
				redirect( base_url('error/code_404') ); // 若未成功获取信息，则转到错误页
			endif;

			// 待验证的表单项
			$this->form_validation->set_error_delimiters('', '；');
			$this->form_validation->set_rules('type', '类型', 'trim|required');
			$this->form_validation->set_rules('cargo_status', '货物状态', 'trim|required');
			$this->form_validation->set_rules('reason', '原因', 'trim|required');
			$this->form_validation->set_rules('description', '补充说明', 'trim');
			$this->form_validation->set_rules('url_images', '相关图片URL', 'trim');
			$this->form_validation->set_rules('total_applied', '申请退款金额（元）', 'trim|required|greater_than_equal_to[1]|less_than_equal_to[99999.99]');
			$this->form_validation->set_rules('total_approved', '实际退款金额（元）', 'trim|greater_than_equal_to[1]|less_than_equal_to[99999.99]');
			$this->form_validation->set_rules('deliver_method', '发货方式', 'trim');
			$this->form_validation->set_rules('deliver_biz', '物流服务商', 'trim');
			$this->form_validation->set_rules('waybill_id', '物流运单号', 'trim');

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
					//'name' => $this->input->post('name')),
				);
				// 自动生成无需特别处理的数据
				$data_need_no_prepare = array(
					'refund_id', 'order_id', 'biz_id', 'user_id', 'record_id', 'type', 'cargo_status', 'reason', 'description', 'url_images', 'total_applied', 'total_approved', 'deliver_method', 'deliver_biz', 'waybill_id', 'time_create', 'time_cancel', 'time_close', 'time_refuse', 'time_accept', 'time_refund', 'time_edit', 'operator_id', 'status',
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
					// 若修改失败，则进行提示
					$data['error'] = $result['content']['error']['message'];

					$this->load->view('templates/header', $data);
					$this->load->view($this->view_root.'/edit', $data);
					$this->load->view('templates/footer', $data);

				endif;

			endif;
		} // end edit

        /**
         * 拒绝退款
         */
        public function refuse()
        {
            // 操作可能需要检查操作权限
            // $role_allowed = array('管理员', '经理'); // 角色要求
// 			$min_level = 30; // 级别要求
// 			$this->basic->permission_check($role_allowed, $min_level);

            $op_name = '拒绝'; // 操作的名称
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
         * 同意退款
         */
        public function accept()
        {
            // 操作可能需要检查操作权限
            // $role_allowed = array('管理员', '经理'); // 角色要求
// 			$min_level = 30; // 级别要求
// 			$this->basic->permission_check($role_allowed, $min_level);

            $op_name = '同意'; // 操作的名称
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
            $this->form_validation->set_rules('note_stuff', '员工备注', 'trim');

            // 非批量同意退款时可以修改同意退款金额
            if (count($data['items']) === 1):
                $this->form_validation->set_rules('total_approved', '同意退款金额', 'trim|required|greater_than[0.01]|less_than_equal_to[99999.99]');
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
                    'password' => $password,
                    'operation' => $op_view, // 操作名称

                    'total_approved' => $this->input->post('total_approved'),
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
        } // end accept

        /**
         * 确认收货
         */
        public function confirm()
        {
            // 操作可能需要检查操作权限
            // $role_allowed = array('管理员', '经理'); // 角色要求
// 			$min_level = 30; // 级别要求
// 			$this->basic->permission_check($role_allowed, $min_level);

            $op_name = '收货'; // 操作的名称
            $op_view = 'confirm'; // 视图文件名

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
        } // end confirm

	} // end class Refund

/* End of file Refund.php */
/* Location: ./application/controllers/Refund.php */
