<?php
//加密用户名密码用的密匙，请随便输入一字符串
define('SECURE_KEY', 'zlb_NC');
//twitter api地址，如果是国外空间，请用http://twitter.com，国内空间需要用第三方API proxy
define('API_URL', 'https://twitter.com');
//“随便看看”与“排行榜”的api地址，由架设在GAE的twitese提供
define('TWITESE_API_URL', 'http://twiteseapi.appspot.com');
//网站名称
define('SITE_NAME', '真理部内参');

//oAuth密钥等相关数据，登陆https://twitter.com/oauth获取
define('OAUTH_CONSUMER_KEY', 'U9yQykQqD1olTUClTNHAA');
define('OAUTH_CONSUMER_SECRET', 'BILGqKu9jcWierOhrCnQ22Cn7JVvjNmm84JIREo11PE');
define('OAUTH_CALLBACK_URL', 'http://nc.alwaysdata.net/oauth');
?>