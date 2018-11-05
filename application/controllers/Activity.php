<?php
	defined('BASEPATH') OR exit('此文件不可被直接访问');
	require_once './sdk/php-sdk-upyun/vendor/autoload.php'; // 针对压缩包安装
	use Upyun\Upyun;
	use Upyun\Config;
	/**
	 * Salor
	 *
	 * @version 1.0.0
	 * @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	 * @copyright ICBG <www.bingshankeji.com>
	 */
	class Activity extends MY_Controller
	{
		public $template_data = [];
		public $inWeb = [];

		public function __construct(){
			parent::__construct();
			// 若已登录，转到首页
			($this->session->time_expire_login > time()) OR redirect( base_url("bizlogin/index") );
			// 向类属性赋值
			$this->class_name = strtolower(__CLASS__);
			$this->class_name_cn = '商家后台'; // 改这里……
			$this->table_name = 'order_items'; // 和这里……
			$this->id_name = 'record_id'; // 还有这里，OK，这就可以了
			$this->view_root = $this->class_name; // 视图文件所在目录
			$this->media_root = MEDIA_URL. $this->class_name.'/'; // 媒体文件所在目录
			if ($_SERVER['HTTP_X_REAL_IP'] == '218.201.110.203') {
				$this->inWeb = TRUE;
			}
			// 设置需要自动在视图文件中生成显示的字段
			$this->data_to_display = array(
				'name' => '商家后台',
				'description' => '描述',
			);
			$this->template_data = ['title'=>'', 'index'=>'', 'verify'=>'','refund'=>'', 'chat'=>'', 'activity_index'=>'', 'activity_verify'=>'','activity_add'=>'','name'=>$this->session->brief_name,'activity'=>'active open','activity_ticketlist'=>'','inWeb' => $this->inWeb];
		} // end __construct

		public function activity_index(){
			$data = $this->template_data;
			$data['activity_index'] = 'active';

			$this->load->model('activity_model');
			$data['status'] = empty($this->input->get('status')) ? 'all' : $this->input->get('status');
			$data['res']    = $this->activity_model->verifylist(FALSE);
			$data['count']  = $this->activity_model->verifylist(TRUE);
			$data['pager']  = $this->activity_model->pager($data['count'], 20, "/activity/activity_index?status={$data['status']}&page=", "");
			$data['status_color'] = ['未使用'=>'info','已使用'=>'success','已作废'=>'danger',''=>'warning'];
			$this->load->view($this->view_root.'/header', $data);
			$this->load->view($this->view_root.'/index', $data);
			$this->load->view($this->view_root.'/footer', $data);
			$this->load->view($this->view_root.'/index-js', $data);
		}
		public function activity_verify(){
			$data = $this->template_data;
			$data['activity_verify'] = 'active';
			$this->load->view($this->view_root.'/header', $data);
			$this->load->view($this->view_root.'/verify', $data);
			$this->load->view($this->view_root.'/footer', $data);
			$this->load->view($this->view_root.'/verify-js', $data);
		}
	
		public function ajaxdetail(){
			$returnJson = ['status'=>100, 'allowcheck'=>'no', 'msg'=>'', 'html'=>''];
			$data = ['large'=>'col-md-6 col-xs-12' ,'offset'=>'col-md-offset-1 col-xs-offset-0'];
			$verify_code = $this->input->get('verify_code');
			if (!preg_match('/\d{10}/', $verify_code)) {
				$returnJson['msg'] = '核销码非法';
				echo json_encode($returnJson);
				exit;
			}
			$this->load->model('activity_model');
			$data['res'] = $this->activity_model->getdetail(false, $verify_code);

			if (empty($data['res']) || strlen($verify_code) != 10 ) {
				$returnJson['msg'] = '没有找到核销券';
				echo json_encode($returnJson);
				exit;
			}
            $returnJson['allowcheck'] = 'yes';
			if ($data['res']['status'] != '未使用') {
				$returnJson['msg'] = '无法使用 ';
                $returnJson['allowcheck'] = 'no';
			}
		
			$returnJson['rid'] = $data['res']['id'];

			$this->switch_model('user', 'user_id');
			$data['user'] = $this->basic_model->find('user_id', $data['res']['user_id']);

			$returnJson['html'] = $this->load->view($this->view_root.'/detail', $data, TRUE);
            $returnJson['status'] = 200;
			echo json_encode($returnJson);
			exit;
		}

		public function activity_add(){
			$data = $this->template_data;
			$data['activity_add'] = 'active';
			$data['status'] = 200;
			if ($_POST) {
				$validCheck = TRUE;
				$ticket_data = [];
				$ticket_data['ticket_name']  = empty($this->input->post('ticket_name')) ? '' : $this->input->post('ticket_name');
				
				$ticket_data['show_count']   = empty($this->input->post('show_count')) ? 100 : intval($this->input->post('show_count'));
				$ticket_data['sort']         = empty($this->input->post('sort')) ? '' : intval($this->input->post('sort'));
				foreach ($ticket_data as $key => $value) {
					if (empty($value)) {
						$validCheck = FALSE;
						break;
					}
				}
	            $url = api_url('biz/detail');
	            $result = $this->curl->go($url, ['app_type'=>'client','id'=>$this->session->biz_id], 'array');
	            //https://medias.517ybang.com/item/url_image_main/201807/0713/104740.jpg
	            if ($validCheck && !empty($_FILES['picture']['tmp_name'])) { //判断上传内容是否为空
	            	$serviceConfig = new Config('jinlaisandbox-images', 'jinlaisandbox', 'jinlaisandbox');
	            	$client = new Upyun($serviceConfig);
	                $name = $_FILES['picture']['tmp_name'];
	                $file = fopen($name, 'r');
	                $url = '/item/homepage_img_url/';
	                $url.= date("Ym") . '/';
	                $url.= date("md") . '/';
	                $url.= 'pageimg' . time() . '.jpg';
	                $client->write($url, $file, array(
	                    'x-gmkerl-thumb' => '/format/jpg'
	                ));
	                $ticket_data['picture'] = 'https://medias.517ybang.com' . $url;

	                $ticket_data['time_create'] = time();
	                $ticket_data['biz_id']      = $this->session->biz_id;
					$ticket_data['sub_name']    = $result['content']['brief_name'];
					$this->load->model('activity_model');
					$res = $this->activity_model->create($ticket_data);	               
					if ($res) {
						$data['status'] = 200;
	            		$data['msg'] = '添加成功！';
					} else {
						$data['status'] = 400;
	            		$data['msg'] = '添加失败';
					}
	            } else {
	            	$data['status'] = 400;
	            	$data['msg'] = '请填写完整';
	            }
	            
			}
		

			$this->load->view($this->view_root.'/header', $data);
			$this->load->view($this->view_root.'/add', $data);
			$this->load->view($this->view_root.'/footer', $data);
			$this->load->view($this->view_root.'/add-js', $data);
		}
		public function delete_ticket(){
			$id = $this->input->get('id');
			if (empty($id)) {
				redirect(base_url("activity/activity_ticketlist"));
				exit;
			}

			$this->load->model('activity_model');
			$res = $this->activity_model->delete($id);
			redirect(base_url("activity/activity_ticketlist"));
			exit;
		}
		public function activity_ticketlist(){
			$data = $this->template_data;
			$data['activity_ticketlist'] = 'active';

			$this->load->model('activity_model');
			$data['res']    = $this->activity_model->ticketlist(FALSE);
			$data['count']  = $this->activity_model->ticketlist(TRUE);
			$data['pager']  = $this->activity_model->pager($data['count'], 20, "/activity/activity_index?page=", "");
			$this->load->view($this->view_root.'/header', $data);
			$this->load->view($this->view_root.'/ticketlist', $data);
			$this->load->view($this->view_root.'/footer', $data);

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
			$this->load->model('activity_model');
			$res = $this->activity_model->verify($verify_code);
			if($res) {
				echo 'success';
				exit;
			}
			echo 'fail';
			exit;
		}
		public function cancel(){
			$id = intval($this->input->get('id'));
			$this->load->model('activity_model');
			$res = $this->activity_model->cancel($id);
			if($res) {
				echo 'success';
				exit;
			}
			echo 'fail';
			exit;
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