<?php

class db {
	static function conectar() {
		$_GET['db'] = mysqli_connect('localhost','root','2KTiQ&*c65n-A#8') or die('ERDB');
		mysqli_select_db($_GET['db'],'mastodonshare');
		mysqli_query($_GET['db'],"set names `utf8mb4`");
	}
	static function fetch($a,$cache = false,$cache_int = false) {
		$return = array();
		$q = db::query($a);
		while($r = mysqli_fetch_assoc($q)) {
			$return[] = $r;
		}
		return $return;
	}
	static function group($a,$key,$value) {
		$b = db::fetch($a);
		$return = array();
		foreach($b as $v) {
			$return[$v[$key]] = $v[$value]; 
		}
		return $return;
	}
	static function one($a,$field = false) {
		$b = db::fetch($a);
		$b = reset($b);
		if($field) {
			return $b[$field] ? : false;
		} else {
			return is_array($b) ? $b : false;
		}
	}
	static function last() {
		return mysqli_insert_id($_GET['db']);
	}
	static function query($a) {
		return mysqli_query($_GET['db'],$a);
	}
	static function desconectar() {
		mysqli_close($_GET['db']);
		exit;
	}
}
?>