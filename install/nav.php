<?php

$pages = array(
	'welcome'=>'Welcome',
	'sanity'=>'Sanity Checks',
	'dbconfig'=>'Database Settings',
	'dbcheck'=>'Database Verification',
	'tmp_create_site'=>'Default Content',
	'final'=>'Finished'
);

echo '<table cellspacing="0" cellpadding="0" width="200">';
$done = 1;
foreach ($pages as $p=>$name) {
		if ($p == $_REQUEST['page']) $done = 0;
		echo '<tr>';
		echo '<td class="step'.($done ? ' completed':'').'">';
		echo (!$done ? $name : '<a href="?page='.$p.'" style="color: inherit; font: inherit; text-decoration: none; border-width: 0px;">'.$name.'</a>');
		echo '</td>';
		echo '</tr>';
}
echo '</table>';

?>