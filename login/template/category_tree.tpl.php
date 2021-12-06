<?php include $this->admin_tpl('header');?>
<script type="text/javascript">
    $(document).on('click', '.item', function(event) {
        event.stopPropagation();
        t = $(this);
        if(t.hasClass("expandable")) {
            t.addClass('expanded');
            t.parent().find(">ul").show();
        }
        $(".item").removeClass("active");
        $(this).addClass("active");
    });
    $(document).on('click', '.expandable .folder', function(event) {
        p = $(this).parent();
        if(p.hasClass("expanded")) {
            p.removeClass('expanded');
            p.parent().find(">ul").hide();
        } else {
            p.addClass('expanded');
            p.parent().find(">ul").show();
        }
        return false; // 阻止a连接
    });
</script>
<style type="text/css">
 /* 滚动条自定义*/
 ::-webkit-scrollbar {
    width:5px;
    margin-right:2px
}
::-webkit-scrollbar-track-piece {
    background-color:#F5F5F5;
    border-left:1px solid #D2D2D2;
}
::-webkit-scrollbar-thumb {
    background:#CBCBCB;
    width:10px
}
a:hover {
    text-decoration:none;
    color: #000;
}
.toolbar {
    margin-top: 10px;
    background: #eee;
    color: #666;
    height: 32px;
    line-height: 32px;
    padding: 0 8px;
    border-bottom: 1px solid #ccc;
    border-top: 1px solid #ccc;
}
.bg1 {
    background: #f8f8f8;
}
.bg2 {
    background: #f1f1f1;
}

.arrow-down {
    display: block;
    width: 16px; 
    height: 16px; 
    float: left;
    margin-top:8px; 
    background: url(./img/jt.png) center left no-repeat;
}
.tree {
    line-height: 1.2;
    font-size: 13px;
    font-family: Arial, Verdana, "宋体";
padding-bottom: 30px;}

.tree ul {
    padding-left: 17px;
}

.tree li {
    overflow: hidden;
}

.tree .item {
    width: 100%;
    display: block;
    border-bottom: 1px solid #e1e1e1;
    height: 34px;
    line-height: 34px;
    padding-left: 8px;
}

.tree .item:hover {
 border-left: 1px solid #F44336;
 background-color: #f6f6f6;
}

.tree .active {
 border-left: 1px solid #F44336;
 background-color: #f6f6f6;
}

.tree .active:hover {
 border-left: 1px solid #F44336;
 background-color: #f6f6f6;
}

.tree .folder, .tree .file {
    display: inline-block;
    height: 15px;
    width: 15px;
    vertical-align: middle;
}

.tree .txt {
    vertical-align: middle;
}

.tree .file {
    background: url(./img/file-a.png) no-repeat;
}



.tree .expandable .folder {
    background: url(./img/folder-a.png) no-repeat;
}

.tree .expandable .folder:hover {
    background: url(./img/folder-b.png) no-repeat;
}

.tree .expanded .folder {
    background: url(./img/folder-open-a.png) no-repeat;
}

.tree .expanded .folder:hover {
    background: url(./img/folder-open-b.png) no-repeat;
}



</style>


<?php echo $categorys; ?>
<?php echo $form; ?>
<?php echo $diytable; ?>

</body>
</html>