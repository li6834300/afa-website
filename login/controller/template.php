<?php

class template extends Admin {
    
    private $dir;
   	private $file_info;

    public function __construct() {
		parent::__construct();
		$this->dir = TEMPLATE_DIR;
		$dir = Request::get('dir');
		if($dir){
			$dir = strstr($dir,'\\',true);
 			if (file_exists($this->dir.$dir.'\config.php')) {
				$this->file_info = include $this->dir.$dir.'\config.php';
 			}
		}else{
			$this->file_info = include $this->dir.'\config.php';
		}
	}
    
    public function indexAction() {
        $dir    = Request::get('dir') ? urldecode(Request::get('dir')) : '';
		$dir = str_replace(array('..\\', '../', './', '.\\'), '', trim($dir));
        $dir    = substr($dir, 0, 1) == '/' ? substr($dir, 1) : $dir;
        $dir    = str_replace(array('\\', '//'), DS, $dir);
        $filepath = $this->dir.$dir;
        $list = glob($filepath.DS.'*');
		$local = str_replace(XIAOCMS_PATH, '', $filepath);	
		$encode_local = str_replace(array('/', '\\'), '|', $local);
		$file_explan = $this->file_info['file_explan'];
        include $this->admin_tpl('template_list');

    }

	public function updatefilenameAction() {
		$file_explan = Request::post('file_explan') ? Request::post('file_explan') : '';
		$dir = key($file_explan);
		$a = explode('|', $dir);
		$dir = $a[1];
		$file_info = include $this->dir.$dir.'\config.php';
		if (!isset($file_info['file_explan'])) $file_info['file_explan'] = array();
		$file_info['file_explan'] = array_merge($file_info['file_explan'], $file_explan);
 		@file_put_contents($this->dir.'\\'.$dir.'\config.php', '<?php return '.var_export($file_info, true).';?>');
		$this->show_message('ζδΊ€ζε',1,1);
	}
	
    public function editAction() {
        $dir    = Request::get('dir') ? urldecode(Request::get('dir')) : '';
		$dir = str_replace(array('..\\', '../', './', '.\\'), '', trim($dir));
        $dir    = substr($dir, 0, 1) == '/' ? substr($dir, 1) : $dir;
        $dir    = str_replace(array('\\', '//'), DS, $dir);
        $filename  = urldecode(Request::get('file'));
        $filepath = $this->dir . $dir.DS.$filename;
		$ext  = fileext($filepath);
		if (!in_array($ext, array('html', 'css', 'js', 'txt'))) $this->show_message('ζδ»ΆεεηΌδΈε―Ή',2,1);

		$local = str_replace(XIAOCMS_PATH, '', $filepath);
		if (!is_file($filepath)) $this->show_message($dir.$filename.'θ―₯ζδ»ΆδΈε­ε¨',2, url('template', array('dir'=>$dir)));
		if (Request::isPost()) {
		    file_put_contents($filepath, htmlspecialchars_decode(Request::post('file_content')), LOCK_EX);
		    $this->show_message('ζδΊ€ζε',1,url('template', array('dir'=>$dir)));
		}
        $filecontent = htmlspecialchars(file_get_contents($filepath));
        include $this->admin_tpl('template_add');
    }
	
	public function addAction() {
        $dir    = Request::get('dir') ? urldecode(Request::get('dir')) : '';
		$dir = str_replace(array('..\\', '../', './', '.\\'), '', trim($dir));
        $dir    = substr($dir, 0, 1) == '/' ? substr($dir, 1) : $dir;
        $dir    = str_replace(array('\\', '//'), DS, $dir);
        $filepath = $this->dir . $dir;
		$local = str_replace(XIAOCMS_PATH, '', $filepath);
		$filecontent = '';
		if (Request::isPost()) {
		    $filename = Request::post('file_name');
    		if (file_exists($filepath . $filename)) {
    		$this->show_message('θ―₯ζδ»Άε·²η»ε­ε¨' ,2,1);
    		}
			$ext  = fileext($filename);
			if (!in_array($ext, array('html', 'css', 'js' , 'txt'))) $this->show_message('ζδ»ΆεεηΌδΈε―Ή',2,1);
			file_put_contents($filepath . $filename, htmlspecialchars_decode(Request::post('file_content')), LOCK_EX);
		    $this->show_message('ζδΊ€ζε',1, url('template', array('dir'=>$dir)) );
		}
        include $this->admin_tpl('template_add');
    }

	public function delAction() {
        $dir    = Request::get('dir') ? urldecode(Request::get('dir')) : '';
		$dir = str_replace(array('..\\', '../', './', '.\\'), '', trim($dir));
        $dir    = substr($dir, 0, 1) == '/' ? substr($dir, 1) : $dir;
        $dir    = str_replace(array('\\', '//'), DS, $dir);
        $filename  = urldecode(Request::get('file'));
        $filepath = $this->dir . $dir.$filename;
//δΈΊδΊιθ――ε ι€ζ¨‘ζΏεζ³¨ιζ		
//    	if (@unlink($filepath))
//		$this->show_message('ε ι€ζε',1);
//		else
//		$this->show_message('ε ι€ε€±θ΄₯',2, url('template', array('dir'=>$dir)));
	}
	
	public function cacheAction() {
		$dir = DATA_DIR . 'tplcache';
	    delete_dir($dir);
		if (!file_exists($dir)) mkdirs($dir);
	    $this->show_message('ηΌε­ζ΄ζ°ζε',1, url('template/index'));
	}
	
}