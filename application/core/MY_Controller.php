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
	    }

		/**
		 * 截止3.1.3为止，CI_Controller类无析构函数，所以无需继承相应方法
		 */
		public function __destruct()
		{
			
		}

		/**
		 * 签名有效性检查
		 *
		 * 依次检查签名的时间是否过期、参数是否完整、签名是否正确
		 *
		 * @params array sign_to_check 待检查的签名数据
		 */
		public function sign_check($sign_to_check)
		{
			$this->sign_check_exits();
			$this->sign_check_time();
			$this->sign_check_params();
			$this->sign_check_string();
		}

		// TODO 检查签名是否传入
		public function sign_check_exits()
		{
			$this->sign = $this->input->post('sign');

			if ( empty($this->sign) ):
				$this->result['status'] = 444;
				$this->result['content']['error']['message'] = '未传入签名';
				exit();
			endif;
		}

		// TODO 签名时间检查
		public function sign_check_time()
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
		}

		// TODO 签名参数检查
		public function sign_check_params()
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
		}

		// TODO 签名正确性检查
		public function sign_check_string()
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
		}

		/**
		 * 生成签名
		 */
		public function sign_generate($params)
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
		}

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
		}
	}