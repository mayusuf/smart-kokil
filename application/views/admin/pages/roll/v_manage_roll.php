<div class = "col-md-12 center-bcatk">
    <div class = "panel panel-default">
        <div class = "panel-heading">
            <p class = "panel-title">Manage Role
                <?php if ($this->m_permission->check_module_action_permission('Roll', 'Add')) { ?>
                <button class = "btn btn-success" onclick = "add_roll()"><i class = "glyphicon glyphicon-plus"></i>
                    Add New Roll
                </button>
                <?php } ?>
            </p>
        </div>
        <div class = "panel-body">
            <div class = "row">
                <div class = "col-md-12 col-sm-12">
                    <?php echo $this->session->flashdata('msg'); ?>

                    <table id = "manage_all_roll" class = "table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th> Roll name</th>
                            <th> Action</th>
                        </tr>
                        </thead>

                        <tfoot>
                        <tr>
                            <th>#</th>
                            <th> Roll name</th>
                            <th> Action</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>


<!--========================  Roll Modal  section =================-->
<div class = "modal fade" id = "modalRoll" tabindex = "-1" role = "dialog"
     aria-labelledby = "myModalLabel" aria-hidden = "true" data-keyboard = "false">
    <div class = "modal-dialog">
        <div class = "modal-content">
            <!-- Modal Header -->
            <div class = "modal-header">
                <p class = "modal-title" id = "myModalLabel"></p>
            </div>

            <!-- Modal Body -->
            <div class = "modal-body">
                <div id = "roll_form"></div>
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

        table = $('#manage_all_roll').DataTable({
            dom: "<'row'<'col-sm-4'l><'col-sm-8'f>>" +
            "<'row'<'col-sm-12'>>" + //
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-4'i><'col-sm-8'p>>",

            "lengthMenu": [[10, 15, 25, 50, -1], [10, 15, 25, 50, "All"]],

            "ajax": {
                "url": BASE_URL + 'admin/roll/get_all_roll',
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


    function add_roll() {

        $("#roll_form").empty();
        $('.modal-title').text('Add New Roll'); // Set Title to Bootstrap modal title

        $.ajax({
            type: 'POST',
            url: BASE_URL + 'admin/roll/get_insert_roll_form_view',
            success: function (msg) {
                $("#roll_form").html(msg);
                $('#modalRoll').modal('show'); // show bootstrap modal
            },
            error: function (result) {
                $("#roll_form").html("Sorry Cannot Load Data");
            }
        });

    }

</script>
<script type = "text/javascript">
    $(document).ready(function () {
        $("#manage_all_roll").on("click", ".updateRoll", function () {

            $("#roll_form").empty();
            $('.modal-title').text('Update Roll'); // Set Title to Bootstrap modal title

            var id = $(this).attr('id');

            $.ajax({
                url: BASE_URL + 'admin/roll/get_update_roll_form_view',
                type: 'POST',
                data: 'id=' + id,
                success: function (msg) {
                    $("#roll_form").html(msg);
                    $('#modalRoll').modal('show'); // show bootstrap modal
                },
                error: function (result) {
                    $("#roll_form").html("Sorry Cannot Load Data");
                }
            });
        });
    });
</script>
<script type = "text/javascript">
    $(document).ready(function () {
        $("#manage_all_roll").on("click", ".deleteinformation", function () {
            var id = $(this).attr('id');
            var btn = this;
            var result = confirm("Are you sure to delete the record?");
            if (result) {
                $.ajax({
                    type: 'POST',
                    url: BASE_URL + 'admin/roll/delete_roll',
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
