<?php
if ($last_balance) {
    $balance = $last_balance[0]['balance'];
} else {
    $balance = 0;
}


foreach ($account_inf as $account) {
    $name = $account['accountName'];
}
foreach ($single_template as $temp) {
    $sms = $temp['temp_message'];
}


$search = array("{name}", "{balance}");
$replace = array($name, $balance);

$preview = str_replace($search, $replace, $sms);
?>
<form id='send_sms_preview' action="" enctype="multipart/form-data" method="post"
      accept-charset="utf-8">
    <div class="box-body">
        <div id="sms_status"></div>
        <div class="form-group">
            <label>Message Preview</label>
            <textarea name="preview_message" id="preview_message" rows="6" class="form-control"><?php echo $preview; ?></textarea>
            <input type="hidden" name="contact" value="<?php echo $account['contactNo']; ?>">
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        <input type="submit" id="send_submit" name="submit" value="Submit" class="btn btn-primary">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        <small><img id = "sms_loader" src = "<?php echo site_url('assets/images/loadingg.gif'); ?>" /></small>
    </div>
</form>
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
<script type="text/javascript">
    $(document).ready(function () {
        $('#sms_loader').hide();
        $('#send_sms_preview').validate({// <- attach '.validate()' to your form
            // Rules for form validation
            rules: {
                preview_message: {
                    required: true
                }
            },
            // Messages for form validation
            messages: {
                preview_message: {
                    required: 'Nothing to send'
                }
            },
            submitHandler: function (form) {

                var myData = new FormData($("#send_sms_preview")[0]);

                $.ajax({
                    url: BASE_URL + 'admin/send_sms/send_single_sms',
                    type: 'POST',
                    data: myData,
                    cache: false,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
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
            }
            // <- end 'submitHandler' callback
        });                    // <- end '.validate()'

    });
</script>
