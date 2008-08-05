<?php

return array(
	'title'=>'Opciones del servidor SMTP',
	
	'php_mail'=>'&#191;Usar la funci&#243;n de PHP mail()?',
	'php_mail_desc'=>'Utiliza esta opci&#243;n si el servidor SMTP no te funciona, la configuraci&#243; del servidor no permite utilizar directamente SMTP o no dispones de servidor SMTP. NOTE: Si seleccionas esta opci&#243;n, no es necesario modificar ninguna de las otras opciones, ser&#225;n ignoradas.',
	
	'server'=>'Servidor SMTP',
	'server_desc'=>'La direcci&#243;n IP o el nombre del host/dominio del servidor a trav&#233;s del cual se envia el email via SMTP.',
	
	'port'=>'Puerto',
	'port_desc'=>'El puerto a trav&#233;s del cual el servidor SMTP esta escuchando las conexiones SMTP.  Si conoce cual es, deje el puerto por defecto (puerto 25).',
	
	'auth'=>'M&#233;todo de autentificaci&#243;n',
	'auth_desc'=>'Aqu&#237; puede especificar que tipo de autentificaci&#243;n requiere su servidor SMTP (si es necesaria). Por favor, consulte esta informaci&#243;n al admnistrador de su servidor de correo.',
	'auth_none'=>'Sin autentifiacaci&#243;n',
	'auth_plain'=>'PLAIN',
	'auth_login'=>'LOGIN',
	
	'username'=>'Usuario SMTP',
	'username_desc'=>'El usuario que va a usar cuando conecte a un servidor SMTP que requiera autentifiacaci&#243;n',
	
	'password'=>'Contrase&#241;a SMTP',
	'password_desc'=>'La contraseña que tiene que usar cuando conecte a un servidor SMTP que requiera autentifiacaci&#243;n',
	
	'from_address'=>'Direcci&#243;n de env&#237;o',
	'from_address_desc'=>'Direcci&#243; de email que se usa para comunicarse con el servidor SMTP. Esto es importante para servidores SMTP que restringen el acceso a ciertas direcciones de email.',
);

?>