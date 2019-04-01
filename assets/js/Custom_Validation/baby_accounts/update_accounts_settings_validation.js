$(document).ready(function () {
    $('#loader').hide();
    //alert();
    $('#update_accounts_settings').validate({// <- attach '.validate()' to your form
        // Rules for form validation
        rules: {
            account_no: {
                required: true
            },
            account_name: {
                required: true
            },
            account_address: {
                required: true
            },
            contact_no: {
                required: true,
                number: true,
                minlength: 11,
                maxlength: 11
            },
			account_type: {
                required: true
            },
            circle_tax: {
                required: true
            }
        },
        // Messages for form validation
        messages: {
            account_no: {
                required: 'Please enter your account number'
            },
            account_name: {
                required: 'Please enter your account name'
            },
            account_address: {
                required: 'Please enter your address'
            },
            contact_no: {
                required: 'Please enter your contact no'
            }
        },
        submitHandler: function (form) {

            var myData = new FormData($("#update_accounts_settings")[0]);

            $.ajax({
                url: BASE_URL + 'admin/account_settings/update_accounts_settings_process',
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
                      //  $("#status").html(data.message);
                        $('#loader').hide();
                        $("#submit").prop('disabled', false); // disable button
                        $("html, body").animate({scrollTop: 0}, "slow");
                        setTimeout(function(){location.href = BASE_URL + 'admin/account_settings/manage_accounts_settings';},500);

                    } else if (data.type === 'danger') {
                        notify_view(data.type, data.message);
                        if(data.errors){
                            $.each(data.errors, function (key, val) {
                                $('#error_' + key).html(val);
                            });
                        }
                       // $("#status").html(data.message);
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