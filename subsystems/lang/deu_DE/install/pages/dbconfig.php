<?php

return array(
	'subtitle'=>'Datenbank Konfiguration',
	
	'in_doubt'=>'Keine Ahnung? Bitte fragen Sie jemand der sich damit auskennt: Freunde, den Hoster oder das Deutsche Exponent Portal..',
	'more_info'=>'Weitere Informationen',
	
	'server_info'=>'Server Informationen',
	'backend'=>'Backend',
	'backend_desc'=>'W�hlen Sie ihren Datenbankserver aus. Sollte ihrer nicht dabei sein dann wird er vonn Exponent (noch) nicht unterst�tzt.',
	
	'address'=>'Adresse',
	'address_desc'=>'Falls ihr Datenbankserver und Webserver verschiedene IP-Adressen haben, geben Sie bitte hier die IP des Datenbankservers ein.  Sie k�nnen eine IP-Addresse(like 1.2.3.4) oder eine Internetdomain wie (exponentcms-portal.de) eingeben.<br /><br />Wenn sich ihre Datenbank und der Webserver auf den gleichen Rechner befinden, eicht die Auswahl von, "localhost".',
	
	'port'=>'Port',
	'port_desc'=>'Wenn Sie einen Datenbankserver mit TCP oder einen anderen Netzwerkprotoll benutzen, und Datenbankserver und Webserver laufen auf verschiedenen Hardwareumgebungen, m�ssen Sie den connection port eingeben.<br /><br />Fall Sie jedoch bereits "localhost" bei der Adresse eingegeben haben lassen Sie dies als Voreinstellung.',
	
	'database_info'=>'Datenbank Informationen',
	'dbname'=>'Datenbank Name',
	'dbname_desc'=>'Geben Sie den reallen namen der Datenbank an. Fall Sie das nicht wissen sollten Sie bei ihren Hoster nachfragen. Ansonsten verwenden Sie phpMyAdmin um den Namen der DB herauszufinden.',
	
	'username'=>'Benutzername',
	'username_desc'=>'Datenbankserver verlangen eine Autentifizierung. Geben Sie hier den Usernamen f�r die Datenbank ein.',
	'username_desc2'=>'Stellen Sie sicher das der User �ber die notwendigen Rechte verf�gt.',
	
	'password'=>'Passwort',
	'password_desc'=>'Zum User geh�rt ein Passwort das Sie bitte hier eingeben. Das Pa�wort wird <b>nicht</b> verdeckt, weil es ohnehin in die Konfigurationsdatei geschrieben wird. Als Exponent Entwickler bitten wir Sie ein komplette neues Pa�wort zu vergeben - eines das Sie bislang noch nie verwendet haben.',
	
	'prefix'=>'Tabellen Vorzeichen',
	'prefix_desc'=>'Datenbanken k�nnen sehr viele Tabellen verwalten. So ist es durchaus m�glich das unterschiedliche Webauftritte oder Exponentinstallationen in einer DB gespeichert werden. Wenn Sie Tabellenvorzeichen vergeben kann Exponent mehrer Installationen verwalten und auf die richtigen Tabellen zugreifen. .',
	'prefix_note'=>'<b>Anmerkung:</b> Verwenden Sie f�r Tabellenvorzeichen keine Sonderzeichen. Verwenden Sie nur Nummern und Buchstaben. Exponent f�gt den von ihnen eingebenen Tabellenvorzeichen noch einen Unterstrich (_) hinzu.',
	
	'default_content'=>'Beispiel Webauftritt',
	'install'=>'Installieren einer komplette Website',
	'install_desc'=>'Was man sehen kann - ist was man am schnellsten Versteht. Deshalb k�nnen Sie ein komplette Beispiel eines Webauftrittes der zugleich auch Dokumentation ist, installieren. Bitte tun Sie das auf alle F�lle wenn Exponent Neuland f�r sie ist.',
	
	'verify'=>'�berpr�fe Konfiguration',
	'verify_desc'=>'Nach der Angabe aller Daten klicken Sie einfach auf den "Teste die Einstellungen" Schalter. Der Exponentinstallasisstent f�hrt nun verschiedene Test durch um die Eingaben zu verifizieren..',
	'test_settings'=>'Teste die Einstellungen',
);

?>