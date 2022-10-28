# starter-laravel9

Starter project based on [Laravel](https://laravel.com/) 9 + [Docker](https://www.docker.com/), uses [Bootstrap](https://getbootstrap.com/) 5 + [Font Awesome](https://fontawesome.com/) 6 for styling.

## Development

You can use [Docker](https://www.docker.com/) to run the project quickly and easily. Or if you prefer flat usage, it's covered as well.

### Docker (Full)

If you have [Docker](https://www.docker.com/) installed, you don't need anything else to start building.
Simply download or clone the code and run below commands in project folder:

```shell
# start the services
$ docker-compose up -d -f docker-compose.yml -f docker-compose.full.yml

# spawn a shell in web container
$ docker-compose exec web bash

# install dependencies
$ composer install && yarn install

# create sample .env file
$ php -r "file_exists('.env') || copy('.env.docker', '.env');"

# set application key
$ php artisan key:generate

# compile static assets
$ yarn build

# prepare database
$ php artisan migrate --seed
```

### Docker (Partial)

If you have [Docker](https://www.docker.com/), [Node.js](https://nodejs.org/en/), [Yarn](https://yarnpkg.com/), [PHP](https://www.php.net/) and [composer](https://getcomposer.org/) installed on your workstation, you can run the web server (or queue worker) on host and other services e.g., [MySQL](https://www.mysql.com/), [Redis](https://redis.io/) etc. can be served through [Docker](https://www.docker.com/).
To get started, download or clone the code and run below commands in project folder:

```shell
# start the services
$ docker-compose up -d

# install dependencies
$ composer install && yarn install

# create sample .env file
$ php -r "file_exists('.env') || copy('.env.example', '.env');"

# set application key
$ php artisan key:generate

# compile static assets
$ yarn build

# prepare database
$ php artisan migrate --seed

# start the web server
$ php artisan serve
```

### Flat

To develop as a usual flat app, ensure you have [Node.js](https://nodejs.org/en/), [Yarn](https://yarnpkg.com/), [PHP](https://www.php.net/) and [composer](https://getcomposer.org/) or any other services e.g., [MySQL](https://www.mysql.com/), [Redis](https://redis.io/) etc. installed on your workstation.
Download or clone the code and run below commands in project folder when you have everything ready:

```shell
# install dependencies
$ composer install && yarn install

# create sample .env file
$ php -r "file_exists('.env') || copy('.env.example', '.env');"

# set application key
$ php artisan key:generate

# set the values in .env file
$ nano .env

# compile static assets
$ yarn build

# prepare database
$ php artisan migrate --seed

# start the web server
$ php artisan serve
```

## Extras

The [Docker](https://www.docker.com/) setup also include below services to ease local development:

- [MailCatcher](https://mailcatcher.me/) - to catch all outgoing emails, access on [http://localhost:8025](http://localhost:8025)
- [MinIO](https://min.io/) - an S3 compatible storage, access on [http://localhost:9091](http://localhost:9091)
- [phpMyAdmin](https://www.phpmyadmin.net/) - to manage SQL database, access on [http://localhost:8080](http://localhost:8080)
- [Redis Commander](http://joeferner.github.io/redis-commander/) - to manage Redis data, access on [http://localhost:8081](http://localhost:8081)

Some additional configuration described below may be needed for extended functionality.

### File uploads

Before uploading files, you may need to log in to [MinIO](https://min.io/) console at [http://localhost:9091/](http://localhost:9091/) using `starterapp` as both (username and password) and create a bucket named `starterapp`.
Once created, go to bucket's settings and change its **Access Policy** to `Public`.

## Deployment

You can deploy the project into production (using [Docker](https://www.docker.com/)) using below commands:

```shell
# build production container
$ docker build -t starter-laravel9 .

# push image to registry
$ docker push starter-laravel9
```
