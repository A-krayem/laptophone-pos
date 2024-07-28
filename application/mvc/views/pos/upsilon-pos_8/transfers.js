function show_all_transfers(){
    
    if (!navigator.onLine) {
            //swal("Check your internet connection");
            //return;
        }
        
    var table_name = "modal_Transfers_";
    var modal_name = "modal_Transfers_modal_";
    var modal_title = "Transfers";
    
    var devices = [];
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    $.getJSON("?r=mobile_store&f=getDevicesIDs", function (data) {
        $.each(data, function (key, val) {
            devices.push(val);
        });
    }).done(function () {
        var content =
        '<div class="modal large" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="closeModal(\'modal_Transfers_modal_\')"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                <table style="width:100%" id="'+table_name+'" class="table table-striped table-bordered" cellspacing="0">\n\
                                    <thead>\n\
                                        <tr>\n\
                                            <th>pck_id</th>\n\
                                            <th>op_id</th>\n\
                                            <th>Package Description</th>\n\
                                            <th style="width:80px;">Days</th>\n\
                                            <th style="width:80px;">Credits</th>\n\
                                            <th style="width:80px;">Price</th>\n\
                                            <th>Note</th>\n\
                                        </tr>\n\
                                    </thead>\n\
                                    <tfoot>\n\
                                        <tr>\n\
                                            <th>pck_id</th>\n\
                                            <th>op_id</th>\n\
                                            <th>Package Description</th>\n\
                                            <th>Days</th>\n\
                                            <th>Credits</th>\n\
                                            <th>Price</th>\n\
                                            <th>Note</th>\n\
                                        </tr>\n\
                                    </tfoot>\n\
                                    <tbody></tbody>\n\
                                </table>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>';
    
        $('#'+modal_name).modal('hide');
        
        $("body").append(content);
        $('#'+modal_name).on('show.bs.modal', function (e) {

        });

        $('#'+modal_name).on('shown.bs.modal', function (e) {

            $('#'+table_name).show();

            var _cards_table__var =null;

            var search_fields = [0,1,2,3,4,5,6];
            var index = 0;
            $('#'+table_name+' tfoot th').each( function () {

                if(jQuery.inArray(index, search_fields) !== -1){
                    var title = $(this).text();
                    $(this).html('<input id="idif_'+index+'" style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder="'+title+'" />' );
                    index++;
                }
            });

            _cards_table__var = $('#'+table_name).DataTable({
                ajax: {
                    url: "?r=pos&f=get_packages&p0=0&p1=0&p2=0&p3=0",
                    type: 'POST',
                    error:function(xhr,status,error) {
                    },
                },
                responsive: true,
                orderCellsTop: true,
                scrollX: true,
                scrollY: "55vh",
                iDisplayLength: 100,
                aoColumnDefs: [
                    { "targets": [0], "searchable": true, "orderable": true, "visible": false },
                    { "targets": [1], "searchable": true, "orderable": true, "visible": false },
                    { "targets": [2], "searchable": true, "orderable": true, "visible": true },
                    { "targets": [3], "searchable": true, "orderable": true, "visible": true },
                    { "targets": [4], "searchable": true, "orderable": true, "visible": true },
                    { "targets": [5], "searchable": true, "orderable": true, "visible": true },
                    { "targets": [6], "searchable": true, "orderable": true, "visible": true },
                ],
                scrollCollapse: true,
                paging: true,
                bPaginate: false,
                bLengthChange: false,
                bFilter: true,
                bInfo: false,
                bAutoWidth: true,
                aaSorting: [[ 1, "asc" ]],
                initComplete: function(settings, json) {                
                    //var row = $('#'+table_name+' tr:first-child');
                    //$(row).addClass('selected');
                    //$('.dataTables_scrollBody thead tr').css({visibility:'collapse'});
                    //$('#idif_1').focus();
                    $(".sk-circle-layer").hide();
                },
                fnDrawCallback: function(){
                    //setTimeout(function(){
                        //$("#idf_1").focus();
                    //},5000);
                    //$("#idf_1").focus();
                    $("#idif_2").remove();
                    $("#idif_2").focus();
                },
            });

            
            $('#'+table_name).DataTable().on('mousedown',"tbody tr", function ( e, dt, type, indexes ) { 
                $('.selected').removeClass("selected");
                $(this).addClass('selected');
             });

            $('#'+table_name).DataTable().on('dblclick',"tr", function ( e, dt, type, indexes ) {
                $('.selected').removeClass("selected");
                $(this).addClass('selected');
                var dt = $('#'+table_name).DataTable();
                var id = dt.row(this).data()[0];
                var operator = dt.row(this).data()[1];
                
                var nm =0;
                var dv =0;
                for(var k=0;k<devices.length;k++){
                    if(devices[k].operator_id==operator){
                        nm++;
                        dv = devices[k].id;
                    }
                }
                if(nm==1){
                    addTransferItemToInvoice(id,dv);
                }
             });


            $('#'+table_name).on('click', 'td', function () {
                //if ($(this).index() == 3) {
                    //return false;
                //}
            });
            
            $('#'+table_name).DataTable().on( 'key', function ( e, datatable, key, cell, originalEvent ) {
                if ( key === 13 ) { // return
                    alert("dasd");
                }
            } );

            $('#'+table_name).DataTable().columns().every( function () {
                var that = this;
                $( 'input', this.footer() ).on( 'keyup change', function () {
                    search_in_datatable(this.value,that.index(),100,table_name);
                } );
            } );

        });
        $('#'+modal_name).on('hide.bs.modal', function (e) {
            $("#"+modal_name).remove();
        });
        $('#'+modal_name).modal('show');
    }).fail(function() {
        swal("Check your internet connection");
    }).always(function() {
        $(".sk-circle-layer").hide();
        lockMainPos = false;
    });;
}

var tmo = null;
function search_in_datatable(val,index,delay,table_name){
    clearTimeout(tmo);
    tmo = setTimeout(function(){
        $('#'+table_name).DataTable().columns(index).search(val).draw();
    },delay); 
}


var $input_prepare_search_items = null;
function prepare_search_items(){
    
    $("#input_prepare_items").select2({
            ajax: {
                url: '?r=items&f=search',
                data: function (params) {
                    var query = {
                        p0: params.term || "",
                        p1: params.page || 1
                    }

                    // Query parameters will be ?search=[term]&type=public
                    return query;
                },
                delay: 250,
                dataType: 'json',
            },
            placeholder: "Search by Id, barcode, SKU and description",
            dropdownParent: $(`body`),
            allowClear: true,
            closeOnSelect: false
        });

        $("#input_prepare_items").on("change", () => {
            add_to_invoive($("#input_prepare_items").val(),0);
            
        });
        
    /*
    $.getJSON("?r=items&f=get_items_names_with_boxes", function (data) {

        var sourceArr = [];
        for (var i = 0; i < data.length; i++) {
           sourceArr.push({id:data[i].id,name:data[i].name});
        }
        $input_prepare_search_items = $("#input_prepare_items");
        $input_prepare_search_items.typeahead({
            source: sourceArr,
            items: 100,
        });
        $input_prepare_search_items.change(function() {
            var current = $input_prepare_search_items.typeahead("getActive");
            if (current) {
                if (current.name == $input_prepare_search_items.val()) {
                    add_to_invoive(current.id,0);
                    $("#input_prepare_items").val("");
                    setTimeout(function(){
                        $('#input_prepare_items').blur();
                    },100);
                } else {
                    
                }
            } else {
            }
        });
        
        
    }).done(function () {

    }).fail(function() {

    }).always(function() {
        
    });*/
}



function edit_cash(payment_type,transaction_id){
    var modal_name = "edit_pay_modal_";
    var modal_title = "Edit Payment";
    
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var _data=[];
    $.getJSON("?r=cashinout&f=get_cashinout_by_id&p0="+payment_type+"&p1="+transaction_id, function (data) {
        _data=data;
    }).done(function () {
        var content =
        '<div class="modal small" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <form id="edit_payment_form" action="" method="post" enctype="multipart/form-data" >\n\
                    <span style="display:none" id="difference_inv" ></span>\n\
                    <input type="hidden" name="payment_type" id="transaction_id" value='+payment_type+' />\n\
                    <input type="hidden" name="transaction_id" id="transaction_id" value='+transaction_id+' />\n\
                    <div class="modal-content">\n\
                        <div class="modal-header"> \n\
                            <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="closeModal(\'edit_pay_modal_\')"></i></h3>\n\
                        </div>\n\
                        <div class="modal-body" >\n\
                            <div class="row">\n\
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">\n\
                                    <div class="form-group" style="margin-bottom:3px;">\n\
                                        <label for="cash_usd">AMOUNT </label>\n\
                                        <input autocomplete="off" id="edit_base_amount" type="text" class="form-control med_input" readonly placeholder="">\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">\n\
                                    <div class="form-group" style="margin-bottom:3px;">\n\
                                        <label for="cash_usd" >RATE </label>\n\
                                        <input autocomplete="off" id="edit_base_rate" name="edit_base_rate" type="text" class="form-control med_input" readonly placeholder="">\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="row" style="margin-top:20px;">\n\
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">\n\
                                    <div class="form-group" style="margin-bottom:3px;">\n\
                                        <label for="cash_usd" style="font-size:14px !important;">IN USD </label><span id="to_return_c_usd" style="float:right;font-size:14px !important;">&nbsp;&nbsp;&nbsp;</span>\n\
                                        <input onkeyup="cash_changed_usd(this)" autocomplete="off" id="cash_usd" name="cash_usd" type="text" class="form-control med_input" placeholder="">\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">\n\
                                    <div class="form-group" style="margin-bottom:3px;">\n\
                                        <label for="cash_usd" style="font-size:14px !important;">OUT USD</label>\n\
                                        <input style="display:none" readonly onkeyup="" autocomplete="off" id="r_cash_usd" name="r_cash_usd" type="text" class="form-control med_input" placeholder="">\n\
                                        <input onkeyup="r_cash_usd_action_changed(this)" autocomplete="off" id="r_cash_usd_action" name="r_cash_usd_action" type="text" class="form-control med_input" placeholder="">\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">\n\
                                    <div class="form-group" style="margin-bottom:3px;">\n\
                                        <label for="cash_usd" style="font-size:14px !important;">IN LBP </label><span id="to_return_c_lbp" style="float:right;font-size:14px !important;">&nbsp;&nbsp;&nbsp;</span>\n\
                                        <input onkeyup="cash_changed_lbp(this)"  autocomplete="off" id="cash_lbp" name="cash_lbp" type="text" class="form-control med_input" placeholder="">\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">\n\
                                    <div class="form-group" style="margin-bottom:3px;">\n\
                                        <label for="cash_usd" style="font-size:14px !important;">OUT LBP</label>\n\
                                        <input style="display:none" readonly onkeyup="" autocomplete="off" id="r_cash_lbp" name="r_cash_lbp" type="text" class="form-control med_input" placeholder="">\n\
                                        <input onkeyup="r_cash_lbp_action_changed(this)" autocomplete="off" id="r_cash_lbp_action" name="r_cash_lbp_action" type="text" class="form-control med_input" placeholder="">\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="modal-footer">\n\
                            <button id="payment_edit_submit" type="submit" class="btn btn-primary" style="width:100%">UPDATE</button>\n\
                        </div>\n\
                    </div>\n\
                </form>\n\
            </div>\n\
        </div>';
       
        $('#'+modal_name).modal('hide');
        $("body").append(content);
        
        submitPayment_edit();
        
        $('#'+modal_name).on('show.bs.modal', function (e) {

        });

        $('#'+modal_name).on('shown.bs.modal', function (e) {
            $(".sk-circle-layer").hide();
            
            if(payment_type==1){
              $("#difference_inv").html(-_data[0].base_usd_amount);  
            }
            if(payment_type==2){
                if(_data[0].base_usd_amount<0){
                    $("#difference_inv").html(-_data[0].base_usd_amount);  
                }else{
                    $("#difference_inv").html(_data[0].base_usd_amount*(-1));  
                }
              
            }
            
            
            $("#edit_base_amount").val(Math.abs(parseFloat(_data[0].base_usd_amount).toFixed(2)));
            $("#edit_base_rate").val(parseFloat(_data[0].rate));
            
            $("#cash_usd").val(parseFloat(_data[0].cash_usd));
            $("#cash_lbp").val(parseFloat(_data[0].cash_lbp));
            cleaves_id("cash_usd",0);
            cleaves_id("cash_lbp",0);
            
            $("#r_cash_usd_action").val(parseFloat(_data[0].returned_cash_usd));
            $("#r_cash_lbp_action").val(parseFloat(_data[0].returned_cash_lbp));
            cleaves_id("r_cash_usd_action",0);
            cleaves_id("r_cash_lbp_action",0);
            
            
            $("#r_cash_usd").val(parseFloat(_data[0].must_return_cash_usd));
            $("#r_cash_lbp").val(parseFloat(_data[0].must_return_cash_lbp));
            cleaves_id("r_cash_usd_action",0);
            cleaves_id("r_cash_lbp_action",0);
            
            
            set_current_cash_var(2);
            cleaves_id("edit_base_amount",5);
            cleaves_id("edit_base_rate",0);
            
            r_cash_lbp_action_changed($("#r_cash_usd"));
            
            
            if(pos_disable_edit_payment==1){
                $("#edit_payment_form input").prop("disabled",true);
                $("#payment_edit_submit").prop("disabled",true);
                
            }
            
            
            
        });
        $('#'+modal_name).on('hide.bs.modal', function (e) {
            $("#"+modal_name).remove();
        });
        $('#'+modal_name).modal('show');
    });
}


function submitPayment_edit() {
    $("#edit_payment_form").on('submit', (function (e) {
        e.preventDefault();
        $("#payment_edit_submit").prop("disabled",true);
        $.ajax({
            url: "?r=cashinout&f=update_cashinout",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            success: function (data)
            {
                
                $(".sk-circle-layer").show();
                $('#drep_table__').DataTable().ajax.url("?r=cashinout&f=get_full_report_table&p0=0&p1=0").load(function () {
                    $(".sk-circle-layer").hide();
                }, false);
    
                $('#edit_pay_modal_').modal('hide');
            }
        });
    }));
}

function print_full_report(cashbox_id){
    if(usd_but_show_lbp_priority==1){
        if (set_password_for_cashbox_and_report_pos != -1) {
            swal({
                    title: "Enter Password",
                    html: true,
                    text: '<input class="form-control" value="" type="password" id="pass"/>',
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Ok",
                    cancelButtonText: "Cancel",
                    closeOnConfirm: true,
                    closeOnCancel: true
                },
                function(isConfirm) {
                    if (isConfirm) {
                        if ($("#pass").val() == set_password_for_cashbox_and_report_pos) {
                            get_full_report(cashbox_id);
                            return;
                        } else {
                            alert("Wrong Password");
                            return;
                        }
                    }
                });
            setTimeout(function() { $("#pass").focus(); }, 200);
            return;
        } else {
            get_full_report(cashbox_id);
            return;
        }
        
        
    }
    
    
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    
    var hd_omt=";display:block;";
    if(OMT_VERSION==1){
        hd_omt=";display:none;";
    }
    
    var _data = [];
    $.getJSON("?r=print_invoice&f=print_preview_full_report_custom_with_vat&p0="+cashbox_id, function (data) {
        _data = data;
    }).done(function () { 
        
        var starting_lbp_val='';
        if(true){
            starting_lbp_val='<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">\n\
                <table style="width:100%;border:1px solid #CCC;">\n\
                    <tr>\n\
                        <td class="quick_report_title quick_report_value_cash_in"><i class="glyphicon glyphicon glyphicon-arrow-down"></i>&nbsp;&nbsp;<b>Starting Value LBP</b></td>\n\
                    </tr>\n\
                    <tr>\n\
                        <td class="quick_report_value">'+_data.starting_cashbox_lbp+'</td>\n\
                    </tr>\n\
                </table>\n\
            </div>';
        }
        
        var content =
        '<div class="modal large" data-backdrop="static"  id="quickreport_modal" role="dialog" >\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title">Quick report<i style="float:right;font-size:35px" class="glyphicon glyphicon-remove" onclick="closeModal(\'quickreport_modal\')"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">\n\
                                <table style="width:100%;border:1px solid #CCC;">\n\
                                    <tr>\n\
                                        <td class="quick_report_title"><b>Starting Date</b></td>\n\
                                    </tr>\n\
                                    <tr>\n\
                                        <td class="quick_report_value">'+_data.starting_cashbox_date+'</td>\n\
                                    </tr>\n\
                                </table>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">\n\
                                <table style="width:100%;border:1px solid #CCC;'+hd_omt+'">\n\
                                    <tr>\n\
                                        <td class="quick_report_title quick_report_value_cash_in"><i class="glyphicon glyphicon glyphicon-arrow-down"></i>&nbsp;&nbsp;<b>Starting Value</b></td>\n\
                                    </tr>\n\
                                    <tr>\n\
                                        <td class="quick_report_value">'+_data.starting_cashbox+'</td>\n\
                                    </tr>\n\
                                </table>\n\
                            </div>\n\
                            '+starting_lbp_val+'\n\
                        </div>\n\
                        <div class="row" style="margin-top:20px;">\n\
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">\n\
                                <table style="width:100%;border:1px solid #CCC;">\n\
                                    <tr>\n\
                                        <td class="quick_report_title quick_report_value_cash_in"><i class="glyphicon glyphicon glyphicon-arrow-down"></i>&nbsp;&nbsp;<b>Cash Sales</b></td>\n\
                                    </tr>\n\
                                    <tr>\n\
                                        <td class="quick_report_value">'+_data.total_cash_sale+'</td>\n\
                                    </tr>\n\
                                </table>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="'+hd_omt+'">\n\
                                <table style="width:100%;border:1px solid #CCC;">\n\
                                    <tr>\n\
                                        <td class="quick_report_title quick_report_value_cash_in"><i class="glyphicon glyphicon glyphicon-arrow-down"></i>&nbsp;&nbsp;<b>Credit Card(s) Sales</b></td>\n\
                                    </tr>\n\
                                    <tr>\n\
                                        <td class="quick_report_value">'+_data.sales_creditcard+'</td>\n\
                                    </tr>\n\
                                </table>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="'+hd_omt+'">\n\
                                <table style="width:100%;border:1px solid #CCC;">\n\
                                    <tr>\n\
                                        <td class="quick_report_title quick_report_value_cash_in"><i class="glyphicon glyphicon glyphicon-arrow-down"></i>&nbsp;&nbsp;<b>Cheque(s) Sales</b></td>\n\
                                    </tr>\n\
                                    <tr>\n\
                                        <td class="quick_report_value">'+_data.sales_cheques_line+'</td>\n\
                                    </tr>\n\
                                </table>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="">\n\
                                <table style="width:100%;border:1px solid #CCC;">\n\
                                    <tr>\n\
                                        <td class="quick_report_title"><b>Debts Sales</b></td>\n\
                                    </tr>\n\
                                    <tr>\n\
                                        <td class="quick_report_value">'+_data.sales_notpaid_line+'</td>\n\
                                    </tr>\n\
                                </table>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row" style="margin-top:20px;'+hd_omt+'">\n\
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">\n\
                                <table style="width:100%;border:1px solid #CCC;">\n\
                                    <tr>\n\
                                        <td class="quick_report_title quick_report_value_cash_in"><i class="glyphicon glyphicon glyphicon-arrow-down"></i>&nbsp;&nbsp;<b>Debts payments (Cash)</b></td>\n\
                                    </tr>\n\
                                    <tr>\n\
                                        <td class="quick_report_value">'+_data.customers_payment_debts_cash+'</td>\n\
                                    </tr>\n\
                                </table>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">\n\
                                <table style="width:100%;border:1px solid #CCC;">\n\
                                    <tr>\n\
                                        <td class="quick_report_title quick_report_value_cash_in"><i class="glyphicon glyphicon glyphicon-arrow-down"></i>&nbsp;&nbsp;<b>Debts payments (Cheques)</b></td>\n\
                                    </tr>\n\
                                    <tr>\n\
                                        <td class="quick_report_value">'+_data.customers_payment_debts_cheque+'</td>\n\
                                    </tr>\n\
                                </table>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">\n\
                                <table style="width:100%;border:1px solid #CCC;">\n\
                                    <tr>\n\
                                        <td class="quick_report_title quick_report_value_cash_in"><i class="glyphicon glyphicon glyphicon-arrow-down"></i>&nbsp;&nbsp;<b>Debts payments (CC)</b></td>\n\
                                    </tr>\n\
                                    <tr>\n\
                                        <td class="quick_report_value">'+_data.customers_payment_debts_cc+'</td>\n\
                                    </tr>\n\
                                </table>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row" style="margin-top:20px;'+hd_omt+'">\n\
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">\n\
                                <table style="width:100%;border:1px solid #CCC;">\n\
                                    <tr>\n\
                                        <td class="quick_report_title quick_report_value_cash_out"><i class="glyphicon glyphicon glyphicon-arrow-up"></i>&nbsp;&nbsp;<b>Suppliers Payments (Cash)</b></td>\n\
                                    </tr>\n\
                                    <tr>\n\
                                        <td class="quick_report_value">'+_data.total_supplier_payment_cash+'</td>\n\
                                    </tr>\n\
                                </table>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">\n\
                                <table style="width:100%;border:1px solid #CCC;">\n\
                                    <tr>\n\
                                        <td class="quick_report_title quick_report_value_cash_out"><i class="glyphicon glyphicon glyphicon-arrow-up"></i>&nbsp;&nbsp;<b>Suppliers Payments (Cheques)</b></td>\n\
                                    </tr>\n\
                                    <tr>\n\
                                        <td class="quick_report_value">'+_data.total_supplier_payment_cheque+'</td>\n\
                                    </tr>\n\
                                </table>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">\n\
                                <table style="width:100%;border:1px solid #CCC;">\n\
                                    <tr>\n\
                                        <td class="quick_report_title quick_report_value_cash_out"><i class="glyphicon glyphicon glyphicon-arrow-up"></i>&nbsp;&nbsp;<b>Suppliers Payments (CC)</b></td>\n\
                                    </tr>\n\
                                    <tr>\n\
                                        <td class="quick_report_value">'+_data.total_supplier_payment_cc+'</td>\n\
                                    </tr>\n\
                                </table>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row" style="margin-top:20px;">\n\
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">\n\
                                <table style="width:100%;border:1px solid #CCC;">\n\
                                    <tr>\n\
                                        <td class="quick_report_title quick_report_value_cash_in"><i class="glyphicon glyphicon glyphicon-arrow-up"></i><i class="glyphicon glyphicon glyphicon-arrow-down"></i>&nbsp;&nbsp;<b>Total Returns and Changes</b></td>\n\
                                    </tr>\n\
                                    <tr>\n\
                                        <td class="quick_report_value">'+_data.total_return+'</td>\n\
                                    </tr>\n\
                                </table>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="'+hd_omt+'">\n\
                                <table style="width:100%;border:1px solid #CCC;">\n\
                                    <tr>\n\
                                        <td class="quick_report_title quick_report_value_cash_in"><i class="glyphicon glyphicon glyphicon-arrow-up"></i><i class="glyphicon glyphicon glyphicon-arrow-down"></i>&nbsp;&nbsp;<b>Total Changes - Another Branche</b></td>\n\
                                    </tr>\n\
                                    <tr>\n\
                                        <td class="quick_report_value">'+_data.total_return_another_branche+'</td>\n\
                                    </tr>\n\
                                </table>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="'+hd_omt+'">\n\
                                <table style="width:100%;border:1px solid #CCC;">\n\
                                    <tr>\n\
                                        <td class="quick_report_title quick_report_value_cash_out"><i class="glyphicon glyphicon glyphicon-arrow-up"></i>&nbsp;&nbsp;<b>Total Expenses</b></td>\n\
                                    </tr>\n\
                                    <tr>\n\
                                        <td class="quick_report_value">'+_data.total_expenses+'</td>\n\
                                    </tr>\n\
                                </table>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">\n\
                                <table style="width:100%;border:1px solid #CCC;">\n\
                                    <tr>\n\
                                        <td class="quick_report_title"><b>Total Invoices Discounts</b></td>\n\
                                    </tr>\n\
                                    <tr>\n\
                                        <td class="quick_report_value">'+_data.total_invoice_discount+'</td>\n\
                                    </tr>\n\
                                </table>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-footer">\n\
                        <div class="row" style="margin-top:20px;">\n\
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pull-right">\n\
                                <button onclick="print_report__('+cashbox_id+')" class="confirm btn btn-sm btn-default" tabindex="1" style="display: inline-block; width:100%">Print Report</button>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>';

        $('#quickreport_modal').modal('hide');
        
        $("body").append(content);
        $('#quickreport_modal').on('show.bs.modal', function (e) {
            
            //print_report__(cashbox_id)
        });

        $('#quickreport_modal').on('shown.bs.modal', function (e) {
            
            $(".sk-circle-layer").hide();
        });

        $('#quickreport_modal').on('hide.bs.modal', function (e) {
            $("#quickreport_modal").remove();
        });
        $('#quickreport_modal').modal('show');
    }).fail(function() {
        
    }); 
    
}

function print_report__manual(cashbox_id){
    var width = 500;
    var height = 600;
    var left = (screen.width - width) / 2;
    var top = (screen.height - height) / 2;
    window.open("?r=print_invoice&f=manual_print_report&p0="+cashbox_id, '_blank', 'width=' + width + ', height=' + height + ', left=' + left + ', top=' + top);
               
    /*$(".sk-circle").center();
    $(".sk-circle-layer").show();
    $.getJSON("?r=print_invoice&f=print_full_report_custom_with_vat_current&p0="+cashbox_id, function (data) {

    }).done(function () {
        $(".sk-circle-layer").hide();
    }).fail(function() {
        $(".sk-circle-layer").hide();
    });*/
}


function print_report__(cashbox_id){
    if(usd_but_show_lbp_priority==1){
        print_report__manual(cashbox_id);
    }else{
        $(".sk-circle").center();
        $(".sk-circle-layer").show();
        $.getJSON("?r=print_invoice&f=print_full_report_custom_with_vat_current&p0="+cashbox_id, function (data) {

        }).done(function () {
            $(".sk-circle-layer").hide();
        }).fail(function() {
            $(".sk-circle-layer").hide();
        });
    }
    
}

function received_branch_transfer_print(id,store_id){
    var width = 500;
      var height = 600;
     var left = (screen.width - width) / 2;
    var top = (screen.height - height) / 2;
    window.open("?r=transfer&f=print_pos_transfer&p0=" + id+"&p1="+store_id, '_blank', 'width=' + width + ', height=' + height + ', left=' + left + ', top=' + top);
}
