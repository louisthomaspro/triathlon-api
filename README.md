<h1 align="center"><a href="https://api-platform.com"><img src="https://api-platform.com/logo-250x250.png" alt="API Platform"></a></h1>

# Triathlon API

Web applications allowing the management of stocks of several stores.
Several roles are available:

* Administrator (management of all stores)
* Store manager (product and seller management for his store)
* Seller (management of the quantity of stock in his store)

The front application is available [here](https://github.com/louisthomaspro/triathlon-front).

## Installation

1. Clone

``` sh
git clone https://github.com/louisthomaspro/triathlon-api
cd tirathlon-api
```

2. Run docker-compose

``` sh
docker-compose up -d
```

3. Load fixtures (mockdata)

``` sh
docker-compose exec php bin/console hautelook:fixtures:load --purge-with-truncate -n --no-bundles
```

4. Find the API Swagger documentation at : https://localhost:8443/api/v1/docs

5. Génerate a token

``` sh
curl -X POST \
  https://localhost:8443/api/v1/login \
  -k \
  -H 'Content-Type: application/json' \
  -H 'Host: localhost:8443' \
  -H 'cache-control: no-cache' \
  -d '{"email":"louisthomas.pro@gmail.com","password":"coucou"}'
```

5. You can test with multiple roles accounts (password always "coucou")
* Admin (email: louisthomas.pro@gmail.com)
* Store manager (email: user2@gmail.com)
* Seller (email: user3@gmail.com)

6. For a better experience, install [Triathlon-front](https://github.com/louisthomaspro/triathlon-front)

## Useful commands

Recreate the database

``` sh
docker-compose exec php bin/console --env=prod doctrine:schema:drop --full-database --force && docker-compose exec php bin/console doctrine:schema:update --force
```

Update the database

``` sh
docker-compose exec php bin/console doctrine:schema:update --force
```

Create/update an entity

``` sh
docker-compose exec php bin/console make:entity --api-resource
```

Problem of rigths ?

``` sh
sudo chown -R $(whoami) triathlon-api/
```

Clear you repo

``` sh
git rm --cached .-r
```

Generate manually the JWT files

``` sh
docker-compose exec php sh -c '
    set -e
    apk add openssl
    mkdir -p config/jwt
    jwt_passhrase=$(grep ''^JWT_PASSPHRASE='' .env | cut -f 2 -d ''='')
    echo "$jwt_passhrase" | openssl genpkey -out config/jwt/private.pem -pass stdin -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
    echo "$jwt_passhrase" | openssl pkey -in config/jwt/private.pem -passin stdin -out config/jwt/public.pem -pubout
    setfacl -R -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt
    setfacl -dR -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt

'
```

When deplying on a server (say yeeees)

``` sh
composer require symfony/apache-pack
```

## Heroku commands

You have to install [Heroku CLI](https://devcenter.heroku.com/articles/heroku-cli) for the next commands

Heroku without CI/CD (in 'api' folder)

``` sh
heroku run bash -a triathlon-api
php bin/console doctrine:schema:create
heroku config:set APP_ENV=prod
git add --all
git commit -a -m "My first API Platform app running on Heroku!"
heroku git:remote -a triathlon-api
git push heroku master
```

Heroku github login

``` sh
heroku auth:token
username: blank
password: token
```

## Load local data to Heroku

1. Make a backup

``` sh
docker-compose exec db pg_dump -U api-platform api --no-owner --no-acl -f backup.sql
```

2. Copie the sql file generated to local

``` sh
docker cp CONTAINER_ID:/backup.sql ./backup.sql
```

3. Load the sql file to the server
``` sh
heroku pg:psql --app triathlon-api < ./backup.sql 
```

