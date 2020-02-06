<h1 align="center"><a href="https://api-platform.com"><img src="https://api-platform.com/logo-250x250.png" alt="API Platform"></a></h1>


```sh
docker-compose exec php bin/console --env=prod doctrine:schema:drop --full-database --force && docker-compose exec php bin/console doctrine:schema:update --force

docker-compose exec php bin/console make:entity --api-resource

docker-compose exec php bin/console doctrine:schema:update --force

sudo chown -R louis monpanier-api/

git rm --cached . -r

docker-compose exec php bin/console hautelook:fixtures:load --purge-with-truncate -n --no-bundles


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



composer require symfony/apache-pack     ===> YYYYEEEEEESSSSSS

git add --all
git commit -a -m "My first API Platform app running on Heroku!"
heroku git:remote -a triathlon-api
git push heroku master

heroku config:set APP_ENV=prod

heroku run bash -a triathlon-api
php bin/console doctrine:schema:create

GITHUB
heroku auth:token
username: blank
password: token


pg_dump -U api-platform api --no-owner --no-acl -f backup.sql
docker cp CONTAINER_ID:/backup.sql /tmp/backup.sql
export DATABASE_URL=postgres://$(whoami)
heroku pg:psql --app triathlon-api < ./backup.sql 

```