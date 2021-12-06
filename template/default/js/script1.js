var ww = $(window).width();

$(window).scroll(function(){
	//判断置顶
    var sTop=$(window).scrollTop();
    if(sTop>200){
      $('a.backTop').css({"display":"block"});
    }else{
      $('a.backTop').hide();
    }
    //判断二级导航置顶

	if(sTop > 10) {
		$('body').addClass('on-fixed');
	} else {
		$('body').removeClass('on-fixed');
	}
  
 });
  

$(function(){
	
	if(ww>970){
		//顶部菜单显示隐藏
		$("header .menu .level1>li").hover(function(){
			$(this).find("a").toggleClass("on");
			$(this).find(".level2").slideToggle("fast");
		})
	}else{
		//顶部菜单显示隐藏
		$("header .menu .level1>li").hover(function(){
			$(this).find("a").toggleClass("on");
		})
		$("header .menu .level1>li").click(function(){
			if($(this).hasClass("level")){
				$(this).find("a").attr("href","###");
				$(this).siblings("li").find(".level2").slideUp();
				$(this).find(".level2").slideToggle("fast");
			}
		})
	}
	
	//特效
	if (!(/msie [6|7|8|9]/i.test(navigator.userAgent))){
		new WOW().init();
	};
	
	//手机端点击菜单
	$('header .mbtn').click(function(event) {
		$('body').toggleClass('on-menu');
		return false;
	});
	
	//预约提交
/*	$(".modal-body .subBtn").click(function(){
		alert("已提交");
		$(".modal .close").click();
	})*/
})
