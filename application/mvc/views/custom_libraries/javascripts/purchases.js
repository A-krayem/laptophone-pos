var purchasesFunctionLocked = false;

function updatePurchaseItems(){
    var items = "";
    $.getJSON("?r=pos&f=getPurchases&p0=" + store_id +"&p1="+$("#purchasesDate").val(), function (data) {
        $.each(data, function (key, val) {
            items+='<div class="col-lg-2 col-md-2 col-sm-4 col-xs-12 inv_purchased"><div onclick="showItemsForInvoice('+val.id+')"><b>'+pad_invoice(val.id)+'</b><br/>'+val.creation_date+'</div></div>';
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
                items+='<div class="col-lg-2 col-md-2 col-sm-4 col-xs-12 inv_purchased"><div onclick="showItemsForInvoice('+val.id+')"><b>'+pad_invoice(val.id)+'</b><br/>'+val.creation_date+'</div></div>';
            });
            
        }).done(function () {
            var content =
                '<div class="modal fade" data-keyboard="false" id="purchasesModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
                <div class="modal-dialog" role="document">\n\
                    <div class="modal-content">\n\
                        <div class="modal-header"> \n\
                            <h3 class="modal-title"><i class="icon-invoice"></i>&nbsp;Purchase Invoice<i style="float:right;font-size:30px" class="glyphicon glyphicon-remove" onclick="closeModal(\'purchasesModal\')"></i></h3>\n\
                        </div>\n\
                        <div class="modal-body">\n\
                            <div class="row">\n\
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">\n\
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
                startDate: '-3d',
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

var showItemsForInvoiceLockedFunction = false
function showItemsForInvoice(invoice_id){
    if(showItemsForInvoiceLockedFunction==false){
        showItemsForInvoiceLockedFunction=true;
         var items = "";
        $.getJSON("?r=pos&f=getPurchasesItemsOfInvoice&p0=" + invoice_id, function (data) {
            $.each(data, function (key, val) {
                items+='<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 inv_purchased inv_purchased_items cit_'+val.id+'"><div><b>'+val.description+'</b><br/>QTY: <span id="'+val.id+'">'+val.qty+'</span>&nbsp;&nbsp;&nbsp;Price: '+val.final_price_disc_qty+"&nbsp;"+default_currency_symbol+'</div></div>';
                if(val.qty==1){
                    items+='<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 inv_purchased inv_purchased_items citc_'+val.id+'">\n\
                        <div>\n\
                            <i class="glyphicon glyphicon-trash" onclick="confirmDeleteItem('+val.id+',"fi")" style="float:right"></i>\n\
                        </div>\n\
                    </div>';
                }else{
                   items+='<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 inv_purchased inv_purchased_items citc_'+val.id+'">\n\
                        <div>\n\
                            <i class="glyphicon glyphicon-minus-sign" onclick="DeleteOneUnit('+val.id+')" style="float:left"></i>&nbsp;&nbsp;\n\
                            <i class="glyphicon glyphicon-trash" onclick="confirmDeleteItem('+val.id+',"fi")" style="float:right"></i>\n\
                        </div>\n\
                    </div>'; 
                }
                
            });
            
        }).done(function () {
            var content =
                '<div class="modal fade" data-keyboard="false" id="invoiceItemsModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
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
        })
        .fail(function() {

        })
        .always(function() {
            showItemsForInvoiceLockedFunction = false;
        });
    }
}

    function confirmDeleteItem(id,source){
        swal({
            title: "Are you sure?",
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
                    $("#cashboxTotal").html(cashBoxTotalReturn);
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


            $.getJSON("?r=pos&f=getPurchasedList&p0="+current_date+"&p1="+customer_id, function (data) {
                $("#purchasedList").empty();
                
                if(customer_id==0){
                    expenses+="<div class='row purchasedItemsList'><div class='col-lg-12 col-md-12 col-sm-12 col-xs-12' style='font-size:18px;text-decoration: underline;'><b>Expenses</b></div></div>";
                }
                $.each(data.expenses, function (key, val) {
                    expenses_total+=parseFloat(val.value);
                    expenses+="<div class='row purchasedItemsList'><div class='col-lg-7 col-md-7 col-sm-7 col-xs-12'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>"+val.creation_date.split(" ")[1]+": "+val.description+"</div><div class='col-lg-1 col-md-1 col-sm-1 col-xs-1 purchasedItemsList'>&nbsp;</div><div class='col-lg-2 col-md-2 col-sm-2 col-xs-2 purchasedItemsList>&nbsp;</div><div class='col-lg-2 col-md-2 col-sm-2 col-xs-2 purchasedItemsList'>&nbsp;</div><div class='col-lg-2 col-md-2 col-sm-2 col-xs-2 purchasedItemsList'>"+format_price_already_fixed(val.value)+"</div></div>";
                });
                
                if(customer_id==0){
                    expenses+="<div class='row divider'><div class='col-lg-7 col-md-7 col-sm-7 col-xs-12'></div></div>";
                }
                
                items+="<div class='row purchasedItemsList'><div class='col-lg-12 col-md-12 col-sm-12 col-xs-12' style='font-size:18px;text-decoration: underline;'><b>Purchase Invoice</b></div></div>";
                $.each(data.purchases, function (key, val) {
                    
                    debt = "";
                    n_debt = "";
                    customer = "";
                    if(val.closed==0){
                        closed ="notclosed";
                        debt = format_price_already_fixed(val.final_price_disc_qty);
                        sum_debts+=parseFloat(val.final_price_disc_qty);
                    }else{
                        closed ="";
                        n_debt = format_price_already_fixed(val.final_price_disc_qty);
                        sum_n_debts+=parseFloat(val.final_price_disc_qty);
                    }

                    if(val.customer!="" && val.closed==0){
                        customer = "("+val.customer+")";
                    }
                    
         
                    if(customer_id==0){
                        items+="<div class='row purchasedItemsList' id='r_"+val.id+"'><div class='col-lg-7 col-md-7 col-sm-7 col-xs-12 "+closed+"'><i class='glyphicon glyphicon-trash purchasedItemsListDelete' onclick='confirmDeleteItem("+val.inv_item_id+",\"il\")'></i>&nbsp;<b>"+val.invoice_date.split(" ")[1]+":</b> "+val.desc+" "+customer+"</div><div class='col-lg-1 col-md-1 col-sm-1 col-xs-1 purchasedItemsList'>x "+val.qty+"</div><div class='col-lg-2 col-md-2 col-sm-2 col-xs-2 purchasedItemsList "+closed+"'>"+debt+"</div><div class='col-lg-2 col-md-2 col-sm-2 col-xs-2 purchasedItemsList'>"+n_debt+"</div></div>";
                    }else{
                        if(val.customer_id==customer_id){
                            items+="<div class='row purchasedItemsList' id='r_"+val.id+"'><div class='col-lg-7 col-md-7 col-sm-7 col-xs-12 "+closed+"'><i class='glyphicon glyphicon-trash purchasedItemsListDelete' onclick='confirmDeleteItem("+val.inv_item_id+",\"il\")'></i>&nbsp;<b>"+val.invoice_date.split(" ")[1]+":</b> "+val.desc+" "+customer+"</div><div class='col-lg-1 col-md-1 col-sm-1 col-xs-1 purchasedItemsList'>x "+val.qty+"</div><div class='col-lg-2 col-md-2 col-sm-2 col-xs-2 purchasedItemsList "+closed+"'>"+debt+"</div><div class='col-lg-2 col-md-2 col-sm-2 col-xs-2 purchasedItemsList'>"+n_debt+"</div></div>";
                        }
                    }
                });
            }).done(function () {
                    var content =
                        '<div class="modal fade" data-keyboard="false" id="purchasedItemsModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
                        <div class="modal-dialog" role="document">\n\
                            <div class="modal-content">\n\
                                <div class="modal-header"> \n\
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">\n\
                                        <h3 class="modal-title" style="float:left">Purchased Items  </h3>\n\
                                        <input id="purchasedItemsdate"  class="span2 form-control datepicker" type="text" placeholder="Select date" style="cursor:pointer; width:200px; float:left; margin-left:10px; display:'+footer_display+'">\n\
                                    </div>\n\
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">\n\
                                        <i style="float:right;font-size:30px" class="glyphicon glyphicon-remove" onclick="closeModal(\'purchasedItemsModal\')"></i>                                </div>\n\
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
                        startDate: '-3d',
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
                '<div class="modal fade" data-keyboard="false" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
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