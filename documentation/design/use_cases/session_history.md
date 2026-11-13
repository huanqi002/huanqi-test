# Session History Design

## Module Structure

- `backend/use_cases/session_history/` contains this use case's PHP pages and request handlers:
    - `cancel_session.php` — cancels an eligible scheduled session, frees the volunteer's slot, and returns the request to `Accepted`.
    - `mark_completed.php` — lets the volunteer mark an eligible scheduled session `Completed`.
    - `feedback.php` — shows the rating form for a completed session that has not yet been rated.
    - `submit_feedback.php` — validates and saves the student's rating and optional comment.
    - `history.php` — lists all of the signed-in user's past and current requests and sessions.
- Shared helpers used from `backend/general/`: `session.php` (`findScheduledSession`, `updateSessionStatus`, `freeSlot`, `findSessionForFeedback`, `hasFeedback`, `insertFeedback`), `support_request.php` (`updateSupportRequestStatus`), `user_profile.php` (`requireLogin`, `currentUser`), and `history.php` (`fetchFullHistory`).

Sessions are created by the `support_request` use case; this module owns their later lifecycle (cancel, complete, feedback) and the historical view.

## Design Decisions

Keep this small module in the existing PHP/MySQL stack. Share the schema in version control, but use local database credentials for development and never commit production secrets. Record agreed changes to this design here.
