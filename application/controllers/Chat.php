<?php
	defined('BASEPATH') OR exit('此文件不可被直接访问');

	/**
	 * 客服
	 *
	 * @version 1.0.0
	 * @author huangxin
	 * @copyright ICBG <www.bingshankeji.com>
	 */


	class Chat extends MY_Controller
	{	
		public function __construct(){
			parent::__construct();

			// （可选）未登录用户转到登录页
			($this->session->time_expire_login > time()) OR redirect( base_url('login') );

			// 向类属性赋值
			$this->class_name = strtolower(__CLASS__);
			$this->class_name_cn = '消息'; // 改这里……
			$this->table_name = 'chat'; // 和这里……
			$this->id_name = 'message_id'; // 还有这里，OK，这就可以了
			$this->view_root = $this->class_name; // 视图文件所在目录
			$this->media_root = MEDIA_URL. $this->class_name.'/'; // 媒体文件所在目录
		} // end __construct

		public function index(){
			// 从API服务器获取相应详情信息
			$url = api_url('biz/detail');
			$result = $this->curl->go($url, ['id'=>$this->session->biz_id], 'array');
			if($result['status'] == 200){
				$result = $result['content'];
				$this->session->url_logo = is_null($result['url_logo']) ? 'https://cdn-remote.517ybang.com/default_avatar.png' : $result['url_logo'];
				$this->session->brief_name = $result['brief_name'];
			} else {
				exit;
			}
			
			$data = [];
			// 输出视图
			$this->load->view($this->view_root.'/index', $data);
		}
	}