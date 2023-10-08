<?php

include('/var/www/mastodonshare.com/index/m/config.php');

$token = 'iUFsTF39JkTcVRMK3NJHQCphcRh26BlQn4eNAkQa5s5iQbXDJVc0cyeXy58w6X8cfuHaUOwp91b5T3C3aaOpTS230tJijUKKMIMxZlE8l3O55HaB2xl79gTPkf1bxX1q';

p($token);

// $t = new \theCodingCompany\Mastodon();
// require_once ROOT.'vendor/thecodingcompany/php-mastodon/autoload.php';

// $http = ::Get('https://instances.social/api/1.0/instances/list',[],['Bearer' => $token]);


$http = HttpRequest::Instance('https://instances.social');

$instances = $http::Get(
    "api/1.0/instances/list",
    [
        'sort_by'    => 'users',
        'sort_order' => 'desc',
        'min_users'  => 100,
        'count'      => 20
    ],
    ['Authorization' => 'Bearer '.$token]
);

// p($instances);

db::conectar();
foreach($instances['instances'] as $instance) {
    $exists = db::one("select `instance` from `instances` where `instance` = '".$instance['name']."' limit 1");
    if(empty($exists['instance'])) {
        db::query("insert into `instances` (`instance`,`data`,`statuses`) values ('".$instance['name']."','".addslashes(json_encode($instance))."','".$instance['statuses']."');");
        // db::query("insert into `instances` (`instance`,`data`,`statuses`) values ('".$instance['name']."','".addslashes(json_encode($instance))."','".$instance['statuses']."');");
        p("nueva ".$instance['name']);
    } else {
        p("existe ".$instance['name']);
    }
}
?>