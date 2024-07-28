function auto_generate_barcodes_changed(){
    if ($("#auto_generate_barcodes").is(':checked')) {
        $(".g_bc_").prop("disabled",true);
    } else {
        $(".g_bc_").prop("disabled",false);
    }
}

function clean_unselected(){
    var text_colors_g_array = $("#item_text_color_g").val().toString().split(',');
    var item_size_g_array = $("#item_size_g").val().toString().split(',');
    
    $('.g_qty_').each(function(index) {
        var idattr = $(this).attr("id").split("_");
        var _color_id = idattr[2];
        var _size_id = idattr[3];
           
        if ($.inArray(_color_id, text_colors_g_array) !== -1 && $.inArray(_size_id, item_size_g_array) !== -1) {
            
        } else {
            $("#grp_bc_"+_color_id+"_"+_size_id+"_c").remove();
            $("#sku_bc_"+_color_id+"_"+_size_id+"_c").remove();
            $("#qty_bc_"+_color_id+"_"+_size_id+"_c").remove();
        }   
    });
}

function default_qty_changed(){
    if($("#default_qty_grp").val()>=0){
        
        $.confirm({
            title: 'Adjust the quantities for all items in this group to '+$("#default_qty_grp").val()+'!',
            content: 'Are you sure?',
            buttons: {
                YES: {
                    btnClass: 'btn-success',
                    action: function(){
                        $('.g_qty_').each(function(index) {
                            $(this).val($("#default_qty_grp").val());
                        });
                    }
                },
                CANCEL: {
                    btnClass: 'btn-red any-other-class', // multiple classes.
                    action: function(){
                        
                    }
                },
            }
        });
        
        
    }
}

function delete_grn_grp(_color_id,_size_id){
    
    $.confirm({
            title: 'Delete Item',
            content: 'Are you sure?',
            buttons: {
                DELETE: {
                    btnClass: 'btn-danger',
                    action: function(){
                        $("#grp_bc_"+_color_id+"_"+_size_id+"_c").remove();
                        $("#sku_bc_"+_color_id+"_"+_size_id+"_c").remove();
                        $("#qty_bc_"+_color_id+"_"+_size_id+"_c").remove();
                    }
                },
                CANCEL: {
                    btnClass: 'btn-default any-other-class', // multiple classes.
                    action: function(){
                        
                    }
                },
            }
        });
        
    
}

function update_grp_container(){
    //$("#update_grp_container").empty();
    
    var text_colors_g = $("#item_text_color_g").val();
    var item_size_g = $("#item_size_g").val();
    for(var i=0;i<text_colors_g.length;i++){
         for(var k=0;k<item_size_g.length;k++){
             
            var optionText = $('#item_text_color_g').find('option[value="' + text_colors_g[i] + '"]').text();
            var optionText_size_ = $('#item_size_g').find('option[value="' + item_size_g[k] + '"]').text();
            
            if(item_size_g[k]==0){
                optionText_size_="";
            }
            
            if($("#grp_bc_"+text_colors_g[i]+"_"+item_size_g[k]).length==0){
                $("#update_grp_container").append('\
                <div class="col-xs-4" id="grp_bc_'+text_colors_g[i]+'_'+item_size_g[k]+'_c">\n\
                    <div class="form-group">\n\
                        <input name="grp_bc_'+text_colors_g[i]+'_'+item_size_g[k]+'" id="grp_bc_'+text_colors_g[i]+'_'+item_size_g[k]+'" value="" type="text" class="form-control g_bc_" placeholder="Barcode of '+optionText+" "+ optionText_size_+'">\n\
                    </div>\n\
                </div>\n\
                <div class="col-xs-4" id="sku_bc_'+text_colors_g[i]+'_'+item_size_g[k]+'_c">\n\
                    <div class="form-group">\n\
                        <input name="sku_bc_'+text_colors_g[i]+'_'+item_size_g[k]+'" id="sku_bc_'+text_colors_g[i]+'_'+item_size_g[k]+'" value=""  type="text" class="form-control" placeholder="SKU of '+optionText+" "+optionText_size_+'">\n\
                    </div>\n\
                </div>\n\
                <div class="col-xs-4" id="qty_bc_'+text_colors_g[i]+'_'+item_size_g[k]+'_c">\n\
                    <div class="row">\n\
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">\n\
                            <div class="form-group">\n\
                                <input name="qty_bc_'+text_colors_g[i]+'_'+item_size_g[k]+'" id="qty_bc_'+text_colors_g[i]+'_'+item_size_g[k]+'" value="0"  type="number" class="form-control g_qty_" placeholder="Quantity of '+optionText+" "+optionText_size_+'">\n\
                            </div>\n\
                        </div>\n\
                        <div class="col-ld-4 col-md-4 col-sm-4 col-xs-4">\n\
                            <i  id="del_'+text_colors_g[i]+'_'+item_size_g[k]+'" onclick="delete_grn_grp('+text_colors_g[i]+','+item_size_g[k]+')" title="Delete" class="glyphicon glyphicon-trash trash_icon red"></i>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
                ');
            }
        }
    }
    
    auto_generate_barcodes_changed();
    clean_unselected();
    
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

var total_active_toast=0;
var max_active_toast=5;
function generate_toast(heading,text,icon,hideAfter,bgColor,position){
    return $.toast({
        heading: heading,
        text: text,
        position:position,
        showHideTransition: 'slide',
        icon: icon,
        hideAfter: hideAfter, 
        bgColor:'#'+bgColor,
        afterShown: function () {
            total_active_toast++;
        },
        afterHidden: function () {
            total_active_toast--;
        },
    });
}

function check_notifications(){
    if(total_active_toast<max_active_toast){
        var _data=[];
        $.getJSON("?r=notifications&f=get_new_not", function (data) {
            _data=data;
        }).done(function () {
            if(_data.length>0){
               for(var i=0;i<_data.length;i++){
                   generate_toast(_data[i].heading,_data[i].text,_data[i].icon,_data[i].hideAfter,_data[i].bgColor,"bottom-left"); 
               }
            }
        });
    }
}

var intervalId=null;
function set_interval(time){
    check_notifications();
    intervalId = setInterval(check_notifications, time);
}

function fixed_price_changed(){
    if($("#fixed_price").val()==1){
        $("#fixed_price_val").val($("#convert_to_usd_input").val());
        cleaves_class("#fixed_price_val",5);
    }else{
         $("#fixed_price_val").val("");
    }
}

function precisionRound(number, precision) {
  var factor = Math.pow(10, precision);
  return Math.round(number * factor) / factor;
}

function convert_to_usd(object){
    $("#selling_price").val(precisionRound(parseFloat($(object).val()/current_rate),5));
    change_selling_price();
}

function convert_to_usd_cost(object){
    $("#item_cost").val(precisionRound(parseFloat($(object).val()/current_rate),5));
    change_cost();
}


function set_final_price(p){
    $("#custom_per_profit").val("");
    $("#convert_to_usd_input").val("");
    
    
    $("#selling_price").val(precisionRound(parseFloat($("#item_cost").val())*(1+p/100),3));
    change_selling_price();
}

function set_final_price_custom(object){
    $("#convert_to_usd_input").val("");
    $("#selling_price").val(precisionRound(parseFloat($("#item_cost").val())*(1+$(object).val()/100),3));
    //$("#item_cost").trigger("change");
    change_selling_price();
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
function update_rate(){
    for(var i=0;i<all_currencies.length;i++){
        if(all_currencies[i].id==$("#payment_currency").val()){
            $("#currency_rate").val(parseFloat(all_currencies[i].rate_to_system_default).toFixed(10));
            $("#currency_rate").trigger("input");
        }
    }
}


var current_rate=0;
function addItem(source) {
    if(source=="add_item"){
        $(".tab_toolbar button.multiselect").removeClass("disabled");
        //$('.selected').removeClass("selected");
    }
    
    
    var suppliers = "";
    var materials = "";
    var categories = "";
    var parents_categories = "";
    var measures = "";
    var sizes = "";
    var text_colors = "";
    var index = 0;
    var item_another_description_lang = 0;
    var usd_but_show_lbp_priority=0;
    $(".sk-circle-layer").show();
    $.getJSON("?r=items&f=get_needed_data", function (data) {
        
        usd_but_show_lbp_priority= data.usd_but_show_lbp_priority;
       
        current_rate=data.rate;
        $.each(data.suppliers, function (key, val) {
            if (val.id == 1) {
                suppliers += "<option selected value=" + val.id + ">" + val.name + "</option>";
            } else {
                suppliers += "<option value=" + val.id + ">" + val.name + "</option>";
            }
            index++;
        });
        
        index = 0;
        $.each(data.categories, function (key, val) {
            if (index == 0) {
                categories += "<option selected value=" + val.id + ">" + val.description + "</option>";
            } else {
                categories += "<option value=" + val.id + ">" + val.description + "</option>";
            }
            index++;
        });
        
        index = 0;
        $.each(data.materials, function (key, val) {
            if (index == 0) {
                materials += "<option selected value=" + val.id + ">" + val.name + "</option>";
            } else {
                materials += "<option value=" + val.id + ">" + val.name + "</option>";
            }
            index++;
        });
        
        
        index = 0;
        $.each(data.parents_categories, function (key, val) {
            if (index == 0) {
                parents_categories += "<option selected value=" + val.id + ">" + val.name + "</option>";
            } else {
                parents_categories += "<option value=" + val.id + ">" + val.name + "</option>";
            }
            index++;
        });
        
        $.each(data.measures, function (key, val) {
            measures += "<option value=" + val.id + ">" + val.name + "</option>";
        });
        
        sizes += "<option selected value='0'>None</option>";
        $.each(data.sizes, function (key, val) {
            sizes += "<option value=" + val.id + ">" + val.name + "</option>";
        });
        
        text_colors += "<option selected value='0'>None</option>";
        $.each(data.colors_text, function (key, val) {
            text_colors += "<option value=" + val.id + ">" + val.name + "</option>";
        });
        
        item_another_description_lang = data.item_another_description_lang;
        
    }).done(function () {
      
        var extra_add_item = "";
        if(source=="add_item" || source=="categories"){
            extra_add_item = '<span onclick="addSupplier(\'items\')" style="cursor:pointer; font-size:14px; font-weight:bold;color:#286090">Add new supplier</span>';
        }
         
        var enable_wholasale_field = '';
        if(enable_wholasale==1){
            enable_wholasale_field += '\n\
                <div class="col-xs-4">\n\
                    <div class="form-group">\n\
                        <label for="item_final_wholesale_price">Wholesale Price</label>\n\
                        <div class="inner-addon addon_item_icon"><input onkeyup="" id="item_final_wholesale_price" value="0" name="item_final_wholesale_price" type="text" class="form-control med_input item_pc"" placeholder="Wholesale price" /></div>\n\
                    </div>\n\
                </div>\n\
                <div class="col-xs-4">\n\
                    <div class="form-group">\n\
                        <label for="item_final_sec_wholesale_price">Second Wholesale Price</label>\n\
                        <div class="inner-addon addon_item_icon"><input onkeyup="" id="item_final_sec_wholesale_price" value="0" name="item_final_sec_wholesale_price" type="text" class="form-control med_input item_pc"" placeholder="Second Wholesale price" /></div>\n\
                    </div>\n\
                </div>';
        }
        
        var enable_fixed_field = '';
        if(usd_but_show_lbp_priority==1){
            enable_fixed_field += '\n\
                <div class="col-xs-4">\n\
                    <div class="form-group">\n\
                        <label for="item_final_wholesale_price">Fixed</label>\n\
                        <div class="inner-addon"><select onchange="fixed_price_changed()" id="fixed_price" name="fixed_price" class="selectpicker form-control" style="width:100%"><option selected value="0">NO</option><option value="1">YES</option></select></div>\n\
                    </div>\n\
                </div>\n\
                <div class="col-xs-4">\n\
                    <div class="form-group">\n\
                        <label for="item_final_wholesale_price">Fixed Value</label>\n\
                        <div class="inner-addon addon_item_icon"><input id="fixed_price_val" value="" name="fixed_price_val" type="text" class="form-control med_input item_pc" placeholder="" /></div>\n\
                    </div>\n\
                </div>';
        }
        
        var item_another_description_lang_field = '';
        if(item_another_description_lang == "1"){
            item_another_description_lang_field += '\n\
                <div class="col-xs-12">\n\
                    <div class="form-group">\n\
                        <label for="another_description">Another Lang Description</label>\n\
                        <input id="another_description" name="another_description" type="text" class="form-control" placeholder="Another Lang Description" />\n\
                    </div>\n\
                </div>\n\
            ';
        }

        hide_critical_data_display="";
       if(hide_critical_data==1){
           hide_critical_data_display="display:none";
       }
        
        var content =
            '<div class="modal" data-backdrop="static" data-keyboard="false" id="newItem" tabindex="-1" role="dialog" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <form id="add_new_item" action="" method="post" enctype="multipart/form-data" >\n\
                    <input id="id_to_edit" name="id_to_edit" type="hidden" value="0" />\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title" id="exampleModalLongTitle"><i class="glyphicon glyphicon-plus"></i>&nbsp;Define new Item</h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div id="exTab1" class="container">\n\
                            <ul class="nav nav-pills">\n\
                                <li class="active">\n\
                                    <a  href="#1a" data-toggle="tab">General</a>\n\
                                </li>\n\
                                <li>\n\
                                    <a href="#2a" data-toggle="tab">Pricing</a>\n\
                                </li>\n\
                                <li>\n\
                                    <a href="#3a" data-toggle="tab">Advanced</a>\n\
                                </li>\n\
                                <li>\n\
                                    <a href="#4a" data-toggle="tab">Box</a>\n\
                                </li>\n\
                                <li>\n\
                                    <a href="#5a" data-toggle="tab">Group</a>\n\
                                </li>\n\
                            </ul>\n\
                            <div class="tab-content clearfix">\n\
                                <div class="tab-pane active" id="1a">\n\
                                    <div class="row">\n\
                                        <div class="col-xs-10">\n\
                                            <div class="form-group">\n\
                                                <label for="item_desc">Item Description</label>\n\
                                                <div class="inner-addon"><input id="item_desc" name="item_desc" type="text" class="form-control" placeholder="Item Description" aria-describedby="basic-addon1" /></div>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-xs-2">\n\
                                            <div class="form-group">\n\
                                                <label for="item_sku">SKU Code</label>\n\
                                                <input id="item_sku" name="item_sku" type="text" class="form-control" placeholder="SKU Code" />\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="row">\n\
                                        '+item_another_description_lang_field+'\n\
                                    </div>\n\
                                    <div class="row">\n\
                                        <div class="col-xs-6">\n\
                                            <div class="form-group">\n\
                                                <label for="item_alias">Alias Description</label>\n\
                                                <input id="item_alias" name="item_alias" type="text" class="form-control" placeholder="Alias name" aria-describedby="basic-addon1" />\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-xs-4">\n\
                                            <div class="form-group">\n\
                                                <label for="item_barcode">Primary Barcode</label>\n\
                                                <div class="inner-addon"><input onchange="checkBarcodeIfExist()" id="item_barcode" name="item_barcode" type="text" class="form-control" placeholder="Barcode" aria-describedby="basic-addon1"/></div>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-xs-2">\n\
                                            <label for="item_barcode">&nbsp;</label>\n\
                                           <button style="width:100%" type="button" class="btn btn-default" onclick="generateBarcode(0)">Generate</button>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="row" style="'+ptype_hide+'">\n\
                                        <div class="col-xs-6" >\n\
                                            <div class="form-group">\n\
                                                <label for="supplier_id">Suppliers</label>&nbsp;&nbsp;'+extra_add_item+'\n\
                                                <select data-live-search="true" id="supplier_id" name="supplier_id" class="selectpicker form-control" style="width:100%">' + suppliers + '</select>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-xs-4">\n\
                                            <div class="form-group">\n\
                                                <label for="item_barcode">Second Barcode</label>\n\
                                                <div class="inner-addon"><input oninput="" id="item_barcode_second" name="item_barcode_second" type="text" class="form-control" placeholder="Second Barcode" aria-describedby="basic-addon1"/></div>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-xs-2">\n\
                                            <div class="form-group">\n\
                                                <label for="item_weight">Weight</label>\n\
                                                <input id="item_weight" name="item_weight" type="text" class="form-control item_pc" placeholder="Weight" />\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="row">\n\
                                        <div class="col-xs-6">\n\
                                            <div class="form-group">\n\
                                                <label for="item_cat_p">Categories</label>&nbsp;&nbsp;<span onclick="addParentCategory(\'otherPage_P\')" style="cursor:pointer; font-size:14px; font-weight:bold;color:#286090">Add new category</span>\n\
                                                <select  data-live-search="true" id="item_cat_p" name="item_cat_p" class="selectpicker form-control" style="width:100%" onchange="parent_cat_changed(0,0)">' + parents_categories + '</select>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-xs-6">\n\
                                            <div class="form-group">\n\
                                                <label for="item_cat">Sub-Categories</label>&nbsp;&nbsp;<span onclick="addCategory(\'otherPage\')" style="cursor:pointer; font-size:14px; font-weight:bold;color:#286090">Add new sub-category</span>\n\
                                                <select  data-live-search="true" id="item_cat" name="item_cat" class="selectpicker form-control" style="width:100%">' + categories + '</select>\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="tab-pane" id="2a">\n\
                                    <div class="row">\n\
                                        <div class="col-xs-4" style="'+hide_critical_data_display+'">\n\
                                            <div class="form-group">\n\
                                                <label for="item_cost">Unit Cost </label><input id="convert_to_usd_input_cost" onkeyup="convert_to_usd_cost(this)" placeholder="In lbp" class="form-control sm_input lbpprio" type="text" style="float:right; width:90px;padding:0px;height:23px;" />\n\
                                                <div class="inner-addon"><input onkeyup="change_cost()" id="item_cost" value="0" name="item_cost" type="text" class="form-control med_input item_pc " placeholder="Cost" aria-describedby="basic-addon1" /></div>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-xs-4" style="'+hide_critical_data_display+'">\n\
                                            <div class="form-group">\n\
                                              <label for="item_vat">Add Tax Rate</label>\n\
                                                <select onChange="vat_change()" id="item_vat" name="item_vat" class="selectpicker form-control"><option value="1" disabled>Add VAT '+Math.floor((vatValue-1)*100)+'%</option><option selected value="0">No VAT</option></select>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-xs-4" style="'+hide_critical_data_display+'">\n\
                                            <div class="form-group">\n\
                                              <label for="item_final_cost">Final Unit Purchase Cost</label>\n\
                                                <div class="inner-addon"><input onkeyup="change_final_cost()" id="item_final_cost" value="0" name="item_final_cost" type="text" class="form-control med_input item_pc" placeholder="Cost" aria-describedby="basic-addon1" /></div>\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="row">\n\
                                        <div class="col-xs-4" style="'+ptype_hide+'">\n\
                                           <div class="row">\n\
                                                <div class="col-lg-12">\n\
                                                    <div class="form-group" style="margin-bottom:2px;">\n\
                                                        <label for="selling_price">Unit Sales Price </label><input id="convert_to_usd_input" onkeyup="convert_to_usd(this)" placeholder="In lbp" class="form-control sm_input lbpprio" type="text" style="float:right; width:90px;padding:0px;height:23px;" />\n\
                                                        <div class="inner-addon"><input onkeyup="change_selling_price()" id="selling_price" value="0" name="selling_price" type="text" class="form-control med_input item_pc" placeholder="Cost" aria-describedby="basic-addon1" /></div>\n\
                                                    </div>\n\
                                                </div>\n\
                                                <div class="col-lg-12">\n\
                                                    <div class="row">\n\
                                                        <div class="col-lg-2" style="font-size:14px;text-align:center;padding-right:0px;padding-top:2px;">\n\
                                                            <span title="Final price with profit" onclick="set_final_price(10)" style="cursor:pointer">+10%</span>\n\
                                                        </div>\n\
                                                        <div class="col-lg-2" style="font-size:14px;text-align:center;padding-right:0px;padding-top:2px;">\n\
                                                            <span title="Final price with profit"  onclick="set_final_price(15)" style="cursor:pointer">+15%</span>\n\
                                                        </div>\n\
                                                        <div class="col-lg-2" style="font-size:14px;text-align:center;padding-right:0px;padding-top:2px;">\n\
                                                            <span title="Final price with profit"  onclick="set_final_price(20)" style="cursor:pointer">+20%</span>\n\
                                                        </div>\n\
                                                        <div class="col-lg-6" style="text-align:center">\n\
                                                            <input placeholder="Custom %" title="Final price with profit"  onchange="set_final_price_custom(this)" onkeyup="set_final_price_custom(this)" type="number" value="" id="custom_per_profit" style="width:100%; height:22px;font-size:14px;" /> \n\
                                                        </div>\n\
                                                    </div>\n\
                                                </div>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-xs-4" style="'+ptype_hide+'">\n\
                                            <div class="form-group">\n\
                                              <label for="item_disc">Discount %</label>\n\
                                                <div class="inner-addon"><input onkeyup="change_discount()" id="item_disc" value="0" name="item_disc" type="text" class="form-control med_input only_numeric" placeholder="Cost" aria-describedby="basic-addon1" /></div>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-xs-2" style="padding-left:0px;display:none">\n\
                                            <div class="form-group">\n\
                                                <label for="item_vat">Vat</label>\n\
                                                <select onChange="vat_on_sale_change()" id="item_vat_on_sale" name="item_vat_on_sale" class="selectpicker form-control"><option value="1">Add VAT '+Math.floor((vatValue-1)*100)+'%</option><option selected value="0">No VAT</option></select>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-xs-4">\n\
                                            <div class="form-group">\n\
                                              <label for="item_final_price">Final Price</label>\n\
                                                <div class="inner-addon"><input onkeyup="item_final_price_Changed()" id="item_final_price" value="0" name="item_final_price" type="text" class="form-control med_input item_pc" placeholder="Price" aria-describedby="basic-addon1" /></div>\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="row">\n\
                                        '+enable_fixed_field+'\n\
                                    </div>\n\
                                    <div class="row">\n\
                                        '+enable_wholasale_field+'\n\
                                    </div>\n\
                                    <div class="row">\n\
                                        <div class="col-xs-4">\n\
                                            <div class="form-group">\n\
                                                <label for="item_cost">Show on POS</label>\n\
                                                <br/><input checked id="show_on_pos"  name="show_on_pos" type="checkbox" style="width:30px;height:30px;" />\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-xs-4" style="display:none">\n\
                                            <div class="form-group">\n\
                                                <label for="item_cost">Depend on var price</label>\n\
                                                <br/><input id="dvar"  name="dvar" type="checkbox" style="width:30px;height:30px;" />\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="tab-pane" id="3a">\n\
                                    <div class="row">\n\
                                        <div class="col-xs-3" style="'+ptype_hide+'">\n\
                                            <div class="form-group">\n\
                                                <label for="item_unit_measure">Unit Measure</label>&nbsp;<span onclick="addUnit()" style="cursor:pointer; font-size:14px; font-weight:bold;color:#286090">Add Unit</span>\n\
                                                <select  data-live-search="true" id="item_unit_measure" name="item_unit_measure" class=" selectpicker form-control" style="width:100%">'+measures+'</select>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-xs-3">\n\
                                            <div class="form-group">\n\
                                                <label for="item_size">Unit Size</label>&nbsp;<span onclick="addSize()" style="cursor:pointer; font-size:14px; font-weight:bold;color:#286090">Add Size</span>\n\
                                                <select  data-live-search="true" id="item_size" name="item_size" class="selectpicker form-control" style="width:100%">'+sizes+'</select>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-xs-3" style="'+ptype_hide+'">\n\
                                            <div class="form-group">\n\
                                                <label for="item_color_p">Unit Color</label>\n\
                                                <div id="cp2" class="input-group colorpicker-component"><input type="text" value="#00AABB" class="form-control" name="item_color" id="item_color" /><span class="input-group-addon"><i></i></span></div>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-xs-3">\n\
                                            <div class="form-group">\n\
                                                <label for="item_text_color">Text Color</label>&nbsp;<span onclick="addColor()" style="cursor:pointer; font-size:14px; font-weight:bold;color:#286090">Add Color</span>\n\
                                                <select  data-live-search="true" id="item_text_color" name="item_text_color" class="selectpicker form-control" style="width:100%">'+text_colors+'</select>\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="row">\n\
                                        <div class="col-xs-3" style="'+ptype_hide+'">\n\
                                            <div class="form-group">\n\
                                              <label for="item_final_price">Warn quantity</label>\n\
                                                <div class="inner-addon"><input id="lack_warning" value="5" name="lack_warning" type="text" class="form-control only_numeric" placeholder="" aria-describedby="basic-addon1" /></div>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-xs-3" style="'+ptype_hide+'">\n\
                                            <div class="form-group">\n\
                                              <label for="item_final_price">Supplier reference</label>\n\
                                                <div class="inner-addon"><input id="supplier_ref" value="" name="supplier_ref" type="text" class="form-control" placeholder="" /></div>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-xs-3" style="'+ptype_hide+'">\n\
                                            <div class="form-group">\n\
                                                <label for="material_id">Material</label>&nbsp;<span onclick="addMaterial()" style="cursor:pointer; font-size:14px; font-weight:bold;color:#286090">Add Material</span>\n\
                                                <select  data-live-search="true" id="material_id" name="material_id" class="selectpicker form-control" style="width:100%">'+materials+'</select>\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="row">\n\
                                        <div class="col-xs-3" style="'+ptype_hide+'">\n\
                                            <div class="form-group">\n\
                                              <label for="item_final_price">Show in instant report</label>\n\
                                                <div class="inner-addon"><input name="i_report" type="checkbox" value="0" style="width:20px; height:20px;" /></div>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-xs-3" style="display:none">\n\
                                            <div class="form-group">\n\
                                              <label for="item_final_price">Vendor qty access</label>\n\
                                                <div class="inner-addon "><input name="v_access" type="checkbox" value="0" style="width:20px; height:20px;" /></div>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-xs-3" style="'+ptype_hide+'">\n\
                                            <div class="form-group">\n\
                                                <label for="official_or_not">Off. or not</label>\n\
                                                <div class="inner-addon"><input id="official_or_not" name="official_or_not" type="checkbox" value="0" style="width:20px; height:20px;" /></div>\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="row">\n\
                                        <div class="col-lg-12">\n\
                                            <div class="form-group">\n\
                                              <label for="image_link">Image Link</label>\n\
                                                <div class="inner-addon"><input id="image_link" value="" name="image_link" type="text" class="form-control" placeholder="" /></div>\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="tab-pane" id="4a">\n\
                                    <div class="row">\n\
                                        <div class="col-xs-6">\n\
                                            <div class="form-group">\n\
                                                <label for="item_final_price">Item</label>\n\
                                                <input type="hidden" value="0" name="composite_item_id" id="composite_item_id" />\n\
                                                <input autocomplete="off" onkeyup="composite_item_changed(this)" id="composite_item" value="" name="composite_item" type="text" class="form-control" placeholder=""/>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-xs-2">\n\
                                            <div class="form-group">\n\
                                                <label for="item_final_price">Quantity</label>\n\
                                                <input id="composite_item_qty" value="0" name="composite_item_qty" type="text" class="form-control item_pc" placeholder=""/>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-xs-2">\n\
                                            <div class="form-group">\n\
                                                <label for="item_final_price">Packs</label>\n\
                                                <div class="inner-addon"><input name="is_pack" type="checkbox" style="width:30px; height:30px;" /></div>\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="tab-pane" id="5a">\n\
                                    <div class="row">\n\
                                        <div class="col-xs-6">\n\
                                            <div class="form-group">\n\
                                                <label for="item_text_color_g">Text Color</label>\n\
                                                <select onchange="update_grp_container()" multiple data-live-search="true" id="item_text_color_g" name="item_text_color_g[]" class="selectpicker form-control" style="width:100%">'+text_colors+'</select>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-xs-6">\n\
                                            <div class="form-group">\n\
                                                <label for="item_size_g">Unit Size</label>\n\
                                                <select onchange="update_grp_container()" multiple data-live-search="true" id="item_size_g" name="item_size_g[]" class="selectpicker form-control" style="width:100%">'+sizes+'</select>\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="row">\n\
                                        <div class="col-xs-4">\n\
                                            <div class="form-group">\n\
                                                <label for="">Auto Generate Barcodes</label><br/>\n\
                                                <input type="checkbox" onchange="auto_generate_barcodes_changed()" id="auto_generate_barcodes" name="" style="width:20px; height:20px;"  />\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-xs-4">\n\
                                            \n\
                                        </div>\n\
                                        <div class="col-xs-4">\n\
                                            <div class="form-group">\n\
                                                <label for="">Default Quantity</label>\n\
                                                <input class="form-control" type="number" id="default_qty_grp" onchange="default_qty_changed()" value="0" />\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="row" id="update_grp_container">\n\
                                        \n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-footer">\n\
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\n\
                        <a onclick="$(this).closest(\'form\').submit()" type="submit" class="btn btn-primary">Add</a>\n\
                    </div>\n\
                    <form/>\n\
                </div>\n\
            </div>\n\
        </div>';

        $('#newItem').remove();
        $('#editItem').remove();
        $('body').append(content);
        $('.selectpicker').selectpicker();
        $(".only_numeric").numeric({ negative : false});
        
        
        $(".only_numeric").attr("autocomplete", "off");
        
        
        
        submitNewItem(source);


        $('#newItem').on('hide.bs.modal', function (e) {
            $('#newItem').remove();
        });
        
        
        $('#newItem').on('shown.bs.modal', function (e) {
            cleaves_class(".item_pc",5);
            if($("#sbarcode").length>0 && $("#sbarcode").val().length>0){
                $("#item_barcode").val($("#sbarcode").val());
                $("#sbarcode").val("");
            }
            
            $(".sk-circle-layer").hide();
            $("#item_desc").focus();
            $('#cp2').colorpicker({
                format:'hex',
                horizontal: true,
                colorSelectors: {
                    'White': '#FFFFFF',
                    'Silver': '#C0C0C0',
                    'Gray': '#808080',
                    'Black': '#000000',
                    'Red': '#FF0000',
                    'Maroon': '#800000',
                    'Yellow': '#FFFF00',
                    'Olive': '#808000',
                    'Lime': '#00FF00',
                    'Green': '#008000',
                    'Aqua': '#00FFFF',
                    'Teal': '#008080',
                    'Blue':'#0000FF',
                    'Navy':'#000080',
                    'Fuchsia':'#FF00FF',
                    'Purple':'#800080	'
                }
            });
            
            type_ahead_items("?r=items&f=getitems_for_type_head","composite_item");
            items_autocomplete("item_desc");
            
            if(source=="categories"){
                
                var dt = $('#categories_table').DataTable();
                var sdata_category = dt.row('.selected', 0).data();
                
                var dt = $('#parent_categories_table').DataTable();
                var sdata_parent = dt.row('.selected', 0).data();
                $('#item_cat_p').selectpicker('val', parseInt(sdata_parent[0].split('-')[1]));
                
                parent_cat_changed(parseInt(sdata_parent[0].split('-')[1]),parseInt(sdata_category[0].split('-')[1]));

                
            }else{
                parent_cat_changed(0,0);
            }
            
            
            if(usd_but_show_lbp_priority==1){
                $(".lbpprio").show();
            }else{
                $(".lbpprio").hide();
            }
            
        });
        
        $('#newItem').modal('show');
        
    });
}



function parent_cat_changed(default_,select_cat){
    $.getJSON("?r=items&f=get_needed_data", function (data) {
        $("#item_cat").empty();
        $("#item_cat").selectpicker('refresh');
        $.each(data.categories, function (key, val) {
            if( val.parent == $("#item_cat_p").val() ){
                $("#item_cat").append("<option value='"+val.id+"'>"+val.description+"</option>");
            }
        });
        if(default_>0){
            $("#item_cat").selectpicker('val', default_);
        }
        $("#item_cat").selectpicker('refresh');
    }).done(function () {
        if(select_cat!=0){            
            $("#item_cat").selectpicker('val', select_cat);
        }
    });
}

function composite_item_changed(object){
    if($(object).val().length==0){
        //alert($("#"+$(object).attr("id")).length);
        $("#"+$(object).attr("id")+"_id").val(0);
        $("#"+$(object).attr("id")+"_qty").val(0);
    };
}

function type_ahead_items(url,input_id){
    $.get(url, function(data){
        var $input = $("#"+input_id);
        $input.typeahead({
            source: data,
            autoSelect: true,
            fitToElement:false,
        });

        $input.change(function() {
            var current = $input.typeahead("getActive");
            if (current) {
                //alert(current.name +"=="+ $input.val());
                // Some item from your model is active!
                if (current.name == $input.val()) {

                    //alert("HERE");
                    // This means the exact match is found. Use toLowerCase() if you want case insensitive match.
                    ///$("#customer_id").val(current.id);

                        $("#composite_item_id").val(current.id);
                        $("#composite_item_id").css({ 'border-color' : ''});
                }else{
                    ///$("#customer_id").val(0);
                    // This means it is only a partial match, you can either add a new item
                    // or take the active if you don't want new items
                }
            } else {
                ///$("#customer_id").val(0);
                // Nothing is active so it is a new value (or maybe empty value)
            }
        });

    },'json')
    .done(function(){
    })
    .fail(function() {
    })
    .always(function() {
    });
}


function checkBarcodeIfExist(){
    var barcode = $("#item_barcode").val();
    $.getJSON("?r=items&f=checkBarcodeIfExist&p0="+barcode, function (data) {
        if(data.length>0){
            $("#item_barcode").css("border-color", "red");
        }else{
            $("#item_barcode").css("border-color", ""); 
        }
    }).done(function () {
        
    });
}

function generateBarcode(exist){ 
    swal({
        title: "Generating Barcode",
        html: true ,
        text: 'Are you sure?',
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "",
        cancelButtonText: "Cancel",
        closeOnConfirm: true,
        closeOnCancel: true
    },
    function(isConfirm) {
        if (isConfirm) {
            $.getJSON("?r=items&f=generateBarcode&p0="+exist, function (data) {
                $("#item_barcode").val(data[0]);
                $("#complex_item_barcode").val(data[0]);
            }).done(function () {
                checkBarcodeIfExist();
            });
        }
        $(".sweet-alert").remove();
        $(".sweet-overlay").remove();
    });
}


function editItem(id,duplicate) {
    var id_int = parseInt(id.split('-')[1]);
    
    var item_id = null;
    var item_sup_id = null;
    var item_desc = null;
    var item_barcode = null;
    var item_barcode_second = null;
    var item_category = null;
    var item_buying_cost = null;
    var item_vat = null;
    var vat_on_sale = null;
    var item_selling_price = null;
    var item_discount = null;
    var item_final_cost = null;
    var item_final_price = null;
    var lack_warning = null;
    var v_access = null;
    var instant_report = null;
    var item_alias = null;
    
    var item_sku = null;
    
    var supplier_ref = null;
    var is_official = null;
    var is_pack=null;
    
    var color_text_id = null;
    
    var unit_measure_id = null;
    var color_id = null;
    var size_id = null;
    var material_id = null;
    var item_another_description_lang = 0;
    var another_description = "";
    
     var show_on_pos_checked = "";
     var dvar_checked = "";
    
    var material_id = null;
    var composite_items_info = [{"item_id":"0","qty":"0","description":""}];
    var is_compo = false;
    
    var weight="";
    
    var usd_but_show_lbp_priority="";
    
    var wholesale_price = null;
    var second_wholesale_price=null;
    var image_link="";
    var fixed_price = null;
    var fixed_price_value = null;
            
    $(".sk-circle-layer").show();
    $.getJSON("?r=items&f=get_item&p0=" + id_int, function (data) {
        $.each(data, function (key, val) {
            item_id = parseInt(val.id);
            item_sup_id = parseInt(val.supplier_reference);
            item_desc = val.description.replace(/"/g, '&quot;');
            item_barcode = val.barcode;
            
            fixed_price=val.fixed_price;
            fixed_price_value=val.fixed_price_value;
            
            
            weight=val.weight;
            
            item_barcode_second = val.second_barcode;
            if(val.second_barcode==null){
                item_barcode_second = "";
            }
           
            item_category = parseInt(val.item_category);
            item_buying_cost = parseFloat(val.buying_cost);
            item_vat = parseInt(val.vat);
            vat_on_sale = parseInt(val.vat_on_sale);
            
            image_link=val.image_link;
            
            
            if(val.show_on_pos==1){
                show_on_pos_checked=" checked ";
            }else{
                show_on_pos_checked="";
            }
            
            if(val.depend_on_var_price==1){
                dvar_checked=" checked ";
            }else{
                dvar_checked="";
            }
            
   
            
            item_selling_price = parseFloat(val.selling_price);
            item_discount = parseFloat(val.discount);
            lack_warning = parseFloat(val.lack_warning);
            v_access = val.vendor_quantity_access;
            instant_report = val.instant_report;
            item_alias = val.item_alias;
            
            material_id = val.material_id;
            
            if(val.item_alias==null){
                item_alias = "";
            }
            
            supplier_ref = val.supplier_ref;
            is_official = val.is_official;
            
            wholesale_price = parseFloat(val.wholesale_price);
            
            second_wholesale_price= parseFloat(val.second_wholesale_price);

            unit_measure_id = val.unit_measure_id;
            color_id = val.color_id;
            size_id = val.size_id;
            
            color_text_id = val.color_text_id;
            
            
            item_sku = val.sku_code;
            if(val.sku_code==null){
                item_sku = "";
            }
            
            is_compo = val.is_composite;
         
            if(val.is_composite==1 && val.composite_items.length>0){
                composite_items_info = val.composite_items;
                is_pack=val.composite_items[0].is_pack;
       
            }

            if(item_vat==0){
                item_final_cost = item_buying_cost;
            }else{
                item_final_cost = parseFloat(item_buying_cost*vatValue);
            }
            
            //item_final_price = Math.floor()(item_selling_price *(1 - item_discount / 100)).toFixed(2);
            item_final_price = val.item_final_price;
    
            
            another_description = val.another_description;
            if(val.another_description==null){
                another_description = "";
            }
            
        });
    }).done(function () {
        var suppliers = "";
        var categories = "";
        var parents_categories = "";
        var measures = "";
        var sizes = "";
        var text_colors = "";
        var materials = "";
        var current_parent = null;
        var current_cat = null;
        
        $.getJSON("?r=items&f=get_needed_data", function (data) {
            $.each(data.suppliers, function (key, val) {
                if (val.id == item_sup_id) {
                    suppliers += "<option selected value=" + val.id + ">" + val.name + "</option>";
                } else {
                    suppliers += "<option value=" + val.id + ">" + val.name + "</option>";
                }
            });

            $.each(data.categories, function (key, val) {
                if (item_category == val.id) {
                    current_parent = val.parent;
                    current_cat = val.id;
                }
            });

            measures += "<option selected value='0'>None</option>";
            var sel = "";
            $.each(data.measures, function (key, val) {
                sel ="";
                if(val.id == unit_measure_id){sel = "selected";}
                measures += "<option "+sel+" value=" + val.id + ">" + val.name + "</option>";
            });

            sizes += "<option selected value='0'>None</option>";
            $.each(data.sizes, function (key, val) {
                sel ="";
                if(val.id == size_id){sel = "selected";}
                sizes += "<option "+sel+" value=" + val.id + ">" + val.name + "</option>";
            });
            
            parents_categories += "<option selected value='0'>None</option>";
            $.each(data.parents_categories, function (key, val) {
                parents_categories += "<option value=" + val.id + ">" + val.name + "</option>";
            });
            
            text_colors += "<option value='0'>None</option>";
            $.each(data.colors_text, function (key, val) {
                sel ="";
                if(val.id == color_text_id){sel = "selected";}
                text_colors += "<option "+sel+" value=" + val.id + ">" + val.name + "</option>";
            });
            
            $.each(data.materials, function (key, val) {
                sel ="";
                if(val.id == material_id){sel = "selected";}
                materials += "<option "+sel+" value=" + val.id + ">" + val.name + "</option>";
            });
            
            item_another_description_lang = data.item_another_description_lang;
            
            current_rate=data.rate;
            usd_but_show_lbp_priority= data.usd_but_show_lbp_priority;

        }).done(function () {
            var id_to_edit = id_int;
            
            
            
            
            var title_modal = "Edit Item";
            var submit_btn_name = "Update";
            if(duplicate==1){
                id_to_edit=0;
                title_modal = "Add New Item";
                submit_btn_name = "Add";
            }
            
            var enable_wholasale_field = '';
            if(enable_wholasale==1){
                enable_wholasale_field += '\n\
                    <div class="col-xs-4">\n\
                        <div class="form-group">\n\
                            <label for="item_final_wholesale_price">Final Unit Wholesale/Min Price</label>\n\
                            <div class="inner-addon"><input onkeyup="" id="item_final_wholesale_price" value="'+wholesale_price+'" name="item_final_wholesale_price" type="text" class="form-control med_input item_pc"" placeholder="Wholesale price" /></div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-xs-4">\n\
                        <div class="form-group">\n\
                            <label for="item_final_sec_wholesale_price">Second Wholesale Price</label>\n\
                            <div class="inner-addon"><input onkeyup="" id="item_final_sec_wholesale_price" value="'+second_wholesale_price+'" name="item_final_sec_wholesale_price" type="text" class="form-control med_input item_pc"" placeholder="Second Wholesale price" /></div>\n\
                        </div>\n\
                    </div>';
            }
            
            var enable_fixed_field = '';
            if(usd_but_show_lbp_priority==1){
                enable_fixed_field += '\n\
                    <div class="col-xs-4">\n\
                        <div class="form-group">\n\
                            <label for="item_final_wholesale_price">Fixed</label>\n\
                            <div class="inner-addon"><select onchange="fixed_price_changed()" id="fixed_price" name="fixed_price" class="selectpicker form-control" style="width:100%"><option selected value="0">NO</option><option value="1">YES</option></select></div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-xs-4">\n\
                        <div class="form-group">\n\
                            <label for="item_final_wholesale_price">Fixed  Value</label>\n\
                            <div class="inner-addon"><input id="fixed_price_val" value="" name="fixed_price_val" type="text" class="form-control med_input item_pc" placeholder="" /></div>\n\
                        </div>\n\
                    </div>';
            }
            
            var item_another_description_lang_field = '';
            if(item_another_description_lang == "1"){
                item_another_description_lang_field += '\n\
                    <div class="col-xs-12">\n\
                        <div class="form-group">\n\
                            <label for="another_description">Another Lang Description</label>\n\
                            <input id="another_description" name="another_description" type="text" class="form-control" value="'+another_description+'" placeholder="Another Lang Description" />\n\
                        </div>\n\
                    </div>\n\
                ';
            }
     
     hide_critical_data_display="";
       if(hide_critical_data==1){
           hide_critical_data_display="display:none";
       }
       
            var content =
                '<div class="modal" data-backdrop="static" data-keyboard="false" id="editItem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">\n\
                    <div class="modal-dialog" role="document">\n\
                        <div class="modal-content">\n\
                            <form id="add_new_item" action="" method="post" enctype="multipart/form-data" >\n\
                            <input id="id_to_edit" name="id_to_edit" type="hidden" value="' + id_to_edit+  '" />\n\
                            <div class="modal-header"> \n\
                                <h3 class="modal-title" id="exampleModalLongTitle"><i class="glyphicon glyphicon-plus"></i>&nbsp;'+title_modal+'</h3>\n\
                            </div>\n\
                            <div class="modal-body">\n\
                                <div id="exTab1" class="container">\n\
                                    <ul class="nav nav-pills">\n\
                                        <li class="active">\n\
                                            <a href="#1a" data-toggle="tab">General</a>\n\
                                        </li>\n\
                                        <li>\n\
                                            <a href="#2a" data-toggle="tab">Pricing</a>\n\
                                        </li>\n\
                                        <li>\n\
                                            <a href="#3a" data-toggle="tab">Advanced</a>\n\
                                        </li>\n\
                                        <li id="tab_composite_item">\n\
                                            <a href="#4a" data-toggle="tab">Box</a>\n\
                                        </li>\n\
                                    </ul>\n\
                                    <div class="tab-content clearfix">\n\
                                        <div class="tab-pane active" id="1a">\n\
                                            <div class="row">\n\
                                                <div class="col-xs-10">\n\
                                                    <div class="form-group">\n\
                                                        <label for="item_desc">Item Description</label>\n\
                                                        <div class="inner-addon"><input value="'+item_desc+'" id="item_desc" name="item_desc" type="text" class="form-control" placeholder="Item Description" aria-describedby="basic-addon1"></div>\n\
                                                    </div>\n\
                                                </div>\n\
                                                <div class="col-xs-2">\n\
                                                    <div class="form-group">\n\
                                                        <label for="item_sku">SKU Code</label>\n\
                                                        <input id="item_sku" name="item_sku" value="'+item_sku+'" type="text" class="form-control" placeholder="SKU Code" />\n\
                                                    </div>\n\
                                                </div>\n\
                                            </div>\n\
                                            <div class="row">\n\
                                                '+item_another_description_lang_field+'\n\
                                            </div>\n\
                                            <div class="row">\n\
                                                <div class="col-xs-6">\n\
                                                    <div class="form-group">\n\
                                                        <label for="item_alias">Alias Description</label>\n\
                                                        <input value="'+item_alias+'" id="item_alias" name="item_alias" type="text" class="form-control" placeholder="Alias name" aria-describedby="basic-addon1" />\n\
                                                    </div>\n\
                                                </div>\n\
                                                <div class="col-xs-4">\n\
                                                    <div class="form-group inputP30">\n\
                                                        <label for="item_barcode">Primary Barcode</label>\n\
                                                        <div class="inner-addon"><input value="'+item_barcode+'" id="item_barcode" name="item_barcode" type="text" class="form-control" placeholder="Barcode" aria-describedby="basic-addon1"/></div>\n\
                                                    </div>\n\
                                                </div>\n\
                                                <div class="col-xs-2">\n\
                                                    <label for="item_barcode">&nbsp;</label>\n\
                                                    <button style="width:100%" type="button" class="btn btn-default" onclick="generateBarcode('+id_to_edit+')">Generate</button>\n\
                                                </div>\n\
                                            </div>\n\
                                            <div class="row" style="'+ptype_hide+'">\n\
                                                <div class="col-xs-6" >\n\
                                                    <div class="form-group">\n\
                                                        <label for="supplier_id">Suppliers</label>&nbsp;&nbsp;<span onclick="addSupplier(\'items\')" style="cursor:pointer; font-size:14px; font-weight:bold;color:#286090">Add new supplier</span>\n\
                                                        <select  data-live-search="true" id="supplier_id" name="supplier_id" class="selectpicker form-control" style="width:100%">' + suppliers + '</select>\n\
                                                    </div>\n\
                                                </div>\n\
                                            <div class="col-xs-4">\n\
                                                <div class="form-group ">\n\
                                                    <label for="item_barcode ">Second Barcode</label>\n\
                                                    <div class="inner-addon"><input oninput="" value="'+item_barcode_second+'" id="item_barcode_second" name="item_barcode_second" type="text" class="form-control" placeholder="Second Barcode" aria-describedby="basic-addon1"/></div>\n\
                                                </div>\n\
                                            </div>\n\
                                            <div class="col-xs-2">\n\
                                                <div class="form-group">\n\
                                                    <label for="item_weight">Weight</label>\n\
                                                    <input id="item_weight" name="item_weight" type="text" class="form-control item_pc" placeholder="Weight" value='+weight+' />\n\
                                                </div>\n\
                                            </div>\n\
                                            </div>\n\
                                            <div class="row">\n\
                                                <div class="col-xs-6">\n\
                                                    <div class="form-group">\n\
                                                        <label for="item_cat_p">Categories</label>&nbsp;&nbsp;<span onclick="addParentCategory(\'otherPage_P\')" style="cursor:pointer; font-size:14px; font-weight:bold;color:#286090">Add new category</span>\n\
                                                        <select  data-live-search="true" id="item_cat_p" name="item_cat_p" class="selectpicker form-control" style="width:100%" onchange="parent_cat_changed(0,0)">' + parents_categories + '</select>\n\
                                                    </div>\n\
                                                </div>\n\
                                                <div class="col-xs-6">\n\
                                                    <div class="form-group">\n\
                                                        <label for="item_cat">Sub-Categories</label>&nbsp;&nbsp;<span onclick="addCategory(\'otherPage\')" style="cursor:pointer; font-size:14px; font-weight:bold;color:#286090">Add new sub-category</span>\n\
                                                        <select  data-live-search="true" id="item_cat" name="item_cat" class="selectpicker form-control" style="width:100%"></select>\n\
                                                    </div>\n\
                                                </div>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="tab-pane" id="2a">\n\
                                            <div class="row">\n\
                                                <div class="col-xs-4" style="'+hide_critical_data_display+'">\n\
                                                    <div class="form-group">\n\
                                                        <label for="item_cost">Unit Purchase Cost</label><input id="convert_to_usd_input_cost" onkeyup="convert_to_usd_cost(this)" placeholder="In lbp" class="form-control lbpprio sm_input" type="text" style="float:right; width:90px;padding:0px;height:23px;" />\n\
                                                        <div class="inner-addon"><input onkeyup="change_cost()" id="item_cost" value="'+item_buying_cost+'" name="item_cost" type="text" class="form-control med_input item_pc" placeholder="Cost" aria-describedby="basic-addon1" /></div>\n\
                                                    </div>\n\
                                                </div>\n\
                                                <div class="col-xs-4" style="'+hide_critical_data_display+'">\n\
                                                    <div class="form-group">\n\
                                                      <label for="item_vat">Add Tax Rate</label>\n\
                                                        <select onChange="vat_change()" id="item_vat" name="item_vat" class="selectpicker form-control"><option selected value="1" disabled>Add VAT '+Math.floor((vatValue-1)*100)+'%</option><option value="0">No VAT</option></select>\n\
                                                    </div>\n\
                                                </div>\n\
                                                <div class="col-xs-4" style="'+hide_critical_data_display+'">\n\
                                                    <div class="form-group">\n\
                                                      <label for="item_final_cost">Final Cost</label>\n\
                                                        <div class="inner-addon"><input onkeyup="change_final_cost()" id="item_final_cost" value="'+item_final_cost+'" name="item_final_cost" type="text" class="form-control med_input item_pc" placeholder="Cost" aria-describedby="basic-addon1" /></div>\n\
                                                    </div>\n\
                                                </div>\n\
                                            </div>\n\
                                            <div class="row">\n\
                                                <div class="col-xs-4" style="'+ptype_hide+'">\n\
                                                    <div class="row">\n\
                                                        <div class="col-lg-12">\n\
                                                            <div class="form-group" style="margin-bottom:2px;">\n\
                                                                <label for="selling_price">Unit Sales Price</label><input id="convert_to_usd_input" onkeyup="convert_to_usd(this)" placeholder="In lbp" class="form-control  sm_input lbpprio" type="text" style="float:right; width:90px;padding:0px;height:23px;" />\n\
                                                                <div class="inner-addon"><input onkeyup="change_selling_price()" id="selling_price" value="'+item_selling_price+'" name="selling_price" type="text" class="form-control med_input item_pc" placeholder="Cost" aria-describedby="basic-addon1" /></div>\n\
                                                            </div>\n\
                                                        </div>\n\
                                                        <div class="col-lg-12">\n\
                                                            <div class="row">\n\
                                                                <div class="col-lg-2" style="font-size:14px;text-align:center;padding-right:0px;padding-top:2px;">\n\
                                                                    <span title="Final price with profit" onclick="set_final_price(10)" style="cursor:pointer">+10%</span>\n\
                                                                </div>\n\
                                                                <div class="col-lg-2" style="font-size:14px;text-align:center;padding-right:0px;padding-top:2px;">\n\
                                                                    <span title="Final price with profit"  onclick="set_final_price(15)" style="cursor:pointer">+15%</span>\n\
                                                                </div>\n\
                                                                <div class="col-lg-2" style="font-size:14px;text-align:center;padding-right:0px;padding-top:2px;">\n\
                                                                    <span title="Final price with profit"  onclick="set_final_price(20)" style="cursor:pointer">+20%</span>\n\
                                                                </div>\n\
                                                                <div class="col-lg-6" style="text-align:center">\n\
                                                                    <input placeholder="Custom %"  title="Final price with profit" onchange="set_final_price_custom(this)"  onkeyup="set_final_price_custom(this)" type="number" value="" id="custom_per_profit" style="width:100%; height:22px;font-size:14px;" /> \n\
                                                                </div>\n\
                                                            </div>\n\
                                                        </div>\n\
                                                    </div>\n\
                                                </div>\n\
                                                <div class="col-xs-4" style="'+ptype_hide+'">\n\
                                                    <div class="form-group">\n\
                                                      <label for="item_disc">Discount %</label>\n\
                                                        <div class="inner-addon"><input onkeyup="change_discount()" id="item_disc" value="'+item_discount+'" name="item_disc" type="text" class="form-control med_input" placeholder="Cost" aria-describedby="basic-addon1" /></div>\n\
                                                    </div>\n\
                                                </div>\n\
                                                <div class="col-xs-2" style="'+ptype_hide+'">\n\
                                                    <div class="form-group" style="padding-left:0px;display:none">\n\
                                                        <label for="item_vat">Vat</label>\n\
                                                        <select onChange="vat_on_sale_change()" id="item_vat_on_sale" name="item_vat_on_sale" class="selectpicker form-control"><option value="1">Add VAT '+Math.floor((vatValue-1)*100)+'%</option><option selected value="0">No VAT</option></select>\n\
                                                    </div>\n\
                                                </div>\n\
                                                <div class="col-xs-4">\n\
                                                    <div class="form-group">\n\
                                                      <label for="item_final_price">Final Price</label>\n\
                                                        <div class="inner-addon"><input onkeyup="item_final_price_Changed()"  id="item_final_price" value="'+item_final_price+'" name="item_final_price" type="text" class="form-control med_input item_pc" placeholder="Cost" aria-describedby="basic-addon1" /></div>\n\
                                                    </div>\n\
                                                </div>\n\
                                            </div>\n\
                                            <div class="row">\n\
                                                '+enable_fixed_field+'\n\
                                            </div>\n\
                                            <div class="row">\n\
                                                <div class="col-xs-4">\n\
                                                    <div class="form-group">\n\
                                                        <label for="item_cost">Update Final Cost/Price to all Group</label>\n\
                                                        <br/><input checked id="up_all_gp"  name="up_all_gp" type="checkbox" style="width:30px;height:30px;" />\n\
                                                    </div>\n\
                                                </div>\n\
                                                <div class="col-xs-4">\n\
                                                    <div class="form-group">\n\
                                                        <label for="item_cost">Show on POS</label>\n\
                                                        <br/><input '+show_on_pos_checked+' id="show_on_pos"  name="show_on_pos" type="checkbox" style="width:30px;height:30px;" />\n\
                                                    </div>\n\
                                                </div>\n\
                                                <div class="col-xs-4" style="display:none">\n\
                                                    <div class="form-group">\n\
                                                        <label for="item_cost">Depend on var price</label>\n\
                                                        <br/><input '+dvar_checked+' id="dvar"  name="dvar" type="checkbox" style="width:30px;height:30px;" />\n\
                                                    </div>\n\
                                                </div>\n\
                                            </div>\n\
                                            <div class="row">\n\
                                                '+enable_wholasale_field+'\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="tab-pane" id="3a">\n\
                                            <div class="row">\n\
                                                <div class="col-xs-3" style="'+ptype_hide+'">\n\
                                                    <div class="form-group">\n\
                                                        <label for="item_unit_measure">Unit Measure</label>&nbsp;<span onclick="addUnit()" style="cursor:pointer; font-size:14px; font-weight:bold;color:#286090">Add Unit</span>\n\
                                                        <select  data-live-search="true" id="item_unit_measure" name="item_unit_measure" class=" selectpicker form-control" style="width:100%">'+measures+'</select>\n\
                                                    </div>\n\
                                                </div>\n\
                                                <div class="col-xs-3">\n\
                                                    <div class="form-group">\n\
                                                        <label for="item_size">Unit Size</label>&nbsp;<span onclick="addSize()" style="cursor:pointer; font-size:14px; font-weight:bold;color:#286090">Add Size</span>\n\
                                                        <select  data-live-search="true" id="item_size" name="item_size" class="selectpicker form-control" style="width:100%">'+sizes+'</select>\n\
                                                    </div>\n\
                                                </div>\n\
                                                <div class="col-xs-3" style="'+ptype_hide+'">\n\
                                                    <div class="form-group">\n\
                                                        <label for="item_color_p">Unit Color</label>\n\
                                                        <div id="cp2" class="input-group colorpicker-component"><input type="text" class="form-control" name="item_color" id="item_color" /><span class="input-group-addon"><i></i></span></div>\n\
                                                    </div>\n\
                                                </div>\n\
                                                <div class="col-xs-3">\n\
                                                    <div class="form-group">\n\
                                                        <label for="item_text_color">Text Color</label>&nbsp;<span onclick="addColor()" style="cursor:pointer; font-size:14px; font-weight:bold;color:#286090">Add Color</span>\n\
                                                        <select  data-live-search="true" id="item_text_color" name="item_text_color" class="selectpicker form-control" style="width:100%">'+text_colors+'</select>\n\
                                                    </div>\n\
                                                </div>\n\
                                            </div>\n\
                                            <div class="row" style="'+ptype_hide+'">\n\
                                                <div class="col-xs-3">\n\
                                                    <div class="form-group">\n\
                                                      <label for="item_final_price">Warn quantity</label>\n\
                                                        <div class="inner-addon"><input id="lack_warning" value="'+lack_warning+'" name="lack_warning" type="text" class="form-control only_numeric" placeholder="" aria-describedby="basic-addon1" /></div>\n\
                                                    </div>\n\
                                                </div>\n\
                                                <div class="col-xs-3">\n\
                                                    <div class="form-group">\n\
                                                      <label for="item_final_price">Supplier reference</label>\n\
                                                        <div class="inner-addon"><input id="supplier_ref" value="'+supplier_ref+'" name="supplier_ref" type="text" class="form-control" placeholder="" /></div>\n\
                                                    </div>\n\
                                                </div>\n\
                                                <div class="col-xs-3">\n\
                                                    <div class="form-group">\n\
                                                        <label for="material_id">Material</label>&nbsp;<span onclick="addMaterial()" style="cursor:pointer; font-size:14px; font-weight:bold;color:#286090">Add Material</span>\n\
                                                        <select  data-live-search="true" id="material_id" name="material_id" class="selectpicker form-control" style="width:100%">'+materials+'</select>\n\
                                                    </div>\n\
                                                </div>\n\
                                            </div>\n\
                                        <div class="row" >\n\
                                            <div class="col-xs-3" style="display:none">\n\
                                                <div class="form-group">\n\
                                                  <label for="item_final_price">Vendor qty access</label>\n\
                                                    <div class="inner-addon"><input id="v_access" name="v_access" type="checkbox" value="0" style="width:20px; height:20px;" /></div>\n\
                                                </div>\n\
                                            </div>\n\
                                            <div class="col-xs-3">\n\
                                                <div class="form-group">\n\
                                                  <label for="item_final_price">Show in instant report</label>\n\
                                                    <div class="inner-addon"><input id="i_report" name="i_report" type="checkbox" value="0" style="width:20px; height:20px;" /></div>\n\
                                                </div>\n\
                                            </div>\n\
                                            <div class="col-xs-3">\n\
                                                <div class="form-group">\n\
                                                    <label for="official_or_not">Off. or not</label>\n\
                                                    <div class="inner-addon"><input id="official_or_not" name="official_or_not" type="checkbox" value="0" style="width:20px; height:20px;" /></div>\n\
                                                </div>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="row">\n\
                                            <div class="col-lg-12">\n\
                                                <div class="form-group">\n\
                                                  <label for="image_link">Image Link</label>\n\
                                                    <div class="inner-addon"><input id="image_link" value="" name="image_link" type="text" class="form-control" placeholder="" /></div>\n\
                                                </div>\n\
                                            </div>\n\
                                        </div>\n\
                                        </div>\n\
                                    <div class="tab-pane" id="4a">\n\
                                        <div class="row">\n\
                                            <div class="col-xs-6">\n\
                                                <div class="form-group">\n\
                                                    <label for="item_final_price">Item</label>\n\
                                                    <input type="hidden" value="'+composite_items_info[0].item_id+'" name="composite_item_id" id="composite_item_id" />\n\
                                                    <input autocomplete="off" onkeyup="composite_item_changed(this)" id="composite_item" value="'+composite_items_info[0].description+'" name="composite_item" type="text" class="form-control" placeholder=""/>\n\
                                                </div>\n\
                                            </div>\n\
                                            <div class="col-xs-2">\n\
                                                <div class="form-group">\n\
                                                    <label for="item_final_price">Quantity</label>\n\
                                                    <input id="composite_item_qty" value="'+composite_items_info[0].qty+'" name="composite_item_qty" type="text" class="form-control item_pc" placeholder=""/>\n\
                                                </div>\n\
                                            </div>\n\
                                            <div class="col-xs-2">\n\
                                                <div class="form-group">\n\
                                                    <label for="item_final_price">Packs</label>\n\
                                                    <div class="inner-addon"><input id="is_pack" name="is_pack" type="checkbox" style="width:30px; height:30px;" /></div>\n\
                                                </div>\n\
                                            </div>\n\
                                        </div>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="modal-footer">\n\
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\n\
                                <a onclick="$(this).closest(\'form\').submit()" type="submit" class="btn btn-primary">'+submit_btn_name+'</a>\n\
                            </div>\n\
                            <form/>\n\
                        </div>\n\
                    </div>\n\
                </div>';

                //$('#newItem').remove();
                //$('#editItem').remove();
                
                $('#newItem').modal("hide");
                $('#editItem').modal("hide");
                
                $('body').append(content);
                
                $('.selectpicker').selectpicker();
                $('#item_vat').selectpicker('val', item_vat);
                
                $('#editItem').on('hidden.bs.modal', function (e) {
                    $('#newItem').remove();
                    $('#editItem').remove();
                });
                
                $('#editItem').on('shown.bs.modal', function (e) {
                    //$("#item_desc").focus();
                    
                    var SearchInput = $('#item_desc');
                    SearchInput.val(SearchInput.val());
                    var strLength= SearchInput.val().length;
                    SearchInput.focus();
                    SearchInput[0].setSelectionRange(strLength, strLength);

                    if(v_access==1){
                        $('#v_access').prop("checked",true);
                    }
                    if(instant_report==1){
                        $('#i_report').prop("checked",true);
                    }
                    
                    
                    
                    //show_on_pos
           
                    if(is_official==1){
                        $('#official_or_not').prop("checked",true);
                    }
                    
                    $('#image_link').val(image_link);
                    
                  
                    if(is_pack==1){
                        $('#is_pack').prop("checked",true);
                    }
                    
                  
                    $("#convert_to_usd_input").val(precisionRound(current_rate*item_selling_price,-2));

                    //$('#cp2').colorpicker({horizontal: true,color : color_id,format:'hex'});
                    $('#cp2').colorpicker({
                        format:'hex',
                        color : color_id,
                        horizontal: true,
                        colorSelectors: {
                            'White': '#FFFFFF',
                            'Silver': '#C0C0C0',
                            'Gray': '#808080',
                            'Black': '#000000',
                            'Red': '#FF0000',
                            'Maroon': '#800000',
                            'Yellow': '#FFFF00',
                            'Olive': '#808000',
                            'Lime': '#00FF00',
                            'Green': '#008000',
                            'Aqua': '#00FFFF',
                            'Teal': '#008080',
                            'Blue':'#0000FF',
                            'Navy':'#000080',
                            'Fuchsia':'#FF00FF',
                            'Purple':'#800080	'
                        }});
                    
                    
                    if(is_compo==1){
                        type_ahead_items("?r=items&f=getitems_for_type_head","composite_item");
                        
                         //$('#tab_composite_item').addClass('disabled');
                        //$('#tab_composite_item a').removeAttr("data-toggle");
                        
                    }else{
                        type_ahead_items("?r=items&f=getitems_for_type_head","composite_item");
                       // $('#tab_composite_item').addClass('disabled');
                        //$('#tab_composite_item a').removeAttr("data-toggle");
                    }
                   
                    $("#item_cat_p").selectpicker('val', current_parent);
                 
                    $("#item_vat_on_sale").selectpicker('val', vat_on_sale);
                    
                    $("#fixed_price").selectpicker('val', fixed_price);
                    $("#fixed_price_val").val(fixed_price_value);
                    
                    
                    if(vat_on_sale==1){
                        $("#item_final_price").val($("#item_final_price").val()*vatValue);
                    }
                    
                    
                    //$("#item_cat").selectpicker('val', current_cat);
                    parent_cat_changed(current_cat,0);
                    
                    if(usd_but_show_lbp_priority==1){
                        $(".lbpprio").show();
                    }else{
                        $(".lbpprio").hide();
                    }
            
                    cleaves_class(".item_pc",5);
                    $(".sk-circle-layer").hide();
                });
                
                
                
                
                
                //$(".only_numeric").numeric();
                $(".only_numeric").attr("autocomplete", "off");
                submitEditItem(duplicate);
                
                
                //$('#editItem').modal('toggle');
                $('#editItem').modal({
                    backdrop: 'static',
                });
                
        });
    });
}

function addParentCategory(action){
    var tt="Update category";
    if(action=="add"){
        tt="Add new category";
    }
    
    $(".sk-circle-layer").show();
    var content =
        '<div class="modal" data-keyboard="false" data-backdrop="static" id="add_new_parent_cat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <form id="add_new_parent_form" action="" method="post" enctype="multipart/form-data" >\n\
                <input id="id_to_edit" name="id_to_edit" type="hidden" value="0" />\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title" id="exampleModalLongTitle"><i class="glyphicon glyphicon-plus"></i>&nbsp;'+tt+'</h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="form-group">\n\
                        <div class="inner-addon"><input autocomplete="off" id="subcat_desc" name="subcat_desc" type="text" class="form-control" placeholder="Category name" aria-describedby="basic-addon1"></div>\n\
                    </div>\n\
                </div>\n\
                <div class="modal-footer">\n\
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\n\
                    <button id="action_btn" type="submit" class="btn btn-primary">Add</button>\n\
                </div>\n\
                <form/>\n\
            </div>\n\
        </div>\n\
    </div>';

    $('#add_new_parent_cat').remove();
    $('body').append(content);
    submitParentCategory(action);
    
    
    $('#add_new_parent_cat').on('show.bs.modal', function (e) {
    });
    
    $('#add_new_parent_cat').on('shown.bs.modal', function (e) {
        $("#subcat_desc").focus();
        $(".sk-circle-layer").hide();
    });
    
    
    
    $('#add_new_parent_cat').on('hide.bs.modal', function (e) {
        $('#add_new_parent_cat').remove();
    });
    
    $('#add_new_parent_cat').modal('show');
}

function submitParentCategory(action) {
    $("#add_new_parent_form").on('submit', (function (e) {
        e.preventDefault();
        if (!emptyInput("subcat_desc")) {
            $(".sk-circle-layer").show();
            $.ajax({
                url: "?r=categories&f=add_new_parent_category",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function (data)
                {
                    if(data.exist>0){
                        $(".sk-circle-layer").hide();
                        
                        $.alert({
                            title: 'Alert!',
                            content: 'This category name already exists!',
                            animation: 'zoom',
                            closeAnimation: 'zoom',
                            animateFromElement:false,
                        });

                        return;
                    }
                    
                    if(action == "otherPage"){
                        $("#item_cat").append("<option value='"+data.id+"'>"+data.cat_desc+"</option>");
                        $("#item_cat").selectpicker('refresh');
                        $("#item_cat").selectpicker('val', data.id);
                        $('#add_new_cat').modal('hide');
                        $(".sk-circle-layer").hide();
                    }else if(action == "otherPage_P"){
                        $("#item_cat_p").append("<option value='"+data.id+"'>"+data.cat_desc+"</option>");
                        $("#item_cat_p").selectpicker('refresh');
                        $("#item_cat_p").selectpicker('val', data.id);
                        $('#add_new_parent_cat').modal('hide');
                        parent_cat_changed(0,0);
                        $(".sk-circle-layer").hide();
                    }else{
                        $('#add_new_parent_cat').modal('hide');
                        var table = $('#parent_categories_table').DataTable();
                        table.ajax.url('?r=categories&f=getAllParentCategories').load(function () {
                            if(action == "up"){
                                table.row('.' + pad_parentcat(data.id), {page: 'current'}).select();
                            }else{
                                table.page('last').draw(false);
                                table.row(':last', {page: 'current'}).select();
                                
                                var sdata = table.row('.selected', 0).data();
                                update_categories(parseInt(sdata[0].split('-')[1]));
                            } 
                            $(".sk-circle-layer").hide();
                        },false);
                       
                    } 
                }
            });
        }
    }));
}


function addMaterial(){
    var content =
        '<div class="modal" data-backdrop="static" data-keyboard="false" id="add_new_material">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <form id="add_new_material_form" action="" method="post" enctype="multipart/form-data" >\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title"><i class="glyphicon glyphicon-plus"></i>&nbsp;Add Material</h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="form-group">\n\
                            <label for="material_name">Material name</label>\n\
                            <div class="inner-addon"><input id="material_name" name="material_name" type="text" class="form-control" placeholder="Material name"></div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-footer">\n\
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\n\
                        <a onclick="$(this).closest(\'form\').submit()" id="action_btn" type="submit" class="btn btn-primary">Add</a>\n\
                    </div>\n\
                <form/>\n\
            </div>\n\
        </div>\n\
    </div>';
    $('#add_new_size').remove();
    $('body').append(content);
    
    submitMaterial();
    
    $('#add_new_material').on('show.bs.modal', function (e) {
        
    });

    $('#add_new_material').on('shown.bs.modal', function (e) {
        //$("#color_name").focus();
    });

    $('#add_new_material').on('hide.bs.modal', function (e) {
        $('#add_new_material').remove();
    });

    $('#add_new_material').modal('show');
}

function submitMaterial() {
    $("#add_new_material_form").on('submit', (function (e) {
        e.preventDefault();
        if (!emptyInput("material_name")) {
            $(".sk-circle-layer").show();
            $.ajax({
                url: "?r=material&f=add_new_material",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function (data)
                {
                    $("#material_id").append("<option value='"+data.id+"'>"+data.material_name+"</option>");
                    $("#material_id").selectpicker('refresh');
                    $("#material_id").selectpicker('val', data.id);
                    $('#add_new_material').modal('hide');
                    $(".sk-circle-layer").hide();
                }
            });
        }
    }));
}


function addUnit(){
    var content =
        '<div class="modal" data-backdrop="static" data-keyboard="false" id="add_new_unit_modal">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <form id="add_new_unit_form" action="" method="post" enctype="multipart/form-data" >\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title"><i class="glyphicon glyphicon-plus"></i>&nbsp;Add Unit</h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="form-group">\n\
                            <label for="color_name">Unit measure</label>\n\
                            <div class="inner-addon"><input id="unit_name" name="unit_name" type="text" class="form-control" placeholder="Unit name"></div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-footer">\n\
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\n\
                        <a onclick="$(this).closest(\'form\').submit()" id="action_btn" type="submit" class="btn btn-primary">Add</a>\n\
                    </div>\n\
                <form/>\n\
            </div>\n\
        </div>\n\
    </div>';
    $('#add_new_unit_modal').remove();
    $('body').append(content);
    
    submitUnit();
    
    $('#add_new_unit_modal').on('show.bs.modal', function (e) {
        
    });

    $('#add_new_unit_modal').on('shown.bs.modal', function (e) {
        //$("#color_name").focus();
    });

    $('#add_new_unit_modal').on('hide.bs.modal', function (e) {
        $('#add_new_unit_modal').remove();
    });

    $('#add_new_unit_modal').modal('show');
}

function submitUnit() {
    $("#add_new_unit_form").on('submit', (function (e) {
        e.preventDefault();
        if (!emptyInput("unit_name")) {
            $(".sk-circle-layer").show();
            $.ajax({
                url: "?r=measure&f=add_new_unit",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function (data)
                {
                    $("#item_unit_measure").append("<option value='"+data.id+"'>"+data.unit_name+"</option>");
                    $("#item_unit_measure").selectpicker('refresh');
                    $("#item_unit_measure").selectpicker('val', data.id);
                    $('#add_new_unit_modal').modal('hide');
                    $(".sk-circle-layer").hide();
                }
            });
        }
    }));
}


function addSize(){
    var content =
        '<div class="modal" data-backdrop="static" data-keyboard="false" id="add_new_size">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <form id="add_new_size_form" action="" method="post" enctype="multipart/form-data" >\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title"><i class="glyphicon glyphicon-plus"></i>&nbsp;Add Size</h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="form-group">\n\
                            <label for="color_name">Size name</label>\n\
                            <div class="inner-addon"><input id="size_name" name="size_name" type="text" class="form-control" placeholder="Color name"></div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-footer">\n\
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\n\
                        <a onclick="$(this).closest(\'form\').submit()" id="action_btn" type="submit" class="btn btn-primary">Add</a>\n\
                    </div>\n\
                <form/>\n\
            </div>\n\
        </div>\n\
    </div>';
    $('#add_new_size').remove();
    $('body').append(content);
    
    submitSize();
    
    $('#add_new_size').on('show.bs.modal', function (e) {
        
    });

    $('#add_new_size').on('shown.bs.modal', function (e) {
        //$("#color_name").focus();
    });

    $('#add_new_size').on('hide.bs.modal', function (e) {
        $('#add_new_size').remove();
    });

    $('#add_new_size').modal('show');
}

function submitSize() {
    $("#add_new_size_form").on('submit', (function (e) {
        e.preventDefault();
        if (!emptyInput("size_name")) {
            $(".sk-circle-layer").show();
            $.ajax({
                url: "?r=size&f=add_new_size",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function (data)
                {
                    $("#item_size").append("<option value='"+data.id+"'>"+data.size_name+"</option>");
                    $("#item_size").selectpicker('refresh');
                    $("#item_size").selectpicker('val', data.id);
                    $('#add_new_size').modal('hide');
                    $(".sk-circle-layer").hide();
                }
            });
        }
    }));
}

function addColor(){
    var content =
        '<div class="modal" data-backdrop="static" data-keyboard="false" id="add_new_color">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <form id="add_new_color_form" action="" method="post" enctype="multipart/form-data" >\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title"><i class="glyphicon glyphicon-plus"></i>&nbsp;Add Color</h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="form-group">\n\
                            <label for="color_name">Color name</label>\n\
                            <div class="inner-addon"><input id="color_name" name="color_name" type="text" class="form-control" placeholder="Color name"></div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-footer">\n\
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\n\
                        <a onclick="$(this).closest(\'form\').submit()" id="action_btn" type="submit" class="btn btn-primary">Add</a>\n\
                    </div>\n\
                <form/>\n\
            </div>\n\
        </div>\n\
    </div>';
    $('#add_new_color').remove();
    $('body').append(content);
    
    submitColor();
    
    $('#add_new_color').on('show.bs.modal', function (e) {
        
    });

    $('#add_new_color').on('shown.bs.modal', function (e) {
        //$("#color_name").focus();
    });

    $('#add_new_color').on('hide.bs.modal', function (e) {
        $('#add_new_color').remove();
    });

    $('#add_new_color').modal('show');
}

function submitColor() {
    $("#add_new_color_form").on('submit', (function (e) {
        e.preventDefault();
        if (!emptyInput("color_name")) {
            $(".sk-circle-layer").show();
            $.ajax({
                url: "?r=color&f=add_new_color",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function (data)
                {
                    $("#item_text_color").append("<option value='"+data.id+"'>"+data.color_name+"</option>");
                    $("#item_text_color").selectpicker('refresh');
                    $("#item_text_color").selectpicker('val', data.id);
                    $('#add_new_color').modal('hide');
                    $(".sk-circle-layer").hide();
                }
            });
        }
    }));
}
    
function addCategory(action,info) {

        $(".sk-circle-layer").show();
        var categories_parents = "";
        var current_selected_parent = -1;
        var dt = $('#parent_categories_table').DataTable();
        if(dt.row('.selected', 0).length>0){
            var id = dt.row('.selected', 0).data();
            current_selected_parent = parseInt(id[0].split("-")[1]);
        }
                  
        $.getJSON("?r=categories&f=getAllPCat", function (data) {
            $.each(data, function (key, val) {
                if (val.id == current_selected_parent) {
                    categories_parents += "<option selected value=" + val.id + ">" + val.name + "</option>";
                } else {
                    categories_parents += "<option value=" + val.id + ">" + val.name + "</option>";
                }
            });
        }).done(function () {
            var content =
                '<div class="modal" data-keyboard="false" data-backdrop="static" id="add_new_cat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">\n\
                <div class="modal-dialog" role="document">\n\
                    <div class="modal-content">\n\
                        <form id="add_new_cat_form" action="" method="post" enctype="multipart/form-data" >\n\
                        <input id="id_to_edit" name="id_to_edit" type="hidden" value="0" />\n\
                        <div class="modal-header"> \n\
                            <h3 class="modal-title" id="exampleModalLongTitle"><i class="glyphicon glyphicon-plus"></i>&nbsp;Add new sub-category</h3>\n\
                        </div>\n\
                        <div class="modal-body">\n\
                            <div class="form-group">\n\
                                <label for="parent_sq">Category</label>\n\
                                <select id="parent_sq" name="parent_cat_id" class="form-control" style="width:100%">' + categories_parents + '</select>\n\
                            </div>\n\
                            <div class="form-group">\n\
                                <label for="cat_desc">Sub-Category</label>\n\
                                <div class="inner-addon"><input id="cat_desc" name="cat_desc" type="text" class="form-control" placeholder="Sub-Category name"></div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="modal-footer">\n\
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\n\
                            <button id="action_btn" type="submit" class="btn btn-primary">Add</button>\n\
                        </div>\n\
                        <form/>\n\
                    </div>\n\
                </div>\n\
            </div>';

            $('#add_new_cat').remove();
            $('body').append(content);
            //$('#parent_sq').selectpicker();
            
            submitCategory(action);
            $(".sk-circle-layer").hide();
            
            $('#add_new_cat').on('show.bs.modal', function (e) {
                if(action=="up"){
                    $("#action_btn").html("Update");
                    $("#id_to_edit").val(info[0].id_int);
                    $("#cat_desc").val(info[0].description);
                }
                
                if(action=="otherPage"){
                    $("#parent_sq").selectpicker('val', $("#item_cat_p").val());
                }
                //$("#parent_cat_id").selectpicker('val', $("#item_cat_p").val());
            });
            
            $('#add_new_cat').on('shown.bs.modal', function (e) {
                $("#cat_desc").focus();
                $(".sk-circle-layer").hide();
            });

            $('#add_new_cat').on('hide.bs.modal', function (e) {
                $('#add_new_cat').remove();
            });

            $('#add_new_cat').modal('show');
    
        });
    }
    
    function submitCategory(action) {
        $("#add_new_cat_form").on('submit', (function (e) {
            e.preventDefault();
            if (!emptyInput("cat_desc")) {
                $(".sk-circle-layer").show();
                $.ajax({
                    url: "?r=categories&f=add_new_category",
                    type: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: 'json',
                    success: function (data)
                    {
                        if(data.exist>0){
                            $(".sk-circle-layer").hide();

                            $.alert({
                                title: 'Alert!',
                                content: 'A subcategory with this name already exists within this category!',
                                animation: 'zoom',
                                closeAnimation: 'zoom',
                                animateFromElement:false,
                            });

                            return;
                        }
                        if(action == "otherPage"){
                            $("#item_cat").append("<option value='"+data.id+"'>"+data.cat_desc+"</option>");
                            $("#item_cat").selectpicker('refresh');
                            $("#item_cat").selectpicker('val', data.id);
                            $('#add_new_cat').modal('hide');
                            $(".sk-circle-layer").hide();
                        }else{
                            $('#add_new_cat').modal('hide');
                            
                            var table = $('#categories_table').DataTable();
                            var table_parent = $('#parent_categories_table').DataTable();
                            
                            var current_selected_parent = 1;
                            if(table_parent.row('.selected', 0).length>0){
                                var id = table_parent.row('.selected', 0).data();
                                current_selected_parent = parseInt(id[0].split("-")[1]);
                            }
                            
                            table.ajax.url('?r=categories&f=getAllCategoriesByParent&p0='+current_selected_parent+'&p1='+current_store_id).load(function () {
                                if(action == "up"){
                                    table.row('.' + pad_cat(data.id), {page: 'current'}).select();
                                }else{
                                    table.page('last').draw(false);
                                    table.row(':last', {page: 'current'}).select();
                                } 
                                $(".sk-circle-layer").hide();
                            },false);
                            
                            table_parent.ajax.url('?r=categories&f=getAllParentCategories').load(function () {
                                table_parent.row('.' + pad_parentcat(current_selected_parent), {page: 'current'}).select();
                            },false);
                        } 
                    }
                });
            }
        }));
    }
    
    function editParentCategory(id){
        $(".sk-circle-layer").show();
        var id_int = parseInt(id.split('-')[1]);
        //$(".sk-circle-layer").show();
        var description = null;
        $.getJSON("?r=categories&f=get_parent_category&p0=" + id_int, function (data) {
            description=data[0].name;
        }).done(function () {
            addParentCategory('up');
            $("#action_btn").text('Update');
            $("#id_to_edit").val(id_int);
            $("#subcat_desc").val(description);
            $(".sk-circle-layer").hide();
        });
    }
    
    function editParentCategory_new(id_int){
        $(".sk-circle-layer").show();
        //$(".sk-circle-layer").show();
        var description = null;
        $.getJSON("?r=categories&f=get_parent_category&p0=" + id_int, function (data) {
            description=data[0].name;
        }).done(function () {
            addParentCategory('up');
            $("#action_btn").text('Update');
            $("#id_to_edit").val(id_int);
            $("#subcat_desc").val(description);
            $(".sk-circle-layer").hide();
        });
    }
    
    function editCategory_new(id_int){
        $(".sk-circle-layer").show();
        var description = null;
        $.getJSON("?r=categories&f=get_category&p0=" + id_int, function (data) {
            description=data[0].description;
        }).done(function () {
            var info = [];
            info.push({id_int:id_int,description:description});
            addCategory('up',info);
            
            //$("#id_to_edit").val(id_int);
            //$("#cat_desc").val(description);
            //$(".sk-circle-layer").hide();
        });
    }
    
    function editCategory(id){
        var id_int = parseInt(id.split('-')[1]);
        $(".sk-circle-layer").show();
        var description = null;
        $.getJSON("?r=categories&f=get_category&p0=" + id_int, function (data) {
            description=data[0].description;
        }).done(function () {
            var info = [];
            info.push({id_int:id_int,description:description});
            addCategory('up',info);
            
            //$("#id_to_edit").val(id_int);
            //$("#cat_desc").val(description);
            $(".sk-circle-layer").hide();
        });
    }

    function submitEditItem(duplicate){
        $("#add_new_item").on('submit', (function (e) {
        e.preventDefault();

        if (!emptyInput("item_desc")) {
            $(".sk-circle-layer").show();
            $.ajax({
                url: "?r=items&f=add_new_item",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function (data)
                {
                    var edited_item = $("#id_to_edit").val();
                    $('#editItem').modal('hide');
                    var table = $('#items_table').DataTable();
                    
                    if(duplicate==1){
                        if($("#items_by_cat_table_details").length>0){
                            var table_details = $("#items_by_cat_table_details").DataTable();
                            
                            var dt = $('#categories_table').DataTable();
                            var sdata_category = dt.row('.selected', 0).data();

                            var dt_ = $('#parent_categories_table').DataTable();
                            var sdata_parent = dt_.row('.selected', 0).data();
                            
                            
                            table_details.ajax.url("?r=items&f=get_all_items_of_sub_category&p0="+parseInt(sdata_category[0].split('-')[1])+"&p1="+current_store_id).load(function () {
                                table_details.page('last').draw(false);
                                table_details.row(':last', {page: 'current'}).select();
                                 $(".sk-circle-layer").hide();
                            });
                            
                      
                            dt.ajax.url('?r=categories&f=getAllCategoriesByParent&p0='+parseInt(sdata_parent[0].split('-')[1])+'&p1='+current_store_id).load(function () {
                                dt.row('.' + sdata_category[0], {page: 'current'}).select();
                                $(".sk-circle-layer").hide();
                            },false); 
                        }else if($("#receive_stockModal").length>0){
                            updateAllItems(data.id);
                            $(".sk-circle-layer").hide();
                        }else{
                            table.ajax.url('?r=items&f=getAllItems&p0='+current_store_id+"&p1="+current_category_id+"&p2="+current_subcategory_id+"&p3="+item_boxex+"&p4="+current_supplier_id+"&p5="+$("#stock_status").val()).load(function () {
                                table.page('last').draw(false);
                                table.row(':last', {page: 'current'}).select();
                                $(".dataTables_scrollBody").scrollTop($('.dataTables_scrollBody')[0].scrollHeight);

                                 $(".sk-circle-layer").hide();
                            });
                        }
                       
                    }else{
                        
                        if($("#items_table").length>0){
                            table.ajax.url('?r=items&f=getAllItems&p0='+current_store_id+"&p1="+current_category_id+"&p2="+current_subcategory_id+"&p3="+item_boxex+"&p4="+current_supplier_id+"&p5="+$("#stock_status").val()).load(function () {
                                
                                if($('#group_items_table').length==0){
                                    table.row('.' + padItem(data.id), {page: 'current'}).select();
                                }else{
                                    table.row('.' + padItem($("#item_id").val()), {page: 'current'}).select();
                                }
                                
                                $(".sk-circle-layer").hide();
                            }, false);
                        }else{
                            $(".sk-circle-layer").hide();
                        }  
                        
                        
                        if($('#group_items_table').length>0){
                          
                            /*var table_g = $('#group_items_table').DataTable();
                            $(".sk-circle-layer").show();
                            $('#add_items_to_store').modal('hide');
                            table_g.ajax.url("?r=items&f=get_group&p0="+$("#item_id").val()).load(function () {
                               
                                $('#group_items_table .' + padItem(data.id)).addClass("selected");
                                $(".sk-circle-layer").hide();
                            },false);*/
                        }else{
                            $(".sk-circle-layer").hide();
                        }
                        
                        //??????? refresh group table
                    }
                    
                    
                    if($("#showItemsBySubCat").length>0){
                        var table_details = $("#items_by_cat_table_details").DataTable();
                            
                        var dt = $('#categories_table').DataTable();
                        var sdata_category = dt.row('.selected', 0).data();

                        table_details.ajax.url("?r=items&f=get_all_items_of_sub_category&p0="+parseInt(sdata_category[0].split('-')[1])+"&p1="+current_store_id).load(function () {
                            //$("#items_by_cat_table_details ."+padItem(edited_item, 5)).addClass('selected');
                            table_details.row('.' + padItem(edited_item), {page: 'current'}).select();
                            $(".sk-circle-layer").hide();
                        },false);
                    }
                    
                    
                    
                }
            });
        }else{
            $('#exTab1 a[href="#1a"]').tab('show');
        }
    }));
}

function show_complex(item_id){
    alert(item_id);
    var content =
    '<div class="modal" data-backdrop="static" id="show_complex_modal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title">Complex Item<i style="float:right;font-size:30px; cursor:pointer" class="glyphicon glyphicon-remove" onclick="show_complex_modal()"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="row">\n\
                        <div class="col-xs-12">\n\
                            <table id="show_complex_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                <thead>\n\
                                    <tr>\n\
                                        <th style="width:100px;">Item</th><th>Description</th><th style="width:100px;">Quantity</th><th style="width:100px;">Cost/u</th><th style="width:100px;">VAT</th><th style="width:100px;">Discount %</th><th style="width:100px;">Total Cost</th>\n\
                                    </tr>\n\
                                </thead>\n\
                                <tfoot><tr><th>Item</th><th>Description</th><th>Quantity</th><th>Cost/u</th><th>VAT</th><th>Discount %</th><th>Total Cost</th></tr></tfoot>\n\
                            </table>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>\n\
    </div>';
    $("#show_complex_modal").remove();
    $("body").append(content);

    $("#show_complex_modal").centerWH();

    $('#show_complex_modal').on('show.bs.modal', function (e) {

    });
    $('#show_complex_modal').on('shown.bs.modal', function (e) {
            var search_fields = [0,1,2,3,4,5,6];
            var index = 0;
            $('#show_complex_table tfoot th').each( function () {
                if(jQuery.inArray(index, search_fields) !== -1){
                    var title = $(this).text();
                    $(this).html( '<div class="inner-addon left-addon"><input style="width: 100% !important;" class="form-control input-sm" type="text" placeholder=" '+title+'" /></div>' );
                    index++;
                }
            });
            var table = $('#show_complex_table').DataTable({
                //ajax: "?r=stock&f=get_purchase_invoice_by_id&p0="+po_id,
                paging: true,
                select: true,
                ordering: false,
                bPaginate: false,
                bLengthChange: false,
                bFilter: true,
                bInfo: false,
                bAutoWidth: false
            });
    });

    $('#show_complex_modal').on('hide.bs.modal', function (e) {
        $('#show_complex_modal').remove();
    });
    $('#show_complex_modal').modal('show');
}

function submitNewItem(source) {
    $("#add_new_item").on('submit', (function (e) {
        e.preventDefault();

        if (!emptyInput("item_desc")) {
            $(".sk-circle-layer").show();
            $.ajax({
                url: "?r=items&f=add_new_item",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function (data)
                {
                    if(source=="add_item"){
                        $('#newItem').modal('hide');
                        var table = $('#items_table').DataTable();
                        table.ajax.url('?r=items&f=getAllItems&p0='+current_store_id+"&p1="+current_category_id+"&p2="+current_subcategory_id+"&p3="+item_boxex+"&p4="+current_supplier_id+"&p5="+$("#stock_status").val()).load(function () {
                            table.page('last').draw(false);
                            table.row(':last', {page: 'current'}).select();
                            
                            $(".dataTables_scrollBody").scrollTop($('.dataTables_scrollBody')[0].scrollHeight);
                            $(".sk-circle-layer").hide();
                        });
                    }else if(source=="receive_stock"){
                        $('#newItem').modal('hide');
                        $(".sk-circle-layer").hide();
                        updateAllItems(data.id);
                    }else if(source=="categories"){
                        $('#newItem').modal('hide');
                        $(".sk-circle-layer").hide();
                        
                        var dt = $('#categories_table').DataTable();
                        var sdata_category = dt.row('.selected', 0).data();
                        
                        var dt_ = $('#parent_categories_table').DataTable();
                         var sdata_parent = dt_.row('.selected', 0).data();
                            
                        dt.ajax.url('?r=categories&f=getAllCategoriesByParent&p0='+parseInt(sdata_parent[0].split('-')[1])+'&p1='+current_store_id).load(function () {
                            dt.row('.' + sdata_category[0], {page: 'current'}).select();
                            $(".sk-circle-layer").hide();
                        },false);
                            
                        setTimeout(function(){
                            //add_items_to_store(data.id,current_store_id,"from_categories");
                        },500);
                        
                    }
                }
            });
        }else{
            $('#exTab1 a[href="#1a"]').tab('show');
        }
    }));
}

function vat_change() {
    if ($("#item_vat").val() == 1 && $("#item_cost").val() != "" && parseFloat($("#item_cost").val()) > 0) {
        $("#item_final_cost").val((parseFloat($("#item_cost").val().replace(/,/g , '')) * vatValue));
    }

    if ($("#item_vat").val() == 0 && $("#item_cost").val() != "" && parseFloat($("#item_cost").val()) > 0) {
        $("#item_final_cost").val(parseFloat($("#item_cost").val().replace(/,/g , '')));
    }
    
    cleaves_class(".item_pc",5);
}

function change_discount() {
    if ($("#item_disc").val().replace(/,/g , '') > 100)
        $("#item_disc").val(100);

    if ($("#item_disc").val().replace(/,/g , '') == "" || $("#item_disc").val().replace(/,/g , '') == null) {
        $("#item_final_price").val($("#selling_price").val().replace(/,/g , ''));
    } else {
        if ($("#selling_price").val().replace(/,/g , '') >= 0 && $("#item_disc").val().replace(/,/g , '') >= 0) {
            $("#item_final_price").val(($("#selling_price").val().replace(/,/g , '') * (1 - $("#item_disc").val().replace(/,/g , '') / 100)));
        }
    }

    if($("#item_vat_on_sale").val().replace(/,/g , '')==1){
        $("#item_final_price").val(decimal_round($("#item_final_price").val().replace(/,/g , '')*vatValue,2));
    }
    
    cleaves_class(".item_pc",5);

}

function vat_on_sale_change(){
    change_selling_price();
}

function change_final_cost() {
    
    if ($("#item_vat").val().replace(/,/g , '') == 1 && $("#item_final_cost").val().replace(/,/g , '') != "" && parseFloat($("#item_final_cost").val().replace(/,/g , '')) > 0) {
        $("#item_cost").val((parseFloat($("#item_final_cost").val().replace(/,/g , '')) / vatValue));
    }

    if ($("#item_vat").val().replace(/,/g , '') == 0 && $("#item_final_cost").val().replace(/,/g , '') != "" && parseFloat($("#item_final_cost").val().replace(/,/g , '')) > 0) {
        $("#item_cost").val(parseFloat($("#item_final_cost").val().replace(/,/g , '')));
    }
    
    cleaves_class(".item_pc",5);
}

function item_final_price_Changed(){
    //if(ptype!="1"){
        if (parseFloat($("#item_final_price").val().replace(/,/g , '')) < 0) {
            $("#item_final_price").val(Math.abs(parseFloat($("#item_final_price").val().replace(/,/g , ''))));
        }
        if ($("#item_final_price").val().replace(/,/g , '') == "" || $("#item_final_price").val().replace(/,/g , '') == null) {
            $("#selling_price").val(0);
        } else{
            if(parseFloat($("#selling_price").val().replace(/,/g , '')) > 0){
                var disc = 100*$("#item_final_price").val().replace(/,/g , '')/$("#selling_price").val().replace(/,/g , '');
                $("#item_disc").val(100-disc);
            }
        }
    //}else{
        //$("#selling_price").val(parseFloat($("#item_final_price").val().replace(/,/g , '')));
        
    //}
    
    cleaves_class(".item_pc",5);
}

function change_selling_price() {
    
    if (parseFloat($("#selling_price").val().replace(/,/g , '')) < 0) {
        $("#selling_price").val(Math.abs(parseInt($("#selling_price").val().replace(/,/g , ''))));
    }
    
    if (parseFloat($("#selling_price").val().replace(/,/g , '')) == 0) {
        $("#item_disc").val(0);
    }

    if ($("#selling_price").val().replace(/,/g , '') == "" || $("#selling_price").val().replace(/,/g , '') == null) {
        $("#item_final_price").val(0);
    } else {
        if (parseFloat($("#item_disc").val().replace(/,/g , '')) > 0) {
            $("#item_final_price").val((parseFloat($("#selling_price").val().replace(/,/g , '')) * (1 - parseFloat($("#item_disc").val().replace(/,/g , '')) / 100)));
        } else {
            $("#item_final_price").val(($("#selling_price").val().replace(/,/g , '')));
        }
    }
    
    if($("#item_vat_on_sale").val().replace(/,/g , '')==1){
        $("#item_final_price").val($("#item_final_price").val().replace(/,/g , '')*vatValue);
    }
    cleaves_class(".item_pc",5);
    
    fixed_price_changed();
}

function change_cost() {
    //if ($("#item_cost").val().replace(/,/g , '') < 0) {
        //$("#item_cost").val(Math.abs($("#item_cost").val().replace(/,/g , '')));
    //}
    if ($("#item_cost").val().replace(/,/g , '') == "" || $("#item_cost").val().replace(/,/g , '') == null) {
        $("#item_final_cost").val(0);
    } else {
        if ($("#item_vat").val() == 0) {
            $("#item_final_cost").val($("#item_cost").val().replace(/,/g , ''));
        } else {
            $("#item_final_cost").val($("#item_cost").val().replace(/,/g , '') * vatValue);
        }
    }
    cleaves_class(".item_pc",5);
}

var addSupplier_lock = false;
function addSupplier(page) {
    if(addSupplier_lock){
        return;
    }
    
    var hide_st_balance="";
    var hide_complex_st_balance=";display:block;";
     var hide_st_default_balance=";display:block;";
    
    var hide_complex_st_balance_lbp_section="";
    
    addSupplier_lock = true;
    var countries = "";
    $.getJSON("?r=countries&f=getCountries", function (data) {
        var selected = "";
        $.each(data.countries, function (key, val) {
            selected = "";
            if (val.default_selection == 1) {
                selected = "selected";
            }
            countries += "<option " + selected + " value=" + val.id + ">" + val.country_name + "</option>";
        });
        
        
        if (data.currency_counnt == 1 && data.default_currency==1) {
            hide_complex_st_balance_lbp_section=";display:none;";     
        }
        
        //hide_complex_st_balance_lbp_section
        
        if( (typeof omt_version !== 'undefined' && omt_version==1) || data.suppliers_complex_stmt==0){
                hide_complex_st_balance=";display:none;";     
        }
         
        if( typeof omt_version !== 'undefined' && omt_version==0){
            hide_st_balance=";display:none;";
        }
        
        if( data.suppliers_complex_stmt==1){
                hide_st_default_balance=";display:none;";   
        }  
    
    }).done(function () {
        var content =
                '<div class="modal" data-backdrop="static" data-keyboard="false" id="newSupplier" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <form id="add_new_supplier" action="" method="post" enctype="multipart/form-data" >\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title" id="exampleModalLongTitle"><i class="glyphicon glyphicon-plus"></i>&nbsp;Add new supplier</h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-xs-6">\n\
                                <div class="form-group">\n\
                                    <input required autocomplete="off" id="sup_name" name="sup_name" type="text" class="form-control" placeholder="Supplier name">\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-xs-6">\n\
                                <div class="form-group">\n\
                                    <input autocomplete="off" id="sup_contact" name="sup_contact" type="text" class="form-control" placeholder="Contact name" />\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-xs-6">\n\
                                <div class="form-group">\n\
                                    <select style="width:100%" data-live-search="true" id="sup_country" name="sup_country" class="form-control selectpicker">' + countries + '</select>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-xs-6">\n\
                                <div class="form-group">\n\
                                    <input autocomplete="off" id="sup_phone" name="sup_phone" type="text" class="form-control" placeholder="Phones" />\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-xs-12">\n\
                                <div class="form-group">\n\
                                    <input autocomplete="off" value="" id="sup_email" name="sup_email" type="text" class="form-control" placeholder="Email" aria-describedby="basic-addon1" />\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-xs-12">\n\
                                <div class="form-group">\n\
                                    <input autocomplete="off" id="sup_adr" name="sup_adr" type="text" class="form-control" placeholder="Address" aria-describedby="basic-addon1" />\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-xs-6" style="'+hide_st_default_balance+'">\n\
                                <div class="form-group">\n\
                                    <label for="item_final_price">Starting Balance</label>\n\
                                    <input id="sup_starting_balance" name="sup_starting_balance" type="text" value="0" class="form-control med_input cleaves5" placeholder="Starting Balance" />\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-xs-6" style="'+hide_st_balance+'">\n\
                                <div class="form-group">\n\
                                    <label for="deb_cred">&nbsp;</label>\n\
                                    <select style="width:100%" id="deb_cred" name="deb_cred" class="form-control selectpicker"><option value="1">Debit</option><option value="2">Credit</option></select>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row" style="'+hide_complex_st_balance_lbp_section+'">\n\
                            <div class="col-xs-6">\n\
                                <div class="form-group">\n\
                                    <label for="">Starting Balance LBP</label>\n\
                                    <input id="sup_starting_balance_lbp" name="sup_starting_balance_lbp" type="text" value="0" class="form-control med_input cleaves5" />\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-xs-6">\n\
                                <div class="form-group">\n\
                                    <label for="deb_cred_lbp">&nbsp;</label>\n\
                                    <select style="width:100%" id="deb_cred_lbp" name="deb_cred_lbp" class="form-control selectpicker"><option value="1">Debit</option><option value="2">Credit</option></select>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row" style="'+hide_complex_st_balance+'">\n\
                            <div class="col-xs-6">\n\
                                <div class="form-group">\n\
                                    <label for="">Starting Balance USD</label>\n\
                                    <input id="sup_starting_balance_usd" name="sup_starting_balance_usd" type="text" value="0" class="form-control med_input cleaves5" />\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-xs-6" >\n\
                                <div class="form-group">\n\
                                    <label for="deb_cred_lbp">&nbsp;</label>\n\
                                    <select style="width:100%" id="deb_cred_usd" name="deb_cred_usd" class="form-control selectpicker"><option value="1">Debit</option><option value="2">Credit</option></select>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-footer">\n\
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\n\
                        <button id="add_new_supplier_button" type="submit" class="btn btn-primary">Add</button>\n\
                    </div>\n\
                    <form/>\n\
                </div>\n\
            </div>\n\
        </div>';
        $('#newSupplier').remove();
        $('#editSupplier').remove();

        $('body').append(content);

        $(document.body).on('click', '.changeType', function () {
            $(this).closest('.phone-input').find('.type-text').text($(this).text());
            $(this).closest('.phone-input').find('.type-input').val($(this).data('type-value'));
        });
        $(document.body).on('click', '.btn-remove-phone', function () {
            $(this).closest('.phone-input').remove();
        });
        /*
        $('.btn-add-phone').click(function () {
            var index = $('.phone-input').length + 1;
            $('.phone-list').append('' +
                    '<div class="input-group phone-input">' +
                    '<span class="input-group-btn">' +
                    '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="type-text">Type</span> <span class="caret"></span></button>' +
                    '<ul class="dropdown-menu" role="menu">' +
                    '<li><a class="changeType" href="javascript:;" data-type-value="phone">Phone</a></li>' +
                    '<li><a class="changeType" href="javascript:;" data-type-value="fax">Fax</a></li>' +
                    '<li><a class="changeType" href="javascript:;" data-type-value="mobile">Mobile</a></li>' +
                    '</ul>' +
                    '</span>' +
                    '<input type="text" name="phone[' + index + '][number]" class="form-control" placeholder="+1 (999) 999 9999" />' +
                    '<input type="hidden" name="phone[' + index + '][type]" class="type-input" value="" />' +
                    '<span class="input-group-btn">' +
                    '<button class="btn btn-danger btn-remove-phone" type="button"><span class="glyphicon glyphicon-remove"></span></button>' +
                    '</span>' +
                    '</div>'
                    );
        });
        */
        $('.selectpicker').selectpicker();
        $(".only_numeric").numeric({ negative : false});
        submitNewSupplier(page);
 
 
        $('#newSupplier').on('show.bs.modal', function (e) {
     
        });

        $('#newSupplier').on('shown.bs.modal', function (e) {
            $("#sup_name").focus();
            cleaves_class(".cleaves5",5);
            getsup_name_for_type_head("sup_name");
        });
            
        $('#newSupplier').on('hidden.bs.modal', function (e) {
               addSupplier_lock = false;
               $('#newSupplier').remove();
        });
        
        $('#newSupplier').modal('toggle');
            
    });
}


function editSupplier(id_sup) {
    
    var hide_st_balance="";
    var hide_complex_st_balance=";display:block;";
     var hide_st_default_balance=";display:block;";
     
    if(omt_version==0){
        hide_st_balance=";display:none;";
    }
    var countries = "";
    $(".sk-circle-layer").show();
    $.getJSON("?r=countries&f=getCountries", function (data) {
        var selected = "";
        
        $.each(data.countries, function (key, val) {
            countries += "<option value=" + val.id + ">" + val.country_name + "</option>";
        });
        
        if( (typeof omt_version !== 'undefined' && omt_version==1) || data.suppliers_complex_stmt==0){
                hide_complex_st_balance=";display:none;";     
        }
         
        if( typeof omt_version !== 'undefined' && omt_version==0){
            hide_st_balance=";display:none;";
        }
        
        if( data.suppliers_complex_stmt==1){
                hide_st_default_balance=";display:none;";   
        }  
        
    }).done(function () {
        var id_int = parseInt(id_sup.split('-')[1]);
        var content = null;
        $.getJSON("?r=suppliers&f=get_supplier&p0=" + id_int, function (data) {
  
            content =
                    '<div class="modal " data-backdrop="static" data-keyboard="false" id="edit_supplier" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">\n\
                <div class="modal-dialog" role="document">\n\
                    <div class="modal-content">\n\
                        <form id="editSupplier" action="" method="post" enctype="multipart/form-data" >\n\
                            <input id="id_to_edit" name="id_to_edit" type="hidden" value="' + id_int + '" />\n\
                            <div class="modal-header"> \n\
                                <h3 class="modal-title" id="exampleModalLongTitle"><i class="glyphicon glyphicon-edit"></i>&nbsp;Edit supplier</h3>\n\
                            </div>\n\
                            <div class="modal-body">\n\
                                <div class="row">\n\
                                    <div class="col-xs-6">\n\
                                        <div class="form-group">\n\
                                            <input required autocomplete="off" id="sup_name" name="sup_name" type="text" class="form-control" placeholder="Supplier name" value="' + data[0].name + '" />\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-xs-6">\n\
                                        <div class="form-group">\n\
                                            <input autocomplete="off" id="sup_contact" name="sup_contact" type="text" class="form-control" placeholder="Contact name" value="' + data[0].contact_name + '" />\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="row">\n\
                                    <div class="col-xs-6">\n\
                                        <div class="form-group">\n\
                                            <select data-live-search="true" id="sup_country" name="sup_country" class="selectpicker form-control">' + countries + '</select>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-xs-6">\n\
                                        <div class="form-group">\n\
                                            <input autocomplete="off" id="sup_adr" name="sup_adr" type="text" class="form-control" placeholder="Address" value="' + data[0].address + '" />\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="row">\n\
                                    <div class="col-xs-12">\n\
                                        <div class="form-group">\n\
                                            <input autocomplete="off" value="' + data[0].email + '" id="sup_email" name="sup_email" type="text" class="form-control" placeholder="Email" />\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="row">\n\
                                    <div class="col-xs-12">\n\
                                        <div class="form-group">\n\
                                            <input autocomplete="off" value="' + data[0].phone + '" id="sup_phone" name="sup_phone" type="text" class="form-control" placeholder="Phones" />\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="row">\n\
                                    <div class="col-xs-6">\n\
                                        <div class="form-group" style="'+hide_st_default_balance+'">\n\
                                            <label for="item_final_price">Starting Balance</label>\n\
                                            <input id="sup_starting_balance" value="' + parseFloat(data[0].starting_balance) + '" name="sup_starting_balance" type="text" class="form-control med_input cleaves5" placeholder="Starting Balance" />\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-xs-6" style="'+hide_st_balance+'">\n\
                                        <div class="form-group">\n\
                                            <label for="deb_cred">&nbsp;</label>\n\
                                            <select style="width:100%" id="deb_cred" name="deb_cred" class="form-control selectpicker"><option value="1">Debit</option><option value="2">Credit</option></select>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="row"  style="display:none">\n\
                                    <div class="col-xs-6">\n\
                                        <div class="form-group">\n\
                                            <label for="">Starting Balance LBP</label>\n\
                                            <input id="sup_starting_balance_lbp" name="sup_starting_balance_lbp" type="text" value="0" class="form-control med_input cleaves2" />\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-xs-6">\n\
                                        <div class="form-group">\n\
                                            <label for="deb_cred_lbp">&nbsp;</label>\n\
                                            <select style="width:100%" id="deb_cred_lbp" name="deb_cred_lbp" class="form-control selectpicker"><option value="1">Debit</option><option value="2">Credit</option></select>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="row" style="'+hide_complex_st_balance+'">\n\
                                    <div class="col-xs-6">\n\
                                        <div class="form-group">\n\
                                            <label for="">Starting Balance USD</label>\n\
                                            <input id="sup_starting_balance_usd" name="sup_starting_balance_usd" type="text" value="0" class="form-control med_input cleaves2" />\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-xs-6" >\n\
                                        <div class="form-group">\n\
                                            <label for="deb_cred_lbp">&nbsp;</label>\n\
                                            <select style="width:100%" id="deb_cred_usd" name="deb_cred_usd" class="form-control selectpicker"><option value="1">Debit</option><option value="2">Credit</option></select>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="modal-footer">\n\
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\n\
                                <button id="update_supplier_button" type="submit" class="btn btn-primary">Update</button>\n\
                            </div>\n\
                        <form/>\n\
                    </div>\n\
                </div>\n\
            </div>';
            //$("div.id_100 select").val("val2");
            $('#newSupplier').remove();
            $('#edit_supplier').remove();
            $('body').append(content);

            $('.selectpicker').selectpicker();
            
           
            $('#sup_country').selectpicker('val', data[0].c_id);
            $('#sup_country').selectpicker('refresh');
            

            $('#deb_cred').selectpicker('val', data[0].debit_credit);
            $('#deb_cred').selectpicker('refresh');
            
            //alert(data[0].lbp_starting_balance);
            $('#sup_starting_balance_lbp').val(Math.abs(data[0].lbp_starting_balance));
            $('#sup_starting_balance_usd').val(Math.abs(data[0].usd_starting_balance));
            
            if(data[0].lbp_starting_balance<0){
                $('#deb_cred_lbp').selectpicker('val', 2);
            }
            
            if(data[0].usd_starting_balance<0){
                $('#deb_cred_usd').selectpicker('val', 2);
            }

            updateSupplier();
            
            //$(".only_numeric").numeric({ negative : false});
            
            
            $('#edit_supplier').on('show.bs.modal', function (e) {

            });

            $('#edit_supplier').on('shown.bs.modal', function (e) {
                var SearchInput = $('#sup_name');
                SearchInput.val(SearchInput.val());
                var strLength= SearchInput.val().length;
                SearchInput.focus();
                SearchInput[0].setSelectionRange(strLength, strLength);
                
                
                cleaves_class(".cleaves2",2);
                
                $(".sk-circle-layer").hide();
            });

            $('#edit_supplier').on('hidden.bs.modal', function (e) {
                $('#edit_supplier').remove();
            });

            $('#edit_supplier').modal('toggle');
            
        }).done(function (content) {

        });

    });
}


function showDetails(id_sup) {
    var id_int = parseInt(id_sup.split('-')[1]);
    $.getJSON("?r=suppliers&f=get_supplier_details&p0=" + id_int, function (data) {

        var ct = "";
        $.each(data.contacts, function (key, val) {
            ct += "<li class='list-group-item'><i class='glyphicon glyphicon-phone'></i> <b>" + val.pt + "</b>: " + val.pn + "</li>";
        });
        var content =
                '<div class="modal fade" data-keyboard="false" id="sup_details" tabindex="-1" role="dialog" aria-labelledby="title_details" aria-hidden="true">\n\
                <div class="modal-dialog" role="document">\n\
                    <div class="modal-content">\n\
                            <div class="modal-header"> \n\
                                <h5 class="modal-title" id="title_details">' + data.sup.contact_name + ' </h5>\n\
                            </div>\n\
                            <div class="modal-body">\n\
                                <ul class="list-group">' + ct + '</ul>\n\
                            </div>\n\
                            <div class="modal-footer">\n\
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Ok</button>\n\
                            </div>\n\
                    </div>\n\
                </div>\n\
            </div>';
        $('#sup_details').remove();
        if(ct!=""){
            $('body').append(content);
            $('#sup_details').modal('toggle');
        }
        
        
    }).done(function (content) {

    });
}

function updateSupplier() {
    $("#editSupplier").on('submit', (function (e) {
        e.preventDefault();
        if (!emptyInput("sup_name") && !emptyInput("sup_contact")) {
            $(".sk-circle-layer").show();
            $.ajax({
                url: "?r=suppliers&f=update_supplier",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function (data)
                {
                    $('#edit_supplier').modal('hide');
                    //location.reload();
                    var table = $('#suppliers_table').DataTable();
                    table.ajax.url('?r=suppliers&f=getSuppliers&p0=0').load(function () {
                        table.row('.' + pad(data.id, 5), {page: 'current'}).select();
                        $(".sk-circle-layer").hide();
                    }, false);

                }
            });
        }
    }));
}

function submitNewSupplier(page) {
    $("#add_new_supplier").on('submit', (function (e) {
        e.preventDefault();
            $(".sk-circle-layer").show();
            $.ajax({
                url: "?r=suppliers&f=add_new_supplier",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function (data)
                {
                    $('#newSupplier').modal('hide');
                    if (page == "supplier") {
                        var table = $('#suppliers_table').DataTable();
                        table.ajax.url('?r=suppliers&f=getSuppliers&p0=0').load(function () {
                            table.page('last').draw(false);
                            table.row(':last', {page: 'current'}).select();
                            $(".sk-circle-layer").hide();
                        });
                    }else if(page=="items"){
                        $("#supplier_id").append("<option value='"+data.id+"'>"+data.sup_name+"</option>");
                        $("#supplier_id").selectpicker('refresh');
                        $("#supplier_id").selectpicker('val', data.id);
                        $(".sk-circle-layer").hide();
                    }else if(page=="receive_stock"){
                        $("#supplier_id").append("<option value='"+data.id+"'>"+data.sup_name+"</option>");
                        $("#supplier_id").selectpicker('refresh');
                        $("#supplier_id").selectpicker('val', data.id);
                        $(".sk-circle-layer").hide();
                        updateSuppliers();
                    }else if(page=="delivery_item"){
                        $("#suppliers_list").append("<option value='"+data.id+"'>"+data.sup_name+"</option>");
                        $("#suppliers_list").selectpicker('refresh');
                        $("#suppliers_list").selectpicker('val', data.id);
                        $(".sk-circle-layer").hide();
                        supplier_changed_delivery();
                        //updateSuppliers();
                    }
                    
                    
                    
                }
            });
        
    }));
}

function add_global_items(){
    var content =
    '<div class="modal fade" data-keyboard="false" id="add_global_items" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                    <input id="id_to_edit" name="id_to_edit" type="hidden" value="0" />\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title" id="exampleModalLongTitle"><i class="glyphicon glyphicon-plus"></i>&nbsp;Global Items</h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-lg-12">\n\
                                <table id="global_items_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                    <thead>\n\
                                        <tr><th style="width:80px;">Refernce</th><th>Name</th><th>Action</th></tr>\n\
                                    </thead>\n\
                                    <tfoot></tfoot>\n\
                                </table>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-footer">\n\
                    </div>\n\
            </div>\n\
        </div>\n\
    </div>';
    $('#add_global_items').remove();
    $('body').append(content);
    $('#add_global_items').modal('toggle');
    
    $('#add_global_items').on('shown.bs.modal', function (e) {
        $('#global_items_table').dataTable({
            ajax: "?r=items&f=get_global_items",
            paging: true,
            select: true,
            bLengthChange: false,
            bInfo: true,
            aoColumnDefs: [
                { "targets": [0], "searchable": true, "orderable": false, "visible": true },
                { "targets": [1], "searchable": true, "orderable": true, "visible": true },
                { "targets": [2], "searchable": true, "orderable": false, "visible": true },
            ]
        });
    });
    $('#add_global_items').on('hidden.bs.modal', function (e) {

    });
}

function print_barcode_group(group_id){
    
    if (typeof print_barcode_in_browser !== 'undefined' && print_barcode_in_browser=="1") {
        alert("Need imp");
        return;
    }
    
    
    swal({
        title: "Number Of Barcodes",
        html: true ,
        text: '<input class="form-control" value="1" type="text" id="print_nb"/>',
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "",
        cancelButtonText: "Cancel",
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function(isConfirm) {
        if (isConfirm) {
            if ($("#print_nb").val() == "" || $("#print_nb").val() == null) {
                return false;
            }else{
                
                
                $.getJSON("?r=items&f=print_barcode_group&p0=" + group_id + "&p1="+$("#print_nb").val(), function (data) {
                    if(data[0]==1){
                        swal("Printing Failed.");
                    }
                    if(data[0]==2){
                        swal("Try to generate it.");
                    }
                }).done({

                });
                
                
            }
        }
        $(".sweet-alert").remove();
        $(".sweet-overlay").remove();
    });
    setTimeout(function(){
        var SearchInput = $('#print_nb');
        SearchInput.val(SearchInput.val());
        var strLength= SearchInput.val().length;
        SearchInput.focus();
        SearchInput[0].setSelectionRange(strLength, strLength);
    },200);
}

function print_barcode_(id_int,qty){
    
    if (typeof print_barcode_in_browser !== 'undefined' && print_barcode_in_browser=="1") {
        
        $.confirm({
            title: 'Print Barcodes!',
            content: 'Please choose an option',
            buttons: {
                cancel: {
                    text: 'Cancel',
                    btnClass: 'btn-danger',
                    action: function(){
                        
                    }
                },
                only_one_item: {
                    text: 'Only selected Item',
                    btnClass: 'btn-blue',
                    action: function(){
                        w=window.open('?r=items&f=print_barcode_using_windows_print&p0='+id_int+"&p1=0&p2=0"); 
                    }
                },
                all_qty: {
                    text: 'Print barcodes based on current inventory levels',
                    btnClass: 'btn-blue',
                    action: function(){
                        w=window.open('?r=items&f=print_barcode_using_windows_print&p0='+id_int+"&p1=1&p2=0"); 
                    }
                },
                all_grp_with_qtities: {
                    text: 'Print barcodes for all Group based on current inventory levels',
                    btnClass: 'btn-blue',
                    action: function(){
                       w=window.open('?r=items&f=print_barcode_using_windows_print&p0='+id_int+"&p1=1&p2=1"); 
                    }
                }
                
            }
        });
        return;
    }
    
    
    if(qty==0){
        qty="";
    }
    swal({
            title: "Number Of Barcodes",
            html: true ,
            text: '<input class="form-control" value="'+qty+'" type="text" id="print_nb"/><br/><input placeholder="Price in LBP" class="form-control med_input prince_in_lbp" value="" type="text" id="prince_in_lbp"/>',
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "",
            cancelButtonText: "Cancel",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function(isConfirm) {
            if (isConfirm) {
                if ($("#print_nb").val() == "" || $("#print_nb").val() == null) {
                    return false;
                }else{
                    var nprice = 0;
                    if($("#prince_in_lbp").val()!=""){
                        nprice = $("#prince_in_lbp").val().replace(/,/g , '');
                    }
                    $.getJSON("?r=items&f=print_barcode&p0=" + id_int + "&p1="+$("#print_nb").val()+"&p2="+nprice, function (data) {
                        if(data[0]==1){
                            swal("Printing Failed.");
                        }
                        if(data[0]==2){
                            swal("Try to generate it.");
                        }
                    }).done({

                    }); 
                }
            }
            $(".sweet-alert").remove();
            $(".sweet-overlay").remove();
        });
        setTimeout(function(){
            var SearchInput = $('#print_nb');
            SearchInput.val(SearchInput.val());
            var strLength= SearchInput.val().length;
            SearchInput.focus();
            SearchInput[0].setSelectionRange(strLength, strLength);
            cleaves_class(".prince_in_lbp",2);
        },200);
    
}

function show_details_invoice(inv_id){
    $.getJSON("?r=invoice&f=get_invoice_by_id&p0="+inv_id, function (data) {
        $("#invoice_id").html(data[0].id);
        $("#invoice_date").html(data[0].creation_date);
        $("#vendor_name").html(data[0].vendor_name);
        $("#salesperson_name").html(data[0].salesperson_name);

        $("#total_value").html(data[0].total_value_);
        $("#invoice_discount").val(data[0].invoice_discount_);
        $("#total_after_discount").html(data[0].total_after_discount);
        $("#currency_invoice").html(data[0].default_currency_symbol);
        
        
    }).done(function () {

    }); 

    var content =
    '<div class="modal" data-backdrop="static" id="show_details_invoice_modal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <form id="update_invoice" action="" method="post" enctype="multipart/form-data" >\n\
                <input id="id_to_edit" name="id_to_edit" type="hidden" value="'+inv_id+'" />\n\
                <div class="modal-content">\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title">Invoice Details<i style="float:right;font-size:30px; cursor:pointer" class="glyphicon glyphicon-remove" onclick="show_details_invoice_modal()"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-lg-3 col-md-3">\n\
                               <input class="form-control input-sm" id="search_item_to_add" type="text">\n\
                               <input id="search_item_to_add_id" type="hidden" value="0">\n\
                            </div>\n\
                            <div class="col-lg-1 col-md-1">\n\
                               <button type="button" class="btn btn-sm btn-primary" onclick="add_item_to_invoice('+inv_id+')">Add To Invoice</button>\n\
                            </div>\n\
                            <div class="col-lg-7 col-md-7">\n\
                               &nbsp;\n\
                            </div>\n\
                            <div class="col-lg-1 col-md-1">\n\
                               <button type="submit" class="btn btn-sm btn-primary" style="width:100%">Update</button>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-lg-3 col-md-3">\n\
                                <b>Invoice ID:</b>&nbsp;<span id="invoice_id" style="font-size:14px;">-</span>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3">\n\
                                <b>Date:</b>&nbsp;<span id="invoice_date" style="font-size:14px;">-</span>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3">\n\
                                <b>Vendor:</b>&nbsp;<span id="vendor_name" style="font-size:14px;">-</span>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3">\n\
                                <b>Sales Person:</b>&nbsp;<span id="salesperson_name" style="font-size:14px;">-</span>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-lg-3 col-md-3">\n\
                                <b>Total Value:</b>&nbsp;<span id="total_value" style="font-size:14px;">-</span>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3">\n\
                                <label class="form-group" style="display: block">\n\
                                    <span class="control-label col-md-3" style="padding-left: 0px">Discount:</span>\n\
                                    <div class="col-md-9">\n\
                                        <input class="form-control input-sm input-xs only_numeric" onkeyup="invoice_discount_changed()" id="invoice_discount" name="invoice_discount" type="text">\n\
                                    </div>\n\
                                </label>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3">\n\
                                <b>Total After Discount:</b>&nbsp;<span id="total_after_discount" style="font-size:14px;">-</span>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3">\n\
                                <b>Currency: </b>&nbsp;<span id="currency_invoice" style="font-size:14px;">-</span>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-lg-12 col-md-12">\n\
                                <table id="invoice_detail_table" class="table table-striped table-bordered " cellspacing="0">\n\
                                    <thead>\n\
                                        <tr>\n\
                                            <th style="width:100px;">Item invoice Ref</th><th style="width:100px;">Item Ref</th><th>Description</th><th style="width:100px;">Quantity</th><th style="width:100px;">Selling Price/u</th><th style="width:100px;">Discount (%)</th><th style="width:100px;">Vat</th><th style="width:100px;">Total Price/u</th><th style="width:100px;">Total</th><th style="width:100px;">&nbsp;</th>\n\
                                        </tr>\n\
                                    </thead>\n\
                                    <tfoot><tr><th>Item invoice Ref</th><th>Item Ref</th><th>Description</th><th>Quantity</th><th>Selling Price/u</th><th>Discount</th><th>Vat</th><th>Total Price/u</th><th>Total</th><th>&nbsp;</th></tr></tfoot>\n\
                                </table>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
            </form>\n\
        </div>\n\
    </div>';
    $("#show_details_invoice_modal").remove();
    $("body").append(content);

    $("#show_details_invoice_modal").centerWH();

    $('#show_details_invoice_modal').on('show.bs.modal', function (e) {
        var search_fields = [0,1,2,3,4,5,6,7,8];
        var index = 0;
        $('#invoice_detail_table tfoot th').each( function () {
            if(jQuery.inArray(index, search_fields) !== -1){
                var title = $(this).text();
                $(this).html( '<div class="inner-addon left-addon"><input style="width: 100% !important;" class="form-control input-sm" type="text" placeholder=" '+title+'" /></div>' );
                index++;
            }
        });
        var table = $('#invoice_detail_table').DataTable({
            ajax: "?r=invoice&f=get_invoice_details_by_id&p0="+inv_id,
            aoColumnDefs: [
                { "targets": [0], "searchable": true, "orderable": true, "visible": false },
            ],
            paging: true,
            select: true,
            ordering: false,
            bPaginate: false,
            bLengthChange: false,
            bFilter: true,
            bInfo: false,
            bAutoWidth: false,
            fnDrawCallback: update_barcode,
        });
        
        $(".only_numeric").numeric({ negative : false});
        
        
        $.get("?r=items&f=get_items_names", function(data){
            //var search_by_name_typehead = $('#search_item_to_add').typeahead();
           // search_by_name_typehead.data('typeahead').source = data;
            
            var $input = $("#search_item_to_add");
            $input.typeahead({
                source: data,
                autoSelect: true,
                fitToElement:false,
                items:"all",
                scrollHeight:0,
                minLength:2
            });

            $input.change(function() {
                var current = $input.typeahead("getActive");
                if (current) {
                    if (current.name == $input.val()) {
                        $("#search_item_to_add_id").val(current.id);
                    }else{

                    }
                } else {

                }
            });

        },'json')
        .done(function(){
            
        })
        .fail(function() {
        })
        .always(function() {
        });
        
        $("#update_invoice").on('submit', (function (e) {
            e.preventDefault();
            $(".sk-circle-layer").show();
            $.ajax({
                url: "?r=invoice&f=update_invoice",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function (data)
                {
                    $(".sk-circle-layer").hide();
                    $('#show_details_invoice_modal').modal('hide');
                    setTimeout(function(){
                        show_details_invoice(inv_id);
                        if($('#customers_statement_table').length>0){
                            var table = $('#customers_statement_table').DataTable();
                            table.ajax.url("?r=customers&f=get_customer_statement&p0="+$("#customers_list").val()).load(function () {
                                $(".sk-circle-layer").hide();
                            }, false);
                        }
                    },10);
                }
            });
        }));
    });
    $('#show_details_invoice_modal').on('hide.bs.modal', function (e) {
        $('#show_details_invoice_modal').remove();
    });
    $('#show_details_invoice_modal').modal('show');
}

function add_item_to_invoice(id){
    $.getJSON("?r=invoice&f=addItemsToInvoice&p0=" + id+"&p1="+$("#search_item_to_add_id").val(), function (data) {
    
    }).done(function () {
        $("#update_invoice").submit();
        $("#search_item_to_add").val("");
    });
    
    
    
    //alert($("#search_item_to_add_id").val());
}

function show_details_invoice_modal(){
    $('#show_details_invoice_modal').modal('toggle');
}

function show_change_salesperson_of_invoice(){
    $('#sales_invoice_modal').modal('toggle');
}

function show_complex_modal(){
    $('#show_complex_modal').modal('toggle');
}


function update_barcode(){
    $(".only_numeric").numeric({ negative : false});
    var table = $('#invoice_detail_table').DataTable();
    var p = table.rows({ page: 'current' }).nodes();
    for (var k = 0; k < p.length; k++){
        var index = table.row(p[k]).index();
        table.cell(index, 9).data('<button onclick="print_barcode_d(\''+parseInt(table.cell(index, 0).data().split("-")[1])+'\')" type="button" class="btn btn-xs btn-primary" style="width:100%">Print Barcode</button>');

    }
}

function invoice_discount_changed(){
    $("#total_after_discount").html(($("#total_value").html().replace(/,/g, '').trim()-$("#invoice_discount").val()).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
}

function print_barcode_d(id){
    if (typeof print_barcode_in_browser !== 'undefined' && print_barcode_in_browser=="1") {
        w=window.open('?r=items&f=print_barcode_using_windows_print&p0='+id_int+"&p1=0&p2=0"); 
        return;
    }
    
    $.getJSON("?r=items&f=print_barcode&p0=" + id + "&p1=1&p2=0", function (data) {

    }).done({

    });

}

function updateTableQty(table_id,url,force){
    var update = 0;
    $.getJSON("?r=settings_info&f=getIfToUpdate", function (data) {
        update = data[0];
    }).done(function () {
        if(update>=1 || force==1){
            var table = $('#'+table_id).DataTable();
            var id_selected = table.row('.selected', 0).data();
            table.ajax.url(url).load(function () {
                if(id_selected != undefined){
                    table.row('.' + id_selected[0], {page: 'current'}).select();
                }
            },false);
        }
    });  
}

function update_salesperson_of_invoice(invoice_id){
    $.getJSON("?r=invoice&f=update_salesperson_of_invoice&p0="+invoice_id+"&p1="+$("#employee_id").val(), function (data) {

    }).done(function () {
        
        if($('#cutomer_invoice_table').length>0){
            var table = $('#cutomer_invoice_table').DataTable();        
            $(".sk-circle-layer").show();        
            table.ajax.url("?r=invoice&f=getAllInvoicesDateRange&p0="+current_store_id+"&p1="+current_date+"&p2="+current_invoice_filter+"&p3="+$("#filter_salesperson").val()+"&p4="+$("#filter_vendors").val()+"&p5="+$("#filter_taxable").val()).load(function () {
                $(".sk-circle-layer").hide();
            }, false);
        }
    });
}

function update_invoice_info(invoice_id){
    var employees_options = "";
    var sales_person_id = -1;
    $.getJSON("?r=invoice&f=get_invoice_info&p0="+invoice_id, function (data) {
        employees_options += "<option value='0'>None</option>";
        $.each(data.employees, function (key, val) {
            employees_options += "<option value=" + val.id + ">" + val.first_name + " " + val.last_name + "</option>";
        });
        $.each(data.invoice, function (key, val) {
            sales_person_id = val.sales_person;
        });
    }).done(function () {
        var content =
        '<div class="modal" data-backdrop="static" id="sales_invoice_modal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title">Invoice Info<i style="float:right;font-size:30px; cursor:pointer" class="glyphicon glyphicon-remove" onclick="show_change_salesperson_of_invoice()"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-xs-4">\n\
                                <div class="form-group">\n\
                                    <label for="employee_id">Sales Person</label>\n\
                                    <select onchange="update_salesperson_of_invoice('+invoice_id+')" id="employee_id" name="employee_id" class="selectpicker form-control" style="width:100%">' + employees_options + '</select>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>';
        $("#sales_invoice_modal").remove();
        $("body").append(content);

        $("#sales_invoice_modal").centerWH();

        $('#sales_invoice_modal').on('show.bs.modal', function (e) {

        });
        $('#sales_invoice_modal').on('shown.bs.modal', function (e) {
            $("#employee_id").selectpicker();
            $('#employee_id').selectpicker('val', sales_person_id);
            //nvoice_id
        });
        
        
        $('#sales_invoice_modal').on('hide.bs.modal', function (e) {
            $('#sales_invoice_modal').remove();
        });
        $('#sales_invoice_modal').modal('show');
    });  
}

function bulk_items_modal_close(){
    $('#bulk_items_modal').modal('toggle');
}

function modal_close(id){
    $('#'+id).modal('toggle');
}

function shortcut_types_changed(){
    if($("#shortcut_type").val()>0){
        $("#shortcut_qty").show();
    }else{
        $("#shortcut_qty").hide();
    }
}

function BulkItem(item_id){
    
    var colors_options = "";
    var sizes_options = "";
    $(".sk-circle-layer").show();
    $.getJSON("?r=items&f=get_needed_data", function (data) {
        $.each(data.colors_text, function (key, val) {
            colors_options += "<option value=" + val.id + ">" + val.name + "</option>";
        });
        $.each(data.sizes, function (key, val) {
            sizes_options += "<option value=" + val.id + ">" + val.name + "</option>";
        });
    }).done(function () {
        var content =
        '<div class="modal" tabindex="-1" data-backdrop="static" id="bulk_items_modal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
            <form id="bulk_items_form" action="" method="post" enctype="multipart/form-data" >\n\
                <input id="item_id" name="item_id" type="hidden" value="'+item_id+'" />\n\
                <div class="modal-dialog" role="document">\n\
                    <div class="modal-content">\n\
                        <div class="modal-header"> \n\
                            <h3 class="modal-title">Create group of items<i style="float:right;font-size:30px; cursor:pointer" class="glyphicon glyphicon-remove" onclick="bulk_items_modal_close()"></i></h3>\n\
                        </div>\n\
                        <div class="modal-body">\n\
                            <div class="row">\n\
                                <div class="col-lg-2 col-md-2" style="padding-right:2px;">\n\
                                    <div class="form-group">\n\
                                        <label for="bulk_items_select_colors">Colors</label>\n\
                                        <select data-live-search="true" id="bulk_items_select_colors" name="bulk_items_select_colors[]" class="form-control selectpicker" multiple data-actions-box="true">\n\
                                          '+colors_options+'\n\
                                        </select>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2" style="padding-right:2px;padding-left:2px;">\n\
                                    <div class="form-group">\n\
                                        <label for="bulk_items_select_sizes">Sizes</label>\n\
                                        <select data-live-search="true"  id="bulk_items_select_sizes" name="bulk_items_select_sizes[]" class="form-control selectpicker" multiple data-actions-box="true">\n\
                                          '+sizes_options+'\n\
                                        </select>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-1 col-md-1" style="padding-right:2px;padding-left:2px;">\n\
                                    <div class="form-group">\n\
                                        <label for="bulk_items_qty" style="font-size:15px;">Qty in stock</label>\n\
                                        <input type="text" class="form-control only_numeric" id="bulk_items_qty" name="bulk_items_qty" value="0" placeholder="Default Quantity">\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-3 col-md-3 col-sm-3" style="padding-right:2px;padding-left:2px;">\n\
                                    <div id="tab_toolbar_3" class="btn-group tab_toolbar" role="group" aria-label="" style="width:100% !important;">\n\
                                        <div class="btn-group" role="group" aria-label="" style="width:100% !important;">\n\
                                            <label for="shortcut_type" >Create shortcuts</label>\n\
                                            <select data-live-search="true" data-width="100%" id="shortcut_type" name="shortcut_type" class="selectpicker" onchange="shortcut_types_changed()">\n\
                                                <option selected value="0" title="None">None</option>\n\
                                                <option value="1" title="Create shorcuts, each color = x,y,z sizes">Create shorcuts, each color = x,y,z sizes</option>\n\
                                            </select>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-3 col-md-3 col-sm-3" id="shortcut_qty" style="padding-right:2px;padding-left:2px;display:none">\n\
                                    <div class="form-group">\n\
                                        <label for="bulk_items_qty" style="font-size:15px;">Qty in shortcut (Exp 1,1,1,1,1,1)</label>\n\
                                        <input type="text" class="form-control" id="shortcut_items_qty" name="shortcut_items_qty" value="" placeholder="">\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-1 col-md-1" style="padding-right:2px;padding-left:2px;">\n\
                                    <label>&nbsp;</label>\n\
                                    <div class="form-group">\n\
                                        <a style="width:100%" onclick="$(this).closest(\'form\').submit()" type="submit" class="btn btn-primary">Create</a>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2" style="padding-left:2px;display:none">\n\
                                    <label>&nbsp;</label>\n\
                                    <div class="form-group">\n\
                                        <a style="width:100%" onclick="set_group_as_shortcut('+item_id+')" type="button" class="btn btn-primary">Set group as shortcut</a>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-2" style="display:none">\n\
                                    <label>&nbsp;</label>\n\
                                    <div class="btn-group" role="group" aria-label="" style="width:100%">\n\
                                        <div class="btn-group" id="buttons" style="float:right"></div>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="row">\n\
                                <div class="col-lg-2 col-md-2" style="padding-right:2px;">\n\
                                    <div class="form-group">\n\
                                        <a style="width:100%" onclick="import_shortcuts_to_stock('+item_id+')" type="button" class="btn btn-primary">Import shortcuts to stock</a>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="row">\n\
                                <div class="col-lg-12 col-md-12">\n\
                                    <table id="group_items_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                        <thead>\n\
                                            <tr>\n\
                                                <th style="width: 50px !important;">Item ID</th>\n\
                                                <th style="width: 120px !important;">Barcode</th>\n\
                                                <th>Description</th>\n\
                                                <th style="width: 90px !important;">Color</th>\n\
                                                <th style="width: 90px !important;">Size</th>\n\
                                                <th style="width: 50px !important;">Qty</th>\n\
                                                <th style="width: 50px !important;">Cost</th>\n\
                                                <th style="width: 50px !important;">Price</th>\n\
                                                <th style="width: 130px !important;">&nbsp;</th>\n\
                                            </tr>\n\
                                        </thead>\n\
                                        <tfoot>\n\
                                            <tr>\n\
                                                <th>Item ID</th>\n\
                                                <th>Barcode</th>\n\
                                                <th>Description</th>\n\
                                                <th>Color</th>\n\
                                                <th>Size</th>\n\
                                                <th>Qty</th>\n\
                                                <th>Cost</th>\n\
                                                <th>Price</th>\n\
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
            </form>\n\
        </div>';
        $("#bulk_items_modal").remove();
        $("body").append(content);

        $("#bulk_items_modal").centerWH();

        $('#bulk_items_modal').on('show.bs.modal', function (e) {

        });
        $('#bulk_items_modal').on('shown.bs.modal', function (e) {
            $('.selectpicker').selectpicker();
            $(document).off('focusin.modal');
            /* table */
            var search_fields = [0,1,2,3,4,5,6,7];
            var index = 0;
            $('#group_items_table tfoot th').each( function () {
                if(jQuery.inArray(index, search_fields) !== -1){
                    var title = $(this).text();
                    $(this).html( '<div class="inner-addon left-addon"><input id="idf_'+index+'" style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder="'+title+'" /></div>' );
                    index++;
                }
            });

            var group_items_table = $('#group_items_table').dataTable({
                ajax: "?r=items&f=get_group&p0="+item_id,
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
                    { "targets": [5], "searchable": true, "orderable": false, "visible": true },
                    { "targets": [6], "searchable": true, "orderable": false, "visible": true },
                    { "targets": [7], "searchable": true, "orderable": false, "visible": true },
                    { "targets": [8], "searchable": true, "orderable": false, "visible": true },
                ],
                scrollY: '35vh',
                scrollCollapse: true,
                paging: true,
                order: [[ 0, "asc" ]],
                dom: '<"toolbar_sh">frtip',
                initComplete: function( settings ) {
                    
                    /*
                    var buttons = new $.fn.dataTable.Buttons(group_items_table, {
                        buttons: [
                          {
                                extend: 'excel',
                                text: 'Export excel',
                                className: 'exportExcel',
                                filename: 'Group ',
                                customize: _customizeExcelOptions,
                                exportOptions: {
                                    modifier: {
                                        page: 'all'
                                    },
                                    format: {
                                        body: function ( data, row, column, node ) {
                                            // Strip $ from salary column to make it numeric
                                            //return column === 5 ? $("#id_"+parseInt(table.cell(row,0).data().split('-')[1])).val() : data; //table.cell(row,0).data().split('-')[1]
                                        }
                                    }
                                }
                          }
                        ]

                   }).container().appendTo($('#buttons'));

                  function _customizeExcelOptions(xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        var clR = $('row', sheet);
                        //var r1 = Addrow(clR.length+2, [{key:'A',value: "Total Lost"},{key:'B',value: $("#total_lost").html()}]);
                        //var r2 = Addrow(clR.length+3, [{key:'A',value: "Total profit"},{key:'B',value: $("#total_profit").html()}]);
                        //var r3 = Addrow(clR.length+4, [{key:'A',value: "Total Expenses"},{key:'B',value: $("#total_expenses").html()}]);
                        //var r4 = Addrow(clR.length+5, [{key:'A',value: "Total Invoices Discounts"},{key:'B',value: $("#tm_discount").html()}]);
                        //var r5 = Addrow(clR.length+6, [{key:'A',value: "Total Credit Notes"},{key:'B',value: $("#total_credit_notes").html()}]);
                        //sheet.childNodes[0].childNodes[1].innerHTML = sheet.childNodes[0].childNodes[1].innerHTML + r1;

                        //$('row c[r^="A'+(clR.length+2)+'"]', sheet).attr('s', '48');
                        //$('row c[r^="A'+(clR.length+3)+'"]', sheet).attr('s', '48');
                        //$('row c[r^="A'+(clR.length+4)+'"]', sheet).attr('s', '48');
                        //$('row c[r^="A'+(clR.length+5)+'"]', sheet).attr('s', '48');
                        //$('row c[r^="A'+(clR.length+6)+'"]', sheet).attr('s', '48');

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
                    }*/

                    $(".sk-circle-layer").hide();
                },
                fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    $(nRow).addClass(aData[0]);
                },
                fnDrawCallback: updateGroupRows_,
            });
            
            
            
            $(group_items_table).DataTable().on('mousedown',"tr", function ( e, dt, type, indexes ) { 
                $('#group_items_table .selected').removeClass("selected");
                $(this).addClass('selected');
            });
            
            function updateGroupRows_(){
                var table = $('#group_items_table').DataTable();
                var p = table.rows({ page: 'current' }).nodes();
                for (var k = 0; k < p.length; k++){
                    var index = table.row(p[k]).index();
                    shortcuts = "";
                    shortcuts+='<i class="glyphicon glyphicon-barcode shortcut" title="Print Barcode" onclick="print_barcode_(\''+parseInt(table.cell(index, 0).data().split('-')[1])+'\',\'\')"></i>';
                    //if(disabled_action==0){
                        shortcuts+='<i class="glyphicon glyphicon-plus shortcut" title="Add qty" onclick="add_items_to_store(\''+parseInt(table.cell(index, 0).data().split('-')[1])+'\','+parseInt($("#store_list").val())+',\'from_group\')"></i>';
                    //}
                    
                    var sdata_name = table.row(index).data();
                    var item_name = sdata_name[3];
                    shortcuts+='<i class="glyphicon glyphicon-align-center shortcut" title="Stock Tracking" onclick="logs(\''+parseInt(table.cell(index, 0).data().split('-')[1])+'\',\''+item_name+'\','+parseInt($("#store_list").val())+')"></i>';

                    if(disabled_action==0){
                        shortcuts+='<i class="glyphicon glyphicon-edit shortcut" title="Edit" onclick="editItem(\''+table.cell(index, 0).data()+'\',0)"></i>';
                    }
                    shortcuts+='<i class="glyphicon glyphicon-trash shortcut redandsize" title="Delete" onclick="deleteItemGroup('+parseInt(table.cell(index, 0).data().split('-')[1])+')"></i>';
                    table.cell(index, 8).data(shortcuts);

                    //table.cell(index, 6).data('<button onclick="add_items_to_store(\''+parseInt(table.cell(index, 0).data().split('-')[1])+'\','+parseInt($("#store_list").val())+',\'from_group\')" type="button" class="btn btn-xs btn-info btn-xss" style="width:100% !important">Add Qty</button>');
                }
            }

            
            $(".only_numeric").numeric({ negative : false});
            
            $("#bulk_items_form").on('submit', (function (e) {
                e.preventDefault();
                
                
                if($("#shortcut_type").val()==1){
                    var sh_qty = $("#shortcut_items_qty").val().split(',');
                    var sh_sizes = String($("#bulk_items_select_sizes").val()).split(',');
                    
                    if( sh_sizes=="" || (sh_sizes.length>0 && sh_sizes.length!=sh_qty.length)){
                        swal("Sizes is not selected or lenght between sizes and qty in shortcut not equal");
                        return;
                    }
                }
          
                
                $(".sk-circle-layer").show();
                $.ajax({
                    url: "?r=items&f=add_bulk",
                    type: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: 'json',
                    success: function (data)
                    {
                        
                        
                        var table = $('#group_items_table').DataTable();
                        $(".sk-circle-layer").show();
                        table.ajax.url("?r=items&f=get_group&p0="+item_id).load(function () {
                            $(".sk-circle-layer").hide();
                        },false);
                        
                        
                        if($('#items_table').length>0){
                            var table = $('#items_table').DataTable();
                            table.ajax.url('?r=items&f=getAllItems&p0='+current_store_id+"&p1="+current_category_id+"&p2="+current_subcategory_id+"&p3="+item_boxex+"&p4="+current_supplier_id+"&p5="+$("#stock_status").val()).load(function () {
                                
                                if($('#group_items_table').length>0){
                                    
                                }else{
                                    table.page('last').draw(false);
                                    table.row(':last', {page: 'current'}).select();
                                    $(".dataTables_scrollBody").scrollTop($('.dataTables_scrollBody')[0].scrollHeight);
                                }

                                $(".sk-circle-layer").hide();
                            });
                        }
                        
                        if($('#parent_categories_table').length>0){
                            var dt = $('#parent_categories_table').DataTable();
                            var sdata = dt.row('.selected', 0).data();
                            update_categories(parseInt(sdata[0].split('-')[1])); 
                        }
                        
                        if($('#categories_table').length>0){
                            var dt = $('#categories_table').DataTable();
                            var sdata_category = dt.row('.selected', 0).data();

                            var table_details = $("#items_by_cat_table_details").DataTable();
                            table_details.ajax.url("?r=items&f=get_all_items_of_sub_category&p0="+parseInt(sdata_category[0].split('-')[1])+"&p1="+current_store_id).load(function () {
                                 
                            },false);
                        }
                    }
                });
            }));
        });
        
        
        $('#bulk_items_modal').on('hide.bs.modal', function (e) {
            $('#bulk_items_modal').remove();
        });
        $('#bulk_items_modal').modal('show');
    });
    
}

function deleteItemGroup(id){
    swal({
        title: "Delete Item",
        html: true ,
        text: 'Are you sure?',
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "",
        cancelButtonText: "Cancel",
        closeOnConfirm: true,
        closeOnCancel: true
    },
    function(isConfirm) {
        if (isConfirm) {
            
            $.getJSON("?r=items&f=delete_item&p0="+id , function (data) {
                if(!data.status){
                    $(".sk-circle-layer").hide();
                    swal("Unable to delete!", "Please contact system administrator", "warning");
                }else{
                    $(".sk-circle-layer").hide();
                }
            }).done(function () {
                $(".sk-circle-layer").show();
                $("#group_items_table").DataTable().ajax.url("?r=items&f=get_group&p0="+$("#item_id").val()).load(function () {
                    $(".sk-circle-layer").hide();
                },false);
            }); 
        }
    });
}

function deleteItem(id){
    $.confirm({
        title: 'Delete Item?',
        content: '',
        animation: 'zoom',
        closeAnimation: 'zoom',
        animateFromElement:false,
        buttons: {
            DELETE: {
                btnClass: 'btn-danger',
                action: function(){
                    $(".sk-circle-layer").show();
                    $.getJSON("?r=items&f=delete_item&p0="+id , function (data) {
                        if(!data.status){
                            $(".sk-circle-layer").hide();
                            swal("Unable to delete!", "Please contact system administrator", "warning");
                        }else{
                            $(".sk-circle-layer").hide();
                        }
                    }).done(function () {
                        if($("#showItemsBySubCat").length>0){
                            var table_details = $("#items_by_cat_table_details").DataTable();

                            var dt = $('#categories_table').DataTable();
                            var sdata_category = dt.row('.selected', 0).data();

                            table_details.ajax.url("?r=items&f=get_all_items_of_sub_category&p0="+parseInt(sdata_category[0].split('-')[1])+"&p1="+current_store_id).load(function () {
                                 $(".sk-circle-layer").hide();
                            },false);
                        }
                    });
                }
            },
            CANCEL: {
                btnClass: 'btn-default any-other-class', // multiple classes.
                action: function(){

                }
            },
        }
    });

}
    
    function print_debitnote(debitnote_id){
        w=window.open('?r=debit_note&f=print_debitnote&p0='+debitnote_id); 
    }
    
    function print_creditnote(creditnote_id){
        w=window.open('?r=credit_note&f=print_creditnote&p0='+creditnote_id); 
    }
    
    
    function logs(id,name){
        $(".sk-circle-layer").show();
        var content =
        '<div class="modal" data-backdrop="static" id="logsModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title">Logs Of <span class="itemlog_name">'+name+'</span> Created By <span id="itemlog_admin_name"></span><i style="float:right;font-size:35px" class="glyphicon glyphicon-remove" onclick="closeModal(\'logsModal\')"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >\n\
                                <table id="logs_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                    <thead>\n\
                                        <tr>\n\
                                            <th style="width: 100px !important;">Ref.</th>\n\
                                            <th style="width: 250px !important;">Action</th>\n\
                                            <th style="width: 120px !important;">Qty After Action</th>\n\
                                            <th style="width: 200px !important;">Useranme - name</th>\n\
                                            <th>Action Date</th>\n\
                                        </tr>\n\
                                    </thead>\n\
                                    <tfoot>\n\
                                        <tr>\n\
                                            <th>Ref.</th>\n\
                                            <th>Action</th>\n\
                                            <th>Qty After Action</th>\n\
                                            <th>Useranme - name</th>\n\
                                            <th>Action Date</th>\n\
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
        $("#logsModal").remove();
        $("body").append(content);
        $('#logsModal').on('show.bs.modal', function (e) {

            var h_logs = null;
            var search_fields = [0,1,2,3,4];
            var index = 0;
            $('#logs_table tfoot th').each( function () {
                if(jQuery.inArray(index, search_fields) !== -1){
                    var title = $(this).text();
                    $(this).html( '<div class="inner-addon left-addon"><input style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder=" '+title+'" /></div>' );
                    index++;
                }
            });

            h_logs = $('#logs_table').DataTable({
                ajax: {
                    url: "?r=items&f=get_all_logs&p0="+id+"&p1="+current_store_id,
                    type: 'POST',
                    error:function(xhr,status,error) {
                        //logged_out_warning();
                    },
                    dataSrc: function (json) {
                        if(json.data=="-1"){
                            swal("Network error");
                        }
                        return json.data;
                    }
                },
                orderCellsTop: true,
                pageLength: 100,
                aoColumnDefs: [
                    { "targets": [0], "searchable": true, "orderable": true, "visible": true },
                    { "targets": [1], "searchable": true, "orderable": true, "visible": true },
                    { "targets": [2], "searchable": true, "orderable": true, "visible": true },
                    { "targets": [3], "searchable": true, "orderable": false, "visible": true },
                ],
                scrollCollapse: true,
                paging: true,
                ordering: false,
                scrollY: '50vh',
                initComplete: function(settings, json) {
                   // h_cashboxes.cell( ':eq(0)' ).focus();
                    //$('#history_of_cashboxes_table tfoot input:eq(0)').focus();

                    //$( "#logs_table_date" ).change(function() {
                       // history_cashbox_changed();
                    //});

                    $.getJSON("?r=items&f=get_item_by_id_&p0=" + id, function (data) {
                        if(data[0].user_name.length>0){
                            $("#itemlog_admin_name").html(data[0].user_name+" At "+data[0].creation_date);
                        }
                    }).done(function () {

                    });

                    //h_logs.cell(':last').focus();
                    $(".sk-circle-layer").hide();
                },
                //fnDrawCallback: updateRows_history_cashbox,
            });

            $("#logs_table").on("mousedown", "tr", function(event) {
                $('#logs_table .selected').removeClass("selected");
                var dt = $('#logs_table').DataTable();
                var index = dt.row(this).index();
                dt.row(index).select(index);
            });

            $('#logs_table').DataTable().on('dblclick',"tr", function ( e, dt, type, indexes ) {
                //var sdata = items_search.row('.selected', 0).data();
                //returnQty(parseInt(sdata[0].split("-")[1]));
            });

            $('#logs_table').on('key-focus.dt', function(e, datatable, cell){
                $(h_logs.row(cell.index().row).node()).addClass('selected');
            });

            $('#logs_table').on('key-blur.dt', function(e, datatable, cell){
                $(h_logs.row(cell.index().row).node()).removeClass('selected');
            });

            $('#logs_table').on('key.dt', function(e, datatable, key, cell, originalEvent){
                if(key === 13){
                     //var sdata = items_search.row('.selected', 0).data();
                    //returnQty(parseInt(sdata[0].split("-")[1]));
                }
            });

            $('#logs_table').DataTable().columns().every( function () {
                var that = this;
                $( 'input', this.footer() ).on( 'keyup change', function () {
                    h_logs.keys.disable();
                    if ( that.search() !== this.value ) {
                        that.search( this.value ).draw();
                    }
                    h_logs.keys.enable();
                } );
            } );
        });

        $('#logsModal').on('shown.bs.modal', function (e) {
            /*$('.date_s').daterangepicker({
                dateLimit:{month:1},
                locale: {
                    format: 'YYYY-MM-DD'
                },
            });

            $('.date_s').on('apply.daterangepicker', function(ev) {
                ev.preventDefault();
                ev.stopPropagation();
            });*/

        });
        $('#logsModal').on('hide.bs.modal', function (e) {
            $("#logsModal").remove();
        });
        $('#logsModal').modal('show');
    }
    
    
function trash(){
    $(".sk-circle").center();
    $(".sk-circle-layer").show(); 
    var table_name = "modal_all_trash_table";
    var modal_name = "modal_all_trash____";
    var modal_title = "Trash";
    
    var content =
    '<div class="modal full" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\''+modal_name+'\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <table style="width:100%" id="'+table_name+'" class="table table-striped table-bordered" cellspacing="0">\n\
                                <thead>\n\
                                    <tr>\n\
                                        <th style="width: 50px !important;">Ref.</th>\n\
                                        <th style="width: 70px !important;">Barcode</th>\n\
                                        <th>Description</th>\n\
                                        <th style="width: 80px !important;">Final Cost</th>\n\
                                        <th style="width: 80px !important;">Final Price</th>\n\
                                        <th style="width: 50px !important;">Size</th>\n\
                                        <th style="width: 50px !important;">Color</th>\n\
                                        <th style="width: 30px !important;"></th>\n\
                                    </tr>\n\
                                </thead>\n\
                                <tfoot>\n\
                                    <tr>\n\
                                        <th>Ref.</th>\n\
                                        <th>Barcode</th>\n\
                                        <th>Description</th>\n\
                                        <th>Final Cost</th>\n\
                                        <th>Final Price</th>\n\
                                        <th>Size</th>\n\
                                        <th>Color</th>\n\
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
    $("#"+modal_name).remove();
    $("body").append(content);
    $('#'+modal_name).on('show.bs.modal', function (e) {

    });
    
    $('#'+modal_name).on('shown.bs.modal', function (e) {
        
        $('#'+table_name).show();
        
        var _cards_table__var =null;
        
        var search_fields = [0,1,2,3,4,5,6,7];
        var index = 0;
        $('#'+table_name+' tfoot th').each( function () {

            if(jQuery.inArray(index, search_fields) !== -1){
                var title = $(this).text();
                $(this).html( '<input id="idf_'+index+'" style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder="'+title+'" />' );
                index++;
            }
        });

        _cards_table__var = $('#'+table_name).DataTable({
            ajax: {
                url: "?r=items&f=get_trash_items",
                type: 'POST',
                error:function(xhr,status,error) {
                },
            },
            responsive: true,
            orderCellsTop: true,
            scrollX: true,
            scrollY: "55vh",
            iDisplayLength: 100,
            aoColumnDefs: [
                { "targets": [0], "searchable": true, "orderable": true, "visible":  true },
                { "targets": [1], "searchable": true, "orderable": true, "visible": true },
                { "targets": [2], "searchable": true, "orderable": true, "visible": true },
                { "targets": [3], "searchable": true, "orderable": true, "visible": true },
                { "targets": [4], "searchable": true, "orderable": true, "visible": true },
                { "targets": [5], "searchable": true, "orderable": true, "visible": true },
                { "targets": [6], "searchable": true, "orderable": false, "visible": true },
                { "targets": [7], "searchable": true, "orderable": false, "visible": true,"className": "dt-center" },
            ],
            scrollCollapse: true,
            paging: true,
            bPaginate: false,
            bLengthChange: false,
            bFilter: true,
            bInfo: false,
            bAutoWidth: true,
            dom: '<"toolbar_trash">frtip',
            initComplete: function(settings, json) {
                 $("div.toolbar_trash").html('\n\
                    ');        
                $(".sk-circle-layer").hide();
            },
            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $(nRow).addClass(aData[0]);
            },
            fnDrawCallback: setTrashOptions,
        });
        
        $('#'+table_name).DataTable().on('mousedown',"tr", function ( e, dt, type, indexes ) { 
            $('#modal_all_trash_table .selected').removeClass("selected");
            $(this).addClass('selected');
        });
        
        $('#'+table_name).on('click', 'td', function () {
        });
        
        $('#'+table_name).DataTable().columns().every( function () {
            var that = this;
            $( 'input', this.footer() ).on( 'keyup change', function () {
                search_in_datatable_global(this.value,that.index(),100,table_name);
            } );
        } );
       
    });
    $('#'+modal_name).on('hide.bs.modal', function (e) {
        if($('#items_table').length>0){
            update_table_data();
        }
        $("#"+modal_name).remove();
    });
    $('#'+modal_name).modal('show'); 
}    

function setTrashOptions(){
    var table = $('#modal_all_trash_table').DataTable();
    var p = table.rows({ page: 'current' }).nodes();
    for (var k = 0; k < p.length; k++){
        var index = table.row(p[k]).index();
        table.cell(index,7).data('<i title="Restore" class="glyphicon icon-undo-arrow" onclick="restore(\''+parseInt(table.cell(index, 0).data().split("-")[1])+'\')" ></i>');
    }
}

function restore(id){
    $(".sk-circle-layer").show();
    $.getJSON("?r=items&f=restore_item&p0=" + id, function (data) {
        
    }).done(function () {
        $('#modal_all_trash_table').DataTable().ajax.url("?r=items&f=get_trash_items").load(function () {
            $(".sk-circle-layer").hide();
       },false);
    });
}


function var_price(){
    $(".sk-circle").center();
    $(".sk-circle-layer").show(); 
    var _data = [];
    $.getJSON("?r=dashboard&f=var_price_values", function (data) {
        _data=data;
    }).done(function () {
        var modal_name = "modal_all_varprice__";
        var modal_title = "Var Price";

        var content =
        '<div class="modal small" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\''+modal_name+'\')"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">\n\
                                <label for="enable_rate">Enable</label><br/>\n\
                                <input onchange="enable_var_price()" id="enable_rate" type="checkbox" style="width:25px;height:25px;" />\n\
                            </div>\n\
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">\n\
                                <label for="system_base_rate">Current System USD Rate</label>\n\
                                <input id="system_base_rate" value="'+_data.base_price_rate_to_usd+'" name="system_base_rate" type="text" class="form-control med_input cleaves_var" />\n\
                            </div>\n\
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">\n\
                                <label for="system_new_rate">New System USD Rate</label>\n\
                                <input id="system_new_rate" value="'+_data.new_price_rate_to_lbp+'" name="system_new_rate" type="text" class="form-control med_input cleaves_var" />\n\
                            </div>\n\
                        </div>\n\
                        <div class="row" style="margin-top:5px;">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                <button onclick="update_var_prices()" type="button" class="btn btn-primary" style="float:right">Update</button>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>';
        $("#"+modal_name).remove();
        $("body").append(content);
        $('#'+modal_name).on('show.bs.modal', function (e) {
            cleaves_class(".cleaves_var",5);
            if(_data.enable_price_var==0){
                $(".cleaves_var").attr("readonly","readonly");
            }else{
                $("#enable_rate").prop("checked",true);
            }
            $(".sk-circle-layer").hide(); 
        });

        $('#'+modal_name).on('shown.bs.modal', function (e) {

        });
        $('#'+modal_name).on('hide.bs.modal', function (e) {
            $("#"+modal_name).remove();
        });
        $('#'+modal_name).modal('show');
    }); 
}

function update_var_prices(){
    $(".sk-circle").center();
    $(".sk-circle-layer").show(); 
    
    var enable=0;
    if($('#enable_rate').is(':checked')){
        enable=1;
    }
    
    
    var base_price_var_rate=$("#system_base_rate").val();
    var new_var_price_rate=$("#system_new_rate").val();
    var round=1;

    $.getJSON("?r=dashboard&f=update_var_prices&p0="+enable+"&p1="+base_price_var_rate+"&p2="+new_var_price_rate+"&p3="+round, function (data) {
   
    }).done(function () {
        $('#modal_all_varprice__').modal('hide');
        $(".sk-circle-layer").hide(); 
    });
}

function enable_var_price(){
    if($('#enable_rate').is(':checked')){
        $(".cleaves_var").removeAttr("readonly","readonly");
    }else{
        $(".cleaves_var").attr("readonly","readonly");
    }
}


function prepare_search_items_g_modal(object_id,modal_name){
    $("#"+object_id).select2({
        ajax: {
            url: '?r=items&f=search',
            data: function (params) {
                var query = {
                    p0: params.term || "",
                    p1: params.page || 1
                }

                // Query parameters will be ?search=[term]&type=public
                return query;
            },
            delay: 250,
            dataType: 'json',
        },
        placeholder: "Search by Id, barcode, SKU and description",
        dropdownParent: $('#'+modal_name),
        allowClear: true,
        closeOnSelect: true
    });
    $("#"+object_id).on("change", () => {
        wasting_get_by_id_new($("#"+object_id).val());
    });
}
function prepare_search_items_g(object_id){
    $("#"+object_id).select2({
        ajax: {
            url: '?r=items&f=search',
            data: function (params) {
                var query = {
                    p0: params.term || "",
                    p1: params.page || 1
                }

                // Query parameters will be ?search=[term]&type=public
                return query;
            },
            delay: 250,
            dataType: 'json',
        },
        placeholder: "Search by Id, barcode, SKU and description",
        dropdownParent: $(`body`),
        allowClear: true,
        closeOnSelect: true
    });
    $("#"+object_id).on("change", () => {
        wasting_get_by_id_new($("#"+object_id).val());
    });
}

function closeModal(id){
    $('#'+id).modal('toggle');
}

