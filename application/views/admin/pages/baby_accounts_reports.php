<div class="col-md-12 center-block">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title"> <?php echo $this->lang->line('reports'); ?> </h2>
        </div>
        <div class="panel-body">
            <div class="col-md-8" id="status"></div>
            <div class="col-md-12">
                <form id='baby_new_accounts' action="" method="post">
                    <div class="box-body">
                        <div class="form-group  col-md-8">
                            <label>Choose Account Number</label>
                            <select class="form-control select2 dropdown" name="account_no" id="account_no" required="">
                                <option value="" selected disabled>Choose Account Number</option>
                                <?php foreach ($account_no as $value) { ?>
                                    <option value="<?php echo $value["accountNo"] ?>"><?php echo $value["accountNo"]; ?></option>
                                <?php } ?>
                            </select>
                            <span id="error_account_no" class="has-error error"></span>
                        </div>
                        <div class="col-md-8  form-group">
                            <label for=""> <?php echo $this->lang->line('start_date'); ?> </label>
                            <input type="text" class="form-control" id="start_date" name="start_date" value=""
                                   placeholder="">
                            <span id="error_amount" class="has-error"></span>
                        </div>
                        <div class="col-md-8  form-group">
                            <label for=""> <?php echo $this->lang->line('end_date'); ?> </label>
                            <input type="text" class="form-control" id="end_date" name="end_date" value=""
                                   placeholder="">
                            <span id="error_amount" class="has-error"></span>
                        </div>
                        <div class="box-footer col-md-8">
                            <input type="submit" id="submit" name="submit" value="Submit" class="btn btn-primary">
                            <small><img id="loader" src="<?php echo site_url('assets/images/loadingg.gif'); ?>"/></small>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </form>
            </div>
        </div>
        <div id="accounts_reports"></div>
    </div>
</div>
<!-- /.row -->
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/select2/select2.min.css'); ?>">
<script src="<?php echo base_url('assets/vendor/select2/select2.full.min.js'); ?>"></script>
<script>
    $(function () {
        //Initialize Select2 Elements
        $(".select2").select2();
    });
</script>
<link href="<?php echo base_url(); ?>assets/css/datepicker.css" rel="stylesheet">
<script src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.printElement.min.js"></script>


<script type="text/javascript">
    // When the document is ready
    $(document).ready(function () {

        $('#start_date').datepicker({
            format: "yyyy-mm-dd"

        }).on('changeDate', function (e) {
            $(this).datepicker('hide');
        });

        $('#end_date').datepicker({
            format: "yyyy-mm-dd"

        }).on('changeDate', function (e) {
            $(this).datepicker('hide');
        });

    });
</script>
<script>
    function printContent() {
        $('#invoice_print').printThis();
    }

</script>
<script>
    // print invoice function
    function PrintElem() {

        Popup($('#invoice_print').html());
    }
    function Popup(data) {
        var mywindow = window.open('', 'Fee_Collection', 'height=800,width=1200');
        mywindow.document.write('<html><head><title>Invoice</title>');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/billboy.css" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>assets/datatables/css/dataTables.bootstrap.min.css" type="text/css" />');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.print();
        mywindow.close();
        return true;
    }
</script>

<script>
    function set_value(val) {

        $('#account_no').val(val);
    }
</script>
<script>
    $(document).ready(function () {
        $('#loader').hide();
        //alert();
        $('#baby_new_accounts').validate({// <- attach '.validate()' to your form
            // Rules for form validation
            rules: {
                account_no: {
                    required: true
                }
            },
            // Messages for form validation
            messages: {
                account_no: {
                    required: 'Please enter your account number'
                }
            },
            submitHandler: function (form) {

                var myData = new FormData($("#baby_new_accounts")[0]);

                $.ajax({
                    url: BASE_URL + 'admin/account_transactions/get_transaction_reports_view',
                    type: 'POST',
                    data: myData,
                    //  dataType: 'json',
                    cache: false,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        $('#loader').show();
                        $("#submit").prop('disabled', true); // disable button
                    },
                    success: function (data) {

                        $("#accounts_reports").html(data);
                        $("#submit").prop('disabled', false); // disable button
                        $('#loader').hide();
                        $('html, body').animate({ scrollTop: $("#accounts_reports").offset().top}, 1000);
                    }
                });
            }
            // <- end 'submitHandler' callback
        });                    // <- end '.validate()'

    });
</script>