<?php

return array(
	'subtitle'=>'Configuraci&#243;n de la base de datos',
	
	'in_doubt'=>'Si tienes alguna duda, contacta con el administrador del sistema o el proveedor de alojamiento.',
	'more_info'=>'M&#225;s informaci&#243;n',
	
	'server_info'=>'Informaci&#243;n del servidor',
	'backend'=>'Sistema',
	'backend_desc'=>'Selecciona que software de bases de datos esta instalada en su servidor web.  Si el software no esta listado, no es soportado por Exponent.',
	
	'address'=>'Direcci&#243;n',
	'address_desc'=>'Si la base de datos esta instalada en otra maquina diferente al servidor web, introduzca la direcci&#243;n del servidor de la base de datos. Introduzca una direcci&#243;n IP (como 1.2.3.4) o un dominio de internet (como ejemplo.com) .<br /><br />Si la base de datos esta instalada en la misma maquiena que el servidor web, utilice el valor por defecto, "localhost".',
	
	'port'=>'Puerto',
	'port_desc'=>'Si esta usando un servidor de bases de datos que soporte TCP o otros protocolos de red, y la base de datos esta funcionando en un servidor diferente al servidor web, introduzca el puerto de conexi&#243;n.<br /><br />Si ha introducido en el campo de direcci&#243;n "localhost", debe dejar este campo con su valor por defecto.',
	
	'database_info'=>'Infomaci&#243;n de la base de datos',
	'dbname'=>'Nombre de la base de datos',
	'dbname_desc'=>'El nombre de la base de datos. Consulte con su administrador del sistema o su proveedor de alojamiento si no esta seguro y no sabe como crear una base de datos.',
	
	'username'=>'Usuario',
	'username_desc'=>'Todos los servidores de bases de datos que utiliza Exponent necesitan alg&#250;n metodo de autentificaci&#243;n. Introduzca el nombre del usuario que se conecta al servidor de base de datos.',
	'username_desc2'=>'Compruebe que el usuario tiene los privilegios apropiados para la base de datos que va a utilizar.',
	
	'password'=>'Contrase&#241;a',
	'password_desc'=>'Introduzca la contrase&#241;a para el usuario que ha introducido anteriormente.  La contrase&#241;a <b>no</b> ser&#225; ocultada, porque no puede ser ocultada en el archivo de configuraci&#243;n. Por razones de seguridad, los desarrolladores de Exponent te recomiendan que uses una contrase&#241;a totalmente diferente a otras que uses.',
	
	'prefix'=>'Prefijo de las tablas',
	'prefix_desc'=>'UN prefijo de tabla ayuda a Exponent a diferenciar las tablas de su sitio de otras tablas existentes (o creadas por otras aplicaciones).  Si esta usando una base de datos ya existente, puede que sea necesario cambiar esto.',
	'prefix_note'=>'<b>Nota:</b> Un prefijo de tabla solo puede contener caracteres alfanum&#233;ricos.  Espacio y s&#237;mbolos (incluyendo "_") no estan permitidos.  El gui&#243;n bajo ser&#225; a&#241;adido por Exponent.',
	
	'default_content'=>'Contenido de ejemplo',
	'install'=>'Instala contenido de ejemplo',
	'install_desc'=>'Para ayudarle a compreder como funciona Exponent, le sugerimos que intale el paquete con el contenido de ejemplo. Si es la primera vez que usa Exponent, le recomendamos en&#233;rgicamente que lo instale.',

	'enable_sef'=>'Activar URLs Amistosas',
	'sef'=>'URLs Amistosas',
	'sef_desc'=>'Active este opci&#243;n para utilizar URLs Amistosas.',	
	
	'verify'=>'Verificaci&#243;n de la configuraci&#243;n',
	'verify_desc'=>'Despu&#233;s de comprobar que ha introducido la infomaci&#243;n correctamente, presione el bot&#243;n "Probar configuraci&#243;n". El asistente de instalci&#243;n de Exponent realizar&#225; una serie de pruebas para comprobar que la configuraci&#243;n es v&#225;lida.',
	'test_settings'=>'Probar configuraci&#243;n',

	'DB_ENCODING'=>'Codificaci&#243;n Base de Datos',
	'DB_ENCODING_desc'=>'No cambiar si no sabes lo que haces. Esta configuraci&#243;n solo es repecto MySQL 4.1.2+'
);

?>