# Requirements

## Functional Requirements

- The user can select a sample student or volunteer identity to sign in as.
- The user can register a new sample identity (full name, email, university, password hash, volunteer flag) and be signed in as it.
- The user can log out to switch to a different identity.

## Data Requirements

Uses the shared `users` table (full name, email, university, password hash, volunteer flag). See [General requirements](../README.md) for the shared schema.

## Constraints and Assumptions

- This module is a course-project prototype. The user picker and register form are not production authentication: there is no password.
- The app expects PHP with MySQLi and a MySQL database named `support_system`.

Review these requirements with the team and lecturer; update them when the agreed assignment scope changes.
