function addEmployee(data) {
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var id_to_edit = 0;
    if (data.length > 0) {
        id_to_edit = data[0].id;
    }
    var content =
        '<div class="modal" data-backdrop="static" data-keyboard="false" id="add_new_employee_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <form id="add_new_employee_form" action="" method="post" enctype="multipart/form-data" >\n\
                <input id="id_to_edit" name="id_to_edit" type="hidden" value="'+ id_to_edit + '" />\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title" id="exampleModalLongTitle"><i class="glyphicon glyphicon-user"></i>&nbsp;Add Employee<i style="float:right;font-size:30px" class="glyphicon glyphicon-remove" onclick="closeModal(\'add_new_employee_modal\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="row">\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">\n\
                            <div class="form-group">\n\
                                <label for="usr">First name</label>\n\
                                <div class="inner-addon left-addon addon_item_icon"><input id="first_name" name="first_name" type="text" class="form-control" placeholder="First name"></div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">\n\
                            <div class="form-group">\n\
                                <label for="usr">Middle name</label>\n\
                                <div class="inner-addon left-addon addon_item_icon"><input id="middle_name" name="middle_name" type="text" class="form-control" placeholder="Middle name"></div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">\n\
                            <div class="form-group">\n\
                                <label for="usr">Last name</label>\n\
                                <div class="inner-addon left-addon addon_item_icon"><input id="last_name" name="last_name" type="text" class="form-control" placeholder="Last name"></div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="row">\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">\n\
                            <div class="form-group">\n\
                                <label for="usr">Address</label>\n\
                                <div class="inner-addon left-addon addon_item_icon"><input id="address" name="address" type="text" class="form-control" placeholder="Address"></div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">\n\
                            <div class="form-group">\n\
                                <label for="usr">Phone</label>\n\
                                <div class="inner-addon left-addon addon_item_icon"><input id="phone" name="phone" type="text" class="form-control" placeholder="Phone"></div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">\n\
                            <div class="form-group">\n\
                                <label for="usr">Email</label>\n\
                                <div class="inner-addon left-addon addon_item_icon"><input id="email" name="email" type="text" class="form-control" placeholder="Email"></div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="row">\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">\n\
                            <div class="form-group">\n\
                                <label for="usr">Start date</label>\n\
                                <div class="inner-addon left-addon addon_item_icon"><input id="start_date" name="start_date" type="text" class="form-control" placeholder="Address" aria-describedby="basic-addon1"></div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6">\n\
                            <div class="form-group">\n\
                                <label for="usr">Note</label>\n\
                                <div class="inner-addon left-addon addon_item_icon"><input id="note" name="note" type="text" class="form-control" placeholder="Note"></div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="row">\n\
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">\n\
                            <div class="form-group">\n\
                                <label for="basic_salary">Basic Salary</label>\n\
                                <input autocomplete="off" value="0"  id="basic_salary" name="basic_salary" type="text" class="form-control cleavesf2 med_input ">\n\
                            </div>\n\
                        </div>\n\
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="display:none">\n\
                            <div class="form-group">\n\
                                <label for="paycut">Pay Cut/Hour</label>\n\
                                <input autocomplete="off" value="0" id="paycut" name="paycut" type="text" class="form-control cleavesf2 med_input ">\n\
                            </div>\n\
                        </div>\n\
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">\n\
                            <div class="form-group">\n\
                                <label for="overtime">Overtime/Hour</label>\n\
                                <input autocomplete="off" value="0"  id="overtime" name="overtime" type="text" class="form-control cleavesf2 med_input ">\n\
                            </div>\n\
                        </div>\n\
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">\n\
                            <div class="form-group">\n\
                                <label for="hours_per_day">Hours/Day</label>\n\
                                <input autocomplete="off" value="8"  id="hours_per_day" name="hours_per_day" type="text" class="form-control cleavesf2 med_input ">\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="row">\n\
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">\n\
                            <div class="form-group">\n\
                                <label for="basic_salary">Customer</label>\n\
                                <input value="" id="customer_emp" name="customer_emp" type="text" class="form-control ">\n\
                                <input value="0" id="customer_emp_id" name="customer_emp_id" type="hidden">\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
                <div class="modal-footer">\n\
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\n\
                    <button id="action_btn" type="submit" class="btn btn-primary">Add</button>\n\
                </div>\n\
                <form/>\n\
            </div>\n\
        </div>\n\
    </div>';

    $('#add_new_employee_modal').remove();
    $('body').append(content);

    submitEmployee();



    $('#add_new_employee_modal').on('show.bs.modal', function (e) {
    });

    $('#add_new_employee_modal').on('shown.bs.modal', function (e) {

        $('#start_date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,

        });
        $('#start_date').datepicker("setDate", new Date());
        $('#start_date').datepicker().on('changeDate', function (ev) {

        }).on('hide show', function (event) {
            event.preventDefault();
            event.stopPropagation();
        });


        if (data.length > 0) {
            $("#first_name").val(data[0].first_name);
            $("#middle_name").val(data[0].middle_name);
            $("#last_name").val(data[0].last_name);

            $("#address").val(data[0].address);
            $("#phone").val(data[0].phone_number);
            $("#email").val(data[0].email);


            $("#basic_salary").val(parseFloat(data[0].basic_salary));
            $("#paycut").val(parseFloat(data[0].paycut_per_hour));
            $("#overtime").val(parseFloat(data[0].overtime_per_hour));
            $("#hours_per_day").val(parseFloat(data[0].hours_per_day));

            $("#customer_emp_id").val(parseFloat(data[0].also_customer_id));

            $("#customer_emp").val(data[0].typeahead_cname);




            $("#note").val(data[0].note);

            $('#start_date').datepicker("setDate", data[0].start_date.split(' ')[0]);

            $("#action_btn").html("Update");
        }



        $.get("?r=customers&f=get_customers_typeahead", function (data) {
            var $input = $("#customer_emp");
            $input.typeahead({
                source: data,
                autoSelect: true,
                fitToElement: false,
            });

            $input.change(function () {
                var current = $input.typeahead("getActive");
                if (current) {
                    //alert(current.name +"=="+ $input.val());
                    // Some item from your model is active!
                    if (current.name == $input.val()) {
                        $("#customer_emp_id").val(current.id);
                    } else {
                        $("#customer_emp_id").val(0);
                    }
                } else {
                    $("#customer_emp_id").val(0);
                }
            });

        }, 'json')
            .done(function () {
                $(".sk-circle-layer").hide();
            })
            .fail(function () {
            })
            .always(function () {
            });


        cleaves_class(".cleavesf2", 2);
    });
    $('#add_new_employee_modal').on('hide.bs.modal', function (e) {
        $("#add_new_employee_modal").remove();
    });

    $('#add_new_employee_modal').modal('show');
}


function submitEmployee() {
    $("#add_new_employee_form").on('submit', (function (e) {
        e.preventDefault();
        if (!emptyInput("first_name")) {
            $(".sk-circle-layer").show();
            $.ajax({
                url: "?r=employees&f=add_new_employee",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function (data) {
                    $('#add_new_employee_modal').modal('toggle');
                    $('#edit_new_employeeModal').modal('toggle');

                    var table = $('#employee_table').DataTable();
                    table.ajax.url("?r=employees&f=getAllEmployees").load(function () {
                        table.row('.' + pad_employee(data.id), { page: 'current' }).select();
                        $(".sk-circle-layer").hide();
                    }, false);

                }
            });
        }
    }));
}

function addEmployeeAttendance(info) {
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var employees_options = "";
    $.getJSON("?r=employees&f=getAllEmployeesDetails", function (data) {
        $.each(data, function (key, val) {
            employees_options += "<option value=" + val.id + ">" + val.first_name + " " + val.last_name + "</option>";
        });

    }).done(function () {
        var content =
            '<div class="modal" data-backdrop="static" data-backdrop="static" id="add_new_employee_attendance" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <form id="add_new_employee_attendance_form" action="" method="post" enctype="multipart/form-data" >\n\
                    <input id="id_to_edit" name="id_to_edit" type="hidden" value="0" />\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title" id="exampleModalLongTitle"><i class="glyphicon glyphicon-time"></i>&nbsp;Add New Attendance<i style="float:right;font-size:30px" class="glyphicon glyphicon-remove" onclick="closeModal(\'add_new_employee_attendance\')"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                <div class="form-group">\n\
                                    <label for="usr">Employee Name</label>\n\
                                    <div class="inner-addon left-addon addon_item_icon"><select id="employee_id" name="employee_id" class="selectpicker" style="width:100% !important">' + employees_options + '</select></div>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">\n\
                                <div class="form-group">\n\
                                    <label for="usr">Start Date/Time</label>\n\
                                    <div class="input-group date" id="start_date">\n\
                                        <input autocomplete="off" name="start_date" type="text" class="form-control" />\n\
                                        <span class="input-group-addon">\n\
                                            <span class="glyphicon glyphicon-calendar"></span>\n\
                                        </span>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">\n\
                                <div class="form-group">\n\
                                    <label for="usr">End Date/Time</label>\n\
                                    <div class="input-group date" id="end_date">\n\
                                        <input autocomplete="off" name="end_date" type="text" class="form-control" />\n\
                                        <span class="input-group-addon">\n\
                                            <span class="glyphicon glyphicon-calendar"></span>\n\
                                        </span>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-footer">\n\
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\n\
                        <button id="action_btn" type="submit" class="btn btn-primary">Add Attendance</button>\n\
                    </div>\n\
                    <form/>\n\
                </div>\n\
            </div>\n\
        </div>';

        $('#add_new_employee').remove();
        $('body').append(content);

        submitEmployeeAttendance();


        $('#add_new_employee_attendance').on('show.bs.modal', function (e) {
        });

        $('#add_new_employee_attendance').on('shown.bs.modal', function (e) {

            $('.selectpicker').selectpicker();

            $('#start_date').datetimepicker({
                format: 'YYYY-MM-DD HH:mm:ss'

            });
            $('#end_date').datetimepicker({
                format: 'YYYY-MM-DD HH:mm:ss'
            });

            $(".sk-circle-layer").hide();

        });
        $('#add_new_employee_attendance').on('hide.bs.modal', function (e) {
            $("#add_new_employee_attendance").remove();
        });

        $('#add_new_employee_attendance').modal('show');


    });

}



function submitEmployeeAttendance() {
    $("#add_new_employee_attendance_form").on('submit', (function (e) {
        e.preventDefault();
        $(".sk-circle-layer").show();
        $.ajax({
            url: "?r=employees&f=add_new_employee_attendance",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            success: function (data) {
                $('#add_new_employee_attendance').modal('toggle');
                $('#edit_employee_attendance').modal('toggle');
                var table = $('#employee_attendance_table').DataTable();
                table.ajax.url("?r=employees&f=getAllEmployeesAttendance").load(function () {
                    table.row('.' + data.id, { page: 'current' }).select();
                    $(".sk-circle-layer").hide();
                });
            }
        });
    }));
}


//////CASHBOX//////

function show_employees_cashbox() {

    $(".sk-circle").center();
    $(".sk-circle-layer").show();


    modal_name = "employees_cashbox"
    modal_title = "<i class='glyphicon glyphicon-briefcase'></i> Working Hours"
    var content =
        `<div class= "modal" data-backdrop="static" id = "${modal_name}" tabindex = "-1" role = "dialog" aria - labelledby="payment_info__" aria - hidden="true" >
                <div class="modal-dialog" style="margin-top:0!important" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">${modal_title}<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\'${modal_name}\')"></i></h3>
                        </div>

                        <div class="modal-body" style="padding-top:2px;">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <table id="employees_cashbox_table" class="table table-striped table-bordered" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Cashbox ID</th>
                                                <th style="width: 100px !important;">User ID</th>
                                                <th>Username</th>
                                                <th>Starting Cashbox</th>
                                                <th>Ending Cashbox</th>
                                                <th>Working hrs</th>
                                                <th>Paid</th>
                                                <th>Paid Val</th>
                                               <th style="width: 150px !important;">Actions</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div> `;
    $("#" + modal_name).remove();
    $("body").append(content);

    $(`#${modal_name}`).modal("show");

    getallcashbox();


}

var current_cashbox_date = "today"

function getallcashbox() {
    //var search_fields = [0, 1, 2, 3, 4, 5,6];
    //var index = 0;
    //$('#employees_cashbox_table tfoot th').each(function () {
        //if (jQuery.inArray(index, search_fields) !== -1) {
            //var title = $(this).text();
            //$(this).html('<div class="inner-addon left-addon"><input  style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder=" ' + title + '" /></div>');
        //}
        //index++;
    //});

    $('#employees_cashbox_table').dataTable({
        ajax: "?r=employees&f=getAllEmployeesCashbox&p0=" + ($("#filter_include_username").val() ?? "") + "&p1=" + current_cashbox_date,
        orderCellsTop: true,
        aoColumnDefs: [{
            "targets": [1, 2, 3, 4, 5, 6],
            "searchable": true,
            "orderable": true,
            "visible": true
        }, {
            "targets": [0, 7],
            "searchable": false,
            "orderable": false,
            "visible": false
        }
        ],
        scrollY: '44vh',
        iDisplayLength: 100,
        scrollCollapse: true,
        paging: true,
        dom: '<"toolbar_cash_employee">frtip',
        initComplete: function (settings) {
            $("#employees_cashbox_table").show();

            var table = $('#employees_cashbox_table').DataTable();


            var cashbox_overview = '<div class="panel panel-default" style="margin-bottom: 5px;">\n\
            <div class="panel-body" style="padding: 5px;">\n\
            <div class="row">\n\
                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 cashbox_info">\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 cashbox_info_inside_left" style="background-color: #b8c3c5">\n\
                            <i class="glyphicon icon-money"></i></div>\n\
                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 cashbox_info_inside_right">\n\
                                <b>Total Working hrs</b><br /><span id="total_working_hrs" style="font-weight: bold">0</span>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 cashbox_info">\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 cashbox_info_inside_left" style="background-color: #b8c3c5">\n\
                            <i class="glyphicon icon-money"></i></div>\n\
                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 cashbox_info_inside_right">\n\
                                <b>Total Paid hrs</b><br /><span id="total_paid_hrs" style="font-weight: bold">0</span>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 cashbox_info">\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 cashbox_info_inside_left" style="background-color: #b8c3c5">\n\
                            <i class="glyphicon icon-money"></i></div>\n\
                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 cashbox_info_inside_right">\n\
                                <b>Total Unpaid hrs</b><br /><span id="total_unpaid_hrs" style="font-weight: bold">0</span>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>';




            $("div.toolbar_cash_employee").html('\n\
                <div class="row" style="margin-top:10px;">\n\
                    <div class="col-lg-2 col-md-2 col-sm-12 pr2" >\n\
                        <div class="btn-group" role="group" aria-label="" style="width:100%;">\n\
                            <input id="cashbox_starting_date" class="form-control datepicker" type="text" placeholder="Select date" style="cursor:pointer; width:100%;">\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-lg-4 col-md-4 col-sm-12 pl2" >\n\
                            <select  id="filter_include_username" multiple style="width:100%"  class="form-control" onchange="refresh_cashbox_employees_table();refresh_cashbox_employees_overview()" >\n\
                            </select>\n\
                    </div>\n\
                </div>'+ cashbox_overview + '\n\
                ');




            $('.selectpicker').selectpicker();

            $("#filter_include_username").select2({
                ajax: {
                    url: '?r=employees&f=search_employee',
                    data: function (params) {
                        var query = {
                            p0: params.term || "",
                            p1: params.page || 1
                        }

                        // Query parameters will be ?search=[term]&type=public
                        return query;
                    },
                    delay: 250,
                    dataType: 'json'
                },
                placeholder: "Search By Username",
                dropdownParent: $("#employees_cashbox"), allowClear: true
            });

            var defaultStart = moment().startOf('month');
            var end = moment();

            $("#cashbox_starting_date").daterangepicker({
                startDate: defaultStart,
                endDate: end,
                locale: {
                    format: 'YYYY-MM-DD'
                },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            });

            refresh_cashbox_employees_overview();

            $("#cashbox_starting_date").change(function () {

                refresh_cashbox_employees_table();
                refresh_cashbox_employees_overview();
            });


            $(".sk-circle-layer").hide();
        },
        fnDrawCallback: Cashbox_employees_callback,
        fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            //$(nRow).addClass(aData[0]);
        },
    });

    $('#employees_cashbox_table').on('page.dt', function () {
        // $("#tab_toolbar button.blueB").addClass("disabled");
        Cashbox_employees_callback();
    });

    $('#employees_cashbox_table').DataTable().columns().every(function () {
        var that = this;
        $('input', this.footer()).on('keyup change', function () {
            if (that.search() !== this.value) {
                that.search(this.value).draw();
            }
        });
    });

    $('#employees_cashbox_table').DataTable().on('mousedown', "tr", function (e, dt, type, indexes) {
        $('.selected').removeClass("selected");
        $(this).addClass('selected');
    });

}

function refresh_cashbox_employees_table() {
    // $(".sk-circle-layer").show();
    $('#employees_cashbox_table').DataTable().ajax.url('?r=employees&f=getAllEmployeesCashbox&p0=' + ($("#filter_include_username").val() ?? "") + '&p1=' + $("#cashbox_starting_date").val()).load(function () {
        $(".sk-circle-layer").hide();
        setTimeout(function () {
            $('#employees_cashbox_table').DataTable().columns.adjust().draw();
        }, 100);
    }, false);
}




function refresh_cashbox_employees_overview() {
    // $(".sk-circle-layer").show();

    $("#total_unpaid_hrs").html("");
    $("#total_working_hrs").html("");
    $("#total_paid_hrs").html("");

    var _data = [];
    $.getJSON("?r=employees&f=getEmployeesCashbox_overview&p0=" + ($("#filter_include_username").val() ?? "") + "&p1=" + $("#cashbox_starting_date").val(), function (data) {
        _data = data;
    }).done(function () {
        $("#total_paid_hrs").html(_data.total_paid_hrs);
        $("#total_unpaid_hrs").html(_data.total_unpaid_hrs);
        $("#total_working_hrs").html(_data.total_working_hrs);

    });
}


function Cashbox_employees_callback() {
    var table = $('#employees_cashbox_table').DataTable();
    var p = table.rows({
        page: 'current'
    }).nodes();
    for (var k = 0; k < p.length; k++) {
        var index = table.row(p[k]).index();
        // var paid_btn = '<span class="glyphicon glyphicon-ok text-success" title="Paid" aria-hidden="true" style="font-size:18px;cursor:pointer" onclick="update_cashbox_is_paid(' + table.cell(index, 0).data() + ',1)">Paid</span>';
        // var unpaid_btn = '<span class="glyphicon glyphicon-remove text-danger"  title="Unpaid" aria-hidden="true" style="font-size:18px;cursor:pointer"  onclick="update_cashbox_is_paid(' + table.cell(index, 0).data() + ',0)">UnPaid</span>';

        var paid_btn = '<button class="btn btn-default btn-xs" onclick="update_cashbox_is_paid(' + table.cell(index, 0).data() + ',1)" >Paid</button>';
        var unpaid_btn = '<button class="btn btn-default btn-xs" onclick="update_cashbox_is_paid(' + table.cell(index, 0).data() + ',0)">UnPaid</button>';

        if (table.cell(index, 7).data() == "0") {
            table.cell(index, 8).data(paid_btn);
        } else {
            table.cell(index, 8).data(unpaid_btn);
        }

    }
}


function update_cashbox_is_paid(cashbox_id, is_paid) {
    var btn_Class = "btn-danger";
    var text = "UnPaid";
    var desc_txt = "Set Cashbox as UnPaid.";


    if (is_paid == 1) {
        btn_Class = "btn-success";
        text = "Paid";
        desc_txt = "Set Cashbox as Paid.";
    }
    swal({
        title: "Are you sure?",
        text: desc_txt,
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: btn_Class,
        confirmButtonText: text,
        closeOnConfirm: true
    },
        function (isConfirm) {
            var _data = [];
            if (isConfirm) {
                $.getJSON("?r=employees&f=update_cashbox_paid&p0="+cashbox_id +"&p1="+ is_paid, function (data) {
                    _data = data;
                }).done(function () {
                    refresh_cashbox_employees_table();
                    refresh_cashbox_employees_overview();
                  
                });
            }
        });
}