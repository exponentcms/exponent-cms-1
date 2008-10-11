<?php

return array(
	'title'=>'Erklärung der Systemvoraussetzungen',
	'header'=>'Die Überprüfungen werden durchgeführt um Probleme mit ihrer Serverkonfiguration zu verhindern. Diese Seite erklärt die einzelnen Tests und warum diese notwendig sind.<br /><br />Anmerkung: Bei den Lösungsvorschlägen, <span class="var">WEBUSER</span> wird der Username des verwendeten Webservers, und <span class="var">EXPONENT</span> und der volle Pfad zum Exponent Verzeichniss benutzt.',
	
	'filedir_tests'=>'Datei- und Verzeichnistests für Berechtigungen',
	
	'rw_server'=>'Müssen schreib- und lesebar durch den Webserver sein.',
	'unix_solution'=>'UNIX Lösung',
	
	'config.php'=>'In der conf/config.php Datei wird die Konfiguration für die aktuelle Site gespeichert. Dazu gehört die DB-Verbindung und das gewählte Thema.',
	'profiles'=>'Im conf/profiles Verzeichniss werden alle Konfigurationsangaben der Site abgelegt.  Auch wenn Sie nur ein Profil benutzen, muss der Webserver die Rechte haben um hier Dateien zu erstellen.',
	'overrides.php'=>'Die overrides.php wird bei der Installation benutzt um verschiedene Konstanten die von System geliefert werden darin festzuhalten. Diese Daten werden dann mit den realen Daten (ändern des voreingestellten Installationspfad) überschrieben. Nach der Installation wird diese Datei nicht mehr benötigt.',
	'install'=>'Das Install Verzeichnis beinhaltet alle Dateien des Exponent Installationsassistent. Sobald der Assistent einmal ausgeführt wurde, wird das Verzeichnis automatisch gelöscht. Dazu werden Schreibrechte für dieses Verzeichnis benötigt.',
	'modules'=>'Exponent testet die mitgelieferten Module um sicherzustellen das alles korrekt installiert werden kann. Falls es dabei zu Problemem kommt, wenden Sie sich bitte an das Deutsche Portal (<a href="http://www.exponent-portal.de/" target="_blank">http://www.exponent-portal.de/</a>).',
	'views_c'=>'Exponent verwendet Smarty um die Daten von der Darstellung zu trennen. Smarty Templates werden jedoch aus Geschwindigkeitsgründen in PHP kompiliert. Die erzeugten kompilierten Templates werden im Verzeichnis views_c gespeichert. Für dieses Verzeichnis muss der Webserver ebenfalls Schreibberechtigung haben.',
	'extensionuploads'=>'Wenn Sie das <b>Erweiterungen Hochladen</b> Tool im Administrator Control Panel benutzen, werden die Dateien zuerst im Verzeichnis <i>extensionuploads</i> temporär gespeichert. Deshalb benötigt der Webserver vollen Zugriff auf dieses Verzeichnis.',
	'files'=>'Alle hochgeladenen Content Dateien (Resourcen, importierte Daten, Bilder, etc.) werden im Verzeichnis <i>files/</i>  gespeichert. Der Webserver braucht dafür Schreib- und Leserechte.  Falls der Test negativ ausfällt sollten Sie die Berechtigungen manuell ändern.',
	'tmp'=>'Das Verzeichnis "tmp" wird von verschiedenene Exponent Komponenten zum vorübergehenden Speichern benutzt.',
	
	'other_tests'=>'Weitere Tests',
	'db_backend'=>'Datenbank',
	'db_backend_desc'=>'Exponent speichert den Inhalt der Webseiten in einer Relational Databank. Aus Portabilitäts Gründen wird ein Datenbankzugrifflayer dafür benutzt. Momentan unterstützt diese API nur MySQL und PostGreSQL. Wenn der Test fehlschlägt wurde keine PHP Untersützung für diese Datenbank festgestellt.',
	'gd'=>'GD Grafik Bibliothek',
	'gd_desc'=>'Verschiedenen Komponenten von Exponent benötigen die GD Graphics library für Bildermanipulationen. Exponent funktioniert aber auch ohne GD, leider verlieren Sie dann den Captcha Test und die Automatische Bildvorschauerzeugung. Eine GD-Version die mit  2.0.x kompatible ist, erstellt schärfere und bessere Bildvorschauen.',
	'php_desc'=>'Exponent benötigt für einige Funktionen zwingend eine PHP Version ab 4.0.6.',
	'zlib'=>'ZLib Support',
	'zlib_desc'=>'ZLib wird benötigt zum packen und entpacken der Tar und Zip Archive.',
	'xml'=>'XML (Expat Library)',
	'xml_desc'=>'Die Weberweiterung braucht die Expat Library. Fall Sie keine Webservices nutzen, können Sie die Warnungen ignorieren.',
	'safemode'=>'Safe Mode <b>Nicht aktiviert</b>',
	'safemode_desc'=>'Safe Mode ist der Versuch, Sicherheitsprobleme bei gemeinsam genutzten Servern zu lösen. Bezogen auf die Systemarchitektur, ist es der falsche Ansatz, diese Probleme innerhalb der PHP Schicht lösen zu wollen. Da es auf Ebene des Webservers bzw. des Betriebssystems keine praktischen Alternativen gibt, wird Safe Mode nunmehr von vielen Providern, eingesetzt.<br /><br />Wenn Sie diese Warnung ignorieren dann sollten Sie sicherstellen das ALLE Dateien einschlossen das Exponent package von selbem Systemuser verwaltet werden.',
	'safemode_req'=>'Exponent funktioniert am <b>B</b>esten wenn der Safe Mode <i>deaktiviert</i> ist.',
	'basedir'=>'Open BaseDir deaktiviert',
	'basedir_req'=>'Exponent funktioniert am besten wenn Open BaseDir deaktiviert ist.',
	'basedir_desc'=>'open_basedir ist eine Beschränkung für alle PHP-eigenen Dateisystemfunktionen. Dabei kann es zu Problemem mit den Exponent Dateien in <i>files\</i> Verzeichnis kommen. Das betrifft vor allem den Multi-Site-Manager. Sie können diese Meldung auf eigenes Risiko ignorieren.',
	'upload'=>'Datei Hochladen aktiviert',
	'upload_desc'=>'Server Administratoren können PHP uploads deaktivieren. Zusätzlich können schlecht konfigurierte Server Probleme beim Verarbeiten der hochgeladenen Dateien verursachen. Ohne eine Uploadmöglichkeit wird jedoch nur ein kleiner Teil der Fähigkeiten von Exponent benutzbar sein weil es keine Möglichkeit gibt z. B. Bilder oder Dateien hochzuladen.',
	'tempfile'=>'Temporäre Dateierstellung',
	'tempfile_desc'=>'Verschiedene Exponenterweiterungen müssen temporäre Dateien erstellen. Normallerweise zeigt diese Fehlermeldung auf Probleme bei der Verzeichnis Berechtigung für die <i>tmp/ </i>Datei und das Verzeichnis hin.',
);

?>