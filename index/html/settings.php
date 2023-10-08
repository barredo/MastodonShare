<?php

include('inc_header.php');


db::conectar();

$user_exists = db::one("select * from `mastodeck_users`

where `user`     = '".$_SESSION['MASTODON_USER']."'
  and `instance` = '".$_SESSION['instance']."'

limit 1");


$settings = json_decode(stripslashes($user_exists['settings']),true);

// p($settings);

/*
- diseño claro/oscuro/segun
- tamaño columnas
- tamaño letra
- ocultar media
- ocultar avatares

enlaces

*/

// p($_SESSION['settings']);

$available_settings = settings_list();

echo '<form action="'.URL.'settingssave" method="POST" class="card mb-5 border border-light-blue bg-dark-blue text-light">';
echo '<div class="card-header fw-bold">Settings</div>';

foreach($available_settings as $setting_name => $setting) {

	echo '<div class="card-body border-top border-not-so-light-blue">';
	echo '<div class="mb-3">';
		echo '<label for="'.$setting_name.'" class="form-label">'.$setting['title'].'</label>';
		echo '<select class="form-control bg-darker-blue border-light-blue text-light" id="'.$setting_name.'" name="'.$setting_name.'">';
		foreach($setting['options'] as $v) {
			echo '<option ';
			echo 'class="bg-darker-blue border-light-blue text-light" ';
			echo 'value="'.$v.'" '.(isset($settings[$setting_name]) && $settings[$setting_name] === $v?' selected':'').'>';
			echo ucwords($v).'</option>';
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


// - editar perfil (desde settings)
// - preferencias de cuenta (desde settings)
// - exportar/importar (desde settings)
// - delete mastodeck

$options = [
	'<i class="fa fa-sign-out me-2"></i><a title="Sign out" href="'.URL.'logout?check='.logout_hash().'">Sign out from Mastodeck</a>',
	'<i class="fa fa-user-plus me-2"></i><a href="#" target="_new">Follow @MastoDeck</a> on Mastodon',
	'<i class="fa fa-trash me-2"></i><a href="'.URL.'about">Delete your MastoDeck account</a>',
	'<i class="fa fa-cog me-2"></i><a href="https://'.$_SESSION['instance'].'/settings/profile">Edit your Mastodon profile</a> on your instance',
];


echo '<div class="card mb-5 border border-light-blue bg-dark-blue text-light">';
echo '<div class="card-header fw-bold">Other options</div>';

foreach($options as $option) {

	echo '<div class="card-body border-top border-not-so-light-blue"><p class="card-text">';
	echo $option;
	echo '</p></div>';
}


echo '</div>';
echo '</form>';

include('inc_footer.php');