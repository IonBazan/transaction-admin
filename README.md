# Transaction Admin
[![PHP 8.0+](https://img.shields.io/badge/PHP-8.0+-informational.svg)](http://laravel.com)
![Tests](https://github.com/IonBazan/transaction-admin/workflows/Tests/badge.svg)
[![codecov](https://codecov.io/gh/IonBazan/transaction-admin/branch/master/graph/badge.svg)](https://codecov.io/gh/IonBazan/transaction-admin)

This application provides a simple dashboard to manage companies and transactions

# Installation

Prerequisites:
- PHP 8.0+ with `mongodb` extension
- Composer
- MongoDB server

```shell
composer install # Install dependencies
bin/console doctrine:mongodb:fixtures:load -n
```

## Using Docker-Compose

This app is shipped with easy to run Docker-Compose environment built on top of PHP 8 with Symfony CLI and MongoDB container. 

```shell
cd docker
cp .env.dist .env # Customize expposed ports
docker-compose up # Run the app
```

## Locally using Symfony CLI

Another easy way to run the app is to install [Symfony CLI](https://symfony.com/download) and run `symfony serve`. Please note that it requires you to install PHP and MongoDB locally.

# Loading sample data

To import some dummy data for testing, you may want to run:

```shell
bin/console doctrine:mongodb:fixtures:load -n
```

# Accessing the application

Application should be available at http://localhost:8000.
To login, use test credentials:
 - username: `admin`
 - password: `admin123`

These credentials are currently stored using Symfony InMemoryUserProvider (see [config/packages/security.yaml](https://github.com/IonBazan/transaction-admin/blob/master/config/packages/security.yaml)) just for sake of simplicity. This can be moved to a MongoDB UserProvider or any other implementation for production use.

# Running tests

Application test suite consists mostly of functional tests because most of the logic is a simple CRUD. This can be refactored to use less framework-specific features and therefore allow to be unit-tested.

This repository uses Github Actions CI to run the test automatically. If you want to run them locally, simply execute:
```shell
bin/phpunit
```

Please note that it will purge your MongoDB database by default. You may avoid that by customizing database name in `.env.test.local`.

