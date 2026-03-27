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
    <title>Days | Register</title>
    <link rel="stylesheet" href="/~2020584/public/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Create your Days account</h1>

        <?php if (!empty($_SESSION['errors'])): ?>
            <div class="message-error">
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <form action="/~2020584/public/index.php?url=register" method="POST">
            <label for="username">Username</label>
            <input
                type="text"
                id="username"
                name="username"
                value="<?php echo htmlspecialchars($_SESSION['old']['username'] ?? ''); ?>"
                required
            >

            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="<?php echo htmlspecialchars($_SESSION['old']['email'] ?? ''); ?>"
                required
            >

            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                required
            >

            <label for="confirm_password">Confirm Password</label>
            <input
                type="password"
                id="confirm_password"
                name="confirm_password"
                required
            >

            <button type="submit">Register</button>
        </form>

        <p class="auth-link">
            Already have an account?
            <a href="/~2020584/public/index.php?url=login">Login here</a>
        </p>

        <?php unset($_SESSION['old']); ?>
    </div>
</body>
</html>