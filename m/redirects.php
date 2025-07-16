<?php
/**
 * Helpers for performing HTTP redirects based on conditions, for
 * example when certain bots visit the service.
 */
class r {
	static function google($to = null) {
		if(stripos($_SERVER['HTTP_USER_AGENT'],'googlebot') !== false) {
			header('location: '.URL.$to,TRUE,302);
			// header('location: '.URL.$to,TRUE,301);
			exit;
		}
	}
	static function home() {
		header('location: '.URL,TRUE,302);
		exit;
	}
	static function fof() {
		header('location: '.URL.'?s=404',TRUE,302);
		exit;
	}
	static function ref($hash = false) {
		header('Location: '.(
			isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'').
			($hash?'#'.$hash:''),TRUE,302);
		exit;
	}
	static function tohtml($a,$c = false) {
		if(isset($_GET['NO_R'])
		&& $_GET['NO_R'] === true) return;

		if($c && stripos($a,'http://')===false) {
			$a = 'http://'.$c.'.wocial.com/'.$a;
		} elseif(stripos($a,'http://')===false) {
			$a = URL.$a;
		} else {
			$a = $a;
		}
		echo '<html><head><meta http-equiv="refresh" content="0; url='.$a.'"></head><body>Cargando...</body></html>';
		exit;
	}
	static function to($a,$contenido = false) {
		if(stripos($a,'http://')===false && stripos($a,'https://')===false && substr($a,0,2) != '//') {
			$a = URL.$a;
		}
		header('location: '.$a,TRUE,302);
		if(is_string($contenido)) {
			// header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
			// header('Pragma: no-cache'); // HTTP 1.0.
			// header('Expires: 0'); // Proxies.
			header('Content-Length: '.strlen($contenido));
			echo $contenido;
		}
		exit;
	}
	static function get($url,$saltos = 0,$saltos_maximos = 5) {
		if($saltos >= $saltos_maximos) {
			return $url;
		}
		$headers = get_headers($url,1);
		// print_r($headers);
		if(!isset($headers['Location'])) {
			return $url;
		}
		
		if(is_array($headers['Location'])) {
			return r::get(end($headers['Location']),$saltos+1,$saltos_maximos);
		} elseif(is_string($headers['Location'])) {
			return r::get($headers['Location'],$saltos+1,$saltos_maximos);
		} else {
			return $url;
		}
	}
}

?>