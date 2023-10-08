<?php
function strlen_urls($a) {
	$t = array();
	$a = str_ireplace('  ',' ',$a);
	$b = explode(' ',trim($a));
	foreach($b as $k => $v) {
		$trim = trim($v,' .,');
		if(filter_var($trim,FILTER_VALIDATE_URL)) {
			$t[] = 23+
				mb_strlen($v,mb_detect_encoding($a))-
				mb_strlen($trim,mb_detect_encoding($a)); //.' '.$v;
		} else {
			$t[] = mb_strlen($v,mb_detect_encoding($a)); //.' '.$v;
		}
	}
	return array_sum($t)+count($t)-1;
	return array(strlen($a),$t,array_sum($t)+count($t)-1);
}
function wordwrap_urls($a,$cada,$separador = "\n") {
	$l = mb_strlen($a,mb_detect_encoding($a));
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
	die("Sorry about this: ".$a."<br/>Please contact us at @quote_plus if this error persists.");
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
		$nicks[] = $v['screen_name'];
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
		$lists[$nick] = db::fetch("select lists.list from lists inner join lists_auths on lists.id = lists_auths.list_id inner join auths on auths.id = lists_auths.auth_id where lists.online = 1 and auths.screen_name = '".$nick."'");
		foreach($lists[$nick] as $k => $v) {
			$lists[$nick][$k] = $v['list'];
			$lists['*'][] = $v['list'];
		}
	}
	
	if($screen_name) {
		return $lists[$screen_name];
	}

	return array_unique($lists['*']);
}
function clean($string) {
	$string = trim(str_replace(' ', '-', $string));
	return preg_replace('/[^A-Za-z0-9\_\-]/','',$string);
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
function paginacion($a) {
	$r = '<nav aria-label="...">';
	$r.= '<ul class="pager">';
	if(isset($a['newer'])) {
		$r.= '<li class="previous">';
		$r.= '<a href="'.$a['newer'].'"><span aria-hidden="true">&larr;</span> Newer</a>';
		$r.= '</li>';
	}
	if(isset($a['older'])) {
		$r.= '<li class="next">';
		$r.= '<a href="'.$a['older'].'">Older <span aria-hidden="true">&rarr;</span></a>';
		$r.= '</li>';
	}
	$r.= '</ul>';
	$r.= '</nav>';
	return $r;
}
function mostrar_tweet($l) {
	
	$r = '<div style="margin:0 auto;display:block;max-width:550px">';
	$r.= '<blockquote class="twitter-tweet" data-lang="en-gb">Loading tweet...<a href="https://twitter.com/'.$l['twitter'].'/status/'.$l['twitter_id'].'"></a></blockquote>';
	$r.= '<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>';
	$r.= '</div>';
	$r.= '<hr/>';
	return $r;

}
function mostrar_link($l) {
	if(empty($l['quote'])
	&& empty($l['image'])
	&& empty($l['title'])
	&& empty($l['comment'])) {
		return;
	}

	$l['comment'] = trim($l['comment']);
	$l['mark'] = (reset(explode("\n",$l['mark'])));
	$antes = $despues = '';
	$pos = stripos($l['quote'],$l['mark']);
	$antes = substr($l['quote'],0,$pos);
	$despues = substr($l['quote'],$pos+strlen($l['mark']));

	$l['s_mark'] = array(
		'text' => $text,
		'antes' => $antes,
		'pos' => $pos,
		'len' => strlen($l['mark']),
		'resaltar' => $l['mark'],
		'despues' => $despues
	);

	$r  = '<div class="entry">';
	$r .= '<h3><a href="http://quote.plus/'.seseintaydos($l['id']).'">'.($l['title']?:($l['comment']?:'http://quote.plus/'.seseintaydos($l['id']))).'</a></h3>';
	$r .= '<p><small>'.dominio($l['url']).'</small></p>';

	if(!empty($l['quote'])) {
		$r .= '<blockquote>'.nl2br($antes).'<span style="background:rgba(192,249,201,1)">'.nl2br($l['mark']).'</span>'.nl2br($despues).'</blockquote>';
	}

	if((!empty($l['comment']) && !empty($l['title']))
	&& preg_replace('/([^a-zA-Z])/i','',$l['comment']) != preg_replace('/([^a-zA-Z])/i','',$l['title'])) {
		$r .= '<p>'.$l['comment'].'</p>';
	}

	if(empty($l['quote'])
	&& !empty($l['image'])) {
		$r .= '<img class="img-responsive img-rounded" src="'.str_ireplace(ROOT,URL,$l['image']).'"/>';
	}

	if(!empty($l['screen_name'])) {
		$r .= '<p>Shared by <a href="'.URL.'@'.$l['screen_name'].'">@'.$l['screen_name'].'</a>';
		if(!empty($l['clicks'])) {
			$r .= ' &mdash; '.$l['clicks'].' clicks';
		}
		$r .= '</p>';
	}

	$r .= '</div>';

	return $r;
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
	if(!DEBUG) return;
	echo "\n\n";
	echo '<pre>';
	print_r($a);
	echo '</pre>';
}
function pexit($a = false) {
	if(!DEBUG) return;
	p($a);
	exit;
}
function save_image($a) {
	ini_set('user_agent', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.125 Safari/537.36');
	$headers = get_headers($a,true);

	if(is_array($headers['Content-Type'])) {
		$headers['Content-Type'] = end($headers['Content-Type']);
	}

	if(reset(explode('/',$headers['Content-Type'])) != 'image'
	&& $headers['Content-Type'] != 'application/octet-stream') {
		return false;
	}

	$ext = 'png';
	if(end(explode('.',$a)) == 'gif') {
		$ext = 'gif';
	}

	$save = ROOT.'media/'.md5($a.time()).'.'.$ext;
	$content = file_get_contents($a);

	if($ext != 'gif'
	&& strlen($content) > 100) {
		$b = imagecreatefromstring($content);
		if(!$b) {
			return false;
		}
		imagepng($b,$save);
		$size = filesize($save);
		// p($size);
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
					// p("L:".$l);
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
?>