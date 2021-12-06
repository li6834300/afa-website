<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
        <title><?php echo $site_title; ?></title>
        <meta name="keywords" content="<?php echo $site_keywords; ?>" />
        <meta name="description" content="<?php echo $site_description; ?>" />
		<link rel="stylesheet" type="text/css" href="<?php echo $site_template; ?>css/bootstrap.css"/>
		<link rel="stylesheet" type="text/css" href="<?php echo $site_template; ?>css/animate.min.css"/>
		<link rel="stylesheet" type="text/css" href="<?php echo $site_template; ?>css/style.css"/>
        
        
		<!--[if lt IE 9]>
		  <script src="http://apps.bdimg.com/libs/html5shiv/3.7/html5shiv.min.js"></script>
		  <script src="http://apps.bdimg.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<!---- 顶部导航 开始 ------------>
		<header>
			<div class="header-container">
				<div class="logoDiv clearfix">
					<div class="wrap">
						<a href="/" class="fl logoImg"><img src="<?php echo $site_template; ?>images/logo.png" alt=""/></a>
						<a href="###" class="fr orderDiv" data-toggle="modal" data-target="#myModal"><span>参观预约</span></a>
					</div>
				</div>
				<a href="###" class="mbtn"></a>
				<div class="menu">
					<div class="wrap">
						<ul class="level1 clearfix">
							<?php $return = $this->_category("num=6");  if (is_array($return))  foreach ($return as $key=>$xiao) { $allchildids = @explode(',', $xiao['allchildids']);    $current = in_array($catid, $allchildids);?>
							<li class="level">
				<a href="<?php echo $xiao['url']; ?>" <?php if ($current) { ?> class="on" <?php }  if ($xiao['xin']!="") { ?> target="_blank"<?php } else {  } ?>><?php echo $xiao['catname']; ?><span><?php echo $xiao['yw']; ?></span></a>

                                 <?php if ($xiao['child']) { ?>
								<ul class="level2">
                                 <?php $return = $this->_category("parentid=$xiao[catid]");  if (is_array($return))  foreach ($return as $key=>$xiao) { $allchildids = @explode(',', $xiao['allchildids']);    $current = in_array($catid, $allchildids);?>
									<li><a href="<?php echo $xiao['url']; ?>" <?php if ($xiao['xin']!="") { ?> target="_blank"<?php } else {  } ?>><?php echo $xiao['catname']; ?><span><?php echo $xiao['yw']; ?></span></a></li>
                                <?php } ?>
								</ul>
                                <?php } ?>


							</li>
                        <?php } ?>
						</ul>
				
					</div>
				</div>
			</div>
		</header>