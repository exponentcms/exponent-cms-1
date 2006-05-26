<?php

return array(
	'title'=>'Datenbank-Benutzer-Berechtigungen',
	'header'=>'Wenn Exponent zur DB verbindet müssen folgende Querietypen erlaubt sein:',
	
	'create'=>'CREATE TABLE',
	'create_desc'=>'Dieser Typ erstellt die Tabellenstruktur für Exponent und wird bei der 1. Installation benötigt. CREATE TABLE werde zudem von einigen Modulen bei der Installation benötigt.',
	'alter'=>'ALTER TABLE',
	'alter_desc'=>'Dieser Typ verändert Tabellen und wird auch im laufenden Betrieb benötigt.',
	'drop'=>'DROP TABLE',
	'drop_desc'=>'Dieser Typ wird ausgeführt um Tabellen aus der DB zu entfernen.',
	'select'=>'SELECT',
	'select_desc'=>'Dieser Typ holt Inhalte aus der DB und übergibt sie zur Weiterverarbeitung an Exponent.',
	'insert'=>'INSERT',
	'insert_desc'=>'Dieser Typ ist zuständige für den Einfügen von Inhalten (Content) in die DB.',
	'update'=>'UPDATE',
	'update_desc'=>'Damit wird ein in der DB vorhandener Inhalt mit einem aktuellerem ersetzt.',
	'delete'=>'DELETE',
	'delete_desc'=>'Wird zum löschen von Inhalten in der DB benötigt.',
	
);

?>