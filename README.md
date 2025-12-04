# Production-ready CRUD Multi-Tables (PHP + SQLite) - Instructions

This package prepares the CRUD application for production with:
- Session-based authentication (login)
- API key (Bearer) support for API access
- CSRF protection for forms
- Basic rate limiting per IP
- Logging to `logs/app.log`
- .htaccess rules to improve security

## Installation

1. Extract the archive to your PHP server directory (e.g., `/var/www/html/full_crud_production`).
2. Copy your `pcp_referentiel.db` into the project root (next to `db.php`).
3. Create `logs` and `rate` directories and make them writable by the webserver:
   ```
   mkdir logs rate
   chown www-data:www-data logs rate
   chmod 770 logs rate
   ```
4. Generate an admin password hash locally and update the migrations SQL:
   ```
   php generate_admin_hash.php "VotreMotDePasse"
   ```
   Copy the printed hash and replace `REPLACE_WITH_HASH` in `migrations/001_create_users.sql`.

5. Import migrations (using sqlite3 CLI):
   ```
   sqlite3 pcp_referentiel.db < migrations/001_create_users.sql
   ```
6. Verify the `utilisateur` table has the admin user and api_key (sample given in migration).

7. Set secure permissions for the DB:
   ```
   chown www-data:www-data pcp_referentiel.db
   chmod 660 pcp_referentiel.db
   ```

8. Configure HTTPS on your server (Let's Encrypt recommended).

## Usage

- Open `https://your-host/full_crud_production/index.php`
- Login at `login.php` with the admin user.
- Use the UI to manage tables. API endpoints require `Authorization: Bearer <api_key>` header or session auth.

## Security notes / improvements

- Consider migrating to MySQL/Postgres for concurrent write heavy workloads.
- Add more advanced rate-limiting and IP blocking for abusive clients.
- Use a proper secrets manager for storing API keys in production.
- Add HTTPS HSTS header and other security headers at server configuration.
