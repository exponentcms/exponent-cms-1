<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# Exponent is distributed in the hope that it
# will be useful, but WITHOUT ANY WARRANTY;
# without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR
# PURPOSE.  See the GNU General Public License
# for more details.
#
# You should have received a copy of the GNU
# General Public License along with Exponent; if
# not, write to:
#
# Free Software Foundation, Inc.,
# 59 Temple Place,
# Suite 330,
# Boston, MA 02111-1307  USA
#
# $Id: slideshow.js.php,v 1.5 2005/03/18 14:55:52 filetreefrog Exp $
##################################################

?>
var g_<?php echo $_GET['u'];?>_img = document.getElementById("<?php echo $_GET['u'];?>_slideshowImg");
var g_<?php echo $_GET['u'];?>_i = 0;
var last_<?php echo $_GET['u'];?>_i = 0;

g_<?php echo $_GET['u'];?>_img.setAttribute("src",<?php echo $_GET['u'];?>_images[0]);

function <?php echo $_GET['u'];?>_swap() {
	while (g_<?php echo $_GET['u'];?>_i == last_<?php echo $_GET['u']?>_i) {
		if (g_<?php echo $_GET['u'];?>_random) g_<?php echo $_GET['u'];?>_i = Math.floor(Math.random() * (<?php echo $_GET['u'];?>_images.length-1)+.5);
		else {
			g_<?php echo $_GET['u'];?>_i++;
			if (g_<?php echo $_GET['u'];?>_i >= <?php echo $_GET['u'];?>_images.length) g_<?php echo $_GET['u'];?>_i = 0;
		}
	}
	last_<?php echo $_GET['u'];?>_i = g_<?php echo $_GET['u'];?>_i;
	g_<?php echo $_GET['u'];?>_img.setAttribute("src",<?php echo $_GET['u'];?>_images[g_<?php echo $_GET['u'];?>_i]);
	setTimeout("<?php echo $_GET['u'];?>_swap()",g_<?php echo $_GET['u'];?>_delay);
}

if (<?php echo $_GET['u'];?>_images.length > 1) {
	if (g_<?php echo $_GET['u'];?>_random) {
		<?php echo $_GET['u'];?>_swap()
	} else {
		setTimeout("<?php echo $_GET['u'];?>_swap()",g_<?php echo $_GET['u'];?>_delay);
	}
}
