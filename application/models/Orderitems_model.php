<?php
	/**
	 * 基础模型类
	 *
	 * @version 1.0.0
	 * @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	 * @copyright ICBG <www.bingshankeji.com>
	 * @copyright Basic <https://github.com/kamaslau/BasicCodeIgniter>
	 */
	class Orderitems_model extends CI_Model
	{
		/**
		 * 数据库表名
		 *
		 * @var string $table_name 表名
		 */
		public $table_name = 'order_items';

		/**
		 * 数据库主键名
		 *
		 * @var string $id_name 数据库主键名
		 */
		public $id_name = 'record_id';
		public $CI;
		private $bmodel;
		public function __construct(){
			$this->CI = & get_instance();
		}
		public function verifylist($count = false){
			$statusMap = ['all'=>'', 'done'=>'已使用', 'yet'=>'未消费', 'expire'=>'已过期'];
			$status = $this->CI->input->get('status');
			$page   = intval($this->CI->input->get('page'));
			$status = array_key_exists($status, $statusMap) ? $statusMap[$status] : '';
			$pagesize = 20;

			$query = ' biz_id=' . intval($this->CI->session->biz_id);
			if (!empty($status))
				$query .= ' and status=\'' . $status . '\'';
			$query .= ' and nature=\'服务\'';
			$page = $page <= 0 ? 1 : $page;
			$page = ($page - 1) * $pagesize;
			if($count) {
				$sql = 'select count(*) count from ' . $this->table_name . ' where ' . $query;
			} else {
				$sql = 'select * from ' . $this->table_name . ' where ' . $query . ' order by record_id desc limit ' . $page . ', ' . $pagesize;
			}
			$res = $this->db->query($sql);
			$data = $res->result_array();
			if($count) {
				return intval($data[0]['count']);
			}
			return $data;
		}
		public function getdetail($record_id, $vcode){
			if($record_id) {
				$res = $this->db->query("select * from " . $this->table_name . " where record_id={$record_id} and biz_id=" . $this->CI->session->biz_id);
			} elseif (!empty($vcode) && strlen($vcode) == 10) {
				
				$res = $this->db->query("select * from " . $this->table_name . " where verify_code='{$vcode}' and biz_id=" . $this->CI->session->biz_id);
				// echo "select * from " . $this->table_name . " where verify_code='{$vcode}' and biz_id=" . $this->CI->session->biz_id;
			}
			
			$data = $res->result_array();
			if($data) {
				return $data[0];
			}
			return [];

		}

		public function verify($vcode, $order_id){
			$now = time();
			$res = $this->db->query("update " . $this->table_name . " set time_verified={$now},`status`='已使用' where verify_code='{$vcode}' and time_expire>={$now} and status='未消费' and biz_id=" . $this->CI->session->biz_id);
			if($res){
				$res = $this->db->query("select count(*) count from " . $this->table_name . " where order_id=" . $order_id . " and status='未消费'");
				$data = $res->result_array();
				if (isset($data[0]['count']) && $data[0]['count'] == 0) {
					$res = $this->db->query("update `order` set `status`='已完成',time_confirm=" . time() . " where order_id=" . $order_id);
				}
			}
			return $res;
		}
		/**
	     * 分页函数
	     * @param $cur int  当前页码
	     * @param $total int  总条数
	     * @param $size int 每页条数
	     * @param $url string  URL
	     * @param $url_suffix string  URL后缀
	     *
	     */
	    public function pager($total, $size, $url, $url_suffix='') {
	    	$cur = intval($this->CI->input->get('page'));
	        if ($cur <= 0) {
	            $cur = 1;
	        }
	        if ($total == 0 || $total < $size){
	            $page_num = 0;
	        } else {
	            $page_num = floor($total / $size);
	        }

	        if (($total % $size) > 0) {
	            $page_num++;
	        }

	        if ($cur > $page_num) {
	            $cur = $page_num;
	        }

	        $cur > 5 ? $page_start = $cur - 4 : $page_start = 1;

	        if ($page_start > ($page_num - 9)) {
	            $page_start = $page_num - 8;
	        }

	        if ($page_start < 1) {
	            $page_start = 1;
	        }

	        $cur < 5 ? $page_end = 10 : $page_end = $cur + 5;
	        if ($page_end > $page_num) {
	            $page_end = $page_num + 1;
	        }

	        $cur > 1 ? $pagestr = '<li class="prev"><a href="'.$url.'1'.$url_suffix.'"><i class="fa fa-angle-double-left"></i></a></li><li class="prev"><a href="'.$url.($cur - 1).$url_suffix.'"><i class="fa fa-angle-left"></i></a></li>' : $pagestr = '<li class="prev disabled"><a href="#"><i class="fa fa-angle-double-left"></i></a></li><li class="prev disabled"><a href="#"><i class="fa fa-angle-left"></i></a>';

	        for ($i = $page_start; $i < $page_end; $i++){
	            $pagestr .= ($i == $cur) ? '<li class="active"><a href="#">'.$cur.'</a></li>' : '<li><a href="'.$url.$i.$url_suffix.'">'.$i.'</a></li>';
	        }

	        if ($total == 0) {
	            $pagestr .= '<li class="active"><a href="#">1</a></li>';
	        }

	        $cur < $page_num ? $pagestr .= '<li class="next"><a href="'.$url.($cur+1).$url_suffix.'"><i class="fa fa-angle-right"></i></a></li><li class="next"><a href="'.$url.$page_num.$url_suffix.'"><i class="fa fa-angle-double-right"></i></a></li>' : $pagestr .= '<li class="next disabled"><a href="#"><i class="fa fa-angle-right"></i></a></li><li class="next disabled"><a href="#"><i class="fa fa-angle-double-right"></i></a></li>';
	        return $pagestr;
	    }
	}