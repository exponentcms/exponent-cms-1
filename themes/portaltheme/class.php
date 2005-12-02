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

if (class_exists('portaltheme')) return;

class portaltheme {
	function name() { return "Portal Site"; }
	function author() { return "Jake Hamann (jake@jakehamann.com)"; }
	function description() { return "A simple portal page theme."; }
}

?>