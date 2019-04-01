<?php foreach ($single_users as $key => $value) {
    $id = $value['user_id'];
}
?>
<form id='update_user' action="" enctype="multipart/form-data" method="post"
      accept-charset="utf-8">
    <div class="box-body">
        <div id="status"></div>
        <div class="form-group">
            <label for=""> User Name </label>
            <input type="text" class="form-control" id="user_name" name="user_name" value="<?php echo $value['user_name']; ?>"
                   placeholder="">
            <input type="hidden" name="updateId" id="updateId" value="<?php echo $id ?>">
            <span id="error_user_name" class="has-error"></span>
        </div>
        <div class="form-group ">
            <label for=""> User Email </label>
            <input type="text" class="form-control" id="user_email" name="user_email" value="<?php echo $value['user_email']; ?>"
                   placeholder="" readonly>
            <span id="error_user_email" class="has-error"></span>
        </div>
        <div class="form-group ">
            <label for=""> Password </label>
            <input type="password" class="form-control" id="user_password" name="user_password" value=""
                   placeholder="">
            <span id="error_user_password" class="has-error"></span>
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
    $('[data-toggle="tooltip"]').tooltip();
    $('#user_name').keyup(function () {

        var accountRegex = /^[a-zA-Z_ ]+$/;
        var user_name = $("#user_name").val();

        if (!(accountRegex.test(user_name))) {
            $("#error_user_name").html('The user name contains only characters and underscore.');
            return false;
        } else {
            $("#error_user_name").html('');
        }
    });
</script>
<script src="<?php echo base_url(); ?>assets/js/Custom_Validation/user/update_user_validation.js"></script>