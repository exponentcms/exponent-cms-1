<?php
define('EXPONENT','1');
ob_start();
$deps = include("../deps.php");
ob_end_clean();

foreach ($deps as $dep) {
	if ($dep['type'] == 'CORE_EXT_SUBSYSTEM') echo $dep['name']."\r\n";
}

?>