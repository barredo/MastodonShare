<?php
// require_once ROOT.'lib/twitteroauth/autoload.php';
// use Abraham\TwitterOAuth\TwitterOAuth;

function detect_objects($a) {
	$return = [];
	$a = explode(' ',$a);
	foreach($a as $o) {
		$is = is_object_index($o);
		if($is) {
			array_push($return,$is);
		}
	}
	return $return;
}

function is_object_index($a) {
	// if(substr($a,0,1) == '#')
	if(preg_match('/#([\p{Pc}\p{N}\p{L}\p{Mn}]+)/u',$a)) {
		return ['hashtag',$a];
	}
	if(preg_match('/@([A-Za-z0-9\-\_]+)/u',$a)) {
		return ['mention',$a];
	}
	if(filter_var($a, FILTER_VALIDATE_URL)) {
		return ['url',$a];

	}
	return false;
}

function highlight($original,$highlight) {
	if(is_string($highlight)) {
		$highlight = explode(' ',$highlight);
	}
	$highlight = array_unique($highlight);

	if(is_string($original)) {
		// $original = stripslashes($original);
		$original = mb_convert_encoding($original,'UTF-8');
		$original = explode(' ',$original);
	}

	foreach($original as $k => $o) {
		if(empty($o)) continue;
		$clean[$k] = mb_strtolower(clean_i18n($o));
	}

	foreach($clean as $k => $o) {
		if(empty($o)) continue;

		if(in_array($o,$highlight)
		OR in_array('#'.$o,$highlight)
		OR in_array('@'.$o,$highlight)) {
			$original[$k] = '<strong>'.$original[$k].'</strong>';
		}
	}
	return implode(' ',$original);
}

function clean_i18n($a) {
	return preg_replace("/[^\p{L}\p{N}]/",'',$a);
}

function logout_hash() {
	return md5('ssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss'.actual_logged());
}

function is_logged() {
	if(empty($_SESSION['MASTODON_USER'])) {
		return false;
	}
	return !empty(clean_user($_SESSION['MASTODON_USER']));
	return $t > 0 ? $t : false;
}
function is_blank($value) {
    return empty($value) && !is_numeric($value);
}
function file_get_contents_curl($url) {
	$ch = curl_init();

	// $useragent = "Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/W.X.Y.Z‡ Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)";
	// $useragent = "Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)";
	$useragent = "W3C_Validator/1.3";

	// $useragent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36";

	$cookie = '/var/www/mastofeed.org/cookies.txt';

	curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_COOKIESESSION, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($ch, CURLOPT_USERAGENT, $useragent);

	// curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	// 	':authority: www.bs.ch',
	// 	':method: GET',
	// 	':path: /nrss/?generatorName=nachrichten&host=6e3f010f-a06a-49fc-a2ad-341184c270b7&types=medienmitteilung&title=Aktuelle%20Medienmitteilungen%20und%20News%20des%20Kantons%20Basel-Stadt&description=Aktuelle%20Medienmitteilungen%20und%20News%20des%20Kantons%20Basel-Stadt&quantiy=10',
	// 	':scheme: https',
	// 	'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
	// 	'accept-encoding: gzip, deflate, br',
	// 	'accept-language: en-US,en;q=0.9,es;q=0.8',
	// 	'cache-control: no-cache',
	// 	'cookie: AL_SESS-S=ASPmvEVQH5rR8LhrhtZrF7cQ0ZpBwP4HL4_dWx0dEzM0rcIwX7Q8Cv2TADbdwu1OdWJa; AL_LB-S=$xc/lpV1myhAR4I6e1l2CaX1_f2LIshU2o!aSt7kQBmTMU3S7tHU',
	// 	'dnt: 1',
	// 	'pragma: no-cache',
	// 	'sec-ch-ua: "Google Chrome";v="107", "Chromium";v="107", "Not=A?Brand";v="24"',
	// 	'sec-ch-ua-mobile: ?0',
	// 	'sec-ch-ua-platform: "Windows"',
	// 	'sec-fetch-dest: document',
	// 	'sec-fetch-mode: navigate',
	// 	'sec-fetch-site: same-origin',
	// 	'sec-fetch-user: ?1',
	// 	'upgrade-insecure-requests: 1',
	// 	'user-agent: '.$useragent
	// ));

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	$data = curl_exec($ch);
	curl_close($ch);

	return $data;
}

function OpenURLcloudflare($url) {
    //get cloudflare ChallengeForm
    $data = OpenURL($url);
    preg_match('/<form id="ChallengeForm" .+ name="act" value="(.+)".+name="jschl_vc" value="(.+)".+<\/form>.+jschl_answer.+\(([0-9\+\-\*]+)\);/Uis',$data,$out);
    if(count($out)>0) {
        eval("\$jschl_answer=$out[3];");
        $post['act']            = $out[1];
        $post['jschl_vc']        = $out[2];
        $post['jschl_answer']    = $jschl_answer;
        //send jschl_answer to the website
        $data = OpenURL($url, $post);
    }
    return($data);
}

function OpenURL($url, $post=array()) {
    $headers[] = 'User-Agent: ozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/W.X.Y.Z‡ Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)';

    $useragent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36";
    $headers[] = 'Accept: application/json, text/javascript, */*; q=0.01';
    $headers[] = 'Accept-Language: en;q=0.5';
    $headers[] = 'Connection: keep-alive';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    if(count($post)>0) {
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }
    curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/curl.cookie');
    curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/curl.cookie');
    $data = curl_exec($ch);
    return($data);
}

function cloudFlareBypass($url){

	$useragent = "Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/W.X.Y.Z‡ Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)";

    // $useragent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36";

	$ct = curl_init();
	
	curl_setopt_array($ct, Array(
		CURLOPT_URL => $url,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_RETURNTRANSFER => true,
		// CURLOPT_HTTPHEADER => array("X-Requested-With: XMLHttpRequest"),
		CURLOPT_REFERER => "https://mastofeed.org",
		CURLOPT_USERAGENT =>  $useragent,
		CURLOPT_HEADER => false,
		CURLOPT_POST => true,
		CURLOPT_COOKIEFILE => '/tmp/curl.cookie',
		CURLOPT_COOKIEJAR => '/tmp/curl.cookie',
		CURLOPT_POSTFIELDS => 'schn=csrf'
	));
	
	$html = curl_exec($ct);
	
	$dochtml = new DOMDocument();
	@$dochtml->loadHTML($html);
	$xpath = new DOMXpath($dochtml);
	
	// Auth
	if(isset($xpath->query("//input[@name='r']/@value")->item(0)->textContent)){
	
		$action = $url . $xpath->query("//form/@action")->item(0)->textContent;
		$r = $xpath->query("//input[@name='r']/@value")->item(0)->textContent;
		$jschl_vc = $xpath->query("//input[@name='jschl_vc']/@value")->item(0)->textContent;
		$pass = $xpath->query("//input[@name='pass']/@value")->item(0)->textContent;
	 
		// Generate curl post data
		$post_data = array(
			'r' => $r,
			'jschl_vc' => $jschl_vc,
			'pass' => $pass,
			'jschl_answer' => ''
		);
	
		curl_close($ct); // Close curl
	
		return $html;
	
		$ct = curl_init();
		
		// Post cloudflare auth parameters
		curl_setopt_array($ct, Array(
			CURLOPT_HTTPHEADER => array(
				'Accept: application/json, text/javascript, */*; q=0.01',
				'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
				'Referer: '. $url,
				'Origin: '. $url, 
				'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
				'X-Requested-With: XMLHttpRequest'
			),
			CURLOPT_URL => $action,
			CURLOPT_REFERER => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_USERAGENT => $useragent,
			CURLOPT_POSTFIELDS => http_build_query($post_data)
	
		));
	
		$html_reponse = curl_exec($ct);

		curl_close($ct); // Close curl
	
	}else{

		// Already auth
		return $html;
	
	}
	
}

function is_admin() {

	if(actual_logged() == '@barredo@mastodon.social'
	// OR actual_logged() == '@mixxio@mas.to'
) {
		return true;
	}
	return false;
}

function debugp($a,$exit = false) {
	if(DEBUG) {
		p($a);
		if($exit) {
			exit;
		}
	}
}

function actual_logged() {
	// p($_SESSION);

	if(!empty($_SESSION['MASTODON_USER'])) {
		return strtolower('@'.$_SESSION['MASTODON_USER'].'@'.$_SESSION['instance']);
	}

	return false;

	if(!empty($_SESSION['twitter'])
	&& count($_SESSION['twitter'] > 0)) {
		$first = reset($_SESSION['twitter']);
	// p($first);
		return $first['screen_name'];
	}
}

function actual_logged_hash() {
	return md5('9823179823749827349827349823792874982zsus91!!!aslañañañ'.actual_logged());
}

function actual_mastodon_link($class = '') {
	if(!empty($_SESSION['MASTODON_USER']) && !empty($_SESSION['instance'])) {
		return mastodon_link($_SESSION['MASTODON_USER'],$_SESSION['instance'],$class);
	}
}

function mastodon_link($user,$instance,$class = '') {
	$instance = trim($instance,'@ ');
	$user     = trim($user,'@ ');
	return '<a target="_new"'.(empty($class)?'':' class="'.$class.'"').' href="'.mastodon_url($user,$instance).'">@'.$user.'@'.$instance.'</a>';
}

function mastodon_name($user,$instance) {
	$instance = trim($instance,'@ ');
	$user     = trim($user,'@ ');
	return '@'.$user.'@'.$instance;
}

function mastodon_url($user,$instance) {
	return 'https://'.$instance.'/@'.$user;
}

function taccounts() {
	return count($_SESSION['twitter']);
}
function accounts() {
	$t = $_SESSION['twitter'];
	foreach($t as $k => $v) {
		unset($t[$k]);
		$k = strtolower($k);
		$t[$k] = $v;
	}
	ksort($t);
	return $t;
}
function is_logged_as($user) {
	if(!is_logged()) {
		return false;
	}
	foreach(accounts() as $t => $r) {
		if(strtolower($t) == strtolower($user)) {
			return $r;
		}
	}
	return false;
}

function strlen_urls($a) {
	$t = array();
	$b = explode(' ',trim($a));
	foreach($b as $k => $v) {
		$trim = trim($v);
		if(filter_var($trim,FILTER_VALIDATE_URL)) {
			$t[] = 23+
				mb_strlen($v,mb_detect_encoding($a))-
				mb_strlen($trim,mb_detect_encoding($a)); //.' '.$v;
		// } elseif(preg_match('/@[a-zA-Z0-9\_]/',$trim) === true) {
		// 	$t[] =
		} else {
			$t[] = mb_strlen($v,mb_detect_encoding($a)); //.' '.$v;
		}
	}
	return array_sum($t)+count($t)-1;
	return array(strlen($a),$t,array_sum($t)+count($t)-1);
}
function tail($string,$tail) {
	$tail = trim($tail);
	$uno = 1;
	$string = str_ireplace(strrev($tail),'',strrev($string),$uno);
	$string = trim(strrev($string));
	return $string;
}
function public_url($path) {
	return str_ireplace(ROOT,URL,$path);
}
function show_domain($url) {
	$domain = parse_url($url,PHP_URL_HOST) ? : $url;
	return str_ireplace('www.','',$domain);
}
function show_date($time) {
	if($time < time()-(86400*30)) {
		return date('Y-m-d',$time);
	} elseif($time < time()-(86400*2)) {
		return ceil((time()-$time)/86400).' days ago';
	} elseif($time < time()-(86400)) {
		return '1 day ago';
	} elseif($time >= time()-86400
		&& $time < time()-3600) {
		return ceil((time()-$time)/3600).' hours ago';
	} elseif($time >= time()-3600
		&& $time < time()) {
		return '1 hour ago';
	} elseif($time < time()) {
		return date('Y-m-d H:i',$time);
	} else {
		return date('Y-m-d H:i',$time);
	}
}
function show_url($url,$max = 50) {
	$join = '(...)';
	$url = str_ireplace(array('http://','https://','www.'),'',$url);
	if(strlen($url) > $max-strlen($join)) {
		return substr($url,0,ceil($max/2)-strlen($join)).
				$join.
				substr($url,(ceil($max/2)-strlen($join))*-1);
	}
	return $url;
}

function get_friends() {
	// p($friends);
}

function save_following_list($list) {
	if(!$list OR !is_object($list) OR count($list->users) == 0) {
		return false;
	}
	foreach($list->users as $user) {
		$data = [
			'screen_name' => mb_strtolower($user->screen_name),
			'id'          => mb_strtolower($user->id_str),
			'bio'         => mb_strtolower($user->description),
			'url'         => $user->url //empty($url) ? '' : get_headers($user->url)
		];
		$_SESSION['friends'][] = mb_strtolower($user->screen_name);
		save_twitter($data);
	}
}

function create_twitter($data) {
	$exists = db::one("select * from mastodon where twitter = '".clean($data['screen_name'])."' limit 1");
	// p($exists)
	if(!empty($exists['id'])) {
		// p($exists.' existe');
		$_SESSION['mastodon'] = $exists['mastodon'];
	} else {
		// p($data['screen_name'].' insertado');
		db::query("insert into mastodon (twitter,twitter_id) values
			('".$data['screen_name']."','".$data['id']."')");
	}
}
function save_twitter($data) {
	$exists = db::one("select twitter from mastodon where twitter = '".clean($data['screen_name'])."' limit 1","twitter");
	if($exists) {
		// p($exists.' existe');
	} else {
		// p($data['screen_name'].' insertado');
		db::query("insert into mastodon (twitter,twitter_id,twitter_bio,twitter_url) values
			('".clean($data['screen_name'])."','".$data['id']."','".clean($data['bio'])."','".($data['url'])."')");
	}
}

function get_followings_twitter($auth) {
	if(is_string($auth)) {
		$auth = db::one("select * from `auths` where `screen_name` = '".$auth."' limit 1");
	}
	$connection = new TwitterOAuth(
		CONSUMER_KEY,
		CONSUMER_SECRET,
		$auth['oauth_token'],
		$auth['oauth_token_secret']
	);
	$friends = $connection->get("friends/ids",array(
		'stringify_ids' => true,
		'count' => 5000,
		'screen_name' => $auth['screen_name']
	));
	if(count($friends->ids) > 0) {
		foreach($friends->ids as $r) {
			db::query("insert into `relationships`
				(`twitter`,`twitter_following_id`,`status`)
			values
				('".$auth['screen_name']."','".$r."','0')");
		}
	}
	db::query("update `auths` set `update` = '".time()."' where `screen_name` = '".$auth['screen_name']."' limit 1");
	p(count($friends->ids).' followings importados de '.$auth['screen_name']);
}
function get_avatar_twitter($screen_name) {

	$screen_name = strtolower($screen_name);
	if(!file_exists(ROOT.'s/resources/avatars/'.($screen_name).'.jpg')) {
		$con = new TwitterOAuth(CONSUMER_KEY,CONSUMER_SECRET,OAUTH_QUOTE,OAUTH_QUOTE_TOKEN);
		if(!$con) {
			return false;
		}
		$i = $con->get('users/show',array('screen_name' => $screen_name));
		$url = $i->profile_image_url;
		$url = str_ireplace('_normal.','.',$url);
		get_avatar($url,$screen_name);
	}
}
function save_tweet($a) {
	db::query("insert into `links`
		(`url`,`twitter`,`date`,`status`,`shorten`)
	values
		('".$a['url']."','".$a['twitter']."','".$a['time']."','".$a['status']."','".$a['shorten']."')");
	$last = db::last();
	$quotes_q = "insert into `quotes` (
			`link_id`,
			`comment`,
			`image`
		) values (
			".intval($last).",
			'".addslashes($a['tweet'])."',
			'".$a['picture']."'
		)";
	db::query($quotes_q);
}
function send_tweet($tweet,$auth,$link_id = false,$only_send = false) {

	// print_r($auth);

	$connection = new TwitterOAuth(
		CONSUMER_KEY,
		CONSUMER_SECRET,
		$auth['token']?:$auth['oauth_token']?:false,
		$auth['secret']?:$auth['oauth_token_secret']?:false
	);

	$status = false;

	if(!$connection) {
		return false;
	}

	if(is_string($tweet)) {
		$tweet = array(
			'status' => $tweet
		);
	}

	if(!empty($tweet['image'])) {
		$media = $connection->upload('media/upload', array(
			'media' => $tweet['image']
		));
		if(isset($media->media_id)) {
			$tweet['media_ids'] = $media->media_id;
		}
	}

	// $tweet = array(
	// 	'status' => $tweet_title,
	// 	'in_reply_to_status_id' => $reply_to ? : false
	// );

	// if($tweet['spread']
	// && strlen(trim($tweet['status'])) > 140) {
	// 	$storm = storm($tweet['status']);
	// 	p("Storm");
	// 	p($storm);
	// 	if(count($storm['post']) > 1) {
	// 		foreach($storm['post'] as $tweet_storm) {
	// 			$tweet['status'] = $tweet_storm;
	// 			$status = $connection->post('statuses/update',$tweet);
	// 			$tweet['in_reply_to_status_id'] = $status->id_str;
	// 		}
	// 	}
	// }

// 	if(!empty($tweet['list_id'])) {

// 		$q_list = "select * from `lists` where lists.id = '".$tweet['list_id']."' limit 1";
// 		$list = db::one($q_list);

// 		if(!$tweet['in_reply_to_status_id']
// 		&& isset($list['id'])) {
// 			$q_posted = "select  links.id,links_twitter.twitter_id,links.url from `links`

// inner join links_twitter on links.id = links_twitter.link_id
// inner join lists_links on links.id = lists_links.link_id
// inner join lists on lists.id = lists_links.list_id

// where

// links.twitter = '".$list['twitter']."' and
// lists.list = '".$list['list']."'

// order by links.id desc

// limit 1";
// 			$posted = db::one($q_posted);

// 			if(!empty($posted['twitter_id'])) {
// 				$tweet['in_reply_to_status_id'] = $posted['twitter_id'];
// 			}
// 		}
// 	}

	// if(!empty($url)
	// && !$tweet['in_reply_to_status_id']) {
	// 	$posted = reset(db::fetch("select links.id,links_twitter.twitter_id from `links` inner join links_twitter on links.id = links_twitter.link_id where `url` = '".$url."' and `twitter` = '".$valor_twitter['screen_name']."' and links.date > '".(time()-43200)."' order by links.id desc limit 1"));
	// 	if(!empty($posted['twitter_id'])) {
	// 		$reply_to = $posted['twitter_id'];
	// 	}
	// }

	if(!$status) {
		$status = $connection->post('statuses/update',$tweet);
	}

	// print_r($status);
	// exit;

	if(!empty($status->id_str)
	&& is_numeric($link_id)) {
		db::query("update `links` set `status` = '1',`date` = '".time()."' where `id` = '".$link_id."' limit 1");
		db::query("insert into `links_twitter` (`link_id`,`twitter_id`)
			values
		('".$link_id."','".$status->id_str."')");
	}

	if(empty($status->id_str)
	&& is_numeric($link_id)) {
		db::query("update `links` set `status` = -2,`date` = '".time()."' where `id` = '".$link_id."' limit 1");
	}

	return $status;
}

function get_avatar($url,$screen_name) {
	if(empty($url)) {
		return false;
	}
	$img = imagecreatefromstring(file_get_contents($url));
	if(!$img) {
		return false;
	}
	imagejpeg($img,ROOT.'s/resources/avatars/'.strtolower($screen_name).'.jpg');
}

function wordwrap_urls($a,$cada,$separador = "\n") {
	$l = strlen_urls($a);
	if($l <= $cada) {
		return $a;
	}
	$r = array();
	$e = explode(' ',$a);
	$c = $j = 0;
	foreach($e as $k => $v) {
		$trim = trim($v,' .,');
		if(filter_var($trim,FILTER_VALIDATE_URL)) {
			$t = 23+
				mb_strlen($v,mb_detect_encoding($a))-
				mb_strlen($trim,mb_detect_encoding($a));
		} else {
			$t = mb_strlen($v,mb_detect_encoding($a));
		}
		$c += $t+1;
		if($c > $cada) {
			$j++;
			$c = 0;
		}
		$r[$j][] = $v; //' '.$t.' '.$c;
	}
	foreach($r as $j => $v) {
		$r[$j] = implode(' ',$v);
	}
	return implode($separador,$r);
}
function storm($a) {
	return;

	$storm = trim($a);
	$storm = str_ireplace(array("\t"),' ',$storm);
	$storm = str_ireplace("\n",'++++++N+++++',$storm);
	$storm = preg_replace('/\s+/',' ',$storm);
	$storm = str_ireplace('++++++N+++++',"\n",$storm);
	$max = 50;
	$i = 1;
	$start = '/ ';
	$max_chars = 0;
	// for($i=1;$i<=$max;$i++) {
	// 	$max_chars += strlen($start)+strlen($i);
	// }
	$storm = substr($storm,0,(140*$max)-$max_chars);
	// $storm = substr($storm,0,140*$max);
	$tweets = array('pre' => array(),'post' => array());
	$tweets['pre'] = explode("\n",$storm);
	$tweets['pre'] = array_values(array_filter($tweets['pre'],function($a){return strlen(trim($a)) > 0;}));

	// p("!");

	foreach($tweets['pre'] as $k => $v) {
		$v =  trim($v,' .,');

		// p(array(strlen($v),strlen_urls($v)));

		if(strlen_urls($v) > 140-strlen($i)+strlen($start)) {
			$e = wordwrap($v,140-strlen($i.$start),"\n");
			$eu = wordwrap_urls($v,140-strlen($i.$start),"\n");
			// p(array('e',$e,$eu));
			$e = explode("\n",$e);
			$eu = explode("\n",$eu);
			foreach($eu as $y) {
				// p(array(strlen($y),strlen_urls($y)));
				$tweets['post'][] = $i.$start.$y;
				$i++;
			}
		} else {
			$tweets['post'][] = $i.$start.$v;
			$i++;
		}
	}
	return $tweets;
}
function error($a) {
	die("Sorry about this: ".$a."<br/>Please contact me at <a href='mailto:alex@barredo.es'>alex@barredo.es</a> if this error persists.");
}
function alter_brightness($colourstr, $steps) {

	$colourstr = str_replace('#','',$colourstr);
	$rhex = substr($colourstr,0,2);
	$ghex = substr($colourstr,2,2);
	$bhex = substr($colourstr,4,2);

	$r = hexdec($rhex);
	$g = hexdec($ghex);
	$b = hexdec($bhex);

	$r = max(0,min(255,$r + $steps));
	$g = max(0,min(255,$g + $steps));
	$b = max(0,min(255,$b + $steps));

	return dechex($r).dechex($g).dechex($b);
}
function get_average_color($image_path){
	$image	= imagecreatefromstring(file_get_contents($image_path));
	$scaled	= imagescale($image,1,1, IMG_BICUBIC);
	$index	= imagecolorat($scaled,0,0);
	$rgb	= imagecolorsforindex($image,$index);
	$red	= round(round(($rgb['red'] / 0x33)) * 0x33);
	$green	= round(round(($rgb['green'] / 0x33)) * 0x33);
	$blue	= round(round(($rgb['blue'] / 0x33)) * 0x33);
	return sprintf('%02X%02X%02X', $red, $green, $blue);
}
function diez($num) {
	$base = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$limit = strlen($num);
	$res=strpos($base,$num[0]);
	for($i=1;$i<$limit;$i++) {
		$res = 62 * $res + strpos($base,$num[$i]);
	}
	return $res;
}
function seseintaydos($num, $b=62) {
	$base='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$r = $num  % $b ;
	$res = $base[$r];
	$q = floor($num/$b);
	while ($q) {
		$r = $q % $b;
		$q =floor($q/$b);
		$res = $base[$r].$res;
	}
	return $res;
}
function screen_names() {
	$nicks = array();
	foreach($_SESSION['twitter'] as $k => $v) {
		$nicks[] = strtolower($v['screen_name']);
	}
	return $nicks;
}
function is_screen_name($a) {
	$s = screen_names();
	return in_array($a,$s);
}
function user_lists($screen_name = false) {
	$nicks = $lists = array();

	foreach(screen_names() as $nick) {
		// $lists[$nick] = db::fetch("select lists.list from lists inner join lists_auths on lists.id = lists_auths.list_id inner join auths on auths.id = lists_auths.auth_id where lists.online = 1 and auths.screen_name = '".$nick."'");
		$lists[$nick] = db::fetch("select list,twitter from lists where `twitter` = '".($nick)."' and active = 1");

		foreach($lists[$nick] as $k => $v) {
			$lists[$nick][$k] = $v['list'];
			$lists['*'][] = $v;
		}
	}

	if($screen_name) {
		return $lists[$screen_name];
	}

	return ($lists['*']);
}
function column_id($user,$instance,$order) {
	return "c".md5($user.$instance.$order);
}
function clean_user($string) {
	$string = strtolower(trim($string));
	return preg_replace('/[^a-z0-9\_]/','',$string);
}
function clean_instance($string) {
	if(filter_var($string, FILTER_VALIDATE_URL)) {
		$string = parse_url($string,PHP_URL_HOST);
	}
	$string = strtolower(trim($string));
	return preg_replace('/[^a-z0-9\-\.]/','',$string);
}
function clear_address($string) {
	$string = strtolower(trim($string));
	return preg_replace('/[^a-z0-9\-\.\_\@]/','',$string);
}


function instance_user_status($string) {

	$return = [
		'user'     => '',
		'instance' => '',
		'status'   => ''
	];

	if(filter_var($string, FILTER_VALIDATE_URL)) {
		// p("es url");
		if(stripos($string, "/") === false) {
			return $return;
			// return clean_instance($string);
		}
		$split = explode("/",$string);

		if(count($split) > 3 && empty($split[1])) {
			$return['instance'] = clean_instance($split[2]);
			$return['user']     = clean_user($split[3]);
			$return['status']   = ($split[4]);
		}

		return $return;
	}

	if(stripos($string, "@") === false) {
		return $return;
		// return clean_instance($string);
	}
	$split = explode("@",$string);

	// p(($return));

	if(count($split) == 3 && empty($split[0])) {
		unset($split[0]);
		$split = array_values($split);
	}

	// p(($return));

	if(count($split) == 2) {
		$return['user'] = clean_user($split[0]);

		if(stripos($split[1], "/") !== false) {
			$split2 = explode("/",$split[1]);

			if(count($split2) == 2) {
				$return['instance'] = clean_instance($split2[0]);
				$return['status']   = clean_instance($split2[1]);
			}
		}
	}

	// p(($return));

	// if(count($split) >= 2) {
	// 	return clean_instance(end($split));
	// }

	return $return;
}

function only_instance($string) {
	if(stripos($string, "@") === false) {
		return clean_instance($string);
	}
	$split = explode("@",$string);
	if(count($split) >= 2) {
		return clean_instance(end($split));
	}
}

function only_user($string) {
	$instance = only_instance($string);
	return str_ireplace([$instance,'@'],'',$string);
}

function clean_general($string) {
	$string = str_ireplace([
		'"',
		'\\',
		// '/',
		';',
		'(',
		')',
		'{',
		'}',
		"'",
	],'',$string);
	$string = str_ireplace([
		'&',
	],[
		'& '
	],$string);
	return trim($string);
}

function clean_url($string) {
	$url = parse_url($string);
	// p($url);
	// exit;
	if(!empty($url['query'])) {
		$url['path'] .= '?'.$url['query'];
	}
	return mb_strtolower($url['host'].$url['path']);
}
function clean_url_no_case($string) {
	$url = parse_url($string);
	// p($url);
	// exit;
	if(!empty($url['query'])) {
		$url['path'] .= '?'.$url['query'];
	}
	return trim($url['host'].$url['path']);
}
function clean($string) {
	$string = trim(str_replace(' ', '-', $string));
	return preg_replace('/[^A-Za-z0-9\_\-]/','',$string);
}
function clean_az($string) {
	return mb_strtolower(preg_replace('/[^A-Za-z]/','',$string));
}
function clean_09($string) {
	return (preg_replace('/[^0-9]/','',$string));
}

function numeros($string) {
	return intval(preg_replace('/[^0-9]/','',$string));
}

function quote_url_decode($a) {
	$a = rawurldecode($a);
	$a = str_ireplace('&#8217;',"´",$a);
	$a = str_ireplace('&#8216;',"`",$a);
	return $a;
}
function dominio($a) {
	$a = parse_url($a,PHP_URL_HOST);
	$a = str_ireplace('www.','',$a);
	return $a;
}
function lista($a,$between = ', ',$final = ' & ') {
	if(is_string($a)) {
		$b = array();
		$b[] = $a;
		$a = $b;
		unset($b);
	}
	$a = array_values($a);
	$a = array_unique($a);
	$a = array_map('trim',$a);
	$c = count($a);
	if($c == 1) {
		return $a[0];
	} elseif($c == 2) {
		return implode($a,$final);
	}
	$a[$c-2] = $a[$c-2].$final.$a[$c-1];
	unset($a[$c-1]);
	return implode($a,$between);
}
function ordenar($array,$campo,$desc = true,$sinclave = false) {
	$t = array();
	foreach($array as $key => $value) {
		$t[$key] = $value[$campo];
	}

	if($desc === true) {
		arsort($t);
	} elseif($desc === 'desc') {
		arsort($t);
	} elseif($desc === 'asc') {
		asort($t);
	} elseif($desc === 'nat') {
		natsort($t);
	} else {
		asort($t);
	}

	$r = array();
	foreach($t as $key => $value) {
		$r[$key] = $array[$key];
	}
	if($sinclave) {
		$r = array_values($r);
	}
	return $r;
}

function p($a) {
	// if(!is_admin()) return;
	echo "\n\n";
	echo '<pre class="text-white bg-dark">';
	print_r($a);
	echo '</pre>';
}
function pexit($a = false) {
	p($a);
	exit;
}
function save_image($a) {
	ini_set('user_agent', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.125 Safari/537.36');
	$headers = get_headers($a,true);
	if(is_array($headers[0])) {
		$headers[0] = end($headers[0]);
	}

	if(stripos($headers[0],'400') !== false
	OR stripos($headers[0],'401') !== false
	OR stripos($headers[0],'403') !== false
	OR stripos($headers[0],'404') !== false) {
		return false;
	}

	if(is_array($headers['Content-Type'])) {
		$headers['Content-Type'] = end($headers['Content-Type']);
	}

	if(reset(explode('/',$headers['Content-Type'])) != 'image'
	&& $headers['Content-Type'] != 'application/octet-stream'
	&& $headers['Content-Type'] != 'application') {
		return false;
	}

	$ext = 'png';
	if(end(explode('.',$a)) == 'gif') {
		$ext = 'gif';
	} elseif(end(explode('.',$a)) == 'webp') {
		$ext = 'webp';
	}

	$save = ROOT.'media/'.md5($a.time()).'.'.$ext;
	$content = file_get_contents($a);

	if($ext != 'gif'
	&& $ext != 'webp'
	&& strlen($content) > 100) {
		$b = imagecreatefromstring($content);
		if(!$b) {
			return false;
		}
		imagepng($b,$save);
		$size = filesize($save);
		// p(3*1024*1024);
		// p($size);
		// pexit($save);
		if($size > 3*1024*1024) {
			// for($l=1;$l<=9;$l++) {
			// 	imagepng($b,$save,$l);
			unlink($save);
			for($l=70;$l>=40;$l-=5) {
				$save = str_ireplace('.png','.jpg',$save);
				imagejpeg($b,$save,$l);
				$size = filesize($save);
				// p("LA:".$l.' --'.$size.' --- '.$save);
				if($size < 3*1024*1024) {
					// p("LLLL:".$l);
					break;
				}
			}
		}
		// p($size);
		// pexit("!");
	} else {
		file_put_contents($save,$content);
	}

	if(!file_exists($save)) {
		return false;
	}

	return $save;
}


function ago2str($ts)
{
	if(empty($ts)) {
		return 'Never';
	}

	if(substr($ts, 0,2) == '19') {
		return 'Old';
	}

    if(!ctype_digit($ts))
        $ts = strtotime($ts);

    $diff = time() - $ts;
    if($diff == 0)
        return 'Now';
    	elseif($diff > 0)
    {
        $day_diff = floor($diff / 86400);
        if($day_diff == 0)
        {
            if($diff < 60) return 'Now';
            if($diff < 120) return '1M';
            if($diff < 3600) return floor($diff / 60) . 'M';
            if($diff < 7200) return '1H';
            if($diff < 86400) return floor($diff / 3600) . 'H';
        }
        if($day_diff == 1) return '1D';
        if($day_diff < 7) return $day_diff . 'D';
        if($day_diff < 31) return ceil($day_diff / 7) . 'W';
        return date('F Y', $ts);
    }
    else
    {
        $diff = abs($diff);
        $day_diff = floor($diff / 86400);
        if($day_diff == 0)
        {
            if($diff < 120) return 'in a minute';
            if($diff < 3600) return 'in ' . floor($diff / 60) . ' minutes';
            if($diff < 7200) return 'in an hour';
            if($diff < 86400) return 'in ' . floor($diff / 3600) . ' hours';
        }
        if($day_diff == 1) return 'Tomorrow';
        if($day_diff < 4) return date('l', $ts);
        if($day_diff < 7 + (7 - date('w'))) return 'next week';
        if(ceil($day_diff / 7) < 4) return 'in ' . ceil($day_diff / 7) . ' weeks';
        if(date('n', $ts) == date('n') + 1) return 'next month';
        return date('F Y', $ts);
    }
}

function time2str($ts)
{
	if(empty($ts)) {
		return 'Never';
	}

	if(substr($ts, 0,2) == '19') {
		return 'Never';
	}

    if(!ctype_digit($ts))
        $ts = strtotime($ts);

    $diff = time() - $ts;
    if($diff == 0)
        return 'now';
    elseif($diff > 0)
    {
        $day_diff = floor($diff / 86400);
        if($day_diff == 0)
        {
            if($diff < 60) return 'just now';
            if($diff < 120) return '1 minute ago';
            if($diff < 3600) return floor($diff / 60) . ' minutes ago';
            if($diff < 7200) return '1 hour ago';
            if($diff < 86400) return floor($diff / 3600) . ' hours ago';
        }
        if($day_diff == 1) return 'Yesterday';
        if($day_diff < 7) return $day_diff . ' days ago';
        if($day_diff < 31) return ceil($day_diff / 7) . ' weeks ago';
        if($day_diff < 60) return 'last month';
        return date('F Y', $ts);
    }
    else
    {
        $diff = abs($diff);
        $day_diff = floor($diff / 86400);
        if($day_diff == 0)
        {
            if($diff < 120) return 'in a minute';
            if($diff < 3600) return 'in ' . floor($diff / 60) . ' minutes';
            if($diff < 7200) return 'in an hour';
            if($diff < 86400) return 'in ' . floor($diff / 3600) . ' hours';
        }
        if($day_diff == 1) return 'Tomorrow';
        if($day_diff < 4) return date('l', $ts);
        if($day_diff < 7 + (7 - date('w'))) return 'next week';
        if(ceil($day_diff / 7) < 4) return 'in ' . ceil($day_diff / 7) . ' weeks';
        if(date('n', $ts) == date('n') + 1) return 'next month';
        return date('F Y', $ts);
    }
}

function get_redirect_url($url){
	// ini_set('user_agent', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.9) Gecko/20071025 Firefox/2.0.0.9');
	// $h = get_headers($url,true);
	//
	// // p(htmlspecialchars(file_get_contents($url)));
	// // pexit($h);
	// // $redirect_url = null;


	$url_parts = @parse_url($url);
	if (!$url_parts) return false;
	if (!isset($url_parts['host'])) return false; //can't process relative URLs
	if (!isset($url_parts['path'])) $url_parts['path'] = '/';

	$sock = fsockopen($url_parts['host'], (isset($url_parts['port']) ? (int)$url_parts['port'] : 80), $errno, $errstr, 30);
	if (!$sock) return false;

	$request = "HEAD " . $url_parts['path'] . (isset($url_parts['query']) ? '?'.$url_parts['query'] : '') . " HTTP/1.1\r\n";
	$request .= 'Host: ' . $url_parts['host'] . "\r\n";
	$request .= "Connection: Close\r\n\r\n";
	fwrite($sock, $request);
	$response = '';
	while(!feof($sock)) $response .= fread($sock, 8192);
	fclose($sock);

	if(preg_match('/^Location: (.+?)$/m', $response, $matches)){
		if(substr($matches[1], 0, 1) == "/" )
			return $url_parts['scheme'] . "://" . $url_parts['host'] . trim($matches[1]);
		else
			return trim($matches[1]);

	} else {
		return false;
	}

}
function get_all_redirects($url){
	$redirects = array();
	while ($newurl = get_redirect_url($url)){
		if (in_array($newurl,$redirects)){
			break;
		}
		$redirects[] = $newurl;
		$url = $newurl;
	}
	return $redirects;
}
function get_final_url($url){
	$redirects = get_all_redirects($url);
	if(count($redirects)>0){
		return array_pop($redirects);
	} else {
		return $url;
	}
}

function show_toot($toot,$class = '') {

	$t = isset($toot['reblog']['url']) ? $toot['reblog'] : $toot;
	$zid     = $t['id'];
	$tootUrl = TOOT_URL.$t['url'];

	$data = instance_user_status($t['url']);
	// p($data);
	// exit;

	$return  = '';
	$content = '';
	$media = [];

	if(!empty($t['content'])) {
		$content .= '<div class="text">'.$t['content'].'</div>';
	}

	if($t['media_attachments']) {

		$mac = count($t['media_attachments']);
		if($mac > 0) {
			$content .= '<div class="text">';
			$content .= '<div class="media_attachments row">';
			foreach($t['media_attachments'] as $m) {

				$url = $m['url'];
				$prl = $m['preview_url'];
				$alt = $m['description'];

				if($m['type'] == 'image') {
					$content .= '<img class="'.($mac==1?'col-12':'col-6').' rounded" src="'.$prl.'" alt="'.$alt.'" title="'.$alt.'"/>';
				} elseif($m['type'] == 'video') {
					$content .= '<video class="'.($mac==1?'col-12':'col-6').' rounded" src="'.$prl.'" preload="none" controls/></video>';
				} elseif($m['type'] == 'gifv') {
					$content .= '<video class="'.($mac==1?'col-12':'col-6').' rounded" src="'.$url.'" preload autoplay muted loop/></video>';
				}elseif($m['type'] == 'audio') {
					$content .= '<audio class="'.($mac==1?'col-12':'col-6').' rounded" src="'.$prl.'" preload="none" controls/></audio>';
				}
			}
			$content .= '</div>';
			$content .= '</div>';
		}
	}

	// p($toot);

	$return .= '<div class="cell text-light p-3 border-bottom border-dark w-100 '.$class.'">';
	$return .= '<a href="'.$t['account']['url'].'" target="_new" class="avatar">';
	$return .= '<img src="'.$t['account']['avatar'].'"/>';
	$return .= '</a>';
	$return .= '<div class="content">';
	$return .= '<div class="meta">';
	$return .= '<a href="'.$t['account']['url'].'" target="_new class="text-light fw-bold">@'.$t['account']['username'].'@'.$data['instance'].'</a>';
	$return .= '<a href="'.$tootUrl.'" class="float-right fs-8">'.ago2str($t['created_at']).'</a>';
	$return .= '</div>';
	$return .= $content;
	// $return .= '<p>Just do a GET request on loripsum.net/api, to get some placeholder text. You can add extra parameters to specify the output you</p>';
	$return .= '<div class="actions">';
	$return .= '<a onclick="column_post_show('.$zid.')"><i class="fa fa-comment"></i><span class="ms-2">'.$t['replies_count'].'</span></a>';
	$return .= '<a href="'.URL.'?a=reblog&id='.$zid.'" class="'.(isset($t['reblogged'])&&$t['reblogged']==1?'text-green':'').'"><i class="fa fa-retweet" aria-hidden="true"></i><span class="ms-2">'.$t['reblogs_count'].'</span></a>';
	$return .= '<a href="'.URL.'?a=like&id='.$zid.'" class="'.(isset($t['favourited'])&&$t['favourited']==1?'text-gold':'').'"><i class="fa fa-star" aria-hidden="true"></i><span class="ms-2">'.$t['favourites_count'].'</span></a>';
	$return .= '<a href="'.URL.'?a=bookmark&id='.$zid.'" class="'.(isset($t['bookmarked'])&&$t['bookmarked']==1?'text-blue':'').'"><i class="fa fa-bookmark" aria-hidden="true"></i></a>';
	$return .= '<a href="'.$t['url'].'" target="_new"><i class="fa fa-chain" aria-hidden="true"></i></a>';
	$return .= '</div>';
	$return .= '</div>';
	$return .= '</div>';
	return $return;
}
?>
