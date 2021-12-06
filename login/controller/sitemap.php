<?php

class sitemap extends Admin {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * 生成首页
	 */
	public function indexAction() {
		$num    = 3000; // 生成的数量
		$data = $this->db->setTableName('content')->order('id DESC')->fields('time,id,catid')->limit(0, $num)->getAll('status!=0', null, null);
		foreach ($data as $key => $t) {
			$data[$key]['url'] = $this->view->get_show_url($t);
		}
 
    	$xml = '<?xml version="1.0" encoding="UTF-8"?>';
    	$xml .= "<urlset>";
    	foreach ($data as $v){
        	$xml .= "<url><loc>http://";
        	$xml .= $_SERVER['HTTP_HOST'].$v['url'];
        	$xml .= "</loc><lastmod>";
        	$xml .= date('Y-m-d', $v['time']);
        	$xml .= "</lastmod><changefreq>always</changefreq><priority>1.0</priority></url>";
    	}
    	$xml .= '</urlset>';
    	file_put_contents(XIAOCMS_PATH . 'sitemap.xml', $xml, LOCK_EX);

		$this->show_message("生成<a target='_blank' href='http://{$_SERVER['HTTP_HOST']}/sitemap.xml' >sitemap.xml</a> 成功", 1, '#');

	}

}