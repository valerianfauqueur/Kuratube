# Kuratube
A curation website to find interesting youtube channels | School Project

## Requirements
- Symphony `3.2.*`

## Getting started

### Install

First clone the repository and install the dependencies:

```shell
$ git clone https://github.com/valerianfauqueur/project-symfony
$ cd project-symfony
$ composer install
```

Then start your MySQL server and initialize the database with the following command
```shell
$ php bin/console doctrine:database:create
$ php bin/console doctrine:schema:update --force
```

Finally start the server 

```shell
$ php bin/console server:run
```
