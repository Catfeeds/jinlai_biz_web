<?php
	defined('BASEPATH') OR exit('此文件不可被直接访问');

	/**
	 * Salor
	 *
	 * @version 1.0.0
	 * @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	 * @copyright ICBG <www.bingshankeji.com>
	 */
	class Salor extends MY_Controller
	{
		public $inWeb = FALSE;
		public function __construct(){
			parent::__construct();
			// 若已登录，转到首页
			($this->session->time_expire_login > time()) OR redirect( base_url("bizlogin/index") );
			// 向类属性赋值
			if ($_SERVER['HTTP_X_REAL_IP'] == '218.201.110.203') {
				$this->inWeb = TRUE;
			}
			$this->class_name = strtolower(__CLASS__);
			$this->class_name_cn = '商家后台'; // 改这里……
			$this->table_name = 'order_items'; // 和这里……
			$this->id_name = 'record_id'; // 还有这里，OK，这就可以了
			$this->view_root = $this->class_name; // 视图文件所在目录
			$this->media_root = MEDIA_URL. $this->class_name.'/'; // 媒体文件所在目录

			// 设置需要自动在视图文件中生成显示的字段
			$this->data_to_display = array(
				'name' => '商家后台',
				'description' => '描述',
			);
		} // end __construct

		public function index(){
			$data = ['title'=>'核销列表', 'index'=>'active', 'verify'=>'','refund'=>'', 'chat'=>'','activity'=>'', 'name'=>$this->session->brief_name, 'inWeb'=>$this->inWeb,];

			$this->load->model('orderitems_model');
			$data['status'] = empty($this->input->get('status')) ? 'all' : $this->input->get('status');
			$data['res']    = $this->orderitems_model->verifylist(FALSE);
			$data['count']  = $this->orderitems_model->verifylist(TRUE);
			$data['pager']  = $this->orderitems_model->pager($data['count'], 20, "/salor/index?status={$data['status']}&page=", "");
			$data['status_color'] = ['未付款'=>'grey','未消费'=>'info','已过期'=>'inverse','已关闭'=>'danger','已使用'=>'success','已退款'=>'warning',''];
			$this->load->view($this->view_root.'/header', $data);
			$this->load->view($this->view_root.'/index', $data);
			$this->load->view($this->view_root.'/footer', $data);
			$this->load->view($this->view_root.'/index-js', $data);
		}
		public function verify(){
			$data = ['title'=>'核销', 'index'=>'', 'verify'=>'active', 'refund'=>'', 'chat'=>'','activity'=>'','name'=>$this->session->brief_name, 'inWeb'=>$this->inWeb];
			$this->load->view($this->view_root.'/header', $data);
			$this->load->view($this->view_root.'/verify', $data);
			$this->load->view($this->view_root.'/footer', $data);
			$this->load->view($this->view_root.'/verify-js', $data);
		}
		public function detail(){
			$record_id = intval($this->input->get('record_id'));
			$data = ['title'=>'订单详情', 'index'=>'', 'verify'=>'','refund'=>'', 'chat'=>'', 'activity'=>'','name'=>$this->session->brief_name, 'large'=>'col-xs-5', 'offset'=>' col-xs-offset-2', 'inWeb'=>$this->inWeb];
			$this->load->model('orderitems_model');
			$data['res'] = $this->orderitems_model->getdetail($record_id, false);
			if (empty($data['res'])) {
				redirect(base_url("bizlogin/index"));
			}
			$this->switch_model('user', 'user_id');
			$data['user'] = $this->basic_model->find('user_id', $data['res']['user_id']);
			
			$this->load->view($this->view_root.'/header', $data);
			$this->load->view($this->view_root.'/detail', $data);
			$this->load->view($this->view_root.'/footer', $data);
		}
		public function ajaxdetail(){
			$returnJson = ['status'=>100, 'allowcheck'=>'no', 'msg'=>'', 'html'=>'', 'inWeb'=>$this->inWeb];
			$data = ['large'=>'col-md-6 col-xs-12' ,'offset'=>'col-md-offset-1 col-xs-offset-0'];
			$verify_code = $this->input->get('verify_code');
			if (!preg_match('/\d{10}/', $verify_code)) {
				$returnJson['msg'] = '核销码非法';
				echo json_encode($returnJson);
				exit;
			}
			$this->load->model('orderitems_model');
			$data['res'] = $this->orderitems_model->getdetail(false, $verify_code);

			if (empty($data['res']) || strlen($verify_code) != 10 ) {
				$returnJson['msg'] = '没有找到核销券';
				echo json_encode($returnJson);
				exit;
			}
            $returnJson['allowcheck'] = 'yes';
			if ($data['res']['status'] != '未消费') {
				$returnJson['msg'] = '无法核销 ';
                $returnJson['allowcheck'] = 'no';
			}
			if (intval($data['res']['time_expire']) < time()) {
				$returnJson['msg'] .= '已经过期';
                $returnJson['allowcheck'] = 'no';
			}
			$returnJson['rid'] = $data['res']['record_id'];
			$returnJson['oid'] = $data['res']['order_id'];
			$this->switch_model('user', 'user_id');
			$data['user'] = $this->basic_model->find('user_id', $data['res']['user_id']);

			$returnJson['html'] = $this->load->view($this->view_root.'/detail', $data, TRUE);
            $returnJson['status'] = 200;
			echo json_encode($returnJson);
			exit;
		}

		public function confirm(){
			$verify_code = $this->input->get('verify_code');
			$order_id    = intval($this->input->get('oid'));
			if (!preg_match('/\d{10}/', $verify_code)) {
				$returnJson['allowcheck'] = 'no';
				$returnJson['msg'] = '核销码非法';
				$returnJson['status'] = 100;
				echo json_encode($returnJson);
				exit;
			}
			$this->load->model('orderitems_model');
			$res = $this->orderitems_model->verify($verify_code, $order_id);
			if($res) {
				echo 'success';
				exit();
			}
			echo 'fail';
			exit();
		}

		public function refund(){
			$data = ['title'=>'核销列表', 'index'=>'', 'verify'=>'','refund'=>'active', 'chat'=>'', 'activity'=>'','name'=>$this->session->brief_name, 'res'=>[], 'inWeb'=>$this->inWeb];
			$params = [];
			$url = api_url('refund/index');
			$res = $this->curl->go($url, $params, 'array');
			if (empty($res) || $res['status'] != 200) {
				$data['res'] = [];
			} else {
				$data['res'] = $res['content'];
			}
			$this->load->view($this->view_root.'/header', $data);
			$this->load->view($this->view_root.'/refund', $data);
			$this->load->view($this->view_root.'/footer', $data);
		}

		public function accept(){
			$data = ['title'=>'核销列表', 'index'=>'', 'verify'=>'','refund'=>'', 'chat'=>'','activity'=>'', 'name'=>$this->session->brief_name, 'inWeb'=>$this->inWeb];
			$refund_id = $this->input->get('refund_id');
			$url = api_url('refund/detail');
			$res = $this->curl->go($url, ['id'=>$refund_id], 'array');
			$record = [];
			if (is_array($res) && $res['status'] == 200){
				$record = $res['content'];
				$data['record'] = $record;
				if ($record['status'] != '待处理') {
					redirect(BASE_URL("salor/index"));
				}
			} else {
				redirect(BASE_URL("salor/index"));
			}
			if(!empty($_POST) && !empty($_POST['password'])) {
				$refund = [];
				$refund['user_id'] = $record['user_id'];
				$refund['ids']     = $refund_id;
				$refund['password'] = $this->input->post('password');
				$refund['operation'] = 'accept';;
				$refund['total_approved'] = $this->input->post('total_approved');
				$refund['note_stuff'] = '';;
				$url = api_url('refund/edit_bulk');
				$res = $this->curl->go($url, $refund, 'array');
				if(is_array($res) && $res['status'] == 200) {
					$data['msg'] = $res['content']['message'];
				} else {

					$data['msg'] = '出错了 请联系工作人员';
				}
			}
			$this->load->view($this->view_root.'/header', $data);
			$this->load->view($this->view_root.'/accept', $data);
			$this->load->view($this->view_root.'/footer', $data);
		}
		/**
         * 更换所用数据库
         */
		protected function switch_model($table_name, $id_name)
		{
			$this->db->reset_query(); // 重置查询
			$this->basic_model->table_name = $table_name;
			$this->basic_model->id_name = $id_name;
		} // end switch_model

		
	}