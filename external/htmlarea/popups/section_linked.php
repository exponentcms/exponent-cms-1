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

#  Thanks to Daniel Grabert for this patch. - 1/12/05

	if (isset($_REQUEST['section'])) {
		$section_id = $_REQUEST['section'];
		$section_name = isset($_REQUEST['section_name']) ? $_REQUEST['section_name'] : '';
	} else {
		// bad request - no section found

		// go back to referring page, if available
		$referer_url = $_SERVER['HTTP_REFERER'];
		if ( $referer_url ) {
			header("Location: $referer_url");
		} else {
			echo SITE_403_HTML;
			exit();
		}
	}
?>
<html>
	<body>
	<script type="text/javascript">
	var f_url = window.opener.document.getElementById("f_href");
	var f_extern = window.opener.document.getElementById("f_extern");
	var f_title = window.opener.document.getElementById("f_title");
	
	// set value for url form element in opener
	f_url.value = "?section=<?php echo $section_id; ?>";
	
	// uncheck external link box in parent window
	f_extern.checked = false;

	// set title
	f_title.value = "<?php echo 'Link to section ' . $section_name; ?>";
	
	window.close();
	</script>
	</body>
</html>