<?php 
$uri = service('uri');

$menu = [
  [
      'title'   => 'Home',
      'submenu' => [
          [
              'title' => 'Profile',
              'uri'   => 'Member',  // compare with $uri->getSegment(2)
              'link'  => base_url('Cms/Member')
          ]
      ]
  ],
  [
      'title'   => 'Settings',
      'submenu' => [
          [
              'title' => 'Accounts',
              'uri'   => 'AdminList',
              'link'  => base_url('Cms/AdminList')
          ],
          [
              'title' => 'Group',
              'uri'   => 'GroupList',
              'link'  => base_url('Cms/GroupList')
          ],
          [
            'title' => 'CrudSample',
            'uri'   => 'CrudSample',
            'link'  => base_url('Generated/CrudSample')
          ]
          
      ]
  ],
  [
      'title' => 'Logout',
      'link'  => '#'
  ]
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token-name" content="<?= csrf_token() ?>">
  <meta name="csrf-token-hash" content="<?= csrf_hash() ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>APP Name</title>
  <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>📝</text></svg>">
  <link href="<?= base_url('assets/bs5_offline/bootstrap.min.css') ?>" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
  <script src="<?= base_url('assets/jquery/jquery-3.7.1.min.js') ?>" integrity="sha384-1H217gwSVyLSIfaLxHbE7dRb3v4mYCKbpQvzx0cegeju1MVsGrX5xXxAvs/HgeFs" crossorigin="anonymous"></script>
  <script src="<?= base_url('assets/bs5_offline/bootstrap.bundle.min.js') ?>" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <script src="<?= base_url('assets/swal/sweetalert2@11.js') ?>" integrity="sha384-Y1zzU5I7+ujiuXE1zuR3FJEPzfjZulUbsE9v7KDX7ztQ+fpy+aCix9RgfskCL2Oz" crossorigin="anonymous"></script>
  <style>
    body {
      min-height: 100vh;
    }
    /* Disabled style */
    a[disabled] {
      filter: grayscale(70%) brightness(90%) contrast(80%);
      pointer-events: none;
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
    .tr_selected_jambo > td{
      background-color: rgb(167, 199, 237) !important;
    }

    .form_menu{
      margin-left: 2px;
      margin-right: 2px;
    }

    .nav-link:focus{
      color:yellow;
    }

    .nav-link.active{
      color:yellow;
    }

    :root {
      --bs-border-width: 1px; /* Set your desired border width */
      --bs-border-color:#8d8d8d;
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
        <!-- <h5 class="text-center">LOGO</h5> -->
         <div class="text-center">
         <img src="<?= base_url('assets/img/logo_minifigure_compressed_nobg.png') ?>" alt="" style="width: 60%;">
         <br><br>
         </div>

        <ul class="nav flex-column">
          <?php foreach ($menu as $item): ?>
            <?php if (isset($item['submenu'])): ?>
              <?php 
                // Use the title (lowercase) to create a unique ID for the submenu collapse
                $submenuId = strtolower($item['title']) . 'Submenu';
              ?>
              <li class="nav-item">
                <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#<?= $submenuId ?>" role="button" aria-expanded="false" aria-controls="<?= $submenuId ?>">
                  <span><?= $item['title'] ?></span>
                  <i class="bi bi-chevron-down"></i>
                </a>
                <div class="collapse show" id="<?= $submenuId ?>">
                  <ul class="nav flex-column ms-3">
                    <?php foreach ($item['submenu'] as $sub): ?>
                      <?php $activeClass = ($uri->getSegment(2) == $sub['uri']) ? 'active' : ''; ?>
                      <li class="nav-item">
                        <a class="nav-link <?= $activeClass ?>" href="<?= $sub['link'] ?>"><?= $sub['title'] ?></a>
                      </li>
                    <?php endforeach; ?>
                  </ul>
                </div>
              </li>
            <?php else: ?>
              <?php 
                // For standalone items, check if a 'uri' is defined for active highlighting.
                $activeClass = (isset($item['uri']) && $uri->getSegment(2) == $item['uri']) ? 'active' : '';
              ?>
              <li class="nav-item">
                <a class="nav-link <?= $activeClass ?>" href="<?= $item['link'] ?>"><?= $item['title'] ?></a>
              </li>
            <?php endif; ?>
          <?php endforeach; ?>
        </ul>

      </nav>


      <!-- Main Content -->
      <main class="col-10" id="main-content">
        <div class="pt-1 pb-1 mb-3 border-bottom">
          <div class="d-flex align-items-center">
            <button class="btn btn-sm btn-secondary toggle-sidebar">=</button>&nbsp;&nbsp;&nbsp;&nbsp;
            <h5 class="me-auto mb-0"><?= str_replace('_', ' ', ucwords(implode(' / ', $uri->getSegments()))) ?></h5>
            <a class="btn btn-sm btn-primary" href="<?= base_url('Auth/Logout') ?>">Logout</a>
          </div>
        </div>

<?php unset($uri); ?>