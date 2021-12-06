<?php

class Member extends Base {

    protected $form_model;
    protected $member_model;

	public function __construct() {
		parent::__construct();
		$this->isMemberLogin();
		$this->form_model   = get_cache('form_model');
        $this->member_model = get_cache('member_model');
		$member_nav = null;
		if ($this->content_model) {
		    foreach ($this->content_model as $t) {
				$member_nav[$t['modelid']] = array('name'=>$t['modelname'], 'url'=>url('content/', array('modelid'=>$t['modelid'])));
			}
		}
		if ($this->form_model) {
		    foreach ($this->form_model as $id=>$t) {
			    if (!empty($t['setting']['form']['member'])) {
				$member_nav[$t['modelid']] = array('name'=>$t['modelname'], 'url'=>url('content/form', array('modelid'=>$t['modelid'])));
				}
			}
		}
	    $this->view->assign(array(
			'member_model'     => $this->member_model,
		    'member_nav' => $member_nav,
		));
	}
	
	protected function isMemberLogin() {
       if (CONTROLLER_NAME == 'login' || CONTROLLER_NAME == 'register' ) return false;
	    if ($this->member_info) return false;
		$back = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : url('index');
		$this->redirect(url('login', array('back' => urlencode($back))));
	}
	
}