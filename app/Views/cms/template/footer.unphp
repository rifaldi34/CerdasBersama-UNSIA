        </main>
    </div>
  </div>
  
<script>
  $(document).ready(function() {
      <?php if (session()->has('custom_success')): ?>
          Swal.fire({
            //   toast: true,
            //   position: 'bottom-start',
              icon: 'success',
              title: '<?= session()->get("custom_success") ?>',
              showConfirmButton: true,
            //   timer: 3000,
            //   timerProgressBar: true
          });
          <?php session()->remove('custom_success'); ?>
      <?php endif; ?>

      <?php if (session()->has('custom_error')): ?>
          Swal.fire({
            //   toast: true,
            //   position: 'bottom-start',
              icon: 'error',
              title: '<?= session()->get("custom_error") ?>',
              showConfirmButton: true,
            //   timer: 3000,
            //   timerProgressBar: true
          });
          <?php session()->remove('custom_error'); ?>
      <?php endif; ?>
  });
</script>


</body>
</html>