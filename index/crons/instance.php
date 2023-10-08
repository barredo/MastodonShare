<?php

exit;
include('/var/www/mastodonshare.com/index/m/config.php');

$instance = '';
$num      = 0;
foreach($argv as $arg) {
    $arg = explode('=',$arg);
    if($arg[0] == 'instance') {
        $instance = $arg[1];
    }
    if($arg[0] == 'num') {
        $num = rand(5,10); //min(15,intval($arg[1]));
        $num = rand(min(intval($arg[1]),15),15);
    }
}

if(empty($instance)) {
    exit;
}
if($num < 1) {
    exit;
}

db::conectar();

// array_push($instances,"mastodon.social");

// $instances = db::fetch("select * from `instances` order by rand()");

// $instances = db::fetch("select `instance` from `instances` where `instance` = '".$instance."' limit 1");

$instances = [];

for($i = 0; $i < $num;$i++) {
    array_push($instances,$instance);
}

foreach($instances as $instance) {

    $instance_name = (is_array($instance) && isset($instance['instance'])) ? $instance['instance'] : $instance;
    
    $min = 0;
    $max = 0;
    $index = null;

    // p($instance_name);
    // continue;

    $instance = db::one("select * from `instances` where `instance` = '".$instance_name."' limit 1");

    // if($instance['data'] == 'top') {
    //     p("es top ".$instance_name);
    //     exit;
    // }

    $statuses_url = 'https://'.$instance['instance'].'/api/v1/timelines/public?local=true&limit=40&min_id='.$instance['min'];
    $statuses_get = file_get_contents($statuses_url);
    $statuses     = json_decode($statuses_get,true);

    p([$instance_name,$instance['min']]);

    if(!is_array($statuses)) {
        p($statuses_url);
        p($statuses_get);
        p("Chill statuses for ".$instance_name);
        db::query("update `instances`
        set
            `data` = 'chill3'
        where
            `id` = '".$instance['id']."'
        limit 1");
        exit;
    }
    // if(count($statuses) == 0) {
    //     p($statuses_url);
    //     p($statuses_get);
    //     p("Top y No statuses for ".$instance_name);
    //     db::query("update `instances`
    //     set
    //         `data` = 'top3'
    //     where
    //         `id` = '".$instance['id']."'
    //     limit 1");
    //     exit;
    // }

    // if(isset($_GET['instance'])) {
    //     p($statuses);
    //     exit;
    // }
    // shuffle($statuses);
    
    foreach($statuses as $status) {
        $index    = str_ireplace('https://','',$status['uri']);
        // $instance = $
        $min = min($min,$status['id']);
        $max = max($max,$status['id']);

        $exists = db::one("select `index` from `crawl` where `index` = '".$index."' limit 1");
        // p("select `index` from `crawl` where `index` = '".$index."' limit 1");
        // p($exists);
        if(empty($exists['index'])) {
            db::query("insert into `crawl` (`index`,`instance`,`data`) values ('".$index."','".$instance_name."','".addslashes(json_encode($status))."');");
            // p("insert into `crawl` (`index`,`instance`,`data`) values ('".$index."','".$instance_name."');");
        }
    
    }

    // p("update `instances`
    // set
    //     `min` = '".$max."',
    //     `crawled_at` = '".date('Y-m-d H:i:s')."'
    // where
    //     `id` = '".$instance['id']."'
    // limit 1");


    db::query("update `instances`
    set
        `min` = '".$max."',
        `crawled_at` = '".date('Y-m-d H:i:s')."',
        `data`= '',
        `last_status` = '".$status['created_at']."'
    where
        `id` = '".$instance['id']."'
    limit 1");

    db::query("insert into `queue` (`instance`,`min`,`max`,`crawled_at`,`crawled`) values ('".$instance['instance']."','".$instance['min']."','".$max."','".date('Y-m-d H:i:s')."','".count($statuses)."');");
    // p($statuses);

    p([$instance_name,count($statuses),$status['created_at'],$min,$max,'----------------------']);
    
    if(count($statuses) < 40) {
        p("Top y No statuses for ".$instance_name);
        db::query("update `instances`
        set
            `data` = 'top',
            `last_status` = '".$status['created_at']."'
        where
            `id` = '".$instance['id']."'
        limit 1");
        exit;
    }

    sleep(rand(1,2));

}


// foreach($instances['instances'] as $instance) {
//     $exists = db::one("select `instance` from `instances` where `instance` = '".$instance['name']."' limit 1");
//     if(empty($instance['instance'])) {
//         db::query("insert into `instances` (`instance`,`data`,`statuses`) values ('".$instance['name']."','".addslashes(json_encode($instance))."','".$instance['statuses']."');");
//         // db::query("insert into `instances` (`instance`,`data`,`statuses`) values ('".$instance['name']."','".addslashes(json_encode($instance))."','".$instance['statuses']."');");
//         p("nueva ".$instance['name']);
//     }
// }
?>