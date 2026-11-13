# Campus-Connect

BIT 216 University Student Support and Volunteer Platform.

## Project Structure

```text
Campus-Connect/
|-- frontend/                 Shared CSS and browser JavaScript
|-- backend/
|   |-- general/              Shared PHP configuration, page layout, and entity helpers
|   `-- use_cases/
|       |-- user_management/      Login, registration, logout
|       |-- support_request/      View accepted requests, schedule a session
|       `-- session_history/      Cancel, complete, feedback, history
|-- database/
|   `-- general/
|       |-- schema.sql        Shared platform schema (tables)
|       `-- seed.sql          Sample data for local testing
|-- documentation/
|   |-- project-plan/
|   |-- requirements/         General and per-use-case requirements
|   |-- design/               General and per-use-case design
|   `-- testing/              General and per-use-case tests
|-- README.md
`-- .gitignore
```

Keep each use case's pages, handlers, requirements, design, and tests in its own named folder. Put cross-cutting code and artifacts in `general/`; use cases may depend on shared components, while shared components must not depend on a specific use case.

## Support Session Manager

The current module lets students and volunteers schedule support sessions, manage bookings, mark sessions complete, and submit feedback.

### Run Locally

1. Install XAMPP with Apache, PHP, and MySQL.
2. Place or clone this repository inside XAMPP's `htdocs` directory.
3. Start Apache and MySQL from the XAMPP Control Panel.
4. In phpMyAdmin, import [`database/general/schema.sql`](database/general/schema.sql) to create the shared `support_system` database and its tables, then import [`database/general/seed.sql`](database/general/seed.sql) to load the sample records. Every sample account's password is `welcome@123`.
5. If your local MySQL credentials differ from XAMPP defaults, copy `backend/general/config.local.example.php` to `backend/general/config.local.php` and update the local values there. The local file is ignored by Git; never commit production credentials.
6. Open `http://localhost/Campus-Connect/backend/use_cases/user_management/select_user.php` in a browser. Adjust `Campus-Connect` in the URL if the repository folder has a different name under `htdocs`.

## Git Collaboration

- Keep `main` stable. Create a short-lived branch for each feature, fix, or documentation task, such as `feature/session-feedback` or `docs/project-plan`.
- Commit focused changes with imperative messages, for example `Add session cancellation flow`.
- Push the branch and open a pull request to `main`. Request review from a teammate; add the lecturer as a repository collaborator if required and permitted by the repository owner.
- Before starting work and before opening a pull request, fetch and integrate the latest `main`. Resolve conflicts in the affected feature branch, run the relevant checks, and describe the files and decisions in the pull request. Record actual conflict resolutions in `documentation/collaboration.md`; do not record conflicts that did not occur.
- Merge reviewed pull requests using the repository's agreed merge method, then remove the merged branch.

See [`documentation/collaboration.md`](documentation/collaboration.md) for the team workflow and conflict record template. Invite contributors and the lecturer from the repository's GitHub **Settings > Collaborators** page; invitations require repository-owner access.

## Documentation

- [Project plan](documentation/project-plan/README.md)
- [General requirements](documentation/requirements/README.md)
  - [User Management requirements](documentation/requirements/use_cases/user_management.md)
  - [Support Request requirements](documentation/requirements/use_cases/support_request.md)
  - [Session History requirements](documentation/requirements/use_cases/session_history.md)
- [General design](documentation/design/README.md)
  - [User Management design](documentation/design/use_cases/user_management.md)
  - [Support Request design](documentation/design/use_cases/support_request.md)
  - [Session History design](documentation/design/use_cases/session_history.md)
- [General testing guidance](documentation/testing/README.md)
  - [User Management tests](documentation/testing/use_cases/user_management.md)
  - [Support Request tests](documentation/testing/use_cases/support_request.md)
  - [Session History tests](documentation/testing/use_cases/session_history.md)
