<?php

class index extends Member {

    public function __construct() {
		parent::__construct();
	}
	
	public function indexAction() {
	    $this->view->assign(array(
			'member_index'     => 1,
		    'site_title' => '会员中心 - ' . $this->site_config['site_name'],
		));
	    $this->view->display('member/index.html');
	}

	/**
	 * 资料修改
	 */
	public function editAction() {
	    $modelid = $this->member_info['modelid'];
		$tablename = $this->member_model[$modelid]['tablename'];
	    $fields  = $this->member_model[$modelid]['fields'];
	    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	        $data = Request::post('data');
	        unset($data['id']);
			$data = $this->post_check_fields($fields , $data);
			$memberdata = $this->db->setTableName($tablename)->find($this->member_info['id']);
			if ($memberdata) {
			    //修改附表内容
				$this->db->setTableName($tablename)->update($data, 'id=?' , $this->member_info['id']);
			} else {
				$data['id'] = $this->member_info['id'];
				$this->db->setTableName($tablename)->insert($data);
			}
			$this->show_message('修改成功', 1, url('index/edit'));
	    }
	    $this->view->assign(array(
	        'fields' => $this->get_data_fields($fields, $this->member_info),
			'site_title'  => '修改资料 - 会员中心 - ' . $this->site_config['site_name'],
	    ));
	    $this->view->display('member/info_edit.html');
	}
	
	
	/**
	 * 密码修改
	 */
	public function passwordAction() {
	    if (Request::isPost()) {
	        $data   = Request::post('data');
	        unset($data['id']);
			if ($this->member_info['password'] != md5(md5($data['password1'])) ) $this->show_message('原密码错误');
			if (empty($data['password2'])) $this->show_message('新密码不能为空。');
			if ($data['password2'] != $data['password3']) $this->show_message('两次密码不一致。');
            $this->db->setTableName('member')->update(array('password'=>md5(md5($data['password2']))), 'id=?', $this->member_info['id']);
			$this->show_message('修改成功', 1, url('index/password'));
	    }
		$this->view->assign(array(
			'site_title' => '修改密码 - 会员中心 - ' . $this->site_config['site_name'],
	    ));
	    $this->view->display('member/password.html');
	}

	
}