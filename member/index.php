<?php
/**
 * 企业建站版
 */
define('XIAOCMS_MEMBER',   dirname(__FILE__) . DIRECTORY_SEPARATOR);//定义后台路径
define('CONTROLLER_DIR',     XIAOCMS_MEMBER . 'controller' . DIRECTORY_SEPARATOR);   //controller目录的路径
define('XIAOCMS_PATH',   dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);//定义项目目录
include XIAOCMS_PATH . 'core/xiaocms.php'; //加载框架核心
xiaocms::load_file(CONTROLLER_DIR . 'Member.class.php');//加载后台公共控制器
xiaocms::run();