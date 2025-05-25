<p align="center">
<img src="https://vatsim-germany.org/images/vacc_logo_white.png" width="400" alt="vatger"></img>
</p>

## Useful documentation

- Laravel https://laravel.com/docs
- Template https://shreethemes.in/landrick/landing/index.html
- Laravel Livewire https://livewire.laravel.com/docs/quickstart

## Installation

1. Clone this git repo `git clone https://github.com/vatger/homepage.git`
2. Install `php ^8.4`, `composer ^2`, `node`, `npm`
3. Setup a mysql database (e.g. `mariadb`)
4. copy `.env.example` to `.env` and edit its contents (maybe ask someone for the best settings)
5. in your console run
   1. `composer update`
   2. `npm update`
   3. `php artisan migrate`
   4. `php artisan db:seed`
   5. `npm run dev` or `npm run build`
6. to start the website run
   1. `php artisan serve --port=80`
   2. or set up your own php-fpm/cgi webserver according to the laravel documentation
7. in local development it may be helpful to point some dns domains `*.vatger.test` to your local ip address
8. the develop branch is auto published to https://dev.vatsim-germany.org/
9. before committing to git please run `npm run format:write` to format the code or set up your IDE to auto format using prettier

### Other things

The vACC Germany Website Code is closed source, due to legal reasons.
