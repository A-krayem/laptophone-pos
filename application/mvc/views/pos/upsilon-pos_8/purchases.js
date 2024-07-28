var purchasesFunctionLocked = false;

function cancelDiscount(invoice_id){
    swal({
        title: "cancel Discount?",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: LG_YES,
        closeOnConfirm: true
    },
    function(isConfirm){
        if(isConfirm){
            $.getJSON("?r=pos&f=cancelDiscount&p0=" + invoice_id, function (data) {
                
            })
            .done(function () {
                $("#dsc_"+invoice_id).html(" "+format_price_pos(0));
            })
            .fail(function() {
                logged_out_warning();
            })
            .always(function() {

            });
        }
    });
}

function printAgain(id){
    inv.print_invoice(id,0);
}

function printAgain_Gift(id){
    inv.print_invoice(id,1);
}

function updatePurchaseItems(){
    var items = "";

    $.getJSON("?r=pos&f=getPurchases&p0=" + store_id +"&p1="+$("#purchasesDate").val(), function (data) {
        $.each(data, function (key, val) {
            items+='<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 inv_purchased"><div><b>'+pad_invoice(val.id)+' </b><br/><b>Value:</b> '+format_price_pos(val.total_value)+'<br/><b>Discount:</b><span id="dsc_'+val.id+'"> '+format_price_pos(val.invoice_discount)+'</span><br/><b>Date:</b> '+val.creation_date+'<button onclick="printAgain('+val.id+')" type="button" class="btn btn-default print_again">&nbsp;Print&nbsp;</button><button onclick="cancelDiscount('+val.id+')" type="button" class="btn btn-default cancelDiscount">Cancel Disc.</button><button onclick="showItemsForInvoice('+val.id+')" type="button" class="btn btn-default showItems">Show Items</button></div></div>';
        });
    }).done(function () {
        $("#purchaseItems").empty();
        $("#purchaseItems").html(items);
    })
    .fail(function() {
        logged_out_warning();
    })
    .always(function() {

    });
}

function operations_types_invoices_changed(){
    invoices_date_changed();
}

function purchases(){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    lockMainPos = true;
    var content =
    '<div class="modal" data-backdrop="static" id="invoices_itemsModal" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title" '+dir_+'>Invoices<i style="float:'+float_+';font-size:35px" class="glyphicon glyphicon-remove" onclick="closeModal(\'invoices_itemsModal\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body" id="noBarcodeItems">\n\
                    <div class="row">\n\
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">\n\
                            <input id="sold_items_per_invoices" class="form-control date_s" type="text" />\n\
                        </div>\n\
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">\n\
                            <select onchange="operations_types_invoices_changed()" id="operations_type" name="operations_type" class="selectpicker form-control" style="width:100%"><option value="1">Current Shift</option><option value="2">All my operations</option><option value="3">All operations</option></select>\n\
                        </div>\n\
                    </div>\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <table id="invoices_list_table" class="table table-striped table-bordered" cellspacing="0" style="width:100%">\n\
                                <thead>\n\
                                    <tr>\n\
                                        <th style="width: 70px !important;">Ref.</th>\n\
                                        <th style="width: 130px !important;">Date</th>\n\
                                        <th>Operator</th>\n\
                                        <th>Client</th>\n\
                                        <th >Sub-Total</th>\n\
                                        <th >Inv. Disc.</th>\n\
                                        <th >Total After Disc.</th>\n\
                                        <th>Tax</th>\n\
                                        <th>Freight</th>\n\
                                        <th>Total</th>\n\
                                        <th style="width: 50px !important;">Method</th>\n\
                                        <th style="max-width: 150px !important;">&nbsp;</th>\n\
                                        <th>Disable delete</th>\n\
                                        <th>Disable edit</th>\n\
                                        <th>Disable edit/delete older</th>\n\
                                    </tr>\n\
                                </thead>\n\
                                <tfoot>\n\
                                    <tr>\n\
                                        <th>Ref.</th>\n\
                                        <th>Date</th>\n\
                                        <th>Operator</th>\n\
                                        <th>Client</th>\n\
                                        <th>Sub-Total</th>\n\
                                        <th>Inv. Disc.</th>\n\
                                        <th>Total After Discount</th>\n\
                                        <th>Tax</th>\n\
                                        <th>Freight</th>\n\
                                        <th>Total</th>\n\
                                        <th>Method</th>\n\
                                        <th>&nbsp;</th>\n\
                                        <th>Disable delete</th>\n\
                                        <th>Disable edit</th>\n\
                                        <th></th>\n\
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
        var search_fields = [0,1,2,3,4,5,6,7,8,9];
        var index = 0;
        $('#invoices_list_table tfoot th').each( function () {
            if(jQuery.inArray(index, search_fields) !== -1){
                var title = $(this).text();
                $(this).html( '<div class="inner-addon no-left-addon"><input style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder=" '+title+'" /></div>' );
                index++;
            }
        });           
        items_search = $('#invoices_list_table').DataTable({
            ajax: {
                url: "?r=pos&f=get_all_invoices_list&p0=today&p1=0&p2=1",
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
                { "targets": [7], "searchable": true, "orderable": true, "visible": true },
                { "targets": [8], "searchable": true, "orderable": true, "visible": true },
                { "targets": [9], "searchable": true, "orderable": true, "visible": true },
                { "targets": [10], "searchable": true, "orderable": true, "visible": false },
                { "targets": [11], "searchable": true, "orderable": false, "visible": true },
                { "targets": [12], "searchable": true, "orderable": false, "visible": false },
                { "targets": [13], "searchable": true, "orderable": false, "visible": false },
                { "targets": [14], "searchable": true, "orderable": false, "visible": false },
            ],
            scrollCollapse: true,
            paging: true,
            initComplete: function(settings, json) {
                items_search.cell( ':eq(0)' ).focus();
                
                $(".selectpicker").selectpicker();
                
                $( "#sold_items_per_invoices" ).change(function() {
                    invoices_date_changed();
                });
                
                $(".sk-circle-layer").hide();
            },
            fnDrawCallback: updateRows_invoices,
        });
        
        $('#invoices_list_table').DataTable().on('dblclick',"tr", function ( e, dt, type, indexes ) {
            var sdata = items_search.row('.selected', 0).data();
            return_items(parseInt(sdata[0].split("-")[1]));
        });

        
        
        $('#invoices_list_table').DataTable().on('mousedown',"tbody tr", function ( e, dt, type, indexes ) { 
            $('.selected').removeClass("selected");
            $(this).addClass('selected');
         });
         
         $('#invoices_list_table').on( 'page.dt', function () {
            $('.selected').removeClass("selected");
        } );

        $('#invoices_list_table').on('key.dt', function(e, datatable, key, cell, originalEvent){
            if(key === 13){
                 //var sdata = items_search.row('.selected', 0).data();
                //returnQty(parseInt(sdata[0].split("-")[1]));
            }
        });
        
        $('#invoices_list_table').DataTable().columns().every( function () {
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
        $('.date_s').daterangepicker({
            //dateLimit:{month:12},
            locale: {
                format: 'YYYY-MM-DD'
            },
        });
        
        $('.date_s').on('apply.daterangepicker', function(ev) {
            ev.preventDefault();
            ev.stopPropagation();
        });
        
        //$('#invoices_list_table').DataTable().ajax.url("?r=pos&f=get_all_invoices_list&p0="+$("#sold_items_per_invoices").val()+"&p1=0").load(function () {
            //$(".sk-circle-layer").hide();
        //},false);
        
    });
    $('#invoices_itemsModal').on('hide.bs.modal', function (e) {
        lockMainPos = false;
        $("#invoices_itemsModal").remove();
    });
    $('#invoices_itemsModal').modal('show');
}

function update_invoice_date(){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    $.getJSON("?r=pos&f=update_invoice_date&p0=" + $("#invoice_id_d").val() +"&p1="+$("#invoice_new_date").val(), function (data) {

    }).done(function () {
        $("#modal_change_invoice_date_modal__").modal('hide');
        $(".sk-circle-layer").hide();
    });
}

function change_invoice_date(id){
    var modal_name = "modal_change_invoice_date_modal__";
    var modal_title = "Set Date";
    var content =
    '<div class="modal small" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header">\n\
                    <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\''+modal_name+'\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="row">\n\
                        <input type="hidden" value="'+id+'" id="invoice_id_d" />\n\
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">\n\
                            <input required id="invoice_new_date" name="invoice_new_date" value="" type="text" class="form-control" placeholder="New date">\n\
                        </div>\n\
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">\n\
                            <button onclick="update_invoice_date()" type="button" class="btn btn-primary btn-sm" style="width:100px;font-size:14px;">Set</button>\n\
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
        $('#invoice_new_date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose:true
        });
        $("#invoice_new_date").datepicker( "setDate", new Date() );

        $('#invoice_new_date').datepicker().on('changeDate', function(ev) {

        }).on('hide show', function(event) {
            event.preventDefault();
            event.stopPropagation();
        });
    });
    $('#'+modal_name).on('hide.bs.modal', function (e) {
        $("#"+modal_name).remove();
    });
    $('#'+modal_name).modal('show');
}

function updateRows_invoices(){
    var table = $('#invoices_list_table').DataTable();
    var p = table.rows({ page: 'current' }).nodes();
    var  shortcuts = "";
    for (var k = 0; k < p.length; k++){
        var index = table.row(p[k]).index();
        shortcuts = "";
        
        if(table.cell(index, 13).data()==0){
            shortcuts+='<i class="glyphicon glyphicon-edit shortcut" title="Edit" onclick="show_invoice_to_change('+parseInt(table.cell(index, 0).data().split("-")[1])+')"></i>';
        }
        
        if(print_a4_pdf_version==0){
            shortcuts+='<i class="glyphicon glyphicon-print shortcut" title="Print Receipt" onclick="printAgain('+parseInt(table.cell(index, 0).data().split("-")[1])+')"></i>';
            shortcuts+='<i class="glyphicon glyphicon-print shortcut" title="Print Gift Receipt" onclick="printAgain_Gift('+parseInt(table.cell(index, 0).data().split("-")[1])+')"></i>';

        }
        
        shortcuts+='<i class="glyphicon icon-printer-tool shortcut" title="Print Other Size" onclick="print_sheet(\''+parseInt(table.cell(index, 0).data().split("-")[1])+'\')"></i>';
        shortcuts+='<i class="glyphicon glyphicon-briefcase shortcut" title="Show Items" onclick="return_items('+parseInt(table.cell(index, 0).data().split("-")[1])+')"></i>';

        if(table.cell(index, 12).data()==0 && table.cell(index, 14).data()==0){
            shortcuts+='<i class="glyphicon glyphicon-trash shortcut red" title="Delete" onclick="delete_invoice('+parseInt(table.cell(index, 0).data().split("-")[1])+')"></i>';
        }
       
        if(enable_change_invoice_date==1 && table.cell(index, 14).data()==0)
            shortcuts+='<i class="glyphicon glyphicon-time shortcut" title="Change date" onclick="change_invoice_date('+parseInt(table.cell(index, 0).data().split("-")[1])+')"></i>';
        
        if(enable_change_invoice_client_cash_debts==1){
            shortcuts+='<i class="glyphicon glyphicon-cog shortcut" title="Changes" onclick="change_client_invoice('+parseInt(table.cell(index, 0).data().split("-")[1])+')"></i>';
        }
        
        table.cell(index, 11).data(shortcuts);


    }
}


   function delete_invoice(id){
      if(enable_only_return_password==1){
            swal({
                title: "Enter Password",
                html: true ,
                text: '<input style="z-index:999999999999" class="form-control" value="" type="password" id="passdel"/>',
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Ok",
                cancelButtonText: "Cancel",
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function(isConfirm) {
                if (isConfirm) {
                    
                    if($("#passdel").val() == only_return_password){
                        $(".sk-circle").center();
                        $(".sk-circle-layer").show();
                        $.getJSON("?r=pos&f=delete_invoice&p0="+id, function (data) {

                        }).done(function () {
                             //alert("dsds");
                            var table = $('#invoices_list_table').DataTable();
                            table.ajax.url("?r=pos&f=get_all_invoices_list&p0="+$("#sold_items_per_invoices").val()+"&p1=0&p2="+$("#operations_type").val()).load(function () {
                                $(".sk-circle-layer").hide();
                            },false);
                            $(".sk-circle-layer").hide();
                        })
                        .fail(function() {
                            logged_out_warning();
                        })
                        .always(function() {

                        });
                    }else{
                        alert("Wrong Password");
                    } 
                    
                }
            });
            setTimeout(function(){ $("#passdel").focus(); },500);
        }else{
            swal({
            title: "Are you sure?",
            type: 'warning' ,
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Return",
            cancelButtonText: LG_CANCEL,
            closeOnConfirm: true,
            closeOnCancel: true
        },
        function(isConfirm) {
            if (isConfirm) {
                $(".sk-circle").center();
                $(".sk-circle-layer").show();
                $.getJSON("?r=pos&f=delete_invoice&p0="+id, function (data) {

                 }).done(function () {
                     var table = $('#invoices_list_table').DataTable();
                     table.ajax.url("?r=pos&f=get_all_invoices_list&p0="+$("#sold_items_per_invoices").val()+"&p1=0&p2="+$("#operations_type").val()).load(function () {
                         $(".sk-circle-layer").hide();
                     },false);
                 })
                 .fail(function() {
                     logged_out_warning();
                 })
                 .always(function() {

                 });
             }
             $(".inp_rv").removeAttr("disabled");
         });
        }
   }

function invoices_date_changed(){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var table = $('#invoices_list_table').DataTable();
    table.ajax.url("?r=pos&f=get_all_invoices_list&p0="+$("#sold_items_per_invoices").val()+"&p1=0&p2="+$("#operations_type").val()).load(function () {
        $(".sk-circle-layer").hide();
    },false);
}

function _purchases(){
    if(purchasesFunctionLocked==false){
        purchasesFunctionLocked=true;
        lockMainPos = true;
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1;

        var yyyy = today.getFullYear();
        if(dd<10){dd='0'+dd;} 
        if(mm<10){mm='0'+mm;} 
        var current_date = yyyy+'-'+mm+'-'+dd;
                
        var items = "";
        $.getJSON("?r=pos&f=getPurchases&p0=" + store_id +"&p1="+current_date, function (data) {
            $.each(data, function (key, val) {
                items+='<div class="col-lg-2 col-md-2 col-sm-4 col-xs-12 inv_purchased"><div onclick="showItemsForInvoice('+val.id+')"><b>'+pad_invoice(val.id)+'</b><br/>'+val.creation_date+' </div></div>';
            });
        }).done(function () {
            var content =
                '<div class="modal" data-backdrop="static" id="purchasesModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
                <div class="modal-dialog" role="document">\n\
                    <div class="modal-content">\n\
                        <div class="modal-header"> \n\
                            <h3 class="modal-title"><i class="icon-invoice"></i>&nbsp;Received Invoice<i style="float:right;font-size:30px" class="glyphicon glyphicon-remove" onclick="closeModal(\'purchasesModal\')"></i></h3>\n\
                        </div>\n\
                        <div class="modal-body">\n\
                            <div class="row">\n\
                                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">\n\
                                    <input id="purchasesDate" onchange="purchasesChangeDate()" class="span2 col-md-2 form-control datepicker" type="text" placeholder="Select date" style="cursor:pointer">\n\
                                </div>\n\
                            </div>\n\
                            <div class="row">\n\
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="purchaseItems">\n\
                                    '+items+'\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="modal-footer">\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
            </div>';
    
            $('#purchasesModal').modal('hide');
            $("body").append(content);
            $("#purchasesModal").centerWH();
            
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose:true
            }).attr('readonly','readonly');
            $(".datepicker").datepicker( "setDate", new Date() ).attr('readonly','readonly');
            
            $('.datepicker').datepicker().on('changeDate', function(ev) {

            }).on('hide show', function(event) {
                event.preventDefault();
                event.stopPropagation();
            });

            $('#purchasesModal').on('show.bs.modal', function (e) {

            });
            $('#purchasesModal').on('hide.bs.modal', function (e) {
                lockMainPos = false;
                //$('#purchasesModal').remove();
            });
            $('#purchasesModal').modal('show');

        })
        .fail(function() {
            logged_out_warning();
        })
        .always(function() {
            purchasesFunctionLocked = false;
        });

    }
}

function purchasesChangeDate(){
    if($("#purchasesDate").val() != "" && $("#purchasesDate").val() != null){
        updatePurchaseItems();
    }
}

var showItemsForInvoiceLockedFunction = false;
function showItemsForInvoice(invoice_id){
    if(showItemsForInvoiceLockedFunction==false){
        showItemsForInvoiceLockedFunction=true;
         var items = "<span style='color:red'>Not available</span>";
        $.getJSON("?r=pos&f=getPurchasesItemsOfInvoice&p0=" + invoice_id, function (data) {
            if(data.length>0){
                items = "";
            }
            $.each(data, function (key, val) {
                items+='<div class="row" id="row_'+val.id+'"><div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 inv_purchased inv_purchased_items cit_'+val.id+'"><div><b>'+val.description+'</b><br/>QTY: <span id="iqty_'+val.id+'">'+val.qty+'</span>&nbsp;&nbsp;&nbsp;Unit Price: '+format_price_pos(val.selling_price)+'&nbsp;&nbsp;&nbsp;Total Price: <span id="total_p_'+val.id+'">'+format_price_pos(val.final_price_disc_qty)+'</span> </div></div>';
                items+='<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 inv_purchased"><input class="form-control inp_rv" value="'+val.qty+'" type="text" id="rb_qty_'+val.id+'" /></div>';
                items+='<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 inv_purchased inv_purchased_items citc_'+val.id+'">\n\
                    <div style="text-align:center">\n\
                        <i class="glyphicon glyphicon-trash" onclick="deleteQuantity('+val.id+')"></i>\n\
                    </div>\n\
                </div>';
                items+='</div>';
            });
            
        }).done(function () {
            var content =
                '<div class="modal" data-backdrop="static" id="invoiceItemsModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
                <div class="modal-dialog" role="document">\n\
                    <div class="modal-content">\n\
                        <div class="modal-header"> \n\
                            <h3 class="modal-title"><i class="icon-invoice"></i>&nbsp;Invoice Items ('+pad_invoice(invoice_id)+')<i style="float:right;font-size:30px" class="glyphicon glyphicon-remove" onclick="closeModal(\'invoiceItemsModal\')"></i></h3>\n\
                        </div>\n\
                        <div class="modal-body">\n\
                            <div class="row">\n\
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="purchaseItemsInvoice">\n\
                                    '+items+'\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="modal-footer">\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
            </div>';
  
            $('#invoiceItemsModal').modal('hide');
            $("body").append(content);
            $("#invoiceItemsModal").centerWH();
           
            //$('#purchasesModal').modal('toggle');
            $("#purchasesModal").hide();
            
            
            $('#invoiceItemsModal').on('show.bs.modal', function (e) {
                $(".inp_rv").numeric({ negative : false});
                /*$('.inp_rv').keyboard({btnClasses: 'btn btn-default btn_key',type:'tel',placement: 'left',
                    layout:[
                        [['7'],['8'],['9']],
                        [['4'],['5'],['6']],
                        [['1'],['2'],['3']],
                        [['del'],['0']],
                    ]
                });*/
            });
            $('#invoiceItemsModal').on('hide.bs.modal', function (e) {
                
                $('#invoiceItemsModal').remove();
                $("#purchasesModal").show();
                
                $(".sweet-alert").remove();
                $(".sweet-overlay").remove();
            });
            $('#invoiceItemsModal').modal('show');
            
        })
        .fail(function() {
            logged_out_warning();
        })
        .always(function() {
            showItemsForInvoiceLockedFunction = false;
        });
    }
}
    
    function deleteQuantity(id){
        if(parseInt($("#iqty_"+id).html()) >= parseInt($("#rb_qty_"+id).val())){
            $(".inp_rv").attr("disabled","disabled");
            swal({
                title: "Delete Item Quantity",
                type: 'warning' ,
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Delete",
                cancelButtonText: LG_CANCEL,
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function(isConfirm) {
                if (isConfirm) {
                    var remain = 0;
                    var total_price = 0;
                    $.getJSON("?r=pos&f=returnBackItems&p0="+id+"&p1="+$("#rb_qty_"+id).val()+"&p2=0&p3=0&p4=0&p5=0&p6=0", function (data) {
                        remain = data.remain;
                        total_price = data.total_price;
                    }).done(function () {
                        if(remain==0){
                            $("#row_"+id).remove();
                        }else{
                            $("#iqty_"+id).html(remain);
                            $("#total_p_"+id).html(format_price_pos(total_price));
                        }
                        swal("Deleted!", "", "success");
                    })
                    .fail(function() {
                        logged_out_warning();
                    })
                    .always(function() {

                    });
                    
                }else{

                }
                $(".inp_rv").removeAttr("disabled");
            });
        }else{
            alert("Error");
        }
        
        /*
        setTimeout(function(){
            $("#d_qty").numeric();
            $('#d_qty').keyboard({btnClasses: 'btn btn-default btn_key',type:'tel',placement: 'top',
            layout:[
		[['7'],['8'],['9']],
                [['4'],['5'],['6']],
                [['1'],['2'],['3']],
                [['del'],['0']],
            ]});
            $("#d_qty").focus();
        },300);
        */
    }
    
    function confirmDeleteItem(id,source){
        swal({
            title: LG_ARE_YOU_SURE,
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        },
        function(){
            var cashBoxTotalReturn = null;
            var done = null;
            $.getJSON("?r=pos&f=returnAllPurchasedItem&p0=" + id, function (data) {
                cashBoxTotalReturn = data.cashBoxTotal;
                done = data.done;
            }).done(function () {
                if(done == 1){
                    //$("#cashboxTotal").html(cashBoxTotalReturn);
                    if(source=="fi"){ 
                        $(".cit_"+id).remove();
                        $(".citc_"+id).remove();
                        
                    }else if(source=="il"){
                        //purchasedItemsChangeDate();
                    }
                    swal("Deleted!", "", "success");
                }else{
                    alert("Failed");
                }
                
            })
            .fail(function() {
                logged_out_warning();
            })
            .always(function() {

            });
        });
    }
    
    var showPurchasedItemFunctionLocked = false;
    function showPurchasedItem(date,spec_customer){
        if(showPurchasedItemFunctionLocked==false){
            showPurchasedItemFunctionLocked = true;
            var items = "";
            var expenses = "";
            var closed = "";
            var debt = "";
            var n_debt = "";
            var customer = "";
            var sum_debts = 0;
            var sum_n_debts = 0;
            var expenses_total = 0;

            var current_date = null;
            
            var footer_display = "block";

            if(date==null){
                var today = new Date();
                var dd = today.getDate();
                var mm = today.getMonth()+1;

                var yyyy = today.getFullYear();
                if(dd<10){dd='0'+dd;} 
                if(mm<10){mm='0'+mm;} 
                current_date = yyyy+'-'+mm+'-'+dd;
            }else{
                current_date = date;
            }
            
            var customer_id=0;
            if(spec_customer==1){
                customer_id = $("#customer_id").val();
                footer_display = "none";
            }
            
            var customer_id=0;
            var url = "?r=pos&f=getPurchasedList&p0="+current_date+"&p1="+customer_id;
            if(spec_customer==1){
                customer_id = $("#customer_id").val();
                footer_display = "none";
                url="?r=pos&f=getPurchasedListOfCustomer&p0="+customer_id;
            }

            $.getJSON(url, function (data) {
                $("#purchasedList").empty();
                
                if(customer_id==0){
                    expenses+="<div class='row purchasedItemsList'><div class='col-lg-12 col-md-12 col-sm-12 col-xs-12' style='font-size:18px;text-decoration: underline;"+direction_+"'><b>"+LG_EXPENSES+"</b></div></div>";
                }
                $.each(data.expenses, function (key, val) {
                    expenses_total+=parseFloat(val.value);
                    expenses+="<div class='row purchasedItemsList' "+dir_+"><div class='col-lg-8 col-md-8 col-sm-8 col-xs-8 "+pull_+"'><b>"+val.creation_date.split(" ")[1]+": "+val.description+"</div><div class='col-lg-4 col-md-4 col-sm-4 col-xs-4 purchasedItemsList "+pull_+"'>"+format_price_pos(val.value)+"</div></div>";
                });
                
                if(customer_id==0){
                    expenses+="<div class='row divider'><div class='col-lg-7 col-md-7 col-sm-7 col-xs-12'></div></div>";
                }
               
                items+="<div class='row purchasedItemsList' "+dir_+"><div class='col-lg-12 col-md-12 col-sm-12 col-xs-12' style='font-size:18px;text-decoration: underline;'><b>"+LG_SALES+"</b></div></div>";
                $.each(data.purchases, function (key, val) {
                    
                    debt = "";
                    n_debt = "";
                    customer = "";
                    if(val.closed==0){
                        closed ="notclosed";
                        debt = format_price_pos(parseFloat(val.final_price_disc_qty));
                        sum_debts+=parseFloat(val.final_price_disc_qty);
                    }else{
                        closed ="";
                        n_debt = format_price_pos(parseFloat(val.final_price_disc_qty));
                        //alert(val.final_price_disc_qty);
                        sum_n_debts+=parseFloat(val.final_price_disc_qty);
                    }

                    if(val.customer!="" && val.closed==0){
                        customer = "("+val.customer+")";
                    }
                    
         
                    if(customer_id==0){
                        items+="<div class='row purchasedItemsList' "+dir_+" id='r_"+val.id+"'><div class='col-lg-7 col-md-7 col-sm-7 col-xs-12 "+closed+" "+pull_+"''><i class='glyphicon glyphicon-trash purchasedItemsListDelete' onclick='confirmDeleteItem("+val.inv_item_id+",\"il\")'></i>&nbsp;<b>"+val.invoice_date+":</b> "+val.desc+" "+customer+"</div><div class='col-lg-1 col-md-1 col-sm-1 col-xs-1 purchasedItemsList "+pull_+"''>x "+val.qty+"</div><div class='col-lg-2 col-md-2 col-sm-2 col-xs-2 purchasedItemsList "+closed+" "+pull_+"''>"+debt+"</div><div class='col-lg-2 col-md-2 col-sm-2 col-xs-2 purchasedItemsList "+pull_+"''>"+n_debt+"</div></div>";
                    }else{
                        if(val.customer_id==customer_id){
                            items+="<div class='row purchasedItemsList' "+dir_+" id='r_"+val.id+"'><div class='col-lg-7 col-md-7 col-sm-7 col-xs-12 "+closed+" "+pull_+"''><i class='glyphicon glyphicon-trash purchasedItemsListDelete' onclick='confirmDeleteItem("+val.inv_item_id+",\"il\")'></i>&nbsp;<b>"+val.invoice_date+":</b> "+val.desc+" "+customer+"</div><div class='col-lg-1 col-md-1 col-sm-1 col-xs-1 purchasedItemsList "+pull_+"''>x "+val.qty+"</div><div class='col-lg-2 col-md-2 col-sm-2 col-xs-2 purchasedItemsList "+closed+" "+pull_+"''>"+debt+"</div><div class='col-lg-2 col-md-2 col-sm-2 col-xs-2 purchasedItemsList "+pull_+"''>"+n_debt+"</div></div>";
                        }
                    }
                });
            }).done(function () {
                    var content =
                        '<div class="modal" data-backdrop="static" id="purchasedItemsModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
                        <div class="modal-dialog" role="document">\n\
                            <div class="modal-content">\n\
                                <div class="modal-header"> \n\
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 '+pull_+'">\n\
                                        <h3 class="modal-title" style="float:'+ofloat_+'">'+LG_SALES_ITEM+'</h3>\n\
                                        <input id="purchasedItemsdate"  class="span2 form-control datepicker" type="text" placeholder="Select date" style="cursor:pointer; width:200px; float:'+ofloat_+'; margin-'+ofloat_+':10px;text-align:'+ofloat_+'; display:'+footer_display+'">\n\
                                    </div>\n\
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 '+opull_+'">\n\
                                        <i style="float:'+float_+';font-size:30px" class="glyphicon glyphicon-remove" onclick="closeModal(\'purchasedItemsModal\')"></i>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="modal-body">\n\
                                    <div class="row">\n\
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="purchaseItemsInvoice">\n\
                                            '+expenses+'\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="row">\n\
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="purchaseItemsInvoice">\n\
                                            '+items+'\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="modal-footer">\n\
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="display:'+footer_display+'"><b style="color:red">Total debts: </b><span id="span_db" style="color:red">'+format_price_pos(sum_debts)+'</span>&nbsp;&nbsp;<b style="color:red">Total expenses: </b><span id="span_exp" style="color:red">'+format_price_pos(expenses_total)+'</span>&nbsp;&nbsp;<b>Total Cash: </b><span id="span_n_db">'+format_price_pos(sum_n_debts)+'</span></div>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>';
         
                    $('#purchasedItemsModal').modal('hide');
                    $("body").append(content);
                    $("#purchasedItemsModal").centerWH();

                    $('.datepicker').datepicker({
                        format: 'yyyy-mm-dd',
                        autoclose:true
                    }).attr('readonly','readonly');
                    $(".datepicker").datepicker( "setDate", current_date ).attr('readonly','readonly');
                    
                    $('.datepicker').datepicker().on('changeDate', function(ev) {

                    }).on('hide show', function(event) {
                        event.preventDefault();
                        event.stopPropagation();
                    });

                    $( "#purchasedItemsdate" ).on( "change", purchasedItemsChangeDate);

                    $('#purchasedItemsModal').on('show.bs.modal', function (e) {
                        $('#purchasedItemsModal .modal-body').scrollTop($('#purchasedItemsModal .modal-body')[0].scrollHeight);
                    });
                    $('#purchasedItemsModal').on('hide.bs.modal', function (e) {
                        $("#purchasedItemsModal").remove();
                    });
                    $('#purchasedItemsModal').modal('show');

            }).fail(function() {
                logged_out_warning();
            })
            .always(function() {
                showPurchasedItemFunctionLocked = false;
            });
        }
    }

    function purchasedItemsChangeDate(){
        var selected_date = $("#purchasedItemsdate").val();
        $('#purchasedItemsModal').modal('toggle');
        setTimeout(function(){showPurchasedItem(selected_date,null);},500);
    }
   
function searchSoldItems(){
    
    if(lockMainPos==false){
        lockMainPos = true;
        swal({
            title: "Barcode/Code Of Item",
            html: true ,
            text: '<input class="keyboard form-control" value="" type="text" id="barcode_item_search"/>',
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Search",
            cancelButtonText: LG_CANCEL,
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function(isConfirm) {
            if (isConfirm) {
                if ($("#barcode_item_search").val() == "" || $("#barcode_item_search").val() == null) {
                    return false;
                }else{
                    return_items_by_barcode($("#barcode_item_search").val());
                }
            }
            $(".sweet-alert").remove();
            $(".sweet-overlay").remove();
            lockMainPos = false;
        });
        setTimeout(function(){
            //$("#barcode_item_search").numeric();
            /*$('#invoice_id_search').keyboard({btnClasses: 'btn btn-default btn_key',type:'tel',placement: 'top',
            layout:[
		[['7'],['8'],['9']],
                [['4'],['5'],['6']],
                [['1'],['2'],['3']],
                [['del'],['0']],
            ]});*/
            $("#barcode_item_search").focus();
        },500);
    }
}

function searchInvoice(){
    if(lockMainPos==false){
        lockMainPos = true;
        swal({
            title: "Invoice ID",
            html: true ,
            text: '<input class="keyboard form-control" value="" type="text" id="invoice_id_search"/>',
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Search",
            cancelButtonText: LG_CANCEL,
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function(isConfirm) {
            if (isConfirm) {
                if ($("#invoice_id_search").val() == "" || $("#invoice_id_search").val() == null) {
                    return false;
                }else{
                    show_invoice_to_change($("#invoice_id_search").val());
                }
            }
            $(".sweet-alert").remove();
            $(".sweet-overlay").remove();
            lockMainPos = false;
        });
        setTimeout(function(){
            $("#invoice_id_search").numeric();
            /*$('#invoice_id_search').keyboard({btnClasses: 'btn btn-default btn_key',type:'tel',placement: 'top',
            layout:[
		[['7'],['8'],['9']],
                [['4'],['5'],['6']],
                [['1'],['2'],['3']],
                [['del'],['0']],
            ]});*/
            $("#invoice_id_search").focus();
        },500);
    }
}

function operations_types_changed(){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var table = $('#return_items_table').DataTable();
    table.ajax.url("?r=pos&f=get_all_sold_items_with_vat&p0="+$("#sold_items_per_items").val()+"&p1="+$("#invoice_id_list").val()+"&p2="+$("#operations_type").val()).load(function () {
        $(".sk-circle-layer").hide();
    },false);
}

function return_items(invoice_id){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var show_date_range = "block";
    if(invoice_id>0){
        show_date_range = "none";
    }

    if($("#invoices_itemsModal").length>0){
        $( "#sold_items_per_invoices").unbind("change");
        //$("#invoices_itemsModal").modal("hide");
    }

    
    lockMainPos = true;
    var content =
    '<div class="modal" data-backdrop="static" id="return_itemsModal" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <input id="invoice_id_list" value='+invoice_id+' type="hidden" />\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title" '+dir_+'>Sold Items<i style="float:'+float_+';font-size:35px" class="glyphicon glyphicon-remove" onclick="closeModal(\'return_itemsModal\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body" id="noBarcodeItems">\n\
                    <div class="row">\n\
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">\n\
                            <input id="sold_items_per_items" class="form-control date_s" type="text" style="display:'+show_date_range+'" />\n\
                        </div>\n\
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">\n\
                            <select onchange="operations_types_changed()" id="operations_type" name="operations_type" class="selectpicker form-control" style="width:100%"><option value="1">Current Shift</option><option value="2">All my operations</option><option value="3">All operations</option></select>\n\
                        </div>\n\
                    </div>\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <table id="return_items_table" class="table table-striped table-bordered" cellspacing="0" style="width:100%">\n\
                                <thead>\n\
                                    <tr>\n\
                                        <th style="width: 70px !important;">Ref.</th>\n\
                                        <th style="width: 85px !important;">Inv Item ID</th>\n\
                                        <th style="width: 120px !important;">Date</th>\n\
                                        <th style="width: 60px !important;">Operator</th>\n\
                                        <th >Description</th>\n\
                                        <th style="width: 120px !important;">More Info</th>\n\
                                        <th>Barcode</th>\n\
                                        <th>Customer name</th>\n\
                                        <th>QTY</th>\n\
                                        <th >Price</th>\n\
                                        <th style="width: 45px !important; background-color:'+discount_bg_color+'">Disc.</th>\n\
                                        <th style="width: 30px !important;">TAX</th>\n\
                                        <th style="width: 8px !important;">Total</th>\n\
                                        <th>Inv. It. ID</th>\n\
                                        <th style="width: 50px !important;">&nbsp;</th>\n\
                                        <th style="width: 50px !important;">&nbsp;</th>\n\
                                        <th style="width: 50px !important;">Disable Return</th>\n\
                                    </tr>\n\
                                </thead>\n\
                                <tbody></tbody>\n\
                                <tfoot>\n\
                                    <tr>\n\
                                        <th>Ref.</th>\n\
                                        <th>Inv Item ID</th>\n\
                                        <th>Date</th>\n\
                                        <th>Operator</th>\n\
                                        <th>Description</th>\n\
                                        <th>Info</th>\n\
                                        <th>Barcode</th>\n\
                                        <th>Customer name</th>\n\
                                        <th>Quantity</th>\n\
                                        <th>Price</th>\n\
                                        <th>Discount</th>\n\
                                        <th>Vat</th>\n\
                                        <th>Total</th>\n\
                                        <th>&nbsp;</th>\n\
                                        <th>&nbsp;</th>\n\
                                        <th>&nbsp;</th>\n\
                                        <th>&nbsp;</th>\n\
                                    </tr>\n\
                                </tfoot>\n\
                            </table>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>\n\
    </div>';

    $('#return_itemsModal').modal('hide');
    $("body").append(content);
    $('#return_itemsModal').on('show.bs.modal', function (e) {
        var items_search = null;
        var search_fields = [0,1,2,3,4,5,6,7,8,9,10,11,12];
        var index = 0;
        $('#return_items_table tfoot th').each( function () {
            if(jQuery.inArray(index, search_fields) !== -1){
                var title = $(this).text();
                $(this).html( '<div class="inner-addon left-addon"><input style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder=" '+title+'" /></div>' );
                index++;
            }
        });
                    
        items_search = $('#return_items_table').DataTable({
            ajax: {
                url: "?r=pos&f=get_all_sold_items_with_vat&p0=today&p1="+invoice_id+"&p2=1",
                type: 'POST',
                error:function(xhr,status,error) {
                    //logged_out_warning();
                },
            },
            orderCellsTop: true,
            scrollY: "55vh",
            iDisplayLength: 100,
            aoColumnDefs: [
                { "targets": [0], "searchable": true, "orderable": true, "visible": true },
                { "targets": [1], "searchable": true, "orderable": true, "visible": false },
                { "targets": [2], "searchable": true, "orderable": true, "visible": true },
                { "targets": [3], "searchable": true, "orderable": true, "visible": true },
                { "targets": [4], "searchable": true, "orderable": true, "visible": true },
                { "targets": [5], "searchable": true, "orderable": true, "visible": false },
                { "targets": [6], "searchable": true, "orderable": true, "visible": true },
                { "targets": [7], "searchable": true, "orderable": true, "visible": true },
                { "targets": [8], "searchable": false, "orderable": true, "visible": true },
                { "targets": [9], "searchable": false, "orderable": true, "visible": true },
                { "targets": [10], "searchable": true, "orderable": true, "visible": true },
                { "targets": [11], "searchable": true, "orderable": true, "visible": true },
                { "targets": [12], "searchable": true, "orderable": true, "visible": true },
                { "targets": [13], "searchable": true, "orderable": false, "visible": false },
                { "targets": [14], "searchable": true, "orderable": false, "visible": true },
                { "targets": [15], "searchable": true, "orderable": false, "visible": false },
                { "targets": [16], "searchable": true, "orderable": false, "visible": false },
            ],
            scrollCollapse: true,
            paging: true,
            initComplete: function(settings, json) {
                items_search.cell( ':eq(0)' ).focus();
                //$('#return_items_table tfoot input:eq(2)').focus();
                
                $(".selectpicker").selectpicker();
                
                $( "#sold_items_per_items" ).change(function() {
                    sales_date_changed();
                });
                sales_date_changed();
                //$(".sk-circle-layer").hide();        
            },
            fnDrawCallback: updateRows_return_items,
        });
        
        $('#return_items_table').DataTable().on('dblclick',"tr", function ( e, dt, type, indexes ) {
            //var sdata = items_search.row('.selected', 0).data();
            //returnQty(parseInt(sdata[0].split("-")[1]));
        });
        
        
        $('#return_items_table').DataTable().on('mousedown',"tbody tr", function ( e, dt, type, indexes ) { 
            $('.selected').removeClass("selected");
            $(this).addClass('selected');
         });

        $('#return_items_table').on('key.dt', function(e, datatable, key, cell, originalEvent){
            if(key === 13){
                 //var sdata = items_search.row('.selected', 0).data();
                //returnQty(parseInt(sdata[0].split("-")[1]));
            }
        });
        
        $('#return_items_table').DataTable().columns().every( function () {
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
    
    $('#return_itemsModal').on('shown.bs.modal', function (e) {
        
        var start = moment().subtract(29, 'days');
        var end = moment();
    
        $('.date_s').daterangepicker({
            //dateLimit:{month:12},
            locale: {
                format: 'YYYY-MM-DD'
            },
        });
        
        $('.date_s').on('apply.daterangepicker', function(ev) {
            ev.preventDefault();
            ev.stopPropagation();
        });
        
        //sales_date_changed();
        
    });
    $('#return_itemsModal').on('hide.bs.modal', function (e) {
        lockMainPos = false;
        $("#return_itemsModal").remove();
        
        if($( "#sold_items_per_invoices" ).length>0){
            $( "#sold_items_per_invoices" ).change(function() {
                invoices_date_changed();
            }); 
        }
        
    });
    $('#return_itemsModal').modal('show');
}

function return_items_by_barcode(barcode){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();

    lockMainPos = true;
    var content =
    '<div class="modal" data-backdrop="static" id="return_itemsbybarcodeModal" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <input id="item_barcode_h" value="'+barcode+'" type="hidden" />\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title" '+dir_+'>Sold Items<i style="float:'+float_+';font-size:35px" class="glyphicon glyphicon-remove" onclick="closeModal(\'return_itemsbybarcodeModal\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <table id="return_items_by_barcode_table" class="table table-striped table-bordered" cellspacing="0" style="width:100%">\n\
                                <thead>\n\
                                    <tr>\n\
                                        <th style="width: 70px !important;">Ref.</th>\n\
                                        <th style="width: 85px !important;">Inv Item ID</th>\n\
                                        <th>Description</th>\n\
                                        <th>More Info</th>\n\
                                        <th style="width: 100px !important;">Barcode</th>\n\
                                        <th style="width: 150px !important;">Customer name</th>\n\
                                        <th style="width: 120px !important;">Date</th>\n\
                                        <th style="width: 45px !important;">QTY</th>\n\
                                        <th style="width: 110px !important;">Sold Price</th>\n\
                                        <th style="width: 42px !important; background-color:'+discount_bg_color+' !important;">Disc.</th>\n\
                                        <th style="width: 30px !important; ">TAX</th>\n\
                                        <th>Inv. It. ID</th>\n\
                                        <th style="width: 50px !important;">&nbsp;</th>\n\
                                    </tr>\n\
                                </thead>\n\
                                <tbody></tbody>\n\
                                <tfoot>\n\
                                    <tr>\n\
                                        <th>Ref.</th>\n\
                                        <th>Inv Item ID</th>\n\
                                        <th>Description</th>\n\
                                        <th>Info</th>\n\
                                        <th>Barcode</th>\n\
                                        <th>Customer name</th>\n\
                                        <th>Date</th>\n\
                                        <th>Quantity</th>\n\
                                        <th>Sold Price</th>\n\
                                        <th>Discount</th>\n\
                                        <th>Vat</th>\n\
                                        <th>&nbsp;</th>\n\
                                        <th>&nbsp;</th>\n\
                                    </tr>\n\
                                </tfoot>\n\
                            </table>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>\n\
    </div>';
    
    $('#return_itemsbybarcodeModal').modal('hide');
    $("body").append(content);
    $('#return_itemsbybarcodeModal').on('show.bs.modal', function (e) {
        var items_search = null;
        var search_fields = [0,1,2,3,4,5,6,7,8,9,10];
        var index = 0;
        $('#return_items_by_barcode_table tfoot th').each( function () {
            if(jQuery.inArray(index, search_fields) !== -1){
                var title = $(this).text();
                $(this).html( '<div class="inner-addon left-addon"><input style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder=" '+title+'" /></div>' );
                index++;
            }
        });
                    
        items_search = $('#return_items_by_barcode_table').DataTable({
            ajax: {
                url: "?r=pos&f=get_all_sold_items_with_vat_by_barcode&p1="+barcode,
                type: 'POST',
                error:function(xhr,status,error) {
                    //logged_out_warning();
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
                { "targets": [7], "searchable": true, "orderable": true, "visible": true },
                { "targets": [8], "searchable": false, "orderable": false, "visible": true },
                { "targets": [9], "searchable": false, "orderable": false, "visible": true },
                { "targets": [10], "searchable": true, "orderable": false, "visible": true },
                { "targets": [11], "searchable": true, "orderable": false, "visible": false },
                { "targets": [12], "searchable": true, "orderable": false, "visible": true },
            ],
            scrollCollapse: true,
            paging: true,
            initComplete: function(settings, json) {
                $(".sk-circle-layer").hide();        
            },
            fnDrawCallback: updateRows_return_items_by_barcode,
        });
        
        $('#return_items_by_barcode_table').DataTable().on('dblclick',"tr", function ( e, dt, type, indexes ) {
            //var sdata = items_search.row('.selected', 0).data();
            //returnQty(parseInt(sdata[0].split("-")[1]));
        });

        
        
         $('#return_items_by_barcode_table').DataTable().on('mousedown',"tbody tr", function ( e, dt, type, indexes ) { 
            $('.selected').removeClass("selected");
            $(this).addClass('selected');
         });

        $('#return_items_by_barcode_table').on('key.dt', function(e, datatable, key, cell, originalEvent){
            if(key === 13){
                 //var sdata = items_search.row('.selected', 0).data();
                //returnQty(parseInt(sdata[0].split("-")[1]));
            }
        });
        
        $('#return_items_by_barcode_table').DataTable().columns().every( function () {
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
    
    $('#return_itemsbybarcodeModal').on('shown.bs.modal', function (e) {
        
    });
    $('#return_itemsbybarcodeModal').on('hide.bs.modal', function (e) {
        lockMainPos = false;
        $("#return_itemsbybarcodeModal").remove();
    });
    $('#return_itemsbybarcodeModal').modal('show');
}

function updateRows_return_items_by_barcode(){
    var table = $('#return_items_by_barcode_table').DataTable();
    var p = table.rows({ page: 'current' }).nodes();
    for (var k = 0; k < p.length; k++){
        var index = table.row(p[k]).index();
        table.cell(index, 12).data('<button onclick="returnQty('+parseInt(table.cell(index, 11).data())+')" type="button" class="btn btn-xs btn-info" style="width:100%; font-size:13px;"><b>Return</b></button>');
    }
}

function updateRows_return_items(){
    var table = $('#return_items_table').DataTable();
    var p = table.rows({ page: 'current' }).nodes();
    for (var k = 0; k < p.length; k++){
        var index = table.row(p[k]).index();
        
  
        if(table.cell(index, 16).data()=="0")
            table.cell(index, 14).data('<button onclick="returnQty('+parseInt(table.cell(index, 13).data())+')" type="button" class="btn btn-xs btn-info" style="width:100%; font-size:13px;"><b>Return</b></button>');
        
        if(parseFloat(table.cell(index, 15).data())!=0){
            $(table.row(p[k]).node()).addClass('hilighted_row');
        }
    }
}

function sales_date_changed(){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var table = $('#return_items_table').DataTable();
    table.ajax.url("?r=pos&f=get_all_sold_items_with_vat&p0="+$("#sold_items_per_items").val()+"&p1="+$("#invoice_id_list").val()+"&p2="+$("#operations_type").val()).load(function () {
        $(".sk-circle-layer").hide();
    },false);
}


function return_back(id,return_sms_fees,extra_transfer_fees,customer_acc){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var _data = [];
    $.getJSON("?r=pos&f=get_item_invoice_info&p0="+id, function (data) {
        _data = data;
    }).done(function () {
        $(".sk-circle-layer").hide();
        
        var modal_name = "modal_all_returnback____";
        var modal_title = "Return Cash Info";
        
        var selling_price = _data[0].unit_selling;
        
        var suggested_cash = "";
        if(_data[0].return_lbp_invoice_rate_f<_data[0].return_lbp_current_rate_f){
            suggested_cash = _data[0].return_lbp_invoice_rate_f;
        }
        if(_data[0].return_lbp_invoice_rate_f>=_data[0].return_lbp_current_rate_f){
            suggested_cash = _data[0].return_lbp_current_rate_f;
        }
        
        var content =
        '<div class="modal medium" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <input type="hidden" name="cacc" id="cacc" value="0" />\n\
                <div class="modal-content">\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\''+modal_name+'\')"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="font-size:17px;">\n\
                                <b>Sold Price:</b> '+parseFloat(selling_price).toFixed(2)+' USD\n\
                            </div>\n\
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="font-size:17px;">\n\
                                <b>Invoice Rate:</b> '+_data[0].rate_formated+'\n\
                            </div>\n\
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="font-size:17px;">\n\
                                <b>Current Rate:</b> '+_data[0].current_rate_formated+'\n\
                            </div>\n\
                        </div>\n\
                        <div class="row" style="margin-top:6px;">\n\
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="font-size:17px;">\n\
                                <b>Return USD:</b> <span id="difference_inv">'+parseFloat(selling_price).toFixed(2)+'</span>\n\
                            </div>\n\
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="font-size:17px;">\n\
                                <b>Return LBP:</b> '+_data[0].return_lbp_invoice_rate_f+'\n\
                            </div>\n\
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="font-size:17px;">\n\
                                <b>Return LBP:</b> '+_data[0].return_lbp_current_rate_f+'\n\
                            </div>\n\
                        </div>\n\
                        <div class="row " style="margin-top:10px;">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="cash_d_container">\n\
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
                    <div class="modal-footer">\n\
                       <button onclick="execute_do_return('+id+','+return_sms_fees+','+extra_transfer_fees+','+customer_acc+')" type="button" class="btn btn-danger" style="width:100%">RETURN</button>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>';
       
        $('#'+modal_name).modal('hide');
        $("body").append(content);
        $('#'+modal_name).on('show.bs.modal', function (e) {

        });

        $('#'+modal_name).on('shown.bs.modal', function (e) {
            
            set_current_cash_var(2);
            
            cleaves_id("cash_lbp",0);
            cleaves_id("cash_usd",5);
            cash_changed_usd($("#cash_usd"));
        });
        $('#'+modal_name).on('hide.bs.modal', function (e) {
            $("#"+modal_name).remove();
        });
        $('#'+modal_name).modal('show');
    });
    
}

function execute_do_return(id,return_sms_fees,extra_transfer_fees,customer_acc){
    

    if($("#cash_lbp_r").val()=="" && $("#cash_usd_r").val()==""){
        $("#cash_lbp_r").addClass("error");
        $("#cash_usd_r").addClass("error");
        return;
    }
    
    var return_lbp=0;
    var return_usd=0;
    if(mask_clean($("#cash_lbp_r").val())>0){
        return_lbp=mask_clean($("#cash_lbp_r").val());
    }
    if(mask_clean($("#cash_usd_r").val())>0){
        return_usd=mask_clean($("#cash_usd_r").val());
    }
    
    
    
   
    
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var remain = 0;
    var total_price = 0;
    var returned_id = 0;
        var unique_items = 0;

    
    
    
    var cash_usd=0;
    if($("#cash_usd").length>0){
        cash_usd=mask_clean($("#cash_usd").val());
        if(cash_usd==""){
            cash_usd=0;
        }
    }
    var cash_lbp=0;
    if($("#cash_lbp").length>0){
        cash_lbp=mask_clean($("#cash_lbp").val());
        if(cash_lbp==""){
            cash_lbp=0;
        }
    }
    
    
    
    var returned_cash_usd=0;
    if($("#r_cash_usd_action").length>0){
        returned_cash_usd=mask_clean($("#r_cash_usd_action").val());
        if(returned_cash_usd==""){
            returned_cash_usd=0;
        }
    }

    var returned_cash_lbp=0;
    if($("#r_cash_lbp_action").length>0){
        returned_cash_lbp=mask_clean($("#r_cash_lbp_action").val());
        if(returned_cash_lbp==""){
            returned_cash_lbp=0;
        }
    }


    var r_cash_usd=0;
    if($("#r_cash_usd").length>0){
        r_cash_usd=mask_clean($("#r_cash_usd").val());
        if(r_cash_usd==""){
            r_cash_usd=0;
        }
    }

    var r_cash_lbp=0;
    if($("#r_cash_lbp").length>0){
        r_cash_lbp=mask_clean($("#r_cash_lbp").val());
        if(r_cash_lbp==""){
            r_cash_lbp=0;
        }
    }
      
    
    if($("#modal_all_returnback____").length>0){
        $("#modal_all_returnback____").modal("hide");
    }
    
    $.getJSON("?r=pos&f=returnBackItems_new&p0="+id+"&p1=1&p2="+return_sms_fees+"&p3="+extra_transfer_fees+"&p4="+customer_acc+"&p5="+cash_usd+"&p6="+cash_lbp+"&p7="+returned_cash_usd+"&p8="+returned_cash_lbp+"&p9="+r_cash_usd+"&p10="+r_cash_lbp, function (data) {
        returned_id = data.returned_id;
        unique_items = data.unique_items;
    }).done(function () {
        //swal("Returned!", "", "success");
        $(".sweet-alert,.sweet-overlay").remove();
        if($('#return_items_table').length>0){
            var table = $('#return_items_table').DataTable();
            print_returned_item(returned_id);
            table.ajax.url("?r=pos&f=get_all_sold_items_with_vat&p0="+$("#sold_items_per_items").val()+"&p1="+$("#invoice_id_list").val()+"&p2="+$("#operations_type").val()).load(function () {
                $(".sk-circle-layer").hide();
            },false);
        }
        if($('#return_items_by_barcode_table').length>0){
            var table = $('#return_items_by_barcode_table').DataTable();
            print_returned_item(returned_id);
            table.ajax.url("?r=pos&f=get_all_sold_items_with_vat_by_barcode&p1="+$("#item_barcode_h").val()).load(function () {
                $(".sk-circle-layer").hide();
            },false);
        }
        
        if(unique_items.length>0){
            alert("There are multiple IMEIs assigned to this invoice, and you will need to release them manually.");
        }

    })
    .fail(function() {
        logged_out_warning();
    })
    .always(function() {

    });
}

/* here update */
function do_return(id,return_sms_fees,extra_transfer_fees,customer_acc){
    if(usd_but_show_lbp_priority==1){
        $(".sweet-alert,.sweet-overlay").remove();
        return_back(id,return_sms_fees,extra_transfer_fees,customer_acc);
        return;
    }else{
        execute_do_return(id,return_sms_fees,extra_transfer_fees,customer_acc)
    }   
}

function choose_customer_on_account(inv_item_id){
    
    $(".sk-circle").center();
    $(".sk-circle-layer").show(); 
    var modal_name = "modal_all_cacc____";
    var modal_title = "If the return value is not cash, choose the customer account";
    
    var content =
    '<div class="modal medium" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <input type="hidden" name="cacc" id="cacc" value="0" />\n\
            <div class="modal-content">\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\''+modal_name+'\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <input autocomplete="off" id="on_account_id" name="on_account_id" data-provide="typeahead" type="text" class="form-control med_input" placeholder="Search customer account">\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
                <div class="modal-footer">\n\
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">\n\
                        <button onclick="__returnQty('+inv_item_id+',1)" type="button" class="btn btn-warning" style="width:100%">On Account</button>\n\
                    </div>\n\
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">\n\
                        &nbsp;\n\
                    </div>\n\
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">\n\
                        <button onclick="__returnQty('+inv_item_id+',0)" type="button" class="btn btn-success" style="width:100%" id="cashbtn">Cash</button>\n\
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
   
        var url="?r=pos&f=get_all_customers_acc&p0=0&p1="+inv_item_id;
        var CUSTOMERS_ON_ACC = [];
        var cid = 0;
        var cid_name = 0;
        var $input_prepare_search_items = null;
        $.getJSON(url, function (data) {
            var name_tmp="";
             cid = data.invoice_info[0].customer_id;
            $.each(data.customers, function (key, val) {
                name_tmp = val.name+" "+val.middle_name+" "+val.last_name+" "+val.phone;
                if(val.phone.lenght>0){
                    name_tmp += val.phone;
                }
                if(cid>0 && val.id==cid){
                    cid_name = name_tmp;
                }
                
                CUSTOMERS_ON_ACC.push({id:val.id,name:name_tmp,currency_id:val.currency_id});
            });
           
            
        }).done(function () {
            $("#on_account_id").typeahead('destroy');
            $input_prepare_search_items = $("#on_account_id");
            $input_prepare_search_items.typeahead({
                source: CUSTOMERS_ON_ACC,
                items: 1000000,
            });
            
            $input_prepare_search_items.change(function() {
                var current = $input_prepare_search_items.typeahead("getActive");
                if (current) {
                    if (current.name == $input_prepare_search_items.val()) {
                        $("#cacc").val(current.id);
                        $("#cashbtn").hide();
                    }else{
                        $("#on_account_id").val("");
                        $("#cacc").val(0);
                        $("#cashbtn").show();
                    }
                }else{
                    $("#on_account_id").val("");
                    $("#cacc").val(0);
                    $("#cashbtn").show();
                }
            });
            
            if(cid>0){
               $("#cacc").val(cid);
               $("#on_account_id").val(cid_name);
               $("#on_account_id").attr("readonly","readonly");
            }
        });
        
        
        
        $(".sk-circle-layer").hide(); 
        
    });
    $('#'+modal_name).on('hide.bs.modal', function (e) {
        $("#"+modal_name).remove();
    });
    $('#'+modal_name).modal('show');
    
    
}

function returnQty(id){
    if(OMT_VERSION==1){
        choose_customer_on_account(id);
    }else{
        __returnQty(id,0);
    }
}

function __returnQty(id,on_acc){
    if(on_acc==1 && $("#cacc").val()==0){
        swal("Choose customer account first");
        return;
    }
    
    var cacc = 0;
    if(OMT_VERSION==1){
        cacc = $("#cacc").val();
        if(on_acc==0){
            cacc=0;
        }
        $('#modal_all_cacc____').modal('hide');
    }
    
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var is_credit_transfer = 0;
    $.getJSON("?r=pos&f=check_if_credit_transfer&p0="+id, function (data) {
        if(typeof data[0] !== 'undefined' && data[0].sms_fees>0){
            is_credit_transfer = 1;
        }else{
            is_credit_transfer = 0;
        }
    }).done(function () {
        $(".sk-circle-layer").hide();
        if(is_credit_transfer>0){
            swal({
                title: "return SMS fees to the device balance?",
                html: true ,
                text: (additional_credit_transfer_sms_cost*100)+' Cent Lost<br/><input type="checkbox" id="fees_extra" value="" style="width:25px;height:25px;" />',
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function(isConfirm) {
                
                var fees_extra = 0;
                if($('#fees_extra').is(':checked')){
                    fees_extra=1;
                }
                
                
                if (isConfirm) {
                    if(enable_only_return_password==1){
                        setTimeout(function(){ 
                            swal({
                                title: "Enter Password",
                                html: true ,
                                text: '<input style="z-index:999999999999" class="form-control" value="" type="text" id="passdel"/>',
                                showCancelButton: true,
                                confirmButtonClass: "btn-danger",
                                confirmButtonText: "Ok",
                                cancelButtonText: "Cancel",
                                closeOnConfirm: true,
                                closeOnCancel: true
                            },
                            function(isConfirm) {
                                if (isConfirm) {
                                    if($("#passdel").val() == only_return_password){
                                        do_return(id,1,fees_extra,cacc);
                                    }else{
                                        alert("Wrong Password");
                                    } 
                                }
                            });
                            setTimeout(function(){ $("#passdel").focus(); },500);
                        },500);
                        
                    }else{
                        setTimeout(function(){
                            swal({
                                title: "Return Item",
                                type: 'warning' ,
                                showCancelButton: true,
                                confirmButtonClass: "btn-danger",
                                confirmButtonText: "Return",
                                cancelButtonText: LG_CANCEL,
                                closeOnConfirm: false,
                                closeOnCancel: true
                            },
                            function(isConfirm) {
                                if (isConfirm) {
                                    do_return(id,1,fees_extra,cacc);
                                }
                            });
                        },500);
                   }
                }else{
                    if(enable_only_return_password==1){
                        setTimeout(function(){ 
                            swal({
                                title: "Enter Password",
                                html: true ,
                                text: '<input style="z-index:999999999999" class="form-control" value="" type="text" id="passdel"/>',
                                showCancelButton: true,
                                confirmButtonClass: "btn-danger",
                                confirmButtonText: "Ok",
                                cancelButtonText: "Cancel",
                                closeOnConfirm: true,
                                closeOnCancel: true
                            },
                            function(isConfirm) {
                                if (isConfirm) {
                                    if($("#passdel").val() == only_return_password){
                                        do_return(id,0,fees_extra,cacc);
                                    }else{
                                        alert("Wrong Password");
                                    } 
                                }
                            });
                            setTimeout(function(){ $("#passdel").focus(); },500);
                        },500);
                        
                    }else{
                        setTimeout(function(){
                            swal({
                                title: "Return Item",
                                type: 'warning' ,
                                showCancelButton: true,
                                confirmButtonClass: "btn-danger",
                                confirmButtonText: "Return",
                                cancelButtonText: LG_CANCEL,
                                closeOnConfirm: false,
                                closeOnCancel: true
                            },
                            function(isConfirm) {
                                if (isConfirm) {
                                    do_return(id,0,fees_extra,cacc);
                                }
                            });
                        },500);
                   }
                }
            });
        }else{
            if(enable_only_return_password==1){
                setTimeout(function(){
                    swal({
                        title: "Enter Password",
                        html: true ,
                        text: '<input style="z-index:999999999999" class="form-control" value="" type="text" id="passdel"/>',
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "Ok",
                        cancelButtonText: "Cancel",
                        closeOnConfirm: true,
                        closeOnCancel: true
                    },
                    function(isConfirm) {
                        if (isConfirm) {

                            if($("#passdel").val() == only_return_password){
                                do_return(id,0,0,cacc);
                            }else{
                                alert("Wrong Password");
                            } 
                        }
                    });

                    setTimeout(function(){ $("#passdel").focus(); },500);
                },500);
            }else{
                setTimeout(function(){
                    swal({
                        title: "Return Item",
                        type: 'warning' ,
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "Return",
                        cancelButtonText: LG_CANCEL,
                        closeOnConfirm: false,
                        closeOnCancel: true
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            do_return(id,0,0,cacc);
                        }
                    });
                },500);
           }
        }
        
        
    });
}

function print_returned_item(returned_id){
    $.getJSON("?r=print_invoice&f=print_returned_item&p0="+returned_id, function (data) {
        
    }).done(function () {

    });
}


function history_of_cashboxes(invoice_id){
    lockMainPos = true;
    var content =
    '<div class="modal" data-backdrop="static" id="history_of_cashboxesModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <input id="invoice_id_list" value='+invoice_id+' type="hidden" />\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title" '+dir_+'>History of cashbox<i style="float:'+float_+';font-size:35px" class="glyphicon glyphicon-remove" onclick="closeModal(\'history_of_cashboxesModal\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body" id="noBarcodeItems">\n\
                    <div class="row">\n\
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">\n\
                            <input id="history_cashbox_date" class="form-control date_s" type="text" />\n\
                        </div>\n\
                    </div>\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <table id="history_of_cashboxes_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                <thead>\n\
                                    <tr>\n\
                                        <th style="width: 80px !important;">Ref.</th>\n\
                                        <th style="width: 150px !important;">From Date</th>\n\
                                        <th style="width: 150px !important;">To Date</th>\n\
                                        <th>&nbsp;</th>\n\
                                    </tr>\n\
                                </thead>\n\
                                <tfoot>\n\
                                    <tr>\n\
                                        <th>Ref.</th>\n\
                                        <th>From Date</th>\n\
                                        <th>To Date</th>\n\
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
  
    $('#history_of_cashboxesModal').modal('hide');
    
    $("body").append(content);
    $('#history_of_cashboxesModal').on('show.bs.modal', function (e) {
        
        var h_cashboxes = null;
        var search_fields = [0,1,2,3];
        var index = 0;
        $('#history_of_cashboxes_table tfoot th').each( function () {
            if(jQuery.inArray(index, search_fields) !== -1){
                var title = $(this).text();
                $(this).html( '<div class="inner-addon left-addon"><input style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder=" '+title+'" /></div>' );
                index++;
            }
        });
                    
        h_cashboxes = $('#history_of_cashboxes_table').DataTable({
            ajax: {
                url: "?r=pos&f=get_all_cashboxes&p0=today",
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
                { "targets": [3], "searchable": true, "orderable": false, "visible": true },
            ],
            scrollCollapse: true,
            paging: true,
            initComplete: function(settings, json) {
               // h_cashboxes.cell( ':eq(0)' ).focus();
                //$('#history_of_cashboxes_table tfoot input:eq(0)').focus();
                
                $( "#history_cashbox_date" ).change(function() {
                    history_cashbox_changed();
                });
                        
            },
            fnDrawCallback: updateRows_history_cashbox,
        });
        
        $('#history_of_cashboxes_table').DataTable().on('dblclick',"tr", function ( e, dt, type, indexes ) {
            //var sdata = items_search.row('.selected', 0).data();
            //returnQty(parseInt(sdata[0].split("-")[1]));
        });

        
         $('#history_of_cashboxes_table').DataTable().on('mousedown',"tbody tr", function ( e, dt, type, indexes ) { 
            $('.selected').removeClass("selected");
            $(this).addClass('selected');
         });

        $('#history_of_cashboxes_table').on('key.dt', function(e, datatable, key, cell, originalEvent){
            if(key === 13){
                 //var sdata = items_search.row('.selected', 0).data();
                //returnQty(parseInt(sdata[0].split("-")[1]));
            }
        });
        
        $('#history_of_cashboxes_table').DataTable().columns().every( function () {
            var that = this;
            $( 'input', this.footer() ).on( 'keyup change', function () {
                h_cashboxes.keys.disable();
                if ( that.search() !== this.value ) {
                    that.search( this.value ).draw();
                }
                h_cashboxes.keys.enable();
            } );
        } );
    });
    
    $('#history_of_cashboxesModal').on('shown.bs.modal', function (e) {
        $('.date_s').daterangepicker({
            //dateLimit:{month:12},
            locale: {
                format: 'YYYY-MM-DD'
            },
        });
        
        $('.date_s').on('apply.daterangepicker', function(ev) {
            ev.preventDefault();
            ev.stopPropagation();
        });
        
    });
    $('#history_of_cashboxesModal').on('hide.bs.modal', function (e) {
        lockMainPos = false;
        $("#history_of_cashboxesModal").remove();
    });
    $('#history_of_cashboxesModal').modal('show');
}

function history_cashbox_changed(){
    var table = $('#history_of_cashboxes_table').DataTable();
    table.ajax.url("?r=pos&f=get_all_cashboxes&p0="+$("#history_cashbox_date").val()).load(function () {

    },false);
}


function updateRows_history_cashbox(){
    var table = $('#history_of_cashboxes_table').DataTable();
    var p = table.rows({ page: 'current' }).nodes();
    for (var k = 0; k < p.length; k++){
        var index = table.row(p[k]).index();
        table.cell(index, 3).data('<button onclick="direct_print_full_report('+parseInt(table.cell(index, 0).data())+')" type="button" class="btn btn-xs btn-info" style="width:100px; font-size:13px;"><b>Print Report</b></button>');
    }
}