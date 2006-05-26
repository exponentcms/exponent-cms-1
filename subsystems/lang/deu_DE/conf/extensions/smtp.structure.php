<?php

return array(
	'title'=>'SMTP Servereinstellung',
	
	'php_mail'=>'Verwenden der PHP mail() Funktion?',
	'php_mail_desc'=>'Wenn die Exponent Implementation einer einfachen SMTP nicht funktioniert, aktivieren Sie am Besten die PHP Mail Funktion.  Anmerkung: Bei Verwendung der PHP-MAIL-Funktion werden eventuell vorhandene SMTP-Einstellungen deaktiviert, bzw. ignoriert.',
	
	'server'=>'SMTP Server',
	'server_desc'=>'IP-Adresse oder Namen der Domain. (z.B. smtp.googlemail.com)',
	
	'port'=>'Port',
	'port_desc'=>'Der SMTP Server Port. (Standart ist 25.)',
	
	'auth'=>'Autentifizierung',
	'auth_desc'=>'Geben Sie an welche Autentifizierungart verwendet werden soll.',
	'auth_none'=>'Keine',
	'auth_plain'=>'PLAIN',
	'auth_login'=>'LOGIN',
	
	'username'=>'SMTP Benutzername',
	'username_desc'=>'Der Benutzername für die Verbindung zum SMTP-Server',
	
	'password'=>'SMTP Kennwort',
	'password_desc'=>'Das Kennwort für den Zugriff auf den SMTP-Server',
	
	'from_address'=>'Von welcher E-mailadresse?',
	'from_address_desc'=>'E-Mailadresse des Absenders die an den SMTP-Server übergeben wird. Wichtig für alle Benutzer die einen ISP-SMTP-Server benutzen, weil diese meist die Annahme von Mails mit unbekannten Absendern verweigern.',
);

?>