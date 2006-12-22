<?php

return array(
	'title'=>'Generel side konfiguration',
	
	'site_title'=>'Side titel',
	'site_title_desc'=>'Hjemmesidens titel.',
	
	'use_lang'=>'Brugerflade sprog',
	'use_lang_desc'=>'Hvilket sprog skal Exponent brugerfladen benytte?',
	
	'allow_registration'=>'Tillad registrering?',
	'allow_registration_desc'=>'Hvorvidt brugere skal kunne registrere konti for sig selv.',
	
	'use_captcha'=>'Brug CAPTCHA test?',
	'use_captcha_desc'=>'En CAPTCHA (Computer Automated Public Turing Test to Tell Computers and Humans Apart) er en m�de at forhinde massive brugerregistreringer p�. N�r man registrerer en ny bruger konto, vil g�sten blive bedt om at indtaste en serie af bogstaver og tal fra et vist billede. Dette forhindrer scriptede bots i at oprette et massivt antal brugerkonti.',
	'no_gd_support'=>'<div class="error">Serveren\'s version og/eller konfiguration af PHP inkluderer ikke GD support, s� du har ikke mulighed for at bruge CAPTCHA test.</div>',
	
	'site_keywords'=>'N�gleord',
	'site_keywords_desc'=>'S�gemaskine n�gleord for hjemmesiden.',
	
	'site_description'=>'Beskrivelse',
	'site_description_desc'=>'En beskrivelse af hvad hjemmesiden er om.',
	
	'site_404'=>'"Ikke fundet" fejl tekst',
	'site_404_desc'=>'HTML der skal vises n�r en bruger foresp�rger p� noget der ikke kan findes (som en slettet post, sektion osv.)',
	
	'site_403'=>'"Adgang n�gtet" fejl tekst',
	'site_403_desc'=>'HTML der skal vises n�r en bruger fors�ger at g�re noget deres konto ikke har rettigheder til.',
	
	'default_section'=>'Standard sektion',
	'default_section_desc'=>'Standard sektion p� hjemmesiden.',
	
	'session_timeout'=>'Session udl�b',
	'session_timeout_desc'=>'Hvor lang tid en bruger kan v�re inaktiv (i sekunder) f�r de automatisk bliver logget ud.',
	
	'timeout_error'=>'"Session udl�bet" fejl tekst',
	'timeout_error_desc'=>'HTML der skal vises n�r en brugers session er udl�bet og de fors�ger at gennemf�re en operation der kr�ver at de har visse rettigheder.',
	
	'fileperms'=>'Standard fil rettigheder',
	'fileperms_desc'=>'L�se- og skriverettigheder for uploadede filer, for andre brugere end webserver brugeren.',
	
	'dirperms'=>'Standard mappe rettigheder',
	'dirperms_desc'=>'L�se- og skriverettigheder for oprettede mapper, for andre brugere end webserver brugeren.',
	
	'ssl'=>'Aktiver SSL support',
	'ssl_desc'=>'Hvorvidt Secure Linking gennem SSL skal v�re aktiveret eller ej',
	
	'nonssl_url'=>'Ikke-SSL URL base',
	'nonssl_url_desc'=>'Fuld URL p� hjemmesiden uden SSL support (starter normalt med "http://")',
	
	'ssl_url'=>'SSL URL base',
	'ssl_url_desc'=>'Fuld URL p� hjemmesiden med SSL support (starter normalt med "https://")',

	'revision_limit'=>'Revisions historie begr�nsning',
	'revision_limit_desc'=>'Det maksimale antal af st�rre revisioner (eksklusiv den "nuv�rende" revision) der skal gemmes pr. indholds element. En begr�nsning p� 0 (nul) betyder at alle revisioner bliver gemt.',
);

?>