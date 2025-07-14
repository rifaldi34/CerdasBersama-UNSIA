<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="/public/assets/bs4_offline/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Register</div>
                <div class="card-body">
                    <?php if (!empty(
                        $errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <form method="post" action="<?= base_url('Auth/registerP') ?>">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="irl_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="irl_name" name="irl_name" value="<?= esc($old['irl_name'] ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?= esc($old['username'] ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Register</button>
                        <a href="<?= base_url('Auth/login') ?>" class="btn btn-link">Already have an account?</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html> 