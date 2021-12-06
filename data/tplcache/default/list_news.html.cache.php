<?php include $this->_include('header.html'); ?>
		<!----顶部导航 结束 ------------>
		<div class="main">
			<!-----banner 结束----->
			<div class="activity-main">
				<div class="activity-div">
					<div class="wrap">
						<?php include $this->_include('curr.html'); ?>
						<div class="textList">
							<ul>	
                            <?php $return = $this->_listdata("catid=$catid page=$page pagesize=20 xiaocms=1"); extract($return); if (is_array($return))  foreach ($return as $key=>$xiao) { ?>
								<li><?php if ($xiao['wb']!="") { ?><a href="<?php echo $xiao['wb']; ?>" target="_blank"><?php } else { ?><a href="<?php echo $xiao['url']; ?>" target="_blank"><?php } ?><span class="text"><?php echo $xiao['title']; ?></span><span class="date"><?php echo date("Y.m.d", $xiao['time']); ?></span></a></li>
                            <?php } ?>
                            
							</ul>
                              <div class="listpage" style="clear:both;"><?php echo $pagelist; ?></div> 
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

