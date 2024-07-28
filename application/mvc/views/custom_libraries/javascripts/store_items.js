function add_items_to_store(id,current_store_id,source){
    var item_id = null;
    var item_sup_id = null;
    var item_desc = null;
    var item_cost= null;
    var item_barcode = null;
    var current_quantity = null;
    var measure = null;
    var global_admin_exist = null;
    var is_composite = null;
    $(".sk-circle-layer").show();
    $.getJSON("?r=store&f=get_item&p0="+id+"&p1="+current_store_id, function (data) {
        $.each(data, function (key, val) {
            item_id = parseInt(val.id);
            item_sup_id = parseInt(val.supplier_reference);
            item_desc = val.description;
            item_barcode = val.barcode;
            current_quantity = val.qty;
            measure = val.measure;
            item_cost = val.buying_cost;
            is_composite = val.is_composite;
            global_admin_exist=val.global_admin_exist;
        });
    }).done(function () {
        var qty_readonly = "";
        if(is_composite==1){
            qty_readonly = "readonly";
        }
        
       
        var content =
                '<div class="modal fade" data-keyboard="false" id="add_items_to_store" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">\n\
                    <div class="modal-dialog" role="document">\n\
                        <div class="modal-content">\n\
                            <form id="add_item_quantity" action="" method="post" enctype="multipart/form-data" >\n\
                            <input id="item_id" name="item_id" type="hidden" value="' + item_id+  '" />\n\
                            <input id="store_id" name="store_id" type="hidden" value="' + current_store_id+  '" />\n\
                            <div class="modal-header"> \n\
                                <h3 class="modal-title" id="exampleModalLongTitle"><i class="glyphicon glyphicon-plus"></i>&nbsp;Add Quantity</h3>\n\
                            </div>\n\
                            <div class="modal-body item_store">\n\
                                    <div class="form-group">\n\
                                        <div class="row">\n\
                                            <div class="col-sm-4">\n\
                                                <b>Store:</b>\n\
                                            </div>\n\
                                            <div class="col-sm-8">\n\
                                                '+$("#store_list option[value=\""+$("#store_list").val()+"\"]").text()+'\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="row">\n\
                                            <div class="col-sm-4">\n\
                                                <b>Item Reference:</b>\n\
                                            </div>\n\
                                            <div class="col-sm-8">\n\
                                                '+padItem(item_id)+'\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="row">\n\
                                            <div class="col-sm-4">\n\
                                                <b>Supplier:</b>\n\
                                            </div>\n\
                                            <div class="col-sm-8">\n\
                                                '+pad(item_sup_id,5)+'\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="row">\n\
                                            <div class="col-sm-4">\n\
                                                <b>Description:</b>\n\
                                            </div>\n\
                                            <div class="col-sm-8">\n\
                                                '+item_desc+'\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="row">\n\
                                            <div class="col-sm-4">\n\
                                                <b>Barcode:</b>\n\
                                            </div>\n\
                                            <div class="col-sm-8">\n\
                                                '+item_barcode+'\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="row">\n\
                                            <div class="col-sm-4">\n\
                                                <b>Current quantity:</b>\n\
                                            </div>\n\
                                            <div class="col-sm-8">\n\
                                                '+current_quantity+' '+measure+'\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="row">\n\
                                            <div class="col-sm-4">\n\
                                                <b>Current cost:</b>\n\
                                            </div>\n\
                                            <div class="col-sm-8">\n\
                                                '+item_cost+'\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="row" style="margin-top:5px;">\n\
                                            <div class="col-sm-4">\n\
                                                <b>Add quantity:</b>\n\
                                            </div>\n\
                                            <div class="col-sm-8">\n\
                                                <input '+qty_readonly+'  onkeyup="check_qty()" id="qty" value="" name="qty" type="text" class="form-control item_pc med_input " placeholder="" style="width:200px;" />\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="row" style="margin-top:10px;" id="assign_codes_container">\n\
                                            <div class="col-sm-4">\n\
                                                <b>Assign Codes:</b>\n\
                                            </div>\n\
                                            <div class="col-sm-8">\n\
                                                <input type="checkbox" id="assign_codes" name="assign_codes" value="" />\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="row" style="margin-top:10px;">\n\
                                            <div class="col-sm-4">\n\
                                                <b>New unit buying cost:</b>\n\
                                            </div>\n\
                                            <div class="col-sm-8">\n\
                                                <input '+qty_readonly+' id="cost" value="" name="cost" type="text" class="form-control med_input item_pc" placeholder="" style="width:200px;" />\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                            </div>\n\
                            <div class="modal-footer">\n\
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\n\
                                <button id="sub_qty" type="submit" class="btn btn-primary">Add</button>\n\
                            </div>\n\
                            <form/>\n\
                        </div>\n\
                    </div>\n\
                </div>';
            $('#add_items_to_store').remove();
            $('body').append(content);
            
            //$(".only_numeric").numeric({ negative : true});
            
            $('#add_items_to_store').modal('toggle');
            
            $('#add_items_to_store').on('hidden.bs.modal', function (e) {
                $('#add_items_to_store').modal('hide');
                if(source=="from_categories"){
                    var dt = $('#parent_categories_table').DataTable();
                    var sdata = dt.row('.selected', 0).data();
                    //update_categories(parseInt(sdata[0].split('-')[1]));
                }
                
            });
            
            $('#add_items_to_store').on('shown.bs.modal', function (e) {
                $(".sk-circle-layer").hide();
                $("#qty").focus();
                
                if(global_admin_exist==1){
                    $("#assign_codes_container").hide();
                }
                
                $("#samecost").click( function(){
                    if( $(this).is(':checked') ){
                        $("#cost").val(0);
                        $('#cost').prop('readonly', true);
                    }else{
                        $("#cost").val(0);
                        $('#cost').prop('readonly', false);
                    }
                });
                
                //$(".only_numeric").attr("autocomplete", "off");
                cleaves_class(".item_pc",5);
            });
            
            $("#add_item_quantity").on('submit', (function (e) {
                e.preventDefault();
                $("#sub_qty").attr("disabled","disabled");
                //unMaskValue("cost");
                if($("#qty").val()==0){
                    $('#add_items_to_store').modal('hide');
                }else{
                    $(".sk-circle-layer").show();
                    $.ajax({
                        url: "?r=store&f=add_qty",
                        type: "POST",
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        dataType: 'json',
                        success: function (data)
                        {
                           
                            var itmid=$("#item_id").val();
                            var itmqty=$("#qty").val();
                            
                            if(source=="from_low_qty"){
                                $('#add_items_to_store').modal('hide');
                                var table = $('#lacks_table').DataTable();
                                table.ajax.url("?r=reports&f=getLacksItems&p0="+current_store_id).load(function () {
                                    $(".sk-circle-layer").hide();
                                },false);
                            }else if(source=="from_categories"){
                                $('#add_items_to_store').modal('hide');
                                var dt = $('#parent_categories_table').DataTable();
                                var sdata = dt.row('.selected', 0).data();
                                
                                //update_categories(parseInt(sdata[0].split('-')[1]));
                                
                                var dt = $('#categories_table').DataTable();
                                var sdata_category = dt.row('.selected', 0).data();
                            
                                var table_details = $("#items_by_cat_table_details").DataTable();
                                table_details.ajax.url("?r=items&f=get_all_items_of_sub_category&p0="+parseInt(sdata_category[0].split('-')[1])+"&p1="+current_store_id).load(function () {
                                     $(".sk-circle-layer").hide();
                                },false);
                            
                                $(".sk-circle-layer").hide();
                            }
                            if(source=="from_group"){
                                 var table = $('#group_items_table').DataTable();
                                $(".sk-circle-layer").show();
                                $('#add_items_to_store').modal('hide');
                                table.ajax.url("?r=items&f=get_group&p0="+item_id).load(function () {
                                    $(".sk-circle-layer").hide();
                                },false);

                            }
                    
                            
                            if($('#items_store_table').length>0){
                                
                                $('#add_items_to_store').modal('hide');
                                var table = $('#items_store_table').DataTable();
                                table.ajax.url("?r=store&f=getItemInStore&p0="+current_store_id).load(function () {
                                        table.row('.' + padItem(item_id), {page: 'current'}).select();
                                        $(".sk-circle-layer").hide();
                                }, false);
                            }
                            
                            if($('#items_table').length>0){
                                
                                $('#add_items_to_store').modal('hide');
                                var table = $('#items_table').DataTable();
                                table.ajax.url('?r=items&f=getAllItems&p0='+current_store_id+"&p1="+current_category_id+"&p2="+current_subcategory_id+"&p3="+item_boxex+"&p4="+current_supplier_id+"&p5="+$("#stock_status").val()).load(function () {
                                    table.row('.' + padItem(item_id), {page: 'current'}).select();
                                    $(".sk-circle-layer").hide();
                                },false);
                                $(".sk-circle-layer").hide();
                            }
                            
                            if( $('#assign_codes').is(':checked') ){
                                create_unique_items_for_item(itmid,itmqty);
                            }
                        }
                    });
                }
            }));    
    });
}


function setExpiryItemOfStore(id,current_store_id,source){
    var item_id = null;
    var item_sup_id = null;
    var item_desc = null;
    var item_barcode = null;
    var current_quantity = null;
    var expiry_date = null;
    $(".sk-circle-layer").show();
    $.getJSON("?r=store&f=get_item&p0="+id+"&p1="+current_store_id, function (data) {
        $.each(data, function (key, val) {
            item_id = parseInt(val.id);
            item_sup_id = parseInt(val.supplier_reference);
            item_desc = val.description;
            item_barcode = val.barcode;
            current_quantity = val.qty;
            expiry_date = val.expiry_date;
        });
    }).done(function () {
        var content =
                '<div class="modal fade" id="set_expiry_date_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">\n\
                    <div class="modal-dialog" role="document">\n\
                        <div class="modal-content">\n\
                            <form id="set_expiry_date_form" action="" method="post" enctype="multipart/form-data" >\n\
                            <input id="item_id" name="item_id" type="hidden" value="' + item_id+  '" />\n\
                            <input id="store_id" name="store_id" type="hidden" value="' + current_store_id+  '" />\n\
                            <div class="modal-header"> \n\
                                <h3 class="modal-title" id="exampleModalLongTitle"><i class="glyphicon glyphicon-time"></i>&nbsp;Set Expiry Date</h3>\n\
                            </div>\n\
                            <div class="modal-body item_store">\n\
                                    <div class="form-group">\n\
                                        <div class="row">\n\
                                            <div class="col-sm-4">\n\
                                                <b>Store:</b>\n\
                                            </div>\n\
                                            <div class="col-sm-8">\n\
                                                '+$("#store_list option[value=\""+$("#store_list").val()+"\"]").text()+'\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="row">\n\
                                            <div class="col-sm-4">\n\
                                                <b>Item Reference:</b>\n\
                                            </div>\n\
                                            <div class="col-sm-8">\n\
                                                '+padItem(item_id)+'\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="row">\n\
                                            <div class="col-sm-4">\n\
                                                <b>Supplier:</b>\n\
                                            </div>\n\
                                            <div class="col-sm-8">\n\
                                                '+pad(item_sup_id,5)+'\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="row">\n\
                                            <div class="col-sm-4">\n\
                                                <b>Description:</b>\n\
                                            </div>\n\
                                            <div class="col-sm-8">\n\
                                                '+item_desc+'\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="row">\n\
                                            <div class="col-sm-4">\n\
                                                <b>Barcode:</b>\n\
                                            </div>\n\
                                            <div class="col-sm-8">\n\
                                                '+item_barcode+'\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="row" style="display:none" id="qty_e">\n\
                                            <div class="col-sm-4">\n\
                                                <b>Current quantity:</b>\n\
                                            </div>\n\
                                            <div class="col-sm-8">\n\
                                                '+current_quantity+'\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="row">\n\
                                            <div class="col-sm-4">\n\
                                                <b>Expiry Date:</b>\n\
                                            </div>\n\
                                            <div class="col-sm-8">\n\
                                                <input id="expiry_date" value="" name="expiry_date" type="text" class="form-control datepicker" placeholder="" style="width:200px;" />\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                            </div>\n\
                            <div class="modal-footer">\n\
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\n\
                                <button type="submit" class="btn btn-primary">Update</button>\n\
                            </div>\n\
                            <form/>\n\
                        </div>\n\
                    </div>\n\
                </div>';
            $('#set_expiry_date_modal').remove();
            $('body').append(content);
            $(".only_numeric").numeric({ negative : true});
            
            //if(user_role == 1){
                //$("#qty_e").show();
            //}
            
            $(".datepicker").datepicker(
                {
                    autoclose:true,
                    format: 'yyyy-mm-dd'
                }
            );
    
            if(expiry_date!=null){
                $(".datepicker").datepicker( "setDate", expiry_date );
            }
            
            $('#set_expiry_date_modal').modal('toggle');
            
            $('#set_expiry_date_modal').on('shown.bs.modal', function (e) {
                $("#expiry_date").focus();
                $(".sk-circle-layer").hide();
            });
            
            //alert(expiry_date);
            
            $("#set_expiry_date_form").on('submit', (function (e) {
                e.preventDefault();
                
                $(".sk-circle-layer").show();
                    $.ajax({
                        url: "?r=store&f=update_expiry_date",
                        type: "POST",
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        dataType: 'json',
                        success: function (data)
                        {
                            if(source=="from_items"){
                                $('#set_expiry_date_modal').modal('hide');
                                $(".sk-circle-layer").hide();
                            }else if(source=="from_categories"){
                                $('#set_expiry_date_modal').modal('hide');
                                $(".sk-circle-layer").hide();
                            }else{
                                $('#set_expiry_date_modal').modal('hide');
                                var table = $('#items_store_table').DataTable();
                                table.ajax.url("?r=store&f=getItemInStore&p0="+current_store_id).load(function () {
                                        table.row('.' + padItem(item_id), {page: 'current'}).select();
                                        $(".sk-circle-layer").hide();
                                }, false);
                            }
                        }
                    });
                    
            }));    
    });
}

function check_qty(){
    if($("#qty").val()=="" || $("#qty").val()==null){
        //$("#qty").val(0);
    }
}