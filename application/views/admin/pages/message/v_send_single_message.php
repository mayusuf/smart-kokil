<div class="col-md-12 center-block">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title"> Send message </h2>
        </div>
        <div class="panel-body">
            <div class="col-md-8" id="status"></div>
            <div class="col-md-11">
                <form id='send_single_sms' action="" enctype="multipart/form-data" method="post"
                      accept-charset="utf-8">
                    <div class="box-body">
                        <div class="form-group  col-md-8">
                            <label>Choose Account Number</label>
                            <select class="form-control select2 dropdown" name="account_no" id="account_no">
                                <option value="" selected>Choose Account Name</option>
                                <?php foreach ($account_no as $value) { ?>
                                    <option value="<?php echo $value["accountNo"] ?>"><?php echo $value["accountNo"]; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group  col-md-8">
                            <label>Choose message template</label>
                            <select class="form-control select2 dropdown" name="temp_name" id="temp_name">
                                <option value="" selected disabled>Choose message template</option>
                                <?php foreach ($single_template as $value) { ?>
                                    <option
                                        value="<?php echo $value["id"] ?>"><?php echo $value["temp_name"]; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="box-footer col-md-8">
                            <input type="submit" id="submit" name="submit" value="Preview" class="btn btn-primary">
                            <small><img id="loader" src="<?php echo site_url('assets/images/loadingg.gif'); ?>"/>
                            </small>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<!--========================  Product Modal  section =================-->
<div class = "modal fade" id = "SmsPreview" tabindex = "-1" role = "dialog"
     aria-labelledby = "myModalLabel" aria-hidden = "true" data-keyboard = "false">
    <div class = "modal-dialog">
        <div class = "modal-content">
            <!-- Modal Header -->
            <div class = "modal-header">
                <p class = "modal-title" id = "myModalLabel"></p>
            </div>

            <!-- Modal Body -->
            <div class = "modal-body">
                <div id = "sms_temp"></div>
            </div>

            <!-- Modal Footer -->
            <div class = "modal-footer">
                <button type = "button" class = "btn btn-default"
                        data-dismiss = "modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    @media screen and (min-width: 768px) {
        #SmsPreview .modal-dialog {
            width: 80%;
            border-radius: 5px;
        }

    }

    .modal-body .form-horizontal .col-sm-2,
    .modal-body .form-horizontal .col-sm-10 {
        width: 100%
    }

    .modal-body .form-horizontal .control-label {
        text-align: left;
    }

    .modal-body .form-horizontal .col-sm-offset-2 {
        margin-left: 15px;
    }
</style>
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/select2/select2.min.css'); ?>">
<script src="<?php echo base_url('assets/vendor/select2/select2.full.min.js'); ?>"></script>
<script>
    $(function () {
        //Initialize Select2 Elements
        $(".select2").select2();
    });
</script>
<script>
    $('#c_loader').hide();
    function leaveChange() {
        $("#error_account_action").html('');
    }
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#loader').hide();
        $('#send_single_sms').validate({// <- attach '.validate()' to your form
            // Rules for form validation
            rules: {
                account_no: {
                    required: true
                },
                temp_name: {
                    required: true
                }
            },
            // Messages for form validation
            messages: {
                account_no: {
                    required: 'Please choose account'
                }
            },
            submitHandler: function (form) {

                var myData = new FormData($("#send_single_sms")[0]);

                $.ajax({
                    url: BASE_URL + 'admin/send_sms/single_sms_preview',
                    type: 'POST',
                    data: myData,
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function (msg) {
                        $("#sms_temp").empty();
                        $('.modal-title').text('Message Preview'); // Set Title to Bootstrap modal title
                        $("#sms_temp").html(msg);
                        $('#SmsPreview').modal('show'); // show bootstrap modal
                    },
                    error: function (result) {
                        $("#sms_temp").html("Sorry Cannot Load Data");
                        $('#SmsPreview').modal('show'); // show bootstrap modal
                    }
                });
            }
            // <- end 'submitHandler' callback
        });                    // <- end '.validate()'

    });
</script>