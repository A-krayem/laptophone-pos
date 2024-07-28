var MAX_ROW_INDEX = 0;
var CURRENT_SELECTED_ROW_INDEX = 0;
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
    
    this.setCookies = function() {
        this.deleteCookies();
        $.cookie("cookies_items", JSON.stringify(items),{ expires: 99999999 });
        //var tmp = this.getCookies();
        //alert(tmp.length);
    };
    
    this.reloadCookies = function() {
        var tmp = this.getCookies();
         
        for(var i=0;i<tmp.length;i++){
            this.addItem(tmp[i]);
        }
    };
    
    this.getCookies = function() {
        return JSON.parse($.cookie("cookies_items"));
    };
    
    this.deleteCookies = function() {
        $.removeCookie("cookies_items",{});
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

        $(document).on('click', '.notifyjs-foo-base .no', function() {
          $(this).trigger('notify-hide');
        });
        $(document).on('click', '.notifyjs-foo-base .yes', function() {
            customer_info = [];
            self.delete_customer();
            $(".history_prices").hide();
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
        var customer_type = 1;
        $.getJSON("?r=pos&f=get_customer_by_id&p0="+id_, function (data) {
            customer_type = data[0].customer_type;
            customer_info = data;
            self.showCustomerNot(customer_info);
            $(".history_prices").show();
        }).done(function () {
            if(customer_type==2){
                self.apply_wholesale();
            }
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
        total = precisionRound(total,round_val);
        
        
        return total;
        //return accounting.toFixed(total, 1);
    };
    
    this.getTotalItems = function(){
        return items.length;
    };
    
    this.reorder_rw_nb = function(){
        $( ".rw_nb" ).each(function( index ) {
           $(this).html(index+1);
           MAX_ROW_INDEX = index+1;
        });
    };
    
    this.print_invoice = function(id,gift){
        if(a4_printer==0){
            if(pos_manual_print==1){
                window.open("?r=printing&f=print_invoice&p0="+id,'_blank');
            }else if(enable_qz_print==1){
                //print_invoice_id_qz(id);
            }else{
                $.getJSON("?r=print_invoice&f=print_invoice_id&p0="+id+"&p1="+gift, function (data) {
                }).done(function () {
                }).fail(function() {
                });
            } 
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
        CURRENT_SELECTED_ROW_INDEX = 0;
        customer_info = [];
        //$("#client_name").
                
        $(".notifyjs-foo-base").trigger('notify-hide');

        $("#p_items").empty();
        $("#totalPrice").html(format_price_pos(precisionRound(self.getTotalPrice(),round_val)));
        update_second_currency();
    };
    
    this.getData = function(){
        return items;
    };
    
    this.getDataMinimized = function(){
        var items_to_submit = [];
        for(var i=0;i<items.length;i++){
            items_to_submit.push({id:items[i].id,description:items[i].description,qty:items[i].qty,ds:items[i].discount,m_d:items[i].manual_discounted,custom_item:items[i].custom_item,mobile_transfer_item:items[i].mobile_transfer_item,mobile_transfer_device_id:items[i].mobile_transfer_device_id,price:items[i].price,cost:items[i].cost});
        }
        return items_to_submit;
    };
    
    this.set_qty = function(item_id,qty){
        var index = null;
        for(var i=0;i<items.length;i++){
            if(items[i].id == item_id){
                items[i].qty=qty;
                items[i].final_price = items[i].price*(1-(items[i].discount/100));
                index = i;               
                break;
            }
        }
        
        if(index!=null){
            $("#qty_"+items[index].id).val(items[index].qty);
            if(items[index].vat>0){
                $("#final_"+items[index].id).html(format_price_pos(precisionRound(items[index].qty*items[index].final_price*items[index].vat,round_val)));
            }else{ 
                $("#final_"+items[index].id).html(format_price_pos(precisionRound(items[index].qty*items[index].final_price,round_val)));
                
            }
            $("#totalPrice").html(format_price_pos(precisionRound(self.getTotalPrice(),round_val)));
            update_second_currency();
        }
        this.submit_to_customer_display(items[index].id,items[index].qty);
        this.setCookies();
    }
    
    this.incrementItemQty = function(item_id,manual){ 
        var index = null;
        for(var i=0;i<items.length;i++){
            if(items[i].id == item_id && items[i].plu==0){
                items[i].qty++;
                items[i].final_price = items[i].price*(1-(items[i].discount/100));
                index = i;
                break;
            }
        }

        if(index!=null){
            $("#qty_"+items[index].id).val(items[index].qty);
            
            if(items[index].vat>0){
                $("#final_"+items[index].id).html(format_price_pos(precisionRound(items[index].qty*items[index].final_price*items[index].vat,round_val)));
            }else{
                $("#final_"+items[index].id).html(format_price_pos(precisionRound(items[index].qty*items[index].final_price,round_val)));
            }
            
            $("#totalPrice").html(format_price_pos(precisionRound(self.getTotalPrice(),round_val)));
            update_second_currency();
            if(manual==0){
                this.submit_to_customer_display(items[index].id,items[index].qty);
            }
            
            //$.playSound('libraries/sounds/success.mp3');
        }
        
        this.setCookies();
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
            $("#qty_"+items[index].id).val(items[index].qty);
            
            if(items[index].vat>0){
                $("#final_"+items[index].id).html(format_price_pos(precisionRound(items[index].qty*items[index].final_price*items[index].vat,round_val)));
            }else{
                $("#final_"+items[index].id).html(format_price_pos(precisionRound(items[index].qty*items[index].final_price,round_val)));
            }
            
            
            $("#totalPrice").html(format_price_pos(precisionRound(self.getTotalPrice(),round_val)));
            update_second_currency();
            if(manual==0){
                this.submit_to_customer_display(items[index].id,items[index].qty);
            }
        }
        
        this.setCookies();
    };
    
    
    this.sleepp = function(milliseconds){
        var start = new Date().getTime();
        for (var i = 0; i < 1e7; i++) {
          if ((new Date().getTime() - start) > milliseconds){
            break;
          }
        }
    };
    
    this.deleteItem = function(item_id){
        monitor_pos_items(item_id);
        if(items.length>0){
            var items_tmp = [];
            for(var i=0;i<items.length;i++){

                if(items[i].mobile_transfer_item>0){
                    if(items[i].micro_id != item_id){
                        items_tmp.push(items[i]);
                    }
                }else{
                    if(items[i].id != item_id){
                        items_tmp.push(items[i]);
                    }
                }
                
            }
            items = items_tmp;
            
            $("#it_"+item_id).remove();
            $("#totalPrice").html(format_price_pos(precisionRound(self.getTotalPrice(),round_val)));
            update_second_currency();
            if(sound_play==1){
                $.playSound('libraries/sounds/success.mp3');
            } 
            
            this.delete_to_customer_display(item_id);
        }
        
        if(items.length==0){
            $("#pay").addClass("disabledPay");
        }
        update_second_currency();
        
        this.setCookies();
    };
    
    this.clear_customer_display = function(timeout){
        if(enable_customer_display==1){
            setTimeout(function(){
                $.getJSON("?r=print_invoice&f=clear_customer_display", function (data) {

                }).done(function () {  
                }).fail(function() {

                }).always(function() {
                });   
            },timeout);
            
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
                    price_tmp = precisionRound(items[i].final_price,round_val);//items[i].final_price;
                    break;
               }
            }
            //price_tmp = format_price_pos(precisionRound(price_tmp,round_val));
            //alert(price_tmp);
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
                $("#price_"+item_id).html(dv);
                
                if(items[i].vat>0){
                    $("#final_"+items[i].id).html(format_price_pos(precisionRound(items[i].qty*items[i].final_price*items[i].vat,round_val)));
                }else{
                    $("#final_"+items[i].id).html(format_price_pos(precisionRound(items[i].qty*items[i].final_price,round_val)));
                }

                this.submit_to_customer_display(item_id,items[i].qty);
                break;
            }
        }
        $("#totalPrice").html(format_price_pos(precisionRound(self.getTotalPrice(),round_val)));
        update_second_currency();
        this.setCookies();
    },
            
    this.apply_wholesale = function(){
        for(var i=0;i<items.length;i++){
            self.change_discount(items[i].id,parseFloat(items[i].wholesale_price,1));
        }
    } 
    
    this.remove_wholesale = function(){
        for(var i=0;i<items.length;i++){ 
            self.change_discount(items[i].id,parseFloat(items[i].price,1));
        }
    } 
    
    this.change_discount = function(item_id,new_value,c_display){
        for(var i=0;i<items.length;i++){
            if(items[i].id == item_id){
                if(items[i].price==0){
                    items[i].price = 1;
                }
                
                var dv = 100-(new_value*100)/items[i].price;
                
                if(dv<0){
                    dv=0;
                    items[i].price = new_value;
                }
                
                items[i].discount = dv;
                items[i].final_price = new_value;
                
                items[i].manual_discounted = 1;
                
                $("#discount_"+item_id).html(parseFloat(dv).toFixed(2)+"%");
                
                cleaves_id("price_"+item_id,number_of_decimal_points);
                
                if(items[i].vat>0){
                    $("#final_"+items[i].id).html(format_price_pos(precisionRound(items[i].qty*items[i].final_price*items[i].vat,round_val)));
                }else{
    
                    $("#final_"+items[i].id).html(format_price_pos(precisionRound(items[i].qty*items[i].final_price,round_val)));
                }
                
                if(c_display==0){
                    //this.submit_to_customer_display(item_id,items[i].qty);
                }else{
                    if(i==items.length-1){
                       //this.submit_to_customer_display(item_id,items[i].qty); 
                    }
                }
                
                break;
            }
        }
        this.submit_to_customer_display(item_id,items[i].qty);
        $("#totalPrice").html(format_price_pos(precisionRound(self.getTotalPrice(),round_val)));
        update_second_currency();
        this.setCookies();
    }
    
    this.addItem  = function (info) {
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
            CURRENT_SELECTED_ROW_INDEX = info["id"];
            
            items.push({id: info["id"],vat: info["vat"],price: info["price"],qty: info["qty"],barcode: info["barcode"],description: info["description"],discount:info["discount"],final_price:info["final_price"],custom_item:info["custom_item"],mobile_transfer_item:info["mobile_transfer_item"],mobile_transfer_device_id:info["mobile_transfer_device_id"],plu:info["plu"],measure_label:info["measure_label"],manual_discounted:info["manual_discounted"],wholesale_price:info["wholesale_price"],stock_qty:info["stock_qty"],composit_qty:info["composit_qty"],cost:info["cost"],micro_id:info["micro_id"]});
            
           
            var price_after_vat_and_dicount = precisionRound(info["qty"]*info["final_price"],round_val);
           
            if(info["vat"]>0){
                price_after_vat_and_dicount=precisionRound(price_after_vat_and_dicount+(price_after_vat_and_dicount*(info["vat"]-1)),round_val);
            }
            
            var qty_readonly="";
            var tmp_id=info["id"];
            if(info["mobile_transfer_item"]>0){
                
                setTimeout(function(){$(".sk-circle-layer").hide()},1000);
                qty_readonly="readonly";
                tmp_id = info["micro_id"];
            }
            
            
           
            
            $("#p_items").append("<div onclick='selectItemByClick("+tmp_id+")' class='row purchases select_p_item' id='it_"+tmp_id+"' style="+direction_+">\n\
                <div class='col-lg-2 col-md-2 col-sm-2 col-xs-1 "+pull_+"'>\n\
                    <div style='padding-left:1px;padding-right:1px;' class='col-lg-4 col-md-4 col-sm-4 col-xs-4 "+pull_+" rw_nb' id='rowindexnb_"+info["id"]+"'>1</div>\n\
                    <div style='padding-left:1px;padding-right:1px;font-size:14px;' class='col-lg-8 col-md-8 col-sm-8 col-xs-8 "+pull_+"'>"+info["barcode"]+"</div>\n\
                </div>\n\
                <div style='padding-left:1px;padding-right:1px;' class='col-lg-4 col-md-4 col-sm-4 col-xs-4 "+pull_+"'>"+info["description"]+" (Stock: "+info["stock_qty"]+")</div>\n\
                <div style='padding-left:1px;padding-right:1px;' class='col-lg-3 col-md-3 col-sm-3 col-xs-3 "+pull_+"'>\n\
                    <div style='padding-left:1px;padding-right:1px;' class='col-lg-4 col-md-4 col-sm-4 col-xs-4 "+pull_+"'><input "+qty_readonly+" oninput='qty_changed(this,"+info["id"]+")' class='qty_input tabdetect' type='number' id='qty_"+info["id"]+"' name='' value='"+info["qty"]+"' /><span class='u_box' id='compoqty_"+info["id"]+"' style='display:none'>"+info["composit_qty"]+"</span></div>\n\
                    <div style='padding-left:1px;padding-right:1px;' class='col-lg-8 col-md-8 col-sm-8 col-xs-8 "+pull_+"' ><input onchange='price_changed(this,"+info["id"]+")' class='price_input tabdetect' type='text' id='price_"+info["id"]+"' name='' value='"+info["price"]+"' ondblclick='ManualDiscount(\"v\")' /></div>\n\
                </div>\n\
                <div style='padding-left:1px;padding-right:1px;' class='col-lg-3 col-md-3 col-sm-3 col-xs-3 "+pull_+"'>\n\
                    <div style='padding-left:1px;padding-right:1px;' class='col-lg-3 col-md-3 col-sm-3 col-xs-3 "+pull_+"' id='discount_"+info["id"]+"' ondblclick='ManualDiscount(\"p\")'>"+parseFloat(info["discount"]).toFixed(2)+"%</div>\n\
                    <div style='padding-left:1px;padding-right:1px;' class='col-lg-2 col-md-2 col-sm-2 col-xs-2 "+pull_+"' id='vat_"+info["id"]+"'>"+info["vat"]+"</div>\n\
                    <div style='padding-left:1px;padding-right:1px;' class='col-lg-4 col-md-4 col-sm-4 col-xs-4 "+pull_+" pos_final_price' id='final_"+info["id"]+"'>"+format_price_pos(price_after_vat_and_dicount)+"</div>\n\
                    <div style='padding-left:1px;padding-right:1px;' class='col-lg-3 col-md-3 col-sm-3 col-xs-3 "+pull_+"' id='close_"+info["id"]+"' style='text-align:center'><i title='Latest price' class='icon-history history_prices' onclick='latest_prices_for_customer("+info["id"]+",\"pos\",0)'></i>&nbsp;&nbsp;<i title='Delete' class='glyphicon glyphicon-trash span_delete' onclick='deleteItem("+tmp_id+")'></i></div>\n\
                </div>\n\
                </div>");
            
            cleaves_id("price_"+info["id"],number_of_decimal_points);
            
            
            
            
            $("#price_"+info["id"]).keypress(function(event){
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if(keycode == '13'){
                     $("#price_"+info["id"]).blur();
                }
            });
            
            $("#qty_"+info["id"]).keypress(function(event){
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if(keycode == '13'){
                    $("#qty_"+info["id"]).blur();
                }
            });
            
            //$("#qty_"+info["id"]).focus();
            //$("#qty_"+info["id"]).select();
            
            $("#price_"+info["id"]).dblclick(function(){
                //ManualDiscount('v');
            });
            
            
            $("#price_"+info["id"]).keydown( function(e) {
                if (e.keyCode == 9 && !e.shiftKey) {
                  if( parseInt($("#rowindexnb_"+$(this).attr('id').split('_')[1]).html())==MAX_ROW_INDEX ){
                     e.preventDefault();
                  }
                }
            });
            
            if(enable_discount_password==1){
                $("#price_"+info["id"]).prop('readonly', true);
            }
            
            this.submit_to_customer_display(info["id"],info["qty"]);
        }else{
            selected_id = items[index].id;
            CURRENT_SELECTED_ROW_INDEX =items[index].id;;
            $("#it_"+items[index].id).remove();
            if(items[index].measure_label==null)
                items[index].measure_label = "";
   
            var price_after_vat_and_dicount = precisionRound(items[index].qty*items[index].final_price,round_val);
            
            if(items[index].vat>0){
                price_after_vat_and_dicount=precisionRound(price_after_vat_and_dicount+(price_after_vat_and_dicount*(items[index].vat-1)),round_val);
            }
            


            $("#p_items").append("<div onclick='selectItemByClick("+items[index].id+")' class='row purchases select_p_item' id='it_"+items[index].id+"' style="+direction_+">\n\
                <div class='col-lg-2 col-md-2 col-sm-2 col-xs-1 "+pull_+"'>\n\
                    <div style='padding-left:1px;padding-right:1px;' class='col-lg-4 col-md-4 col-sm-4 col-xs-4 "+pull_+" rw_nb' id='rowindexnb_"+items[index].id+"'>1</div>\n\
                    <div style='padding-left:1px;padding-right:1px;font-size:14px;' class='col-lg-8 col-md-8 col-sm-8 col-xs-8 "+pull_+"'>"+info["barcode"]+"</div>\n\
                </div>\n\
                <div style='padding-left:1px;padding-right:1px;' class='col-lg-4 col-md-4 col-sm-4 col-xs-4 "+pull_+"'>"+items[index].description+" (Stock: "+items[index].stock_qty+")</div>\n\
                <div style='padding-left:1px;padding-right:1px;' class='col-lg-3 col-md-3 col-sm-3 col-xs-3 "+pull_+"'>\n\
                    <div style='padding-left:1px;padding-right:1px;' class='col-lg-4 col-md-4 col-sm-4 col-xs-4 "+pull_+"'><input oninput='qty_changed(this,"+items[index].id+")' class='qty_input tabdetect' type='number' value='"+items[index].qty+"' id='qty_"+items[index].id+"' name='' /><span class='u_box' id='compoqty_"+items[index].id+"' style='display:none' >"+items[index].composit_qty+"</span></div>\n\
                    <div style='padding-left:1px;padding-right:1px;' class='col-lg-8 col-md-8 col-sm-8 col-xs-8 "+pull_+"'><input onchange='price_changed(this,"+info["id"]+")' class='price_input tabdetect' type='text' id='price_"+items[index].id+"' name='' value='"+items[index].price+"' ondblclick='ManualDiscount(\"v\")' /></div>\n\
                </div>\n\
                <div  style='padding-left:1px;padding-right:1px;' class='col-lg-3 col-md-3 col-sm-3 col-xs-3 "+pull_+"'>\n\
                    <div style='padding-left:1px;padding-right:1px;' class='col-lg-3 col-md-3 col-sm-3 col-xs-3 "+pull_+"' id='discount_"+items[index].id+"' ondblclick='ManualDiscount(\"p\")'>"+parseFloat(items[index].discount).toFixed(2)+"%</div>\n\
                    <div style='padding-left:1px;padding-right:1px;' class='col-lg-2 col-md-2 col-sm-2 col-xs-2 "+pull_+"' id='vat_"+items[index].vat+"'>"+items[index].vat+"</div>\n\
                    <div style='padding-left:1px;padding-right:1px;' class='col-lg-4 col-md-4 col-sm-4 col-xs-4 pos_final_price "+pull_+"' id='final_"+items[index].id+"'>"+format_price_pos(price_after_vat_and_dicount)+"</div>\n\
                    <div style='padding-left:1px;padding-right:1px;' class='col-lg-3 col-md-3 col-sm-3 col-xs-3 "+pull_+"' id='close_"+items[index].id+"' style='text-align:center'><i title='Latest price' class='icon-history history_prices' onclick='latest_prices_for_customer("+items[index].id+",\"pos\",0)'></i>&nbsp;&nbsp;<i title='Delete' class='glyphicon glyphicon-trash span_delete' style='' onclick='deleteItem("+tmp_id+")'></i></div>\n\
                </div>\n\
                </div>"); 
            //$("#price_"+items[index].id).mask(mask_value_format(), { reverse: true });
            cleaves_id("price_"+items[index].id,number_of_decimal_points);
            
            $("#price_"+items[index].id).keypress(function(event){
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if(keycode == '13'){
                     $("#price_"+$("#price_"+items[index].id)).blur();
                }
            });
            
            $("#qty_"+items[index].id).keypress(function(event){
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if(keycode == '13'){
                     $("#qty_"+items[index].id).blur();
                }
            });
            
            $("#price_"+items[index].id).dblclick(function(){
                //ManualDiscount('v');
            });
            
            $("#price_"+items[index].id).keydown( function(e) {
                if (e.keyCode == 9 && !e.shiftKey) {
                    if( parseInt($("#rowindexnb_"+$(this).attr('id').split('_')[1]).html())==MAX_ROW_INDEX ){
                        e.preventDefault();
                    } 
                }
            });            
            
            if(enable_discount_password==1){
                $("#price_"+items[index].id).prop('readonly', true);
            }
            this.submit_to_customer_display(items[index].id,items[index].qty);
        }


        $("#totalPrice").html(format_price_pos(precisionRound(self.getTotalPrice(),round_val)));
        update_second_currency();
        
        
        $("#pay").removeClass("disabledPay");
        
        $('#p_items').scrollTop($('#p_items').prop("scrollHeight"));
        
        var customer_i = inv.getcustomer_info();      
        if(customer_i.length>0){
            $(".history_prices").show();
        }else{
            $(".history_prices").hide();
        }
        
        
         this.setCookies();
    };
    
    
    
    this.updatePurchasedList = function(){
        showPurchasedItem(null,null);
    };
    
    this.addMobileTransferItem = function(info_){
        var info = [];
        info["id"] =  Math.floor(Date.now() / 1000);
        info["description"] = info_["description"];
        info["vat"] = 0;
        
        info["cost"] = 0;
        info["micro_id"] = Date.now();
        
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
    
    this.addInternationnalCall = function(info_){
        var info = [];
        info["id"] =  Math.floor(Date.now() / 1000);
        info["description"] = info_["description"];
        info["vat"] = 0;
        info["qty"] = 1;
        info["barcode"] = "";
        info["discount"] = 0;
        
        info["micro_id"] = Date.now();
        
        info["cost"] = info_["cost"];
        
        info["price"] = parseFloat(info_["price"]) ;
        info["final_price"] = parseFloat(info_["price"]) ;
        info["custom_item"] = 1;
        info["mobile_transfer_item"] = 'NULL';
        info["mobile_transfer_device_id"] = 0;
        info["wholesale_price"] = 0;
        info["stock_qty"] = 0;
        info["manual_discounted"] = 0;
        
        info["composit_qty"]="";
        
        $("#international_call_name").val("");
        $("#international_call_value").val(0);
                    
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
        
        info["micro_id"] = Date.now();
        
        info["cost"] = 0;
        
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
    
    this.getItemByBarcodeLink = function (second_barcode) {
        var info = [];
        var data_length = 0;
        var all_data = [];
        var bc = "";
        $.getJSON("?r=pos&f=get_item&p0=" + second_barcode.replace("L", ""), function (data) {
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
                info["micro_id"] = Date.now();


                info["qty"] = val.qty;

                info["cost"] = 0;


                info["composit_qty"]="";
                if(val.is_composite ==1 && val.composite_items.length>0){
                    info["composit_qty"] = "("+val.composite_items[0].qty+"/box)";
                }

                info["measure_label"] = val.measure_label;
                info["barcode"] = val.barcode;
                bc =  val.barcode;
                info["discount"] = val.discount;
                info["price"] = parseFloat(val.selling_price);
                
                info["final_price"] = info["price"]*(1-(info["discount"]/100));
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
        })
        .always(function() {
        });
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
                    info["second_barcode"] = val.second_barcode;
                    
                    info["micro_id"] = Date.now();
                    
                    
                    info["vat"] = 0;
                    if(val.vat==1){
                        info["vat"] = vat_value;
                    }
                    
                    info["manual_discounted"] = 0;
                    
                    
                    info["qty"] = val.qty;
                    
                    info["cost"] = 0;
                    
                    
                    info["composit_qty"]="";
                    if(val.is_composite ==1 && val.composite_items.length>0){
                        info["composit_qty"] = "("+val.composite_items[0].qty+"/box)";
                    }
                    
                    info["measure_label"] = val.measure_label;
                    info["barcode"] = val.barcode;
                    bc =  val.barcode;
                    info["discount"] = val.discount;
                    
                    info["price"] = parseFloat(val.selling_price);
                    if(val.enable_price_var=="1"){
                        info["price"]=(info["price"]/val.base_price_rate_to_usd)*val.new_price_rate_to_lbp;
                        info["price"] = Math.round(info["price"]/1000)*1000;
                    }

                    info["final_price"] = info["price"]*(1-(info["discount"]/100));
                    

                    
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
                        
                        if(customer_info.length>0 && customer_info[0].customer_type==2){
                            self.apply_wholesale();
                        }
                    
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
                if(barcode_link_enable=="1"){
                    self.getItemByBarcodeLink(info["second_barcode"]);
                }
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
                    info["second_barcode"] = val.second_barcode;
                    
                    
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
                    
                    info["micro_id"] = Date.now();
                    
                    info["cost"] = 0;
                    
                    info["barcode"] = val.barcode;
                    info["discount"] = val.discount;
                    info["price"] = parseFloat(val.selling_price);
                    
                 
                    if(val.enable_price_var=="1"){
                        info["price"]=(info["price"]/val.base_price_rate_to_usd)*val.new_price_rate_to_lbp;
                        info["price"] = Math.round(info["price"]/1000)*1000;
            
                    }
              
                    info["final_price"] = info["price"]*(1-(info["discount"]/100));
                  
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
                    if(customer_info.length>0 && customer_info[0].customer_type==2){
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
                if(barcode_link_enable=="1"){
                    self.getItemByBarcodeLink(info["second_barcode"]);
                }
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
    if($("#discount").val()=="" || $("#discount").val()==null ){
        //$("#discount").val(inv.getTotalPrice());
    }
    
    var  disc_v = $("#discount").val().replace(/,\s?/g, "")*100/$("#total_price").val().replace(/,\s?/g, "");
    
    $("#invdiscper").val(100-disc_v);
    cleaves_id("invdiscper",1);
    /*
    var after_dis = 0;
    var discount_val = $("#invdiscper").val().replace(/,\s?/g, "");
    after_dis = $("#total_price").val().replace(/,\s?/g, "")*(1-discount_val/100);
    $("#discount").val(after_dis);
    cleaves_id("discount",number_of_decimal_points);
    */
    
    
    cash_from_client_changed();
}

function update_select_sales_person(id){
    $(".sales_person_box_selected").removeClass("sales_person_box_selected");
    $("#sps_"+id).addClass("sales_person_box_selected");
    $("#sales_person_id").selectpicker("val",id);
    $('#salesperson_modal').modal('hide'); 
}

function show_sales_persons(){
    var salesperson_boxes = "";
    for(var i=0;i<salesperson.length;i++){
        salesperson_boxes+="\
        <div class='col-lg-4 col-md-4 col-sm-4 col-xs-4 sales_person_box_container'>\n\
            <div class='row'><div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 sales_person_box' onclick='update_select_sales_person("+salesperson[i].id+")' id='sps_"+salesperson[i].id+"'>"+salesperson[i].first_name+" "+salesperson[i].last_name+"</div></div>\n\
        </div>";
    }
    
    var modal_name = "salesperson_modal";
    var modal_title = "Sales Person";
   
    var content =
    '<div class="modal" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true" style="z-index:999999999">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\''+modal_name+'\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="row">\n\
                        '+salesperson_boxes+'\n\
                    </div>\n\
                </div>\n\
                <div class="modal-footer">\n\
                    <button style="width:150px;" type="button" class="btn btn-info" data-dismiss="modal">Ok</button>\n\
                </div>\n\
            </div>\n\
        </div>\n\
    </div>';
    $("#"+modal_name).remove();
    $("body").append(content);

    $('#'+modal_name).on('shown.bs.modal', function (e) {
        
    });

    $('#'+modal_name).on('show.bs.modal', function (e) {
        $(".sk-circle-layer").hide();
    });
    $('#'+modal_name).on('hide.bs.modal', function (e) {
        $("#"+modal_name).remove();
    });
    $('#'+modal_name).modal('show');
}

function delivery_pos_changed(){
    if ($("#delivery_pos").is(':checked')) {
        $('#delivery_cost').prop('readonly', false);
        $('#delivery_ref').prop('readonly', false);
        $('#delivery_cost').val("");
        $('#delivery_ref').val("");
        $('#delivery_cost').focus();
    } else {
        $('#delivery_cost').prop('readonly', true);
        $('#delivery_ref').prop('readonly', true);
    }
}

var showPaymentInformationFunctionLocked = false;
function showPaymentInformation(){
    if(showPaymentInformationFunctionLocked==false){
        showPaymentInformationFunctionLocked==true;
        lockMainPos = true;
        var options = '';
        if(settings_pl == 1) options+='<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6"><button onclick="Payment_later()" type="submit" class="btn btn-primary btn-sm">'+LG_LATER_PAYMENT+'</button></div>';
        if(settings_cc == 1) options+='<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6"><button onclick="PaymentCreditCard()" type="submit" class="btn btn-primary btn-sm">'+LG_CREDIT_CARD+'</button></div>';
        if(settings_pf == 1) options+='<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6"><button onclick="Payment()" type="submit" class="btn btn-primary">'+LG_CASH+'</button></div>';
        if(settings_pc == 1) options+='<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6"><button onclick="PaymentCheque()" type="submit" class="btn btn-primary btn-sm">By Cheque</button></div>';
        
        invoice_discount_read_only = "";
        if(enable_invoice_discount==0){
            invoice_discount_read_only = "readonly";
        }
        
        var garage_plugin_select = "none";
        var garage_plugin_options = "";
        
        var hide_select_sales_person = "block";
        if(pos_sales_person_boxes==1){
            //hide_select_sales_person = "none";
        }
        
        
        
        var hide_delivery = "none";
        if(enable_delivery_pos==1){
            hide_delivery = "block";
        }
        
        var hide_customer_cashback = "none";
        if(ENABLE_CUSTOMERS_CASHBACK==1){
            hide_customer_cashback = "block";
        }
        
        
        
        var salespersons = "<option value='0' title='Select Salesperson'>Select Salesperson</option>";
        for(var i=0;i<salesperson.length;i++){
            salespersons+="<option value='"+salesperson[i].id+"' title='"+salesperson[i].first_name+" "+salesperson[i].last_name+"'>"+salesperson[i].first_name+" "+salesperson[i].last_name+"</option>";
        }
        
        
        /* Delivery Section */
        var delivery_section =
            '\n\
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                    <div class="panel panel-default">\n\
                        <div class="panel-heading" style="padding-top:5px;padding-bottom:5px;"><b>Delivery Info</b></div>\n\
                            <div class="panel-body" style="padding:5px;">\n\
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">\n\
                                    <div class="form-group">\n\
                                        <label for="delivery_fees">Fees</label>\n\
                                        <input type="text" class="form-control big_input" id="delivery_fees" id="delivery_fees">\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
            ';
        /* End delivery section */
        
        var content =
            '<div class="modal" data-backdrop="static" id="payment_info" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true" style="'+direction_+';">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title" id="payment_info__">Payment Type</h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 '+pull_+'">\n\
                                <div class="panel panel-default">\n\
                                    <div class="panel-heading" style="padding-top:5px;padding-bottom:5px;"><b>Customer Info</b>&nbsp;<i onclick="addCustomer(\'add\',[],0)" class="glyphicon glyphicon-plus" style="font-size:20px;cursor:pointer"></i></div>\n\
                                    <div class="panel-body" style="padding:10px;">\n\
                                        <input name="customer_id" id="customer_id" type="hidden" value="0" />\n\
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 '+pull_+'">\n\
                                            <div class="form-group" style="margin-bottom:3px;">\n\
                                                <input autocomplete="off" id="customer_name_payment" name="customer_name_payment" data-provide="typeahead" type="text" class="form-control med_input" placeholder="'+LG_CUSTOMER_MAME+'">\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 '+pull_+'">\n\
                                            <div class="form-group" style="margin-bottom:3px;">\n\
                                                <input autocomplete="off" id="customer_middle_payment" name="customer_middle_payment" type="text" class="form-control med_input" placeholder="Middle Name">\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 '+pull_+'">\n\
                                            <div class="form-group" style="margin-bottom:3px;">\n\
                                                <input autocomplete="off" id="customer_last_payment" name="customer_last_payment" type="text" class="form-control med_input" placeholder="Last Name">\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">\n\
                                            <div class="form-group" style="margin-bottom:5px;">\n\
                                                <input autocomplete="off" id="customer_phone" name="customer_phone" data-provide="typeahead" type="text" class="form-control med_input" placeholder="'+LG_PHONE+'"/>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">\n\
                                            <div class="form-group" style="margin-bottom:5px;">\n\
                                                <input autocomplete="off" id="customer_address" name="customer_address" data-provide="typeahead" type="text" class="form-control med_input" placeholder="'+LG_ADDRESS+'">\n\
                                            </div>\n\
                                        </div>\n\
                                        <div id="gara_card_id_cont" class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="display:'+garage_plugin_select+'">\n\
                                            <div class="form-group" style="margin-bottom:5px;">\n\
                                                <select data-width="100%" data-live-search="true" id="gara_card_id" name="gara_card_id" class="selectpicker"><option value="0">Choose Card</option></select>\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            \n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 '+pull_+'">\n\
                                <div class="panel panel-default">\n\
                                    <div class="panel-heading" style="padding-top:5px;padding-bottom:5px;"><b>Invoice Info</b></div>\n\
                                    <div class="panel-body" style="padding:5px;">\n\
                                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 '+pull_+' pl2 pr2">\n\
                                        <div class="form-group">\n\
                                            <label for="usr">'+LG_TOTAL_AMOUNT+'</label>\n\
                                            <input id="total_price" readonly value="" type="text" class="form-control onPay big_input">\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 '+pull_+' pl2 pr2">\n\
                                        <div class="form-group">\n\
                                            <label for="usr">Discount (%)</label>\n\
                                            <input onchange="invdiscperchanged()" id="invdiscper" value="0" type="text" class="form-control onPay big_input">\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12  pl2 pr2">\n\
                                        <div class="form-group">\n\
                                            <label for="usr">'+LG_TOTAL_AFTER_INVOICE_DISCOUNT+'</label>&nbsp;<span id="cus_disc" style="display:none"></span>\n\
                                            <input '+invoice_discount_read_only+' onkeyup="discountChanged()" type="text" class="form-control onPay big_input" id="discount">\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 '+pull_+'  pl2 pr2">\n\
                                        <div class="form-group">\n\
                                            <label for="usr">Cash From Client '+default_currency_symbol+'</label>\n\
                                            <input id="cash_from_client" onkeyup="cash_from_client_changed()" value="" type="text" class="form-control onPay big_input" autofocus>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12  pl2 pr2">\n\
                                        <div class="form-group">\n\
                                            <label for="usr">Return To Client '+default_currency_symbol+'</label>\n\
                                            <input readonly id="return_to_client" value="" type="text" class="form-control onPay big_input" >\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8  pl2 pr2">\n\
                                        <div class="form-group">\n\
                                            <input autocomplete="off" id="payment_note" name="payment_note" type="text" class="form-control" placeholder="Note">\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="display:'+hide_customer_cashback+'">\n\
                                        <div class="form-group">\n\
                                            <input id="cus_ref_id" name="cus_ref_id" type="hidden" value="0">\n\
                                            <input autocomplete="off" id="cus_ref" name="cus_ref" data-provide="typeahead" type="text" class="form-control" placeholder="Referrer">\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12  pl2 pr2" style="display:'+hide_select_sales_person+'">\n\
                                        <div class="form-group">\n\
                                            <select data-size="5" data-live-search="true" id="sales_person_id" name="sales_person_id" class="selectpicker form-control" >'+salespersons+'</select>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12  pl2 pr2" style="display:'+hide_delivery+';padding-right:5px;padding-left:5px;">\n\
                                        <div class="form-group" style="text-align:center">\n\
                                             <label style="font-size:28px;"><input onchange="delivery_pos_changed()" name="delivery_pos" id="delivery_pos" type="checkbox" value="" style="width:25px;height:25px;" />&nbsp;&nbsp;Delivery</label>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12  pl2 pr2" style="display:'+hide_delivery+';padding-right:5px;padding-left:5px;">\n\
                                        <div class="form-group">\n\
                                            <input readonly="readonly" autocomplete="off" id="delivery_cost" name="delivery_cost" value="" type="text" class="form-control onPay med_input" placeholder="Fees">\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12  pl2 pr2" style="display:'+hide_delivery+';padding-left:5px;">\n\
                                        <div class="form-group">\n\
                                            <input readonly="readonly" autocomplete="off" id="delivery_ref" name="delivery_ref" value="" type="text" class="form-control onPay med_input" placeholder="Delivery Ref">\n\
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
        
        $('#discount').val(inv.getTotalPrice());
        
        var customer_i = inv.getcustomer_info();
        if(customer_i.length>0){
            setTimeout(function(){
                $('#discount').val(inv.getTotalPrice()*(1-customer_i[0].discount/100));
                cleaves_id("discount",number_of_decimal_points);
                $("#cus_disc").html(Math.floor(customer_i[0].discount)+" %");
                $("#cus_disc").show();
            },100);
        } 
        
        $('#total_price').val(inv.getTotalPrice());
        
        
        update_second_currency();
        cleaves_id("total_price",number_of_decimal_points);
        
        cleaves_id("discount",number_of_decimal_points);
        
        cleaves_id("invdiscper",1);
        
        cleaves_id("delivery_cost",number_of_decimal_points);
        
        if(CUSTOMERS_PHONE_FORMAT!="-1"){
            $("#customer_phone").mask(CUSTOMERS_PHONE_FORMAT);
        }
 
        $('#return_to_client').val(0);
        cleaves_id("return_to_client",number_of_decimal_points);
        
        $('#discount').select();
        
        $('.selectpicker').selectpicker();
        
        var $input = null;
        var $input_phone = null;
        var $input_referrer = null;
        
        
        $.get("?r=pos&f=getAllCustomersDetails", function(data){
            
            var sourceArr = [];
            for (var i = 0; i < data.length; i++) {
               sourceArr.push({phone:data[i].phone,first_name:data[i].name,middle_name:data[i].middle_name,last_name:data[i].last_name,only_name:data[i].name+" "+data[i].middle_name+" "+data[i].last_name,name:data[i].name+" "+data[i].middle_name+" "+data[i].last_name+"-"+data[i].phone,id:data[i].id,address:data[i].address,discount:data[i].discount});
            }
            
            $input = $("#customer_name_payment");
            $input_referrer = $("#cus_ref");
            $input_phone = $("#customer_phone");
            $input.typeahead({
              source: sourceArr,
              autoSelect: true,
            });
            
            if(ENABLE_CUSTOMERS_CASHBACK==1){
                $input_referrer.typeahead({
                  source: sourceArr,
                  autoSelect: true,
                });
            }
            
            $input_phone.typeahead({
              source: sourceArr,
              autoSelect: true,
            });
            
            if(ENABLE_CUSTOMERS_CASHBACK==1){
                $input_referrer.change(function() {
                    var current = $input_referrer.typeahead("getActive");
                    if (current.name == $input_referrer.val()) {
                        $("#cus_ref_id").val(current.id);
                    } else {
                        $("#cus_ref_id").val(0);
                    }
                });
            }

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
                       
                        $('#discount').val(inv.getTotalPrice()*(1-current.discount/100));
                        cleaves_id("discount",number_of_decimal_points);
                        
                        if(garage_car_plugin==1){
                            garage_plugin_options = "";
                            $("#gara_card_id_cont").show();
                            var g_data = [];
                            $.getJSON("?r=garage&f=get_unassigned_card&p0="+current.id, function (data) {
                                g_data = data;
                                $.each(data, function (key, val) {
                                    garage_plugin_options += "<option value=" + val.id + ">" + val.car_type + " " + val.model + "</option>";
                                });
                            }).done(function () {
                                if(g_data.length>0){
                                    $("#gara_card_id").empty();
                                    $("#gara_card_id").append(garage_plugin_options);
                                    $("#gara_card_id").selectpicker("refresh");
                                }
                            });
                        }
                    } else {
                        //$("#customer_id").val(0);
                    }
                } else {
                    //$("#customer_id").val(0);
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
                        $('#discount').val(inv.getTotalPrice()*(1-current.discount/100));
                        
                        cleaves_id("discount",number_of_decimal_points);

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
                cleaves_id("cash_from_client",number_of_decimal_points);
                
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
                
                if(pos_sales_person_boxes==1){
                    show_sales_persons();
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

function invdiscperchanged(){
    var after_dis = 0;
    var discount_val = $("#invdiscper").val().replace(/,\s?/g, "");
    after_dis = $("#total_price").val().replace(/,\s?/g, "")*(1-discount_val/100);
    $("#discount").val(after_dis);
    cleaves_id("discount",number_of_decimal_points);
}

function cash_from_client_changed(){
    if($("#cash_from_client").val()=="" || $("#cash_from_client").val()==null) {
        $('#return_to_client').val(0-$("#discount").val().replace(/,\s?/g, ""));
    }else{
        if($("#discount").val().replace(/,\s?/g, "")=="" || $("#discount").val().replace(/,\s?/g, "")==null ){
             $('#return_to_client').val($("#cash_from_client").val().replace(/,\s?/g, "")-parseFloat(0));
        }else{
            $("#return_to_client").val($("#cash_from_client").val().replace(/,\s?/g, "")-$("#discount").val().replace(/,\s?/g, ""));
        }   
    }
    
    if($("#cash_from_client").val().replace(/,\s?/g, "") < $("#discount").val().replace(/,\s?/g, "")){
        $("#return_to_client").css("border-color", "red");
    }else{
         $("#return_to_client").css("border-color", "#CCC");
    }
    
    cleaves_id("return_to_client",number_of_decimal_points);
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
                            <h3 class="modal-title" id="payment_info__">Statement &nbsp;<b style="color:red;">Total Balance:</b> <span id="pay_unpaid">-</span><i style="float:right;font-size:30px" class="glyphicon glyphicon-remove" onclick="closeModal(\'payments_of_customer\')"></i></h3>\n\
                        </div>\n\
                        <div class="modal-body">\n\
                            <div class="row">\n\
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">\n\
                                    <div class="form-group">\n\
                                        <input autocomplete="off" id="customer_id_payment_search" name="customer_id_payment_search" data-provide="typeahead" type="text" class="form-control" placeholder="">\n\
                                        <input id="customer_id_payment" name="customer_id_payment" type="hidden" >\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">\n\
                                    <div class="input-group" style="width:100%">\n\
                                    <input type="text" id="pdatepicker" class="form-control" placeholder="">\n\
                                    <span class="input-group-btn">\n\
                                        <button disabled="disabled" id="pdatepickerbtn" class="btn btn-primary" type="button"  onclick="prepare_print_customer_statement_(1)">Statement of Account</button>\n\
                                        <button disabled="disabled" id="pdatepickerbtn_d" class="btn btn-primary" type="button"  onclick="prepare_print_customer_statement(1)">Detailed Statement</button>\n\
                                    </span>\n\
                                </div>\n\
                                </div>\n\
                                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-6" style="text-align:center;padding-left:5px;padding-right:5px;">\n\
                                    <button disabled onclick="addPaymentToCustomer()" style="font-size:16px; width:100%" id="add_payment_btn" type="button" class="btn btn-default">Payment</button>\n\
                                </div>\n\
                                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-6" style="text-align:center">\n\
                                    <button disabled onclick="print_statement()" style="font-size:16px; width:100%" id="stmt_btn" type="button" class="btn btn-default">Print</button>\n\
                                </div>\n\
                                <div class="col-lg-1 col-md-1 col-sm-12" >\n\
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
                                            <th style="width: 90px;"></th>\n\
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

            var $input = null;

            $.getJSON("?r=customers&f=getCustomersToPay", function (data) {
                
                var sourceArr = [];
                for (var i = 0; i < data.length; i++) {
                   sourceArr.push({id:data[i].id,name:data[i].name+" "+data[i].last_name+" "+data[i].phone});
                }

                $input = $("#customer_id_payment_search");
                $input.typeahead({
                  source: sourceArr,
                  autoSelect: true,
                });

                $input.change(function() {
                    var current = $input.typeahead("getActive");
                    if (current) {
                        if (current.name == $input.val()) {
                            $("#customer_id_payment").val(current.id);
                            customer_changed_pos();
                        } else {
                            $("#customer_id_payment").val(0);
                        }
                    } else {
                        $("#customer_id_payment").val(0);
                    }
                });   
            }).done(function () {

                $('#payments_of_customer').on('show.bs.modal', function (e) {
                    $(".sk-circle-layer").hide();
                    
                    
                    var defaultStart = moment().startOf('month');
                    var end = moment();

                    $('#pdatepicker').daterangepicker({
                        //dateLimit:{month:12},
                        startDate: defaultStart,
                        endDate: end,
                        locale: {
                            format: 'YYYY-MM-DD'
                        },
                        ranges: {
                            'Today': [moment(), moment()],
                            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                            'This Month': [moment().startOf('month'), moment().endOf('month')],
                            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                         }
                    });
                        
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
        ajax: "?r=customers&f=get_customer_statement&p0="+$("#customer_id_payment").val(),
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
            { "targets": [9], "searchable": false, "orderable": false, "visible": true},
            { "targets": [10], "searchable": false, "orderable": false, "visible": false,sClass: "alignCenter" },
            { "targets": [11], "searchable": false, "orderable": false, "visible": false,sClass: "alignCenter" }
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
        fnDrawCallback: updateRowsStCUS,
        fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            $(nRow).addClass(aData[2]);
        },
        
    });
    
    $('#customers_statement_table').DataTable().on('mousedown',"tr", function ( e, dt, type, indexes ) { 
        $('.selected').removeClass("selected");
        $(this).addClass('selected');
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

function updateRowsStCUS(){

    var table = $('#customers_statement_table').DataTable();
    var p = table.rows({ page: 'current' }).nodes();
    for (var k = 0; k < p.length; k++){
        var index = table.row(p[k]).index();
        
        if(table.cell(index, 8).data()==0 && table.cell(index, 1).data()==""){
            if(table.cell(index, 2).data().indexOf("CUS") !== -1){
                table.cell(index, 9).data('<i class="glyphicon glyphicon glyphicon-trash trash_icon" onclick="delete_customer_payment(\''+parseInt(table.cell(index, 2).data().split("-")[1])+'\')"></i>&nbsp;<i class="glyphicon glyphicon glyphicon-edit trash_icon" onclick="edit_customer_payment(\''+parseInt(table.cell(index, 2).data().split("-")[1])+'\')"></i>&nbsp;<i class="glyphicon icon-printer-tool trash_icon" onclick="print_payment_receipt_customer_payment(\''+parseInt(table.cell(index, 2).data().split("-")[1])+'\')"></i>');
                table.cell(index, 10).data('');
                table.cell(index, 11).data('');
            }
        }
        
        if(table.cell(index, 1).data().indexOf("INV") !== -1){
            table.cell(index, 9).data('<i class="glyphicon glyphicon-print shortcut" title="Print Receipt" onclick="printAgain(\''+parseInt(table.cell(index, 2).data().split("-")[1])+'\')"></i>');
            table.cell(index, 10).data('');
            table.cell(index, 11).data('');
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
                customer_changed_pos();
            });
       }
    }); 
}


function refreshCustomersPaymentsTable(){
    var table = $('#customers_statement_table').DataTable();
    table.ajax.url("?r=customers&f=get_customer_statement&p0="+$("#customer_id_payment").val()).load(function () {
        table.page('last').draw(false);
    },false);
}

function print_statement(){
    $.getJSON("?r=print_invoice&f=print_statement&p0="+$("#customer_id_payment").val(), function (data) {

    }).done(function () {
        
    });
}

function addPaymentToCustomer(){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    addCustomerPaymentDetails($("#customer_id_payment").val(),0,[],"pos");
    $("#add_payment_btn").removeAttr("disabled");

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
    
}

function prepare_print_customer_statement_(full){
    printing_customer_statement_date_range__($("#customer_id_payment").val(),$("#pdatepicker").val());
    //printing_customer_statement($("#customers_list").val());
}

function prepare_print_customer_statement(full){
        printing_customer_statement_date_range($("#customer_id_payment").val(),$("#pdatepicker").val());
        //printing_customer_statement($("#customers_list").val());
    }

function customer_changed_pos(){
    if($("#customer_id_payment").val() != 0){
        $("#add_payment_btn").removeAttr("disabled");
        $("#stmt_btn").removeAttr("disabled");
        
        $("#pay_val").removeAttr("disabled");
        $("#show_payment_btn").removeAttr("disabled");
        
        $("#pdatepickerbtn").removeAttr("disabled");
        $("#pdatepickerbtn_d").removeAttr("disabled");
        
        updateCustomerPaymentInfo();
    }else{
        $("#pay_unpaid").html("-");
        $("#pay_val").attr("disabled","disabled");
        $("#add_payment_btn").attr("disabled","disabled");
        $("#stmt_btn").attr("disabled","disabled");
        $("#show_payment_btn").attr("disabled","disabled");
        
        $("#pdatepickerbtn").attr("disabled","disabled");
        $("#pdatepickerbtn_d").attr("disabled","disabled");
        
    }
    refreshCustomersPaymentsTable();
}

function updateCustomerPaymentInfo(){
    $.getJSON("?r=pos&f=getCustomersPaymentInfo&p0="+$("#customer_id_payment").val(), function (data) {
        $("#pay_unpaid").html(data.total_remain)
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
    if(force_select_sales_persion_on_pos==1){
        if($("#sales_person_id").val()==0){
            PaymentFunctionLocked=false;
            swal("Select Salesperson first");
            return;
        }
    }
    
    if($("#customer_id").val()==0){
        swal("Select Customer First");
        return;
    }
    
    if(Payment_laterFunctionLocked==false){
        Payment_laterFunctionLocked=true;
        if(!emptyInput("customer_name_payment")){
            var more_info = [];
            more_info.push({customer_name:$("#customer_name_payment").val(),phone:$("#customer_phone").val(),customer_id:$("#customer_id").val(),address:$("#customer_address").val(),payment_note:$("#payment_note").val(),sales_person_id:$("#sales_person_id").val(),middle_name:$("#customer_middle_payment").val(),last_name:$("#customer_last_payment").val(),cus_referrer:$("#cus_ref_id").val(),to_second_currency_rate:$("#to_second_currency").val().replace(/,/g , '')});
            $("#payment_info button").attr("disabled","disabled");
            
            $("#customer_phone").unmask();
            
            var dlv = 0;
            var dlv_cost = 0;
            var dlv_ref = "";
            if(enable_delivery_pos==1){
                if ($("#delivery_pos").is(':checked')) {
                    dlv = 1;
                    dlv_cost = $("#delivery_cost").val().replace(/,\s?/g, "");
                    dlv_ref = $('#delivery_ref').val();
                    if(dlv_cost==""){
                            dlv_cost = 0;
                    }
                }
            }

            $.ajax({
                type: 'POST',
                url: '?r=invoice&f=pay_pos_6',
                dataType: 'json',
                data: {'items': inv.getDataMinimized(),'pay':'lp','store_id':store_id,'more_info':more_info,"after_discount":$("#discount").val(),"cash_from_client":$("#cash_from_client").val().replace(/,\s?/g, ""),"gara_card_id":$("#gara_card_id").val(),"delivery":dlv,"delivery_cost":dlv_cost,"delivery_ref":dlv_ref},
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
                           inv.clear_customer_display(0);
                        });
                    }else if(auto_print==1){
                        inv.print_invoice(msg.inv_id,0);
                        inv.clear_customer_display(0);
                    }else{
                        inv.clear_customer_display(0);
                    }
                
                    $('#payment_info').modal('toggle');
                    $("#pay").addClass("disabledPay");
                    inv.clear_customer_display(0);
                    
                    if(garage_car_plugin=="1"){
                        edit_card_client(msg.card_id);
                    }
                    
                    inv.deleteCookies();
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
    if(force_select_sales_persion_on_pos==1){
            if($("#sales_person_id").val()==0){
                PaymentFunctionLocked=false;
                swal("Select Salesperson first");
                return;
            }
        }
    if(PaymentFunctionLocked==false){
        PaymentFunctionLocked=true;
        
        var dlv = 0;
        var dlv_cost = 0;
        var dlv_ref = "";
        if(enable_delivery_pos==1){
            if ($("#delivery_pos").is(':checked')) {
                dlv = 1;
                dlv_cost = $("#delivery_cost").val().replace(/,\s?/g, "");
                dlv_ref = $('#delivery_ref').val();
                if(dlv_cost==""){
                        dlv_cost = 0;
                }
            }
        }
        
        $("#payment_info button").attr("disabled","disabled");
        $("#customer_phone").unmask();
        var more_info = [];
        more_info.push({customer_name:$("#customer_name_payment").val(),phone:$("#customer_phone").val(),customer_id:$("#customer_id").val(),address:$("#customer_address").val(),payment_note:$("#payment_note").val(),middle_name:$("#customer_middle_payment").val(),last_name:$("#customer_last_payment").val(),cus_referrer:$("#cus_ref_id").val(),to_second_currency_rate:$("#to_second_currency").val().replace(/,/g , '')});
        $.ajax({
            type: 'POST',
            url: '?r=invoice&f=pay_pos_6',
            dataType: 'json',
            data: {'items': inv.getDataMinimized(),'pay':'pc','store_id':store_id,'more_info':more_info,"after_discount":$("#discount").val(),"gara_card_id":$("#gara_card_id").val(),"delivery":dlv,"delivery_cost":dlv_cost,"delivery_ref":dlv_ref},
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
                            
                       inv.clear_customer_display(0);
                    });
                }else if(auto_print==1){
                    inv.print_invoice(msg.inv_id,0);
                    inv.clear_customer_display(0);
                }else{
                    inv.clear_customer_display(0);
                }
                if(garage_car_plugin=="1"){
                    edit_card_client(msg.card_id);
                }
                
                inv.deleteCookies();
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
        if(force_select_sales_persion_on_pos==1){
            if($("#sales_person_id").val()==0){
                PaymentFunctionLocked=false;
                swal("Select Salesperson first");
                return;
            }
        }
        
         
        var dlv = 0;
        var dlv_cost = 0;
        var dlv_ref = "";
        if(enable_delivery_pos==1){
            if ($("#delivery_pos").is(':checked')) {
                dlv = 1;
                dlv_cost = $("#delivery_cost").val().replace(/,\s?/g, "");
                dlv_ref = $('#delivery_ref').val();
                if(dlv_cost==""){
                        dlv_cost = 0;
                }
            }
        }
   
        $("#payment_info button").attr("disabled","disabled");
        $("#customer_phone").unmask();
        var more_info = [];
        
       
        more_info.push({customer_name:$("#customer_name_payment").val(),phone:$("#customer_phone").val(),customer_id:$("#customer_id").val(),address:$("#customer_address").val(),payment_note:$("#payment_note").val(),sales_person_id:$("#sales_person_id").val(),middle_name:$("#customer_middle_payment").val(),last_name:$("#customer_last_payment").val(),cus_referrer:$("#cus_ref_id").val(),to_second_currency_rate:$("#to_second_currency").val().replace(/,/g , '')});
        $.ajax({
            type: 'POST',
            url: '?r=invoice&f=pay_pos_6',
            dataType: 'json',
            data: {'items': inv.getDataMinimized(),'pay':'cc','store_id':store_id,'more_info':more_info,"after_discount":$("#discount").val(),"gara_card_id":$("#gara_card_id").val(),"delivery":dlv,"delivery_cost":dlv_cost,"delivery_ref":dlv_ref},
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
                            
                       inv.clear_customer_display(0);
                    });
                }else if(auto_print==1){
                    inv.print_invoice(msg.inv_id,0);
                    inv.clear_customer_display(0);
                }else{
                    inv.clear_customer_display(0);
                }
                
                if(garage_car_plugin=="1"){
                    edit_card_client(msg.card_id);
                }
                
                inv.deleteCookies();
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
    if(force_select_sales_persion_on_pos==1){
            if($("#sales_person_id").val()==0){
                PaymentFunctionLocked=false;
                swal("Select Salesperson first");
                return;
            }
        }
        
    if(QuickPaymentFunctionLocked==false && inv.getDataMinimized().length>0){
        QuickPaymentFunctionLocked=true;
        
        
        
        $(".sk-circle").center();
        $(".sk-circle-layer").show();
        $("#payment_info button").attr("disabled","disabled");

        $("#customer_phone").unmask();
        var more_info = [];
        
        var dlv = 0;
        var dlv_cost = 0;
        var dlv_ref = "";
        if(enable_delivery_pos==1){
            if ($("#delivery_pos").is(':checked')) {
                dlv = 1;
                dlv_cost = $("#delivery_cost").val().replace(/,\s?/g, "");
                dlv_ref = $('#delivery_ref').val();
                if(dlv_cost==""){
                        dlv_cost = 0;
                }
            }
        }
        
        more_info.push({customer_name:$("#customer_name_payment").val(),phone:$("#customer_phone").val(),customer_id:$("#customer_id").val(),address:$("#customer_address").val(),payment_note:$("#payment_note").val(),sales_person_id:0,middle_name:$("#customer_middle_payment").val(),last_name:$("#customer_last_payment").val(),cus_referrer:$("#cus_ref_id").val(),to_second_currency_rate:$("#to_second_currency").val().replace(/,/g , '')});
        inv.open_cashDrawer();
        $.ajax({
            type: 'POST',
            url: '?r=invoice&f=pay_pos_6',
            dataType: 'json',
            data: {'items': inv.getDataMinimized(),'pay':'full','store_id':store_id,'more_info':more_info,"after_discount":inv.getTotalPrice(),"gara_card_id":$("#gara_card_id").val(),"delivery":dlv,"delivery_cost":dlv_cost,"delivery_ref":dlv_ref},
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
                        inv.clear_customer_display(0);  
                    });
                    
                }else if(auto_print==1){
                    inv.print_invoice(msg.inv_id,0);
                    inv.clear_customer_display(0);
                }else{
                    inv.clear_customer_display(0);
                }
                
          
                if(garage_car_plugin=="1"){
                    edit_card_client(msg.card_id);
                }
                
                inv.deleteCookies();
                
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
    if(force_select_sales_persion_on_pos==1){
            if($("#sales_person_id").val()==0){
                PaymentFunctionLocked=false;
                swal("Select Salesperson first");
                return;
            }
        }
    if(PaymentFunctionLocked==false){
        PaymentFunctionLocked=true;
        
        
        
        $("#payment_info button").attr("disabled","disabled");
        
        var dlv = 0;
        var dlv_cost = 0;
        var dlv_ref = "";
        if(enable_delivery_pos==1){
            if ($("#delivery_pos").is(':checked')) {
                dlv = 1;
                dlv_cost = $("#delivery_cost").val().replace(/,\s?/g, "");
                dlv_ref = $('#delivery_ref').val();
                if(dlv_cost==""){
                        dlv_cost = 0;
                }
            }
        }
        

        $("#customer_phone").unmask();
        var more_info = [];
        more_info.push({customer_name:$("#customer_name_payment").val(),phone:$("#customer_phone").val(),customer_id:$("#customer_id").val(),address:$("#customer_address").val(),payment_note:$("#payment_note").val(),sales_person_id:$("#sales_person_id").val(),middle_name:$("#customer_middle_payment").val(),last_name:$("#customer_last_payment").val(),cus_referrer:$("#cus_ref_id").val(),to_second_currency_rate:$("#to_second_currency").val().replace(/,/g , '')});
        inv.open_cashDrawer();
        $.ajax({
            type: 'POST',
            url: '?r=invoice&f=pay_pos_6',
            dataType: 'json',
            data: {'items': inv.getDataMinimized(),'pay':'full','store_id':store_id,'more_info':more_info,"after_discount":$("#discount").val(),"gara_card_id":$("#gara_card_id").val(),"delivery":dlv,"delivery_cost":dlv_cost,"delivery_ref":dlv_ref},
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
                        inv.clear_customer_display(0);
                    });
                    
                }else if(auto_print==1){
                    inv.print_invoice(msg.inv_id,0);
                    inv.clear_customer_display(0);
                }else{
                    inv.clear_customer_display(0);
                }
                
                if(garage_car_plugin=="1"){
                    edit_card_client(msg.card_id);
                }
                
                inv.deleteCookies();
                
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

function deleteItem(item_id_) {
    if($(".select_p_item").length>0){
        setTimeout(function () {
            var item_id = null;
            if(item_id_>0){
                item_id=item_id_;
            }else{
                item_id = parseInt($(".select_p_item").attr("id").split('_')[1]);
            }
            //var item_id = parseInt($(".select_p_item").attr("id").split('_')[1]);
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
    if(cashBox == 0){
        setCashbox();
        return;
    }
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

var categories_parents = [];
var categories = [];
var tmp = [];
function categories_list_changed(){
    $('#subcategories_list').empty();
    $('#subcategories_list').append('<option value=0 title="All Sub-Categories">All Sub-Categories</option>');
    for(var i=0;i<categories.length;i++){
        if(categories[i].parent==$("#categories_list").val() || $("#categories_list").val()==0){
           $('#subcategories_list').append('<option value='+categories[i].id+' title="'+categories[i].name+'" data-subtext="'+tmp[categories[i].parent]+'">'+categories[i].name+'</option>');
        }
    }
    $('#subcategories_list').selectpicker('refresh');
    updateAllItems();
}

function updateAllItems(){
    $(".sk-circle-layer").show();
    $('#items_search').DataTable().ajax.url("?r=pos&f=get_all_items_new&p0=0&p1="+$("#categories_list").val()+"&p2="+$("#subcategories_list").val()+"").load(function () {
        $(".sk-circle-layer").hide();
    }, false);
}

function showAllItems(barcode){
    if(cashBox == 0){
        setCashbox();
        return;
    }
    
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
        
    $.getJSON("?r=items&f=get_needed_data_categories_subcategories", function (data) {
        $.each(data.parents_categories, function (key, val) {
            categories_parents.push({id:val.id,name:val.name});
            tmp[val.id] = val.name;
        });
        $.each(data.categories, function (key, val) {
            categories.push({id:val.id,name:val.description,parent:val.parent});
            
        });
    }).done(function () {
        var title = "";
        if(barcode==0){
            title="All Items";
        }else{
            title="All Items - Same Barcode";
        }
        
        var col_multi_branches = false;
        //alert(multi_branches);
        if(multi_branches==1){
            col_multi_branches = true;
        }
        
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
                                    <th style="width: 100px !important;">Price</th>\n\
                                    <th style="width: 50px !important;">Disc.</th>\n\
                                    <th style="width: 35px !important;">Vat</th>\n\
                                    <th style="width: 100px !important;">Total</th>\n\
                                    <th style="width: 35px !important;">Qty</th>\n\
                                    <th style="width: 80px !important;">Color</th>\n\
                                    <th style="width: 45px !important;">Size</th>\n\
                                    <th style="width: 45px !important;"></th>\n\
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
                                    <th>Total</th>\n\
                                    <th>Qty</th>\n\
                                    <th>Color</th>\n\
                                    <th>Size</th>\n\
                                    <th></th>\n\
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
            var search_fields = [0,1,2,3,4,5,6,7,8,9];
            var index = 0;
            $('#items_search tfoot th').each( function () {
                if(jQuery.inArray(index, search_fields) !== -1){
                    var title = $(this).text();


                    $(this).html( '<input style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder=" '+title+'" />' );
                    index++;
                }
            });

            items_search = $('#items_search').DataTable({
                ajax: {
                    url: "?r=pos&f=get_all_items_new&p0="+barcode+"&p1=0&p2=0",
                    //url: "useful/items.json",
                    type: 'POST',
                    deferRender: true,
                    error:function(xhr,status,error) {
                        logged_out_warning();
                    },
                },
                orderCellsTop: true,
                iDisplayLength: 100,
                scrollY: '44vh',
                scrollCollapse: true,
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
                    { "targets": [10], "searchable": true, "orderable": false, "visible": col_multi_branches,"className": "dt-center" },
                ],
                paging: true,
                dom: '<"toolbar">frtip',
                initComplete: function(settings, json) {
                    //items_search.cell( ':eq(0)' ).focus();
                    //$('#items_search tfoot input:eq(1)').focus();

                    var categories_parents_options = "";
                    categories_parents_options+='<option value=0 title="All Categories">All Categories</option>';
                    for(var i=0;i<categories_parents.length;i++){
                        categories_parents_options+='<option value='+categories_parents[i].id+' title="'+categories_parents[i].name+'">'+categories_parents[i].name+'</option>';
                    }

                    var categories_options = "";
                    categories_options+='<option value=0 title="All Sub-Categories">All Sub-Categories</option>';
                    for(var i=0;i<categories.length;i++){
                        categories_options+='<option value='+categories[i].id+' title="'+categories[i].name+'" data-subtext="'+tmp[categories[i].parent]+'">'+categories[i].name+'</option>';
                    }

                    $("div.toolbar").html('\n\
                    <div class="row">\n\
                        <div class="col-lg-2 col-md-2">\n\
                            <div class="btn-group" role="group" aria-label="" style="width:100%">\n\
                                <select id="categories_list" data-live-search="true" data-width="100%" id="" class="selectpicker" onchange="categories_list_changed()">\n\
                                    '+categories_parents_options+'\n\
                                </select>\n\
                            </div>\n\
                        </div>\n\
                        <div class="col-lg-2 col-md-2">\n\
                            <div class="btn-group" role="group" aria-label="" style="width:100%">\n\
                                <select id="subcategories_list" data-live-search="true" data-width="100%" id="" class="selectpicker" onchange="updateAllItems()">\n\
                                    '+categories_options+'\n\
                                </select>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    ');

                    $(".selectpicker").selectpicker();
                    $(".sk-circle-layer").hide();
                },
                fnDrawCallback: updateRows_items_search,
            });
            
            $('#items_search').DataTable().on('dblclick',"tr", function ( e, dt, type, indexes ) {
                var sdata = items_search.row('.selected', 0).data();
                
                if($("#modal_wasting_modal__").length>0){
        
                    wasting_get_by_id(parseInt(sdata[0].split("-")[1]));
                    refresh_wasting_table(1);
                    if(pos_all_items_hide_on_add_to_invoice==1){
                        $('#noBarcodeModal').modal('toggle');
                    }else{
                        $.playSound('libraries/sounds/success.mp3');
                    }
                }else if($("#show_invoice_to_changeModal").length>0){
                    search_barcode_iv_changed(parseInt(sdata[0].split("-")[1]));
                    if(pos_all_items_hide_on_add_to_invoice==1)
                        $('#noBarcodeModal').modal('toggle');
                }else{
                    add_to_invoive(parseInt(sdata[0].split("-")[1]));
                }  
            });
            
            $('#items_search').DataTable().on('mousedown',"tr", function ( e, dt, type, indexes ) { 
                $('.selected').removeClass("selected");
                $(this).addClass('selected');
            });

            $('#items_search').on('key.dt', function(e, datatable, key, cell, originalEvent){
                if(key === 13){
                    var sdata = items_search.row('.selected', 0).data();
                    add_to_invoive(parseInt(sdata[0].split("-")[1]));
                }
            });
            
            $('#items_search').on( 'page.dt', function () {
                $('.selected').removeClass("selected");
            } );

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
    });
    
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
        table.cell(index, 10).data('<i title="Search in branches" class="glyphicon glyphicon-cloud" style="cursor:pointer;font-size:20px;" onclick="get_all_qty_if_item('+parseInt(table.cell(index, 0).data().split('-')[1])+')"></i>');
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

function _showCashBox(){
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

function showCashBox(){
    if(set_password_for_cashbox_and_report_pos!=-1){
        swal({
            title: "Enter Password",
            html: true ,
            text: '<input class="form-control" value="" type="password" id="pass"/>',
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Ok",
            cancelButtonText: "Cancel",
            closeOnConfirm: true,
            closeOnCancel: true
        },
        function(isConfirm) {
            if (isConfirm) {
                if($("#pass").val() == set_password_for_cashbox_and_report_pos){
                    _showCashBox();
                }else{
                    alert("Wrong Password");
                } 
            }
        });
        setTimeout(function(){ $("#pass").focus(); },200);
    }else{
        _showCashBox();
    }
    
    
}

function qty_changed(qty_object,item_id){
    inv.set_qty(item_id,$(qty_object).val());
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
                        for(var j=parseInt($("#qty_"+item_id).val());j>=1;j--){
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

function price_changed(price_object,item_id){
    inv.change_discount(item_id,mask_clean($(price_object).val()),0);
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
                            inv.change_discount(item_id,$("#m_disc").val(),0);
                        }else{
                            inv.change_discount_percentage(item_id,parseFloat($("#m_disc").val()),0);
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
                            $("#m_disc").attr("placeholder",cu_items[i].final_price)+" - "+ parseFloat(cu_items[i].wholesale_price); 
                        }else{
                           $("#m_disc").attr("placeholder",cu_items[i].final_price); 
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
                text: '<input class="form-control" value="" type="password" id="pass"/>',
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
            switch (event.which) { 
                case 13: // enter
                    if($(".select_p_item").length>0){
                        var item_id = parseInt($(".select_p_item").attr("id").split('_')[1]);
                    }
                    break;
                case 38: // up
                    $("#qty_"+CURRENT_SELECTED_ROW_INDEX).blur();
                    if($(".select_p_item").prev().length>0){
                        var current = $(".select_p_item");
                        $(".select_p_item").prev().addClass("select_p_item");
                        current.removeClass("select_p_item");
                        CURRENT_SELECTED_ROW_INDEX = $(".select_p_item").attr("id").split('_')[1];
                        current = null;
                        scrollToSelectedItem();
                    }
                    event.preventDefault();
                    break;
                case 40: // down
                    $("#qty_"+CURRENT_SELECTED_ROW_INDEX).blur();
                    if($(".select_p_item").next().length>0){
                        var current = $(".select_p_item");
                        $(".select_p_item").next().addClass("select_p_item");
                        current.removeClass("select_p_item");
                        CURRENT_SELECTED_ROW_INDEX = $(".select_p_item").attr("id").split('_')[1];
                        current = null;
                        scrollToSelectedItem();
                    }
                    event.preventDefault();
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
                    if(enable_keyboard_open_cashdrawer==1){
                        inv.open_cashDrawer();
                    }
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
                case 9: //tab key
                    
                    select_and_focus(event);
                    
                    break;    
                default: 
                    return;
            }
        }
    });
}

function select_and_focus(event){
    if(CURRENT_SELECTED_ROW_INDEX>0){
        if ($("#qty_"+CURRENT_SELECTED_ROW_INDEX).is(":focus")) {
            
        }else{
            $("#qty_"+CURRENT_SELECTED_ROW_INDEX).focus();
            $("#qty_"+CURRENT_SELECTED_ROW_INDEX).select();
            event.preventDefault();
        } 
    }
    
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

            
function precisionRound(number, precision) {
  var factor = Math.pow(10, precision);
  return Math.round(number * factor) / factor;
}


function format_input_number(nb,input_id,decimal_digit_nb,round){
    nb = parseFloat(nb).toFixed(decimal_digit_nb);
    var dcm = "";
    if(decimal_digit_nb==0){
        dcm = "";
    }else{
        dcm = ".";
    }
    for(var i=0;i<decimal_digit_nb;i++){
        dcm+="0";
    }
    
    $(input_id).mask("#,##0"+dcm, {reverse: true});
    $(input_id).val(nb);
    $(input_id).trigger('input');
}

function update_rate(){
    for(var i=0;i<all_currencies.length;i++){
        
        if(all_currencies[i].id==$("#payment_currency").val()){
            $("#currency_rate").val(all_currencies[i].rate_to_system_default);
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
        addCustomerPaymentDetails($("#customer_id_payment").val(),id,_data,"pos");
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

function modal_close(id){
    $('#'+id).modal('toggle');
}