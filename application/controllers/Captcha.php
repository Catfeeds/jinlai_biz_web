<?php
	defined('BASEPATH') OR exit('此文件不可被直接访问');

	/**
	 * Captcha 类
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

		// 生成验证码图片
		public function index($length = 4, $width = 100, $height = 38)
		{
			// 生成随机数字字符串
		    $code = '';
		    for ($i = 0; $i < $length; $i++)
			{
		        $code .= rand(0, 9); 
		    }

			// 将验证码信息存入SESSION
			$time_expire = time() + 60*5; // 5分钟有效
			$this->session->captcha = $code;
			$this->session->captcha_time_expire = $time_expire;

		    // 创建图片，定义颜色值 
		    header("Content-type:image/PNG");
		    $im = imagecreate($width, $height); // 图片尺寸
		    $frame_color = imagecolorallocate($im, 0, 0, 0); // 边框色
			$background_color = imagecolorallocate($im, 196, 0, 1); // 背景色
		    $font_color = imagecolorallocate($im, 255, 255, 255); // 字体色

		    // 填充背景 
		    imagefill($im, 0, 0, $background_color);

		    // 画边框
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
			{
		        imagesetpixel($im, rand(0, $width), rand(0, $height), $frame_color);
		    }

		    // 将数字随机显示在画布上,字符的水平间距和位置都按一定波动范围随机生成
		    $strx = rand(5, 15);
		    for ($i = 0; $i < $length; $i++)
			{
		        $strpos = rand(5, 15);
				$font_style = rand(1, 5); // 字号
		        imagestring($im, $font_style, $strx, $strpos, substr($code, $i, 1), $font_color);
		        $strx += rand(15, 25);
		    } 
		    imagepng($im); // 输出图片
		    imagedestroy($im); // 释放图片所占内存
		}
	}
	
/* End of file Captcha.php */
/* Location: ./application/controllers/Captcha.php */