# User Management Tests

## Local Setup

Follow the XAMPP setup in the root [README](../../../README.md), then use the sample users and records loaded by [`database/general/seed.sql`](../../../database/general/seed.sql).

## Manual Smoke Tests

| Scenario | Expected result |
| --- | --- |
| Open the user-selection page | Sample users are listed and the page styling loads. |
| Select a sample user | The user is signed in and lands on the support request dashboard. |
| Register a new identity | The identity is created, the user is signed in as it, and it later appears in the user-selection list. |
| Log out | The session ends and the user-selection page is shown. |

## Evidence and Defects

For each verification pass, record the date, tester, environment, scenarios run, and pass/fail results. File defects with reproduction steps and link the fixing pull request. Do not mark a test as passed without running it.
