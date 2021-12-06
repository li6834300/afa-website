<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

 <head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <title><?php echo $this->site_config['site_name'];?>- 后台管理中心</title>
 <script src="../core/img/ueditor/third-party/jquery-1.10.2.min.js"></script>
 <style type="text/css">
body, h1, h2, h3, ul, li, p {
	margin: 0;
	padding: 0;
	font-size: 12px;
	font-family: arial, \5b8b\4f53;
}
body {
	position: relative;
	scroll: no;
	overflow: hidden;
}
div, img {
	border: 0;
}
ul li {
	list-style: none;
}
a {
	color: #333;
	text-decoration: none;
}
a:hover {
	color: #f60;
}
.fl {
	float: left;
}
.fr {
	float: right;
}
#head {
	background: #35495e;
	color: #fff;
	height: 40px;
	line-height: 40px;
	font-size: 14px;
	font-family: arial, \5FAE\8F6F\96C5\9ED1;
}
#head a {
	font-size: 14px;
	color: #fff;
	font-family: arial, \5FAE\8F6F\96C5\9ED1;
}
#head .logo {
	text-align: center;
	font-size: 24px;
	background: #ff6700;
	height: 40px;
width: <?php echo $left_width;
?>px;
	float: left;
	color: #fff
}
#head .fl {
	color: #fff;
	float: right;
	padding-right: 15px;
}
#head .user a {
	display: block;
	float: left;
	padding: 0 10px;
	height: 30px;
	line-height: 30px;
	margin: 5px 0px;
}
#head .user a:hover {
	background: #fff;
	color: #666;
	border-radius: 2px;
}
#menu {
	position: relative;
	float: left;
	margin-left: 10px;
}
#menu li {
	float: left;
	display: inline;
	position: relative;
	margin: 5px 2px;
}
#menu .subnav {
	width: 101px;
	display: none;
	position: absolute;
	top: 100%;
	left: -1px;
	padding: 2px 0 0 0px;
	margin-top: -3px;
	background: #fff;
	border: 1px solid #35495e;
	border-top: 0;
	border-radius: 0px 3px 3px 3px;
}
#menu .subnav li {
	width: 100%;
	display: block;
	margin: 0;
	padding: 1px 0;
}
#menu .subnav li a {
	display: block;
	margin: 0;
	padding-left: 12px;
	color: #333
}
#menu .m {
	float: left;
	display: block;
	color: #fff;
	text-align: center;
	height: 30px;
	line-height: 30px;
	padding: 0 12px
}
#menu li:hover .m, #menu .m:hover, #menu .focused {
	background: #fff;
	color: #333;
	border-radius: 2px;
}
#menu .focused a {
	color: #333;
}
#menu li:hover li a, #menu li li a {
	float: none;
	color: #333;
	height: 30px;
	line-height: 30px;
}
#menu li:hover li a:hover, #menu li:hover li:hover a, #menu li li a:hover {
	background: #317EAA;
	color: #fff;
	font-weight: normal;
}
#menu li:hover ul {
	display: block;
}
#left {
	border-right: 1px solid #ccc;
width:<?php echo $left_width;
?>px;
	position: absolute;
	top: 40px;
	left: 0;
}
#right2 {
margin-left:<?php echo $left_width;
?>px;
}
#home {
	overflow: hidden;
	clear: both;
	height: 32px;
	line-height: 32px;
	padding: 0 8px;
	border-bottom: 1px solid #ccc;
}
#position {
	background: url(./img/home.gif) center left no-repeat;
	padding-left: 18px;
	float: left
}
.toolbar {
	color: #666;
	height: 32px;
	line-height: 32px;
	padding: 0 8px;
	border-bottom: 1px solid #ccc;
}
.arrow-down {
	display: block;
	width: 16px;
	height: 16px;
	float: left;
	margin-top: 8px;
	background: url(./img/jt.png) center left no-repeat;
}
.refresh {
	display: block;
	width: 16px;
	height: 16px;
	float: left;
	cursor: default;
	margin-top: 8px;
	background: url(./img/refresh.png) center left no-repeat;
}
</style>
 </head>
 <body scroll="no">
<!--头部开始-->
<div id="head"> <a href="#">
  <div class="logo">网站管理系统</div>
  </a>
   <ul id="menu">
    <li><a href="<?php echo url('index/my') ;?>" target="right" class="m">设置</a>
       <ul class="subnav">
        <li><a href="<?php echo url('index/my') ;?>" target="right">我的账号</a></li>
        <?php if($this->menu('index-config') ) { ;?>
        <li><a href="<?php echo url('index/config', array('type'=>1)) ;?>"  target="right">系统设置</a></li>
        <?php } ;?>
        <?php if($this->menu('administrator-index')) { ;?>
        <li><a href="<?php echo url('administrator/index') ;?>"  target="right">账号管理</a></li>
        <?php } ;?>
        <li><a href="<?php echo url('index/cache') ;?>"  target="right">更新缓存</a></li>
        <?php if($this->menu('database-index')) { ;?>
        <li><a href="<?php echo url('database') ;?>"  target="right">数据备份</a></li>
        <?php } ;?>
        <?php if($this->menu('models-index')) { ;?>
        <li style="display:none;"><a href="<?php echo url('models') ;?>"  target="right">内容模型</a></li>
        <li><a href="<?php echo url('models', array('typeid'=>3)) ;?>"  target="right">表单模型</a></li>
        <li  style="display:none;"><a href="<?php echo url('models', array('typeid'=>4)) ;?>"  target="right">自定义表</a></li>
        <?php } ;?>
        
        <li style="display:none;"><a href="<?php echo url('keylink') ;?>"  target="right">内链关键字</a></li>
        <li style="display:none;"><a href="<?php echo url('sitemap') ;?>"  target="right">生成sitemap</a></li>
       <li style="display:none;"><a href="<?php echo url('uploadfile/manager') ;?>"  target="right">附件管理</a></li>
        
      </ul>
     </li>
    <?php if($this->menu('category-index')) { ;?>
    <li><a href="<?php echo url('category') ;?>"  target="right" class="m">栏目</a></li>
    <?php } ?>
    <?php if($this->menu('block-index')) { ;?>
    <li><a href="<?php echo url('block') ;?>"  target="right" class="m">区块</a></li>
    <?php } ?>
    <?php if (defined('XIAOCMS_MEMBER') && $this->menu('member-index')) {  ?>
    <li><a href="<?php echo url('member') ;?>"  target="right" class="m">会员</a></li>
    <?php } ?>
    
    <?php if($this->menu('template-index')) { ;?>
    <li><a href="<?php echo url('template') ;?>"  target="right" class="m">模板</a></li>
    <?php } ?>
    <?php if($this->site_config['diy_url']==2 && $this->menu('createhtml-index')) { ?>
    <li><a href="<?php echo url('createhtml') ;?>"  target="right" class="m">生成</a>
       <ul class="subnav">
        <li><a href="<?php echo url('createhtml') ;?>"  target="right">生成首页</a></li>
        <?php if($this->menu('createhtml-category')) { ;?>
        <li><a href="<?php echo url('createhtml/category') ;?>"  target="right">生成栏目页</a></li>
        <?php } ?>
        <?php if($this->menu('createhtml-show')) { ;?>
        <li><a href="<?php echo url('createhtml/show') ;?>"  target="right">生成内容页</a></li>
        <?php } ?>
      </ul>
     </li>
    <?php } ?>
  </ul>
   <!--账户信息-->
   <div class="fr">
    <div class="user"> <a href="<?php echo url('index/my') ;?>" target="right"><?php echo $this->admin['username'].' ( '.$this->admin['realname']; ?>）</a> <a href="javascript:;" onClick="logout();">退出</a> </div>
  </div>
 </div>
<!--头部结束-->
<div id="main"> 
   <!--左侧开始-->
   <div id="left">
    <div class="toolbar" id="t1" style=""> <i class="arrow-down"></i> <span id="leftname"><a href='<?php echo url('content'); ?>' target='right'>内容管理</a></span> <span class="fr"> <i onclick="refresh();" title="刷新菜单" class="refresh"></i> </span> </div>
    <iframe name="leftMain" id="leftMain" src="<?php echo url('index/tree'); ?>" frameborder="false" scrolling="auto" style="border:none" width="100%" height="600" allowtransparency="true"></iframe>
  </div>
   <!--左侧结束--> 
   <!--右侧开始-->
   <div id="right2">
    <div id="home">
       <div id="position">后台首页</div>
       <div class="fr"> <a href="../" title="网站首页"  target="_blank" >网站首页</a> <a href="<?php echo url('index/cache'); ?>" target="right" style="padding:0 10px;">更新缓存</a> </div>
     </div>
    <iframe name="right" id="right" src="<?php echo url('index/main'); ?>" frameborder="false" scrolling="auto" style="border:none;" width="100%" allowtransparency="true"></iframe>
  </div>
 </div>
<script type="text/javascript">
    $(function(){
      $m = $("#menu>li");
      $m.click(function() {
        $m.removeClass("focused");
        $(this).addClass("focused");
      });
    });
    window.onresize = function(){
     var heights = document.documentElement.clientHeight;
     document.getElementById('right').height = heights-73;
     document.getElementById('leftMain').height = heights-73;
   }
   window.onresize();
   function logout(){
     if (confirm("确定退出吗"))
       top.location = '<?php echo url("login/logout"); ?>';
     return false;
   }
   function refresh() {
     document.getElementById('leftMain').src = '<?php echo url('index/tree'); ?>';
   }
 </script>
</body>
</html>