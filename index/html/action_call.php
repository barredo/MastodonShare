<?php

ini_set('display_errors','On');
error_reporting(E_ERROR);

$method = clean_az($_REQUEST['method']);
$return = clean_az($_REQUEST['return']);

// p($method);
// p($return);

if(empty($method)
OR empty($return)) {
	$results = ['error' => 'is empty'];
}

$methods = [
	'hometimeline'   => [
		'endpoint'   => 'api/v1/timelines/home',
		'args'       => ['min_id','since_id','limit']
	],
	'notifications'  => [
		'endpoint'   => 'api/v1/notifications',
		'args'       => ['min_id','max_id','since_id','limit']
	],
	// 'conversations'  => [
	// 	'endpoint'   => 'api/v1/conversations',
	// 	'args'       => ['min_id','max_id','since_id','limit']
	// ],
	'lists'          => [],
	'list'           => [
		'endpoint'   => '/api/v1/timelines/list/'.clean_09($_REQUEST['id']),
		'need'       => ['id'],
		'args'       => ['min_id','max_id','since_id','limit']
	],
	'thread'         => [
		'need'       => ['status'],
		'args'       => ['thread']
	],
	'notifications'  => [
		'endpoint'   => 'api/v1/conversations',
		'args'       => ['min_id','max_id','since_id','limit']
	],
	'search'         => [
		'need'       => ['q'],
		'endpoint'   => 'api/v2/search',
		'args'       => ['min_id','since_id','limit']
	],
	'tag'            => [
		'need'       => ['hashtag'],
		'endpoint'   => '/api/v1/timelines/tag/'.clean($_REQUEST['hashtag']),
		'args'       => ['local','remote','only_media','min_id','since_id','limit']
	],
	'bookmarks'      => [
		'endpoint'   => '/api/v1/bookmarks',
		'args'       => ['min_id','max_id','since_id','limit']
	],
	'favourites'     => [
		'endpoint'   => '/api/v1/favourites',
		'args'       => ['min_id','max_id','since_id','limit']
	],
	'profile'        => [
		'need'       => ['acct'],
		'data'       => clear_address($_REQUEST['acct'])
	],
	'profileexternal'=> [
		'need'       => ['acct'],
		'data'       => clear_address($_REQUEST['acct'])
	],
	'user'           => [
		'need'       => ['account_id'],
		'endpoint'   => '/api/v1/accounts/'.clean_09($_REQUEST['account_id']).'/statuses',
		'args'       => ['min_id','max_id','since_id','limit','exclude_replies','exclude_reblogs','only_media']
	],
	'followers'      => [
		'need'       => ['account_id'],
		'endpoint'   => '/api/v1/accounts/'.clean_09($_REQUEST['account_id']).'/followers',
		'args'       => ['min_id','max_id','since_id','limit']
	],
	'following'     => [
		'need'       => ['account_id'],
		'endpoint'   => '/api/v1/accounts/'.clean_09($_REQUEST['account_id']).'/following',
		'args'       => ['min_id','max_id','since_id','limit']
	]
];

$returns = [
	'json',
	'xml'
];
$args = [];
$valid_args = !empty($methods[$method]['args']) ? $methods[$method]['args'] : [];
$need_args  = !empty($methods[$method]['need']) ? $methods[$method]['need'] : [];

// p($_REQUEST);

if(count($_REQUEST) > 0) {
	foreach($_REQUEST as $k => $v) {
		if(in_array($k, $valid_args)) {
			$args[$k] = $v;
		}
	}
} 

if(count($need_args) > 0) {
	foreach($need_args as $v) {
		// p($v);
		if(!isset($_REQUEST[$v])) {
			$results = ['error' => 'no '.$v];
			break;
		}
	}
}
// p($_REQUEST);


if(!isset($methods[$method])) {
	$results = ['error' => 'no method'];
}

if(!in_array($return, $returns)) {
	$results = ['error' => 'no return'];
}

// if(is_admin()) {
// 	$data = file_get_contents(ROOT.'fakepost.txt');
// 	// die("!");
// 	$results = json_decode($data);
// }

// p($args);
// p($methods[$method]['endpoint']);
// exit;

// $info = $t->getUser();
// p($_SESSION);
// p($info);
// exit;

if(!$results) {

	$recoveredArray = [
		"client_id" 		=> $_SESSION['client_id'],
		"client_secret" 	=> $_SESSION['client_secret'],
		"bearer" 			=> $_SESSION['MASTODON_TOKEN']
	];

	define('MASTODON_INSTANCE',$_SESSION['instance']);

	if($method == 'profileexternal') {
		$profile_address = $methods[$method]['data'];
		if(empty($profile_address)) {
		    return [];
		}
		$instance = only_instance($profile_address);
		$t = new \theCodingCompany\Mastodon($instance);
		$t->setMastodonDomain($instance);
		$t->setCredentials($recoveredArray);

	} else {
		$t = new \theCodingCompany\Mastodon();
		$t->setMastodonDomain($_SESSION['instance']);
		$t->setCredentials($recoveredArray);
	}

	// p($methods[$method]);

	if($method == 'thread') {
	
		$pepe    = [];
		$results = [];
		$status  = $t->getStatus($_REQUEST['status']);
		$context = $t->getStatusContext($_REQUEST['status']);

		if(!empty($status['id'])) {
			$status['highlight'] = 1;
			$results[] = $status;
		}

		if(isset($context['ancestors'])
		&& is_array($context['ancestors'])
		&& count($context['ancestors']) > 0) {
			foreach($context['ancestors'] as $ancestor) {
				$ascestor['ancestor'] = 1;
				array_push($results,$ancestor);
			}
		}

		if(isset($context['descendants'])
		&& is_array($context['descendants'])
		&& count($context['descendants']) > 0) {
			foreach($context['descendants'] as $descendant) {
				array_push($results,$descendant);
			}
		}
		$results = ordenar($results,'created_at',false,true);

	} elseif($method == 'search') {
		$results = $t->getSearchResults(
			$methods[$method]['endpoint'],
			$args
		);
	} elseif($method == 'user') {
		$results = $t->getTimeline(
			$methods[$method]['endpoint'],
			$args
		);
	} elseif($method == 'lists') {
		$results = $t->getLists();
		// foreach($results as $k => $v) {
		// 	$results[$k]['list_id']   = $v['id'];
		// 	$results[$k]['list_name'] = $v['title'];
		// 	unset($results[$k]['id']);
		// 	unset($results[$k]['title']);
		// }
	} elseif($method == 'list') {
		$results = $t->getTimeline(
			$methods[$method]['endpoint'],
			$args
		);
	} elseif($method == 'profileexternal') {
		$results = $t->getProfile($methods[$method]['data']);
	} elseif($method == 'profile') {
		$results = $t->getProfile($methods[$method]['data']);
	} elseif($method == 'followers') {
		$results = $t->getTimeline(
			$methods[$method]['endpoint'],
			$args
		);
	} elseif($method == 'following') {
		$results = $t->getTimeline(
			$methods[$method]['endpoint'],
			$args
		);
	} elseif($method == 'tag') {
		$results = $t->getTimeline(
			$methods[$method]['endpoint'],
			$args
		);
	} elseif($method == 'notifications') {
		$results = $t->getNotifications($args);
	} elseif($method == 'bookmarks') {
		$results = $t->getTimeline(
			$methods[$method]['endpoint'],
			$args
		);
		$results = ordenar($results,'created_at',true,true);
	} elseif($method == 'favourites') {
		$results = $t->getTimeline(
			$methods[$method]['endpoint'],
			$args
		);
		$results = ordenar($results,'created_at',true,true);
	} elseif($method == 'hometimeline') {
		$results = $t->getTimeline(
			$methods[$method]['endpoint'],
			$args
		);
		$results = ordenar($results,'created_at',true,true);
	} else {
		$results = [];
	}


    // $results = ordenar($results,'created_at',true,true);

    foreach($results as $k => $v) {
    	if(isset($v['created_at'])) {
        	$results[$k]['created'] = ago2str($v['created_at']);
    	}
    	if(isset($v['status']['created_at'])) {
        	$results[$k]['status']['created'] = ago2str($v['status']['created_at']);
    	}
    }

}

if($return == 'json') {
	header('Content-Type: application/json');
	echo json_encode($results);
	exit;
}

if($return == 'xml') {
	header('Content-Type: application/json');
	// $xml = new SimpleXMLElement('<root/>');
	// array_walk_recursive($results, array ($xml, 'addChild'));
	// echo ($xml->asXML());

	print_r($results);
	exit;

	// echo arrayToXML($results, new SimpleXMLElement('<root/>'), 'child_name_to_replace_numeric_integers');
}

// p($results);

?>