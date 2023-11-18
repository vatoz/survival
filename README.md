survival
========

Aplikace pro hlasování na vip impro survival II.

později upravená pro survival III
a ještě později survival IV, následně V, následně 6


Základ je symfony aplikace ( bin/console server:start)
Asi nejjednodušší je poradit se s autorem

tipy
----
composer install 
nahraje závislosti

do
.env zadáme cestu k db

 bin/console doctrine:schema:create
 
 vytvoří databázi
 insert into user (username,roles,password) values('vatoz','{}','hasshh'); 
 
kde hasshh získáme
   bin/console security:hash-password

do public/borci dáme obrázky našich hráčů.

pak už se můžem přihlásit na /admin
a do player  je zadat.
taky můžeme vytvořit kostru hráčů pomocí /player/add/{Male}/{PlayerName} a pak je jen doplnit.







