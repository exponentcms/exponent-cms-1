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
	'use_captcha_desc'=>'En CAPTCHA (Computer Automated Public Turing Test to Tell Computers and Humans Apart) er en måde at forhinde massive brugerregistreringer på. Når man registrerer en ny bruger konto, vil gæsten blive bedt om at indtaste en serie af bogstaver og tal fra et vist billede. Dette forhindrer scriptede bots i at oprette et massivt antal brugerkonti.',
	'no_gd_support'=>'<div class="error">Serveren\'s version og/eller konfiguration af PHP inkluderer ikke GD support, så du har ikke mulighed for at bruge CAPTCHA test.</div>',
	
	'site_keywords'=>'Nøgleord',
	'site_keywords_desc'=>'Søgemaskine nøgleord for hjemmesiden.',
	
	'site_description'=>'Beskrivelse',
	'site_description_desc'=>'En beskrivelse af hvad hjemmesiden er om.',
	
	'site_404'=>'"Ikke fundet" fejl tekst',
	'site_404_desc'=>'HTML der skal vises når en bruger forespørger på noget der ikke kan findes (som en slettet post, sektion osv.)',
	
	'site_403'=>'"Adgang nægtet" fejl tekst',
	'site_403_desc'=>'HTML der skal vises når en bruger forsøger at gøre noget deres konto ikke har rettigheder til.',
	
	'default_section'=>'Standard sektion',
	'default_section_desc'=>'Standard sektion på hjemmesiden.',
	
	'session_timeout'=>'Session udløb',
	'session_timeout_desc'=>'Hvor lang tid en bruger kan være inaktiv (i sekunder) før de automatisk bliver logget ud.',
	
	'timeout_error'=>'"Session udløbet" fejl tekst',
	'timeout_error_desc'=>'HTML der skal vises når en brugers session er udløbet og de forsøger at gennemføre en operation der kræver at de har visse rettigheder.',
	
	'fileperms'=>'Standard fil rettigheder',
	'fileperms_desc'=>'Læse- og skriverettigheder for uploadede filer, for andre brugere end webserver brugeren.',
	
	'dirperms'=>'Standard mappe rettigheder',
	'dirperms_desc'=>'Læse- og skriverettigheder for oprettede mapper, for andre brugere end webserver brugeren.',
	
	'ssl'=>'Aktiver SSL support',
	'ssl_desc'=>'Hvorvidt Secure Linking gennem SSL skal være aktiveret eller ej',
	
	'nonssl_url'=>'Ikke-SSL URL base',
	'nonssl_url_desc'=>'Fuld URL på hjemmesiden uden SSL support (starter normalt med "http://")',
	
	'ssl_url'=>'SSL URL base',
	'ssl_url_desc'=>'Fuld URL på hjemmesiden med SSL support (starter normalt med "https://")',

	'revision_limit'=>'Revisions historie begrænsning',
	'revision_limit_desc'=>'Det maksimale antal af større revisioner (eksklusiv den "nuværende" revision) der skal gemmes pr. indholds element. En begrænsning på 0 (nul) betyder at alle revisioner bliver gemt.',
);

?>