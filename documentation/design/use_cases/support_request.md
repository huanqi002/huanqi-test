# Support Request Design

## Module Structure

- `backend/use_cases/support_request/` contains this use case's PHP pages and request handlers:
    - `index.php` — the "My sessions" dashboard: lists the signed-in user's support requests together with their latest session and feedback status.
    - `schedule.php` — lets a student pick a date, time, and mode for an accepted request.
    - `confirm_session.php` — validates the chosen slot, creates the `sessions` row, books the slot, and moves the request to `Scheduled`.
    - `get_slots.php` — JSON endpoint the scheduling form calls for a volunteer's open times on a given date.
- Shared helpers used from `backend/general/`: `user_profile.php` (`requireLogin`, `currentUser`), `support_request.php` (`findSupportRequestById`, `updateSupportRequestStatus`), `session.php` (`findAvailableSlot`, `bookSlot`, `createSession`, `getAvailableTimes`), and `history.php` (`fetchMyActivity` for the dashboard).
- `frontend/js/script.js`'s `initScheduleForm` calls `get_slots.php` in this same folder.

Actions on an existing session (cancel, mark complete, leave feedback) are linked from the dashboard but handled by the `session_history` use case, which owns those transitions.

## Design Decisions

Keep this small module in the existing PHP/MySQL stack. Share the schema in version control, but use local database credentials for development and never commit production secrets. Record agreed changes to this design here.
