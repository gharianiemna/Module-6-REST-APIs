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

6-Upload fixtures into database
 php bin/console doctrine:fixtures:load

7-Installing JWT management bundle:
composer require lexik/jwt-authentication-bundle


8-Generate a public and private key with a passphrase to report in the .env (JWT_PASSPHRASE=TALANacademy)
mkdir -p config/jwt
openssl genrsa -out config/jwt/private.pem -aes256 4096 
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem

9-Run the server
php bin/console server:run

10-Login via "/login" path with these credentials: emna@talan.com + emna123

11-Go to Postman and select NEW button, then choose HTTP request.
Generate a token by POST METHOD and the following object in the body { "username" : "emna@talan.com", "password":"emna123"}

12-Follow the routes:

****without token****

find all articles:
- GET METHOD: articles

find article by ID
- GET METHOD: article/{id}

find last 3 articles:
-GET METHOD: article/lastThree

****with token****

In the header add a line:
Authorization  | bearer +token
In the body add the data:
  {
    "title": "test API",
    "body": "test",
    "author": "talan",
    "date": 26/07/2022
  }


- POST METHOD: api/article/

- PUT METHOD: api/article/{id}

- DELETE METHOD: api/article/{id}
