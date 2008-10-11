<?php

return array(
	'form_title'=>'Backup nuværende database',
	'form_header'=>'Nedenfor ses en liste med alle tabeller i din hjemmeside\'s database. Vælg hvilke tabeller du ønsker at tage backup af, aog klik så the "Eksporter Data" knappen. Så dannes der en EQL fil (som du skal gemme. Den indeholder data fra de valgte tabeller. Denne fil kan senere bruges til at genskabe databasens nuværended tilstand.',
	
	'at_least_one'=>'Du skal mindst vælge en tabel til eksport.',
	
	'select_all'=>'Vælg alle',
	'deselect_all'=>'Fravælg alle',
	
	'file_template'=>'Filnavn forlæg:',
	'template_description'=>'Brug __DOMAIN__ for dette hjemmeside\'s domænenavn, __DB__ for site\'s database navn og enhver strftime option for tids-specifikation. EQL-extension vil blive tilføjet for dig for you. Anden tekst vil blive bevaret.',
	
	'export_data'=>'Eksporter data',
);

?>