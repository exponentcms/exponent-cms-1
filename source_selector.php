<?php

##################################################
#
# Copyright 2004 James Hunt and OIC Group, Inc.
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

/**
 * Source Selector
 *
 * @author James Hunt
 * @copyright 2004 James Hunt and the OIC Group, Inc.
 *
 * @package Exponent
 */

/**
 * Define Source Selector constant as 1, since we are not selecting orphaned sources.
 */
define("SOURCE_SELECTOR",1);

/**
 * Initialize the Pathos Framework
 */
include_once('pathos.php');
/**
 * @ignore
 */
define('SCRIPT_RELATIVE',PATH_RELATIVE);
/**
 * @ignore
 */
define('SCRIPT_ABSOLUTE',BASE);
/**
 * @ignore
 */
define('SCRIPT_FILENAME','source_selector.php');

/**
 * Call the real selector script.  It will use the value of SOURCE_SELECTOR to determine what it needs to do.
 */
include_once("selector.php");

?>