<?php

class content extends Member {
    
    public function __construct() {
		parent::__construct();
		if (!$this->member_info['status']) $this->show_message('您还没有通过审核。',2,'../'); //判断审核
	}
	
	/*
	 * 内容管理
	 */
	public function indexAction() {
	    if (Request::post('catid')) $this->redirect(url('content/add', array('catid'=>Request::post('catid'))));
		$page     = (int)Request::get('page') ? (int)Request::get('page') : 1;
		$modelid  = (int)Request::get('modelid');
	    $pagelist = xiaocms::load_class('Pager');
	    $pagesize = 10;//分页列表		
		if ($modelid) $this->db->where('modelid=?', $modelid);
		$list = $this->db->setTableName('content')->pageLimit($page, $pagesize)->where('username=?',$this->member_info['username'])->getAll(null, null,null,'status DESC,time DESC');
		if ($modelid) $this->db->where('modelid=?', $modelid);
	    $total    = $this->db->setTableName('content')->where('username=?',$this->member_info['username'])->count();
	    $pagelist = $pagelist->total($total)->url(url('content', array('modelid'=>$modelid, 'page'=>'&page=[page]')))->hide()->num($pagesize)->page($page)->output();
		$tree =  xiaocms::load_class('Tree');
		$tree->icon = array(' ','  ','  ');
		$tree->nbsp = '&nbsp;';
		$categorys = array();
		foreach($this->category_cache as $cid=>$r) {
			if(!$r['ispost'] || $r['typeid']!=1) continue;
			$r['disabled'] = $r['child'] ? 'disabled' : '';
			$r['selected'] = $cid == $catid ? 'selected' : '';
			$categorys[$cid] = $r;
		}
		$str  = "<option value='\$catid' \$selected \$disabled>\$spacer \$catname</option>";
		$tree->init($categorys);
		$category = $tree->get_tree(0, $str);
        foreach ($list as $key => $t) {
            $list[$key]['url'] = $this->view->get_show_url($t);
        }
	    $this->view->assign(array(
	        'list'       => $list,
	        'pagelist'   => $pagelist,
			'site_title' =>  '内容管理 - 会员中心 - ' . $this->site_config['site_name'],
			'modelid'    => $modelid,
			'model'		 => $this->content_model[$modelid],
			'category'   => $category,
	    ));
	    $this->view->display('member/content_list.html');
	}
	
	/*
	 * 发布
	 */
	public function addAction() {
	    $catid    = (int)Request::get('catid');
	    if (empty($this->category_cache[$catid])) $this->show_message('选择发布的栏目不存在');
	    $modelid  = $this->category_cache[$catid]['modelid'];
	    if (empty($this->content_model[$modelid])) $this->show_message('模型不存在');
		if (!empty($this->category_cache[$catid]['child'])) $this->show_message('只能发布到子栏目');
		if (empty($this->category_cache[$catid]['ispost'])) $this->show_message('该栏目不能投稿');
	    $fields   = $this->content_model[$modelid]['fields'];
	    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	        $data  = Request::post('data');
	        if (empty($data['title'])) $this->show_message('请填写标题',2,1);
			$data = $this->post_check_fields($fields, $data);
	        $data['username']  = $this->member_info['username'];
	        $data['time'] =  time();
			$data['status']    = $this->category_cache[$catid]['verify'];
	        $data['modelid']   = $modelid;
			$data['id'] = $this->db->setTableName('content')->insert($data,true);
			if (!is_numeric($data['id'])) 	        $this->show_message('发布失败',2,1);
			$id = $this->db->setTableName($this->category_cache[$catid]['tablename'])->insert($data,true);
			if (!is_numeric($id)) 	        $this->show_message('发布失败 添加附表失败',2,1);
			$msg = '<a href="' . url('content/add', array('catid'=>$catid)) . '" style="font-size:14px;">继续发布</a>&nbsp;&nbsp;<a href="' . url('content/', array('modelid'=>$modelid)) . '" style="font-size:14px;">返回列表</a>';
	        $this->show_message($msg, 1,url('content/', array('modelid'=>$modelid)));
	    }
	    $fields_data      = $this->get_data_fields($fields);
		$tree =  xiaocms::load_class('Tree');
		$tree->icon = array(' ','  ','  ');
		$tree->nbsp = '&nbsp;';
		$categorys = array();
		foreach($this->category_cache as $cid=>$r) {
			if($modelid && $modelid != $r['modelid']) continue;
			if(!$r['ispost'] || $r['typeid']!=1) continue;
			$r['disabled'] = $r['child'] ? 'disabled' : '';
			$r['selected'] = $cid == $catid ? 'selected' : '';
			$categorys[$cid] = $r;
		}
		$str  = "<option value='\$catid' \$selected \$disabled>\$spacer \$catname</option>";
		$tree->init($categorys);
		$category = $tree->get_tree(0, $str);
	    $this->view->assign(array(
	        'data'        => array('catid'=>$catid),
	        'fields' => $fields_data,
			'site_title'  => '发布内容 - 会员中心 - ' . $this->site_config['site_name'],
			'model'       => $this->content_model[$modelid],
			'modelid'     => $modelid,
			'category'    => $category,
	    ));
	    $this->view->display('member/content_add.html');
	}
	
	/**
	 * 修改文章
	 */
    public function editAction() {
	    $id       = (int)Request::get('id');
		$data = $this->db->setTableName('content')->where('username=?', $this->member_info['username'])->where('id=?' , $id)->getOne();
	    $catid    = $data['catid'];
	    if (empty($data))  $this->show_message('内容不存在');
	    if (empty($catid)) $this->show_message('内容栏目不存在');
	    $modelid  = $this->category_cache[$catid]['modelid'];
	    if (empty($this->content_model[$modelid])) $this->show_message('模型不存在');
		if (!empty($this->category_cache[$catid]['child'])) $this->show_message('文章在父栏目 无法修改请联系管理员,或官方人员');
		if (empty($this->category_cache[$catid]['ispost'])) $this->show_message('该栏目不能投稿');
	    $fields   = $this->content_model[$modelid]['fields'];
	    $tablename       = $this->content_model[$modelid]['tablename'];
	    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	        $data = Request::post('data');
	        if (empty($data['title'])) $this->show_message('请填写标题',2,1);
	        if ($data['catid'] != $catid) $this->show_message('栏目模型不一致，无法修改栏目',2,1);
			$data = $this->post_check_fields($fields, $data);
	        $data['username']  = $this->member_info['username'];
	        $data['time'] =  time();
			$data['status']    = $this->category_cache[$catid]['verify'];
	        $data['modelid']   = $modelid;
			unset($data['modelid']);
			$this->db->setTableName('content')->update($data,  'id=?' , $id);
	        $this->db->setTableName($tablename)->update($data,  'id=?' , $id);
	        $this->show_message('修改成功', 1, url('content/', array('modelid'=>$modelid)));
	    }
	    $data  = $this->db->setTableName('content')->find($id);
	    $data2  = $this->db->setTableName($tablename)->find($id);
	    if ($data2) $data = array_merge($data, $data2);
	    $fields_data = $this->get_data_fields($fields, $data);
		$tree =  xiaocms::load_class('Tree');
		$tree->icon = array(' ','  ','  ');
		$tree->nbsp = '&nbsp;';
		$categorys = array();
		foreach($this->category_cache as $cid=>$r) {
			if($modelid && $modelid != $r['modelid']) continue;
			if(!$r['ispost'] || $r['typeid']!=1) continue;
			$r['disabled'] = $r['child'] ? 'disabled' : '';
			$r['selected'] = $cid == $catid ? 'selected' : '';
			$categorys[$cid] = $r;
		}
		$str  = "<option value='\$catid' \$selected \$disabled>\$spacer \$catname</option>";
		$tree->init($categorys);
		$category = $tree->get_tree(0, $str);
	    $this->view->assign(array(
	        'data'		  => $data,
	        'fields' => $fields_data,
			'site_title'  => '修改内容 - 会员中心 -' . $this->site_config['site_name'],
			'model'       => $this->content_model[$modelid],
			'modelid'     => $modelid,
			'category'    => $category,
	    ));
	    $this->view->display('member/content_add.html');
	}

	/**
	 * 删除文章 注销掉此功能 会员只能发布不能删除 此段代码逻辑也有问题 会员可以任意删除自己发布的文章

	public function delAction(){
	    $id    = (int)Request::get('id');
		$data  = $this->db->setTableName('content')->find($id);
		if ($data['username'] == $this->member_info['username'] && $data['status'] ==0 ) {
			$this->db->setTableName('content')->delete('id=?' , $id);
			$this->show_message('删除成功',1 );
		} else {
		    $this->show_message('无权操作');
		}
	}
	*/
	
	/*
	 * 表单管理
	 */
	public function formAction() {
	    $form_model = get_cache('form_model');
		$cid      = (int)Request::get('cid');
		$page     = (int)Request::get('page') ? (int)Request::get('page') : 1;
		$modelid  = (int)Request::get('modelid');
		if (empty($this->form_model[$modelid])) $this->show_message('表单不存在');
	    $table    = $this->form_model[$modelid]['tablename'];
	    $pagelist = xiaocms::load_class('Pager');
	    $pagesize = 15;
        if (!empty($cid)) $this->db->where('cid=?', $cid);
		$total = $this->db->setTableName($table)->where(array('status=1', 'userid=?'), $this->member_info['id'])->count();
		$list = $this->db->setTableName($table)->where(array('status=1', 'userid=?'), $this->member_info['id'])->getAll(null,null,null, 'id DESC');
        if (!empty($cid)) $this->db->where('cid=?', $cid);
	    $pagelist = $pagelist->total($total)->url(url('content/form', array('modelid'=>$modelid, 'cid'=>$cid, 'page'=>'&page=[page]')))->hide()->num($pagesize)->page($page)->output();
	    $this->view->assign(array(
	        'list'   => $list,
	        'pagelist'   => $pagelist,
			'site_title' => $this->form_model[$modelid]['modelname'] . ' - 会员中心 - ' . $this->site_config['site_name'],
			'fields' => isset($this->form_model[$modelid]['setting']['form']['membershow']) ? $this->form_model[$modelid]['setting']['form']['membershow'] : array(),
			'form'       => $this->form_model[$modelid],
			'modelid'    => $modelid,
			'join'       => $this->form_model[$modelid]['joinid'] ? $this->form[$modelid]['joinname'] : null,
	    ));
	    $this->view->display('member/form_list.html');
	}
	
	/*
	 * 查看表单内容
	 */
	public function formshowAction() {
	    $id      = (int)Request::get('id');
		if (empty($id)) $this->show_message('id不能为空');
		$modelid = (int)Request::get('modelid');
		if (empty($this->form_model[$modelid])) $this->show_message('表单模型不存在');
		$data = $this->db->setTableName($this->form_model[$modelid]['tablename'])->where('userid=?', $this->member_info['id'])->where('id=?' , $id)->getOne();
		if (!$data) $this->show_message('内容不存在');
		$this->view->assign(array(
			'data'        => $data,
			'form'        => $this->form_model[$modelid],
			'listurl'        => HTTP_REFERER,
			'modelid'     => $modelid,
			'site_title'  => $this->form_model[$modelid]['modelname'] . ' - 会员中心 - ' . $this->site_config['site_name'],
			'fields' => $this->get_data_fields($this->form_model[$modelid]['fields'], $data),
		));
		$this->view->display('member/form_show.html');
	}
	 
}