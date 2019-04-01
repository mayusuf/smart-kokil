<?php

foreach ($single_roll as $key => $value) {

    $id = $value['roll_id'];
}

?>
<form id = 'update_roll' action = "" enctype = "multipart/form-data" method = "post"
      accept-charset = "utf-8">
    <div class = "box-body">
        <div id = "status"></div>
        <div class = "form-group">
            <label for = ""> Roll Name </label>
            <input type = "text" class = "form-control" id = "roll_name" name = "roll_name" value = "<?php echo $value['roll_name']; ?>"
                   placeholder = "">
            <input type = "hidden" name = "updateId" id = "updateId" value = "<?php echo $id; ?>">
            <span id = "error_roll_name" class = "has-error"></span>
        </div>
    </div>
    <!-- /.box-body -->
    <div class = "box-footer">
        <input type = "submit" id = "submit" name = "submit" value = "Save" class = "btn btn-primary">
        <button type = "button" class = "btn btn-danger" data-dismiss = "modal">Cancel</button>
        <small><img id = "loader" src = "<?php echo site_url('assets/images/loadingg.gif'); ?>" /></small>
    </div>
</form>
<script>

    $('#roll_name').keyup(function () {

        var accountRegex = /^[a-zA-Z_ ]+$/;
        var roll_name = $("#roll_name").val();

        if (!(accountRegex.test(roll_name))) {
            $("#roll_name").val('');
            $("#error_roll_name").html('The Roll name contains only characters and underscore.');
            return false;
        } else {
            $("#error_roll_name").html('');
        }
    });

</script>
<script src="<?php echo base_url(); ?>assets/js/Custom_Validation/roll/update_roll_validation.js"></script>