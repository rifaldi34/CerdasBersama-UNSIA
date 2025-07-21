
<h1>Crud Sample</h1>

<form action="<?php echo base_url($base_url_table); ?>" method="GET">
    <table>
    <tbody>
        <tr>
            <td class="module_filter" style="width: 600pt;">Filter By : 
            <select name="filter_type" class="form_select" id="filter_type">
                <option value="testing_name">Testing Name</option>
                <option value="testing_description">Testing Description</option>
            </select>
                        
                <input type="text" name="filter_val" id="filter_val" value="<?= service('request')->getGet('filter_val') ?>" style="width:200px;">
                <input type="submit" value="Find" class="btn btn-success btn-sm" >
            </td>
            <td class="module_filter" style="padding-left:10px;">
                <!-- Sort By : 
                <select name="sort_field" id="sort_field" class="form_select">
                    <option value="PROD_ID" >Product ID</option>/n		
                </select>
                <select name="sort_type" id="sort_type" class="form_select">
                    <option value="asc" >Asc</option>/n<option value="desc" >Desc</option>/n		
                </select>
                
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                <input type="submit" value="Find" class="btn btn-success btn-sm" >-->
            </td>	
        </tr>
        </tbody>
    </table>

    <hr>

</form>

<table style="margin-bottom: 10px;">
    <tr style="text-align: center;">
        <td>
            <a href="<?php echo base_url($base_url_table.'/Add'); ?>" class="btn form_menu btn-success" <?= $lib_auth->btn_checkpermis('crudsample', "add") ?>>Add</a>
        </td>
        <td>
            <button class="btn form_menu btn-warning" id="btnEdit" <?= $lib_auth->btn_checkpermis('crudsample', "edit") ?>>Edit</button>
        </td>
        <td>
            <button class="btn form_menu btn-danger" id="btnDelete" <?= $lib_auth->btn_checkpermis('crudsample', "delete") ?>>Delete</button>
        </td>
        <td>
            <button class="btn form_menu btn-success" onclick="location.reload();">Refresh</button>
        </td>
    </tr>
</table>

<?php 


    $data_shown = [
        'rec_id' => 'ID',
        'testing_name' => 'Testing Name',
        'testing_description' => 'Testing Description',
    ];

    $allow_sort = ['rec_id', 'testing_name'];

    $table_arr['primary_key'] = 'rec_id';
    $table_arr['url'] = $base_url_table;
    $table_arr['data_shown'] = $data_shown;
    $table_arr['allow_sort'] = $allow_sort;
    $table_arr['data_obj'] = $table_compiled_1['data_current'];
    $table_arr['pager_links'] = $pager_links;

    echo view('cms/template/table', $table_arr);
?>
