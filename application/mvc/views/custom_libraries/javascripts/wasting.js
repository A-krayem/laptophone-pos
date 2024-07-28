function wasting_clear(){
    
    swal({
        title: "Are you sure?",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes",
        closeOnConfirm: true,
        cancelButtonText: "Cancel",
    },
    function(isConfirm){
        if(isConfirm){
            $(".sk-circle").center();
            $(".sk-circle-layer").show();
            $.getJSON("?r=wasting&f=wasting_clear", function (data) {

            }).done(function () {
                $(".sk-circle-layer").hide();
                refresh_wasting_table(0);
            });
        }
    });
    
    
     
}

function wasting(is_admin){
    var hide_pos_section="";
    var hide_admin_section="display:none;";
    if(is_admin==1){
        hide_pos_section="display:none;";
        hide_admin_section="";
    }
    
    var modal_name = "modal_wasting_modal__";
    var modal_title = "Wasting";
    var content =
    '<div class="modal medium-plus" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <form id="generate_form" action="" method="post" enctype="multipart/form-data" >\n\
                    <input id="id_to_edit" name="id_to_edit" value="0" type="hidden" />\n\
                    <input id="counter_type" name="counter_type" value="2" type="hidden" />\n\
                    <div class="modal-header">\n\
                        <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\''+modal_name+'\')"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row" style="'+hide_pos_section+'">\n\
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">\n\
                                <button onclick="showAllItems(0)" style="width:100%" type="button" class="btn btn-primary">Items Table</button>\n\
                            </div>\n\
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">\n\
                                <button onclick="open_gallery(0)" style="width:100%" type="button" class="btn btn-primary">Items Gallery</button>\n\
                            </div>\n\
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">\n\
                                <button onclick="wasting_clear()" style="width:100%" type="button" class="btn btn-warning">Clear</button>\n\
                            </div>\n\
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">\n\
                                <span style="font-size:20px;" id="w_total"></span>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row" style="'+hide_admin_section+'">\n\
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style=" margin-top:5px;">\n\
                                <input autocomplete="off" placeholder="Search by description, Barcode" type="text" id="input_prepare_items_w" name="input_prepare_items" style="width: 100%; height: 36px; margin-top: 3px; font-size: 14px;" />\n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                <table style="width:100%" id="wasting_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                    <thead>\n\
                                        <tr>\n\
                                            <th style="width:120px;">ID</th>\n\
                                            <th >Description</th>\n\
                                            <th>Note</th>\n\
                                            <th >Price</th>\n\
                                            <th >Quantity</th>\n\
                                            <th >Total</th>\n\
                                            <th >User</th>\n\
                                            <th >Type</th>\n\
                                            <th style="width:140px;">date</th>\n\
                                            <th></th>\n\
                                            <th >Same shift</th>\n\
                                        </tr>\n\
                                    </thead>\n\
                                    <tbody></tbody>\n\
                                </table>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-footer" style=" text-align: left;">\n\
                    </div>\n\
                </form>\n\
            </div>\n\
        </div>\n\
    </div>';
    $("#"+modal_name).remove();
    $("body").append(content);
    //submitGenerateInvoice(modal_name,2);
            
            
    $('#'+modal_name).on('show.bs.modal', function (e) {

    });
    
    $('#'+modal_name).on('shown.bs.modal', function (e) {
        $('#wasting_barcode').focus();
        
        if(is_admin==1){
            prepare_search_items_g_modal("input_prepare_items_w",modal_name);
        }
        
        var table_name = "wasting_table";
        var _cards_table__var =null;

            var search_fields = [];
            var index = 0;
            $('#'+table_name+' tfoot th').each( function () {
                if(jQuery.inArray(index, search_fields) !== -1){
                    var title = $(this).text();
                    $(this).html( '<input id="idf_'+index+'" style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder="'+title+'" />' );
                    index++;
                }
            });

            _cards_table__var = $('#'+table_name).DataTable({
                ajax: {
                    url: "?r=wasting&f=get_all_wasting_items&p0=0&p1=0&p2=0",
                    type: 'POST',
                    error:function(xhr,status,error) {
                    },
                    dataSrc: function (json) {
                        $("#w_total").html("Total Wasting: "+json.total);
                        return json.data;
                    }
                },
                //order: [[1, 'asc']],
                responsive: true,
                orderCellsTop: true,
                scrollX: true,
                scrollY: "55vh",
                iDisplayLength: 10,
                aoColumnDefs: [
                    { "targets": [0], "searchable": false, "orderable": true,"visible": false },
                    { "targets": [1], "searchable": false, "orderable": true,"visible": true },
                    { "targets": [2], "searchable": false, "orderable": true,"visible": true },
                    { "targets": [3], "searchable": true, "orderable": true, "visible": true },
                    { "targets": [4], "searchable": true, "orderable": true, "visible": true },
                    { "targets": [5], "searchable": true, "orderable": true, "visible": true },
                    { "targets": [6], "searchable": true, "orderable": false, "visible": true},//,"className": "dt-center"
                    { "targets": [7], "searchable": true, "orderable": false, "visible": false},
                    { "targets": [8], "searchable": true, "orderable": false, "visible": true },
                    { "targets": [9], "searchable": true, "orderable": false, "visible": true,"className": "dt-center" },
                    { "targets": [10], "searchable": true, "orderable": false, "visible": false },
                ],
                scrollCollapse: true,
                paging: true,
                bPaginate: false,
                bLengthChange: false,
                bFilter: true,
                bInfo: false,
                bAutoWidth: true,
                dom: '<"toolbarwasting">frtip',
                initComplete: function(settings, json) {  
                    
                    /*$("div.toolbar").html('\n\
                    <div class="row">\n\
                        <div class="col-lg-2 col-md-2 col-xs-12" style="padding-left:15px;padding-right:5px;">\n\
                            <div class="btn-group" role="group" aria-label="" style="width:100% !important;">\n\
                                <select disabled data-live-search="true" data-width="100%" id="set_status_all" class="selectpicker" onchange="set_status_all()">\n\
                                    '+order_status_options+'\n\
                                </select>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    ');*/
                    
                    //$(".selectpicker").selectpicker();
                    //$('#set_status_all').val(status);
                    //$('#set_status_all').selectpicker('refresh');

                    $(".sk-circle-layer").hide();
                },
                fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    $(nRow).addClass(aData[0]);
                },
                 fnDrawCallback: function(){
                    
                    var table = $('#'+table_name).DataTable();
                    var p = table.rows({ page: 'current' }).nodes();
                    for (var k = 0; k < p.length; k++){
                        var index = table.row(p[k]).index();
                        if(table.cell(index,10).data()==1)
                            table.cell(index,9).data('<i class="glyphicon glyphicon-trash" onclick="delete_waste(\''+parseInt(table.cell(index, 0).data())+'\')" style="font-size:18px;cursor:pointer" ></i>');
                    }
                 },
            });
            
            $('#'+table_name).DataTable().on('mousedown',"tr", function ( e, dt, type, indexes ) { 
                $('.selected').removeClass("selected");
                $(this).addClass('selected');
            });
        

            $('#'+table_name).on('click', 'td', function () {
                if ($(this).index() == 4 || $(this).index() == 5) {
                    //return false;
                }
            });
 
    });
    $('#'+modal_name).on('hide.bs.modal', function (e) {
        $("#"+modal_name).remove();
    });
    $('#'+modal_name).modal('show');
}

function delete_waste(id){
    
    swal({
        title: "Are you sure?",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes",
        closeOnConfirm: true,
        cancelButtonText: "Cancel",
    },
    function(isConfirm){
        if(isConfirm){
            $(".sk-circle").center();
            $(".sk-circle-layer").show();
            $.getJSON("?r=wasting&f=delete_wasting_id&p0="+id, function (data) {
        
            }).done(function () {
                $(".sk-circle-layer").hide();
                refresh_wasting_table(0);
            }); 
        }
    });
    
    
}

function wasting_get_by_id_new(w_item_id){
    var modal_name = "modal_wasting_details_modal__";
    var modal_title = "Wasting Details";
    var content =
    '<div class="modal medium" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <form id="generate_form" action="" method="post" enctype="multipart/form-data" >\n\
                    <input id="w_item_id" name="w_item_id" value="'+w_item_id+'" type="hidden" />\n\
                    <div class="modal-header">\n\
                        <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\''+modal_name+'\')"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">\n\
                                <div class="form-group">\n\
                                    <label for="wasting_note">Note</label>\n\
                                    <input class="form-control" id="wasting_note_n" name="wasting_note_n" value="" type="text" />\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">\n\
                                <div class="form-group">\n\
                                    <label for="wasting_qty">Quantity</label>\n\
                                    <input class="form-control" id="wasting_qty_n" name="wasting_qty_n" value="" type="number" />\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-footer" style=" text-align: left;">\n\
                        <div class="row">\n\
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">\n\
                                <button onclick="submit_wasing_new()" style="width:100%" type="button" class="btn btn-primary">Add</button>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                </form>\n\
            </div>\n\
        </div>\n\
    </div>';
    $("#"+modal_name).remove();
    $("body").append(content);
       
    $('#'+modal_name).on('show.bs.modal', function (e) {

    });
    
    $('#'+modal_name).on('shown.bs.modal', function (e) {
        $('#wasting_note_n').focus();
    });
    $('#'+modal_name).on('hide.bs.modal', function (e) {
        $("#"+modal_name).remove();
    });
    $('#'+modal_name).modal('show');
}

function submit_wasing_new(){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    $.getJSON("?r=wasting&f=add_wasting_item_by_id&p0="+$("#w_item_id").val()+"&p1="+$("#wasting_note_n").val()+"&p2="+$("#wasting_qty_n").val(), function (data) {
        
    }).done(function () {
        $(".sk-circle-layer").hide();
        $('#modal_wasting_details_modal__').modal('hide');
        refresh_wasting_table(1);
    });
}

function wasting_get_by_id(id){
    wasting_get_by_id_new(id);
    return;
    
    $('#noBarcodeModal').modal('toggle');
    swal({
        title: "Note and Quantity",
        html: true ,
        text: '<input type="text" id="wasting_note" value="" style="width:50%" /><input type="text" id="wasting_qty" value="" style="width:25%; float:right" />',
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes",
        cancelButtonText: "Cancel",
        closeOnConfirm: true,
        closeOnCancel: true
    },
    function(isConfirm) {
        if (isConfirm) {
            var _data = [];
            $.getJSON("?r=wasting&f=add_wasting_item_by_id&p0="+id+"&p1="+$("#wasting_note").val()+"&p2="+$("#wasting_qty").val(), function (data) {
                _data = data;
            }).done(function () {
                refresh_wasting_table(1);
            });
        }
    });
    setTimeout(function(){
        $("#wasting_note").focus();
    },100);
}



function refresh_wasting_table(last_row){
    var table = $('#wasting_table').DataTable();
    table.ajax.url("?r=wasting&f=get_all_wasting_items&p0=0&p1=0&p2=0").load(function () { 
        if(last_row==1){
            table.page('last').draw(false);
        }
    },false);
}
