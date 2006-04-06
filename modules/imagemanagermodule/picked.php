<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
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

?>
<html>
	<head>
		<script type="text/javascript">
		function go() {
			var f_url = window.opener.document.getElementById("f_url");
			f_url.value = "<?php echo $_GET['url']; ?>";
			window.opener.onPreview();
			window.close();
		}
		</script>
	</head>
	<body onLoad="go();">
	</body>
</html>