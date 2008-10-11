<?php

return array(
	'subtitle'=>'Database konfiguration',
	
	'in_doubt'=>'Hvis du er i tvivl, kontakt din systemadministrator eller serverudbyder.',
	'more_info'=>'Mere information',
	
	'server_info'=>'Server information',
	'backend'=>'Motor',
	'backend_desc'=>'V�lg hvilken database server software pakke som din web server k�rer. Hvis softwaren ikke er listet, er den ikke underst�ttet af Exponent.',
	
	'address'=>'Adresse',
	'address_desc'=>'Hvis din database server software k�rer p� en anden fysisk maskine end web serveren, indtast adressen for database server maskinen. Enten en IP adresse (f.eks. 1.2.3.4) eller et internet dom�ne navn (f.eks. example.com) vil virke.<br /><br />Hvis din database server software k�rer p� samme maskine som web serveren, brug standardindstillingen "localhost".',
	
	'port'=>'Port',
	'port_desc'=>'Hvis du bruger en database server der underst�tter TCP eller en anden netv�rks protokol, og database softwaren k�rer p� en anden fysisk maskine end web serveren, indtast porten.<br /><br />Hvis du indtastede "localhost" i adressefeltet, skal du efterlade den som standardindstillingen.',
	
	'database_info'=>'Database information',
	'dbname'=>'Database navn',
	'dbname_desc'=>'Dette er det rigtige navn for databasen, i overensstemmelse med database serveren. Konsult�r din system administrator eller webudbyder hvis du er usikker og ikke satte databasen op selv.',
	
	'username'=>'Brugernavn',
	'username_desc'=>'Al database server software underst�ttet af Exponent kr�ver en eller anden form for autorisation. Indtast navnet p� den bruger der skal bruges til at loggeind p� database serveren.',
	'username_desc2'=>'V�r sikker p� at denne bruger har korrekte database bruger privilegier.',
	
	'password'=>'Adgangskode',
	'password_desc'=>'Indtast adgangskoden til brugernavnet som du indtastede ovenfor. Adgangskoden vil <b>ikke</b> v�re skjult, da den ikke kan v�re skjult i konfigurationsfilen. Exponent udviklerne tilskynder dig at bruge helt nye adgangskoder, forskellig fra dine andre af sikkerhedsm�ssige �rsager.',
	
	'prefix'=>'Tabel pr�fiks',
	'prefix_desc'=>'Et tabel pr�fiks hj�lper Exponent med at differentiere tabeller, for denne hjemmeside, fra andre tabeller som m�ske allerede eksisterer (eller eventuelt oprettes af andre scripts). Hvis du bruger en eksisterende database, �nsker du m�ske at �ndre dette.',
	'prefix_note'=>'<b>OBS:</b> Et tabel pr�fiks kan kun indeholde tal og bogstaver. Mellemrun og symboler (inklusiv "_") er ikke tilladt. En "_" vil blive tilf�jet for dig af Exponent.',
	
	'default_content'=>'Standard eksempel indhold',
	'install'=>'Install�r eksempel indhold',
	'install_desc'=>'Til at hj�lpe med at forst� hvordan Exponent arbejder, og hvordan alt passer sammen, foresl�r vi at du installerer eksempel indholdet. Hvis du er ny til Exponent, opfordrer vi dig til at g�re dette.',
	
	'verify'=>'Verific�r konfigurationen',
	'verify_desc'=>'Efter at du er tilfreds med den information du har indtastet, klik p� "Test indstillinger" knappen nedenunder. Exponent Installations Guiden vil s� udf�re nogle indledende tests for at sikre at konfigurationen er gyldig.',
	'test_settings'=>'Test indstillinger',
);

?>