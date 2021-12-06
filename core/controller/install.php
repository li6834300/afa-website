<?php

class install extends Base{
    public $link;
	public function __construct() {
		if (!is_writable(DATA_DIR)) {
		    exit('data 目录没有写入权限，无法进行安装！');
		}
		if (file_exists(DATA_DIR . 'install.lock')) {
		    exit('网站管理系统安装程序已锁定。如果要重新安装，请删除<b>/data/install.lock</b>文件！');
        }
	}
	
	public function indexAction() {
    	 include $this->install_tpl('1'); 
	}
	
	public function step2Action() {
		if (PHP_VERSION < '5.2.0') {
             $error = 'PHP版本低于5.2，无法进行安装！';
		}
		if (!function_exists("session_start")) {
             $error = '系统不支持session，无法进行安装！';
		}
		if (!extension_loaded('mysqli')) {
             $error = '不支持mysql，无法进行安装！';
		}
		if (!extension_loaded('PDO')) {
             $error = '不支持PDO，无法进行安装！';
		}
		if (!extension_loaded('pdo_mysql')) {
             $error = '没有开启pdo_mysql，无法进行安装！';
		}
		if (!function_exists('imagejpeg') || !function_exists('imagegif') || !function_exists('imagepng')) {
             $error = '不支持GD库，无法进行安装！';
		}
		if (!function_exists('json_decode')) {
             $error = '不支持JSON，无法进行安装！';
		}
		include $this->install_tpl('2');
	}
	
	public function step3Action() {
		function dexit($msg) {
                	echo '<script>alert("' . $msg . '");window.history.back();</script>';
					exit;
		}
		
		extract(Request::post('data'));
		if (!preg_match('/^[a-z0-9]+$/i', $admin_pass) || strlen($admin_pass) < 5) dexit('请填写正确的后台帐号'. $admin_pass);
		if (strlen($admin_pass) < 5) dexit('后台密码最少5位');
		@$link = mysqli_connect($host, $username, $password) OR  dexit('无法连接到数据库，请检查数据库配置信息');
		$this->link = $link;
		$dbname or dexit('连接正常\n\n不过您没有填写数据库名');
		if (!mysqli_select_db($link,$dbname)) {
            if (!mysqli_query($link,"CREATE DATABASE " . $dbname)) dexit('无权限创建数据库\n\n请通过其他方式建立数据库');
		}
		mysqli_query($link, 'SET NAMES utf8');
		mysqli_query($link, "SET sql_mode=''");
    	$arr = explode(':',$host);
		$host = $arr['0'];
		$port = isset($arr['1']) ? $arr['1'] : '3306';
		$dbconfig = array(
							'host'	=>	$host,
							'username'	=>	$username,
							'password'	=>	$password,
							'dbname'	=>	$dbname,
							'prefix'	=>	$prefix,
							'port'	=>	$port,
							'charset'	=>	'utf8',
		);
		
		$dbconfig_arr = var_export($dbconfig,true);
		$dbconfig_txt = "<?php" . PHP_EOL . "if (!defined('IN_XIAOCMS')) exit();" . PHP_EOL . "return " . $dbconfig_arr. ";";
		if(!file_put_contents(DATA_DIR . 'config' . DS . 'database.ini.php', $dbconfig_txt))  dexit('数据库配置文件保存失败，请检查文件权限！');
		$sql      = file_get_contents(DATA_DIR . 'install/xiaocms.sql');		
		$sql      = str_replace(array('xiao_', 'admin_name', 'admin_pass'), array($prefix, $admin_name, md5(md5($admin_pass))), $sql);
		$this->install_sql_execute($sql);
		if ($import) {
		$sql = file_get_contents(DATA_DIR . 'install/xiaocms_data.sql');
		$sql = str_replace('xiao_', $prefix, $sql);
		$this->install_sql_execute($sql);
		}
        $adminurl = $this->get_http_host() . $this->get_base_url();
		include $this->install_tpl('3');
	} 
	
    public function testAction() {
    	if (!Request::post('data')) {
    		exit('0');
    	}
		extract(Request::post('data'));
    	@$link = mysqli_connect($host, $username, $password) OR die('2');
		if (!mysqli_select_db($link,$dbname )) {
			if (!mysqli_query($link, "CREATE DATABASE " . $dbname)) {
			exit('3');
			}
		}
		$tableprefix   = array();
    	$query    = mysqli_query($link, "SHOW TABLES FROM $dbname");
    	while ($r = mysqli_fetch_row($query)) {
			$tableprefix[] = $r[0];
    	}
    	if (is_array($tableprefix) && in_array($prefix . 'content', $tableprefix)) {
			exit('4');
		}
    	exit('1');
    }
  
	private function install_sql_execute($sql) {
		$sql = str_replace("\r", "\n", $sql);
		$ret  = array(); 
		$num  = 0;
		$qarr = explode(";\n", trim($sql));
		foreach($qarr as $query){
			$queries = explode('\n', trim($query)); 
			foreach($queries as $query) {
				$ret[$num] .= $query[0] == '#' || $query[0].$query[1] == '--' ? '' : $query; 
			}
			$num++; 
		}
		foreach($ret as $query) {  
			if(trim($query) != ''){ 
				mysqli_query($this->link,$query) or die('数据导入出错<hr>' . mysqli_error() . '<br>SQL语句：<br>' . $query);
			} 
		}
		file_put_contents(DATA_DIR . 'install.lock', 'Powered by cms:'.time());
	}
	
    protected function install_tpl($file) {
        return  XIAOCMS_PATH.'data/install'.DS.$file.'.tpl.php';
    }
	
}