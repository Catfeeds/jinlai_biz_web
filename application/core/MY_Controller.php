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

		/* 当前用户员工信息 */
		public $stuff = array();

        /* 访问设备信息 */
        public $user_agent = array();

		/* 客户端类型 */
        public $app_type;

		/* 客户端版本号 */
        public $app_version;

		/* 设备操作系统平台ios/android；非移动客户端传空值 */
        public $device_platform;

		/* 设备唯一码；全小写 */
		protected $device_number;

		/* 当前时间戳 */
		protected $timestamp;

		/* 请求签名 */
		private $sign;

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

			// 向类属性赋值
			$this->timestamp = time();
			$this->app_type = 'biz';
			$this->app_version = '0.0.1';
			$this->device_platform = 'web';
			$this->device_number = '';

            // 若当前用户是某商家员工，获取该员工身份信息
            if ( ! empty($this->session->stuff_id) )
                $this->stuff = $this->get_stuff($this->session->stuff_id, FALSE);

            // 检查当前设备信息
			$this->user_agent_determine();
	    } // end __construct

		/**
		 * 截止3.1.3为止，CI_Controller类无析构函数，所以无需继承相应方法
		 */
		public function __destruct()
		{
            // 如果已经打开测试模式，则输出调试信息
            if ($this->input->post_get('test_mode') === 'on')
                $this->output->enable_profiler(TRUE);
		} // end __destruct

        /**
         * 检查访问设备类型
         */
        protected function user_agent_determine()
        {
            // 获取当前设备信息
            $user_agent = $_SERVER['HTTP_USER_AGENT'];

            // 判断是否为移动端
            $this->user_agent['is_wechat'] = strpos($user_agent, 'MicroMessenger/')? TRUE: FALSE;
            $this->user_agent['is_alipay'] = strpos($user_agent, 'AlipayClient/')? TRUE: FALSE;
            $this->user_agent['is_ios'] = strpos($user_agent, 'like Mac OS')? TRUE: FALSE;
            $this->user_agent['is_android'] = strpos($user_agent, 'Android')? TRUE: FALSE;
            $this->user_agent['is_mobile'] = ($this->user_agent['is_wechat'] || $this->user_agent['is_ios'] || $this->user_agent['is_android'])? TRUE: FALSE; // 移动端设备

            // 判断是否为非移动端
            $this->user_agent['is_macos'] = strpos($user_agent, 'Macintosh;')? TRUE: FALSE;
            $this->user_agent['is_linux'] = (strpos($user_agent, 'Linux;') && !strpos($user_agent, 'Android'))? TRUE: FALSE;
            $this->user_agent['is_windows'] = strpos($user_agent, 'Windows ')? TRUE: FALSE;
            $this->user_agent['is_desktop'] = ( ! $this->user_agent['is_mobile'])? TRUE: FALSE; // 非移动端设备
        } // user_agent_determine

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
         * 计算特定表数据量
         *
         * @params string $table_name 需要计数的表名
         * @params array $conditions 筛选条件
         * @return int/boolean
         */
        protected function count_table($table_name, $conditions = NULL)
        {
            //$params['biz_id'] = 'NULL'; // 默认可获取不属于当前商家的数据
            $params = array();

            // 获取筛选条件
            if ( empty($conditions) ):
                $params['time_delete'] = 'NULL';
            else:
                $params = array_merge($params, $conditions);
            endif;

            // 从API服务器获取相应列表信息
            $url = api_url($table_name. '/count');
            $result = $this->curl->go($url, $params, 'array');
            if ($result['status'] === 200):
                $count = $result['content']['count'];
            else:
                $count = 0; // 若获取失败则返回“0”
            endif;

            return $count;
        } // count_table

        // 将数组输出为key:value格式，主要用于在postman等工具中进行api测试
        protected function key_value($params)
        {
            echo 'app_type:'.$this->app_type ."\n";

            foreach (array_filter($params) as $key => $value):
                echo $key .':' .$value ."\n";
            endforeach;
        } // end key_value

        /**
         * 拆分CSV为数组
         */
        protected function explode_csv($text, $seperator = ',')
        {
            // 清理可能存在的空字符、冗余分隔符
            $text = trim($text);
            $text = trim($text, $seperator);

            // 拆分文本为数组并清理可被转换为布尔型FALSE的数组元素（空数组、空字符、NULL、0、’0‘等）
            $array = array_filter( explode(',', $text) );

            return $array;
        } // end explode_csv
		
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

            return array_unique($ids); // 清除重复项
		} // end parse_ids_array

        /**
         * 将可读日期转为精确到分钟的Unix时间戳
         *
         * @param $time_string 'Y-m-d H:i'或'Y-m-d H:i:s'格式，例如2018-01-01 06:06:06
         * @return string
         */
        protected function strto_minute($time_string)
        {
            if (strlen($time_string) === 16):
                $timestamp = strtotime($time_string. ':00');
            else:
                $timestamp = strtotime(substr($time_string, 0, 16) .':00');
            endif;

            return $timestamp;
        } // end strto_minute

        /**
         * 检查生日字符串格式是否正确
         *
         * @param string $value 生日字符串；Y-m-d格式，例如"1989-07-28"
         * @return boolean
         */
        public function time_dob($value)
        {
            if ( empty($value) ):
                return true;

            elseif (strlen($value) !== 10):
                return false;

            else:
                $eldest_dob = strtotime("- 120 years"); // 120岁
                $youngest_dob = strtotime("- 14 years"); // 14岁

                // 不可超出上述限制
                if ($value < $eldest_dob || $value > $youngest_dob):
                    return false;
                else:
                    return true;
                endif;

            endif;
        } // end time_dob

        /**
         * 删除单行或多行项目
         *
         * 一般用于发货、退款、存为草稿、上架、下架、删除、恢复等状态变化，请根据需要修改方法名，例如deliver、refund、delete、restore、draft等
         */
        public function delete()
        {
            // 检查必要参数是否已传入
            if ( empty($this->input->post_get('ids')))
                redirect( base_url('error/code_400') ); // 若缺少参数，转到错误提示页

            // 操作可能需要检查操作权限
            // $role_allowed = array('管理员', '经理'); // 角色要求
// 			$min_level = 30; // 级别要求
// 			$this->basic->permission_check($role_allowed, $min_level);

            $op_name = '删除'; // 操作的名称
            $op_view = 'delete'; // 操作名、视图文件名

            // 赋值视图中需要用到的待操作项数据
            $ids = $this->parse_ids_array(); // 数组格式，已去掉重复项及空项
            $ids_string = implode(',', $ids); // 字符串格式

            // 页面信息
            $data = array(
                'title' => $op_name. $this->class_name_cn,
                'class' => $this->class_name. ' '. $op_view,
                'error' => '', // 预设错误提示

                'op_name' => $op_view,
                'ids' => $ids_string,
            );

            // 获取待操作项数据
            $params = array('ids' => $ids_string);
            $url = api_url($this->class_name.'/index');
            $data['items'] = $this->curl->go($url, $params, 'array')['content'];

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
                    'user_id' => $this->session->user_id,
                    'ids' => $ids,
                    'password' => $password,
                    'operation' => $op_view, // 操作名称
                );

                // 向API服务器发送待修改数据
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
                    // 若修改失败，则进行提示
                    $data['error'] .= $result['content']['error']['message'];

                    $this->load->view('templates/header', $data);
                    $this->load->view($this->view_root.'/'.$op_view, $data);
                    $this->load->view('templates/footer', $data);
                endif;

            endif;
        } // end delete

        /**
         * 恢复单行或多行项目
         */
        public function restore()
        {
            // 检查必要参数是否已传入
            if ( empty($this->input->post_get('ids')))
                redirect( base_url('error/code_400') ); // 若缺少参数，转到错误提示页

            // 操作可能需要检查操作权限
            // $role_allowed = array('管理员', '经理'); // 角色要求
// 			$min_level = 30; // 级别要求
// 			$this->basic->permission_check($role_allowed, $min_level);

            $op_name = '恢复'; // 操作的名称
            $op_view = 'restore'; // 操作名、视图文件名

            // 赋值视图中需要用到的待操作项数据
            $ids = $this->parse_ids_array(); // 数组格式，已去掉重复项及空项
            $ids_string = implode(',', $ids); // 字符串格式

            // 页面信息
            $data = array(
                'title' => $op_name. $this->class_name_cn,
                'class' => $this->class_name. ' '. $op_view,
                'error' => '', // 预设错误提示

                'op_name' => $op_view,
                'ids' => $ids_string,
            );

            // 获取待操作项数据
            $params = array('ids' => $ids_string);
            $url = api_url($this->class_name.'/index');
            $data['items'] = $this->curl->go($url, $params, 'array')['content'];

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
                    'user_id' => $this->session->user_id,
                    'ids' => $ids,
                    'password' => $password,
                    'operation' => $op_view, // 操作名称
                );

                // 向API服务器发送待修改数据
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
                    // 若修改失败，则进行提示
                    $data['error'] .= $result['content']['error']['message'];

                    $this->load->view('templates/header', $data);
                    $this->load->view($this->view_root.'/'.$op_view, $data);
                    $this->load->view('templates/footer', $data);
                endif;

            endif;
        } // end restore

        // 获取特定商家信息
        protected function get_biz($id)
        {
            // 从API服务器获取相应详情信息
            $params['id'] = $id;

            $url = api_url('biz/detail');
            $result = $this->curl->go($url, $params, 'array');

            return ($result['status'] === 200)? $result['content']: NULL;
        } // end get_biz

        // 获取特定用户信息
        protected function get_user($id)
        {
            $params['id'] = $id;

            // 从API服务器获取相应信息
            $url = api_url('user/detail');
            $result = $this->curl->go($url, $params, 'array');

            return ($result['status'] === 200)? $result['content']: NULL;
        } // end get_user

        // 获取特定员工信息
        protected function get_stuff($id, $allow_deleted = TRUE)
        {
            $params['id'] = $id;

            // 从API服务器获取相应信息
            $url = api_url('user/detail');
            $result = $this->curl->go($url, $params, 'array');
            if ($result['status'] === 200):
                // 若不允许已删除项
                if ($allow_deleted === FALSE && !empty($data['item']['time_delete'])):
                    $data['item'] = NULL;
                else:
                    $data['item'] = $result['content'];
                endif;
            else:
                $data['item'] = NULL;
            endif;

            return $data['item'];
        } // end get_stuff

		// 获取商品列表
		protected function list_item($params = NULL)
		{
            // 默认获取未删除项
            if ( empty($params) ) $params['time_delete'] = 'NULL';

			// 从API服务器获取相应列表信息
			$url = api_url('item/index');
			$result = $this->curl->go($url, $params, 'array');

            return ($result['status'] === 200)? $result['content']: NULL;
		} // end list_item

		// 获取特定商品信息
		protected function get_item($id)
		{
            $params['id'] = $id;

			// 从API服务器获取相应信息
			$url = api_url('item/detail');
			$result = $this->curl->go($url, $params, 'array');

            return ($result['status'] === 200)? $result['content']: NULL;
		} // end get_item

        /**
         * 获取规格列表
         *
         * @param array $params 筛选条件
         * @param string/int $item_id 所属商品ID
         * @return null
         */
		protected function list_sku($params = NULL, $item_id = NULL)
		{
            // 默认获取未删除项
            if ( empty($params) )
                $params['time_delete'] = 'NULL';

            // 限制所属商品ID
		    if ( !empty($item_id) )
		        $params['item_id'] = $item_id;

			// 从API服务器获取相应列表信息
			$url = api_url('sku/index');
			$result = $this->curl->go($url, $params, 'array');

            return ($result['status'] === 200)? $result['content']: NULL;
		} // end list_sku

		// 获取特定规格信息
		protected function get_sku($id)
		{
            $params['id'] = $id;

			// 从API服务器获取相应信息
			$url = api_url('sku/detail');
			$result = $this->curl->go($url, $params, 'array');

            return ($result['status'] === 200)? $result['content']: NULL;
		} // end get_sku
		
		// 获取品牌列表
		protected function list_brand($params = NULL)
		{
            // 默认获取未删除项
            if ( empty($params) )
                $params['time_delete'] = 'NULL';

            // 从API服务器获取相应列表信息
			$url = api_url('brand/index');
			$result = $this->curl->go($url, $params, 'array');

            return ($result['status'] === 200)? $result['content']: NULL;
		} // end list_brand
		
		// 获取特定品牌信息
		protected function get_brand($id)
		{
            $params['id'] = $id;

			// 从API服务器获取相应信息
			$url = api_url('brand/detail');
			$result = $this->curl->go($url, $params, 'array');

            return ($result['status'] === 200)? $result['content']: NULL;
		} // end get_brand

		// 获取系统分类列表
		protected function list_category($params = NULL, $level = 1)
		{
            // 默认获取未删除项
		    if ( empty($params) )
		        $params['time_delete'] = 'NULL';

            // 限制分类级别
		    $params['level'] = $level;

			// 从API服务器获取相应列表信息
			$url = api_url('item_category/index');
			$result = $this->curl->go($url, $params, 'array');

            return ($result['status'] === 200)? $result['content']: NULL;
		} // end list_category
		
		// 获取特定系统分类信息
		protected function get_category($id)
		{
            $params['id'] = $id;

			// 从API服务器获取相应信息
			$url = api_url('item_category/detail');
			$result = $this->curl->go($url, $params, 'array');

            return ($result['status'] === 200)? $result['content']: NULL;
		} // end get_category
		
		// 获取商家分类列表
		protected function list_category_biz($params = NULL)
		{
            // 默认获取未删除项
            if ( empty($params) )
                $params['time_delete'] = 'NULL';

			// 从API服务器获取相应列表信息
			$url = api_url('item_category_biz/index');
			$result = $this->curl->go($url, $params, 'array');

            return ($result['status'] === 200)? $result['content']: NULL;
		} // end list_category_biz
		
		// 获取特定商家分类信息
		protected function get_category_biz($id)
		{
            $params['id'] = $id;

			// 从API服务器获取相应信息
			$url = api_url('item_category_biz/detail');
			$result = $this->curl->go($url, $params, 'array');

            return ($result['status'] === 200)? $result['content']: NULL;
		} // end get_category_biz

        // 获取优惠券模板列表
        protected function list_coupon_template($params = NULL)
        {
            // 默认获取未删除项
            if ( empty($params) )
                $params['time_delete'] = 'NULL';

            // 从API服务器获取相应列表信息
            $url = api_url('coupon_template/index');
            $result = $this->curl->go($url, $params, 'array');

            return ($result['status'] === 200)? $result['content']: NULL;
        } // end list_coupon_template

        // 获取特定优惠券模板信息
        protected function get_coupon_template($id)
        {
            $params['id'] = $id;

            // 从API服务器获取相应信息
            $url = api_url('coupon_template/detail');
            $result = $this->curl->go($url, $params, 'array');

            return ($result['status'] === 200)? $result['content']: NULL;
        } // end get_coupon_template

		// 获取店内活动列表
		protected function list_promotion_biz($params = NULL)
		{
            // 默认获取未删除项
            if ( empty($params) )
                $params['time_delete'] = 'NULL';

            // 从API服务器获取相应列表信息
			$url = api_url('promotion_biz/index');
			$result = $this->curl->go($url, $params, 'array');

            return ($result['status'] === 200)? $result['content']: NULL;
		} // end list_promotion_biz
		
		// 获取店内活动详情
		protected function get_promotion_biz($id)
		{
            $params['id'] = $id;

			// 从API服务器获取相应信息
			$url = api_url('promotion_biz/detail');
			$result = $this->curl->go($url, $params, 'array');

            return ($result['status'] === 200)? $result['content']: NULL;
		} // end get_promotion_biz
		
		// 获取商家运费模板
		protected function list_freight_template_biz($params = NULL)
		{
            // 默认获取未删除项
            if ( empty($params) )
                $params['time_delete'] = 'NULL';

            // 从API服务器获取相应列表信息
			$url = api_url('freight_template_biz/index');
			$result = $this->curl->go($url, $params, 'array');

            return ($result['status'] === 200)? $result['content']: NULL;
		} // end list_freight_template_biz

		// 获取特定商家运费模板详情
		protected function get_freight_template_biz($id)
		{
            $params['id'] = $id;

			// 从API服务器获取相应信息
			$url = api_url('freight_template_biz/detail');
			$result = $this->curl->go($url, $params, 'array');

            return ($result['status'] === 200)? $result['content']: NULL;
		} // end get_freight_template_biz

	} // end class MY_Controller
	
/* End of file MY_Controller.php */
/* Location: ./application/controllers/MY_Controller.php */