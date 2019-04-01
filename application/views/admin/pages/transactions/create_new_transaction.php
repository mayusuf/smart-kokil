<div class = "col-md-12 center-block">
    <div class = "panel panel-default">
        <div class = "panel-heading">
            <h2 class = "panel-title"> <?php echo $this->lang->line('new_transaction'); ?> </h2>
        </div>
        <div class = "panel-body">
            <div class = "col-md-8" id = "status"></div>
            <div class = "col-md-11">
                <form id = 'create_new_transactions' action = "" enctype = "multipart/form-data" method = "post"
                      accept-charset = "utf-8">
                    <div class = "box-body">
                        <?php if($this->session->flashdata('acc_no') !=''){
                            $acc_no = $this->session->flashdata('acc_no');
                            $acc_val =  $this->session->flashdata('acc_no');
                        }else{
                            $acc_no = "Choose Account Number";
                            $acc_val = "";

                        } ?>
                        <div class="form-group  col-md-8">
                            <label>Choose Account Number</label>
                            <select class="form-control select2 dropdown" name="account_no" id="account_no" onchange= "get_old_balance(this.value)">
                                <option value="<?php echo $acc_val ?>" selected><?php echo $acc_no ?></option>
                                <?php foreach ($account_no as $value) { ?>
                                    <option value="<?php echo $value["accountNo"] ?>"><?php echo $value["accountNo"]; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class = "col-md-3  form-group">
                            <label for = ""> <?php echo $this->lang->line('old_balance'); ?> </label>
                            <input type = "text" class = "form-control" id = "old_balance" name = "old_balance" value = "0"
                                   readonly>
                        </div>

                        <div class = "col-md-8  form-group">
                            <label for = ""> <?php echo $this->lang->line('select_transaction'); ?> </label>
                            <select class = "form-control" name = "account_action" id = "account_action" onchange="leaveChange();">
                                <option value = ""><?php echo $this->lang->line('select_transaction'); ?></option>
                                <option value = "deposit"> Receive</option>
                                <option value = "withdraw"> Payment</option>
                                <option value = "discount"> Discount</option>
                            </select>
                            <span id = "error_account_action" class = "has-error"></span>
                        </div>
                        <div class = "col-md-8  form-group">
                            <label for = ""> <?php echo $this->lang->line('transaction_amount'); ?> </label>
                            <input type = "text" class = "form-control" id = "amount" name = "amount" value = ""
                                   placeholder = "">
                            <span id = "error_amount" class = "has-error"></span>
                        </div>
                        <div class = "col-md-8  form-group">
                            <label for = ""> <?php echo $this->lang->line('transaction_date'); ?> </label>
                            <input type = "text" class = "form-control" id = "transaction_date" name = "transaction_date" value = ""
                                   placeholder = "" >
                        </div>
                        <div class = "col-md-8  form-group">
                            <label for = ""> <?php echo $this->lang->line('comments'); ?> </label>
                            <input type = "text" class = "form-control" id = "comments" name = "comments" value = ""
                                   placeholder = "">
                            <span id = "error_comments" class = "has-error"></span>
                        </div>
                        <div class = "col-md-3  form-group new_balance">
                            <label for = ""> <?php echo $this->lang->line('new_balance'); ?> </label>
                            <input type = "text" class = "form-control" id = "new_balance" name = "new_balance" value = ""
                                   readonly>
                        </div>
                        <div class = "box-footer col-md-8">
                            <input type = "submit" id = "submit" name = "submit" value = "Save" class = "btn btn-primary">
                            <small><img id = "loader" src = "<?php echo site_url('assets/images/loadingg.gif'); ?>" /></small>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<style>
    .accno a:hover {
        background: #2acdd3;
        text-decoration: none;
    }


    #baby_new_accounts input[readonly] {
        background-color: #1e90c0;
        color: #fefcff;
        height: 45px;
        font-weight: bold;
    }

    @media only screen and (min-width: 990px) {
        .new_balance {

            margin-top: -160px;
        }
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
<link href = "<?php echo base_url(); ?>assets/css/datepicker.css" rel = "stylesheet">
<script src = "<?php echo base_url(); ?>assets/js/bootstrap-datepicker.js"></script>
<script src = "<?php echo base_url(); ?>assets/js/Custom_Validation/baby_accounts/create_new_transactions_validation.js"></script>
<script>
    function leaveChange(){
        $("#error_account_action").html('');
    }

    function get_old_balance(val) {
        $('#account_no').val(val);
      //  alert(val);
        $("#error_account_no").html('');
        $.ajax({
            type: "POST",
            url: BASE_URL + 'admin/account_transactions/get_last_balance',
            data: 'account_no=' + val,
            success: function (data) {
                $("#old_balance").val(data);

            }
        });
    }
</script>
<script>
    $(document).ready(function () {

        $('#transaction_date').datepicker({
            format: "yyyy-mm-dd"

        }).on('changeDate',function(e){
            $(this).datepicker('hide');
        });


        function select_account_number(account_no){
            if(account_no ==""){
                $("#error_account_no").html('Please select an account number');
                $('#new_balance').val(0);
                return false;
            }else{
                $("#error_account_no").html('');
                return true;
            }
        }

        function select_account_action(account_action){
            if(account_action ==""){
                $("#error_account_action").html('Please select an account action');
                $('#new_balance').val(0);
                return false;

            }else{
                $("#error_account_action").html('');
                return true;
            }

        }

        $('#amount').keyup(function () {
                   // alert();

            var accountRegex = /^-?\d+(?:\.\d+)?$/;
            var amount = $("#amount").val();
            var account_action = $("#account_action").val();
            var account_no = $("#account_no").val();
            var old_balance = $("#old_balance").val();

            select_account_number(account_no);
            select_account_action(account_action);


            if (!(accountRegex.test(amount))) {
                $("#amount").val('');
                $("#error_amount").html('Please enter only digit number');
                $('#new_balance').val(0);
                return false;

            } else if (amount == "") {
                $("#amount").val('');
                $("#error_amount").html('Please enter only digit number');
                $('#new_balance').val(0);
                return false;
            } else {

                $("#error_amount").html('');
                if (account_action == 'deposit') {

                    var new_balance = parseFloat(old_balance) + parseFloat(amount);

                    $('#new_balance').val(new_balance.toFixed(2));

                } else if(account_action == 'discount'){

                    var max_dis = parseFloat(old_balance) -1 ;
                    if(parseFloat(old_balance)<0 && parseFloat(max_dis)<-amount ){
                        var new_balance = parseFloat(old_balance) + parseFloat(amount);
                        $('#new_balance').val(new_balance.toFixed(2));
                    }else{
                        $("#amount").val('');
                        $("#error_amount").html('Can not add discount');
                    }

                }else {
                    var new_balance = parseFloat(old_balance) - parseFloat(amount);

                    $('#new_balance').val(new_balance.toFixed(2));
                }
            }


        });

    });
</script>
<script>
    setTimeout(function () {
        $('.alert-success').fadeOut(2000);
    }, 5000); // <-- time in milliseconds
</script>