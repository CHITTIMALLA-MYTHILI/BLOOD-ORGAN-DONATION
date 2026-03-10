# Organ and Blood Donation Management System

A complete academic mini-project built with **PHP + MySQL + HTML/CSS/JS** for running on **XAMPP**.

## 1) Project Architecture

### Layered Architecture
- **Presentation Layer**: HTML, CSS, minimal JavaScript pages under `public/`
- **Application Layer**: PHP handlers, module dashboards, auth guards, matching logic
- **Data Layer**: MySQL schema in `sql/schema.sql`

### Module Mapping
- **Admin Module**: monitor counts and request queue (`public/admin/dashboard.php`)
- **Donor Module**: register/login, update profile, view requests (`public/donor/dashboard.php`)
- **Patient Module**: create blood/organ requests, track statuses (`public/patient/dashboard.php`)
- **Hospital Module**: verify and approve/reject requests (`public/hospital/dashboard.php`)

## 2) Folder Structure

```text
BLOOD-ORGAN-DONATION/
├── config/
│   └── database.php
├── docs/
│   └── diagrams.md
├── includes/
│   ├── auth.php
│   ├── layout.php
│   └── matching.php
├── public/
│   ├── index.php
│   ├── login.php
│   ├── register.php
│   ├── assets/css/styles.css
│   ├── auth/
│   │   ├── login_handler.php
│   │   ├── logout.php
│   │   └── register_handler.php
│   ├── admin/dashboard.php
│   ├── donor/dashboard.php
│   ├── patient/dashboard.php
│   └── hospital/dashboard.php
├── scripts/
│   └── run_matching.php
└── sql/
    ├── schema.sql
    └── sample_data.sql
```

## 3) Database Schema (SQL)
- Full schema: `sql/schema.sql`
- Sample test data: `sql/sample_data.sql`

## 4) Frontend Pages
- Home page: `public/index.php`
- Login page: `public/login.php`
- Registration page: `public/register.php`
- Dashboards: module-specific pages under `public/admin`, `public/donor`, `public/patient`, `public/hospital`

## 5) Backend Logic
- DB connectivity: `config/database.php`
- Session-based access control: `includes/auth.php`
- Auth workflows: `public/auth/*`
- Request management in dashboard pages

## 6) Authentication System
- Password hashing during registration with `password_hash`
- Password verification during login with `password_verify`
- Role-based redirects to dashboards
- Route protection using `requireLogin([...roles])`

## 7) Donor Matching Algorithm
Implemented in `includes/matching.php`, executed via `scripts/run_matching.php`.

Priority score formula combines:
1. Blood group compatibility
2. Urgency level
3. Location proximity
4. Waiting time

## 8) Flowcharts & 9) System Diagrams
See `docs/diagrams.md` (Mermaid format).

## 10) Sample Test Data
- Inserted via `sql/sample_data.sql`.

---

## Run on XAMPP (Local Setup)

1. Copy project to: `C:/xampp/htdocs/BLOOD-ORGAN-DONATION`.
2. Start **Apache** and **MySQL** in XAMPP control panel.
3. Open phpMyAdmin and run:
   - `sql/schema.sql`
   - `sql/sample_data.sql`
4. Update DB credentials in `config/database.php` if needed.
5. Open in browser: `http://localhost/BLOOD-ORGAN-DONATION/public/`.
6. For matching run script in terminal:
   ```bash
   php scripts/run_matching.php
   ```

## Notes for Academic Submission
- Expand dashboards with charts using Chart.js if needed.
- Add AJAX polling for real-time status updates.
- Add audit logging table for hospital/admin actions.
