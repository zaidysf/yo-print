<h2 align="center">
    Yo Print - Assignment
</h2>

<p align="center">
The project is originally created for Yo Print by Zaid Yasyaf.
</p>

<div align="center">
<img width="500" src="https://github.com/zaidysf/yo-print/assets/5093672/f19aac6c-65c2-41dd-9769-1f29f118d26e"/>
</div>

## Features / Technologies

- [PHP v8.2](https://www.php.net/releases/8.2/en.php)
- [Laravel v10](https://laravel.com/docs/10.x) - web application framework with expressive, elegant syntax.
- [MySQL v8.0](https://dev.mysql.com/downloads/mysql/8.0.html) - an open-source relational database management system.
- [Redis v7.2 Alpine](https://redis.io/) - in-memory data store used by millions of developers as a database, cache, streaming engine, and message broker.
- [Ably](https://ably.com/) - realtime experience infrastructure that just works at any scale.
- [Tailwind](https://tailwindcss.com/) - A utility-first CSS framework packed with classes.
- [Vite](https://vitejs.dev/) - Next Generation Frontend Tooling.
- [Livewire](https://livewire.laravel.com/) - Powerful, dynamic, front-end UIs without leaving PHP.
- [AlpineJS](https://alpinejs.dev/) - Your new, lightweight, JavaScript framework.
- [Composer](https://getcomposer.org/) - Dependency Manager for PHP.
- etc.

### Laravel Packages

- [Sail](https://laravel.com/docs/10.x/sail) - light-weight command-line interface for interacting with Laravel's default
  Docker development environment
- [Horizon](https://laravel.com/docs/10.x/horizon) - Dashboard and code-driven configuration for Laravel queues.
- [Echo](https://github.com/laravel/echo) - Laravel Echo library for beautiful Pusher and Ably integration. 
- [Sanctum](https://laravel.com/docs/10.x/sanctum) - provides a featherweight authentication system for SPAs (single page applications), mobile applications, and simple, token based APIs.
- etc.

### Requirements

- Ably's API Key ([Tutorial](https://ably.com/tutorials/publish-subscribe#setup-ably-account))
- Docker

### Installation

- Make sure that your docker is running
- Clone the repository

```
git clone git@github.com:zaidysf/yo-print.git
```

- Enter yo-print directory

```
cd yo-print
```

- Install laravel sail

```
chmod a+x initialize-sail.sh
./initialize-sail.sh
```

- Copy .env.example to .env

```
cp .env.example .env
```

- Since this project is using [Ably](https://ably.com/accounts/) as the broadcast provider,
fill up ABLY_KEY value with your Ably API Key

```bash
ABLY_KEY=xxxxxxxxxxxxxxxxxxxx
```

- Run laravel sail

```
./vendor/bin/sail up -d
```

- Migrate and seed database

```
./vendor/bin/sail artisan migrate:fresh --seed
```

**[In another new terminal]** 
- Start the Vite development server to automatically recompile our CSS and refresh the browser when we make changes to our Blade templates

```
./vendor/bin/sail npm run dev
```

**[In another new terminal]** 
- Start Horizon server

```
./vendor/bin/sail artisan horizon
```

Finally, we can access this project by visiting below URLs
- Web : http://localhost
- Horizon : http://localhost/horizon

### Notes
- You have to self register to the application by clicking "**Register**" button on the right top corner page
- or by visiting the following URL : http://localhost/register
- You may use any dummy emails to register/login since the email verification feature is inactive

- For Yo-Print Management, Please kindly contact me by email if you want to use my Ably API Key

