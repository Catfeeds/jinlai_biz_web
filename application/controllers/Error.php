<?php
	defined('BASEPATH') OR exit('此文件不可被直接访问');

	/**
	 * Error 错误类
	 *
	 * @version 1.0.0
	 * @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	 * @copyright ICBG <www.bingshankeji.com>
	 */
	class Error extends MY_Controller
	{
		public function __construct()
		{
			parent::__construct();
			
			// 向类属性赋值
			$this->class_name = strtolower(__CLASS__);
			$this->class_name_cn = '错误'; // 改这里……
			$this->table_name = NULL; // 和这里……
			$this->id_name = NULL; // 还有这里，OK，这就可以了
            $this->view_root = $this->class_name; // 视图文件所在目录
            $this->media_root = MEDIA_URL. $this->class_name.'/'; // 媒体文件所在目录
		}

		/**
		 * 列表页
		 */
		public function index()
		{
			redirect( base_url() );
		} // end index
		
		/**
		 * 404
		 */
		public function code_400()
		{
			// 页面信息
			$data = array(
				'title' => '400',
				'class' => 'error error-400',
				'content' => '必要的请求参数未全部传入。',
			);

			$this->load->view('templates/header', $data);
			$this->load->view($this->view_root.'/400', $data);
			$this->load->view('templates/footer', $data);
		} // end code_400
		
		/**
		 * 404
		 */
		public function code_404()
		{
			// 页面信息
			$data = array(
				'title' => '404',
				'class' => 'error error-404',
				'content' => '未找到相应的信息。',
			);

			$this->load->view('templates/header', $data);
			$this->load->view($this->view_root.'/404', $data);
			$this->load->view('templates/footer', $data);
		} // end code_404
		
		/**
		 * 权限 所有权不符
		 */
		public function not_yours()
		{
			// 页面信息
			$data = array(
				'title' => '权限问题 - 所有权不符',
				'class' => 'error error-role',
				'content' => '您无法操作该项。',
			);

			$this->load->view('templates/header', $data);
			$this->load->view($this->view_root.'/not_yours', $data);
			$this->load->view('templates/footer', $data);
		} // end not_yours
		
		/**
		 * 权限 角色不符
		 */
		public function permission_role()
		{
			// 页面信息
			$data = array(
				'title' => '权限问题 - 角色不符',
				'class' => 'error error-role',
				'content' => '只有特定角色的用户可以进行该操作。',
			);

			$this->load->view('templates/header', $data);
			$this->load->view($this->view_root.'/permission', $data);
			$this->load->view('templates/footer', $data);
		} // end permission_role
		
		/**
		 * 权限 级别不足
		 */
		public function permission_level()
		{
			// 页面信息
			$data = array(
				'title' => '权限问题 - 级别不足',
				'class' => 'error error-role',
				'content' => '只有达到特定级别的用户可以进行该操作。',
			);

			$this->load->view('templates/header', $data);
			$this->load->view($this->view_root.'/permission', $data);
			$this->load->view('templates/footer', $data);
		} // end permission_level
	} // end class Error

/* End of file Error.php */
/* Location: ./application/controllers/Error.php */
