<?php

return array(
	'title'=>'Usar una base de datos existente',
	'p1'=>'Se puede utilizar una base de datos ya existente para almacenar el contenido de su sitio web, no obstante deben considerarse una serie de cuestiones.',
	'p2'=>'Exponent necesita tener sus propias tablas en la base de datos ya existente para un funcionamiento correcto.  Esto se puede llevar a cabo especificando un nuevo prefijo de tabla.',
	'p3'=>'El prefijo de tabla se utilizar para hacer que el nombre de cada tablas sea unico en la base de datos. El prefijo es a&#241;adido al inicio del nombre de cada tabla.  Esto implica que puede coexistir dos sitios con Exponent en la misma base de datos si, por ejemplo, uno usa como prefijo "exponent" y el otro usa "cms".',
	'p4'=>'Exponent a&#241;adirá entre el prefijo y el nombre de la tabla un gui&#243;n bajo.  Esto mejora la legibilidad de la base de datos, y ayuda con los posibles problemas.',
);

?>