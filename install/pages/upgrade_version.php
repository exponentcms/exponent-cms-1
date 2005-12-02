<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
# Written and Designed by James Hunt
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##################################################

if (!defined('PATHOS')) exit('');

$versions = array(
	'0.95'=>'Nebula',
	'0.96'=>'Origin',
	'0.97'=>'Portent',
	'0.98'=>'Quip',
	'0.99'=>'Ramose',
	'1.0'=>'Salient',
);


?>

<form method="post" action="index.php">
<input type="hidden" name="page" value="upgrade" />

<div class="form_section_header"><?php echo $i18n['select_ver']; ?></div>
<div class="form_section">
	<div class="control">
		<select name="from_version" value="<?php echo PATHOS; ?>">
		<?php
			foreach ($versions as $version=>$release) {
				echo '<option value="'.$version.'">';
				echo $version . ' ' . $release;
				if ($version == PATHOS) {
					echo ' - '.$i18n['prev_rel'];
				}
				echo '</option>';
				
				if ($version == PATHOS) {
					break;
				}
			}
		?>
		</select>
		<div class="control_help">
			<?php echo $i18n['select_version']; ?>
			<br /><br />
			<div class="important_message">
				<?php echo $i18n['choose_correct']; ?>
			</div>
		</div>
		<input type="submit" value="<?php echo $i18n['upgrade']; ?>" class="text" />
	</div>
</div>
</form>