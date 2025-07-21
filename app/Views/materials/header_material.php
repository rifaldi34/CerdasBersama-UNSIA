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
    body {
      min-height: 100vh;
      background-color: #f8f9fc;
      font-family: 'Segoe UI', sans-serif;
    }
    .navbar-brand {
      font-weight: 800;
      font-size: 1.4rem;
      color: #fff !important;
      text-shadow: none;
    }
    .header-color {
      background: linear-gradient(90deg, #27408b 60%, #4362b6 100%);
      border-bottom: 3px solid #ffc107;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }
    .navbar {
      border-radius: 0 0 16px 16px;
      padding-top: 0.5rem;
      padding-bottom: 0.5rem;
    }
    .btn-outline-danger,
    .btn-outline-primary {
      border-width: 2px;
      font-weight: 600;
      font-size: 0.95rem;
      letter-spacing: 0.5px;
      border-radius: 8px;
      padding: 0.4rem 1rem;
    }
    .btn-outline-primary {
      border-color: #ffc107 !important;
      color: #3b5998 !important;
      background-color: #fff !important;
    }
    .btn-outline-primary:hover {
      background-color: #ffc107 !important;
      color: #fff !important;
    }
    .btn-outline-view {
      border-color: #3567dcff !important;
      color: #3567dcff !important;
      background-color: #fff !important;
    }
    .btn-outline-danger {
      border-color: #dc3545 !important;
      color: #dc3545 !important;
      background-color: #fff !important;
    }
    .btn-outline-danger:hover {
      background-color: #dc3545 !important;
      color: #fff !important;
    }
    @media (max-width: 576px) {
      .navbar-brand {
        font-size: 1.1rem;
      }
    }
    .card-actions .btn {
      min-width: 90px;
      margin: 4px 6px 0 0;
      font-weight: 500;
      border-width: 2px;
      padding: 6px 12px;
      font-size: 0.875rem;
    }

    .card-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 6px;
      margin-top: 1rem;
    }

    .btn-outline-approve {
      color: #198754;
      border-color: #198754;
      background-color: #fff;
    }

    .btn-outline-approve:hover {
      background-color: #198754;
      color: #fff;
    }

    .btn-outline-reject {
      color: #dc3545;
      border-color: #dc3545;
      background-color: #fff;
    }

    .btn-outline-reject:hover {
      background-color: #dc3545;
      color: #fff;
    }
    .navbar-toggler {
      border: none;
      outline: none;
    }

    .navbar-toggler-icon {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%23ffffff' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 255, 255, 1)' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }
  </style>
</head>
<body>
<nav class="navbar header-color shadow-sm mb-4">
  <div class="container-fluid d-flex justify-content-between align-items-center px-4 py-2">
    <div class="d-flex align-items-center gap-2">
      <i class="bi bi-journal-code fs-4 text-warning"></i>
      <a class="navbar-brand m-0 p-0" href="<?= base_url('Material') ?>">
        Cerdas Bersama-UNSIA
      </a>
    </div>

    <div class="d-flex align-items-center">
      <?php if (session('user_token')): ?>
        <a class="btn btn-outline-danger" href="<?= base_url('Auth/logout') ?>">
          <i class="bi bi-box-arrow-right me-1"></i> Logout
        </a>
      <?php else: ?>
        <a class="btn btn-outline-primary" href="<?= base_url('Auth/login') ?>">
          <i class="bi bi-person-circle me-1"></i> Login/Register
        </a>
      <?php endif; ?>
    </div>
  </div>
</nav>
