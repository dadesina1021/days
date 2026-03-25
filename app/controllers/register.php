<?php require_once '../app/views/layouts/header.php'; ?>

<div class="container mt-5">
    <h2>Create your DAYS account</h2>
    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" class="form-control" name="username">
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email">
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="password">
        </div>
        <button type="submit" class="btn btn-success">Register</button>
    </form>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>