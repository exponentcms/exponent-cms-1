<?php

return array(
	'title'=>'Obecná konfigurace stránek',

	'org_name'=>'Jméno organizace',
        'org_name_desc'=>'Jméno společnosti nebo organizace. Je použito na několikati místech v systému.',
	
	'site_title'=>'Titulek stránek',
	'site_title_desc'=>'Titulek webových stránek.',
	
	'use_lang'=>'Jazyk rozhraní',
	'use_lang_desc'=>'Jaký jazyk má být použit pro uživatelské rozhraní?',
	
	'allow_registration'=>'Povolit registraci?',
	'allow_registration_desc'=>'Zda povolit návštěvníkům, aby si vytvořili sami nový uživatelský účet.',
	
	'use_captcha'=>'Pouřívat CAPTCHA test?',
	'use_captcha_desc'=>'CAPTCHA test (Computer Automated Public Turing Test to Tell Computers and Humans Apart = test pro oddělení lidí a robotů) má zabránit hromadné registraci uživatelů. Když si návštěvník vytváří nový účet, bude požádát o přepsání řady písmen a čísel zobrazených na obrázku. Toto zabrání robotům, aby vytvářely velká množství účtů.',
	'no_gd_support'=>'<div class="error">Verze serveru a/nebo konfigurace PHP neobsahuje podporu knihovny GD, takže nebude možné zapnout a používat CAPTCHA test.</div>',
	
	'site_keywords'=>'Klíčová slova',
	'site_keywords_desc'=>'Klíčová slova k této stránce - určené pro vyhledávací programy (například Google a podobně).',
	
	'site_description'=>'Popis',
	'site_description_desc'=>'Popis, o čem stránky jsou.',
	
	'site_404'=>'Chybová hláška "Nenalezeno"',
	'site_404_desc'=>'HTML kód, který se uživateli zobrazí, pokusí-li se otevřít obsah, který neexistuje (odstraněný příspěvek, sekce, atd.)',
	
	'site_403'=>'Chybová hláška "Přístup odepřen"',
	'site_403_desc'=>'HTML kód, který se uživateli zobrazí, pokusí-li se provést operaci, ke které nemá dostatečná oprávnění (přístup do zakázané sekce, atd.).',
	
	'default_section'=>'Výchozí sekce',
	'default_section_desc'=>'Výchozí sekce.',

	'enable_session_timeout'=>'Aktivovat časový limit sezení',
	'enable_session_timeout_desc'=>'Toto aktivuje nebo deaktivuje platnost časového limitu sezení.',
	
	'session_timeout'=>'Časový limit sezení',
	'session_timeout_desc'=>'Jak dlouho (v sekundách) může být uživatel nečinný, než bude automaticky odhlášen.',
	
	'timeout_error'=>'Chybová hláška "Časový limit sezení vypršel"',
	'timeout_error_desc'=>'HTML kód, který se uživateli zobrazí, jestliže jeho sezení vyprší a on se pokouší provést akci, která vyžaduje příslušná práva.',
	
	'fileperms'=>'Výchozí práva souborů',
	'fileperms_desc'=>'Právo na zápis/čtení pro nahrané soubory, pro uživatele jiné, než je uživatel webserveru.',
	
	'dirperms'=>'Výchozí práva adresářů',
	'dirperms_desc'=>'Právo na zápis/čtení pro nahrané adresáře, pro uživatele jiné, než je uživatel webserveru.',
	
	'ssl'=>'Povolit podporu SSL',
	'ssl_desc'=>'Zda-li povolit podporu pro SSL šifrovaný přenos',
	
	'nonssl_url'=>'Ne-SSL základní URL adresa',
	'nonssl_url_desc'=>'Plná adresa pro komunikaci se serverem bez zabezpečení (SSL) - většinou začíná na http://)',
	
	'ssl_url'=>'SSL základní URL adresa',
	'ssl_url_desc'=>'Plná adresa pro komunikaci se serverem s zabezpečením (SSL) - většinou začíná na https://)',

	'revision_limit'=>'Limit historie revizí',
	'revision_limit_desc'=>'Nejvyšší počet hlavních revizí (nepočítá se současná revize), které se mají uchovávat na jeden modul s obsahem.  Limit 0 (nula) znamená, že všechny revize budou ponechány...',

	'enable_workflow'=>'Povolit pracovní postupy',
	'enable_workflow_desc'=>'Vypíná a zapíná podporu pracovních postupů. Ponechejte vypnuté, pokud se je doopravdy nechystáte využívat, protože zapnuté pracovní postupy snižují výkon systému.',

	'wysiwyg_editor'=>'HTML Editor',
	'wysiwyg_editor_desc'=>'Vyberte, jaký HTML editor má být použit jako výchozí na těchto stránkách.',
);

?>