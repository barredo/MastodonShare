<?php
include(HTML.'inc_header.php');

echo '<form method="get" action="'.URL.'" id="landing" class="px-3">';
// echo '<div class="mx-3">';
echo '<a href="'.URL.'" class="mb-3"><img alt="'.APP_NAME.'" src="'.URL.'s/img/160w.png"/></a><div class="input-group">
<input type="hidden" name="s" value="results"/><input type="text" name="q" class="form-control" placeholder="Search">
  <button class="btn btn-own" type="submit"><i class="fa fa-search"></i><span class="ms-2 d-none d-sm-inline">Search</span></button>
</div>';

// echo '</div>';
echo '</form>';

include(HTML.'inc_footer.php');