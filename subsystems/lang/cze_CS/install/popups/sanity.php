<?php

return array(
	'title'=>'Vysvětlení systémových požadaků',
	'header'=>'Kontrola prostředí se provádí, abychom zabránili problémům s prostředím serveru (nastavení práv souborů, PHP rozšíření, atd.) a ujistili se o jeho použitelnosti pro instalaci Exponentu. tato stránka vysvětluje každý test prostředí, proč je prováděn, jak změnit nastavení Vašeho webserveru, jestliže některý test selže. <br /><br />Poznámka: Ve všech řešeních problémů  <span class="var">WEBUSER</span> je použito pro uživatelské jméno nebo uživatele, který vlastní procesy webserveru a<span class="var">EXPONENT</span> je použito pro absolutní (plnou) cestu k adresáři Exponentu.',
	
	'filedir_tests'=>'testy oprávnění souborů a adresářů',
	
	'rw_server'=>'Webserver musí mít oprávnění pro čtení a zápis',
	'unix_solution'=>'UNIXové řešení',
	
	'config.php'=>'Soubor conf/config.php obsahuje aktivní konfiguraci stránek, včetně nastavení připojení k databázi a tématu vzhledu.',
	'profiles'=>'Adresář conf/profiles obsahuje uložené konfigurace stránek. Ani pokud nepoužíváte více než jeden profil konfigurace, webserver musí mít práva pro vytvoření souborů v tomto adresáři.',
	'overrides.php'=>'Soubor overrides.php se používá pro vlastní nastavení konstant automaticky detekovaných Exponentem. Jestliže najde instalátor problém s některou automaticky detekovanou hodnotou, zapíše korektní hodnotu do tohoto souboru před dokončením instalace. Jakmile už je Exponent nainstalovaný, jsou potřebuje webserver pouze práva pro čtení.',
	'install'=>'Adresář install obsahuje všechny soubory Průvodce instalací Exponentu. Jakmile jste už jednou prošli tímto průvodcem, sám sebe deaktivuje (odstraněním souboru install/not_configured).   Aby to mohl provést, potřebuje webserver práva na zápis v adresáři install. Po instalaci už není tento adresář potřeba, proto ho můžete klidně smazat, nebo nastavit práva tak, že jej webserver nebude moci číst.',
	'modules'=>'Exponent provádí několik testů v soivislosti s instalovanými moduly, aby se ujistil, že se netalo nic zvláštního. Jestlože test selže, prosíme napište žádost o podoporu na stránkách projektu Exponent na SourceForge(<a href="http://www.sourceforge.net/projects/exponent/" target="_blank">http://www.sourceforge.net/projects/exponent/</a>).',
	'views_c'=>'Exponent používá Smarty pro oddělení zpracování dat od jejich zobrazování.  Šablony Smarty jsou pro urychlení zpracovány do čistého PHP a uloženy do adresáře views_c - proto musí mít webserver pro tento adresář práva na zápis.',
	'extensionuploads'=>'Používáte-li rozšíření pro natahování obsahu (lze ho nalézt v Administračním panelu), natažený archiv je dočasně umístěn do adresáře "extensionuploads". Proto zde potřebuje mít webserver plný přístup.',
	'files'=>'Všechna nahraná data (obrázky, importovaná data, různé jiné zdroje) se ukládají do adresáře "files", proto zde musí mít webserver práva pro zápis a čtení. Jestliže tento test selhává a Vy si myslíte, že by neměl, nezapomeňte, že práva musíte přiřadit rekurzivně.',
	'tmp'=>'Adresář tmp je je používám různými částmi Exponentu jako adresář pro dočasná data.',
	
	'other_tests'=>'Ostatní testy',
	'db_backend'=>'Databáze',
	'db_backend_desc'=>'Exponent používá pro ukládání veškerého obsahu Vašich stránek databázi. Z důvodů přenositelnosti je použita vlastní abstrakční vrstva. V současné době tato abstrakční vrstva podporuje pouze databáze MySQL a PostGreSQL. Selže-li tento test, nepodařilo se detekovat PHP podporu pro tyto databázové servery.',
	'gd'=>'Grafická knihovna GD',
	'gd_desc'=>'Různé části Exponentu používají knihovnu GD pro manipulaci s obrázky. Exponent může pracovat bez knihovny GD, ale ztratíte možnosti jako CAPTCHA test (brání robotům hromadně se registrovat) nebo automatické vytváření náhledů. Knihovna GD verze 2.0.x a kompatibilní Vám nabídne ostřejší a lepší náhledy.',
	'php_desc'=>'Kvůli některým funkcím, které Exponent používá není možné ho provozovat na serveru s PHP verze 4.0.6 a nižším. Většina funkcí je podporována, nebo existuje řešení, ale stále je zde několik významných chyb, které nemohou být vyřešeny s PHP 4.0.6 a nižším.',
	'zlib'=>'Podpora ZLib',
	'zlib_desc'=>'Knihovna ZLib je vyžadována pro podporu archivů - tzn. pro rozbalování archivů Tar a Zip.',
	'xml'=>'XML (Knihovna Expat)',
	'xml_desc'=>'Rozšíření webových služeb pro Exponent vyžaduje knihovnu Expat. Nepoužíváte-li webové služby nebo modul na nich závisející, můžete toto varování s klidem ignorovat.',
	'safemode'=>'Není povolen Safe Mode (bezpečný mód)',
	'safemode_desc'=>'Safe Mode je bezpečnostní měřítko použité v některých sdílených hostingových prostředích. Omezuje PHP skripty při vkládání [include] a upravování souborů, které nejsou vlastněny vlastníkem spouštěného skriptu. To může způsobit vážné i choulostivé problémy, které mohou vypadat jako problémy, když nejsou správně nastaveny soubory Exponentu.<br /><br />Rozhodnete-li se toto varování ignorovat, ujistěte se, že jsou všechny soubory Exponentu vlastněny týmž uživatelem.',
	'safemode_req'=>'Exponent funguje nejlépe tehdy, je-li Safe Mode vypnutý',
	'basedir'=>'Není povoleno Open BaseDir',
	'basedir_req'=>'Exponent works best when Open BaseDir is disabled',
	'basedir_desc'=>'Omezení open_basedir je bezpečnostní měřítko v některých sdílených hostingových prostředích. Omezuje PHP skripty při manipulaci se soubory mimo zadaný adresář. To může způsobit problémy s některými souborovými operacemi Exponentu, včetně Multi-stránkového manažeru. Toto varování ignorujte pouze na vlastní nebezpečí.',
	'upload'=>'Nahrávání souborů povoleno',
	'upload_desc'=>'Administrátoři serverů mají možnost vypnutí PHP nahrávání souborů. Navíc mohou mít špatně nastavené servery problémy se zpracováním nahraných souborů. Bez možnosti nahrávat soubory budou Vaše možnosti s Exponentem velmi omezené, protože nebudete moci nahrávat nový kód, opravy, obrázky nebo další zdroje.',
	'tempfile'=>'Vytváření dočasných souborů',
	'tempfile_desc'=>'Aby mohly různé části Exponentu fungovat, musí mít možnost vytvářet dočsné soubory. Většinou souvisí tato chyba s testem práv adresáře "tmp", který je zmíněn výše.',
);

?>