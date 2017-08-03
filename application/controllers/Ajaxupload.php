<?php
	defined('BASEPATH') OR exit('此文件不可被直接访问');

	/**
	 * Ajaxupload类
	 *
	 * 处理AJAX文件上传
	 *
	 * @version 1.0.0
	 * @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	 * @copyright ICBG <www.bingshankeji.com>
	 */
	class Ajaxupload extends CI_Controller
	{
		// 上传目标文件夹名
		public $target_directory;

		// 上传目标路径，即含有处理上传的服务器本地路径的URL，例如"uploads/..."
		public $target_url;

		// 可访问该文件的路径，即忽略服务器本地路径的文件URL
		public $path_to_file;

		// 初始化总体上传结果，默认上传成功
		public $result = array(
			'status' => 200,
		);

		// 构造函数
		public function __construct()
		{
			parent::__construct();

			// 统计业务逻辑运行时间起点
			$this->benchmark->mark('start');

			// 仅接受AJAX请求
			($this->input->is_ajax_request() === TRUE) OR (redirect( base_url('error/code_404') ));

			// 获取并设置可访问路径、上传目标路径
			$this->path_to_file = $this->input->post_get('target').'/'. date('Ym').'/'. date('md').'/'. date('Hi').'/'; // 按上传时间进行分组，最小分组单位为分
			$this->target_directory = 'uploads/'. $this->path_to_file;

			// 检查目标路径是否存在
			if ( ! file_exists($this->target_directory) )
				mkdir($this->target_directory, 0777, TRUE); // 若不存在则新建，且允许新建多级子目录

			// 设置目标路径
			chmod($this->target_directory, 0777); // 设置权限为可写
			$this->target_url = $_SERVER['DOCUMENT_ROOT']. '/'. $this->target_directory;
		}

		/**
		 * 析构时将待输出的内容以json格式返回
		 * 截止3.1.3为止，CI_Controller类无析构函数，所以无需继承相应方法
		 */
		public function __destruct()
		{
			// 将请求参数一并返回以便调试
			$this->result['param']['get'] = $this->input->get();
			$this->result['param']['post'] = $this->input->post();

			// 统计业务逻辑运行时间终点
			$this->benchmark->mark('end');
			// 计算并输出业务逻辑运行时间
			$this->result['elapsed_time'] = $this->benchmark->elapsed_time('start', 'end'). ' s';

			header("Content-type:application/json;charset=utf-8");
			$output_json = json_encode($this->result);
			echo $output_json;
		}

		// 上传入口
		public function index()
		{
			// 若有文件被上传，继续处理文件
			if ( !empty($_FILES) ):

				// 获取待处理文件总数
				$file_count = count($_FILES);

				// 依次处理文件
				for ($i=0; $i<$file_count; $i++):
					// 获取待处理文件
					$file_index = 'file'. $i;
					$file = $_FILES[$file_index];

					// 若获取成功，继续处理文件
					if ($file['error'] === 0):
						// 处理上传
						$upload_result = $this->upload_process($file_index);

						// 储存上传结果
						// 若存在上传失败的文件，在总体结果中进行体现
						if ( $upload_result['status'] === 400 ):
							$this->result['status'] = 400;
							$this->result['content']['error']['message'] = $upload_result['content']['error']['message'];
						endif;
						$this->result['content']['items'][] = $upload_result;

					// 若获取失败，判断失败原因，并返回相应提示
					else:
						switch( $file['error'] ):
							case 1:
								$content = '文件大小超出系统限制'; // 文件大小超出了PHP配置文件中 upload_max_filesize 的值
								break;
							case 2:
								$content = '文件大小超出页面限制'; // 文件大小超出了HTML表单中 MAX_FILE_SIZE 的值（若有）
								break;
							case 3:
								$content = '网络传输失败，请重试或切换联网方式'; // 文件只有部分被上传
								break;
							case 4:
								$content = '没有文件被上传';
								break;
							default:
								$content = '上传失败';
						endswitch;
						$this->result['status'] = 400;
						$this->result['content']['error']['message'] = $content;

					endif;

				endfor;

			// 若没有文件被上传，返回相应提示
			else:
				$content = '没有文件被上传';
				$this->result['status'] = 400;
				$this->result['content']['error']['message'] = $content;

			endif;
		}

		// 上传具体文件
		private function upload_process($field_index)
		{
			// 设置上传限制
			$config['upload_path'] = $this->target_url;
			$config['file_name'] = date('Ymd_His');
			$config['file_ext_tolower'] = TRUE; // 文件名后缀转换为小写
			$config['allowed_types'] = 'webp|jpg|jpeg|png';
			$config['max_width'] = 4096; // 图片宽度不得超过4096px
			$config['max_height'] = 4096; // 图片高度不得超过4096px
			$config['max_size'] = 4096; // 文件大小不得超过4M

			//TODO 预处理图片

			// 载入CodeIgniter的上传库并尝试上传文件
			// https://www.codeigniter.com/user_guide/libraries/file_uploading.html
			$this->load->library('upload', $config);
			$result = $this->upload->do_upload($field_index);

			if ($result === TRUE):
				$data['status'] = 200;
				$data['content'] = $this->path_to_file. $this->upload->data('file_name'); // 返回上传后的文件路径
			else:
				$data['status'] = 400;
				$data['content']['file'] = $_FILES[$field_index]; // 返回源文件信息
				$data['content']['error']['message'] = $this->upload->display_errors('',''); // 返回纯文本格式的错误说明
			endif;

			return $data;
		}
		
		private function prepare_image()
		{
			// 等比例缩放到最长边小于等于2048px
			$config['image_library'] = 'gd2';
			$config['source_image'] = $field_index;
			$config['maintain_ratio'] = TRUE;
			$config['width']         = 2048;
			$config['height']       = 2048;

			// 载入CodeIgniter的上传库并尝试处理文件
			$this->load->library('image_lib', $config);
			if ( ! $this->image_lib->resize() ):
				echo $this->image_lib->display_errors();

			else:
				return TRUE;

			endif;
		}
	}