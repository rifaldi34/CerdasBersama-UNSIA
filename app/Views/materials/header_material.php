<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token-name" content="<?= csrf_token() ?>">
  <meta name="csrf-token-hash" content="<?= csrf_hash() ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Open Course App</title>
  <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>📝</text></svg>">
  <link href="<?= base_url('assets/bs5_offline/bootstrap.min.css') ?>" rel="stylesheet">
  <script src="<?= base_url('assets/jquery/jquery-3.7.1.min.js') ?>"></script>
  <script src="<?= base_url('assets/bs5_offline/bootstrap.bundle.min.js') ?>"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <script src="<?= base_url('assets/swal/sweetalert2@11.js') ?>"></script>
  <style>
    body { min-height: 100vh; }
    .navbar-brand { font-weight: bold; }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= base_url('Material/index') ?>">StudySpace UNSIA</a>
    <div class="d-flex">
      <?php if (session('user_token')): ?>
        <a class="btn btn-outline-danger" href="<?= base_url('Auth/logout') ?>">Logout</a>
      <?php else: ?>
        <a class="btn btn-outline-primary" href="<?= base_url('Auth/login') ?>">Login/Register</a>
      <?php endif; ?>
    </div>
  </div>
</nav> 