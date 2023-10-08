<?php
include('inc_header.php');

db::conectar();

$lists       = column_types();
$user_exists = db::one("select * from `mastodeck_users`

where `user`     = '".$_SESSION['MASTODON_USER']."'
  and `instance` = '".$_SESSION['instance']."'

limit 1");

if(empty($user_exists['id'])) {
	exit;
}

$order = null;
if(isset($_GET['order'])) {
	$order = clean_09($_GET['order']);
}


$columns     = db::fetch("

select * from 
			`mastodeck_columns`

where `user` = '".$user_exists['user']."'
	and `instance` = '".$user_exists['instance']."'

order by 
			`order` asc

limit 100");

$columns_total = count($columns);

if(is_blank($order)) {


	echo '<div class="card mb-5 border border-light-blue bg-dark-blue text-light">';
	echo '<div class="card-header fw-bold border-bottom border-light-blue">Manage your columns</div>';

	foreach($columns as $column_key => $column) {
		$column_data = !empty($column['data']) ? json_decode(stripslashes($column['data']),true) : [];
		$column['data'] = $column_data;

		$link = column_editable($column['type']) ? URL.'manage?order='.$column['order'] : '#';
		

		echo '<div class="card-body d-flex flex-row justify-content-between align-items-center border-bottom border-light-blue">';
		echo '<span class="text-light fw-bold me-3 d-flex flex-column align-items-center">';
			if($column_key > 0) {
				echo '<a href="'.URL.'columnmove?from='.$column_key.'&to='.($column_key-1).'" class="text-muted fs-7 fa fa-arrow-up"></a>';
			}
			echo '#'.($column['order']+1);
			if(($column_key+1) < $columns_total) {
				echo '<a href="'.URL.'columnmove?from='.$column_key.'&to='.($column_key+1).'" class="text-muted fs-6 fa fa-arrow-down"></a>';
			}
		echo '</span>';
		echo '<a href="'.$link.'" class="flex-grow-1 text-light">';
		echo '<i class="me-2 fa '.column_icon($column['type']).'"></i>';
		echo column_name($column['type']);
		echo '<span class="d-block text-muted fs-7">'.column_data($column).'</span>';
		echo '<span class="d-block text-muted fs-7">'.mastodon_name($column['user'],$column['instance']).'</span>';
		echo '</a>';
		if(column_editable($column['type'])) {
			echo '<a href="'.$link.'" class="btn btn-blue btn-sm fs-7"><i class="fa fa-edit" title="Edit"></i> Edit</a>';
		}
		if($columns_total > 1) {
			echo '<a href="'.URL.'columndelete?order='.$column_key.'" class="btn btn-danger btn-sm fs-7 ms-2"><i class="fa fa-trash" title="Delete"></i> Delete</a>';
		}
		echo '</div>';

	}

	echo '</div>';
	echo '</div>';


	echo '<div class="card mb-5 border border-light-blue bg-dark-blue text-light">';
	echo '<div class="card-header fw-bold">Add another column</div>';
	foreach($lists as $list) {
		echo '<div class="card-body d-flex flex-row justify-content-between align-items-center border-top border-light-blue">';
		echo '<a class="text-light">';
			echo '<i class="fa '.column_icon($list).' me-2" style="width:1em;"/></i>';
			echo '<span class="">'.column_name($list).'</span>';
		echo '</a>';
		echo '<a href="'.URL.'columnadd?type='.$list.'" class="btn btn-blue btn-sm fs-7"><i class="fa fa-plus-circle" title="Add"></i> Add</a>';
		echo '</div>';
	}
	echo '</div>';
	echo '</div>';
	
} else {

	$column     = db::one("

	select * from 
				`mastodeck_columns`

	where `user`   = '".$user_exists['user']."'
	and `instance` = '".$user_exists['instance']."'
	and `order`    = '".$order."'

	limit 1");
	
	if(empty($column['id'])) {
		r::to(URL.'manage');
		exit;
	}

	// p($column);
	// p(column_editable($column['type']));
	// p($column);
	// exit;

	if(!column_editable($column['type'])) {
		r::to(URL.'manage');
		exit;
	}

	$variables = column_edit_variables($column['type']);
	if(!is_array($variables)) {
		r::to(URL.'manage');
		exit;
	}

	$column_data = !empty($column['data']) ? json_decode(stripslashes($column['data']),true) : [];

	// p($variables);
	// p($column_data);

	if($variables['type'] == 'text') {
		$value = '';
		if(empty($column_data[$variables['value']])) {
			r::to(URL.'manage');
			exit;
		}
		$value = $column_data[$variables['value']];
	} elseif($variables['type'] == 'user') {

		$recoveredArray = [
			"client_id" 		=> $_SESSION['client_id'],
			"client_secret" 	=> $_SESSION['client_secret'],
			"bearer" 			=> $_SESSION['MASTODON_TOKEN']
		];

		if(empty($column_data[$variables['value']])) {
			r::to(URL.'manage');
			exit;
		}
		$value = $column_data[$variables['value']];

		// p($value);
	} elseif($variables['type'] == 'select') {
		if(empty($variables['method'])) {
			r::to(URL.'manage');
			exit;
		}

		$recoveredArray = [
			"client_id" 		=> $_SESSION['client_id'],
			"client_secret" 	=> $_SESSION['client_secret'],
			"bearer" 			=> $_SESSION['MASTODON_TOKEN']
		];

		if(empty($column_data[$variables['key']])) {
			r::to(URL.'manage');
			exit;
		}
		$value = $column_data[$variables['key']];

		$t = new \theCodingCompany\Mastodon();
		$t->setMastodonDomain($_SESSION['instance']);
		$t->setCredentials($recoveredArray);
		
		$results = [];
		if($variables['method'] == 'lists') {
			$results = $t->getLists();
		}

		if(!is_array($results) OR count($results) == 0) {
			r::to(URL.'manage');
			exit;
		} 

		// p($results);
		// p($value);
	}
	
	echo '<form action="'.URL.'columnedit" method="POST" class="card mb-5 border border-light-blue bg-dark-blue text-light">';
	echo '<div class="card-header fw-bold">Edit column '.column_name($column['type']).'</div>';
	echo '<input type="hidden" name="order" value="'.$order.'"/>';
	echo '<input type="hidden" name="type"  value="'.$column['type'].'"/>';

	if($variables['type'] == 'text') {
		echo '<div class="card-body border-top border-not-so-light-blue">';
		echo '<div class="mb-3">';
		echo '<label for="'.$variables['label'].'" class="form-label">'.ucwords($variables['label']).'</label>';
		echo '<input type="text" value="'.$value.'" name="'.$variables['label'].'" id="'.$variables['label'].'" class="form-control bg-darker-blue border-light-blue text-light"/>';
		echo '</div>';
		echo '</div>';
	} elseif($variables['type'] == 'user') {
		echo '<div class="card-body border-top border-not-so-light-blue">';
		echo '<div class="mb-3">';
		echo '<label for="'.$variables['label'].'" class="form-label">'.ucwords($variables['label']).'</label>';
		echo '<input type="text" value="'.$value.'" name="'.$variables['label'].'" id="'.$variables['label'].'" class="form-control bg-darker-blue border-light-blue text-light"/>';
		echo '</div>';
		echo '</div>';
	} elseif($variables['type'] == 'select') {
		echo '<div class="card-body border-top border-not-so-light-blue">';
		echo '<div class="mb-3">';
		echo '<label for="'.$variables['label'].'" class="form-label">'.ucwords($variables['label']).'</label>';
		echo '<select class="form-control bg-darker-blue border-light-blue text-light" id="'.$variables['label'].'" name="'.$variables['label'].'">';
		foreach($results as $k => $v) {
			echo '<option ';
			echo 'class="bg-darker-blue border-light-blue text-light" ';
			echo 'value="'.base64_encode(serialize([$variables['key'] => $v[$variables['key']],$variables['value'] => $v[$variables['value']]])).'" '.($v[$variables['key']]==$value?' selected':'').'>';
			echo $v[$variables['value']].'</option>';
		}
		echo '</select>';
		echo '</div>';
		echo '</div>';
	}

	echo '<div class="card-footer border-top border-not-so-light-blue">';
	echo '<button type="submit" class="btn btn-blue"><i class="fa fa-cloud me-2"/></i>Save</button>';
	echo '</div>';

	echo '</div>';
	echo '</form>';

	
	echo '<div style="margin-top:10em;" class="card mb-5 border border-light-blue bg-dark-blue text-light">';
	echo '<div class="card-header fw-bold">Delete this column</div>';
	echo '<div class="card-footer">';
	if($columns_total > 1) {
		echo '<a href="'.URL.'columndelete?order='.$column['order'].'" class="btn btn-danger btn-sm2"><i class="fa fa-trash" title="Delete"></i> Delete</a>';
	}
	echo '</div>';

	echo '</div>';

}

include('inc_footer.php');