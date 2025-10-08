# Local preview without managed hosting

If you need a quick way to verify the application without provisioning a
full LEMP stack, you can run Laravel's built-in development server with an
SQLite database. This keeps all assets and data on your workstation so no
public "temp link" is required.

## Prerequisites

- PHP 8.2 – 8.4 with the `sqlite3` extension enabled
- Composer 2.5+
- Node.js 20.10+ and npm 10.2+

## Step-by-step

1. **Install PHP dependencies**
   ```bash
   composer install --ignore-platform-req=php --ignore-platform-req=ext-sodium
   ```
   > If Composer reports GitHub 403 errors, supply a Personal Access Token
   > using `composer config -g github-oauth.github.com <token>`.

2. **Copy the environment file and switch to SQLite**
   ```bash
   cp .env.example .env
   php -r "file_put_contents('.env', str_replace('\nDB_CONNECTION=mysql', '\nDB_CONNECTION=sqlite', file_get_contents('.env')));"
   php -r "file_put_contents('.env', preg_replace('/^DB_DATABASE=.*/m', 'DB_DATABASE="'$(pwd)'/database/database.sqlite"', file_get_contents('.env')));"
   ```

3. **Create the SQLite database and run migrations**
   ```bash
   touch database/database.sqlite
   php artisan migrate --seed
   ```

4. **Generate an application key and Passport keys**
   ```bash
   php artisan key:generate
   php artisan passport:install
   chmod 600 storage/oauth-private.key storage/oauth-public.key
   ```

5. **Build frontend assets**
   ```bash
   npm install
   npm run build
   ```

6. **Serve the backend and frontend**
   Open two terminals:

   - Start the PHP development server
     ```bash
     php artisan serve --host=127.0.0.1 --port=8000
     ```
   - Start the Vite dev server so that styles and scripts hot-reload
     ```bash
     npm run dev -- --host 127.0.0.1 --port 5173
     ```

You can now browse `http://127.0.0.1:8000` locally. The site uses your
SQLite database, so no external services are exposed.

## Notes

- To share access with teammates on the same network, tunnel the local port
  with a tool such as `ngrok` or `cloudflared tunnel`. The repository does
  not publish a public demo environment.
- Running `npm run build` instead of `npm run dev` lets you keep everything
  in a single terminal, but you will lose hot module replacement.
- If you later switch back to MySQL, update the `DB_CONNECTION`,
  `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` keys accordingly and run
  `php artisan migrate:fresh`.
