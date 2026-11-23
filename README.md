# Online Grievance Management System

Beginner-friendly PHP/PDO and MySQL application for XAMPP.

## Architecture and relationships

The application uses shared configuration, PDO, authentication/CSRF/escaping helpers, public pages and role-separated citizen, department and super-admin pages. `users` belong to `roles` and optionally `departments`; `grievances` belong to a citizen, department and category; attachments and history belong to grievances; officer assignments link staff and grievances; notifications and feedback refer to users/grievances; audit logs record administrative actions. See `database/database.sql` for keys and indexes.

## Setup

1. Copy this folder into XAMPP's `htdocs`.
2. Start Apache and MySQL; import `database/database.sql` using phpMyAdmin.
3. Set database credentials in `config/config.php` if needed.
4. Ensure `uploads/grievances` is writable by Apache. The directory denies direct web requests; files are served only through the authorized download endpoint.
5. Visit `/online-grievance-system/public/` (adjust the URL to the folder name).
6. Register the first citizen. Create the first super admin by updating that user's `role_id` to the seeded super-admin role in phpMyAdmin. Subsequent administrative role changes are available only to super admins.

Uploads are stored outside the public document root and served through an authorized download endpoint. Local notification records work without external email/SMS services.

## Folder structure

`config/` configuration and database; `includes/` shared security and layout; `public/` public routes and assets; `citizen/`, `department/`, `admin/` role areas; `database/` schema; `uploads/` private attachments.
