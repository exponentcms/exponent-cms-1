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
	"SMTP Server Settings",
	array(
		"SMTP_SERVER"=>array(
			"title"=>"SMTP Server",
			"description"=>"The IP address or host/domain name of the server to connect to for sending email through smtp.",
			"control"=>new textcontrol()
		),
		"SMTP_PORT"=>array(
			"title"=>"SMTP Port",
			"description"=>"The port that the SMTP server is listening to for SMTP connections.  If you don't know what this is, leave it as the default of 25.",
			"control"=>new textcontrol()
		),
		"SMTP_AUTHTYPE"=>array(
			"title"=>"Authentication Method",
			"description"=>"Here, you can specify what type of authentication your SMTP server requires (if any).  Please consult your mailserver administrator for this information.",
			"control"=>new dropdowncontrol("",array("NONE"=>"No Authentication","LOGIN"=>"LOGIN","PLAIN"=>"PLAIN"))
		),
		"SMTP_USERNAME"=>array(
			"title"=>"SMTP Username",
			"description"=>"The username to use when connecting to an SMTP server that requires some form of authentication",
			"control"=>new textcontrol()
		),
		"SMTP_PASSWORD"=>array(
			"title"=>"SMTP Password",
			"description"=>"The password to use when connecting to an SMTP server that requires some form of authentication",
			"control"=>new passwordcontrol()
		),
		"SMTP_FROMADDRESS"=>array(
			"title"=>"From Address",
			"description"=>"The from address to use when talking to the SMTP server.  This is important for people using ISP SMTP servers, which may restrict access to certain email addresses.",
			"control"=>new textcontrol()
		),
	)
);

?>