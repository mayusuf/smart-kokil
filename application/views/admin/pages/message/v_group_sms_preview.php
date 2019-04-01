<?php

foreach ($group_template as $temp) {
    $sms = $temp['temp_message'];
}

?>
<form id='send_sms_preview' action="" enctype="multipart/form-data" method="post"
      accept-charset="utf-8">
    <div class="box-body">
        <div id="sms_status"></div>
        <div class="form-group">
            <label>Message Preview</label>
            <textarea name="preview_message" id="preview_message" rows="6" class="form-control"><?php echo $sms; ?></textarea>
        </div>
        <div class="form-group">
            <h4>Select Clients to send sms </h4>
            <strong> Select all : <input type="checkbox" name="all_account_number" value="ALL"/> </strong><br/><br/>
            <?php foreach ($account_inf as $account) { ?>
                <div class="col-md-2">
                    <input type="checkbox" name="all_account_number" class="data-check flat-red" value="<?php echo $account['accountNo']; ?>" id="accountNo"/>
                    &nbsp; <?php echo $account['accountName']; ?></div>
                <input type="hidden" name="contact" value="<?php echo $account['accountNo']; ?>">
            <?php } ?>
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        <input type="button" id="send_submit" onclick="return send_group_sms()" name="submit" value="Submit" class="btn btn-primary">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        <small><img id="sms_loader" src="<?php echo site_url('assets/images/loadingg.gif'); ?>"/></small>
    </div>
</form>
<script type="text/javascript">
    //Flat red color scheme for iCheck

    $(function () {
        $('input[name=all_account_number]').click(function () {
            //If the ALL option was selected
            if ($(this).val() == "ALL") {
                //Checks all of the non-"ALL" options
                $('input[name=all_account_number]').not('[value=ALL]').each(function () {
                    $(this)[0].checked = $("input[name=all_account_number][value=ALL]")[0].checked;
                });
            }
            //Otherwise - determine if all should be
            else {
                //Checks the "ALL" option accordingly
                var othersChecked = $('input[name=all_account_number]:checked').not('[value=ALL]').length == $('input[name=all_account_number]').not('[value=ALL]').length;

                $("input[name=all_account_number][value=ALL]")[0].checked = othersChecked;
            }
        });
    });
</script>

<script>
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

<script type='text/javascript'>
    $('#sms_loader').hide();
    function send_group_sms() {
        var list_id = [];
        var preview_message = $("#preview_message").val();
        $(".data-check:checked").each(function () {
            list_id.push(this.value);
        });
        if (list_id.length > 0) {
            if (confirm('Are you sure to send sms to ' + list_id.length + ' selected clients?')) {
                $.ajax({
                    url: BASE_URL + 'admin/send_sms/send_group_sms',
                    type: 'POST',
                    data: {id: list_id, preview_message:preview_message},
                    cache: false,
                    dataType: 'json',
                    beforeSend: function () {
                        $('#sms_loader').show();
                        $("#send_submit").prop('disabled', true); // disable button
                    },
                    success: function (data) {

                        if (data.type === 'success') {
                            $('#sms_loader').hide();
                            $("#send_submit").prop('disabled', false); // disable button
                            $('#SmsPreview').modal('hide'); // hide bootstrap modal
                               notify_view(data.type, data.message);

                        } else if (data.type === 'danger') {
                            $("#sms_status").html(data.message);
                            $('#sms_loader').hide();
                            $("#send_submit").prop('disabled', false); // disable button
                        }
                    }
                });
            }else{
                return false;
            }
        } else {
            $("#sms_status").html("<div class='alert alert-danger alert-dismissable'><i class='icon fa fa-times'></i><strong> Please!!! </strong>   Select clients.</div>");
            return false;
        }
    }

</script>
