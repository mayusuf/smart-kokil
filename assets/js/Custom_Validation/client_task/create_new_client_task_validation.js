$(document).ready(function () {
    $('#loader').hide();
   // alert();
    $('#create_new_client_task').validate({// <- attach '.validate()' to your form
        // Rules for form validation
        rules: {
            account_no: {
                required: true
            }
        },
        // Messages for form validation
        messages: {
            account_no: {
                required: 'Please select account number'
            }
        },
        submitHandler: function (form) {

            var myData = new FormData($("#create_new_client_task")[0]);

            $.ajax({
                url: BASE_URL + 'admin/client_task/create_new_client_task_process',
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
						
                        reload_table();
						
						notify_view(data.type, data.message); 
                        $("#status").html(data.message);
                        $('#loader').hide();
                        $("#submit").prop('disabled', false); // disable button
                        $("html, body").animate({scrollTop: 0}, "slow");
                        $("#create_new_client_task")[0].reset();
						
						 $('#modalClienttask').modal('hide'); // hide bootstrap modal

                    } else if (data.type === 'danger') {
                      //  notify_view(data.type, data.message);
                        if(data.errors){
                            $.each(data.errors, function (key, val) {
                                $('#error_' + key).html(val);
                            });
                        }
                       $("#status").html(data.message);
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