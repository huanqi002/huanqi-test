# Support Request Tests

## Local Setup

Follow the XAMPP setup in the root [README](../../../README.md), then use the sample users and records loaded by [`database/general/seed.sql`](../../../database/general/seed.sql).

## Manual Smoke Tests

| Scenario | Expected result |
| --- | --- |
| Sign in as a student with an accepted request | The dashboard shows the request with a "Book a session" action. |
| Schedule an available slot | The session is created and the slot is no longer offered. |
| Attempt to book an unavailable slot | The booking is rejected and the user can choose another time. |

## Evidence and Defects

For each verification pass, record the date, tester, environment, scenarios run, and pass/fail results. File defects with reproduction steps and link the fixing pull request. Do not mark a test as passed without running it.
