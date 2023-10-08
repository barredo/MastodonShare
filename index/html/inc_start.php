<?php

echo '<div class="card border bg-darkblue border-light-blue text-white mb-3">';
echo '<div class="card-body fs-6 py-2"><i class="fa fa-flask text-blue me-2" aria-hidden="true"></i>';
echo '<span class="text-blue">MastoDeck</span> is in closed beta for the moment. Sign ups wont work if you are not in the list. If you want to help, contact me at <a class="fw-bold text-orange" href="https://mastodon.social/@barredo">mastodon.social/@barredo</a> or <a class="fw-bold text-orange" href="mailto:alex@barredo.es">alex@barredo.es</a>.';
echo '</div></div>';

echo '<div class="card mb-5 border border-blue bg-dark-blue text-light">
  <div class="card-header bg-trans">Sing up to <img src="'.URL.'s/img/mastodeck.png" alt="MastoDeck" style="width:1.2em;" class="me-1"><span class="text-blue fw-bold">MastoDeck</span></div>
  <div class="card-body">';

// echo '<p class="card-text">Click on the link below to connect with your Mastodon account</p>';

echo '
<form method="get" action="'.URL.'">
  <input type="hidden" name="a" value="request"/>
  <div class="mb-3">
  <input type="text" class="form-control border border-dark" id="instance" name="instance" aria-describedby="instance" placeholder="mastodon instance (mastodon.social, mas.to, pawoo.net?)">
  </div>
  <div class="text-center">
  <button type="submit" class="btn btn-blue"><i class="fa fa-user-circle me-2" aria-hidden="true"></i> Sign in with Mastodon</button>
  </div>
</form>';

    // <div class="form-text">Which Mastodon Instance do you use? (mastodon.social? mas.to?)</div>
// echo '<a class="btn btn-primary" href="'.URL.'?s=request"><i class="fa fa-user-circle me-2" aria-hidden="true"></i> Sign in with Mastodon</a>';

echo '
  </div>
</div>';

?>