<?php
if ($single_client_task) {

    foreach ($single_client_task as $value) {

        $id = $value['id'];
    }
    ?>
    <form id='update_client_task' action="" enctype="multipart/form-data" method="post"
          accept-charset="utf-8">
        <div class="box-body">
            <div id="status"></div>
            <div class="form-group  col-md-12">
                <label>Choose account number</label>
                <select class="form-control  dropdown" name="account_no" id="account_no" required="">
                    <option value="" selected>Choose Account</option>
                    <?php
                    foreach ($account_no as $account) {
                        ?>
                        <option  <?php if ($account['accountNo'] == $value['accountNo']) echo "selected" ?>
                            value="<?php echo $account['accountNo']; ?>">
                            <?php echo $account['accountNo']; ?></option>
                    <?php
                    }
                    ?>
                </select>

                <input type="hidden" name="updateId" id="updateId" value="<?php echo $id; ?>">
            </div>
            <div class="form-group  col-md-12">
                <label>Choose client status</label>
                <select class="form-control" name="task_status" id="task_status" required="">
                    <option value="" selected>Choose task status</option>
                    <?php
                    foreach ($all_client_status as $status) {
                        ?>
                        <option  <?php if ($status['id'] == $value['task_status']) echo "selected" ?>
                            value="<?php echo $status['id']; ?>">
                            <?php echo $status['client_status']; ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div class="form-group col-md-12">
                <label for=""> Task details </label>
                <textarea type="text" class="form-control" id="task_details" name="task_details"
                          placeholder="" required=""><?php echo $value['task_desc']; ?></textarea>
                <span id="error_client_task_name" class="has-error"></span>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <input type="submit" id="submit" name="submit" value="Save" class="btn btn-primary">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            <small><img id="loader" src="<?php echo site_url('assets/images/loadingg.gif'); ?>"/></small>
        </div>
    </form>
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/select2/select2.min.css'); ?>">
    <script src="<?php echo base_url('assets/vendor/select2/select2.full.min.js'); ?>"></script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
        });
    </script>
    <script>

        $('#client_task_name').keyup(function () {

            var accountRegex = /^[a-zA-Z0-9-&_ ]+$/;
            var client_task_name = $("#client_task_name").val();

            if (!(accountRegex.test(client_task_name))) {
                $("#error_client_task_name").html('Allowed Characters are a-zA-Z-&0-9_ ');
                return false;
            } else {
                $("#error_client_task_name").html('');
            }
        });

    </script>
    <script src="<?php echo base_url(); ?>assets/js/Custom_Validation/client_task/update_client_task_validation.js"></script>
<?php

} else {
    echo '<i class="icon fa fa-times"></i><strong> Sorry ! </strong>  No records have found .';
}
?>
