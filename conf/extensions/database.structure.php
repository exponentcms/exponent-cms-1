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

return array(
	"Database Options",
	array(
		"DB_ENGINE"=>array(
			"title"=>"Backend Software",
			"description"=>"The database server software package.",
			"control"=>new dropdowncontrol("",pathos_database_backends())
		),
		"DB_NAME"=>array(
			"title"=>"Database Name",
			"description"=>"The name of the database to store the site tables in.",
			"control"=>new textcontrol()
		),
		"DB_USER"=>array(
			"title"=>"Username",
			"description"=>"The name of the user to connect to the database server as",
			"control"=>new textcontrol()
		),
		"DB_PASS"=>array(
			"title"=>"Password",
			"description"=>"Password of the user above.",
			"control"=>new passwordcontrol()
		),
		"DB_HOST"=>array(
			"title"=>"Server Address",
			"description"=>"The domain name or IP address of the database server.  If this is a local server, use 'localhost'",
			"control"=>new textcontrol()
		),
		"DB_PORT"=>array(
			"title"=>"Server Port",
			"description"=>"The port that the database server runs on.  For MySQL, this is 3306.",
			"control"=>new textcontrol()
		),
		"DB_TABLE_PREFIX"=>array(
			"title"=>"Table Prefix",
			"description"=>"A prefix to prepend to all table names.",
			"control"=>new textcontrol()
		)
	)
);

?>