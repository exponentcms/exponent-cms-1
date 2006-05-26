<?php

return array(
	'title'=>'Generelle-Site-Einstellungen',
	
	'site_title'=>'Titel der Site',
	'site_title_desc'=>'Titel der Website.',
	
	'use_lang'=>'Spracheinstellung',
	'use_lang_desc'=>'In welche Sprache soll Exponent angezeigt werden?',
	
	'allow_registration'=>'Registrierungen erlauben?',
	'allow_registration_desc'=>'Legt fest ob sich Benutzer registrieren dürfen.',
	
	'use_captcha'=>'CAPTCHA Spamtest aktiv?',
	'use_captcha_desc'=>'Benutzer müssen bei der Registrierung einen Zahlencode den Sie von einer Grafik ablesen, eingeben. Schutz vor Spamregistrationen.',
	'no_gd_support'=>'<div class="error">In ihrer Serverversion und/oder PHP-Konfiguration ist der notwendige GD-Support nicht aktiviert. Sie können deshalb den CAPTCHA-Test nicht nutzen.</div>',
	
	'site_keywords'=>'Suchwörter',
	'site_keywords_desc'=>'Suchwörter (Keywords) für diesen Webauftritt.',
	
	'site_description'=>'Beschreibung',
	'site_description_desc'=>'Geben Sie hier eine möglichst präzise Beschreibung ein die Ihren Webauftritt erklärt.',
	
	'site_404'=>'"Nicht gefunden" Fehlertext',
	'site_404_desc'=>'Der Benutzer bekommt diese HTML-Nachtricht angezeigt, wenn eine Seite oder ein Inhalt nicht gefunden wird. Ersetzen Sie den vorgegebenen Text mit ihren Eigenen',
	
	'site_403'=>'"Zugriff verweigert" Fehlertext',
	'site_403_desc'=>'Der Benutzer bekommt diese HTML-Nachricht angezeigt, wenn er versucht ein Aktion auszuführen für die er nicht die notwenige Berechtigung besitzt. Ersetzen Sie den vorgegebenen Text mit ihren Eigenen',
	
	'default_section'=>'Start Bereich',
	'default_section_desc'=>'Startseite von Exponent. Wählen Sie aus der Auflistung den Bereich Ihres Webauftritts aus der als erster im Browser erscheinen soll wenn ihre URL eingeben wird ',
	
	'session_timeout'=>'Verbindungseinstellung',
	'session_timeout_desc'=>'Geben Sie die Zeit in Sekunden ein nach der ein Benutzer automatisch wegen Inaktivität abgemeldet wird.',
	
	'timeout_error'=>'"Verbindung getrennt" Fehlertext',
	'timeout_error_desc'=>'Der Benutzer bekommt diese HTML-Nachricht angezeigt wenn seine Verbundung wegen Zeitüberschreitung getrennt wurde. Ersetzen Sie den vorgegebenen Text mit ihren Eigenen',
	
	'fileperms'=>'Voreinstellung der Dateiberechtigungen',
	'fileperms_desc'=>'Lese- und Schreibzugriff für Upload-Dateien, betrifft alle Benutzer außer dem Webserveruser.',
	
	'dirperms'=>'Voreinstellung der Verzeichnisberechtigungen',
	'dirperms_desc'=>'Lese- und Schreibzugriff zum Erstellen von Verzeichnissen. Betrifft Benutzer die nicht zur Gruppe der Webserveruser gehören.',
	
	'ssl'=>'Aktivierung der SSL Unterstützung',
	'ssl_desc'=>'Einstellen des Secure Linking mittels SSL. <a href="http://de.wikipedia.org/wiki/SSL" title="Erklärung">Erläuterung hier</a> ',
	
	'nonssl_url'=>'Standart URL',
	'nonssl_url_desc'=>'Komplette URL der Webseite (beginnt normalerweise mit "http://")',
	
	'ssl_url'=>'SSL URL Angaben',
	'ssl_url_desc'=>'Komplette URL der Webseite mit aktivierter SSL Unterstützung (beginnt normalerweise mit "https://")',
);

?>