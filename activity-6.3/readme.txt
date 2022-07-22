Leçon 6.3 : Sécuriser Son API Rest

1-Clone the repository
https://github.com/gharianiemna/Module-6-REST-APIs.git

2-Move to the file of act 6.3 
cd Activity-6.3
cd Act-6.3

3-Install dependencies 
composer install
 
4-Create the database
php bin/console doctrine:database:create

5-Run migrations
php bin/console doctrine:migrations:migrate

6- Run upload fixtures into database
 php bin/console doctrine:fixtures:load

7- Run the server
php bin/console server:run
