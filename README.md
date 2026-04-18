# Team Activity Tracker

A web application built for the Npontu Technologies platforms developer assessment. It tracks the daily activities of an applications support team, captures who updated what and when, and provides a handover-ready view for shift changes.

---

## What It Does

The system covers all six requirements from the brief:

1. **Activity input** — Admins define the activities the team tracks each day (e.g. "Daily SMS count vs SMS count from logs"). Activities are grouped by category so the daily view stays organised.

2. **Status updates** — Any team member can open an activity and mark it as Done or Pending, with a remark. Every update is saved as a new entry, so previous updates are never overwritten.

3. **Personnel capture** — Each update is automatically tied to the logged-in user (name, employee ID, department) and the exact time it was submitted.

4. **Daily view** — The main screen shows all active activities for the current day, the latest status for each, who last updated it, and at what time. Team members can also click through to see the full update trail for any single activity — useful for handovers.

5. **Reporting** — The reports page lets you filter activity history by date range, activity, team member, and status. Results show all updates in the selected range with a summary count at the top.

6. **Authentication** — Access requires a valid email and password. Registration is disabled; only admins can create accounts. There are two roles: Admin (manages activities and users) and Member (updates activity statuses).

---

## Tech Stack

| Layer | Choice |
|---|---|
| Framework | Laravel 11 |
| Database | MySQL |
| Frontend | Blade templates, Bootstrap 5.3, Bootstrap Icons |
| Hosting | Railway |
| Build | Nixpacks (auto-detected by Railway) |

No frontend build step is required. Bootstrap is loaded from CDN.

---

## Project Structure

```
app/
  Http/
    Controllers/
      ActivityController.php       -- CRUD for activity definitions (admin only)
      ActivityLogController.php    -- Daily view, update form, history trail
      ReportController.php         -- Date-range reporting with filters
      UserController.php           -- Team member account management (admin only)
    Middleware/
      CheckRole.php                -- Restricts routes to admin role
  Models/
    Activity.php
    ActivityLog.php
    User.php

database/
  migrations/
    ..._create_users_table.php
    ..._create_activities_table.php
    ..._create_activity_logs_table.php
  seeders/
    DatabaseSeeder.php             -- Default admin account + sample activities

resources/views/
  layouts/app.blade.php            -- Sidebar layout used by all authenticated views
  auth/login.blade.php
  logs/
    daily.blade.php                -- Main daily tracking screen
    update.blade.php               -- Status update form
    history.blade.php              -- Full update trail for one activity
  activities/
    index.blade.php
    create.blade.php
    edit.blade.php
  users/
    index.blade.php
    create.blade.php
    edit.blade.php
  reports/index.blade.php

routes/web.php
railway.json
nixpacks.toml
.env.example
```

---

## Local Setup

**Requirements:** PHP 8.2+, Composer, MySQL

```bash
# 1. Clone and install dependencies
git clone <your-repo-url>
cd teamtracker
composer install

# 2. Copy environment file and set your database credentials
cp .env.example .env
# Edit .env: set DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD

# 3. Generate app key
php artisan key:generate

# 4. Run migrations and seed default data
php artisan migrate --seed

# 5. Start the server
php artisan serve
```

Visit `http://localhost:8000` and sign in with the default admin account.

---

## Deploying to Railway

1. Push your code to a GitHub repository.

2. On Railway, create a new project and connect your GitHub repo.

3. Add a **MySQL** plugin to the project. Railway will inject `MYSQLHOST`, `MYSQLPORT`, `MYSQLDATABASE`, `MYSQLUSER`, and `MYSQLPASSWORD` as environment variables automatically.

4. In your service's **Variables** tab, add these manually:

   ```
   APP_KEY=           (run: php artisan key:generate --show, paste the output)
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-app.up.railway.app
   DB_CONNECTION=mysql
   DB_HOST=${{MySQL.MYSQLHOST}}
   DB_PORT=${{MySQL.MYSQLPORT}}
   DB_DATABASE=${{MySQL.MYSQLDATABASE}}
   DB_USERNAME=${{MySQL.MYSQLUSER}}
   DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}
   SESSION_DRIVER=database
   ```

5. Railway will detect `nixpacks.toml` and build the app automatically. On first deploy, migrations and the database seeder run as part of the start command.

6. Once the deploy is green, open the generated Railway URL and log in.

---

## Default Login

After seeding, use these credentials to sign in:

| Field | Value |
|---|---|
| Email | admin@company.com |
| Password | Admin@1234 |

Change this password immediately after first login by editing the admin user from the Team Members screen.

A sample team member account is also created (`john.mensah@company.com` / `Member@1234`) and eight sample activities are loaded across four categories so you can see how the daily view looks with real data.

---

## Design Decisions

**Why every update is a new row and not an overwrite**

The brief specifically asks for a view that shows what each person updated, and what time, to help manage handovers. Overwriting a status would lose that trail. Each call to the update form appends a new `activity_logs` row. The daily view shows the most recent entry per activity, and the history screen shows every entry in order.

**Why registration is disabled**

The brief requires authentication before access is granted, and the team is a known, controlled group. Open registration would undermine that. Admins create accounts for team members directly.

**Why roles are kept simple**

The brief does not specify granular permissions. Two roles cover the requirement cleanly: admins configure the system, members use it. Adding more roles without a spec to justify it would be over-engineering.

**Bootstrap from CDN**

This avoids needing Node.js or a build step during development and keeps the Railway build straightforward. For a production system with more complex UI requirements, a Vite-based setup with npm would be the right move.
