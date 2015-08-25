<?php
	include ('twitese.php');
	if(isset($_POST['nc'])){
		switch ($_POST['nc']){
			case 'shortUrl': actShortUrl(); break;
			case 'update': actUpdate(); break;
			case 'delStatus': actDelStatus(); break;
			case 'delDM': actDelDM(); break;
			case 'addFriend': actAddFriend(); break;
			case 'delFriend': actDelFriend(); break;
			case 'addFavor': actAddFavor(); break;
			case 'delFavor': actDelFavor(); break;
			case 'addList': actAddList(); break;
			case 'editList': actEditList(); break;
			case 'delList': actDelList(); break;
			case 'folList': actFollowList(); break;
			case 'unfList': actUnfollowList(); break;
			case 'addMember': actAddMember(); break;
			case 'delMember': actDelMember(); break;
			case 'editProfile': actEditProfile(); break;
		}
	}
	
	function actShortUrl(){
		if (isset($_POST['longurl']) && isset($_POST['type'])) {
			$result = shortUrl($_POST['longurl'], $_POST['type']);
			if ($result) echo $result;
			else echo 'error';
		}
	}

	function actUpdate(){
			if(trim($_POST['status']) == ''){echo 'empty';}
			else{
				$t = getTwitter();
				if(isset($_POST['sent_id'])){
					$result = $t->sendDirectMessage($_POST['sent_id'], $_POST['status']);
					if ($result){echo 'ok';}else{echo $_POST['status'];}
				}else{
					if(trim($_POST['in_reply_to']) == ''){
						$result = $t->update($_POST['status']);
					}else{
						$result = $t->update($_POST['status'],$_POST['in_reply_to']);
					}
					if ($result){
						echo 'ok';
						$user = $result->user;
						$time = time()+3600*24*365;
						if ($user) {
							setcookie('friends_count', $user->friends_count, $time, '/');
							setcookie('statuses_count', $user->statuses_count, $time, '/');
							setcookie('followers_count', $user->followers_count, $time, '/');
							setcookie('imgurl', $user->profile_image_url, $time, '/');
							setcookie('name', $user->name, $time, '/');
						}
					}else{
						echo 'error';
					}
				}				
			}
	}
	function actDelStatus(){
			$t = getTwitter();
			$result = $t->deleteStatus($_POST['id']);
            if($result){echo 'ok';}else{echo 'error';}
	}

	function actDelDM(){
			$t = getTwitter();
	        $result = $t->deleteDirectMessage($_POST['id']);
	        if($result){echo 'ok';}else{echo 'error';}
	}
	
	function actAddFriend(){
		$t = getTwitter();
		$result = $t->followUser($_POST['id']);
		if($result){echo 'ok';}else{echo 'error';}
	}
	function actDelFriend(){
		$t = getTwitter();
		$result = $t->destroyUser($_POST['id']);
		if ($result){echo 'ok';}else{echo 'error';}
	}
	function actAddFavor(){
			$t = getTwitter();
			$result = $t->makeFavorite($_POST['id']);
			if($result){echo 'ok';}else{echo 'error';}
	}
	function actDelFavor(){
			$t = getTwitter();
			$result = $t->removeFavorite($_POST['id']);
            if($result){echo 'ok';}else{echo 'error';}
	}
	
	function actAddList(){
		$t = getTwitter();
		$result = $t->createList($_POST['list_name'], $_POST['list_description'], $_POST['isProtect']);
		if ($result){echo 'ok';}else{echo 'error';}
	}
	function actEditList(){
		$t = getTwitter();
		$result = $t->editList($_POST['pre_list_name'], $_POST['list_name'], $_POST['list_description'], $_POST['isProtect']);
		if ($result){echo 'ok';}else{echo 'error';}
	}
	function actDelList(){
		$t = getTwitter();
		$result = $t->deleteList($_POST['uname'], $_POST['id']);
		if ($result){echo 'ok';}else{echo 'error';}
	}
	function actFollowList(){
		$t = getTwitter();
		$result = $t->followList($_POST['uname'], $_POST['id']);
		if ($result){echo 'ok';}else{echo 'error';}
	}
	function actUnfollowList(){
		$t = getTwitter();
		$result = $t->unfollowList($_POST['uname'], $_POST['id']);
		if ($result){echo 'ok';}else{echo 'error';} 
	}
	function actAddMember(){
		$t = getTwitter();
		$result = $t->addListMember($_POST['member_list_name'], $_POST['list_members']);
		if ($result){echo 'ok';}else{echo 'error';} 
	}
	function actDelMember(){
		$t = getTwitter();
		$result = $t->deleteListMember($_POST['uname'], $_POST['id'], $_POST['memberid']);
		if($result){echo 'ok';}else{echo 'error';}
	}
	
	function actEditProfile(){
		$t = getTwitter();
		$result = $t->updateProfile($_POST['name'],$_POST['url'],$_POST['location'],$_POST['description']);
		if($result){echo 'ok';}else{echo 'error';}
	}
?>