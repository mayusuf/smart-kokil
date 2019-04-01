<form id='create_new_tax' action="" enctype="multipart/form-data" method="post"
      accept-charset="utf-8">
    <div class="box-body">
        <div id="status"></div>
        <div class="form-group">
            <label for=""> Type Name </label>
            <input type="text" class="form-control" id="tax_name" name="tax_name" value=""
                   placeholder="">
            <span id="error_tax_name" class="has-error"></span>
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

    $('#tax_name').keyup(function () {

        var accountRegex = /^[a-zA-Z0-9-&_. ]+$/;
        var tax_name = $("#tax_name").val();

        if (!(accountRegex.test(tax_name))) {
            $("#error_tax_name").html('Allowed Characters are a-zA-Z-&0-9_ ');
            return false;
        } else {
            $("#error_tax_name").html('');
        }
    });

</script>
<script src="<?php echo base_url(); ?>assets/js/Custom_Validation/tax/create_new_tax_validation.js"></script>
