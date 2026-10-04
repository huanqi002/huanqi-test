# Requirements

## Functional Requirements

- A participant can cancel an eligible scheduled session, returning the request so a new session can be booked.
- A volunteer can mark an eligible session complete.
- A student can submit one rating and optional comment for a completed session.
- Users can review their full session history.

## Data Requirements

Uses the shared `sessions`, `requests`, `volunteers`, and `feedbacks` tables; a unique session identifier in `feedbacks` limits each session to one feedback record. See [General requirements](../README.md) for the shared schema.

## Constraints and Assumptions

- This module is a course-project prototype.
- The app expects PHP with MySQLi and a MySQL database named `support_system`.
- Real notification delivery to the other participant depends on the broader platform and is outside this module's current scope.

Review these requirements with the team and lecturer; update them when the agreed assignment scope changes.
