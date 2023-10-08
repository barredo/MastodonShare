<?php
// echo '<div id="cobra"></div>';

echo '<div class="" id="about">';

echo '<h1 class="text-white text-center mb-5 fs-2 fw-bolder ls-n1">Welcome to <img src="'.URL.'s/img/mastodeck.png" alt="MastoDeck" style="width:1.5em;" class="me-1"><span class="text-blue">MastoDeck</span></h1>';

echo '<div class="mb-5 row" style="width: 150%;margin-left: -25%;">';
echo '<div class="col-12 col-md-6 mb-3">';
echo '<a href="'.URL.'s/img/screenshots/dark.png"><img class="w-100" src="'.URL.'s/img/screenshots/dark.png" title="Dark version of Mastodeck"/></a>';
echo '</div>';
echo '<div class="col-12 col-md-6 mb-3">';
echo '<a href="'.URL.'s/img/screenshots/light.png"><img class="w-100" src="'.URL.'s/img/screenshots/light.png" title="Light version of Mastodeck"/></a>';
echo '</div>';
echo '</div>';

echo '<div class="row text-white">';

	echo '<div class="col-12 col-md-6 mb-3">';
		echo '<h2 class="fw-bolder ls-n1"><i class="fa text-blue fa-heartbeat me-2" aria-hidden="true"></i>Stay Alert</h2>';
		echo '<p>The most advanced UI with plenty of columns that you can customize to never miss anything.</p>';
	echo '</div>';

	echo '<div class="col-12 col-md-6 mb-3">';
		echo '<h2 class="fw-bolder ls-n1"><i class="fa text-blue fa-users me-2" aria-hidden="true"></i>Multiple accounts</h2>';
		echo '<p>Manage several Mastodon accounts over differente instances at the same time.</p>';
	echo '</div>';

	echo '<div class="col-12 col-md-6 mb-3">';
		echo '<h2 class="fw-bolder ls-n1"><i class="fa text-blue fa-bolt me-2" aria-hidden="true"></i>Real time</h2>';
		echo '<p>Get updates by the second when new messages are posted.</p>';
	echo '</div>';

	echo '<div class="col-12 col-md-6 mb-3">';
		echo '<h2 class="fw-bolder ls-n1"><i class="fa text-blue fa-eye-slash me-2" aria-hidden="true"></i>Privacy</h2>';
		echo '<p>No tracking, no logging, and not storing any of your personal data. Everything stays at your Mastodon severs.</p>';
	echo '</div>';

	echo '<div class="col-12 col-md-6 mb-3">';
		echo '<h2 class="fw-bolder ls-n1"><i class="fa text-blue fa-cog me-2" aria-hidden="true"></i>Customizable</h2>';
		echo '<p>Set the preferences to keep MastoDeck the way you like it.</p>';
	echo '</div>';

	// echo '<div class="col-6 text-center mb-5">';
	// 	echo '<i class="fa text-blue fa-check-circle fs-1" aria-hidden="true"></i>';
	// 	echo '<h2>Automated</h2>';
	// 	echo '<p>Add your feeds and you\'re done. MastoFeed will check it periodically to send your content.</p>';
	// echo '</div>';

echo '</div>';

// 	echo '<div class="card mb-5 border border-orange shadow">
//   <div class="card-header">Sign up with your Mastodon account!</div>
//   <div class="card-body">';

// // echo '<p class="card-text">Click on the link below to connect with your Mastodon account</p>';

// echo '
// <form method="get" action="'.URL.'">
//   <input type="hidden" name="s" value="request"/>
//   <div class="mb-3">
//     <label for="instance" class="form-label">Your Mastodon Instance is:</label>
//     <input type="text" class="form-control" id="instance" name="instance" aria-describedby="instance">
//     <div id="mastodon" class="form-text">Which Mastodon Instance do you use? (mastodon.social? mas.to?)</div>
//   </div>
//   <button type="submit" class="btn btn-orange"><i class="fa fa-user-circle me-2" aria-hidden="true"></i> Sign in with Mastodon</button>
// </form>';

// // echo '<a class="btn btn-primary" href="'.URL.'?s=request"><i class="fa fa-user-circle me-2" aria-hidden="true"></i> Sign in with Mastodon</a>';

// echo '
//   </div>
// </div>';

?>