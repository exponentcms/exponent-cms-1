<?php

return array(
	'subtitle'=>'Database konfiguration',
	
	'in_doubt'=>'Hvis du er i tvivl, kontakt din systemadministrator eller serverudbyder.',
	'more_info'=>'Mere information',
	
	'server_info'=>'Server information',
	'backend'=>'Motor',
	'backend_desc'=>'Vælg hvilken database server software pakke som din web server kører. Hvis softwaren ikke er listet, er den ikke understøttet af Exponent.',
	
	'address'=>'Adresse',
	'address_desc'=>'Hvis din database server software kører på en anden fysisk maskine end web serveren, indtast adressen for database server maskinen. Enten en IP adresse (f.eks. 1.2.3.4) eller et internet domæne navn (f.eks. example.com) vil virke.<br /><br />Hvis din database server software kører på samme maskine som web serveren, brug standardindstillingen "localhost".',
	
	'port'=>'Port',
	'port_desc'=>'Hvis du bruger en database server der understøtter TCP eller en anden netværks protokol, og database softwaren kører på en anden fysisk maskine end web serveren, indtast porten.<br /><br />Hvis du indtastede "localhost" i adressefeltet, skal du efterlade den som standardindstillingen.',
	
	'database_info'=>'Database information',
	'dbname'=>'Database navn',
	'dbname_desc'=>'Dette er det rigtige navn for databasen, i overensstemmelse med database serveren. Konsultér din system administrator eller webudbyder hvis du er usikker og ikke satte databasen op selv.',
	
	'username'=>'Brugernavn',
	'username_desc'=>'Al database server software understøttet af Exponent kræver en eller anden form for autorisation. Indtast navnet på den bruger der skal bruges til at loggeind på database serveren.',
	'username_desc2'=>'Vær sikker på at denne bruger har korrekte database bruger privilegier.',
	
	'password'=>'Adgangskode',
	'password_desc'=>'Indtast adgangskoden til brugernavnet som du indtastede ovenfor. Adgangskoden vil <b>ikke</b> være skjult, da den ikke kan være skjult i konfigurationsfilen. Exponent udviklerne tilskynder dig at bruge helt nye adgangskoder, forskellig fra dine andre af sikkerhedsmæssige årsager.',
	
	'prefix'=>'Tabel præfiks',
	'prefix_desc'=>'Et tabel præfiks hjælper Exponent med at differentiere tabeller, for denne hjemmeside, fra andre tabeller som måske allerede eksisterer (eller eventuelt oprettes af andre scripts). Hvis du bruger en eksisterende database, ønsker du måske at ændre dette.',
	'prefix_note'=>'<b>OBS:</b> Et tabel præfiks kan kun indeholde tal og bogstaver. Mellemrun og symboler (inklusiv "_") er ikke tilladt. En "_" vil blive tilføjet for dig af Exponent.',
	
	'default_content'=>'Standard eksempel indhold',
	'install'=>'Installér eksempel indhold',
	'install_desc'=>'Til at hjælpe med at forstå hvordan Exponent arbejder, og hvordan alt passer sammen, foreslår vi at du installerer eksempel indholdet. Hvis du er ny til Exponent, opfordrer vi dig til at gøre dette.',
	
	'verify'=>'Verificér konfigurationen',
	'verify_desc'=>'Efter at du er tilfreds med den information du har indtastet, klik på "Test indstillinger" knappen nedenunder. Exponent Installations Guiden vil så udføre nogle indledende tests for at sikre at konfigurationen er gyldig.',
	'test_settings'=>'Test indstillinger',
);

?>