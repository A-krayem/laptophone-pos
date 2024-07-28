var original_inv_data = [];

function show_invoice_to_change(invoice_id){
    if(enable_edit_invoice_password==1){
        setTimeout(function(){
            
           swal({
                title: "Enter Password",
                html: true ,
                text: '<input style="z-index:999999999999" class="form-control" value="" type="password" id="inv_pass"/>',
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Ok",
                cancelButtonText: "Cancel",
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function(isConfirm) {
                if (isConfirm) {
                    if($("#inv_pass").val()==edit_invoice_password){
                        __show_invoice_to_change(invoice_id);
                    }else{
                        alert("Password Incorrect");
                    }
                }
            });
            setTimeout(function(){ $("#inv_pass").focus(); },500); 
            
        },200);
        
    }else{
        __show_invoice_to_change(invoice_id);
    }
    
}

function __show_invoice_to_change(invoice_id){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var _data=[];
    $.getJSON("?r=pos&f=get_invoice_with_all_details&p0="+invoice_id, function (data) {
        original_inv_data = data;
        _data=data;
    }).done(function () {
        if(_data.notfound==0){
            _show_invoice_to_change(original_inv_data);
        }else{
            $(".sk-circle-layer").hide();
        }
    }).fail(function() {
        logged_out_warning();
    }).always(function() {
        
    });
}

function get_total_of_new_items(){
    var total = 0;
    var final = 0;
    $('#return_show_invoice_to_change_table').DataTable().rows().eq(0).each( function ( index ) {
        var row = $('#return_show_invoice_to_change_table').DataTable().row(index);
        var data = row.data();
        total = 0;
        //total+=parseFloat($("#price_inv_ch_"+data[0]).val()*$("#qty_"+data[0]).val())*(1-(parseFloat($("#disc_inv_ch_"+data[0]).val())/100));
        total+=precisionRound(($("#qty_"+data[0]).val()*$("#price_inv_ch_"+data[0]).val().replace(/,/g , '')*(1-$("#disc_inv_ch_"+data[0]).val().replace(/,/g , '')/100))*parseFloat(1+$("#vat_inv_ch_"+data[0]).html()/100),round_val);
        final+=total;
    } );
    $('#total_new_invoice_price').html(final);

    return final;
}


function get_difference(){
    var total = precisionRound(get_original_total_without_return_as_flag()-get_total_of_new_items(),round_val);


    $('body').append("<input id='tmp_input' value='"+(parseFloat(total)+parseFloat($('#manual_discount_d').val()))+"' type='hidden' />");
    cleaves_id("tmp_input",number_of_decimal_points);
    $('#difference_inv').html($('#tmp_input').val());
    $("#tmp_input").remove();
    
    $("#cash_usd").val(0);
    
    
    $("#cash_usd").val(0);
    $("#cash_lbp").val(0);
    $("#r_cash_usd_action").val(0);
    $("#r_cash_lbp_action").val(0);
    $("#r_cash_usd").val(0);
    $("#r_cash_lbp").val(0);
    
    cash_changed_usd($("#cash_usd"));
    
    return total;
}

function get_original_total_without_return_as_flag(){
    var original_total = 0;
    var tmp = 0;
    for(var i=0;i<original_inv_data.invoice_details.length;i++){
        
        if(parseFloat($("#qty_"+original_inv_data.invoice_details[i].id).val())>parseFloat($("#old_qty_"+original_inv_data.invoice_details[i].id).html())){
            $("#qty_"+original_inv_data.invoice_details[i].id).val(parseFloat($("#old_qty_"+original_inv_data.invoice_details[i].id).html()));
        }
        
        if(original_inv_data.invoice_details[i].discount<0){
            tmp+=original_inv_data.invoice_details[i].selling_price*parseFloat($("#qty_"+original_inv_data.invoice_details[i].id).val());
            //tmp+= parseFloat((original_inv_data.invoice_details[i].selling_price)*(parseFloat($("#qty_"+original_inv_data.invoice_details[i].id).val())))/(1-(parseFloat(Math.abs(original_inv_data.invoice_details[i].discount))/100));
        }else{
            tmp+= parseFloat(original_inv_data.invoice_details[i].selling_price)*(parseFloat($("#qty_"+original_inv_data.invoice_details[i].id).val()))*(1-(parseFloat(original_inv_data.invoice_details[i].discount)/100));
        }
        
        if(parseFloat(original_inv_data.invoice_details[i].vat)==1){
            tmp=tmp*parseFloat(original_inv_data.invoice_details[i].vat_value);
        }
        original_total+=tmp;
        tmp = 0;
    }
    return original_total;
}

function get_original_total(){
    var original_total = 0;
    var tmp = 0;
    for(var i=0;i<original_inv_data.invoice_details.length;i++){
        tmp+= parseFloat(original_inv_data.invoice_details[i].selling_price)*original_inv_data.invoice_details[i].qty*(1-(parseFloat(original_inv_data.invoice_details[i].discount)/100));
        if(parseFloat(original_inv_data.invoice_details[i].vat)==1){
            tmp=tmp*parseFloat(original_inv_data.invoice_details[i].vat_value);
        }
        original_total+=tmp;
        tmp = 0;
    }
    return original_total;
}

function delete_row_item(){
    $('.glyphicon-trash').on( 'click', function () {
            $('#return_show_invoice_to_change_table tbody').DataTable()
            .row( $(this).parents('tr') )
            .remove()
            .draw();
    } );
    get_total_of_new_items();
    get_difference();
}

function discount_change_new_inv(id){
    $("#price_inv_ch_"+id).val($("#hidden_price_inv_ch_"+id).val()*(1-$("#disc_inv_ch_"+id).val()/100));
    cleaves_id("price_inv_ch_"+id,number_of_decimal_points);
}

function calculate_finale_prince_inv_changed(id){
    var tt = $("#qty_"+id).val()*$("#price_inv_ch_"+id).val().replace(/,/g , '')*(1-$("#disc_inv_ch_"+id).val().replace(/,/g , '')/100)*parseFloat(1+$("#vat_inv_ch_"+id).html()/100);
    $("#total_inv_ch_"+id).html(precisionRound(tt,round_val));

    get_total_of_new_items();
    get_difference();
}

function discounts_history(id){
    var content =
    '<div class="modal" data-backdrop="static" id="history_discount_Modal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title" '+dir_+'>Discounts History<i style="float:'+float_+';font-size:35px" class="glyphicon glyphicon-remove" onclick="closeModal(\'history_discount_Modal\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body" id="noBarcodeItems">\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <table id="history_discount_table__" class="table table-striped table-bordered" cellspacing="0">\n\
                                <thead>\n\
                                    <tr>\n\
                                        <th style="width: 60px;">Item ID</th>\n\
                                        <th>Discount Description</th>\n\
                                        <th style="width: 210px;">Start Date</th>\n\
                                        <th style="width: 210px;">End Date</th>\n\
                                        <th style="width: 100px;">Original Price</th>\n\
                                        <th style="width: 40px;">Discount</th>\n\
                                        <th style="width: 40px;">TAX</th>\n\
                                        <th style="width: 100px;">Final Price</th>\n\
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
 
    $('#history_discount_Modal').modal('hide');
    $("body").append(content);
    $('#history_discount_Modal').on('show.bs.modal', function (e) {

    });
    
    $('#history_discount_Modal').on('shown.bs.modal', function (e) {
        $('#quick_stock_table__').show();
        
        var history_discount_table__ =null;
        
        var search_fields = [0,1,2,3,4,5,6,7];
        var index = 0;
        $('#history_discount_table__ tfoot th').each( function () {

            if(jQuery.inArray(index, search_fields) !== -1){
                var title = $(this).text();
                $(this).html( '<div class="inner-addon left-addon"><input id="idf_'+index+'" style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder="'+title+'" /></div>' );
                index++;
            }
        });

        history_discount_table__ = $('#history_discount_table__').DataTable({
            ajax: {
                url: "?r=pos&f=getHistoryDiscounts&p0="+id,
                type: 'POST',
                error:function(xhr,status,error) {
                    //logged_out_warning();
                },
            },
            orderCellsTop: true,
            iDisplayLength: 50,
            ordering: false,
            scrollY: '45vh',
            aoColumnDefs: [
                { "targets": [0], "searchable": true, "orderable": false, "visible": true },
                { "targets": [1], "searchable": true, "orderable": false, "visible": true },
                { "targets": [2], "searchable": true, "orderable": false, "visible": true },
                { "targets": [3], "searchable": true, "orderable": false, "visible": true },
                { "targets": [4], "searchable": true, "orderable": false, "visible": true },
                { "targets": [5], "searchable": true, "orderable": false, "visible": true },
                { "targets": [6], "searchable": true, "orderable": false, "visible": true },
                { "targets": [7], "searchable": true, "orderable": false, "visible": true },
            ],
            scrollCollapse: true,
            paging: true,
            initComplete: function(settings, json) {
                //$(history_discount_table__.row(1)).addClass('selected');
            },
            
        });

        
         $('#quick_stock_table__').DataTable().on('mousedown',"tbody tr", function ( e, dt, type, indexes ) { 
            $('.selected').removeClass("selected");
            $(this).addClass('selected');
         });
        
    });
    $('#history_discount_Modal').on('hide.bs.modal', function (e) {
        $("#history_discount_Modal").remove();
    });
    $('#history_discount_Modal').modal('show');
}



function search_barcode_iv_changed(item_id){
    if($("#search_barcode_iv").val().length>0 || item_id>0){
        $.getJSON("?r=pos&f=get_item_by_barcode_for_change&p0=" + encodeURIComponent($("#search_barcode_iv").val())+"&p1="+item_id, function (data) {
            var total_p = 0;
            $.each(data, function (key, val) {
                total_p=0;
                
                if (val.fixed_price == 1) {
                    var rate = parseFloat(mask_clean($("#to_second_currency").val().replace(/,/g, '')));
                    val.selling_price = (val.fixed_price_value) / rate;
                }
                
               
                if(val.enable_price_var=="1"){
                    val.selling_price=(val.selling_price/val.base_price_rate_to_usd)*val.new_price_rate_to_lbp;
                    
                    if(val.enable_round=="1"){
                        val.selling_price = Math.round(val.selling_price/1000)*1000;
                    }
                    
                }
                total_p = precisionRound(val.selling_price*(1-val.discount/100)*parseFloat(1+val.vat_value/100),round_val);
              
              
                var disable_added_item_invoice_input="";
    
                if(pos_on_edit_invoice_disable_new_item_input==1){
                    disable_added_item_invoice_input=" readonly ";
                }
              
                if($("#return_show_invoice_to_change_table #qty_"+val.id).length==0){
                    $("#return_show_invoice_to_change_table").DataTable().row.add( [
                        val.id,
                        val.barcode,
                        val.description,
                        val.size_id,
                        val.color_text_id,
                        val.quantity,
                        "<input "+disable_added_item_invoice_input+" class='qty_input' type='text' id='qty_"+val.id+"' name='qty_["+val.id+"]' value='1' onkeyup='calculate_finale_prince_inv_changed("+val.id+")' />",
                        "<input  class='qty_input' type='hidden' id='hidden_price_inv_ch_"+val.id+"' name='hidden_price_inv_ch_"+val.id+"' value='"+val.selling_price+"' /><input class='qty_input ' "+disable_added_item_invoice_input+" onkeyup='calculate_finale_prince_inv_changed("+val.id+")' type='text' id='price_inv_ch_"+val.id+"' name='price_inv_ch_["+val.id+"]' value='"+val.selling_price+"' />",
                        "<input "+disable_added_item_invoice_input+" class='qty_input' type='text' id='disc_inv_ch_"+val.id+"' name='disc_inv_ch_["+val.id+"]' value='"+val.discount+"' onkeyup='calculate_finale_prince_inv_changed("+val.id+")' />",
                        "<span id='vat_inv_ch_"+val.id+"' >"+val.vat_value+"</span>",
                        "<span id='total_inv_ch_"+val.id+"' >"+total_p+"</span>",
                        "<i class='glyphicon glyphicon glyphicon-trash' style='cursor:pointer'></i>&nbsp;<i onclick='discounts_history("+val.id+")' title='Discounts History' class='glyphicon glyphicon-header' style='cursor:pointer'></i>"

                    ] ).draw(false);
                }
                cleaves_id("price_inv_ch_"+val.id,2);
                cleaves_id("disc_inv_ch_"+val.id,2);
            });
        }).done(function () {
            
            $('.dataTables_scrollBody thead tr').css({visibility:'collapse'});
            $("#search_barcode_iv").val("");
            $(".only_numeric").numeric({ negative : false});
            get_total_of_new_items();
            get_difference();
            
            $('.glyphicon-trash').on( 'click', function () {
                $("#return_show_invoice_to_change_table").DataTable()
                .row( $(this).parents('tr') )
                .remove()
                .draw();
                $('.dataTables_scrollBody thead tr').css({visibility:'collapse'});
                get_total_of_new_items();
                get_difference();
                $("#return_show_invoice_to_change_table .selected").removeClass('selected');
            });
    
        }).fail(function() {

        }).always(function() {

        });
    }
}

function _show_invoice_to_change(data){
    
    var cls=" col-lg-12 col-md-12 col-sm-12 col-xs-12 ";
    if(usd_but_show_lbp_priority==1){
        cls=" col-lg-8 col-md-8 col-sm-8 col-xs-8 ";
    }

    
    var content =
    '<div class="modal" data-backdrop="static" id="show_invoice_to_changeModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <form id="show_invoice_to_change_form" action="" method="post" enctype="multipart/form-data" >\n\
            <input id="invoice_id" name="invoice_id" value='+data.invoice[0].id+' type="hidden" />\n\
            <input id="manual_discount_d" name="manual_discount_d" value="'+data.invoice[0].manual_discount_value+'" type="hidden" />\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title" '+dir_+'>Invoices ID: '+data.invoice[0].id+'<i style="float:'+float_+';font-size:35px" class="glyphicon glyphicon-remove" onclick="closeModal(\'show_invoice_to_changeModal\')"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body" id="noBarcodeItems">\n\
                        <div class="row">\n\
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">\n\
                                <b class="ic_title">Creation Date:&nbsp;</b><span class="ic_title_val">'+data.invoice[0].creation_date+'</span>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">\n\
                                <b class="ic_title">Customer:&nbsp;</b><span class="ic_title_val">'+data.customer[0].name+'</span>\n\
                            </div>\n\
                            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">\n\
                                <b class="ic_title">Total Value:&nbsp;</b><span class="ic_title_val" style="font-size:18px !important;">'+data.invoice[0].total_value_formated+'</span>\n\
                            </div>\n\
                            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">\n\
                                 <b class="ic_title">Invoice Discount:&nbsp;</b><span class="ic_title_val" style="color:rgb(217, 83, 79) !important;font-size:18px !important;">'+data.invoice[0].discount_formated+'</span>\n\
                            </div>\n\
                            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">\n\
                                 <b class="ic_title">Total: </b><span class="ic_title_val" style="font-size:18px !important;">'+data.invoice[0].total_formated+'</span>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">\n\
                                <b class="ic_title">Salesperson: </b><span class="ic_title_val">'+data.sales_person[0].name+'</span>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">\n\
                                <b class="ic_title">Cashier: </b><span class="ic_title_val">'+data.cashier[0].name+'</span>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">\n\
                                <b class="ic_title">Payment Method: </b><span class="ic_title_val">'+data.payment_method[0].name+'</span>\n\
                            </div>\n\
                        </div>\n\
                         <div class="row">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                <table id="show_invoice_to_change_table" class="table table-striped table-bordered" cellspacing="0" style="width:100%;">\n\
                                    <thead>\n\
                                        <tr>\n\
                                            <th style="width: 85px !important;">Inv Item ID</th>\n\
                                            <th style="width: 100px !important;">Barcode</th>\n\
                                            <th>Description</th>\n\
                                            <th style="width: 70px !important;">Size</th>\n\
                                            <th style="width: 70px !important;">Color</th>\n\
                                            <th style="width: 70px !important;">QTY</th>\n\
                                            <th style="width: 70px !important;">Return</th>\n\
                                            <th style="width: 100px !important;">Price</th>\n\
                                            <th style="width: 90px !important;">Disc.</th>\n\
                                            <th style="width: 50px !important;">TAX</th>\n\
                                            <th style="width: 100px !important;">Total</th>\n\
                                        </tr>\n\
                                    </thead>\n\
                                    <tbody></tbody>\n\
                                </table>\n\
                            </div>\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:10px;">\n\
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="padding-left:0px;">\n\
                                    <b>Barcode&nbsp;</b><input autocomplete="off" onchange="search_barcode_iv_changed()" type="text" name="search_barcode_iv" id="search_barcode_iv">&nbsp;<i onclick="showAllItems(0)" class="glyphicon glyphicon-search" style="cursor:pointer"></i>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="padding-left:0px;">\n\
                                    <b>Total Price:&nbsp;</b> <span id="total_new_invoice_price" style="font-size:17px;">0</span>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="padding-left:0px;">\n\
                                    <b>Total Difference:&nbsp;</b><span style="font-size:20px; color:rgb(217, 83, 79) !important" id="difference_inv">0</span>\n\
                                </div>\n\
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="padding-left:0px;">\n\
                                    <a onclick="$(this).closest(\'form\').submit()" type="submit" class=" btn btn-primary" style="float:right;padding-top:0px;padding-bottom:0px;">UPDATE</a>\n\
                                </div>\n\
                                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1" style="padding-left:0px;padding-right:0px;">\n\
                                 \n\
                                </div>\n\
                            </div>\n\
                            <div class="'+cls+'" style="padding-right:0px;">\n\
                                <table id="return_show_invoice_to_change_table" class="table table-striped table-bordered" cellspacing="0" style="width:100%">\n\
                                    <thead>\n\
                                        <tr>\n\
                                            <th style="width: 85px !important;">Inv Item ID</th>\n\
                                            <th style="width: 100px !important;">Barcode</th>\n\
                                            <th >Description</th>\n\
                                            <th style="width: 70px !important;">Size</th>\n\
                                            <th style="width: 70px !important;">Color</th>\n\
                                            <th style="width: 70px !important;">Stock QTY</th>\n\
                                            <th style="width: 70px !important;">Sell QTY</th>\n\
                                            <th style="width: 100px !important;">Price</th>\n\
                                            <th style="width: 90px !important;">Disc.</th>\n\
                                            <th style="width: 50px !important;">TAX</th>\n\
                                            <th style="width: 100px !important;">Total</th>\n\
                                            <th style="width: 40px !important;">&nbsp;</th>\n\
                                        </tr>\n\
                                    </thead>\n\
                                    <tbody></tbody>\n\
                                </table>\n\
                            </div>\n\
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" id="cash_d_container">\n\
                                <div id="cash_info_container" style="padding-left:2px;">\n\
                                    <div class="panel panel-default">\n\
                                        <div class="panel-heading" style="padding-top:5px;padding-bottom:5px;"><b>Cash Info</b></div>\n\
                                        <div class="panel-body" style="padding:10px;">\n\
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-left:2px;padding-right:2px;">\n\
                                                <div class="form-group" style="margin-bottom:3px;">\n\
                                                    <label for="cash_usd">IN USD </label><span id="to_return_c_usd" style="float:right">&nbsp;&nbsp;&nbsp;</span>\n\
                                                    <input onkeyup="cash_changed_usd(this)" autocomplete="off" id="cash_usd" name="cash_usd" type="text" class="form-control med_input" placeholder="">\n\
                                                </div>\n\
                                            </div>\n\
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-left:2px;padding-right:2px;">\n\
                                                <div class="form-group" style="margin-bottom:3px;">\n\
                                                    <label for="cash_usd">OUT USD</label>\n\
                                                    <input style="display:none" readonly onkeyup="" autocomplete="off" id="r_cash_usd" name="r_cash_usd" type="text" class="form-control med_input" placeholder="">\n\
                                                    <input onkeyup="r_cash_usd_action_changed(this)" autocomplete="off" id="r_cash_usd_action" name="r_cash_usd_action" type="text" class="form-control med_input" placeholder="">\n\
                                                </div>\n\
                                            </div>\n\
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-left:2px;padding-right:2px;">\n\
                                                <div class="form-group" style="margin-bottom:3px;">\n\
                                                    <label for="cash_usd">IN LBP </label><span id="to_return_c_lbp" style="float:right">&nbsp;&nbsp;&nbsp;</span>\n\
                                                    <input onkeyup="cash_changed_lbp(this)"  autocomplete="off" id="cash_lbp" name="cash_lbp" type="text" class="form-control med_input" placeholder="">\n\
                                                </div>\n\
                                            </div>\n\
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-left:2px;padding-right:2px;">\n\
                                                <div class="form-group" style="margin-bottom:3px;">\n\
                                                    <label for="cash_usd">OUT LBP</label>\n\
                                                    <input style="display:none" readonly onkeyup="" autocomplete="off" id="r_cash_lbp" name="r_cash_lbp" type="text" class="form-control med_input" placeholder="">\n\
                                                    <input onkeyup="r_cash_lbp_action_changed(this)" autocomplete="off" id="r_cash_lbp_action" name="r_cash_lbp_action" type="text" class="form-control med_input" placeholder="">\n\
                                                </div>\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </form>\n\
    </div>';
    //$("#show_invoice_to_changeModal").remove();
    $('#show_invoice_to_changeModal').modal('hide');
    $("body").append(content);
    $('#show_invoice_to_changeModal').on('show.bs.modal', function (e) {

    });
    
    $('#show_invoice_to_changeModal').on('shown.bs.modal', function (e) {
        
        var show_invoice_to_change_table = null;
        
        if(usd_but_show_lbp_priority==0){
            $("#cash_d_container").hide();
        }else{
            $("#cash_d_container").show();
        }
        /*
        var search_fields = [0,1,2,3,4,5,6,7,8];
        var index = 0;
        $('#show_invoice_to_change_table tfoot th').each( function () {
            if(jQuery.inArray(index, search_fields) !== -1){
                var title = $(this).text();
                $(this).html( '<div class="inner-addon left-addon"><input style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder=" '+title+'" /></div>' );
                index++;
            }
        });*/
        
        
        
        show_invoice_to_change_table = $('#show_invoice_to_change_table').DataTable({
            ajax: {
                url: "?r=pos&f=show_invoice_to_change&p0="+data.invoice[0].id+"&p1=0&p2=0",
                type: 'POST',
                error:function(xhr,status,error) {
                    logged_out_warning();
                },
            },
            orderCellsTop: true,
            scrollX: true,
            bSort:false,
            //scrollY: true,
            iDisplayLength: 50,
            scrollY: '15vh',
            aoColumnDefs: [
                { "targets": [0], "searchable": true, "orderable": false, "visible": false },
                { "targets": [1], "searchable": true, "orderable": false, "visible": true },
                { "targets": [2], "searchable": true, "orderable": false, "visible": true },
                { "targets": [3], "searchable": true, "orderable": false, "visible": true ,"className": "dt-center"},
                { "targets": [4], "searchable": true, "orderable": false, "visible": true },
                { "targets": [5], "searchable": true, "orderable": false, "visible": true,"className": "dt-center" },
                { "targets": [6], "searchable": true, "orderable": false, "visible": true },
                { "targets": [7], "searchable": true, "orderable": false, "visible": true },
                { "targets": [8], "searchable": true, "orderable": false, "visible": true },
                { "targets": [9], "searchable": true, "orderable": false, "visible": true,"className": "dt-center" },
                { "targets": [10], "searchable": true, "orderable": false, "visible": true },
            ],
            //scrollCollapse: true,
            paging: false,
            dom: '<"toolbar_inv_change">frtip',
            initComplete: function(settings, json) {

                var locations_ch_inv = "<option value='0' title='Select Location'>Select Location</option>";
           
                for(var i=0;i<all_stores.length;i++){
                    if(current_store_id!=all_stores[i].id){
                        locations_ch_inv+="<option value='"+all_stores[i].id+"' title='"+all_stores[i].name+"'>"+all_stores[i].name+"</option>";
                    }
                }
                
                $("div.toolbar_inv_change").html('\n\
                    <div class="row">\n\
                        <div class="col-lg-3 col-md-3 col-xs-3" style="padding-left:15px;padding-right:5px;">\n\
                            <div class="form-group" style="margin-bottom:0px;width:100%">\n\
                                <select data-width="100%" data-live-search="true" id="locations_list" name="" class="selectpicker form-control" onchange="">'+locations_ch_inv+'</select>\n\
                            </div>\n\
                        </div>\n\
                        <div class="col-lg-2 col-md-2 col-xs-2" style="padding-left:5px;padding-right:5px;">\n\
                            <div class="form-group" style="margin-bottom:0px;width:100%">\n\
                                <input id="locations_inv" name="" class="form-control" style="width:100%" placeholder="Invoice number"/>\n\
                            </div>\n\
                        </div>\n\
                        <div class="col-lg-1 col-md-1 col-xs-1" style="padding-left:5px;padding-right:5px;">\n\
                            <div class="form-group" style="margin-bottom:0px;width:100%">\n\
                                <button style="width:100%" onclick="search_invoice_loc()" type="button" class="btn btn-info">Search</button>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    ');  
                
                $(".selectpicker").selectpicker();
                
                $('.dataTables_scrollBody thead tr').css({visibility:'collapse'});
                
                $( window ).resize(function() {
                    $('.dataTables_scrollBody thead tr').css({visibility:'collapse'});
                });
                
                $(".only_numeric").numeric({ negative : false});
                $(".sk-circle-layer").hide();
            },
        });

        
        
         $('#show_invoice_to_change_table').DataTable().on('mousedown',"tbody tr", function ( e, dt, type, indexes ) { 
            $('.selected').removeClass("selected");
            $(this).addClass('selected');
         });
        
        /*
        $('#show_invoice_to_change_table').DataTable().columns().every( function () {
            var that = this;
            $( 'input', this.footer() ).on( 'keyup change', function () {
                show_invoice_to_change_table.keys.disable();
                if ( that.search() !== this.value ) {
                    that.search( this.value ).draw();
                }
                show_invoice_to_change_table.keys.enable();
                $('.dataTables_scrollBody thead tr').css({visibility:'collapse'});
            } );
        } );
        */
        
        
        
        
        
        
        var return_show_invoice_to_change_table = null;
        /*var search_fields = [0,1,2,3,4,5,6,7,8];
        var index = 0;
        $('#return_show_invoice_to_change_table tfoot th').each( function () {
            if(jQuery.inArray(index, search_fields) !== -1){
                var title = $(this).text();
                $(this).html( '<div class="inner-addon left-addon"><input style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder=" '+title+'" /></div>' );
                index++;
            }
        });*/
        return_show_invoice_to_change_table = $('#return_show_invoice_to_change_table').DataTable({
            /*ajax: {
                url: "?r=pos&f=show_invoice_to_change&p0="+data.invoice[0].id,
                type: 'POST',
                error:function(xhr,status,error) {
                    logged_out_warning();
                },
            },*/
                        
            data: [],
            orderCellsTop: true,
            scrollX: true,
            bSort:false,
            //scrollY: true,
            iDisplayLength: 50,
            scrollY: '15vh',
            aoColumnDefs: [
                { "targets": [0], "searchable": true, "orderable": false, "visible": false },
                { "targets": [1], "searchable": true, "orderable": false, "visible": true },
                { "targets": [2], "searchable": true, "orderable": false, "visible": true },
                { "targets": [3], "searchable": true, "orderable": false, "visible": true },
                { "targets": [4], "searchable": true, "orderable": false, "visible": true },
                { "targets": [5], "searchable": true, "orderable": false, "visible": true ,"className": "dt-center"},
                { "targets": [6], "searchable": true, "orderable": false, "visible": true,"className": "dt-center" },
                { "targets": [7], "searchable": true, "orderable": false, "visible": true },
                { "targets": [8], "searchable": true, "orderable": false, "visible": true },
                { "targets": [9], "searchable": true, "orderable": false, "visible": true,"className": "dt-center" },
                { "targets": [10], "searchable": true, "orderable": false, "visible": true },
                { "targets": [11], "searchable": true, "orderable": false, "visible": true,"className": "dt-center" },
            ],
            //scrollCollapse: true,
            paging: false,
            initComplete: function(settings, json) {
                //return_show_invoice_to_change_table.cell( ':eq(0)' ).focus;
                
                $('.dataTables_scrollBody thead tr').css({visibility:'collapse'});
                
                $( window ).resize(function() {
                    $('.dataTables_scrollBody thead tr').css({visibility:'collapse'});
                });
                
                $(".only_numeric").numeric({ negative : false});
                $(".sk-circle-layer").hide();
            },
        });

        
        $('#return_show_invoice_to_change_table').DataTable().on('mousedown',"tbody tr", function ( e, dt, type, indexes ) { 
            $('.selected').removeClass("selected");
            $(this).addClass('selected');
         });
       
        $("#show_invoice_to_change_form").on('submit', (function (e) {
            e.preventDefault();
            if(enable_edit_invoice_even_new_item_is_added==0){
                
                var total_to_return=0;
                $('.qty_input__').each(function(i, obj) {
                    total_to_return+=$(this).val();
                });
                
                if(total_to_return==0){
                    swal("You must choose and set return qty first");
                    return false;
                }
                
                if($('#return_show_invoice_to_change_table').DataTable().rows().count()==0){
                    swal("You must add an item to change");
                    return false;
                }
                
                
            }
            
            
            var check_difference=parseFloat($("#difference_inv").html());
     
           
            if(edit_invoice_block_return_money==1 && check_difference>0){
                swal("Return money to clients is not allowed");
                return false;
            }
         
            
            /*if(usd_but_show_lbp_priority==1){
                if($("#cash_usd_r").val()=="" && $("#cash_lbp_r").val()==""){
                   $("#cash_usd_r").addClass("error");
                   $("#cash_lbp_r").addClass("error");
                    
                    return;
                }
            }*/
            
            
            
            
            
            $('#return_show_invoice_to_change_table').DataTable().rows().eq(0).each( function ( index ) {
                var row = $('#return_show_invoice_to_change_table').DataTable().row(index);
                var data = row.data();
                $("#price_inv_ch_"+data[0]).val($("#price_inv_ch_"+data[0]).val().replace(/,/g , ''));
            } );
            

            //$tousd = (($transactions[$i]["cash_lbp"] / $transactions[$i]["rate"]) + $transactions[$i]["cash_usd"] - $transactions[$i]["returned_cash_usd"] - $transactions[$i]["returned_cash_lbp"] / $transactions[$i]["rate"]);
           
            if(usd_but_show_lbp_priority==1){
                if($("#cash_info_container .error").length>0){ // || (check_difference!=0)
                    swal("Cash Error 001");
                    return;
                }
                if(check_difference<0 && $("#cash_usd").val().replace(/,/g , '')==0 && $("#cash_lbp").val().replace(/,/g , '')==0 ){
                    swal("Cash IN Missing");
                    return;
                }
                var rate = parseFloat(mask_clean($("#to_second_currency").val().replace(/,/g, '')));
                var to_usd=parseFloat($("#cash_usd").val().replace(/,/g , ''))+parseFloat(($("#cash_lbp").val().replace(/,/g , '')/rate))-parseFloat($("#r_cash_usd_action").val().replace(/,/g , ''))-parseFloat(($("#r_cash_lbp_action").val().replace(/,/g , '')/rate));

                if(pos_force_money_in_equal_total_amount==1){
                    if(Math.abs(to_usd+check_difference)>0.1){
                        swal("Cash IN/OUT Error 002");
                        return;
                    }
                }
                
                
                
                 
            }
            
            
            $(".sk-circle-layer").show();
            
            $.ajax({
                url: "?r=pos&f=edit_invoice_change",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function (data)
                {
                    printAgain($("#invoice_id").val());
                    
                    if($('#invoices_list_table').length>0){
                        var table = $('#invoices_list_table').DataTable();
                       
                        table.ajax.url("?r=pos&f=get_all_invoices_list&p0=today&p1=0&p2="+$("#operations_type").val()).load(function () {
                           
                           //$(items_search.row(cell.index().row).node()).addClass('selected');
                            //table.row('.' + pad_invoice($("#invoice_id").val()), {page: 'current'}).select(); 
                            //inv.print_invoice($("#invoice_id").val(),0);
                            $('#show_invoice_to_changeModal').modal('hide');
                            $(".sk-circle-layer").hide();
                        },false);
                    }else if($('#return_items_by_barcode_table').length>0){
                        $('#show_invoice_to_changeModal').modal('hide');
                        var table = $('#return_items_by_barcode_table').DataTable();
                        table.ajax.url("?r=pos&f=get_all_sold_items_with_vat_by_barcode&p1="+$("#item_barcode_h").val()).load(function () {
                            $(".sk-circle-layer").hide();
                        },false);
                    }else{
                        //inv.print_invoice($("#invoice_id").val(),0);
                        $('#show_invoice_to_changeModal').modal('hide');
                        $(".sk-circle-layer").hide();
                    }
                    
                    
                    
                    
                }
            });
        }));
        
        //cleaves_id("cash_lbp_r",0);
            //cleaves_id("cash_usd_r",5);
            
            set_current_cash_var(2);

    });
    $('#show_invoice_to_changeModal').on('hide.bs.modal', function (e) {
        original_inv_data = [];
        $("#show_invoice_to_changeModal").remove();
    });
    $('#show_invoice_to_changeModal').modal('show');
}

function search_invoice_loc(){
    if($("#locations_list").val()==0){
        alert("select location first");
        return;
    }
    if($("#locations_inv").val()==""){
        alert("invoice number no valid");
        return;
    }
    
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var table = $('#show_invoice_to_change_table').DataTable();
    table.ajax.url("?r=pos&f=show_invoice_to_change_other_location&p0="+$("#locations_inv").val()+"&p1="+$("#locations_list").val()+"&p2="+$("#invoice_id").val()).load(function (data) {
        if(data.er==1){
            $(".sk-circle-layer").hide();
            swal("Invoice already imported (Local ID: "+data.local_id+")");
            return;
        }
        
        if(data.er==2){
            $(".sk-circle-layer").hide();
            swal("Connection error");
            return;
        }
        
        if(table.rows().count()==0){
            swal("Invoice number "+$("#locations_inv").val()+ " is not found in "+$("#locations_list option:selected" ).text()+" Or unable to connect");
        }else{
            var tmpinv = $("#invoice_id").val();
            $("#show_invoice_to_changeModal").modal('hide');
            setTimeout(function(){
                show_invoice_to_change(tmpinv);
            },200);
        }
        
        $(".sk-circle-layer").hide();
    },false);
    
}

function edit_invoice_another_branche(){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    lockMainPos = true;
    var content =
    '<div class="modal" data-backdrop="static" id="invoices_itemsModal" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title" '+dir_+'>Invoices of other branches<i style="float:'+float_+';font-size:35px" class="glyphicon glyphicon-remove" onclick="closeModal(\'invoices_itemsModal\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body" id="noBarcodeItems">\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <table id="invoices_virtual_list_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                <thead>\n\
                                    <tr>\n\
                                        <th style="width: 70px !important;">Ref.</th>\n\
                                        <th style="width: 130px !important;">Date</th>\n\
                                        <th>Customer name</th>\n\
                                        <th style="width: 90px !important;">Sub-Total</th>\n\
                                        <th style="width: 90px !important;">Inv. Disc.</th>\n\
                                        <th style="width: 90px !important;">Total</th>\n\
                                        <th style="width: 60px !important;">Method</th>\n\
                                        <th style="width: 140px !important;">&nbsp;</th>\n\
                                    </tr>\n\
                                </thead>\n\
                                <tfoot>\n\
                                    <tr>\n\
                                        <th>Ref.</th>\n\
                                        <th>Date</th>\n\
                                        <th>Customer name</th>\n\
                                        <th>Sub-Total</th>\n\
                                        <th>Inv. Disc.</th>\n\
                                        <th>Total</th>\n\
                                        <th>Method</th>\n\
                                        <th>&nbsp;</th>\n\
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
   
    $('#invoices_itemsModal').modal('hide');
    $("body").append(content);
    $('#invoices_itemsModal').on('show.bs.modal', function (e) {
        
        var items_search = null;
        var search_fields = [0,1,2,3,4,5,6];
        var index = 0;
        $('#invoices_virtual_list_table tfoot th').each( function () {
            if(jQuery.inArray(index, search_fields) !== -1){
                var title = $(this).text();
                $(this).html( '<div class="inner-addon no-left-addon"><input style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder=" '+title+'" /></div>' );
                index++;
            }
        });           
        items_search = $('#invoices_virtual_list_table').DataTable({
            ajax: {
                url: "?r=pos&f=get_all_other_branches_invoices_list&p0=today&p1=0",
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
                { "targets": [5], "searchable": true, "orderable": true, "visible": true },
                { "targets": [6], "searchable": true, "orderable": true, "visible": true },
                { "targets": [7], "searchable": true, "orderable": false, "visible": true },
            ],
            scrollCollapse: true,
            paging: true,
            dom: '<"toolbar_inv_change_branche">frtip',
            initComplete: function(settings, json) {
                items_search.cell( ':eq(0)' ).focus();
                $('#invoices_virtual_list_table tfoot input:eq(2)').focus();
                
                $("div.toolbar_inv_change_branche").html('\n\
                    <div class="row">\n\
                        <div class="col-lg-2 col-md-2 col-xs-2" style="padding-left:15px;padding-right:5px;">\n\
                            <div class="form-group" style="margin-bottom:0px;width:100%">\n\
                                <button style="width:100%" onclick="generate_invoice_for_another_branche_change()" type="button" class="btn btn-primary">Import Invoice</button>\n\
                            </div>\n\
                        </div>\n\
                        <div class="col-lg-2 col-md-2 col-xs-2" style="padding-left:15px;padding-right:5px;">\n\
                            <div class="form-group" style="margin-bottom:0px;width:100%">\n\
                                <input style="width:100%" id="invbr_date" class="form-control date_sob" type="text" />\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    ');
                
                
                $('.date_sob').daterangepicker({
                    //dateLimit:{month:12},
                    locale: {
                        format: 'YYYY-MM-DD'
                    },
                });

                $('.date_sob').on('apply.daterangepicker', function(ev) {
                    ev.preventDefault();
                    ev.stopPropagation();
                });
                
                $( "#invbr_date" ).change(function() {
                    $(".sk-circle").center();
                    $(".sk-circle-layer").show();
                    var table = $('#invoices_virtual_list_table').DataTable();
                    table.ajax.url("?r=pos&f=get_all_other_branches_invoices_list&p0="+$("#invbr_date").val()+"&p1=0").load(function () {
                        $(".sk-circle-layer").hide();
                    },false);
                });
                
                 //invoices_date_changed();
                
                $(".sk-circle-layer").hide();
            },
            fnDrawCallback: updateRows_invoices_branche,
        });
        
        $('#invoices_virtual_list_table').DataTable().on('dblclick',"tr", function ( e, dt, type, indexes ) {
            var sdata = items_search.row('.selected', 0).data();
            return_items(parseInt(sdata[0].split("-")[1]));
        });

        
        $('#invoices_virtual_list_table').DataTable().on('mousedown',"tbody tr", function ( e, dt, type, indexes ) { 
            $('.selected').removeClass("selected");
            $(this).addClass('selected');
         });

        $('#invoices_virtual_list_table').on('key.dt', function(e, datatable, key, cell, originalEvent){
            if(key === 13){
                 //var sdata = items_search.row('.selected', 0).data();
                //returnQty(parseInt(sdata[0].split("-")[1]));
            }
        });
        
        $('#invoices_virtual_list_table').DataTable().columns().every( function () {
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
    
    $('#invoices_itemsModal').on('shown.bs.modal', function (e) {
        
        
        //$('#invoices_list_table').DataTable().ajax.url("?r=pos&f=get_all_invoices_list&p0="+$("#invbr_date").val()+"&p1=0").load(function () {
            //$(".sk-circle-layer").hide();
        //},false);
        
    });
    $('#invoices_itemsModal').on('hide.bs.modal', function (e) {
        lockMainPos = false;
        $("#invoices_itemsModal").remove();
    });
    $('#invoices_itemsModal').modal('show');
}

function updateRows_invoices_branche(){
    var table = $('#invoices_virtual_list_table').DataTable();
    var p = table.rows({ page: 'current' }).nodes();
    var  shortcuts = "";
    for (var k = 0; k < p.length; k++){
        var index = table.row(p[k]).index();
        shortcuts = "";
        shortcuts+='<i class="glyphicon glyphicon-edit shortcut" title="Edit" onclick="show_invoice_to_change('+parseInt(table.cell(index, 0).data().split("-")[1])+')"></i>';
        
        shortcuts+='<i class="glyphicon glyphicon-print shortcut" title="Print Receipt" onclick="printAgain('+parseInt(table.cell(index, 0).data().split("-")[1])+')"></i>';
        shortcuts+='<i class="glyphicon icon-printer-tool shortcut" title="Print A4" onclick="print_sheet(\''+parseInt(table.cell(index, 0).data().split("-")[1])+'\')"></i>';
        shortcuts+='<i class="glyphicon glyphicon-briefcase shortcut" title="Show Items" onclick="return_items('+parseInt(table.cell(index, 0).data().split("-")[1])+')"></i>';

        shortcuts+='<i class="glyphicon glyphicon-trash shortcut red" title="Delete" onclick="delete_invoice('+parseInt(table.cell(index, 0).data().split("-")[1])+')"></i>';

        table.cell(index, 7).data(shortcuts);
    }
}

function generate_invoice_for_another_branche_change(){
    swal({
        title: "Are you sure?",
        html: false ,
        text: '',
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
            $.getJSON("?r=pos&f=generate_empty_invoice", function (data) {
                _data = data;
            }).done(function () {
                var table = $('#invoices_virtual_list_table').DataTable();
                table.ajax.url("?r=pos&f=get_all_other_branches_invoices_list&p0="+$("#invbr_date").val()+"&p1=0").load(function () {
                    show_invoice_to_change(_data);
                    $(".sk-circle-layer").hide();
                },false);
            });
        }
    });
}