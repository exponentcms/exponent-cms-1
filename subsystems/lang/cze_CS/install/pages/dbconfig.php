<?php

return array(
	'subtitle'=>'Nastavení databáze',
	
	'in_doubt'=>'Jste-li na pochybách, kontaktujte Vašeho administrátora nebo poskytovatele hostingu.',
	'more_info'=>'Více informací',
	
	'server_info'=>'Informace o serveruServer Information',
	'backend'=>'Použitý software',
	'backend_desc'=>'Zvolte, jaký databázový software na Vašem serveru běží. Není-li tento software na seznamu, není Exponentem podporován.',
	
	'address'=>'Adresa',
	'address_desc'=>'Běží-li databázový server na jiném počítači, než je tento webserver, zadejte adresu databázového serveru. Může to být buď IP adresa (jako 1.2.3.4) nebo doménové jméno (jako priklad.cz).<br /><br />Pokud databázový server běží na stejném počítači jako webserver, ponechejte výchozí nastavení "localhost".',
	
	'port'=>'Port',
	'port_desc'=>'Pokud používáte databázový server, který podporuje TCP nebo jiné síťové protokoly a nachází se na jiném počítači než webserver, zadejte prosíme port, na kterém databázový server naslouchá.<br /><br />Zadali jste-li do pole Adresa "localhost", měli byste nechat výchozí nastavení.',
	
	'database_info'=>'Informace o databázi',
	'dbname'=>'Název databáze',
	'dbname_desc'=>'Toto je skutečné jméno databáze, tak jak ho zná databázový server. Konzultujte tuto informaci, prosíme, s administrátorem systému nebo poskytovatelem hostingu, jestliže si nejste jisti a nenastavovali jste databázi sami.',
	
	'username'=>'Uživatelské jméno',
	'username_desc'=>'Všechny databáze podporované Exponentem vyžadují určitý typ autentifikace. Zadejte jméno uživatelského účtu, kterým se má Exponent přihlašovat k databázovému serveru.',
	'username_desc2'=>'Ujistěte se, že daný uživatel má dostatečná oprávnění pro práci s databází.',
	
	'password'=>'Heslo',
	'password_desc'=>'Zadejte heslo k uživatelskému účtu, který jste zadali výše. Heslo <b>nebude</b> zašifrováno, protože nemůže být zašifrováno v konfiguračním souboru. Vývojáři Exponentu Vás vyzívají, bayste použili úplně nové heslo, odlišující se od ostatních - z bezpečnostních důvodů.',
	
	'prefix'=>'Předpona tabulek',
	'prefix_desc'=>'Předpona tabulek pomáhá Exponentu rozlišit tabulky těchto stránek od tabulek stránek jiných, které už mohou existovat (nebo být jindy vytvořeny dalšími skripty). Pokud používáte již existující databázi, možná budete chtít tuto hodnotu změnit. ',
	'prefix_note'=>'<b>Poznámka</b> Předpona tabulek obsahuje pouze čísla a písmena. Mezery a speciální znaky jako "_" nejsou povoleny. Podtržítko bude automaticky přidáno Exponentem.',
	
	'default_content'=>'Výchozí ukázkový obsah.',
	'install'=>'Instalovat ukázkový obsah',
	'install_desc'=>'Abychom Vám pomohli pochopit, jak Exponent funguje a jak všechny komponenty pracují, doporučujeme Vám, abyste nainstalovali přibalený ukázkový obsah. Toto doporučujeme obvzláště pokud ještě nemáte s Exponentme žádné zkušenosti.',

	'enable_sef'=>'Zapnout hezké adresy',
	'sef'=>'Hezké adresy',
	'sef_desc'=>'Povolte tuto možnost pokud chcete zapnout hezké adresy. POZOR: Ujistěte se, že je na tomto serveru zaplý Apache mod_rewrite, jinak tuto možnosz nezapínejte!',
	
	'verify'=>'Ověření konfigurace.',
	'verify_desc'=>'Jestliže jste spokojeni s nastaveními a jste si jisti tím, že jsou správná, klikněte na tlačítko "Vyzkoušet nastavení", které vidíte níže. Průvodce instalací Exponentu provede několik předběžných testů, aby se ujistil, že nastavení je v pořádku.',
	'test_settings'=>'Vyzkoušet nastavení',

	'DB_ENCODING'=>'Kování databáze',
	'DB_ENCODING_desc'=>'Tuto hodnotu neměňtě, pokud si nejste opravdu jisti, že víte, co děláte. Toto nastavení funguje jen pro MySQL 4.1.12 a vyšší.',
);

?>