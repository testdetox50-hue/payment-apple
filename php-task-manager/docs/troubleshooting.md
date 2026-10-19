# Troubleshooting Daylist

Run these commands from the `php-task-manager` folder.

## The page is blank or reports a PHP parse error

Check syntax before starting the server:

```sh
php -l app.php
php -l public/index.php
php -l public/health.php
php -l public/about.php
```

Resolve reported syntax errors before checking browser behavior. At the time
this guide was added, `app.php` contained malformed function declarations and
a damaged filter assignment. Its session initialization also used `b2hex`
instead of PHP's `bin2hex`. These issues need repair for the task page to run.
Syntax checks alone do not detect every runtime error.

## PHP is not recognized

Install PHP 8.0 or newer and add its executable directory to your PATH, or use
the executable's full path. With a default Windows XAMPP installation:

```powershell
C:\xampp\php\php.exe -S localhost:8000 -t public
```

## The server runs but tasks do not load

Read the terminal output from the PHP server. Confirm PHP can create and write
`data/tasks.json`. Back up the file before changing it, and check that it
contains valid JSON matching the [data format](data-format.md).

The `/health.php` endpoint returns a small JSON response, but does not load
`app.php` or check task storage. A successful health response does not establish
that the task page works.

## A form reports that the session expired

Refresh the page and retry with cookies enabled. Forms must submit the CSRF
token matching the current PHP session. If fresh forms consistently fail,
inspect `formFields` in `app.php` and confirm it outputs a hidden `csrf` field
alongside the action and task ID.

## Changes disappear after chaos testing

The restore command in `tools/chaos.py` replaces tasks with the saved backup.
Edits made after injection are not retained by restoration. Follow the
[README instructions](../README.md#python-chaos-test), stopping the PHP server
before both injection and restoration.
