![arabiafarmers logo](https://phpstack-1100125-4199885.cloudwaysapps.com/images/logo/Arabiafarmers.png)

### Technical requirements

- PHP 8.2
- MySQL
- Node v20.10+
- NPM v10.2+

---
### Install Project
#### PHP Packages
- Make sure you have PHP v8.2 otherwise the project may not run on machine.

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

Congratulations you should see empty homepage, for sure since arabiafarmers contains senetive data we don't share database dumps, you can use [Laravel Factories](https://laravel.com/docs/6.x/database-testing) to generate needed data.

Please Note we are working in [TDD approach](https://en.wikipedia.org/wiki/Test-driven_development).

---
#### Notes
- If you have issue with images please check environment value `SECURE_LINKS` to be `false` if you don't have HTTPS connection.
- Can run test using
```bash
> ./vendor/bin/phpunit 
```
