<?php require dirname(__DIR__) . '/bootstrap.php'; ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daylist — PHP Task Manager</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main>
    <header><a class="brand" href="./"><span class="logo">✓</span> daylist<span class="dot">.</span></a><span class="tag">A LITTLE FOCUS GOES A LONG WAY</span></header>
    <section class="intro"><p class="eyebrow">YOUR EVERYDAY WORKSPACE</p><h1>Small steps.<br><span>Good progress.</span></h1><p>Clear your mind. Write it down. Make things happen.</p></section>
    <section class="stats" aria-label="Task statistics">
        <div><strong><?= count($tasks) ?></strong><span>Total tasks</span></div>
        <div><strong><?= count($tasks) - $completed ?></strong><span>In progress</span></div>
        <div><strong><?= $completed ?></strong><span>Completed</span></div>
    </section>
    <?php if ($error): ?><p class="notice error" role="alert"><?= escape($error) ?></p><?php endif; ?>
    <?php if ($message): ?><p class="notice" role="status"><?= escape($message) ?></p><?php endif; ?>
    <section class="workspace" aria-label="Tasks">
        <form method="post" action="./" class="add-form">
            <?php formFields('add'); ?>
            <label class="sr-only" for="title">New task</label>
            <input id="title" name="title" placeholder="What would you like to get done?" maxlength="200" required autocomplete="off">
            <button class="primary">+ Add task</button>
        </form>
        <nav aria-label="Filter tasks"><?php foreach (['all' => 'All tasks', 'active' => 'In progress', 'done' => 'Completed'] as $key => $label): ?><a href="?filter=<?= $key ?>" <?= $filter === $key ? 'aria-current="page" class="selected"' : '' ?>><?= $label ?></a><?php endforeach; ?></nav>
        <div class="task-list">
        <?php if (!$visible): ?>
            <div class="empty"><span>✦</span><h2><?= $filter === 'all' ? 'A fresh start awaits' : 'Nothing here just yet' ?></h2><p><?= $filter === 'all' ? 'Add your first task above and take it one step at a time.' : 'Tasks matching this filter will appear here.' ?></p></div>
        <?php endif; ?>
        <?php foreach ($visible as $task): ?>
            <article class="task <?= $task['done'] ? 'done' : '' ?>">
                <form method="post" action="./"><?php formFields('toggle', $task['id']); ?><button class="check" aria-label="<?= escape(($task['done'] ? 'Mark incomplete: ' : 'Complete: ') . $task['title']) ?>"><?= $task['done'] ? '✓' : '' ?></button></form>
                <div class="task-content"><p><?= escape($task['title']) ?></p>
                    <details><summary>Edit task</summary><form method="post" action="./" class="edit-form"><?php formFields('edit', $task['id']); ?><label class="sr-only" for="edit-<?= escape($task['id']) ?>">Task title</label><input id="edit-<?= escape($task['id']) ?>" name="title" value="<?= escape($task['title']) ?>" maxlength="200" required><button class="primary">Save</button></form></details>
                </div>
                <details class="delete"><summary>Delete</summary><form method="post" action="./"><?php formFields('delete', $task['id']); ?><button class="danger">Confirm delete</button></form></details>
            </article>
        <?php endforeach; ?>
        </div>
    </section>
    <footer><span>One task at a time.</span><span>Built with PHP · Stored locally</span></footer>
</main>
</body>
</html>
