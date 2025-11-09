# 1. Cloner le dépôt et se positionner sur la branche de travail
git clone <URL_DU_VOTRE_DEPOT>
cd <NOM_DU_DEPOT>
git checkout develop

# Entrer dans le répertoire Back-End
cd backend

# 1. Installer les dépendances
composer install

# 2. Configurer les variables d'environnement (DB, REDIS)
cp .env.example .env
# Assurez-vous que DB_DATABASE, REDIS_HOST et REDIS_PASSWORD sont configurés.

# 3. Préparer l'application
php artisan key:generate
    
# 4. Lancer les migrations de la DB
php artisan migrate

# 5. Remplissage des données (CRUCIAL pour les tests de performance)
# Ceci crée les 10 sources et les 20 000+ articles nécessaires.
php artisan db:seed

# 6. Démarrer le serveur API (par défaut : http://127.0.0.1:8000)
php artisan serve