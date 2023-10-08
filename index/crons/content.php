<?php
include('/var/www/mastodonshare.com/index/m/config.php');

db::conectar();

// select `instance`,`id`,`id`%3 from `instances`

// $instances = db::fetch("select `instance` from `instances` where (`data` != 'no' or `data` is null) order by rand() desc limit 8");
$instances = db::fetch("select `instance` from `instances` where (`data` != 'no' && `data` != 'top' && `data` != 'chill' && `data` != 'chill3' && `data` != 'top3') or `data` is null or `data` = '' order by rand() desc limit 9");
// $instances = db::fetch("select `instance` from `instances` where (`data` != 'no' or `data` is null) order by created_at desc");
// $instances = db::fetch("select `instance` from `instances` where `data` = '' order by rand() desc limit 9");


// print_r($instances);
// exit;

$instance = '';
$num      = 0;
foreach($argv as $arg) {
    $arg = explode('=',$arg);
    if($arg[0] == 'instance') {
        $_GET['instance'] = $arg[1];
    }
}

if(isset($_GET['instance'])) {
    // ini_set('user_agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36');
    $instances = db::fetch("select `instance` from `instances` where `instance` = '".$_GET['instance']."' limit 1");
}
// print_r($instances);

$start = time();

for($rounds = 0; $rounds < 5; $rounds++) {
    foreach($instances as $instance) {

        $instance_name = (is_array($instance) && isset($instance['instance'])) ? $instance['instance'] : $instance;
        
        $min = 0;
        $max = 0;
        $index = null;

        // p($instance_name);
        // continue;

        $instance = db::one("select * from `instances` where `instance` = '".$instance_name."' limit 1");

        $statuses_get = file_get_contents('https://'.$instance['instance'].'/api/v1/timelines/public?local=true&limit=40&min_id='.$instance['min']);
        $statuses     = json_decode($statuses_get,true);

        p([$instance_name,$instance['min']]);

        // if(!is_array($statuses)
        // OR count($statuses) == 0) {
        //     p("No statuses for ".$instance_name);
        //     continue;
        // }

        if(!is_array($statuses)) {
            p($statuses_url);
            p($statuses_get);
            p("Chill statuses for ".$instance_name);
            db::query("update `instances`
            set
                `data` = 'chill'
            where
                `id` = '".$instance['id']."'
            limit 1");
            exit;
        }

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

        if(count($statuses) < 40) {
            p("top statuses for ".$instance_name);
            db::query("update `instances`
            set
                `data` = 'top',
                `last_status` = '".$status['created_at']."'
            where
                `id` = '".$instance['id']."'
            limit 1");
        }

        p([$instance_name,$status['created_at'],count($statuses),$min,$max,'----------------------']);

    }
}

p('time '.(time()-$start).' seconds');

if(rand(1,60) == 15) {
    
    p('limpiado los top');
    db::query("update `instances`
    set
        `data` = ''
    where
        `data` = 'top'");
}

if(rand(1,15) == 15) {
    
    p('limpiado los top');
    db::query("update `instances`
    set
        `data` = ''
    where
        `data` = 'chill'");
}
?>