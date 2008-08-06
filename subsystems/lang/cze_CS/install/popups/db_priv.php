<?php

return array(
	'title'=>'Uživatelská práva k databázi',
	'header'=>'Když se Exponent připojuje k databázi, musí mít práva pro vykonání těchto SQL dotazů:',
	
	'create'=>'CREATE TABLE',
	'create_desc'=>'Tento dotaz vytvoří v databázi novou tabulku. Exponent tento dotaz používá při instalaci a také když nainstalujete nové moduly.',
	'alter'=>'ALTER TABLE',
	'alter_desc'=>'Tento dotaz se používá k úpravě struktury tabulky, pokud povýšíte [upgrade] kterýkoliv modul v Exponentu.',
	'drop'=>'DROP TABLE',
	'drop_desc'=>'Tento dotaz se vykonává kdykoliv administrátor ladí databázi, což znamená, že se odtraňují tabulky, které už nejsou používány.',
	'select'=>'SELECT',
	'select_desc'=>'Tyto dotazy jsou velmi důležité pro základní operace Exponentu. Všechna data uložená v databázi jsou zpět načítána právě pomocí dotazu SELECT.',
	'insert'=>'INSERT',
	'insert_desc'=>'Kdykoliv je na stránky přidán nový obsah, nebo jsou změněna práva uživatelů, popřípadě je vytvořen (onebo odstraněn) uživatel (nebo skupina) - jsou upravena data nastavení a Exponent vykonává dotaz INSERT.',
	'update'=>'UPDATE',
	'update_desc'=>'Kdykoliv je obsah nebo nastavení aktualizován, Exponent upravuje data v tabulkách pomocí dotazu UPDATE.',
	'delete'=>'DELETE',
	'delete_desc'=>'Tyto dotazy odstraňují obsah a nastavení z tabulek v databázi stránek. taky jsou vykonávány kdykoliv je odstraněn uživatel nebo skupina a jsou odvolávána práva.',
	
);

?>