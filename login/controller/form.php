<?php

class form extends Admin {

	protected $cid;
	protected $modelid;
	protected $model;
	protected $table;
	protected $join;
	protected $join_info;
    public function __construct() {
		parent::__construct();
		$form_model     = get_cache('form_model');
		$this->cid     = (int)Request::get('cid');
		$this->modelid = (int)Request::get('modelid');
		if (empty($this->modelid)) $this->show_message('表单模型id不存在');
		$this->model   = $form_model[$this->modelid];
		if (empty($this->model)) $this->show_message('表单模型不存在');
		$this->table   = $this->model['tablename'];
		$this->join    = isset($this->content_model[$this->model['joinid']]) ? $this->content_model[$this->model['joinid']] : null;
		if ($this->join)
		$this->join_info  = '已关联：' . $this->join['modelname'];
		else
		$this->join_info  = '独立表单';
	}
		
	/**
	 * 表单内容管理
	 */
	public function indexAction() {
		$cid       = (int)Request::get('cid');
		$modelid   =  (int)Request::get('modelid');
	    if (Request::post('formidarr') && Request::post('status')=='del') {
     		foreach (Request::post('formidarr') as $id) {
	            $this->db->setTableName($this->table)->delete('id=?' , $id);
			}
			$this->show_message('删除成功', 1);
	    } elseif (Request::post('formidarr') && Request::post('status')=='1') {
     		foreach (Request::post('formidarr') as $id) {
	            $this->db->setTableName($this->table)->update(array('status'=>1), 'id=?' , $id);
			}
			$this->show_message('设置成功', 1);
	    } elseif (Request::post('formidarr') && Request::post('status')=='2') {
     		foreach (Request::post('formidarr') as $id) {
	            $this->db->setTableName($this->table)->update(array('status'=>0), 'id=?' , $id);
			}
			$this->show_message('设置成功', 1);
	    } elseif (Request::post('formidarr') && Request::post('status')=='excel') {
			$id = implode(",", Request::post('formidarr'));
			$res = $this->db->setTableName($this->table)->getAll('`id` IN ('.$id.')');;
		// 引入phpexcel核心类文件
		//require_once ROOT_PATH . 'PHPExcel.php';
		$objPHPExcel = xiaocms::load_class('PHPExcel');
		// 实例化excel类
		//$objPHPExcel = new PHPExcel();
		// 操作第一个工作表
		$objPHPExcel->setActiveSheetIndex(0);
		// 设置sheet名
		$objPHPExcel->getActiveSheet()->setTitle($this->model['modelname'].'数据列表');
	
		// 设置表格宽度
	   /* $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(50);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);*/
 
 
		// 列名表头文字加粗
		$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(true);
		// 列表头文字居中
		$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getAlignment()
			->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$cellKey = array(
				'A','B','C','D','E','F','G','H','I','J','K','L','M',
				'N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
				'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM',
				'AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ'
		);
		//获取字段名
		$zd =$this->model['setting']['form']['show'];
		// 列名赋值
		foreach ($zd as $key=>$v) {
		$m=$this->model['fields'][$v]['name'];
		$objPHPExcel->getActiveSheet()->setCellValue($cellKey[$key].'1', $this->model['fields'][$v]['name']);
	
		} 
		// 数据起始行
		$row_num = 2;
		// 向每行单元格插入数据
		foreach($res as $value)
		{
        // 设置所有居中
        $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		foreach ($zd as $key=>$v) {
        // 设置单元格数值
        $objPHPExcel->getActiveSheet()->setCellValue($cellKey[$key] . $row_num, $value[$zd[$key]]);
		}
        $row_num++;
    	}
		$outputFileName = ''.$this->model['modelname'].'.xls';
		$xlsWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="' . $outputFileName . '"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$xlsWriter->save("php://output");
		echo file_get_contents($outputFileName);	
	    } 

		$page     = (int)Request::get('page') ? (int)Request::get('page') : 1;
		$userid   = (int)Request::get('userid');
	    $pagelist = xiaocms::load_class('Pager');
	    $pagesize = empty($this->admin['list_size']) ? 10 : $this->admin['list_size'];
	    $urlparam = array(
			'userid' => $userid,
			'cid'    => $this->cid,
			'modelid'=> $this->modelid,
		);

		if (!empty($userid)) $this->db->where('userid=?',$userid);
		if (!empty($this->cid)) $this->db->where('cid=?',$this->cid);
	    $listArr = $this->db->setTableName($this->table)->page($page, $pagesize,null,'id DESC');
	    $list = $listArr['list'];
	    $total = $listArr['total'];

	    $pagelist = $pagelist->total($total)->url(url('form/index', $urlparam) . '&page=[page]')->num($pagesize)->page($page)->output();
	    include $this->admin_tpl('form_list');
	}
	
	/**
	 * 修改内容
	 */
	public function editAction() {
		$id = (int)Request::get('id');
		if (empty($id)) $this->show_message('表单内容不存在');
		if (Request::isPost()) {
		    $data = Request::post('data');
			$data = $this->post_check_fields($this->model['fields'], $data);
			if ($this->db->setTableName($this->table)->update($data, 'id=' . $id)) {
			    $this->show_message('修改成功', 1, url('form/index', array('modelid'=>$this->modelid, 'cid'=>$this->cid)));
			} else {
			    $this->show_message('操作失败');
			}
		}
		$data     = $this->db->setTableName($this->table)->find($id);
		if (empty($data)) $this->show_message('表单内容不存在');
		$model     = $this->model;
		$cid    = $data['cid'];
		if($cid) $ciddata  = $this->db->setTableName('content')->find($cid);
		$fields = $this->get_data_fields($this->model['fields'], $data);
	    include $this->admin_tpl('form_edit');
	}
	
	/**
	 * 删除
	 */
	public function delAction() {
	    $id    = (int)Request::get('id');
		$this->db->setTableName($this->table)->delete('id=?' , $id);
		$this->show_message('删除成功', 1);
	}
	
}