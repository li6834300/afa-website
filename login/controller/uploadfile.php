<?php

class uploadfile extends Admin {
    
    protected $dir;
    private $upconfig;
   
    public function __construct() {
		parent::__construct();
		$dir = 'data/upload';
        $this->dir = $dir;
$config = <<<EOT
{
    /* 上传图片配置项 */
    "imageActionName": "uploadimage", /* 执行上传图片的action名称 */
    "imageFieldName": "upfile", /* 提交的图片表单名称 */
    "imageMaxSize": 2048000, /* 上传大小限制，单位B */
    "imageAllowFiles": [".png", ".jpg", ".jpeg", ".gif", ".bmp"], /* 上传图片格式显示 */
    "imageCompressEnable": false, /* 是否压缩图片,默认是true */
    "imageCompressBorder": 1920, /* 图片压缩最长边限制 */
    "imageInsertAlign": "none", /* 插入的图片浮动方式 */
    "imageUrlPrefix": "", /* 图片访问路径前缀 */
    "imagePathFormat": "{$dir}/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
                                /* {filename} 会替换成原文件名,配置这项需要注意中文乱码问题 */
                                /* {rand:6} 会替换成随机数,后面的数字是随机数的位数 */
                                /* {time} 会替换成时间戳 */
                                /* {yyyy} 会替换成四位年份 */
                                /* {yy} 会替换成两位年份 */
                                /* {mm} 会替换成两位月份 */
                                /* {dd} 会替换成两位日期 */
                                /* {hh} 会替换成两位小时 */
                                /* {ii} 会替换成两位分钟 */
                                /* {ss} 会替换成两位秒 */
                                /* 非法字符 \ : * ? " < > | */
                                /* 具请体看线上文档: fex.baidu.com/ueditor/#use-format_upload_filename */

    /* 涂鸦图片上传配置项 */
    "scrawlActionName": "uploadscrawl", /* 执行上传涂鸦的action名称 */
    "scrawlFieldName": "upfile", /* 提交的图片表单名称 */
    "scrawlPathFormat": "{$dir}/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
    "scrawlMaxSize": 2048000, /* 上传大小限制，单位B */
    "scrawlUrlPrefix": "", /* 图片访问路径前缀 */
    "scrawlInsertAlign": "none",

    /* 截图工具上传 */
    "snapscreenActionName": "uploadimage", /* 执行上传截图的action名称 */
    "snapscreenPathFormat": "{$dir}/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
    "snapscreenUrlPrefix": "", /* 图片访问路径前缀 */
    "snapscreenInsertAlign": "none", /* 插入的图片浮动方式 */

    /* 抓取远程图片配置 */
    "catcherLocalDomain": ["127.0.0.1", "localhost", "img.baidu.com"],
    "catcherActionName": "catchimage", /* 执行抓取远程图片的action名称 */
    "catcherFieldName": "source", /* 提交的图片列表表单名称 */
    "catcherPathFormat": "$dir/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
    "catcherUrlPrefix": "", /* 图片访问路径前缀 */
    "catcherMaxSize": 2048000, /* 上传大小限制，单位B */
    "catcherAllowFiles": [".png", ".jpg", ".jpeg", ".gif", ".bmp"], /* 抓取图片格式显示 */

    /* 上传视频配置 */
    "videoActionName": "uploadvideo", /* 执行上传视频的action名称 */
    "videoFieldName": "upfile", /* 提交的视频表单名称 */
    "videoPathFormat": "$dir/video/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
    "videoUrlPrefix": "", /* 视频访问路径前缀 */
    "videoMaxSize": 102400000, /* 上传大小限制，单位B，默认100MB */
    "videoAllowFiles": [
        ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
        ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid"], /* 上传视频格式显示 */

    /* 上传文件配置 */
    "fileActionName": "uploadfile", /* controller里,执行上传视频的action名称 */
    "fileFieldName": "upfile", /* 提交的文件表单名称 */
    "filePathFormat": "$dir/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
    "fileUrlPrefix": "", /* 文件访问路径前缀 */
    "fileMaxSize": 51200000, /* 上传大小限制，单位B，默认50MB */
    "fileAllowFiles": [
        ".png", ".jpg", ".jpeg", ".gif", ".bmp",
        ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
        ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid",
        ".rar", ".zip", ".tar", ".gz", ".7z", ".bz2", ".cab", ".iso",
        ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf", ".txt", ".md", ".xml"
    ], /* 上传文件格式显示 */

    /* 列出指定目录下的图片 */
    "imageManagerActionName": "listimage", /* 执行图片管理的action名称 */
    "imageManagerListPath": "$dir/image/", /* 指定要列出图片的目录 */
    "imageManagerListSize": 20, /* 每次列出文件数量 */
    "imageManagerUrlPrefix": "", /* 图片访问路径前缀 */
    "imageManagerInsertAlign": "none", /* 插入的图片浮动方式 */
    "imageManagerAllowFiles": [".png", ".jpg", ".jpeg", ".gif", ".bmp"], /* 列出的文件类型 */

    /* 列出指定目录下的文件 */
    "fileManagerActionName": "listfile", /* 执行文件管理的action名称 */
    "fileManagerListPath": "$dir/file/", /* 指定要列出文件的目录 */
    "fileManagerUrlPrefix": "", /* 文件访问路径前缀 */
    "fileManagerListSize": 20, /* 每次列出文件数量 */
    "fileManagerAllowFiles": [
        ".png", ".jpg", ".jpeg", ".gif", ".bmp",
        ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
        ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid",
        ".rar", ".zip", ".tar", ".gz", ".7z", ".bz2", ".cab", ".iso",
        ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf", ".txt", ".md", ".xml"
    ] /* 列出的文件类型 */

}
EOT;

        $this->upconfig = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", $config), true); // 清除注释并转化为php数组
	}

	public function ueditorAction() {
		$action = Request::get('action');
		switch ($action) {
			case 'config':
        	echo json_encode($this->upconfig);
			break;
			/* 上传图片 */
			case 'uploadimage':
			$this->uploadimage();
			break;
			/* 上传涂鸦 */
			case 'uploadscrawl':
			$this->uploadscrawl();
			break;
			/* 上传视频 */
			case 'uploadvideo':
			$this->uploadvideo();
			break;
			/* 上传文件 */
			case 'uploadfile':
			$this->uploadfile();
			break;
			/* 列出图片 */
			case 'listimage':
			$this->listimage();
			break;
			/* 列出文件 */
			case 'listfile':
			$this->listfile();
			break;
			/* 抓取远程文件 */
			case 'catchimage':
			$this->catchimage();
			break;

			default:
			$result = json_encode(array(
				'state'=> '请求地址出错'
				));
			break;
		}
	}
 
    /**
     * 上传图片
     */
    private function uploadimage()
    {

        $config = array(
            "pathFormat" => $this->upconfig['imagePathFormat'],
            "maxSize" => $this->upconfig['imageMaxSize'],
            "allowFiles" => $this->upconfig['imageAllowFiles']
            );
        $fieldName = $this->upconfig['imageFieldName'];
        /* 生成上传实例对象并完成上传 */
        $base64 = "upload";
        $this->uploader($fieldName, $config, $base64);
    }

    /**
     * 上传涂鸦
     */
    private function uploadscrawl()
    {

        $config = array(
            "pathFormat" => $this->upconfig['scrawlPathFormat'],
            "maxSize" => $this->upconfig['scrawlMaxSize'],
            "allowFiles" => $this->upconfig['scrawlAllowFiles'],
            "oriName" => "scrawl.png"
        );
        $fieldName = $this->upconfig['scrawlFieldName'];

        $base64 = "base64";
        $this->uploader($fieldName, $config, $base64);
    }

    /**
     * 上传视频
     */
    private function uploadvideo()
    {

        $config = array(
            "pathFormat" => $this->upconfig['videoPathFormat'],
            "maxSize" => $this->upconfig['videoMaxSize'],
            "allowFiles" => $this->upconfig['videoAllowFiles']
        );
        $fieldName = $this->upconfig['videoFieldName'];

        $base64 = 'upload';
        $this->uploader($fieldName, $config, $base64);
    }


    /**
     * 上传文件
     */
    private function uploadfile()
    {

        $config = array(
            "pathFormat" => $this->upconfig['filePathFormat'],
            "maxSize" => $this->upconfig['fileMaxSize'],
            "allowFiles" => $this->upconfig['fileAllowFiles']
        );
        $fieldName = $this->upconfig['fileFieldName'];
        $base64 = 'upload';
        $this->uploader($fieldName, $config, $base64);
    }



    /**
     * 抓取远程文件
     */
    public function catchimage()
    {
        set_time_limit(0);
        $config = array(
            "pathFormat" => $this->upconfig['catcherPathFormat'],
            "maxSize" => $this->upconfig['catcherMaxSize'],
            "allowFiles" => $this->upconfig['catcherAllowFiles'],
            "oriName" => "remote.png"
            );
        $fieldName = $this->upconfig['catcherFieldName'];

        $list = array();
        if (isset($_POST[$fieldName])) {
            $source = $_POST[$fieldName];
        } else {
            $source = $_GET[$fieldName];
        }
        foreach ($source as $imgUrl) {
            $item = new Uploader($imgUrl, $config, "remote");
            $info = $item->getFileInfo();
            array_push($list, array(
                "state" => $info["state"],
                "url" => $info["url"],
                "size" => $info["size"],
                "title" => htmlspecialchars($info["title"]),
                "original" => htmlspecialchars($info["original"]),
                "source" => htmlspecialchars($imgUrl)
                ));
        }

        echo json_encode(array(
            'state'=> count($list) ? 'SUCCESS':'ERROR',
            'list'=> $list
            ));
    }


    /**
     * 上传
     */
    private function uploader($fieldName, $config, $base64 = "upload")
    {
        $up = new Uploader($fieldName, $config, $base64);
        $r = $up->getFileInfo();
        if (in_array($r['type'], array('.jpg', '.gif', '.png', '.bmp'))) {
            $this->watermark(XIAOCMS_PATH.$r['url']);
        }
        echo json_encode($r);
    }


    /**
     * 上传
     */
    private function getlist($allowFiles, $listSize, $path)
    {
        $allowFiles = substr(str_replace(".", "|", join("", $allowFiles)), 1);

        /* 获取参数 */
        $size = isset($_GET['size']) ? htmlspecialchars($_GET['size']) : $listSize;
        $start = isset($_GET['start']) ? htmlspecialchars($_GET['start']) : 0;
        $end = $start + $size;

        /* 获取文件列表 */
        $path = $_SERVER['DOCUMENT_ROOT'] . (substr($path, 0, 1) == "/" ? "":"/") . $path;
        $files = getfiles($path, $allowFiles);
        if (!count($files)) {
            return json_encode(array(
                "state" => "no match file",
                "list" => array(),
                "start" => $start,
                "total" => count($files)
                ));
        }

        /* 获取指定范围的列表 */
        $len = count($files);
        for ($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--){
            $list[] = $files[$i];
        }
 
        /* 返回数据 */
        $result = json_encode(array(
            "state" => "SUCCESS",
            "list" => $list,
            "start" => $start,
            "total" => count($files)
            ));

        echo $result;

    }

    /**
     * 上传文件
     */
    private function listfile()
    {
        $allowFiles = $this->upconfig['fileManagerAllowFiles'];
        $listSize = $this->upconfig['fileManagerListSize'];
        $path = $this->upconfig['fileManagerListPath'];
        $this->getlist($allowFiles, $listSize, $path);
    }

    /**
     * 上传文件
     */
    private function listimage()
    {
        $allowFiles = $this->upconfig['imageManagerAllowFiles'];
        $listSize = $this->upconfig['imageManagerListSize'];
        $path = $this->upconfig['imageManagerListPath'];
        $this->getlist($allowFiles, $listSize, $path);
    }

	public function kindeditor_filemanagerAction() {
		$root_path = XIAOCMS_PATH . $this->dir.'/';
		$root_url  = SITE_PATH . $this->dir.'/';
		$ext_arr = array('gif', 'jpg', 'jpeg', 'png', 'bmp');
		$dir_name = Request::get('dir') == 'image' ? 'image' : 'file';
		if (!in_array($dir_name, array('', 'image', 'flash', 'media', 'file'))) {
			echo "Invalid Directory name.";
			exit;
		}
		if ($dir_name !== '') {
			$root_path .= $dir_name . "/";
			$root_url .= $dir_name . "/";
			if (!file_exists($root_path)) {
				mkdir($root_path);
			}
		}
		if (empty($_GET['path'])) {
			$current_path = realpath($root_path) . '/';
			$current_url = $root_url;
			$current_dir_path = '';
			$moveup_dir_path = '';
		} else {
			$current_path = realpath($root_path) . '/' . $_GET['path'];
			$current_url = $root_url . $_GET['path'];
			$current_dir_path = $_GET['path'];
			$moveup_dir_path = preg_replace('/(.*?)[^\/]+\/$/', '$1', $current_dir_path);
		}
		$order = empty($_GET['order']) ? 'name' : strtolower($_GET['order']);
		if (preg_match('/\.\./', $current_path)) {
			echo 'Access is not allowed.';
			exit;
		}
		if (!preg_match('/\/$/', $current_path)) {
			echo 'Parameter is not valid.';
			exit;
		}
		if (!file_exists($current_path) || !is_dir($current_path)) {
			echo 'Directory does not exist.';
			exit;
		}
		$file_list = array();
		if ($handle = opendir($current_path)) {
			$i = 0;
			while (false !== ($filename = readdir($handle))) {
				if ($filename{0} == '.') continue;
				$file = $current_path . $filename;
				if (is_dir($file)) {
					$file_list[$i]['is_dir'] = true; 
					$file_list[$i]['has_file'] = (count(scandir($file)) > 2);
					$file_list[$i]['filesize'] = 0;
					$file_list[$i]['is_photo'] = false;
					$file_list[$i]['filetype'] = '';
				} else {
					$file_list[$i]['is_dir'] = false;
					$file_list[$i]['has_file'] = false;
					$file_list[$i]['filesize'] = filesize($file);
					$file_list[$i]['dir_path'] = '';
					$file_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
					$file_list[$i]['is_photo'] = in_array($file_ext, $ext_arr);
					$file_list[$i]['filetype'] = $file_ext;
				}
				$file_list[$i]['filename'] = $filename;
				$file_list[$i]['datetime'] = date('Y-m-d H:i:s', filemtime($file));
				$i++;
			}
			closedir($handle);
		}

		function cmp_func($a, $b) {
			global $order;
			if ($a['is_dir'] && !$b['is_dir']) {
				return -1;
			} else if (!$a['is_dir'] && $b['is_dir']) {
				return 1;
			} else {
				if ($order == 'size') {
					if ($a['filesize'] > $b['filesize']) {
						return 1;
					} else if ($a['filesize'] < $b['filesize']) {
						return -1;
					} else {
						return 0;
					}
				} else if ($order == 'type') {
					return strcmp($a['filetype'], $b['filetype']);
				} else {
					return strcmp($a['filename'], $b['filename']);
				}
			}
		}
		usort($file_list, 'cmp_func');
		$result = array();
		$result['moveup_dir_path'] = $moveup_dir_path;
		$result['current_dir_path'] = $current_dir_path;
		$result['current_url'] = $current_url;
		$result['total_count'] = count($file_list);
		$result['file_list'] = $file_list;
		echo json_encode($result);
	}
	
	/**
	 * 附件管理
	 */
	public function managerAction() {
	    $iframe = Request::get('iframe') ? 1 : 0;
        $dir    = Request::get('dir') ? Request::get('dir') : '';
		$dir = str_replace(array('..\\', '../', './', '.\\'), '', trim($dir));
        $dir    = substr($dir, 0, 1) == '/' ? substr($dir, 1) : $dir;
        $dir    = str_replace(array('\\', '//'), DS, $dir);
        $file_list = glob(XIAOCMS_PATH.$this->dir.'/' . $dir.'*');
		$data =$list = array();
        foreach($file_list as $v) {
            $data[] = basename ($v);
        }
        if ($data) {
            foreach ($data as $t) {
                if ($t == 'index.html') continue;
                $path = $dir . $t . '/';
				if (is_dir(XIAOCMS_PATH . $this->dir.'/' . $path))
				{
                   $dirlist[] = array(
                    'name'     => $t, 
                    'url'      =>  url('uploadfile/manager', array('dir'=>$path)) ,
					);
				}
				else{
                    $ext  = fileext($t);
		    		if($ext=='gif' ||$ext=='png' || $ext=='jpg' )
	    			$ico  = 'pic.gif' ;
			    	else 
			    	$ico  = 'file.gif' ;
			    	$list[] = array(
                          'name'     => $t, 
                          'ico'      => $ico,
			    	);
		 		}
			}
        }


        $pdir   = url('uploadfile/manager', array('dir'=>str_replace(basename($dir), '', $dir)));
        $dir    = $this->dir.'/'. $dir;
        include $this->admin_tpl('upload_manager');
	}
 	//删除文件
	public function delAction() {
		//文件路径
		$filename = '../'.$_GET[del];
		//删除
		if(file_exists($filename)){
			unlink($filename);
			$this->show_message('删除成功',1);  
		}else{
			$this->show_message('删除失败',2, url('uploadfile/manager'));
		}
	}
	
	/**
     * 编辑器上传
     */
	public function kindeditor_uploadAction() {
        $ext_arr = array(
           	'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
          	'flash' => array('swf', 'flv'),
          	'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
          	'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2'),
         );
        $dir_name = Request::get('dir') ? Request::get('dir')  : 'image';
        if (empty($ext_arr[$dir_name])) {
		    echo json_encode(array('error' => 1, 'message' => '目录名不正确。'));
			exit;
        }
		$data = $this->upload('imgFile', $ext_arr[$dir_name]);
		if (!$data['result']) {
			echo json_encode(array('error' => 1, 'message' => ''));exit;
		} else {
			echo json_encode(array('error' => 0, 'url' => $data['path']));exit;
		}
		
	}

    /**
     * 文件上传
     */
    private function upload($fields, $type, $size = 100) {
		$upload   = xiaocms::load_class('Upload');
        $ext      = strtolower(substr(strrchr($_FILES[$fields]['name'], '.'), 1));
        if (in_array($ext, array('jpg','jpeg','bmp','png','gif'))) {
            $dir  = 'image';
        } else {
            $dir  = 'file';
        }
        $path    = $this->dir . '/' . $dir . '/' . date('Ym') . '/';
		if (!is_dir(XIAOCMS_PATH.$path)) mkdirs(XIAOCMS_PATH.$path);
        $file     = $_FILES[$fields]['name'];
	    $filename = md5(time() . $_FILES[$fields]['name']) . '.' . $ext;
		$filenpath = $path.$filename;
        $result   = $upload->set_limit_size(1024*1024*$size)->set_limit_type($type)->upload($_FILES[$fields],XIAOCMS_PATH.$filenpath);
        if (in_array($ext, array('jpg', 'gif', 'png', 'bmp'))) {
		$this->watermark(XIAOCMS_PATH.$filenpath);
        }
        return array('result'=>$result, 'path'=>  SITE_PATH . $filenpath, 'file'=>$file , 'ext'=>$dir=='image' ? 1 : $ext);
    }
	
	
}
/**
 * 遍历获取目录下的指定类型的文件
 * @param $path
 * @param array $files
 * @return array
 */
function getfiles($path, $allowFiles, &$files = array())
{
    if (!is_dir($path)) return null;
    if(substr($path, strlen($path) - 1) != '/') $path .= '/';
    $handle = opendir($path);
    while (false !== ($file = readdir($handle))) {
        if ($file != '.' && $file != '..') {
            $path2 = $path . $file;
            if (is_dir($path2)) {
                getfiles($path2, $allowFiles, $files);
            } else {
                if (preg_match("/\.(".$allowFiles.")$/i", $file)) {
                    $files[] = array(
                        'url'=> substr($path2, strlen($_SERVER['DOCUMENT_ROOT'])),
                        'mtime'=> filemtime($path2)
                    );
                }
            }
        }
    }
    return $files;
}