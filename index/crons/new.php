<?php
exit;
include('/var/www/mastodonshare.com/index/m/config.php');

db::conectar();

/*

buscar en lo actual
 - hacer un repaso de 4-5 páginas de lo último hasta que se repitan más de X indices

*/

$instances = db::fetch("select `instance` from `instances` where `data` != 'no' order by rand() desc limit 2");
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
    $instances = db::fetch("select `instance` from `instances` where `instance` = '".$_GET['instance']."' limit 1");
}

$start = time();

foreach($instances as $instance) {  
    $hits  = 0;
    $nuevost = 0;
    $max   = false;
    $dates = [];

    for($rounds = 0; $rounds < 5; $rounds++) {

        $instance_name = (is_array($instance) && isset($instance['instance'])) ? $instance['instance'] : $instance;
        
        $nuevos = 0;
        $min   = 0;
        $index = null;

        $instance = db::one("select * from `instances` where `instance` = '".$instance_name."' limit 1");

        $statuses_get = file_get_contents('https://'.$instance['instance'].'/api/v1/timelines/public?limit=40&local=true'.($max===false?'':'&max_id='.$max));
        $statuses     = json_decode($statuses_get,true);

        p([$instance_name,$max]);

        if(!is_array($statuses)) {
            p("New no encontró statuses: ".$instance_name);
            exit;
        }
        
        foreach($statuses as $status) {
            $index    = str_ireplace('https://','',$status['uri']);
            $max      = $max === false ? $status['id'] : min($max,$status['id']);

            $exists = db::one("select `index` from `crawl` where `index` = '".$index."' limit 1");
            if(empty($exists['index'])) {
                $nuevos++;
                $nuevost++;
                $dates['ok'][$index] = $status['created_at']; 
                db::query("insert into `crawl` (`index`,`instance`,`data`) values ('".$index."','".$instance_name."','".addslashes(json_encode($status))."');");
            } else {
                $dates['ya'][$index] = $status['created_at']; 
                // p("Ya insertado: ".$index);
                $hits++;
            }
        }

        db::query("insert into `queue` (`instance`,`min`,`max`,`crawled_at`,`crawled`) values ('".$instance['instance']."','0','".$max."','".date('Y-m-d H:i:s')."','".$nuevos."');");
    
        if($hits >= 5) {
            // p("New ya tuvo cinco hits: ".$instance_name);
            p(["New ya tuvo cinco hits",$instance_name,'nuevos' => $nuevost,'ya' => $hits,'----------------------']);
            exit;
        }
        sleep(rand(1,5));
    }

    p([$instance_name,'nuevos' => $nuevost,'ya' => $hits,'----------------------']);
}


?>