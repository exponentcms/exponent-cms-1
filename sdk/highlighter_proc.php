<?php

$code = highlight_string(stripslashes($_POST['code']),true);

echo $code;
echo '<hr /><hr /><xmp>';
echo $code;
echo '</xmp>';

?>
