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
	class Excel
	{
        // 原始CodeIgniter对象
        private $CI;

	 	public $_headline; // 标题/表头行号
	 	public $_dataline; // 数据起始行号
	 	public $_file; // 待导入文件URL
	 	protected $_phpexcel; // PHPExcel对象

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

            //验证字段
            $res = $this->validrow($names_to_import, $names_in_file);
            if ($res != TRUE) :
                $this->CI->result['status'] = 411;
                $this->CI->result['content']['error']['message'] = '缺少' . $res . '字段';
            endif;

            // 轮流读取文件中每一数据行到待处理数据集，直到空行为止
            $items = array();
            $row_to_fetch = $this->_dataline; // 待读取行号；初始化为数据行首行
            $one_more = TRUE;
            $names_count_in_file = count($names_in_file); //之前已经检测过file，所以用它
            do
            {   
                // 获取当前行数据
                $item = $this->getaline($names_count_in_file, $this->_dataline);

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

                // * 这里 表明要导入的字段一定要在文件中出现
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

                if ($result['status'] === 200):
                    $rows_created ++;
                else:
                    $rows_failed[] = $i + 1;
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

        /**
        *
        * 把数据导出excel
        * @param data 要导出的数据 [{key:value}, ...]
        * @param description 内容说明
        * @param output:   'download' 直接输出到浏览器，并终止其它操作 ; 'file'生成文件保存到Public目录
        * $this->user_id = $this->session->user_id;
        * $this->load->library('Excel');
        * $this->excel->export($result['content'], '商家商品导出', 'download');
        */
        public function export($data, $description, $output = 'download'){
            if (empty($data)) :
                $this->CI->result['status'] = 411;
                $this->CI->result['content']['error']['message'] = '数据为空';
            endif;

            $outputMap = ['download', 'file'];
            if (!in_array($output, $outputMap)) :
                $this->CI->result['status'] = 411;
                $this->CI->result['content']['error']['message'] = '请确定文件输出位置';
            endif;

            //创建一个spreadsheet
            $this->_phpexcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $this->_phpexcel->getProperties()
                        ->setCreator("userid:" . (isset($this->CI->user_id) ? $this->CI->user_id : 'unknown'))
                        ->setSubject("数据导出")
                        ->setDescription(date('Y-m-d H:i:s'));
            $this->_phpexcel->getActiveSheet()->setTitle(empty($description) ? $this->CI->class_name . '文件导出' : $description);

            //写入字段
            $indexRow = 1;
            $this->writedata(array_keys(current($data)), $indexRow);
            
            //写入数据
            foreach ($data as $index => $item) :
                $indexRow++;
                $this->writedata($item, $indexRow);
            endforeach;

            //文件名
            $filename = $this->CI->user_id . '-' . $this->CI->class_name . '-' . date('Ymd_his') . '.xlsx' ;
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($this->_phpexcel);

            if ($output == 'download') :
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename=' . $filename);
                header('Cache-Control: max-age=0');
                $writer->save('php://output');
                exit;
            else :
                if (!is_dir('./public')){
                    $this->CI->result['status'] = 411;
                    $this->CI->result['content']['error']['message'] = 'public目录不存在';
                    return FALSE;
                }

                $filepath = './public/' . $filename; //项目下没有此目录
                $writer->save($filepath);
                //处理结果
                if (file_exists($filepath)) :
                    $this->CI->result['status'] = 200;
                    $this->CI->result['content']['message'] = $filepath;
                    return TRUE;
                else :
                    $this->CI->result['status'] = 411;
                    $this->CI->result['content']['error']['message'] = '生成excel文件失败';
                    return FALSE;
                endif;
            endif;
            return TRUE;
        } // end export


        //写入一行excel文件
        private function writedata($row, $line = 1)
        {   
            $column = 1;
            //暂时默认为字符串格式
            $typeString = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING;
            foreach ($row as $key => $value) :
                if (is_array($value))
                    continue;

                if (strpos( '-' . $key, 'time') && is_numeric($value))
                    $value = date('Y-m-d H:i:s', $value);
        
                $this->_phpexcel->getActiveSheet()->setCellValueByColumnAndRow($column++, $line, $value, $typeString);
            endforeach;
        } //end writedata

      /**
        * 
        * 查看表中字段是否合法
        *
        */
        private function validrow($table, $file){
            foreach ($table as $key => $value) {
                if (!in_array($value, $file)) {
                    return $value;
                }
            }
            return TRUE;
        }


        /**
         * 转换对应数据
         *
         * @param array original [{key:value,},] 外部数据,默认为多条形式
         * @param array relation {newkey:originalkey,}字段对应关系 没有对应关系的写成''即可
         * @return array outputdata
         * 测试代码
         $this->load->library('excel');
            $original_data = [
                ['price'=>10,'name'=>'大苹果','store'=>'167','sold'=>'10','pic'=>'kxdjfsd.jpg','grouppart'=>1],
                ['price'=>20,'name'=>'榴莲','store'=>'10','sold'=>'0','pic'=>'kxdjfsd.jpg','grouppart'=>1],
                ['price'=>11,'name'=>'香蕉','store'=>'582','sold'=>'25','pic'=>'kxdjfsd.jpg','grouppart'=>1],
                ['price'=>18,'name'=>'木瓜','store'=>'1565','sold'=>'156','pic'=>'kxdjfsd.jpg','grouppart'=>1],
                ['price'=>455,'name'=>'西瓜','store'=>'156','sold'=>'54','pic'=>'kxdjfsd.jpg','grouppart'=>1]
            ];
            $relation = ['current_price'=>'price','tag_price'=>'','stocks'=>'store','sold_monthly'=>'sold','url_image_main'=>'pic','comment_num'=>'','sold_name'=>'name'];
            $r = $this->excel->convert($original_data, $relation);
            var_dump($r);
            exit;
         */
        public function convert($original, $relation)
        {   
            if (count($relation) == 0) :
                $this->CI->result['status'] = 411;
                $this->CI->result['content']['error']['message'] = '对应关系不能为空';
                return FALSE;
            endif;
            
            if (count($original) == 0) :
                $this->CI->result['status'] = 411;
                $this->CI->result['content']['error']['message'] = '处理的数据不能为空';
                return FALSE;
            endif;

            if (!is_array(current($original)))
                $original = [$original];
            
            //处理的结果
            $output_data = [];

            //我们的数据中，没有对应关系的键
            $without_relation = [];
            //有对应关系的键
            $with_relation = [];

            foreach ($relation as $newkey => $value) {
                if (empty($value)) :
                    $without_relation[$newkey] = null;
                else :
                    $with_relation[$newkey] = $value;
                endif;
            }

            foreach ($original as $key => $data) :
                //把没有对应关系的先放上
                $convert_arr = $without_relation; 

                foreach ($with_relation as $newkey => $originkey) :
                    //保存有对应关系的数据
                    $convert_arr[$newkey] = $data[$originkey];
                endforeach;

                $output_data[] = $convert_arr;
            endforeach;

            return $output_data;
        }

	} // end class Importexcel

/* End of file Importexcel.php */
/* Location: ./application/libraries/Importexcel.php */