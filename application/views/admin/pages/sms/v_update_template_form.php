<?php
if ($single_template) {

foreach ($single_template as $value) {

    $id = $value['id'];
}
?>
<form id = 'update_template' action=""  enctype = "multipart/form-data" method = "post"
      accept-charset = "utf-8">
    <div class = "box-body">
        <div id = "status"></div>
        <div class = "form-group col-md-6">
            <label for = ""> Template Name </label>
            <input type = "text" class = "form-control" id = "template_name" name = "template_name" value = "<?php echo $value['temp_name']; ?>"
                   placeholder = "">
            <input type = "hidden" name = "updateId" id = "updateId" value = "<?php echo $id; ?>">
            <span id = "error_template_name" class = "has-error"></span>
        </div>
        <div class="form-group  col-md-6">
            <label>Template TYpe</label>
            <select class="form-control" name="temp_type" id="temp_type">
                <option value="" disabled>Choose Template Type</option>
                <option value="<?php echo $value['temp_type']; ?>" selected><?php echo $value['temp_type']; ?></option>
                <option value="Single">Single</option>
                <option value="Group">Group</option>
            </select>
            <span id="error_temp_type" class="has-error"></span>
        </div>
        <div class = "form-group col-md-12">
            <label>Message</label>
            <textarea name="message" id="message" rows="6" class="form-control"><?php echo $value['temp_message']; ?></textarea>
        </div>

    </div>
    <!-- /.box-body -->
    <div class = "box-footer">
        <input type = "submit" id = "submit" name = "submit" value = "Save" class = "btn btn-primary">
        <button type = "button" class = "btn btn-danger" data-dismiss = "modal">Cancel</button>
        <small><img id = "loader" src = "<?php echo site_url('assets/images/loadingg.gif'); ?>" /></small>
    </div>
</form>
<?php

} else {
    echo '<i class="icon fa fa-times"></i><strong> Sorry ! </strong>  No records have found .';
}
?>
<script>
    $("#temp_type option").each(function(){
        $(this).siblings("[value='"+ this.value+"']").remove();
    });

    $('#template_name').keyup(function () {

        var accountRegex = /^[a-zA-Z0-9-&_ ]+$/;
        var template_name = $("#template_name").val();

        if (!(accountRegex.test(template_name))) {
            $("#error_template_name").html('Allowed Characters are a-zA-Z-&0-9_ ');
            return false;
        } else {
            $("#error_template_name").html('');
        }
    });

</script>
<script src="<?php echo base_url(); ?>assets/js/Custom_Validation/template/update_template_validation.js"></script>
