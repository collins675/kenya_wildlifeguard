KENYA WILDLIFEGUARD — LOGIN + REGISTRATION STARTER
=================================================

WHAT IS INCLUDED
- Citizen registration with basic validation
- Shared login page for all roles
- Password hashing with PHP password_hash() / password_verify()
- Prepared SQL statements
- Role-based redirects and access checks for citizen, ranger and officer dashboards
- Sessions and simple CSRF protection
- Logout
- A responsive home page

BEFORE STARTING
1. In Laragon, click Start All. Stop Apache/MySQL in XAMPP to avoid port conflicts.
2. Back up your current C:\laragon\www\kenya-wildlifeguard folder first.
3. Copy the CONTENTS of the included kenya-wildlifeguard folder into:
   C:\laragon\www\kenya-wildlifeguard
   (or copy the whole folder into C:\laragon\www if it does not exist).
4. In HeidiSQL, confirm the database kenya_wildlifeguard has a users table.
   If not, run database/schema.sql in a query tab (this does not delete existing data).
5. Open config/db.php. The default is 127.0.0.1:3306, user root, blank password.
   Change the port, username and password to match your Laragon MySQL configuration.

TEST IT
1. Visit http://localhost/kenya-wildlifeguard/
2. Click Register, or visit http://localhost/kenya-wildlifeguard/register.php
3. Create a test citizen account using an email and a password of at least 8 characters.
4. You should be redirected to the shared login page.
5. Log in; you should see the CITIZEN dashboard.
6. Click Logout; login should be required to open the citizen dashboard again.
7. Visiting /dashboard/ranger.php or /dashboard/officer.php as a citizen should
   send you back to your own dashboard, not open those pages.

IMPORTANT
- Public registration always creates CITIZEN accounts, never ranger or officer.
- Do not put a user-selectable role field on public registration.
- Ranger/officer accounts will be created in a separate authorized-admin step.
- Dashboards are placeholders. Incident reporting and assignments are not implemented yet.
- This is a LOCAL STUDENT PROJECT starter, not a production deployment.
- Add server-side rate limiting, email verification, HTTPS, a least-privileged database
  account, audit logging and proper deployment security before public use.
- The project is not affiliated with KWS; do not imply official KWS endorsement.

NEXT DEVELOPMENT STAGE
Create the incidents table and a citizen incident-reporting form.
