# Task data format

Daylist stores tasks in `data/tasks.json`, relative to the project folder.
The file contains a JSON array of task objects. See
[`examples/tasks.json`](../examples/tasks.json) for sample records.

| Field | Type | Requirement |
| --- | --- | --- |
| `id` | string | Nonempty; use a unique value for each task. New tasks receive 24 hexadecimal characters. |
| `title` | string | Nonempty after trimming whitespace; at most 200 UTF-8 bytes. |
| `done` | boolean | `true` for completed tasks, `false` for active tasks. |

The storage reader filters out records with missing or incorrectly typed fields.
Malformed JSON causes a storage error instead of being treated as an empty list.
An empty file is treated as an empty list. New tasks are inserted at the front.

The 200-byte limit is measured by PHP's `strlen`, so a title with non-ASCII
characters may reach the limit before it contains 200 characters.

## Trying the sample

Stop the PHP server and back up any existing `data/tasks.json` first. Create the
`data` folder if needed, then copy `examples/tasks.json` to `data/tasks.json`.
Restart the server. This replaces the active task list with three sample tasks;
it does not merge them with existing tasks. The example file itself is never
loaded automatically.

Stop the server again before restoring your backup. Keep the data directory
outside the web document root.
