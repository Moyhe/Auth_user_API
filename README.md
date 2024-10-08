## Laravel 10 authentication RESTFUL API with jwt and docker

You have to register first in order to generate access token and make API requests.

## Installation with docker

1-Clone the project

    git clone https://github.com/Moyhe/Auth_user_API.git

2-Run composer install

Navigate into project folder using terminal and run

    docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs

3-Copy .env.example into .env

    cp .env.example .env

Start the project in detached mode

    ./vendor/bin/sail up -d

From now on whenever you want to run artisan command you should do this from the container. Access to the docker container

    ./vendor/bin/sail bash

5-Set encryption key

    php artisan key:generate --ansi

6-Run migrations

    php artisan migrate

7.to generate jwt secret use this command

    ./vendor/bin/sail artisan jwt:secret

8.Test the Application, run this command

    php artisan test
