<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body py-2">
                <h4 class="page-title d-inline-block">
                    <i class="mdi mdi-account-circle title_icon"></i> <?php echo get_phrase('drivers'); ?>
                </h4>
                <button type="button" class="btn btn-outline-primary btn-rounded alignToTitle float-end mt-1"
                    onclick="rightModal('<?php echo site_url('modal/popup/driver/create'); ?>', '<?php echo get_phrase('create_driver'); ?>')"> <i class="mdi mdi-plus"></i>
                    <?php echo get_phrase('create_driver'); ?></button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body driver_content">
                <?php include 'list.php'; ?>
            </div>
        </div>
    </div>
</div>

<script>
var showAllDrivers = function() {
    var url = '<?php echo route('driver/list'); ?>';

    $.ajax({
        type: 'GET',
        url: url,
        success: function(response) {
            $('.driver_content').html(response);
            initDataTable('basic-datatable');
        }
    });
}
</script>
