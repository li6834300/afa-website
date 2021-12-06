<?php

class login extends Admin {
    
    public function __construct() {
		parent::__construct();
	}
	
    public function indexAction() {
		if (Request::isPost()) {
			if (!$this->checkCode(Request::post('code'))) $this->show_message('验证码不正确',2,url('login'));
			if (Cookie::get('admin_login')) $this->show_message('密码错误次数过多，请15分钟后重新登录');
		    $username = Request::post('username');
		    $password = Request::post('password');
			$admin  = $this->db->setTableName('admin')->getOne('username=?', $username);
		    if ($admin['username'] == $username &&  $admin['password'] == md5(md5($password))) {
		        Session::set('user_id', $admin['userid']);
				if (Session::get('admin_login_error_num')) 
				{
				Session::delete('admin_login_error_num');
				}
			    $this->show_message('恭喜您！'.$username.' 登录成功', 1, './');
		    } else {
			    if (Session::get('admin_login_error_num')) {
				    $error = Session::get('admin_login_error_num') - 1;
					if ($error <= 1) {
						Session::delete('admin_login_error_num');
					    Cookie::set('admin_login', 1, 60*15);
					} else {
					    Session::set('admin_login_error_num', $error);
					}
				} else {
				    $error = 10;
					Session::set('admin_login_error_num', 10);
				}
			    $this->show_message('账户或密码不正确，您还可以尝试'.$error.'次', 2, url('login'));
			}
		}

        include $this->admin_tpl('login');
    }
    
    public function logoutAction() {
        Session::delete('user_id');
        $this->show_message('已经成功退出网站管理系统', 1, url('login'));
    }
	
}