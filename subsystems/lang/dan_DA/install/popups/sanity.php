<?php

return array(
	'title'=>'Forklaringer til systemkrav',
	'header'=>'Kvalitets kontrollerne er til stede for at sikre at problemer med server miljøet (fil rettigheder, PHP udvidelser, osv.) er egnet for installion af Exponent. Denne side forklarer hver enkelt kvalitetskontrol, hvorfor den udføres, og hvordan man rekonfigurerer sin web server hvis en kontrol fejler.<br /><br />Bemærk: I alle løsninger er, <span class="var">WEBUSER</span> brugt som brugernavnet for brugeren der kører web serveren, og <span class="var">EXPONENT</span> bruges som den fulde sti til Exponent mappen.',
	
	'filedir_tests'=>'Test af fil og mappe retigheder',
	
	'rw_server'=>'Skal være læse og skrivebar for web serveren',
	'unix_solution'=>'UNIX løsninge',
	
	'config.php'=>'Conf/config.php filen gemmer den aktive konfiguration for sitet, inklusiv indstillinger for database forbindelse og valg af tema.',
	'profiles'=>'Conf/profiles mappen gemmer den gemte konfiguration for sitet. Selv om du ikke bruger mere end en profil, skal web serveren være i stand til at oprette filer i denne mappe.',
	'overrides.php'=>'Overrides.php filen bruges til at tilsidesætte konstanter der automatisk findes af Exponent. Hvis installationen finder et problem med nogle af de automatisk fundne værdier, vil den skrive de korrekte værdier i denne fil før den fuldfører installationen. Efter at du har installeret Exponent, behøver denne fil kun at være læsbar af web serveren.',
	'install'=>'Install mappen indeholder alle filer til Exponent Installations Guiden. Når først du har gået guiden igennem en gang, deaktiverer den sig selv automatisk (ved at fjerne install/not_configured filen). For at kunne gøre dette, behøver den skriverettigheder til install mappen. Efter installationen, behøves denne mappe ikke, så du kan fjerne den eller sætte rettighederne sådan at webserveren ikke kan læse den.',
	'modules'=>'Exponent kører nogle få tjek af de installerede moduler for at sikre at der ikke sker noget mærkeligt. Hvis denne test fejler, post venligst en support forespørgsel på SourceForge projektsiden for Exponent (<a href="http://www.sourceforge.net/projects/exponent/" target="_blank">http://www.sourceforge.net/projects/exponent/</a>).',
	'views_c'=>'Exponent bruger Smarty til at adskille dens data process logik fra dens brugergrænseflade logik. Smarty templates compileres fra Smarty syntax til rå PHP for hastighedens skyld, og de compilerede templates går alle i views_c mappen, som skal være skrivebar af web serveren.',
	'extensionuploads'=>'Når du bruger Upload Udvidelse muligheden i Administrations Kontrol Panelet, placeres den uploadedede fil midlertidigt i extensionuploads mappen. Derfor behøver web serveren fuld adgang til denne.',
	'files'=>'Alle uploadede indoldsfiler (resourcer, importer data, billeder, osv.) gemmes i hjemmeside files/ mappen, som web serveren behøver fuld læse og skrive adgang til. Hvis denne test fejler og du ikke mener den burde gøre det, så husk at du rekursivt skal tildele læse og skrive rettigheder til web server brugeren.',
	'tmp'=>'Tmp mappen bruges til forskellige dele af Exponent for midlertidige filer.',
	
	'other_tests'=>'Andre tests',
	'db_backend'=>'Database motor',
	'db_backend_desc'=>'Exponent gemmer al indholdet for din hjemmeside i en relational database. Af portabilitets årsager bruges et specielt database abstraktionslag. P.t. supporterer dette abbstraktionslag kun MySQL og PostGreSQL. Hvis denne test fejler, så fandtes der ikke PHP support for disse database motorer.',
	'gd'=>'GD Graphics Library',
	'gd_desc'=>'Forskellige dele af Exponent benytter GD Graphics library til billedfunktioner. Exponent kan køre uden GD, men du vil miste funktioner som Captcha tests og automatisk thumbnails. En version af GD som er 2.0.x kompatibel giver skarpere og klarere thumbnails.',
	'php_desc'=>'Fordi nogle af de funktioner som Exponent bruger, er versioner af PHP ældre end 4.0.6 ikke egnede. De fleste funktioner der ikke understøttes i senere versioner kan omgåes, men der er få større fejl, og funktioner som ikke kan re-implementeres i PHP ældre end 4.0.6.',
	'zlib'=>'ZLib understøttelse',
	'zlib_desc'=>'ZLib bruges til understøttelse for arkiver, som Exponent bruger til at udpakke Tar og Zip arkiver.',
	'xml'=>'XML (Expat Library)',
	'xml_desc'=>'Web services udvidelserne for Exponent kræver Expat Library. Hvis du ikke bruger web services eller moduler der er afhængige af web services, er det sikkert at ignorere dette.',
	'safemode'=>'Safe Mode ikke aktiveret',
	'safemode_desc'=>'Safe Mode er en sikkerhedsmæssig forholdregel der er tilstede i mange Shared Hosting miljøer. Det begrænser PHP scripts fra at inkludere eller redigere filer der ikke er ejet af dette script\'s ejer. Dette kan forårsage alvorlige og underfundige problemer der ser ud som fejl hvis ikke Exponent\'s filer er sat korrekt op.<br /><br />Hvis du beslutter at ignorere denne advarsel, så vær sikker på at ALLE filer inkluderet i Exponent pakken er ejet af den samme system bruger.',
	'safemode_req'=>'Exponent virker bedst hvis Safe Mode er deaktiveret',
	'basedir'=>'Open BaseDir ikke aktiveret',
	'basedir_req'=>'Exponent virker bedst hvis Open BaseDir er deaktiveret',
	'basedir_desc'=>'Open_basedir restriktionen er en sikkerhedsmæssig forholdsregel i mange Shared Hosting miljøer. Den begrænser PHP scripts i at behandle filer udenfor en given mappe. Det kan forårsage nogle problemer med nogle af Exponent\'s fil operationer, inklusiv Fler-side håndterings modulet. Ignorering af denne fejl er på din egen risiko.',
	'upload'=>'Upload Af filer aktiveret',
	'upload_desc'=>'Server administratorer har muligheden for at deaktiver PHP uploads. Desuden kan forkert konfigurerede servere have problemer med at behandle uploadede filer. Uden evnen til at uploade filer, vil din oplevelse med Exponent blive stærkt begrænset, siden du ikke vil være i stand til at uploade ny kode, patches, billeder eller resourcer.',
	'tempfile'=>'Oprettelse af midlertidig fil',
	'tempfile_desc'=>'Forskellige dele af Exponent har brug for at oprette midlertidige filer for at fuldføre en given opgave. Sædvanligvis er denne fejl relateret til ovennævnte test for "tmp/" fil og mappe rettigheder.',
);

?>