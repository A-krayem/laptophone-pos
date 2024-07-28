function add_credit_note(id,src){
    var customers_options = "";
    var pm_options = "";
    var currencies_options = "";
    var banks_options = "";
    var sourceItems = [];
    
    customers_options="<option value='0'>Select</option>";
    $.getJSON("?r=credit_note&f=getCreditNoteInfoNeeds", function (data) {
        $.each(data.customers, function (key, val) {
            customers_options+="<option value='"+val.id+"'>"+val.name+"</option>";
        });
        $.each(data.pm, function (key, val) {
            if(val.id==1)
                pm_options+="<option value='"+val.id+"'>"+val.method_name+"</option>";
        });
        $.each(data.currencies, function (key, val) {
            if(val.system_default==1)
                currencies_options+="<option value='"+val.id+"'>"+val.name+" ("+val.symbole+") </option>";
        });
        $.each(data.banks, function (key, val) {
            banks_options+="<option value='"+val.id+"' title='"+val.name+"'>"+val.name+"</option>";
        });
        $.each(data.items, function (key, val) {
            sourceItems.push({id:val.id,name:val.name});        
        });
    }).done(function () {
        var content =
        '<div class="modal" data-backdrop="static" id="credit_noteModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <form id="add_new_credit_note_form" action="" method="post" enctype="multipart/form-data" >\n\
                        <input id="id_to_edit" name="id_to_edit" value='+id+' type="hidden" />\n\
                        <div class="modal-header"> \n\
                            <h3 class="modal-title">Add Credit Note<i style="float:right;font-size:35px" class="glyphicon glyphicon-remove" onclick="closeModal(\'credit_noteModal\')"></i></h3>\n\
                        </div>\n\
                        <div class="modal-body" style="padding-top:5px;">\n\
                            <div class="row">\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pr2">\n\
                                    <div class="form-group">\n\
                                        <label for="customer_id">Customer Name</label>\n\
                                        <select data-live-search="true" id="customer_id" name="customer_id" class="form-control">'+customers_options+'</select>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 plr2" style="display:none">\n\
                                    <div class="form-group">\n\
                                        <label for="payment_method_id">Payment Method</label>\n\
                                        <select onchange="payment_method_changed()"  id="payment_method_id" name="payment_method_id" class="form-control">'+pm_options+'</select>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 plr2">\n\
                                    <div class="form-group">\n\
                                        <label for="credit_value">Credit Value</label>\n\
                                        <input autocomplete="off" id="credit_value" value="0" name="credit_value" type="text" class="form-control med_input" placeholder="Credit Value" />\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 plr2">\n\
                                    <div class="form-group">\n\
                                        <label for="auto_sum">Auto Sum</label>\n\
                                        <select onchange="calculation_total_cn_value()" id="auto_sum" name="auto_sum" class="selectpicker form-control" ><option value="0">Disable</option><option value="1">Enable</option></select>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 plr2" id="payment_currency_container">\n\
                                    <div class="form-group">\n\
                                        <label for="payment_currency">Currency</label>\n\
                                        <select onchange="check_currency_rate()" data-live-search="true" id="payment_currency" name="payment_currency" class="selectpicker form-control" >'+currencies_options+'</select>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 pl2" id="currency_rate_container">\n\
                                    <div class="form-group">\n\
                                        <label for="payment_note">Rate</label>\n\
                                        <div class="inner-addon"><input autocomplete="off" id="currency_rate" name="currency_rate" type="text" class="form-control med_input" placeholder="Rate"></div>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pl2" id="to_lbp_rate_container">\n\
                                    <div class="form-group">\n\
                                        <label for="cr_rate_to_lbp" style="width:100%">Rate</label>\n\
                                        <div class="input-group">\n\
                                            <span class="input-group-addon" style="width:40px;"><b>1 USD </b>= </span>\n\
                                                <input type="text" class="form-control cleavesf3" name="cr_rate_to_lbp" id="cr_rate_to_lbp" value="0" placeholder="" style="padding-left:5px;padding-right:5px;font-weight:bold;" />\n\
                                            <span class="input-group-addon" style="width:40px;"><b>LBP</b></span>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="row">\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 pl2 bank_input" style="display:none">\n\
                                    <div class="form-group">\n\
                                        <label for="bank_source">Bank</label>&nbsp;&nbsp;<span onclick="addBank(\'supplier_payment\')" style="cursor:pointer; font-size:14px; font-weight:bold;color:#286090">Add new bank</span>\n\
                                        <select data-live-search="true" id="bank_source" name="bank_source" class=" form-control" >'+banks_options+'</select>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12 plr2 bank_input" style="display:none">\n\
                                    <div class="form-group">\n\
                                        <label for="payment_note">Reference Number</label>\n\
                                        <div class="inner-addon"><input autocomplete="off" id="reference" name="reference" type="text" class="form-control" placeholder="Reference"></div>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 plr2 bank_input" style="display:none">\n\
                                    <div class="form-group">\n\
                                        <label for="payment_note">Owner</label>\n\
                                        <div class="inner-addon"><input autocomplete="off" id="payment_owner" name="payment_owner" type="text" class="form-control" placeholder="Owner"></div>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 pl2" style="display:none">\n\
                                    <div class="form-group">\n\
                                        <label for="debit_note_note">Note</label>\n\
                                        <input autocomplete="off" id="debit_note_note" value="" name="debit_note_note" type="text" class="form-control" placeholder="Note" />\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="row">\n\
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pr2">\n\
                                    <div class="form-group" style="margin-bottom:5px;">\n\
                                        <label for="code">Search Item</label>\n\
                                        <input autocomplete="off" id="search_item" name="search_item" class="form-control" style="width:100%;font-size:14px; !important" placeholder="Search by barcode, description" />\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 pl2">\n\
                                    &nbsp;\n\
                                </div>\n\
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 pl2">\n\
                                    <div class="form-group" style="margin-bottom:5px;">\n\
                                        <label for="code">Note</label>\n\
                                        <input autocomplete="off" id="credit_note_note" name="credit_note_note" class="form-control" style="width:100%;font-size:14px; !important" placeholder="Note" />\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="row">\n\
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                    <table id="creditnote_table_" class="table table-striped table-bordered" cellspacing="0" >\n\
                                        <thead>\n\
                                            <tr>\n\
                                                <th style="width: 80px !important;">ID</th>\n\
                                                <th style="width: 80px !important;">Items ID</th>\n\
                                                <th style="width: 120px !important;">Barcode</th>\n\
                                                <th>Description</th>\n\
                                                <th style="width: 70px !important;">Color</th>\n\
                                                <th style="width: 70px !important;">Size</th>\n\
                                                <th style="width: 60px !important;text-align:center">Qty</th>\n\
                                                <th style="width: 100px !important;text-align:center">Price</th>\n\
                                                <th style="width: 100px !important;text-align:center">Total</th>\n\
                                                <th style="width: 40px !important;">&nbsp;</th>\n\
                                            </tr>\n\
                                        </thead>\n\
                                        <tbody></tbody>\n\
                                    </table>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="modal-footer">\n\
                            <a onclick="$(this).closest(\'form\').submit()" id="add_btn" type="submit" class="btn btn-primary">Add</a>\n\
                        </div>\n\
                    </form>\n\
                </div>\n\
            </div>\n\
        </div>';
        $("#credit_noteModal").remove();
        $("body").append(content);

        $('#credit_noteModal').on('show.bs.modal', function (e) {   
        });

        $('#credit_noteModal').on('shown.bs.modal', function (e) {
            $('#customer_id').selectpicker();
            $('#payment_method_id').selectpicker();
            
            $('#payment_currency').selectpicker();
            $('#bank_source').selectpicker();
            $('#autocalc').selectpicker();
            
            
            
            check_currency_rate();
            
           
            //$(".only_numeric").numeric({ negative : false});
            //format_input_number(0,"#credit_value",2,0);
            cleaves_id("credit_value",5);
            cleaves_id("currency_rate",10);
            cleaves_id("cr_rate_to_lbp",5);
            
            if(id>0){
                var customer_id = null;
                var credit_payment_method = null;
                var credit_value = null;
                var note = null;
                
                var payment_currency = null;
                var bank_id = null;
                var reference = null;
                var payment_owner = null;
                var currency_rate = null;
                var cr_rate_to_lbp = null;
                var autosum = null;
                
                $(".sk-circle-layer").show();

                $.getJSON("?r=credit_note&f=get_credit_note&p0=" + id, function (data) {
                    customer_id = data[0].customer_id;
                    credit_payment_method = data[0].credit_payment_method;
                    credit_value = data[0].credit_value;
                    cr_rate_to_lbp = data[0].cr_rate_to_lbp;
                    autosum = data[0].auto_sum;
                    
                    note = data[0].note;
                    
                    payment_currency = data[0].payment_currency;
                    currency_rate= data[0].currency_rate;
                    bank_id = data[0].bank_id;
                    reference = data[0].reference;
                    payment_owner = data[0].payment_owner;
                    
                }).done(function () {
                    $('#auto_sum').selectpicker('val', autosum);
                    $('#auto_sum').selectpicker('refresh');
                    
                    $('#customer_id').selectpicker('val', customer_id);
                    $('#customer_id').selectpicker('refresh');
                    
             
                    $('#payment_currency').selectpicker('val', payment_currency);
                    $('#payment_currency').selectpicker('refresh');
                    
                    $('#currency_rate').val(parseFloat(currency_rate));
                    cleaves_id("currency_rate",10);
                    
                    //$('#currency_rate').attr("disabled","disabled");
                    
                    $('#bank_source').selectpicker('val', bank_id);
                    $('#bank_source').selectpicker('refresh');
                    
                    $('#reference').val(reference);
                    $('#payment_owner').val(payment_owner);
                    
                    $('#payment_method_id').selectpicker('val', credit_payment_method);
                    $('#payment_method_id').selectpicker('refresh');
                    
                    payment_method_changed();
                    
                    
                    $('#credit_value').val(parseFloat(credit_value));
                    cleaves_id("credit_value",5);
                    
                    $('#cr_rate_to_lbp').val(parseFloat(cr_rate_to_lbp));
                    cleaves_id("cr_rate_to_lbp",5);
                    
                    
                    $('#credit_note_note').val(note);
                    $('#add_btn').html("Update");
                    $(".sk-circle-layer").hide();
                });
            }
            
            
            /* items */
            $input_prepare_search_items = $("#search_item");
            $input_prepare_search_items.typeahead({
                source: sourceItems,
                items: 1000,
            });
            
            $input_prepare_search_items.change(function() {
                var current = $input_prepare_search_items.typeahead("getActive");
                if (current) {
                    if (current.name == $input_prepare_search_items.val()) {
                        //if($("."+padItem(current.id)).length==0){
                            add_item_to_credit_note(id,current.id);
                        //}else{
                            //$(".selected").removeClass("selected");
                            //$("."+padItem(current.id)).addClass("selected");
                        //}
                        $("#search_item").val("");
                        //$("#search_item").trigger("change");
                    }
                }
            });
            
            
            var search_fields = [];
            var index = 0;
            $('#creditnote_table_ tfoot th').each( function () {
                if(jQuery.inArray(index, search_fields) !== -1){
                    var title = $(this).text();
                    $(this).html( '<div class="inner-addon left-addon"><input id="idf_'+index+'" style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder="'+title+'" /></div>' );
                    index++;
                }
            });
            
            var creditnote_table_ = $('#creditnote_table_').dataTable({
                ajax: "?r=credit_note&f=get_credit_note_details&p0="+id,
                responsive: true,
                orderCellsTop: true,
                bLengthChange: true,
                iDisplayLength: 1000,
                ordering:false,
                aoColumnDefs: [
                    { "targets": [0], "searchable": true, "orderable": false, "visible": false },
                    { "targets": [1], "searchable": true, "orderable": false, "visible": true },
                    { "targets": [2], "searchable": true, "orderable": false, "visible": true },
                    { "targets": [3], "searchable": true, "orderable": false, "visible": true },
                    { "targets": [4], "searchable": true, "orderable": false, "visible": true },
                    { "targets": [5], "searchable": true, "orderable": false, "visible": true },
                    { "targets": [6], "searchable": true, "orderable": false, "visible": true },
                    { "targets": [7], "searchable": true, "orderable": false, "visible": true },
                    { "targets": [8], "searchable": true, "orderable": false, "visible": true },
                    { "targets": [9], "searchable": true, "orderable": false, "visible": true,"className": "dt-center" },
                ],
                scrollY: '45vh',
                scrollCollapse: true,
                paging: false,
                order: [[ 0, "asc" ]],
                dom: '<"toolbar_crn">frtip',
                initComplete: function( settings ) { 
                    cleaves_class(".clv",5);
                    calculation_total_cn_value();
                    $(".sk-circle-layer").hide();
                },
                fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    $(nRow).addClass(aData[0]);
                },
                fnDrawCallback: updateCNRows_,
            });
            
            $('#creditnote_table_').DataTable().on('mousedown',"tr", function ( e, dt, type, indexes ) { 
                $('.selected').removeClass("selected");
                $(this).addClass('selected');
            });
            
            submit_credit_note(src,id);
        });

        $('#credit_noteModal').on('hide.bs.modal', function (e) {
            $("#credit_noteModal").remove();
        });

        $('#credit_noteModal').modal('show');
    });
}

function updateCNRows_(){
    var table = $('#creditnote_table_').DataTable();
    var p = table.rows({ page: 'current' }).nodes();
    for (var k = 0; k < p.length; k++){
        var index = table.row(p[k]).index();
        table.cell(index,9).data('<i title="Delete" class="glyphicon glyphicon-trash red" onclick="delete_row_from_cr(\''+parseInt(table.cell(index, 0).data())+'\')" style="font-size:16px;cursor:pointer" ></i>');
    }
}

function delete_row_from_cr(id){
    
    swal({
        title: "Are you sure?",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, Delete",
        closeOnConfirm: true
    },
    function(isConfirm){
       if(isConfirm){
           $(".sk-circle").center();
            $(".sk-circle-layer").show();
            $.getJSON("?r=credit_note&f=delete_row_from_cr&p0="+id+"&p1=1", function (data) {
                
            }).done(function () {
                $('#creditnote_table_').DataTable().ajax.url("?r=credit_note&f=get_credit_note_details&p0="+$("#id_to_edit").val()).load(function () {
                    $('#creditnote_table_').DataTable().page('last').draw(false);
                    $('#creditnote_table_').DataTable().row(':last', {page: 'current'}).select();
                    cleaves_class(".clv",5);
                    calculation_total_cn_value();
                    $(".sk-circle-layer").hide();
                }, false);
            });
       }
    }); 
}

function add_item_to_credit_note(credit_note_id,item_id){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    $.getJSON("?r=credit_note&f=add_item_to_credit_note&p0="+credit_note_id+"&p1="+item_id, function (data) {

    }).done(function () {
        $('#creditnote_table_').DataTable().ajax.url("?r=credit_note&f=get_credit_note_details&p0="+credit_note_id).load(function () {
            $('#creditnote_table_').DataTable().page('last').draw(false);
            $('#creditnote_table_').DataTable().row(':last', {page: 'current'}).select();
            cleaves_class(".clv",5);
            calculation_total_cn_value();
            $(".sk-circle-layer").hide();
        }, false);
    });
}

function cn_qty_changed(id){
    $.getJSON("?r=credit_note&f=cn_qty_changed&p0="+id+"&p1="+$("#cn_qty_"+id).val().replace(/[^0-9\.]/g, ''), function (data) {

    }).done(function () {
        $("#cn_total_"+id).val($("#cn_qty_"+id).val().replace(/[^0-9\.]/g, '')*$("#cn_price_"+id).val().replace(/[^0-9\.]/g, ''));
        cleaves_id("cn_total_"+id,5);
        calculation_total_cn_value();
    });
}

function cn_price_changed(id){
    $.getJSON("?r=credit_note&f=cn_price_changed&p0="+id+"&p1="+$("#cn_price_"+id).val().replace(/[^0-9\.]/g, ''), function (data) {
        
    }).done(function () {
        $("#cn_total_"+id).val($("#cn_qty_"+id).val().replace(/[^0-9\.]/g, '')*$("#cn_price_"+id).val().replace(/[^0-9\.]/g, ''));
        cleaves_id("cn_total_"+id,5);
        calculation_total_cn_value();
    });
}


function calculation_total_cn_value(){
    if($('#auto_sum').val()==0){
        return;
    }
    var t = 0;
    $(".cntpi").each(function( index ) {
        t+=parseFloat($(this).val().replace(/[^0-9\.]/g, ''));
    });
    $("#credit_value").val(t);
    cleaves_id("credit_value",5);
}


function payment_method_changed(){
    if($("#payment_method_id").val()==2){
        $(".credit_card_input").hide();
        $(".bank_input").show();
    }else if($("#payment_method_id").val()==3){
        $(".bank_input").hide();
        $(".credit_card_input").show();
    }else{
        $(".bank_input").hide();
        $(".credit_card_input").hide();
    }
}

function submit_credit_note(src,id){
    $("#add_new_credit_note_form").on('submit', (function (e) {
        e.preventDefault();
        
        $("#credit_value").val($("#credit_value").val().replace(/,/g , ''));
        $.ajax({
            url: "?r=credit_note&f=add_credit_note",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            success: function (data)
            {
                $('#credit_noteModal').modal('hide');
                $(".sk-circle-layer").show();
                if(src == "credit_note"){
                    var table = $('#credit_notes_table').DataTable();
                    if(id==0){
                        table.ajax.url("?r=credit_note&f=get_credit_notes&p0="+$("#salesDate").val()).load(function () {
                            table.page('last').draw(false);
                            table.row(':last', {page: 'current'}).select();
                             $(".sk-circle-layer").hide();
                        }, false);
                    }else{
                        table.ajax.url("?r=credit_note&f=get_credit_notes&p0="+$("#salesDate").val()).load(function () {
                            table.row('.' + pad_credit_not(id), {page: 'current'}).select();
                            $(".sk-circle-layer").hide();
                        }, false);
                    }
                    
                }
            }
        });
    }));
}
