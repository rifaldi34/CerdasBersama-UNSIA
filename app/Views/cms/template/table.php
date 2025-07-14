<?php 

$request_service = service('request');

?>

<table class="table table-striped jambo_table">
    <thead>
        <tr>
            <?php foreach ($data_shown as $id => $title):
            $arrow = '';
            if ($request_service->getGet('sort_field') == $id && $request_service->getGet('sort_type') == 'ASC') {
                $arrow = '▲';
            }elseif ($request_service->getGet('sort_field') == $id && $request_service->getGet('sort_type') == 'DESC') {
                $arrow = '▼';
            }

            if (in_array($id, $allow_sort)) {
                $allow_sort_bool = 'yes';
            }else{
                $allow_sort_bool = 'no';
            }

            ?>
                <th data-sort="<?= $id ?>" style="cursor:pointer" data-allow_sort="<?= $allow_sort_bool ?>" ><?= $arrow ?> <?= $title ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($data_obj->getResultArray() as $prod_data): ?>
        <tr data-id="<?= esc($prod_data[$primary_key]) ?>">
            <?php foreach ($data_shown as $id => $title): ?>
                <td style="<?= $custom_class[$id]??'' ?>"><?= esc($prod_data[$id]) ?></td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div style="padding-bottom: 50px">
    <?php echo $pager_links; ?>
</div>

<script>
    $(document).ready(function() {
        //onclick tr inside jambo_table
        $('.jambo_table').on('click', 'tr', function() {

            //all tr inside jambo_table removeclass
            if ($(this).closest('thead').length !== 0) {
                $('.tr_selected_jambo').removeClass('tr_selected_jambo');
            }else{
                $('.tr_selected_jambo').removeClass('tr_selected_jambo');
                $(this).addClass('tr_selected_jambo');
            }
        });

        $('.jambo_table').on('click', 'th', function() {
            if ($(this).data('allow_sort') != 'yes') {
                return;
            }

            // Get the data-sortable attribute
            var sort_by = $(this).data('sort');
            var current_sort_type = '<?= $request_service->getGet('sort_type') ?>';

            if (current_sort_type == 'ASC') {
                var sort_type = 'DESC';
            }else{
                var sort_type = 'ASC';
            }

            // Parse the current URL to get the query parameters
            var currentUrl = new URL(window.location.href);
            var searchParams = new URLSearchParams(currentUrl.search);

            // Set the new sort_field parameter
            searchParams.set('sort_field', sort_by);
            searchParams.set('sort_type', sort_type);

            // Create the new URL with the updated query parameters
            var newUrl = currentUrl.origin + currentUrl.pathname + '?' + searchParams.toString();

            // Redirect to the new URL
            window.location.href = newUrl;
        });


        //onclick btn_edit then alert tr id that has tr_selected_jambo
        $('#btnEdit').on('click', function() {
            base_url_edit = '<?= base_url($url.'/Edit?id=') ?>';
            var id = $('.tr_selected_jambo').data('id');

            //if id is undefined then alert nothing
            if (id == undefined) {
                alert('Nothing Selected');
                return;
            }

            location.href = base_url_edit + id;
        });

        $('#btnDelete').on('click', function() {
            const baseUrlDelete      = '<?= base_url($url . '/Process?ROLE=DELETE') ?>';
            const validateDeleteUrl  = baseUrlDelete + '&validate_only=yes';
            const id                 = $('.tr_selected_jambo').data('id');

            if (id === undefined) {
                return Swal.fire('Nothing Selected', 'Please select a row to delete.', 'info');
            }

            // grab current CSRF name & hash from your meta tags
            const csrfName = $('meta[name="csrf-token-name"]').attr('content');
            const csrfHash = $('meta[name="csrf-token-hash"]').attr('content');

            // step 1: server‑side validation (e.g. any business rules before deletion)
            $.post(validateDeleteUrl, { txt_id: id, [csrfName]: csrfHash }, 'json')
            .done(function(response) {
                // update the meta‑tag so next requests use the fresh token
                $('meta[name="csrf-token-hash"]').attr('content', response.csrf_hash);

                if (response.accepted) {
                    // step 2: ask the user to confirm
                    Swal.fire({
                        title: "Are you sure?",
                        text: "This action cannot be undone.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'No, keep it'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // step 3: do the real delete, using the updated hash
                            const data = {
                                txt_id: id,
                                [csrfName]: $('meta[name="csrf-token-hash"]').attr('content')
                            };

                            $.post(baseUrlDelete, data)
                            .done(function(response2) {
                                if (response2.accepted) {
                                    Swal.fire('Deleted!', 'Your record has been removed.', 'success')
                                    .then(() => location.reload());    
                                }else{
                                    $('meta[name="csrf-token-hash"]').attr('content', response2.csrf_hash);
                                    let message_error = response2.errors;
                                    Swal.fire('Error!', message_error, 'error');
                                }
                                
                            })
                            .fail(function() {
                                Swal.fire('Error!', 'There was a problem deleting the record.', 'error');
                            });
                        }
                    });
                } else {
                    // validation failed — show errors
                    Swal.fire('Cannot Delete', response.errors, 'error');
                }
            })
            .fail(function() {
                Swal.fire('Error', 'Validation request failed. Please try again.', 'error');
            });
        });


    })
</script>