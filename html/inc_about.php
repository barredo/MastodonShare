<?php

echo '<div class="card mt-5 fs-7">';
echo '<div class="card-header fw-bold">How to use MastodonShare</div>';
echo '<div class="card-body">';
echo '<p class="card-text">Sharing content in Mastodon can be difficult because people use many domains and apps, and you don\'t know where to link. <strong>MastodonShare allows your visitors to share content in their Mastodon instances</strong>, without the need to use browser extensions or apps.</p>';

echo '<p class="card-text">MastodonShare will ask for their Mastodon instance and redirect them right to share the content. It will keep a simple cookie with the instance, so they will not be asked again.</p>';
echo '</div>';

echo '<div class="card-header border-top border-translucent fw-bold">How it works</div>';
echo '<div class="card-body">';
echo '<p class="card-text">Simply create a link to:';
echo '<ul class="fs-7">';
echo '<li><span class="font-monospace bg-secondary text-light px-1">'.URL.'?text=your+text</span></li>';
echo '<li><span class="font-monospace bg-secondary text-light px-1">'.URL.'?url='.urlencode('yourURL.com').'</span></li>';
echo '<li><span class="font-monospace bg-secondary text-light px-1">'.URL.'?text=text&url='.urlencode('yourURL.com').'</span></li>';
echo '<li>or <span class="font-monospace bg-secondary text-light px-1">'.URL.'share</span> and will fetch the referring URL!</li>';
echo '</ul>';
echo '</p>';
echo '</div>';

echo '<div class="card-header border-top border-translucent fw-bold">Creating a button is very easy</div>';
echo '<div class="card-body">';
$button = '<a href="'.URL.'share" style="padding: 0.2rem 0.6rem 0.3rem;background: #2b90d9;color: white;border-radius: 0.3rem;text-decoration: none;font-weight: bold;">Share on Mastodon</a>';
echo $button;
echo '<textarea class="d-block w-100 mt-2">'.$button.'</textarea>';
echo '</p>';
echo '<p class="card-text">Example: <a target="_new" href="'.URL.'?text='.urlencode('https://mastodonshare.com is a wonderful tool').'">click this link to share how wonderful MastodonShare is!</a></p>';
echo '</div>';

echo '<div class="card-header border-top border-translucent fw-bold">Use it as bookmarklet</div>';
echo '<div class="card-body">';
echo '<p class="card-text">Drag this button to your web browser navigation bar.</a></p>';
$button = '<a href="javascript:(function(e,t){window.open(%22'.URL.'share?url=%22+encodeURIComponent(e.location.href)+%22&text=%22+encodeURIComponent(e.document.title)+%22&note=%22+encodeURIComponent(%22%22+e.getSelection()?e.getSelection():e.document.getSelection?e.document.getSelection():e.document.createRange().text)+%22&v=1.1%22,%22_blank%22);})(window)" style="padding: 0.2rem 0.6rem 0.3rem;background: #009e65;color: white;border-radius: 0.3rem;text-decoration: none;font-weight: bold;">Share on Mastodon</a>';
echo $button;
// echo '<textarea class="d-block w-100 mt-2">'.$button.'</textarea>';
echo '</p>';
echo '</div>';

echo '<div class="card-header border-top border-translucent fw-bold">More information</div>';
echo '<div class="card-body">';
echo '<p class="card-text">MastodonShare is 100% private, 100% free, and 100% secure. It does not store or track anything about the visitors, usernames, instances, links or content. It does not require Mastodon API access. </p>';
echo '<p class="card-text">If you have any questions, please ask me <a href="mailto:alex@barredo.es">alex@barredo.es</a> or <a target="_new" href="https://mastodon.social/@barredo">@barredo@mastodon.social</a>.</p>';
echo '</div></div>';