# User Management Design

## Module Structure

- `backend/use_cases/user_management/` contains this use case's PHP pages:
    - `select_user.php` — lists sample identities to sign in as (stands in for a login page).
    - `set_user.php` — starts the session for the chosen identity and redirects to the support request dashboard.
    - `register.php` — creates a new sample identity (name, role, optional email), then signs it in.
    - `logout.php` — ends the session and returns to `select_user.php`.
- Shared helpers used from `backend/general/user_profile.php`: `listUsers`, `findUserById`, `loginAsUser`, `registerUser`, `logoutUser`, `requireLogin`, `currentUser`.

## Design Decisions

There is no password: picking or registering an identity is a prototype stand-in for authentication, not production login. Keep it that way unless the assignment scope changes to require real credentials. Record agreed changes to this design here.
