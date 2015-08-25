$(function(){
	formFunc();
	timelineFocus();
	ncPage("friendsTimeline");
	$(function(){
		setInterval(function(){ncNew();}, 1000*60);
		//setInterval(function(){NewMessage();}, 1000*300);
	});

	//标题图显示或隐藏
	$("#hlogo").live("click", function(){
		$("#hlogo").replaceWith('<div id="hidelogo"></div>');
		$("#main").css("padding-top", "65px");
	});
	$("#hidelogo").live("click", function(){
		$("#hidelogo").replaceWith('<div id="hlogo"><img src="img/logo.png" /></div>');
		$("#main").css("padding-top", "110px");
	});
	
	//个人设置
	$("#modify_btn").live("click", function(){
		setCookie("my_name",$("#my_name").text());
		setCookie("my_loc",$("#my_loc").text());
		setCookie("my_url",$("#my_url").text());
		setCookie("my_des",$("#my_des").text());
		$("#user_info").replaceWith('<ul id="user_info">'
				+ '<li>昵称：<input class="setting_input" type="text" id="ProName" value="' + getCookie("my_name") + '" /></li>'
				+ '<li>地区：<input class="setting_input" type="text" id="ProLocation" value="' + getCookie("my_loc") + '" /></li>'
				+ '<li>主页：<input class="setting_input" type="text" id="ProUrl" value="' + getCookie("my_url") + '" /></li>'
				+ '<li>简介：<textarea id="setting_text">' + getCookie("my_des") + '</textarea></li></ul>');
		$("#modify_btn").replaceWith('<input type="submit" style="width:50%" id="cancel_btn" value="取消" /><input type="submit" style="width:50%" id="save_btn" value="保存" />');
	});
	$("#save_btn").live("click", function(){
		$("#sentTip").remove();
		$("#content").prepend('<div id="sentTip"> 修改资料中......</div>');
		
		var sentData = "nc=editProfile&name=" + $("#ProName").val() + "&url=" + $("#ProUrl").val()
						+ "&location=" + $("#ProLocation").val() +"&description=" + $("#setting_text").val();
		$.ajax({
			url: "lib/postVar.php",
			type: "POST",
			data: sentData,
			success: function(msg){				
				if (msg == 'ok') {
					$("#sentTip").replaceWith('<div id="sentTip"> 修改成功。</div>');
					$("#sentTip").fadeOut(1500);
					setCookie("my_name",$("#ProName").val());
					setCookie("my_loc",$("#ProLocation").val());
					setCookie("my_url",$("#ProUrl").val());
					setCookie("my_des",$("#setting_text").val());
					$("#user_info").replaceWith('<ul id="user_info">'
							+ '<li>昵称：<span id="my_name">' + getCookie("my_name") + '</span></li>'
							+ '<li>地区：<span id="my_loc">' + getCookie("my_loc") + '</span></li>'
							+ '<li>主页：<span id="my_url">' + getCookie("my_url") + '</span></li>'
							+ '<li>简介：<span id="my_des">' + getCookie("my_des") + '</span></li></ul>');
					$("#cancel_btn").replaceWith('<input type="submit" id="modify_btn" value="修改个人资料" />');
					$("#save_btn").remove();
				} else {
					$("#sentTip").replaceWith('<div id="sentTip"> 未知错误。</div>');
				}
			},
			error: function(msg) {
				$("#sentTip").replaceWith('<div id="sentTip"> 出错，请重试。</div>');
			}
		});
	});
	$("#cancel_btn").live("click", function(){
		$("#user_info").replaceWith('<ul id="user_info">'
				+ '<li>昵称：<span id="my_name">' + getCookie("my_name") + '</span></li>'
				+ '<li>地区：<span id="my_loc">' + getCookie("my_loc") + '</span></li>'
				+ '<li>主页：<span id="my_url">' + getCookie("my_url") + '</span></li>'
				+ '<li>简介：<span id="my_des">' + getCookie("my_des") + '</span></li></ul>');
		$("#cancel_btn").replaceWith('<input type="submit" id="modify_btn" value="修改个人资料" />');
		$("#save_btn").remove();
	});
	
	//样式设置
	$("#style_save").live("click", function(){
		$("#sentTip").remove();
		$("#content").prepend('<div id="sentTip"> 修改样式中......</div>');

		setCookie("headerBg", $("#headerBg").val());
		setCookie("bodyBg", $("#bodyBg").val());
		setCookie("linkColor", $("#linkColor").val());
		setCookie("linkHColor", $("#linkHColor").val());
		setCookie("wordColor", $("#wordColor").val());
		
		$("#sentTip").replaceWith('<div id="sentTip"> setcookie</div>');
		$("#sentTip").fadeOut(1500);
		
		$("#hlogo").css("background-color",$("#headerBg").val());
		$(".tmenu").css("border-bottom-color",$("#headerBg").val());
		$('input[type="submit"]').css("background-color",$("#headerBg").val());
		$("#senttw").css("border-bottom-color",$("#headerBg").val());
		$("#footer").css("background-color",$("#headerBg").val());		
		$("#content").css("background-color",$("#bodyBg").val());
		$("body").css("background-color",$("#headerBg").val());
		
		$("#sentTip").replaceWith('<div id="sentTip"> 修改成功。</div>');
		$("#sentTip").fadeOut(1500);
		window.location.reload();
	});
	$("#style_reset").live("click", function(){
		$("#sentTip").remove();
		$("#content").prepend('<div id="sentTip"> 重置样式中......</div>');
		
		setCookie("headerBg",'');
		setCookie("bodyBg",'');
		setCookie("linkColor",'');
		setCookie("linkHColor",'');
		setCookie("wordColor",'');
		
		$("#hlogo").css("background-color","#CC0033");
		$(".tmenu").css("border-bottom-color","#CC0033");
		$('input[type="submit"]').css("background-color","#CC0033");
		$("#senttw").css("border-bottom-color","#CC0033");
		$("#footer").css("background-color","#CC0033");		
		$("#content").css("background-color","#FFFFFF");
		$("body").css("background-color","#CC0033");
		
		$("#sentTip").replaceWith('<div id="sentTip"> 修改成功。</div>');
		$("#sentTip").fadeOut(1500);
		window.location.reload(); 
	});
	
	//人名链接
	$(".theUser").live("click", function(){ncPage("userTimeline");});
	$(".st_author").live("click", function(){
		var theId = $(this).parent().find(".st_body").find(".status_word").find(".user_name").text();
		ncPage("userTimeline", theId);
	});
	$(".user_name").live("click", function(){
		var theId = $(this).text();
		if ($(this).text().indexOf("@") == 0){theId = $(this).text().slice(1);}		
		ncPage("userTimeline", theId);
	});
	$(".per_name").live("click", function(){
		var theId = $(this).text().slice(1);
		ncPage("userTimeline", theId);
	});
	$(".tw_label").live("click", function(){
		var theId = $(this).text().slice(1);
		ncPage("norSearch", theId);
	});
	//更多
	$("#more_btn").live("click", function(){ncMore();});
	
	//用户小菜单
	$(".relation").live("click", function(){
		var theId = $(this).parent().find(".user_name").text();
		if(!theId){theId = $(this).parent().parent().parent().find("#info_name").text();}
		$(this).replaceWith('<span id="' + theId + 'relt"> [检索中..]</span>');
		$.ajax({
			url: "lib/getVar.php",
			type: "GET",
			dataType: "text",
			data: "nc=relation" + "&id=" +theId ,
			success:function(msg){
				$("#" + theId + "relt").replaceWith(msg);
		}});
	});
	
	$(".pFriend").live("click", function(){
		var theId = $(this).parent().find(".user_name").text();
		if(!theId){theId = $(this).parent().parent().parent().find("#info_name").text();}
		ncPage("friends", theId);
	});
	$(".pFollower").live("click", function(){
		var theId = $(this).parent().find(".user_name").text();
		if(!theId){theId = $(this).parent().parent().parent().find("#info_name").text();}
		ncPage("followers", theId);
	});
	$(".pReply").live("click", function(){ncPage("replies");});
	$(".pMessage").live("click", function(){ncPage("messages");$("#sentTip").remove();});
	$(".pSentMessage").live("click", function(){ncPage("sentmessages");});
	$(".pFavor").live("click", function(){
		var theId = $(this).parent().parent().parent().find("#info_name").text();
		ncPage("favors", theId);
	});
	
	//名单列表	
	$(".pListF").live("click", function(){
		var theId = $(this).parent().parent().find("#theOne").text();
		if(!theId){theId = $(this).parent().parent().parent().find("#info_name").text();}
		ncPage("followedLists", theId);
	});
	$(".pListC").live("click", function(){
		var theId = $(this).parent().parent().find("#theOne").text();
		if(!theId){theId = $(this).parent().parent().parent().find("#info_name").text();}
		ncPage("createdLists", theId);
	});
	$(".pListA").live("click", function(){
		var theId = $(this).parent().parent().find("#theOne").text();
		if(!theId){theId = $(this).parent().parent().parent().find("#info_name").text();}
		ncPage("beAddedLists", theId);
	});	
	$(".list_name").live("click", function(){
		var theId = $(this).parent().find(".user_name").text();
		var listId = $(this).text();
		ncPage("listStatus", theId, listId);
	});
	$(".plistMember").live("click", function(){
		var theId = $(this).parent().parent().find(".user_name").text();
		var listId = $(this).parent().parent().parent().find("#info_name").text();
		ncPage("listMember", theId, listId);
	});
	$(".plistFollower").live("click", function(){
		var theId = $(this).parent().parent().find(".user_name").text();
		var listId = $(this).parent().parent().parent().find("#info_name").text();
		ncPage("listFollower", theId, listId);
	});	
	
	//用户大菜单
	$("#pMain").click(function(){ncPage("friendsTimeline");});
	$("#searchBtn").click(function(){$("#header_search").toggle(500);});
	$("#sentBtn").live("click", function(){$("#senttw").toggle(500);});
	$("#pSetting").click(function(){ncPage("setting");});
	$("#pLogout").click(function(){location.href='lib/getVar.php?nc=logout';});
	
	$("#photoBtn").live("click", function(){$("#photoArea").toggle(500);});
	$("#linkBtn").live("click", function(){$("#linkArea").toggle(500);});
	$("#imageUploadSubmit").live("click", function(){ajaxFileUpload();});
	$("#linkSubmit").live("click", function(){shortUrl();});
	
	//搜索类
	$("#normal_search_submit").click(function(){
			if($("#normal_search_query").val()){ncPage("norSearch", $("#normal_search_query").val());}
	});
	$("#chinese_search_submit").click(function(){
			if($("#chinese_search_query").val()){ncPage("zhSearch", $("#chinese_search_query").val());}
	});
	$("#user_search_submit").click(function(){
			ncPage("userTimeline", $("#user_search_query").val());
	});
	$("#pRank").click(function(){ncPage("rank");});
	
	//发送消息
	$("#submit_btn").click(function(){onSend();});
	
	//操作按钮	
	$(".replie_btn").live("click", function(){$("#senttw").toggle(500);onReplie($(this));});
	$("#info_reply_btn").live("click", function(){$("#senttw").toggle(500);onReplie($(this), true);});
	
	$(".rt_btn").live("click", function(){$("#senttw").toggle(500);onRT($(this));});
	
	$(".msg_replie_btn").live("click", function(){$("#senttw").toggle(500);onMSG($(this));});
	$("#info_send_btn").live("click", function(){$("#senttw").toggle(500);onMSG($(this), true);});
	
	$(".favor_btn").live("click", function(){onFavor($(this));});
	$(".delete_btn").live("click", function(){onDelete($(this));});
	
	if(getCookie("infoShow") == "hide"){onHide();}
	$("#info_hide_btn").live("click", function(){onHide();});
	
	$(".follow_btn").live("click", function(){onFo($(this),"addFriend");});
	$(".unfoll_btn").live("click", function(){onFo($(this),"delFriend");});
	
	$(".follow_list").live("click", function(){followlist($(this));});
	$(".unfollow_list").live("click", function(){followlist($(this), true);});

	$("#list_create_btn").live("click", function(){
		$("#list_create_btn").replaceWith(
				'<span id="creatListForm">名称：<br /><input type="text" name="list_name" id="list_name" />'
		    	+'<br />描述：<br /><textarea name="list_description" id="list_description"></textarea>'
		    	+'<br />秘密：<input type="checkbox" name="list_protect" id="list_protect" />'
		    	+'<br /><input type="submit" style="width:50%" id="cancel_list" value="取消" /><input type="submit" style="width:50%" id="add_list" value="【创建名单】" /></span>'
		);
	});	
	$(".edit_list").live("click", function(){
		theListname = $(this).parent().parent().find(".list_name").text();
		theListdesc = $(this).parent().parent().find(".list_desc").text();
		$("#list_create_btn").replaceWith(
				'<span id="creatListForm">名称：<br /><input type="text" name="list_name" id="list_name" value="'
				+ theListname +'" />'
				+'<input type="hidden" id="preListname" name="preListname" value="'
				+ theListname +'" />'
		    	+'<br />描述：<br /><textarea name="list_description" id="list_description">'
		    	+ theListdesc + '</textarea>'
		    	+'<br />秘密：<input type="checkbox" name="list_protect" id="list_protect" />'
		    	+'<br /><input type="submit" style="width:50%" id="cancel_list" value="取消" /><input type="submit" style="width:50%" id="save_list" value="【修改名单】" /></span>'
		);
	});
	$(".add_member").live("click", function(){
		theListname = $(this).parent().parent().find(".list_name").text();
		$("#list_create_btn").replaceWith(
				'<span id="creatListForm">向'
				+ theListname +'添加成员：<br /><input type="text" name="member_name" id="member_name" />'
				+'<input type="hidden" id="list_name" name="list_name" value="'
				+ theListname +'" />'
		    	+'<br /><input type="submit" style="width:50%" id="cancel_list" value="取消" /><input type="submit" style="width:50%" id="save_member" value="【添加成员】" /></span>'
		);
	});
	
	$("#cancel_list").live("click", function(){$("#creatListForm").replaceWith('<input type="submit" id="list_create_btn" value="创建名单" />');});
	$("#add_list").live("click", function(){creatlist();});
	$("#save_list").live("click", function(){editlist();});	
	$("#save_member").live("click", function(){addmember();});
	
	$('.style_input').ColorPicker({
	    onBeforeShow:function(){$(this).ColorPickerSetColor(this.value);},
		onSubmit: function(hsb, hex, rgb, el){$(el).val("#" + hex);$(el).ColorPickerHide();}
	}).bind('keyup', function(){$(this).ColorPickerSetColor(this.value);});
	var style = {
			"红军":{headerBg:"#CC0033", bodyBg:"#FFFFFF", linkColor:"#990000", linkHColor:"#770000", wordColor:"#000000"},
			"蓝营":{headerBg:"#6699CC", bodyBg:"#FFFFFF", linkColor:"#0066CC", linkHColor:"#99CCFF", wordColor:"#333366"},
			"绿营":{headerBg:"#003300", bodyBg:"#CCCC99", linkColor:"#336633", linkHColor:"#009933", wordColor:"#003300"},
			"黑白":{headerBg:"#001100", bodyBg:"#FFFFEE", linkColor:"#999999", linkHColor:"#000000", wordColor:"#333333"}};
	$.each(style, function (i,o){$("#styleSelect").append('<option value="' + i + '">' + i + '</option>');});
	$("#styleSelect").change(function(){
	    if ($(this).val() != "n/a") {
	        $.each(style[$(this).val()],function(i,o){$("#"+i).val(o);});
	    }
	});
});

function timelineFocus(){
	$(".timeline").find("li").live("mouseover", function(){
		$(this).css("background-color", "#f5f5f5");
		$(this).find(".replie_btn").css("display", "inline-block");
		$(this).find(".rt_btn").css("display", "inline-block");
		$(this).find(".favor_btn").css("display", "inline-block");
		$(this).find(".delete_btn").css("display", "inline-block");
		$(this).find(".msg_replie_btn").css("display", "inline-block");
	});

	$(".timeline").find("li").live("mouseout", function(){
		$(this).css("background-color", "#FFFFFF");
		$(this).find(".replie_btn").css("display", "none");
		$(this).find(".rt_btn").css("display", "none");
		$(this).find(".favor_btn").css("display", "none");
		$(this).find(".delete_btn").css("display", "none");
		$(this).find(".msg_replie_btn").css("display", "none");
	});
}

function getCookie(name){
     var strCookie=document.cookie;
     var arrCookie=strCookie.split("; ");
     for(var i=0;i<arrCookie.length;i++){
           var arr=arrCookie[i].split("=");
           if(arr[0]==name)return unescape(arr[1]);
     }
     return "";
}
function setCookie(name,value,expireHours){
	var cookieString=name+"="+escape(value);
	if(expireHours>0){
		var date=new Date();
		date.setTime(date.getTime+expireHours*3600*1000);
		cookieString=cookieString+"; expire="+date.toGMTString();
	}
     document.cookie=cookieString;
} 
function onHide(){
	$this = $("#info_hide_btn");
	$this.after('<a href="javascript:void(0)" id="info_show_btn">显示@</a>');
	$this.remove();
	
	$("#info_show_btn").click(function(){
		$(".timeline li").each(function(i,o) {
			$(this).show();
		});
		$(this).after('<a href="javascript:void(0)" id="info_hide_btn">隐藏@</a>');
		$(this).remove();
		$("#info_hide_btn").live("click", function(){
			onHide();
		});
		setCookie("infoShow","show");
	});
	
	$(".timeline li").each(function(i,o) {
		if ($(this).find(".status_word").text().indexOf("@") > -1) {
			$(this).hide();
		}
	});
	setCookie("infoShow","hide");
}

function ajaxFileUpload()
{
	$("#sentTip").remove();
	$("#content").prepend('<div id="sentTip"> 正在上传图片......</div>');
	
    $.ajaxFileUpload({
        url:'lib/getVar.php?nc=unloadPic',
        secureuri:false,
        fileElementId:'imageFile',
        dataType: 'json',
        success: function (data, status)
        {
            if(typeof(data.result) != 'undefined' && data.result == "success") {
            	$("#textbox").val($("#textbox").val() + data.url);
            	$("#sentTip").replaceWith('<div id="sentTip"> 发送成功。</div>');
				$("#sentTip").fadeOut(1500);
        		$("#photoArea").fadeOut(1500);
            } else {
            	$("#sentTip").replaceWith('<div id="sentTip"> 图片上传失败，请重试。</div>');
            }
        }
    });
    return false;
}  

function shortUrl() {
	$("#sentTip").remove();
	$("#content").prepend('<div id="sentTip"> 正在缩短网址......</div>');
		
	var longurl = $("#longurl").val();
	var type = $("#shortUrlType").val();
	$.ajax({
		url: "lib/postVar.php",
		type: "POST",
		dataType: "text",
		data: "nc=shortUrl&longurl=" + longurl + "&type=" + type,
		success: function(msg) {
			if ($.trim(msg).indexOf("error") < 0) {
            	$("#textbox").val($("#textbox").val() + $.trim(msg));
            	$("#sentTip").replaceWith('<div id="sentTip"> 缩短网址成功。</div>');
				$("#sentTip").fadeOut(1500);
            	$("#linkArea").fadeOut(1500);
			} else {
				$("#sentTip").replaceWith('<div id="sentTip"> 缩短网址失败，请确认网址格式或选择其他短网址。</div>');
			}
		}
	});
}

function ncNew() {
	var type = getCookie("pageType");
	if(type == 'friendsTimeline' || type == 'userTimeline' || type == 'listStatus' ||
		type == 'replies' || type == 'messages'){
		var sinceID = $("#allTimeline li:first-child").find(".status_id").text();
		$.ajax({
			url: "lib/output.php",
			type: "GET",
			dataType: "text",
			data: "nc=" + type + "&since_id=" + sinceID,
			success:function(msg){if($.trim(msg).indexOf("</li>") > 0){
					$("#allTimeline").prepend(msg);
					if(type == 'messages'){setCookie("meSinceId",$("#allTimeline li:first-child").find(".status_id").text());}
		}}});
	}
}

function NewMessage() {
	var type = getCookie("pageType");
	if(type != 'messages'){
		$.ajax({
			url: "lib/output.php",
			type: "GET",
			dataType: "text",
			data: "nc=newMessage&since_id=" + getCookie("meSinceId"),
			success:function(msg){
					if(msg == 1){
						$("#sentTip").remove();
						$("#content").prepend('<div id="sentTip">你收到了新的暗号</div>');
					}
		}});
	}else{
		setCookie("meSinceId",$("#allTimeline li:first-child").find(".status_id").text());
	}
}

function ncPage(type, id, listid) {
	if(getCookie("pageType")=='setting'){
		$("#style_form").css("display","none");
		$("#style_form").css("left","-800px");
	}
	$("#content").prepend('<div id="wait"><img src="img/wait.gif"></div>');
	var idata;
	if(!id){idata = "nc=" + type;}else{idata = "nc=" + type + "&id=" + id;}
	if(listid){idata = "nc=" + type + "&id=" + id + "&listid=" + listid;}
	setCookie("pageType",type);
	$.ajax({
		url: "lib/output.php",
		type: "GET",
		dataType: "text",
		data: idata,
		success:function(msg){
			$("#content").replaceWith(msg);
			switch(type){
				case 'userTimeline':
					$(".st_author").css("display", "none"); $(".st_body").css("margin-left", "3px");
					$(".user_name").css("display", "none");break;
				case 'setting':
					$("#style_form").css("display","inline"); $("#style_form").css("left","40px");break;
				case 'favors':
					if(!id){$(".favor_btn").replaceWith('<a class="delete_btn" href="javascript:void(0)">删除</a>');}
					break;
			}
			if(type == 'messages' || type == 'sentmessages'){
				$("#sentBtn").replaceWith('<a href="javascript:void(0)" id="sentBtn" style="font-weight:900">发暗号</a>');
				$("h1").replaceWith('<h1>向  <input type="text" name="sent_id" id="sent_id" value=""/> 同志发暗号：</h1>');
				$("#photoBtn").css("display","none");$("#linkBtn").css("display","none");
			}else{
				$("#sentBtn").replaceWith('<a href="javascript:void(0)" id="sentBtn" style="font-weight:900">批示</a>');
				$("h1").replaceWith('<h1>请' + $("#iam").text() + '同志批示：</h1>');
				$("#photoBtn").css("display","inline");$("#linkBtn").css("display","inline");
			}
			$("#allTimeline").bind("DOMNodeInserted", main);main();//showpic.js
	}});
}

function ncMore() {
	var type = getCookie("pageType"); var maxID; var usrID;
	if(type == 'friendsTimeline' || type == 'userTimeline' || type == 'listStatus' ||
			type == 'replies' || type == 'messages' || type == 'sentmessages'){
		maxID = $("#allTimeline li:last-child").find(".status_id").text() -1;
		usrID = $("#allTimeline li:last-child").find(".user_name").text();
	}else{maxID = 'cookie';usrID = 'cookie';}
	
    $("#more_btn").replaceWith('<div id="wait"><img src="img/wait.gif"></div>');
    $.ajax({
		url: "lib/output.php",
		type: "GET",
		dataType: "text",
		data: "nc=" + type + "&max_id=" + maxID + "&id=" + usrID,
		success:
		function(msg){
			if($.trim(msg).indexOf("</li>") > 0){$("#allTimeline").append(msg);}
			if($.trim(msg) == 'none'){$("#wait").replaceWith('<span id="nomore_btn" style="color:#FFFFFF">下面没有了</span>');}
			$("#wait").replaceWith('<a href="javascript:void(0)" id="more_btn" style="color:#FFFFFF">【更多】</a>');
			}
	});
}

function formFunc() {
	document.getElementById("submit_btn").disabled=false;	
	leaveWord();
	$("#textbox").focus();
	$("#textbox").keydown(function(){leaveWord();}).keyup(function(){leaveWord();}).keydown(function(event){
		if (event.ctrlKey && event.keyCode==13) {
			onSend();
		}
		});
	
	$(".submit_btn").click(function(){
		document.getElementById("submit_btn").disabled=true;	
	});

}

function onSend(){
	$("#sentTip").remove();
	$("#content").prepend('<div id="sentTip"> 发送中......</div>');
	
	var text = $("#textbox").val();
	var sentId = $("#sent_id").val();
	var sentData = "nc=update&status=" + text + "&in_reply_to=" + $("#in_reply_to").val();
	if(sentId){sentData = "nc=update&status=" + text + "&sent_id=" + sentId;}
	
	$.ajax({
		url: "lib/postVar.php",
		type: "POST",
		data: sentData,
		success: function(msg){
			if (msg == 'empty'){
				$("#sentTip").replaceWith('<div id="sentTip"> 你还没写内容。</div>');
			} else if (msg == 'error'){
				$("#sentTip").replaceWith('<div id="sentTip"> 未知错误。</div>');
			} else{
				$("#sentTip").replaceWith('<div id="sentTip"> 发送成功。</div>');
				$("#sentTip").fadeOut(1500);
				$("#textbox").val("");
				$("#senttw").toggle(500);
				ncNew();
			}
		}
	});
}

function onRT($this){
	$("#sentBtn").replaceWith('<a href="javascript:void(0)" id="sentBtn" style="font-weight:900">批示</a>');
	$("h1").replaceWith('<h1>请' + $("#iam").text() + '同志批示：</h1>');
	$("#photoBtn").css("display","inline");$("#linkBtn").css("display","inline");
	
	var replie_id = $this.parent().parent().find(".status_word").find(".user_name").text();
	$("#textbox").val("RT @" + replie_id + ":" + $this.parent().parent().find(".status_word").text().replace(replie_id, ""));
	$("#textbox").focus();
	leaveWord();
}

function onReplie($this, $info){
	$("#sentBtn").replaceWith('<a href="javascript:void(0)" id="sentBtn" style="font-weight:900">批示</a>');
	$("h1").replaceWith('<h1>请' + $("#iam").text() + '同志批示：</h1>');
	$("#photoBtn").css("display","inline");$("#linkBtn").css("display","inline");
	
	var replie_id;
	if($info){
		replie_id = $this.parent().parent().parent().parent().find("#info_name").text();
		$("#textbox").val("@" + replie_id + " ");
	}else{
		replie_id = $this.parent().parent().find(".status_word").find(".user_name").text();
		$("#textbox").val($("#textbox").val() + "@" + replie_id + " ");
	}
	$("#textbox").focus();
	$("#in_reply_to").val($this.parent().parent().find(".status_id").text());
	leaveWord();
}

function onMSG($this, $info){
	$("#sentBtn").replaceWith('<a href="javascript:void(0)" id="sentBtn" style="font-weight:900">发暗号</a>');
	$("h1").replaceWith('<h1>向  <input type="text" name="sent_id" id="sent_id" value=""/> 同志发暗号：</h1>');
	$("#photoBtn").css("display","none");$("#linkBtn").css("display","none");
	
	if($info){
		$("#sent_id").val($this.parent().parent().parent().parent().find("#info_name").text());
	}else{
		$("#sent_id").val($this.parent().parent().find(".status_word").find(".user_name").text());
	}
	$("#textbox").focus();
	leaveWord();
}

function onFavor($this){
	var status_id = $.trim($this.parent().parent().find(".status_id").text());
	
	$("#sentTip").remove();
	$("#content").prepend('<div id="sentTip"> 正在收藏ID为 ' + status_id + ' 的批示...</div>');

	$.ajax({
		url: "lib/postVar.php",
		type: "POST",
		data: "nc=addFavor&id=" + status_id,
		success: function(msg) {
			if (msg == 'error') {
				$("#sentTip").replaceWith('<div id="sentTip"> 收藏出错。</div>');
			} else {
				$this.remove();
				$("#sentTip").replaceWith('<div id="sentTip"> 收藏成功。</div>');
				$("#sentTip").fadeOut(1500);
			}
		}
	});
}

function followlist($this, unfo){
	var usrname = $this.parent().parent().find(".user_name").text();
	var listname = $this.parent().parent().find(".list_name").text();
	if(!usrname || !listname){
		usrname = $this.parent().parent().parent().parent().find(".user_name").text();
		listname = $this.parent().parent().parent().parent().parent().find("#info_name").text();
	}
	var postData = "nc=folList&uname=" + usrname + "&id=" +listname;
	if(unfo){postData = "nc=unfList&uname=" + usrname + "&id=" +listname;}
	
	$("#sentTip").remove();
	if(unfo){$("#content").prepend('<div id="sentTip"> 正在取消对' + usrname + '的名单' + listname + '的关注...</div>');}
	else{$("#content").prepend('<div id="sentTip"> 正在关注' + usrname + '的名单' + listname + '...</div>');}

	$.ajax({
		url: "lib/postVar.php",
		type: "POST",
		data: postData,
		success: function(msg) {
			if (msg == 'error') {
				$("#sentTip").replaceWith('<div id="sentTip"> 发生了一些错误，请重试。</div>');
			} else {
				if(unfo){
					$this.parent().parent().parent().parent().remove();
					$("#sentTip").replaceWith('<div id="sentTip"> 成功取消了对此名单的关注。</div>');
				}else{
					$("#sentTip").replaceWith('<div id="sentTip"> 成功开始关注此名单。</div>');
				}
				$("#sentTip").fadeOut(1500);
			}
		}
	});
}

function creatlist(){	
	$("#sentTip").remove();
	$("#content").prepend('<div id="sentTip"> 创建名单中......</div>');

	var sentData = "nc=addList&list_name=" + $("#list_name").val() + "&list_description="
					+ $("#list_description").val() + "&isProtect=" + $("#list_protect").attr("checked");

	$.ajax({
		url: "lib/postVar.php",
		type: "POST",
		data: sentData,
		success: function(msg){				
			if (msg == 'error') {
				$("#sentTip").replaceWith('<div id="sentTip"> 创建失败。</div>');
			} else {
				$("#sentTip").replaceWith('<div id="sentTip"> 创建成功。</div>');
				$("#sentTip").fadeOut(1500);
				$("#creatListForm").replaceWith('<input type="submit" id="list_create_btn" value="创建名单" />');
				ncPage("createdLists");
			}
		}
	});
}

function editlist(){
	$("#sentTip").remove();
	$("#content").prepend('<div id="sentTip"> 修改名单中......</div>');

	var sentData = "nc=editList&pre_list_name=" + $("#preListname").val() + "&list_name=" + $("#list_name").val() + "&list_description="
					+ $("#list_description").val() + "&isProtect=" + $("#list_protect").attr("checked");

	$.ajax({
		url: "lib/postVar.php",
		type: "POST",
		data: sentData,
		success: function(msg){				
			if (msg == 'error') {
				$("#sentTip").replaceWith('<div id="sentTip"> 修改失败。</div>');
			} else {
				$("#sentTip").replaceWith('<div id="sentTip"> 修改成功。</div>');
				$("#sentTip").fadeOut(1500);
				$("#creatListForm").replaceWith('<input type="submit" id="list_create_btn" value="创建名单" />');
				ncPage("createdLists");
			}
		}
	});
}
function addmember(){
	$("#sentTip").remove();
	$("#content").prepend('<div id="sentTip"> 添加成员中......</div>');

	var sentData = "nc=addMember&member_list_name=" + $("#list_name").val() + "&list_members=" + $("#member_name").val();

	$.ajax({
		url: "lib/postVar.php",
		type: "POST",
		data: sentData,
		success: function(msg){				
			if (msg == 'error') {
				$("#sentTip").replaceWith('<div id="sentTip"> 添加失败。</div>');
			} else {
				$("#sentTip").replaceWith('<div id="sentTip"> 添加' + $("#member_name").val() + '成功。</div>');
				$("#member_name").val('');
			}
		}
	});
}
function onFo($this,type){
	var id = $("#info_name").text();
	if(!id){id = $this.parent().parent().find(".user_name").text();}
	$("#sentTip").remove();
	$("#content").prepend('<div id="sentTip"> 开始改变与' + id + '的关系......</div>');
	
	var idata = "nc=" + type + "&id=" +id;
	
	$.ajax({
		url: "lib/postVar.php",
		type: "POST",
		data: idata,
		success: function(msg) {
			if (msg == 'error') {
				$("#sentTip").replaceWith('<div id="sentTip"> 出了点问题，请重试。</div>');
			} else {
				$("#sentTip").replaceWith('<div id="sentTip"> 操作成功。</div>');
				$("#sentTip").fadeOut(1500);
				switch($this.parent().attr('class'))
				{
					case 'relSame':
						$this.parent().replaceWith(
					'<span class="relDown"><b>是你的下级</b> [<a href="javascript:void(0)" class="follow_btn">使其成为同级</a>]</span>');
					break;
					case 'relUp':
						$this.parent().replaceWith(
					'<span class="relNone"><b>与你无关</b> [<a href="javascript:void(0)" class="follow_btn">使其成为上级</a>]</span>');
					break;
					case 'relDown':
						$this.parent().replaceWith(
					'<span class="relSame"><b>和你同一级别</b> [<a href="javascript:void(0)" class="unfoll_btn">使其成为下级</a>]</span>');
					break;
					case 'relNone':
						$this.parent().replaceWith(
					'<span class="relUp"><b>是你的上级</b> [<a href="javascript:void(0)" class="unfoll_btn">与其脱离关系</a>]</span>');
					break;
				}				
			}
		}
	});
}
function onBlock($this){
	var id = $("#info_name").text();
	var confirm = window.confirm("确定要把" + id + "拉入黑名单?");
	if (confirm) {
		$("#sentTip").remove();
		var $sentTip = $("<div id='sentTip'></div>");
		$sentTip.prependTo("#statuses");
		$sentTip.html("正在把" + id + "拉入黑名单...");
		
		$.ajax({
			url: "/block",
			type: "POST",
			data: "action=create&id=" + id,
			success: function(msg) {
				if (msg.indexOf("success") >= 0) {
					$sentTip.html("成功把" + id + "拉入黑名单");
				} else {
					$sentTip.html("把" + id + "拉入黑名单失败，请重试");
				}
			},
			error: function(msg) {
				$sentTip.html("把" + id + "拉入黑名单失败，请重试");
			}
		});
	}
	var id = $("#info_name").text();
	var confirm = window.confirm("确定解除黑名单?");
	if (confirm) {
		$("#sentTip").remove();
		var $sentTip = $("<div id='sentTip'></div>");
		$sentTip.prependTo("#statuses");
		$sentTip.html("正在解除黑名单...");
		
		$.ajax({
			url: "/block",
			type: "POST",
			data: "action=destory&id=" + id,
			success: function(msg) {
				if (msg.indexOf("success") >= 0) {
					$sentTip.html("成功解除黑名单");
				} else {
					$sentTip.html("解除黑名单失败，请重试");
				}
			},
			error: function(msg) {
				$sentTip.html("解除黑名单失败，请重试");
			}
		});
	}
}
function onDelete($this){
	var type = getCookie("pageType");
	var status_id = $.trim($this.parent().parent().find(".status_id").text());
	var txty = '批示';
	var postData = "nc=delStatus&id=";
	
	switch(type){
		case 'favors': txty = '收藏'; postData = "nc=delFavor&id=";break;
		case 'sentmessages': txty = '暗号'; postData = "nc=delDM&id=";break;
		case 'listMember':
			status_id = $.trim($this.parent().parent().find(".user_name").text());
			usrname = 'dgxy';
			listname= 'ttttttteees';
			txty = '成员';
			postData = "nc=delMember&id=" + listname + "&uname=" + usrname + "&memberid=";
			break;
		case 'createdLists':
			usrname = $.trim($this.parent().parent().find(".user_name").text());
			status_id = $.trim($this.parent().parent().find(".list_name").text());
			txty = '名单';
			postData = "nc=delList&uname=" + usrname + "&id=";
			break;
	}
		
	var confirm = window.confirm('确定要删除id为 ' + status_id + ' 的' + txty + '？');
	
	if (confirm){
		$("#sentTip").remove();
		$("#content").prepend('<div id="sentTip"> 正在删除ID为 ' + status_id + ' 的' + txty + '...</div>');

		$.ajax({
			url: "lib/postVar.php",
			type: "POST",
			data: postData + status_id,
			success: function(msg) {
				if (msg == 'error') {
					$("#sentTip").replaceWith('<div id="sentTip"> 删除出错。</div>');
				} else {
					if(type == 'createdLists'){$this.parent().parent().parent().parent().remove();}
					else{$this.parent().parent().parent().remove();}
					$("#sentTip").replaceWith('<div id="sentTip"> 删除成功。</div>');
					$("#sentTip").fadeOut(1500);
				}
			}
		});
	}
}
function leaveWord(num) {
	if (!num) num = 140;
	var leave = num-$("#textbox").val().length;
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