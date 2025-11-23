# PHP Book CRUD App (Student project)

Features:
- PHP + MySQL (PDO with prepared statements)
- Login & Register (password_hash)
- CRUD for books (create, read, update, delete)
- Search (multi-criteria) + AJAX autocomplete
- Protects against SQL Injection (prepared statements) and XSS (output escaping)
- CSRF tokens for forms
- Twig templates to separate markup and logic
- Tailwind CSS via CDN for styling
- Instructions to run on a student server

## Quick setup
1. Copy project to server (public/ should be the document root).
2. Create a MySQL database and user.
3. Import `init_db.sql` into MySQL.
4. Edit `src/config.php` with DB credentials.
5. Install dependencies with Composer (Twig):
   ```bash
   composer require twig/twig
