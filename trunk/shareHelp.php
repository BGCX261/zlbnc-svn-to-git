<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="img/favicon.ico" />
<link type="text/css" href="css/main.css" rel="stylesheet" />
<title>真理部内参.传阅文件帮助</title>
</head>
<body>
<?php 
	$url = str_replace('shareHelp', 'share', 'http://' . $_SERVER ['HTTP_HOST'] . $_SERVER['PHP_SELF']);
?>
<div id="description">
		<p><b>把你正在看的网页，告诉其他同志</b></p>
		<p> <a target="_self" href="javascript:var%20d=document,w=window,f='<?php echo $url ?>',l=d.location,e=encodeURIComponent,p='?u='+e(l.href)+'&t='+e(d.title)+'&d='+e(w.getSelection?w.getSelection().toString():d.getSelection?d.getSelection():d.selection.createRange().text)+'&s=bm';a=function(){if(!w.open(f+p,'sharer','toolbar=0,status=0,resizable=0,width=600,height=350'))l.href=f+'.new'+p};if(/Firefox/.test(navigator.userAgent))setTimeout(a,0);else{a()}void(0);"><img src="img/share.gif" /></a></p>
		<p>右击上方图片，选择“将此链接加入书签”（注意改名字），为了使用方便快捷，推荐移至书签的工具栏。</p>
		<p>成功后，当你想将当前浏览的网页传阅给twitter上的同志，则点击上面收藏的链接，即出现一窗口，编辑内容后点击发送即可。若未登录真理部内参，则会提示先登录。</p>
		<p>传阅时默认内容为网页的标题，当你在网页上选择了内容，传阅的内容会变成选择部分的内容。</p>
</div>
</body></html>