<?php

function smarty_modifier_hide_email($string) {
	return str_replace(array('@','.'),array(' at ',' dot '),$string);
}

?>