<?php

return array(
	'title'=>'Forklaringer til systemkrav',
	'header'=>'Kvalitets kontrollerne er til stede for at sikre at problemer med server milj�et (fil rettigheder, PHP udvidelser, osv.) er egnet for installion af Exponent. Denne side forklarer hver enkelt kvalitetskontrol, hvorfor den udf�res, og hvordan man rekonfigurerer sin web server hvis en kontrol fejler.<br /><br />Bem�rk: I alle l�sninger er, <span class="var">WEBUSER</span> brugt som brugernavnet for brugeren der k�rer web serveren, og <span class="var">EXPONENT</span> bruges som den fulde sti til Exponent mappen.',
	
	'filedir_tests'=>'Test af fil og mappe retigheder',
	
	'rw_server'=>'Skal v�re l�se og skrivebar for web serveren',
	'unix_solution'=>'UNIX l�sninge',
	
	'config.php'=>'Conf/config.php filen gemmer den aktive konfiguration for sitet, inklusiv indstillinger for database forbindelse og valg af tema.',
	'profiles'=>'Conf/profiles mappen gemmer den gemte konfiguration for sitet. Selv om du ikke bruger mere end en profil, skal web serveren v�re i stand til at oprette filer i denne mappe.',
	'overrides.php'=>'Overrides.php filen bruges til at tilsides�tte konstanter der automatisk findes af Exponent. Hvis installationen finder et problem med nogle af de automatisk fundne v�rdier, vil den skrive de korrekte v�rdier i denne fil f�r den fuldf�rer installationen. Efter at du har installeret Exponent, beh�ver denne fil kun at v�re l�sbar af web serveren.',
	'install'=>'Install mappen indeholder alle filer til Exponent Installations Guiden. N�r f�rst du har g�et guiden igennem en gang, deaktiverer den sig selv automatisk (ved at fjerne install/not_configured filen). For at kunne g�re dette, beh�ver den skriverettigheder til install mappen. Efter installationen, beh�ves denne mappe ikke, s� du kan fjerne den eller s�tte rettighederne s�dan at webserveren ikke kan l�se den.',
	'modules'=>'Exponent k�rer nogle f� tjek af de installerede moduler for at sikre at der ikke sker noget m�rkeligt. Hvis denne test fejler, post venligst en support foresp�rgsel p� SourceForge projektsiden for Exponent (<a href="http://www.sourceforge.net/projects/exponent/" target="_blank">http://www.sourceforge.net/projects/exponent/</a>).',
	'views_c'=>'Exponent bruger Smarty til at adskille dens data process logik fra dens brugergr�nseflade logik. Smarty templates compileres fra Smarty syntax til r� PHP for hastighedens skyld, og de compilerede templates g�r alle i views_c mappen, som skal v�re skrivebar af web serveren.',
	'extensionuploads'=>'N�r du bruger Upload Udvidelse muligheden i Administrations Kontrol Panelet, placeres den uploadedede fil midlertidigt i extensionuploads mappen. Derfor beh�ver web serveren fuld adgang til denne.',
	'files'=>'Alle uploadede indoldsfiler (resourcer, importer data, billeder, osv.) gemmes i hjemmeside files/ mappen, som web serveren beh�ver fuld l�se og skrive adgang til. Hvis denne test fejler og du ikke mener den burde g�re det, s� husk at du rekursivt skal tildele l�se og skrive rettigheder til web server brugeren.',
	'tmp'=>'Tmp mappen bruges til forskellige dele af Exponent for midlertidige filer.',
	
	'other_tests'=>'Andre tests',
	'db_backend'=>'Database motor',
	'db_backend_desc'=>'Exponent gemmer al indholdet for din hjemmeside i en relational database. Af portabilitets �rsager bruges et specielt database abstraktionslag. P.t. supporterer dette abbstraktionslag kun MySQL og PostGreSQL. Hvis denne test fejler, s� fandtes der ikke PHP support for disse database motorer.',
	'gd'=>'GD Graphics Library',
	'gd_desc'=>'Forskellige dele af Exponent benytter GD Graphics library til billedfunktioner. Exponent kan k�re uden GD, men du vil miste funktioner som Captcha tests og automatisk thumbnails. En version af GD som er 2.0.x kompatibel giver skarpere og klarere thumbnails.',
	'php_desc'=>'Fordi nogle af de funktioner som Exponent bruger, er versioner af PHP �ldre end 4.0.6 ikke egnede. De fleste funktioner der ikke underst�ttes i senere versioner kan omg�es, men der er f� st�rre fejl, og funktioner som ikke kan re-implementeres i PHP �ldre end 4.0.6.',
	'zlib'=>'ZLib underst�ttelse',
	'zlib_desc'=>'ZLib bruges til underst�ttelse for arkiver, som Exponent bruger til at udpakke Tar og Zip arkiver.',
	'xml'=>'XML (Expat Library)',
	'xml_desc'=>'Web services udvidelserne for Exponent kr�ver Expat Library. Hvis du ikke bruger web services eller moduler der er afh�ngige af web services, er det sikkert at ignorere dette.',
	'safemode'=>'Safe Mode ikke aktiveret',
	'safemode_desc'=>'Safe Mode er en sikkerhedsm�ssig forholdregel der er tilstede i mange Shared Hosting milj�er. Det begr�nser PHP scripts fra at inkludere eller redigere filer der ikke er ejet af dette script\'s ejer. Dette kan for�rsage alvorlige og underfundige problemer der ser ud som fejl hvis ikke Exponent\'s filer er sat korrekt op.<br /><br />Hvis du beslutter at ignorere denne advarsel, s� v�r sikker p� at ALLE filer inkluderet i Exponent pakken er ejet af den samme system bruger.',
	'safemode_req'=>'Exponent virker bedst hvis Safe Mode er deaktiveret',
	'basedir'=>'Open BaseDir ikke aktiveret',
	'basedir_req'=>'Exponent virker bedst hvis Open BaseDir er deaktiveret',
	'basedir_desc'=>'Open_basedir restriktionen er en sikkerhedsm�ssig forholdsregel i mange Shared Hosting milj�er. Den begr�nser PHP scripts i at behandle filer udenfor en given mappe. Det kan for�rsage nogle problemer med nogle af Exponent\'s fil operationer, inklusiv Fler-side h�ndterings modulet. Ignorering af denne fejl er p� din egen risiko.',
	'upload'=>'Upload Af filer aktiveret',
	'upload_desc'=>'Server administratorer har muligheden for at deaktiver PHP uploads. Desuden kan forkert konfigurerede servere have problemer med at behandle uploadede filer. Uden evnen til at uploade filer, vil din oplevelse med Exponent blive st�rkt begr�nset, siden du ikke vil v�re i stand til at uploade ny kode, patches, billeder eller resourcer.',
	'tempfile'=>'Oprettelse af midlertidig fil',
	'tempfile_desc'=>'Forskellige dele af Exponent har brug for at oprette midlertidige filer for at fuldf�re en given opgave. S�dvanligvis er denne fejl relateret til ovenn�vnte test for "tmp/" fil og mappe rettigheder.',
);

?>