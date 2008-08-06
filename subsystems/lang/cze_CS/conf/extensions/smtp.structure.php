<?php

return array(
	'title'=>'Nastavení SMTP serveru',
	
	'php_mail'=>'Používat PHP funkci mail()?',
	'php_mail_desc'=>'Pokud Vám nefunguje Exponentí způsob implementace čistého SMTP, buď kvůli problémům se serverem, nebo kvůli konfiguraci hostingu, zaškrtněte tuto možnost pro použití standartní PHP funkce mail().POZNÁMKA: Pokud tak učiníte, nemusíte už upravovat další nastavení SMTP, jelikož budou ignorována.',
	
	'server'=>'SMTP Server',
	'server_desc'=>'IP adresa nebo doména serveru, ke kterému se má Exponent připojit pro zaslání e-mailu pomocí SMTP.',
	
	'port'=>'Port',
	'port_desc'=>'Port, na kterém SMTP server naslouchá pro navázání SMTP připojení. Jestliže si nejste jisti, ponechejte výchozí hodnotu 25.',
	
	'auth'=>'Metoda autentifikace',
	'auth_desc'=>'Zde můžete určit, který typ autentifikace SMTP server vyžaduje (pokud nějaký vyžaduje). Pokud si nejste jisti, konzultujte tuto otázku s administrátorem vašeho mailserveru.',
	'auth_none'=>'Žádná autentifikace',
	'auth_plain'=>'PLAIN',
	'auth_login'=>'LOGIN',
	
	'username'=>'Uživatelské jméno SMTP',
	'username_desc'=>'Pomocí jakého uživatelského jména se má komunikovat se SMTP serverem, který vyžaduje určitou formu autentifikace',
	
	'password'=>'SMTP heslo',
	'password_desc'=>'Heslo použité pro komunikaci se SMTP serverem, který vyžaduje určitou formu autentifikace',
	
	'from_address'=>'Adresa Z:',
	'from_address_desc'=>'Adresa Z:, která se má použít při komunikaci se SMTP serverem. Toto je důležité pro lidi, kteří používají SMTP server svého ISP (poskytovatele Internetu), protože ti někdy omezují rozsah možných použitých adres.',
);

?>