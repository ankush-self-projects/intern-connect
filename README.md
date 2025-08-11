## Intern Connect

A minimal PHP + MySQL web app for collecting internship applications and processing payments via Stripe Checkout. Includes a simple admin dashboard to review and update application statuses.

### Features
- Application form with PDF CV upload
- Stripe Checkout integration (test mode by default)
- Admin dashboard to view applications and update statuses
- Basic email notification on successful payment (uses PHP `mail()`)

### Tech Stack
- **Backend**: PHP (PDO, cURL)
- **Database**: MySQL
- **Payments**: Stripe Checkout API
- **UI**: Bootstrap-based templates

---

## Prerequisites
- PHP 8.0+ with extensions: `pdo_mysql`, `curl`, `openssl`
- MySQL 5.7+/8+
- Composer not required (no dependencies)
- Stripe account for API keys (test or live)

---

## Project Structure
```
.
├─ admin/
│  ├─ dashboard.php
│  ├─ login.php               # empty placeholder (no auth implemented)
│  ├─ update-status.php
│  └─ view-application.php
├─ assets/
│  ├─ css/style.css
│  ├─ js/script.js
│  └─ uploads/                # uploaded CVs (PDF)
├─ config/
│  ├─ config.php              # DB connection (edit credentials here)
│  └─ payment.php             # Stripe keys via env, with test defaults
├─ includes/
│  ├─ bootstrap.php           # loads config, helpers, payment config
│  ├─ functions.php           # helpers (sanitization, status, email)
│  ├─ header.php
│  └─ footer.php
├─ public/
│  ├─ index.php               # landing page
│  ├─ apply.php               # application form
│  ├─ application-request.php # form handler: upload + DB insert
│  ├─ payment.php             # creates Stripe Checkout session
│  └─ payment-success.php     # verifies session + marks paid
├─ database.sql               # schema + sample rows
└─ package.json               # not used by the PHP app
```

---

## Setup

### 1) Database
1. Create the database and tables:
   ```sql
   -- from MySQL client
   SOURCE /absolute/path/to/database.sql;
   ```
   This creates a database named `internconnect` and a table `applications`.

2. Update database credentials in `config/config.php` if needed:
   ```php
   $host = 'localhost';
   $db   = 'internconnect';
   $user = 'root';
   $pass = 'password';
   ```

### 2) Stripe configuration
- The app reads keys from environment variables with safe defaults in `config/payment.php`:
  - `STRIPE_SECRET_KEY`
  - `STRIPE_PUBLISHABLE_KEY`
- For local development (test mode):
  - You can export variables in your shell before starting PHP:
    ```bash
    export STRIPE_SECRET_KEY=sk_test_...
    export STRIPE_PUBLISHABLE_KEY=pk_test_...
    ```
  - Or edit `config/payment.php` to hardcode your test keys.
- Currency defaults to `eur` via `APP_CURRENCY`.

### 3) File uploads
- Ensure `assets/uploads/` exists and is writable by the webserver process:
  ```bash
  mkdir -p assets/uploads
  chmod 775 assets/uploads
  ```

---

## Running Locally

Option A: PHP built-in server (recommended for quick start)
```bash
php -S 127.0.0.1:8000 -t public
```
Then open `http://127.0.0.1:8000/`.

Option B: Apache/Nginx
- Point the virtual host root to the `public/` directory
- Ensure PHP is enabled and the site user can write to `assets/uploads/`

---

## Usage Flow
- Visit `/apply.php` to submit an application (PDF only for CV)
- After submission, you are redirected to `/payment.php?id={applicationId}`
- Click "Pay" to create a Stripe Checkout session (test mode shows test payment page)
  - Test card: `4242 4242 4242 4242`, any future expiry, any CVC
- On success, `/payment-success.php` verifies the session and marks the application as `completed`
- Admin dashboard: `/admin/dashboard.php` to view, review, approve, or reject applications

---

## Admin Notes
- `admin/` views are currently **not authenticated**. Protect these routes before production use (e.g., add login, HTTP auth, or network ACLs).
- Status updates are performed via `admin/update-status.php?id=...&status=...` with allowed values: `pending`, `reviewed`, `approved`, `rejected`.

---

## Email
- Confirmation email on payment success uses PHP `mail()`.
- This requires a properly configured mail transfer agent (MTA) on your server or alternative SMTP configuration. In many local environments, `mail()` may be a no-op.

---

## Security Considerations
- Replace test Stripe keys with your own and set them as environment variables in production.
- Protect `admin/` with authentication and HTTPS.
- Validate and scan uploaded files as needed; current implementation accepts PDFs and stores them under `assets/uploads/` with a timestamped filename.
- Ensure proper file and directory permissions.

---

## Troubleshooting
- Stripe error about missing secret key: ensure `STRIPE_SECRET_KEY` is set or update `config/payment.php`.
- Cannot connect to DB: verify credentials in `config/config.php` and that MySQL is running and reachable.
- File upload failures: verify permissions on `assets/uploads/`.

---

## License
ISC (see `package.json`).