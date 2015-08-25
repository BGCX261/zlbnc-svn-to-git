<?php
	include ('twitese.php');
	switch ($_GET['nc']){
		case 'friendsTimeline': outpFriendsTL(); break;
		case 'userTimeline': outpUsersTL(); break;
		case 'replies': outpReplies(); break;
		case 'messages': outpMessages(); break;
		case 'newMessage': newMessages(); break;
		case 'sentmessages': outpSentMessages(); break;
		case 'friends': outpFriends(); break;
		case 'followers': outpFollowers(); break;
		case 'norSearch': outpSearch(); break;
		case 'zhSearch': outpSearch(true); break;
		case 'followedLists': outpList(0); break;
		case 'createdLists': outpList(1); break;
		case 'beAddedLists': outpList(2); break;
		case 'listStatus': outpListStatus(); break;
		case 'favors': outpFavor(); break;
		case 'rank': outpRank(); break;
		case 'status': outpSta(); break;
		case 'listMember': outpListMember(); break;
		case 'listFollower': outpListFollower(); break;		
		case 'setting': outpSetting(); break;
	}
	
	function outpFriendsTL(){
		$t = getTwitter();
		if(isset($_GET['since_id'])){
			$twitterdata = $t->friendsTimeline($_GET['since_id']);
			if($twitterdata === false){echo 'error';}
			else{if(count($twitterdata) == 0){echo 'none';}else{echo builtStaus($twitterdata);}}
		}
		else if(isset($_GET['max_id'])){
			$twitterdata = $t->friendsTimeline(false, $_GET['max_id']);
			if($twitterdata === false){echo 'error';}
			else{if(count($twitterdata) == 0){echo 'none';}else{echo builtStaus($twitterdata);}}
		}
		else{
			$twitterdata = $t->friendsTimeline();
			if($twitterdata === false){echo builtPlus('error');}
			else{	if(count($twitterdata) == 0){echo builtPlus('none');}
					else{echo builtPlus('head').builtStaus($twitterdata).builtPlus('more');}
			}
		}
	}
	
	function outpUsersTL(){
		$t = getTwitter();
		if(isset($_GET['since_id'])){
			$twitterdata = $t->userTimeline(getCookie("nUser"), $_GET['since_id']);
			if($twitterdata === false){echo 'error';}
			else{if(count($twitterdata) == 0){echo 'none';}else{echo builtStaus($twitterdata);}}
		}
		else if(isset($_GET['max_id'])){
			$twitterdata = $t->userTimeline(getCookie("nUser"), false, $_GET['max_id']);
			if($twitterdata === false){echo 'error';}
			else{if(count($twitterdata) == 0){echo 'none';}else{echo builtStaus($twitterdata);}}
		}
		else{
			$uid = isset($_GET['id']) ? $_GET['id'] : null;
			$twitterdata = $t->userTimeline($uid);
			if($twitterdata === false){echo builtPlus('error');}else{
				$showSta = true;
				echo '<div id="content">';
				echo '<div id="info_head"><div id="info_name">' . $uid . '</div><ul id="user_info">';
				if($uid && $uid != getEncryptCookie('twitterID')){//不是自己
					if ($twitterdata == 'protected'){//受到保护
						echo '<li>这家伙设置了信息保护</li>';
						echo '<li>你要让他接受你的批示（关注你）才可查看。</li>';
						$showSta = false;//无法列出消息
					}else{//不受保护
						echo '<div id="info_headimgDiv"><img id="info_headimg" src="' . $twitterdata[0]->user->profile_image_url . '" /></div>';
						echo '<li>昵称：' . $twitterdata[0]->user->name . '<a class="relation" href="javascript:void(0)"> [关系]</a></li>';
						if($twitterdata[0]->user->location){
							echo '<li>地区：' . $twitterdata[0]->user->location . '</li>';}
						if($twitterdata[0]->user->url){
							echo '<li>主页：<a href="' . $twitterdata[0]->user->url . '" target="_blank">' . $twitterdata[0]->user->url  . '</a></li>';}
						if($twitterdata[0]->user->description){
							echo '<li>简介：' . $twitterdata[0]->user->description . '</li>';}
						echo '<li>接收<a href="javascript:void(0)" class="pFriend">' . $twitterdata[0]->user->friends_count . '人</a>的批示，';
							echo '向<a href="javascript:void(0)" class="pFollower">' . $twitterdata[0]->user->followers_count . '人</a>作批示</li>';
							echo '<li>已作'.$twitterdata[0]->user->statuses_count . '条</a>批示 [<a href="javascript:void(0)" class="pListF">查看其名单</a>] [<a href="javascript:void(0)" class="pFavor">查看其收藏</a>]</li>';						
					}
					echo '</ul><div class="tmenu"><ul>';
					if($t->isFriend($uid, getEncryptCookie('twitterID'))){//已被关注?
						echo '<li style="width:30%"><a href="javascript:void(0)" id="info_hide_btn">隐藏@</a></li>';
						echo '<li style="width:40%"><a href="javascript:void(0)" id="info_reply_btn">回复TA</a></li>';
						echo '<li style="width:30%;"><a href="javascript:void(0)" id="info_send_btn">发暗号</a></li>';
					}else{
						echo '<li style="width:40%"><a href="javascript:void(0)" id="info_hide_btn">隐藏@</a></li>';
						echo '<li style="width:60%"><a href="javascript:void(0)" id="info_reply_btn">回复TA</a></li>';
					}
					echo '</ul></div>';
				}else{//是自己
					$user = $t->showUser();
					echo '<li>昵称：<span id="my_name">' . $user->name . '</span></li>';
					echo '<li>地区：<span id="my_loc">' . $user->location . '</span></li>';
					echo '<li>主页：<span id="my_url">' . $user->url. '</span></li>';
					echo '<li>简介：<span id="my_des">' . $user->description . '</span></li>';
					echo '</ul><input type="submit" id="modify_btn" value="修改个人资料" />';
				}
				echo '</div>';
				if($showSta){
					if(count($twitterdata) == 0){echo builtPlus('noneol');}
					else{
						setCookie("nUser",$uid);
						echo builtPlus('headol').builtStaus($twitterdata).builtPlus('more');}
				}else{
					echo '</div>';
				}
			}
		}
	}
	
	function outpListStatus(){
		$t = getTwitter();
		if(isset($_GET['since_id'])){
			$twitterdata = $t->listStatus(getCookie("nUser"), getCookie("nList"), $_GET['since_id']);
			if($twitterdata === false){echo 'error';}
			else{if(count($twitterdata) == 0){echo 'none';}else{echo builtStaus($twitterdata);}}
		}
		else if(isset($_GET['max_id'])){
			$twitterdata = $t->listStatus(getCookie("nUser"), getCookie("nList"), false, $_GET['max_id']);
			if($twitterdata === false){echo 'error';}
			else{if(count($twitterdata) == 0){echo 'none';}else{echo builtStaus($twitterdata);}}
		}
		else{
			$uid = $_GET['id']; $lid = $_GET['listid'];
			$twitterdata = $t->listInfo($uid, $lid);
			if($twitterdata === false){echo builtPlus('error');}else{
				$showSta = true;
				echo '<div id="content"><div id="info_head">';
				echo '<div id="info_name">'.$twitterdata->name.'</div>
				<ul id="user_info">';
				if($twitterdata->mode != 'public'){//秘密名单
					echo '<li>这是<a class="user_name" href="javascript:void(0)">'.$twitterdata->user->screen_name.'</a>的秘密名单</li>';
					$showSta = false;//无法列出消息
				}else{//不受保护
					echo '<div id="info_headimgDiv"><img id="info_headimg" src="' . $twitterdata->user->profile_image_url . '" /></div>';
					echo '创建者：<a class="user_name" href="javascript:void(0)">'.$twitterdata->user->screen_name.'</a>';
					if($twitterdata->description){
						echo '<li>简介：' . $twitterdata->description . '</li>';}
					echo '<li><a href="javascript:void(0)" class="plistMember">'.$twitterdata->member_count.'人</a>的名单</li>';
					echo '<li>受到<a href="javascript:void(0)" class="plistFollower">'.$twitterdata->subscriber_count.'人</a>关注</li></ul>';						
				}
				echo '<div class="tmenu"><ul>';
				echo '<li style="width:50%"><a href="javascript:void(0)" class="unfollow_list">取消关注</a></li>';
				echo '<li style="width:50%"><a href="javascript:void(0)" class="follow_list">开始关注</a></li>';
				echo '</ul></div></ul><br /></div>';
				
				if($showSta){
					$twitterdata = $t->listStatus($uid, $lid);
					if(count($twitterdata) == 0){echo builtPlus('noneol');}
					else{
						setCookie("nUser",$uid);setCookie("nList",$lid);
						echo builtPlus('headol').builtStaus($twitterdata).builtPlus('more');
					}
				}else{
					echo '</div>';
				}
			}
		}
	}
	
	function newMessages(){
		$t = getTwitter();
		$twitterdata = $t->directMessages(getCookie("meSinceId"));
		if($twitterdata === false){echo 0;}else{
			if(count($twitterdata) == 0){echo 0;}else{
				setCookie("meSinceId",$twitterdata[0]->id); echo 1;
	}}}
	
	function outpReplies(){
		$t = getTwitter();
		if(isset($_GET['since_id'])){
			$twitterdata = $t->replies($_GET['since_id']);
			if($twitterdata === false){echo 'error';}
			else{if(count($twitterdata) == 0){echo 'none';}else{echo builtStaus($twitterdata);}}
		}
		else if(isset($_GET['max_id'])){
			$twitterdata = $t->replies(false, $_GET['max_id']);
			if($twitterdata === false){echo 'error';}
			else{if(count($twitterdata) == 0){echo 'none';}else{echo builtStaus($twitterdata);}}
		}
		else{
			$twitterdata = $t->replies();
			if($twitterdata === false){echo builtPlus('error');}else{
				if(count($twitterdata) == 0){echo builtPlus('none');}
					else{echo builtPlus('head').builtStaus($twitterdata).builtPlus('more');}
			}
		}
	}
	
	function outpMessages(){
		$t = getTwitter();
		if(isset($_GET['since_id'])){
			$twitterdata = $t->directMessages($_GET['since_id']);
			if($twitterdata === false){echo 'error';}
			else{if(count($twitterdata) == 0){echo 'none';}else{echo builtMessage($twitterdata);}}
		}
		else if(isset($_GET['max_id'])){
			$twitterdata = $t->directMessages(false, $_GET['max_id']);
			if($twitterdata === false){echo 'error';}
			else{if(count($twitterdata) == 0){echo 'none';}else{echo builtMessage($twitterdata);}}
		}
		else{
			$twitterdata = $t->directMessages();
			if($twitterdata === false){echo builtPlus('error');}else{
				echo '<div id="content"><br /><div class="tmenu"><ul>
				<li style="width:50%;background-color:#E6E6E6"><a href="javascript:void(0)" class="pMessage"><b>我收到的暗号</b></a></li>
				<li style="width:50%;background-color:#E6E6E6"><a href="javascript:void(0)" class="pSentMessage">我发出的暗号</a></li>
	   			</ul></div>';
				if(count($twitterdata) == 0){echo builtPlus('noneol');}
					else{echo builtPlus('headol').builtMessage($twitterdata).builtPlus('more');}
			}
		}
	}
	
	function outpSentMessages(){
		$t = getTwitter();
		if(isset($_GET['max_id'])){
			$twitterdata = $t->sentDirectMessage(false, $_GET['max_id']);
			if($twitterdata === false){echo 'error';}
			else{if(count($twitterdata) == 0){echo 'none';}else{echo builtMessage($twitterdata, true);}}
		}else{
			$twitterdata = $t->sentDirectMessage();
			if($twitterdata === false){echo builtPlus('error');}else{
				echo '<div id="content"><br /><div class="tmenu"><ul>
				<li style="width:50%;background-color:#E6E6E6"><a href="javascript:void(0)" class="pMessage">我收到的暗号</a></li>
				<li style="width:50%;background-color:#E6E6E6"><a href="javascript:void(0)" class="pSentMessage"><b>我发出的暗号</b></a></li>
	   			</ul></div>';
				if(count($twitterdata) == 0){echo builtPlus('noneol');}
					else{echo builtPlus('headol').builtMessage($twitterdata,true).builtPlus('more');}
			}
		}
	}
	
	function outpFriends(){
		$t = getTwitter();
		if(isset($_GET['max_id'])){
			$twitterdata = $t->friends(getCookie("nUser"), getCookie("nCursor"));
			if($twitterdata === false){echo 'error';}
			else{if(count($twitterdata) == 0){echo 'none';}else{echo builtPerson($twitterdata);}}
		}
		else{
			$uid = isset($_GET['id']) ? $_GET['id'] : null;
			$twitterdata = $t->friends($uid);
			if($twitterdata === false){echo builtPlus('error');}else{
				echo '<div id="content">';
				echo $uid ? "<h2>" . $uid . " 接收这些人的批示：</h2><br />": "<h2>你接收这些人的批示：</h2><br />";
				if(count($twitterdata) == 0){echo 'none';}else{
					setCookie("nUser",$uid);echo builtPlus('headol').builtPerson($twitterdata).builtPlus('more');
				}
			}
		}
	}
	
	function outpFollowers(){
		$t = getTwitter();
		if(isset($_GET['max_id'])){
			$twitterdata = $t->followers(getCookie("nUser"), getCookie("nCursor"));
			if($twitterdata === false){echo 'error';}
			else{if(count($twitterdata) == 0){echo 'none';}else{echo builtPerson($twitterdata);}}
		}
		else{
			$uid = isset($_GET['id']) ? $_GET['id'] : null;
			$twitterdata = $t->followers($uid);
			if($twitterdata === false){echo builtPlus('error');}else{
				echo '<div id="content">';
				echo $uid ? "<h2>接收 " . $uid . " 批示的是这些人：</h2><br />": "<h2>接收你批示的是这些人：</h2><br />";
				if(count($twitterdata) == 0){echo 'none';}else{
					setCookie("nUser",$uid);echo builtPlus('headol').builtPerson($twitterdata).builtPlus('more');
				}
			}
		}
	}
	
	function outpSearch( $zh = false){
		$t = getTwitter();
		if(isset($_GET['max_id'])){
			if($zh){$twitterdata = $t->search(getCookie("nUser"), getCookie("nCursor"), "zh");}
			else{$twitterdata = $t->search(getCookie("nUser"), getCookie("nCursor"));}
			if($twitterdata === false){echo 'error';}
			else{if(count($twitterdata) == 0){echo 'none';}
					else{$nextp = (int)(getCookie("nCursor"))+1; setCookie("nCursor", $nextp);echo builtStaus($twitterdata->results,true);}}
		}else{
			$q = $_GET['id'];
			if($zh){$twitterdata = $t->search($q, 1, "zh");}
			else{$twitterdata = $t->search($q, 1);}
			if($twitterdata === false){echo builtPlus('error');}else{
				echo '<div id="content">';
				setCookie("nUser",$q);setCookie("nCursor", 2);
				echo builtPlus('headol').builtStaus($twitterdata->results,true).builtPlus('more');
			}
		}		
	}
	
	function outpFavor(){
		$t = getTwitter();
		if(isset($_GET['max_id'])){
			$twitterdata = $t->getFavorites(getCookie("nUser"), getCookie("nCursor"));
			if($twitterdata === false){echo 'error';}
			else{if(count($twitterdata) == 0){echo 'none';}
					else{$nextp = (int)(getCookie("nCursor"))+1; setCookie("nCursor", $nextp);echo builtStaus($twitterdata);}}
		}
		else{
			$uid = isset($_GET['id']) ? $_GET['id'] : null;
			$twitterdata = $t->getFavorites($uid);
			if($twitterdata === false){echo builtPlus('error');}else{
				echo '<div id="content">';
				echo $uid ? "<h2> " . $uid . " 的收藏：</h2><br />": "<h2>你的收藏：</h2><br />";
				setCookie("nUser",$uid);setCookie("nCursor", 2);
				echo builtPlus('headol').builtStaus($twitterdata).builtPlus('more');
			}
		}
	}
	
	function outpRank(){
		$t = getTwitter();
		if(isset($_GET['max_id'])){
			$twitterdata = $t->rank(getCookie("nCursor"));
			if($twitterdata === false){echo 'error';}
			else{if(count($twitterdata) == 0){echo 'none';}else{
				$nextp = (int)(getCookie("nCursor"))+1; setCookie("nCursor", $nextp);
				echo builtPerson($twitterdata, true);}
			}
		}
		else{
			$twitterdata = $t->rank();
			if($twitterdata === false){echo builtPlus('error');}else{
				echo '<div id="content">';
				if(count($twitterdata) == 0){echo 'none';}else{
					setCookie("nCursor",2);echo builtPlus('headol').builtPerson($twitterdata, true).builtPlus('more');
				}
			}
		}
	}
	
	function outpList($cho){
		$t = getTwitter();		
		if(isset($_GET['max_id'])){
			$twitterdata = $t->listLists($cho,getCookie("nUser"), getCookie("nCursor"));
			if($twitterdata === false){echo 'error';}
			else{if(count($twitterdata) == 0){echo 'none';}else{echo builtList($cho, getCookie("nUser"), $twitterdata);}}
		}
		else{
			$uid = isset($_GET['id']) ? $_GET['id'] : null;
			$who = $uid ? 'Ta' : '我';
			if($cho == 0){$boldFa='<b>';$boldFb='</b>';$boldCa='';$boldCb='';$boldAa='';$boldAb='';}
			else if($cho == 1){$boldFa='';$boldFb='';$boldCa='<b>';$boldCb='</b>';$boldAa='';$boldAb='';}
			else{$boldFa='';$boldFb='';$boldCa='';$boldCb='';$boldAa='<b>';$boldAb='</b>';}
			$twitterdata = $t->listLists($cho,$uid);
			if($twitterdata === false){echo builtPlus('error');}else{
		       	echo '<div id="content"><br /><div class="tmenu"><ul>';
		       	if($uid){echo ' <span id="theOne" style="display:none">'.$uid.'</span>';}
		       	echo '
		<li style="width:30%;background-color:#E6E6E6"><a href="javascript:void(0)" class="pListF">'.$boldFa.$who.'关注的名单'.$boldFb.'</a></li>
		<li style="width:40%;background-color:#E6E6E6"><a href="javascript:void(0)" class="pListC">'.$boldCa.$who.'创建的名单'.$boldCb.'</a></li>
		<li style="width:30%;background-color:#E6E6E6"><a href="javascript:void(0)" class="pListA">'.$boldAa.$who.'在内的名单'.$boldAb.'</a></li>
					</ul></div>';
		       	if($cho == 1 && !$uid){echo '<input type="submit" id="list_create_btn" value="【创建名单】" />';}
				setCookie("nUser",$uid);echo builtPlus('headol').builtList($cho, $uid, $twitterdata).builtPlus('more');
			}
		}
	}
	
	function outpSta(){
		$t = getTwitter();
		$statusid = $_GET['id'];
		$status = $t->showStatus($statusid);
		if (!$status) {
			header('location: error.php');
		}
		$user = $status->user;
		$date = formatDate($status->created_at);
		$text = formatText($status->text);
	}
	
	function outpListMember(){
		$t = getTwitter();
		if(isset($_GET['max_id'])){
			$twitterdata = $t->listMembers(getCookie("nUser"), getCookie("nList"), getCookie("nCursor"));
			if($twitterdata === false){echo 'error';}
			else{if(count($twitterdata) == 0){echo 'none';}else{echo builtPerson($twitterdata);}}
		}else{
			$uid = $_GET['id']; $lid = $_GET['listid'];
			$twitterdata = $t->listMembers($uid, $lid);
			if($twitterdata === false){echo builtPlus('error');}else{
				echo '<div id="content">';
				echo '<h2>' . $uid . '的名单'.$lid.'包括这些人：</h2><br />';
				if(count($twitterdata) == 0){echo 'none';}else{
					setCookie("nUser",$uid);setCookie("nList",$lid);
					echo builtPlus('headol').builtPerson($twitterdata).builtPlus('more');
				}
			}
		}
	}
	
	function outpListFollower(){
		$t = getTwitter();
		if(isset($_GET['max_id'])){
			$twitterdata = $t->listFollowers(getCookie("nUser"), getCookie("nList"), getCookie("nCursor"));
			if($twitterdata === false){echo 'error';}
			else{if(count($twitterdata) == 0){echo 'none';}else{echo builtPerson($twitterdata);}}
		}else{
			$uid = $_GET['id']; $lid = $_GET['listid'];
			$twitterdata = $t->listFollowers($uid, $lid);
			if($twitterdata === false){echo builtPlus('error');}else{
				echo '<div id="content">';
				echo '<h2>关注' . $uid . '的名单'.$lid.'的是这些人：</h2><br />';
				if(count($twitterdata) == 0){echo 'none';}else{
					setCookie("nUser",$uid);setCookie("nList",$lid);
					echo builtPlus('headol').builtPerson($twitterdata).builtPlus('more');
				}
			}
		}
	}
	
	function outpSetting(){
		echo '<div id="content"><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /></div>';
	}
	
	function builtPlus($cond){
		switch ($cond) {
			case 'error':
				return '<div id="content"><br /><p> 未知错误，请 <a target="_self" href="index.php">重试</a></p></div>';
			break;
			case 'none':
				return '<div id="content"><br /><p> 此页无内容，或者发生未知错误</p></div>';
			break;
			case 'noneol':
				return '<div id="content"><br /><p> 此页无内容，或者发生未知错误</p></div>';
			break;
			case 'head':
				return '<div id="content"><ol class="timeline" id="allTimeline">';
			break;
			case 'headol':
				return '<ol class="timeline" id="allTimeline">';
			break;
			case 'more':
				return 	'</ol><span id="more_btn">【更多】</span><br /><br /></div>';
			break;
			case 'nomore':
				return 	'</ol></div>';
			break;
		}
	}
	
	function builtStaus( $allData, $isearch = false){
		$output = '';
		if(isset($_GET['max_id'])){$lineC = 1;}
		else if(isset($_GET['since_id'])){$lineC = 2; $numC = 0;}
		else{$lineC = 0;}
		foreach ($allData as $status) {
			if(!$isearch){
				$user = $status->user; $theName = $user->screen_name; $imgUrl = $user->profile_image_url;
			}else{$theName = $status->from_user; $imgUrl = $status->profile_image_url;}
			$date = formatDate($status->created_at);
			$text = formatText($status->text);
			
			if($lineC == 0){
				$output .= '<li>';
			}else if($lineC == 1){
				$output .= '<li style="border-top:1px dashed #CC0033;margin-top:10px">';
				$lineC = 0;
			}else{
				if (++$numC == count($allData)){
					$output .= '<li style="background-color:#f9f6ea;border-bottom:1px solid #ccc">';
				}else{ 
					$output .= '<li style="background-color:#f9f6ea">';
				}
			}
						
			$output .= '
				<span class="st_author">
					<a href="javascript:void(0)">';
			$output .= "<img src=\"$imgUrl\" class=\"pimg\" title=\"$theName\" />";
		$output .= '</a>
				</span>
				<span class="st_body">';
		$output .= "<span class=\"status_id\">$status->id</span>
					<span class=\"status_word\"><a class=\"user_name\" href=\"javascript:void(0)\">$theName</a> $text</span>";
				$shorturl = unshortUrl($status->text);
				if($shorturl != false){
			$output .= "
					<span class=\"unshorturl\">$shorturl</span>";
				}
			$output .= '
					<span class="status_info">
						<a class="replie_btn" href="javascript:void(0)">回复</a>
						<a class="rt_btn" href="javascript:void(0)">传达</a>
						<a class="favor_btn" href="javascript:void(0)">收藏</a>';
			if ($theName == getEncryptCookie('twitterID') && ((getCookie("pageType")!='favors'))){
			$output .= '<a class="delete_btn" href="javascript:void(0)">删除</a>';}
			if(!$isearch){if ($status->in_reply_to_status_id){
			$output .= '<span class="in_reply_to">对'. $status->in_reply_to_screen_name.'的回复</span>';}
			$output .= '<span class="source">通过'. $status->source.'</span>';}
			$output .= '<span class="date">'.$date.'</span>';
			$output .= '</span>
				</span>
			</li>';
		}
		return $output;
	}
	
	function builtPerson( $allData, $isrank =false ){
		$output = '';
		if(!$isrank){
			setCookie("nCursor", $allData->next_cursor);
			$users = $allData->users;
		}else{$users = $allData;}
		foreach ($users as $user) {
			if(!$isrank){$imgUrl = $user->profile_image_url;}
			else{$imgUrl = $user->profile_img_url;}
						
				$output .= '
				<li>
					<span class="st_author">
						<a href="javascript:void(0)">';
				$output .= "<img src=\"$imgUrl\" class=\"pimg\" title=\"$user->screen_name\" />";
			$output .= '</a>
					</span>
					<span class="st_body"><span class="status_word">';
			$output .= "$user->name (<a class=\"user_name\" href=\"javascript:void(0)\">$user->screen_name</a>)";
			$output .= '<a class="relation" href="javascript:void(0)"> [关系]</a>';
				 $output .="<br />接收 <a href=\"javascript:void(0)\" class=\"pFriend\">$user->friends_count</a> 人的批示<br />已向 <a href=\"javascript:void(0)\" class=\"pFollower\">$user->followers_count</a> 人作了 $user->statuses_count 条批示<br />";
			if($user->description){$output .= "简介：$user->description";}
			if(getCookie("pageType") == 'listMember'){
				if (getCookie("nUser") == getEncryptCookie('twitterID')){
					$output .= '<span class="status_info"><a class="delete_btn" href="javascript:void(0)">删除</a></span>';
				}
			}
			
			$output .= '</span>
					</span>
				</li>';
		}
		return $output;
	}
	
	function builtList( $whi, $tID, $allData){
		$output = '';
		setCookie("nCursor", $allData->next_cursor);
		$lists = $allData->lists;
		foreach ($lists as $list){
			$user = $list->user;
			$mode = $list->mode == 'private' ? "（秘密名单）" : "";
			$output .= '
				<li>
					<span class="st_author">
						<a href="javascript:void(0)">';
				$output .= "<img src=\"$user->profile_image_url\" class=\"pimg\" title=\"$user->screen_name\" />";
			$output .= '</a>
					</span>
					<span class="st_body"><span class="status_word">';
			$output .= '<a class="user_name" href="javascript:void(0)">'.$user->screen_name.'</a>创建的<a class="list_name" href="javascript:void(0)">'.$list->name.'</a>';
			$output .= '<br />'.$list->member_count.'人名单，受到'.$list->subscriber_count.'人关注'.$mode;
			if($list->description != ''){$output .= "<br />简介：<span class=\"list_desc\">$list->description</span>";}
			if(!$tID){
				if($whi == 0){
					$output .= '<span class="status_info"><a href="javascript:void()" class="unfollow_list">取消关注</a></span>';
				}
				if($whi == 1){
					$output .= '<span class="status_info"><a href="javascript:void()" class="edit_list">编辑名单</a>
									<a href="javascript:void()" class="add_member">添加成员</a>
									<a href="javascript:void()" class="delete_btn">删除名单</a>
									</span>';
				}
			}
			$output .= '</span></span>
				</li>';
		}
		return $output;
	}
	
	function builtMessage( $allData , $sent = false){
		$output = '';
		foreach ($allData as $message) {
			$name = $message->sender_screen_name;
			$imgurl = $message->sender->profile_image_url;
			$date = formatDate($message->created_at);
			$text = formatText($message->text);
			
			$output .= '<li><span class="st_author">';
			$output .= '<a href="javascript:void(0)"><img src="'.$imgurl.'" title="'.$name.'" /></a></span>';
			$output .= '<span class="st_body"><span class="status_id">'.$message->id.'</span>';
			$output .= '<span class="status_word"><a class="user_name" href="javascript:void(0)">'.$name.'</a>'.$text.
						'</span><span class="status_info">';
			if(!$sent){
				$output .= '<a class="msg_replie_btn" href="javascript:void(0)">回复</a>';
			} else {
				$output .= '<a class="delete_btn" href="javascript:void(0)">删除</a>';
			}
			$output .= '<span class="date">'.$date.'</span></span></span></li>';
		}
		return $output;
	}
?>