<?php include $this->admin_tpl('header');?>
<script type="text/javascript">
top.document.getElementById('position').innerHTML = '区块管理';
</script>
<div class="subnav">
	<div class="content-menu">
		<a href="<?php echo url('block'); ?>"  class="on"><em>全部区块</em></a>
		<?php if($this->menu('block-add')) { ;?>
		<a href="<?php echo url('block/add'); ?>" class="add"><em>添加区块</em></a>
    	<?php } ?>
	</div>
	<div class="bk10"></div>
	<form action="" method="post" name="myform">
	<table width="100%"  class="m-table">
	<thead class="table-thead">
	<tr>
		<th width="25" align="left">ID</th>
		<th align="left">区块名称</th>
        <?php if($this->menu('administrator-index')) { ;?>
		<th  width="300"  align="left">模板调用代码</th>
        <?php } ?>
		<th  width="80"  align="left">操作</th>
	</tr>
	</thead>
	<tbody >
	<?php if (is_array($list))  foreach ($list as $t) { ?>
	<tr >
		<td align="left"><?php echo $t['id']; ?></td>
		<td align="left"><a href="<?php echo url('block/edit',array('id'=>$t['id'])); ?>"><?php echo $t['name']; ?></a></td>
        <?php if($this->menu('administrator-index')) { ;?>
		<td align="left">{xiao:block <?php echo $t['id']; ?>}</td>
         <?php } ?>
		<td align="left">
		<a href="<?php echo url('block/edit',array('id'=>$t['id'])); ?>">编辑</a>
        <?php if($this->menu('block-del')) { ;?> |
		<a  href="javascript:confirmurl('<?php  echo url('block/del/',array('id'=>$t['id']));?>','确定删除 『<?php echo $t['name']; ?> 』区块吗？')" >删除</a> 
        <?php } ?>
		</td>
		</td>
	</tr>
	<?php  } ?>
	</table>
	</form>
</div>
</body>
</html>