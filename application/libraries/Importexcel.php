<?php	
	defined('BASEPATH') OR exit('此文件不可被直接访问');

	/**
     *
     * Importexcel
	 * 处理excel文件
	 *
	 * @version 1.0.0
	 * @author huangxin 2018-6-26 15:15:39
	 */
	class Importexcel
	{
        // 原始CodeIgniter对象
        private $CI;

	 	public $_headline; // 标题/表头行号
	 	public $_dataline; // 数据起始行号
	 	public $_file; // 待导入文件URL
	 	protected $_phpexcel; // PHPExcel对象
	 	public $env;

        // 构造函数
        public function __construct()
        {
            // 引用原始CodeIgniter对象
            $this->CI =& get_instance();
        } // end __construct

        /**
         * 导入文件中数据
         *
         * @param string $filepath 待导入文件路径
         * @param string/csv $names_to_import 待导入字段名，CSV
         * @param int $headline 标题行行号
         * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
         */
        public function import($filepath, $names_to_import, $headline = 1)
        {
            $this->_file = $filepath;

            if ( ! file_exists($this->_file)):
                $this->CI->result['status'] = 411;
                $this->CI->result['content']['error']['message'] = '文件不存在';
            endif;
            $this->_headline = $headline;
            $this->_dataline = $headline + 1;

            // 初始化PHPExcel读取方法
            //vendor('PHPExcel.PHPExcel');
            $phpexcel = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
            // 加载文件到PHPExcel类
            $this->_phpexcel = $phpexcel->load($this->_file);

            // 获取表头
            $names_to_import = array_filter( explode(',', $names_to_import) );
            //var_dump($names_to_import);
            $names_in_file = $this->getfilehead();
            //var_dump($names_in_file);

            // 轮流读取文件中每一数据行到待处理数据集，直到空行为止
            $items = array();
            $row_to_fetch = $this->_dataline; // 待读取行号；初始化为数据行首行
            $one_more = TRUE;
            do
            {
                // 获取当前行数据
                $item = $this->getaline(count($names_to_import), $this->_dataline);

                if (empty( implode('',$item) )):
                    $one_more = FALSE;
                else:
                    // 添加当前行到数据集，并标识下一行行号
                    $items[] = $item;
                    $this->_dataline++;
                endif;
            }
            while ($one_more);
            //var_dump($items);

            // 按照$names_to_import的顺序，获取相应字段名所在列的值，并调用相应API
            $rows_created = 0; // 导入成功的总行数
            $rows_failed = array(); // 导入失败的行号
            $url = api_url($this->CI->class_name. '/create');
            for ($i=0;$i<count($items);$i++):
                $item = $items[$i];

                // 待创建的数据行
                $data_to_create = array();

                foreach ($names_to_import as $name):
                    $name_index = array_search($name, $names_in_file);
                    $data_to_create[$name] = $item[$name_index];
                endforeach;

                // 拼合部分必要字段值
                $params = array_filter($data_to_create);
                $params['user_id'] = $this->CI->user_id;
                $params['url_image_main'] = DEFAULT_IMAGE;
                //var_dump($params);
                //$this->CI->key_value($params);
                $result = $this->CI->curl->go($url, $params, 'array');
                //var_dump($result);
                if ($result['status'] === 200):
                    $rows_created ++;
                else:
                    $rows_failed[] = $i;
                endif;

            endfor;

            if ( ! empty($rows_failed)):
                $rows_failed = implode(',', $rows_failed);
                $this->CI->result['content']['error']['message'] = '第 '. $rows_failed. ' 行导入失败';
            endif;

            $this->CI->result['content']['message'] = '成功导入 '. $rows_created. ' 行';
        } // end import

		/**
		 * 读取EXCEL表头
		 *
		 */
		private function getfilehead()
        {
			$loop   = true;
			$values = [];
			$next   = 1;
			while ($loop)
            {
				$temp = $this->_phpexcel->getActiveSheet()->getCellByColumnAndRow($next++, $this->_headline)->getValue();
				if (is_null($temp)) :
					$loop = false;
				else :
					$values[] = $temp;	
				endif;
			}
			return $values;
		} // end getfilehead

        /**
         * 读取一行数据
         *
         * @param int $length 需获取的列数
         * @param int $start 起始行号
         * @return array
         */
		private function getaline($length, $start)
        {
			$values = [];
			for($s = 1; $s <= $length; $s++):
	            $values[] = $this->_phpexcel->getActiveSheet()->getCellByColumnAndRow($s, $start)->getValue();
			endfor;

			return $values;
		} // end getaline

	} // end class Importexcel

/* End of file Importexcel.php */
/* Location: ./application/libraries/Importexcel.php */