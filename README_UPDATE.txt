KENYA WILDLIFEGUARD — CITIZEN DASHBOARD + INCIDENT REPORTING UPDATE
==================================================================

This is an UPDATE to your existing working login/registration project.
It is NOT a new independent project. It does NOT replace your database config,
users table, registration, login, ranger or officer pages.

INSTALL (Laragon on Windows)
1. Back up C:\laragon\www\kenya-wildlifeguard first.
2. Extract the ZIP. Open its kenya-wildlifeguard folder.
3. COPY its contents INTO C:\laragon\www\kenya-wildlifeguard
   and allow the existing dashboard/citizen.php to be replaced.
   DO NOT create kenya-wildlifeguard\kenya-wildlifeguard (nested folder).
4. In Laragon click Start All. In HeidiSQL, choose Query then open
   database/incidents.sql and run it. It adds the incidents table and
   does NOT delete your existing users or data.
5. IMPORTANT: keep your existing config/db.php and config/auth.php files.
   This ZIP does not contain them; the new pages reuse your working login.
6. Log in as a citizen and open:
   http://localhost/kenya-wildlifeguard/dashboard/citizen.php
7. Click Report Wildlife Incident; submit a TEST report, then use My Reports.

NEW/UPDATED FILES
- dashboard/citizen.php  polished dashboard and live database counts
- assets/citizen.css    responsive green dashboard styling
- includes/citizen_header.php, citizen_footer.php  private citizen layout
- report_incident.php   functional incident report form + optional photo
- my_reports.php       report history for logged-in citizen
- view_incident.php    read report details (only owner)
- incident_photo.php   controlled image retrieval (only owner)
- private_uploads/.htaccess  prevent direct access to upload storage
- database/incidents.sql   incident table migration

SAFETY NOTES
- 'Conflict Map', 'Wildlife Information', and 'Alerts' are visibly marked
  Coming soon, rather than linking to unavailable pages.
- Never upload actual protected personal data to a student demo.
- This is an academic prototype and NOT an official KWS reporting channel.
- 'Pending' is the starting status; future ranger/officer code will update it.
- PHP 8+ with PDO MySQL and fileinfo extensions required.
- If you changed the folder name, update BASE_URL in config/auth.php.
- Keep local secrets in config/db.php, and exclude it with .gitignore.
- Before any public launch, further security review is needed.

GITHUB AFTER TESTING
  git status
  git add dashboard/citizen.php assets/citizen.css includes/citizen_header.php includes/citizen_footer.php report_incident.php my_reports.php view_incident.php incident_photo.php database/incidents.sql private_uploads/.htaccess README_UPDATE.txt
  git commit -m "Build citizen dashboard and incident reporting"
  git push origin main

Do NOT commit real incident photos. Add private_uploads/* to .gitignore
with an exception for private_uploads/.htaccess if you choose to track it.
