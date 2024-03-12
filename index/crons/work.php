<?php
exit;

include('/var/www/mastodonshare.com/index/m/config.php');

db::conectar();

$num      = false;
foreach($argv as $arg) {
    $arg = explode('=',$arg);
    if($arg[0] == 'num') {
        $num = $arg[1];
    }
}

sleep(rand(0,15));
// $crawler = db::fetch("select * from `crawl` where `indexed` = 0 order by `date` asc limit 10");

$start = time();

if($num == false) {
    // $crawler = db::fetch("select * from `crawl` where `indexed` < 3 limit 1000");
} else {
    $crawler = db::fetch("select * from `crawl` where `indexed` < 3 and `index` like '%".$num."' limit 10");
}
// $crawler = db::fetch("select * from `crawl` where `indexed` = 0 and `data` like '%You are welcome to follow me here%' order by rand() limit 10");
// $crawler = db::fetch("select * from `crawl` where `indexed` = 0 and `data` like '%@bipasha%' order by rand() limit 10");

foreach($crawler as $crawled) {

    // p($crawled);

    $instance_name = $crawled['instance']; //'mastodon.social';
    $data = json_decode(($crawled['data']),true);

    // p($data);
    // exit;

    $objects = [];

    // p(html_entity_decode($data['content']));

    $content = trim(($data['content']));

    preg_match_all('/href\=\"(.*?)\"/im', $content, $links);
    $temp_links = $links[1];

    foreach($temp_links as $k => $link) {
        if(stripos($link,$instance_name.'/tags/') !== false) {
            $temp_links[] = '#'.end(explode('/',$link));
            unset($temp_links[$k]);
        } elseif(stripos($link,'/@') !== false && substr_count($link,'/') == 3) {
            $temp_links[] = '@'.end(explode('@',$link));
            unset($temp_links[$k]);
        }
    }

    // $content = trim(strip_tags(str_ireplace(["\n","\r","<br>","<br />","<br/>"],' ',$data['content'])));

    // p($data);
    $popularity = intval($data['reblogs_count'])+intval($data['favourites_count']);
    $media      = [];
    $purl       = [];
    $content    = trim(strip_tags($data['content'],"<p><br>"));
    $date       = date('YmdHis',strtotime($data['created_at']));

    foreach($data['media_attachments'] as $k => $m) {
        $purl[] = $m['preview_url'];
        $temp_links[] = $m['type'].':'.$m['preview_url'];
    }

    $media_root = '';
    if(count($purl) > 1) {
        for($j = 0;$j < strlen($purl[0]);$j++) {
            $is = $purl[0][$j];
            foreach($purl as $pk => $pv) {
                if($pk > 0) {
                    if($is != $purl[$pk][$j]) {
                        // p([$is,$purl[$pk][$j]]);
                        break 2;
                    }
                }
            }
            $media_root .= $is;
        }
        $media['root'] = $media_root;
    }

    if(!empty($media_root)) {
        // foreach($purl as $pk => $pv) {
        foreach($data['media_attachments'] as $k => $m) {
            $media[] = [$m['type'],str_ireplace($media_root,'',$m['preview_url'])];
        }
    }

    // p($purl);
    // p($media);
    // p($media_root);
    // p($temp_links);

    // $temp_links = $media;

    // p([])


    $exists = db::one("select `index` from `index` where `index` = '".$crawled['index']."' limit 1");
    if(!empty($exists['index'])) {
        p("deleting...");
        db::query("delete from `index` where `index` = '".$crawled['index']."'");
        // db::query("delete from `objects` where `index` = '".$crawled['index']."'");
    }
    db::query("insert into `index` (`index`,`user`,`instance`,`date`,`content`,`popularity`,`media`) values
        ('".$crawled['index']."','".$data['account']['acct']."','".$instance_name."','".$date."','".addslashes($content)."','".$popularity."','".addslashes(json_encode($media))."');
    ");

    // preg_match_all('/^(?!\-)(?:(?:[a-zA-Z\d][a-zA-Z\d\-]{0,61})?[a-zA-Z\d]\.){1,126}(?!\d+)[a-zA-Z\d]{1,63}$/m',$content,$links);

    
    // preg_match_all('~(?<=[\s])@[^\s.,!?]+~', $content, $mentions);
    // preg_match_all('~(?<=[\s])#[^\s.,!?]+~', $content, $hashtags);

    // p(['content',$content,$data['content']]);

    if(is_array($temp_links) && count($temp_links)> 0) {
        $temp_links = array_unique($temp_links);
        // p(['links',$temp_links]);
        foreach($temp_links as $object) {
            if(strlen($object) > 2) {

                $object = trim($object,"\' ");
                // if($date == '20180603113751') {
                //     p("insert into `objects` (`index`,`object`,`date`) values ('".$crawled['index']."','".$object."','".$date."')");
                // }
                db::query("insert into `objects` (`index`,`object`,`date`) values ('".$crawled['index']."','".$object."','".$date."')");
            }
        }
    }

    db::query("update `crawl`
    set
        `indexed` = 3
    where
        `index` = '".$crawled['index']."'
    limit 1");

    p([$crawled['index'],$date,count($temp_links),count($media)]);

}

p("Seconds ".time()-$start);

?>