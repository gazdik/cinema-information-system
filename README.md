# Reťazec multikín
----------------------------------------

Informačný systém pre reťazec multikín do predmetu Informačné systémy (IIS).

### Autori

- Peter Gazdík, xgazdi03@stud.fit.vutbr.cz
- Marek Bielik, xbieli05@stud.fit.vutbr.cz

### URL aplikácie

http://multikina.6f.sk/

## Užívatelia systému pre testovanie

| Login                | Heslo | Roľa               |
|----------------------|-------|--------------------|
| admin@mail.com       | pass  | Administrátor      |
| manager@mail.com     | pass  | Manažér kina       |
| cashier@mail.com     | pass  | Pokladník v kine   |
| user@mail.com        | pass  | Zákazník           |
| blockedUser@mail.com | pass  | Blokovaný zákazník |

## Inštalácia

### Inštalácia na server

- Presunieme sa do zložky `src/`
- Nakonfigurujeme parametre databáze v súbore `app/config/parameters.yml`
  - `composer install --no-dev --optimize-autoloader`
- Vymažeme cache a odstránime závislosti Asseticu
  - `php bin/console cache:clear --env=prod --no-debug`
  - `php bin/console assetic:dump --env=prod --no-debug`
- Aby bola stránka dostupná rovno po zadaní domény, zmeníme vo všetkých cestách v php súboroch v zložke `web/` podreťazec `/../` na `/` a následne môžeme celý obsah web skopírovať o úroveň vyššie príkazom
  - `cp -L -rf web/. ./`
- Súbory nahráme pomocou ftp na server.
- Importujeme databázu napr. pomocou phpmyadmin zo súboru `database.sql`

### Softwarové požiadavky

- PHP vo verzii 5.3.9 a vyššej
- Povolený JSON
- Povolený ctype

### Databáza

Inicializačný SQL skript databáze sa nachádza v súbore `database.sql`