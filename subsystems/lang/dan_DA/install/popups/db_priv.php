<?php

return array(
	'title'=>'Database bruger priviligier',
	'header'=>'Når Exponent forbinder sig til databasen, skal den være i stand til at køre følgende typer af forespørgsler:',
	
	'create'=>'CREATE TABLE',
	'create_desc'=>'Disse forespørgsler opretter nye tabel strukturer inde i databasen. Exponent behøver disse når du installerer det for første gang. CREATE TABLE forespørgsler køres også når nye moduler uploades til sitet.',
	'alter'=>'ALTER TABLE',
	'alter_desc'=>'Hvis du opgraderer et vilkårligt modul i Exponent, vil disse forspørgsler blive kørt for at ændre tabel strukturen i databasen.',
	'drop'=>'DROP TABLE',
	'drop_desc'=>'Disse forespørgsler udføres på databasen nårsomhelst en administrator trimmer den for at fjerne tabeller der ikke længere bruges.',
	'select'=>'SELECT',
	'select_desc'=>'forespørgsler af denne type er meget vigtige for den basale drift af Exponent. Alle data gemt i databasen læses tilbage ved brug af SELECT forespørgsler.',
	'insert'=>'INSERT',
	'insert_desc'=>'Nårsomhelst nyt indhold tilføjes denne hjemmeside, nye rettigheder tildeles, brugere og/eller grupper oprettes og konfigurationsdata genmmes, kører Exponent INSERT forespørgsler.',
	'update'=>'UPDATE',
	'update_desc'=>'Når indhold eller konfigurationer opdateres, modificerer Exponent data i dens tabeller ved at udføre UPDATE forespørgsler.',
	'delete'=>'DELETE',
	'delete_desc'=>'Disse forespørgsler fjerner indhold og konfiguration fra tabeller i hjemmeside databasen. De udføres også nårsomhelst brugere og grupper fjernes, og rettigheder ophæves.',
	
);

?>