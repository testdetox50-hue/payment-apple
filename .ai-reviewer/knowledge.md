# payment-apple reviewer notes

## Architecture
Daylist is a server-rendered PHP task manager with no framework, JavaScript, database, or Composer dependencies. `public/index.php` renders the UI, while `app.php` handles sessions, CSRF validation, task actions, JSON persistence, filtering, and shared escaping. Tasks are stored under `data/tasks.json`; `tools/chaos.py` is an optional malformed-data resilience test.

## Conventions
- Keep the web root limited to `public`; `app.php` and `data` are intended to remain outside it (`README.md`).
- Use POST for mutations and redirect with HTTP 303 afterward (`app.php`), preventing duplicate submissions on refresh.
- All mutations require the session CSRF token and validate the action against an allowlist (`app.php`).
- Persisted task records are treated as untrusted. `taskStore()` filters records to non-empty string IDs/titles, titles no longer than 200 bytes, and boolean `done` values (`app.php`).
- Storage reads and writes occur under one exclusive `flock()` lock, with directory creation and atomic-length truncation handled in `taskStore()` (`app.php`).
- Escape dynamic HTML with `htmlspecialchars(..., ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')`; task titles, IDs, status messages, and errors are escaped in `public/index.php`.
- Use progressively enhanced HTML controls rather than client-side behavior: separate POST forms handle add, edit, toggle, and delete (`public/index.php`).
- The chaos tool deliberately injects scalars, missing fields, and wrong field types; valid tasks must continue rendering (`README.md`, `tools/chaos.py`).

## Intentional non-standard choices
- A local JSON file is the application database, and all visitors to one running instance share its task list (`README.md`).
- The project intentionally includes no JavaScript, framework, Composer packages, accounts, or multi-user isolation (`README.md`).
- `tools/chaos.py` must be run with PHP stopped; its restore operation intentionally replaces the current task file and discards edits made during testing (`README.md`).

## Watch out for
- The sampled PHP is currently syntactically/runtime broken and needs correction before functional review: `escape()` has a malformed signature and undefined `$value`; `b2hex()` is not a PHP function; the filter assignment is truncated; and `formFields()` has an invalid return-type declaration (`app.php`).
- `public/index.php` uses `$completed`, but the sampled `app.php` never defines it; this will produce incorrect statistics or notices.
- `tools/chaos.py` is not executable as shown: `from pathlib impo` is truncated and `Path` is therefore unavailable.
- Preserve strict input validation and output escaping when changing task schemas or templates. Do not expose `data/tasks.json` through the web root.
- The mutation closure reads `$_POST['id']` directly instead of receiving the ID as an explicit value (`app.php`); changes should avoid introducing hidden request-global dependencies.