# Requirements

## Functional Requirements

- A student can view their accepted support requests alongside the status of any related session.
- A student can schedule an available volunteer time slot for an accepted request.
- A booking is rejected if the chosen slot is no longer available, and the student can choose another time.

## Data Requirements

Uses the shared `requests`, `volunteers`, `sessions`, and `users` tables. Scheduling creates a `sessions` row and marks the chosen slot booked; see [General requirements](../README.md) for the shared schema.

## Constraints and Assumptions

- This module is a course-project prototype.
- The app expects PHP with MySQLi and a MySQL database named `support_system`.
- Notifying the volunteer of a new booking depends on the broader platform and is outside this module's current scope.

Review these requirements with the team and lecturer; update them when the agreed assignment scope changes.
