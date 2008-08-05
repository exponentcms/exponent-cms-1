<?php

return array(
	'title'=>'Exploraci&#243;n de los Requerimientos del Sistema',
	'header'=>'El chequeo del sistema realiza una serie de pruebas para asegurarse que no haya problemas de software (permisos de archivos, extensiones PHP...) en el servidor antes de la instalci&#243;n de Exponent. Esta pa&#225;gina explica cada una de las pruebas y como reconfigurar el servidor si alguna falla.<br /><br />Nots: En todas las pruebas, el usuario usado es el correspondiente al <span class="var">usuario web</span> del servidor, y <span class="var">EXPONENT</span> es usado como ruta absoluta al directorio de Exponent.',
	
	'filedir_tests'=>'Pruebas de permisos sobre archivos y directorios',
	
	'rw_server'=>'El servidor web debe tener permisos de lectura y escritura',
	'unix_solution'=>'Soluci&#243;n UNIX',
	
	'config.php'=>'El archivo conf/config.php almacena la configuraci&#243;n activa del sitio, incluyendo la configuraci&#243;n de la base de datos y el tema elegido.',
	'profiles'=>'El directorio conf/profiles almacena las diferentes configuraciones para el sitio. Aunque no use m&#225;s de un perfil, el servidor tiene que poder crear archivos en este directorio.',
	'overrides.php'=>'El archivo overrides.php es usado para sobrescribir algunas constantaes que son detectadas autom&#225;ticamente por Exponet. Si el instalador se entcontra con alg&#250;n problema con algunos valores autodetectados, el instalador escribir&#225; los valores correctos en este archivos antes de completar la instalaci&#243;n.  Despu&#233;s de instalar Exponent, este archivo s&#243;lo necesita tener permisos de lectura por el servidor.',
	'install'=>'El directorio install contiene todos los archivos del Asistente de Instalaci&#243;n de Exponent. Una vez se haya instalado Exponent, el asistente se elimina a si mismo (borrando el archivo install/not_configured). Para hacer esto, necesita permisos de escritura sobre el directorio install. Despu&#233;s de la instalaci&#243;n, este directorio no ser&#225; necesario, puede borrarlo o configurar los permisos de tal forma que el servidor no tenga permiso de lectura.',
	'modules'=>'Exponent realiza algunas comprobaciones sobre los m&#243;rdulos instalados para asegurarse de que no se encuentra nada extra&#241;o. Si estas pruebas fallan, escriba un post en Support Request en la p&#225;gina del proyecto Exponent en SourceForge (<a href="http://www.sourceforge.net/projects/exponent/" target="_blank">http://www.sourceforge.net/projects/exponent/</a>).',
	'views_c'=>'Exponent usa Smarty para separar su motor de datos de su motor de interfaces. La plantillas Smarty son compiladas por PHP y guardadas en el directorio  views_c, el servidor tiene que tener permisos de escritura en este directorio.',
	'extensionuploads'=>'Cuando usa la funci&#243;n "Subir Extension" del Panel de Control del Administrador, el archivo subido se guarda en el directorio extension uploads temporalmente. Por lo tanto, el servidor necesito acceso total a este directorio.',
	'files'=>'Todos los archivos de contenidos subidos (recursos, informaci&#243;n importada, imagenes...) se almacenan en el directorio files/, el servidor necesita acceso de lectura y escrituro en este directorio. Si la prueba falla y usted esta seguro que tiene permisos sobre ese directorio, recuerde dar permisos recursivamente sobre todos los subdirectorios y archivos que pueda haber dentro del directorio files/.',
	'tmp'=>'El directorio tmp es usado por Exponent para archivos temporales.',
	
	'other_tests'=>'Otras pruebas',
	'db_backend'=>'Sistema de bases de datos',
	'db_backend_desc'=>'Exponent almacena todo el contenidos de su sitio en una base de datos relacional. Por razones de portabilidad, se utiliza una capa de abstracci&#243;n personalizada para la base de datos.  Actualmente, esta capa solo soporta MySQL y PostGreSQL. Si la prueba falla, significa que PHP no ha detectado estas bases de datos.',
	'gd'=>'Librer&#237;a gr&#225;fica GD',
	'gd_desc'=>'Exponent utiliza Librer&#237;a gr&#225;fica GD para las funciones sobre imagenes. Exponent puede funcoinar sin esta librer&#237;a, pero perder&#225; muchas funcionalidades como pruebas Captcha y previsualizaciones autom&#225;ticas. Una versi&#243;n de la librer&#237;a igual o superior a 2.0.x proporcionar&#225; unas previsualizaciones con mayor nitidez y calidad.',
	'php_desc'=>'Debido a las funciones que utiliza Exponent, necesita una versi&#243;n posterior a la 4.0.6 . La mayor&#237;a de las funciones son soportadas por versiones anteriores, pero suele provocar fallos, y no puede implementarse en versiones de PHP inferiores a 4.0.6.',
	'zlib'=>'Soperte ZLib',
	'zlib_desc'=>'ZLib es usada para descomprimir los archivos Tar y Zip.',
	'xml'=>'XML (Expat Library)',
	'xml_desc'=>'Las extesiones de servicios web para Exponent requieren Expat Library.  Si no esta usando servicios web o m&#243;dulos que dependan de servicios web, esta alerta puede ser ignorada.',
	'safemode'=>'Safe Mode no activado',
	'safemode_desc'=>'Safe Mode es una medida de seguridad presente en los servidores de alojamiento compartido. Esto limita a los archivos PHP acceder y modificar archivos que no pertenezcan al mismo propietario.  Esto puede causar serios problemas si los archivos de Exponent no correctamente configurados.<br /><br />Si decide ignorar esta advertencia comprueba que TODOS los archivos del paquete Exponent pertenecen al mismo usuario.',
	'safemode_req'=>'Exponent funciona mejor cuando Safe Mode esta desactivado',
	'basedir'=>'Open BaseDir no activado',
	'basedir_req'=>'Exponent funciona mejor cuando Open BaseDir esta desactivado',
	'basedir_desc'=>'La restricci&#243;n open_basedir es una medidad de seguridad presente en algunos servidores de alojamiento compartido. Esto impide a los archivos PHP realizar transferencias con archivos no pertenecientes al mismo directorio. Esto puede causar problemas con algunas operaciones con archivos, incluyendo el administrador Multi-Site. Ignore este error bajo su propio riesgo.',
	'upload'=>'Subir archivos activado',
	'upload_desc'=>'Los administradores de un servidor pueden de desactivar la posibilidad de subir archivos v&#237;a web. Sin la posibilidad de subir archivos v&#237;a web, las funcionalidades de Exponent se ver&#225;n gravemente limitadas, no podrá subir nuevo c&#243;digo, parches o imagenes y recursos.',
	'tempfile'=>'Creaci&#243;n de archivos temporales',
	'tempfile_desc'=>'Varias funcionalidades de Exponent tienen que crear archivos temporales para llevar a cabo sus tareas. Muchas veces este error esta relacionado con permisos de escritura y lectura sobre el directorio "tmp/".',
);

?>