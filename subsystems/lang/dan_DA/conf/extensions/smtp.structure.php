<?php

return array(
	'title'=>'SMTP server indstillinger',
	
	'php_mail'=>'Brug PHP\'s mail() funktion?',
	'php_mail_desc'=>'Hvis Exponent implementeringen af r� SMTP ikke virker for dig, enten pga. server problemer eller hosting konfigurationen, aktiver denne funktion for at bruge standard mail() funktionen stillet til r�dighed af PHP. BEM�RK: At hvis du g�r dette, beh�ver du ikke �ndre nogen af de andre SMTP indstillinger, da de vil blive ignoreret.',
	
	'server'=>'SMTP server',
	'server_desc'=>'IP adresse eller host/dom�ne navn p� den server der skal forbindes til for at sende e-mail gennem SMTP.',
	
	'port'=>'Port',
	'port_desc'=>'Port nummeret serveren lytter til for SMTP forbindelser. Hvis du ikke ved hvad dette er b�r du lade den st� som standard: 25.',
	
	'auth'=>'Autorisations metode',
	'auth_desc'=>'her kan du specificere hvilken autorisations metode serveren kr�ver (hvis der kr�ves en). Venligst konsulter mailsever administratoren for at f� denne information.',
	'auth_none'=>'Ingen autorisation',
	'auth_plain'=>'PLAIN',
	'auth_login'=>'LOGIN',
	
	'username'=>'SMTP brugernavn',
	'username_desc'=>'Brugernavnet der skal bruges ved opkobling til SMTP server der kr�ver autorisation',
	
	'password'=>'SMTP kode',
	'password_desc'=>'Koden der skal bruges ved opkobling til SMTP server der kr�ver autorisation',
	
	'from_address'=>'Fra adresse',
	'from_address_desc'=>'Den fra adresse der skal bruges n�r der kommunikeres med SMTP serveren. Dette er vigtigt for personer der bruger ISP SMTP servere, der kan begr�nse adgang til bestemte e-mail adresser.',
);

?>