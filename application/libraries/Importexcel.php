<?php	
	defined('BASEPATH') OR exit('此文件不可被直接访问');

	/**
	* 储存excel文件
	*
	* @version 1.0.0
	* @author huangxin 2018-6-26 15:15:39

	*/
	class Importexcel
	{

	 	public $_headline;
	 	public $_dataline;
	 	public $_file;
	 	protected $_phpexcel;
	 	public $env;
	 	public $CI;
	 	/**
		 * @param $filepath 要读取的文件路径
		 * @param $classname 要存储的文件表名
		 * @param $headline 文件中 字段的行号
		 * @param $dataline 文件在 数据的行号
		 */
		public function config($filepath, $classname, $headline = 1, $dataline = 2){
			$this->CI = &get_instance();
			$this->CI->load->model('Basic_model');
			$this->env = ['development' => 'https://api.517ybang.com/', 'production' => 'https://www.ybslux.com/'];
			$this->_file =  $filepath;
			//设置表名、主键
        	$this->CI->basic_model->table_name = $classname;
        	$this->CI->basic_model->id_name    = $classname . '_id';
        	
        	//设置行号
			$this->_headline = $headline;
			$this->_dataline = $dataline;

			//创建phpexcel实例
        	$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        	//加载文件
			$this->_phpexcel = $reader->load($this->_file);
		}
		/**
		 * 
		 * 开始处理
		 */
		public function run(){
			//返回信息
			$json = ['status'=>1, 'msg'=>'', 'error'=>'', 'res'=>''];
			// 检查文件
			if (!file_exists($this->_file)) {
				$json['msg'] = '文件不存在';
            	return $json;
			}
			//验证表信息
        	$tablerows = $this->getfield();
        	if ($tablerows === false) {
        		$json['msg'] = '该表/api不存在';
            	return $json;
        	}

			//读取上传文件的字段
			$filerow = $this->getfilehead();

			//验证字段
			$res = $this->validrow($tablerows, $filerow);
			if ($res != true) {
				$json['msg'] = '文件中有不允许上传的字段:' . $res;
            	return $json;
			}

			//开始读取数据
			$data = [];
			$keysnum = count($filerow);
			while ($row = $this->getaline($keysnum, $this->_dataline)) {
				$data[] = $row;
				$this->_dataline++;
			}
			if (empty($data)) {
				$json['msg'] = '没有数据';
            	return $json;
			}

			$res = [];
			$curllink  = $this->env[ENVIRONMENT] . $this->CI->basic_model->table_name . '/create';
			$this->CI->load->library('curl');
			foreach ($data as $key => $value) {
				// 创建接口提交的post数据
				$record = array_combine($filerow, $value);

				if(array_key_exists('creator_id', $record) && (is_null($record['creator_id']) || empty($record['creator_id']))) {
					$record['creator_id'] = 1;
				}

				//暂时只保存item
				if ($this->CI->basic_model->table_name == 'item') :
					$record['app_type'] = 'biz';
					//添加接口签名
					$this->withsignature($record);
					//发送数据
					$ret = $this->CI->curl->go($curllink, $record);
					//接口返回正常
					if (is_array($data) && $ret['status'] == 200 && isset($ret['content']['id'])) :
						$res[] = $ret['content']['id'];
					else :
						//接口报错
					    $json['status'] = 2;
						$json['error'] .= '第' . ($key + 1) .'条数据出错：';
						if (is_array($ret) && $ret['status'] != 200) :
							$json['error'] .= $ret['content']['error']['message'] . "<br>";
						else :
							$json['error'] .= '接口异常<br>';
						endif;
					endif;
				// else :
				// 	//从数据库添加
				// 	$res[] = $this->CI->Basic_model->create($record, TRUE);
				endif;				
			}
			if ($json['status'] == 2) :
                $json['msg'] .= count($data);
                $json['res'] = implode(',', $res);
            else:
                $json['status'] = 0;
                $json['msg'] .= count($data);
                $json['res'] = implode(',', $res);
            endif;
			return $json;
        }

	    /**
		 * 
		 * 获取可以保存的字段
		 *
		 */
        private function getfield(){
			//从csv文件获取可传字段
			$csvfile = 'public/' . $this->CI->basic_model->table_name . '.csv';
			$tablerows = [];
			if (file_exists($csvfile)) :
        		$tablerows = explode(',', file_get_contents($csvfile));
			else :
				//从数据库获取表结构 作为可穿字段
        		$tablerows = $this->CI->basic_model->tablefields();
			endif;
        	return $tablerows;
		}

		/**
		 * 
		 * 读取文件字段
		 *
		 */
		private function getfilehead(){
			$loop   = true;
			$values = [];
			$next   = 1;
			while ($loop) {
				$temp = $this->_phpexcel->getActiveSheet()->getCellByColumnAndRow($next++, $this->_headline)->getValue();
				if (is_null($temp)) :
					$loop = false;
				else :
					$values[] = $temp;	
				endif;
			}
			return $values;
		}

		/**
		 * 
		 * 读取文件的一行
		 *
		 */
		private function getaline($length, $index){
			$values = [];
			for($s = 1; $s <= $length; $s++) {
	            $values[] = $this->_phpexcel->getActiveSheet()->getCellByColumnAndRow($s, $index)->getValue();
			}
			return empty(implode('', $values)) ? false : $values;
		}

		/**
		 * 
		 * 查看表中字段是否合法
		 *
		 */
		private function validrow($table, $file){
			foreach ($file as $key => $value) {
				if (!in_array($value, $table)) {
					return $value;
				}
			}
			return true;
		}

		/**
	     * 正式环境下添加签名
	     * 
	     */
	    private function withsignature(&$params)
	    {
	        if (ENVIRONMENT == 'production') {
	            $params = [
	                'app_type' => 'biz',
	                'app_version' => '1.0.0',
	                'device_platform' => 'server',
	                'device_number' => '-----',
	                'timestamp' => time(),
	                'random' => rand(1000, 9999),
	            ];
	            $item['sign'] = $this->sign_generate($params);
	            $item  += $params;
	        }
	        return true;
	    }

	   /**
		 * 生成签名
		 */
		private function sign_generate($params)
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
	}