<?php

if (!function_exists('md5_file')) {
	function md5_file($filename) {
		return md5(file_get_contents($filename));
	}
}

?>