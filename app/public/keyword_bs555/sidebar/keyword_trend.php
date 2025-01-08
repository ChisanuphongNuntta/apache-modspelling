<h1>Group Keyword</h1>
<hr>
<!-- Bootstrap Card -->
<div class="card shadow">
    <div class="card-header">
        ตาราง Keyword
    </div>
    <div class="card-body">

        <!-- Button to Open the Modal -->
        <button type="button" class="btn btn-primary" id="modal_add_keyword" data-toggle="modal" data-target="#myModal">
            <i class="fas fa-plus"></i> Add New Group Keyword
        </button>

        <!-- Bootstrap Table -->
        <table id="table" data-toggle="table"
            data-url="http://localhost:8080/keyword_bs555/api/controls/controller_k_main.php" data-pagination="true"
            data-search="true" data-show-refresh="true" data-show-columns="true" data-show-columns-toggle-all="true"
            data-click-to-select="true" data-minimum-count-columns="2" data-show-pagination-switch="true"
            data-pagination="true" data-id-field="id" data-page-list="[10, 25, 50, 100, all]">
            <thead>
                <tr>
                    <th data-field="tagColor" data-formatter="colorPicker" data-width="50%">Tag Color</th>
                    <th data-field="id" data-width="50%" class="text-center">No.</th>
                    <th data-field="name">Group Keyword</th>
                    <th data-field="count">Count</th>
                    <th data-field="remark">Remark</th>
                    <th data-field="action" data-formatter="btnAction" class="text-center" data-width="300%">
                        Action</th>
                </tr>
            </thead>
        </table>

        <!-- The Modal -->
        <div class="modal" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 id="title_modal" class="modal-title">Add New Group Keyword</h4>
                        <button type="button" class="btn btn-danger" id="close_modal" data-dismiss="modal"><i
                                class="fas fa-xmark"></i></button>
                        <!-- <span id="close_modal" data-dismiss="modal"><i class="fas fa-xmark"></i></span> -->
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body">
                        <form id="newRowForm">
                            <div class="form-group" hidden>
                                <label for="tagColor">ID:</label>
                                <input type="text" class="form-control" id="id" name="id" style="height: 40px;">
                            </div>
                            <div class="form-group">
                                <label for="tagColor">Tag Color:</label>
                                <input type="color" class="form-control" id="tagColor" name="tagColor"
                                    style="height: 40px;">
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="name">Keyword Trend:</label>
                                <input type="text" class="form-control" id="name" name="name">
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="count">Count:</label>
                                <input type="number" class="form-control" id="count" name="count" disabled>
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="remark">Remark:</label>
                                <input type="text" class="form-control" id="remark" name="remark">
                            </div>
                            <!-- Add more input fields as needed -->
                        </form>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="btn_submit"
                            onclick="addNewRow()">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#path_keyword').html(" > Keyword Trend ");
    var $table = $('#table')
    var $remove = $('#remove')
    var selections = []


    function addNewRow() {

        var formData = $('#newRowForm').serializeArray();
        var newRow = {};
        var form = $('#newRowForm')[0];
        // console.log(form);
        // console.log($('#newRowForm')[0]);
        var data = new FormData(form);
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: "http://localhost/keyword_bs555/api/controls/controller_insert_k_main.php",
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                // console.log(data);
            }
        });
        $table.bootstrapTable('refresh');
        $('#myModal').modal('hide');
    }

    function editRow() {

        var formData = $('#newRowForm').serializeArray();
        var newRow = {};
        var form = $('#newRowForm')[0];
        // console.log(formData);
        // console.log($('#newRowForm')[0]);
        var data = new FormData(form);
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: "http://localhost/keyword_bs555/api/controls/controller_update_k_main.php",
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                // console.log(data);
            }
        });
        $table.bootstrapTable('refresh');
        $('#myModal').modal('hide');
    }

    function initTable() {
        $table.bootstrapTable('destroy').bootstrapTable({
            // height: 550,
            locale: $('#locale').val()
        })
        $table.on('check.bs.table uncheck.bs.table ' +
            'check-all.bs.table uncheck-all.bs.table',
            function () {
                $remove.prop('disabled', !$table.bootstrapTable('getSelections').length)

                // save your data, here just save the current page
                selections = getIdSelections()
                // push or splice the selections if you want to save all data selections
            })
        $table.on('all.bs.table', function (e, name, args) {
            // console.log(name, args)
        })
        $table.on('click-row.bs.table', function (e, row, $element) {
            localStorage.setItem('rowdata', JSON.stringify(row))
            
            $("#content_keyword").load("sidebar/keyword_info.php");
        });
        $remove.click(function () {
            var ids = getIdSelections()
            $table.bootstrapTable('remove', {
                field: 'id',
                values: ids
            })
            $remove.prop('disabled', true)
        })
    }

    $(function () {
        initTable()

        $table.on('click', '.btnEdit', function () {
            var id = $(this).data('id');
            var allTableData = $table.bootstrapTable('getData');

            // Find the specific row data by the column ID
            var rowData = allTableData.find(function (row) {
                return row.id === id;
            });

            // Populate the form inputs with the row data
            $('#id').val(rowData.id);
            $('#tagColor').val(rowData.tagColor);
            $('#name').val(rowData.name);
            $('#count').val(rowData.count);
            $('#remark').val(rowData.remark);

            // Set the title of the modal based on the action
            $('#title_modal').text('Edit Group Keyword');

            // Show the modal
            $('#myModal').modal('show');

            // Assign the editRow function to the onclick event of the submit button
            $('#btn_submit').attr('onclick', 'editRow()');
        });



        $('#locale').change(initTable)
    })

    function colorPicker(value, row, index) {
        // if (/* condition */) {
        return '<div style="background-color: ' + row['tagColor'] + '; width: 70px; height: 50px;"></div>';
        // }
        return value;
    }

    function btnAction(value, row, index) {
        var strBtnHTML = '<button type="button" data-id="' + row['id'] + '" id="btnEdit' + row['id'] + '" class="btn btn-warning btnEdit"><i class="fa-solid fa-user-pen"></i><span style="font-size: 14px;"> แก้ไข</span></button> ' +
            '<button type="button" data-id="' + row['id'] + '" id="btnDelete' + row['id'] + '"class="btn btn-danger"><i class="fas fa-trash"></i><span style="font-size: 14px;"> ลบ</span></button>';

        return strBtnHTML;
    }

    function responseHandler(res) {
        return res.rows;
    }

    $('#modal_add_keyword').on('click', function (e) {
        $('#myModal').modal('show');
        $('#btn_submit').attr('onclick', 'addNewRow()');
        $(this).find('input').val('');
    });

    $('#close_modal').on('click', function (e) {
        $('#myModal').modal('hide');
    });

    $('#myModal').on('hidden.bs.modal', function () {
        var inputs = $(this).find('input');
        // console.log(inputs); // Check if inputs are being selected
        inputs.val(''); // Attempt to clear the inputs
    });


</script>