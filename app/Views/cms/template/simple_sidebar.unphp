<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Responsive Dashboard</title>
  <link href="<?= base_url('assets/bs5_offline/bootstrap.min.css') ?>" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
  <script src="<?= base_url('assets/jquery/jquery-3.7.1.min.js') ?>" integrity="sha384-1H217gwSVyLSIfaLxHbE7dRb3v4mYCKbpQvzx0cegeju1MVsGrX5xXxAvs/HgeFs" crossorigin="anonymous"></script>
  <script src="<?= base_url('assets/bs5_offline/bootstrap.bundle.min.js') ?>" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <style>
    body {
      min-height: 100vh;
    }
    #sidebar {
      height: 100vh;
      background-color: #343a40;
      color: white;
      padding-top: 5px;
      overflow-y: auto;
    }
    .sidebar a {
      color: #ddd;
      text-decoration: none;
      display: block;
      padding: 0.5rem 1rem;
    }
    .sidebar a:hover {
      background-color: #495057;
      color: white;
    }
    .sidebar.hidden {
      display: none;
    }

    #main-content {
      height: 100vh;
      overflow-y: auto;
    }

    .toggle-sidebar {
      display: none;
    }

  </style>
</head>
<body>
  <script>
    $(document).ready(function() {
      // Handle screen size and toggle button visibility
      function handleScreenSize() {
        if ($(window).width() <= 768) {
          $('.toggle-sidebar').show();
          $('#sidebar').hide();
          $('#main-content').removeClass('col-10').addClass('col-12');
          $('#sidebar').removeClass('col-2').addClass('col-12');
        } else {
          $('.toggle-sidebar').hide();
          $('#sidebar').show();
          $('#main-content').removeClass('col-12').addClass('col-10');
          $('#sidebar').removeClass('col-12').addClass('col-2');
        }
      }
  
      // Initial check on page load
      handleScreenSize();
  
      // Check again on window resize
      $(window).resize(function() {
        handleScreenSize();
      });

      let sidebaron = false;
      // Toggle sidebar visibility when button is clicked
      $('.toggle-sidebar').click(function() {
        sidebaron = !sidebaron;
        if (sidebaron) {
          $('#sidebar').show();
          $('#main-content').hide();
        }else{
          $('#sidebar').hide();
          $('#main-content').show();
        }
      });
    });
  </script>

  <div class="container-fluid">
    <div class="row">

      <!-- Sidebar -->
      <nav class="col-2 sidebar" id="sidebar">
        <div class="d-flex justify-content-end">
          <button class="btn btn-sm btn-secondary toggle-sidebar">=</button>
        </div>
        <h5 class="text-center">LOGO</h5>
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Profile</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Settings</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Logout</a>
          </li>
        </ul>
      </nav>

      <!-- Main Content -->
      <main class="col-10" id="main-content">
        <div class="pt-1 pb-1 mb-3 border-bottom">
          <div class="d-flex align-items-center">
            <button class="btn btn-sm btn-secondary toggle-sidebar">=</button>&nbsp;&nbsp;&nbsp;&nbsp;
            <h5 class="me-auto mb-0">Header</h5>
            <button class="btn btn-sm btn-primary">Logout</button>
          </div>
        </div>
        <p>This is the main content area. You can add charts, tables, or other content here.
        </p>
      </main>
    </div>
  </div>

</body>
</html>