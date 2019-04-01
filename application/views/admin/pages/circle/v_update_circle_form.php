<?php
if ($single_circle) {

    foreach ($single_circle as $value) {

        $id = $value['id'];
    }
    ?>
    <form id='update_circle' action="" enctype="multipart/form-data" method="post"
          accept-charset="utf-8">
        <div class="box-body">
            <div id="status"></div>
            <div class="form-group">
                <label for=""> Circle Name </label>
                <input type="text" class="form-control" id="circle_name" name="circle_name" value="<?php echo $value['circle_name']; ?>"
                       placeholder="">
                <span id="error_circle_name" class="has-error"></span>
                <input type="hidden" name="updateId" id="updateId" value="<?php echo $id; ?>">
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

        $('#circle_name').keyup(function () {

            var accountRegex = /^[a-zA-Z0-9-&_ ]+$/;
            var circle_name = $("#circle_name").val();

            if (!(accountRegex.test(circle_name))) {
                $("#error_circle_name").html('Allowed Characters are a-zA-Z-&0-9_ ');
                return false;
            } else {
                $("#error_circle_name").html('');
            }
        });

    </script>
    <script src="<?php echo base_url(); ?>assets/js/Custom_Validation/circle/update_circle_validation.js"></script>

<?php

} else {
    echo '<i class="icon fa fa-times"></i><strong> Sorry ! </strong>  No records have found .';
}
?>
