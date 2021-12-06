<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
  <meta name="renderer" content="webkit"/>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>管理员登陆 - 建站管理中心</title>
  <meta name="author" content="建站管理中心" />
  <meta name="copyright" content="建站管理中心" />
  <script src="../core/img/ueditor/third-party/jquery-1.10.2.min.js"></script>
  <link rel="stylesheet" href="static/css/font-awesome.css">
  <style type="text/css">
body {
	background-color: #fff;
}
body, input {
	padding: 0;
	margin: 0;
	font-size: 18px;
	font-family: "Microsoft YaHei", Tahoma, SimSun, sans-serif;
	-webkit-text-size-adjust: none;
}
a {
	color: #999;
	text-decoration: none;
}
img {
	vertical-align: middle;
}
 input:-webkit-autofill {
 -webkit-box-shadow: 0 0 0px 1000px #fff inset;
}
.wrap {
	background: rgba(0, 0, 0, .2);
	border-radius: 5px;
	width: 518px;
	padding: 15px;
	min-height: 355px;
	margin: auto;
	margin-top: 10%;
}
.logo {
	height: 70px;
	line-height: 70px;
	margin: auto;
	text-align: center;
	color: #666;
	font-size: 28px;
	font-weight: 400;
	border-bottom: 1px solid #eee;
}
.login {
	border-radius: 4px;
	background-color: #fff;
	width: 100%;
	min-height: 355px;
	position: relative;
}
.form {
	width: 316px;
	padding-top: 33px;
	margin: auto;
}
.input {
	margin-top: 20px;
	position: relative;
}
.ico-username, .ico-password, .ico-captcha {
	width: 18px;
	height: 18px;
	display: inline-block;
	position: absolute;
	top: 13px;
	left: 16px;
	color: #a5aeb8;/*d8d8d8*/
	font: normal normal normal 24px/1 FontAwesome;
	text-rendering: auto;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
}
.ico-username:before {
	content: "☺";
}
.ico-password:before {
	content: "❊";
}
.ico-captcha:before {
	content: "☑";
}
#yzm {
	overflow: hidden;
}
#yzm input {
	float: left;
	width: 180px
}
#yzm img {
	float: right;
	width: 126px;
	height: 50px;
	border: 1px solid #C9C9C9;
	border-radius: 3px;
	box-sizing: border-box;
	cursor: pointer;
}
#yzm img:hover {
	border-color: #6bcf99;
}
.findpsw {
	margin-top: 15px;
	text-align: right;
	font-size: 12px;
	font-family: "";
	color: #888;
}
input {
	color: #666;
	padding-left: 50px;
	vertical-align: middle;
	width: 100%;
	height: 50px;
	border: 1px solid #C9C9C9;
	border-radius: 3px;
	box-sizing: border-box;
}
input:hover {
}
input:focus {
	color: #6bcf99;
	border-color: #6bcf99;
	background-color: none;
	outline: none;
}
.button {
	margin-top: 20px;
	background: #6bcf99 none;
	border-radius: 3px;
	color: #ffffff;
	outline: none;
	text-align: center;
	border: 0;
	cursor: pointer;
	width: 100%;
	height: 50px;
	line-height: 50px;
	font-size: 22px;
	font-family: "Microsoft YaHei";
	box-shadow: 0px 3px 1px rgb(98, 154, 124);
}
.button:hover {
	background: #22bee5;
}
.copyright {
	padding: 35px 0 25px 0;
	color: #999;
	font-size: 14px;
	text-align: center;
	text-shadow: white 0 1px 0;
}
.copyright a {
	color: #999;
}
.copyright a:hover {
	color: #999;
}
 @media only screen and (min-width: 320px) and (max-width: 1000px) {
html, body {
	height: 100%;
}
.logo {
	width: 100%;
}
.wrap {
	width: 100%;
	height: 100%;
	padding: 0px;
}
.login {
	position: relative;
	width: 100%;
	height: 100%;
	min-height: 400px;
}
}
</style>
  </head>
  <body>
  <div class="wrap">
    <div class="login">
      <div class="logo">网站管理中心</div>
      <div class="form">
        <form method="post" action="">
          <div class="input"> <i class="ico-username"></i>
            <input name="username" type="text" id="username" placeholder="请输入用户名">
          </div>
          <div class="input"> <i class="ico-password"></i>
            <input name="password" type="password" id="password" placeholder="请输入密码">
          </div>
          <div id="yzm" >
            <div class="input"> <i class="ico-captcha"></i>
              <input name="code" type="text" id="captcha" placeholder="请输入验证码">
              <img id="yzmimg" src="../index.php?c=api&a=checkcode&width=85&height=26"  alt="点击这里"  title="看不清楚？换一张" onclick="document.getElementById('yzmimg').src='../index.php?c=api&a=checkcode&width=85&height=26&'+Math.random();" ></div>
          </div>
          <div class="findpsw"><span id="msg"></span><!--<a href="#">忘记密码?</a>--></div>
          <button type="submit" name="submit" class="button" value="登录" >登 录</button>
        </form>
      </div>
      <div class="copyright"></div>
    </div>
  </div>
</body>
</html>
