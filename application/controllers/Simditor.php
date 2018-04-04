<?php
	defined('BASEPATH') OR exit('此文件不可被直接访问');
	
	// 载入又拍云相关类
	require_once './sdk/upyun/vendor/autoload.php';

	// 填写又拍云类的基础配置
	use Upyun\Upyun;
	use Upyun\Config;

	/**
	 * Simditor 类
	 *
	 * 处理Simditor富文本编辑器文件上传
	 *
	 * @version 1.0.0
	 * @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	 * @copyright ICBG <www.bingshankeji.com>
	 */
	class Simditor extends CI_Controller
	{
		// 上传目标一级文件夹名，例如user、item、branch等
		public $top_directory;
		
		// 上传目标文件夹名
		public $target_directory;

		// 上传目标路径，即含有处理上传的服务器本地路径的URL，例如"uploads/..."
		public $target_url;

		// 可访问该文件的路径，即忽略服务器本地路径的文件URL
		public $path_to_file;

		// 初始化总体上传结果，默认上传成功
		public $result = array(
			'status' => 200,
            'content' => NULL,
		);

		// 构造函数
		public function __construct()
		{
			parent::__construct();

			// 仅接受AJAX请求
			($this->input->is_ajax_request() === TRUE) OR (redirect( base_url('error/code_404') ));

			// 获取并设置类属性信息
			$dir_until = strpos($this->input->post_get('target'), '/'); // 获取一级目录名结束位置（含斜杠）
			$this->top_directory = '/'.substr( $this->input->post_get('target'), 0, $dir_until ).'/';
			$this->path_to_file = $this->input->post_get('target').'/'. date('Ym').'/'. date('md').'/'; // 按上传时间进行分组，最小分组单位为分
			$this->target_directory = 'uploads/'. $this->path_to_file;
			$this->editor_name = $this->input->post_get('editor_name');

			// 检查目标路径是否存在
			if ( ! file_exists($this->target_directory) )
				mkdir($this->target_directory, 0777, TRUE); // 若不存在则新建，且允许新建多级子目录

			// 设置目标路径
			chmod($this->target_directory, 0777); // 设置权限为可写
			$this->target_url = $_SERVER['DOCUMENT_ROOT']. '/'. $this->target_directory;
		} // end __construct

		/**
		 * 析构时将待输出的内容以json格式返回
		 * 截止3.1.3为止，CI_Controller类无析构函数，所以无需继承相应方法
		 */
		public function __destruct()
		{
			// 将请求参数一并返回以便调试
			if ($this->input->post_get('test_mode') === 'on'):
                $this->result['param']['get'] = $this->input->get();
                $this->result['param']['post'] = $this->input->post();
            endif;

			header("Content-type:application/json;charset=utf-8");
			echo json_encode($this->result);
		} // end __destruct

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

						// 处理上传结果
						if ( $upload_result['status'] === 400 ):
							// 若存在上传失败的文件，在总体结果中进行体现
                            $this->result['status'] = 400;
                            $this->result['success'] = FALSE;
                            $this->result['msg'] = $upload_result['content']['error']['message'];
                            $this->result['file_path'] = '';

						else:
							// 若上传成功，处理冗余的文件目录名
							$dir_until = strpos($upload_result['content'], '/') + 1; // 获取一级目录名结束位置（含斜杠）

                            $this->result['status'] = 200;
                            $this->result['success'] = TRUE;
                            $this->result['msg'] = '上传成功';
                            $this->result['file_path'] = substr($upload_result['content'], $dir_until); // 去掉一级目录名的相对路径

						endif;

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
						$this->result['success'] = FALSE;
						$this->result['msg'] = $content;
                        $this->result['file_path'] = '';

					endif;

				endfor;

			// 若没有文件被上传，返回相应提示
			else:
                $this->result['status'] = 400;
                $this->result['success'] = FALSE;
                $this->result['msg'] = '没有文件被上传';
                $this->result['file_path'] = '';

			endif;
		} // end index

		// 上传具体文件
		private function upload_process($field_index)
		{
			// 设置上传限制
			$config['upload_path'] = $this->target_url;
			$config['file_name'] = date('His');
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

				// 上传到CDN
				@$this->upload_to_cdn();

			else:
				$data['status'] = 400;
				$data['content']['file'] = $_FILES[$field_index]; // 返回源文件信息
				$data['content']['error']['message'] = $this->upload->display_errors('',''); // 返回纯文本格式的错误说明
			endif;

			return $data;
		} // end upload_process

		// 上传到CDN；目前采用的是又拍云
		private function upload_to_cdn()
		{
			$upyun_config = new Config('jinlaisandbox-images', 'jinlaisandbox', 'jinlaisandbox');
			$upyun = new Upyun($upyun_config);
 
			// 待上传到的又拍云URL
			$target_path =  $this->path_to_file. $this->upload->data('file_name');
			//echo $target_path;

			// 待上传文件的本地相对路径 注意，只能是相对路径！！！
			$source_file_url = './uploads/'.$this->path_to_file. $this->upload->data('file_name');
			//echo $source_file_url;

			// 获取待上传文件
			$file = fopen($source_file_url, 'rb'); // 打开文件流

			// 添加作图参数
			// 最长边2048px，短边自适应
			$tasks = array('x-gmkerl-thumb' => '/max/2048');

			// 进行上传
			$upyun->write($target_path, $file, $tasks);
			fclose($file); // 关闭文件流
		} // end upload_to_cdn

		// TODO 预处理照片
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
		} // end prepare_image

	} // end Class Simditor

/* End of file Simditor.php */
/* Location: ./application/controllers/Simditor.php */