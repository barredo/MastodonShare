<?php

include('inc_header.php');

$recoveredArray = [
	"client_id" 		=> $_SESSION['client_id'],
	"client_secret" 	=> $_SESSION['client_secret'],
	"bearer" 			=> $_SESSION['MASTODON_TOKEN']
];

// define('MASTODON_INSTANCE',$_SESSION['instance']);

// p($_SESSION);

// if(stripos($_REQUEST['id'], "@" !== false)) {
// 	p("externo");
// 	$f = new \theCodingCompany\Mastodon("social.lansky.name");
// 	// $f->setMastodonDomain("social.lansky.name");
// 	$toot = $f->getStatusExternal("109575163518503112",false);
// 	p($toot);

// 	p("!!");

// } else {
// 	p("interno");
// 	$t = new \theCodingCompany\Mastodon("mastodon.social");
// 	$t->setCredentials($recoveredArray);

// 	$toot = $t->getStatus("109575163518503112");
// 	p($toot);
// }


$data = instance_user_status($_REQUEST['id']);

if(!empty($data['instance'])
&& !empty($data['status'])) {
	$f = new \theCodingCompany\Mastodon($data['instance']);
	// $f->setMastodonDomain("social.lansky.name");
	$toot    = $f->getStatusExternal($data['status'],true);

	if(isset($toot['id'])) {

		$context = $f->getStatusExternalContext($data['status'],true);

		if(isset($context['ancestors'])
		&& is_array($context['ancestors'])
		&& count($context['ancestors']) > 0) {
			foreach($context['ancestors'] as $ancestor) {
				echo show_toot($ancestor);
			}
		}

		echo show_toot($toot,"cell_highlight");

		if(isset($context['descendants'])
		&& is_array($context['descendants'])
		&& count($context['descendants']) > 0) {
			foreach($context['descendants'] as $descendant) {
				echo show_toot($descendant);
			}
		}
	}

	// p($context);
	// p($toot);

}

include('inc_footer.php');