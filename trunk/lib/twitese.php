<?php
	include ('config.php');
	include ('utility.php');
	include ('twitter.php');
	
	function verify($name, $password, $remember) {
		$t = new twitter($name, $password, user_type());
		$user = $t->veverify();
		if (!isset($user->error) && isset($user->name)) {
			$time = $remember ? time()+3600*24*365 : 0; 
			setEncryptCookie('twitterID', $name, $time, '/');
			setEncryptCookie('twitterPW', $password, $time, '/');
			setcookie('friends_count', $user->friends_count, $time, '/');
			setcookie('statuses_count', $user->statuses_count, $time, '/');
			setcookie('followers_count', $user->followers_count, $time, '/');
			setcookie('imgurl', $user->profile_image_url, $time, '/');
			setcookie('name', $user->name, $time, '/');
			
			$twDM = $t->directMessages(false,false,1);
			if($twDM === false){}else{if(count($twDM) == 0){}else{
			setCookie("meSinceId",$twDM[0]->id);}}
			return true;
		} else {
			return false;
		}
	}
	
	function saveStyle($headerBg, $bodyBg, $linkColor, $linkHColor, $wordColor) {
		$time = time() + 3600*24*30;
		setcookie("headerBg", $headerBg, $time);
		setcookie("bodyBg", $bodyBg, $time);
		setcookie("linkColor", $linkColor, $time);
		setcookie("linkHColor", $linkHColor, $time);
		setcookie("wordColor", $wordColor, $time);
	}

	function resetStyle() {
		delCookie("headerBg");
		delCookie("bodyBg");
		delCookie("linkColor");
		delCookie("linkHColor");
		delCookie("wordColor");
	}
	
	function getColor($name, $default) {
		if (getCookie($name)) return getCookie($name);
		else return $default;
	}
		
	function setUpdateCookie($value) {
		setcookie('update_status', $value);
	}
	
	function getUpdateCookie() {
		if ( isset($_COOKIE['update_status']) ) {
			$update_status = $_COOKIE['update_status'];
			setcookie('update_status', '', time()-300);
			return $update_status;
		} else {
			return null;
		}
	}
	
	function formatText($text) {
		//如果开启了魔术引号\" \' 转回来 
		if (get_magic_quotes_gpc()) {
			$text = stripslashes($text);
		}
		
		//添加url链接
		$urlReg = '(((http|https|ftp)://){1}([[:alnum:]\-\.])+(\.)([[:alnum:]]){2,4}([[:alnum:]/+=%&_\.~?\-]*))';
		$text = eregi_replace($urlReg, ' <a href="\1" target="_blank">\1</a>', $text);
		
		//添加@链接
		$atReg = '@{1}(([a-zA-Z0-9\_\.\-])+)';
		$text = eregi_replace($atReg,  ' <a class="per_name" href="javascript:void(0)">\0</a>', $text);
		
		//添加标签链接
		$tagReg = "/(#{1}([\x80-\xff_0-9A-Za-z]{1,10}))[[:space:]]*/";
		$text = preg_replace($tagReg, '<a class="tw_label" href="javascript:void(0)">\0</a> ', $text);
		return $text;
	}
	
	function formatDate($date) {
		
		$differ = time() - strtotime($date);
				
		if ($differ < 60) {
			$dateFormated = ceil($differ) . "秒前";
		} else if ($differ < 3600) {
			$dateFormated = ceil($differ/60) . "分钟前";
		} else if ($differ < 3600*24) {
			$dateFormated = "约" . ceil($differ/3600) . "小时前";
		} else {
			$dateFormated = date('Y-m-d H:i:s', strtotime($date)); 
		}
		
		return $dateFormated;
	}
	
	function unshortUrl($text) {
		$urlRegs = array();
		$urlRegs[] ='/http:\/\/bit\.ly\/([a-z0-9]{5}[a-z0-9]*)/i';
		$urlRegs[] ='/http:\/\/j\.mp\/([a-z0-9]{5}[a-z0-9]*)/i';
		$urlRegs[] ='/http:\/\/tinyurl\.com\/([a-z0-9]{5}[a-z0-9]*)/i';
		$urlRegs[] ='/http:\/\/retwt\.me\/([a-z0-9]{5}[a-z0-9]*)/i';
		$urlRegs[] ='/http:\/\/is\.gd\/([a-z0-9]{5}[a-z0-9]*)/i';
		/*根据需要开启
		$urlRegs[] ='/http:\/\/moby\.to\/([a-z0-9]{5}[a-z0-9]*)/i';
		$urlRegs[] ='/http:\/\/tr\.im\/([a-z0-9]{4}[a-z0-9]*)/i';
		$urlRegs[] ='/http:\/\/snurl\.com\/([a-z0-9]{5}[a-z0-9]*)/i';
		$urlRegs[] ='/http:\/\/short\.ie\/([a-z0-9]{6}[a-z0-9]*)/i';
		$urlRegs[] ='/http:\/\/kl\.am\/([a-z0-9]{4}[a-z0-9]*)/i';
		$urlRegs[] ='/http:\/\/idek\.net\/([a-z0-9]{3}[a-z0-9]*)/i';
		$urlRegs[] ='/http:\/\/cli\.gs\/([a-z0-9]{6}[a-z0-9]*)/i';
		$urlRegs[] ='/http:\/\/u\.nu\/([a-z0-9]{5}[a-z0-9]*)/i';
		$urlRegs[] ='/http:\/\/digg\.com\/([a-z0-9]{6}[a-z0-9]*)/i';
		*/
		$objs = false;
		
		if(preg_match_all('/http:\/\/[a-z0-9\/\.]+[^<]/i',$text,$urls,PREG_PATTERN_ORDER)){
			foreach($urls[0] as $url) {
				foreach($urlRegs as $urlReg) {
					if(preg_match_all($urlReg,$url,$matchs,PREG_PATTERN_ORDER)){
						foreach($matchs[0] as $match){
							$request = 'http://api.unshort.me/?r=' . $match;
							$obj = objectifyXml(processCurl( $request ));
							if (isset($obj->resolvedURL) && trim($obj->resolvedURL) != '')
							$objs .= "<span>URL:<a href=\"$obj->resolvedURL\" target=\"_blank\">$obj->resolvedURL</a></span>";
						}
					}
				}
			}
		}
		return $objs;
	}
	
	function shortUrl($url, $type = "isgd") {
		switch ($type) {
			case 'isgd':
				$request = 'http://is.gd/api.php?longurl=' . rawurlencode($url);
				$result = processCurl( $request );
				if ($result) return $result;
				else return false;
				break;
			case 'aacx':
				$request = 'http://aa.cx/api.php?url=' . rawurlencode($url);
				$result = processCurl( $request );
				if ($result) return $result;
				else return false;
				break;
			default:
				return false;
		}
	}
	
	function processCurl($url,$postargs=false)
	{
	    $ch = curl_init($url);
	
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
   		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $response = curl_exec($ch);
        $responseInfo=curl_getinfo($ch);
        
        curl_close($ch);
        if( intval( $responseInfo['http_code'] ) == 200 )
			return $response;    
        else
            return false;
	}
	
	function objectifyXml( $data )
	{
		if( function_exists('simplexml_load_string') ) {
			$obj = @simplexml_load_string( $data );
		}
		if (isset($obj->error) || !$obj) return false;
		else return $obj;

		return false;
	}
	
	function user_type() {
		if (isset($_COOKIE["bOauth"])) return true;
		else return false;
	}
	
	function getTwitter() {
		return new twitter(getEncryptCookie('twitterID'), getEncryptCookie('twitterPW'), user_type());
	}
	
	
	function isLogin(){
		return getEncryptCookie('twitterID') && getEncryptCookie('twitterPW');
	}
?>