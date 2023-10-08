<?php
if(!is_admin()) {
    exit;
}

include('inc_header.php');

// p($_SESSION);

p($_SESSION);
p($_COOKIE);
p(json_decode($_COOKIE['mastodeck']));

include('inc_footer.php');