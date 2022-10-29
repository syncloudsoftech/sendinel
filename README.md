<h1 align="center">sendinel</h1>

<p align="center">
    Open-source alternative or clone of <a href="https://wetransfer.com/">wetransfer.com</a> to share large files easily over email
    or social media.
</p>

[![Screenshot](https://raw.githubusercontent.com/syncloudsoftech/sendinel/main/.github/screen-anim.gif)](.github)

## Development

You can use [Docker](https://www.docker.com/) to run the project quickly and easily.
In addition, make sure [Node.js](https://nodejs.org/en/), [Yarn](https://yarnpkg.com/), [PHP](https://www.php.net/) and [composer](https://getcomposer.org/) are also installed on your workstation.
Once you have everything, download or clone the code and run below commands in project folder:

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

## Extras

The setup also include below services to ease local development:

- [MailCatcher](https://mailcatcher.me/) - to catch all outgoing emails, access on [http://localhost:8025](http://localhost:8025)
- [MinIO](https://min.io/) - an S3 compatible storage, access on [http://localhost:9091](http://localhost:9091)
- [phpMyAdmin](https://www.phpmyadmin.net/) - to manage SQL database, access on [http://localhost:8080](http://localhost:8080)
- [Redis Commander](http://joeferner.github.io/redis-commander/) - to manage Redis data, access on [http://localhost:8081](http://localhost:8081)

Some additional configuration described below may be needed for extended functionality.

### File uploads

Before uploading files, you may need to log in to [MinIO](https://min.io/) console at [http://localhost:9091/](http://localhost:9091/) using `sendinel` as both (username and password) and create a bucket named `sendinel`.
Once created, go to bucket's settings and change its **Access Policy** to `Public`.
