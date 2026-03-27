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
    <link rel="stylesheet" href="/~2020584/public/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Welcome to Days, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>

        <p>
            <a class="logout-link" href="/~2020584/public/index.php?url=logout">Logout</a>
        </p>

        <div class="quote-box">
            <h2>Daily Motivation</h2>
            <p class="quote-text" id="quoteText">Click the button below to load a motivational quote.</p>
            <p class="quote-author" id="quoteAuthor"></p>
            <button type="button" onclick="loadQuote()">Load New Quote</button>
            <p style="margin-top:10px; font-size:14px;">
                Quotes provided by ZenQuotes
            </p>
        </div>

        <h2>Add a new task</h2>

        <form id="taskForm">
            <input type="text" id="taskTitle" name="title" placeholder="Enter a task..." required>
            <button type="submit">Add Task</button>
        </form>

        <div id="taskMessage"></div>

        <h2>Your tasks</h2>

        <ul id="taskList">
            <?php if (!empty($tasks)): ?>
                <?php foreach ($tasks as $task): ?>
                    <li>
                        <div class="task-row">
                            <span class="task-title <?php echo $task['is_completed'] ? 'completed' : ''; ?>">
                                <?php echo htmlspecialchars($task['title']); ?>
                            </span>

                            <div class="actions">
                                <button onclick="toggleTask(<?php echo $task['id']; ?>)">
                                    <?php echo $task['is_completed'] ? 'Undo' : 'Complete'; ?>
                                </button>

                                <button onclick="deleteTask(<?php echo $task['id']; ?>)">Delete</button>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p id="emptyMessage">No tasks yet. Add your first task above.</p>
            <?php endif; ?>
        </ul>
    </div>

    <script>
        const taskForm = document.getElementById('taskForm');
        const taskTitle = document.getElementById('taskTitle');
        const taskList = document.getElementById('taskList');
        const taskMessage = document.getElementById('taskMessage');
        const quoteText = document.getElementById('quoteText');
        const quoteAuthor = document.getElementById('quoteAuthor');

        function renderTasks(tasks) {
            taskList.innerHTML = '';

            if (!tasks || tasks.length === 0) {
                taskList.innerHTML = '<p id="emptyMessage">No tasks yet. Add your first task above.</p>';
                return;
            }

            tasks.forEach(task => {
                const li = document.createElement('li');

                li.innerHTML = `
                    <div class="task-row">
                        <span class="task-title ${task.is_completed == 1 ? 'completed' : ''}">
                            ${escapeHtml(task.title)}
                        </span>

                        <div class="actions">
                            <button onclick="toggleTask(${task.id})">
                                ${task.is_completed == 1 ? 'Undo' : 'Complete'}
                            </button>

                            <button onclick="deleteTask(${task.id})">Delete</button>
                        </div>
                    </div>
                `;

                taskList.appendChild(li);
            });
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        taskForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData();
            formData.append('title', taskTitle.value);

            fetch('/~2020584/public/index.php?url=ajax-store-task', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderTasks(data.tasks);
                    taskTitle.value = '';
                    taskMessage.innerHTML = '<div class="message-success"><p>Task added successfully.</p></div>';
                } else {
                    taskMessage.innerHTML = '<div class="message-error"><p>' + data.message + '</p></div>';
                }
            })
            .catch(() => {
                taskMessage.innerHTML = '<div class="message-error"><p>Something went wrong.</p></div>';
            });
        });

        function toggleTask(taskId) {
            const formData = new FormData();
            formData.append('task_id', taskId);

            fetch('/~2020584/public/index.php?url=ajax-toggle-task', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderTasks(data.tasks);
                }
            });
        }

        function deleteTask(taskId) {
            const formData = new FormData();
            formData.append('task_id', taskId);

            fetch('/~2020584/public/index.php?url=ajax-delete-task', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderTasks(data.tasks);
                }
            });
        }

        function loadQuote() {
    fetch('/~2020584/public/index.php?url=ajax-quote')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                quoteText.textContent = `"${data.quote}"`;
                quoteAuthor.textContent = `— ${data.author}`;
            } else {
                quoteText.textContent = 'Could not load quote right now.';
                quoteAuthor.textContent = '';
            }
        })
        .catch(() => {
            quoteText.textContent = 'Could not load quote right now.';
            quoteAuthor.textContent = '';
        });
}
        window.addEventListener('DOMContentLoaded', loadQuote);
    </script>
</body>
</html>