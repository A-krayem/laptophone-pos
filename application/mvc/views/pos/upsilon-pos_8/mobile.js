function moreFunctions(mobile_shop){
    if(cashBox == 0){
        setCashbox();
        return;
    }
    var disp = ";display:none;";
    if(mobile_shop){
        disp = ";display:block;";
    }
    
    var disp_global_admin_exist = ";display:none;";
    if(global_admin_exist=="1"){
        disp_global_admin_exist = ";display:block;";
    }
    
    var disp_international_calls = ";display:none;";
    if(disable_international_calls=="0"){
        disp_international_calls = ";display:block;";
    }
    

    if(OMT_CLIENT==1){
        disp_international_calls = ";display:none;";
    }
    
    
    var disp_omt_version = ";display:block;";
    if(OMT_VERSION==true){
        var disp_omt_version = ";display:none;";
    }
    
    
    var disp_gaz_station=";display:block;";
    if(GAZ_STATION==0){
        disp_gaz_station = ";display:none;";
    }
   
    lockMainPos = true;
    var content =
    '<div class="modal" data-backdrop="static" id="mobile_section_modal" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title" '+dir_+'>More Functions<i style="float:'+float_+';font-size:35px" class="glyphicon glyphicon-remove" onclick="closeModal(\'mobile_section_modal\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body" id="noBarcodeItems">\n\
                    <div class="row">\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mobile_section" style="'+disp_omt_version+'">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mobile_section_inside" onclick="import_pda()"><i class="glyphicon glyphicon-phone" style="margin-top: 10px;float:left; font-size:30px;"></i>Import PDA</div>\n\
                        </div>\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mobile_section" style="'+disp_omt_version+'">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mobile_section_inside" onclick="addCustomItemModal()"><i class="glyphicon glyphicon-cog" style="margin-top: 10px;float:left; font-size:30px;"></i>Custom Item</div>\n\
                        </div>\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mobile_section" style="'+disp+';">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mobile_section_inside" onclick="showAvailablePhones(\'c\')"><i class="glyphicon glyphicon-transfer" style="margin-top: 10px;float:left; font-size:30px;"></i>Credit transfers</div>\n\
                        </div>\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mobile_section" style="'+disp+';">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mobile_section_inside" onclick="showAvailablePhones(\'d\')"><i class="icon-calendar blueButton_icon" style="margin-top: 10px;float:left; font-size:30px;"></i>Days</div>\n\
                        </div>\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mobile_section" style="'+disp+'">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mobile_section_inside" onclick="recharge_lines()"><i class="glyphicon glyphicon-phone blueButton_icon" style="margin-top: 10px;float:left; font-size:30px;"></i>Recharge Line Status</div>\n\
                        </div>\n\                        \n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mobile_section" style='+disp+'>\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mobile_section_inside" onclick="quick_stock_report()"><i class="glyphicon icon-store" style="margin-top: 10px;float:left; font-size:30px;"></i>Quick Stock Report</div>\n\
                        </div>\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mobile_section" style="'+disp+'">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mobile_section_inside" onclick="addInternationCallModal()"><i class="glyphicon glyphicon-phone blueButton_icon" style="margin-top: 10px;float:left; font-size:30px;"></i>Add International Calls</div>\n\
                        </div>\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mobile_section"  style="display:none">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mobile_section_inside" onclick="show_international_calls()"><i class="glyphicon glyphicon-phone blueButton_icon" style="margin-top: 10px;float:left; font-size:30px;"></i>Show International Calls</div>\n\
                        </div>\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mobile_section" style="display:none">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mobile_section_inside" onclick="searchBarcode_All_Stores()"><i class="glyphicon glyphicon-search blueButton_icon" style="margin-top: 10px;float:left; font-size:30px;"></i>Search All Branches</div>\n\
                        </div>\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mobile_section" style="display:none">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mobile_section_inside" onclick="add_debit_note(0,\'pos\')"><i class="glyphicon icon-payment blueButton_icon" style="margin-top: 10px;float:left; font-size:30px;"></i>Debit Note</div>\n\
                        </div>\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mobile_section" style="'+disp+'">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mobile_section_inside" onclick="initCustomSearchUniqueItems()"><i class="glyphicon glyphicon-search blueButton_icon" style="margin-top: 10px;float:left; font-size:30px;"></i>Search IMEI</div>\n\
                        </div>\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mobile_section" style="'+disp+'">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mobile_section_inside" onclick="imei_report()"><i class="glyphicon glyphicon-search blueButton_icon" style="margin-top: 10px;float:left; font-size:30px;"></i>IMEI REPORT</div>\n\
                        </div>\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mobile_section" style="'+disp_gaz_station+'">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mobile_section_inside" onclick="gaz_station(0)"><i class="glyphicon glyphicon-search blueButton_icon" style="margin-top: 10px;float:left; font-size:30px;"></i>Gaz Station</div>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>\n\
    </div>';
    $("#mobile_section_modal").modal("hide");
    $("body").append(content);
    
    $('#mobile_section_modal').on('show.bs.modal', function (e) {
        
    });
    
    $('#mobile_section_modal').on('shown.bs.modal', function (e) {
        
    });
    $('#mobile_section_modal').on('hide.bs.modal', function (e) {
       lockMainPos = false;
    });
    $('#mobile_section_modal').on('hidden.bs.modal', function (e) {
       lockMainPos = false;
       $('#mobile_section_modal').remove();
    });
    $('#mobile_section_modal').modal('show');
}


function addCustomItemModal(){
    $('#mobile_section_modal').modal('hide');
    lockMainPos = true;
    var content =
        '<div class="modal" data-keyboard="false" data-backdrop="static" id="addCustomItemModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title"><i class="icon-invoice"></i>&nbsp;Sell custom item<i style="float:right;font-size:30px" class="glyphicon glyphicon-remove" onclick="closeModal(\'addCustomItemModal\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="form-group">\n\
                        <input autocomplete="off" id="custom_item_name" name="custom_item_name" data-provide="typeahead" type="text" class="form-control" placeholder="Item description">\n\
                    </div>\n\
                    <div class="form-group" style="width:200px">\n\
                        <input autocomplete="off" id="custom_item_cost" name="custom_item_cost" data-provide="typeahead" type="text" value="0" class="form-control" placeholder="" >\n\
                    </div>\n\
                    <div class="form-group" style="width:150px">\n\
                        <button id="addCustomItemBtn" onclick="addCustomItemBtn()" type="button" class="btn btn-default" style="width: 100%; color: #000; font-size: 20px; font-weight: bold;">Add to invoice</button>\n\
                    </div>\n\
                </div>\n\
                <div class="modal-footer">\n\
                </div>\n\
            </div>\n\
        </div>\n\
    </div>';
  
     $('#addCustomItemModal').modal('hide');
    $("body").append(content);
    $(".only_numeric").numeric();
    $("#addCustomItemModal").centerWH();

    $('#addCustomItemModal').on('show.bs.modal', function (e) {
        
    });
    
    $('#addCustomItemModal').on('shown.bs.modal', function (e) {
        cleaves_id("custom_item_cost",5);
    });
    $('#addCustomItemModal').on('hide.bs.modal', function (e) {
       lockMainPos = false;
    });
    $('#addCustomItemModal').on('hidden.bs.modal', function (e) {
       lockMainPos = false;
       $('#addCustomItemModal').remove();
    });
    $('#addCustomItemModal').modal('show');
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
        infCust["price"] = parseFloat($("#custom_item_cost").val().replace(/[^0-9\.]/g, ''));
        inv.addCustomItem(infCust);
        $('#addCustomItemModal').modal('toggle');
    }
}

function get_name_description(device_id){
    for(var i=0;i<all_devices__.length;i++){
        if(all_devices__[i].device_id==device_id){
            return all_devices__[i].device_name;
        }
    }
}


function showAvailablePhones(action){
    $('#mobile_section_modal').modal('hide');
    lockMainPos = true;
    var devices = "";
    $.getJSON("?r=pos&f=getTransferPackages&p0=" + store_id, function (data) {
        all_devices__=[];
        $.each(data.devices, function (key, val) {
            all_devices__.push({"device_id":val.id,"device_name":val.description});
            devices+="<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 devicesC' onclick='transfer("+val.id+","+val.operator_id+",\""+action+"\")'><div style='padding:5px;background-color:"+val.color+"'><i class='icon-smartphone'></i>&nbsp;"+val.description+"&nbsp;&nbsp;&nbsp;("+val.balance+" $) - <b>"+val.operator_name+"</b></div></div>";
        });
    }).done(function () {
        var ic = "";
        if(action=="c"){
            ic="<i class='glyphicon glyphicon-usd'></i>";
        }else if(action=="d"){
            ic="<i class='icon-calendar'></i>";
        }else if(action=="sim"){
            ic="<i class='icon-sim'></i>";
        }
        var content =
            '<div class="modal" data-keyboard="false" data-backdrop="static" id="mobileDevicesModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
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
      
        $('#mobileDevicesModal').modal('hide');
        $("body").append(content);
        $("#mobileDevicesModal").centerWH();

        $('#mobileDevicesModal').on('show.bs.modal', function (e) {

        });
        $('#mobileDevicesModal').on('hide.bs.modal', function (e) {
            lockMainPos = false;
            $('#mobileDevicesModal').remove();
        });
        $('#mobileDevicesModal').modal('show');

    }).fail(function () {
        lockMainPos = false;
    }).always(function () {
        
    });
}

function transfer(id_device,operator_id_p,action) {
    $('#mobileDevicesModal').modal('hide');
    lockMainPos = true;
    var content =
    '<div class="modal" data-keyboard="false" data-backdrop="static" id="transferModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title"><i class="glyphicon glyphicon-usd"></i>&nbsp;Transfer credits - Touch/Alfa<i style="float:right;font-size:30px" class="glyphicon glyphicon-remove" onclick="closeModal(\'transferModal\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body" id="transfer_operators">\n\
                </div>\n\
                <div class="modal-footer">\n\
                </div>\n\
            </div>\n\
        </div>\n\
    </div>';

    $('#transferModal').modal('hide');
    
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
                if(action=="sim"){
                    $("#transfer_operators").append("<div class='row' id='transfer_operators_section_sim_" + val.id + "'></div>");
                }
                if(action=="alfaTopUp"){
                    $("#transfer_operators").append("<div class='row' id='transfer_operators_section_topup_" + val.id + "'></div>");
                }
            }
        });
        $.each(data.packages, function (key, val) {
            if(val.type==0){
                /* credits only */
                if(val.operator_id == operator_id_p && parseInt(val.days)==0){
                    if(val.no_sms_fees=="1"){
                        val.base_color = "#000";
                    }
                    $("#transfer_operators_section_" + val.operator_id).append("<div title='"+val.description+"' class='col-lg-2 col-md-2 col-sm-2 col-xs-2 PKG' onclick='addTransferItemToInvoice(" + val.id + ","+id_device+")'><div class='PKG_C' ><p style='background-color: " + val.base_color + " !important; font-size:16px;'><span style='text-decoration:underline'>" + val.operator_name + " " + val.qty + "$ - " + val.price + "</span> <br/><span style='display:none' >"+val.description+"</span></p></div></div>");
                }else{
                    /* days and credits */
                     if(val.operator_id == operator_id_p){

                        if(action=="alfaTopUp"){
                            if (val.description.indexOf("TOP") >= 0){
                                //$("#transfer_operators_section_topup_" + val.operator_id).append("<div class='col-lg-2 col-md-2 col-sm-2 col-xs-2 PKG' onclick='addTransferItemToInvoice(" + val.id + ","+id_device+")' style='padding-left:5px;padding-right:5px;'><div class='PKG_C' ><p style='background-color: " + val.base_color + " !important;'><span style='text-decoration:underline'>" + val.operator_name+ "" + " "+val.days+" days and " + val.qty + "$ - " + val.price + " " + default_currency_symbol + "</span> <br/><span style='display:none'>"+val.description+"</span></p></div></div>");
                            }
                        }else{

                            if (val.description.indexOf("TOP") == -1){ 
                                $("#transfer_operators_section_days_" + val.operator_id).append("<div  title='"+val.description+"' class='col-lg-2 col-md-2 col-sm-2 col-xs-2 PKG' onclick='addTransferItemToInvoice(" + val.id + ","+id_device+")' style='padding-left:5px;padding-right:5px;'><div class='PKG_C' ><p style='background-color: " + val.base_color + " !important;'><span style='text-decoration:underline'>" + val.operator_name+ "" + " "+val.days+" days and " + val.qty + "$ - " + val.price + "</span> <br/><span style='display:none'></span></p></div></div>");
                            }
                        }
                     }
                }
            }else if(val.type==1){
                $("#transfer_operators_section_sim_" + val.operator_id).append("<div class='col-lg-2 col-md-2 col-sm-2 col-xs-2 PKG' onclick='addSIMItemToInvoice(" + val.id + ","+id_device+")'><div class='PKG_C' ><p style='background-color: " + val.base_color + " !important;'>" + val.operator_name + " SIM and " + val.qty + "$ <br/>" + val.price + " " + default_currency_symbol + "</p></div></div>");
            }

        });
    }).done(function () {
        $("#transferModal").centerWH();
        $('#transferModal').on('show.bs.modal', function (e) {

        });
        $('#transferModal').on('hide.bs.modal', function (e) {
            lockMainPos = false;
            $('#transferModal').remove();
        });
        $('#transferModal').modal('show');
    }).fail(function () {
        lockMainPos = false;
    }).always(function () {

    });
}


function addTransferItemToInvoice(package_id,id_device){
    var info = [];
    var price = null;
    var operator_name = null;
    var qty = null;
    var days = null;
    var description = null;
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    $.getJSON("?r=pos&f=getPackageById&p0=" + package_id, function (data) {
        $.each(data, function (key, val) {
            price = val.price;
            operator_name = val.operator_name;
            qty = val.qty;
            days = val.days;
            description = val.description;
        });
    }).done(function () {
        info["description"] = description;
        
        info["price"] = price;
        info["mobile_transfer_item"] = package_id;
        info["id_device"] = id_device;
        
        inv.addMobileTransferItem(info);

        //$('#transferModal').modal('toggle');
    });
}

function quick_stock_report(){
    var content =
    '<div class="modal" data-backdrop="static" id="quick_stock_report_Modal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title" '+dir_+'>Quick Stock Report<i style="float:'+float_+';font-size:35px" class="glyphicon glyphicon-remove" onclick="closeModal(\'quick_stock_report_Modal\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="row">\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="background-color:#ddd;"><b style="font-size:22px;">Internationnal Calls Balance:</b> <span id="int_calls_balance" style="font-size:22px;"></span></div>\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="background-color:#ddd;"><b style="font-size:22px;">Alfa Balance:</b> <span id="alfa_balance" style="font-size:22px;"></span></div>\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="background-color:#ddd;"><b style="font-size:22px;">MTC Balance:</b> <span id="mtc_balance" style="font-size:22px;"></span></div>\n\
                    </div>\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <table id="quick_stock_table__" class="table table-striped table-bordered" cellspacing="0">\n\
                                <thead>\n\
                                    <tr>\n\
                                        <th style="width: 85px;">Item ID</th>\n\
                                        <th>Description</th>\n\
                                        <th style="width: 120px;">Barcode</th>\n\
                                        <th style="width: 100px;">Quantity</th>\n\
                                    </tr>\n\
                                </thead>\n\
                                <tbody></tbody>\n\
                                <tfoot>\n\
                                    <tr>\n\
                                        <th>Item ID</th>\n\
                                        <th>Description</th>\n\
                                        <th>Barcode</th>\n\
                                        <th>Quantity</th>\n\
                                    </tr>\n\
                                </tfoot>\n\
                            </table>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>\n\
    </div>';
  
    $('#quick_stock_report_Modal').modal('hide');
    
    $("body").append(content);
    $('#quick_stock_report_Modal').on('show.bs.modal', function (e) {

    });
    
    $('#quick_stock_report_Modal').on('shown.bs.modal', function (e) {
        $('#quick_stock_table__').show();
        
        var quick_stock_table__var =null;
        
        var search_fields = [0,1,2,3];
        var index = 0;
        $('#quick_stock_table__ tfoot th').each( function () {

            if(jQuery.inArray(index, search_fields) !== -1){
                var title = $(this).text();
                $(this).html( '<div class="inner-addon left-addon"><input id="idf_'+index+'" style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder="'+title+'" /></div>' );
                index++;
            }
        });

        quick_stock_table__var = $('#quick_stock_table__').DataTable({
            ajax: {
                url: "?r=pos&f=getStockReport",
                type: 'POST',
                error:function(xhr,status,error) {
                    logged_out_warning();
                },
            },
            orderCellsTop: true,
            iDisplayLength: 50,
            ordering: true,
            scrollY: '45vh',
            aoColumnDefs: [
                { "targets": [0], "searchable": true, "orderable": true, "visible": true },
                { "targets": [1], "searchable": true, "orderable": true, "visible": true },
                { "targets": [2], "searchable": true, "orderable": true, "visible": true },
                { "targets": [3], "searchable": true, "orderable": true, "visible": true },
            ],
            scrollCollapse: true,
            paging: true,
            initComplete: function(settings, json) {
                $(quick_stock_table__var.row(1)).addClass('selected');
                $('.dataTables_scrollBody thead tr').css({visibility:'collapse'});
                $(window).resize(function() {
                    $('.dataTables_scrollBody thead tr').css({visibility:'collapse'});
                });
                
                
                
            },
            
        });
        
        
        $('#quick_stock_table__').DataTable().on('mousedown',"tbody tr", function ( e, dt, type, indexes ) { 
            $('.selected').removeClass("selected");
            $(this).addClass('selected');
         });
         
         
         $('#quick_stock_table__').DataTable().columns().every( function () {
            var that = this;
            $( 'input', this.footer() ).on( 'keyup change', function () {
                //items_search.keys.disable();
                if ( that.search() !== this.value ) {
                    that.search( this.value ).draw();
                }
                //items_search.keys.enable();
            } );
        } );
        
        quick_report_info();
    });
    $('#quick_stock_report_Modal').on('hide.bs.modal', function (e) {
        $("#quick_stock_report_Modal").remove();
    });
    $('#quick_stock_report_Modal').modal('show');
}

function quick_report_info(){
    var int_calls_balance = 0;
    var alfa_balance = 0;
    var mtc_balance = 0;
    $.getJSON("?r=pos&f=quick_report_info", function (data) {
        int_calls_balance = data.interna_call_balance;
        alfa_balance = data.mobile_stock_value_alfa;
        mtc_balance = data.mobile_stock_value_mtc;
    }).done(function () {
        $("#int_calls_balance").html(int_calls_balance);
        $("#alfa_balance").html(alfa_balance); 
        $("#mtc_balance").html(mtc_balance); 
    });    
    
}

function addInternationalCallToPOS(index,munique){

    var call = [];
    call["description"] = $("#international_call_name_"+index).val();
    call["price"] = $("#international_call_price_"+index).val().replace(/[^0-9\.]/g, '');
    call["cost"] = $("#international_call_cost_"+index).val();
    
    call["munique"] = "POS"+munique;
    
    call["base_usd_cost"] = $("#base_international_call_cost_"+index).val();
    
    inv.addInternationnalCall(call);
    $("#row_internation_call_"+index).remove();
}

function international_call_duration_s_changed(index){
    var minutes = Math.floor($("#international_call_duration_s_"+index).val() / 60);
    
    var seconds = $("#international_call_duration_s_"+index).val() - minutes * 60;
    if(seconds>0){
        minutes++;
    }
    
    var pr=minutes*$("#rate_call_"+index).val();
    var quotient = Math.floor(pr/1000);
    var remainder = pr % 1000;
    if(remainder>0 && remainder<=500){
        pr=quotient*1000+500;
    }
    if(remainder>500){
        pr=quotient*1000+1000;
    }
    
    $("#international_call_duration_m_"+index).val(minutes);
    $("#international_call_price_"+index).val(pr);
    cleaves_id("international_call_price_"+index,2); 

}

function country_selected(index){
    for(var i=0;i<all_country_for_call.length;i++){
        if(all_country_for_call[i]["country_id"]==$("#country_sel_"+index).val()){
            $("#rate_call_"+index).val(all_country_for_call[i]["rate"]);
            international_call_duration_s_changed(index);
        }
    }
}

var index_inter_call = 1
function add_call_info(line){
    var info = line.split(";;");
    
    
    
    var minutes = Math.floor(info[0].split(';')[1]/60);
    
    var seconds = info[0].split(';')[1] - minutes * 60;
    if(seconds>0){
        minutes++;
    }
    var name_m = minutes+" M";
    
    
    var nm = "*IC*   "+info[0].split(';')[0]+"   "+info[1].split(';')[0]+" - "+name_m;
    
    var nmclass = "IC_"+info[0].split(';')[0]+"_"+info[1].split(';')[0];
    
    nmclass=nmclass.replace(/ /g, 'AA');
    nmclass=nmclass.replace(/-/g, 'BB');
    nmclass=nmclass.replace(/:/g, 'CC');
    
    $.getJSON("?r=pos&f=check_internationnal_call&p0="+nm, function (data) {
        if(data[0]==0 && $("."+nmclass).length==0 && $(".POS"+nmclass).length==0){
            
         
            
            create_internation_call(index_inter_call,nmclass);
            
            $("#international_call_duration_s_"+index_inter_call).val(info[0].split(';')[1]);
            
            var pr = parseFloat(info[1].split(';')[1])*international_call_rate;
  
            $("#base_international_call_cost_"+index_inter_call).val(info[1].split(';')[1]);

            $("#international_call_cost_"+index_inter_call).val(info[1].split(';')[1]*international_calls_source_rate);
            $("#international_call_price_"+index_inter_call).val(pr);
            
            
           
            $("#international_call_name_"+index_inter_call).val(nm);
            
            international_call_duration_s_changed(index_inter_call);
            index_inter_call++;
        }
    }).done(function () {
        $("#international_call_info").val(""); 
    });    
}


function detect_info(){
    setTimeout(function(){
        var lines = $('#international_call_info').val().split('\n');
        for(var i = 0;i < lines.length;i++){
           var info = lines[i].split(";;");
            if(info.length!=2){
                
            }else{
                add_call_info(lines[i]);
            }
        }
    },100);
    
}

;
function create_internation_call(index,nmclass){
    var tmp = '<div class="row row_internation_call '+nmclass+'" id="row_internation_call_'+index+'"><input id="rate_call_'+index+'" name="rate_call_'+index+'" type="hidden" value="0" ><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">\n\
            <div class="form-group">\n\
                <label for="country_sel">Country</label>\n\
                <select onchange="country_selected('+index+')" data-live-search="true" id="country_sel_'+index+'" name="country_sel" class="selectpicker form-control" style="width:100%">'+cties_options+'</select>\n\
            </div>\n\
        </div>\n\
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-2">\n\
            <div class="form-group">\n\
                <label for="international_call_name">Description</label>\n\
                <input readonly="readonly" autocomplete="off" id="international_call_name_'+index+'" name="international_call_name_'+index+'" type="text" class="form-control" placeholder="Item description" value="" >\n\
            </div>\n\
        </div>\n\
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">\n\
            <div class="form-group">\n\
                <label for="international_call_duration_s">Call Duration (S)</label>\n\
                <input readonly="readonly" onkeyup="international_call_duration_s_changed('+index+')" autocomplete="off" id="international_call_duration_s_'+index+'" name="international_call_duration_s_'+index_inter_call+'" type="text" value="" class="form-control price_input only_numeric" placeholder="" > \n\
            </div>\n\
        </div>\n\
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">\n\
            <div class="form-group">\n\
                <label for="international_call_duration_m">Call Duration (M)</label>\n\
                <input readonly="readonly" autocomplete="off" id="international_call_duration_m_'+index+'" name="international_call_duration_m_'+index+'" type="text" value="" class="form-control price_input only_numeric" placeholder="" >\n\
            </div>\n\
        </div>\n\
        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1" style="display:none;">\n\
            <div class="form-group">\n\
                <label for="international_call_cost">Call Cost</label>\n\
                <input id="base_international_call_cost_'+index+'" name="base_international_call_cost_'+index+'" type="hidden" value="0" >\n\
                <input autocomplete="off" id="international_call_cost_'+index+'" name="international_call_cost_'+index+'" type="text" value="0" class="form-control price_input only_numeric" placeholder="" >\n\
            </div>\n\
        </div>\n\
        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1" style="padding-left:5px;padding-right:5px;">\n\
            <div class="form-group">\n\
                <label for="international_call_price">Call Price</label>\n\
                <input readonly="readonly"  autocomplete="off" id="international_call_price_'+index+'" name="international_call_price_'+index+'" type="text" value="0" class="form-control price_input" placeholder="" >\n\
            </div>\n\
        </div>\n\
        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">\n\
            <div class="form-group">\n\
                <label for="international_call_value">&nbsp;</label>\n\
                <button id="addCustomItemBtn" onclick="addInternationalCallToPOS('+index+',\''+nmclass+'\')" type="button" class="btn btn-primary" style="width: 100%; ">Add</button>\n\
            </div>\n\
        </div>\n\
    </div></div>\n\
    ';
    $("#body_of_international_calls").append(tmp);
    $(".selectpicker").selectpicker();
    country_selected(index_inter_call);
    
}

function show_international_calls(){
    $('#mobile_section_modal').modal('hide');
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    lockMainPos = true;
    var content =
        '<div class="modal" data-keyboard="false" data-backdrop="static" id="show_mobile_international_call_modal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title"><i class="glyphicon glyphicon-phone blueButton_icon"></i>&nbsp;Internationnal Calls<i style="float:right;font-size:30px" class="glyphicon glyphicon-remove" onclick="closeModal(\'show_mobile_international_call_modal\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="row">\n\
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">\n\
                            <input id="int_date" class="form-control date_s" type="text" />\n\
                        </div>\n\
                    </div>\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <table id="show_mobile_international_call_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                <thead>\n\
                                    <tr>\n\
                                        <th>Description</th>\n\
                                        <th style="width: 250px !important;">Price</th>\n\
                                        <th style="width: 250px !important;"></th>\n\
                                        <th>&nbsp;</th>\n\
                                    </tr>\n\
                                </thead>\n\
                                <tfoot>\n\
                                    <tr>\n\
                                        <th>Description</th>\n\
                                        <th>Price</th>\n\
                                        <th>&nbsp;</th>\n\
                                        <th>&nbsp;</th>\n\
                                    </tr>\n\
                                </tfoot>\n\
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


    $('#show_mobile_international_call_modal').modal('hide');
    
    $("body").append(content);
    $(".only_numeric").numeric();
    $("#show_mobile_international_call_modal").centerWH();

    $('#show_mobile_international_call_modal').on('show.bs.modal', function (e) {

    });

    $('#show_mobile_international_call_modal').on('shown.bs.modal', function (e) {
        var tb = null;
        var search_fields = [0,1];
        var index = 0;
        $('#show_mobile_international_call_table tfoot th').each( function () {
            if(jQuery.inArray(index, search_fields) !== -1){
                var title = $(this).text();
                $(this).html( '<div class="inner-addon left-addon"><input style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder=" '+title+'" /></div>' );
                index++;
            }
        });
        tb = $('#show_mobile_international_call_table').DataTable({
            ajax: {
                url: "?r=pos&f=get_all_international_calls&p0=today",
                type: 'POST',
                error:function(xhr,status,error) {
                    //logged_out_warning();
                },
            },
            orderCellsTop: true,
            aoColumnDefs: [
                { "targets": [0], "searchable": true, "orderable": true, "visible": true },
                { "targets": [1], "searchable": true, "orderable": true, "visible": true },
                { "targets": [2], "searchable": true, "orderable": false, "visible": true },
                { "targets": [3], "searchable": true, "orderable": false, "visible": true },
            ],
            scrollCollapse: true,
            paging: true,
            initComplete: function(settings, json) {
                $(".sk-circle-layer").hide();
            },
        });
        
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
        
        $( "#int_date" ).change(function() {
            internationnal_date_changed();
        });
    });
    $('#show_mobile_international_call_modal').on('hide.bs.modal', function (e) {
       lockMainPos = false;
    });
    $('#show_mobile_international_call_modal').on('hidden.bs.modal', function (e) {
       lockMainPos = false;
       $('#show_mobile_international_call_modal').remove();
    });
    $('#show_mobile_international_call_modal').modal('show');
}

function internationnal_date_changed(){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var table = $('#show_mobile_international_call_table').DataTable();
    table.ajax.url("?r=pos&f=get_all_international_calls&p0="+$('.date_s').val()).load(function () {
        $(".sk-circle-layer").hide();
    },false);
}

var all_country_for_call = [];
var cties_options = "";
function addInternationCallModal(){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    cties_options = "";
    all_country_for_call = [];
    $.getJSON("?r=mobile_store&f=get_all_international_calls_details", function (data) {
        $.each(data, function (key, val) {
            all_country_for_call.push(val);
            cties_options += "<option selected value=" + val.country_id + ">" + val.country_txt + " " + val.rate_format + "/Minute</option>";
        });
    }).done(function () {
        $('#mobile_section_modal').modal('hide');
        $(".sk-circle-layer").hide();
        lockMainPos = true;
        var content =
            '<div class="modal" data-keyboard="false" data-backdrop="static" id="mobile_international_call_modal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title"><i class="glyphicon glyphicon-phone blueButton_icon"></i>&nbsp;Internationnal Call<i style="float:right;font-size:30px" class="glyphicon glyphicon-remove" onclick="closeModal(\'mobile_international_call_modal\')"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row" id="body_of_international_calls">\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">\n\
                                <div class="form-group">\n\
                                    <label for="international_call_info">Paste Info here</label>\n\
                                    <textarea onpaste="detect_info()" autocomplete="off" id="international_call_info" name="international_call_info" type="text" value="" class="form-control" placeholder="" ></textarea> \n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-footer">\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>';

        $('#mobile_international_call_modal').modal('hide');
        
        $("body").append(content);
        $(".only_numeric").numeric();
        $("#mobile_international_call_modal").centerWH();

        $('#mobile_international_call_modal').on('show.bs.modal', function (e) {
            
        });

        $('#mobile_international_call_modal').on('shown.bs.modal', function (e) {
            $("#international_call_info").focus();
            
        });
        $('#mobile_international_call_modal').on('hide.bs.modal', function (e) {
           lockMainPos = false;
           index_inter_call = 1;
        });
        $('#mobile_international_call_modal').on('hidden.bs.modal', function (e) {
           lockMainPos = false;
           index_inter_call = 1;
           $('#mobile_international_call_modal').remove();
        });
        $('#mobile_international_call_modal').modal('show');
    });
    
    
}


function recharge_lines(){
    $('#mobile_section_modal').modal('hide');
    
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    
    var content =
    '<div class="modal medium-md" data-backdrop="static" id="rechargeModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title">Recharge line status<i style="float:right;font-size:35px" class="glyphicon glyphicon-remove" onclick="closeModal(\'rechargeModal\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <table id="recharge_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                <thead>\n\
                                    <tr>\n\
                                        <th style="width: 100px !important;">Device ID</th>\n\
                                        <th>Line type</th>\n\
                                        <th style="width: 120px !important;">Balance</th>\n\
                                        <th style="width: 120px !important;">Expiry Date</th>\n\
                                        <th style="width: 100px !important;">Action</th>\n\
                                        <th style="width: 100px !important;">History</th>\n\
                                    </tr>\n\
                                </thead>\n\
                                <tbody></tbody>\n\
                            </table>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>\n\
    </div>';
   
    $('#rechargeModal').modal('hide');
    
    $("body").append(content);
    $('#rechargeModal').on('show.bs.modal', function (e) {

    });

    $('#rechargeModal').on('shown.bs.modal', function (e) {
        var chq_table = null;
        var search_fields = [];
        var index = 0;
        $('#recharge_table tfoot th').each( function () {
            if(jQuery.inArray(index, search_fields) !== -1){
                var title = $(this).text();
                $(this).html( '<div class="inner-addon left-addon"><input style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder=" '+title+'" /></div>' );
                index++;
            }
        });
                    
        recharge_table = $('#recharge_table').DataTable({
            ajax: {
                url: "?r=mobile_store&f=get_devices&p0=0",
                type: 'POST',
                error:function(xhr,status,error) {
                   
                },
            },
            orderCellsTop: true,
            aoColumnDefs: [
                { "targets": [0], "searchable": true, "orderable": true, "visible": false },
                { "targets": [1], "searchable": true, "orderable": true, "visible": true },
                { "targets": [2], "searchable": true, "orderable": true, "visible": true },
                { "targets": [3], "searchable": true, "orderable": true, "visible": true },
                { "targets": [4], "searchable": true, "orderable": false, "visible": true },
                { "targets": [5], "searchable": true, "orderable": false, "visible": true },
            ],
            scrollCollapse: true,
            paging: true,
            initComplete: function(settings, json) { 
                
                $(".sk-circle-layer").hide();
            },
            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $(nRow).addClass(""+aData[0]);
            },
            fnDrawCallback: updateRows_recharge,
        });
        
        $('#recharge_table ').DataTable().on('mousedown',"tbody tr", function ( e, dt, type, indexes ) { 
            $('#recharge_table .selected').removeClass("selected");
            $(this).addClass('selected');
        });
    });
    $('#rechargeModal').on('hide.bs.modal', function (e) {
        $("#rechargeModal").remove();
    });
    $('#rechargeModal').modal('show');
    
}

function updateRows_recharge(){
    var table = $('#recharge_table').DataTable();
    var p = table.rows({ page: 'current' }).nodes();
    for (var k = 0; k < p.length; k++){
        var index = table.row(p[k]).index();
        table.cell(index, 4).data("<button onclick='rech_line("+table.cell(index, 0).data()+")' type='button' class='btn btn-xs btn-info' style='width:100%; font-size:13px;'><b>Recharge</b></button>");
        table.cell(index, 5).data("<button onclick='history_rech_line("+table.cell(index, 0).data()+")' type='button' class='btn btn-xs btn-success' style='width:100%; font-size:13px;'><b>History</b></button>");
    }
}


function updateRows_exerecharge(){
    var table = $('#history_table').DataTable();
    var p = table.rows({ page: 'current' }).nodes();
    for (var k = 0; k < p.length; k++){
        var index = table.row(p[k]).index();
        if(table.cell(index, 7).data()==1)
            table.cell(index, 6).data("<button onclick='cancel_recharge("+table.cell(index, 0).data()+")' type='button' class='btn btn-xs btn-info' style='width:100%; font-size:13px;'><b>Cancel</b></button>");
    }
}


function cancel_recharge(recharge_id){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    $.getJSON("?r=mobile_store&f=cancel_recharge&p0="+recharge_id, function (data) {
        
    }).done(function () {
        $(".sk-circle").center();
        $(".sk-circle-layer").show();
        var table = $('#history_table').DataTable();
        table.ajax.url("?r=mobile_store&f=get_recharge_history&p0="+$("#device_r_id").val()).load(function () {
            $(".sk-circle-layer").hide();
        },false);
        
        
        var table = $('#recharge_table').DataTable();
        table.ajax.url("?r=mobile_store&f=get_devices&p0=0").load(function () {
            $("."+$("#device_r_id").val()).addClass("selected");
        },false);
        
    });
}

function history_rech_line(device_id){
   $(".sk-circle").center();
    $(".sk-circle-layer").show();
    
    var content =
    '<div class="modal medium-md" data-backdrop="static" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <input name="device_r_id" id="device_r_id" type="hidden" value="'+device_id+'" />\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title">Line history<i style="float:right;font-size:35px" class="glyphicon glyphicon-remove" onclick="closeModal(\'historyModal\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <table id="history_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                <thead>\n\
                                    <tr>\n\
                                        <th style="width: 100px !important;">Recharge id</th>\n\
                                        <th style="width: 100px !important;">Recharge date</th>\n\
                                        <th>Operator</th>\n\
                                        <th style="width: 200px !important;">Package info</th>\n\
                                        <th style="width: 100px !important;">Extended from</th>\n\
                                        <th style="width: 100px !important;">Extended to</th>\n\
                                        <th style="width: 100px !important;">Action</th>\n\
                                        <th style="width: 100px !important;">Same shift and same operator</th>\n\
                                    </tr>\n\
                                </thead>\n\
                                <tbody></tbody>\n\
                                <tfoot>\n\
                                    <tr>\n\
                                        <th>Recharge id</th>\n\
                                        <th >Recharge date</th>\n\
                                        <th>Operator</th>\n\
                                        <th>Package info</th>\n\
                                        <th>Extended from</th>\n\
                                        <th>Extended to</th>\n\
                                        <th>&nbsp;</th>\n\
                                        <th>Same shift and same operator</th>\n\
                                    </tr>\n\
                                </tfoot>\n\
                            </table>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>\n\
    </div>';
   
    $('#historyModal').modal('hide');
    
    $("body").append(content);
    $('#historyModal').on('show.bs.modal', function (e) {

    });

    $('#historyModal').on('shown.bs.modal', function (e) {
        var chq_table = null;
        var search_fields = [0,1,2,3,4,5];
        var index = 0;
        $('#history_table tfoot th').each( function () {
            if(jQuery.inArray(index, search_fields) !== -1){
                var title = $(this).text();
                $(this).html( '<div class="inner-addon left-addon"><input style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder=" '+title+'" /></div>' );
                index++;
            }
        });
                    
        recharge_table = $('#history_table').DataTable({
            ajax: {
                url: "?r=mobile_store&f=get_recharge_history&p0="+device_id,
                type: 'POST',
                error:function(xhr,status,error) {
                   
                },
            },
            orderCellsTop: true,
            aoColumnDefs: [
                { "targets": [0], "searchable": true, "orderable": true, "visible": false },
                { "targets": [1], "searchable": true, "orderable": true, "visible": true },
                { "targets": [2], "searchable": true, "orderable": true, "visible": true },
                { "targets": [3], "searchable": true, "orderable": true, "visible": true },
                { "targets": [4], "searchable": true, "orderable": true, "visible": true },
                { "targets": [5], "searchable": true, "orderable": false, "visible": true },
                { "targets": [6], "searchable": true, "orderable": false, "visible": true },
                { "targets": [7], "searchable": true, "orderable": false, "visible": false },
            ],
            scrollCollapse: true,
            bSort:true,
            paging: true,
            initComplete: function(settings, json) { 
                
                $(".sk-circle-layer").hide();
            },
            fnDrawCallback: updateRows_exerecharge,
        });
        
        $('#history_table ').DataTable().on('mousedown',"tbody tr", function ( e, dt, type, indexes ) { 
            $('#history_table .selected').removeClass("selected");
            $(this).addClass('selected');
        });
        
        $('#history_table').DataTable().columns().every( function () {
            var that = this;
            $( 'input', this.footer() ).on( 'keyup change', function () {
                //items_search.keys.disable();
                if ( that.search() !== this.value ) {
                    that.search( this.value ).draw();
                }
                //items_search.keys.enable();
            } );
        } );
        
    });
    $('#historyModal').on('hide.bs.modal', function (e) {
        $("#historyModal").remove();
    });
    $('#historyModal').modal('show');
}

function rech_line(id){    
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var items_options = "";
    $.getJSON("?r=mobile_store&f=get_all_items_related_to_recharge&p0="+id, function (data) {
        $.each(data, function (key, val) {
            items_options += "<option value='" + val.id + "'>" + val.description + "</option>";
        });
    }).done(function () {
        var content =
        '<div class="modal" data-backdrop="static" id="rechargeexecModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title">Recharge line status<i style="float:right;font-size:35px" class="glyphicon glyphicon-remove" onclick="closeModal(\'rechargeexecModal\')"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">\n\
                                <div class="form-group">\n\
                                    <label for="country_sel">Package</label>\n\
                                    <select data-live-search="true" id="select_it_to_recharge" name="" class="selectpicker form-control" style="width:100%">'+items_options+'</select>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">\n\
                                <div class="form-group">\n\
                                    <label for="">&nbsp;</label>\n\
                                    <button onclick="execute_recharge('+id+')" type="button" class="btn btn-info" style="width: 100%;">Recharge</button>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>';
        $('#rechargeexecModal').modal('hide');
        $("body").append(content);
        $('#rechargeexecModal').on('show.bs.modal', function (e) {

        });

        $('#rechargeexecModal').on('shown.bs.modal', function (e) {
            $("#select_it_to_recharge").selectpicker();
            $(".sk-circle-layer").hide();
        });
        $('#rechargeexecModal').on('hide.bs.modal', function (e) {
            $("#rechargeexecModal").remove();
        });
        $('#rechargeexecModal').modal('show');
    });
}

function execute_recharge(device_id){
    var _data = [];
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    $.getJSON("?r=mobile_store&f=execute_recharge&p0="+device_id+"&p1="+$("#select_it_to_recharge").val(), function (data) {
       _data=data;
    }).done(function () {
        $('#rechargeexecModal').modal('hide');

        var table = $('#recharge_table').DataTable();
        table.ajax.url("?r=mobile_store&f=get_devices&p0=0").load(function () {
            $("."+device_id).addClass("selected");
            $(".sk-circle-layer").hide();
        },false);
        
        
        
    });
}

function import_pda(){
    inv.reset();
    $("#mobile_section_modal").modal("hide");
    var content =
        '<div class="modal large" data-backdrop="static"  id="pda_modal" role="dialog" >\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title">Import from PDA<i style="float:right;font-size:35px" class="glyphicon glyphicon-remove" onclick="closeModal(\'pda_modal\')"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                <table id="preinvoices_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                    <thead>\n\
                                        <tr>\n\
                                            <th style="width: 70px !important;">ID</th>\n\
                                            <th style="width: 130px !important;">Created Date</th>\n\
                                            <th style="width: 130px !important;">Creation By</th>\n\
                                            <th>Client</th>\n\
                                            <th style="width: 90px !important;">Total</th>\n\
                                            <th style="width: 90px !important;">&nbsp;</th>\n\
                                        </tr>\n\
                                    </thead>\n\
                                    <tbody></tbody>\n\
                                </table>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-footer">\n\
                        <div class="row" style="margin-top:20px;">\n\
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">\n\
                                \n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>';
 
        $('#pda_modal').modal('hide');
        $("body").append(content);
        $('#pda_modal').on('show.bs.modal', function (e) {
            
        });

        $('#pda_modal').on('shown.bs.modal', function (e) {       
            items_search = $('#preinvoices_table').DataTable({
                ajax: {
                    url: "?r=invoice&f=get_preinvoices",
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
                    { "targets": [5], "searchable": true, "orderable": false, "visible": true },
                ],
                scrollCollapse: true,
                paging: true,
                initComplete: function(settings, json) {

                },
                fnDrawCallback: updateRows_preinvoices,
            });

        });

        $('#pda_modal').on('hide.bs.modal', function (e) {
            $("#pda_modal").remove();
        });
        $('#pda_modal').modal('show');
}

function updateRows_preinvoices(){
    
}

var ajax_req_interv=0;
function load_pending_invoice_id(id){
    ajax_req_interv=0;
    $('#pda_modal').modal('hide');
    var _data=[];
    $.getJSON("?r=invoice&f=load_pending_invoice_id&p0="+id, function (data) {
        _data=data;
    }).done(function () {
        
        if(_data.client_id>0){
            inv.setCustomerId(parseInt(_data.client_id));
        }
        for(var i=0;i<_data.items.length;i++){
            processElement(_data.items[i].item_id,_data.items[i].qty, i); 
        }
    });
}

function processElement(item_id,qty, index) {
    ajax_req_interv+=500;
    setTimeout(function() {
        inv.getItemById(item_id,qty,index);
    }, ajax_req_interv); // Change the delay time (in milliseconds) as needed
}


function imei_report(){
    w=window.open('?r=printing&f=imei_report&p0=thismonth'); 
}