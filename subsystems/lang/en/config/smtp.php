<?php

define('TR_CONFIG_SMTP_TITLE','SMTP Server Settings');

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