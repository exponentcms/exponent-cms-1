<?php

return array(
	'title'=>'Datenbank-Benutzer-Berechtigungen',
	'header'=>'Wenn Exponent zur DB verbindet m�ssen folgende Querietypen erlaubt sein:',
	
	'create'=>'CREATE TABLE',
	'create_desc'=>'Dieser Typ erstellt die Tabellenstruktur f�r Exponent und wird bei der 1. Installation ben�tigt. CREATE TABLE werde zudem von einigen Modulen bei der Installation ben�tigt.',
	'alter'=>'ALTER TABLE',
	'alter_desc'=>'Dieser Typ ver�ndert Tabellen und wird auch im laufenden Betrieb ben�tigt.',
	'drop'=>'DROP TABLE',
	'drop_desc'=>'Dieser Typ wird ausgef�hrt um Tabellen aus der DB zu entfernen.',
	'select'=>'SELECT',
	'select_desc'=>'Dieser Typ holt Inhalte aus der DB und �bergibt sie zur Weiterverarbeitung an Exponent.',
	'insert'=>'INSERT',
	'insert_desc'=>'Dieser Typ ist zust�ndige f�r den Einf�gen von Inhalten (Content) in die DB.',
	'update'=>'UPDATE',
	'update_desc'=>'Damit wird ein in der DB vorhandener Inhalt mit einem aktuellerem ersetzt.',
	'delete'=>'DELETE',
	'delete_desc'=>'Wird zum l�schen von Inhalten in der DB ben�tigt.',
	
);

?>