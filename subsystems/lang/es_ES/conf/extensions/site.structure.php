<?php

return array(
	'title'=>'Configuraci&#243;n general del sitio web',

	'org_name'=>'Nombre Organizaci&#243;n',
        'org_name_desc'=>'El nombre de su organizaci&#243;n o empresa. Es usado en varios sitios del sistema.',

	'site_title'=>'T&#237;tulo del sitio web',
	'site_title_desc'=>'El t&#237;tulo del sitio web: departamento, master, centro...',

	'use_lang'=>'Lenguaje del interfaz',
	'use_lang_desc'=>'&#191;Que lenguaje va a ser usado para el interfaz de Exponent?',

	'allow_registration'=>'&#191;Permitir registro?',
	'allow_registration_desc'=>'Permitir o no que los nuevos usuarios puedan crear cuentas ellos mismos.',

	'use_captcha'=>'&#191;Usar test CAPTCHA?',
	'use_captcha_desc'=>'CAPTCHA (Computer Automated Public Turing Test to Tell Computers and Humans Apart) es una forma de evitar registros masivos de cuentas.  Cuando se registra un nuevo usuario, se requirida introducir una serio de letras y n&#250;meros que aparecen en un imagen. Esto previene que robots puedan registrar una gran cantidad de cuentas.',

	'site_keywords'=>'Palabras clave',
	'site_keywords_desc'=>'Palabra claves del sitio web para los motores de busqueda.',

	'site_description'=>'Descripci&#243;n',
	'site_description_desc'=>'Una descripci&#243; sobre la finalidad del sitio web.',

	'site_404'=>'Texto para el error: "No Encontrado"',
	'site_404_desc'=>'HTML mostrado a un usuario cuando el intenta acceder a algo que no es encontrado (como una noticia borrada, secci&#243;n...)',

	'site_403'=>'Texto para el error: "Acceso Denegado"',
	'site_403_desc'=>'HTML mostrado a un usuario cuando el intenta realizar una acci&#243;n para la que no tiene permiso.',

	'default_section'=>'Secci&#243;n por defecto',
	'default_section_desc'=>'La secci&#243;n que se visualiza por defecto.',

	'session_timeout'=>'Tiempo de espera de una sesi&#243;n',
	'session_timeout_desc'=>'Cuanto tiempo (en segundos) puede estar un usuario sin realizar ninguna acci&#243n antes de que sea desconectado automaticamente.',

	'enable_session_timeout'=>'Activar L&#237;mite de Tiempo de sesi&#243;n',
	'enable_session_timeout_desc'=>'Activar o desactivar los valores de tiempo m&#225;ximo de sesi&#243n.',

	'timeout_error'=>'Texto para el error: "Sesi&#243;n Expirada"',
	'timeout_error_desc'=>'HTML mostrado a un usuario cuando su sesi&#243n ha expirado y intenta realizar una acci&#243n que necesita tener ciertos permisos.',

	'fileperms'=>'Permisos por defecto de los archivos',
	'fileperms_desc'=>'Los permisos de lectura y escritura de los archivos subidos, para otros usuario que no sea el usario del servidor web.',

	'dirperms'=>'Permisos por defecto de los directorios',
	'dirperms_desc'=>'Los permisos de lectura y escritura de los directorios creados, para otros usuario que no sea el usario del servidor web.',

	'ssl'=>'Activa SSL',
	'ssl_desc'=>'Permitir o no cambiar a modo seguro a traves de SSL',

	'nonssl_url'=>'URL sin soporte SSL',
	'nonssl_url_desc'=>'URL completa del sitio web sin SSL (normalmente empieza por  "http://")',

	'ssl_url'=>'URL con soporte SSL',
	'ssl_url_desc'=>'URL completa del sitio web con SSL (normalmente empieza por "https://")',

	'revision_limit'=>'L&#237;mite Revisi&#243;n Historial',
	'revision_limit_desc'=>'El n&#250;mero m&#225;ximo de grandes revisiones (excluyendo la revisi&#243;n actual) para guardar de las revisiones por objeto. Un l&#237;mite de 0 (cero) significa que todas las revisiones seran guardadas.',

	'enable_workflow'=>'Activar Flujo de Trabajo',
	'enable_workflow_desc'=>'Activa o desactiva el Flujo de Trabajo. Dejalo desactivado si no haces un especial uso de el.',

	'wysiwyg_editor'=>'Editor HTML',
	'wysiwyg_editor_desc'=>'Elige el editor de HTML a usar como editor por defecto para este sitio.',
);

?>