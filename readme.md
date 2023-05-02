# Instructions pour l'installation de l'application
# ===============================================
## Initialisation de l'application
ouvrir le fichier Database.php dans le dossier src et modifier les paramètres de connexion à la base de données
ouvrire le terminal et taper la commande suivante:
```php -S localhost:8000 MigrationInitiale.php```
ouvrir le navigateur et taper l'adresse suivante:
```localhost:8000```
couper le serveur en appuyant sur ctrl+c
```php -S localhost:8000 CallSql.php```'
ouvrir le navigateur et taper l'adresse suivante:
```localhost:8000```
si une erreur de type "This function has none of DETERMINISTIC, NO SQL, or READS SQL DATA..." apparait, utilisé la commande suivante dans le serveur SQL avant de relancé CallSql.php
```SET GLOBAL log_bin_trust_function_creators = 1;```
couper le serveur en appuyant sur ctrl+c
## Lancement de l'application
ouvrir le terminal et taper la commande suivante:
```php -S localhost:8000```
ouvrir le navigateur et taper l'adresse suivante:
```localhost:8000```
creer un compte  en cliquant sur le lien "m'inscrire"
se connecter avec le compte crée
## Utilisation de l'application
sur la page d'accueil, entre le nom de votre tamagotchi et cliquer sur "je créer mon tamagotchi"
maintenant vous pouvez jouer avec votre tamagotchi en cliquant sur les boutons
et essayer de le faire vivre le plus longtemps possible :).
