Activity 6.2 : Exposer Une API Rest Avec Symfony

1-Clone the repository
https://github.com/gharianiemna/Module-6-REST-APIs.git

2-Move to the file of act 6.2 
cd Activity-6.2
cd Act-6.2

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

8-Go to Postman and select NEW button, then choose HTTP request.

9-Follow the routes:
find all articles:
- GET METHOD: /articles

find article by ID
- GET METHOD: /article/{id}

find last 3 articles:
-GET METHOD: /article/lastThree

- POST METHOD:/article

- PUT METHOD: /article/{id}

- DELETE METHOD: /article/{id}

10-Open the file of act 6.1.2
cd Activity-6.1.2

11-Install dependencies 
composer install

 12-Run the server
php bin/console server:run

9-Follow the routes:

Follow the routes:
- view articles: "/get"
- add article: "/post"
- update article: "/put"
- delete article: "/delete" or click on delete button under the article.
