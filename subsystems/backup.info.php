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
 * Backup Subsystem Info File
 *
 * Provides reflective information to Exponent about the Backup subsystem
 *
 * @package		Subsystems
 * @subpackage	AutoLoader
 *
 * @author	James Hunt
 * @copyright	2004 James Hunt and the OIC Group, Inc.
 * @version	0.95
 */
 
return array(
	"name"=>"Backup Subsystem",
	"author"=>"James Hunt",
	"description"=>"Provides the necessary means to backup and restore certain parts of the site.",
	"version"=>pathos_core_version(true)
);

?>