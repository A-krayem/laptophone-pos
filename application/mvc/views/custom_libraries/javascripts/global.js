decimal_round = function(number, precision) {
    var factor = Math.pow(10, precision);
    var tempNumber = number * factor;
    var roundedTempNumber = Math.round(tempNumber);
    return roundedTempNumber / factor;
};

function sms_sub(){
 
    $.confirm({
        title: '<b>SMS!</b>',
        content: 'Send SMS to your client with amazing package prices and top accuracy. <br/><br/><b>For more information, reach out to our support team.</b>',
        buttons: {
            somethingElse: {
                text: 'Close',
                btnClass: 'btn-blue',
                action: function(){
                    
                }
            }
        }
    });

}

function oilnk(item_id){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var _data=[];
    $.getJSON("?r=items&f=oilnk&p0="+item_id, function (data) {
       _data=data;
    }).done(function () {
        $(".sk-circle-layer").hide();
        window.open(_data.link, '_blank');
    });
}

function e_commerce_webiste(){

    $.confirm({
        title: '<b>E-COMMERCE WEBSITE!</b>',
        content: 'Start your E-Commerce journey now.<br/><br/>To explore a demo website, <a href="" target="_blank">CLICK HERE</a>.<br/><br/><b>For more information, reach out to our support team.',
        buttons: {
            somethingElse: {
                text: 'Close',
                btnClass: 'btn-blue',
                action: function(){
                    
                }
            }
        }
    });
}

function check_invoice_if_free_items(){
    var table = $('#newinvoice_table').DataTable();
    table.rows().every(function() {
        
     
        var data = this.data();
        var cells = this.nodes().to$().find('td');
      
        var it_id=parseFloat($(cells[0]).html().split('-')[1]);


       if($(".itid_"+it_id).val()==0){
            $(cells[0]).css('color', 'red');
            $(cells[1]).css('color', 'red');
            $(cells[2]).css('color', 'red');
            $(cells[3]).css('color', 'red');
        }else{
            $(cells[0]).css('color', 'black');
            $(cells[1]).css('color', 'black');
            $(cells[2]).css('color', 'black');   
            $(cells[3]).css('color', 'black');   
        }
    });
}


function mask_variable(val_,format){
    $('<input>').attr({
        type: 'hidden',
        id: 'mask_variable_tmp',
        value: val_
    }).appendTo('body');
    $("#mask_variable_tmp").mask(format);
    var tmp = $("#mask_variable_tmp").val();
    $("#mask_variable_tmp").remove();
    return tmp;
}

function backupNow(){
    var status = null;

    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var bkswal = swal("Starting backup, please wait");
    
    $.getJSON("?r=dashboard&f=backupNow", function (data) {
        status = data.status;
    }).done(function () {
        $(".sk-circle-layer").hide();
        swal.close(); 
    });
}

function monitor_pos_items(item_id,qty){
    $.getJSON("?r=pos&f=monitor_pos_items_adv&p0="+item_id+"&p1="+qty, function (data) {
        
    }).done(function () {
        
    });
}

function _print_customer_statement(c_id,cur_id,full){
    w=window.open('?r=customers&f=print_statement&p0='+c_id+"&p1="+cur_id+"&p2="+full); 
}

function printing_customer_statement(id){
    w=window.open('?r=printing&f=print_customer_statement&p0='+id); 
}

function printing_customer_statement_date_range(id,datepicker){
    w=window.open('?r=printing&f=print_customer_statement&p0='+id+"&p1="+datepicker); 
}

function printing_customer_statement_date_range__(id,datepicker){
    w=window.open('?r=printing&f=print_customer_statement__&p0='+id+"&p1="+datepicker); 
}

function open_stmt(id,datepicker){
    w=window.open('?r=printing&f=print_delivery_statement__&p0='+id+"&p1="+datepicker); 
}



function _print_suppliers_statement(){
    print_suppliers_statement($("#suppliers_list").val());
}

function print_suppliers_statement(id){
    w=window.open('?r=printing&f=print_suppliers_statement&p0='+id); 
}

function print_suppliers_all_pi(){
    
    w=window.open('?r=printing&f=print_suppliers_all_pi&p0='+$("#suppliers_list").val()+'&p1='+$("#pdatepicker").val()); 
}

function print_identities(){
    w=window.open('?r=customers&f=print_identities&p0='+$("#id_to_edit").val());
}

function print_identities_customer_id(id){
    w=window.open('?r=customers&f=print_identities&p0='+id);
}

function print_identities_1_customer_id(id){
    w=window.open('?r=customers&f=print_identities_1&p0='+id);
}

function print_identities_2_customer_id(id){
    w=window.open('?r=customers&f=print_identities_2&p0='+id);
}


function print_customer_statement(c_id,full){
    $(".sk-circle-layer").show();
    var currencies = "";
    var _data = [];
    $.getJSON("?r=settings_info&f=get_info", function (data) {
        _data = data.currencies;
        $.each(data.currencies, function (key, val) {
            currencies+="<div onclick='_print_customer_statement("+c_id+","+val.id+","+full+")' class='col-lg-6 col-md-6 col-sm6 col-xs-6' style='background-color:gray; height:35px; color:#fff; font-size:25px; text-align:center; cursor:pointer;'>"+val.name+" ("+val.symbole+")</div>";
        });
    }).done(function () {
        $(".sk-circle-layer").hide();
        if(_data.length>1){
            var content =
            '<div class="modal" id="print_cur" role="dialog" >\n\
                <div class="modal-dialog" role="document">\n\
                    <div class="modal-content">\n\
                        <div class="modal-body">\n\
                            <div class="row">\n\
                            '+currencies+'\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
            </div>';
            $("#print_cur").remove();
            $("body").append(content);
            $('#print_cur').on('show.bs.modal', function (e) {

            });

            $('#print_cur').on('shown.bs.modal', function (e) {

            });

            $('#print_cur').on('hide.bs.modal', function (e) {
                $("#print_cur").remove();
            });
            $('#print_cur').modal('show');
        }else{
            _print_customer_statement(c_id,0,0);
        }
    });
}

function print_payment_receipt_customer_payment(id){
     w=window.open('?r=customers&f=print_payment_receipt_customer_payment&p0='+id); 
}

    function _print_sheet(invoice_id,cur_id){
        w=window.open('?r=invoice&f=print_invoice&p0='+invoice_id+"&p1="+cur_id);
        $('#print_cur').modal('hide');
    }
    
    function print_on_pos_printer(invoice_id){
        var width = 500;
                    var height = 600;
                    var left = (screen.width - width) / 2;
                    var top = (screen.height - height) / 2;
        window.open("?r=printing&f=print_invoice&p0=" + invoice_id+ "&p1=0", '_blank', 'width=' + width + ', height=' + height + ', left=' + left + ', top=' + top);
    }
    
    function print_sheet_pdf(invoice_id){
        $(".sk-circle-layer").show();
        var _data=[];
        $.getJSON("?r=print_invoice&f=prepare_pdf_version&p0="+invoice_id, function (data) {
            _data=data;
        }).done(function () {
            $(".sk-circle-layer").hide();
            w=window.open(_data[0]);
        });
    }
    
    function print_sheet(invoice_id){
        if(print_a4_pdf_version==1){
            print_sheet_pdf(invoice_id);
            return;
        }
        $(".sk-circle-layer").show();
        var currencies = "";
        var _data = [];
        var _a4_print_style = 0;
        $.getJSON("?r=settings_info&f=get_info", function (data) {
            _data = data.currencies;
            _a4_print_style = data.a4_print_style;
            $.each(data.currencies, function (key, val) {
                currencies+="<div onclick='_print_sheet("+invoice_id+","+val.id+")' class='col-lg-4 col-md-4 col-sm-4 col-xs-4' style='background-color:gray;margin:5px; height:50px; color:#fff; font-size:25px; text-align:center; cursor:pointer;'>"+val.name+" ("+val.symbole+")</div>";
            });
        }).done(function () {
            $(".sk-circle-layer").hide();
            
            if(_a4_print_style>2){
                _print_sheet(invoice_id,1);
            }else{
                if(_data.length>1){
                    var content =
                    '<div class="modal" id="print_cur" role="dialog" >\n\
                        <div class="modal-dialog" role="document">\n\
                            <div class="modal-content">\n\
                                <div class="modal-body">\n\
                                    <div class="row">\n\
                                    '+currencies+'\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>';
                    $("#print_cur").remove();
                    $("body").append(content);
                    $('#print_cur').on('show.bs.modal', function (e) {

                    });

                    $('#print_cur').on('shown.bs.modal', function (e) {

                    });

                    $('#print_cur').on('hide.bs.modal', function (e) {
                        $("#print_cur").remove();
                    });
                    $('#print_cur').modal('show');
                }else{
                    _print_sheet(invoice_id,0);
                }
            }
            
        });
        
    }
    

function pad(num, size) {
    var s = num + "";
    while (s.length < size)
        s = "0" + s;
    return "SUP-" + s;
}

function padItem(num) {
    var s = num + "";
    while (s.length < 6)
        s = "0" + s;
    return "IT-" + s;
}

function pad_transfer(num) {
    var s = num + "";
    while (s.length < 7)
        s = "0" + s;
    return "TRANS-" + s;
}

function pad_customer_card(num) {
    var s = num + "";
    while (s.length < 7)
        s = "0" + s;
    return "GCC-" + s;
}




function PadINVIT(num) {
    var s = num + "";
    while (s.length < 7)
        s = "0" + s;
    return "INVIT-" + s;
}

function pad_employee(num) {
    var s = num + "";
    while (s.length < 4)
        s = "0" + s;
    return "EMP-" + s;
}

function PadSUPPAY(num) {
    var s = num + "";
    while (s.length < 7)
        s = "0" + s;
    return "SUPPAY-" + s;
}

function pad_sysuser(num) {
    var s = num + "";
    while (s.length < 5)
        s = "0" + s;
    return "SYSUSER-" + s;
}

function pad_shkrinkage(num) {
    var s = num + "";
    while (s.length < 7)
        s = "0" + s;
    return "SHAGE-" + s;
}


function pad_credit_not(num) {
    var s = num + "";
    while (s.length < 7)
        s = "0" + s;
    return "CN-" + s;
}

function pad_debit_not(num) {
    var s = num + "";
    while (s.length < 7)
        s = "0" + s;
    return "DN-" + s;
}

function PadSTKINV(num) {
    var s = num + "";
    while (s.length < 7)
        s = "0" + s;
    return "PINV-" + s;
}

function padExpenses(num) {
    var s = num + "";
    while (s.length < 5)
        s = "0" + s;
    return "EXP-" + s;
}

function padDiscount(num) {
    var s = num + "";
    while (s.length < 5)
        s = "0" + s;
    return "DIS-" + s;
}

function padMobilePKG(num){
     var s = num + "";
    while (s.length < 4)
        s = "0" + s;
    return "MBPKG-" + s;
}

function padMobileDEV(num){
     var s = num + "";
    while (s.length < 4)
        s = "0" + s;
    return "MBDEV-" + s;
}

function pad_cat(num) {
    var s = num + "";
    while (s.length < 5)
        s = "0" + s;
    return "SUBCAT-" + s;
}

function pad_parentcat(num) {
    var s = num + "";
    while (s.length < 5)
        s = "0" + s;
    return "CAT-" + s;
}


function pad_wh(num) {
    var s = num + "";
    while (s.length < 5)
        s = "0" + s;
    return "WH-" + s;
}


function pad_invoice(num) {
    var s = num + "";
    while (s.length < 7)
        s = "0" + s;
    return "INV-" + s;
}

function pad_customer(num) {
    var s = num + "";
    while (s.length < 7)
        s = "0" + s;
    return "CUS-" + s;
}

function padTable(num){
    var s = num + "";
    while (s.length < 3)
        s = "0" + s;
    return "RSTB-" + s;
}

function mask_value_format(){
    if(TO_FIXED_ROUND==0){
        return "#,##0";
    }
    var format = "#,##0.";
    for(var i=0;i<TO_FIXED_ROUND;i++){
        format+="0";
    }
    return format;
}

function mask_clean(val){
    $("#to_mask").remove();
    if(val==""){
        val = 0;
    }
    $("body").append("<input type='hidden' id='to_mask' value='' />");
    $("#to_mask").val(val);
    return parseFloat($("#to_mask").val().replace(/[^0-9\.]/g, ''));
    //return parseFloat(parseNumberCustom($("#tmp").val()));
}

//$(".mask").mask(mask_value_format(), { reverse: true });

function format_price_pos(price){
    $("#to_mask").remove();
    $("body").append("<input type='hidden' id='to_mask' value='"+parseFloat(price)+"' />");
    cleaves_id("to_mask",5);
    //$("#to_mask").mask(mask_value_format(), { reverse: true });
    return $("#to_mask").val();
}

function format_price(price){
    return accounting.formatMoney(price, { symbol: default_currency_symbol,  format: "%v %s" });
    //return price.toFixed(2).toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")+" "+default_currency_symbol;
}

function format_price_already_fixed(price){
    return accounting.formatMoney(price, { symbol: default_currency_symbol,  format: "%v %s" });
    //return price.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")+" "+default_currency_symbol;
}

function generate_pdf_invoice(id){
    $.getJSON("?r=reports_generator&f=generate_invoice&p0="+id, function (data) {
    }).done(function () {
        swal("Invoice Generated");
    }).fail(function() {
    });
}

function generate_pdf_creditnote(id){
    $.getJSON("?r=reports_generator&f=generate_creditnote&p0="+id, function (data) {
    }).done(function () {
        swal("Credit Note Generated");
    }).fail(function() {
    });
}

function emptyInput(id) {
    if ($("#" + id).val() == "") {
        $("#" + id).addClass("error");
        return true;
    } else {
        $("#" + id).removeClass("error");
        return false;
    }
}

function maskValue(inputID){
    return $('#'+inputID).mask("#.##0", {reverse: true});
}

function unMaskValue(inputID){
    $("#"+inputID).unmask("#.##0");
}

function sync(store_id){
        //$(".lds-dual-ring").center();
        //$(".blocker_panel").show();
        //$(".lds-dual-ring").show();
        
        
        if($("#ssyn").length>0){
            $("#ssyn").html("Syncing...");
        }else{
            $(".sk-circle").center();
            $(".sk-circle-layer").show();
        }
        
        
        
        
        var _dt = [];
        $.getJSON("?r=sync&f=sync_&p0="+store_id, function (data) {
            _dt=data;
            
        }).done(function () {
            
            if($("#ssyn").length>0){
                if(_dt[0]!="0"){
                    $("#ssyn").html('Sync Error <i onclick="sync('+store_id+')" style="font-size:14px;cursor:pointer" title="retry sync" class="glyphicon glyphicon-refresh""></i>');
                }else{
                    $("#ssyn").html('&nbsp;<i onclick="sync('+store_id+')" style="font-size:14px;cursor:pointer;color:green" title="Sync data" class="glyphicon glyphicon-refresh""></i>');
                }
            }else{
                $(".sk-circle-layer").hide();
            }
            
            
            if($("#items_table").length>0){
                updateTableQty("items_table","?r=items&f=getAllItems&p0="+current_store_id+"&p1="+current_category_id+"&p2="+current_subcategory_id+"&p3="+item_boxex+"&p4="+current_supplier_id+"&p5="+$("#stock_status").val(),1);
            }
        }); 
    }

(function($){
    $.fn.disableSelection = function() {
        return this
                 .attr('unselectable', 'on')
                 .css('user-select', 'none')
                 .on('selectstart', false);
    };
})(jQuery);

jQuery.fn.center = function () {
    this.css("position","absolute");
    //this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) + $(window).scrollTop()) + "px");
    this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + $(window).scrollLeft()) + "px");
    return this;
};

jQuery.fn.centerWH = function () {
    this.css("position","absolute");
    this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) + $(window).scrollTop()) + "px");
    this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + $(window).scrollLeft()) + "px");
    return this;
};


function show_expired(){
    $(".sk-circle-layer").show();
    var content =
    '<div class="modal" id="expired_items_modal" role="dialog" >\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title">Expired<i style="float:right;font-size:35px" class="glyphicon glyphicon-remove" onclick="closeModal(\'expired_items_modal\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <table id="expired_items_table" class="table table-striped table-bordered" cellspacing="0" >\n\
                                <thead>\n\
                                    <tr>\n\
                                        <th style="width: 60px !important;">Items ID</th>\n\
                                        <th>Description</th>\n\
                                        <th style="width: 140px !important;">Expiry Date</th>\n\
                                        <th style="width: 140px !important;">Will Expire After</th>\n\
                                        <th style="width: 140px !important;">Qty</th>\n\
                                    </tr>\n\
                                </thead>\n\
                                <tbody></tbody>\n\
                            </table>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>\n\
    </div>';
    $("#expired_items_modal").remove();
    $("body").append(content);
    $('#expired_items_modal').on('show.bs.modal', function (e) {

    });

    $('#expired_items_modal').on('shown.bs.modal', function (e) {
        var search_fields = [];
        var index = 0;
        $('#expired_items_table tfoot th').each( function () {
            if(jQuery.inArray(index, search_fields) !== -1){
                var title = $(this).text();
                $(this).html( '<div class="inner-addon left-addon"><input id="idf_'+index+'" style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder="'+title+'" /></div>' );
                index++;
            }
        });

        var expired_items_table_ = $('#expired_items_table').dataTable({
            ajax: "?r=reports&f=getExpiredStockReportDetails",
            responsive: true,
            orderCellsTop: true,
            bLengthChange: true,
            iDisplayLength: 50,
            ordering:false,
            aoColumnDefs: [
                { "targets": [0], "searchable": true, "orderable": false, "visible": true },
                { "targets": [1], "searchable": true, "orderable": false, "visible": true },
                { "targets": [2], "searchable": true, "orderable": false, "visible": true },
                { "targets": [3], "searchable": true, "orderable": false, "visible": true },
                { "targets": [4], "searchable": true, "orderable": false, "visible": true },
            ],
            scrollY: '35vh',
            scrollCollapse: true,
            paging: false,
            order: [[ 0, "asc" ]],
            dom: '<"toolbar_dbn">frtip',
            initComplete: function( settings ) {
                $(".sk-circle-layer").hide();
            },
            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $(nRow).addClass(aData[0]);
            },
            //fnDrawCallback: updateGroupRows_,
        });
    });

    $('#expired_items_modal').on('hide.bs.modal', function (e) {
        $("#expired_items_modal").remove();
    });
    $('#expired_items_modal').modal('show');
}

function modal_close__(id){
    $('#'+id).modal('toggle');
}

function create_manual_invoice(){
    swal({
        title: "Are you sure?",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes",
        closeOnConfirm: true
    },
    function(isConfirm){
       if(isConfirm){
           
            $.getJSON("?r=invoice&f=generateInvoiceId", function (data) {
                window.open('?r=invoice&f=create_invoice_manual&p0='+data.invoice_id, '_blank');
            }).done(function () {
                
            });
       }
    }); 
}

function cleaves_class(class_name,decimal){
    for(let field of $(class_name).toArray()){
        new Cleave(field, {
            numeral: true,
            numeralDecimalScale: decimal,
            stripLeadingZeroes: true
        });
    }
}

function cleaves_id(id,decimal){
    new Cleave('#'+id, {
        numeral: true,
        numeralDecimalScale: decimal,
        stripLeadingZeroes: true
    });
}

function re_tr(id){
    $.getJSON("?r=items&f=re_tr&p0="+id, function (data) {
        
    }).done(function () {
        alert("Done");
    });
}

var tmo = null;
function search_in_datatable_global(val,index,delay,table_name){
    clearTimeout(tmo);
    tmo = setTimeout(function(){
        $('#'+table_name).DataTable().columns(index).search(val).draw();
    },delay); 
}

function search_invoice_notes(){
    if($('#search_invoice_notes').val()==""){
        swal("Invoice Note field is empty");
        return;
    }
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var content =
    '<div class="modal large" data-backdrop="static" id="invoices_searchbynoteModal" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title">Invoices<i style="float:right;font-size:35px" class="glyphicon glyphicon-remove" onclick="closeModal(\'invoices_searchbynoteModal\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body" id="noBarcodeItems">\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <table id="invoices_search_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                <thead>\n\
                                    <tr>\n\
                                        <th style="width: 70px !important;">Ref.</th>\n\
                                        <th style="width: 130px !important;">Date</th>\n\
                                        <th style="width: 200px !important;">Customer name</th>\n\
                                        <th style="width: 90px !important;">Total</th>\n\
                                        <th >Note</th>\n\
                                    </tr>\n\
                                </thead>\n\
                                <tfoot>\n\
                                    <tr>\n\
                                        <th>Ref.</th>\n\
                                        <th>Date</th>\n\
                                        <th>Customer name</th>\n\
                                        <th>Total</th>\n\
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
    $("#invoices_searchbynoteModal").remove();
    $("body").append(content);
    $('#invoices_searchbynoteModal').on('show.bs.modal', function (e) {
        
        var items_search = null;
        var search_fields = [0,1,2,3,4];
        var index = 0;
        $('#invoices_search_table tfoot th').each( function () {
            if(jQuery.inArray(index, search_fields) !== -1){
                var title = $(this).text();
                $(this).html( '<div class="inner-addon no-left-addon"><input style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder=" '+title+'" /></div>' );
                index++;
            }
        });           
        items_search = $('#invoices_search_table').DataTable({
            ajax: {
                url: "?r=dashboard&f=search_invoices_&p0="+$('#search_invoice_notes').val(),
                type: 'POST',
                error:function(xhr,status,error) {
                    logged_out_warning();
                },
            },
            orderCellsTop: true,
            aoColumnDefs: [
                { "targets": [0], "searchable": true, "orderable": true, "visible": true },
                { "targets": [1], "searchable": true, "orderable": true, "visible": true },
                { "targets": [2], "searchable": true, "orderable": true, "visible": true },
                { "targets": [3], "searchable": true, "orderable": true, "visible": true },
                { "targets": [4], "searchable": true, "orderable": true, "visible": true },
            ],
            scrollCollapse: true,
            paging: true,
            initComplete: function(settings, json) {                
                $(".sk-circle-layer").hide();
            },
            //fnDrawCallback: updateRows_invoices,
        });
        
        $('#invoices_search_table').DataTable().on('dblclick',"tr", function ( e, dt, type, indexes ) {
            var sdata = items_search.row('.selected', 0).data();
            return_items(parseInt(sdata[0].split("-")[1]));
        });

        $('#invoices_search_table').on('key-focus.dt', function(e, datatable, cell){
            $(items_search.row(cell.index().row).node()).addClass('selected');
        });

        $('#invoices_search_table').on('key-blur.dt', function(e, datatable, cell){
            $(items_search.row(cell.index().row).node()).removeClass('selected');
        });

        $('#invoices_search_table').on('key.dt', function(e, datatable, key, cell, originalEvent){
            if(key === 13){
                 //var sdata = items_search.row('.selected', 0).data();
                //returnQty(parseInt(sdata[0].split("-")[1]));
            }
        });
        
        $('#invoices_search_table').DataTable().columns().every( function () {
            var that = this;
            $( 'input', this.footer() ).on( 'keyup change', function () {
                items_search.keys.disable();
                if ( that.search() !== this.value ) {
                    that.search( this.value ).draw();
                }
                items_search.keys.enable();
            } );
        } );
    });
    
    var start = moment().subtract(29, 'days');
    var end = moment();
    
    $('#invoices_searchbynoteModal').on('shown.bs.modal', function (e) {
        
    });
    
    $('#invoices_searchbynoteModal').on('hide.bs.modal', function (e) {
        $("#invoices_searchbynoteModal").remove();
    });
    $('#invoices_searchbynoteModal').modal('show');
}

