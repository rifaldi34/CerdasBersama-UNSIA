<form id="main_form" action="IN_JAVASCRIPT" method="POST">
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" id="csrf_form_field1" />
    <?php if (!empty($data_obj_table->rec_id)): ?>
        <input type="hidden" name="rec_id" value="<?= esc($data_obj_table->rec_id ?? '') ?>">
    <?php endif; ?>

    <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" class="form-control" name="testing_name" value="<?= esc($data_obj_table->testing_name ?? '') ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea class="form-control" name="testing_description"><?= esc($data_obj_table->testing_description ?? '') ?></textarea>
    </div>

    <button type="submit" class="btn btn-primary d-none" id="submit_real">Submit</button>
    <button type="button" class="btn btn-primary" id="submit_fake">Submit</button>
    <a type="button" class="btn btn-secondary ms-5" href="<?= base_url('Generated/CrudSample') ?>">Cancel</a>
</form>

<script>
$(document).ready(function() {
    var originalAction = "<?= base_url('Generated/CrudSample/Process?ROLE=' . $role) ?>";

    $('#submit_fake').on('click', function() {
        var $form = $('#main_form');
        var validateUrl = originalAction + '&validate_only=yes';

        // Get CSRF values from meta tags
        const csrfName = $('meta[name="csrf-token-name"]').attr('content');
        const csrfHash = $('meta[name="csrf-token-hash"]').attr('content');

        var formData = $form.serialize();

        // Perform server-side validation via AJAX
        $.ajax({
            url: validateUrl,
            method: 'POST',
            data: formData,
            dataType: 'json'
        }).done(function(response) {
            $('meta[name="csrf-token-hash"]').attr('content', response.csrf_hash);
            $('#csrf_form_field1').val(response.csrf_hash);

            if (response.accepted) {
                // If validation passes, show confirmation
                Swal.fire({
                    title: "Are you sure?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit real form without validation flag
                        $form.attr('action', originalAction);
                        $('#submit_real').click();
                    }
                });
            } else {
                // Show validation errors in a SweetAlert
                var errorHtml = '';
                errorHtml = response.errors;
                Swal.fire({
                    title: "Validation Failed",
                    icon: "error",
                    text: errorHtml
                });
            }
        }).fail(function() {
            Swal.fire('Error', 'Validation request failed. Please try again.', 'error');
        });
    });
});
</script>
