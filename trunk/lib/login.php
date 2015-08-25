<?php
function user_oauth() {
	require_once 'lib/OAuth.php';
	session_start();
	
	if (isset($_GET['oauth_token'])) {
		$oauth_token = $_GET['oauth_token'];
	    $params = array('oauth_verifier' => $_GET['oauth_verifier']);
	    $response = oauth_process('https://twitter.com/oauth/access_token', $params);
	    parse_str($response, $token);
	    
	    $GLOBALS['oauthpass'] = $token['oauth_token'] .'|'.$token['oauth_token_secret'];
	    unset($_SESSION['oauth_request_token_secret']);
		
	    $user = oauth_process('https://twitter.com/account/verify_credentials.json');
	    if (!isset($user->error)){
		    setEncryptCookie('twitterPW', $GLOBALS['oauthpass']);
			setEncryptCookie('twitterID', $user->screen_name);
			setcookie('friends_count', $user->friends_count);
			setcookie('statuses_count', $user->statuses_count);
			setcookie('followers_count', $user->followers_count);
			setcookie('imgurl', $user->profile_image_url);
			setcookie('name', $user->name);
			
			setCookie("bOauth","oauth");
			header('location: ../index.php');
	    }else{
	    	header('location: index.php?ln=error');
	    }
	    exit();
	  } else {
	    $params = array('oauth_callback' => OAUTH_CALLBACK_URL);
	    $response = oauth_process('https://twitter.com/oauth/request_token', $params);
	    if (($oauth_token = $_GET['oauth_token']) && $_SESSION['oauth_request_token_secret']) {
	    $oauth_token_secret = $_SESSION['oauth_request_token_secret'];
		} else {
		  list($oauth_token, $oauth_token_secret) = explode('|', $GLOBALS['oauthpass']);
		}
		$token = new OAuthConsumer($oauth_token, $oauth_token_secret);
	    parse_str($response, $token);
	    
	    $_SESSION['oauth_request_token_secret'] = $token['oauth_token_secret'];
	    
	    $authorise_url = 'https://twitter.com/oauth/authorize?oauth_token='.$token['oauth_token'];
	    header("Location: $authorise_url");
	}
}

function oauth_process($url, $post_data = false) {
	if ($post_data === true) $post_data = array();
	user_oauth_sign($url, $post_data);
	
	$ch = curl_init($url);
	if($post_data !== false) {
		curl_setopt ($ch, CURLOPT_POST, true);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, $post_data);
	}
	
	curl_setopt($ch, CURLOPT_VERBOSE, 0);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_USERAGENT, 'zlbnc');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	$response = curl_exec($ch);
	$response_info=curl_getinfo($ch);
	curl_close($ch);
	if(intval($response_info['http_code']) == 200) {
		$json = json_decode($response);
		if ($json) return $json;
		return $response;
	}else{
		delCookie('bOauth');
		header('location: index.php?ln=error');
	}
}

function user_oauth_sign(&$url, &$args = false) {
	require_once 'lib/OAuth.php';
	$method = $args !== false ? 'POST' : 'GET';

	if (preg_match_all('#[?&]([^=]+)=([^&]+)#', $url, $matches, PREG_SET_ORDER)) {
		foreach ($matches as $match){$args[$match[1]] = $match[2];}
		$url = substr($url, 0, strpos($url, '?'));
	}
  
	$sig_method = new OAuthSignatureMethod_HMAC_SHA1();
	$consumer = new OAuthConsumer(OAUTH_CONSUMER_KEY, OAUTH_CONSUMER_SECRET);
	$token = NULL;
	
	if (($oauth_token = $_GET['oauth_token']) && $_SESSION['oauth_request_token_secret']) {
		$oauth_token_secret = $_SESSION['oauth_request_token_secret'];
	} else {
		list($oauth_token, $oauth_token_secret) = explode('|', $GLOBALS['oauthpass']);
	}
	if ($oauth_token && $oauth_token_secret) {
		$token = new OAuthConsumer($oauth_token, $oauth_token_secret);
	}
  
	$request = OAuthRequest::from_consumer_and_token($consumer, $token, $method, $url, $args);
	$request->sign_request($sig_method, $consumer, $token);
  
	switch ($method) {
		case 'GET':
		  $url = $request->to_url();
		  $args = false;
		  return;
		case 'POST':
		  $url = $request->get_normalized_http_url();
		  $args = $request->to_postdata();
		  return;
	}
}

if(isset($_GET['q'])){if($_GET['q'] == 'oauth'){user_oauth();}}
?>
<script type="text/javascript">
//登陆方式
$(function(){
	$("#toAuth_btn").live("click", function(){
		$("#description").css("display", "none");
		$("#logway").replaceWith(
			'<div id="logway" style="margin-bottom:40px"><div style="padding:10px;margin:10px;background-color:#CC0033;color:#FFFFFF;">'
			+'<p style="line-height:22px;margin:15px"><b>oAuth登陆方式：通过登陆twitter.com官网验证实现，安全，但需翻墙。</b></p>'
			+'<p style="line-height:22px;margin:15px">oAuth登陆后，你的批示将显示“<b>通过真理部内参</b>”。</p>'
			+'<p style="line-height:22px;margin:15px">但由于无需在本站输入密码，上传图片功能将不可用。</p></div>'
			+'<input type="submit" id="oAuth_btn" style="width:70%;" value="【oAuth登录】" />'
			+'<input type="submit" id="toCom_btn" style="width:30%;border-left:3px solid #FFF" value="》普通登录" /></div>');
	});
	$("#toCom_btn").live("click", function(){
		$("#description").css("display", "block");
		$("#logway").replaceWith(
			'<div id="logway"><form id="login" method="post" action="lib/getVar.php?nc=login">'
			+'<div>用户名：<input type="text" id="username" name="username" onmouseover="this.style.borderColor=\'#CC0033\'" onmouseout="this.style.borderColor=\'\'" /></div>'
			+'<div>密　码：<input type="password" id="password" name="password" onmouseover="this.style.borderColor=\'#CC0033\'" onmouseout="this.style.borderColor=\'\'" /></div>'
			+'<div id="remember"><input type="checkbox" name="remember" id="remember_input" value="1" /><label for="remember_input">记住我</label></div>'
			+'<input type="submit" id="login_btn" style="width:70%;" value="【普通登录】" /></form>'
			+'<input type="submit" id="toAuth_btn" style="width:30%;border-left:3px solid #FFF" value="》oAuth登录" /></div>');
	});
	$("#oAuth_btn").live("click", function(){
		window.location.href="/oauth";
	});
});
</script>
<div id="header">
<div id="hlogo"><a target="_self" href="index.php"><img src="img/logo.png" /></a><br /></div>
<div style="width:100%; text-align:center; font-weight:bolder; border-bottom:3px solid #CC0033">战争即和平。自由即奴役。无知即力量。</div>
<div id="logway"><form id="login" method="post" action="lib/getVar.php?nc=login">
<div>用户名：<input type="text" id="username" name="username" onmouseover="this.style.borderColor='#CC0033'" onmouseout="this.style.borderColor=''" /></div>
<div>密　码：<input type="password" id="password" name="password" onmouseover="this.style.borderColor='#CC0033'" onmouseout="this.style.borderColor=''" /></div>
<div id="remember"><input type="checkbox" name="remember" id="remember_input" value="1" /><label for="remember_input">记住我</label></div>
<input type="submit" id="login_btn" style="width:70%;" value="【普通登录】" /></form>
<input type="submit" id="toAuth_btn" style="width:30%;border-left:3px solid #FFF" value="》oAuth登录" /></div>
<div id="description">
<?php
	if(isset($_GET['ln']) && (($_GET['ln'])=='error')){
		echo '<p style="color:#ffe600;font-weight:bolder">登陆失败，请重试。</p>';
	}
?>
<p><b>《真理部内部参考》旨在帮助真理部同仁了解真实发生的事，以避免在洗脑工作中被自己人所洗。</b></p>
<?php
	if(strstr($_SERVER["HTTP_USER_AGENT"], "Firefox")){
?>	
	<p>此版本为Firefox侧栏专用，第一次使用请先：</p>
	<p><a href="index.php" target="_self" class="a_btn" style="color:#FFFFFF;font-weight:900;text-align:center;border:1px solid #FFFFFF" title="真理部内参" rel="sidebar">【加入书签侧栏】</a></p>
	<p>然后从书签侧栏打开（Ctrl+B）本网页即可</p>
<?php }else{ ?>
	<p style="color:#ffe600;font-weight:bolder">这是 Firefox 侧栏专用的客户端，请在 Firefox 中使用。</p>
<?php } ?>
</div>
<div id="foot">
<p><a target="_blank" href="http://twreg.info/">注册账号</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class="per_name" href="javascript:void(0)">@iamzzm</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a target="_blank" href="http://code.google.com/p/zlbnc/">需要帮助</a></p>
</div>
</div>