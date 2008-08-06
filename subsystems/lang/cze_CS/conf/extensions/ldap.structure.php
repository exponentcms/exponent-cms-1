<?php

return array(
	'title'=>'Nastavení LDAP autorizace',
	
	'use_ldap'=>'Zapnout LDAP autorizaci',
	'use_ldap_desc'=>'Zaškrtnutím této možnosti způsobíte, že se Exponent bdue snažit autorizovat proti LDAP serveru nastavenému níže.',
	
	'ldap_server'=>'LDAP Server',
	'ldap_server_desc'=>'Zadejte doménové jméno nebo IP adresu LDAP serveru.',
	
	'ldap_base_dn'=>'Základní DN',
	'ldap_base_dn_desc'=>'zadejte základní kontext pro toto LDAP připojení.',
	
	'ldap_bind_user'=>'Oprávněný LDAP uživatel',
	'ldap_bind_user_desc'=>'Uživatelské jméno nebo kontext pro připojení k LDAP serveru při provádění administrativních úkonů (tato možnost se zatím nepoužívá).',

	'ldap_bind_pass'=>'LDAP heslo',
	'ldap_bind_pass_desc'=>'Zadejte heslo pro uživatelské jméno/kontext uvedené výše.',
	
	'db_host'=>'Adresa serveru',
	'db_host_desc'=>'Doménové jméno nebo IP adresa databázového serveru. Pokud se jedná o lokální server, použijte "localhost".',
	
	'db_port'=>'Port serveru',
	'db_port_desc'=>'Port, na kterém naslouchá databázový server. Pro MySQL to je 3306.',
	
	'db_table_prefix'=>'Předpona tabulek',
	'db_table_prefix_desc'=>'Předpona přidávaná před názvy všech tabulek.',
	
	'db_encoding'=>'Kódování připojení k databázi',
	'db_encoding_desc'=>'Nastavuje kódování připojení. Podporováno od MySQL 4.1.12 a výše.'

);

?>
