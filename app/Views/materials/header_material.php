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
    body { min-height: 100vh }
    .navbar-brand {
      font-weight: 900;
      letter-spacing: 2px;
      font-size: 1.6rem;
      color: #fff !important;
      text-shadow: 1px 2px 8px rgba(67,98,182,0.18), 0 1px 0 #2a3a6e;
    }
    .header-color {
      background: linear-gradient(90deg, #27408b 60%, #4362b6 100%);
      box-shadow: 0 4px 16px rgba(39,64,139,0.15);
      border-bottom: 4px solid #ffb300;
    }
    .navbar {
      border-radius: 0 0 18px 18px;
      padding-top: 0.7rem;
      padding-bottom: 0.7rem;
    }
    .btn-outline-danger, .btn-outline-primary {
      border-width: 2.5px;
      font-weight: 700;
      font-size: 1.05rem;
      letter-spacing: 1px;
      box-shadow: 0 2px 8px rgba(67,98,182,0.08);
      transition: background 0.2s, color 0.2s, box-shadow 0.2s;
      background: #fff !important;
      color: #27408b !important;
      border-color: #ffb300 !important;
      margin-left: 0.5rem;
    }
    .btn-outline-danger:hover, .btn-outline-primary:hover {
      background: #ffb300 !important;
      color: #fff !important;
      border-color: #ffb300 !important;
      box-shadow: 0 4px 16px rgba(255,179,0,0.15);
    }
    @media (max-width: 576px) {
      .navbar-brand { font-size: 1.1rem; }
      .navbar { padding-left: 0.5rem; padding-right: 0.5rem; }
    }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark mb-4 header-color">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= base_url('Material/index') ?>">Cerdas Bersama-UNSIA</a>
    <div class="d-flex">
      <?php if (session('user_token')): ?>
        <a class="btn btn-outline-danger" href="<?= base_url('Auth/logout') ?>">Logout</a>
      <?php else: ?>
        <a class="btn btn-outline-primary" href="<?= base_url('Auth/login') ?>">Login/Register</a>
      <?php endif; ?>
    </div>
  </div>
</nav> 