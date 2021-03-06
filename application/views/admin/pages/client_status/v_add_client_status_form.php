<form id='create_new_client_status' action="" enctype="multipart/form-data" method="post"
      accept-charset="utf-8">
    <div class="box-body">
        <div id="status"></div>
        <div class="form-group">
            <label for=""> Client Status Name </label>
            <input type="text" class="form-control" id="client_status_name" name="client_status_name" value=""
                   placeholder="">
            <span id="error_client_status_name" class="has-error"></span>
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        <input type="submit" id="submit" name="submit" value="Save" class="btn btn-primary">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        <small><img id="loader" src="<?php echo site_url('assets/images/loadingg.gif'); ?>"/></small>
    </div>
</form>
<script>

    $('#client_status_name').keyup(function () {

        var accountRegex = /^[a-zA-Z0-9-&_ ]+$/;
        var client_status_name = $("#client_status_name").val();

        if (!(accountRegex.test(client_status_name))) {
            $("#error_client_status_name").html('Allowed Characters are a-zA-Z-&0-9_ ');
            return false;
        } else {
            $("#error_client_status_name").html('');
        }
    });

</script>
<script src="<?php echo base_url(); ?>assets/js/Custom_Validation/client_status/create_new_client_status_validation.js"></script>
