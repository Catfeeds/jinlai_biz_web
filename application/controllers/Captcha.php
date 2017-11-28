<?php
	defined('BASEPATH') OR exit('此文件不可被直接访问');

	/**
	 * Captcha 图片验证码类
	 *
	 * @version 1.0.0
	 * @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	 * @copyright ICBG <www.bingshankeji.com>
	 */
	class Captcha extends CI_Controller
	{
		/* 类名称小写，应用于多处动态生成内容 */
		public $class_name;

		/* 类名称中文，应用于多处动态生成内容 */
		public $class_name_cn;

		/* 主要相关表名 */
		public $table_name;

		/* 主要相关表的主键名*/
		public $id_name;

		public function __construct()
		{
			parent::__construct();

			// 向类属性赋值
			$this->class_name = strtolower(__CLASS__);
			$this->class_name_cn = '图片验证码'; // 改这里……
			$this->table_name = 'captcha'; // 和这里……
			$this->id_name = 'captcha_id';  // 还有这里，OK，这就可以了

			// 初始化模型
			$this->basic_model->table_name = $this->table_name;
			$this->basic_model->id_name = $this->id_name;
		}

		// 输出验证码图片
		public function __destruct()
        {
            header("Content-type:image/PNG");
            imagepng($this->im);
            imagedestroy($this->im); // 释放图片所占内存
        }

        /**
         * 使用系统默认字体创建图片验证码图片
         *
         * @param int $length 验证码字符数
         */
		public function index_old($length = 4)
		{
            // 获取验证码图片宽度、高度
            $width = !empty($this->input->get_post('width'))? $this->input->get_post('width'): 100;
            $height = !empty($this->input->get_post('height'))? $this->input->get_post('height'): 38;

			// 生成随机数字字符串
		    $code = '';
		    for ($i = 0; $i < $length; $i++) $code .= rand(0, 9);

			// 将验证码信息存入SESSION
			$time_expire = time() + 60*5; // 5分钟内有效
			$this->session->captcha = $code;
			$this->session->captcha_time_expire = $time_expire;

		    // 创建图片，定义颜色值
		    $im = imagecreate($width, $height); // 图片尺寸
		    $frame_color = imagecolorallocate($im, 0, 0, 0); // 边框色
			$background_color = imagecolorallocate($im, 255, 255, 255); // 背景色

		    // 填充背景 
		    imagefill($im, 0, 0, $background_color);
		    // 绘制边框
		    imagerectangle($im, 0, 0, $width-1, $height-1, $frame_color);

		    // 随机绘制两条虚线，起干扰作用
		    $style = array ($frame_color,$frame_color,$frame_color,$frame_color,$frame_color,$background_color,$background_color,$background_color,$background_color,$background_color 
		    );
		    imagesetstyle($im, $style);
		    $y1 = rand(0, $height);
		    $y2 = rand(0, $height);
		    $y3 = rand(0, $height);
		    $y4 = rand(0, $height);
		    imageline($im, 0, $y1, $width, $y3, IMG_COLOR_STYLED);
		    imageline($im, 0, $y2, $width, $y4, IMG_COLOR_STYLED);

            // 在画布上随机生成大量黑点，起干扰作用;
            for ($i = 0; $i < 60; $i++)
                imagesetpixel($im, rand(0, $width), rand(0, $height), $frame_color);

		    // 将数字随机显示在画布上,字符的水平间距和位置都按一定波动范围随机生成
		    $strx = rand(5, 15);
		    for ($i = 0; $i < $length; $i++):
		        $strpos = rand(5, 15);
				$font_style = rand(1, 5); // 字号
                $font_color = imagecolorallocate($im, rand(0, 180), rand(0, 180), rand(0, 180)); // 字体色
		        imagestring($im, $font_style, $strx, $strpos, substr($code, $i, 1), $font_color);

		        // 下一个字符的x起始位置
		        $strx += rand(15, 25);
		    endfor;

            // 输出图片
            $this->im = &$im;
		} // end index_old

        /**
         * 使用自定义字体创建图片验证码图片
         * 需要先将要使用的字体的文件部署好
         *
         * @param int $length 验证码字符数
         */
        public function index($length = 4)
        {
            // 获取验证码图片宽度、高度
            $width = !empty($this->input->get_post('width'))? $this->input->get_post('width'): 100;
            $height = !empty($this->input->get_post('height'))? $this->input->get_post('height'): 38;

            // 生成随机数字字符串
            $code = '';
            for ($i = 0; $i < $length; $i++) $code .= rand(0, 9);

            // 将验证码信息存入SESSION
            $time_expire = time() + 60*5; // 5分钟内有效
            $this->session->captcha = $code;
            $this->session->captcha_time_expire = $time_expire;

            // 创建图片，定义颜色值
            $im = imagecreatetruecolor($width, $height);
            $frame_color = imagecolorallocate($im, 0, 0, 0); // 边框色
            $background_color = imagecolorallocate($im, 255, 255, 255); // 背景色
            //$font_color = imagecolorallocate($im, 255, 255, 255); // 字体色

            // 填充背景
            imagefill($im, 0, 0, $background_color);
            // 绘制边框
            imagerectangle($im, 0, 0, $width-1, $height-1, $frame_color);

            // 随机绘制两条虚线，起干扰作用
            $style = array ($frame_color,$frame_color,$frame_color,$frame_color,$frame_color,$background_color,$background_color,$background_color,$background_color,$background_color
            );
            imagesetstyle($im, $style);
            $y1 = rand(0, $height);
            $y2 = rand(0, $height);
            $y3 = rand(0, $height);
            $y4 = rand(0, $height);
            imageline($im, 0, $y1, $width, $y3, IMG_COLOR_STYLED);
            imageline($im, 0, $y2, $width, $y4, IMG_COLOR_STYLED);

            // 在画布上随机生成大量黑点，起干扰作用;
            for ($i = 0; $i < 60; $i++)
                imagesetpixel($im, rand(0, $width), rand(0, $height), $frame_color);

            // 将数字随机显示在画布上,字符的水平间距和位置都按一定波动范围随机生成
            $strx = rand(2, 4);
            for ($i = 0; $i < $length; $i++):
                $font_size = rand(14, 28);
                $font_angle = rand(-40, 40); // 字体倾斜角度
                $x = $strx;
                $y = rand($font_size, $height);
                $font_color = imagecolorallocate($im, rand(0, 180), rand(0, 180), rand(0, 180)); // 字体色
                $font_file = FCPATH.'font/Verdana.ttf';
                imagefttext($im, $font_size, $font_angle, $x, $y, $font_color, $font_file, substr($code, $i, 1));

                // 下一个字符的x起始位置
                $strx += rand($font_size+2, $font_size+4);
            endfor;

            // 输出图片
            $this->im = &$im;
        } // end index

	} // end Class Captcha

/* End of file Captcha.php */
/* Location: ./application/controllers/Captcha.php */