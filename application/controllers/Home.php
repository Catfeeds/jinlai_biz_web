<?php
	defined('BASEPATH') OR exit('此文件不可被直接访问');

	/**
     * Home/HOM 首页类
	 *
	 * @version 1.0.0
	 * @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	 * @copyright ICBG <www.bingshankeji.com>
	 */
	class Home extends MY_Controller
	{
        public function __construct()
        {
            parent::__construct();
			// 未登录用户转到登录页
			($this->session->time_expire_login > time()) OR redirect( base_url('login') );

			if (isset($this->session->hideorigin)){
                redirect( base_url('salor/index') );
            }
            // 向类属性赋值
            $this->class_name = strtolower(__CLASS__);
            $this->class_name_cn = '首页'; // 改这里……
            $this->table_name = NULL; // 和这里……
            $this->id_name = NULL;  // 还有这里，OK，这就可以了
            $this->view_root = $this->class_name;
        } // end __construct

		// 首页
		public function index()
		{

			// 页面信息
			$data = array(
				'title' => '进来商家中心', // 页面标题
				'class' => $this->class_name, // 页面body标签的class属性值
			);

            // 若当前用户是某商家员工，获取该商家信息
            if ( ! empty($this->session->biz_id) ):
                $data['biz'] = $this->get_biz($this->session->biz_id);

                // 获取核心数据计数
                $data['count'] = array(
                    'item' => $this->count_table('item'),

                    //'order' => $this->count_table('order'),
                    'order_pay' => $this->count_table('order', array('status' => '待接单')),
                    'order_confirm' => $this->count_table('order', array('status' => '待发货')),

                    'refund' => $this->count_table('refund'),
                );
			endif;

			// 载入视图
			$this->load->view('templates/header', $data);
			$this->load->view('home', $data);
			$this->load->view('templates/nav-main', $data);
			$this->load->view('templates/footer', $data);
		} // end index

        /**
         * 删除
         *
         * 不可删除
         */
        public function delete()
        {
            exit('不可删除首页；您意图违规操作的记录已被发送到安全中心。');
        } // end delete

        /**
         * 找回
         *
         * 商家不可找回
         */
        public function restore()
        {
            exit('不可找回首页；您意图违规操作的记录已被发送到安全中心。');
        } // end restore

        /**
         * 以下为工具类方法
         */

	} // end class Home

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */
