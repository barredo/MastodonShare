<?php
$query      = clean_general($_GET['q']);

$max_results = 10;
$page       = intval(isset($_GET['page'])?$_GET['page']:0);

$sorts      = list_sort();
$sort       = detect_sort(isset($_GET['sort'])?$_GET['sort']:false);
$objects    = detect_objects($query);
$operators  = detect_operators($query);

$query_work = remove_operators($query,$operators);
$query_work = remove_stopwords($query_work); 

include(HTML.'inc_header.php');

echo '<div id="results_header">';
echo '<form method="get" action="'.URL.'" id="nav">';
// echo '<a href="'.URL.'" class="d-none d-sm-inline">'.APP_NAME.'</a>';
// echo '<a href="'.URL.'" class="d-inline d-sm-none">'.substr(APP_NAME,0,1).'</a>';
echo '<a href="'.URL.'"><img alt="'.APP_NAME.'" src="'.URL.'s/img/160w.png"/></a>';
echo '<div class="ms-3 input-group">';
// echo '<input type="hidden" name="s" value="results"/>';
echo '<input type="hidden" name="sort" value="'.$sort.'">';
echo '<input type="text" name="q" class="form-control" placeholder="Search" value="'.$query.'">';
echo '<button class="btn btn-own" type="submit"><i class="fa fa-search"></i><span class="ms-2 d-none d-sm-inline">Search</span></button>';
echo '</div>';
echo '</form>';
echo '</div>';
echo '<div id="results_menu">';
  echo '<div class="fr">';
  echo '<span class="fs-7">Sort by';
  foreach($sorts as $k => $v) {
    echo '<a class="mx-1'.($k==$sort?' fw-bold':'').'" href="'.URL.'?q='.urlencode($query).'&sort='.$k.'">'.$v.'</a>';
  }
  echo '</span>';
  echo '</div>';
echo '</div>';

db::conectar();

/*
select `objects`.`index`,`index`.`content`,`index`.`date` from `objects` inner join `index` on `objects`.`index` = `index`.`index` where `object` = '#mastodon' 
*/
$sql_where = [];

$sql_query = "select * from";
$sql_colum = "`index`";
if(!empty($query_work)) {
  $sql_where['match'] = "match(`content`) against('".$query_work."')";
}
$sql_limit = "limit ".($max_results*$page).",".$max_results;

if(isset($operators['instance'])) {
  $sql_where['instance'] = " `instance` = '".$operators['instance']."'";
}

if(isset($operators['date'])) {
  $sql_where['date'] = " `date` like '".clean_09($operators['date'])."%'";
} elseif(isset($operators['with']) && $operators['with'] = 'media') {
  $sql_where['with'] = " `media` != '[]'";
} elseif(isset($operators['date_min'])) {
  $sql_where['date_min'] = " `date` >= '".str_pad(clean_09($operators['date_min']),14,0)."%'";
} elseif(isset($operators['date_max'])) {
  $operators['date_max'] = clean_09($operators['date_max'])+1;
  $sql_where['date_maxate'] = " `date` < '".str_pad(clean_09($operators['date_max']),14,0)."%'";
}

if($sort == 'relevance') {
  $sql_order = "";
} elseif($sort == 'old') {
  $sql_order = "order by date asc";
} elseif($sort == 'recent') {
  $sql_order = "order by date desc";
}

$sql_where_final = '';
if(count($sql_where) > 0) {
  $sql_where_final = " where ".implode(' and ',$sql_where);
}
$sql = $sql_query." ".$sql_colum." ".$sql_where_final." ".$sql_order." ".$sql_limit; //limit 10";

p($sql);

// $results = db::fetch("select * from `index` where `content` like '%".$_GET['q']."%' limit 10");
$results = db::fetch($sql);

echo '<div id="results" class="px-3">';

p($query);
p($query_work);
p($objects);
p($operators);

foreach($results as $result) {
  $content = html_entity_decode($result['content']);
  // $content = html_entity_decode($result['content']);
  // $content = strip_tags(nl2br($content),"<br><p>");
  $content = (trim(str_ireplace(['<p>','</p>'],["\n\n",''],$content)));
  $content = fixes_content($content);
  $content = highlight($content,$query_work);
  $content = highlight_twitter($content);
  // $content = highlight_links($content);
  // $content = highlight_hashtags($content);
  $content = nl2br($content);

  echo '<div class="card">';
  echo '<div class="card-body"><p class="card-text">'.$content.'</p></div>';
  if(strlen($result['media']) > 4) {
    $media = [];
    $result['media'] = json_decode(stripslashes($result['media']),true);
    foreach($result['media'] as $k => $v) {
      if(is_array($v) && isset($v[1])) {
        $media[] = $result['media']['root'].$v[1];
      }
    }
    // p($media);
    // p($result['media']);
    echo '<div class="card-body row py-0">';
    foreach($media as $m) {
      echo '<div class="col-6 col-md-3 text-center pb-3">';
      echo '<a href="'.$m.'" target="_new" class="d-block border border-dark rounded" style="height:9rem;background:url('.$m.');background-size:cover;backgrouhnd-repeat:no-repeat;background-position:center center;">';
      // echo '<img class="rounded" style="height:100%; width: auto;" src="'.$m.'">';
      echo '</a>';
      echo '</div>';
    }
    echo '</div>';
  }
  echo '<div class="card-footer fs-7 d-flex justify-content-between">';
    echo mastodon_link($result['user'],$result['instance']);
    // echo '<a href="https://'.$result['index'].'" target="_new">'.$result['media'].'</a>';
    // echo '<a href="https://'.$result['index'].'" target="_new">'.$result['popularity'].'</a>';
    echo '<a href="https://'.$result['index'].'" target="_new">'.ago2str(strtotime($result['date'])).'</a>';
  echo '</div>';
  echo '</div>';
}

echo '</div>';

if(count($results) == $max_results OR $page > 0) {
  echo '<div id="results_menu" class="mt-5 footer">';
    echo '<div class="fr fs-7">';
      if($page > 0) {
        echo '<a class="mx-1" href="'.URL.'?q='.urlencode($query).'&sort='.$sort.'&page='.($page-1).'">Previous page</a>';
      }
      if(count($results) == $max_results) {
        echo '<a class="mx-1" href="'.URL.'?q='.urlencode($query).'&sort='.$sort.'&page='.($page+1).'">Next page</a>';
      }
    echo '</div>';
  echo '</div>';
}

include(HTML.'inc_footer.php');