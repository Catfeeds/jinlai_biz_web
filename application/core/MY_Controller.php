<?php
	defined('BASEPATH') OR exit('此文件不可被直接访问');
	
	/**
	 * MY_Controller 基础控制器类
	 *
	 * 针对API服务，对Controller类进行了扩展
	 *
	 * @version 1.0.0
	 * @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	 * @copyright ICBG <www.bingshankeji.com>
	 */
	class MY_Controller extends CI_Controller
	{
		// 初始化返回结果
		public $result = array(
			'status' => null, // 请求响应状态
			'content' => null, // 返回内容
			'param' => array(
				'get' => array(), // GET请求参数
				'post' => array(), // POST请求参数
			), // 接收到的请求参数
			'timestamp' => null, // 返回时时间戳
			'datetime' => null, // 返回时可读日期
			'timezone' => null, // 服务器本地时区
			'elapsed_time' => null, // 处理业务请求时间
		);

		/* 类名称小写，应用于多处动态生成内容 */
		public $class_name;

		/* 类名称中文，应用于多处动态生成内容 */
		public $class_name_cn;
		
		/* 主要相关表名 */
		public $table_name;

		/* 主要相关表的主键名*/
		public $id_name;
		
		/* 视图文件所在目录名 */
		public $view_root;

		/* 媒体文件（非样式图片、视频、音频等）所在目录名 */
		public $media_root;
		
		/* 需要显示的字段 */
		public $data_to_display;

		// 客户端类型
		protected $app_type;

		// 客户端版本号
		protected $app_version;

		// 设备操作系统平台ios/android；非移动客户端传空值
		protected $device_platform;

		// 设备唯一码；全小写
		protected $device_number;

		// 请求时间戳
		protected $timestamp;

		// 请求签名
		private $sign;

		public function __construct()
	    {
	        parent::__construct();

			// 向类属性赋值
			$this->timestamp = time();
			$this->app_type = 'biz';
			$this->app_version = '0.0.1';
			$this->device_platform = 'web';
			$this->device_number = '';
	    } // end __construct

		/**
		 * 截止3.1.3为止，CI_Controller类无析构函数，所以无需继承相应方法
		 */
		public function __destruct()
		{
			
		} // end __destruct
		
		// 将数组输出为key:value格式，主要用于在postman等工具中进行api测试
		protected function key_value($params)
		{
			foreach ($params as $key => $value):
				echo $key .':' .$value ."\n";
			endforeach;
		} // end key_value

		/**
		 * 签名有效性检查
		 *
		 * 依次检查签名的时间是否过期、参数是否完整、签名是否正确
		 *
		 * @params array sign_to_check 待检查的签名数据
		 */
		protected function sign_check($sign_to_check)
		{
			$this->sign_check_exits();
			$this->sign_check_time();
			$this->sign_check_params();
			$this->sign_check_string();
		} // end sign_check

		// 检查签名是否传入
		protected function sign_check_exits()
		{
			$this->sign = $this->input->post('sign');

			if ( empty($this->sign) ):
				$this->result['status'] = 444;
				$this->result['content']['error']['message'] = '未传入签名';
				exit();
			endif;
		} // end sign_check_exits

		// 签名时间检查
		protected function sign_check_time()
		{
			$timestamp_sign = $this->input->post('timestamp');

			if ( empty($timestamp_sign) ):
				$this->result['status'] = 440;
				$this->result['content']['error']['message'] = '必要的签名参数未全部传入；安全起见不做具体提示，请参考开发文档。';
				exit();

			else:
				$this->timestamp = time();
				$time_difference = ($this->timestamp - $timestamp_sign);

				// 测试阶段签名有效期为600秒，生产环境应为60秒
				if ($time_difference > 600):
					$this->result['status'] = 441;
					$this->result['content']['error']['message'] = '签名时间已超过有效区间。';
					exit();

				else:
					return TRUE;

				endif;

			endif;
		} // end sign_check_time

		// 签名参数检查
		protected function sign_check_params()
		{
			// 检查需要参与签名的必要参数；
			$params_required = array(
				'app_type',
				'app_version',
				'device_platform',
				'device_number',
				'timestamp',
				'random',
			);

			// 获取传入的参数们
			$params = $_POST;

			// 检查必要参数是否已传入
			if ( array_intersect_key($params_required, array_keys($params)) !== $params_required ):
				$this->result['status'] = 440;
				$this->result['content']['error']['message'] = '必要的签名参数未全部传入；安全起见不做具体提示，请参考开发文档。';
			else:
				return TRUE;
			endif;
		} // end sign_check_params

		// 签名正确性检查
		protected function sign_check_string()
		{
			// 获取传入的参数们
			$params = $_POST;
			unset($params['sign']); // sign本身不参与签名计算

			// 生成参数
			$sign = $this->sign_generate($params);

			// 对比签名是否正确
			if ($this->sign !== $sign):
				$this->result['status'] = 449;
				$this->result['content']['error']['message'] = '签名错误，请参考开发文档。';
				$this->result['content']['sign_expected'] = $sign;
				$this->result['content']['sign_offered'] = $this->sign;
				exit();

			else:
				return TRUE;

			endif;
		} // end sign_check_string

		/**
		 * 生成签名
		 */
		protected function sign_generate($params)
		{
			// 对参与签名的参数进行排序
			ksort($params);

			// 对随机字符串进行SHA1计算
			$params['random'] = SHA1( $params['random'] );

			// 拼接字符串
			$param_string = '';
			foreach ($params as $key => $value)
				$param_string .= '&'. $key.'='.$value;

			// 拼接密钥
			$param_string .= '&key='. API_TOKEN;

			// 计算字符串SHA1值并转为大写
			$sign = strtoupper( SHA1($param_string) );

			return $sign;
		} // end sign_generate

		/**
		 * 权限检查
		 *
		 * @param array $role_allowed 拥有相应权限的角色
		 * @param int $min_level 最低级别要求
		 * @return void
		 */
		protected function permission_check($role_allowed, $min_level)
		{
			// 目前管理员角色和级别
			$current_role = $this->session->role;
			$current_level = $this->session->level;

			// 检查执行此操作的角色及权限要求
			if ( ! in_array($current_role, $role_allowed)):
				redirect( base_url('error/permission_role') );
			elseif ( $current_level < $min_level):
				redirect( base_url('error/permission_level') );
			endif;
		} // end permission_check
		
		/**
		 * 获取批量操作时的ID数组
		 *
		 * @return array $ids 解析为数组的ID们
		 */
		protected function parse_ids_array()
		{
			// 检查是否已传入必要参数
			if ( !empty($this->input->get_post('ids')) ):
				$ids = $this->input->get_post('ids');

				// 将字符串格式转换为数组格式
				if ( !is_array($ids) ):
					$ids = explode(',', $ids);
				endif;

			elseif ( !empty($this->input->post('ids[]')) ):
				$ids = $this->input->post('ids[]');

			else:
				redirect( base_url('error/code_400') ); // 若缺少参数，转到错误提示页

			endif;
			
			return $ids;
		} // end parse_ids_array

		/**
		 * 删除单行或多行项目
		 *
		 * 一般用于发货、退款、存为草稿、上架、下架、删除、恢复等状态变化，请根据需要修改方法名，例如deliver、refund、delete、restore、draft等
		 */
		public function delete()
		{
			// 操作可能需要检查操作权限
			// $role_allowed = array('管理员', '经理'); // 角色要求
// 			$min_level = 30; // 级别要求
// 			$this->basic->permission_check($role_allowed, $min_level);

			$op_name = '删除'; // 操作的名称
			$op_view = 'delete'; // 视图文件名

			// 页面信息
			$data = array(
				'title' => $op_name. $this->class_name_cn,
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
		} // end delete

		/**
		 * 恢复单行或多行项目
		 *
		 * 一般用于存为草稿、上架、下架、删除、恢复等状态变化，请根据需要修改方法名，例如delete、restore、draft等
		 */
		public function restore()
		{
			// 操作可能需要检查操作权限
			// $role_allowed = array('管理员', '经理'); // 角色要求
// 			$min_level = 30; // 级别要求
// 			$this->basic->permission_check($role_allowed, $min_level);

			$op_name = '恢复'; // 操作的名称
			$op_view = 'restore'; // 视图文件名

			// 页面信息
			$data = array(
				'title' => $op_name. $this->class_name_cn,
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
		} // end restore

		// 获取商品列表
		protected function list_item()
		{
			// 仅可获取当前商家的商品
			$params['biz_id'] = $this->session->biz_id;

			// 从API服务器获取相应列表信息
			$url = api_url('item/index');
			$result = $this->curl->go($url, $params, 'array');
			if ($result['status'] === 200):
				$data['items'] = $result['content'];
			else:
				$data['items'] = NULL;
			endif;
			
			return $data['items'];
		}

		// 获取特定商品信息
		protected function get_item($id)
		{
			// 仅可获取当前商家的商品
			$params['biz_id'] = $this->session->biz_id;

			// 从API服务器获取相应列表信息
			$params['id'] = $id;
			$url = api_url('item/detail');
			$result = $this->curl->go($url, $params, 'array');
			if ($result['status'] === 200):
				$data['item'] = $result['content'];
			else:
				$data['item'] = NULL;
			endif;
			
			return $data['item'];
		}
		
		// 获取商品列表
		protected function list_sku($item_id = NULL)
		{
			// 仅可获取当前商家的商品
			$params['biz_id'] = $this->session->biz_id;
			
			if ( !empty($item_id) ):
				$params['item_id'] = $item_id;
			endif;

			// 从API服务器获取相应列表信息
			$url = api_url('sku/index');
			$result = $this->curl->go($url, $params, 'array');
			if ($result['status'] === 200):
				$data['items'] = $result['content'];
			else:
				$data['items'] = NULL;
			endif;
			
			return $data['items'];
		}

		// 获取特定商品信息
		protected function get_sku($id)
		{
			// 仅可获取当前商家的商品
			$params['biz_id'] = $this->session->biz_id;

			// 从API服务器获取相应列表信息
			$params['id'] = $id;
			$url = api_url('sku/detail');
			$result = $this->curl->go($url, $params, 'array');
			if ($result['status'] === 200):
				$data['item'] = $result['content'];
			else:
				$data['item'] = NULL;
			endif;
			
			return $data['item'];
		}
		
		// 获取品牌列表
		protected function list_brand()
		{
			// 从API服务器获取相应列表信息
			$params = NULL;
			$url = api_url('brand/index');
			$result = $this->curl->go($url, $params, 'array');
			if ($result['status'] === 200):
				$data['items'] = $result['content'];
			else:
				$data['items'] = NULL;
			endif;

			return $data['items'];
		}
		
		// 获取特定品牌信息
		protected function get_brand($id)
		{
			// 从API服务器获取相应列表信息
			$params['id'] = $id;
			$url = api_url('brand/detail');
			$result = $this->curl->go($url, $params, 'array');
			if ($result['status'] === 200):
				$data['item'] = $result['content'];
			else:
				$data['item'] = NULL;
			endif;
			
			return $data['item'];
		}

		// 获取系统分类列表
		protected function list_category()
		{
			// 从API服务器获取相应列表信息
			$params = NULL;
			$url = api_url('item_category/index');
			$result = $this->curl->go($url, $params, 'array');
			if ($result['status'] === 200):
				$data['items'] = $result['content'];
			else:
				$data['items'] = NULL;
			endif;
			
			return $data['items'];
		}
		
		// 获取特定系统分类信息
		protected function get_category($id)
		{
			// 从API服务器获取相应列表信息
			$params['id'] = $id;
			$url = api_url('item_category/detail');
			$result = $this->curl->go($url, $params, 'array');
			if ($result['status'] === 200):
				$data['item'] = $result['content'];
			else:
				$data['item'] = NULL;
			endif;
			
			return $data['item'];
		}
		
		// 获取商家分类列表
		protected function list_category_biz($id = NULL)
		{
			if ( !empty($this->session->biz_id) ):
				$params['biz_id'] = $this->session->biz_id;
			else:
				$params['biz_id'] = $id;
			endif;

			// 从API服务器获取相应列表信息
			$url = api_url('item_category_biz/index');
			$result = $this->curl->go($url, $params, 'array');
			if ($result['status'] === 200):
				$data['items'] = $result['content'];
			else:
				$data['items'] = NULL;
			endif;
			
			return $data['items'];
		}
		
		// 获取特定商家分类信息
		protected function get_category_biz($id)
		{
			// 从API服务器获取相应列表信息
			$params['id'] = $id;
			$url = api_url('item_category_biz/detail');
			$result = $this->curl->go($url, $params, 'array');
			if ($result['status'] === 200):
				$data['item'] = $result['content'];
			else:
				$data['item'] = NULL;
			endif;
			
			return $data['item'];
		}

		// 获取店内活动列表
		protected function list_promotion_biz()
		{
			// 从API服务器获取相应列表信息
			$params = array(
				'biz_id' => $this->session->biz_id,
			);
			$url = api_url('promotion_biz/index');
			$result = $this->curl->go($url, $params, 'array');
			if ($result['status'] === 200):
				$data['items'] = $result['content'];
			else:
				$data['items'] = NULL;
			endif;

			return $data['items'];
		}
		
		// 获取店内活动详情
		protected function get_promotion_biz($id)
		{
			// 从API服务器获取相应列表信息
			$params['id'] = $id;
			$url = api_url('promotion_biz/detail');
			$result = $this->curl->go($url, $params, 'array');
			if ($result['status'] === 200):
				$data['item'] = $result['content'];
			else:
				$data['item'] = NULL;
			endif;
			
			return $data['item'];
		}
		
		// 获取商家运费模板
		protected function list_freight_template_biz($id = NULL)
		{
			if ( !empty($this->session->biz_id) ):
				$params['biz_id'] = $this->session->biz_id;
			else:
				$params['biz_id'] = $id;
			endif;

			// 从API服务器获取相应列表信息
			$url = api_url('freight_template_biz/index');
			$result = $this->curl->go($url, $params, 'array');
			if ($result['status'] === 200):
				$data['items'] = $result['content'];
			else:
				$data['items'] = NULL;
			endif;

			return $data['items'];
		}

		// 获取特定商家运费模板详情
		protected function get_freight_template_biz($id)
		{
			// 从API服务器获取相应列表信息
			$params['id'] = $id;
			$url = api_url('freight_template_biz/detail');
			$result = $this->curl->go($url, $params, 'array');
			if ($result['status'] === 200):
				$data['item'] = $result['content'];
			else:
				$data['item'] = NULL;
			endif;

			return $data['item'];
		}

		/**
		 * count_table
		 *
		 * @params string $table_name 需要计数的表名
		 * @params array $conditions 筛选条件
		 * @return int/boolean
		 **/
		protected function count_table($table_name, $conditions = NULL)
		{
			// 获取筛选条件
			if ( !empty($conditions) ):
				$params = $conditions;
			endif;

			// 获取当前商家信息
			$params['biz_id'] = $this->session->biz_id;

			// 从API服务器获取相应列表信息
			$url = api_url($table_name. '/count');
			$result = $this->curl->go($url, $params, 'array');
			if ($result['status'] === 200):
				$count = $result['content']['count'];
			else:
				$count = 0; // 若获取失败则返回“0”
			endif;

			return $count;
		}

		// 输出POST参数
		protected function echo_param($param)
		{
			$result = '';
			foreach ($param as $name => $value):
				$result .= $name. ':'. $value. "\n";
			endforeach;
			
			echo $result;
		}

	} // end class MY_Controller
	
/* End of file MY_Controller.php */
/* Location: ./application/controllers/MY_Controller.php */