<?php

class diytable extends Admin {

	protected $modelid;
	protected $model;
	protected $table;
    public function __construct() {
		parent::__construct();
		$diy_model     = get_cache('diy_model');
		$this->modelid = (int)Request::get('modelid');
		if (empty($this->modelid)) $this->show_message('自定义模型id不存在');
		$this->model   = $diy_model[$this->modelid];
		if (empty($this->model)) $this->show_message('自定义模型不存在');
		$this->table   = $this->model['tablename'];
	}
	
	/**
	 * 自定表内容列表管理
	 */
	public function indexAction() {
		$modelid   =  $this->modelid;
	    if (Request::post('formidarr') && Request::post('status')=='del') {
     		foreach (Request::post('formidarr') as $id) {
	            $this->db->setTableName($this->table)->delete('id=?' , $id);
			}
			$this->show_message('删除成功', 1);
	    }
		$page     = (int)Request::get('page') ? (int)Request::get('page') : 1;
	    $pagelist = xiaocms::load_class('Pager');
	    $pagesize = empty($this->admin['list_size']) ? 10 : $this->admin['list_size'];

	    $listArr = $this->db->setTableName($this->table)->page($page, $pagesize,null,'id DESC');
	    $list = $listArr['list'];
	    $total = $listArr['total'];

	    
	    $pagelist = $pagelist->total($total)->url(url('diytable/index', array('modelid'=> $this->modelid)) . '&page=[page]')->num($pagesize)->page($page)->output();
	    include $this->admin_tpl('diytable_list');
	}
	
	/**
	 * 添加内容
	 */
	public function addAction() {
		if (Request::isPost()) {
		    $data = Request::post('data');
	        unset($data['id']);
			$data = $this->post_check_fields($this->model['fields'], $data);
			$insertid = $this->db->setTableName($this->table)->insert($data,true);
			if ($insertid) {
			    $this->show_message('添加成功', 1, url('diytable/index', array('modelid'=>$this->modelid)));
			} else {
			    $this->show_message('添加失败');
			}
		}
		$fields = $this->get_data_fields($this->model['fields']);
	    include $this->admin_tpl('diytable_add');
	}
	
	/**
	 * 修改内容
	 */
	public function editAction() {
		$id = (int)Request::get('id');
		if (empty($id)) $this->show_message('内容id不存在');
		if (Request::isPost()) {
		    $data = Request::post('data');
	        unset($data['id']);
			$data = $this->post_check_fields($this->model['fields'], $data);
			if ($this->db->setTableName($this->table)->update($data, 'id=' . $id)) {
			    $this->show_message('修改成功', 1, url('diytable/index', array('modelid'=>$this->modelid)));
			} else {
			    $this->show_message('操作失败');
			}
		}
		$data     = $this->db->setTableName($this->table)->find($id);
		if (empty($data)) $this->show_message('内容不存在');
		$fields = $this->get_data_fields($this->model['fields'], $data);
	    include $this->admin_tpl('diytable_add');
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