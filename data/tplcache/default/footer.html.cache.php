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