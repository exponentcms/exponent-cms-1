<?php

return array(
	'title'=>'Permisos del usuario de la base de datos',
	'header'=>'Exponent necesita los permisos para poder ejecutar los siguientes comandos en la base de datos:',
	
	'create'=>'CREATE TABLE',
	'create_desc'=>'Este comando crea una nueva tabla en la base de datos. Exponent necesita este comando cuando se instala por primera vez. Tambi&#233;n se ejecuta cuando se instala un nuevo m&#243;dulo.',
	'alter'=>'ALTER TABLE',
	'alter_desc'=>'Este comando se utiliza cuando se actualiza cualquier m&#243;dulo en Exponent. Cambia las estrutura de las tables que se vean afectadas.',
	'drop'=>'DROP TABLE',
	'drop_desc'=>'Este comando se utiliza cuando se desea borrar tablas que no se usan. S&#243;lo puede ejecutarlo un usuario administrador.',
	'select'=>'SELECT',
	'select_desc'=>'Este es el comando más importante para Exponent. Todo la informaci&#243;n almacenada en la base de datos es le&#237;da a trav&#233;s de comandos SELECT.',
	'insert'=>'INSERT',
	'insert_desc'=>'Se usa cuando se a&#241;aden nuevos contenidos, se asignan nuevos permisos, se crean usuarios o grupos y se guarada configuraci&#243;n.',
	'update'=>'UPDATE',
	'update_desc'=>'Cuando el contenidos o las configuraciones son actualizadas, Exponent utiliza este comando para modificar la infomaci&#243;n.',
	'delete'=>'DELETE',
	'delete_desc'=>'Este comando borra contenidos y configuraciones en las tablas de la base de datos. Tambi&#233;n se utiliza cuando los usuarios y los grupos son borrado, y cuando se revocan los permisos.',
	
);

?>