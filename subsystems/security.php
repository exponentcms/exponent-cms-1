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

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
define('SYS_SECURITY',1);

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function pathos_security_checkPasswordStrength($username,$password) {

	pathos_lang_loadDictionary('subsystems','security');
// Return blank string on success, error message on failure.
// The error message should let the user know why their password is wrong.
	if (strcasecmp($username,$password) == 0) return TR_SECURITYSUBSYSTEM_DIFFFROMNAME;
	# For example purposes, the next line forces passwords to be over 8 characters long.
	if (strlen($password) < 8) return TR_SECURITYSUBSYSTEM_NOTLONGENOUGH;
	
	return ""; // by default, accept any passwords
}

?>
