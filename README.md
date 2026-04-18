# Team-Activity-Tracker
A web application built for the Npontu Technologies platforms developer assessment. It tracks the daily activities of an applications support team, captures who updated what and when, and provides a handover-ready view for shift changes.
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
