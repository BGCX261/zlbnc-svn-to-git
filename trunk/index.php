<?php
	include ('lib/twitese.php');
	ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="twitter,推特,twitese,真理部内参" />
<meta name="description" content="真理部内部参考，Firefox侧栏专用的twitter网页客户端" />
<link rel="shortcut icon" href="img/favicon.ico" />
<link type="text/css" href="css/main.css" rel="stylesheet" />
<link rel="stylesheet" href="css/colorpicker.css" type="text/css" />
<title>真理部内参</title>
<script type="text/javascript" src="lib/jquery.js"></script>
<?php
if (!isLogin()){ echo '</head><body>'; include ('lib/login.php'); echo '</body></html>';}
else{
	$headerBg = getColor("headerBg","#CC0033");
	$bodyBg = getColor("bodyBg","#FFFFFF");
	$linkColor = getColor("linkColor","#990000");
	$linkHColor = getColor("linkHColor","#770000");
	$wordColor = getColor("wordColor","#000000");
?>
<style type="text/css">
	body{color:<?php echo $wordColor ?>;background-color:<?php echo $headerBg ?>}
	#wait, .tmenu{border-top:3px solid <?php echo $headerBg ?>}
	.tmenu{border-bottom:3px solid <?php echo $headerBg ?>}
	#header_search, #senttw {border-bottom-color:<?php echo $headerBg ?>}
	#hlogo, #hidelogo, input[type="submit"], .a_btn, #more_btn, #nomore_btn, .tmenu a:hover{background-color:<?php echo $headerBg ?>}
	#hidelogo:hover, input[type="submit"]:hover, .a_btn:hover, #more_btn:hover{background-color:<?php echo $linkHColor ?>}
	.timeline li:hover a:hover{color:<?php echo $linkHColor ?>}
	a{color:<?php echo $linkColor ?>}
	a:hover{color:<?php echo $linkHColor ?>}
</style>
<script type="text/javascript" src="lib/public.js"></script>
<script type="text/javascript" src="lib/ajaxfileupload.js"></script>
<script type="text/javascript" src="lib/showpic.js"></script>
<script type="text/javascript" src="lib/colorpicker3.js"></script>
</head>
<body>
<div id="header">
<div id="hlogo"><img src="img/logo.png" /></div>
<div id="headinfo">
 <a href="javascript:void(0)" class="theUser"><img class="pimg" src="<?php echo getCookie("imgurl")?>" /></a>
<span style="display:block"> 接收<a href="javascript:void(0)" class="pFriend"><?php echo getCookie('friends_count')?>人</a>的批示，向<a href="javascript:void(0)" class="pFollower"><?php echo getCookie('followers_count')?>人</a>作批示</span>
<span style="display:block"> 已作<a href="javascript:void(0)" class="theUser"><?php echo getCookie('statuses_count')?>条批示</a> [<a href="javascript:void(0)" class="pReply">明信</a>] [<a href="javascript:void(0)" class="pMessage">暗号</a>] [<a href="javascript:void(0)" class="pListF">名单</a>] [<a href="javascript:void(0)" class="pFavor">收藏</a>]</span>
</div>
			<div class="tmenu"><ul>
			<li> <a href="javascript:void(0)" id="pMain">首页</a></li>
			<li> <a href="javascript:void(0)" id="searchBtn">搜索</a></li>
			<li> <a href="javascript:void(0)" id="sentBtn" style="font-weight:900">批示</a></li>
			<li> <a href="javascript:void(0)" id="pSetting">设置</a></li>
			<li> <a href="javascript:void(0)" id="pLogout">退出</a></li>
			</ul></div>
<span id="header_search">
<input type="text" id="normal_search_query"  onmouseover="this.style.borderColor='#990000'" onmouseout="this.style.borderColor=''" />
<input type="button" id="normal_search_submit" value="普通搜索" /><br />
<input type="text" id="chinese_search_query"  onmouseover="this.style.borderColor='#990000'" onmouseout="this.style.borderColor=''" />
<input type="button" id="chinese_search_submit" value="中文搜索" /><br />
<input type="text" id="user_search_query"  onmouseover="this.style.borderColor='#990000'" onmouseout="this.style.borderColor=''" />
<input type="button" id="user_search_submit" value="用户搜索" /><br />
 [<a href="javascript:void(0)" id="pRank">名人榜</a>] [标签云] [传颂榜]
</span>
<div id="senttw">
<div id="update_form">
<?php echo "<h1>请", getCookie('name'), "同志批示：</h1><br />"; ?>
<span id="sent_function">
	 <a href="javascript:void(0)" id="photoBtn"><img src="img/photo.gif" /></a>
	 <a href="javascript:void(0)" id="linkBtn"><img src="img/link.gif" /></a>
</span>
<div id="photoArea">
	图片上传：
	<input name="image" id="imageFile" type="file" onmouseover="this.style.borderColor='#990000'" onmouseout="this.style.borderColor=''" />
	<input type="button" id="imageUploadSubmit" value="提交" />
</div>
<div id="linkArea">
	缩短URL：
	<input type="text" name="longurl" id="longurl" onmouseover="this.style.borderColor='#990000'" onmouseout="this.style.borderColor=''" />
	<select name="shortUrlType" id="shortUrlType">
		<option value="isgd">is.gd</option>
		<option value="aacx">aa.cx</option>
	</select>
	<input type="button" value="提交" id="linkSubmit" />
</div>
<span id="tip">还可以输入<b>140</b>个字</span>
	<textarea name="status" id="textbox" onmouseover="this.style.borderColor='#990000'" onmouseout="this.style.borderColor=''"></textarea>
	<input type="hidden" id="in_reply_to" name="in_reply_to" />
	<br /><br />
	<input type="submit" id="submit_btn" value="发送" />
</div><br /><br />
</div>
</div>
<div id="main"><div id="content"></div></div>
<div id="style_form">
<table id="setting_table">
<tr><td colspan="2">预设样式：<select style="padding:2px" id="styleSelect"><option value="n/a">请选择</option></select></td></tr>
<tr>
<td class="style_title">头部背景：</td>
<td><input class="style_input" type="text" id="headerBg" name="headerBg" value="<?php echo getColor("headerBg","#CC0033") ?>" /></td>
</tr>
<tr>
<td class="style_title">内容背景：</td>
<td><input class="style_input" type="text" id="bodyBg" name="bodyBg" value="<?php echo getColor("bodyBg","#FFFFFF") ?>" /></td>
</tr>
<tr>
<td class="style_title">链接颜色：</td>
<td><input class="style_input" type="text" id="linkColor" name="linkColor" value="<?php echo getColor("linkColor","#990000") ?>" /></td>
</tr>
<tr>
<td class="style_title">链接悬浮颜色：</td>
<td><input class="style_input" type="text" id="linkHColor" name="linkHColor" value="<?php echo getColor("linkHColor","#770000") ?>" /></td>
</tr>
<tr>
<td class="style_title">文字颜色：</td>
<td><input class="style_input" type="text" id="wordColor" name="wordColor" value="<?php echo getColor("wordColor","#000000") ?>" /></td>
</tr>
<tr>
<td colspan="2">
<input type="submit" style="width:50%" id="style_reset" value="默认" />
<input type="submit" style="width:50%" id="style_save" value="保存" />
</td>
</tr></table></div>
<div id="footer"><div id="iam" style="display:none"><?php echo getCookie('name'); ?></div>
<p><a target="_blank" href="shareHelp.php">传阅文件</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class="per_name" href="javascript:void(0)">@iamzzm</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a target="_blank" href="http://code.google.com/p/zlbnc/">需要帮助</a></p>
<p><script type="text/javascript" src="http://widgets.amung.us/colored.js"></script><script type="text/javascript">WAU_colored('q7bus152uuta', 'ed1c24ffffff')</script></p>
</div>
</body>
</html>
<?php } ?>
