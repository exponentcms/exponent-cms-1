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

define('TR_CONFIG_SMTP_TITLE','SMTP Server Settings');

define('TR_CONFIG_SMTP_USE_PHP_MAIL','Use PHP mail() Function?');
define('TR_CONFIG_SMTP_USE_PHP_MAIL_DESC','If the Exponent implementation of raw SMTP does not work for you, either because of server issues or hosting configurations, check this option to use the standard mail() function provided by PHP.  NOTE: If you do so, you will not have to modify any other SMTP settings, as they will be ignored.');

define('TR_CONFIG_SMTP_SERVER','SMTP Server');
define('TR_CONFIG_SMTP_SERVER_DESC','The IP address or host/domain name of the server to connect to for sending email through smtp.');

define('TR_CONFIG_SMTP_PORT','SMTP Port');
define('TR_CONFIG_SMTP_PORT_DESC','The port that the SMTP server is listening to for SMTP connections.  If you do not know what this is, leave it as the default of 25.');

define('TR_CONFIG_SMTP_AUTHTYPE','Authentication Method');
define('TR_CONFIG_SMTP_AUTHTYPE_DESC','Here, you can specify what type of authentication your SMTP server requires (if any).  Please consult your mailserver administrator for this information.');

define('TR_CONFIG_SMTP_USERNAME','SMTP Username');
define('TR_CONFIG_SMTP_USERNAME_DESC','The username to use when connecting to an SMTP server that requires some form of authentication');

define('TR_CONFIG_SMTP_PASSWORD','SMTP Password');
define('TR_CONFIG_SMTP_PASSWORD_DESC','The password to use when connecting to an SMTP server that requires some form of authentication');

define('TR_CONFIG_SMTP_ADDRESS','From Address');
define('TR_CONFIG_SMTP_ADDRESS_DESC','The from address to use when talking to the SMTP server.  This is important for people using ISP SMTP servers, which may restrict access to certain email addresses.');

?>