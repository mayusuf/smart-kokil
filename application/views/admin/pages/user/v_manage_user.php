<div class = "col-md-12 center-bcatk">
    <div class = "panel panel-default">
        <div class = "panel-heading">
            <p class = "panel-title">Manage Users
                <button class = "btn btn-success" onclick = "add_user()"><i class = "glyphicon glyphicon-plus"></i>
                    Add New User
                </button>
            </p>
        </div>
        <div class = "panel-body">
            <div class = "row">
                <div class = "col-md-12 col-sm-12">
                    <?php echo $this->session->flashdata('msg'); ?>

                    <table id = "manage_all_user" class = "table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th> User name</th>
                            <th> User Email</th>
                            <th> User Roll</th>
                            <th> Action</th>
                        </tr>
                        </thead>

                        <tfoot>
                        <tr>
                            <th>#</th>
                            <th> User name</th>
                            <th> User Email</th>
                            <th> User Roll</th>
                            <th> Action</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
<style>
    table
    {
        table-layout: scroll;
        word-wrap:normal;
    }
</style>

<!--========================  User Modal  section =================-->
<div class = "modal fade" id = "modalUser" tabindex = "-1" role = "dialog"
     aria-labelledby = "myModalLabel" aria-hidden = "true" data-keyboard = "false">
    <div class = "modal-dialog">
        <div class = "modal-content">
            <!-- Modal Header -->
            <div class = "modal-header">
                <p class = "modal-title" id = "myModalLabel"></p>
            </div>

            <!-- Modal Body -->
            <div class = "modal-body">
                <div id = "user_form"></div>
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


<script>
    $(document).ready(function () {

        table = $('#manage_all_user').DataTable({
            dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>" +
            "<'row'<'col-sm-12'>>" + //
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-4'i><'col-sm-8'p>>",

            "lengthMenu": [[10, 15, 25, 50, -1], [10, 15, 25, 50, "All"]],

            "ajax": {
                "url": BASE_URL + 'admin/user/get_all_user',
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


    function add_user() {

        $("#user_form").empty();
        $('.modal-title').text('Add New User'); // Set Title to Bootstrap modal title

        $.ajax({
            type: 'POST',
            url: BASE_URL + 'admin/user/get_insert_user_form_view',
            success: function (msg) {
                $("#user_form").html(msg);
                $('#modalUser').modal('show'); // show bootstrap modal
            },
            error: function (result) {
                $("#user_form").html("Sorry Cannot Load Data");
            }
        });

    }

</script>
<script type = "text/javascript">
    $(document).ready(function () {
        $("#manage_all_user").on("click", ".updateUser", function () {

            $("#user_form").empty();
            $('.modal-title').text('Update User'); // Set Title to Bootstrap modal title

            var id = $(this).attr('id');

            $.ajax({
                url: BASE_URL + 'admin/user/get_update_user_form_view',
                type: 'POST',
                data: 'id=' + id,
                success: function (msg) {
                    $("#user_form").html(msg);
                    $('#modalUser').modal('show'); // show bootstrap modal
                },
                error: function (result) {
                    $("#user_form").html("Sorry Cannot Load Data");
                }
            });
        });
    });
</script>
<script type = "text/javascript">
    $(document).ready(function () {
        $("#manage_all_user").on("click", ".assignRole", function () {

            $("#user_form").empty();
            $('.modal-title').text('Assign Role To User'); // Set Title to Bootstrap modal title

            var id = $(this).attr('id');

            $.ajax({
                url: BASE_URL + 'admin/user/assign_role_form_view',
                type: 'POST',
                data: 'id=' + id,
                success: function (msg) {
                    $("#user_form").html(msg);
                    $('#modalUser').modal('show'); // show bootstrap modal
                },
                error: function (result) {
                    $("#user_form").html("Sorry Cannot Load Data");
                }
            });
        });
    });
</script>
<script type = "text/javascript">
    $(document).ready(function () {
        $("#manage_all_user").on("click", ".deleteinformation", function () {
            var id = $(this).attr('id');
            var btn = this;
            var result = confirm("Are you sure to delete the record?");
            if (result) {
                $.ajax({
                    type: 'POST',
                    url: BASE_URL + 'admin/user/delete_user',
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
