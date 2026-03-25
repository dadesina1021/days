<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Days | Tasks</title>
</head>
<body>
    <h1>Welcome to Days, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>

    <p>
        <a href="/~2020584/public/index.php?url=logout">Logout</a>
    </p>

    <h2>Add a new task</h2>

    <form action="/~2020584/public/index.php?url=store-task" method="POST">
        <input type="text" name="title" placeholder="Enter a task..." required>
        <button type="submit">Add Task</button>
    </form>

    <h2>Your tasks</h2>

    <?php if (!empty($tasks)): ?>
        <ul>
            <?php foreach ($tasks as $task): ?>
                <li>
                    <?php if ($task['is_completed']): ?>
                        <span style="text-decoration: line-through;">
                            <?php echo htmlspecialchars($task['title']); ?>
                        </span>
                    <?php else: ?>
                        <span>
                            <?php echo htmlspecialchars($task['title']); ?>
                        </span>
                    <?php endif; ?>

                    <form action="/~2020584/public/index.php?url=toggle-task" method="POST" style="display:inline;">
                        <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                        <button type="submit">
                            <?php echo $task['is_completed'] ? 'Undo' : 'Complete'; ?>
                        </button>
                    </form>

                    <form action="/~2020584/public/index.php?url=delete-task" method="POST" style="display:inline;">
                        <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                        <button type="submit">Delete</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No tasks yet. Add your first task above.</p>
    <?php endif; ?>
</body>
</html>