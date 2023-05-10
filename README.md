<p align="center">
<img src="https://vatsim-germany.org/images/vacc_logo_white.png" width="400" alt="vatger"></img>
</p>

## Useful documentation

-   Laravel https://laravel.com/docs
-   Template https://shreethemes.in/landrick/landing/index.html
-   Laravel Livewire https://laravel-livewire.com/docs/2.x/quickstart

## Installation

1. Clone this git repo `git clone https://git.vatsim-germany.org/website/vacc-germany-website.git && git checkout develop`
2. Init and pull the submodules `git submodule init && git submodule update`
3. Install `php`, `composer`, `node`, `npm`
4. Setup a mysql database (e.g. `mariadb`)
5. copy `.env.example` to `.env` and edit its contents (maybe ask someone for the best settings)
6. in your console run
    1. `composer update`
    2. `npm update`
    3. `php artisan migrate`
    4. `php artisan db:seed`
    5. `npm run dev`
7. to start the website run
    1. `php artisan serve --port=80`
    2. or set up your own php-fpm/cgi webserver according to the laravel documentation
8. in local development it may be helpful to point some dns domains `*.vatger.test` to your local ip address
9. the develop branch is auto published to https://dev.vatsim-germany.org/
10. before committing to git please run `npm run prettier:write:all` to format the code or set up your IDE to auto format using prettier

### Other things

The vACC Germany Webservices Code is closed source only available for active developers.
