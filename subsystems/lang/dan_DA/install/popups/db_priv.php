<?php

return array(
	'title'=>'Database bruger priviligier',
	'header'=>'N�r Exponent forbinder sig til databasen, skal den v�re i stand til at k�re f�lgende typer af foresp�rgsler:',
	
	'create'=>'CREATE TABLE',
	'create_desc'=>'Disse foresp�rgsler opretter nye tabel strukturer inde i databasen. Exponent beh�ver disse n�r du installerer det for f�rste gang. CREATE TABLE foresp�rgsler k�res ogs� n�r nye moduler uploades til sitet.',
	'alter'=>'ALTER TABLE',
	'alter_desc'=>'Hvis du opgraderer et vilk�rligt modul i Exponent, vil disse forsp�rgsler blive k�rt for at �ndre tabel strukturen i databasen.',
	'drop'=>'DROP TABLE',
	'drop_desc'=>'Disse foresp�rgsler udf�res p� databasen n�rsomhelst en administrator trimmer den for at fjerne tabeller der ikke l�ngere bruges.',
	'select'=>'SELECT',
	'select_desc'=>'foresp�rgsler af denne type er meget vigtige for den basale drift af Exponent. Alle data gemt i databasen l�ses tilbage ved brug af SELECT foresp�rgsler.',
	'insert'=>'INSERT',
	'insert_desc'=>'N�rsomhelst nyt indhold tilf�jes denne hjemmeside, nye rettigheder tildeles, brugere og/eller grupper oprettes og konfigurationsdata genmmes, k�rer Exponent INSERT foresp�rgsler.',
	'update'=>'UPDATE',
	'update_desc'=>'N�r indhold eller konfigurationer opdateres, modificerer Exponent data i dens tabeller ved at udf�re UPDATE foresp�rgsler.',
	'delete'=>'DELETE',
	'delete_desc'=>'Disse foresp�rgsler fjerner indhold og konfiguration fra tabeller i hjemmeside databasen. De udf�res ogs� n�rsomhelst brugere og grupper fjernes, og rettigheder oph�ves.',
	
);

?>