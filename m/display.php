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

	$ac_id = rand(2342342342,234234929429929292);

	$r  = '<form method="post" action="'.URL.'?s=add_comments">';
	$r .= '<input type="hidden" name="link_id" value="'.$l['id'].'"/>';
	$r .= '<input type="hidden" name="future" value="'.($l['future_comments']==true?'1':'0').'"/>';
	$r .= '<input type="hidden" name="screen_name" value="'.$l['screen_name'].'"/>';

	$r .= '<small class="form-text mb-3 text-muted">Tweet the comments as @'.$l['screen_name'].' '.($l['future_comments']==true?' (when the tweet is published)':'').'<input checked name="tweet" type="checkbox" name="tweet_it" value="1" class="form-check-input ml-2"></small>';

	// $r .= '<label class="col-sm-2 form-control-label" for="inputDanger1">Tweet the comments as @'.$l['screen_name'].'</label>';
	// $r .= '<div class="col-sm-10">';
	$r .= '<textarea name="comment" placeholder="Add further comments" class="form-control mb-1 form-control-sm add_comments" id="ac_'.$ac_id.'" rows="1"></textarea>';

	// $r .= '</div>';
	// $r .= '<div class="form-group">';
	$r .= '<button type="submit" class="btn btn-sm btn-success">Comment</button>';
	$r .= '<small class="ml-3 text-muted pull-right count-comments"><span id="cac_'.$ac_id.'">0</span>/140</small>';
	// $r .= '</div>';

	// $r .= '<textarea name="comment" placeholder="Add further comments" class="form-control" rows="3"></textarea>';

	// $r .= '<div class="form-check">';
	// $r .= '<label class="form-check-label"><input checked name="tweet" type="checkbox" name="tweet_it" value="1" class="form-check-input">Tweet the comments as @'.$l['screen_name'].'</label>';
	// $r .= '</div>';

	// $r .= '<button type="submit" class="btn btn-sm btn-success">Comment</button>';

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
	$r.= '<ul class="pagination justify-content-center mt5">';
	if(isset($a['newer'])) {
		$r.= '<li class="page-item">';
		$r.= '<a class="page-link" href="'.$a['newer'].'"><span aria-hidden="true">&larr;</span> Newer</a>';
		$r.= '</li>';
	}
	if(isset($a['older'])) {
		$r.= '<li class="page-item">';
		$r.= '<a class="page-link" href="'.$a['older'].'">Older <span aria-hidden="true">&rarr;</span></a>';
		$r.= '</li>';
	}
	$r.= '</ul>';
	$r.= '</nav>';
	return $r;
}
function mostrar_import($a) {
	// p($a);
	$r  = '<hr/><div class="mt-5 import well media-quote">';
	$r .= '<form action="'.URL.'?s=import" method="post" class="form-horizontal">';
	if(!empty($a['id'])) {
		$r .= '<input type="hidden" name="id" value="'.$a['id'].'">';
	}

	$r .= '<div class="form-group row">';
	$r .= '<label for="rss" class="col-3 col-form-label">RSS Feed URL</label>';
	$r .= '<div class="col-9">';
	$r .= '<input type="text" name="rss" class="form-control" id="rss" value="'.$a['rss'].'" placeholder="http://">';
	$r .= '</div></div>';

	$r .= '<div class="form-group row">';
	$r .= '<label for="twitter" class="col-3 col-form-label">Account</label>';
	$r .= '<div class="col-9">';
	$r .= '<select class="custom-select" name="twitter" id="twitter">';
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

	$r .= '<div class="form-group row">';
	$r .= '<label for="active" class="col-3 col-form-label">Active</label>';
	$r .= '<div class="col-9">';
	$r .= '<input type="checkbox" id="active" name="active" value="1"'.($a['active']==1?' checked':'').'>';
	$r .= '</div></div>';

	// $r .= '<div class="form-group">';
	// $r .= '<label for="shorten" class="col-sm-2 control-label">Shorten links</label>';
	// $r .= '<div class="col-sm-10">';
	// $r .= '<input type="checkbox" id="shorten" name="shorten" value="1"'.($a['shorten']==1?' checked':'').'>';
	// $r .= '</div></div>';

	$r .= '<div class="form-group row">';
	$r .= '<div class="col-9 offset-md-3">';
	$r .= '<button type="submit" class="btn btn-primary">';
	if(!empty($a['id'])) {
		$r .= 'Update';
	} else {
		$r .= 'Add RSS';
	}
	$r .= '</button>';
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

	$r .= '<div class="media media-quote mt-4">';

	$r .= '<a class="d-flex mr-3" href="'.URL.'@'.($l['screen_name']?:$l['twitter']).'">';
	$r .= '<img style="width:50px;height:50px" class="rounded" src="'.avatar_twitter($l['screen_name']?:$l['twitter']).'"/>';
	$r .= '</a>';

	$r .= '<div class="card">';
	$r .= '<div class="card-header">';

	if(!empty($l['url'])) {
		$r .= '<a href="'.URL.seseintaydos($l['id']).'">';
	}

	$r .= nl2br($l['title']?:($l['comment']?:($l['url']?URL.seseintaydos($l['id']):'Quote:')));

	if(!empty($l['url'])) {
		$r .= '</a><small class="muted"> &mdash; <a href="'.URL.'domain/'.dominio($l['url']).'">'.dominio($l['url']).'</a></small>';
	}

	$r .= '</div>';

	if(!empty($l['quote'])) {
		$r .= '<div class="card-block p-3 font-italic">';
		$r .= '<blockquote class="card-text" style="font-size:140%;line-height:1.3">'.nl2br($antes).'<span style="background:rgba(192,249,201,1)">'.nl2br($l['mark']).'</span>'.nl2br($despues).'</blockquote>';
		$r .= '</div>';
	}

	if(empty($l['quote'])
	&& !empty($l['image'])
	&& file_exists($l['image'])) {
		$r .= '<div class="card-block">';
		if(!empty($l['url'])) {
			$r .= '<a href="http://quote.plus/'.seseintaydos($l['id']).'">';
		}
		$r .= '<img class="img-fluid rounded" src="'.str_ireplace(ROOT,URL,$l['image']).'"/>';
		if(!empty($l['url'])) {
			$r .= '</a>';
		}
		$r .= '</div>';
	}

	if(is_logged()) {
		$r .= '<div class="card-block p-1">';

		if(($l['status'] == 1
		OR $l['status'] == 0)
		&& is_logged_as($l['screen_name']?:$l['twitter'])) {
			$r .= '<a onclick="return confirm(\'Are you sure want to delete this?\');" class="btn btn-link btn-sm" href="'.URL.'index.php?s=delete&link='.seseintaydos($l['id']).'" title="Delete this link"><span class="fa fa-trash"></span> Delete</a>';
		}

		if(count(accounts()) > 1
		&& !empty($l['twitter_id'])) {
			$r .= '<div class="btn-group ml-1">';
			$r .= '<button class="btn btn-link btn-sm dropdown-toggle" type="button" id="dropdownMenu'.$l['twitter_id'].'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">';
			$r .= '<span class="fa fa-retweet"></span> Retweet <span class="caret"></span>';
			$r .= '</button>';
			$r .= '<div class="dropdown-menu" aria-labelledby="dropdownMenu'.$l['twitter_id'].'">';
			foreach(accounts() as $a => $b) {
				if($a != $l['screen_name']) {
					$r .= '<a class="dropdown-item" href="'.URL.'?s=retweet&tweet='.$l['twitter_id'].'&from='.$a.'">@'.$a.'</a>';
				}
			}
			$r .= '<div class="dropdown-divider"></div>';
			$r .= '<a class="dropdown-item" href="https://twitter.com/'.$l['screen_name'].'/status/'.$l['twitter_id'].'" target="_blank">View tweet</a>';
			$r .= '</div>';
			$r .= '</div>';
		}
		if(is_logged_as($l['screen_name']?:$l['twitter'])
		&& $l['status'] == 0) {
			$r .= '<a title="Scheduled. Only you can see this link" class="btn btn-link btn-sm pull-right" href="'.URL.'link?link='.seseintaydos($l['id']).'"><span class="fa fa-clock-o"></span> <span data-time="'.$l['date'].'" class="time">'.date('Y-m-d, H:i',$l['date']).'</span></a>';
		}
		$r .= '</div>';
	}

	if((!empty($l['comment']) && !empty($l['title']))
	&& preg_replace('/([^a-zA-Z])/i','',$l['comment']) != preg_replace('/([^a-zA-Z])/i','',$l['title'])) {
		$r .= '<div class="card-footer">';
		$r .= nl2br($l['comment']);
		$r .= '</div>';
	}

	if(is_logged_as($l['screen_name']?:$l['twitter'])) {

		if($l['status'] == 0) {
			// if(DEBUG) {
			//
			// 	$r .= '<div class="card-block card-text">';
			// 	$r .= '<small><a class="btn btn-warning btn-sm float-right" href="'.URL.'link?link='.seseintaydos($l['id']).'">Only you can see this link</a> The link is scheduled to be published<br/><span data-time="'.$l['date'].'" class="time">@ '.date('Y-m-d, H:i',$l['date']).'</span></small>';
			// 	$r .= '</div>';
			// }

		}
		if(DEBUG OR $l['status'] > 0) {
			if(is_array($l['comments'])
			&& count($l['comments']) > 0) {
				foreach($l['comments'] as $comment) {
					if(empty($comment['comment'])) {
						continue;
					}
					$r .= '<div class="card-footer">';
					if(is_logged_as($l['screen_name']?:$l['twitter'])) {
						$r .= '<a class="btn btn-sm btn-link float-right p-0" onclick="return confirm(\'Are you sure want to delete this?\');" href="'.URL.'?s=deletequote&id='.$comment['id'].'"><span class="fa fa-trash" title="Delete this comment"></span></a>';
					}
					$r .= nl2br($comment['comment']);
					$r .= '</div>';
				}
			}
			if($l['add_comments']) {
				$r .= '<div class="card-footer">';
				$r .= add_comments($l);
				$r .= '</div>';
			}
		}
	}

	$r .= '</div>';
	$r .= '</div>';

	return $r;
}
?>
