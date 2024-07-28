function Invoice() {
    
    var items = [];
    var self = this;
    var selected_id = null;
    var getItemFunctionLocked = false;
    
    var customer_info = [];
    
    this.getTotalQtyItems = function() {
        var total = 0;
        for(var i=0;i<items.length;i++){
            total+=parseInt(items[i].qty);
        }
        return total;
    };
    
    this.setItems = function(items_) {
        for(var i=0;i<items_.length;i++){
            this.addItem(items_[i]);
        }
    };
    
    this.delete_customer = function() {
        customer_info = [];
        self.remove_wholesale();
    };
    
    this.showCustomerNot = function(customer_info){
        $.notify.addStyle('foo', {
          html: 
            "<div>" +
              "<div class='clearfix'>" +
                "<div class='title' data-notify-html='title'/>" +
                "<div class='buttons'>" +
                    "<button class='yes' data-notify-text='button'></button>" +
                "</div>" +
              "</div>" +
            "</div>"
        });

        //listen for click events from this style
        $(document).on('click', '.notifyjs-foo-base .no', function() {
          //programmatically trigger propogating hide event
          $(this).trigger('notify-hide');
        });
        $(document).on('click', '.notifyjs-foo-base .yes', function() {
          //show button text
          customer_info = [];
          self.delete_customer();
          //hide notification
          $(this).trigger('notify-hide');
        });

        $("#client_name").notify({
            title: ''+customer_info[0].name.ucwords(),
            button: 'Cancel'
        }, { 
            style: 'foo',
            autoHide: false,
            clickToHide: false
        });
    };
    
    this.setCustomerId = function(id_) {
        $.getJSON("?r=pos&f=get_customer_by_id&p0="+id_, function (data) {
            customer_info = data;
            self.showCustomerNot(customer_info);
            //$("#client_name").notify(data[0].name.ucwords(),{position:"bottom center",autoHide:false,clickToHide: false});
        }).done(function () {
            self.apply_wholesale();
        }).fail(function() {

        });
    };
    
    this.getcustomer_info = function(){
        return customer_info;
    };
    
    this.setcustomer_info = function(info){
        customer_info = info;
    };
    
    this.getTotalPrice = function() {
        var total = 0;
        var total_item_qty = 0;
        for(var i=0;i<items.length;i++){
            total+=(items[i].final_price*items[i].qty);
            if(items[i].vat>0){
                total+=(items[i].final_price*items[i].qty*(items[i].vat-1));
            }
            total_item_qty+=parseFloat(items[i].qty);
        }
        if(items.length>0){
            $("#holdBtn").removeClass("disabledOnHold");
            $(".mdisable").removeClass("disableBtn");
        }else{
            $("#holdBtn").addClass("disabledOnHold");
            $(".mdisable").addClass("disableBtn");
        }
        
        $("#t_n_i").html(total_item_qty);
            
        this.reorder_rw_nb();
        
        //return total;
        return accounting.toFixed(total, 1);
    };
    
    this.getTotalItems = function(){
        return items.length;
    };
    
    this.reorder_rw_nb = function(){
        $( ".rw_nb" ).each(function( index ) {
           $(this).html(index+1);
        });
    };
    
    this.print_invoice = function(id,gift){
        if(a4_printer==0){
            $.getJSON("?r=print_invoice&f=print_invoice_id&p0="+id+"&p1="+gift, function (data) {
            }).done(function () {
            }).fail(function() {
            });
        }else{
            $.getJSON("?r=reports_generator&f=generate_invoice&p0="+id, function (data) {
            }).done(function () {
            }).fail(function() {
            });
        }
    };
    
    this.open_cashDrawer = function(){
        $.getJSON("?r=print_invoice&f=open_cashDrawer", function (data) {
            $.each(data, function (key, val) {
            });
        }).done(function () {

        }).fail(function() {
            //logged_out_warning();
        });
    };
    
    
    this.reset = function(){
        items = [];
        selected_id = null;
        customer_info = [];
        //$("#client_name").
                
        $(".notifyjs-foo-base").trigger('notify-hide');

        $("#p_items").empty();
        $("#totalPrice").html(format_price(self.getTotalPrice()));
    };
    
    this.getData = function(){
        return items;
    };
    
    this.getDataMinimized = function(){
        var items_to_submit = [];
        for(var i=0;i<items.length;i++){
            items_to_submit.push({id:items[i].id,description:items[i].description,qty:items[i].qty,ds:items[i].discount,m_d:items[i].manual_discounted,custom_item:items[i].custom_item,mobile_transfer_item:items[i].mobile_transfer_item,mobile_transfer_device_id:items[i].mobile_transfer_device_id,price:items[i].price});
        }
        return items_to_submit;
    };
    
    this.incrementItemQty = function(item_id,manual){ 
        var index = null;
        for(var i=0;i<items.length;i++){
            if(items[i].id == item_id && items[i].plu==0){
                items[i].qty++;
                items[i].final_price = items[i].price*(1-(items[i].discount/100));
                
                //if(items[i].vat>0){
                    //items[i].final_price = items[i].final_price + items[i].final_price*(vat_value-1);
                //}
                
                index = i;
                break;
            }
        }

        if(index!=null){
            $("#qty_"+items[index].id).html(items[index].qty);
            if(items[index].vat>0){
                $("#final_"+items[index].id).html(format_price(items[index].qty*items[index].final_price*items[index].vat));
            }else{
                $("#final_"+items[index].id).html(format_price(items[index].qty*items[index].final_price));
            }
            $("#totalPrice").html(format_price(self.getTotalPrice()));
            
            if(manual==0){
                this.submit_to_customer_display(items[index].id,items[index].qty);
            }
            
            //$.playSound('libraries/sounds/success.mp3');
        }
    };
    
    this.decrementItemQty = function(item_id,manual){
        var index = null;
        for(var i=0;i<items.length;i++){
            if(items[i].id == item_id && items[i].plu==0){
                if(items[i].qty>1){
                    items[i].qty--;
                    index = i;
                    break;
                }
            }
        }
        if(index!=null){
            $("#qty_"+items[index].id).html(items[index].qty);
            //$("#final_"+items[index].id).html(format_price(items[index].qty*items[index].final_price));
            
            if(items[index].vat>0){
                $("#final_"+items[index].id).html(format_price(items[index].qty*items[index].final_price*items[index].vat));
            }else{
                $("#final_"+items[index].id).html(format_price(items[index].qty*items[index].final_price));
            }
            
            
            $("#totalPrice").html(format_price(self.getTotalPrice()));
            if(manual==0){
                this.submit_to_customer_display(items[index].id,items[index].qty);
            }
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
            
            if(sound_play==1){
                $.playSound('libraries/sounds/success.mp3');
            } 
            
            this.delete_to_customer_display(item_id);
        }
        
        if(items.length==0){
            $("#pay").addClass("disabledPay");
        }
    };
    
    this.clear_customer_display = function(){
        if(enable_customer_display==1){
            $.getJSON("?r=print_invoice&f=clear_customer_display", function (data) {

            }).done(function () {  
            }).fail(function() {
                logged_out_warning();
            }).always(function() {
            });
        }
    };
    
    this.delete_to_customer_display = function(item_id){
        if(enable_customer_display==1){
            $.getJSON("?r=print_invoice&f=delete_to_customer_display&p0="+item_id+"&p1="+self.getTotalPrice(), function (data) {

            }).done(function () {  
            }).fail(function() {
                //logged_out_warning();
            }).always(function() {
            });
        }
    };
    
    this.submit_to_customer_display = function(item_id,qty){
        if(enable_customer_display==1){
            var price_tmp = 0;
            for(var i=0;i<items.length;i++){
                if(items[i].id == item_id){
                    price_tmp = items[i].final_price;
                    break;
               }
            }
            $.getJSON("?r=print_invoice&f=submit_to_customer_display&p0="+item_id+"&p1="+qty+"&p2="+price_tmp+"&p3="+self.getTotalPrice(), function (data) {

            }).done(function () {  
            }).fail(function() {
                //logged_out_warning();
            }).always(function() {
            });
        }
    };
    
    this.change_discount_percentage = function(item_id,new_value){
        for(var i=0;i<items.length;i++){
            if(items[i].id == item_id){
                var dv = (1-(new_value/100))*items[i].price;
                
                items[i].discount = new_value;
                items[i].final_price = dv;
                items[i].manual_discounted = 1;
                
                $("#discount_"+item_id).html(parseFloat(new_value).toFixed(2)+"%");
                $("#price_"+item_id).html(format_price(dv));
                
                if(items[i].vat>0){
                    $("#final_"+items[i].id).html(format_price(items[i].qty*items[i].final_price*items[i].vat));
                }else{
                    $("#final_"+items[i].id).html(format_price(items[i].qty*items[i].final_price));
                }
            
                //$("#final_"+item_id).html(format_price(items[i].qty*items[i].final_price));
                
                this.submit_to_customer_display(item_id,items[i].qty);
                break;
            }
        }
        $("#totalPrice").html(format_price(self.getTotalPrice()));
    },
            
    this.apply_wholesale = function(){
        for(var i=0;i<items.length;i++){
            self.change_discount(items[i].id,parseFloat(items[i].wholesale_price));
        }
    } 
    
    this.remove_wholesale = function(){
        for(var i=0;i<items.length;i++){
            self.change_discount(items[i].id,parseFloat(items[i].price));
        }
    } 
    
    this.change_discount = function(item_id,new_value){
        for(var i=0;i<items.length;i++){
            if(items[i].id == item_id){
                var dv = 100-(new_value*100)/items[i].price;
                items[i].discount = dv;
                items[i].final_price = new_value;
                items[i].manual_discounted = 1;
                
                $("#discount_"+item_id).html(parseFloat(dv).toFixed(2)+"%");
                $("#price_"+item_id).html(new_value);
                //$("#final_"+item_id).html(format_price(items[i].qty*items[i].final_price));
                
                if(items[i].vat>0){
                    $("#final_"+items[i].id).html(format_price(items[i].qty*items[i].final_price*items[i].vat,5));
                }else{
                    $("#final_"+items[i].id).html(format_price(items[i].qty*items[i].final_price));
                }
                
                this.submit_to_customer_display(item_id,items[i].qty);
                break;
            }
        }
        $("#totalPrice").html(format_price(self.getTotalPrice()));
    }
    
    this.addItem  = function (info) {
        //alert(info["mobile_transfer_item"]);
        var exist = false;
        var index = 0;
        for(var i=0;i<items.length && info["mobile_transfer_item"]==0;i++){
            if(items[i].id == info["id"]){
                if(items[i].plu==1){
                    items[i].qty+=info["qty"];
                }else{
                    items[i].qty++;
                }
                exist=true;
                index = i;
                break;
            }
        }
        
        $(".select_p_item").removeClass("select_p_item");
        if(!exist){
            selected_id = info["id"];
            items.push({id: info["id"],vat: info["vat"],price: info["price"],qty: info["qty"],barcode: info["barcode"],description: info["description"],discount:info["discount"],final_price:info["final_price"],custom_item:info["custom_item"],mobile_transfer_item:info["mobile_transfer_item"],mobile_transfer_device_id:info["mobile_transfer_device_id"],plu:info["plu"],measure_label:info["measure_label"],manual_discounted:info["manual_discounted"],wholesale_price:info["wholesale_price"],stock_qty:info["stock_qty"],composit_qty:info["composit_qty"]});
            
            var price_after_vat_and_dicount = info["qty"]*info["final_price"];
            if(info["vat"]>0){
                price_after_vat_and_dicount=price_after_vat_and_dicount+(price_after_vat_and_dicount*(info["vat"]-1));
            }
            
            $("#p_items").append("<div onclick='selectItemByClick("+info["id"]+")' class='row purchases select_p_item' id='it_"+info["id"]+"' style="+direction_+">\n\
                <div class='col-lg-2 col-md-2 col-sm-2 col-xs-1 "+pull_+"'>\n\
                    <div style='padding-left:1px;padding-right:1px;' class='col-lg-4 col-md-4 col-sm-4 col-xs-4 "+pull_+" rw_nb'>1</div>\n\
                    <div style='padding-left:1px;padding-right:1px;' class='col-lg-8 col-md-8 col-sm-8 col-xs-8 "+pull_+"'>"+info["barcode"]+"</div>\n\
                </div>\n\
                <div style='padding-left:1px;padding-right:1px;' class='col-lg-5 col-md-5 col-sm-5 col-xs-5 "+pull_+"'>"+info["description"]+" (Stock: "+info["stock_qty"]+")</div>\n\
                <div style='padding-left:1px;padding-right:1px;' class='col-lg-2 col-md-2 col-sm-2 col-xs-2 "+pull_+"'>\n\
                    <div style='padding-left:1px;padding-right:1px;' class='col-lg-3 col-md-3 col-sm-3 col-xs-3 "+pull_+"'><span id='qty_"+info["id"]+"'>"+info["qty"]+"</span>&nbsp;<span class='u_box' id='compoqty_"+info["id"]+"'>"+info["composit_qty"]+"</span></div>\n\
                    <div style='padding-left:1px;padding-right:1px;' class='col-lg-9 col-md-9 col-sm-9 col-xs-9 "+pull_+"' id='price_"+info["id"]+"' >"+format_price(info["final_price"])+"</div>\n\
                </div>\n\
                <div style='padding-left:1px;padding-right:1px;' class='col-lg-3 col-md-3 col-sm-3 col-xs-3 "+pull_+"'>\n\
                    <div style='padding-left:1px;padding-right:1px;' class='col-lg-3 col-md-3 col-sm-3 col-xs-3 "+pull_+"' id='discount_"+info["id"]+"'>"+parseFloat(info["discount"]).toFixed(2)+"%</div>\n\
                    <div style='padding-left:1px;padding-right:1px;' class='col-lg-3 col-md-3 col-sm-3 col-xs-3 "+pull_+"' id='vat_"+info["id"]+"'>"+info["vat"]+"</div>\n\
                    <div style='padding-left:1px;padding-right:1px;' class='col-lg-6 col-md-6 col-sm-6 col-xs-6 "+pull_+"' id='final_"+info["id"]+"'>"+format_price(price_after_vat_and_dicount)+"</div>\n\
                </div>\n\
                </div>");
            this.submit_to_customer_display(info["id"],info["qty"]);
        }else{
            selected_id = items[index].id;
            $("#it_"+items[index].id).remove();
            if(items[index].measure_label==null)
                items[index].measure_label = "";
   
            var price_after_vat_and_dicount = items[index].qty*items[index].final_price;
            if(items[index].vat>0){
                price_after_vat_and_dicount=price_after_vat_and_dicount+(price_after_vat_and_dicount*(items[index].vat-1));
            }

            $("#p_items").append("<div onclick='selectItemByClick("+info["id"]+")' class='row purchases select_p_item' id='it_"+items[index].id+"' style="+direction_+">\n\
                <div class='col-lg-2 col-md-2 col-sm-2 col-xs-1 "+pull_+"'>\n\
                    <div style='padding-left:1px;padding-right:1px;' class='col-lg-4 col-md-4 col-sm-4 col-xs-4 "+pull_+" rw_nb'>1</div>\n\
                    <div style='padding-left:1px;padding-right:1px;' class='col-lg-8 col-md-8 col-sm-8 col-xs-8 "+pull_+"'>"+info["barcode"]+"</div>\n\
                </div>\n\
                <div style='padding-left:1px;padding-right:1px;' class='col-lg-5 col-md-5 col-sm-5 col-xs-5 "+pull_+"'>"+items[index].description+" (Stock: "+items[index].stock_qty+")</div>\n\
                <div style='padding-left:1px;padding-right:1px;' class='col-lg-2 col-md-2 col-sm-2 col-xs-2 "+pull_+"'>\n\
                    <div style='padding-left:1px;padding-right:1px;' class='col-lg-3 col-md-3 col-sm-3 col-xs-3 "+pull_+"'><span id='qty_"+items[index].id+"'>"+items[index].qty+"</span>&nbsp;<span class='u_box' id='compoqty_"+items[index].id+"'>"+items[index].composit_qty+"</span></div>\n\
                    <div style='padding-left:1px;padding-right:1px;' class='col-lg-9 col-md-9 col-sm-9 col-xs-9 "+pull_+"' id='price_"+items[index].id+"'>"+format_price(items[index].final_price)+"</div>\n\
                </div>\n\
                <div  style='padding-left:1px;padding-right:1px;' class='col-lg-3 col-md-3 col-sm-3 col-xs-3 "+pull_+"'>\n\
                    <div style='padding-left:1px;padding-right:1px;' class='col-lg-3 col-md-3 col-sm-3 col-xs-3 "+pull_+"' id='discount_"+items[index].id+"'>"+parseFloat(items[index].discount).toFixed(2)+"%</div>\n\
                    <div style='padding-left:1px;padding-right:1px;' class='col-lg-3 col-md-3 col-sm-3 col-xs-3 "+pull_+"' id='vat_"+items[index].vat+"'>"+items[index].vat+"</div>\n\
                    <div style='padding-left:1px;padding-right:1px;' class='col-lg-6 col-md-6 col-sm-6 col-xs-6 "+pull_+"' id='final_"+items[index].id+"'>"+format_price(price_after_vat_and_dicount)+"</div>\n\
                </div>\n\
                </div>"); 
            this.submit_to_customer_display(items[index].id,items[index].qty);
        }
        $("#totalPrice").html(format_price(self.getTotalPrice()));
        
        $("#pay").removeClass("disabledPay");
        
        //$('#p_items').scrollTop($('#p_items').scrollHeight);
        
        $('#p_items').scrollTop($('#p_items').prop("scrollHeight"));
    };
    
    this.updatePurchasedList = function(){
        showPurchasedItem(null,null);
    };
    /*
    this.showMultiItems = function(all_data){
        if($('#showMultiItems').length>0){
            $('#showMultiItems').modal('hide');
        }
        var item_content = "<div class='row'>";
        $.each(all_data, function (key, val) {
            item_content+="<div class='col-lg-6 col-md-6 col-sm-12 col-xs-12 funcContainer'><div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 func fmit' id='"+val.id+"'>"+val.description+" <p>"+val.selling_price+"</p></div><div class='col-lg-4 col-md-4 col-sm-4 col-xs-4' style='background-color:"+val.color_id+";border:1px solid #000'>&nbsp;</div><div class='col-lg-4 col-md-4 col-sm-4 col-xs-4' style='background-color:#fff;border:1px solid #000'>Color Name: "+val.color_text_id+"</div><div class='col-lg-4 col-md-4 col-sm-4 col-xs-4' style='background-color:#fff;border:1px solid #000'>Size: "+val.size_id+"</div></div>";
        });
        item_content+="<div class='row'>";
        var content =
        '<div class="modal" data-backdrop="static" id="showMultiItems" tabindex="-1" role="dialog" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title" '+dir_+'>Select Item<i style="float:'+float_+';font-size:30px" class="glyphicon glyphicon-remove" onclick="closeModal(\'showMultiItems\')"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                    '+item_content+'\n\
                    </div>\n\
                    <div class="modal-footer">\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>';
        $("#showMultiItems").remove();
        $("body").append(content);

        $("#showMultiItems").centerWH();

        $('#showMultiItems').on('show.bs.modal', function (e) {
            $(".fmit").on('click', function (e) {
                inv.getItemById($(this).attr('id'));
                $('#showMultiItems').modal('hide');
            });
        });
        $('#showMultiItems').on('hide.bs.modal', function (e) {
            $('#showMultiItems').remove();
        });
        $('#showMultiItems').modal('show');
    };
    */
    this.addMobileTransferItem = function(info_){
        var info = [];
        info["id"] =  Math.floor(Date.now() / 1000);
        info["description"] = info_["description"];
        info["vat"] = 0;
        
        info["qty"] = 1;
        info["barcode"] = "";
        info["discount"] = 0;
        info["price"] = parseFloat(info_["price"]);
        info["final_price"] = parseFloat(info_["price"]);
        info["custom_item"] = 1;
        info["mobile_transfer_item"] = parseInt(info_["mobile_transfer_item"]);
        info["mobile_transfer_device_id"] = info_["id_device"];
        info["wholesale_price"] = 0;
        
        info["manual_discounted"] = 0;
        
        info["stock_qty"] = 0;
        
        info["composit_qty"]="";
        self.addItem(info);
    };
    
    this.addCustomItem = function(info_){
        var info = [];
        info["id"] =  Math.floor(Date.now() / 1000);
        info["description"] = info_["description"];
        info["vat"] = 0;
        info["qty"] = 1;
        info["barcode"] = "";
        info["discount"] = 0;
        info["price"] = parseFloat(info_["price"]) ;
        info["final_price"] = parseFloat(info_["price"]) ;
        info["custom_item"] = 1;
        info["mobile_transfer_item"] = 'NULL';
        info["mobile_transfer_device_id"] = 0;
        info["wholesale_price"] = 0;
        info["stock_qty"] = 0;
        info["manual_discounted"] = 0;
        
        info["composit_qty"]="";
                    
        self.addItem(info);
    };

    this.getItemByBarcode = function (barcode) {
        if(getItemFunctionLocked==false){
            getItemFunctionLocked=true;
            var info = [];
            var data_length = 0;
            var all_data = [];
            var bc = "";
            $.getJSON("?r=pos&f=get_item_by_barcode&p0=" + encodeURIComponent(barcode), function (data) {
                data_length = data.length;
                all_data = data;
                $.each(data, function (key, val) {
                    info["plu"] = val.plu;
                    info["id"] = val.id;
                    info["description"] = val.description;
                    
                    info["vat"] = 0;
                    if(val.vat==1){
                        info["vat"] = vat_value;
                    }
                    
                    info["manual_discounted"] = 0;
                    
                    
                    info["qty"] = val.qty;
                    
                    
                    info["composit_qty"]="";
                    if(val.is_composite ==1 && val.composite_items.length>0){
                        info["composit_qty"] = "("+val.composite_items[0].qty+"/box)";
                    }
                    
                    info["measure_label"] = val.measure_label;
                    info["barcode"] = val.barcode;
                    bc =  val.barcode;
                    info["discount"] = val.discount;
                    info["price"] = parseFloat(val.selling_price);
                    info["final_price"] = precisionRound(info["price"]*(1-(info["discount"]/100)),round_val);
                    info["custom_item"] = 0;
                    info["mobile_transfer_item"] = 0;
                    info["mobile_transfer_device_id"] = 0;
                    info["wholesale_price"] = val.wholesale_price;
                    info["stock_qty"] = val.quantity;
                });
            }).done(function () {
                if(data_length>1){
                    //self.showMultiItems(all_data);
                    showAllItems(bc);
                    if(sound_play==1){
                        $.playSound('libraries/sounds/out-of-bounds.mp3');
                    }
                    
                    
                }else{
                    if(data_length>0){
                        self.addItem(info);
                        if($(".sweet-alert").length>0){
                            $(".sweet-alert").remove();
                            $(".sweet-overlay").remove();
                            lockMainPos = false;
                        }
                        if(sound_play==1){
                            $.playSound('libraries/sounds/success.mp3');
                        }
                        
                    }else{
                        if(sound_play==1){
                            $.playSound('libraries/sounds/beep-02.mp3');
                        }
                        
                    }
                }
            }).fail(function() {
                logged_out_warning();
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
            $.getJSON("?r=pos&f=get_item&p0=" + id, function (data) {
                data_length = data.length;
                $.each(data, function (key, val) {
                    info["id"] = val.id;
                    info["price"] = val.selling_price;
                    info["description"] = val.description;
                    
                    info["vat"] = 0;
                    if(val.vat==1){
                        info["vat"] = vat_value;
                    }
                    
                    measure_label = val.measure_label;
                    
                    info["qty"] = val.qty;
                    
                    info["composit_qty"]="";
                    if(val.is_composite ==1 && val.composite_items.length>0){
                        info["composit_qty"] = "("+val.composite_items[0].qty+"/box)";
                    }
                    
                    info["measure_label"] = val.measure_label;
                    info["plu"] = val.plu;
                    
                    info["barcode"] = val.barcode;
                    info["discount"] = val.discount;
                    info["price"] = parseFloat(val.selling_price);

                    info["final_price"] = precisionRound(info["price"]*(1-(info["discount"]/100)),round_val);
                    info["custom_item"] = 0;
                    info["mobile_transfer_item"] = 0;
                    info["mobile_transfer_device_id"] = 0;
                    info["wholesale_price"] = val.wholesale_price;
                    info["stock_qty"] = val.quantity;
                    
                    info["manual_discounted"] = 0;
                });
            }).done(function () {
                if(data_length>0){
                    self.addItem(info);
                    if(customer_info.length>0){
                        self.apply_wholesale();
                    }
                    
                    if(sound_play==1){
                        $.playSound('libraries/sounds/success.mp3');
                    }
                    
                }else{
                    
                }
            }).fail(function() {
                logged_out_warning();
            })
            .always(function() {
               getItemFunctionLocked=false;
            });
        };
    };
}

function tracking_pos(action,item_id){
    $.getJSON("?r=pos&f=tracking_pos&p0="+action+"&p1="+item_id, function (data) {
        
    }).done(function () {
        
    }).fail(function() {
        logged_out_warning();
    });
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
    if(licenseExpired){
        alert("expired");
    }else{
        if($("#pay").hasClass("disabledPay") == false){
            showPaymentInformation();
        }
    }
}

function removeOverlay(){
    //$("#overlay").remove();
    $("#payment_info").remove();
}

function discountChanged(){
    if(accounting.unformat($("#discount").val())=="" || accounting.unformat($("#discount").val())==null ){
        //$("#discount").val(inv.getTotalPrice());
    }
    cash_from_client_changed();
}

var showPaymentInformationFunctionLocked = false;
function showPaymentInformation(){
    if(showPaymentInformationFunctionLocked==false){
        showPaymentInformationFunctionLocked==true;
        lockMainPos = true;
        var options = '';
        if(settings_pl == 1) options+='<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6"><button onclick="Payment_later()" type="submit" class="btn btn-primary">'+LG_LATER_PAYMENT+'</button></div>';
        if(settings_pf == 1) options+='<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6"><button onclick="Payment()" type="submit" class="btn btn-primary">'+LG_CASH+'</button></div>';
        if(settings_cc == 1) options+='<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6"><button onclick="PaymentCreditCard()" type="submit" class="btn btn-primary">'+LG_CREDIT_CARD+'</button></div>';
        if(settings_pc == 1) options+='<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6"><button onclick="PaymentCheque()" type="submit" class="btn btn-primary">By Cheque</button></div>';
        
        invoice_discount_read_only = "";
        if(enable_invoice_discount==0){
            invoice_discount_read_only = "readonly";
        }
        
        var salespersons = "<option value='0' title='Select Salesperson'>Select Salesperson</option>";
        for(var i=0;i<salesperson.length;i++){
            salespersons+="<option value='"+salesperson[i].id+"' title='"+salesperson[i].first_name+" "+salesperson[i].last_name+"'>"+salesperson[i].first_name+" "+salesperson[i].last_name+"</option>";
        }
        
        var content =
            '<div class="modal" data-backdrop="static" id="payment_info" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true" style="'+direction_+'">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title" id="payment_info__"><i class="glyphicon glyphicon-usd"></i>&nbsp;'+LG_PAY+'</h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 '+pull_+'">\n\
                                <div class="panel panel-default">\n\
                                    <div class="panel-heading"><b>Customer Info</b></div>\n\
                                    <div class="panel-body" style="padding:10px;">\n\
                                        <input name="customer_id" id="customer_id" type="hidden" value="0" />\n\
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 '+pull_+'">\n\
                                            <div class="form-group" style="margin-bottom:3px;">\n\
                                                <input autocomplete="off" id="customer_name_payment" name="customer_name_payment" data-provide="typeahead" type="text" class="form-control" placeholder="'+LG_CUSTOMER_MAME+'">\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 '+pull_+'">\n\
                                            <div class="form-group" style="margin-bottom:3px;">\n\
                                                <input autocomplete="off" id="customer_middle_payment" name="customer_middle_payment" type="text" class="form-control" placeholder="Middle Name">\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 '+pull_+'">\n\
                                            <div class="form-group" style="margin-bottom:3px;">\n\
                                                <input autocomplete="off" id="customer_last_payment" name="customer_last_payment" type="text" class="form-control" placeholder="Last Name">\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">\n\
                                            <div class="form-group" style="margin-bottom:5px;">\n\
                                                <input autocomplete="off" id="customer_phone" name="customer_phone" data-provide="typeahead" type="text" class="form-control" placeholder="'+LG_PHONE+'"/>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">\n\
                                            <div class="form-group" style="margin-bottom:5px;">\n\
                                                <input autocomplete="off" id="customer_address" name="customer_address" data-provide="typeahead" type="text" class="form-control" placeholder="'+LG_ADDRESS+'">\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 '+pull_+'">\n\
                                <div class="panel panel-default">\n\
                                    <div class="panel-heading"><b>Invoice Info</b></div>\n\
                                    <div class="panel-body" style="padding:5px;">\n\
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 '+pull_+'">\n\
                                        <div class="form-group">\n\
                                            <label for="usr">'+LG_TOTAL_AMOUNT+'</label>\n\
                                            <input id="total_price" readonly value="" type="text" class="form-control onPay">\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">\n\
                                        <div class="form-group">\n\
                                            <label for="usr">'+LG_TOTAL_AFTER_INVOICE_DISCOUNT+'</label>&nbsp;<span id="cus_disc" style="display:none"></span>\n\
                                            <input '+invoice_discount_read_only+' onkeyup="discountChanged()" type="text" class="form-control onPay only_num" id="discount">\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 '+pull_+'">\n\
                                        <div class="form-group">\n\
                                            <label for="usr">Cash From Client '+default_currency_symbol+'</label>\n\
                                            <input id="cash_from_client" onkeyup="cash_from_client_changed()" value="" type="text" class="form-control onPay only_num" autofocus>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">\n\
                                        <div class="form-group">\n\
                                            <label for="usr">Return To Client '+default_currency_symbol+'</label>\n\
                                            <input readonly id="return_to_client" value="" type="text" class="form-control onPay" >\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                        <div class="form-group">\n\
                                            <input autocomplete="off" id="payment_note" name="payment_note" type="text" class="form-control" placeholder="Note">\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">\n\
                                        <div class="form-group">\n\
                                            <select data-size="5" data-live-search="true" id="sales_person_id" name="sales_person_id" class="selectpicker form-control" >'+salespersons+'</select>\n\
                                        </div>\n\
                                    </div>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                        <div>\n\
                    </div>\n\
                    <div class="modal-footer" style="border:none">\n\
                        <div class="row"><div class="col-lg-3 col-md-3 col-sm-6 col-xs-6"><button type="button" class="btn btn-secondary payi_cancel" data-dismiss="modal">'+LG_CANCEL+'</button></div>'+options+'</div> \n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>';
        $("#payment_info").remove();
        $("body").append(content);
        $("#payment_info").centerWH();
       
        $('#discount').val(accounting.formatMoney(inv.getTotalPrice(), { symbol: default_currency_symbol,  format: "%v %s" }));
        $('#total_price').val(accounting.formatMoney(inv.getTotalPrice(), { symbol: default_currency_symbol,  format: "%v %s" }));
        
       
        
        $('#return_to_client').val(accounting.formatMoney(0, { symbol: default_currency_symbol,  format: "%v %s" }));
        
        $('#discount').select();
        
        $('.selectpicker').selectpicker();
        
        var $input = null;
        var $input_phone = null;
        $.get("?r=pos&f=getAllCustomersDetails", function(data){
            
            var sourceArr = [];
            for (var i = 0; i < data.length; i++) {
               sourceArr.push({phone:data[i].phone,first_name:data[i].name,middle_name:data[i].middle_name,last_name:data[i].last_name,only_name:data[i].name+" "+data[i].middle_name+" "+data[i].last_name,name:data[i].name+" "+data[i].middle_name+" "+data[i].last_name+"-"+data[i].phone,id:data[i].id,address:data[i].address,discount:data[i].discount});
            }

            $input = $("#customer_name_payment");
            $input_phone = $("#customer_phone");
            $input.typeahead({
              source: sourceArr,
              autoSelect: true,
            });
            
            $input_phone.typeahead({
              source: sourceArr,
              autoSelect: true,
            });

            $input.change(function() {
                var current = $input.typeahead("getActive");
                if (current) {
                    if (current.name == $input.val()) {
                        $("#customer_name_payment").val(current.first_name);
                        $("#customer_middle_payment").val(current.middle_name);
                        $("#customer_last_payment").val(current.last_name);
                        
                        $("#customer_id").val(current.id);
                        $("#customer_phone").val(current.phone);
                        $("#customer_address").val(current.address);
                        $("#cus_disc").html(Math.floor(current.discount)+" %");
                        $("#cus_disc").show();
                        $('#discount').val(accounting.formatMoney(inv.getTotalPrice()*(1-current.discount/100), { symbol: default_currency_symbol,  format: "%v %s" }));
                    } else {
                        $("#customer_id").val(0);
                    }
                } else {
                    $("#customer_id").val(0);
                }
            });
            
            $input_phone.change(function() {
                var current = $input_phone.typeahead("getActive");
                if (current) {
                    if (current.name == $input_phone.val()) {
                        
                        $("#customer_name_payment").val(current.first_name);
                        $("#customer_middle_payment").val(current.middle_name);
                        $("#customer_last_payment").val(current.last_name);
                        
                        
                        $("#customer_id").val(current.id);
                        $("#customer_phone").val(current.phone);
                        $("#customer_address").val(current.address);
                        $("#cus_disc").html(Math.floor(current.discount)+" %");
                        $("#cus_disc").show();
                        $('#discount').val(accounting.formatMoney(inv.getTotalPrice()*(1-current.discount/100), { symbol: default_currency_symbol,  format: "%v %s" }));

                    } else {
                        $("#customer_id").val(0);
                    }
                } else {
                    $("#customer_id").val(0);
                }
            });
        },'json')
        .done(function(){
            $('#payment_info').on('show.bs.modal', function (e) {
                
            });
            $('#payment_info').on('shown.bs.modal', function (e) {
                $(".only_num").numeric({ negative : true});
                $( "#cash_from_client" ).focus();
                
                var customer_i = inv.getcustomer_info();
                if(customer_i.length>0){
                    setTimeout(function(){
                        $('#customer_name_payment').val(customer_i[0].name);
                        $('#customer_middle_payment').val(customer_i[0].middle_name);
                        $('#customer_last_payment').val(customer_i[0].last_name);
                        
                        
                        $('#customer_phone').val(customer_i[0].phone);
                        $('#customer_address').val(customer_i[0].address);
                        $("#customer_id").val(customer_i[0].id);
                    },100);
                }
            });
            $('#payment_info').on('hide.bs.modal', function (e) {
                lockMainPos = false;
                $('#payment_info').remove();
            });
            $('#payment_info').modal('show');
        })
        .fail(function() {
            alert( "error" );
        })
        .always(function() {
            showPaymentInformationFunctionLocked==false;
        });
    }
}

function cash_from_client_changed(){
    if($("#cash_from_client").val()=="" || $("#cash_from_client").val()==null) {
        //$("#return_to_client").val(0-parseFloat(accounting.unformat($("#discount").val())));
        $('#return_to_client').val(accounting.formatMoney(0-parseFloat(accounting.unformat($("#discount").val())), { symbol: default_currency_symbol,  format: "%v %s" }));
    }else{
        if(accounting.unformat(accounting.unformat($("#discount").val()))=="" || accounting.unformat(accounting.unformat($("#discount").val()))==null ){
            //$("#return_to_client").val(parseFloat($("#cash_from_client").val())-parseFloat(0));
             $('#return_to_client').val(accounting.formatMoney(parseFloat($("#cash_from_client").val())-parseFloat(0), { symbol: default_currency_symbol,  format: "%v %s" }));
        }else{
            $("#return_to_client").val(accounting.formatMoney(parseFloat($("#cash_from_client").val())-parseFloat(accounting.unformat($("#discount").val())), { symbol: default_currency_symbol,  format: "%v %s" }));
        }   
    }
    //$('#return_to_client').unmask();
    //$('#return_to_client').mask('000.000.000.000.000', {reverse: true});
    /*$('#return_to_client').mask('#.##0', {
        reverse: true,
        translation: {
          '#': {
            pattern: /-|\d/,
            recursive: true
          }
        },
        onChange: function(value, e) {      
          e.target.value = value.replace(/(?!^)-/g, '').replace(/^,/, '').replace(/^-,/, '-');
        }
    });*/
    
    if(parseFloat($("#cash_from_client").val()) < parseFloat(accounting.unformat($("#discount").val()))){
        $("#return_to_client").css("border-color", "red");
    }else{
         $("#return_to_client").css("border-color", "#CCC");
    }
    
}

var addPaymentFunctionLocked = false;
function addPayment(){
    if(addPaymentFunctionLocked==false){
        $(".sk-circle").center();
        $(".sk-circle-layer").show();
        addPaymentFunctionLocked=true;
        lockMainPos = true;
        
        var payment_options = "";
        $.getJSON("?r=settings_info&f=get_payment_method", function (data) {
            $.each(data, function (key, val) {
                payment_options+="<option value='"+val.id+"' title='"+val.method_name+"'>"+val.method_name+"</option>";
            });
        }).done(function () {
            var content =
                '<div class="modal" data-backdrop="static" id="payments_of_customer"  role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
                <div class="modal-dialog" role="document">\n\
                    <div class="modal-content">\n\
                        <div class="modal-header"> \n\
                            <h3 class="modal-title" id="payment_info__">Statement<i style="float:right;font-size:30px" class="glyphicon glyphicon-remove" onclick="closeModal(\'payments_of_customer\')"></i></h3>\n\
                        </div>\n\
                        <div class="modal-body">\n\
                            <div class="row">\n\
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">\n\
                                    <div class="form-group">\n\
                                        <select data-live-search="true" onchange="customer_changed()" data-size="10" id="customer_id" name="customer_id" class="selectpicker form-control" ></select>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="font-size:15px; text-align:center; padding-left:1px;padding-right:1px;">\n\
                                    <b style="color:red;">Total Unpaid:</b> <span id="pay_unpaid">-</span> \n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6" style="text-align:center">\n\
                                    <button disabled onclick="addPaymentToCustomer()" style="font-size:16px; width:100%" id="add_payment_btn" type="button" class="btn btn-default">Add payment</button>\n\
                                </div>\n\
                                <div class="col-lg-3 col-md-3 col-sm-12" >\n\
                                    <div class="btn-group" role="group" aria-label="" style="width:100%">\n\
                                        <div class="btn-group" id="buttons" style="float:right"></div>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="row">\n\
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                    <table id="customers_statement_table" class="table table-striped table-bordered" cellspacing="0" style="width:100%">\n\
                                        <thead>\n\
                                        <tr>\n\
                                            <th style="width: 140px;">Date</th>\n\
                                            <th style="width: 100px;">Ref. Invoice</th>\n\
                                            <th style="width: 120px;">Ref. Payment</th>\n\
                                            <th>Note</th>\n\
                                            <th style="width: 100px;">Charges</th>\n\
                                            <th style="width: 100px;">Payment Value</th>\n\
                                            <th style="width: 80px;">P. Method</th>\n\
                                            <th style="width: 100px;">Remain</th>\n\
                                            <th style="width: 80px;">Deleted flag</th>\n\
                                            <th style="width: 20px;"></th>\n\
                                            <th style="width: 20px;"></th>\n\
                                        </tr>\n\
                                    </thead>\n\
                                    <tbody></tbody>\n\
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
                    $("#customer_id").append("<option value='"+val.id+"'>"+val.name+" "+val.middle_name+" "+val.last_name+"</option>");
                });
            }).done(function () {

                $("#customer_id").selectpicker('refresh');

                $('#payments_of_customer').on('show.bs.modal', function (e) {
                    $(".sk-circle-layer").hide();
                     updateCustomersPaymentsTable();
                });
                $('#payments_of_customer').on('hide.bs.modal', function (e) {
                    lockMainPos = false;
                    _table = null;
                    $('#payments_of_customer').remove();
                });
                $('#payments_of_customer').modal('show');

            }).fail(function() {
                logged_out_warning();
                lockMainPos = false;
            })
            .always(function() {
                addPaymentFunctionLocked=false;
            });
        });
    }
}

var _table = null;
function updateCustomersPaymentsTable(){
    _table = $('#customers_statement_table').dataTable({
        ajax: "?r=customers&f=get_customer_statement&p0="+$("#customer_id").val(),
        orderCellsTop: true,
        aoColumnDefs: [
            { "targets": [0], "searchable": true, "orderable": false, "visible": true },
            { "targets": [1], "searchable": true, "orderable": false, "visible": true },
            { "targets": [2], "searchable": true, "orderable": false, "visible": true },
            { "targets": [3], "searchable": true, "orderable": false, "visible": true,sClass: "alignCenter" },
            { "targets": [4], "searchable": true, "orderable": false, "visible": true },
            { "targets": [5], "searchable": true, "orderable": false, "visible": true },
            { "targets": [6], "searchable": true, "orderable": false, "visible": true },
            { "targets": [7], "searchable": true, "orderable": false, "visible": true },
            { "targets": [8], "searchable": true, "orderable": false, "visible": false },
            { "targets": [9], "searchable": false, "orderable": false, "visible": true,sClass: "alignCenter" },
            { "targets": [10], "searchable": false, "orderable": false, "visible": true,sClass: "alignCenter" },
        ],
        ordering: false,
        scrollCollapse: true,
        paging: true,
        select: true,
        dom: '<"toolbar">frtip',
        initComplete: function( settings ) {
            var buttons = new $.fn.dataTable.Buttons(_table, {
            buttons: [
              {
                    extend: 'excel',
                    text: 'Export excel',
                    className: 'exportExcel',
                    filename: 'Statement',
                    customize: _customizeExcelOptions,
                    exportOptions: {
                        modifier: {
                            page: 'all'
                        },
                        columns: [ 0,1,2,3,4,5,6,7 ]
                        //format: {
                            //body: function ( data, row, column, node ) {
                                // Strip $ from salary column to make it numeric
                                ///return column === 6 ? data.replace( /[L.L.,]/g, '' ) : data;
                            //}
                        //}
                    }
              }
            ]

       }).container().appendTo($('#buttons'));

       function _customizeExcelOptions(xlsx) {
           var sheet = xlsx.xl.worksheets['sheet1.xml'];
            var clR = $('row', sheet);
            //var r1 = Addrow(clR.length+2, [{key:'A',value: "Total Credit Notes"},{key:'B',value: total}]);
            //sheet.childNodes[0].childNodes[1].innerHTML = sheet.childNodes[0].childNodes[1].innerHTML + r1;

            //$('row c[r^="A'+(clR.length+2)+'"]', sheet).attr('s', '48');

            function Addrow(index, data) {
                var msg = '<row r="' + index + '">'
                for (var i = 0; i < data.length; i++) {
                    var key = data[i].key;
                    var value = data[i].value;
                    msg += '<c t="inlineStr" r="' + key + index + '">';
                    msg += '<is>';
                    msg += '<t>' + value + '</t>';
                    msg += '</is>';
                    msg += '</c>';
                }
                msg += '</row>';
                return msg;
            }
       }
        },
        fnDrawCallback: updateRows,
        fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            $(nRow).addClass(aData[2]);
        },
    });
}

function add_item_to_invoice(id){
    $.getJSON("?r=invoice&f=addItemsToInvoice&p0=" + id+"&p1="+$("#search_item_to_add_id").val(), function (data) {
    
    }).done(function () {
        $("#update_invoice").submit();
        $("#search_item_to_add").val("");
    });
    
    
    
    //alert($("#search_item_to_add_id").val());
}

function updateRows(){
    var table = $('#customers_statement_table').DataTable();
    var p = table.rows({ page: 'current' }).nodes();
    for (var k = 0; k < p.length; k++){
        var index = table.row(p[k]).index();
        if(table.cell(index, 8).data()==0 && table.cell(index, 1).data()==""){
            if(table.cell(index, 2).data().indexOf("CUS") !== -1){
                table.cell(index, 9).data('<i class="glyphicon glyphicon glyphicon-trash trash_icon" onclick="delete_customer_payment(\''+parseInt(table.cell(index, 2).data().split("-")[1])+'\')"></i>');
                table.cell(index, 10).data('<i class="glyphicon glyphicon glyphicon-edit trash_icon" onclick="edit_customer_payment(\''+parseInt(table.cell(index, 2).data().split("-")[1])+'\')"></i>');
            }
        }
    }
}

function delete_customer_payment(pay_id){
    swal({
        title: "Are you sure?",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: true
    },
    function(isConfirm){
       if(isConfirm){
            $.getJSON("?r=customers&f=delete_customer_payment&p0="+pay_id, function (data) {

            }).done(function () {
                customer_changed();
            });
       }
    }); 
}


function refreshCustomersPaymentsTable(){
    var table = $('#customers_statement_table').DataTable();
    table.ajax.url("?r=customers&f=get_customer_statement&p0="+$("#customer_id").val()).load(function () {
        table.page('last').draw(false);
    },false);
}

function addPaymentToCustomer(){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    addCustomerPaymentDetails($("#customer_id").val(),0,[],"pos");
    $("#add_payment_btn").removeAttr("disabled");
    /*
    if($("#pay_val").val() != 0){
        $("#pay_val").attr("disabled","disabled");
        $("#add_payment_btn").attr("disabled","disabled");
        var cashBoxTotalReturn = 0;
        $.getJSON("?r=invoice&f=addCustomerPaymentDetails&p0=" + $("#customer_id").val() +"&p1="+$("#pay_val").val()+"&p2="+$("#payment_method").val(), function (data) {
            cashBoxTotalReturn = data.cashBoxTotal;
        }).done(function () {
            //$("#cashboxTotal").html(cashBoxTotalReturn);
            refreshCustomersPaymentsTable();
            updateCustomerPaymentInfo();
        }).fail(function() {
            logged_out_warning();
        })
        .always(function() {
            $("#add_payment_btn").removeAttr("disabled");
            $("#pay_val").removeAttr("disabled");
            $("#pay_val").val(0);
        });
    }
    */
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
    refreshCustomersPaymentsTable();
}

function updateCustomerPaymentInfo(){
    $.getJSON("?r=pos&f=getCustomersPaymentInfo&p0="+$("#customer_id").val(), function (data) {
        $("#pay_unpaid").html(format_price_already_fixed(data.total_remain));
        //$("#customer_balance").html(format_price_already_fixed(data.customer_balance));
        //$("#pay_remain").html(format_price_already_fixed(data.total_remain));
    }).done(function () {

    }).fail(function() {
            logged_out_warning();
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
            //$("#cashboxTotal").html(cashBoxTotalReturn);
        }).fail(function() {
            logged_out_warning();
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
            more_info.push({customer_name:$("#customer_name_payment").val(),phone:$("#customer_phone").val(),customer_id:$("#customer_id").val(),address:$("#customer_address").val(),payment_note:$("#payment_note").val(),sales_person_id:$("#sales_person_id").val(),middle_name:$("#customer_middle_payment").val(),last_name:$("#customer_last_payment").val()});
            $("#payment_info button").attr("disabled","disabled");
            $.ajax({
                type: 'POST',
                url: '?r=invoice&f=pay_custom_new',
                dataType: 'json',
                data: {'items': inv.getDataMinimized(),'pay':'lp','store_id':store_id,'more_info':more_info,"after_discount":accounting.unformat($("#discount").val())},
                success: function(msg) {
                    
                    if(msg.inv_id==-1){
                        $('#payment_info').modal('toggle');
                        $("#payment_info button").removeAttr("disabled");
                        swal("Demo Account");
                        return;
                    }
                    
                    inv.reset();
                    //if(auto_print==1){
                        //inv.print_invoice(msg.inv_id);
                    //}
                   // inv.open_cashDrawer();
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
                           inv.print_invoice(msg.inv_id,0);
                           print_inv_as_gift(msg.inv_id);
                           inv.clear_customer_display();
                        });
                    }else if(auto_print==1){
                        inv.print_invoice(msg.inv_id,0);
                        inv.clear_customer_display();
                    }else{
                        inv.clear_customer_display();
                    }
                
                    $('#payment_info').modal('toggle');
                    $("#pay").addClass("disabledPay");
                    inv.clear_customer_display();
                    
                    if(garage_car_plugin=="1"){
                        edit_card_client(msg.card_id);
                    }

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
function PaymentCheque(){
    if(PaymentFunctionLocked==false){
        PaymentFunctionLocked=true;
   
        $("#payment_info button").attr("disabled","disabled");
        var more_info = [];
        more_info.push({customer_name:$("#customer_name_payment").val(),phone:$("#customer_phone").val(),customer_id:$("#customer_id").val(),address:$("#customer_address").val(),payment_note:$("#payment_note").val(),middle_name:$("#customer_middle_payment").val(),last_name:$("#customer_last_payment").val()});
        $.ajax({
            type: 'POST',
            url: '?r=invoice&f=pay_custom_new',
            dataType: 'json',
            data: {'items': inv.getDataMinimized(),'pay':'pc','store_id':store_id,'more_info':more_info,"after_discount":accounting.unformat($("#discount").val())},
            success: function(msg) {
                
                if(msg.inv_id==-1){
                    $('#payment_info').modal('toggle');
                    $("#payment_info button").removeAttr("disabled");
                    swal("Demo Account");
                }
                
                inv.reset();
                //$("#cashboxTotal").html(msg.cashbox_value);
                $('#payment_info').modal('toggle');
                $("#pay").addClass("disabledPay");

                //inv.open_cashDrawer();
                
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
                       inv.print_invoice(msg.inv_id,0);
                       
                       print_inv_as_gift(msg.inv_id);
                            
                       inv.clear_customer_display();
                    });
                }else if(auto_print==1){
                    inv.print_invoice(msg.inv_id,0);
                    inv.clear_customer_display();
                }else{
                    inv.clear_customer_display();
                }
                if(garage_car_plugin=="1"){
                    edit_card_client(msg.card_id);
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

var PaymentFunctionLocked = false;
function PaymentCreditCard(){
    if(PaymentFunctionLocked==false){
        PaymentFunctionLocked=true;
   
        $("#payment_info button").attr("disabled","disabled");
        var more_info = [];
        more_info.push({customer_name:$("#customer_name_payment").val(),phone:$("#customer_phone").val(),customer_id:$("#customer_id").val(),address:$("#customer_address").val(),payment_note:$("#payment_note").val(),sales_person_id:$("#sales_person_id").val(),middle_name:$("#customer_middle_payment").val(),last_name:$("#customer_last_payment").val()});
        $.ajax({
            type: 'POST',
            url: '?r=invoice&f=pay_custom_new',
            dataType: 'json',
            data: {'items': inv.getDataMinimized(),'pay':'cc','store_id':store_id,'more_info':more_info,"after_discount":accounting.unformat($("#discount").val())},
            success: function(msg) {
                
                if(msg.inv_id==-1){
                    $('#payment_info').modal('toggle');
                    $("#payment_info button").removeAttr("disabled");
                    swal("Demo Account");
                }
                
                inv.reset();
                //$("#cashboxTotal").html(msg.cashbox_value);
                $('#payment_info').modal('toggle');
                $("#pay").addClass("disabledPay");

                //inv.open_cashDrawer();
                
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
                       inv.print_invoice(msg.inv_id,0);
                       
                       print_inv_as_gift(msg.inv_id);
                            
                       inv.clear_customer_display();
                    });
                }else if(auto_print==1){
                    inv.print_invoice(msg.inv_id,0);
                    inv.clear_customer_display();
                }else{
                    inv.clear_customer_display();
                }
                
                if(garage_car_plugin=="1"){
                    edit_card_client(msg.card_id);
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

function print_inv_as_gift(id){
    if(ask_print_for_gift==1){
        setTimeout(function(){
            swal({
                title: "One More Copy As Gift?",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, print it!",
                closeOnConfirm: true,
                cancelButtonText: "Do not print",
            },
            function(isConfirm) {
                if (isConfirm) {
                    inv.print_invoice(id,1);
                }
            });
        },1000);
    }
    
}

var QuickPaymentFunctionLocked = false;
function QuickPayment(){
    if(QuickPaymentFunctionLocked==false){
        QuickPaymentFunctionLocked=true;
        $(".sk-circle").center();
        $(".sk-circle-layer").show();
        $("#payment_info button").attr("disabled","disabled");
        var more_info = [];
        more_info.push({customer_name:$("#customer_name_payment").val(),phone:$("#customer_phone").val(),customer_id:$("#customer_id").val(),address:$("#customer_address").val(),payment_note:$("#payment_note").val(),sales_person_id:0,middle_name:$("#customer_middle_payment").val(),last_name:$("#customer_last_payment").val()});
        $.ajax({
            type: 'POST',
            url: '?r=invoice&f=pay_custom_new',
            dataType: 'json',
            data: {'items': inv.getDataMinimized(),'pay':'full','store_id':store_id,'more_info':more_info,"after_discount":inv.getTotalPrice()},
            success: function(msg) {
                
                if(msg.inv_id==-1){
                    $('#payment_info').modal('toggle');
                    $("#payment_info button").removeAttr("disabled");
                    swal("Demo Account");
                    return;
                }
                
                inv.reset();
                //$("#cashboxTotal").html(msg.cashbox_value);
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
                    function(isConfirm) {
                        if (isConfirm) {
                            inv.print_invoice(msg.inv_id,0);
                            print_inv_as_gift(msg.inv_id);

                        }
                        inv.clear_customer_display();  
                    });
                    
                }else if(auto_print==1){
                    inv.print_invoice(msg.inv_id,0);
                    inv.clear_customer_display();
                }else{
                    inv.clear_customer_display();
                }
                
          
                if(garage_car_plugin=="1"){
                    edit_card_client(msg.card_id);
                }
                $(".sk-circle-layer").hide();
            },
        }).fail(function() {
             $("#payment_info button").removeAttr("disabled");
        })
        .always(function() {
            QuickPaymentFunctionLocked=false;
        });
    }
}


var PaymentFunctionLocked = false;
function Payment(){
    if(PaymentFunctionLocked==false){
        PaymentFunctionLocked=true;
        $("#payment_info button").attr("disabled","disabled");
        var more_info = [];
        more_info.push({customer_name:$("#customer_name_payment").val(),phone:$("#customer_phone").val(),customer_id:$("#customer_id").val(),address:$("#customer_address").val(),payment_note:$("#payment_note").val(),sales_person_id:$("#sales_person_id").val(),middle_name:$("#customer_middle_payment").val(),last_name:$("#customer_last_payment").val()});
        $.ajax({
            type: 'POST',
            url: '?r=invoice&f=pay_custom_new',
            dataType: 'json',
            data: {'items': inv.getDataMinimized(),'pay':'full','store_id':store_id,'more_info':more_info,"after_discount":accounting.unformat($("#discount").val())},
            success: function(msg) {
                
                if(msg.inv_id==-1){
                    $('#payment_info').modal('toggle');
                    $("#payment_info button").removeAttr("disabled");
                    swal("Demo Account");
                    return;
                }
                
                inv.reset();
                //$("#cashboxTotal").html(msg.cashbox_value);
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
                    function(isConfirm) {
                        if (isConfirm) {
                            inv.print_invoice(msg.inv_id,0);
                            
                            print_inv_as_gift(msg.inv_id);

                        }
                        inv.clear_customer_display();
                        
                        
                        
                    });
                    
                }else if(auto_print==1){
                    inv.print_invoice(msg.inv_id,0);
                    inv.clear_customer_display();
                }else{
                    inv.clear_customer_display();
                }
                
                if(garage_car_plugin=="1"){
                    edit_card_client(msg.card_id);
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

function scrollToSelectedItem(){ // working here
    
    //alert($('.select_p_item').position().top);
    //if($('.select_p_item').position().top<0){
      //  $('#p_items').scrollTop($('#p_items').scrollTop()-$('.select_p_item').height());
    //}else if($('.select_p_item').position().top>=$('#p_items').height()){
      //  $('#p_items').scrollTop($('#p_items').scrollTop()+$('.select_p_item').height());
    //}
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

function closeModal(id){
    $('#'+id).modal('toggle');
}

function searchBarcode(){
    swal({
        title: ""+LG_MANUAL_BARCODE,
        html: true ,
         text: '<input autofocus type="text" id="m_barcode" input/>',
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: LG_ADD,
        cancelButtonText: LG_CANCEL,
        closeOnConfirm: true,
        closeOnCancel: true
    },
    function(isConfirm) {
        if (isConfirm) {
            if ($("#m_barcode").val() == "" || $("#m_barcode").val() == null) {
                return false;
            }else{
                 inv.getItemByBarcode($("#m_barcode").val());
            }
        }else{
            $(".sweet-alert").remove();
            $(".sweet-overlay").remove();

        }
    });
    
    setTimeout(function(){
        $("#m_barcode").focus();
    },300);
     /*
    setTimeout(function(){

        $('#m_barcode').keyboard({btnClasses: 'btn btn-default btn_key_full',placement: 'top',
        layout: [
            [
                ['1', '1'],
                ['2', '2'],
                ['3', '3'],
                ['4', '4'],
                ['5', '5'],
                ['6', '6'],
                ['7', '7'],
                ['8', '8'],
                ['9', '9'],
                ['0', '0'],
                ['del', 'del']
            ],
            [
                ['q', 'Q'],
                ['w', 'W'],
                ['e', 'E'],
                ['r', 'R'],
                ['t', 'T'],
                ['y', 'Y'],
                ['u', 'U'],
                ['i', 'I'],
                ['o', 'O'],
                ['p', 'P'],
            ],
            [
                ['a', 'A'],
                ['s', 'S'],
                ['d', 'D'],
                ['f', 'F'],
                ['g', 'G'],
                ['h', 'H'],
                ['j', 'J'],
                ['k', 'K'],
                ['l', 'L'],
            ],
            [
                ['z', 'Z'],
                ['x', 'X'],
                ['c', 'C'],
                ['v', 'V'],
                ['b', 'B'],
                ['n', 'N'],
                ['m', 'M'],
            ],
        ]});
        $("#m_barcode").focus();
    },300);
    */
        
}

function searchBarcode_(){
    //if(lockMainPos==false){
        //lockMainPos = true;
        swal({
            title: "Manual Barcode",
            text: '<input autofocus type="text" id="m_barcode" input/>',
            html: true ,
            //type: "input",
            showCancelButton: true,
            closeOnConfirm: false,
            inputPlaceholder: "Barcode"
        }, function () {
            if ($("#m_barcode").val() == "" || $("#m_barcode").val() == null) {
                return false;
            }else{
                 inv.getItemByBarcode($("#m_barcode").val());
                 
            }
        });
        setTimeout(function(){$("#m_barcode").focus();},300);
    //}
    
}

function showAllItems(barcode){
    var title = "";
    if(barcode==0){
        title="All Items";
    }else{
        title="All Items - Same Barcode";
    }
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    lockMainPos = true;
    var content =
    '<div class="modal" data-backdrop="static" id="noBarcodeModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title" '+dir_+'>'+title+'<i style="float:'+float_+';font-size:35px" class="glyphicon glyphicon-remove" onclick="closeModal(\'noBarcodeModal\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body" id="noBarcodeItems">\n\
                    <table id="items_search" class="table table-striped table-bordered" cellspacing="0" style="width:100%">\n\
                        <thead>\n\
                            <tr>\n\
                                <th style="width: 60px !important;">Ref.</th>\n\
                                <th>Name</th>\n\
                                <th style="width: 100px !important;">Barcode</th>\n\
                                <th style="width: 100px !important;">Price/u</th>\n\
                                <th style="width: 50px !important;">Disc.</th>\n\
                                <th style="width: 45px !important;">Vat</th>\n\
                                <th style="width: 45px !important;">Qty</th>\n\
                                <th style="width: 80px !important;">Color</th>\n\
                                <th style="width: 45px !important;">Size</th>\n\
                                <th style="width: 70px !important;">&nbsp;</th>\n\
                            </tr>\n\
                        </thead>\n\
                        <tfoot>\n\
                            <tr>\n\
                                <th>Ref.</th>\n\
                                <th>Name</th>\n\
                                <th>Barcode</th>\n\
                                <th>Price /u</th>\n\
                                <th>Disc.</th>\n\
                                <th>vat</th>\n\
                                <th>Qty</th>\n\
                                <th>Color</th>\n\
                                <th>Size</th>\n\
                                <th>&nbsp;</th>\n\
                            </tr>\n\
                        </tfoot>\n\
                        <tbody></tbody>\n\
                    </table>\n\
                </div>\n\
            </div>\n\
        </div>\n\
    </div>';
    $("#noBarcodeModal").remove();
    $("body").append(content);
    $('#noBarcodeModal').on('show.bs.modal', function (e) {
        
        var items_search = null;
        var search_fields = [0,1,2,3,4,5,6,7,8];
        var index = 0;
        $('#items_search tfoot th').each( function () {
            if(jQuery.inArray(index, search_fields) !== -1){
                var title = $(this).text();
                $(this).html( '<div class="inner-addon left-addon"><input style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder=" '+title+'" /></div>' );
                index++;
            }
        });
                    
        items_search = $('#items_search').DataTable({
            ajax: {
                url: "?r=pos&f=get_all_items_new&p0="+barcode,
                type: 'POST',
                error:function(xhr,status,error) {
                    logged_out_warning();
                },
            },
            orderCellsTop: true,
            aoColumnDefs: [
                { "targets": [0], "searchable": true, "orderable": true, "visible": false },
                { "targets": [1], "searchable": true, "orderable": true, "visible": true },
                { "targets": [2], "searchable": true, "orderable": true, "visible": true },
                { "targets": [3], "searchable": true, "orderable": true, "visible": true },
                { "targets": [4], "searchable": true, "orderable": true, "visible": true },
                { "targets": [5], "searchable": true, "orderable": true, "visible": true },
                { "targets": [6], "searchable": true, "orderable": true, "visible": true },
                { "targets": [7], "searchable": true, "orderable": true, "visible": true },
                { "targets": [8], "searchable": true, "orderable": false, "visible": true },
                { "targets": [9], "searchable": true, "orderable": false, "visible": false },
            ],
            scrollCollapse: true,
            paging: true,
            initComplete: function(settings, json) {
                items_search.cell( ':eq(0)' ).focus();
                $('#items_search tfoot input:eq(1)').focus();
                
                $(".sk-circle-layer").hide();
            },
            fnDrawCallback: updateRows_items_search,
        });
        
        $('#items_search').DataTable().on('dblclick',"tr", function ( e, dt, type, indexes ) {
            var sdata = items_search.row('.selected', 0).data();
            add_to_invoive(parseInt(sdata[0].split("-")[1]));
        });

        $('#items_search').on('key-focus.dt', function(e, datatable, cell){
            $(items_search.row(cell.index().row).node()).addClass('selected');
        });

        $('#items_search').on('key-blur.dt', function(e, datatable, cell){
            $(items_search.row(cell.index().row).node()).removeClass('selected');
        });

        $('#items_search').on('key.dt', function(e, datatable, key, cell, originalEvent){
            if(key === 13){
                var sdata = items_search.row('.selected', 0).data();
                add_to_invoive(parseInt(sdata[0].split("-")[1]));
            }
        });
        
        $('#items_search').DataTable().columns().every( function () {
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
    
    $('#noBarcodeModal').on('shown.bs.modal', function (e) {

    });
    $('#noBarcodeModal').on('hide.bs.modal', function (e) {
        lockMainPos = false;
        $("#noBarcodeModal").remove();
    });
    $('#noBarcodeModal').modal('show');
}

function add_to_invoive(id){
    inv.getItemById(id);
    if(pos_all_items_hide_on_add_to_invoice==1)
        $('#noBarcodeModal').modal('toggle');
}

function updateRows_items_search(){
    var table = $('#items_search').DataTable();
    var p = table.rows({ page: 'current' }).nodes();
    for (var k = 0; k < p.length; k++){
        var index = table.row(p[k]).index();
        table.cell(index, 9).data('<button onclick="add_to_invoive(\''+parseInt(table.cell(index, 0).data().split("-")[1])+'\')" type="button" class="btn btn-xs btn-info" style="width:100%; font-size:13px;"><b>Add To Inv.</b></button>');
    }
    //alert("dsda");
    //$(".sk-circle-layer").hide();
}


var showNoBarcodeItemsFunctionLocked = false;
function showNoBarcodeItems(filter){
    if (cashBox == 0) {
            setCashbox();
    } else {
        if(showNoBarcodeItemsFunctionLocked==false){
            showNoBarcodeItemsFunctionLocked=true;
            lockMainPos = true;
            
            var content =
                '<div class="modal" data-backdrop="static" id="noBarcodeModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
                    <div class="modal-dialog" role="document">\n\
                        <div class="modal-content">\n\
                            <div class="modal-header"> \n\
                                <h3 class="modal-title" '+dir_+'><i style="float:'+float_+';font-size:30px" class="glyphicon glyphicon-remove" onclick="closeModal(\'noBarcodeModal\')"></i><input id="searchIt" onkeyup="searchItemsNonBarcode()" type="text" class="form-control" placeholder="'+JS_LG_SEARCH+'" style="margin-top:5px; width:250px;"></h3>\n\
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
                
                $('#noBarcodeModal').on('show.bs.modal', function (e) {
                    var url = null;
                    if(filter=="only_barcoded"){
                        url = "?r=pos&f=getAllItemsBarcoded&p0="+store_id;
                    }else if(filter=="only_non_barcode"){
                        url = "?r=pos&f=getNonBarcodeItems&p0="+store_id;
                    }
                    $.getJSON(url, function (data) {
                        
                        $.each(data, function (key, val) {
                            $("#noBarcodeItems").append("<div class='col-lg-3 col-md-3 col-sm-3 col-xs-3 funcContainer "+pull_+"' id='ncode_"+val.item_id+"'><div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 func ' id='nc_"+val.item_id+"'><span "+dir_+" class='ncodes' id='ncode_s_"+val.item_id+"'>"+val.item_id+"-"+val.description+"<br/></span><span class='stk_f' style='display:none'>(Stock: "+val.quantity+")</span> <p>"+val.selling_price+"</p></div></div>");
                        });
                    }).done(function () {
                        if(user_role==2){$(".stk_f").show();}
                        setTimeout(function(){
                            $("#searchIt").focus();
                        },300);
                        $(".func").on('click', function (e) {
                            if(cashBox == 0){
                                setCashbox();
                            }else{
                                inv.getItemById($(this).attr('id').split('_')[1]);
                                closeModal('noBarcodeModal');
                            }
                        });
                    }).fail(function() {
                        logged_out_warning();
                    }).always(function () {
                        showNoBarcodeItemsFunctionLocked = false;
                    });
                });
                $('#noBarcodeModal').on('hide.bs.modal', function (e) {
                    lockMainPos = false;
                    $('#noBarcodeModal').remove();
                });
                $('#noBarcodeModal').modal('show');
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

function showCashBox(){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var cb = 0;
    $.getJSON("?r=pos&f=getCashBox", function (data) {
        cb = data.cashBoxTotal;
    }).done(function () {
        $(".sk-circle-layer").hide();
        swal(cb);
    }).fail(function() {
        logged_out_warning();
    }).always(function() {
        
    });
}

function ManualQty(){
    if(inv.getTotalItems()>0 && lockMainPos==false){
        lockMainPos = true;
        swal({
            title: LG_TOTAL_QTY,
            html: true ,
            text: '<input class="keyboard form-control" value="" type="text" id="m_qty"/>',
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: LG_SETTOTALQTY,
            cancelButtonText: LG_CANCEL,
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function(isConfirm) {
            if (isConfirm) {
                if ($("#m_qty").val() == "" || $("#m_qty").val() == null) {
                    return false;
                }else{
                    if($(".select_p_item").length>0){
                        var item_id = parseInt($(".select_p_item").attr("id").split('_')[1]);
                        for(var j=parseInt($("#qty_"+item_id).html());j>=1;j--){
                            keyMinus(1);
                        }
                    }

                    for(var i=0;i<parseInt($("#m_qty").val())-1;i++){
                        keyPlus(1);
                    }
                    inv.submit_to_customer_display(item_id,parseInt($("#m_qty").val()),5000);
                }
            }
            $(".sweet-alert").remove();
            $(".sweet-overlay").remove();
            lockMainPos = false;
        });
        setTimeout(function(){
            $("#m_qty").numeric();
            //$('#m_qty').keyboard({btnClasses: 'btn btn-default btn_key',type:'tel',placement: 'top',
            //layout:[
		//[['7'],['8'],['9']],
                //[['4'],['5'],['6']],
                //[['1'],['2'],['3']],
                //[['del'],['0']],
            //]});
            $("#m_qty").focus();
        },300);
    }
}

function ShowManualDiscount(type){
    setTimeout(function(){
        var item_id = null;
        item_id = parseInt($(".select_p_item").attr("id").split('_')[1]);

        var btn_de = "Manual Discount (Unit Price)";
        if(type=="p"){
            btn_de = "Discount by percentage";
        }

        var btn_confirm = "Add final price per unit";
        if(type=="p"){
            btn_confirm = "Add percentage";
        }

        swal({
            title: ""+btn_de,
            html: true ,
            text: '<input class="keyboard form-control" value="" type="text" id="m_disc"/>',
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: ""+btn_confirm,
            cancelButtonText: LG_CANCEL,
            closeOnConfirm: true,
            closeOnCancel: true
        },
        function(isConfirm) {
            if (isConfirm) {
                if ($("#m_disc").val() == "" || $("#m_disc").val() == null) {
                    return false;
                }else{
                    if($(".select_p_item").length>0){
                        if(type=="v"){
                            inv.change_discount(item_id,$("#m_disc").val());
                        }else{
                            inv.change_discount_percentage(item_id,parseFloat($("#m_disc").val()));
                        }

                    }
                }
            }
        });
        setTimeout(function(){

            $("#m_disc").numeric();
            var cu_items = inv.getData();
            for(var i=0;i<cu_items.length;i++){
                if(cu_items[i].id == item_id){
                    if(type=="v"){
                        if(enable_wholasale==1){
                            $("#m_disc").attr("placeholder",format_price(cu_items[i].final_price)+" - "+ format_price(parseFloat(cu_items[i].wholesale_price))); 
                        }else{
                           $("#m_disc").attr("placeholder",format_price(cu_items[i].final_price)); 
                        }
                    }else{
                        $("#m_disc").attr("placeholder","Discount by percentage"); 
                    }

                    break;
                }
            }

            /*$('#m_disc').keyboard({btnClasses: 'btn btn-default btn_key',type:'tel',placement: 'top',
            layout:[
                [['7'],['8'],['9']],
                [['4'],['5'],['6']],
                [['1'],['2'],['3']],
                [['del'],['0']],
            ]});*/
            $("#m_disc").focus();
        },200);
    },200);
}

function ManualDiscount(type){
    if(inv.getTotalItems()>0){
        if(enable_discount_password==1){
            swal({
                title: "Enter Password",
                html: true ,
                text: '<input class="form-control" value="" type="text" id="pass"/>',
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Ok",
                cancelButtonText: "Cancel",
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function(isConfirm) {
                if (isConfirm) {
                    if($("#pass").val() == discount_password){
                        ShowManualDiscount(type);
                    }else{
                        alert("Wrong Password");
                    } 
                }
            });
            setTimeout(function(){ $("#pass").focus(); },200);
        }else{
            ShowManualDiscount(type);
        }
    }
}

function keyboardEvents(){
    $(window).keydown(function(event) {
        if(lockMainPos == false){
            //alert(event.which);
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
                    keyPlus(0);
                    break;
                case 109: // minus
                    keyMinus(0);
                    break;
                case 81: // q
                    //ManualQty();
                    break; 
                case 82: // r
                    //return_items();
                    break; 
                case 88: // minus
                    inv.open_cashDrawer();
                    break;  
                case 65:
                    //showBarcodedItems();
                    break;  
                case 80: // p
                    //ManualDiscount("p");
                    break;    
                case 86: // V
                    //ManualDiscount("v");
                    break;
                case 69: // d
                    //deleteItem();
                    break;
                case 83: //s
                    //searchBarcode();
                    break;       
                default: 
                    return;
            }
        }
    });
}

function keyPlus(manual){
    if($(".select_p_item").length>0){
        var item_id = parseInt($(".select_p_item").attr("id").split('_')[1]);
        inv.incrementItemQty(item_id,manual);
    }
}

function keyMinus(manual){
    if($(".select_p_item").length>0){
        var item_id = parseInt($(".select_p_item").attr("id").split('_')[1]);
        inv.decrementItemQty(item_id,manual);
    }
}

function logged_out_warning(){
    if (sessionStorage.getItem('status') != null){
        swal({
            title: "Logged out!!",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, Login again!!!",
            closeOnConfirm: true,
            cancelButtonText: "Do Not Login",
        },
        function(isConfirm) {
            if (isConfirm) {
                window.location.href = "index.php";
            }
        });
    }
}

function show_all_customers(){
    
    if(enable_discount_password==1){
        swal({
            title: "Enter Password",
            html: true ,
            text: '<input class="form-control" value="" type="text" id="pass"/>',
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Ok",
            cancelButtonText: "Cancel",
            closeOnConfirm: true,
            closeOnCancel: true
        },
        function(isConfirm) {
            if (isConfirm) {
                if($("#pass").val() == discount_password){
                    _show_all_customers();
                }else{
                    alert("Wrong Password");
                } 
            }
        });
        setTimeout(function(){ $("#pass").focus(); },200);
    }else{
        _show_all_customers(0);
    }
}

function login_again(){
    var content =
    '<div class="modal" data-backdrop="static" id="login_againModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title" '+dir_+'>Invoices<i style="float:'+float_+';font-size:35px" class="glyphicon glyphicon-remove" onclick="closeModal(\'login_againModal\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body" id="noBarcodeItems">\n\
                    <div class="row">\n\
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">\n\
                            <input id="sold_items" class="form-control date_s" type="text" />\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>\n\
    </div>';
    $("#login_againModal").remove();
    $("body").append(content);
    $('#login_againModal').on('show.bs.modal', function (e) {

    });
    
    $('#login_againModal').on('shown.bs.modal', function (e) {
        
    });
    $('#login_againModal').on('hide.bs.modal', function (e) {
        $("#login_againModal").remove();
    });
    $('#login_againModal').modal('show');
}


String.prototype.ucwords = function() {
    str = this.toLowerCase();
    return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,
        function($1){
            return $1.toUpperCase();
        });
}

  function print_sheet(invoice_id){
                w=window.open('?r=invoice&f=print_invoice&p0='+invoice_id); 
            }
            
function precisionRound(number, precision) {
  var factor = Math.pow(10, precision);
  return Math.round(number * factor) / factor;
}


function format_input_number(nb,input_id,decimal_digit_nb,round){
    nb = parseFloat(nb).toFixed(decimal_digit_nb);
    var dcm = "";
    for(var i=0;i<decimal_digit_nb;i++){
        dcm+="0";
    }
    $(input_id).mask("#,##0."+dcm, {reverse: true});
    $(input_id).val(nb);
    $(input_id).trigger('input');
}
function update_rate(){
    for(var i=0;i<all_currencies.length;i++){
        if(all_currencies[i].id==$("#payment_currency").val()){
            $("#currency_rate").val(parseFloat(all_currencies[i].rate_to_system_default).toFixed(2));
            $("#currency_rate").trigger("input");
        }
    }
}

function edit_customer_payment(id){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var _data = [];
    $.getJSON("?r=customers&f=getPaymentDetails&p0="+id, function (data) {
        _data = data;
    }).done(function () {
        addCustomerPaymentDetails($("#customers_list").val(),id,_data,"pos");
    });
}

function payment_method_supplier_changed(){
    if($("#payment_method").val()==2){
        $(".credit_card_input").hide();
        $(".bank_input").show();
    }else if($("#payment_method").val()==3){
        $(".bank_input").hide();
        $(".credit_card_input").show();
    }else{
        $(".bank_input").hide();
        $(".credit_card_input").hide();
    }
}