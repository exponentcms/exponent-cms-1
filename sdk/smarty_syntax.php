<?php

include_once('../pathos.php');

//include_once(BASE.'external/Smarty/libs/Smarty.class.php');

$base = BASE;

$files = explode("\0",`find $base -name '*.tpl' -print0`);

$tmp = tempnam('/dev/null','syntax').'dir';
mkdir($tmp);

$tpl = new Smarty();
$tpl->php_handling = SMARTY_PHP_REMOVE;
$tpl->plugins_dir[] = BASE.'plugins';
$tpl->compile_dir = $tmp;

$files = array_splice($files,0,-1);

foreach ($files as $f) {
	echo $f.'...';
	ob_start();
	$tpl->template_dir = dirname($f).'/';
	$tpl->fetch(basename($f),null,null,false,false);
	$output = trim(ob_get_contents());
	ob_end_clean();
	if ($output == '') {
		echo '<span style="color: green">passed</span><br />';
	}
}

echo '<br /><br />Processed a total of '.count($files).' views<br />';

?>
