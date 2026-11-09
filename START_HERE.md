# Kenya WildlifeGuard — photo dashboard update

This archive contains working PHP screens designed around the wildlife photographs you provided. It is based on your previous WildlifeGuard login and incident-reporting starter project.

## Install in Laragon (Windows)

1. **Back up** your current folder: `C:\laragon\www\kenya-wildlifeguard`. Your registered users and reports remain in MySQL, but backing up your code and database is still recommended.
2. Extract the ZIP. Open the `kenya-wildlifeguard` folder inside it.
3. **Copy the contents into the existing** `C:\laragon\www\kenya-wildlifeguard` folder. Let Windows replace matching PHP/CSS files. **Do not delete your existing project folder or its `private_uploads` files.**
4. The archive intentionally **does not include `config/db.php`**. Keep your working `config/db.php` from Laragon. A template is included as `config/db.example.php` if necessary.
5. In HeidiSQL, open the `kenya_wildlifeguard` database. Make sure both `users` and `incidents` tables exist. If `incidents` is missing, run **only** `database/incidents.sql` in the Query tab. It uses `CREATE TABLE IF NOT EXISTS` and does not delete records.
6. Make sure Laragon is running and log in at: `http://localhost/kenya-wildlifeguard/login.php`.
7. After logging in as a citizen, open `http://localhost/kenya-wildlifeguard/dashboard/citizen.php`. You will see the new dashboard with your real data and the wildlife gallery.

**Important:** These images decorate the site. They are not photos taken by a user or evidence that a specific incident occurred.


## NEW: Change dashboard/background photos in one file

All background photos have been applied **in actual code**. Open:

`assets/backgrounds.css`

Change the photo filenames at the top of that file. Copy any new JPG/PNG/WebP photos into `assets/wildlife/` first. Examples:

- `--citizen-page-photo`: full Citizen Dashboard background (currently savannah).
- `--ranger-page-photo`: full Ranger Dashboard background (currently zebras).
- `--officer-page-photo`: full Officer Dashboard background (currently grazing wildlife).
- `--citizen-banner-photo`: large Citizen welcome banner (currently elephant).
- `--ranger-banner-photo`: large Ranger welcome banner (currently zebras).
- `--officer-banner-photo`: large Officer welcome banner (currently wildebeest).
- `--sidebar-background-photo`: navigation sidebar photo.
- `--home-banner-photo`: homepage hero image.
- `--login-background-photo`: login/registration panel image.

Each full dashboard background has a pale overlay for readability, while welcome banners retain dark overlays so white headings remain clear.

**Note:** Both `includes/portal_header.php` and `includes/header.php` have been updated to load `assets/backgrounds.css`. Copy those two files along with the new CSS.

After copying, press **Ctrl + F5** in your browser to refresh any cached CSS.

## What is implemented

- Photo-based public landing page, login and citizen registration pages.
- Citizen dashboard: actual MySQL totals, recent reports, report type breakdown, links to working reporting and report history pages, wildlife gallery.
- Ranger dashboard: assigned cases with role-protected status updates.
- Officer dashboard: all submitted reports with role-protected ranger assignment.
- Role-aware incident details and protected uploaded-photo access.
- Mobile-responsive layout.

## What is not implemented

- **Interactive GPS map, hotspot analytics and live alerts**. The map panel deliberately says *Planned feature*. The uploaded wildlife photos are not location markers.
- Automatic notifications and KWS integrations.
- Ranger/officer account creation interface. Only a trusted administrator should create these roles. Public registration makes citizens only.

## Test workflow

1. Log in as a citizen. Use *Report Incident* to submit a fictional test incident. Check *My Reports* and confirm totals increase.
2. An authorized officer account can open `dashboard/officer.php` to review and assign the incident to a ranger account that already exists.
3. Log in with that ranger account, open `dashboard/ranger.php`, and set the assigned incident status to *In Progress* or *Resolved*.
4. Log in as the citizen again. The case status should match the database.

To create privileged accounts, use an administrator-controlled process, not a public role selector. Do not change users to privileged roles without authorization.

## GitHub

`config/db.php` and the private uploaded evidence files are excluded by `.gitignore`. If you previously committed your private config, remove it from Git tracking before the next push:

```bash
git rm --cached config/db.php
git add .
git commit -m "Design wildlife dashboards with photo gallery"
git push
```

Keep `.htaccess` inside `private_uploads` to protect uploaded images from direct public access. Before any public hosting, configure HTTPS, proper database credentials and application security reviews. This is an **academic prototype**, not an official KWS emergency channel.

## Software

The project targets PHP 8+ with PDO MySQL, Apache/Laragon and MySQL/MariaDB. It uses no JavaScript framework or external CDN for the dashboard design. The web font on the original public pages is optional.

## Photos

All wildlife images in `assets/wildlife/` were resized to WebP from the files you provided. See `assets/wildlife/PHOTO_CREDITS.txt` for the source filenames and maintain any required photographer attribution.
