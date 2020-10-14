# BistrotHouse_back

Description en cours

## Installation

Cloner le projet.

Mettre à jour les dépendances en saisissant en console :

    composer install
    
Créer un fichier .env.local et saisir à l'interieur l'URI de la base de donnée

    DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=8.0.18

Créer automatiquement une base de donnée en fonction des parametres saisis dans le .env.local :

    php bin/console doctrine:schema:create
    
Mettre à jour les tables :

    php bin/console doctrine:migrations:migrate