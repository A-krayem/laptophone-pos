function Invoice() {
    
    var items = [];
    var self = this;
    var selected_id = null;
    var getItemFunctionLocked = false;
    
    this.getTotalPrice = function() {
        var total = 0;
        for(var i=0;i<items.length;i++){
            total+=parseFloat((items[i].final_price*items[i].qty));
        }
        return total;
    };
    
    this.print_invoice = function(id){
        $.getJSON("?r=print_invoice&f=print_invoice_id&p0="+id, function (data) {
            $.each(data, function (key, val) {
            });
        }).done(function () {

        });
    };
    
    this.open_cashDrawer = function(){
        $.getJSON("?r=print_invoice&f=open_cashDrawer", function (data) {
            $.each(data, function (key, val) {
            });
        }).done(function () {

        });
    };
    
    
    this.reset = function(){
        items = [];
        selected_id = null;
        $("#p_items").empty();
        $("#totalPrice").html(format_price(self.getTotalPrice()));
    };
    
    this.getData = function(){
        return items;
    };
    
    this.incrementItemQty = function(item_id){
        var index = null;
        for(var i=0;i<items.length;i++){
            if(items[i].id == item_id){
                items[i].qty++;
                items[i].final_price = items[i].price*(1-(items[i].discount/100));
                index = i;
                break;
            }
        }
        if(index!=null){
            $("#qty_"+items[index].id).html(items[index].qty);
            $("#final_"+items[index].id).html(format_price(items[index].qty*items[index].final_price));
            $("#totalPrice").html(format_price(self.getTotalPrice()));
        }
    };
    
    this.decrementItemQty = function(item_id){
        var index = null;
        for(var i=0;i<items.length;i++){
            if(items[i].id == item_id){
                if(items[i].qty>1){
                    items[i].qty--;
                    index = i;
                    break;
                }
            }
        }
        if(index!=null){
            $("#qty_"+items[index].id).html(items[index].qty);
            $("#final_"+items[index].id).html(format_price(items[index].qty*items[index].final_price));
            $("#totalPrice").html(format_price(self.getTotalPrice()));
        }
    };
    
    this.deleteItem = function(item_id){
        
        if(items.length>0){
            var items_tmp = [];
            for(var i=0;i<items.length;i++){
                if(items[i].id != item_id){
                    items_tmp.push(items[i]);
                }
            }
            items = items_tmp;
            $("#it_"+item_id).remove();
            $("#totalPrice").html(format_price(self.getTotalPrice()));
        }
        
        if(items.length==0)
            $("#pay").addClass("disabledPay");
    };
    
    this.addCustomItem = function(info_){
        var info = [];
        info["id"] =  Math.floor(Date.now() / 1000);
        info["description"] = info_["description"];
        info["qty"] = 1;
        info["barcode"] = makeVirtualBarcode();
        info["discount"] = 0;
        info["price"] = parseFloat(info_["price"]) ;
        info["final_price"] = parseFloat(info_["price"]) ;
        info["custom_item"] = 1;
        info["mobile_transfer_item"] = 'NULL';
        info["mobile_transfer_device_id"] = 0;
        self.addItem(info);
    };
    
    this.addCallsItem = function(info_){
        var info = [];
        info["id"] =  Math.floor(Date.now() / 1000);
        info["description"] = info_["description"];
        info["qty"] = 1;
        info["barcode"] = makeVirtualBarcode();
        info["discount"] = 0;
        info["price"] = parseFloat(info_["price"]) ;
        info["final_price"] = parseFloat(info_["price"]) ;
        info["custom_item"] = info_["custom_item"];
        info["mobile_transfer_item"] = 'NULL';
        info["mobile_transfer_device_id"] = 0;
        self.addItem(info);
    };
    
    this.addMobileTransferItem = function(info_){
        var info = [];
        info["id"] =  Math.floor(Date.now() / 1000);
        info["description"] = info_["description"];
        info["qty"] = 1;
        info["barcode"] = makeVirtualBarcode();
        info["discount"] = 0;
        info["price"] = parseFloat(info_["price"]);
        info["final_price"] = parseFloat(info_["price"]);
        info["custom_item"] = 1;
        info["mobile_transfer_item"] = parseInt(info_["mobile_transfer_item"]);
        info["mobile_transfer_device_id"] = info_["id_device"];
        self.addItem(info);
    };
    
    this.addItem  = function (info) {
        var exist = false;
        var index = 0;
        for(var i=0;i<items.length;i++){
            if(items[i].id == info["id"]){
                items[i].qty++;
                exist=true;
                index = i;
                break;
            }
        }
        
        $(".select_p_item").removeClass("select_p_item");
        if(!exist){
            selected_id = info["id"];
            items.push({id: info["id"],price: info["price"],qty: info["qty"],barcode: info["barcode"],description: info["description"],discount:info["discount"],final_price:info["final_price"],custom_item:info["custom_item"],mobile_transfer_item:info["mobile_transfer_item"],mobile_transfer_device_id:info["mobile_transfer_device_id"]});
            $("#p_items").append("<div onclick='selectItemByClick("+info["id"]+")' class='row purchases select_p_item' id='it_"+info["id"]+"'><div class='col-lg-4 col-md-4 col-sm-4 col-xs-4'>"+info["description"]+"</div><div class='col-lg-1 col-md-1 col-sm-1 col-xs-1'><span  id='qty_"+info["id"]+"'>"+info["qty"]+"</span></div><div class='col-lg-1 col-md-1 col-sm-1 col-xs-1'>"+info["discount"]+"%</div><div class='col-lg-2 col-md-2 col-sm-2 col-xs-2' id='price_"+items[index].id+"'>"+format_price(info["final_price"])+"</div><div class='col-lg-4 col-md-4 col-sm-4 col-xs-4' id='final_"+info["id"]+"'>"+format_price(info["qty"]*info["final_price"])+"</div></div>"); //<div class='col-lg-2 col-md-2 col-sm-2 col-xs-2 id_content'><i class='glyphicon glyphicon-trash p_i' onclick='deleteItem()'></i>&nbsp;"+padItem(info["id"])+"</div>
        }else{
            selected_id = items[index].id;
            $("#it_"+items[index].id).remove(); 
            $("#p_items").append("<div onclick='selectItemByClick("+info["id"]+")' class='row purchases select_p_item' id='it_"+items[index].id+"'><div class='col-lg-4 col-md-4 col-sm-4 col-xs-4'>"+items[index].description+"</div><div class='col-lg-1 col-md-1 col-sm-1 col-xs-1'><span id='qty_"+items[index].id+"'>"+items[index].qty+"</span></div><div class='col-lg-1 col-md-1 col-sm-1 col-xs-1'>"+info["discount"]+"%</div><div class='col-lg-2 col-md-2 col-sm-2 col-xs-2' id='price_"+items[index].id+"'>"+format_price(items[index].final_price)+"</div><div class='col-lg-4 col-md-4 col-sm-4 col-xs-4' id='final_"+items[index].id+"'>"+format_price(items[index].qty*items[index].final_price)+"</div></div>"); //<div class='col-lg-2 col-md-2 col-sm-2 col-xs-2 id_content'><i class='glyphicon glyphicon-trash p_i' onclick='deleteItem()'></i>&nbsp;"+padItem(items[index].id)+"</div>
        }
        $("#totalPrice").html(format_price(self.getTotalPrice()));
        
        $("#pay").removeClass("disabledPay");
        scrollToSelectedItem();
    };
    
    this.updatePurchasedList = function(){
        showPurchasedItem(null,null);
    };

    this.getItemByBarcode = function (barcode) {
        if(getItemFunctionLocked==false){
            getItemFunctionLocked=true;
            var info = [];
            var data_length = 0;
            $.getJSON("?r=items&f=get_item_by_barcode&p0=" + barcode, function (data) {
                data_length = data.length;
                $.each(data, function (key, val) {
                    info["id"] = val.id;
                    info["description"] = val.description;
                    info["qty"] = 1;
                    info["barcode"] = val.barcode;
                    info["discount"] = val.discount;
                    info["price"] = val.selling_price;
                    info["final_price"] = info["price"]*(1-(info["discount"]/100));
                    info["custom_item"] = 0;
                    info["mobile_transfer_item"] = 0;
                    info["mobile_transfer_device_id"] = 0;
                });
            }).done(function () {
                if(data_length>0)
                    self.addItem(info);
            }).fail(function() {

            })
            .always(function() {
                getItemFunctionLocked = false;
            });
        }
        
    };
    
    this.getItemById = function (id) {
        if(getItemFunctionLocked==false){
            getItemFunctionLocked=true;
            var info = [];
            var data_length = 0;
            $.getJSON("?r=items&f=get_item&p0=" + id, function (data) {
                data_length = data.length;
                $.each(data, function (key, val) {
                    info["id"] = val.id;
                    info["price"] = val.selling_price;
                    info["description"] = val.description;
                    info["qty"] = 1;
                    info["barcode"] = val.barcode;
                    info["discount"] = val.discount;
                    info["price"] = val.selling_price;
                    info["final_price"] = info["price"]*(1-(info["discount"]/100));
                    info["custom_item"] = 0;
                    info["mobile_transfer_item"] = 0;
                    info["mobile_transfer_device_id"] = 0;
                });
            }).done(function () {
                if(data_length>0)
                    self.addItem(info);
            }).fail(function() {
                
            })
            .always(function() {
               getItemFunctionLocked=false;
            });
        };
    }
}

function makeid(){
    var text = "";
    var possible = "0123456789";
    for( var i=0; i < 10; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    return text;
}

function makeVirtualBarcode(){
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    for( var i=0; i < 10; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    return text;
}

function pay(){
    if($("#pay").hasClass("disabledPay") == false){
        showPaymentInformation();
    }
}

function removeOverlay(){
    //$("#overlay").remove();
    $("#payment_info").remove();
}

var showPaymentInformationFunctionLocked = false;
function showPaymentInformation(){
    if(showPaymentInformationFunctionLocked==false){
        showPaymentInformationFunctionLocked==true;
        lockMainPos = true;
        var options = '';
        if(settings_pl == 1) options+='<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"><button onclick="Payment_later()" type="submit" class="btn btn-primary">Later Payment</button></div>';
        if(settings_pf == 1) options+='<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"><button onclick="Payment()" type="submit" class="btn btn-primary">Cash Payment</button></div>';
        var content =
            '<div class="modal fade" data-keyboard="false" id="payment_info" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title" id="payment_info__"><i class="glyphicon glyphicon-usd"></i>&nbsp;Payment</h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <input name="customer_id" id="customer_id" type="hidden" value="0" />\n\
                        <div class="form-group">\n\
                            <div class="inner-addon"><input autocomplete="off" id="customer_name_payment" name="customer_name_payment" data-provide="typeahead" type="text" class="form-control" placeholder="Customer name" aria-describedby="basic-addon1"></div>\n\
                        </div>\n\
                        <div class="form-group">\n\
                            <div class="inner-addon"><input autocomplete="off" id="customer_phone" name="customer_phone" type="text" class="form-control" placeholder="Phone" aria-describedby="basic-addon1"/></div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-footer">\n\
                        <div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"><button type="button" class="btn btn-secondary payi_cancel" data-dismiss="modal">CANCEL</button></div>'+options+'</div> \n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>';
        $("#payment_info").remove();
        $("body").append(content);
        $("#payment_info").centerWH();
       

        $.get("?r=customers&f=getCustomers", function(data){
            var $input = $("#customer_name_payment");
            $input.typeahead({
              source: data,
              autoSelect: true
            });
            $input.change(function() {
                var current = $input.typeahead("getActive");
                if (current) {
                    // Some item from your model is active!
                    if (current.name == $input.val()) {
                        // This means the exact match is found. Use toLowerCase() if you want case insensitive match.
                        $("#customer_id").val(current.id);
                    } else {
                        $("#customer_id").val(0);
                        // This means it is only a partial match, you can either add a new item
                        // or take the active if you don't want new items
                    }
                } else {
                    $("#customer_id").val(0);
                    // Nothing is active so it is a new value (or maybe empty value)
                }
            });
        },'json')
        .done(function(){
            $('#payment_info').modal('toggle');
            $('#payment_info').on('hidden.bs.modal', function (e) {
                lockMainPos = false;
                $('#payment_info').remove();
            });
        })
        .fail(function() {
            lockMainPos = false;
            alert( "error" );
        })
        .always(function() {
            lockMainPos = false;
            showPaymentInformationFunctionLocked==false;
        });
        
        
    }
}

var addPaymentFunctionLocked = false;
function addPayment(){
    if(addPaymentFunctionLocked==false){
        addPaymentFunctionLocked=true;
        lockMainPos = true;
        
        var content =
            '<div class="modal fade" data-keyboard="false" id="payments_of_customer" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title" id="payment_info__"><i class="glyphicon glyphicon-usd"></i>&nbsp;Payments<i style="float:right;font-size:30px" class="glyphicon glyphicon-remove" onclick="closeModal(\'payments_of_customer\')"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                <div class="form-group">\n\
                                    <select onchange="customer_changed()" data-size="10" id="customer_id" name="customer_id" class="selectpicker form-control" ></select>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row" style="margin-top:10px;">\n\
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="font-size:15px; text-align:center; padding-left:1px;padding-right:1px;">\n\
                                <b style="color:red;">Total Unpaid:</b> <span id="pay_unpaid">-</span> \n\
                            </div>\n\
                        </div>\n\
                        <div class="row" style="margin-top:20px;">\n\
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="font-size:18px; text-align:center">\n\
                                <input disabled id="pay_val" style="text-align:center; font-size:18px;" autocomplete="off" id="value" name="value" value="0" data-provide="typeahead" type="text" class="form-control only_numeric" >\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6" style="text-align:center">\n\
                                <button disabled onclick="addPaymentToCustomer()" style="font-size:16px; width:100%" id="add_payment_btn" type="button" class="btn btn-default">Add payment</button>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6" style="text-align:center">\n\
                                <button disabled onclick="showPurchasedItem(null,1)" style="font-size:16px; width:100%" id="show_payment_btn" type="button" class="btn btn-default">Show items</button>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row" style="margin-top:20px;">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center">\n\
                                <table id="customer_payment_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                    <thead><tr><th style="width:40px;">Ref.</th><th>Date</th><th  style="width:100px;">Balance added</th></tr></thead>\n\
                                </table>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-footer">\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>';
        $("#payments_of_customer").remove();
        $("body").append(content);
        
        $('.selectpicker').selectpicker();
        
        $(".only_numeric").numeric();
        
        $("#payments_of_customer").centerWH();

        $.getJSON("?r=customers&f=getCustomersToPay", function (data) {
            $("#customer_id").append("<option value='0'>Select customer</option>");
            $.each(data, function (key, val) {
                $("#customer_id").append("<option value='"+val.id+"'>"+val.name+"</option>");
            });
        }).done(function () {
            
            $("#customer_id").selectpicker('refresh');

            $('#payments_of_customer').modal('toggle');

            $('#payments_of_customer').on('shown.bs.modal', function (e) {   
                updateCustomersPaymentsTable();
            });
            
            $('#payments_of_customer').on('hidden.bs.modal', function (e) {
                lockMainPos = false;
                _table = null;
                $('#payments_of_customer').remove();
            });
        }).fail(function() {
            lockMainPos = false;
        })
        .always(function() {
            addPaymentFunctionLocked=false;
        });
    }
}

var _table = null;
function updateCustomersPaymentsTable(){
if ( _table==null ) {
    _table = $('#customer_payment_table').dataTable({
        ajax: "?r=pos&f=getCustomersPayments&p0="+$("#customer_id").val(),
        scrollY:        '20vh',
        scrollCollapse: true,
        paging:         false,
        order: [[ 1, "desc" ]],
        aoColumnDefs: [
            { "targets": [0], "searchable": true, "orderable": false, "visible": true },
            { "targets": [1], "searchable": true, "orderable": true, "visible": true },
            { "targets": [2], "searchable": true, "orderable": false, "visible": true },
        ],
    });
 }else{
        var table = $('#customer_payment_table').DataTable();
        table.ajax.url("?r=pos&f=getCustomersPayments&p0="+$("#customer_id").val()).load(function () {
            
        },false);
 }  
}




function addPaymentToCustomer(){
    if($("#pay_val").val() != 0){
        $("#pay_val").attr("disabled","disabled");
        $("#add_payment_btn").attr("disabled","disabled");
        var cashBoxTotalReturn = 0;
        $.getJSON("?r=invoice&f=addCustomerPayment&p0="+$("#customer_id").val()+"&p1="+$("#pay_val").val(), function (data) {
            cashBoxTotalReturn = data.cashBoxTotal;
        }).done(function () {
            $("#cashboxTotal").html(cashBoxTotalReturn);
            updateCustomerPaymentInfo();
        }).fail(function() {

        })
        .always(function() {
            $("#add_payment_btn").removeAttr("disabled");
            $("#pay_val").removeAttr("disabled");
            $("#pay_val").val(0);
        });
    }
}

function customer_changed(){
    if($("#customer_id").val() != 0){
        $("#add_payment_btn").removeAttr("disabled");
        $("#pay_val").removeAttr("disabled");
        $("#show_payment_btn").removeAttr("disabled");
        
        updateCustomerPaymentInfo();
    }else{
        $("#pay_unpaid").html("-");
        $("#pay_val").attr("disabled","disabled");
        $("#add_payment_btn").attr("disabled","disabled");
        $("#show_payment_btn").attr("disabled","disabled");
    }
    updateCustomersPaymentsTable();
}

function updateCustomerPaymentInfo(){
    $.getJSON("?r=pos&f=getCustomersPaymentInfo&p0="+$("#customer_id").val(), function (data) {
        $("#pay_unpaid").html(format_price_already_fixed(data.total_remain));
        //$("#customer_balance").html(format_price_already_fixed(data.customer_balance));
        //$("#pay_remain").html(format_price_already_fixed(data.total_remain));
    }).done(function () {

    }).fail(function() {

    })
    .always(function() {

    });
}



function addPaymentToInvoice(id){
    if($("#inp_p_"+id).val() != "0" && $("#inp_p_"+id).val() != ""){
        var cashBoxTotalReturn = 0;
        $.getJSON("?r=invoice&f=addPayment&p0=" + id + "&p1="+$("#inp_p_"+id).val(), function (data) {
            cashBoxTotalReturn = data.cashBoxTotal;
        }).done(function () {
            $("#tp_"+id).html((parseFloat($("#tp_"+id).html())+parseFloat($("#inp_p_"+id).val())).toFixed(2)+" "+default_currency_symbol);
            $("#inp_p_"+id).val(0);
            $("#cashboxTotal").html(cashBoxTotalReturn);
        });
    }
}

/*
function closeInvoice(id){
    //$(".sk-circle-layer").show();
    var cashBoxTotalReturn = 0;
    $.getJSON("?r=invoice&f=close_invoice&p0=" + id, function (data) {
        cashBoxTotalReturn = data.cashBoxTotal;
    }).done(function () {
        //$(".sk-circle-layer").hide();
        $("#cashboxTotal").html(cashBoxTotalReturn);
        $("#row_"+id).remove();
    });
}
*/

var Payment_laterFunctionLocked = false;
function Payment_later(){
    if(Payment_laterFunctionLocked==false){
        Payment_laterFunctionLocked=true;
        if(!emptyInput("customer_name_payment")){
            var more_info = [];
            more_info.push({customer_name:$("#customer_name_payment").val(),phone:$("#customer_phone").val(),customer_id:$("#customer_id").val()});
            $("#payment_info button").attr("disabled","disabled");
            $.ajax({
                type: 'POST',
                url: '?r=invoice&f=pay',
                dataType: 'json',
                data: {'items': inv.getData(),'pay':'partial','store_id':store_id,'more_info':more_info},
                success: function(msg) {
                    inv.reset();
                    if(auto_print==1){
                        inv.print_invoice(msg.inv_id);
                    }
                    $('#payment_info').modal('toggle');
                    $("#pay").addClass("disabledPay");

                },
            }).fail(function() {
                $("#payment_info button").removeAttr("disabled");
            })
            .always(function() {
                Payment_laterFunctionLocked=false;
            });
        }else{
            Payment_laterFunctionLocked=false;
        }
    }
}


var PaymentFunctionLocked = false;
function Payment(){
    if(PaymentFunctionLocked==false){
        PaymentFunctionLocked=true;
   
        $("#payment_info button").attr("disabled","disabled");
        var more_info = [];
        more_info.push({customer_name:$("#customer_name_payment").val(),phone:$("#customer_phone").val(),customer_id:$("#customer_id").val()});
        $.ajax({
            type: 'POST',
            url: '?r=invoice&f=pay',
            dataType: 'json',
            data: {'items': inv.getData(),'pay':'full','store_id':store_id,'more_info':more_info},
            success: function(msg) {
                inv.reset();
                $("#cashboxTotal").html(msg.cashbox_value);
                $('#payment_info').modal('toggle');
                $("#pay").addClass("disabledPay");
                
                
                inv.open_cashDrawer();
                
                //inv.print_invoice(msg.inv_id);
                if(auto_print==2){
                    swal({
                        title: "Do you want to print invoice?",
                        text: "",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "Yes, print it!",
                        closeOnConfirm: true,
                        cancelButtonText: "Do not print",
                    },
                    function(){
                       inv.print_invoice(msg.inv_id);
                    });
                }else if(auto_print==1){
                    inv.print_invoice(msg.inv_id);
                }
                

            },
        }).fail(function() {
             $("#payment_info button").removeAttr("disabled");
        })
        .always(function() {
            PaymentFunctionLocked=false;
        });
      
    }
}

function selectItemByClick(id){
    $(".select_p_item").removeClass("select_p_item");
    $("#it_"+id).addClass("select_p_item");
}

function scrollToSelectedItem(){
   $('#p_items').scrollTop($('#p_items').scrollTop() + $('.select_p_item').position().top - $('#p_items').height()/2 + $('.select_p_item').height()/2);
}

function deleteItem() {
    if($(".select_p_item").length>0){
        setTimeout(function () {
            var item_id = parseInt($(".select_p_item").attr("id").split('_')[1]);
            if ($(".select_p_item").next().length > 0) {
                var current = $(".select_p_item");
                $(".select_p_item").next().addClass("select_p_item");
                current.removeClass("select_p_item");
                current = null;
                scrollToSelectedItem();
            } else {
                if ($(".select_p_item").prev().length > 0) {
                    var current = $(".select_p_item");
                    $(".select_p_item").prev().addClass("select_p_item");
                    current.removeClass("select_p_item");
                    current = null;
                    scrollToSelectedItem();
                }
            }
            if(inv!=null)
                inv.deleteItem(item_id);
        }, 50);
    }

}

function addCustomItemBtn(){
    if( $("#custom_item_name").val() == "" ){
       $("#custom_item_name").addClass("error"); 
    }else if( $("#custom_item_cost").val() == "" || $("#custom_item_cost").val() == "0" ){
        $("#custom_item_name").removeClass("error"); 
        $("#custom_item_cost").addClass("error"); 
    }else{
        $("#custom_item_cost").removeClass("error");
        var infCust = [];
        infCust["description"] = $("#custom_item_name").val();
        infCust["price"] = $("#custom_item_cost").val();
        inv.addCustomItem(infCust);
        $('#addCustomItemModal').modal('toggle');
    }
}

function addCustomItemModal(){
    if(cashBox == 0){
        setCashbox();
    }else{
        lockMainPos = true;
        var content =
            '<div class="modal fade" id="addCustomItemModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title"><i class="icon-invoice"></i>&nbsp;Sell custom item<i style="float:right;font-size:30px" class="glyphicon glyphicon-remove" onclick="closeModal(\'addCustomItemModal\')"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="form-group">\n\
                            <div class="inner-addon"><input autocomplete="off" id="custom_item_name" name="custom_item_name" data-provide="typeahead" type="text" class="form-control" placeholder="Item description" aria-describedby="basic-addon1"></div>\n\
                        </div>\n\
                        <div class="form-group" style="width:100px">\n\
                            <div class="inner-addon"><input autocomplete="off" id="custom_item_cost" name="custom_item_cost" data-provide="typeahead" type="text" value="0" class="form-control only_numeric" placeholder="" aria-describedby="basic-addon1"></div>\n\
                        </div>\n\
                        <div class="form-group" style="width:150px">\n\
                            <div class="inner-addon"><button id="addCustomItemBtn" onclick="addCustomItemBtn()" type="button" class="btn btn-default" style="width: 100%; color: #000; font-size: 20px; font-weight: bold;">Add to invoice</button></div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-footer">\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>';
        $("#addCustomItemModal").remove();
        $("body").append(content);
        $(".only_numeric").numeric();
        $("#addCustomItemModal").centerWH();
        $('#addCustomItemModal').modal('toggle');

        $('#addCustomItemModal').on('hidden.bs.modal', function (e) {
            lockMainPos = false;
            $('#addCustomItemModal').remove();
        });
    }
 
}

/*
var showInvoicesFunctionLocked = false;
function showInvoices(){
    if(showInvoicesFunctionLocked == false){
        showInvoicesFunctionLocked = true;
        lockMainPos = true;
        var invoices = "";
        invoices+="<div class='row invoices_list_row_header'><div class='col-lg-2 col-md-2 col-sm-2 col-xs-2 invoices_list'>Reference</div><div class='col-lg-2 col-md-2 col-sm-2 col-xs-2 invoices_list'>Invoice date</div><div class='col-lg-2 col-md-2 col-sm-2 col-xs-2'>Items</div><div class='col-lg-2 col-md-2 col-sm-2 col-xs-2'>Invoice value</div><div class='col-lg-4 col-md-4 col-sm-4 col-xs-4 invoices_list'>Customer</div></div>";
        $.getJSON("?r=pos&f=getTodayInvoices&p0=" + store_id, function (data) {
            $.each(data, function (key, val) {
                invoices+="<div class='row invoices_list_row'><div class='col-lg-2 col-md-2 col-sm-2 col-xs-2 invoices_list'>"+pad_invoice(val.id)+"</div><div class='col-lg-2 col-md-2 col-sm-2 col-xs-2 invoices_list'>"+val.creation_date+"</div><div class='col-lg-2 col-md-2 col-sm-2 col-xs-2'>"+val.items_nb+"</div><div class='col-lg-2 col-md-2 col-sm-2 col-xs-2'>"+val.invoice_value+"</div><div class='col-lg-4 col-md-4 col-sm-4 col-xs-4 invoices_list'>"+val.customer_name+"</div></div>";
            });
        }).done(function () {
                var content =
                    '<div class="modal fade" id="invoicesModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
                    <div class="modal-dialog" role="document">\n\
                        <div class="modal-content">\n\
                            <div class="modal-header"> \n\
                                <h3 class="modal-title"><i class="icon-invoice"></i>&nbsp;Invoices<i style="float:right;font-size:30px" class="glyphicon glyphicon-remove" onclick="closeModal(\'invoicesModal\')"></i></h3>\n\
                            </div>\n\
                            <div class="modal-body">'+invoices+'</div>\n\
                            <div class="modal-footer">\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                </div>';
                $("#invoicesModal").remove();
                $("body").append(content);
                $("#invoicesModal").centerWH();
                $('#invoicesModal').modal('toggle');

                $('#invoicesModal').on('hidden.bs.modal', function (e) {
                    lockMainPos = false;
                    $('#invoicesModal').remove();
                });
        })
        .fail(function() {
            lockMainPos = false;
        })
        .always(function() {
            showInvoicesFunctionLocked = false;
        });
    }
}
*/

function closeModal(id){
    $('#'+id).modal('toggle');
}

var showAvailablePhonesFunctionLocked = false;
function showAvailablePhones(action){
     if (cashBox == 0) {
        setCashbox();
    } else {
        if (showAvailablePhonesFunctionLocked == false) {
            showAvailablePhonesFunctionLocked = true;
            var devices = "";
            $.getJSON("?r=pos&f=getTransferPackages&p0=" + store_id, function (data) {
                $.each(data.devices, function (key, val) {
                    devices+="<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 devicesC'  onclick='transfer("+val.id+","+val.operator_id+",\""+action+"\")'><div style='padding:5px;background-color:"+val.color+"'><i class='icon-smartphone'></i>&nbsp;"+val.description+"("+val.balance+" $) - <b>"+val.operator_name+"</b></div></div>";
                });
            }).done(function () {
                var ic = "";
                if(action=="c"){
                    ic="<i class='glyphicon glyphicon-usd'></i>";
                }else if(action=="d"){
                    ic="<i class='icon-calendar'></i>";
                }
                var content =
                    '<div class="modal fade" id="mobileDevicesModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
                        <div class="modal-dialog" role="document">\n\
                            <div class="modal-content">\n\
                                <div class="modal-header"> \n\
                                    <h3 class="modal-title">'+ic+'&nbsp;Devices<i style="float:right;font-size:30px" class="glyphicon glyphicon-remove" onclick="closeModal(\'mobileDevicesModal\')"></i></h3>\n\
                                </div>\n\
                                <div class="modal-body" id="transfer_devices">\n\
                                    <div class="row">'+devices+'</div>\n\
                                </div>\n\
                                <div class="modal-footer">\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>';
                $("#mobileDevicesModal").remove();
                $("body").append(content);
                $("#mobileDevicesModal").centerWH();
                $('#mobileDevicesModal').modal('toggle');
                $('#mobileDevicesModal').on('hidden.bs.modal', function (e) {
                    lockMainPos = false;
                    $('#mobileDevicesModal').remove();
                });
            }).fail(function () {
                lockMainPos = false;
                alert("error");
            }).always(function () {
                showAvailablePhonesFunctionLocked = false;
            });
        }
    }
}

    var transferFunctionLocked = false;
    function transfer(id_device,operator_id_p,action) {
        $('#mobileDevicesModal').modal('toggle');
        if (cashBox == 0) {
            setCashbox();
        } else {
            if (transferFunctionLocked == false) {
                transferFunctionLocked = true;
                lockMainPos = true;
                var content =
                        '<div class="modal fade" id="transferModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
                            <div class="modal-dialog" role="document">\n\
                                <div class="modal-content">\n\
                                    <div class="modal-header"> \n\
                                        <h3 class="modal-title"><i class="glyphicon glyphicon-usd"></i>&nbsp;Transfer $ Touch/Alfa<i style="float:right;font-size:30px" class="glyphicon glyphicon-remove" onclick="closeModal(\'transferModal\')"></i></h3>\n\
                                    </div>\n\
                                    <div class="modal-body" id="transfer_operators">\n\
                                    </div>\n\
                                    <div class="modal-footer">\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                        </div>';
                $("#transferModal").remove();
                $("body").append(content);
                $.getJSON("?r=pos&f=getTransferPackages&p0=" + store_id, function (data) {
                    $.each(data.operators, function (key, val) {
                        if(val.id == operator_id_p){
                            if(action=="c"){
                                $("#transfer_operators").append("<div class='row' id='transfer_operators_section_" + val.id + "'></div>");
                            }
                            if(action=="d"){
                                $("#transfer_operators").append("<div class='row' id='transfer_operators_section_days_" + val.id + "'></div>");
                            }
                        }
                    });
                    $.each(data.packages, function (key, val) {
                        if(val.operator_id == operator_id_p && parseInt(val.days)==0){
                            $("#transfer_operators_section_" + val.operator_id).append("<div class='col-lg-3 col-md-3 col-sm-12 col-xs-12 PKG' onclick='addTransferItemToInvoice(" + val.id + ","+id_device+")'><div class='PKG_C' ><p style='background-color: " + val.base_color + " !important;'>" + val.operator_name + " " + val.qty + "$ <br/>" + val.price + " " + default_currency_symbol + "</p></div></div>");
                        }else{
                             if(val.operator_id == operator_id_p){
                                $("#transfer_operators_section_days_" + val.operator_id).append("<div class='col-lg-3 col-md-3 col-sm-12 col-xs-12 PKG' onclick='addTransferItemToInvoice(" + val.id + ","+id_device+")'><div class='PKG_C' ><p style='background-color: " + val.base_color + " !important;'>" + val.operator_name + " "+val.days+" days and " + val.qty + "$ <br/>" + val.price + " " + default_currency_symbol + "</p></div></div>");
                             }
                        }
                    });
                }).done(function () {
                    $("#transferModal").centerWH();
                    $('#transferModal').modal('toggle');

                    $('#transferModal').on('hidden.bs.modal', function (e) {
                        lockMainPos = false;
                        $('#transferModal').remove();
                    });
                }).fail(function () {
                    lockMainPos = false;
                    alert("error");
                }).always(function () {
                    transferFunctionLocked = false;
                });
            }
        }
    }

function addTransferItemToInvoice(package_id,id_device){
    var info = [];
    var price = null;
    var operator_name = null;
    var qty = null;
    var days = null;
    $.getJSON("?r=pos&f=getPackageById&p0=" + package_id, function (data) {
        $.each(data, function (key, val) {
            price = val.price;
            operator_name = val.operator_name;
            qty = val.qty;
            days = val.days;
        });
    }).done(function () {
        if(days>0){
            info["description"] = days+" days and "+qty+"$ "+operator_name;
        }else{
            info["description"] = qty+"$ "+operator_name;
        }
        
        info["price"] = price;
        info["mobile_transfer_item"] = package_id;
        info["id_device"] = id_device;
        
        inv.addMobileTransferItem(info);
        $('#transferModal').modal('toggle');
    });
}

var showNoBarcodeItemsFunctionLocked = false;
function showNoBarcodeItems(){
    if (cashBox == 0) {
            setCashbox();
    } else {
        if(showNoBarcodeItemsFunctionLocked==false){
            showNoBarcodeItemsFunctionLocked=true;
            lockMainPos = true;
            
            $.getJSON("?r=pos&f=getTransferPackages&p0=" + store_id, function (data) {
                $.each(data.operators, function (key, val) {
                });
            }).done(function () {
                var content =
                        '<div class="modal fade" id="noBarcodeModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
                            <div class="modal-dialog" role="document">\n\
                                <div class="modal-content">\n\
                                    <div class="modal-header"> \n\
                                        <h3 class="modal-title"><i style="float:right;font-size:30px" class="glyphicon glyphicon-remove" onclick="closeModal(\'noBarcodeModal\')"></i><input id="searchIt" onkeyup="searchItemsNonBarcode()" type="text" class="form-control" placeholder="non-barcode filtration" style="margin-top:5px; width:250px;"></h3>\n\
                                    </div>\n\
                                    <div class="modal-body" id="noBarcodeItems">\n\
                                    </div>\n\
                                    <div class="modal-footer">\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                        </div>';
                $("#noBarcodeModal").remove();
                $("body").append(content);
                
                $("#noBarcodeModal").centerWH();
                $('#noBarcodeModal').modal('toggle');

                $('#noBarcodeModal').on('hidden.bs.modal', function (e) {
                    lockMainPos = false;
                    $('#noBarcodeModal').remove();
                });
                $('#noBarcodeModal').on('shown.bs.modal', function (e) {
                    $.getJSON("?r=pos&f=getNonBarcodeItems&p0="+store_id, function (data) {
                        $.each(data, function (key, val) {
                            $("#noBarcodeItems").append("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3 funcContainer' id='ncode_"+val.item_id+"'><div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 func' id='nc_"+val.item_id+"'><span class='ncodes' id='ncode_s_"+val.item_id+"'>"+val.item_id+"-"+val.description+"</span> <p>"+val.selling_price+"</p></div></div>");
                        });
                    }).done(function () {
                        $(".func").on('click', function (e) {
                            if(cashBox == 0){
                                setCashbox();
                            }else{
                                inv.getItemById($(this).attr('id').split('_')[1]);
                                closeModal('noBarcodeModal');
                            }

                        });
                    }).fail(function () {
                        
                    }).always(function () {
                        
                    });
                });
            }).fail(function () {
                lockMainPos = false;
                alert("error");
            }).always(function () {
                showNoBarcodeItemsFunctionLocked = false;
            }); 
        }
    }
}

function searchItemsNonBarcode(){
    $.each( $(".ncodes"), function( key, value ) {
        var id  = $(this).attr('id').split('_');
        if ($(this).html().toLowerCase().indexOf($("#searchIt").val().toLowerCase()) >= 0){
            $("#"+id[0]+"_"+id[2]).show();
        }else{
            $("#"+id[0]+"_"+id[2]).hide();
        }
    });
}

function keyboardEvents(){
    $(window).keydown(function(event) {
        if(lockMainPos == false){
            switch (event.which) {
                case 13: // enter
                    if($(".select_p_item").length>0){
                        var item_id = parseInt($(".select_p_item").attr("id").split('_')[1]);
                    }
                    break;
                case 38: // up
                    if($(".select_p_item").prev().length>0){
                        var current = $(".select_p_item");
                        $(".select_p_item").prev().addClass("select_p_item");
                        current.removeClass("select_p_item");
                        current = null;
                        scrollToSelectedItem();
                    }
                    break;
                case 40: // down
                    if($(".select_p_item").next().length>0){
                        var current = $(".select_p_item");
                        $(".select_p_item").next().addClass("select_p_item");
                        current.removeClass("select_p_item");
                        current = null;
                        scrollToSelectedItem();
                    }
                    break;
                case 46: // delete
                    if($(".select_p_item").length>0){
                        deleteItem();
                    }
                    break;
                case 107: // plus
                    keyPlus();
                    break;
                case 109: // minus
                    keyMinus();
                    break;
                default: 
                    return;
            }
        }
    });
}

function keyPlus(){
    if($(".select_p_item").length>0){
        var item_id = parseInt($(".select_p_item").attr("id").split('_')[1]);
        inv.incrementItemQty(item_id);
    }
}

function keyMinus(){
    if($(".select_p_item").length>0){
        var item_id = parseInt($(".select_p_item").attr("id").split('_')[1]);
        inv.decrementItemQty(item_id);
    }
}