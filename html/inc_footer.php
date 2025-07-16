<?php
/**
 * Footer markup shared across pages.
 */

// echo '</div>'; // container
// echo '</div>'; // main
// echo '</div>'; // global

// echo '<script src="https://cdn.counter.dev/script.js" data-id="32a6a1d2-4a91-4531-ae63-1c1b8312d608" data-utcoffset="1"></script>';
// echo '<script src="'.URL.'s/mastodeck.js"></script>';

echo '<div id="f">';
if(!empty($_COOKIE['instance'])) {
    echo '<a href="'.URL.'?del_instance=true">Remove '.$_COOKIE['instance'].' as default</a>';
}
echo '<a href="'.URL.'">Home</a>';
echo '<a href="'.URL.'about">About</a>';
echo '</div>';

echo '</body>';
echo '</html>';