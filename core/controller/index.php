<?php

class index extends Base {

	public function __construct() {
		parent::__construct();
	}

	public function indexAction() {
		if(Request::get('catdir') || Request::get('catid')){
			$this->cat(1);
		}elseif(Request::get('id')){
			$this->show($page);
		}else{
            $this->view->assign(array(
                'index'           => 1,
                'site_title'       => $this->site_config['site_title'],
                'site_keywords'    => $this->site_config['site_keywords'], 
                'site_description' => $this->site_config['site_description'],
                ));
            $this->view->display('index.html');
        }
	}


    /**
     * 栏目处理 
     */
    private function cat($page)
    {
    	$catid  = Request::get('catid');
    	if(!$catid) {
    		$catdir = Request::get('catdir');
    		$category_dir = get_cache('category_dir');
    		$catid = (int)$category_dir[$catdir];
    	}
    	$category = $this->category_cache[$catid];
    	if (empty($category)) {
    		header('HTTP/1.1 404 Not Found');
    		$this->show_message('当前栏目不存在');
    	}
    	if($category['islook'] && !$this->member_info) $this->show_message('当前栏目游客不允许查看');
        $category_fields = get_cache('category_fields');
        $category = $this->handle_fields($category_fields, $category);
    	$category['cat'] = $category;
    	$this->view->assign($category);
    	$this->view->assign($this->listSeo($category, $page));
    	if ($category['typeid'] == 1) {
    		$this->view->display($category['listtpl']);
    	}elseif ($category['typeid'] == 2) {
    		$this->view->display($category['pagetpl']);
    	}elseif ($category['typeid'] == 3) {
    		header('Location: ' . $category['http']);
    	}elseif ($category['typeid'] == 4) {
 
    		$modelid = $category['modelid'];
    		$form_model   = get_cache('form_model');
    		$form_model = $form_model[$modelid];
    		!empty($form_model)  or $this->show_message('表单模型不存在');

    		if (Request::isPost()) {
    			$this->postcat($modelid);
    		}
    		$this->view->assign(array(
    			'code'             => $form_model['setting']['form']['code'],
    			'fields'           => $this->get_data_fields($form_model['fields']),
    		));
    		$this->view->display($category['pagetpl']);
    	}
    }


    /**
     * 栏目提交处理 
     */
    private function postcat($modelid)
    {

    	$form_model   = get_cache('form_model');
    	$form_model = $form_model[$modelid];
    	!empty($form_model)  or $this->show_message('表单模型不存在');
    	$formsetting = $form_model['setting']['form'];
    	$gobackurl = Request::post('gobackurl');
    	if (!empty($formsetting['code']) && !$this->checkCode(Request::post('code'))) $this->show_message('验证码不正确',2,1);
    	if (!empty($formsetting['post']) && !$this->member_info) $this->show_message('只允许会员提交，请注册会员后提交',2,1);
    	if (!empty($formsetting['time'])){
    		$time =  $formsetting['time'] * 60;
    		$this->db->setTableName($form_model['tablename'])->where('ip=?', $this->get_user_ip());
    		$ipdata = $this->db->order('time DESC')->getOne('','','time');
    		if (time() - $ipdata['time'] < $time) $this->show_message('同一IP'. $formsetting['time'] .'分钟内不能重复提交',2,1);
    	}
    	if (!empty($formsetting['num']) && !empty($formsetting['post']) && $this->member_info ) {
    		$this->db->setTableName($form_model['tablename'])->where('userid=?', 1);
     		if ($this->db->getOne('','','id')) $this->show_message('您已经提交过了，不能重复提交',2,1);
    	}
    	$data = Request::post('data');
    	unset($data['id']);
    	$data = $this->post_check_fields($form_model['fields'], $data);
    	$data['ip']       = $this->get_user_ip();
    	$data['userid']   = empty($this->member_info) ? 0  : $this->member_info['id'];
    	$data['username'] = empty($this->member_info) ? '' : $this->member_info['username'];
    	$data['time']= time();
    	$data['status']   = empty($formsetting['check']) ? 1 : 0;
    	if(empty($gobackurl)) $gobackurl = HTTP_REFERER;

    	if ($this->db->setTableName($form_model['tablename'])->insert($data,true)) {
                // 邮件发送
    		if (!empty($formsetting['email'])) {
    			extract($this->site_config);
    			$smtpemailto = $formsetting['smtpemailto']?$formsetting['smtpemailto']:$smtpemailto;
                $mailsubject = $formsetting['mailsubject']?$formsetting['mailsubject']:"您有新的表单信息";//邮件主题
                $mailbody = $form_model['modelname'].'<br><hr><br>';
                foreach($form_model['fields'] as $k=>$v){
                	$mailbody .= $v['name'];
                	$mailbody .= ' : ';
                	$mailbody .= $data[$k];
                	$mailbody .= '<br><br>';
                }
                $smtp =  xiaocms::load_class('Email');
                $mailtype = 'HTML';//邮件格式（HTML/TXT）,TXT为文本邮件
                $smtp->config($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);
                $smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype);
            }
            $this->show_message($data['status'] ? '提交成功' : '提交成功，等待审核', 1, $gobackurl);
        } else {
        	$this->show_message('提交失败',2,1);
        }
    }

    private function show($page)
    {
    	$id = (int)Request::get('id');
    	$content  = $this->getContent($id);
    	if (empty($content)) {
    		header('HTTP/1.1 404 Not Found');
    		$this->show_message('不存在此内容！');
    	}
    	if (empty($content['status'])) $this->show_message('此内容正在审核中不能查看！');
    	$category   = $this->category_cache[$content['catid']];
    	if($category['islook'] && !$this->member_info) $this->show_message('请登录后提交');
        $category_fields = get_cache('category_fields');
        $category = $this->handle_fields($category_fields, $category);

    	if (strpos($content['content'], '[XiaoCms-page]') !== false) {
    		$pdata  = array_filter ( explode('[XiaoCms-page]', $content['content']) );
    		$pagenumber = count($pdata);
    		$content['content'] = $pdata[$page-1];
    		$pageurl = $this->view->get_show_url($content, 1);
    		$pagelist = xiaocms::load_class('Pager');
    		$pagelist = $pagelist->total($pagenumber)->url($pageurl)->num(1)->hide()->page($page)->output();
    		$this->view->assign('pagelist', $pagelist);
    	}
        $content['url'] = $this->view->get_show_url($content, $page);
    	$content['content'] = keyword_link($content['content']);
    	$content['cat'] = $category;
        $prev_page = $this->db->setTableName('content')->order('id DESC')->getOne(array('id<?', 'catid=' .$content['catid'], 'status!=0'), $id);
        if ($prev_page) $prev_page['url'] =  $this->view->get_show_url($prev_page);
        $next_page = $this->db->setTableName('content')->order('id ASC')->getOne(array('id>?', 'catid=' .$content['catid'] , 'status!=0'), $id);
        if ($next_page) $next_page['url'] =  $this->view->get_show_url($next_page);
    	$this->view->assign($content);
    	$this->view->assign($this->showSeo($content, $page));
    	$this->view->assign(array(
            'topid' => $category['topid'],
    		'catname' => $category['catname'],
    		'caturl' =>  $category['url'],
    		'prev_page' => $prev_page,
    		'next_page' => $next_page,
    		));
    	$this->view->display($category['showtpl']);
    }

	/**
	 * 内容搜索
	 */
	public function searchAction() {
		$kw    = urldecode(Request::get('kw'));
		if($kw == '')$this->show_message('请输入要搜索的关键字 如:ceshi');
		$catid    = $catid ? $catid : (int)Request::get('catid');
		$modelid    = $modelid ? $modelid : (int)Request::get('modelid');
		$page   = (int)Request::get('page') ? (int)Request::get('page') : 1;
		$pagesize = 10;
		$urlparam = array();
		$urlparam['kw']      = $kw;
		$url      = url('index/search', $urlparam);
		if ($catid) $this->db->where('catid=?', $catid);
		if ($modelid) $this->db->where('modelid=?', $modelid);
		$data    = $this->db->setTableName('content')->pageLimit($page, $pagesize)->where("`title` LIKE  ?",'%'.$kw.'%')->getAll(null,null,null,array('listorder DESC', 'time DESC'));
		foreach ($data as $key => $t) {
			$data[$key]['url'] = $this->view->get_show_url($t);
		}
		if ($catid) $this->db->where('catid=?', $catid);
		if ($modelid) $this->db->where('modelid=?', $modelid);
		$total = $this->db->setTableName('content')->where("`title` LIKE  ?",'%'.$kw.'%')->count();
		$pagelist = xiaocms::load_class('Pager');
		$pagelist = $pagelist->total($total)->url($url. '&page=[page]')->hide(true)->num($pagesize)->page($page)->output();
		$this->view->assign($this->listSeo($cat, $page, $kw));
		$this->view->assign(array(
			'kw'         => $kw,
			'pagelist' => $pagelist,
			'data' => $data,
			'num' => $total,
			'site_title'  => '搜索 ' . $kw . ' - ' . $this->site_config['site_name'],
			'site_keywords'    => $kw, 
			'site_description' => '搜索 ' . $kw . ' - ',
			));
		$this->view->display('search.html');
	}
	

	/*
	 * 表单提交页面
	 */
	public function formAction() {
		$modelid = (int)Request::get('modelid');
		$cid  = (int)Request::get('cid');
		$form_model   = get_cache('form_model');
		$form_model = $form_model[$modelid];
		!empty($form_model)  or $this->show_message('表单模型不存在');
		if (!empty($form_model['joinid'])) {
			!empty($cid) or $this->show_message('缺少关联内容id');
			$this->db->setTableName('content')->getOne(array('id=?', 'modelid=?'), array($cid, $form_model['joinid']),'id')  or $this->show_message('关联id不存在');
		}

		if (Request::isPost()) {
			$gobackurl = Request::post('gobackurl');
			if (!empty($form_model['setting']['form']['code']) && !$this->checkCode(Request::post('code'))) $this->show_message('验证码不正确',2,1);
			if (!empty($form_model['setting']['form']['post']) && !$this->member_info) $this->show_message('只允许会员提交，请注册会员后提交',2,1);
			if (!empty($form_model['setting']['form']['time'])){
				$time   =  $form_model['setting']['form']['time'] * 60;
				$this->db->setTableName($form_model['tablename'])->where('ip=?', $this->get_user_ip());
				if (!empty($form_model['joinid'])) $this->db->where('cid=?', $cid);
				$ipdata = $this->db->order('time DESC')->getOne('','','time');
				if (time() - $ipdata['time'] < $time) $this->show_message('同一IP在'. $form_model['setting']['form']['time'] .'分钟内不能重复提交',2,1);
			}
			if (!empty($form_model['setting']['form']['num']) && !empty($form_model['setting']['form']['post']) && $this->member_info ) {
				$this->db->setTableName($form_model['tablename'])->where('userid=?', 1);
				if (!empty($form_model['joinid'])) $this->db->where('cid=?', $cid);
				if ($this->db->getOne('','','id')) $this->show_message('您已经提交过了，不能重复提交',2,1);
			}
			$data = Request::post('data');
			unset($data['id']);
			$data = $this->post_check_fields($form_model['fields'], $data);
			$data['cid']      = $cid;
			$data['ip']       = $this->get_user_ip();
			$data['userid']   = empty($this->member_info) ? 0  : $this->member_info['id'];
			$data['username'] = empty($this->member_info) ? '' : $this->member_info['username'];
			$data['time']= time();
			$data['status']   = empty($form_model['setting']['form']['check']) ? 1 : 0;
			if(empty($gobackurl)) $gobackurl = HTTP_REFERER;

			if ($this->db->setTableName($form_model['tablename'])->insert($data,true)) {
                // 邮件发送
				if (!empty($form_model['setting']['form']['email'])) {
					extract($this->site_config);
					$smtpemailto = $form_model['setting']['form']['smtpemailto']?$form_model['setting']['form']['smtpemailto']:$smtpemailto;
                    $mailsubject = $form_model['setting']['form']['mailsubject']?$form_model['setting']['form']['mailsubject']:"您有新的表单信息";//邮件主题
                    $mailbody = $form_model['modelname'].'<br><hr><br>';
                    foreach($form_model['fields'] as $k=>$v){
                    	$mailbody .= $v['name'];
                    	$mailbody .= ' : ';
                    	$mailbody .= $data[$k];
                    	$mailbody .= '<br><br>';
                    }
                    $smtp =  xiaocms::load_class('Email');
                    $mailtype = 'HTML';//邮件格式（HTML/TXT）,TXT为文本邮件
                    $smtp->config($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);
                    $smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype);
                }

                $this->show_message($data['status'] ? '提交成功' : '提交成功，等待审核', 1, $gobackurl);
            } else {
            	$this->show_message('提交失败',2,1);
            }
        }
        $this->view->assign(array(
        	'code'             => $form_model['setting']['form']['code'],
        	'fields'           => $this->get_data_fields($form_model['fields']),
        	'form_name' => $form_model['modelname'],
        	'site_title'       => $form_model['modelname'] .' - ' . $this->site_config['site_name'],
        	'site_keywords'    => $this->site_config['site_keywords'], 
        	'site_description' => $this->site_config['site_description'].'',
        	));
        $this->view->display($form_model['showtpl']);
    }

}