<?php

include_once('../pathos.php');

$base = BASE;

$files = explode("\0",`find $base -name '*.tpl' -print0`);

$tmp = tempnam('/dev/null','syntax').'dir';
mkdir($tmp);

$tpl = new Smarty();
$tpl->php_handling = SMARTY_PHP_REMOVE;
$tpl->plugins_dir[] = BASE.'plugins';
$tpl->compile_dir = $tmp;

$files = array_splice($files,0,-1);

$succeed = 0;
$fail = 0;

ob_start();

foreach ($files as $f) {
	echo $f.'...';
	ob_start();
	$tpl->template_dir = dirname($f).'/';
	$tpl->fetch(basename($f),null,null,false,false);
	$output = trim(ob_get_contents());
	ob_end_clean();
	if ($output == '') {
		echo '<span style="color: green">passed</span><br />';
		$succeed++;
	} else {
		echo '<span style="color: red">failes</span><br />';
		$fail++;
	}
}

$str = ob_get_contents();
ob_end_clean();

echo 'Processed a total of '.count($files).' views<br />';
echo $succeed . ' Succeeded<br />' . $fail . ' Failed<br />';
echo '<br /><hr />';

echo $str;

?>
