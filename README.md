<p align="center">
<img src="https://vatsim-germany.org/images/brand/logo-email-dark.png" width="400" alt="vatger"></img>
</p>

## Technology

- PHP ^8.5, Laravel 13 and Livewire 4
- Tailwind CSS 4 with Vite
- TypeScript for interactive UI and page-specific integrations
- Sass modules for public, administration and email styling

## License

The original software code in this repository is licensed under the [MIT
License](LICENSE).

The website's text, documentation content, logos, trademarks, names, visual
identity, fonts, photographs, illustrations, maps, and other media or
branding assets are not covered by the MIT License. Unless a separate license
or permission says otherwise, those materials remain reserved by their
respective rights holders and may not be copied, modified, or redistributed
without prior written permission.

Useful documentation:

- [Laravel](https://laravel.com/docs)
- [Livewire](https://livewire.laravel.com/docs)
- [Tailwind CSS](https://tailwindcss.com/docs)
- [Vite](https://vite.dev/guide/)

## Installation

1. Clone this git repo `git clone https://github.com/vatger/homepage.git`
2. Install `php ^8.4`, `composer ^2`, `node`, `npm`
3. Setup a mysql database (e.g. `mariadb`)
4. Copy `.env.example` to `.env` and configure it for your environment.
5. In your console run
   1. `composer install`
   2. `npm install`
   3. `php artisan migrate`
   4. `php artisan db:seed`
   5. `npm run dev` or `npm run build`
6. to start the website run
   1. `php artisan serve --port=80`
   2. or set up your own php-fpm/cgi webserver according to the laravel documentation
7. in local development it may be helpful to point some dns domains `*.vatger.test` to your local ip address
8. (the develop branch is auto published to https://dev.vatsim-germany.org/) (if avail)
9. Before committing, run `npm run format:write`, `npm run build`, and `php artisan test`.

## Frontend organization

- `resources/css/app-public.css` — public Tailwind entry point
- `resources/css/app-admin.css` — administration Tailwind entry point
- `resources/scss/public/` — shared public components and page modules
- `resources/scss/admin/` — administration shell and components
- `resources/scss/mail.scss` — standalone email stylesheet
- `resources/fonts/` — source-controlled fonts processed by Vite
- `resources/ts/` — shared and page-specific TypeScript
