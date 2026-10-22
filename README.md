# Cartalyst Global

Production-ready, mobile-first website for **Cartalyst Global** — a procurement and sourcing partner.

Stack: PHP 8.2+ (PDO), MySQL/MariaDB, Tailwind CDN, Alpine.js, PHPMailer. Designed for cPanel / Apache shared hosting.

## Folder structure

```
cartalyst-global/
  public/          ← document root (index.php, assets, .htaccess)
  app/             ← PHP application (not web-accessible)
  config/          ← config.example.php, .env (secrets)
  admin/           ← convenience redirect; real UI is /admin via the front controller
  database/        ← schema.sql, seed.sql
  uploads/         ← procurement files (blocked from HTTP)
  logs/
  vendor/PHPMailer
```

Clean URLs: `/`, `/about`, `/services`, `/request-procurement`, `/reviews`, `/contact`, `/privacy`, `/terms`, `/admin`.

---

## Deploy on cPanel / shared hosting

1. **Create a MySQL database** in cPanel. Note host (usually `localhost`), name, user, and password.
2. In **phpMyAdmin**, import `database/schema.sql`, then `database/seed.sql`.
3. Upload the project:
   - **Best:** set the domain document root to the `public` folder (Domains → Document Root).
   - **If you cannot change the document root:** upload the whole project into `public_html`. The root `.htaccess` forwards traffic into `public/`.
4. Copy config:
   - `config/config.example.php` → `config/config.php`
   - `config/.env.example` → `config/.env`
5. Edit `config/.env`:
   - `APP_URL` — `https://your-domain.com` (no trailing slash)
   - `CANONICAL_HOST` — `your-domain.com`
   - `APP_KEY` — a long random string
   - Database credentials
   - SMTP settings and `PROCUREMENT_TO_EMAIL`
6. Permissions: `uploads/` and `logs/` writable (0755/0750). PHP must not execute files in `uploads/`.
7. Visit `https://your-domain.com/setup` and create the admin (default suggestion is `admin@cartalystglobal.com` / `ChangeMeNow!2026`). **Change that password immediately.**
8. Sign in at `/admin`.
9. After SSL works, keep the HTTPS and HSTS rules in `public/.htaccess`. For local HTTP testing, temporarily comment out the HTTPS rewrite.

PHPMailer is already in `vendor/PHPMailer` — Composer is not required.

---

## How to customize

| What | Where |
| --- | --- |
| Logo | Replace `public/assets/img/logo.svg` and `logo-light.svg`. Keep similar proportions. |
| Colours | `public/assets/css/app.css` (`:root`) and the Tailwind config in `app/Views/layouts/public.php`. Navy `#0B1F3A`, emerald `#0F9D6E`, gold `#D4A017`. |
| Fonts | Google Fonts: **Fraunces** (display) + **Manrope** (body) in the public layout. |
| Recipient email | `PROCUREMENT_TO_EMAIL`, `CONTACT_TO_EMAIL`, `REVIEWS_TO_EMAIL` in `config/.env` |
| Services | Admin → Services, or edit `database/seed.sql` before import |
| Phone, address, hours | `settings` table (phpMyAdmin) after seed |
| Map | Put an iframe HTML snippet into `settings.map_embed` |
| Captcha | `CAPTCHA_PROVIDER=turnstile` or `recaptcha` plus site/secret keys |
| Unsplash images | Search views for `REPLACE:` and swap URLs or local WebP files |

---

## SEO launch checklist (rank for “Cartalyst Global”)

Each public page already includes a unique title and meta description with the brand name, canonical tags, Open Graph / Twitter cards, JSON-LD (Organization, WebSite, BreadcrumbList, Service, AggregateRating/Review from **approved** reviews), plus `/sitemap.xml` and `/robots.txt` (admin and uploads are disallowed).

Do this after go-live:

1. Confirm `APP_URL` and canonical host (www vs apex) match `public/.htaccess`.
2. [Google Search Console](https://search.google.com/search-console) — verify the property (`GSC_VERIFICATION` in `.env` or a DNS TXT record).
3. Submit `https://your-domain.com/sitemap.xml` in Search Console.
4. Request indexing for the homepage and key URLs (`/services`, `/request-procurement`, `/reviews`, `/about`, `/contact`).
5. Create or claim a **Google Business Profile** using the exact name **Cartalyst Global**, matching NAP (name, address, phone) on `/contact`.
6. [Bing Webmaster Tools](https://www.bing.com/webmasters) — verify (`BING_VERIFICATION`) and submit the sitemap.
7. Add real `sameAs` social profile URLs in the `settings` table so Organization schema is complete.
8. Replace Unsplash placeholders and `og-default.svg` with a 1200×630 PNG/JPG social image; set `seo.og_image` in config.
9. Enable Turnstile or reCAPTCHA in production.
10. Test Core Web Vitals (PageSpeed Insights). Prefer self-hosted WebP for hero images once you have brand photography.

---

## Security notes

- All SQL uses PDO prepared statements.
- Output is escaped with `e()` except trusted email HTML you control.
- CSRF tokens, honeypot fields, IP-hashed rate limits, MIME+extension upload checks, randomized filenames under `/uploads` (HTTP denied).
- Admin: `password_hash`, session cookies `HttpOnly` + `SameSite=Strict`, login throttling and lockout.
- Do not commit `config/.env` or `config/config.php`.
- Remove `/setup` access after `config/installed.lock` exists (the wizard refuses to run once an admin is present).

---

## Local notes

PHP, MySQL, and Git were not available on the machine that generated this project. After upload, `/setup` hashes the admin password with PHP’s `password_hash()`.
