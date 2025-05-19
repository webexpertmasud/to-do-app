<?php
// File where tasks are stored
$file = 'tasks.json';

// Load tasks from JSON file
$tasks = [];
if (file_exists($file)) {
    $tasks = json_decode(file_get_contents($file), true);
    if (!is_array($tasks)) {
        $tasks = [];
    }
}

// Function to save tasks back to JSON file
function save_tasks($tasks, $file) {
    file_put_contents($file, json_encode($tasks, JSON_PRETTY_PRINT));
}

// Handle add task
if (isset($_POST['add_task'])) {
    $new_task = trim($_POST['task']);
    if ($new_task !== '') {
        // Add new task (not done)
        $tasks[] = ['task' => htmlspecialchars($new_task), 'done' => false];
        save_tasks($tasks, $file);
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Handle mark done/undone
if (isset($_GET['toggle'])) {
    $index = (int) $_GET['toggle'];
    if (isset($tasks[$index])) {
        $tasks[$index]['done'] = !$tasks[$index]['done'];
        save_tasks($tasks, $file);
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Handle delete task
if (isset($_GET['delete'])) {
    $index = (int) $_GET['delete'];
    if (isset($tasks[$index])) {
        array_splice($tasks, $index, 1);
        save_tasks($tasks, $file);
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Simple To-Do App</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.min.css">
    <style>
        body {
            max-width: 500px;
            margin: 2em auto;
        }
        .done {
            text-decoration: line-through;
            color: gray;
        }
        .task-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
        }
        .task-text {
            flex-grow: 1;
        }
        button.delete-btn {
            background-color: #e74c3c;
            border: none;
            color: white;
            padding: 4px 8px;
            cursor: pointer;
            border-radius: 3px;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <h2>Simple To-Do App</h2>

    <!-- Add Task Form -->
    <form method="post" action="">
        <input type="text" name="task" placeholder="Enter new task" required />
        <button type="submit" name="add_task">Add Task</button>
    </form>

    <ul style="list-style: none; padding: 0; margin-top: 20px;">
        <?php foreach ($tasks as $index => $task): ?>
            <li class="task-item">
                <a href="?toggle=<?= $index ?>" class="task-text <?= $task['done'] ? 'done' : '' ?>">
                    <?= $task['task'] ?>
                </a>
                <a href="?delete=<?= $index ?>" onclick="return confirm('Delete this task?');">
                    <button class="delete-btn" type="button">Delete</button>
                </a>
            </li>
        <?php endforeach; ?>
        <?php if (empty($tasks)): ?>
            <li>No tasks yet!</li>
        <?php endif; ?>
    </ul>

</body>
</html>
