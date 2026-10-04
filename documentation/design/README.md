# General Design

## Shared Boundaries

- `frontend/` contains shared styles and browser helpers.
- `backend/general/` contains reusable configuration, page layout, and entity helpers:
    - `config.php` — database connection and session start.
    - `header.php` / `footer.php` / `navigation.php` — shared page layout and top navigation.
    - `user_profile.php` — identifying, switching, registering, and requiring the current user.
    - `support_request.php` — helpers for the `requests` entity.
    - `session.php` — helpers for the `sessions`, `volunteers`, `feedbacks`, and `history` entities.
    - `history.php` — helpers for the "My sessions" dashboard and full history views.
- `backend/use_cases/<use_case>/` contains one use case's pages and request handlers.
- `database/general/schema.sql` is the shared platform schema; use cases must not maintain duplicate copies of shared tables.

Keep each use case cohesive within its own folder. Use cases may depend on shared components; shared components must not import or call use-case-specific code. Add cross-cutting design decisions here and use-case-specific flows and decisions in that use case's design document.

## Data Model

- `users` stores accounts; a volunteer is a user with `is_volunteer = 'Y'`, so every `volunteer_id` references `users`.
- `requests` stores a student's support request; `volunteer_id` is set once a volunteer accepts it.
- `volunteers` stores each volunteer's weekly time slots (day of the week + time); a slot is booked on a date when a Scheduled session uses it.
- `sessions` stores bookings, their status, and the booked slot.
- `feedbacks` stores a rating and optional comment for a completed session (one per session).
- `history` stores the final outcome of each completed or cancelled session.
- `notifications` stores messages for a user.

See [`database/general/schema.sql`](../../database/general/schema.sql) for the authoritative schema. All three use cases below share this same schema through the `backend/general/` helpers rather than owning any table themselves.

## Use Cases

- [User Management design](use_cases/user_management.md) — login, registration, and logout.
- [Support Request design](use_cases/support_request.md) — viewing accepted requests and scheduling a session.
- [Session History design](use_cases/session_history.md) — cancelling, completing, rating, and reviewing sessions.