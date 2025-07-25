<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login / Register – StudySpace</title>
  <link href="<?= base_url('public/assets/bs4_offline/bootstrap.min.css') ?>" rel="stylesheet">
  <style>
    body {
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: #f8f9fa;
    }
    .card {
      width: 400px;
      padding: 20px;
    }
    .toggle-link {
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div class="card shadow-sm">
    <div class="card-body">
      <!-- LOGIN FORM -->
      <div id="login-form">
        <h3 class="text-center mb-4">📚 Login To <br> <b>Cerdas Bersama-UNSIA</b></h3>
        <form action="<?= base_url('Auth/loginp') ?>" method="post">
          <?= csrf_field() ?>
          <div class="form-group">
            <label for="login-username">Username</label>
            <input name="username" id="login-username" class="form-control" placeholder="Enter Username" required>
          </div>
          <div class="form-group">
            <label for="login-password">Password</label>
            <input type="password" name="password" id="login-password" class="form-control" placeholder="Enter Password" required>
          </div>
          <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
        <hr>
        <p class="text-center mb-0">
          Don’t have an account?
          <span class="text-primary toggle-link" data-target="#register-form">Register</span>
        </p>
      </div>

      <!-- REGISTER FORM -->
      <div id="register-form" style="display: none;">
        <h3 class="text-center mb-4">Register for StudySpace</h3>
        <!-- Display validation errors, if any -->
        <?php if (! empty($errors)): ?>
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
          <div class="form-group">
            <label for="register-fullname">Full Name</label>
            <input type="text" class="form-control" id="register-fullname" name="irl_name"
                   value="<?= esc($old['irl_name'] ?? '') ?>" required>
          </div>
          <div class="form-group">
            <label for="register-username">Username</label>
            <input type="text" class="form-control" id="register-username" name="username"
                   value="<?= esc($old['username'] ?? '') ?>" required>
          </div>
          <div class="form-group">
            <label for="register-password">Password</label>
            <input type="password" class="form-control" id="register-password" name="password" required>
          </div>
          <button type="submit" class="btn btn-success btn-block">Register</button>
        </form>
        <hr>
        <p class="text-center mb-0">
          Already have an account?
          <span class="text-primary toggle-link" data-target="#login-form">Login</span>
        </p>
      </div>
    </div>
  </div>

  <!-- JS dependencies -->
  <script src="<?= base_url('public/assets/jquery/jquery-3.7.1.min.js') ?>"></script>
  <script src="<?= base_url('public/assets/bs4_offline/popper.min.js') ?>"></script>
  <script src="<?= base_url('public/assets/bs4_offline/bootstrap.min.js') ?>"></script>

  <!-- Toggle script -->
  <script>
    $(function(){
      $('.toggle-link').on('click', function(){
        var showId = $(this).data('target');
        // hide both, then show the one we want
        $('#login-form, #register-form').hide();
        $(showId).fadeIn();
      });
    });
  </script>
  <script src="<?= base_url('assets/swal/sweetalert2@11.js') ?>"></script>
  <?php if (session()->getFlashdata('login_error')): ?>
  <script>
      Swal.fire({
          icon: 'error',
          title: 'Gagal Login',
          text: '<?= session()->getFlashdata('login_error') ?>',
          confirmButtonColor: '#d33'
      });
  </script>
  <?php endif; ?>
</body>
</html>