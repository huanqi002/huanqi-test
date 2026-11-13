# Git Collaboration

## Branch and Review Practice

- Keep `main` as the reviewed integration branch.
- Create one branch per bounded task, using names such as `feature/session-history` or `docs/testing-plan`.
- Commit small, coherent changes with imperative messages.
- Push task branches and open pull requests against `main`. Ask a teammate to review and run relevant checks before merging.
- Pull or fetch the latest `main` before starting work and before merging. Do not force-push shared branches.

## Resolving a Merge Conflict

1. Update the task branch with the latest `main` using the team's agreed merge or rebase method.
2. Run `git status` to identify conflicted files. Open each file, compare both changes, and edit the result so it satisfies both requirements where possible.
3. Remove conflict markers, inspect the complete result, and run the relevant tests or manual checks.
4. Stage the resolved files, commit the resolution, push the task branch, and explain the resolution in its pull request.
5. Add a dated entry below for each conflict that was actually resolved. Never invent an entry for a conflict that did not occur.

## Conflict Resolution Log

For each real conflict, record:

- Date and branch or pull request:
- Conflicted files:
- Cause of overlap:
- Resolution and verification performed:
- Team members involved:
