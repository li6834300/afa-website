<?php

class Admin extends Base {
	protected $auth;
	protected $admin;

	public function __construct() {
		parent::__construct();
		$this->isAdminLogin();
		$userid = Session::get('user_id');
		$admin_cache = get_cache('admin');
		if ($admin_cache) {
	    $this->admin = $admin_cache[$userid];
		}
		else {
	    $this->admin = $this->db->setTableName('admin')->find($userid);
		}
	    $go = CONTROLLER_NAME . '-' . ACTION_NAME;
	    $this->auth = string2array($this->admin['auth']);
		$skip = array(
		'index-my' => '1',
		'index-tree' => '1',
		'index-main' => '1',
		'index-index' => '1',
		'login-index' => '1',
		'login-logout' => '1',
		'index-cache' => '1',
		'block-cache' => '1',
		'models-cache' => '1',
		'category-cache' => '1',
		'template-cache' => '1',
		'content-related' => '1',
		'content-check_title' => '1',
		'member-ajaxemail' => '1',
		'uploadfile-kindeditor_filemanager' => '1',
		'uploadfile-kindeditor_upload' => '1',
		'uploadfile-ueditor' => '1',
		);
		$auth = array_merge ($skip,$this->auth);
		if(empty($auth[$go]) && empty($this->admin['roleid'])) {
		$this->show_message('您没有权限', 2);
		}
	}
	
    protected function menu($string) {
	    $auth = string2array($this->admin['auth']);
		if ($this->admin['roleid']) return true;
		if ($auth[$string]){
		return true;
		}
		else {
		return false;
		}
    }
	
    protected function isAdminLogin() {
        if (CONTROLLER_NAME == 'login') return false;
        if ($user_id = Session::get('user_id')) {
		    if ($user_id) return false;
        }
        $this->redirect(url('login'));
    }

    protected function admin_tpl($file) {
        return  XIAOCMS_ADMIN . 'template' . DS . $file.'.tpl.php';
    }
	
}