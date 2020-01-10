<h1 align="center"><a href="https://api-platform.com"><img src="https://api-platform.com/logo-250x250.png" alt="API Platform"></a></h1>


Usefull commands : 

* Clear cache
```sh
docker-compose exec php bin/console cache:clear
```

* Update database
```sh
docker-compose exec php bin/console doctrine:schema:update --force
```

* Clear database
```sh
docker-compose exec php bin/console --env=prod doctrine:schema:drop --full-database --force && docker-compose exec php bin/console doctrine:schema:update --force
```