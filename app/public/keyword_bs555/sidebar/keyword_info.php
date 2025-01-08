<h1>Keyword</h1>
<hr>
<div class="card">
    <div class="card-header">
        ตาราง Group Keyword
    </div>
    <div class="card-body">

        <!-- Button to Open the Modal -->
        <button type="button" class="btn btn-primary" id="modal_add_keyword" data-toggle="modal" data-target="#myModal">
            <i class="fas fa-plus"></i> Add New Keyword
        </button>

        <!-- Bootstrap Table -->
        <table id="table" data-toggle="table" data-url="" data-pagination="true" data-search="true"
            data-show-refresh="true" data-show-columns="true" data-show-columns-toggle-all="true"
            data-click-to-select="true" data-minimum-count-columns="2" data-show-pagination-switch="true"
            data-pagination="true" data-id-field="id" data-page-list="[10, 25, 50, 100, all]">
            <thead>
                <tr>
                    <!-- <th data-field="tagColor" data-formatter="colorPicker" data-width="50%">Tag Color</th> -->
                    <!-- <th data-field="ksid" data-width="50%">No.</th> -->
                    <th data-field="keyword" data-width="300%">Keyword</th>
                    <th data-field="cerrent_search_month" data-width="100%">Last Search Month</th>
                    <th data-field="search_avg" data-width="100%">Search AVG (12 เดือนย้อนหลัง)</th>
                    <th data-field="cpc" data-width="100%">Cost per Click (CPC)</th>
                    <th data-field="cmp" data-width="100%">Cost per Mille (CPM)</th>
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
                        <h4 id="title_modal" class="modal-title">Add New Keyword</h4>
                        <button type="button" class="btn btn-danger" id="close_modal" data-dismiss="modal"><i
                                class="fas fa-xmark"></i></button>
                        <!-- <span id="close_modal" data-dismiss="modal"><i class="fas fa-xmark"></i></span> -->
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body">
                        <form id="newRowForm">
                            <!-- Button to add new input field -->
                            <button type="button" class="btn btn-primary" id="addButton" hidden><i
                                    class="fas fa-plus"></i> Add
                                Keyword</button>
                            <br>
                            <div class="form-group" hidden>
                                <label for="tagColor">ID:</label>
                                <input type="text" class="form-control" id="id" name="id" style="height: 40px;">
                            </div>
                            <div class="form-group" id="inputContainer">
                                <label for="keyword0">Keyword: </label>
                                <input type="text" class="form-control" id="keyword0" name="keyword0">
                            </div>
                        </form>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" onclick="addNewRow()">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    var rowdata = JSON.parse(localStorage.getItem('rowdata'));
    $('#path_keyword').html(" > Keyword Trend > " + rowdata.name);

    var $table = $('#table')
    var $remove = $('#remove')
    var selections = []

    function addNewRow() {

        var formData = $('#newRowForm').serializeArray();
        var newRow = {};
        var form = $('#newRowForm')[0];
        // console.log(formData);
        // console.log($('#newRowForm')[0]);
        var data = new FormData(form);
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: "http://localhost/keyword_bs555/api/controls/controller_insert_k_sub_main.php",
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                // console.log(data);
            }
        });
        // readDataKeyword()
        $('#myModal').modal('hide');
        $table.bootstrapTable('refresh');
        // $("#content_keyword").load("sidebar/keyword_info.php");

    }


    function initTable() {
        $table.bootstrapTable('destroy').bootstrapTable({
            // data: data,
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
        $remove.click(function () {
            var ids = getIdSelections()
            $table.bootstrapTable('remove', {
                field: 'id',
                values: ids
            })
            $remove.prop('disabled', true)
        })
    }

    function readDataKeyword() {
        $.ajax({
            type: "POST",
            url: "http://localhost/keyword_bs555/api/controls/controller_k_sub_main.php",
            data: { kid: rowdata.id },
            success: function (data) {
                $table.bootstrapTable('destroy').bootstrapTable({
                    data: data,
                    locale: $('#locale').val()
                })
            }
        });
    }

    $(function () {
        initTable()
        readDataKeyword()
        $table.on('click', '.btnEdit', function () {
            var id = $(this).data('id'); // Get the ID of the row
            var allTableData = $table.bootstrapTable('getData');

            // Find the specific row data by the column ID
            var rowData = allTableData.find(function (row) {
                return row.id === id; // Make sure 'id' matches the field name for your unique identifier
            });

            // Populate the form inputs with the row data
            // console.log("nice: " + rowData.tagColor);
            $('#tagColor').val(rowData.tagColor);
            $('#name').val(rowData.name);
            $('#count').val(rowData.count);
            $('#remark').val(rowData.remark);

            // Set the title of the modal based on the action
            $('#title_modal').text('Edit Group Keyword');

            // Show the modal
            $('#myModal').modal('show');
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
        $('#id').val(rowdata.id);
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


    // Starting index for keyword IDs
    var keywordIndex = 0;

    // Function to add new input field
    function addNewInput() {
        // Increment the index for a new unique ID
        keywordIndex++;

        // Create a new div element for the input group
        var newDiv = document.createElement('div');
        newDiv.className = 'form-group';

        // Create a new label for the input
        var newLabel = document.createElement('label');
        newLabel.setAttribute('for', 'keyword' + keywordIndex);
        newLabel.innerHTML = 'Keyword: ';
        newDiv.appendChild(newLabel);

        // Create a new input element
        var newInput = document.createElement('input');
        newInput.type = 'text';
        newInput.className = 'form-control';
        newInput.id = 'keyword' + keywordIndex;
        newInput.name = 'keyword' + keywordIndex;

        // Append the new input to the new div
        newDiv.appendChild(newInput);

        // Append the new div to the container
        var container = document.getElementById('inputContainer');
        container.appendChild(newDiv);
    }

    // Add click event listener to the button
    document.getElementById('addButton').addEventListener('click', addNewInput);

</script>