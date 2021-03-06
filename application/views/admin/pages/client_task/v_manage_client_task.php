<div class = "col-md-12 center-bcatk">
    <div class = "panel panel-default">
        <div class = "panel-heading">
            <p class = "panel-title">Manage Client Task
                    <button class = "btn btn-success" onclick = "add_client_task()"><i class = "glyphicon glyphicon-plus"></i>
                        Add New Client Task
                    </button>
            </p>
        </div>
        <div class = "panel-body">
            <div class = "row">
                <div class = "col-md-12 col-sm-12">
                    <table id = "manage_all_client_task" class = "table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th> Client Name</th>
                            <th> Account No</th>
                            <th> Status</th>
                            <th> Action</th>
                        </tr>
                        </thead>

                        <tfoot>
                        <tr>
                            <th>#</th>
                            <th> Client Name</th>
                            <th> Account No</th>
                            <th> Status</th>
                            <th> Action</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<!--========================  Client Task Modal  section =================-->
<div class = "modal fade" id = "modalClienttask"  role = "dialog"
     aria-labelledby = "myModalLabel" aria-hidden = "true" data-keyboard = "false">
    <div class = "modal-dialog">
        <div class = "modal-content">
            <!-- Modal Header -->
            <div class = "modal-header">
                <p class = "modal-title" id = "myModalLabel"></p>
            </div>

            <!-- Modal Body -->
            <div class = "modal-body">
                <div id = "client_task_form"></div>
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
        #modalClienttask .modal-dialog {
            width: 50%;
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

    .select2-container{
        z-index: 10050;
    }


</style>
<script>
    $(document).ready(function () {

        table = $('#manage_all_client_task').DataTable({
            dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>" +
            "<'row'<'col-sm-12'>>" + //
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-4'i><'col-sm-8'p>>",

            "lengthMenu": [[10, 15, 25, 50, -1], [10, 15, 25, 50, "All"]],

            "ajax": {
                "url": BASE_URL + 'admin/client_task/get_all_client_task',
                "type": "POST"
            },

            "autoWidth": false,
            select: true,
            scrollY: '90vh',
            scrollX: true,
            scrollCollapse: true,
            paging: true,
            stateSave: true,

            buttons: [
                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-table"> EXCEL </i>',
                    titleAttr: 'Excel',
                    exportOptions: {
                        columns: ':visible:not(.not-exported)'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fa fa-file-pdf-o"> PDF</i>',
                    titleAttr: 'PDF',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fa fa-print"> PRINT </i>',
                    titleAttr: 'Print',
                    exportOptions: {
                        columns: ':visible'
                    }

                },
                {
                    extend: 'colvis',
                    text: '<i class="fa fa-eye-slash"> Column Visibility </i>',
                    titleAttr: 'Visibility'
                }


            ],

            "oSelectorOpts": {filter: 'applied', order: "current"},
            language: {
                buttons: {},

                "emptyTable": "<strong style='color:#ff0000'> Sorry!!! No Records have found </strong>",
                "search": "<i class='fa fa-search'> Search : </i>",
                "paginate": {
                    "next": "Next",
                    "previous": "Previous"
                },

                "zeroRecords": ""
            }
        });


        $('.dataTables_filter input[type="search"]').
            attr('placeholder', 'Type here to search...').
            css({'width': '580px'});

        $('[data-toggle="tooltip"]').tooltip();

    });
</script>
<script>


    function reload_table() {
        table.ajax.reload(null, false); //reload datatable ajax
    }


    function add_client_task() {

        $("#client_task_form").empty();
        $('.modal-title').text('Add New Client Task'); // Set Title to Bootstrap modal title

        $.ajax({
            type: 'POST',
            url: BASE_URL + 'admin/client_task/get_insert_client_task_form_view',
            success: function (msg) {
                $("#client_task_form").html(msg);
                $('#modalClienttask').modal('show'); // show bootstrap modal
            },
            error: function (result) {
                $("#client_task_form").html("Sorry Cannot Load Data");
            }
        });

    }

</script>
<script type = "text/javascript">
    $(document).ready(function () {
        $("#manage_all_client_task").on("click", ".updateClientTask", function () {

            $("#client_task_form").empty();
            $('.modal-title').text('Update Client Task'); // Set Title to Bootstrap modal title

            var id = $(this).attr('id');

            $.ajax({
                url: BASE_URL + 'admin/client_task/get_update_client_task_form_view',
                type: 'POST',
                data: 'id=' + id,
                success: function (msg) {
                    $("#client_task_form").html(msg);
                    $('#modalClienttask').modal('show'); // show bootstrap modal
                },
                error: function (result) {
                    $("#client_task_form").html("Sorry Cannot Load Data");
                }
            });
        });
    });
</script>
<script type = "text/javascript">
    $(document).ready(function () {
        $("#manage_all_client_task").on("click", ".deleteinformation", function () {
            var id = $(this).attr('id');
            var btn = this;
            var result = confirm("Are you sure to delete the record?");
            if (result) {
                $.ajax({
                    type: 'POST',
                    url: BASE_URL + 'admin/client_task/delete_client_task',
                    dataType: 'json',
                    data: 'id=' + id,
                    success: function (data) {

                        if (data.type === 'success') {

                            notify_view(data.type, data.message);

                            reload_table();

                        } else if (data.type === 'danger') {

                            notify_view(data.type, data.message);

                        }
                    }
                });
            }
        });
    });

</script>
