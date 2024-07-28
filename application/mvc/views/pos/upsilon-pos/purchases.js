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

            })
            .always(function() {

            });
        }
    });
}

function printAgain(id){
    inv.print_invoice(id);
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

    })
    .always(function() {

    });
}

function purchases(){
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
                '<div class="modal fade" id="purchasesModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
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

            $('#purchasesModal').modal('toggle');

            $('#purchasesModal').on('hidden.bs.modal', function (e) {
                lockMainPos = false;
                $('#purchasesModal').remove();
            });
        })
        .fail(function() {

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
                '<div class="modal fade" id="invoiceItemsModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
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
            
            $('#invoiceItemsModal').modal('toggle');

            $('#invoiceItemsModal').on('hidden.bs.modal', function (e) {
                $('#invoiceItemsModal').remove();
                $("#purchasesModal").show();
                
                $(".sweet-alert").remove();
                $(".sweet-overlay").remove();
                 
            });
            
            $('#invoiceItemsModal').on('shown.bs.modal', function (e) {
                $(".inp_rv").numeric({ negative : false});
                $('.inp_rv').keyboard({btnClasses: 'btn btn-default btn_key',type:'tel',placement: 'left',
                    layout:[
                        [['7'],['8'],['9']],
                        [['4'],['5'],['6']],
                        [['1'],['2'],['3']],
                        [['del'],['0']],
                    ]
                });
            });
            
        })
        .fail(function() {

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
                    $.getJSON("?r=pos&f=returnBackItems&p0="+id+"&p1="+$("#rb_qty_"+id).val(), function (data) {
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
                        purchasedItemsChangeDate();
                    }
                    swal("Deleted!", "", "success");
                }else{
                    alert("Failed");
                }
                
            })
            .fail(function() {

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
                        '<div class="modal fade" id="purchasedItemsModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
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

                    $( "#purchasedItemsdate" ).on( "change", purchasedItemsChangeDate);

                    $('#purchasedItemsModal').modal('toggle');

                    $('#purchasedItemsModal').on('shown.bs.modal', function (e) {
                        $('#purchasedItemsModal .modal-body').scrollTop($('#purchasedItemsModal .modal-body')[0].scrollHeight);
                    });

                    $('#purchasedItemsModal').on('hidden.bs.modal', function (e) {
                        $("#purchasedItemsModal").remove();
                    });

            }).fail(function() {

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
   
    
    function DeleteOneUnit(id){
        
    }
    
    function showInstantReport(){
        var text = "";
        $.getJSON("?r=pos&f=showInstantReport&p0="+store_id, function (data) {
            $.each(data, function (key, val) {
                text+= "<b>"+val.description+":</b> "+val.quantity+"<br/>";
            });
        }).done(function () {
            var content =
                '<div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
                <div class="modal-dialog" role="document">\n\
                    <div class="modal-content">\n\
                        <div class="modal-header"> \n\
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">\n\
                                <h3 class="modal-title" style="float:left">Report</h3>\n\
                            </div>\n\
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">\n\
                                <i style="float:right;font-size:30px" class="glyphicon glyphicon-remove" onclick="closeModal(\'reportModal\')"></i>\n\
                            </div>\n\
                        </div>\n\
                        <div class="modal-body">\n\
                            <div class="row">\n\
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="reportIn">\n\
                                    '+text+'\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="modal-footer">\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
            </div>';
            $("#reportModal").remove();
            $("body").append(content);
            $("#reportModal").centerWH();

             $('#reportModal').modal('toggle');

            $('#reportModal').on('shown.bs.modal', function (e) {
            });

            $('#reportModal').on('hidden.bs.modal', function (e) {
                $("#reportModal").remove();
            });
        })
        .fail(function() {

        })
        .always(function() {
            
        });
        
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
                    showItemsForInvoice($("#invoice_id_search").val());
                }
            }
            $(".sweet-alert").remove();
            $(".sweet-overlay").remove();
            lockMainPos = false;
        });
        setTimeout(function(){
            $("#invoice_id_search").numeric();
            $('#invoice_id_search').keyboard({btnClasses: 'btn btn-default btn_key',type:'tel',placement: 'top',
            layout:[
		[['7'],['8'],['9']],
                [['4'],['5'],['6']],
                [['1'],['2'],['3']],
                [['del'],['0']],
            ]});
            $("#invoice_id_search").focus();
        },500);
    }
}