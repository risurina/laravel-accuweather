## Laravel - Accu Weather Integration

### Application Req:
* [PHP v8.1](https://www.php.net/)
* [Composer v2](https://getcomposer.org/)
* [Nodejs v16](https://nodejs.org/en/)

### Tech Used
* PHP\Laravel
* Laravel Jetstream
* Vuejs

### Quick Local Machine Setup
```
    # clone repository
    git clone https://github.com/risurina/laravel-accuweather

    # goto project directory
    cd laravel-accuweather

    # copy .env
    cp .env.example .env

    # set you accu weather api key
    opne .env -> ACCUWEATHER_API_KEY="Your Token"

    # install dependencies
    composer install
    yarn instal or npm install

    # setup you database
    touch database/database.sqlite # if you want to use sqlite as  database
    php artisan migrate

    # build frontend
    yarn build

    # run server
    php artisan serve
 ```
