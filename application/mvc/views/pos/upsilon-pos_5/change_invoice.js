var original_inv_data = [];

function show_invoice_to_change(invoice_id){
    if(enable_edit_invoice_password==1){
        swal({
                title: "Enter Password",
                html: true ,
                text: '<input style="z-index:999999999999" class="form-control" value="" type="text" id="inv_pass"/>',
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
                    }
                }
            });
             setTimeout(function(){ $("#inv_pass").focus(); },500);
    }else{
        __show_invoice_to_change(invoice_id);
    }
    
}

function __show_invoice_to_change(invoice_id){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    $.getJSON("?r=pos&f=get_invoice_with_all_details&p0="+invoice_id, function (data) {
        original_inv_data = data;
    }).done(function () {
        //alert(get_original_total());
        _show_invoice_to_change(original_inv_data);
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
        total+=Math.round($("#qty_"+data[0]).val()*$("#price_inv_ch_"+data[0]).val()*(1-$("#disc_inv_ch_"+data[0]).val()/100))*parseFloat(1+$("#vat_inv_ch_"+data[0]).html()/100);
        final+=total;
    } );
    $('#total_new_invoice_price').html(accounting.formatMoney(final, { symbol: default_currency_symbol,  format: "%v %s" }));

    return final;
}


function get_difference(){
    var total = get_original_total_without_return_as_flag()-get_total_of_new_items();
    $('#difference_inv').html(accounting.formatMoney(total, { symbol: default_currency_symbol,  format: "%v %s" }));
    return total;
}

function get_original_total_without_return_as_flag(){
    var original_total = 0;
    var tmp = 0;
    for(var i=0;i<original_inv_data.invoice_details.length;i++){
        tmp+= parseFloat(original_inv_data.invoice_details[i].selling_price)*(parseFloat($("#qty_"+original_inv_data.invoice_details[i].id).val()))*(1-(parseFloat(original_inv_data.invoice_details[i].discount)/100));
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
    $("#price_inv_ch_"+id).val(Math.round($("#hidden_price_inv_ch_"+id).val()*(1-$("#disc_inv_ch_"+id).val()/100)));
}

function calculate_finale_prince_inv_changed(id){
    $("#total_inv_ch_"+id).html(accounting.formatMoney(Math.round($("#qty_"+id).val()*$("#price_inv_ch_"+id).val()*(1-$("#disc_inv_ch_"+id).val()/100))*parseFloat(1+$("#vat_inv_ch_"+id).html()/100), { symbol: default_currency_symbol,  format: "%v %s" }));
    get_total_of_new_items();
    get_difference();
}

function search_barcode_iv_changed(){
    if($("#search_barcode_iv").val().length>0){
        $.getJSON("?r=pos&f=get_item_by_barcode_for_change&p0=" + encodeURIComponent($("#search_barcode_iv").val()), function (data) {
            var total_p = 0;
            $.each(data, function (key, val) {
                total_p=0;
           
                total_p = accounting.formatMoney(Math.round(val.selling_price*(1-val.discount/100))*parseFloat(1+val.vat_value/100), { symbol: default_currency_symbol,  format: "%v %s" });
                /*
                new_inv_data.push([
                    val.id,
                    val.barcode,
                    val.description,
                    val.size_id,
                    val.color_text_id,
                    val.quantity,
                    "<input class='qty_input only_numeric' type='text' id='qty_"+val.id+"' name='qty_"+val.id+"' value='1' onkeyup='calculate_finale_prince_inv_changed("+val.id+")' />",
                    "<input class='qty_input only_numeric' type='hidden' id='hidden_price_inv_ch_"+val.id+"' name='hidden_price_inv_ch_"+val.id+"' value='"+val.selling_price+"' /><input class='qty_input only_numeric' onkeyup='calculate_finale_prince_inv_changed("+val.id+")' type='text' id='price_inv_ch_"+val.id+"' name='price_inv_ch_"+val.id+"' value='"+val.selling_price+"' />",
                    "<input class='qty_input only_numeric' type='text' id='disc_inv_ch_"+val.id+"' name='disc_inv_ch_"+val.id+"' value='"+val.discount+"' onkeyup='calculate_finale_prince_inv_changed("+val.id+")' />",
                    "<span id='vat_inv_ch_"+val.id+"' >"+val.vat_value+"</span>",
                    "<span id='total_inv_ch_"+val.id+"' >"+total_p+"</span>",
                    "<i class='glyphicon glyphicon glyphicon-trash' style='cursor:pointer'></i>"
                ]);*/
                if($("#return_show_invoice_to_change_table #qty_"+val.id).length==0){
                    $("#return_show_invoice_to_change_table").DataTable().row.add( [
                        val.id,
                        val.barcode,
                        val.description,
                        val.size_id,
                        val.color_text_id,
                        val.quantity,
                        "<input class='qty_input only_numeric' type='text' id='qty_"+val.id+"' name='qty_["+val.id+"]' value='1' onkeyup='calculate_finale_prince_inv_changed("+val.id+")' />",
                        "<input class='qty_input only_numeric' type='hidden' id='hidden_price_inv_ch_"+val.id+"' name='hidden_price_inv_ch_"+val.id+"' value='"+val.selling_price+"' /><input class='qty_input only_numeric' onkeyup='calculate_finale_prince_inv_changed("+val.id+")' type='text' id='price_inv_ch_"+val.id+"' name='price_inv_ch_["+val.id+"]' value='"+val.selling_price+"' />",
                        "<input class='qty_input only_numeric' type='text' id='disc_inv_ch_"+val.id+"' name='disc_inv_ch_["+val.id+"]' value='"+val.discount+"' onkeyup='calculate_finale_prince_inv_changed("+val.id+")' />",
                        "<span id='vat_inv_ch_"+val.id+"' >"+val.vat_value+"</span>",
                        "<span id='total_inv_ch_"+val.id+"' >"+total_p+"</span>",
                        "<i class='glyphicon glyphicon glyphicon-trash' style='cursor:pointer'></i>"
                    ] ).draw( false );
                }
                
            });
        }).done(function () {
            
            //$("#return_show_invoice_to_change_table").DataTable().clear().rows.add(new_inv_data).draw();
            
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
    var content =
    '<div class="modal" data-backdrop="static" id="show_invoice_to_changeModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <form id="show_invoice_to_change_form" action="" method="post" enctype="multipart/form-data" >\n\
            <input id="invoice_id" name="invoice_id" value='+data.invoice[0].id+' type="hidden" />\n\
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
                                <b class="ic_title">Total Value:&nbsp;</b><span class="ic_title_val">'+data.invoice[0].total_value_formated+'</span>\n\
                            </div>\n\
                            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">\n\
                                 <b class="ic_title">Invoice Discount:&nbsp;</b><span class="ic_title_val">'+data.invoice[0].discount_formated+'</span>\n\
                            </div>\n\
                            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">\n\
                                 <b class="ic_title">Total: </b><span class="ic_title_val">'+data.invoice[0].total_formated+'</span>\n\
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
                                <table id="show_invoice_to_change_table" class="table table-striped table-bordered" cellspacing="0" style="width:100%">\n\
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
                                            <th style="width: 50px !important;">Vat</th>\n\
                                            <th style="width: 100px !important;">Total</th>\n\
                                        </tr>\n\
                                    </thead>\n\
                                    <tbody></tbody>\n\
                                </table>\n\
                            </div>\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:10px;">\n\
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="padding-left:0px;">\n\
                                    <b>Barcode&nbsp;</b><input autocomplete="off" onchange="search_barcode_iv_changed()" type="text" name="search_barcode_iv" id="search_barcode_iv">\n\
                                </div>\n\
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="padding-left:0px;">\n\
                                    <b>Total Price:&nbsp;</b> <span id="total_new_invoice_price" style="font-size:17px;">0</span>\n\
                                </div>\n\
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="padding-left:0px;">\n\
                                    <b>Total Difference:&nbsp;</b><span style="font-size:17px; color:rgb(217, 83, 79) !important" id="difference_inv">0</span>\n\
                                </div>\n\
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="padding-left:0px;">\n\
                                 <a onclick="$(this).closest(\'form\').submit()" type="submit" class="btn btn-primary" style="float:right">Update Invoice</a>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
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
                                            <th style="width: 50px !important;">Vat</th>\n\
                                            <th style="width: 100px !important;">Total</th>\n\
                                            <th style="width: 40px !important;">&nbsp;</th>\n\
                                        </tr>\n\
                                    </thead>\n\
                                    <tbody></tbody>\n\
                                </table>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </form>\n\
    </div>';
    $("#show_invoice_to_changeModal").remove();
    $("body").append(content);
    $('#show_invoice_to_changeModal').on('show.bs.modal', function (e) {

    });
    
    $('#show_invoice_to_changeModal').on('shown.bs.modal', function (e) {
        
        var show_invoice_to_change_table = null;
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
                url: "?r=pos&f=show_invoice_to_change&p0="+data.invoice[0].id,
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
            scrollY: '25vh',
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
            initComplete: function(settings, json) {
                //show_invoice_to_change_table.cell( ':eq(0)' ).focus;
                
                $('.dataTables_scrollBody thead tr').css({visibility:'collapse'});
                
                $( window ).resize(function() {
                    $('.dataTables_scrollBody thead tr').css({visibility:'collapse'});
                });
                
                $(".only_numeric").numeric({ negative : false});
                $(".sk-circle-layer").hide();
            },
        });
        $('#show_invoice_to_change_table').on('key-focus.dt', function(e, datatable, cell){
            $(show_invoice_to_change_table.row(cell.index().row).node()).addClass('selected');
        });

        $('#show_invoice_to_change_table').on('key-blur.dt', function(e, datatable, cell){
            $(show_invoice_to_change_table.row(cell.index().row).node()).removeClass('selected');
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
            scrollY: '25vh',
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
        $('#return_show_invoice_to_change_table').on('key-focus.dt', function(e, datatable, cell){
            $(return_show_invoice_to_change_table.row(cell.index().row).node()).addClass('selected');
        });

        $('#return_show_invoice_to_change_table').on('key-blur.dt', function(e, datatable, cell){
            $(return_show_invoice_to_change_table.row(cell.index().row).node()).removeClass('selected');
        });
        
        /*
        $('#return_show_invoice_to_change_table').DataTable().columns().every( function () {
            var that = this;
            $( 'input', this.footer() ).on( 'keyup change', function () {
                return_show_invoice_to_change_table.keys.disable();
                if ( that.search() !== this.value ) {
                    that.search( this.value ).draw();
                }
                return_show_invoice_to_change_table.keys.enable();
                $('.dataTables_scrollBody thead tr').css({visibility:'collapse'});
            } );
        } );
        */
       
        $("#show_invoice_to_change_form").on('submit', (function (e) {
            e.preventDefault();
            if($('#return_show_invoice_to_change_table').DataTable().rows().count()==0){
                swal("You must add an item to change");
                return false;
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
                    if($('#invoices_list_table').length>0){
                        var table = $('#invoices_list_table').DataTable();
                        table.ajax.url("?r=pos&f=get_all_invoices_list&p0=today&p1=0").load(function () {
                           
                           //$(items_search.row(cell.index().row).node()).addClass('selected');
                            //table.row('.' + pad_invoice($("#invoice_id").val()), {page: 'current'}).select(); 
                            inv.print_invoice($("#invoice_id").val(),0);
                            $('#show_invoice_to_changeModal').modal('hide');
                            $(".sk-circle-layer").hide();
                        },false);
                    }else{
                        inv.print_invoice($("#invoice_id").val(),0);
                        $('#show_invoice_to_changeModal').modal('hide');
                        $(".sk-circle-layer").hide();
                    }
                    
                    
                }
            });
        }));

    });
    $('#show_invoice_to_changeModal').on('hide.bs.modal', function (e) {
        original_inv_data = [];
        $("#show_invoice_to_changeModal").remove();
    });
    $('#show_invoice_to_changeModal').modal('show');
}