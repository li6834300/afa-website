<?php include $this->admin_tpl('header');?>
<script type="text/javascript">$(function(){$.getScript("<?php echo $client_url;?>");});</script>
<?php if(!is_file(DATA_DIR . 'cache' . DS."category.cache.php")) echo '<script type="text/javascript">location.href="'. url('index/cache') .'";</script>';?>
<div class="subnav">

  <div class="lf mr10" style="width:48%">
    <table width="100%"   class="m-table">
      <thead class="table-thead">
        <tr>
          <th align="left">系统信息</th>
        </tr>
      </thead>
      <tbody >
        <tr >
          <td align="left">运行环境：<?php echo $_SERVER['SERVER_SOFTWARE'];?></td>
        </tr>
        <tr >
          <td align="left" style="border-bottom: 0px;">mysql版本：<?php echo $this->db->getServerVersion()?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
</body></html>