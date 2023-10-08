<?php
$_GET['include_config'] = 1;


// ini_set('display_errors','On');
// error_reporting(E_ERROR|E_WARNING);

// ini_set('display_errors','On');
// error_reporting(E_ERROR|E_WARNING);

define('URL','https://mastodonshare.com/index/');
define('DOMAIN','mastodonshare.com');
define('APP_NAME','MastodonSearch');
define('ROOT','/var/www/mastodonshare.com/index/');
define('HTML',ROOT.'html/');

// define('DEBUG',($_SERVER['REMOTE_ADDR'] == '37.11.169.124' OR isset($_GET['debug'])) ? true : false);

// if(!isset($_SERVER['HTTPS'])
// && !empty($_SERVER['REQUEST_URI'])) {
// 	header("Location: https://quote.plus".$_SERVER['REQUEST_URI']);
// 	exit;
// }

// if(!empty($_SERVER['REMOTE_ADDR'])) {
// 	define('CONSUMER_COOKIE',sha1($_SERVER['REMOTE_ADDR'].'ljkhklho89789798798789798798789'.$_SERVER['HTTP_USER_AGENT']).sha1($_SERVER['REMOTE_ADDR'].'ljk90919191919191918181798789'.$_SERVER['HTTP_USER_AGENT']));
// }
// define('CONSUMER_KEY','JVl8u8iCNJ1YBv22CuHAiM31e');
// define('CONSUMER_SECRET','wplDafTFUdw3zwHKKW2VauxaFEerrupZvFviuYL0MJItmUMH1L');
// define('OAUTH_QUOTE','3362361766-cWKi5zsaF2dNxHFGfhVkRgrK7zbu6dZnvLy4wvw');
// define('OAUTH_QUOTE_TOKEN','kH8jaxRuDETHJhUmud4doCZN0bJovPHzsTW1TjayzhoNU');

// define('MASTODON_APP_NAME',       'MastoDeck');
// define('MASTODON_APP_WEB',        'https://mastodeck.com');
// define('MASTODON_REDIRECT_URI',   URL.'login');

// define('MASTODON_CONSUMER_KEY',   'qf6KO2UJXPwQakFfBCDmtpGiIjNoJy6CNwPeJbHoxXc');
// define('MASTODON_CONSUMER_SECRET','GSM9MLKBA178sa0tNg4xsXn3zPxTOtTv_To2M_FVDLE');
// define('MASTODON_BEARER',         '280EUXuLj4Fa1VY3U-nbvL0k4yCCY0jRuQHUtdmTJv4');
// define('MASTODON_USER_EMAIL',     'alex@barredo.es');
// define('MASTODON_USER_PASSWORD',  'NMy52JrWnefe3CG');
// define('MASTODON_SCOPE',          'read write');

// define('TOOT_URL',                'https://mastodeck.com/?s=toot&id=');


// Set the values to be used by test_api.php obtained by test_oauth.php.
// $client_id = 'brAOGYxK-z_eYfBq9mw4eC7-IjynJ6RiR8bm7duf_BM';
// $client_secret = 'chw-LJSJZBVlAFnyC3xL7mjS1iGkQjF_VeaLpCQ8LAk';
// $bearer = '280EUXuLj4Fa1VY3U-nbvL0k4yCCY0jRuQHUtdmTJv4';

// // Set your Mastodon email and password
// $mastodon_email = 'alex@barredo.es';
// $mastodon_password = 'NMy52JrWnefe3CG';


// define('OAUTH_QUOTE','2071051-DkvQW0cfrsOV0v8fKdHSlZL2VRmI2FvrIoMrdvqBP6');
// define('OAUTH_QUOTE_TOKEN','5vkyeF42YH1WRvyh9q6dV76iITBX2kErptucqkQvhmjRW');

// define('OAUTH_CALLBACK',URL.'?s=callback');

ini_set("session.cookie_lifetime",31536000);
ini_set("session.gc_maxlifetime",31536000);
ini_set('session.cookie_domain','.'.DOMAIN);

session_start();

// require_once ROOT.'vendor/thecodingcompany/php-mastodon/autoload.php';

// require_once ROOT.'vendor/thecodingcompany/php-mastodon/theCodingCompany/HttpRequest.php';


// require_once(ROOT.'m/HttpRequest.php');
require_once(ROOT.'m/database.php');
require_once(ROOT.'m/general.php');
require_once(ROOT.'m/stopwords.php');
// require_once(ROOT.'m/display.php');
// require_once(ROOT.'m/opengraph.php');
// require_once(ROOT.'m/ascii.php');
require_once(ROOT.'m/redirects.php');
// require_once(ROOT.'m/simple_html_dom.php');


// if(is_admin() && isset($_GET['debug'])) {
// 	ini_set('display_errors','On');
// 	error_reporting(E_ERROR|E_WARNING);
// 	define('DEBUG',true);
// } else {
// 	ini_set('display_errors','Off');
// 	error_reporting(0);
// 	define('DEBUG',false);
// }


ini_set('display_errors','On');
error_reporting(E_ERROR|E_WARNING);
define('DEBUG',true);