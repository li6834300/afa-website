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
		<!--<link rel="stylesheet" type="text/css" href="<?php echo $site_template; ?>css/swiper.min.css"/>''-->
		<link rel="stylesheet" href="<?php echo $site_template; ?>css/sangarSlider.css" type="text/css" media="all">
		<link rel="stylesheet" href="<?php echo $site_template; ?>css/default.css" type="text/css" media="all">
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
						<a href="/" class="fl logoImg"> <?php $return = $this->_listdata("table=diy_bt1  num=1"); extract($return); if (is_array($return))  foreach ($return as $key=>$xiao) { ?><img src="<?php echo $xiao['wangzhan']; ?>" alt=""/></a> <?php } ?> 
						<a href="###" class="fr orderDiv" data-toggle="modal" data-target="#myModal"><span>参观预约</span></a>
					</div>
				</div>
				<a href="###" class="mbtn"></a>
				<div class="menu">
					<div class="wrap">
						<ul class="level1 clearfix">
							<?php $return = $this->_category("num=6");  if (is_array($return))  foreach ($return as $key=>$xiao) { $allchildids = @explode(',', $xiao['allchildids']);    $current = in_array($catid, $allchildids);?>
							<li class="level">
								<a href="<?php echo $xiao['url']; ?>" <?php if ($xiao['xin']!="") { ?> target="_blank"<?php } else {  } ?>><?php echo $xiao['catname']; ?><span><?php echo $xiao['yw']; ?></span></a>
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
		<!----顶部导航 结束 ------------>
		
		<div class="main">
			<!-----banner 开始----->
			<div class="index-banner jq22-container">
				<div class="jq22-content bgcolor-3">
					<div class='sangar-slideshow-container' id='sangar-example'>
						<div class='sangar-content-wrapper' style='display:none;'>	
                        		
                                <?php $return = $this->_listdata("table=diy_huan   num=15"); extract($return); if (is_array($return))  foreach ($return as $key=>$xiao) { ?>
							<div class='sangar-content'>
								<img src="<?php echo $xiao['tp']; ?>"/>
								<div class="wrap">
					        		<div class="textContent wow fadeInRight">
					        			<?php echo $xiao['wenzi']; ?>
					        		</div>
				        		</div>
							</div>
                            <?php } ?>
						</div>
					</div>	
				</div>
			</div>
			<!-----banner 结束----->
			<div class="index-main">
				<!--div1-->
				<div class="index-div1">
					<div class="wrap">
						<div class="titleDiv"><?php echo $cats[1][catname]; ?> About Farragut</div>
						<div class="textDiv">
							<?php $return = $this->_listdata("table=diy_bt1  num=1"); extract($return); if (is_array($return))  foreach ($return as $key=>$xiao) {  echo htmlspecialchars_decode($xiao['guanyu']);  } ?> 
							<!-- <a href="<?php echo $cats[1][url]; ?>" class="more_a">更多 MORE</a> -->
							<?php $return = $this->_listdata("table=diy_bt1  num=1"); extract($return); if (is_array($return))  foreach ($return as $key=>$xiao) { ?><img src="<?php echo $xiao['tup']; ?>" alt=""/><?php } ?> 
						</div>
					</div>
				</div>
			
				<!--div2-->
				<div class="index-div2">
					<?php $return = $this->_listdata("table=diy_bt1  num=1"); extract($return); if (is_array($return))  foreach ($return as $key=>$xiao) { ?><div class="bgImg" style="background-image: url(<?php echo $xiao['bjt']; ?>);background-position: 50% 50%;background-attachment: fixed;"><?php } ?> 
					</div>
					<div class="wrap">
						<div class="titleDiv"><?php echo $cats[12][catname]; ?>&nbsp;<?php echo $cats[12][yw]; ?></div>
						<div class="textDiv">
							<?php $return = $this->_listdata("table=diy_bt1  num=1"); extract($return); if (is_array($return))  foreach ($return as $key=>$xiao) {  echo htmlspecialchars_decode($xiao['jiaoshituanduituan']);  } ?> 
						</div>
						<div class="row">
                        
                        <?php $return = $this->_listdata("catid=12 num=3 xiaocms=1"); extract($return); if (is_array($return))  foreach ($return as $key=>$xiao) { ?>
							<div class="col-md-4 col-xs-12">
								<?php if ($xiao['wb']!="") { ?><a href="<?php echo $xiao['wb']; ?>" target="_blank"><img src="<?php echo $xiao['thumb']; ?>" alt=""/></a>
                                <?php } else { ?><a href="<?php echo $xiao['url']; ?>" target="_blank"><img src="<?php echo $xiao['thumb']; ?>" alt=""/></a>
                                <?php } ?>
								<div class="textDiv">
									<span><?php echo $xiao['yingwen']; ?></span>
									<p><?php echo $xiao['title']; ?></p>
								</div>
							</div>
                       <?php } ?> 
						</div>
						<a href="<?php echo $cats[12][url]; ?>" class="more_a">更多教师信息    MORE TEACHER INFORMATION</a>
					</div>
				</div>
				
				<!--div3-->
				<div class="index-div3">
					<div class="wrap">
						<div class="titleDiv" style="display:none;"><?php echo $cats[13][catname]; ?>&nbsp;<?php echo $cats[13][yw]; ?></div>
						<div class="textList">
							<ul>
                            
                        <?php $return = $this->_listdata("catid=39 num=5 xiaocms=1"); extract($return); if (is_array($return))  foreach ($return as $key=>$xiao) {  if ($xiao['wb']!="") { ?><li><a href="<?php echo $xiao['wb']; ?>" target="_blank"><span class="text"><?php echo $xiao['title']; ?></span><span class="date"><?php echo date("Y.m.d", $xiao['time']); ?></span></a></li>
                        <?php } else { ?> 
                        <li><a href="<?php echo $xiao['url']; ?>" target="_blank"><span class="text"><?php echo $xiao['title']; ?></span><span class="date"><?php echo date("Y.m.d", $xiao['time']); ?></span></a></li>
                        <?php }  } ?>
							</ul>
						</div>
						<a href="<?php echo $cats[39][url]; ?>" class="more_a">更多 MORE</a>
					</div>
				</div>
				
				
			</div>
			
            
			<!--footer-->
			 <?php $return = $this->_listdata("table=diy_bt1  num=1"); extract($return); if (is_array($return))  foreach ($return as $key=>$xiao) { ?>
<div class="footer">
				<div class="wrap">
					<div class="row">
						<div class="col-md-4 col-xs-12">
							<div class="conDiv">
								<img src="<?php echo $xiao['logo']; ?>"/>
								<div class="textDiv">
									<div class="telDiv">座机电话：<br /><?php echo $xiao['dh']; ?></div>
									<div class="addrDiv"><?php echo htmlspecialchars_decode($xiao['dz']); ?></div>
								</div>
							</div>
						</div>
						<div class="col-md-8 col-xs-12 rightDiv">
							<div class="ewmDiv">
								<img src="<?php echo $xiao['ewm1']; ?>"/>
								<span><?php echo $xiao['wz1']; ?></span>
							</div>
							<div class="ewmDiv">
								<img src="<?php echo $xiao['ewm2']; ?>"/>
								<span><?php echo $xiao['wz2']; ?></span>
							</div>
						</div>
					</div>
				</div>
			</div>
            
   <?php } ?>          

		</div>
		
		<!--弹窗-->
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="myModalLabel">咨询与报名</h4>
					</div>
					<div class="modal-body">
						<form action="/index.php?c=index&a=form&modelid=10" method="post">
							<div class="form-group">
								<input type="text" name="data[xm]" id="" value="" class="form-control" placeholder="姓名" required="required" oninvalid="setCustomValidity('请填写您的姓名')" oninput="setCustomValidity('')"/>
							</div>
							<div class="form-group">
								<label for="sex1" class="radio-inline">男<input type="radio" id="sex1" name="data[sex]" checked="checked" value="男"/></label>
								<label for="sex2" class="radio-inline">女<input type="radio" id="sex2" name="data[sex]" value="女"/></label>
							</div>
							<div class="form-group">
								<input type="telephone"  name="data[tel]" id="" value="" class="form-control" placeholder="电话"  required="required" oninvalid="setCustomValidity('请填写您的电话')" oninput="setCustomValidity('')"/>
							</div>
							<div class="form-group">
								<input type="text" name="data[xue]" id="" value="" class="form-control" placeholder="就读学校" />
							</div>
							<div class="form-group">
								<select class="form-control" name="data[nianji]">
							      <option value="">现在就读年级</option>
                                 <?php $return = $this->_listdata("table=diy_bt1   num=1"); extract($return); if (is_array($return))  foreach ($return as $key=>$xiao) {  echo htmlspecialchars_decode($xiao['yue']);  } ?>
							    </select>
							</div>
                            <input type="submit" value="提 交" class="subBtn"  />
                            </form>
						
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal -->
		</div>
	
	</body>
    </html>
            
	<script src="<?php echo $site_template; ?>js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo $site_template; ?>js/bootstrap.min.js" type="text/javascript" charset="utf-8"></script>
	<!--<script src="<?php echo $site_template; ?>js/jquery.SuperSlide.2.1.js" type="text/javascript" charset="utf-8"></script>-->
	<script src="<?php echo $site_template; ?>js/wow.min.js" type="text/javascript" charset="utf-8"></script>
	<!--<script src="<?php echo $site_template; ?>js/swiper.min.js" type="text/javascript" charset="utf-8"></script>-->
	<script src="<?php echo $site_template; ?>js/script.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="<?php echo $site_template; ?>js/jquery.touchSwipe.min.js"></script>
	<script type="text/javascript" src="<?php echo $site_template; ?>js/imagesloaded.min.js"></script>
	<!-- jQuery Sangar Slider -->
	<script type="text/javascript" src="<?php echo $site_template; ?>js/sangarSlider/sangarBaseClass.js"></script>
	<script type="text/javascript" src="<?php echo $site_template; ?>js/sangarSlider/sangarSetupLayout.js"></script>
	<script type="text/javascript" src="<?php echo $site_template; ?>js/sangarSlider/sangarSizeAndScale.js"></script>
	<script type="text/javascript" src="<?php echo $site_template; ?>js/sangarSlider/sangarShift.js"></script>
	<script type="text/javascript" src="<?php echo $site_template; ?>js/sangarSlider/sangarSetupBulletNav.js"></script>
	<script type="text/javascript" src="<?php echo $site_template; ?>js/sangarSlider/sangarSetupNavigation.js"></script>
	<script type="text/javascript" src="<?php echo $site_template; ?>js/sangarSlider/sangarSetupSwipeTouch.js"></script>
	<script type="text/javascript" src="<?php echo $site_template; ?>js/sangarSlider/sangarSetupTimer.js"></script>
	<script type="text/javascript" src="<?php echo $site_template; ?>js/sangarSlider/sangarBeforeAfter.js"></script>
	<script type="text/javascript" src="<?php echo $site_template; ?>js/sangarSlider/sangarLock.js"></script>
	<script type="text/javascript" src="<?php echo $site_template; ?>js/sangarSlider/sangarResponsiveClass.js"></script>
	<script type="text/javascript" src="<?php echo $site_template; ?>js/sangarSlider/sangarResetSlider.js"></script>
	<script type="text/javascript" src="<?php echo $site_template; ?>js/sangarSlider/sangarTextbox.js"></script>
	<script type="text/javascript" src="<?php echo $site_template; ?>js/sangarSlider.js"></script>
	<script>
//  var galleryTop = new Swiper('.gallery-top', {
//    spaceBetween: 10,
//  });
//  var galleryThumbs = new Swiper('.gallery-thumbs', {
//    spaceBetween: 10,
//    centeredSlides: true,
//    slidesPerView: "auto",
////    touchRatio: 0.2,
//    slideToClickedSlide: true,
//  });
//  galleryTop.controller.control = galleryThumbs;
//  galleryThumbs.controller.control = galleryTop;
//	jQuery(".fullSlide").slide({ titCell:".hd li", mainCell:".bd ul", effect:"fold",  autoPlay:true, delayTime:700 });
	var ww = $(window).width();
	jQuery(document).ready(function($) {
		if(ww>768){
			var sangar = $('#sangar-example').sangarSlider({
		        timer :  true, // true or false to have the timer
		        pagination : 'content-horizontal', // bullet, content, none
		        paginationContent : [
				<?php $i = 1;   $return = $this->_listdata("table=diy_huan   num=15"); extract($return); if (is_array($return))  foreach ($return as $key=>$xiao) {  if ($i==1) { ?>"<?php echo $xiao['tp']; ?>" <?php } else { ?>,"<?php echo $xiao['tp']; ?>"<?php }  $i++;  } ?>
				], // can be text, image, or something			        
		        paginationContentType : 'image', // text, image
		        paginationContentWidth : 153, // pagination content width in pixel
		        paginationImageHeight : 80, // pagination image height
		        width : 1920, // slideshow width'
				height : 800, // slideshow height'
		        fixedHeight: false,
		        scaleSlide : true, // slider will scale to the container size
		        animationSpeed : 1500,
			});
		}else{
			var sangar = $('#sangar-example').sangarSlider({
		        timer :  true, // true or false to have the timer
		        pagination : 'content-horizontal', // bullet, content, none
		        paginationContent : [
				
				<?php $i = 1;   $return = $this->_listdata("table=diy_huan   num=15"); extract($return); if (is_array($return))  foreach ($return as $key=>$xiao) {  if ($i==1) { ?>"<?php echo $xiao['tp']; ?>" <?php } else { ?>,"<?php echo $xiao['tp']; ?>"<?php }  $i++;  } ?>
				
				], // can be text, image, or something			        
		        paginationContentType : 'image', // text, image
		        paginationContentWidth : 103, // pagination content width in pixel
		        paginationImageHeight : 60, // pagination image height
		        fixedHeight: false,
		        scaleSlide : true, // slider will scale to the container size
		        animationSpeed : 1500,
			});
		}
	})		
  </script>