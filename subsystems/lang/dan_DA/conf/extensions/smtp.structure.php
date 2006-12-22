<?php

return array(
	'title'=>'SMTP server indstillinger',
	
	'php_mail'=>'Brug PHP\'s mail() funktion?',
	'php_mail_desc'=>'Hvis Exponent implementeringen af rå SMTP ikke virker for dig, enten pga. server problemer eller hosting konfigurationen, aktiver denne funktion for at bruge standard mail() funktionen stillet til rådighed af PHP. BEMÆRK: At hvis du gør dette, behøver du ikke ændre nogen af de andre SMTP indstillinger, da de vil blive ignoreret.',
	
	'server'=>'SMTP server',
	'server_desc'=>'IP adresse eller host/domæne navn på den server der skal forbindes til for at sende e-mail gennem SMTP.',
	
	'port'=>'Port',
	'port_desc'=>'Port nummeret serveren lytter til for SMTP forbindelser. Hvis du ikke ved hvad dette er bør du lade den stå som standard: 25.',
	
	'auth'=>'Autorisations metode',
	'auth_desc'=>'her kan du specificere hvilken autorisations metode serveren kræver (hvis der kræves en). Venligst konsulter mailsever administratoren for at få denne information.',
	'auth_none'=>'Ingen autorisation',
	'auth_plain'=>'PLAIN',
	'auth_login'=>'LOGIN',
	
	'username'=>'SMTP brugernavn',
	'username_desc'=>'Brugernavnet der skal bruges ved opkobling til SMTP server der kræver autorisation',
	
	'password'=>'SMTP kode',
	'password_desc'=>'Koden der skal bruges ved opkobling til SMTP server der kræver autorisation',
	
	'from_address'=>'Fra adresse',
	'from_address_desc'=>'Den fra adresse der skal bruges når der kommunikeres med SMTP serveren. Dette er vigtigt for personer der bruger ISP SMTP servere, der kan begrænse adgang til bestemte e-mail adresser.',
);

?>