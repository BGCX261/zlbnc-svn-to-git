<?php

class twitter{       
        var $username='';
        var $password='';
        var $user_agent='zlbNC';
        var $type='json';
		var $IsoAuth = false;
       
        var $debug = false;
   
        function twitter($username = '', $password = '', $BeoAuth = false)
        {
			if($BeoAuth){$this->IsoAuth = true;}
			if ($username != '' && $password != '') {
					$this->username = $username;
					$this->password = $password;
			}
        }
       
       
        function veverify()
        {
                $request = API_URL . '/account/verify_credentials.' . $this->type;
                return $this->objectify( $this->process($request) );
        }
       
        /**** Status Methods ****/
       
        function showStatus( $id )
        {              
        $request = API_URL . '/statuses/show/'.$id . '.' . $this->type;
                return $this->objectify( $this->process($request) );
    	}
   
        function update( $status, $replying_to = false )
        {      
            $args = array();
            if( $status )
                $args['status'] = htmlspecialchars($status);
            if( $replying_to )
                $args['in_reply_to_status_id'] = $replying_to;
            $args['source'] = 'zlbNC';
           
            $qs = $this->_glue($args);
        $request = API_URL . '/statuses/update.' . $this->type . $qs;
		if($this->IsoAuth){$request = rawurldecode($request);}
                return $this->objectify( $this->process($request, true));
        }
       
        function deleteStatus( $id ) {
        $request = API_URL . '/statuses/destroy/' . $id . '.' . $this->type;
        return $this->objectify( $this->process( $request, true ) );
    	}
   
   
    /**** Timeline Methods ****/

        function friendsTimeline($since_id = false, $max_id = false )
        {
            $args = array();
            if( $since_id )
                $args['since_id'] = $since_id;
            if( $max_id )
            	$args['max_id'] = $max_id;
           
            $qs = '';
            if( !empty( $args ) )
                $qs = $this->_glue( $args );
                                   
        	$request = API_URL . '/statuses/friends_timeline.' . $this->type . $qs;
            return $this->objectify( $this->process($request) ); 
        }
        
		function userTimeline($id = false, $since_id = false, $max_id = false )
        {
            $args = array();
            if( $id )
                $args['screen_name'] = $id;
            if( $since_id )
                $args['since_id'] = $since_id;
            if( $max_id )
            	$args['max_id'] = $max_id;
           
            $qs = '';
            if( !empty( $args ) )
                $qs = $this->_glue( $args );
                                   
       
        	$request = API_URL . '/statuses/user_timeline.'. $this->type . $qs;       
            return $this->objectify( $this->process($request) );
        }
       
        function replies( $since_id = false , $max_id = false , $count = false )
        {
            $args = array();
            if( $since_id )
                $args['since_id'] = $since_id;
            if( $max_id )
                $args['max_id'] = $max_id;
            if( $count )
                $args['count'] = $count;
           
            $qs = '';
            if( !empty( $args ) )
                $qs = $this->_glue( $args );
           
            $request = API_URL . '/statuses/mentions.' . $this->type . $qs;    
	        return $this->objectify( $this->process($request) );               
        }
       
       
        /**** Direct Message Methods ****/
       
        function directMessages( $since_id = false , $max_id = false  , $count = false )
        {
            $args = array();
            if( $since_id )
                $args['since_id'] = $since_id;
            if( $max_id )
                $args['max_id'] = $max_id;
            if( $count )
                $args['count'] = $count;
           
            $qs = '';
            if( !empty( $args ) )
                $qs = $this->_glue( $args );
                       
        	$request = API_URL . '/direct_messages.' . $this->type . $qs;
            return $this->objectify( $this->process($request) );
        }

        function sentDirectMessage( $since_id = false , $max_id = false )
        {
            $args = array();
            if( $since_id )
                $args['since_id'] = $since_id;
            if( $max_id )
                $args['max_id'] = $max_id;
           
            $qs = '';
            if( !empty( $args ) )
                $qs = $this->_glue( $args );
           
        	$request = API_URL . '/direct_messages/sent.' . $this->type  . $qs;
        	return $this->objectify( $this->process($request) );
        }
   
        function sendDirectMessage( $user, $text )
        {
            $args = array();
            $args['user'] = $user;
            $args['text'] = $text;
            $qs = $this->_glue( $args );
               
        	$request = API_URL . '/direct_messages/new.' . $this->type. $qs;
			if($this->IsoAuth){$request = rawurldecode($request);}
            return $this->objectify( $this->process($request, true) );
        }
       
        function deleteDirectMessage( $id )
        {
            $request = API_URL . '/direct_messages/destroy/' . $id . '.' . $this->type;
            return $this->objectify( $this->process( $request, true ) );
        }
       
       
        /**** User Methods ****/

        function showUser( $id = false , $email = false, $user_id = false, $screen_name=false )
        {              
            $args = array();
               
            if (!$id)
                $id = $this->username;
                       
            $qs = '?screen_name=' . $id;
           
        $request = API_URL . '/users/show/' . $id . '.' . $this->type . $qs;
                return $this->objectify( $this->process($request) );
        }
       
        function friends( $id = false, $cursor = -1 )
        {
            $args = array();
            if( $id ){$args['screen_name'] = $id;}
            $args['cursor'] = (int) $cursor;
            
            $qs = '';
            if( !empty( $args ) )
                $qs = $this->_glue( $args );
                
                $request = API_URL . "/statuses/friends.". $this->type . $qs;
                return $this->objectify( $this->process($request) );
        }
   
        function followers( $id = false, $cursor = -1 )
        {
            $args = array();
            if( $id ){$args['screen_name'] = $id;}
            $args['cursor'] = (int) $cursor;
            
            $qs = '';
            if( !empty( $args ) )
                $qs = $this->_glue( $args );
                
                $request = API_URL . "/statuses/followers.". $this->type . $qs;   
                return $this->objectify( $this->process($request) );
        }


       
    /****** Favorites ******/

        function getFavorites( $id = false, $page = false )
        {
        	$args = array();
            if( $id )
                $args['id'] = $id;
            if( $page )
                $args['page'] = (int)$page;
           
            $qs = '';
            if( !empty( $args ) )
                $qs = $this->_glue( $args );
           
        	$request = API_URL . '/favorites.' . $this->type  . $qs;
        	return $this->objectify( $this->process($request) );
        }
       
        function makeFavorite( $id )
        {
                $request = API_URL . '/favorites/create/' . $id . '.' . $this->type;
                return $this->objectify( $this->process($request, true) );
        }
       
        function removeFavorite( $id )
        {
                $request = API_URL . '/favorites/destroy/' . $id . '.' . $this->type;
                return $this->objectify( $this->process($request, true) );      
        }
       

        /**** Friendship Methods ****/
       
        function isFriend( $user_a, $user_b )
        {
            $args = array();
            $args['user_a'] = $user_a;
            $args['user_b'] = $user_b;
            $qs = $this->_glue( $args );
           
                $request = API_URL . '/friendships/exists.' . $this->type . $qs;
                return $this->objectify( $this->process($request) );
        }
       
        function followUser( $id, $notifications = false )
        {              
                $qs = '?screen_name=' . $id;
                if( $notifications )
                    $qs .= '&follow=true';
                       
                $request = API_URL . '/friendships/create/' . $id . '.' . $this->type . $qs;
                return $this->objectify( $this->process($request, true) );
        }
       
        function destroyUser( $id )
        {
                $request = API_URL . '/friendships/destroy/' . $id . '.' . $this->type;
                return $this->objectify( $this->process($request, true) );
        }
       
        /****** Block Methods ******/
       
        function blockUser( $id )
        {
                $request = API_URL . '/blocks/create/' . $id . '.' . $this->type;
                return $this->objectify( $this->process($request, true) );
        }
       
        function unblockUser( $id )
        {
                $request = API_URL . '/blocks/destroy/' . $id . '.' . $this->type;
                return $this->objectify( $this->process($request, true) );
        }

        function isBlock( $id )
        {
                $request = API_URL . '/blocks/exists/' . $id . '.' . $this->type;
                return $this->objectify( $this->process($request, true) );
        }
       
        /****** Account Methods ******/
        
        function updateProfile( $name, $url, $loc, $des )
        {
        	$args = array();
            if( $name )
                $args['name'] = (string) $name;
            if( $url )
                $args['url'] = (string) $url;
            if( $loc )
                $args['location'] = (string) $loc;
            if( $des )
                $args['description'] = (string) $des;
                
            $qs = $this->_glue( $args );
            $request = API_URL . '/account/update_profile.' . $this->type .$qs;
			if($this->IsoAuth){$request = rawurldecode($request);}
            return $this->objectify( $this->process( $request, true ) );
        }
       
        /**** Search Method ****/
       
        function search( $q = false, $page = false, $lang = false, $rpp = 20 )
        {
        	if( !$q ) return false;
        	$args = array();
        	$args['q'] = $q;
            if( $page )
                $args['page'] = $page;
            if( $lang )
                $args['lang'] = $lang;

                $args['rpp'] = $rpp;
            
            
            $qs = $this->_glue( $args );
            $searchApiUrl = strpos(API_URL, "twitter.com") > 0 ? "http://search.twitter.com" : API_URL;
                $request = $searchApiUrl . '/search.' . $this->type . $qs;
                       
                return $this->objectify( $this->process($request) );
        }

        /**** List Method ****/

        function listLists( $whi = 0, $id = false, $cursor = -1 )
        {               
            $args = array();
            if($id){$username = $id;}else{$username = getEncryptCookie('twitterID');}
            $args['cursor'] = (int)$cursor;

            $qs = '';
            if( !empty( $args ))
                $qs = $this->_glue( $args );
                
            switch($whi){
            	case 0:
            		$request = API_URL . '/' . $username . '/lists/subscriptions.' . $this->type . $qs;
            		break;
            	case 1:
            		$request = API_URL . '/' . $username . '/lists.' . $this->type . $qs;
            		break;
            	case 2:
            		$request = API_URL . '/' . $username . '/lists/memberships.' . $this->type . $qs;
            		break;
            }
            return $this->objectify( $this->process($request) );
        }
       
        function listStatus( $username, $listname, $since_id = false, $max_id = false)
        {
            if (!$username || !$listname){ return false; }
           
            $args = array();
            if( $since_id )
                $args['since_id'] = (int) $since_id;
            if( $max_id )
                $args['max_id'] = $max_id;
           
            $qs = '';
            if( !empty( $args ) )
                $qs = $this->_glue( $args );
           
            $request = API_URL . "/$username/lists/$listname/statuses." . $this->type . $qs;
            return $this->objectify( $this->process($request) );               
        }
       
        function listInfo( $username, $listname )
        {
        	if (!$username || !$listname){ return false; }           
            $request = API_URL . "/$username/lists/$listname." . $this->type;    
            return $this->objectify( $this->process($request) );
        }

        function listMembers( $username, $listname, $cursor = -1 )
        {              
        	if (!$username || !$listname){ return false; }
           
            $args = array();
            $args['cursor'] = (int) $cursor;
            
            $qs = '';
            if( !empty( $args ) )
                $qs = $this->_glue( $args );
               
            $request = API_URL . "/$username/$listname/members." . $this->type . $qs;  
        	return $this->objectify( $this->process($request) );
        }

        function listFollowers( $username, $listname, $cursor = -1 )
        {
        	if (!$username || !$listname){ return false; }
           
            $args = array();
            $args['cursor'] = (int) $cursor;
            
            $qs = '';
            if( !empty( $args ) )
                $qs = $this->_glue( $args );
               
            $request = API_URL . "/$username/$listname/subscribers." . $this->type . $qs;  
        return $this->objectify( $this->process($request) );
        }

        function followList( $username, $listname )
        {           
            $request = API_URL . "/1/$username/$listname/subscribers." . $this->type;  
        	return $this->objectify( $this->process($request, true) );
        }

        function unfollowList( $username, $listname )
        {           
            $request = API_URL . "/1/$username/$listname/subscribers." . $this->type;  
        	return $this->objectify( $this->process($request, true, "DELETE") );
        }

        function createList( $name, $description, $isProtect)
        {
            $mode = ($isProtect == 'true') ? "private" : "public";
            $args = array();
            if( $name )
                $args['name'] = $name;
            if( $description )
                $args['description'] = $description;
            if( $isProtect )
                $args['mode'] = $mode;
               $qs = $this->_glue( $args );
               
            $request = API_URL . "/$this->username/lists." . $this->type . $qs;
			if($this->IsoAuth){$request = rawurldecode($request);}
        	return $this->objectify( $this->process($request, true) );
        }

        function editList( $prename, $name, $description, $isProtect)
        {          
            $mode = ($isProtect == 'true') ? "private" : "public";
            $args = array();
            if( $name )
                $args['name'] = $name;
            if( $description )
                $args['description'] = $description;
            if( $isProtect )
                $args['mode'] = $mode;
            $qs = $this->_glue( $args );
            
            $request = API_URL . "/$this->username/lists/$prename." . $this->type . $qs;
           if($this->IsoAuth){$request = rawurldecode($request);}
        	return $this->objectify( $this->process($request, true) );
        }
       
        function deleteList( $username, $listname)
        {           
            $request = API_URL . "/$username/lists/$listname." . $this->type;
        	return $this->objectify( $this->process($request, true, "DELETE") );
        }

  		function _post( $url, $data = array() )
        {
        	$json = $this->http->post( $url, array( 'headers' => $this->headers, 'user-agent' => $this->user_agent, 'body' => $data ));
        	if(is_object($json) && is_a($json, 'WP_Error') ){return $json;}
        	if( $json['headers']['status'] == '500 Internal Server Error'){return $json;}
        	return (object) json_decode( $json['body'] );
        }
		
        function deleteListMember( $username, $listname, $memberid )
        {
        	$this->api_url = 'http://api.twitter.com/1/' . $username . '/' . $listname . '/members.' . $this->type;
            return $this->_post( $this->api_url, array('list_id' => (string) $listname, 'id' => (string) $memberid, '_method' => 'DELETE' ) );
        	/*
            $args = array();
            $args['id'] = $memberid;                
            $qs = $this->_glue( $args );
                           
            $request = API_URL . "/$username/$listname/members." . $this->type . $qs;  
        	return $this->objectify( $this->process($request, $args, "DELETE") );*/
        }

        function addListMember( $listid, $memberid )
        {                          
            $args = array();
            if( $memberid )
                $args['id'] = $memberid;
            $qs = $this->_glue( $args );
               
            $request = API_URL . "/$this->username/$listid/members." . $this->type . $qs;
        	return $this->objectify( $this->process($request, true) );
        }
       
        /**** Twitese Method ****/
       
        function rank( $page = 1, $count = 20 )
        {
                $args = array();

                $args['page'] = $page;
                
                $args['count'] = $count;
            	$qs = $this->_glue( $args );
           
                $request = TWITESE_API_URL . '/rank.' . $this->type . $qs;
                return $this->objectify( $this->process($request) );
        }


        function browse( $page = false, $count = false )
        {
                $args = array();
            if( $page )
                $args['page'] = $page;
            if( $count )
                $args['count'] = $count;
            $qs = $this->_glue( $args );
           
                $request = TWITESE_API_URL . '/browse.' . $this->type . $qs;
                return $this->objectify( $this->process($request) );
        }
        /**** API Rate Limit ****/
        function ratelimit()
        {
                $request = API_URL . '/account/rate_limit_status.' . $this->type;
                return $this->objectify( $this->process($request) );
        }
       
       
        /**** Upload Photo ****/
        function twitgooUpload( $image ) {
                $postdata = array( 'media' => "@$image", 'username' => $this->username, 'password' => $this->password);
            $request = 'http://twitgoo.com/api/upload';
            $this->type = 'xml';
            return $this->objectify( $this->process( $request, $postdata ) );
        }
       
        /****** Tests ******/
       
        function twitterAvailable()
        {
                $request = API_URL . '/help/test.' . $this->type;
                if( $this->objectify( $this->process($request) ) == 'ok' )
                        return true;
               
                return false;
        }
       
       
        /**** request method ****/
		function oauth_sign(&$url, &$args = false) {
			require_once 'OAuth.php';
			$method = $args !== false ? 'POST' : 'GET';

			if (preg_match_all('#[?&]([^=]+)=([^&]+)#', $url, $matches, PREG_SET_ORDER)){
				foreach ($matches as $match){$args[$match[1]] = $match[2];}
				$url = substr($url, 0, strpos($url, '?'));
			}
		  
		  $sig_method = new OAuthSignatureMethod_HMAC_SHA1();
		  $consumer = new OAuthConsumer(OAUTH_CONSUMER_KEY, OAUTH_CONSUMER_SECRET);
		  $token = NULL;
		
		  list($oauth_token, $oauth_token_secret) = explode('|', getEncryptCookie('twitterPW'));

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
		
        function process($url, $postargs=false, $method = false){
			if($this->IsoAuth){
				if ($postargs) $postargs = array();
				$this->oauth_sign($url, $postargs);
				
				$ch = curl_init($url);
				if($postargs !== false) {
					curl_setopt ($ch, CURLOPT_POST, true);
					curl_setopt ($ch, CURLOPT_POSTFIELDS, $postargs);
				}
			}else{
				$ch = curl_init($url);
	
				if($postargs !== false)
				{
					curl_setopt ($ch, CURLOPT_POST, true);
					if (is_array($postargs)) {
						curl_setopt ($ch, CURLOPT_POSTFIELDS, $postargs);
					} 
					if ($method === "DELETE") {
						curl_setopt ($ch, CURLOPT_POSTFIELDS, "_method=DELETE");
					}
				}        
				if($this->username !== false && $this->password !== false)
					curl_setopt($ch, CURLOPT_USERPWD, $this->username.':'.$this->password );
			}
			
			curl_setopt($ch, CURLOPT_VERBOSE, 0);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				
			$response = curl_exec($ch);
			$responseInfo=curl_getinfo($ch);
			curl_close($ch);
			
			if( intval( $responseInfo['http_code'] ) == 200 )
				return $response;    
			else
				return false;
	}
	
	function objectify( $data )
	{
		if( $this->type ==  'json' ) {
			$result = json_decode( $data );
			if ($this->debug) {
				echo '<pre>';
				print_r($result);
				echo '</pre>';
			}
			if (isset($result->error)) {
				if (substr_count($result->request, 'user_timeline') && $result->error == 'Not authorized') {
					return 'protected';
				}
				return false;
			}
			else return $result;
		}
		
		else if( $this->type == 'xml' )
		{
			if( function_exists('simplexml_load_string') ) {
			    $obj = simplexml_load_string( $data );
			}
			if ($this->debug) {
				echo '<pre>';
				print_r($obj);
				echo '</pre>';
			}
			if (isset($obj->error) || !$obj) return false;
			else return $obj;
		}
		else
			return false;
	}
		
	function _glue( $array )
	{
	    $query_string = '';
	    foreach( $array as $key => $val ) :
	        $query_string .= $key . '=' . rawurlencode( $val ) . '&';
	    endforeach;
	    
	    return '?' . substr( $query_string, 0, strlen( $query_string )-1 );
	}
}
?>