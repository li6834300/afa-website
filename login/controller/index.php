<?php

class index extends Admin {

    public function __construct() {
		parent::__construct();
	}

	public function indexAction() {
	    $left_width = $this->admin['left_width'] ? $this->admin['left_width']:150;
		include $this->admin_tpl('index');
	}
	
	public function myAction() {
	    $userid = $this->admin['userid'];
        if (Request::isPost()) {
            $data = Request::post('data');
			if (!empty($data['password'])) {
			if (strlen($data['password']) < 6) $this->show_message('密码最少6位数',2);
			$data['password'] = md5(md5($data['password']));
			}
			else {
			unset ($data['password']);
			}
			if ($data['auth']) unset($data['auth']);
 			if ($data['roleid']) unset($data['roleid']);
            $this->db->setTableName('admin')->update($data, 'userid=?', $userid);
            $data = array();
                foreach ($this->db->setTableName('admin')->getAll() as $t) {
	                unset ($t['password']);
	                $data[$t['userid']] = $t;
                }
            set_cache('admin', $data);
            $this->show_message('修改成功', 1);
        }
	    $data   = $this->db->setTableName('admin')->find($userid);
        include $this->admin_tpl('my');
	}

	public function treeAction() {
		$tree =  xiaocms::load_class('Tree');
		$cats =  $this->db->setTableName('category')->getAll(null, null, array('catid', 'catname','parentid','typeid','modelid','http'),'listorder DESC,catid ASC');
		$categorys = array();
		if(!empty($cats)) {
			foreach($cats as $r) {
				if($r['typeid']==1) {
					$r['icon_type'] = '';
					$r['urla'] = '?c=content&catid='.$r['catid'];
				} elseif ($r['typeid']==2) {
					$r['icon_type'] = '';
					$r['urla'] = '?c=category&a=edit&catid='.$r['catid'];
				} elseif ($r['typeid']==4) {
					$r['icon_type'] = '';
					$r['urla'] = url('form/index', array('modelid'=>$r['modelid']));
				} else {
					$r['icon_type'] = '';
					$r['catname'] = '<span class="red">'.$r['catname'].'</span>';
					$r['urla'] = '?c=category&a=edit&catid='.$r['catid'];
				}
				$categorys[$r['catid']] = $r;
			}
		}
		if(!empty($categorys)) {
			$tree->init($categorys);
			$strs = "<a href='\$urla' target='right' class='item expandable'><i class='file'></i><span class='txt \$icon_type'>\$catname</span></a>";
			$strs2 = "<a href='\$urla' target='right' class='item expandable expanded'><i class='folder'></i><span class='txt \$icon_type'>\$catname</span></a>";
			$categorys = $tree->get_treeview(0,'category_tree',$strs,$strs2,0,'tree');
		} else {
			$categorys = '没有分类请添加或刷新';
		}

	    if($this->menu('diytable-index')) { 
		$diy_model  = get_cache('diy_model');
		if ($diy_model) {
	    	$diytable .= '<div class="toolbar bg2"><i class="arrow-down"></i><span id="leftname"><a href="?c=models&typeid=4" target="right">自定义表</a></span></div>';		
	    	$diytable .= '<ul  class="tree">';
	    	foreach ($diy_model as $t) {
				$id   = $t['modelid'];
				$url  = url('diytable/index', array('modelid'=>$id));
				$diytable .= "<li><a href='".$url."' target='right' class='item expandable'><i class='file'></i><span class='txt'>". $t['modelname']."</span></a></li>";
		    }
	    	$diytable .='</ul>';
		}
		}
	    include $this->admin_tpl('category_tree');
	}
	
	public function mainAction() {
		$sysinfo['fileupload']     = @ini_get('file_uploads') ? ini_get('upload_max_filesize') :'unknown';
		$data = http_build_query(array('domain'=> $_SERVER['HTTP_HOST'],'version'=>'x1','release'=>XIAOCMS_RELEASE,'time'=> date("Y-m-d")));
		$client_url = ''.$data;
	    include $this->admin_tpl('main');
	}
	
	public function configAction() {
	    $data         =  $this->site_config;
        if (Request::isPost()) {
            $configdata = Request::post('data');
			$configdata['rand_code']= md5(microtime());
			$config_arr = var_export($configdata,true);
			$config_txt = "<?php" . PHP_EOL . "if (!defined('IN_XIAOCMS')) exit();" . PHP_EOL . "return " . $config_arr. ";";
            file_put_contents(DATA_DIR . 'config' . DS . 'config.ini.php', $config_txt);
            $this->show_message('修改成功', 1, url('index/config', array('type'=>Request::get('type'))));
        }
        $file_list=glob(TEMPLATE_DIR.'*');
		$arr= array();
        foreach($file_list as $v) {
	       	if(is_dir($v))
            $arr[] = basename ($v);
        }
        $theme = array_diff($arr, array('mobile'));
	    $type  = Request::get('type') ? Request::get('type') : 1;
		$membermodel  = get_cache('member_model');
        include $this->admin_tpl('config');
	}

    public function test_mailAction() {
        $data         =  $this->site_config;
        extract($data);
        $mailsubject = "发送成功 来自：系统测试邮件";//邮件主题
        $mailbody = " 恭喜您 发送成功 来自：系统测试邮件";//邮件内容
        $smtp =  xiaocms::load_class('Email');
        $mailtype = "TXT";//邮件格式（HTML/TXT）,TXT为文本邮件
        $smtp->config($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);
        if ($smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype)) {
            echo '恭喜！发送成功';
        } else {
            echo '发送失败，请检查配置，保存后提交测试';
        }

    }

    public function cacheAction() {
    	$modules = array(
	        0   => array('模型缓存更新成功..........',  'models',       'cache'),
	        1   => array('栏目缓存更新成功..........',  'category',    'cache'),
	        2   => array('区块缓存更新成功..........',  'block',       'cache'),
	        3   => array('模板缓存更新成功..........',  'template',       'cache'),
	        4   => array('关键字内链缓存更新成功..........',  'keylink',       'cache'),
	    );
	    if (Request::get('show')) {
	        $id    = $_GET['id'] ? intval($_GET['id']) : 0;
	        $m = $modules[$id];
	        $c     = $m[1];
	        $a     = $m[2] . 'Action';
	        $id ++;
			if (!empty($m)) {
				echo '<script type="text/javascript">window.parent.frames["hidden"].location="index.php?c='. $c .'&a=cache";</script>';
				echo '<script type="text/javascript">window.parent.addtext("<li>' .  $m[0] . '</li>");</script>';
				$this->show_message($msg, 1, url('index/cache/', array('show'=>1,'id'=>$id)), 100);		
			} else {
	            echo '<script type="text/javascript">window.parent.addtext("<li style=\"color: red;\">全站缓存更新成功</li><li><a style=\"color: #090;font-weight: 700;\" href=\"?c=index&a=main\" >点击返回后台主页</li>");</script>';
			}
	    } else {
	        include $this->admin_tpl('cache');
	    }
	}

}