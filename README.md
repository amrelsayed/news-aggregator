# News Aggeragator API

A test project aggeregating data from external api's and store it in local db built with Laravel.

## Setup

### Add .env file

```sh
cp .env.example .env
```

### Install composer

```sh
composer install
```

### Run docker

```sh
docker-compose up
```

### Generate app key

```sh
./vendor/bin/sail artisan key:generate
```

### Run DB migrations and seeds

```sh
./vendor/bin/sail artisan migrate --seed
```

### App url

Your application is running on `http://localhost`

### Documentation

You can access documentation on `http://localhost/api/documentation`

### Unit tests

To run unit tests:

```sh
./vendor/bin/sail test
```

### Testing News API's

To use news api's on the local db you may want to run:

```sh
./vendor/bin/sail artisan news:fetch newsapi
```

`newsapi` service could be replaced with: `nytimes` or `guardian`
