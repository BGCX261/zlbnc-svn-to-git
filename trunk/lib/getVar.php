<?php
	include ('twitese.php');
	if(isset($_GET['nc'])){
		switch ($_GET['nc']){
			case 'login': actLogin(); break;
			case 'logout': actLogout(); break;
			case 'unloadPic': actUnloadPic(); break;
			case 'relation': actRelation(); break;
		}
	}
	
	function actUnloadPic(){
		$t = getTwitter();
		if (isset($_FILES["image"])) {
			$image = $_FILES["image"]['tmp_name'];
			$result = $t->twitgooUpload($image);
			if (isset($result->mediaurl)) {
				echo '{"result": "success" , "url" : "' . $result->mediaurl . '"}';
			} else {
				echo '{"result": "error"}';
			}
		}
	}
	function actRelation(){
		if(isset($_GET['id'])){
			$t = getTwitter();
			if(getEncryptCookie('twitterID') == $_GET['id']){echo '<br /><span style="color:#CC0033"><b>同志，你不认得你自己了？</b></span>';}
			else{
				$reD = $reU = false;
				if($t->isFriend(getEncryptCookie('twitterID'), $_GET['id'])){$reD = true;}//已关注
				if($t->isFriend($_GET['id'], getEncryptCookie('twitterID'))){$reU = true;}//被关注
				if($reD && $reU){echo '<br /><span class="relSame"><b>和你同一级别</b>
								 [<a href="javascript:void(0)" class="unfoll_btn">使其成为下级</a>]</span>';}
				if($reD && !$reU){echo '<br /><span class="relUp"><b>是你的上级</b>
								 [<a href="javascript:void(0)" class="unfoll_btn">与其脱离关系</a>]</span>';}
				if(!$reD && $reU){echo '<br /><span class="relDown"><b>是你的下级</b>
								 [<a href="javascript:void(0)" class="follow_btn">使其成为同级</a>]</span>';}
				if(!$reD && !$reU){echo '<br /><span class="relNone"><b>与你无关</b>
								 [<a href="javascript:void(0)" class="follow_btn">使其成为上级</a>]</span>';}
			}
		}
	}
	
	function actLogin(){
		if(isset($_POST['username']) && isset($_POST['password'])){
			$remember = isset($_POST['remember']) ? true : false;
			$result = verify($_POST['username'], $_POST['password'], $remember);
			if($result){header('location: ../index.php');}
			else{header('location: ../index.php?ln=error');}
		} else {
			header('location: ../index.php?ln=error');
		}
	}
	
	function actLogout(){
		$time = time() - 300;
		delCookie('bOauth');
		delCookie('twitterID');
		delCookie('twitterPW');
		delCookie('friends_count');
		delCookie('statuses_count');
		delCookie('followers_count');
		delCookie('imgurl');
		delCookie('name');
		header('location: ../index.php');
	}
?>