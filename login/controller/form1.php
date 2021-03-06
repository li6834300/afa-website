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