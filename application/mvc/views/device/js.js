var current_id=0;

function client_changed() {
    if (current_id > 0 && $('#select-client').val()!=null) {
        $.confirm({
            title: 'Change client!',
            content: 'Are you sure?',
            buttons: {
                confirm: function () {

                    var _data=[];
                    $.getJSON("?r=invoice&f=change_client_preinvoice&p0="+$("#select-client").val()+"&p1="+current_id, function(data) {
                        _data=data;
                    }).done(function() {

                    });

                },
                somethingElse: {
                    text: 'Cancel',
                    btnClass: 'btn-danger',

                    action: function () {
                        $('#select-client').val(null).trigger('change');
                    }
                }
            }
        });
    }

}

function prepare_new_invoice(){
    var client_id = $("#select-client").val();
    var msg="";
    if(client_id==null){
        msg="Create pre-Invoice without client, ";
        client_id=0;
    }
    $.confirm({
        title: 'Create Pre-Invoice!',
        content: msg+'Are you sure?',
        buttons: {
            confirm: function () {
                
                $("#cr_inv_btn").prop("disabled", true);
                var _data=[];
                $.getJSON("?r=invoice&f=prepare_new_invoice&p0="+client_id, function(data) {
                    _data=data;
                }).done(function() {
                    $("#cr_inv_btn").prop("disabled", false);
                    current_id=_data;
                    refresh_table_of_items(current_id);
                    $("#curid").html(current_id);
                });
                
            },
            somethingElse: {
                text: 'Cancel',
                btnClass: 'btn-danger',
                
                action: function(){
                    
                }
            }
        }
    });
}

function delete_preinvoice(id){
    $.confirm({
        title: 'Delete Pre-Invoice!',
        content: 'Are you sure?',
        buttons: {
            confirm: {
                text: 'Confirm',
                btnClass: 'btn-danger',
                action: function(){
                    var _data=[];
                    $.getJSON("?r=invoice&f=delete_preinvoice&p0="+id, function(data) {
                        _data=data;
                    }).done(function() {
                        $("#precont_"+id).remove();
                        if(current_id==id){
                            current_id=0;
                            $("#curid").html("");
                            refresh_table_of_items(0);
                            $('#select-client').val(null).trigger('change');
                        }
                        
                    });
                }
            },
            somethingElse: {
                text: 'Cancel',
                btnClass: 'btn-default',
                
                action: function(){
                    
                }
            }
        }
    });
}

function refresh_table_of_items(prepare_invoice_id){
    var table = $('#items_preinvoice').DataTable();
    table.ajax.url("?r=invoice&f=get_pre_invoice_items&p0="+prepare_invoice_id).load(function (data) {
        
    }, false);
}


function add_item_prepare_invoice_by_barcode(bcode){
    var _data=[];
    $.getJSON("?r=invoice&f=add_item_prepare_invoice_by_barcode&p0="+bcode+"&p1="+current_id, function(data) {
        _data=data;
    }).done(function() {
        $("#search_barcode").val("");
        if(_data.added==0){
            $.alert({
                title: 'Alert!',
                content: 'Barcode <b class="text-danger">'+_data.barcode+'</b> not found!',
            });
        }
        
        if(_data.added==1){//item added
            
        }
        
        if(_data.added==2){//item qty increased
            
        }
  
        refresh_table_of_items(current_id);
    });
}


function load_preinvoice(id){
    current_id=id;
    $("#curid").html(current_id);
    $('#preinvoices_modal').modal('hide');
    refresh_table_of_items(current_id);
}


function show_preinvoices() {
    var content =
            '<div class="modal" data-backdrop="static" UseSubmitBehavior="false" id="preinvoices_modal" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
            <div class="modal-dialog custom-modal-class" role="document">\n\
                <div class="modal-content">\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title">Pre-invoices </h3><button type="button" data-bs-dismiss="modal" class="btn-close" aria-label="Close" style="float:right !important"></button>\n\
                    </div>\n\
                    <div class="modal-body" id="preinvoices_container">\n\
                        \n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>';

    $('#preinvoices_modal').modal('hide');
    $("body").append(content);
    $('#preinvoices_modal').on('show.bs.modal', function (e) {
        
    });

    $('#preinvoices_modal').on('shown.bs.modal', function (e) {
        $('.modal-body').css('max-height', $(window).height() * 0.7);
        var _data=[];
        $.getJSON("?r=invoice&f=get_pre_invoices", function(data) {
            _data=data;
        }).done(function() {
            for(var i=0;i<_data.length;i++){
                $("#preinvoices_container").append('\
                    <div class="row mt-2" id="precont_'+_data[i].id+'">\n\
                        <div class="col-12">\n\
                            <div class="card text-center">\n\
                                <div class="card-header">\n\
                                  <h5 class="card-title my-0">'+_data[i].client_name+' <i class="bi bi-trash" onclick="delete_preinvoice('+_data[i].id+')"></i></h5>\n\
                                </div>\n\
                                <div class="card-body">\n\
                                    <h5 class="card-title">'+_data[i].creation_date+'</h5>\n\
                                    <p class="card-text">Total Amount: <b>'+_data[i].total_amount+'</b></p>\n\
                                    <a href="#" onclick="load_preinvoice('+_data[i].id+')" class="btn btn-primary btn-sm">Load Item of Pre-Invoice</a>\n\
                                </div>\n\
                                <div class="card-footer text-muted">\n\
                                  &nbsp;\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>');
            }
        });
    });
    $('#preinvoices_modal').on('hide.bs.modal', function (e) {
        $("#preinvoices_modal").remove();
    });
    $('#preinvoices_modal').modal('show');
}


function updateRows_search_pre(){
    
}

function qty_change(item_id){
 
    var table_name = "modal_get_all_qty_if_item_table__";
    var modal_name = "modal_get_all_qty_if_item_modal__";
    var modal_title = "Available Stock";
    var content =
    '<div class="modal large" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title">'+modal_title+'</h3><button type="button" data-bs-dismiss="modal" class="btn-close" aria-label="Close" style="float:right !important"></button>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <input type="hidden" id="search_all_store_item_id" value="'+item_id+'" />\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <table style="width:100%" id="'+table_name+'" class="table table-striped table-bordered" cellspacing="0">\n\
                                <thead>\n\
                                    <tr>\n\
                                        <th style="width:140px;">Branche</th>\n\
                                        <th style="width:60px;">Qty</th>\n\
                                    </tr>\n\
                                </thead>\n\
                                <tbody></tbody>\n\
                            </table>\n\
                        </div>\n\
                    </div>\n\
                    <div class="row" style="margin-top:10px;">\n\
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" >\n\
                            &nbsp;\n\
                        </div>\n\
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >\n\
                            <input type="number" class="form-control form-control-sm" id="qty_nw" placeholder="Unit Quantity">\n\
                        </div>\n\
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">\n\
                            &nbsp;\n\
                        </div>\n\
                    </div>\n\
                    <div class="row" style="margin-top:5px;">\n\
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" >\n\
                            &nbsp;\n\
                        </div>\n\
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" >\n\
                            <button id="set_new_quantity_btn" onclick="set_new_quantity()" type="button" class="btn btn-sm btn-primary" style="width:100%">SET</button>\n\
                        </div>\n\
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">\n\
                            &nbsp;\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>\n\
    </div>';
    $("#"+modal_name).remove();
    $("body").append(content);
    $('#'+modal_name).on('show.bs.modal', function (e) {

    });
    
    $('#'+modal_name).on('shown.bs.modal', function (e) {
        
        $('#'+table_name).show();
        
        var _cards_table__var = $('#'+table_name).DataTable({
            ajax: {
                url: "?r=all_stores_data&f=get_all_items_qty_in_all_stores___&p0="+item_id,
                type: 'POST',
                error:function(xhr,status,error) {
                },
            },
            responsive: true,
            orderCellsTop: true,
            scrollX: false,
            scrollY: "55vh",
            iDisplayLength: 100,
            aoColumnDefs: [
                { "targets": [0], "searchable": true, "orderable": true, "visible": true },
                { "targets": [1], "searchable": true, "orderable": true, "visible": true },
            ],
            scrollCollapse: true,
            paging: false,
            bPaginate: false,
            bLengthChange: false,
            bFilter: false,
            bInfo: false,
            bAutoWidth: true,
            initComplete: function(settings, json) {                
                
            },
            
        });
        
       
               
    });
    $('#'+modal_name).on('hide.bs.modal', function (e) {
        $("#"+modal_name).remove();
    });
    $('#'+modal_name).modal('show');
}

function set_new_quantity(){
    var qty=$("#qty_nw").val();
    if(qty=="" || qty==null){
        $.alert({
            title: 'Alert!',
            content: 'Enter Quantity',
        });
        return;
    }
    
    $("#set_new_quantity_btn").prop("disabled", true);
    var _data=[];
    $.getJSON("?r=invoice&f=set_qty&p0="+$("#search_all_store_item_id").val()+"&p1="+current_id+"&p2="+$("#qty_nw").val(), function(data) {
        _data=data;
    }).done(function() {
        $('#modal_get_all_qty_if_item_modal__').modal('hide');
        $("#set_new_quantity_btn").prop("disabled", false);
        refresh_table_of_items(current_id);
    });
    
}