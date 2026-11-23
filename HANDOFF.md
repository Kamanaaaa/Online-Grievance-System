# HANDOFF — Online Grievance System

Short setup guide for **Windows + XAMPP**. Follow the steps in order. ✅

---

## 1. What you need

- **XAMPP for Windows** → <https://www.apachefriends.org/download.html> (install in `C:\xampp`)
- **Git for Windows** → <https://git-scm.com/download/win>
- A browser (Chrome / Edge) and Notepad or VS Code
- Access to the GitHub repo:
  - **Private repo?** Ask the owner to add you as a **Collaborator** (GitHub → Settings → Collaborators → Add people) and accept the email invite.
  - **Public repo?** No invite needed — anyone can clone.

---

## 2. Clone the project

Open **PowerShell** and run:

```powershell
cd C:\xampp\htdocs
git clone https://github.com/Kamanaaaa/Online-Grievance-System.git online-grievance-system
cd online-grievance-system
dir
```

You should see folders: `admin`, `citizen`, `config`, `database`, `department`, `includes`, `public`, `uploads`, and files `README.md`, `HANDOFF.md`.

> Keep the folder name **`online-grievance-system`** — the app expects it. If you use another name, change `BASE_URL` in `config\config.php`.

---

## 3. Start XAMPP

1. Open the **XAMPP Control Panel** (right-click → **Run as administrator**).
2. Click **Start** next to **Apache** and next to **MySQL** — both must be green.
3. Test: open <http://localhost/> → you should see the XAMPP welcome page.

---

## 4. Import the database

1. In your browser open <http://localhost/phpmyadmin>.
2. Click the **Import** tab.
3. **Choose File** → select:
   `C:\xampp\htdocs\online-grievance-system\database\database.sql`
4. Click **Import**. The database **`online_grievance_system`** now appears on the left with all tables.

---

## 5. Check the config file (usually no change needed)

Open `config\config.php`:

```php
define('BASE_URL', '/online-grievance-system');
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'online_grievance_system');
define('DB_USER', 'root');
define('DB_PASS', '');
```

These are XAMPP defaults. Only edit if you renamed the folder (`BASE_URL`) or set a MySQL password (`DB_PASS`).

---

## 6. Open the website

Go to:

```
http://localhost/online-grievance-system/public/
```

The **Public Grievance Portal** home page should appear with working menu and styling.

---

## 7. Create the first Super Admin (only once)

1. In the website click **Register** → create an account (password **10+ characters**).
2. Open <http://localhost/phpmyadmin> → select `online_grievance_system` → **SQL** tab → paste and run:

```sql
UPDATE users
SET role_id = (SELECT id FROM roles WHERE name = 'super_admin')
WHERE email = 'you@example.com';
```

3. **Sign out**, then **Sign in** again. The **Dashboard** link now opens the Admin dashboard.

Roles: `citizen` = 1, `department_admin` = 2, `super_admin` = 3.

---

## 8. Add departments, categories and officers

Sign in as **super admin** → **Dashboard → Manage system**:

1. **Add a department** (e.g. `Public Works`).
2. **Add a category** for it (e.g. `Road damage`) — department must exist first.
3. **Officers:** have them register normally, then use **Role → `department_admin`**, pick their department and save. Only super admins can change roles.

---

## 9. Quick test

- **Citizen:** sign in → **Submit a grievance** → note the **tracking number** → check it at `/public/track.php` (no login needed).
- **Department admin:** sign in → dashboard → open the grievance → change status and add a remark.
- **Citizen:** refresh the grievance view → status/history updated; check **Notifications**; leave **Feedback** after resolution.
- **Super admin:** check **Reports** and **Audit log**.

All working? You're done. 🎉

---

## 10. Shortcuts

| Page | URL (after `http://localhost/`) |
| --- | --- |
| Home | `/online-grievance-system/public/` |
| Register / Sign in | `/online-grievance-system/public/register.php` · `login.php` |
| Track | `/online-grievance-system/public/track.php` |
| Citizen / Department / Admin | `/online-grievance-system/citizen/dashboard.php` · `department/dashboard.php` · `admin/dashboard.php` |
| phpMyAdmin | <http://localhost/phpmyadmin> |

Keep `uploads\grievances\` and its `.htaccess` file — attachments are private and served through the app.

---

## 11. If something breaks

| Problem | Fix |
| --- | --- |
| 404 Not Found | Apache not started, or wrong folder name / URL. |
| Unstyled page or broken links | `BASE_URL` in `config\config.php` does not match the folder name. |
| Database error | SQL not imported, or `DB_USER` / `DB_PASS` wrong. |
| Apache or MySQL won't start | Port 80 / 3306 busy (Skype, IIS, another DB). Stop the program or change XAMPP's port. |
| "Invalid form token" | Refresh the page and submit again. |
| Upload fails | File must be under 5 MB and `uploads\grievances` writable; don't delete its `.htaccess`. |
| Blank page / 500 error | Check `C:\xampp\php\logs\php_error_log`. |

---

## 12. Updates later

```powershell
cd C:\xampp\htdocs\online-grievance-system
git pull
```

Press `Ctrl + F5` in the browser if you still see the old version.
