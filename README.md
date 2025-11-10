![arabiafarmers logo](https://phpstack-1100125-4199885.cloudwaysapps.com/images/logo/Arabiafarmers.png)

### Documentation

- [Application features](docs/APPLICATION_FEATURES.md)

### Technical requirements

- PHP 8.2
- MySQL
- Node v20.10+
- NPM v10.2+

---
### Install Project
#### PHP Packages
- Make sure you have PHP v8.2 otherwise the project may not run on machine.

> **Running on PHP 8.4**
>
> The dependency lock currently caps the supported PHP version at 8.3. If your
> environment only ships PHP 8.4 you can still install the dependencies by
> instructing Composer to skip the PHP and `ext-sodium` checks:
> ```bash
> composer install --ignore-platform-req=php --ignore-platform-req=ext-sodium
> ```
> You will continue to see the platform warning until the upstream packages
> declare official PHP 8.4 support.

> **Need a local preview without exposing a public link?**
>
> Follow the [Local preview guide](docs/LOCAL_PREVIEW.md) to bootstrap the
> application with SQLite and run `php artisan serve` plus the Vite dev
> server. This keeps everything on your workstation while still allowing you to
> verify the UI end-to-end.

Copy enviroment file from
```bash
> cp .env.example .env
```
Fill `.env` file
Run command to install libraries and framework
```bash
> composer install
```
Finally
```bash
> php artisan key:generate 
```
---
#### Dataabase
Before process this step make sure to have right configration for MySQL
```bash
> php artisan migrate --seed
```
Now you create MySQL and Mongodb with needed schema

> **SQLite quick start (useful for CI or containers without MySQL):**
> 1. Set `DB_CONNECTION=sqlite` in `.env` and point `DB_DATABASE` to an absolute
>    path such as `/full/path/to/database/database.sqlite`.
> 2. Create the empty database file with `touch database/database.sqlite`.
> 3. Run migrations normally: `php artisan migrate --seed`.
>    This lets you boot the application without provisioning MySQL locally.

---
#### AUTH
Before calling APIs for farmer, nursery
```bash
> php artisan passport:install
> chmod 600 storage/oauth-private.key 
> chmod 600 storage/oauth-public.key
``` 


---
#### Frontend
You must have NodeJS and NPM
```bash
> npm install 
``` 
Make sure you installed packages without issues.
Now you have to complie Javascript and CSS files by
```bash
> npm run dev
```
> In case you are running production environment you must run npm run prod
---

### Working in restricted network environments

Some corporate CI systems and online sandboxes (including the environment used to prepare this report) block outgoing access to GitHub package archives. When Composer cannot download `dist` archives it falls back to cloning from GitHub and prompts for a Personal Access Token. Without providing one the installation will halt. In that case you can either:

* Run `composer config -g github-oauth.github.com <token>` with a token that has at least public repository read access, **or**
* Mirror the dependencies to an internal Composer repository that is reachable from your environment.

Until one of these options is configured the PHP dependencies cannot be fully installed and the application will not boot.


Congratulations you should see empty homepage, for sure since arabiafarmers contains senetive data we don't share database dumps, you can use [Laravel Factories](https://laravel.com/docs/6.x/database-testing) to generate needed data.

Please Note we are working in [TDD approach](https://en.wikipedia.org/wiki/Test-driven_development).

---
#### Notes
- If you have issue with images please check environment value `SECURE_LINKS` to be `false` if you don't have HTTPS connection.
- Can run test using
```bash
> ./vendor/bin/phpunit 
```
