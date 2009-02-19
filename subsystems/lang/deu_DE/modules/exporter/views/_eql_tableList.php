<?php

return array(
	'form_title'=>'Aktuelle Datenbank sichern',
	'form_header'=>'Listed below are all of the tables in your site\'s database.  Select which tables you wish to backup, and then click the "Export Data" button.  Doing so will generate an EQL file (which you must save) that contains the data in the selected tables.  This file can be used later to restore the database to the current state.',
	
	'at_least_one'=>'Sie m�ssen zumindest eine Tabelle ausw�hlen.',
	
	'select_all'=>'Alles ausw�hlen',
	'deselect_all'=>'Alles abw�hlen',
	
	'file_template'=>'File Name Template:',
	'template_description'=>'Use __DOMAIN__ for this website\'s domain name, __DB__ for the site\'s database name and any strftime options for time specification. The EQL extension will be added for you. Any other text will be preserved.',
	
	'export_data'=>'Exportiere Daten',
);

?>