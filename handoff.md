# Handoff Guide — Online Grievance Management System

This guide takes you from **zero to a running local website** using **GitHub + XAMPP on Windows**.
You do not need to be a developer — just follow the steps in order and copy/paste the commands.

> **What this app is:** a PHP + MySQL demo portal where a citizen submits a grievance, a department
> officer processes it, and a super admin manages departments, categories, users and reports.

---

## 0. Contents

1. [What you need before starting](#1-what-you-need-before-starting)
2. [Clone the project from GitHub](#2-clone-the-project-from-github)
3. [Start XAMPP (Apache + MySQL)](#3-start-xampp-apache--mysql)
4. [Import the database in phpMyAdmin](#4-import-the-database-in-phpmyadmin)
5. [Check / edit the configuration file](#5-check--edit-the-configuration-file)
6. [Open the website](#6-open-the-website)
7. [Create the first Super Admin](#7-create-the-first-super-admin)
8. [Set up departments, categories and officers](#8-set-up-departments-categories-and-officers)
9. [Quick test run (make sure everything works)](#9-quick-test-run-make-sure-everything-works)
10. [Useful links and folder map](#10-useful-links-and-folder-map)
11. [Troubleshooting](#11-troubleshooting)
12. [Keeping your copy updated](#12-keeping-your-copy-updated)
13. [For the project owner: pushing to GitHub](#13-for-the-project-owner-pushing-to-github)

---

## 1. What you need before starting

- **XAMPP for Windows** (install from <https://www.apachefriends.org/download.html>).
  Default install folder is `C:\xampp` — this guide assumes that path.
- **Git for Windows** (install from <https://git-scm.com/download/win>).
- A **GitHub account** and the repository URL from the project owner.
- A normal web browser (Chrome / Edge / Firefox).

> Tip: install XAMPP in `C:\xampp` (not inside `Program Files`) so Windows does not block Apache
> from writing to the `uploads` folder.

---

## 2. Clone the project from GitHub

1. Open **PowerShell** (press `Win + R`, type `powershell`, press Enter).
2. Go to XAMPP's website folder and clone the repo there:

```powershell
cd C:\xampp\htdocs
git clone https://github.com/<YOUR-USERNAME>/<REPOSITORY-NAME>.git online-grievance-system
```

3. Confirm the folder exists:

```powershell
cd C:\xampp\htdocs\online-grievance-system
dir
```

You should see folders like `admin`, `citizen`, `config`, `database`, `department`, `includes`,
`public`, `uploads` and the file `README.md`.

**Important — folder name matters.**
The app is coded for the folder name `online-grievance-system`. If you clone with a different
folder name, either rename the folder to `online-grievance-system`, or change `BASE_URL` in
`config\config.php` (see [step 5](#5-check--edit-the-configuration-file)).

---

## 3. Start XAMPP (Apache + MySQL)

1. Open the **XAMPP Control Panel**. Right-click it and choose **Run as administrator** (best to avoid
   permission problems).
2. Click **Start** on the row for **Apache** and on the row for **MySQL**.
3. Both should show a green background. If a module stays red, see [Troubleshooting](#11-troubleshooting).
4. Test that Apache works: open <http://localhost/> in your browser. You should see the XAMPP
   welcome page.

> Apache uses port **80** and MySQL uses port **3306**. If another program (Skype, IIS, another
> database) uses those ports, XAMPP cannot start. The fix is in the troubleshooting section.

---

## 4. Import the database in phpMyAdmin

The SQL file creates the database, all 10 tables and the three roles automatically.

1. Open <http://localhost/phpmyadmin> in your browser.
2. Click the **Import** tab at the top.
3. Under **File to import**, click **Choose File** and select:

```
C:\xampp\htdocs\online-grievance-system\database\database.sql
```

4. Leave the other options as they are and click **Import** at the bottom.
5. On the left side you should now see a database named **`online_grievance_system`**. Click it and
   confirm you can see the tables: `roles`, `departments`, `users`, `categories`, `grievances`,
   `grievance_attachments`, `grievance_history`, `officer_assignments`, `notifications`, `feedback`,
   `audit_logs`.

> If you import a second time and get "table already exists" errors: in phpMyAdmin select the
> database → **Operations** → **Drop database**, then repeat the import. **Only do this on a fresh
> install — it deletes all data.**

---

## 5. Check / edit the configuration file

Open this file in Notepad or VS Code:

```
C:\xampp\htdocs\online-grievance-system\config\config.php
```

These are the default values, which work with a standard XAMPP install:

```php
define('BASE_URL', '/online-grievance-system'); // must match your htdocs folder name
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'online_grievance_system');
define('DB_USER', 'root');
define('DB_PASS', '');                          // XAMPP MySQL root has an empty password by default
```

What to change, if needed:

| Situation | What to do |
| --- | --- |
| You renamed the project folder | Set `BASE_URL` to `/your-folder-name` (leading `/`, no trailing `/`). |
| You set a password for MySQL's `root` user in XAMPP | Put that password inside `DB_PASS`, e.g. `define('DB_PASS', 'mypassword');` |
| You created a different MySQL user | Update `DB_USER` and `DB_PASS` to match. |

Save the file. No other file needs editing.

---

## 6. Open the website

Go to:

```
http://localhost/online-grievance-system/public/
```

You should see the **Public Grievance Portal** home page with a working menu and styling.
(If the page is unstyled or gives 404, your `BASE_URL` or folder name is wrong — see step 5.)

---

## 7. Create the first Super Admin

There is no admin account in the database at the start. Do this once:

1. In the website, open **Register** (<http://localhost/online-grievance-system/public/register.php>)
   and create a normal citizen account. Use an email you will remember, and a password of at least
   **10 characters**.
2. Open <http://localhost/phpmyadmin> → select the `online_grievance_system` database → click the
   **SQL** tab.
3. Paste this query (replace the email with the one you registered) and click **Go**:

```sql
UPDATE users
SET role_id = (SELECT id FROM roles WHERE name = 'super_admin')
WHERE email = 'you@example.com';
```

4. In the website, click **Sign out**, then **Sign in** again with the same account.
5. The **Dashboard** link in the top menu now opens the Admin dashboard
   (`/admin/dashboard.php`) with buttons for **Manage system**, **Reports / CSV export** and
   **Audit log**.

> The seeded roles are: `citizen` (id 1), `department_admin` (id 2), `super_admin` (id 3).
> The SQL above uses the role *name*, so it stays correct even if the IDs are different.

---

## 8. Set up departments, categories and officers

Log in as the **super admin** and open **Dashboard → Manage system** (`/admin/manage.php`).

1. **Add a department** — e.g. `Public Works`, `Sanitation`, `Health`. Add at least one before
   citizens submit grievances.
2. **Add a category** for that department — e.g. `Road damage`, `Garbage collection`. Categories
   belong to a department, so add the department first.
3. **Create officer accounts:**
   - Have each officer register normally at `/public/register.php` (they become citizens).
   - Back in **Manage system**, use **Role → `department_admin`**, pick their department from the
     dropdown, enter their user ID (shown in the users list on that page) and save.
   - You can also deactivate a department with **Toggle department** if it should stop accepting
     new grievances.
4. Only super admins can change roles. A `department_admin` without an assigned department will get
   a "No department is assigned to this account" message — assign one in Manage system.

---

## 9. Quick test run (make sure everything works)

Use three browser profiles (or log out/in between steps) to test the full flow:

1. **Citizen:** register → sign in → **Dashboard → Submit a grievance** → choose department and
   category, fill title/description/location, optionally attach a file (max 5 MB) → submit.
2. Note the **tracking number** shown after submission. Anyone can check it at
   `/public/track.php` without logging in.
3. **Citizen:** check **Notifications** and the grievance's **history** after an update.
4. **Department admin:** sign in → `/department/dashboard.php` → open the grievance → change status
   (e.g. Under Review → In Progress → Resolved) and add a public remark.
5. **Citizen:** refresh the grievance view — the new status and remark appear.
6. **Super admin:** check `/admin/reports.php` (CSV export) and `/admin/audit.php` (role changes are
   logged there).
7. **Citizen:** after resolution, use `/citizen/feedback.php` to leave a 1–5 rating.

If all seven steps work, the handoff is complete. ✅

---

## 10. Useful links and folder map

### Links

| Page | URL |
| --- | --- |
| Home | `http://localhost/online-grievance-system/public/` |
| Register | `http://localhost/online-grievance-system/public/register.php` |
| Sign in | `http://localhost/online-grievance-system/public/login.php` |
| Track a grievance | `http://localhost/online-grievance-system/public/track.php` |
| Citizen dashboard | `http://localhost/online-grievance-system/citizen/dashboard.php` |
| Department dashboard | `http://localhost/online-grievance-system/department/dashboard.php` |
| Admin dashboard | `http://localhost/online-grievance-system/admin/dashboard.php` |
| phpMyAdmin | `http://localhost/phpmyadmin` |

### Folder map (do not delete or move these)

| Folder / file | What it contains |
| --- | --- |
| `config\config.php` | Database credentials, `BASE_URL`, upload settings |
| `database\database.sql` | The SQL file you imported in step 4 |
| `includes\` | Shared security helpers (login/role checks, CSRF) and page layout |
| `public\` | Home, register, login, tracking, downloads and CSS |
| `citizen\` | Citizen pages (submit, view, feedback, notifications, profile) |
| `department\` | Department officer pages |
| `admin\` | Super admin pages (manage, reports, audit) |
| `uploads\grievances\` | Private attachments. Keep `.htaccess` (blocks direct downloads) and `index.html`; files are served through the authorized download page. |

---

## 11. Troubleshooting

| Problem | Fix |
| --- | --- |
| **404 Not Found** on the site URL | Check Apache is running, the folder is really in `C:\xampp\htdocs\online-grievance-system`, and the URL matches the folder name. |
| Page has no styling / broken links | `BASE_URL` in `config\config.php` does not match the folder name. Fix it and refresh. |
| `SQLSTATE[HY000] [1049] Unknown database 'online_grievance_system'` | The SQL import failed or was skipped. Redo [step 4](#4-import-the-database-in-phpmyadmin). |
| `SQLSTATE[HY000] [1045] Access denied for user 'root'` | Your XAMPP MySQL password differs from `DB_PASS`. Update `config\config.php`. |
| `Could not find driver` | The PHP MySQL (PDO) extension is disabled. In XAMPP Control Panel → Apache → **Config** → `php.ini`, make sure `extension=pdo_mysql` and `extension=mysqli` are uncommented, then restart Apache. |
| Apache will not start (red) | Port 80 is busy (often Skype, IIS or another web server). Either stop that program/service or change Apache's port under XAMPP Control Panel → **Config → Service and Port Settings**, then use `http://localhost:<port>/...`. |
| MySQL will not start (red) | Port 3306 may be used by another database. Stop that service or change MySQL's port in XAMPP config. |
| "Invalid form token. Please go back and try again." | The page/session expired. Refresh the page and submit the form again (do not use a very old browser tab). |
| File upload fails | Check that `uploads\grievances` exists and is writable by your Windows user, and that the file is smaller than 5 MB. Do not delete its `.htaccess` file. |
| Blank page / HTTP 500 | Check the PHP error log: `C:\xampp\php\logs\php_error_log` (or Apache's `error.log` in `C:\xampp\apache\logs\`). |
| Old version still showing after an update | Press `Ctrl + F5` to hard-refresh the browser, then `git pull` in the project folder and restart Apache. |
| Windows says "You need permission to modify this folder" | XAMPP was installed in a protected folder (e.g. `Program Files`). Reinstall/move it to `C:\xampp`, or grant your user write permission on `uploads\grievances`. |

---

## 12. Keeping your copy updated

Whenever the owner pushes changes to GitHub, open PowerShell and run:

```powershell
cd C:\xampp\htdocs\online-grievance-system
git pull
```

If someone changed the database structure (a new `.sql` file), import it again **only if instructed**
— re-importing the main SQL file deletes existing data unless told otherwise.

---

## 13. For the project owner: pushing to GitHub

Run this once from the project folder (replace the URL with your own repository URL):

```powershell
cd E:\Online-Grievance-System
git init
git add .
git commit -m "Initial commit - Online Grievance System"
git branch -M main
git remote add origin https://github.com/<YOUR-USERNAME>/<REPOSITORY-NAME>.git
git push -u origin main
```

After that, every update is:

```powershell
cd E:\Online-Grievance-System
git add .
git commit -m "Describe what changed"
git push
```

Before the first commit, double-check:

- `config\config.php` contains only local XAMPP defaults (`root` / empty password) — do not commit
  real database passwords.
- No real user data or uploaded grievance attachments are included. The repo should only contain
  `uploads\grievances\.htaccess` and `index.html`.
- `handoff.md`, `README.md` and `database\database.sql` are included — your friend needs all three.

Then send your friend the clone URL from step 2 and this `handoff.md` file. 🎉

---

**That's it.** If something does not match this guide, check the exact error message in the browser
and compare it with the [Troubleshooting](#11-troubleshooting) table before asking the owner.
