[![Maintainability](https://api.codeclimate.com/v1/badges/d677a13732b368ff06a5/maintainability)](https://codeclimate.com/github/OlivierFL/snowtricks/maintainability)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=OlivierFL_snowtricks&metric=alert_status)](https://sonarcloud.io/dashboard?id=OlivierFL_snowtricks)

# SnowTricks

Project 6 - Snowboard site built with Symfony Framework.

## Requirements

Mandatory :

- `PHP >= 7.4`
- `Symfony CLI`
- `npm` or `yarn`

Optional :

- `Make` to use the [_Makefile_](./Makefile) and custom commands
- `Docker` and `Docker-compose` for _MySQL_ database and _PhpMyAdmin_ containers
- `MailDev` to catch emails (registration and lost password), or another mailcatcher

Unit Tests :

- `PHPUnit`

## Installation

1. To get this project on your local machine, simply clone this repository :
   ```shell
   git clone git@github.com:OlivierFL/snowtricks.git
   ```


2. Install the dependencies :

- `composer install`
- `npm install`

  or if _Make_ is installed on your machine :

- `make deps-install` will run the above commands automatically.


3. Environment configuration :

   To configure local dev environment, create a `.env.local` file at the root of the project.

   To configure database connection, override the `DATABASE_URL` env variable with your database credentials and database name, for example :

    ```dotenv
    DATABASE_URL="mysql://root:root@127.0.0.1:3306/snowtricks?serverVersion=5.7"
    ```

   If you're using the _MySQL_ Docker container, the config is :

    ```dotenv
    DATABASE_URL="mysql://root:admin@127.0.0.1:3306/snowtricks"
    ```

   To enable sending registration and lost password emails, add in the `.env.local` file the `MAILER_DSN` env variable, for example with _MailDev_ :

    ```dotenv
    MAILER_DSN="smtp://localhost:1025"
    ```

   In the `.env` file, 2 variables are available, to configure the default number of tricks displayed on homepage, and the number of comments displayed on each trick detail page :

    ```dotenv
    TRICKS_LIST_LIMIT=8
    COMMENTS_LIST_LIMIT=4
    ```

4. After configuring the database connection, run `bin/console doctrine:database:create` to create the database.


5. Import the [example data set](#sample-data) available in the [dump](./dump/snowtricks.sql) directory in the database. If you're using _Docker_, _PhpMyAdmin_ is available on `localhost:8080` (_user_ : `root`, _password_ : `admin`).


6. Build the assets for development with `npm run dev`, or `npm run watch` to recompile every time a file is changed (CSS or Javascript). To build for production, run `npm run build`. The documentation for WebpackEncore is available
   on [Symfony documentation page](https://symfony.com/doc/current/frontend.html).


7. Start the Symfony server with `symfony server:start`.

The homepage is available on : `localhost:8000`.

## Usage

List of useful commands to use the project :

- `symfony server:start` to start the Symfony server
- `symfony server:stop` to stop the Symfony server
- `npm run dev` to build the assets for dev environment
- `npm run watch` to re-build the assets every time a CSS or Javascript file is modified
- `npm run build` to build the assets for production
- `maildev --hide-extensions STARTTLS` to start _MailDev_, if installed, available on `localhost:1080`

Commands to use with _Docker_ and _Make_ (commands are available in _Makefile_ at the root of the project) :

- `make up` to start _Docker_ stack (_MySQL_ and _PhpMyAdmin_) and _Symfony server_
- `make up-dev` to start _Docker_ stack (_MySQL_ and _PhpMyAdmin_) and _Symfony server_ and build assets for dev environment
- `make up-watch` to start _Docker_ stack (_MySQL_ and _PhpMyAdmin_) and _Symfony server_, build assets for dev environment and watch files changes
- `make mail` to start _MailDev_, available on `localhost:1080`
- `make down` to stop _Docker_ stack (_MySQL_ and _PhpMyAdmin_) and _Symfony server_

## Sample data

In order to have a fully functional application, the SQL file contains :

- 2 users with different states :
    - an admin user with __admin@example.com__ _email_, __admin__ _username_ and __Admin1234__ _password_. This user has role admin and can create, edit and delete any snowboard tricks, post comments and moderate comments in admin dashboard.

    - a simple user with __test@example.com__ _email_, __test__ _username_ and __Test1234!__ _password_. This user can create and edit any tricks, delete his own tricks, post comments.

- A default list of Tricks with some example media and comments.

## Third party libraries

Packages and bundles used in this project :

- [FOSJsRoutingBundle](https://github.com/FriendsOfSymfony/FOSJsRoutingBundle)
- [KnpPaginatorBundle](https://github.com/KnpLabs/KnpPaginatorBundle)
- [RollerWorks PasswordStrengthBundle](https://github.com/rollerworks/PasswordStrengthBundle)
- [SymfonyCasts ResetPasswordBundle](https://github.com/SymfonyCasts/reset-password-bundle)
- [SymfonyCasts VerifyEmailBundle](https://github.com/SymfonyCasts/verify-email-bundle)
- [WebpackEncore](https://symfony.com/doc/current/frontend.html) for building _assets_ (`CSS` and `Javascript` files)
- [TailwindCSS](https://tailwindcss.com/) as _CSS framework_

## Docker (optional)

This project uses Docker for _MySQL_ database and _PhpMyAdmin_.

The stack is composed of 2 containers :

- mysql
- phpMyAdmin

The configuration is available in the [docker-compose.yaml](./docker-compose.yaml).

## Catching emails (optional)

Instead of sending emails to a real email address, a mailcatcher can be used. For this project, I used _MailDev_, installed locally, to catch emails.

The Symfony `MAILER_DSN` env variable configuration for _MailDev_ is available in the [installation](#installation) part.

## Tests

_PhpUnit_ is used to run the tests.

In a terminal, at the root of the project, run `vendor/bin/phpunit`.

## Code quality

Links to code quality tools used for this project:

Codeclimate : https://codeclimate.com/github/OlivierFL/snowtricks

SonarCloud : https://sonarcloud.io/dashboard?id=OlivierFL_snowtricks
