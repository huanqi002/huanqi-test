# Session History Tests

## Local Setup

Follow the XAMPP setup in the root [README](../../../README.md), then use the sample users and records loaded by [`database/general/seed.sql`](../../../database/general/seed.sql).

## Manual Smoke Tests

| Scenario | Expected result |
| --- | --- |
| Cancel an eligible scheduled session | The session status changes to cancelled, the slot is freed, and the request returns to accepted. |
| Mark an eligible session complete as its volunteer | The status changes to completed. |
| Submit valid feedback for a completed session | The rating and optional comment are saved once. |
| Attempt to submit feedback twice for the same session | The second attempt is rejected. |
| Open session history | Previously scheduled and completed sessions are shown. |

## Evidence and Defects

For each verification pass, record the date, tester, environment, scenarios run, and pass/fail results. File defects with reproduction steps and link the fixing pull request. Do not mark a test as passed without running it.
