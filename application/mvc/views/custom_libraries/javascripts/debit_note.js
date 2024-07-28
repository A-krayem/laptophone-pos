function pi_changed(with_data){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var _data = [];
    $.getJSON("?r=stock&f=getStockInvoiceById&p0="+$("#pi_id").val(), function (data) {
        _data = data;
    }).done(function () {
        //$("#payment_currency").selectpicker('val', _data[0].currency_id);
  
        var table = $('#debitnote_table_').DataTable();
        table.ajax.url("?r=stock&f=getStockInvoiceItems&p0="+$("#pi_id").val()+"&p1="+with_data).load(function () {
            table.page('last').draw(false);
            $(".only_numeric").numeric();
            //table.row(':last', {page: 'current'}).select();
            $(".sk-circle-layer").hide();
        }, false);
    });
    
}

function pi_return(id){
    //$(".sk-circle-layer").show();
    $.getJSON("?r=debit_note&f=pi_return&p0="+id+"&p1="+$("#piqty_"+id).val(), function (data) {
        $.each(data.pi, function (key, val) {
            
        });
    }).done(function () {
        //$(".sk-circle-layer").hide();
    });
}

function total_debit_value(){
    var table = $('#debitnote_table_').DataTable();
    var p = table.rows({ page: 'current' }).nodes();
    var tmp = 0;
    for (var k = 0; k < p.length; k++){
        var index = table.row(p[k]).index();
        if($("#piqty_"+table.cell(index, 0).data()).val()>0){
            tmp+=parseFloat($("#tc_"+table.cell(index, 0).data()).val())*parseFloat($("#piqty_"+table.cell(index, 0).data()).val());
        }
    }
    
    $("#debit_value").val(tmp);
    cleaves_id("debit_value",5);
}

function supplier_debit_changed(p_invoice){
    if($("#supplier_id").val()!=0){
        var pi_options = "";
        $.getJSON("?r=debit_note&f=getDebitNoteInfoNeeds&p0="+$("#supplier_id").val(), function (data) {
            pi_options+="<option value='0'>Select PI</option>";
            $.each(data.pi, function (key, val) {
                pi_options+="<option value='"+val.id+"'>"+val.pi_name+"</option>";
            });
        }).done(function () {
            $("#pi_id").empty();
            $("#pi_id").append(pi_options);
            $('#pi_id').selectpicker('refresh');
            
            if(p_invoice!=-1){
                $('#pi_id').selectpicker('val', p_invoice);
                $('#pi_id').selectpicker('refresh');
                
               
            }
        });
    }else{
        $("#pi_id").empty();
        $('#pi_id').selectpicker('refresh');
    }
}

function add_debit_note(id,src){
    var suppliers_options = "";
    var pm_options = "";
    var pi_options = "";
    var banks_options = "";
    var currencies_options = "";
    
    var currencies_rate_display="";
    
    $.getJSON("?r=debit_note&f=getDebitNoteInfoNeeds&p0=0", function (data) {
        
        if(data.currencies_count==1){
            currencies_rate_display="display:none;";
        }
        
        suppliers_options+="<option value='0'>Select Supplier</option>";
        $.each(data.suppliers, function (key, val) {
            suppliers_options+="<option value='"+val.id+"'>"+val.name+"</option>";
        });
        $.each(data.pm, function (key, val) {
            if(val.id==1)
                pm_options+="<option value='"+val.id+"'>"+val.method_name+"</option>";
        });
        $.each(data.banks, function (key, val) {
            banks_options+="<option value='"+val.id+"' title='"+val.name+"'>"+val.name+"</option>";
        });
        $.each(data.currencies, function (key, val) {
            currencies_options+="<option value='"+val.id+"'>"+val.name+" ("+val.symbole+") </option>";
        });
    }).done(function () {
        var content =
        '<div class="modal" data-backdrop="static" id="debit_noteModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <form id="add_new_debit_note_form" action="" method="post" enctype="multipart/form-data" >\n\
                        <input id="id_to_edit" name="id_to_edit" value='+id+' type="hidden" />\n\
                        <div class="modal-header"> \n\
                            <h3 class="modal-title">Add Debit Note<i style="float:right;font-size:35px" class="glyphicon glyphicon-remove" onclick="closeModal(\'debit_noteModal\')"></i></h3>\n\
                        </div>\n\
                        <div class="modal-body">\n\
                            <div class="row">\n\
                                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12 pr2">\n\
                                    <div class="form-group">\n\
                                        <label for="supplier_id">Supplier Name</label>\n\
                                        <select onchange="supplier_debit_changed(-1)" data-live-search="true" id="supplier_id" name="supplier_id" class="form-control">'+suppliers_options+'</select>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12 plr2">\n\
                                    <div class="form-group">\n\
                                        <label for="pi_id">Closed PI</label>\n\
                                        <select onchange="pi_changed(0)" data-live-search="true" id="pi_id" name="pi_id" class="form-control">'+pi_options+'</select>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12" style="display:none">\n\
                                    <div class="form-group">\n\
                                        <label for="payment_method">Payment Method</label>\n\
                                        <select onchange="payment_method_supplier_changed()"  id="payment_method" name="payment_method" class="form-control">'+pm_options+'</select>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12 plr2">\n\
                                    <div class="form-group">\n\
                                        <label for="debit_value">Debit Value</label>\n\
                                        <input autocomplete="off" id="debit_value" value="0" name="debit_value" type="text" class="form-control med_input" placeholder="Debit Value" />\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 plr2">\n\
                                    <div class="form-group">\n\
                                        <label for="payment_currency">Currency</label>\n\
                                        <select data-live-search="true" id="payment_currency" name="payment_currency" class="selectpicker form-control" >'+currencies_options+'</select>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pl2" id="currency_rate_container" style="'+currencies_rate_display+'">\n\
                                    <div class="form-group">\n\
                                        <label for="inv_rate" style="width:100%">Rate</label>\n\
                                        <div class="input-group">\n\
                                            <span class="input-group-addon" style="width:40px;"><b>1 USD </b>= </span>\n\
                                                <input type="text" class="form-control cleavesf3" name="currency_rate" id="currency_rate" value="1500" placeholder="" style="padding-left:5px;padding-right:5px;font-weight:bold;" />\n\
                                            <span class="input-group-addon" style="width:40px;"><b>LBP</b></span>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="row">\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 bank_input" style="display:none">\n\
                                    <div class="form-group">\n\
                                        <label for="bank_source">Bank</label>&nbsp;&nbsp;<span onclick="addBank(\'supplier_payment\')" style="cursor:pointer; font-size:14px; font-weight:bold;color:#286090">Add new bank</span>\n\
                                        <select data-live-search="true" id="bank_source" name="bank_source" class=" form-control" >'+banks_options+'</select>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12 bank_input" style="display:none">\n\
                                    <div class="form-group">\n\
                                        <label for="payment_note">Reference Number</label>\n\
                                        <div class="inner-addon"><input autocomplete="off" id="reference" name="reference" type="text" class="form-control" placeholder="Reference"></div>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 bank_input" style="display:none">\n\
                                    <div class="form-group">\n\
                                        <label for="payment_note">Owner</label>\n\
                                        <div class="inner-addon"><input autocomplete="off" id="payment_owner" name="payment_owner" type="text" class="form-control" placeholder="Owner"></div>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">\n\
                                    <div class="form-group">\n\
                                        <label for="debit_note_note">Note</label>\n\
                                        <input autocomplete="off" id="debit_note_note" value="" name="debit_note_note" type="text" class="form-control" placeholder="Note" />\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="row">\n\
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                    <table id="debitnote_table_" class="table table-striped table-bordered" cellspacing="0" >\n\
                                        <thead>\n\
                                            <tr>\n\
                                                <th style="width: 80px !important;">ID</th>\n\
                                                <th style="width: 60px !important;">Items ID</th>\n\
                                                <th style="width: 80px !important;">Barcode</th>\n\
                                                <th>Description</th>\n\
                                                <th style="width: 70px !important;">Color</th>\n\
                                                <th style="width: 70px !important;">Size</th>\n\
                                                <th style="width: 25px !important;">Qty</th>\n\
                                                <th style="width: 30px !important;">Return</th>\n\
                                                <th style="width: 90px !important;">Cost</th>\n\
                                                <th style="width: 40px !important;">Disc.</th>\n\
                                                <th style="width: 30px !important;">Vat</th>\n\
                                                <th style="width: 70px !important;">Disc. A Vat</th>\n\
                                                <th style="width: 90px !important;">T. Cost</th>\n\
                                            </tr>\n\
                                        </thead>\n\
                                        <tbody></tbody>\n\
                                    </table>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="modal-footer">\n\
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\n\
                            <a onclick="$(this).closest(\'form\').submit()" id="add_btn" type="submit" class="btn btn-primary">Add</a>\n\
                        </div>\n\
                    </form>\n\
                </div>\n\
            </div>\n\
        </div>';
        $("#debit_noteModal").remove();
        $("body").append(content);

        $('#debit_noteModal').on('show.bs.modal', function (e) {   
        });

        $('#debit_noteModal').on('shown.bs.modal', function (e) {
            
            $('#supplier_id').selectpicker();
            $('#payment_method_id').selectpicker();
            $('#pi_id').selectpicker();
            $('#bank_source').selectpicker();
            $('#payment_currency').selectpicker();
            
            
           
           $('#payment_method').selectpicker();
           $('#payment_method').selectpicker();
            cleaves_id("debit_value",5);
            cleaves_id("currency_rate",5);
            
            
            //$(".mask_format").mask("#,##0.00", {reverse: true});
            
            supplier_debit_changed(-1);
            
            //update_rate();
            
            payment_method_supplier_changed();

            if(id>0){
                var supplier_id = null;
                var debit_payment_method = null;
                var debit_value = null;
                var note = null;
                var payment_currency = null;
                
                var bank_id = null;
                var reference = null;
                var payment_owner = null;
                var currency_rate = null;
                
                var p_invoice = null;
                
                $(".sk-circle-layer").show();

                $.getJSON("?r=debit_note&f=get_debit_note&p0=" + id, function (data) {
                    supplier_id = data[0].supplier_id;
                    debit_payment_method = data[0].debit_payment_method;
                    payment_currency = data[0].payment_currency;
                    
                    
                    currency_rate= parseFloat(data[0].currency_rate);
                    
                    bank_id = data[0].bank_id;
                    reference = data[0].reference;
                    payment_owner = data[0].payment_owner;
                    
                    p_invoice = data[0].p_invoice;
                    
                    
                    //alert(data[0].debit_value);
                    //data[0].debit_value = 1500.00;
                    debit_value = parseFloat(data[0].debit_value);
                    cleaves_id("debit_value",5);
                    note = data[0].note;
                }).done(function () {
                    $('#supplier_id').selectpicker('val', supplier_id);
                    $('#supplier_id').selectpicker('refresh');
                    //$('#supplier_id').attr("disabled","disabled");
                    
                    
                    $('#pi_id').append("<option value='"+p_invoice+"'>"+PadSTKINV(p_invoice)+"</option>")
                    $('#pi_id').selectpicker('refresh');
                     pi_changed(1);
                    
                    $('#payment_method').selectpicker('val', debit_payment_method);
                    $('#payment_method').selectpicker('refresh');
                    
                    payment_method_supplier_changed();
               
                    $('#payment_currency').selectpicker('val', payment_currency);
                    $('#payment_currency').selectpicker('refresh');
                    //$('#payment_currency').attr("disabled","disabled");

                    $('#debit_value').val(parseFloat(debit_value));
                    cleaves_id("debit_value",5);
                   
                    
                
                    $('#currency_rate').val(parseFloat(currency_rate));
                     cleaves_id("currency_rate",5);
                    //$('#currency_rate').attr("disabled","disabled");
                    
                    
                    $('#debit_note_note').val(note);
                    
                    $('#add_btn').html("Update");
                    $(".sk-circle-layer").hide();
                    
 
                    $('#bank_source').selectpicker('val', bank_id);
                    $('#bank_source').selectpicker('refresh');
                    
                    //$('#pi_id').attr('disabled','disabled');
                    
                    $('#reference').val(reference);
                    $('#payment_owner').val(payment_owner);
                    
                    //(".mask_format").trigger('input');
                });
            }
            
            cleaves_id("debit_value",5);
            cleaves_id("currency_rate",5);
            
            var search_fields = [];
            var index = 0;
            $('#debitnote_table_ tfoot th').each( function () {
                if(jQuery.inArray(index, search_fields) !== -1){
                    var title = $(this).text();
                    $(this).html( '<div class="inner-addon left-addon"><input id="idf_'+index+'" style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder="'+title+'" /></div>' );
                    index++;
                }
            });
            
            var debitnote_table_ = $('#debitnote_table_').dataTable({
                //ajax: "?r=items&f=get_group&p0="+item_id,
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
                    { "targets": [9], "searchable": true, "orderable": false, "visible": true },
                    { "targets": [10], "searchable": true, "orderable": false, "visible": true },
                    { "targets": [11], "searchable": true, "orderable": false, "visible": true },
                    { "targets": [12], "searchable": true, "orderable": false, "visible": true }
                ],
                scrollY: '45vh',
                scrollCollapse: true,
                paging: false,
                order: [[ 0, "asc" ]],
                dom: '<"toolbar_dbn">frtip',
                initComplete: function( settings ) {
                    //var table = $('#debitnote_table_').DataTable();
                    //table.row(':eq(0)', { page: 'current' }).select();

                    

                    $(".sk-circle-layer").hide();
                },
                fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    $(nRow).addClass(aData[0]);
                },
                //fnDrawCallback: updateGroupRows_,
            });
            submit_debit_note(src,id);
        });

        $('#debit_noteModal').on('hide.bs.modal', function (e) {
            $("#debit_noteModal").remove();
        });

        $('#debit_noteModal').modal('show');
    });
}



function add_debit_note_on_the_fly(id,src){
    var suppliers_options = "";
    var pm_options = "";
    var pi_options = "";
    var banks_options = "";
    var currencies_options = "";
    var sourceItems = [];
    
    var currencies_rate_display="";

    $.getJSON("?r=debit_note&f=getDebitNoteInfoNeeds&p0=0", function (data) {
        if(data.currencies_count==1){
            currencies_rate_display="display:none;";
        }
        
        suppliers_options+="<option value='0'>Select Supplier</option>";
        $.each(data.suppliers, function (key, val) {
            suppliers_options+="<option value='"+val.id+"'>"+val.name+"</option>";
        });
        $.each(data.pm, function (key, val) {
            if(val.id==1)
                pm_options+="<option value='"+val.id+"'>"+val.method_name+"</option>";
        });
        $.each(data.banks, function (key, val) {
            banks_options+="<option value='"+val.id+"' title='"+val.name+"'>"+val.name+"</option>";
        });
        $.each(data.currencies, function (key, val) {
            currencies_options+="<option value='"+val.id+"'>"+val.name+" ("+val.symbole+") </option>";
        });
        $.each(data.items, function (key, val) {
            sourceItems.push({id:val.id,name:val.name});        
        });
    }).done(function () {
        var content =
        '<div class="modal" data-backdrop="static" id="debit_noteModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <form id="add_new_debit_note_on_the_fly_form" action="" method="post" enctype="multipart/form-data" >\n\
                        <input id="id_to_edit" name="id_to_edit" value='+id+' type="hidden" />\n\
                        <div class="modal-header"> \n\
                            <h3 class="modal-title">Add Debit Note on the fly<i style="float:right;font-size:35px" class="glyphicon glyphicon-remove" onclick="closeModal(\'debit_noteModal\')"></i></h3>\n\
                        </div>\n\
                        <div class="modal-body">\n\
                            <div class="row">\n\
                                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">\n\
                                    <div class="form-group">\n\
                                        <label for="supplier_id">Supplier Name</label>\n\
                                        <select onchange="supplier_debit_changed(-1)" data-live-search="true" id="supplier_id" name="supplier_id" class="form-control">'+suppliers_options+'</select>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12" style="display:none">\n\
                                    <div class="form-group">\n\
                                        <label for="pi_id">Closed PI</label>\n\
                                        <select onchange="pi_changed(0)" data-live-search="true" id="pi_id" name="pi_id" class="form-control">'+pi_options+'</select>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12" style="display:none">\n\
                                    <div class="form-group">\n\
                                        <label for="payment_method">Payment Method</label>\n\
                                        <select onchange="payment_method_supplier_changed()"  id="payment_method" name="payment_method" class="form-control">'+pm_options+'</select>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">\n\
                                    <div class="form-group">\n\
                                        <label for="debit_value">Debit Value</label>\n\
                                        <input autocomplete="off" id="debit_value" value="0" name="debit_value" type="text" class="form-control med_input" placeholder="Debit Value" />\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">\n\
                                    <div class="form-group">\n\
                                        <label for="payment_currency">Currency</label>\n\
                                        <select data-live-search="true" id="payment_currency" name="payment_currency" class="selectpicker form-control" >'+currencies_options+'</select>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pl2" id="currency_rate_container" style="'+currencies_rate_display+'">\n\
                                    <div class="form-group">\n\
                                        <label for="inv_rate" style="width:100%">Rate</label>\n\
                                        <div class="input-group" style="width:100%">\n\
                                            <span class="input-group-addon" style="width:40px;"><b>1 USD </b>= </span>\n\
                                                <input required type="text" class="form-control cleavesf3" name="currency_rate" id="currency_rate" value="1500" placeholder="" style="padding-left:5px;padding-right:5px;font-weight:bold;" />\n\
                                            <span class="input-group-addon" style="width:40px;"><b>LBP</b></span>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">\n\
                                    <div class="form-group">\n\
                                        <label for="debit_note_note">Note</label>\n\
                                        <input autocomplete="off" id="debit_note_note" value="" name="debit_note_note" type="text" class="form-control" placeholder="Note" />\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="row">\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 bank_input" style="display:none">\n\
                                    <div class="form-group">\n\
                                        <label for="bank_source">Bank</label>&nbsp;&nbsp;<span onclick="addBank(\'supplier_payment\')" style="cursor:pointer; font-size:14px; font-weight:bold;color:#286090">Add new bank</span>\n\
                                        <select data-live-search="true" id="bank_source" name="bank_source" class=" form-control" >'+banks_options+'</select>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12 bank_input" style="display:none">\n\
                                    <div class="form-group">\n\
                                        <label for="payment_note">Reference Number</label>\n\
                                        <div class="inner-addon"><input autocomplete="off" id="reference" name="reference" type="text" class="form-control" placeholder="Reference"></div>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 bank_input" style="display:none">\n\
                                    <div class="form-group">\n\
                                        <label for="payment_note">Owner</label>\n\
                                        <div class="inner-addon"><input autocomplete="off" id="payment_owner" name="payment_owner" type="text" class="form-control" placeholder="Owner"></div>\n\
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
                            </div>\n\
                            <div class="row">\n\
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                    <table id="debitnote_table_" class="table table-striped table-bordered" cellspacing="0" >\n\
                                        <thead>\n\
                                            <tr>\n\
                                                <th style="width: 80px !important;">ID</th>\n\
                                                <th style="width: 80px !important;">Items ID</th>\n\
                                                <th style="width: 120px !important;">Barcode</th>\n\
                                                <th>Description</th>\n\
                                                <th style="width: 70px !important;">Color</th>\n\
                                                <th style="width: 70px !important;">Size</th>\n\
                                                <th style="width: 60px !important;text-align:center">Qty</th>\n\
                                                <th style="width: 100px !important;text-align:center">Cost</th>\n\
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
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\n\
                            <a onclick="$(this).closest(\'form\').submit()" id="add_btn" type="submit" class="btn btn-primary">Add</a>\n\
                        </div>\n\
                    </form>\n\
                </div>\n\
            </div>\n\
        </div>';
        $("#debit_noteModal").remove();
        $("body").append(content);

        $('#debit_noteModal').on('show.bs.modal', function (e) {   
        });

        $('#debit_noteModal').on('shown.bs.modal', function (e) {
            $('#supplier_id').selectpicker();
            $('#payment_method_id').selectpicker();
            $('#pi_id').selectpicker();
            $('#bank_source').selectpicker();
            $('#payment_currency').selectpicker();
            
            
           
           $('#payment_method').selectpicker();
           $('#payment_method').selectpicker();
            cleaves_id("debit_value",5);
            cleaves_id("currency_rate",5);
            
            
            //$(".mask_format").mask("#,##0.00", {reverse: true});
            
            supplier_debit_changed(-1);
            //update_rate();
            //payment_method_supplier_changed();

            if(id>0){
                var supplier_id = null;
                var debit_payment_method = null;
                var debit_value = null;
                var note = null;
                var payment_currency = null;
                
                var bank_id = null;
                var reference = null;
                var payment_owner = null;
                var currency_rate = null;
                
                var p_invoice = null;
                
                $(".sk-circle-layer").show();

                $.getJSON("?r=debit_note&f=get_debit_note&p0=" + id, function (data) {
                    supplier_id = data[0].supplier_id;
                    debit_payment_method = data[0].debit_payment_method;
                    payment_currency = data[0].payment_currency;
                    
                    if(data[0].currency_rate!=NULL)
                        currency_rate= parseFloat(data[0].currency_rate);
                    
                    bank_id = data[0].bank_id;
                    reference = data[0].reference;
                    payment_owner = data[0].payment_owner;
                    
                    p_invoice = data[0].p_invoice;
                    
                    debit_value = parseFloat(data[0].debit_value);
                    cleaves_id("debit_value",5);
                    note = data[0].note;
                }).done(function () {
                    $('#supplier_id').selectpicker('val', supplier_id);
                    $('#supplier_id').selectpicker('refresh');
                    //$('#supplier_id').attr("disabled","disabled");
                    
                    
                    $('#pi_id').append("<option value='"+p_invoice+"'>"+PadSTKINV(p_invoice)+"</option>")
                    $('#pi_id').selectpicker('refresh');
                     //pi_changed(1);
                    
                    $('#payment_method').selectpicker('val', debit_payment_method);
                    $('#payment_method').selectpicker('refresh');
                    
                    //payment_method_supplier_changed();
                    
                    $('#payment_currency').selectpicker('val', payment_currency);
                    $('#payment_currency').selectpicker('refresh');
                    //$('#payment_currency').attr("disabled","disabled");

                    $('#debit_value').val(parseFloat(debit_value));
                    cleaves_id("debit_value",5);
                    
                
                    $('#currency_rate').val(parseFloat(currency_rate));
                    cleaves_id("currency_rate",5);
                    //update_rate();
                    //$('#currency_rate').attr("disabled","disabled");
                    
                    
                    $('#debit_note_note').val(note);
                    
                    $('#add_btn').html("Update");
                    $(".sk-circle-layer").hide();
                    
 
                    $('#bank_source').selectpicker('val', bank_id);
                    $('#bank_source').selectpicker('refresh');
                    
                    //$('#pi_id').attr('disabled','disabled');
                    
                    $('#reference').val(reference);
                    $('#payment_owner').val(payment_owner);
                    
                    //(".mask_format").trigger('input');
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
                            add_item_to_debit_note(id,current.id);
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
            $('#debitnote_table_ tfoot th').each( function () {
                if(jQuery.inArray(index, search_fields) !== -1){
                    var title = $(this).text();
                    $(this).html( '<div class="inner-addon left-addon"><input id="idf_'+index+'" style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder="'+title+'" /></div>' );
                    index++;
                }
            });
            
            var debitnote_table_ = $('#debitnote_table_').dataTable({
                ajax: "?r=debit_note&f=get_debit_note_details&p0="+id,
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
                    { "targets": [9], "searchable": true, "orderable": false, "visible": true },
                ],
                scrollY: '45vh',
                scrollCollapse: true,
                paging: false,
                order: [[ 0, "asc" ]],
                dom: '<"toolbar_dbn">frtip',
                initComplete: function( settings ) {
                    //var table = $('#debitnote_table_').DataTable();
                    //table.row(':eq(0)', { page: 'current' }).select();

                    cleaves_class(".clv",5);
                    calculation_total_dn_value();

                    $(".sk-circle-layer").hide();
                },
                fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    $(nRow).addClass(aData[0]);
                },
                //fnDrawCallback: updateGroupRows_,
            });
            submit_debit_note_on_the_fly(src,id);
        });

        $('#debit_noteModal').on('hide.bs.modal', function (e) {
            $("#debit_noteModal").remove();
        });

        $('#debit_noteModal').modal('show');
    });
}

function dn_qty_changed(id){

    $.getJSON("?r=debit_note&f=dn_qty_changed&p0="+id+"&p1="+$("#dn_qty_"+id).val().replace(/[^0-9\.]/g, ''), function (data) {

    }).done(function () {
        $("#dn_total_"+id).val($("#dn_qty_"+id).val().replace(/[^0-9\.]/g, '')*$("#dn_price_"+id).val().replace(/[^0-9\.]/g, ''));
        cleaves_id("dn_total_"+id,5);
        calculation_total_dn_value();
    });
}

function dn_price_changed(id){
     $.getJSON("?r=debit_note&f=dn_price_changed&p0="+id+"&p1="+$("#dn_price_"+id).val().replace(/[^0-9\.]/g, ''), function (data) {

    }).done(function () {
        $("#dn_total_"+id).val($("#dn_qty_"+id).val().replace(/[^0-9\.]/g, '')*$("#dn_price_"+id).val().replace(/[^0-9\.]/g, ''));
        cleaves_id("dn_total_"+id,5);
        calculation_total_dn_value();
    });
}

function calculation_total_dn_value(){
    if($('#auto_sum').val()==0){
        return;
    }
    var t = 0;
    $(".cntpi").each(function( index ) {
        t+=parseFloat($(this).val().replace(/[^0-9\.]/g, ''));
    });
    $("#debit_value").val(t);
    cleaves_id("debit_value",5);
}

function add_item_to_debit_note(debit_note_id,item_id){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    $.getJSON("?r=debit_note&f=add_item_to_debit_note&p0="+debit_note_id+"&p1="+item_id, function (data) {

    }).done(function () {
        $('#debitnote_table_').DataTable().ajax.url("?r=debit_note&f=get_debit_note_details&p0="+debit_note_id).load(function () {
            $('#debitnote_table_').DataTable().page('last').draw(false);
            cleaves_class(".clv",5);
            calculation_total_dn_value();
            $(".sk-circle-layer").hide();
        }, false);
    });
}


function submit_debit_note_on_the_fly(src,id){
    $("#add_new_debit_note_on_the_fly_form").on('submit', (function (e) {
        e.preventDefault();
        
        if($('#supplier_id').val()==0){
            swal("Select Supplier first");
            return;
        }
        
        $(".sk-circle-layer").show();
        $(".debit_return_qty").each(function( index ) {
            var id_rec = $(this).attr("id").split('_');
            pi_return(id_rec[1]);
        });
    
    
        $("#debit_value").val($("#debit_value").val().replace(/,/g , ''));
        $.ajax({
            url: "?r=debit_note&f=add_debit_note",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            success: function (data)
            {
                $('#debit_noteModal').modal('hide');
                $(".sk-circle-layer").show();
                if(src == "debit_note"){
                    var table = $('#debit_notes_table').DataTable();
                    if(id==0){
                        table.ajax.url("?r=debit_note&f=get_debit_notes&p0="+$("#salesDate").val()).load(function () {
                            table.page('last').draw(false);
                            table.row(':last', {page: 'current'}).select();
                             $(".sk-circle-layer").hide();
                        }, false);
                    }else{
                        table.ajax.url("?r=debit_note&f=get_debit_notes&p0="+$("#salesDate").val()).load(function () {
                            table.row('.' + pad_debit_not(id), {page: 'current'}).select();
                            $(".sk-circle-layer").hide();
                        }, false);
                    }
                    
                }
                
                if(src == "pos"){
                    $(".sk-circle-layer").hide();
                }
            }
        });
    }));
}

function submit_debit_note(src,id){
    $("#add_new_debit_note_form").on('submit', (function (e) {
        e.preventDefault();
        $(".sk-circle-layer").show();
        $(".debit_return_qty").each(function( index ) {
            var id_rec = $(this).attr("id").split('_');
            pi_return(id_rec[1]);
        });
    
        $("#debit_value").val($("#debit_value").val().replace(/,/g , ''));
        $("#currency_rate").val($("#currency_rate").val().replace(/,/g , ''));
        $.ajax({
            url: "?r=debit_note&f=add_debit_note",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            success: function (data)
            {
                $('#debit_noteModal').modal('hide');
                $(".sk-circle-layer").show();
                if(src == "debit_note"){
                    var table = $('#debit_notes_table').DataTable();
                    if(id==0){
                        table.ajax.url("?r=debit_note&f=get_debit_notes&p0="+$("#salesDate").val()).load(function () {
                            table.page('last').draw(false);
                            table.row(':last', {page: 'current'}).select();
                             $(".sk-circle-layer").hide();
                        }, false);
                    }else{
                        table.ajax.url("?r=debit_note&f=get_debit_notes&p0="+$("#salesDate").val()).load(function () {
                            table.row('.' + pad_debit_not(id), {page: 'current'}).select();
                            $(".sk-circle-layer").hide();
                        }, false);
                    } 
                }
                
                if(src == "pos"){
                    $(".sk-circle-layer").hide();
                }
            }
        });
    }));
}
