<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
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
# $Id$
##################################################

// Set up sessions to use cookies, NO MATTER WHAT
ini_set('session.use_cookies',1);

if (DEVELOPMENT) {
	// In development mode, we need to turn on full throttle error reporting.
	// Display all errors (some production servers have this set to off)
	ini_set('display_errors',1);
	// Up the ante on the error reporting so we can see notices as well.
	ini_set('error_reporting',E_ALL);
	// This is rarely set to true, but the first time it is, we'll be ready.
	ini_set('ignore_repeated_errors',0);
} else {
	// We can't be showing errors in a production environment...
	ini_set('display_errors',0);
	ini_set('ignore_repeated_errors',1);
}

?>