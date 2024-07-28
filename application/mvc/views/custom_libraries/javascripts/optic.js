var global_client_data = [];
function show_client_optic_details(client_id) {
    if (client_id > 0) {
        var _data = [];
        $.getJSON("?r=customers&f=get_client_info&p0=" + client_id, function (data) {
            _data = data;
        }).done(function () {
            show_optic_details(client_id, _data);
        });

    } else {
        show_optic_details(client_id, []);
    }

}



function show_optic_details(client_id, _data) {
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var default_client = '';
    var client_disable = '';
    var client_fname = "";
    var client_mname = "";
    var client_lname = "";
    var client_phone_nb = "";
    var client_address = "";
    var client_pd = "";
    var client_doctor = "";
    var client_note1 = "";
    var client_note2 = "";
    var client_note = "";
    var hide_edit_client = "display:none;";
    var hide_add_client = " ";
    

    if (client_id > 0) {
        // client_disable = 'disabled';
        default_client = '<option value="' + client_id + '">' + _data.client_info + '</option>';
        client_fname = _data.client_data[0].name;
        client_mname = _data.client_data[0].middle_name;
        client_lname = _data.client_data[0].last_name;
        client_phone_nb = _data.client_data[0].phone;
        client_address = _data.client_data[0].address;
        client_pd = _data.client_data[0].pd;
        client_doctor = _data.client_data[0].doctor;
        client_note1 = _data.client_data[0].note1;
        client_note2 = _data.client_data[0].note2;
        client_note = _data.client_data[0].note;

        hide_edit_client = "";
        // hide_add_client = " display:none;";
    }

    modal_name = "optic_details";
    modal_title = "<i class='glyphicon glyphicon-briefcase'></i> Optic Client Details";



    var content =
        `<div class= "modal" data-backdrop="static" id = "${modal_name}" tabindex = "-1" role = "dialog" aria - labelledby="payment_info__" aria - hidden="true" >
                <div class="modal-dialog" style="margin-top:0!important" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">${modal_title}<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\'${modal_name}\')"></i></h3>
                        </div>
                        <div class="modal-body" style="padding-top:2px;">
                            <div class="row" >
                                <div class="col-lg-3">
                                    <div class="row" style="margin-top:3px;margin-bottom:10px;">
                                        <div class="col-lg-12 col-md-12 col-sm-12 " >
                                            <select  id="filter_include_optic_user" ${client_disable} style="width:100%"  class="form-control" onchange="refresh_optic_details_table(\'${client_id}\');refresh_client_info(this)" >
                                            ${default_client}</select>
                                        </div>\n\
                                    </div>\n\
                                    <div class="row">\n\
                                        <div class="col-xs-6" style="padding-right:2px; ">\n\
                                            <div class="form-group" id="client_form_grp" style="${hide_add_client};width:100%">\n\
                                                <button style="width:100%" class="btn btn-primary" onclick="add_new_customer(0)" > Add New Customer</button>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-xs-6" id="edit_customer_col" style="padding-left:2px; ${hide_edit_client}'">\n\
                                            <div class="form-group" id="edit_customer_section" style="width:100%">\n\
                                                <button class="btn btn-default" onclick="add_new_customer(${client_id})"  style="width:100%"> Edit Customer</button>\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="row">\n\
                                        <div class="col-xs-6" style="padding-right:2px;" >\n\
                                            <div class="form-group" id="client_form_grp">\n\
                                                <label for="">First Name</label>\n\
                                                <input id="client_first_name"  type="text" class="form-control " readonly placeholder="" value="${client_fname ?? ''}" >\n\
                                                </div>\n\
                                        </div>\n\
                                        <div class="col-xs-6" style="padding-left:2px;">\n\
                                            <div class="form-group">\n\
                                                <label for="">Middle Name</label>\n\
                                            <input id="client_middle_name"  type="text" class="form-control " readonly placeholder="" value="${client_mname ?? ''}" >\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="row">\n\
                                        <div class="col-xs-6" style="padding-right:2px;">\n\
                                            <div class="form-group">\n\
                                                <label for="">Last Name</label>\n\
                                                <input id="client_last_name" type="text" class="form-control " readonly placeholder="" value="${client_lname ?? ''}"  />\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-xs-6" style="padding-left:2px;">\n\
                                            <div class="form-group">\n\
                                                <label for="">Phone Number</label>\n\
                                                <input id="client_phone_nb" type="text" class="form-control " readonly placeholder="" value="${client_phone_nb ?? ''}"  />\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="row">\n\
                                        <div class="col-xs-6"  >\n\
                                            <div class="form-group">\n\
                                                <label for="">PD</label>\n\
                                                <input id="client_pd" type="text" class="form-control " readonly placeholder="" value="${client_pd ?? ''}"  />\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-xs-6"  >\n\
                                            <div class="form-group">\n\
                                                <label for="">Doctor</label>\n\
                                                <input id="client_doctor" type="text" class="form-control " readonly placeholder="" value="${client_doctor ?? ''}"  />\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="row">\n\
                                        <div class="col-xs-12" style="">\n\
                                            <div class="form-group">\n\
                                                <label for="">Note</label>\n\
                                            <textarea style="height: 150px;"  id="client_note" rows="10"    class="form-control " readonly placeholder="" >${client_note ?? ''}</textarea>\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="row">\n\
                                        <div class="col-xs-12" style="">\n\
                                            <div class="form-group">\n\
                                                <label for="">Address</label>\n\
                                            <textarea  id="client_address"    class="form-control " readonly placeholder="" >${client_address ?? ''}</textarea>\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>
                                <div class="col-lg-9">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12">
                                        <h4 style="margin-bottom:1px !important;margin-top:3px !important;"><button class="btn btn-default" onclick="add_optic_detail(\'${client_id}\','distance','Distance',0)">Add Far</button></h4>
                                            <table id="optic_distance_table" class="table table-striped table-bordered" cellspacing="0">
                                                <thead>
                                                    <tr>
                                                        <th>Optic ID</th>
                                                        <th style="width: 70px !important;">Date</th>
                                                        <th class="rt_class">RT SPH</th>
                                                        <th class="rt_class">RT CYL</th>
                                                        <th class="rt_class">RT AXIS</th>
                                                        <th class="rt_class">RT PRISM</th>
                                                        <th class="lt_class">L. SPH</th>
                                                        <th class="lt_class">L. CYL</th>
                                                        <th class="lt_class">L. AXIS</th>
                                                        <th class="lt_class">L. PRISM</th>
                                                        <th>Doctor</th>
                                                        <th  style="width: 120px !important;">Creation Date</th>
                                                        <th>Is Deleted</th>
                                                    <th style="width: 40px !important;">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top:15px;">
                                        <div class="col-lg-12 col-md-12">
                                        <h4 style="margin-bottom:1px !important;margin-top:1px !important;"><button class="btn btn-default" onclick="add_optic_detail(\'${client_id}\','near','Near',0)">Add Near</button></h4>
                                            <table id="optic_near_table" class="table table-striped table-bordered" cellspacing="0">
                                                <thead>
                                                    <tr>
                                                        <th>Optic ID</th>
                                                        <th style="width: 70px !important;">Date</th>
                                                        <th class="rt_class">RT SPH</th>
                                                        <th class="rt_class">RT CYL</th>
                                                        <th class="rt_class">RT AXIS</th>
                                                        <th class="rt_class">RT PRISM</th>
                                                        <th class="lt_class">L. SPH</th>
                                                        <th class="lt_class">L. CYL</th>
                                                        <th class="lt_class">L. AXIS</th>
                                                        <th class="lt_class">L. PRISM</th>
                                                        <th>Doctor</th>
                                                        <th  style="width: 120px !important;">Creation Date</th>
                                                        <th>Is Deleted</th>
                                                    <th style="width: 40px !important;">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row"  style="margin-top:15px;">
                                        <div class="col-lg-12 col-md-12">
                                        <h4 style="margin-bottom:1px !important;margin-top:1px !important;"><button class="btn btn-default" onclick="add_optic_detail(\'${client_id}\','lens','Lens',0)">Add Lenses</button></h4>
                                            <table id="optic_lens_table" class="table table-striped table-bordered" cellspacing="0">
                                                <thead>
                                                    <tr>
                                                        <th>Optic ID</th>
                                                        <th style="width: 70px !important;">Date</th>
                                                        <th class="rt_class">RT SPH</th>
                                                        <th class="rt_class">RT CYL</th>
                                                        <th class="rt_class">RT AXIS</th>
                                                        <th class="rt_class">RT PRISM</th>
                                                        <th class="lt_class">L. SPH</th>
                                                        <th class="lt_class">L. CYL</th>
                                                        <th class="lt_class">L. AXIS</th>
                                                        <th class="lt_class">L. PRISM</th>
                                                        <th>Doctor</th>
                                                        <th  style="width: 120px !important;">Creation Date</th>
                                                        <th>Is Deleted</th>
                                                    <th style="width: 40px !important;">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> `;
    $("#" + modal_name).modal("hide");
    $("body").append(content);



    $('#' + modal_name).on('shown.bs.modal', function (e) {
        $('.selectpicker').selectpicker();
        $("#filter_include_optic_user").select2({
            ajax: {
                url: '?r=optic&f=search_for_client',
                data: function (params) {
                    var query = {
                        p0: params.term || "",
                        p: params.page || 1,
                        p2: params.client_id || 0

                    }

                    // Query parameters will be ?search=[term]&type=public
                    return query;
                },
                delay: 250,
                dataType: 'json'
            },
            placeholder: "Search By Client",
            dropdownParent: $("#optic_details"),
            allowClear: true
        });

        get_optic_details_table("distance", client_id);
        get_optic_details_table("lens", client_id);
        get_optic_details_table("near", client_id);

    });

    $('#' + modal_name).on('hide.bs.modal', function (e) {
        $("#" + modal_name).remove();
    });

    $('#' + modal_name).modal('show');











}

var current_cashbox_date = "today"

function get_optic_details_table(optic_type, client_id) {
    var search_fields = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11];

    var default_client_selected = client_id;
    if (client_id == 0) {
        default_client_selected = $("#filter_include_optic_user").val() ?? "";
    }

    $('#optic_' + optic_type + '_table').dataTable({
        ajax: "?r=optic&f=get_optic_details&p0=" + default_client_selected + "&p1=" + optic_type,
        orderCellsTop: true,
        aoColumnDefs: [{
            "targets": [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 13],
            "searchable": true,
            "orderable": true,
            "visible": true
        }, {
            "targets": [0, 11, 12],
            "searchable": false,
            "orderable": false,
            "visible": false
        }
        ],
        scrollY: '44vh',
        iDisplayLength: 100,
        scrollCollapse: true,
        paging: false,
        bInfo: false,
        bSort: false,
        dom: '<"toolbar_optic_' + optic_type + '">frtip',
        initComplete: function (settings) {


            $(".sk-circle-layer").hide();
        },
        fnDrawCallback: function () {
            optic_details_callback(optic_type);

        },
        fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            
        },
    });

    $('#optic_' + optic_type + '_table').on('page.dt', function () {
        optic_details_callback(optic_type);
    });


    $('#optic_' + optic_type + '_table').DataTable().on('mousedown', "tr", function (e, dt, type, indexes) {
        $('.selected').removeClass("selected");
        $(this).addClass('selected');
    });

}

function refresh_optic_details_table(client_id) {
    $(".sk-circle-layer").show();
    var optic_type1 = "distance";
    var optic_type2 = "lens";
    var optic_type3 = "near";

    // var default_client_selected = client_id;
    // if (client_id == 0) {
    // }
    default_client_selected = $("#filter_include_optic_user").val() ?? "";


    $('#optic_' + optic_type1 + '_table').DataTable().ajax.url("?r=optic&f=get_optic_details&p0=" + default_client_selected + "&p1=" + optic_type1).load(function () {
        $(".sk-circle-layer").hide();
        setTimeout(function () {
            $('#optic_' + optic_type1 + '_table').DataTable().columns.adjust().draw();
        }, 100);
    }, false);

    $('#optic_' + optic_type2 + '_table').DataTable().ajax.url("?r=optic&f=get_optic_details&p0=" + default_client_selected + "&p1=" + optic_type2).load(function () {
        $(".sk-circle-layer").hide();
        setTimeout(function () {
            $('#optic_' + optic_type2 + '_table').DataTable().columns.adjust().draw();
        }, 100);
    }, false);



    $('#optic_' + optic_type3 + '_table').DataTable().ajax.url("?r=optic&f=get_optic_details&p0=" + default_client_selected + "&p1=" + optic_type3).load(function () {
        $(".sk-circle-layer").hide();
        setTimeout(function () {
            $('#optic_' + optic_type3 + '_table').DataTable().columns.adjust().draw();
        }, 100);
    }, false);
}


function optic_details_callback(optic_type) {

    var table = $('#optic_' + optic_type + '_table').DataTable();
    var p = table.rows({
        page: 'current'
    }).nodes();
    for (var k = 0; k < p.length; k++) {
        var index = table.row(p[k]).index();
        var delete_btn = ""

        if (table.cell(index, 12).data() == "0") {
            delete_btn = '<i title="Delete" class="glyphicon glyphicon-trash red" style="font-size:18px;cursor:pointer" onclick="delete_optic_detail(' + table.cell(index, 0).data() + ',\'' + optic_type + '\'' + ')"></i>';
        }

        var optic_type_txt = optic_type.charAt(0).toUpperCase() + optic_type.slice(1);
        var edit_btn = '<i title="Edit" class="glyphicon glyphicon-edit" style="font-size:18px;cursor:pointer"  onclick="add_optic_detail(' + $("#filter_include_optic_user").val() + ',\'' + optic_type + '\'' + ',\'' + optic_type_txt + '\'' + ',' + table.cell(index, 0).data() + ')" style="font-size:18px;cursor:pointer" ></i> ';
        table.cell(index, 13).data(delete_btn + "&nbsp;&nbsp;&nbsp;" + edit_btn);
    }



}



function add_optic_detail(client_id, optic_type, optic_type_txt, optic_id) {
    $(".sk-circle-layer").show();

    var client_readonly = "";
    $("#optic_client_id").empty();
    if ($("#filter_include_optic_user").val() > 0) {
        client_readonly = "disabled";


        var _data = [];
        $.getJSON("?r=customers&f=get_client_info&p0=" + $("#filter_include_optic_user").val(), function (data) {
            _data = data;
        }).done(function () {
            $("#optic_client_id").append('<option value="' + $("#filter_include_optic_user").val() + '">' + _data.client_info + '</option>');

        });


    }

    if (optic_id == 0) {
        var modal_title = "Add New " + optic_type_txt;
        var optic_action_btn = "Add";

    } else {
        var modal_title = " Edit Opitc " + optic_type_txt + " #" + optic_id;
        var optic_action_btn = "Update";

    }


    // var default_client_selected = client_id;
    // if (client_id == 0) {
    //     default_client_selected = $("#filter_include_optic_user").val() ?? "";
    // }

    var content =
        '<div class="modal medium" data-backdrop="static" data-keyboard="false" id="new_optic_detail_modal" tabindex="-1" role="dialog" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title" id=""><i class="glyphicon glyphicon-plus"></i>&nbsp; '+ modal_title + '<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\'new_optic_detail_modal\')"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-xs-4" >\n\
                                <div class="form-group" id="client_form_grp">\n\
                                    <label for="">Client ID</label>\n\
                                    <select  id="optic_client_id" '+ client_readonly + ' style="width:100%"  class="form-control col_input" onchange="" >\n\
                                    </select>\n\
                                    </div>\n\
                            </div>\n\
                            <div class="col-xs-4" >\n\
                                <div class="form-group">\n\
                                    <label for="">Date</label>\n\
                                   <input id="optic_date"  type="text" class="form-control col_input" placeholder="" >\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-xs-4" >\n\
                                <div class="form-group">\n\
                                    <label for="">Doctor</label>\n\
                                    <input id="optic_doctor" name="optic_doctor" type="text" class="form-control col_input" placeholder="" />\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-xs-3" >\n\
                                <div class="form-group">\n\
                                    <label for="">RT SPH</label>\n\
                                    <input id="optic_r_eye_sph" name="r_eye_sph" type="number" step="0.25" class="form-control col_input rt_class" placeholder="" />\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-xs-3" >\n\
                                <div class="form-group">\n\
                                    <label for="">RT CYL</label>\n\
                                    <input id="optic_r_eye_cyl" name="r_eye_cyl" type="number" step="0.25" class="form-control col_input rt_class" placeholder="" />\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-xs-3" >\n\
                                <div class="form-group">\n\
                                    <label for="">RT Axis</label>\n\
                                    <input id="optic_r_eye_axis" name="r_eye_axis" type="number" step="0.25" class="form-control col_input rt_class" placeholder="" />\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-xs-3" >\n\
                                <div class="form-group">\n\
                                    <label for="">RT Prism</label>\n\
                                    <input id="optic_r_eye_prism" name="r_eye_prism" type="text" class="form-control col_input rt_class" placeholder="" />\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-xs-3" >\n\
                                <div class="form-group ">\n\
                                    <label for="">L. SPH</label>\n\
                                    <input id="optic_l_eye_sph" name="l_eye_sph" type="number" step="0.25" class="form-control col_input lt_class" placeholder="" />\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-xs-3" >\n\
                                <div class="form-group">\n\
                                    <label for="">L. CYL</label>\n\
                                    <input id="optic_l_eye_cyl" name="l_eye_cyl" type="number" step="0.25" class="form-control col_input lt_class" placeholder="" />\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-xs-3" >\n\
                                <div class="form-group">\n\
                                    <label for="">L. Axis</label>\n\
                                    <input id="optic_l_eye_axis" name="l_eye_axis" type="number" step="0.25" class="form-control col_input lt_class" placeholder="" />\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-xs-3" >\n\
                                <div class="form-group">\n\
                                    <label for="">L. Prism</label>\n\
                                    <input id="optic_l_eye_prism" name="l_eye_prism" type="text" class="form-control col_input lt_class" placeholder="" />\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-footer">\n\
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\n\
                        <a onclick="submit_optic_client_details('+ client_id + ',\'' + optic_type + '\',' + optic_id + ')" type="submit" class="btn btn-primary">' + optic_action_btn + '</a>\n\
                    </div>\n\
                    <form/>\n\
                </div>\n\
            </div>\n\
        </div>';

    $('#new_optic_detail_modal').modal("hide");
    $('body').append(content);

    $('.selectpicker').selectpicker();
    $("#optic_client_id").select2({
        ajax: {
            url: '?r=optic&f=search_for_client',
            data: function (params) {
                var query = {
                    p0: params.term || "",
                    p: params.page || 1,
                    p2: params.client_id || 0

                }

                // Query parameters will be ?search=[term]&type=public
                return query;
            },
            delay: 250,
            dataType: 'json'
        },
        placeholder: "Search By Client",
        dropdownParent: $("#new_optic_detail_modal"),
        allowClear: true
    });





    $('#optic_date').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,

    });
    $('#optic_date').datepicker("setDate", new Date());
    $('#optic_date').datepicker().on('changeDate', function (ev) {

    }).on('hide show', function (event) {
        event.preventDefault();
        event.stopPropagation();
    });


    $('#new_optic_detail_modal').on('hide.bs.modal', function (e) {
        $('#new_optic_detail_modal').remove();
    });


    $('#new_optic_detail_modal').on('shown.bs.modal', function (e) {

        $(".sk-circle-layer").hide();

        if (optic_id == 0) {
        } else {
            $.getJSON("?r=optic&f=get_optic_details_by_id&p0=" + optic_id, function (data) {
                _data = data;
            }).done(function () {
                $("#optic_l_eye_sph").val(_data[0].l_eye_sph);
                $("#optic_l_eye_cyl").val(_data[0].l_eye_cyl);
                $("#optic_l_eye_axis").val(_data[0].l_eye_axis);
                $("#optic_l_eye_prism").val(_data[0].l_eye_prism);
                $("#optic_r_eye_sph").val(_data[0].r_eye_sph);
                $("#optic_r_eye_cyl").val(_data[0].r_eye_cyl);
                $("#optic_r_eye_axis").val(_data[0].r_eye_axis);
                $("#optic_r_eye_prism").val(_data[0].r_eye_prism);
                $("#optic_doctor").val(_data[0].doctor);
                $("#optic_date").val(_data[0].date);

            });
        }

    });

    $('#new_optic_detail_modal').modal('show');
    $('#optic_date').datepicker();

}


function submit_optic_client_details(client_id, optic_type, optic_id) {
    var inputs_is_filled = true;

    // $(".input_border_error").removeClass('input_border_error');
    // $("#new_optic_detail_modal input[type=text]").each(function () {
    //     if ($(this).val() == "") {
    //         $("#" + $(this).attr("id")).addClass('input_border_error');
    //         inputs_is_filled = false;
    //     }
    // });

    if ($("#optic_client_id").val() == "" || $("#optic_client_id").val() == null) {
        $("#client_form_grp  .select2-selection--single").addClass('input_border_error');
        inputs_is_filled = false;
    }
    if (inputs_is_filled) {
        $(".sk-circle-layer").show();
        var formData = new FormData();
        formData.append("optic_type", optic_type);

        $('.col_input').each(function (i, obj) {

            formData.append($(obj).attr("id"), $(obj).val());
        });
        if (optic_id > 0) {
            formData.append("optic_id", optic_id);

        }

        $.ajax({
            url: "?r=optic&f=add_optic_details",
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            success: function (data) {
                $(".sk-circle-layer").hide();
                if (data > 0) {
                    // var default_client_selected = client_id;

                    // var client_selected=$("#filter_include_optic_user").val();
                    // if($("#filter_include_optic_user").val()==null){
                    //     client_selected=$("#optic_client_id").val();
                    // }

                    default_client_selected = $("#optic_client_id").val() ?? "";
                    var _data = [];
                    $.getJSON("?r=customers&f=get_client_info&p0=" + $("#optic_client_id").val(), function (data) {
                        _data = data;
                    }).done(function () {
                        // global_client_data=_data.client_data;
                        set_client_info(_data.client_data);
                        if ($("#filter_include_optic_user").val() == null) {
                            $("#filter_include_optic_user").empty();
                            $("#filter_include_optic_user").append('<option value="' + default_client_selected + '">' + _data.client_info + '</option>');
                            $("#filter_include_optic_user").select2({
                                ajax: {
                                    url: '?r=optic&f=search_for_client',
                                    data: function (params) {
                                        var query = {
                                            p0: params.term || "",
                                            p: params.page || 1,
                                            p2: params.client_id || 0

                                        }

                                        // Query parameters will be ?search=[term]&type=public
                                        return query;
                                    },
                                    delay: 250,
                                    dataType: 'json'
                                },
                                placeholder: "Search By Client",
                                dropdownParent: $("#optic_details"),
                                allowClear: true
                            });
                            $("#edit_customer_col").show();
                            $("#edit_customer_section").empty();
                            $("#edit_customer_section").append('<button class="btn btn-default" onclick="add_new_customer(' + default_client_selected + ')" style="width:100%"> Edit Customer</button>');

                        }

                        refresh_optic_details_table(default_client_selected);

                    });

                    $('#new_optic_detail_modal').modal('hide');


                } else {
                    alert("Optic Detail was not added!"); // change it
                }

            }

        });
    }
}


function refresh_client_info(object_id) {

    var client_fname = "";
    var client_mname = "";
    var client_lname = "";
    var client_phone_nb = "";
    var client_address = "";
    var client_pd = "";
    var client_doctor = "";
    var client_note1 = "";
    var client_note2 = "";

    $("#client_first_name").val("");
    $("#client_middle_name").val("");
    $("#client_last_name").val("");
    $("#client_phone_nb").val("");
    $("#client_address").html("");
    $("#client_pd").val("");
    $("#client_doctor").val("");
    $("#client_note1").val("");
    $("#client_note2").val("");
    $("#client_note").html("");

    var client_selected = $(object_id).val() ?? -1;
    var _data = [];
    $.getJSON("?r=customers&f=get_client_info&p0=" + client_selected, function (data) {
        _data = data;
    }).done(function () {
        $("#edit_customer_col").hide();

        if (client_selected > 0) {
            if (_data.client_data.length > 0) {
                $("#edit_customer_col").show();
                $("#edit_customer_section").empty();
                $("#edit_customer_section").append('<button class="btn btn-default" onclick="add_new_customer(' + client_selected + ')" style="width:100%"> Edit Customer</button>');

                client_fname = _data.client_data[0].name;
                client_mname = _data.client_data[0].middle_name;
                client_lname = _data.client_data[0].last_name;
                client_phone_nb = _data.client_data[0].phone;
                client_address = _data.client_data[0].address;
                client_pd = _data.client_data[0].pd;
                client_doctor = _data.client_data[0].doctor;
                client_note1 = _data.client_data[0].note1;
                client_note2 = _data.client_data[0].note2;
                client_note = _data.client_data[0].note;


                $("#client_first_name").val(client_fname);
                $("#client_middle_name").val(client_mname);
                $("#client_last_name").val(client_lname);
                $("#client_phone_nb").val(client_phone_nb);
                $("#client_address").html(client_address);
                $("#client_pd").val(client_pd);
                $("#client_doctor").val(client_doctor);
                $("#client_note1").val(client_note1);
                $("#client_note2").val(client_note2);
                $("#client_note").html(client_note);



            }
        }

    });
}



function set_client_info(client_data) {
    var _data = [];


    var client_fname = "";
    var client_mname = "";
    var client_lname = "";
    var client_phone_nb = "";
    var client_address = "";
    var client_pd = "";
    var client_doctor = "";
    var client_note1 = "";
    var client_note2 = "";
    var client_note = "";

    $("#client_first_name").val("");
    $("#client_middle_name").val("");
    $("#client_last_name").val("");
    $("#client_phone_nb").val("");
    $("#client_address").html("");
    $("#client_note").html("");
    $("#client_pd").val("");
    $("#client_doctor").val("");


    if (client_data.length > 0) {

        client_fname = client_data[0].name;
        client_mname = client_data[0].middle_name;
        client_lname = client_data[0].last_name;
        client_phone_nb = client_data[0].phone;
        client_address = client_data[0].address;
        client_pd = client_data[0].pd;
        client_doctor = client_data[0].doctor;
        client_note1 = client_data[0].note1;
        client_note2 = client_data[0].note2;
        client_note = client_data[0].note;

        $("#client_first_name").val(client_fname);
        $("#client_middle_name").val(client_mname);
        $("#client_last_name").val(client_lname);
        $("#client_phone_nb").val(client_phone_nb);
        $("#client_address").html(client_address);
        $("#client_pd").val(client_pd);
        $("#client_doctor").val(client_doctor);
        $("#client_note1").val(client_note1);
        $("#client_note2").val(client_note2);
        $("#client_note").html(client_note);



    }

}


function delete_optic_detail(optic_id, optic_type) {


    swal({
        title: "Are you sure?",
        text: `Delete Optic Detail with id ${optic_id}?, click delete to continue`,
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Delete",
        closeOnConfirm: true
    },
        function (isConfirm) {
            $(".sk-circle").center();
            $(".sk-circle-layer").show();
            if (isConfirm) {
                $.getJSON(`?r=optic&f=delete_optic_detail&p0=${optic_id}`, function (data) {

                }).done(function () {


                    $('#optic_' + optic_type + '_table').DataTable().ajax.url("?r=optic&f=get_optic_details&p0=" + $("#filter_include_optic_user").val() + "&p1=" + optic_type).load(function () {
                        $(".sk-circle-layer").hide();
                        setTimeout(function () {
                            $('#optic_' + optic_type + '_table').DataTable().columns.adjust().draw();
                        }, 100);
                    }, false);
                });
            } else {
                $(".sk-circle-layer").hide();

            }
        });
}


function add_new_customer(client_id) {

    if (client_id == 0) {

    }

    $(".sk-circle-layer").show();



    if (client_id == 0) {
        var modal_title = "Add New Customer";
        var new_customer_action_btn = "Add";

    } else {
        var modal_title = " Edit Customer # " + client_id;
        var new_customer_action_btn = "Update";

    }


    // var default_client_selected = client_id;
    // if (client_id == 0) {
    //     default_client_selected = $("#filter_include_optic_user").val() ?? "";
    // }

    var content =
        '<div class="modal medium" data-backdrop="static" data-keyboard="false" id="new_customer_modal" tabindex="-1" role="dialog" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title" id=""><i class="glyphicon glyphicon-plus"></i>&nbsp; '+ modal_title + '<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\'new_customer_modal\')"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-xs-4" >\n\
                                <div class="form-group">\n\
                                    <label for="">First Name</label>\n\
                                   <input id="optic_customer_name"  type="text" class="form-control  customer_input" placeholder="" >\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-xs-4" >\n\
                                <div class="form-group">\n\
                                    <label for="">Middle Name</label>\n\
                                    <input id="optic_customer_middle_name"   type="text" class="form-control customer_input" placeholder="" />\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-xs-4" >\n\
                                <div class="form-group">\n\
                                    <label for="">Last Name</label>\n\
                                    <input id="optic_customer_last_name"   type="text" class="form-control  customer_input" placeholder="" />\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-xs-4" >\n\
                                <div class="form-group">\n\
                                    <label for="">Phone Number</label>\n\
                                    <input id="optic_customer_phone"   type="text" class="form-control customer_input" placeholder="" />\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-xs-4" >\n\
                                <div class="form-group">\n\
                                    <label for="">PD</label>\n\
                                    <input id="optic_customer_pd"   type="text" class="form-control customer_input" placeholder="" />\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-xs-4" >\n\
                                <div class="form-group">\n\
                                    <label for="">Doctor</label>\n\
                                    <input id="optic_customer_doctor"   type="text" class="form-control customer_input" placeholder="" />\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-xs-6" >\n\
                                <div class="form-group">\n\
                                    <label for="">Note</label>\n\
                                    <textarea id="optic_customer_note"    class="form-control  customer_input"  placeholder=""></textarea>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-xs-6" >\n\
                                <div class="form-group ">\n\
                                    <label for="">Address</label>\n\
                                    <textarea id="optic_customer_address"    class="form-control  customer_input"  placeholder=""></textarea>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-footer">\n\
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\n\
                        <a onclick="submit_new_customer('+ client_id + ')" type="submit" class="btn btn-primary">' + new_customer_action_btn + '</a>\n\
                    </div>\n\
                    <form/>\n\
                </div>\n\
            </div>\n\
        </div>';

    $('#new_customer_modal').modal("hide");
    $('body').append(content);

    if (client_id > 0) {
        var _data = [];
        $.getJSON("?r=customers&f=get_client_info&p0=" + client_id, function (data) {
            _data = data;
        }).done(function () {
            $("#optic_customer_name").val(_data.client_data[0].name);
            $("#optic_customer_middle_name").val(_data.client_data[0].middle_name);
            $("#optic_customer_last_name").val(_data.client_data[0].last_name);
            $("#optic_customer_phone").val(_data.client_data[0].phone);
            $("#optic_customer_pd").val(_data.client_data[0].pd);
            $("#optic_customer_doctor").val(_data.client_data[0].doctor);
            $("#optic_customer_note").html(_data.client_data[0].note);
            $("#optic_customer_address").html(_data.client_data[0].address);


        });
    }
    $('#new_customer_modal').on('shown.bs.modal', function (e) {
        $(".sk-circle-layer").hide();
        
        
        if($("#optic_cl_first_name").val().length>0){
            $("#optic_customer_name").val($("#optic_cl_first_name").val());
        }
        if($("#optic_cl_middle_name").val().length>0){
            $("#optic_customer_middle_name").val($("#optic_cl_middle_name").val());
        }
        if($("#optic_cl_last_name").val().length>0){
            $("#optic_customer_last_name").val($("#optic_cl_last_name").val());
        }
        
        if($("#optic_cl_phone_nb").val().length>0){
            $("#optic_customer_phone").val($("#optic_cl_phone_nb").val());
        }
        
        if($("#optic_cl_doctor").val().length>0){
            $("#optic_customer_doctor").val($("#optic_cl_doctor").val());
        }

    });



    $('#new_customer_modal').on('hide.bs.modal', function (e) {
        $('#new_customer_modal').remove();
    });

    $('#new_customer_modal').modal('show');


}


function submit_new_customer(client_id) {
    var inputs_is_filled = true;
    
    // $(".input_border_error").removeClass('input_border_error');
    $(".customer_input").each(function () {
        if ($("#optic_customer_name").val() == "") {
            $("#optic_customer_name").addClass('input_border_error');
            inputs_is_filled = false;
        }
    });

    if (inputs_is_filled) {
        $(".sk-circle-layer").show();
        var formData = new FormData();
        $('.customer_input').each(function (i, obj) {
            formData.append($(obj).attr("id"), $(obj).val());
        });
        if (client_id > 0) {
            formData.append("optic_customer_id", client_id);

        }

        $.ajax({
            url: "?r=customers&f=add_customer",
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            success: function (data) {
                $(".sk-circle-layer").hide();
                if (data.error == 0) {
                    show_client_optic_details(data.client_data[0].id);
                    set_client_info(data.client_data);
                    refresh_optic_details_table(data.client_data[0].id);

                    $("#filter_include_optic_user").empty();
                    $("#filter_include_optic_user").append('<option value="' + data.client_data[0].id + '">' + data.client_info + '</option>');
                    $("#filter_include_optic_user").select2({
                        ajax: {
                            url: '?r=optic&f=search_for_client',
                            data: function (params) {
                                var query = {
                                    p0: params.term || "",
                                    p: params.page || 1,
                                    p2: params.client_id || 0

                                }

                                // Query parameters will be ?search=[term]&type=public
                                return query;
                            },
                            delay: 250,
                            dataType: 'json'
                        },
                        placeholder: "Search By Client",
                        allowClear: true
                    });

                    if (client_id == 0) {
                        $("#edit_customer_col").show();
                        $("#edit_customer_section").empty();
                        $("#edit_customer_section").append('<button class="btn btn-default" onclick="add_new_customer(' + data.client_data[0].id + ')" style="width:100%">Edit Customer</button>');

                    }

                    $('#new_customer_modal').modal('hide');

                    if ($("#optic_clients_table").is(":visible") && $("#optic_clients_table").length > 0) {
                        refresh_optic_clients_table();
                    }

                } else {
                    alert("Customer was not added!"); // change it
                }

            }

        });
    }


}


function clear_all(){
    $(".empty").val("");
    $("#optic_cl_first_name").trigger("input");
}

function show_optic_clients_info() {
    
     if (!navigator.onLine) {
            //swal("Check your internet connection");
            //return;
        }
            
    $(".sk-circle").center();
    $(".sk-circle-layer").show();



    modal_name = "optic_clients_modal";
    modal_title = "<i class='glyphicon glyphicon-briefcase'></i> Optic Clients Info  ";


    var content =
        `<div class= "modal" data-backdrop="static" id = "${modal_name}" tabindex = "-1" role = "dialog" aria - labelledby="" aria - hidden="true" >
                <div class="modal-dialog" style="margin-top:0!important" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">${modal_title}<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\'${modal_name}\')"></i></h3>
                        </div>
                        <div class="modal-body" style="padding-top:2px;">
                            <div class="row" >
                                <div class="col-lg-12">
                                    <div class="row">\n\
                                        <div class="col-xs-2" >\n\
                                            <div class="form-group">\n\
                                                <label for="">First Name&nbsp;&nbsp;<span onclick="clear_all()" style="font-size:14px; color:#286090; cursor:pointer">Clear All</span></label>\n\
                                                <input id="optic_cl_first_name"  type="text" class="form-control empty " placeholder="" oninput="refresh_optic_clients_table()" >\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-xs-2" >\n\
                                            <div class="form-group">\n\
                                                <label for="">Middle Name</label>\n\
                                                <input id="optic_cl_middle_name"  type="text" class="form-control empty" placeholder="" oninput="refresh_optic_clients_table()" >\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-xs-2" >\n\
                                            <div class="form-group">\n\
                                                <label for="">Last Name</label>\n\
                                                <input id="optic_cl_last_name"  type="text" class="form-control empty" placeholder="" oninput="refresh_optic_clients_table()">\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-xs-2" >\n\
                                            <div class="form-group">\n\
                                                <label for="">Phone Number</label>\n\
                                                <input id="optic_cl_phone_nb"  type="text" class="form-control empty" placeholder="" oninput="refresh_optic_clients_table()">\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-xs-2" >\n\
                                            <div class="form-group">\n\
                                                <label for="">Doctor</label>\n\
                                                <input id="optic_cl_doctor"  type="text" class="form-control empty" placeholder="" oninput="refresh_optic_clients_table()">\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-xs-2" >\n\
                                            <div class="form-group">\n\
                                                <label for="">&nbsp;</label>\n\
                                                <button style="width:100%" class="btn btn-primary" onclick="add_new_customer(0)" > Add New Customer</button>\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12">
                                        <h4 style="margin-bottom:1px !important;margin-top:3px !important;"></h4>
                                            <table id="optic_clients_table" class="table table-striped table-bordered" cellspacing="0">
                                                <thead>
                                                    <tr>
                                                    <th> ID</th>
                                                    <th> First Name</th>
                                                    <th> Middle Name</th>
                                                    <th> Last Name</th>
                                                    <th> Phone Number</th>
                                                    <th> PD</th>
                                                    <th> Doctor</th>
                                                    <th> Note</th>
                                                    <th> Address</th>
                                                    <th style="width: 180px !important;">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                    <th> ID</th>
                                                    <th> First Name</th>
                                                    <th> Middle Name</th>
                                                    <th> Last Name</th>
                                                    <th> Phone Number</th>
                                                    <th> PD</th>
                                                    <th> Doctor</th>
                                                    <th> Note</th>
                                                    <th> Address</th>
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
                    </div>
                </div>
            </div> `;
    $("#" + modal_name).modal("hide");
    $("body").append(content);

    $('#' + modal_name).on('shown.bs.modal', function (e) {

        get_optic_client_info_table();

    });

    $('#' + modal_name).on('hide.bs.modal', function (e) {
        $("#" + modal_name).remove();
    });

    $('#' + modal_name).modal('show');



}


function get_optic_client_info_table() {

    var search_fields = [0, 1, 2, 3, 4, 5, 6, 7, 8];
    var index = 0;
    $('#optic_clients_table tfoot th').each(function () {
        if (jQuery.inArray(index, search_fields) !== -1) {
            var title = $(this).text();
            $(this).html('<div class="inner-addon left-addon"><input  style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder=" ' + title + '" /></div>');
        }
        index++;
    });



    $('#optic_clients_table').dataTable({
        ajax: "?r=optic&f=get_optic_clients_info_by_filters&p0=" + $("#optic_cl_first_name").val() + "&p1=" + $("#optic_cl_middle_name").val() + "&p2=" + $("#optic_cl_last_name").val() + "&p3=" + $("#optic_cl_phone_nb").val() + "&p4=" + $("#optic_cl_doctor").val(),
        orderCellsTop: true,
        aoColumnDefs: [{
            "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8],
            "searchable": true,
            "orderable": true,
            "visible": true
        }, {
            "targets": [],
            "searchable": false,
            "orderable": false,
            "visible": false
        }
        ],
        scrollY: '44vh',
        iDisplayLength: 100,
        scrollCollapse: true,
        paging: false,
        bInfo: false,
        bSort: false,
        dom: '<"toolbar_optic_clients_table">frtip',
        initComplete: function (settings) {
            $('#optic_clients_table').show();

            $("div.toolbar_optic_clients_table").html();

            $(".sk-circle-layer").hide();

        },
        fnDrawCallback: function () {
            optic_clients_table_callback();

        },
        fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            //$(nRow).addClass(aData[0]);
        },
    });

    $('#optic_clients_table').on('page.dt', function () {
        // $("#tab_toolbar button.blueB").addClass("disabled");
        optic_clients_table_callback();
    });

    $('#optic_clients_table').DataTable().columns().every(function () {
        var that = this;
        $('input', this.footer()).on('keyup change', function () {
            if (that.search() !== this.value) {
                that.search(this.value).draw();
            }
        });
    });

    $('#optic_clients_table').DataTable().on('mousedown', "tr", function (e, dt, type, indexes) {
        $('.selected').removeClass("selected");
        $(this).addClass('selected');
    });

}

function delete_optic_customer(id){
    swal({
        title: "Delete customer!!!",
        text: "Are you sure that you want to delete this customer?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Delete now",
        cancelButtonText: "Cancel",
        closeOnConfirm: true
      },
    function(isconfirm){
        if(isconfirm){
            $(".sk-circle").center();
            $(".sk-circle-layer").show();
            $.getJSON("?r=customers&f=delete_customer_&p0=" + id, function (data) {
                
            }).done(function () {
                $("#optic_cl_first_name").trigger("input");
            });
        }else{

        }
    });
    
}

function optic_clients_table_callback() {
    $(".sk-circle-layer").hide();

    var table = $('#optic_clients_table').DataTable();
    var p = table.rows({
        page: 'current'
    }).nodes();
    for (var k = 0; k < p.length; k++) {
        var index = table.row(p[k]).index();
        var client_btn = '<button  class="btn btn-primary btn-xs" onclick="show_client_optic_details(' + table.cell(index, 0).data() + ')"><i class="glyphicon glyphicon-eye-open" style="float: left;padding-top:1px;"></i> &nbsp; Optic</button>';
        var delete_btn = '&nbsp;&nbsp;<button  class="btn btn-danger btn-xs" onclick="delete_optic_customer(' + table.cell(index, 0).data() + ')"><i class="glyphicon glyphicon-trash" style="float: left;padding-top:1px;"></i> &nbsp; Delete</button>';
        var edit_btn = '&nbsp;&nbsp;<button  class="btn btn-primary btn-xs" onclick="add_new_customer(' + table.cell(index, 0).data() + ')"><i class="glyphicon glyphicon-edit" style="float: left;padding-top:1px;"></i> &nbsp; Edit</button>';
        
        table.cell(index, 9).data(client_btn+edit_btn+delete_btn);
    }


}

var timeoutId=null;
function refresh_optic_clients_table() {
    
    if(timeoutId!=null){
        clearTimeout(timeoutId);
    }
    
    timeoutId = setTimeout(function () {
            $('#optic_clients_table').DataTable().ajax.url("?r=optic&f=get_optic_clients_info_by_filters&p0=" + $("#optic_cl_first_name").val() + "&p1=" + $("#optic_cl_middle_name").val() + "&p2=" + $("#optic_cl_last_name").val() + "&p3=" + $("#optic_cl_phone_nb").val() + "&p4=" + $("#optic_cl_doctor").val()).load(function () {
            setTimeout(function () {
                $('#optic_clients_table').DataTable().columns.adjust().draw();
            }, 100);
        }, false);
    }, 500);
        
    

}
