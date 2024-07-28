
var INVOICE_EDIT_MODE=0;
var IS_OFFICIAl=0;
var IS_OFFICIAL_TAX_VALUE=0;

function manual_invoice_generate_invoice_multi_branches(){
    var multibranches = JSON.parse(accessbranches);
    
    var branches_option="";
    for(var i=0;i<multibranches.details.length;i++){
        branches_option+="<option value='"+multibranches.details[i].id+"'>"+multibranches.details[i].branch_name+"</option>";
    }
    
    var content =
        '<div class="modal small" data-backdrop="static"  id="crt_inv" role="dialog" style="z-index:99999999" >\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title">Create Invoice<i style="float:right;font-size:35px" class="glyphicon glyphicon-remove" onclick="closeModal(\'crt_inv\')"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">\n\
                                <div class="btn-group" role="group" aria-label="">\n\
                                    <label for="br_list">Select Branch</label>\n\
                                    <select id="br_list" class="selectpicker" onchange="">\n\
                                        '+branches_option+'\n\
                                    </select>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-footer">\n\
                        <button style="width:150px;" type="button" class="btn btn-info" onclick="manual_invoice_generate_invoice_multi_branches_action(this)">Create</button>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>';
    $("#crt_inv").modal("hide");
    $("body").append(content);
    $('#crt_inv').on('show.bs.modal', function(e) {

    });

    $('#crt_inv').on('shown.bs.modal', function(e) {
        $("#br_list").selectpicker();
    });

    $('#crt_inv').on('hide.bs.modal', function(e) {
        $("#crt_inv").remove();
    });
    
    $('#crt_inv').modal('show');
}

function manual_invoice_generate_invoice_multi_branches_action(object){
    $(object).prop("disabled",true);
    
    var invoice_id = 0;
    $.getJSON("?r=invoice&f=generate_empty_invoice_for_branch&p0="+$("#br_list").val(), function (data) {
        invoice_id=data;
    }).done(function () {
        $("#crt_inv").modal("hide");
        INVOICE_EDIT_MODE=0;
        create_invoice_quotation(invoice_id,[]);
    });
}

function manual_invoice_generate_invoice(){
    $.confirm({
        title: 'Create new invoice',
        content: 'Are you sure?',
        animation: 'zoom',
        closeAnimation: 'zoom',
        animateFromElement:false,
        buttons: {
            CREATE: {
                btnClass: 'btn-primary',
                action: function(){
                    var invoice_id = 0;
                    $.getJSON("?r=invoice&f=generate_empty_invoice", function (data) {
                        invoice_id=data;
                    }).done(function () {
                        INVOICE_EDIT_MODE=0;
                        create_invoice_quotation(invoice_id,[]);
                    });
                }
            },
            CANCEL: {
                btnClass: 'btn-default any-other-class', // multiple classes.
                action: function(){
                    
                }
            },
        }
    });
    
    
}

function edit_manual_invoice(invoice_id){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var _data = [];
    $.getJSON("?r=invoice&f=getInvoiceItemsDetails&p0="+invoice_id, function (data) {
        _data=data;
    }).done(function () {
        INVOICE_EDIT_MODE=1;
        
  
        
        create_invoice_quotation(invoice_id,_data);
    });
}

function salesman_changed(){
     $('#print_btn').hide();
}

function recurring_changed(){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    $.getJSON("?r=invoice&f=recurring_update&p0="+$("#invoice_id__").val()+"&p1="+$("#recurring_id").val(), function (data) {
        
    }).done(function () {
        $(".sk-circle-layer").hide();
    });
}

function create_invoice_quotation(invoice_id,_data){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var sourceCustomers = [];
    var sourceItems = [];
    
    var is_taxable=0;
    
    
    var salesman_options = "";
    salesman_options+='<option value="0" title="Select">Select</option>'; 
    
    var name_tmp = "";
    $.getJSON("?r=invoice&f=get_needed_data_for_manual_creation", function (data) {

        $.each(data.customers, function (key, val) {
            name_tmp = val.name+" "+val.last_name;
           
           
            if(val.phone!=null && val.phone.toString().lenght>0){
                name_tmp += val.phone;
            }
            sourceCustomers.push({phone:val.phone,first_name:val.name,middle_name:val.middle_name,last_name:val.last_name,only_name:val.name+" "+val.last_name,name:name_tmp,id:val.id,address:val.address,discount:val.discount});
        });
        $.each(data.salesman, function (key, val) {
            salesman_options+='<option value="'+val.id+'" title="'+val.first_name+' '+val.last_name+'">'+val.first_name+' '+val.last_name+'</option>';         
        });
        $.each(data.items, function (key, val) {
            sourceItems.push({id:val.id,name:val.name});        
        });
        
        is_taxable=data.is_taxable;
       
        
    }).done(function () {
        var modal_name = "modal_create_invoice_modal__";
        var modal_title = "New Invoice";
        if(invoice_id>0){
            modal_title = "Edit Invoice <span class='text-success' id='saving_status'></span>";
        }
        
        var content =
        '<div class="modal maxlarge" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <div class="modal-header" style="padding-top:5px;padding-bottom:5px;">\n\
                        <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\''+modal_name+'\')"></i></h3>\n\
                    </div>\n\
                    <input id="invoice_id__" value="'+invoice_id+'" type="hidden" />\n\
                    <div class="modal-body" style="padding-top:2px;">\n\
                        <div class="row">\n\
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pr2">\n\
                                <div class="form-group" style="margin-bottom:5px;">\n\
                                    <label for="code">Invoice date</label>&nbsp;&nbsp;<input type="checkbox" id="change_date_chk" style="width:16px;height:16px;" />\n\
                                    <input autocomplete="off" required onchange="" id="invoice_date" name="invoice_date" class="form-control med_input" style="width:100%" />\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 plr2">\n\
                                <div class="form-group" style="margin-bottom:5px;">\n\
                                    <label for="code">Customer</label>&nbsp;<i onclick="addCustomer(\'add\',[],0)" class="glyphicon glyphicon-plus" style="font-size:18px;cursor:pointer"></i>\n\
                                    <input autocomplete="off" onchange="" id="invoice_customer" name="invoice_customer" class="form-control med_input" />\n\
                                    <input id="invoice_customer_id" value="0" type="hidden" />\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 plr2">\n\
                                <div class="btn-group" role="group" aria-label="" style="width:100%">\n\
                                    <label for="salesman_invoice">Salesman</label>\n\
                                    <select id="salesman_invoice" data-live-search="true" data-width="100%" id="" class="selectpicker" onchange="salesman_changed()">\n\
                                        '+salesman_options+'\n\
                                    </select>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pl2">\n\
                                <div class="form-group" style="margin-bottom:5px;">\n\
                                    <label for="code">Invoice Note</label>\n\
                                    <input autocomplete="off" onchange="" id="invoice_note" name="invoice_note" class="form-control med_input" />\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-5 pl2">\n\
                                <div class="btn-group" role="group" aria-label="" style="width:100%">\n\
                                    <label for="recurring">Recurring</label>\n\
                                    <select id="recurring_id" data-live-search="true" data-width="100%" class="selectpicker" onchange="recurring_changed()">\n\
                                        <option value="0" title="No">No</option>\n\
                                        <option value="1" title="Monthly (after 1 month)">Monthly (after 1 month)</option>\n\
                                        <option value="2" title="Monthly (At the first of month)">Monthly (At the first of month)</option>\n\
                                    </select>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-5 pl2" id="taxable_container" style="display:none">\n\
                                <div class="btn-group" role="group" aria-label="" style="width:100%">\n\
                                    <label for="is_taxable">Taxable</label>\n\
                                    <select id="is_taxable" data-width="100%" class="selectpicker" onchange="taxable_changed()">\n\
                                        <option value="0" selected title="No">No</option>\n\
                                        <option value="1" title="Yes">Yes</option>\n\
                                    </select>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pr2">\n\
                                <div class="form-group" style="margin-bottom:5px;">\n\
                                    <label for="code">Search Item <b onclick="open_gallery(1)" class="text-primary" style="cursor:pointer">OPEN GALLERY</b></label>\n\
                                    <input autocomplete="off" placeholder="Search by description, Barcode" type="text" id="search_item" name="search_item" style="width: 100%; height: 36px; margin-top: 3px; font-size: 14px;" />\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 plr2">\n\
                                <div class="form-group" style="margin-bottom:5px;">\n\
                                    <label for="code">Total</label>\n\
                                    <input readonly autocomplete="off" id="invoice_total" name="invoice_total" class="form-control med_input cleavesf3" value="0" />\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 plr2">\n\
                                <div class="form-group" style="margin-bottom:5px;">\n\
                                    <label for="code">Invoice Discount</label>\n\
                                    <input oninput="calculate_total_amount()" autocomplete="off" id="invoice_discount" name="invoice_discount" class="form-control med_input cleavesf3" value="0" />\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 plr2">\n\
                                <div class="form-group" style="margin-bottom:5px;">\n\
                                    <label for="code">Total Amount</label>\n\
                                    <input readonly autocomplete="off" id="total_amount" name="total_amount" class="form-control med_input cleavesf3" value="0" />\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 plr2">\n\
                                <div class="form-group" style="margin-bottom:5px;">\n\
                                    <label for="code">Total - TAX incl.</label>\n\
                                    <input readonly autocomplete="off" id="total_amount_taxable" name="total_amount_taxable" class="form-control med_input cleavesf3" value="0" />\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 plr2">\n\
                                <div class="btn-group" role="group" aria-label="" style="width:100%">\n\
                                    <label for="paid">Status</label>\n\
                                    <select id="paid_invoice" data-live-search="true" data-width="100%" id="" class="selectpicker">\n\
                                        <option value="1" >Paid</option>\n\
                                        <option value="0" selected>Debt</option>\n\
                                    </select>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pl2" id="rt_container" style="display:none">\n\
                                <div class="form-group">\n\
                                    <label for="inv_rate" style="width:100%">Rate</label>\n\
                                    <div class="input-group">\n\
                                        <span class="input-group-addon" id="ex_b_r_l" style="width:40px;"><b>1 USD </b>= </span>\n\
                                            <input type="text" class="form-control cleavesf3" name="inv_rate" id="inv_rate" value="0" placeholder="" style="padding-left:5px;padding-right:5px;font-weight:bold;" />\n\
                                        <span class="input-group-addon" style="width:40px;"><b>LBP</b></span>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                <table style="width:100%" id="newinvoice_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                    <thead>\n\
                                        <tr>\n\
                                            <th style="width:80px;">Invoice Item ID</th>\n\
                                            <th style="width:80px;">Item ID</th>\n\
                                            <th style="width:80px;">Code</th>\n\
                                            <th style="width:120px;">Barcode</th>\n\
                                            <th >Description</th>\n\
                                            <th style="width: 150px !important;">Additional Description</th>\n\
                                            <th style="width:100px;">Price/U</th>\n\
                                            <th style="width:50px;text-align:center">Discount</th>\n\
                                            <th style="width:50px;">TAX</th>\n\
                                            <th style="width:100px;">Final Price/U</th>\n\
                                            <th style="width:60px;text-align:center">Qty</th>\n\
                                            <th style="width:100px;">Total Amount</th>\n\
                                            <th style="width:50px;">&nbsp;</th>\n\
                                        </tr>\n\
                                    </thead>\n\
                                    <tbody></tbody>\n\
                                </table>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">\n\
                                <div class="form-group" style="margin-top:5px;">\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">\n\
                                <div class="form-group" style="margin-top:5px;">\n\
                                    <button id="print_btn_ous" onclick="print_on_pos_printer('+invoice_id+')" type="button" class="btn btn-primary" style="width:100%">Print On POS Printer</button>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2">\n\
                                <div class="form-group" style="margin-top:5px;">\n\
                                    <button id="print_btn" onclick="print_sheet('+invoice_id+')" type="button" class="btn btn-primary" style="width:100%">Print</button>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2">\n\
                                <div class="form-group" style="margin-top:5px;">\n\
                                    <button id="save_manual_invoice_btn" onclick="save_manual_invoice('+invoice_id+')" type="button" class="btn btn-primary" style="width:100%">Save</button>\n\
                                </div>\n\
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
            
            if(is_taxable==1){
                $("#taxable_container").show();
            }
            
            /* invoice datepicker */
            $('#invoice_date').datepicker({autoclose:true,format: 'yyyy-mm-dd'});
            $('#invoice_date').datepicker( "setDate", new Date() );
            $('#invoice_date').datepicker().on('changeDate', function(ev) {
             //$('#print_btn').hide();
            }).on('hide show', function(event) {
                event.preventDefault();
                event.stopPropagation();
                $('#print_btn').hide();
            });
            
            /* customer */
            var $input = $("#invoice_customer");
            $input.typeahead({
                source: sourceCustomers,
                autoSelect: true,
            });
            $input.change(function() {
                var current = $input.typeahead("getActive");
                if (current) {
                    if (current.name == $input.val()) {
                        $("#invoice_customer_id").val(current.id);
                        $(".history_prices").show();
                         $('#print_btn').hide();
                    } else {
                        $("#invoice_customer_id").val(0);
                        $(".history_prices").hide();
                    }
                } else {
                    $("#invoice_customer_id").val(0);
                    $(".history_prices").hide();
                }
            });
            
            $("#salesman_invoice").selectpicker();
            $("#recurring_id").selectpicker();
            $("#is_taxable").selectpicker();
            
            
            
            $("#paid_invoice").selectpicker();
            
            
            $("#search_item").select2({
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
                dropdownParent: $(`#${modal_name}`), 
                allowClear: true,
                closeOnSelect: false
            }).on('select2:select', function (e) {
                $(this).select2('open');
                $('.select2-search__field').val("");
                $('.select2-search__field').focus();
              });;

            $("#search_item").on("change", () => {
                manual_invoice_add_item_to_invoice(invoice_id,$("#search_item").val());
                $("#search_item").val("");
            });
                
            var table_name = "newinvoice_table";
            var new_table__var =null;
            
            _cards_table__var = $('#'+table_name).DataTable({
                    ajax: {
                        url: "?r=invoice&f=get_all_item_in_invoice&p0="+invoice_id,
                        type: 'POST',
                        error:function(xhr,status,error) {
                        },
                    },
                    //order: [[1, 'asc']],
                    responsive: true,
                    orderCellsTop: true,
                    scrollX: true,
                    scrollY: "45vh",
                    iDisplayLength: 100,
                    aoColumnDefs: [
                        { "targets": [0], "searchable": false, "orderable": true,"visible": false },
                        { "targets": [1], "searchable": false, "orderable": true,"visible": true },
                        { "targets": [2], "searchable": true, "orderable": true, "visible": true },
                        { "targets": [3], "searchable": true, "orderable": true, "visible": true },
                        { "targets": [4], "searchable": true, "orderable": true, "visible": true },
                        { "targets": [5], "searchable": true, "orderable": true, "visible": false },
                        { "targets": [6], "searchable": true, "orderable": true, "visible": true },
                        { "targets": [7], "searchable": true, "orderable": true, "visible": true},//,"className": "dt-center"
                        { "targets": [8], "searchable": true, "orderable": true, "visible": false },
                        { "targets": [9], "searchable": true, "orderable": true, "visible": true },
                        { "targets": [10], "searchable": true, "orderable": true, "visible": true },
                    ],
                    scrollCollapse: true,
                    paging: false,
                    bPaginate: false,
                    bLengthChange: false,
                    bFilter: true,
                    bInfo: false,
                    bAutoWidth: true,
                    bSort:false,
                    dom: '<"toolbar_new_invoice">frtip',
                    initComplete: function(settings, json) { 
                        
                        if (typeof _data.invoice_items !== 'undefined') {
                            var invd = _data.invoice[0].creation_date.split(' ');
                            
                            $("#invoice_date").datepicker( "setDate", invd[0] );
                      
                            //$('#invoice_date').datepicker({autoclose:true,format: 'yyyy-mm-dd'});
                            //$('#invoice_date').datepicker( "setDate", invd[0] );
                            
                            
                            $('#recurring_id').selectpicker('val', _data.invoice[0].recurring);
                            
                            if(_data.customer.length>0){
                                $("#invoice_customer_id").val(_data.customer[0].id);
                                
                                var name = _data.customer[0].name;
                                if(_data.customer[0].middle_name!=null && _data.customer[0].middle_name.length>0){
                                    name+=" "+_data.customer[0].middle_name;
                                }
                                if(_data.customer[0].last_name!=null && _data.customer[0].last_name.length>0){
                                    name+=" "+_data.customer[0].last_name;                                
                                }
                                $("#invoice_customer").val(name);  
                            }
                                   
                            

                            $("#invoice_note").val(_data.invoice[0].payment_note);
                            
                            if(_data.invoice[0].official==1){
                                $('#is_taxable').selectpicker('val', 1);
                                $('#is_taxable').prop('disabled', true);
                                
                            }
                      

                            $("#invoice_total").val(parseFloat(_data.invoice[0].total_value));
                            $("#invoice_discount").val(Math.abs(_data.invoice[0].invoice_discount));

                            var tot=parseFloat(_data.invoice[0].total_value)+parseFloat(_data.invoice[0].invoice_discount);
                            $("#total_amount").val(tot);
                             
                             
                           
                            

                            if(_data.invoice[0].official==1){
                                 IS_OFFICIAl=1;
                                 IS_OFFICIAL_TAX_VALUE=_data.invoice[0].vat_value;
                                $("#total_amount_taxable").val(tot*_data.invoice[0].vat_value);
                            }else{
                                IS_OFFICIAl=0;
                                IS_OFFICIAL_TAX_VALUE=0;
                            }
                            
                            $('#paid_invoice').selectpicker('val', _data.invoice[0].closed);
                            
                            
                            if(_data.invoice[0].sales_person>0){
                                $('#salesman_invoice').selectpicker('val', _data.invoice[0].sales_person);
                            }

                           $("#inv_rate").val(parseFloat(_data.invoice[0].rate));
                        }
                        
                        cleaves_class(".cleavesf3",3);
                        cleaves_class(".cleavesf2",2);
                        
                        
                        
                        
                        $(".sk-circle-layer").hide();
                    },
                    fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                        $(nRow).addClass(aData[0]);
                    },
                    fnDrawCallback: updateRowsManualInvoice,
                });

                $('#'+table_name).DataTable().on('mousedown',"tr", function ( e, dt, type, indexes ) { 
                    $('#modal_create_invoice_modal__ .selected').removeClass("selected");
                    $(this).addClass('selected');
                });


                $('#'+table_name).on('click', 'td', function () {
                    if ($(this).index() == 4 || $(this).index() == 5) {
                        //return false;
                    }
                });
                
      
                //if(_data.currency_counnt==1){
                    //$("#rt_container").hide();
                //}
                
        });
        
        $('#'+modal_name).on('hide.bs.modal', function (e) {
            
            if($('#invoices_table').length>0){
                var table = $('#invoices_table').DataTable();
                table.ajax.url("?r=invoice&f=getAllInvoicesDateRange&p0="+current_store_id+"&p1="+current_date+"&p2="+current_invoice_filter+"&p3="+$("#filter_salesperson").val()+"&p4="+$("#filter_vendors").val()+"&p5="+$("#filter_taxable").val()).load(function () {
                    table.page('last').draw(false);
                    $('#invoices_table').closest('.dataTables_scrollBody').scrollTop($('#invoices_table').closest('.dataTables_scrollBody')[0].scrollHeight);

                }, false);
               
            }
             $("#"+modal_name).remove();
            
        });
        
        $('#'+modal_name).modal('show');
    });

}


function manual_invoice_add_item_to_invoice(invoice_id,item_id){
    $('#print_btn').hide();
    $.getJSON("?r=invoice&f=addItemsToInvoice_manual&p0="+invoice_id+"&p1="+item_id+"&p2="+$("#invoice_customer_id").val(), function (data) {
        
    }).done(function () {
        var table = $('#newinvoice_table').DataTable();
        table.ajax.url("?r=invoice&f=get_all_item_in_invoice&p0="+invoice_id).load(function () {
            calculate_total_amount();
            cleaves_class(".cleavesf3",3);
            cleaves_class(".cleavesf2",2);
            
            if($("#invoice_customer_id").val()>0){
                $(".history_prices").show();
            }
        }, false);
    });
}


function update_total(id,invoice_id){
    
    /*
     * update invoice items in db
     */
    $('#print_btn').hide();
    var _data = [];
    
    $("#save_manual_invoice_btn").hide();
    $("#saving_status").html("SAVING...");
    
    $.getJSON("?r=invoice&f=save_manual_invoice_items&p0="+id+"&p1="+$("#inv_it_price_"+id).val().replace(/[^0-9\.]/g, '')+"&p2="+$("#inv_it_dis_"+id).val().replace(/[^0-9\.]/g, '')+"&p3=0&p4="+$("#inv_it_qty_"+id).val()+"&p5="+invoice_id+"&p6="+$("#inv_rate").val().replace(/[^0-9\.]/g, ''), function (data) {
        _data = data;
    }).done(function (_data) {
        var price_per_unit = 0;
        if($("#inv_it_price_"+id).val().replace(/[^0-9\.]/g, '').length>0){
            price_per_unit = $("#inv_it_price_"+id).val().replace(/[^0-9\.]/g, '');
        }
        
        $("#inv_it_qty_"+id).val(parseFloat(_data[0].qty));


        var discount_per_unit = 0;
        if($("#inv_it_dis_"+id).val().replace(/[^0-9\.]/g, '').length>0){
            discount_per_unit = $("#inv_it_dis_"+id).val().replace(/[^0-9\.]/g, '');
        }
        
        var vat = 1;
        if($("#mivat_"+id).val()=="1"){
            vat = parseFloat(_data[0].vat_value);
            
        }
      
      
        var pertmp = (1-parseFloat(discount_per_unit)/100);
        var total_price=0;
        if(vat==0){
            total_price = price_per_unit*(Math.round(pertmp * 1000) / 1000)*$("#inv_it_qty_"+id).val();
            $("#fp_"+id).val(price_per_unit*(Math.round(pertmp * 1000) / 1000));
        }else{
            total_price = price_per_unit*(Math.round(pertmp * 1000) / 1000)*vat*$("#inv_it_qty_"+id).val();
            $("#fp_"+id).val(price_per_unit*(Math.round(pertmp * 1000) / 1000)*vat);
        }
       
        
        //$("#fp_"+id).val(price_per_unit*(Math.round(pertmp * 1000) / 1000)*vat);
        $("#inv_it_tp_"+id).val(total_price);

        
        cleaves_class(".cleavesf3",3);
        cleaves_class(".cleavesf2",2);

        calculate_total_amount();
        
    check_invoice_if_free_items();
    
    $("#save_manual_invoice_btn").show();
        $("#saving_status").html("");
        
    });
    
}

function calculate_total_amount(){
     $('#print_btn').hide();
    var total_amount = 0;
    $( ".total_per_item" ).each(function( i ) {
        total_amount+=(parseFloat($(this).val().replace(/[^0-9\.]/g, '')));
    });
    
    //alert(total_amount);
    
    var invoice_discount = 0;
    if($("#invoice_discount").val().replace(/[^0-9\.]/g, '').length>0){
        invoice_discount = $("#invoice_discount").val().replace(/[^0-9\.]/g, '');
    }
    
    $("#invoice_total").val(total_amount);
    
    
    $("#total_amount").val(total_amount-invoice_discount);
    if(IS_OFFICIAl==1){
        $("#total_amount_taxable").val((total_amount-invoice_discount)*IS_OFFICIAL_TAX_VALUE);
    }
    
    
   
                            
    cleaves_class(".cleavesf3",3);
}

function updateRowsManualInvoice(){
    var table = $('#newinvoice_table').DataTable();
    var p = table.rows({ page: 'current' }).nodes();
    for (var k = 0; k < p.length; k++){
        var index = table.row(p[k]).index();
        table.cell(index,12).data('<i title="Delete" class="glyphicon glyphicon-trash red" onclick="delete_item_from_manual_invoice(\''+parseInt(table.cell(index, 0).data())+'\')" style="font-size:16px;cursor:pointer" ></i>&nbsp;&nbsp;<i title="Latest price" class="icon-history history_prices" onclick="latest_prices_for_customer('+parseInt(table.cell(index, 1).data().split('-')[1])+',\'admin\','+parseInt(table.cell(index, 0).data())+')" style="font-size:19px;cursor:pointer;display:none"></i>');
    }
    check_invoice_if_free_items();
}

function update_ad_item_description(invoice_item_id){
    $.getJSON("?r=invoice&f=update_add_item_description&p0="+invoice_item_id+"&p1="+$("#addesc_"+invoice_item_id).val(), function (data) {

    }).done(function () {
        
    });
}

function taxable_changed(){
    $.confirm({
        title: 'Taxable Invoice!',
        content: 'Are you sure?',
        buttons: {
            YES: {
                btnClass: 'btn-success',
                action: function(){
                    $(".sk-circle").center();
                    $(".sk-circle-layer").show();
                    var _data=[];
                    $.getJSON("?r=invoice&f=set_as_taxable_invoice&p0="+$("#invoice_id__").val(), function (data) {
                        _data=data;
                    }).done(function () {
                        $(".minv_s").prop("disabled",true);
                        $('.minv_s').val(0);
                        $('#is_taxable').prop('disabled', 1);
                        $(".sk-circle-layer").hide();
                        
                        IS_OFFICIAl=1;
                        IS_OFFICIAL_TAX_VALUE=_data;
                        
                        
                        var tot=parseFloat($("#total_amount").val().replace(/[^0-9\.]/g, ''));
                        if(tot>0){
                            $("#total_amount_taxable").val(tot*IS_OFFICIAL_TAX_VALUE);     
                        }
                        
        
                    });
                }
            },
            CANCEL: {
                btnClass: 'btn-red any-other-class', // multiple classes.
                action: function(){
                    $('#is_taxable').selectpicker('val', 0);
                }
            },
        }
    });
    
    
}

function delete_item_from_manual_invoice(id){
    $.confirm({
        title: 'Delete Item!',
        content: 'Are you sure?',
        buttons: {
            CANCEL: {
                btnClass: 'btn-blue',
                action: function(){
                    
                }
            },
            DELETE: {
                btnClass: 'btn-red any-other-class', // multiple classes.
                action: function(){
                    $('#print_btn').hide();
                    var dlt_toast=generate_toast("Deleting Item","Please wait","info",false,"d43f3a","top-center");
                    $.getJSON("?r=invoice&f=delete_item_from_manual_invoice&p0="+id, function (data) {

                    }).done(function () {


                        if($("#invoices_table").length>0){
                            /*var table = $('#invoices_table').DataTable();
                            table.ajax.url("?r=invoice&f=getAllInvoicesDateRange&p0="+current_store_id+"&p1="+current_date+"&p2="+current_invoice_filter+"&p3="+$("#filter_salesperson").val()+"&p4="+$("#filter_vendors").val()+"&p5="+$("#filter_taxable").val()).load(function () {
                                table.page('last').draw(false);
                                $('#invoices_table').closest('.dataTables_scrollBody').scrollTop($('#invoices_table').closest('.dataTables_scrollBody')[0].scrollHeight);
                               
                            }, false);*/
                        }
                        
                        if($('#newinvoice_table').length>0){
                            var table = $('#newinvoice_table').DataTable();
                            table.ajax.url("?r=invoice&f=get_all_item_in_invoice&p0="+$("#invoice_id__").val()).load(function () {
                                setTimeout(function(){calculate_total_amount();},500);
                                 dlt_toast.reset();
                                cleaves_class(".cleavesf3",3);
                                cleaves_class(".cleavesf2",2);
                            }, false);
                        }
                        
                        

                        if($("#customers_statement_table").length>0){
                            var table = $('#customers_statement_table').DataTable();
                            table.ajax.url("?r=customers&f=get_customer_statement&p0="+$("#customers_list").val()).load(function () {
                                $(".sk-circle-layer").hide();
                            },false);
                        }   

                    });
                }
            },
        }
    });
    
    
}

function save_manual_invoice(invoice_id){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    
    var change_date_chk=0;
    if($("#change_date_chk").is(":checked")){
        change_date_chk=1;
    }

    $.getJSON("?r=invoice&f=save_manual_invoice&p0="+invoice_id+"&p1="+$("#invoice_customer_id").val()+"&p2="+$("#paid_invoice").val()+"&p3="+$("#invoice_discount").val().replace(/[^0-9\.]/g, '')+"&p4="+$("#salesman_invoice").val()+"&p5="+$("#invoice_note").val()+"&p6="+$("#inv_rate").val().replace(/[^0-9\.]/g, '')+"&p7="+$("#invoice_date").val()+"&p8="+change_date_chk, function (data) {
        
    }).done(function () {
        
        if($("#quick_invoices_table").length>0){
            var table = $('#quick_invoices_table').DataTable();
            table.ajax.url("?r=quick_display&f=get_all_invoices_quick&p0="+$("#quick_daterange").val()+"&p1="+$("#quick_status").val()).load(function () {
                $("."+pad_invoice(invoice_id)).addClass("selected");
                $(".sk-circle-layer").hide();
            }, false);
        }
        
        if($("#invoices_table").length>0){
 
            var table = $('#invoices_table').DataTable();
            table.ajax.url("?r=invoice&f=getAllInvoicesDateRange&p0="+current_store_id+"&p1="+current_date+"&p2="+current_invoice_filter+"&p3="+$("#filter_salesperson").val()+"&p4="+$("#filter_vendors").val()+"&p5="+$("#filter_taxable").val()).load(function () {
                table.page('last').draw(false);
                if(INVOICE_EDIT_MODE=="0"){
                    $('#invoices_table').closest('.dataTables_scrollBody').scrollTop($('#invoices_table').closest('.dataTables_scrollBody')[0].scrollHeight);
                }
                $('#print_btn').show();
                $(".sk-circle-layer").hide();
            }, false);
            
        }  
        
        
        if($("#cutomer_invoice_table").length>0){
            var table = $('#cutomer_invoice_table').DataTable();
            table.ajax.url("?r=invoice&f=getInvoicesMustPay&p0="+current_store_id).load(function () {
                $("."+pad_invoice(invoice_id)).addClass("selected");
                $(".sk-circle-layer").hide();
            }, false);
        }
        
        if($("#cutomer_invoice_table___").length>0){
            var table = $('#cutomer_invoice_table___').DataTable();
            table.ajax.url("?r=invoice&f=getInvoicesOfCustomers&p0="+current_customer_id).load(function () {
                $("."+pad_invoice(invoice_id)).addClass("selected");
                $(".sk-circle-layer").hide();
            }, false);
        }
        
   
        $(".sk-circle-layer").hide();
    });
}

function delete_manual_invoice(id){
    $.confirm({
        title: 'Delete Invoice!',
        content: 'Are you sure?',
        animation: 'zoom',
        closeAnimation: 'zoom',
        animateFromElement:false,
        buttons: {
            DELETE: {
                btnClass: 'btn-danger',
                action: function(){
                    $(".sk-circle").center();
                    $(".sk-circle-layer").show();
                    $.getJSON("?r=pos&f=delete_invoice&p0="+id, function (data) {

                    }).done(function () {
                        var table = $('#invoices_table').DataTable();
                        table.ajax.url("?r=invoice&f=getAllInvoicesDateRange&p0="+current_store_id+"&p1="+current_date+"&p2="+current_invoice_filter+"&p3="+$("#filter_salesperson").val()+"&p4="+$("#filter_vendors").val()+"&p5="+$("#filter_taxable").val()).load(function () {
                            $(".sk-circle-layer").hide();
                        },false);
                    });
                }
            },
            CANCEL: {
                btnClass: 'btn-default any-other-class', // multiple classes.
                action: function(){

                }
            },
        }
    });
      
}

function add_to_invoive_admin(id, ask_for_qty) {
    
    if($("#modal_create_invoice_modal__").length>0){
        if($(".minv_"+id).length==0){
            manual_invoice_add_item_to_invoice($("#invoice_id__").val(),id);
        }
    }

    var content =
        '<div class="modal small" data-backdrop="static"  id="qty_gallery" role="dialog" style="z-index:99999999" >\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title">Quantity & Price<i style="float:right;font-size:35px" class="glyphicon glyphicon-remove" onclick="closeModal(\'qty_gallery\')"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">\n\
                                <input type="text" value="" class="form-control" id="qty_to_add" placeholder="Quantity"  />\n\
                            </div>\n\
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">\n\
                                <input type="text" value="" class="form-control" id="new_price" placeholder="Set new Price or leave empty if no change required."  />\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-footer">\n\
                        <button style="width:150px;" type="button" class="btn btn-info" onclick="set_qty_gal_admin('+id+')">SET</button>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>';
    $("#qty_gallery").modal("hide");
    $("body").append(content);
    $('#qty_gallery').on('show.bs.modal', function(e) {

    });

    $('#qty_gallery').on('shown.bs.modal', function(e) {
        $("#qty_to_add").focus();
        
        
        document.getElementById('qty_to_add').addEventListener('keydown', function (event) {
            // Check if the key pressed is Enter (key code 13)
            if (event.keyCode === 13) {
              // Call a function or perform an action when Enter is pressed
                handleEnterKeyPress();
            }
          });

          function handleEnterKeyPress() {
            set_qty_gal_admin(id);
          }
    });

    $('#qty_gallery').on('hide.bs.modal', function(e) {
        $("#qty_gallery").remove();
    });
    
    $('#qty_gallery').modal('show');
    
}

function set_qty_gal_admin(id){
    $(".inv_it_qty_"+id).val($("#qty_to_add").val());
    $(".inv_it_qty_"+id).trigger("change");
    
    
    
    $(".minv_"+id).val(parseFloat($("#new_price").val()));
    $(".minv_"+id).trigger("change");
    
    $("#qty_gallery").modal("hide");
}