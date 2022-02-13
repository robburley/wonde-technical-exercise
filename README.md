##Wonde Technical Exercise

###Requirements
- Docker
- PHP 8.0+
- Composer

###Installation
- Once the repo is cloned, copy the `.env.example` as `.env` into the root directory
- Add `APP_PORT` and `FORWARD_DB_PORT` values as required if clashes with system ports, By default this project will run on port localhost:80
- Set `WONDE_TOKEN` in .env
- Run `composer install`
- Run `./vendor/bin/sail up` to build the containers
- Run `./vendor/bin/sail test` to perform tests

###Overview
Wonde Technical Exercise is a small API utilising Wonde's API to return data for a front end application to build a user interface to show a teacher which students are in their classes each day of the week so that I can be suitably prepared.

###Limitations
Currently the implementation does not group classes by their daily lessons due to time constraints.
