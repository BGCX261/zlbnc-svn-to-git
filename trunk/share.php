<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" href="css/share.css" rel="stylesheet" />
<title>传阅文件-传阅到twitter</title>
<script type="text/javascript" src="lib/jquery.js"></script>
<script type="text/javascript">
function leaveWord() {
	var leave = 110-$("#textbox").val().length;
	if (leave < 0) {
		$("#tip").css("color","#CC0000");
		$("#tip b").css("color","#CC0000");
		$("#tip").html("已经超出<b>" + (-leave) + "</b>个字");
	} else {
		$("#tip").css("color","#000000");
		$("#tip b").css("color","#000000");
		$("#tip").html("还可以输入<b>" + leave + "</b>个字");
	}
}
$(function(){leaveWord();
	$("#textbox").focus();
	$("#textbox").keydown(function(){leaveWord();}).keyup(function(){leaveWord();})
});
</script>
</head>

<body>
<?php 
	include ('lib/twitese.php');
	$t = getTwitter();
	if ( isset($_POST['status']) && isset($_POST['url']) ) {
		$status ='传阅： ';
		$shortUrl = shortUrl($_POST['url']);
		$postText = trim($_POST['status']);
		if ($shortUrl) {
			$status .= $shortUrl . ' ' . $postText . ' ';
		} else {
			$status .= $postText . ' ';
		}
		
		$result = $t->update($status);
	}
	
	$text = '';
	
	if ( isset($_GET['u']) ) {
		$url = $_GET['u'];
	}
	
	if ( isset($_GET['t']) ) {
		$title = $_GET['t'];
		$text = $_GET['t'];
	}
	
	if ( isset($_GET['d']) ) {
		$select = $_GET['d'];
		if ( trim($select) != "" ) $text = $select;
	}
	
	$siteUrl = str_replace('share', 'index', 'http://' . $_SERVER ['HTTP_HOST'] . $_SERVER['PHP_SELF']);

	?>
<div id="share">
	<?php if ( !$t->username ) {?>
		<div id="message">请先登录</div>
	<?php } else if ( isset($_POST['status']) ) { 
			if ($result) {
	?>
				<div id="message">传阅成功</div>
					<script type="text/javascript">
					setTimeout("window.close()",1000);
					</script>
		<?php } else { ?>
				<div id="message">传阅失败，请重试。<a href="javascript:window.history.go(-1)">后退</a></div>
		<?php 
			}
	   } else { 
	?>
		<form action="share.php" method="post">
		<table>
			<tr>
				<td colspan="2"><h2>传阅到真理部内参</h2><span id="tip">还可以输入<b>140</b>个字</span></td>
			</tr>
			<tr>
				<td class="title">网址:</td>
				<td><input type="text" name="url" id="url" value="<?php echo $url?>"/></td>
			</tr>
			<tr>
				<td class="title">内容:</td>
				<td><textarea name="status" id="textbox"><?php echo $text?></textarea></td>
			</tr>
			<tr>
				<th></th>
				<td><input type="submit" id="submit_btn" value="传阅" /></td>
			</tr>
		</table>
		</form>
	<?php } ?>
</div>
</body>
</html>