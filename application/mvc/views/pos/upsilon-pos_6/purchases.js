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
                $("#dsc_"+invoice_id).html(" "+format_price_already_fixed(0));
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

function updatePurchaseItems(){
    var items = "";

    $.getJSON("?r=pos&f=getPurchases&p0=" + store_id +"&p1="+$("#purchasesDate").val(), function (data) {
        $.each(data, function (key, val) {
            items+='<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 inv_purchased"><div><b>'+pad_invoice(val.id)+' </b><br/><b>Value:</b> '+format_price_already_fixed(val.total_value)+'<br/><b>Discount:</b><span id="dsc_'+val.id+'"> '+format_price_already_fixed(val.invoice_discount)+'</span><br/><b>Date:</b> '+val.creation_date+'<button onclick="printAgain('+val.id+')" type="button" class="btn btn-default print_again">&nbsp;Print&nbsp;</button><button onclick="cancelDiscount('+val.id+')" type="button" class="btn btn-default cancelDiscount">Cancel Disc.</button><button onclick="showItemsForInvoice('+val.id+')" type="button" class="btn btn-default showItems">Show Items</button></div></div>';
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
                    </div>\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <table id="invoices_list_table" class="table table-striped table-bordered" cellspacing="0">\n\
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
    $("#invoices_itemsModal").remove();
    $("body").append(content);
    $('#invoices_itemsModal').on('show.bs.modal', function (e) {
        
        var items_search = null;
        var search_fields = [0,1,2,3,4,5,6];
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
                url: "?r=pos&f=get_all_invoices_list&p0=today&p1=0",
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
            initComplete: function(settings, json) {
                items_search.cell( ':eq(0)' ).focus();
                $('#invoices_list_table tfoot input:eq(2)').focus();
                
                $( "#sold_items_per_invoices" ).change(function() {
                    invoices_date_changed();
                });
                
                 //invoices_date_changed();
                
                $(".sk-circle-layer").hide();
            },
            fnDrawCallback: updateRows_invoices,
        });
        
        $('#invoices_list_table').DataTable().on('dblclick',"tr", function ( e, dt, type, indexes ) {
            var sdata = items_search.row('.selected', 0).data();
            return_items(parseInt(sdata[0].split("-")[1]));
        });

        $('#invoices_list_table').on('key-focus.dt', function(e, datatable, cell){
            $(items_search.row(cell.index().row).node()).addClass('selected');
        });

        $('#invoices_list_table').on('key-blur.dt', function(e, datatable, cell){
            $(items_search.row(cell.index().row).node()).removeClass('selected');
        });

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

function updateRows_invoices(){
    var table = $('#invoices_list_table').DataTable();
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

        //table.cell(index, 8).data('<button onclick="printAgain('+parseInt(table.cell(index, 0).data().split("-")[1])+')"  type="button" class="btn btn-xs btn-info" style="width:100%; font-size:13px;"><b>Print Invoice</b></button>');
        //table.cell(index, 7).data('<button onclick="return_items('+parseInt(table.cell(index, 0).data().split("-")[1])+')"  type="button" class="btn btn-xs btn-info" style="width:100%; font-size:13px;"><b>Show items</b></button>');
        //table.cell(index, 9).data('<button onclick="print_sheet(\''+parseInt(table.cell(index, 0).data().split("-")[1])+'\')" type="button" class="btn btn-xs btn-info" style="width:100%; font-size:13px;"><b>Print A4</b></button>');
        //table.cell(index, 10).data('<button onclick="delete_invoice('+parseInt(table.cell(index, 0).data().split("-")[1])+')"  type="button" class="btn btn-xs btn-info" style="width:100%; font-size:13px;"><b>Delete</b></button>');
        //table.cell(index, 11).data('<button onclick="show_invoice_to_change('+parseInt(table.cell(index, 0).data().split("-")[1])+')"  type="button" class="btn btn-xs btn-info" style="width:100%; font-size:13px;"><b>Edit</b></button>');

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
                            var table = $('#invoices_list_table').DataTable();
                            table.ajax.url("?r=pos&f=get_all_invoices_list&p0=today&p1=0").load(function () {
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
                     table.ajax.url("?r=pos&f=get_all_invoices_list&p0=today&p1=0").load(function () {
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
    table.ajax.url("?r=pos&f=get_all_invoices_list&p0="+$("#sold_items_per_invoices").val()+"&p1=0").load(function () {
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
            $("#purchasesModal").remove();
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
                items+='<div class="row" id="row_'+val.id+'"><div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 inv_purchased inv_purchased_items cit_'+val.id+'"><div><b>'+val.description+'</b><br/>QTY: <span id="iqty_'+val.id+'">'+val.qty+'</span>&nbsp;&nbsp;&nbsp;Unit Price: '+format_price_already_fixed(val.selling_price)+'&nbsp;&nbsp;&nbsp;Total Price: <span id="total_p_'+val.id+'">'+format_price_already_fixed(val.final_price_disc_qty)+'</span> </div></div>';
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
            $("#invoiceItemsModal").remove();
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
                    $.getJSON("?r=pos&f=returnBackItems&p0="+id+"&p1="+$("#rb_qty_"+id).val()+"&p2=0", function (data) {
                        remain = data.remain;
                        total_price = data.total_price;
                    }).done(function () {
                        if(remain==0){
                            $("#row_"+id).remove();
                        }else{
                            $("#iqty_"+id).html(remain);
                            $("#total_p_"+id).html(format_price_already_fixed(total_price));
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
                    expenses+="<div class='row purchasedItemsList' "+dir_+"><div class='col-lg-8 col-md-8 col-sm-8 col-xs-8 "+pull_+"'><b>"+val.creation_date.split(" ")[1]+": "+val.description+"</div><div class='col-lg-4 col-md-4 col-sm-4 col-xs-4 purchasedItemsList "+pull_+"'>"+format_price_already_fixed(val.value)+"</div></div>";
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
                        debt = format_price(parseFloat(val.final_price_disc_qty));
                        sum_debts+=parseFloat(val.final_price_disc_qty);
                    }else{
                        closed ="";
                        n_debt = format_price(parseFloat(val.final_price_disc_qty));
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
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="display:'+footer_display+'"><b style="color:red">Total debts: </b><span id="span_db" style="color:red">'+format_price(sum_debts)+'</span>&nbsp;&nbsp;<b style="color:red">Total expenses: </b><span id="span_exp" style="color:red">'+format_price(expenses_total)+'</span>&nbsp;&nbsp;<b>Total Cash: </b><span id="span_n_db">'+format_price(sum_n_debts)+'</span></div>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>';
                    $("#purchasedItemsModal").remove();
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
            title: "Barcode Of Item",
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
                    return_items($("#invoice_id_search").val());
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
                    </div>\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <table id="return_items_table" class="table table-striped table-bordered" cellspacing="0" style="width:100%">\n\
                                <thead>\n\
                                    <tr>\n\
                                        <th style="width: 70px !important;">Ref.</th>\n\
                                        <th style="width: 85px !important;">Inv Item ID</th>\n\
                                        <th >Description</th>\n\
                                        <th style="width: 120px !important;">More Info</th>\n\
                                        <th style="width: 100px !important;">Barcode</th>\n\
                                        <th style="width: 120px !important;">Customer name</th>\n\
                                        <th style="width: 120px !important;">Date</th>\n\
                                        <th style="width: 45px !important;">QTY</th>\n\
                                        <th style="width: 100px !important;">Price</th>\n\
                                        <th style="width: 45px !important; background-color:'+discount_bg_color+'">Disc.</th>\n\
                                        <th style="width: 30px !important;">Vat</th>\n\
                                        <th style="width: 100px !important;">Total</th>\n\
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
                                        <th>Price</th>\n\
                                        <th>Discount</th>\n\
                                        <th>Vat</th>\n\
                                        <th>Total</th>\n\
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
    $("#return_itemsModal").remove();
    $("body").append(content);
    $('#return_itemsModal').on('show.bs.modal', function (e) {
        var items_search = null;
        var search_fields = [0,1,2,3,4,5,6,7,8,9,10];
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
                url: "?r=pos&f=get_all_sold_items_with_vat&p0=today&p1="+invoice_id,
                type: 'POST',
                error:function(xhr,status,error) {
                    //logged_out_warning();
                },
            },
            orderCellsTop: true,
            aoColumnDefs: [
                { "targets": [0], "searchable": true, "orderable": true, "visible": false },
                { "targets": [1], "searchable": true, "orderable": true, "visible": true },
                { "targets": [2], "searchable": true, "orderable": true, "visible": true },
                { "targets": [3], "searchable": true, "orderable": true, "visible": true },
                { "targets": [4], "searchable": true, "orderable": true, "visible": false },
                { "targets": [5], "searchable": true, "orderable": true, "visible": true },
                { "targets": [6], "searchable": true, "orderable": true, "visible": true },
                { "targets": [7], "searchable": true, "orderable": true, "visible": true },
                { "targets": [8], "searchable": false, "orderable": false, "visible": true },
                { "targets": [9], "searchable": false, "orderable": false, "visible": true },
                { "targets": [10], "searchable": true, "orderable": false, "visible": true },
                { "targets": [11], "searchable": true, "orderable": false, "visible": true },
                { "targets": [12], "searchable": true, "orderable": false, "visible": false },
                { "targets": [13], "searchable": true, "orderable": false, "visible": true },
            ],
            scrollCollapse: true,
            paging: true,
            initComplete: function(settings, json) {
                items_search.cell( ':eq(0)' ).focus();
                $('#return_items_table tfoot input:eq(2)').focus();
                
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

        $('#return_items_table').on('key-focus.dt', function(e, datatable, cell){
            $(items_search.row(cell.index().row).node()).addClass('selected');
        });

        $('#return_items_table').on('key-blur.dt', function(e, datatable, cell){
            $(items_search.row(cell.index().row).node()).removeClass('selected');
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
                                        <th style="width: 30px !important; ">Vat</th>\n\
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
    $("#return_itemsbybarcodeModal").remove();
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

        $('#return_items_by_barcode_table').on('key-focus.dt', function(e, datatable, cell){
            $(items_search.row(cell.index().row).node()).addClass('selected');
        });

        $('#return_items_by_barcode_table').on('key-blur.dt', function(e, datatable, cell){
            $(items_search.row(cell.index().row).node()).removeClass('selected');
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
        
        table.cell(index, 13).data('<button onclick="returnQty('+parseInt(table.cell(index, 12).data())+')" type="button" class="btn btn-xs btn-info" style="width:100%; font-size:13px;"><b>Return</b></button>');
        
        if(parseFloat(table.cell(index, 9).data())!=0){
            $(table.row(p[k]).node()).addClass('hilighted_row');
        }
    }
}

function sales_date_changed(){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var table = $('#return_items_table').DataTable();
    table.ajax.url("?r=pos&f=get_all_sold_items_with_vat&p0="+$("#sold_items_per_items").val()+"&p1="+$("#invoice_id_list").val()).load(function () {
        $(".sk-circle-layer").hide();
    },false);
}


function do_return(id,return_sms_fees){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var remain = 0;
    var total_price = 0;
    var returned_id = 0;
    $.getJSON("?r=pos&f=returnBackItems&p0="+id+"&p1=1&p2="+return_sms_fees, function (data) {
        returned_id = data.returned_id;
    }).done(function () {
        swal("Deleted!", "", "success");

        if($('#return_items_table').length>0){
            var table = $('#return_items_table').DataTable();
            print_returned_item(returned_id);
            table.ajax.url("?r=pos&f=get_all_sold_items_with_vat&p0="+$("#sold_items_per_items").val()+"&p1="+$("#invoice_id_list").val()).load(function () {
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

    })
    .fail(function() {
        logged_out_warning();
    })
    .always(function() {

    });
}

function returnQty(id){
    var is_credit_transfer = 0;
    $.getJSON("?r=pos&f=check_if_credit_transfer&p0="+id, function (data) {
        is_credit_transfer = data[0].mobile_transfer_credits;
    }).done(function () {
        if(is_credit_transfer>0){
            swal({
                title: "return SMS fees to the device balance?",
                html: false ,
                text: '',
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function(isConfirm) {
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
                                        do_return(id,1);
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
                                    do_return(id,1);
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
                                        do_return(id,0);
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
                                    do_return(id,0);
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
                                do_return(id,0);
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
                            do_return(id,0);
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
    $("#history_of_cashboxesModal").remove();
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

        $('#history_of_cashboxes_table').on('key-focus.dt', function(e, datatable, cell){
            $(h_cashboxes.row(cell.index().row).node()).addClass('selected');
        });

        $('#history_of_cashboxes_table').on('key-blur.dt', function(e, datatable, cell){
            $(h_cashboxes.row(cell.index().row).node()).removeClass('selected');
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