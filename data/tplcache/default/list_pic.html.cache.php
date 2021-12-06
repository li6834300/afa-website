<?php include $this->_include('header.html'); ?>
		<!----顶部导航 结束 ------------>
		
		<div class="main">
			
			<!-----banner 结束----->
            
			<div class="activity-main">

				<div class="activity-div">
					<div class="wrap">
						<?php include $this->_include('curr.html'); ?>
						<div class="imgList">
							<div class="row">
                             <?php $return = $this->_listdata("catid=$catid page=$page xiaocms=1 pagesize=24"); extract($return); if (is_array($return))  foreach ($return as $key=>$xiao) { ?>
								<div class="col-md-4 col-xs-6">
									<li><?php if ($xiao['wb']!="") { ?><a href="<?php echo $xiao['wb']; ?>" target="_blank"><?php } else { ?><a href="<?php echo $xiao['url']; ?>" target="_blank"><?php } ?>
										<img class="samLazyImg"  data-original="<?php echo $xiao['thumb']; ?>" src="<?php echo $xiao['thumb']; ?>"/>
										<div class="text">
											<strong><?php echo $xiao['yingwen']; ?></strong>
											<span><?php echo $xiao['title']; ?></span>
										</div>
									</a>
								</div>
                               <?php } ?>
                                 <div class="listpage" style="clear:both;"><?php echo $pagelist; ?></div> 
							</div>
						</div>
					</div>
				</div>
				
				
			</div>
			
            
			<!--footer-->
			<?php include $this->_include('footer.html'); ?>
	<script src="<?php echo $site_template; ?>js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo $site_template; ?>js/bootstrap.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo $site_template; ?>js/jquery.SuperSlide.2.1.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo $site_template; ?>js/wow.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo $site_template; ?>js/script.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo $site_template; ?>js/jquery.lazyload.min.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript">
		$("img.samLazyImg").lazyload({
		  placeholder : "<?php echo $site_template; ?>images/logo-down.png", //用图片提前占位
		  effect: "fadeIn", // 载入使用何种效果
		  threshold: 0, // 提前开始加载
		  failurelimit : 10 // 图片排序混乱时
		});
	</script>

