<?php

return array(
	'title'=>'Opciones para Autentificaci&#243;n LDAP',
	
	'use_ldap'=>'Activar Autentificaci&#243;n LDAP',
	'use_ldap_desc'=>'Exponent utilizar&#225; el servidor LDAP indicado abajo para autentificar a los usuarios.',
	
	'ldap_server'=>'Servidor LDAP',
	'ldap_server_desc'=>'Introduzca el hostname o IP del servidor LDAP.',
	
	'ldap_base_dn'=>'DN Base',
	'ldap_base_dn_desc'=>'Introduzca el Base context para la conexi&#243;n LDAP.',
	
	'ldap_bind_user'=>'LDAP Bind User',
	'ldap_bind_user_desc'=>'The username or context for the binding to the LDAP Server to perform administration tasks(This currently doesn\'t do anything.)',

	'ldap_bind_pass'=>'LDAP Password',
	'ldap_bind_pass_desc'=>'Enter the password for the username/context listed above.',
	
	'db_host'=>'Server Address',
	'db_host_desc'=>'The domain name or IP address of the database server.  If this is a local server, use "localhost"',
	
	'db_port'=>'Server Port',
	'db_port_desc'=>'The port that the database server runs on.  For MySQL, this is 3306.',
	
	'db_table_prefix'=>'Table Prefix',
	'db_table_prefix_desc'=>'A prefix to prepend to all table names.',
	
	'db_encoding'=>'Database Connection Encoding',
	'db_encoding_desc'=>'Sets the encoding of a connection. Supported on mySQL higher 4.1.12.'

);

?>
