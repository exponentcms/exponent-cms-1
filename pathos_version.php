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

if (!defined('PATHOS_VERSION')) {
	/* exdoc
	 * If this constant is set to a true value (1 is the prefered value), then
	 * the system will behave slightly differently, to accomodate for developers
	 * and their environment.
	 */
	define('DEVELOPMENT',1); // CHANGE FOR DIST
	/* exdoc
	 * This is the major version number of Exponent; the 0 in 0.96.2-beta3
	 */
	define('PATHOS_VERSION_MAJOR',0);
	/* exdoc
	 * This is the minor version number of Exponent; the 96 in 0.96.2-beta3
	 */
	define('PATHOS_VERSION_MINOR',96);
	/* exdoc
	 * This is the revision version number of Exponent; the 2 in 0.96.2-beta3
	 */
	define('PATHOS_VERSION_REVISION',0);
	/* exdoc
	 * This is the date that this version of Exponent was exported from CVS and built.
	 */
	define('PATHOS_VERSION_BUILDDATE','%%BUILDDATE%%');
	/* exdoc
	 * This specifies the type of release, either 'alpha','beta','rc' or '' (for stable).
	 */
	define('PATHOS_VERSION_TYPE','rc');
	/* exdoc
	 * This number is bumped each time a distribution of a single version is
	 * released.  For instance, the 3rd beta has an version type iteration of 3.
	 */
	define('PATHOS_VERSION_ITERATION',1); // only applies to betas/alphas / rcs
}

return '0.96';

?>
