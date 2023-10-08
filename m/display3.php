<?php
function magic_form($a = array()) {
	$r  = '<form method="post" action="'.URL.'?s=magic" class="media-quote">';
	$r .= '<div class="form-group">';
	$r .= '<textarea name="text" placeholder="Link or whatever" class="form-control" rows="3"></textarea>';
	$r .= '</div>';
	$r .= '<button type="submit" class="btn btn-sm btn-success">Preview</button>';
	$r .= '</form>';
	return $r;
}
function page_header($a = array()) {
	$r  = '<div class="page-header"><h1>';
	if(!empty($a['link'])) {
		$r .= '<a href="'.$a['link'].'">';
	}
	$r .= $a['title'];
	if(!empty($a['link'])) {
		$r .= '</a>';
	}
	if(!empty($a['subtitle'])) {
		$r .= ' <small>'.$a['subtitle'].'</small>';
	}
	$r .= '</h1>';
	if(!empty($a['extra'])) {
		$r .= '<p>'.$a['extra'].'</p>';
	}
	$r .= '</div>';
	return $r;
}
function add_comments($l = array()) {

	if(!is_logged_as($l['screen_name'])) return;

	$r  = '<form method="post" action="'.URL.'?s=add_comments" class="panel-footer">';
	$r .= '<div class="form-group">';
	$r .= '<input type="hidden" name="link_id" value="'.$l['id'].'"/>';
	$r .= '<input type="hidden" name="screen_name" value="'.$l['screen_name'].'"/>';
	$r .= '<textarea name="comment" placeholder="Add further comments" class="form-control" rows="3"></textarea>';
	$r .= '</div>';
	$r .= '<div class="checkbox">';
	$r .= '<label><input checked name="tweet" type="checkbox" name="tweet_it" value="1">Tweet the comments as @'.$l['screen_name'].'</label>';
	$r .= '</div>';
	$r .= '<button type="submit" class="btn btn-sm btn-success">Comment</button>';
	$r .= '</form>';
	return $r;
}

function avatar_twitter($screen_name) {
	$screen_name = strtolower($screen_name);
	if(file_exists(ROOT.'s/resources/avatars/'.($screen_name).'.jpg')) {
		return URL.'s/resources/avatars/'.($screen_name).'.jpg';
	}
	return URL.'s/img/quote_plus_128.png';
}
function paginacion($a) {
	$r = '<nav aria-label="pagination">';
	$r.= '<ul class="pager">';
	if(isset($a['newer'])) {
		$r.= '<li>';
		$r.= '<a href="'.$a['newer'].'"><span aria-hidden="true">&larr;</span> Newer</a>';
		$r.= '</li>';
	}
	if(isset($a['older'])) {
		$r.= '<li>';
		$r.= '<a href="'.$a['older'].'">Older <span aria-hidden="true">&rarr;</span></a>';
		$r.= '</li>';
	}
	$r.= '</ul>';
	$r.= '</nav>';
	return $r;
}
function mostrar_import($a) {
	// p($a);
	$r  = '<div class="import well media-quote">';
	$r .= '<form action="'.URL.'?s=import" method="post" class="form-horizontal">';
	if(!empty($a['id'])) {
		$r .= '<input type="hidden" name="id" value="'.$a['id'].'">';
	}

	$r .= '<div class="form-group">';
	$r .= '<label for="rss" class="col-sm-3 control-label">RSS Feed URL</label>';
	$r .= '<div class="col-sm-9">';
	$r .= '<input type="text" name="rss" class="form-control" id="rss" value="'.$a['rss'].'" placeholder="http://">';
	$r .= '</div></div>';

	$r .= '<div class="form-group">';
	$r .= '<label for="twitter" class="col-sm-3 control-label">Account</label>';
	$r .= '<div class="col-sm-9">';
	$r .= '<select name="twitter" id="twitter">';
	if(empty($a['twitter'])) {
		$r .= '<option selected disabled>Select</option>';
	} else {
		$r .= '<option selected value="'.$a['twitter'].'">@'.$a['twitter'].'</option>';
	}
	if(is_array($a['accounts'])) {
		foreach($a['accounts'] as $t) {
			$r .= '<option value="'.$t.'">@'.$t.'</option>';
		}
	}
	$r .= '</select>';
	$r .= '</div></div>';

	// $r .= '<div class="form-group">';
	// $r .= '<label for="padding" class="col-sm-2 control-label">Padding</label>';
	// $r .= '<div class="col-sm-10">';
	// $r .= '<input type="number" id="padding" name="padding" value="'.$a['padding'].'" placeholder="Minutes between tweets">';
	// $r .= '</div></div>';

	// $r .= '<div class="form-group">';
	// $r .= '<label for="repeat" class="col-sm-2 control-label">Repeat after</label>';
	// $r .= '<div class="col-sm-10">';
	// $r .= '<select name="repeat" id="repeat">';
	// $r .= '<option'.(0==$a['repeat']?' selected':'').' value="0">Send only once</option>';
	// for($i=15;$i<=24*60;$i+=15) {
	// 	$hours = floor($i/60);
	// 	$mins = ($i-($hours*60));
	// 	$r .= '<option value="'.$i.'"'.($i==$a['repeat']?' selected':'').'>'.$hours.' hour'.($hours!=1?'s':'').($mins>0?' & '.$mins.' mins':'').'</option>';
	// }
	// $r .= '</select>';
	// $r .= '</div></div>';

	$r .= '<div class="form-group">';
	$r .= '<label for="active" class="col-sm-3 control-label">Active</label>';
	$r .= '<div class="col-sm-9">';
	$r .= '<input type="checkbox" id="active" name="active" value="1"'.($a['active']==1?' checked':'').'>';
	$r .= '</div></div>';

	// $r .= '<div class="form-group">';
	// $r .= '<label for="shorten" class="col-sm-2 control-label">Shorten links</label>';
	// $r .= '<div class="col-sm-10">';
	// $r .= '<input type="checkbox" id="shorten" name="shorten" value="1"'.($a['shorten']==1?' checked':'').'>';
	// $r .= '</div></div>';

	$r .= '<div class="form-group"><div class="col-sm-offset-3 col-sm-9">';
	$r .= '<button type="submit" class="btn btn-primary">Save</button>';
	$r .= '</div></div>';
	$r .= '</form>';
	$r .= '</div>';
	return $r;
}
function mostrar_tweet($l) {
	
	$r = '<div class="quote-tweet">';
	$r.= '<blockquote class="twitter-tweet" data-lang="en-gb">Loading tweet...<a href="https://twitter.com/'.($l['twitter']?:$l['screen_name']).'/status/'.($l['twitter_id']?:$l['tweet']).'"></a></blockquote>';
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

	if($l['status'] == 0
	&& is_array($_SESSION['twitter'][$l['screen_name']?:$l['twitter']]) == false) {
		// echo 'cant see this';
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

	$r  = '';

	$r .= '<div class="media media-quote">';
	$r .= '<div class="media-left media-top">';
	$r .= '<a href="'.URL.'@'.($l['screen_name']?:$l['twitter']).'">';
	$r .= '<img style="width:50px" class="img-rounded" src="'.avatar_twitter($l['screen_name']?:$l['twitter']).'"/>';
	$r .= '</a>';
	$r .= '</div>';
	$r .= '<div class="media-body">';

	$r .= '<div class="panel-quote panel panel-default">';
	$r .= '<div class="panel-heading">';

	if(($l['status'] == 1 OR $l['status'] == 0)
	&& is_logged_as($l['screen_name']?:$l['twitter'])) {
		$r .= '<a onclick="return confirm(\'Are you sure want to delete this?\');" class="btn btn-danger btn-xs pull-right" href="'.URL.'index.php?s=delete&link='.seseintaydos($l['id']).'" title="Delete this link"><span class="glyphicon glyphicon-trash"></span> Delete</a>';
	}

	if(is_logged()
	&& count(accounts()) > 1
	&& DEBUG
	&& !empty($l['twitter_id'])) {
		// $r .= '<a onclick="return confirm(\'Are you sure want to retweet this?\');" class="btn btn-info btn-xs pull-right" href="'.URL.'index.php?s=retweet&tweet='.$l['twitter_id'].'" title="Retweet this link"><span class="glyphicon glyphicon-retweet"></span> Retweet</a>';
		$r .= '<div class="btn-group pull-right">';
		$r .= '<button class="btn btn-info btn-xs dropdown-toggle" type="button" id="dropdownMenu'.$l['twitter_id'].'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">';
		$r .= '<span class="glyphicon glyphicon-retweet"></span> Retweet <span class="caret"></span>';
		$r .= '</button>';
		$r .= '<ul class="dropdown-menu" aria-labelledby="dropdownMenu'.$l['twitter_id'].'">';
		foreach(accounts() as $a => $b) {
			if($a != $l['screen_name']) {
				$r .= '<li><a href="'.URL.'?s=retweet&tweet='.$l['twitter_id'].'&from='.$a.'">@'.$a.'</a></li>';
			}
		}
		$r .= '<li role="separator" class="divider"></li>';
		$r .= '<li><a href="https://twitter.com/'.$l['screen_name'].'/status/'.$l['twitter_id'].'" target="_blank">View tweet</a></li>';
		$r .= '</ul>';
		$r .= '</div>';
	}

	if(!empty($l['url'])) {
		$r .= '<a href="'.URL.seseintaydos($l['id']).'">';
	}

	$r .= ($l['title']?:($l['comment']?:($l['url']?URL.seseintaydos($l['id']):'Quote:')));

	if(!empty($l['url'])) {
		$r .= '</a><small class="muted"> &mdash; <a href="'.URL.'domain/'.dominio($l['url']).'">'.dominio($l['url']).'</a></small>';
	}

	$r .= '</div>';

	if(!empty($l['quote'])) {
		$r .= '<div class="panel-body">';
		$r .= '<blockquote>'.nl2br($antes).'<span style="background:rgba(192,249,201,1)">'.nl2br($l['mark']).'</span>'.nl2br($despues).'</blockquote>';
		$r .= '</div>';
	}

	if(empty($l['quote'])
	&& !empty($l['image'])
	&& file_exists($l['image'])) {
		$r .= '<div class="panel-body">';
		if(!empty($l['url'])) {
			$r .= '<a href="http://quote.plus/'.seseintaydos($l['id']).'">';
		}
		$r .= '<img class="img-responsive img-rounded" src="'.str_ireplace(ROOT,URL,$l['image']).'"/>';
		if(!empty($l['url'])) {
			$r .= '</a>';
		}
		$r .= '</div>';
	}

	if((!empty($l['comment']) && !empty($l['title']))
	&& preg_replace('/([^a-zA-Z])/i','',$l['comment']) != preg_replace('/([^a-zA-Z])/i','',$l['title'])) {
		$r .= '<div class="panel-footer">';
		$r .= nl2br($l['comment']);
		$r .= '</div>';
	}

	if($l['status'] == 0
	&& is_logged_as($l['screen_name']?:$l['twitter'])) {

		$r .= '<div class="panel-footer">';
		$r .= '<a class="btn btn-warning btn-xs pull-right" href="'.URL.'link?link='.seseintaydos($l['id']).'">Only you can see this link</a> The link is scheduled to be published<br/><span data-time="'.$l['date'].'" class="time">@ '.date('Y-m-d, H:i',$l['date']).'</span>';
		$r .= '</div>';
	} else {
		
		if(is_array($l['comments'])) {
			foreach($l['comments'] as $comment) {
				if(empty($comment['comment'])) {
					continue;
				}
				$r .= '<div class="panel-footer">';
				$r .= nl2br($comment['comment']);
				$r .= '</div>';
			}
		}
		if($l['add_comments']) {
			$r .= add_comments($l);
		}
	}

	$r .= '</div>';

	$r .= '</div>';
	$r .= '</div>';

	return $r;
}
?>