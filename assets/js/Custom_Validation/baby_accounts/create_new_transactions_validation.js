$(document).ready(function () {
    $('#loader').hide();
    //alert();
    $('#create_new_transactions').validate({// <- attach '.validate()' to your form
        // Rules for form validation
        rules: {
            account_number: {
                required: true
            },
            account_action: {
                required: true
            },
            amount: {
                required: true,
                number: true
            },
			transaction_date: {
                required: true
            },
			comments: {
                required: true
            },
            balance: {
                required: true,
                number: true
            }
        },
        // Messages for form validation
        messages: {
            account_no: {
                required: 'Please select an account number'
            },
            account_action: {
                required: 'Please select an action'
            },
			transaction_date: {
                required: 'Please select transaction date'
            },
            amount: {
                required: 'Please enter amount'
            },
            comments: {
                required: 'Please enter comments about transaction'
            }
        },
        submitHandler: function (form) {

            var myData = new FormData($("#create_new_transactions")[0]);

            $.ajax({
                url: BASE_URL + 'admin/account_transactions/create_new_transaction_process',
                type: 'POST',
                data: myData,
                dataType: 'json',
                cache: false,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $('#loader').show();
                    $("#submit").prop('disabled', true); // disable button
                },
                success: function (data) {

                    if (data.type === 'success') {
                        notify_view(data.type, data.message)
                       // $("#status").html(data.message);
                        $('#loader').hide();
                        $("#submit").prop('disabled', false); // disable button
                        $("html, body").animate({scrollTop: 0}, "slow");
                        $("#create_new_transactions")[0].reset();

                    } else if (data.type === 'danger') {
                        notify_view(data.type, data.message);

                        if(data.errors){
                            $.each(data.errors, function (key, val) {
                                $('#error_' + key).html(val);
                            });
                        }
                      //  $("#status").html(data.message);
                        $('#loader').hide();
                        $("#submit").prop('disabled', false); // disable button
                        $("html, body").animate({scrollTop: 0}, "slow");

                    }
                }
            });
        }
        // <- end 'submitHandler' callback
    });                    // <- end '.validate()'

});        