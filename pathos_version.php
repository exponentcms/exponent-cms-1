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
/* 
NOTICE::
This file is being deprecated and is here for backward
compatibility only.  If you need to make changes in this file,
do so in the new version.  Please update your custom themes and modules 
to reflect the new prefix assignment: both filenames and functions
prefixed with 'pathos' are now prefixed with exponent.  All
constants prefixed with PATHOS are now prefixed with EXPONENT
*/
require_once("exponent_version.php");


define('PATHOS_VERSION_MAJOR',EXPONENT_VERSION_MAJOR);
/* exdoc
 * This is the minor version number of Exponent; the 96 in 0.96.2-beta3
 */
define('PATHOS_VERSION_MINOR',EXPONENT_VERSION_MINOR);
/* exdoc
 * This is the revision version number of Exponent; the 2 in 0.96.2-beta3
 */
define('PATHOS_VERSION_REVISION',EXPONENT_VERSION_REVISION);
/* exdoc
 * This is the date that this version of Exponent was exported from CVS and built.
 */
define('PATHOS_VERSION_BUILDDATE',EXPONENT_VERSION_BUILDDATE);
/* exdoc
 * This specifies the type of release, either 'alpha','beta','rc' or '' (for stable).
 */
define('PATHOS_VERSION_TYPE',EXPONENT_VERSION_TYPE);
/* exdoc
 * This number is bumped each time a distribution of a single version is
 * released.  For instance, the 3rd beta has an version type iteration of 3.
 */
define('PATHOS_VERSION_ITERATION',EXPONENT_VERSION_ITERATION); // only applies to betas/alphas / rcs

return '0.96';

?>
