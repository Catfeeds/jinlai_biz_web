<?php
	defined('BASEPATH') OR exit('此文件不可被直接访问');

	/**
	 * Home 类
	 *
	 * 首页的示例代码示例代码
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
            
            // 向类属性赋值
            $this->class_name = strtolower(__CLASS__);
            $this->class_name_cn = '类名'; // 改这里……
            $this->table_name = 'table'; // 和这里……
            $this->id_name = 'table_id';  // 还有这里，OK，这就可以了
            $this->view_root = $this->class_name;
        }

		// 首页
		public function index()
		{
			// 页面信息
			$data = array(
				'title' => NULL, // 直接使用默认标题
				'class' => $this->class_name, // 页面body标签的class属性值
			);

			// 若当前用户是某商家员工，获取该商家信息
			if ( !empty($this->session->biz_id) ):
				// 从API服务器获取相应详情信息
				$params['id'] = $this->session->biz_id;
				$url = api_url('biz/detail');
				$result = $this->curl->go($url, $params, 'array');
				if ($result['status'] === 200):
					$data['biz'] = $result['content'];
				else:
					$data['error'] = $result['content']['error']['message'];
				endif;
			endif;

			// 获取核心数据计数
			$data['count'] = array(
				'item' => $this->count_table('item'),
				'order' => $this->count_table('order'),
				'item_category_biz' => $this->count_table('item_category_biz'),
				'freight_template_biz' => $this->count_table('freight_template_biz'),

				'promotion' => $this->count_table('promotion'),
				'promotion_biz' => $this->count_table('promotion_biz'),
				'coupon_template' => $this->count_table('coupon_template'),
				'coupon_combo' => $this->count_table('coupon_combo'),
				'stuff' => $this->count_table('stuff'),
				'branch' => $this->count_table('branch'),

				// 以下功能开通后可取消注释，并在视图文件中添加相应DOM
				/*
				'refund' => $this->count_table('refund'),
				'comment_item' => $this->count_table('comment_item'),
				'comment_order' => $this->count_table('comment_order'),
				*/
			);

			// 载入视图
			$this->load->view('templates/header', $data);
			$this->load->view('home', $data);
			$this->load->view('templates/nav-main', $data);
			$this->load->view('templates/footer', $data);
		}
	}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */
