function show_all_transfers(){
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
        $("#"+modal_name).remove();
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

            
            $('#'+table_name).DataTable().on('mousedown',"tr", function ( e, dt, type, indexes ) { 
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
    });
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
        
    });
}

function print_full_report(cashbox_id){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var _data = [];
    $.getJSON("?r=print_invoice&f=print_preview_full_report_custom_with_vat&p0="+cashbox_id, function (data) {
        _data = data;
    }).done(function () { 
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
                                <table style="width:100%;border:1px solid #CCC;">\n\
                                    <tr>\n\
                                        <td class="quick_report_title quick_report_value_cash_in"><i class="glyphicon glyphicon glyphicon-arrow-down"></i>&nbsp;&nbsp;<b>Starting Value</b></td>\n\
                                    </tr>\n\
                                    <tr>\n\
                                        <td class="quick_report_value">'+_data.starting_cashbox+'</td>\n\
                                    </tr>\n\
                                </table>\n\
                            </div>\n\
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
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">\n\
                                <table style="width:100%;border:1px solid #CCC;">\n\
                                    <tr>\n\
                                        <td class="quick_report_title quick_report_value_cash_in"><i class="glyphicon glyphicon glyphicon-arrow-down"></i>&nbsp;&nbsp;<b>Credit Card(s) Sales</b></td>\n\
                                    </tr>\n\
                                    <tr>\n\
                                        <td class="quick_report_value">'+_data.sales_creditcard+'</td>\n\
                                    </tr>\n\
                                </table>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">\n\
                                <table style="width:100%;border:1px solid #CCC;">\n\
                                    <tr>\n\
                                        <td class="quick_report_title quick_report_value_cash_in"><i class="glyphicon glyphicon glyphicon-arrow-down"></i>&nbsp;&nbsp;<b>Cheque(s) Sales</b></td>\n\
                                    </tr>\n\
                                    <tr>\n\
                                        <td class="quick_report_value">'+_data.sales_cheques_line+'</td>\n\
                                    </tr>\n\
                                </table>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">\n\
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
                        <div class="row" style="margin-top:20px;">\n\
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
                        <div class="row" style="margin-top:20px;">\n\
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
                                        <td class="quick_report_title quick_report_value_cash_out"><i class="glyphicon glyphicon glyphicon-arrow-up"></i>&nbsp;&nbsp;<b>Total Changes</b></td>\n\
                                    </tr>\n\
                                    <tr>\n\
                                        <td class="quick_report_value">'+_data.total_return+'</td>\n\
                                    </tr>\n\
                                </table>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">\n\
                                <table style="width:100%;border:1px solid #CCC;">\n\
                                    <tr>\n\
                                        <td class="quick_report_title quick_report_value_cash_in"><i class="glyphicon glyphicon glyphicon-arrow-down"></i><i class="glyphicon glyphicon glyphicon-arrow-in"></i>&nbsp;&nbsp;<b>Total Return - Another Branche</b></td>\n\
                                    </tr>\n\
                                    <tr>\n\
                                        <td class="quick_report_value">'+_data.total_return_another_branche+'</td>\n\
                                    </tr>\n\
                                </table>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">\n\
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
        $("#quickreport_modal").remove();
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


function print_report__(cashbox_id){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    $.getJSON("?r=print_invoice&f=print_full_report_custom_with_vat_current&p0="+cashbox_id, function (data) {

    }).done(function () {
        $(".sk-circle-layer").hide();
    }).fail(function() {
        $(".sk-circle-layer").hide();
    });
}
