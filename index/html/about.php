<?php

include('inc_header.php');


echo '<div class="card mb-5 border border-blue bg-dark-blue text-light">';

echo '<div class="card-header fw-bold">Help</div>';

echo '<div class="card-body">';
echo '<p class="card-text">Please email Alex at <a class="fw-bold text-orange" href="mailto:alex@barredo.es">alex@barredo.es</a> with any questions or errors you may encounter.</p>';
echo '<p class="card-text">You can find me at <a class="fw-bold text-orange" href="https://mastodon.social/@barredo">mastodon.social/@barredo</a></p>';
echo '</div></div>';


echo '<div class="card mb-5 border border-blue bg-dark-blue text-light">';

echo '<div class="card-header fw-bold">Known bugs</div>';
echo '<div class="card-body">';
echo '<p class="card-text">Many :-) Please report them anyway to the email or Mastodon addresses above.</p>';
echo '<ul class="card-text">';
echo '<li>Posting does not count characters according to the instance.</li>';
echo '<li>Can not load previous statuses.</li>';
echo '<li>Getting to the top a column should mark it as "read"</li>';
echo '<li>Modal should take whole screen in mobile.</li>';
echo '<li>Click on a hashtag should open a temporal column with that hashtag.</li>';
echo '<li>Multiple accounts not working yet.</li>';
echo '<li>Each status/toot should have a sharing option.</li>';
echo '</ul>';
echo '</div>';

echo '<div class="card-header fw-bold">Changelog</div>';
echo '<div class="card-body">';
echo '<p class="card-text">I was developing MastoDeck privately, but I will share some changes as I push them in this section, and on <a class="fw-bold text-blue" href="https://mastodon.social/@mastodeck">mastodon.social/@mastodeck</a>.</p>';

echo '<p class="card-text">2023-01-02:</p>';
echo '<ul class="card-text">';
echo '<li>Better modal behaviour.</li>';
echo '<li>Escape key closes modals.</li>';
echo '</ul>';

echo '</div>';

echo '</div>';


include('inc_delete.php');

include('inc_footer.php');