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
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mobile_section" style='+disp+'>\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mobile_section_inside" onclick="addCustomItemModal()"><i class="glyphicon glyphicon-cog" style="margin-top: 10px;float:left; font-size:30px;"></i>Repairing Devices</div>\n\
                        </div>\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mobile_section" style='+disp+'>\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mobile_section_inside" onclick="showAvailablePhones(\'c\')"><i class="glyphicon glyphicon-transfer" style="margin-top: 10px;float:left; font-size:30px;"></i>Credit transfers</div>\n\
                        </div>\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mobile_section" style='+disp+'>\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mobile_section_inside" onclick="showAvailablePhones(\'d\')"><i class="icon-calendar blueButton_icon" style="margin-top: 10px;float:left; font-size:30px;"></i>Days </div>\n\
                        </div>\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mobile_section" style='+disp+'>\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mobile_section_inside" onclick="quick_stock_report()"><i class="glyphicon icon-store" style="margin-top: 10px;float:left; font-size:30px;"></i>Quick Stock Report</div>\n\
                        </div>\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mobile_section" style='+disp+'>\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mobile_section_inside" onclick="addInternationCallModal()"><i class="glyphicon glyphicon-phone blueButton_icon" style="margin-top: 10px;float:left; font-size:30px;"></i>Add International Calls</div>\n\
                        </div>\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mobile_section" style='+disp+'>\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mobile_section_inside" onclick="show_international_calls()"><i class="glyphicon glyphicon-phone blueButton_icon" style="margin-top: 10px;float:left; font-size:30px;"></i>Show International Calls</div>\n\
                        </div>\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mobile_section" style="display:none">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mobile_section_inside" onclick="searchBarcode_All_Stores()"><i class="glyphicon glyphicon-search blueButton_icon" style="margin-top: 10px;float:left; font-size:30px;"></i>Search All Branches</div>\n\
                        </div>\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mobile_section" style="display:none">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mobile_section_inside" onclick="add_debit_note(0,\'pos\')"><i class="glyphicon icon-payment blueButton_icon" style="margin-top: 10px;float:left; font-size:30px;"></i>Debit Note</div>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>\n\
    </div>';
    $("#mobile_section_modal").remove();
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

    $('#addCustomItemModal').on('show.bs.modal', function (e) {
        
    });
    
    $('#addCustomItemModal').on('shown.bs.modal', function (e) {
        
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
        infCust["price"] = parseFloat($("#custom_item_cost").val());
        inv.addCustomItem(infCust);
        $('#addCustomItemModal').modal('toggle');
    }
}


function showAvailablePhones(action){
    $('#mobile_section_modal').modal('hide');
    lockMainPos = true;
    var devices = "";
    $.getJSON("?r=pos&f=getTransferPackages&p0=" + store_id, function (data) {
        $.each(data.devices, function (key, val) {
            devices+="<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 devicesC' onclick='transfer("+val.id+","+val.operator_id+",\""+action+"\")'><div style='padding:5px;background-color:"+val.color+"'><i class='icon-smartphone'></i>&nbsp;"+val.description+"("+val.balance+" $) - <b>"+val.operator_name+"</b></div></div>";
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
        $("#mobileDevicesModal").remove();
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
                    $("#transfer_operators_section_" + val.operator_id).append("<div title='"+val.description+"' class='col-lg-2 col-md-2 col-sm-2 col-xs-2 PKG' onclick='addTransferItemToInvoice(" + val.id + ","+id_device+")'><div class='PKG_C' ><p style='background-color: " + val.base_color + " !important; font-size:16px;'><span style='text-decoration:underline'>" + val.operator_name + " " + val.qty + "$ - " + val.price + " " + default_currency_symbol + "</span> <br/><span style='display:none' >"+val.description+"</span></p></div></div>");
                }else{
                    /* days and credits */
                     if(val.operator_id == operator_id_p){

                        if(action=="alfaTopUp"){
                            if (val.description.indexOf("TOP") >= 0){
                                //$("#transfer_operators_section_topup_" + val.operator_id).append("<div class='col-lg-2 col-md-2 col-sm-2 col-xs-2 PKG' onclick='addTransferItemToInvoice(" + val.id + ","+id_device+")' style='padding-left:5px;padding-right:5px;'><div class='PKG_C' ><p style='background-color: " + val.base_color + " !important;'><span style='text-decoration:underline'>" + val.operator_name+ "" + " "+val.days+" days and " + val.qty + "$ - " + val.price + " " + default_currency_symbol + "</span> <br/><span style='display:none'>"+val.description+"</span></p></div></div>");
                            }
                        }else{

                            if (val.description.indexOf("TOP") == -1){ 
                                $("#transfer_operators_section_days_" + val.operator_id).append("<div  title='"+val.description+"' class='col-lg-2 col-md-2 col-sm-2 col-xs-2 PKG' onclick='addTransferItemToInvoice(" + val.id + ","+id_device+")' style='padding-left:5px;padding-right:5px;'><div class='PKG_C' ><p style='background-color: " + val.base_color + " !important;'><span style='text-decoration:underline'>" + val.operator_name+ "" + " "+val.days+" days and " + val.qty + "$ - " + val.price + " " + default_currency_symbol + "</span> <br/><span style='display:none'></span></p></div></div>");
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
        /*
        if(days>0){
            if (description.indexOf("TOP") >=0){ 
                info["description"] = days+" days and "+qty+"$ "+operator_name+ " TOPUP"; 
            }else{
                info["description"] = days+" days and "+qty+"$ "+operator_name; 
            }
            zz
        }else{
            info["description"] = qty+"$ "+operator_name;
        }*/
        info["description"] = description;
        
        info["price"] = price;
        info["mobile_transfer_item"] = package_id;
        info["id_device"] = id_device;
        
        inv.addMobileTransferItem(info);
        $(".sk-circle-layer").hide();
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
                            </table>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>\n\
    </div>';
    $("#quick_stock_report_Modal").remove();
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
            ordering: false,
            scrollY: '45vh',
            aoColumnDefs: [
                { "targets": [0], "searchable": true, "orderable": false, "visible": true },
                { "targets": [1], "searchable": true, "orderable": false, "visible": true },
                { "targets": [2], "searchable": true, "orderable": false, "visible": true },
                { "targets": [3], "searchable": true, "orderable": false, "visible": true },
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
        
        $('#quick_stock_table__').on('key-focus.dt', function(e, datatable, cell){
            $(quick_stock_table__var.row(cell.index().row).node()).addClass('selected');
        });

        $('#quick_stock_table__').on('key-blur.dt', function(e, datatable, cell){
            $(quick_stock_table__var.row(cell.index().row).node()).removeClass('selected');
        });
        
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

function addInternationalCallToPOS(index){
    var call = [];
    call["description"] = $("#international_call_name_"+index).val();
    call["price"] = $("#international_call_price_"+index).val();
    call["cost"] = $("#international_call_cost_"+index).val();
    inv.addInternationnalCall(call);
    $("#row_internation_call_"+index).remove();
}

function international_call_duration_s_changed(index){
    var minutes = Math.floor($("#international_call_duration_s_"+index).val() / 60);
    
    var seconds = $("#international_call_duration_s_"+index).val() - minutes * 60;
    if(seconds>0){
        minutes++;
    }
    
    $("#international_call_duration_m_"+index).val(minutes);
    $("#international_call_price_"+index).val(minutes*$("#rate_call_"+index).val());
}

function country_selected(index){
    for(var i=0;i<all_country_for_call.length;i++){
        if(all_country_for_call[i]["country_id"]==$("#country_sel_"+index).val()){
            $("#rate_call_"+index).val(all_country_for_call[i]["rate"]);
            international_call_duration_s_changed(index);
        }
    }
}

function add_call_info(line){
    var info = line.split(";;");
    var nm = "IC_"+info[0].split(';')[0]+"_"+info[1].split(';')[0];
    $.getJSON("?r=pos&f=check_internationnal_call&p0="+nm, function (data) {
        if(data[0]==0){
            create_internation_call(index_inter_call);
            $("#international_call_duration_s_"+index_inter_call).val(info[0].split(';')[1]);
            $("#international_call_cost_"+index_inter_call).val(parseFloat(info[1].split(';')[1])*international_call_rate);
            $("#international_call_name_"+index_inter_call).val(nm);
            international_call_duration_s_changed(index_inter_call);
            index_inter_call++;
        }
    }).done(function () {
        $("#international_call_info").val(""); 
    });    
}

var index_inter_call = 1
function detect_info(){
    setTimeout(function(){
        var lines = $('#international_call_info').val().split('\n');
        for(var i = 0;i < lines.length;i++){
           var info = lines[i].split(";;");
            if(info.length!=2){
                //alert("Error");
            }else{
                add_call_info(lines[i]);
            }
        }
        /*
        var info = $("#international_call_info").val().split(";;");
        if(info.length!=2){
            alert("Error");
        }else{
            
            create_internation_call(index_inter_call);
            $("#international_call_duration_s_"+index_inter_call).val(info[0].split(';')[1]);
            $("#international_call_cost_"+index_inter_call).val(info[1].split(';')[1]);
            international_call_duration_s_changed(index_inter_call);
            index_inter_call++;
            $("#international_call_info").val("");  
        }*/
    },100);
    
}

;
function create_internation_call(index){
    var tmp = '<div class="row row_internation_call" id="row_internation_call_'+index+'"><input id="rate_call_'+index+'" name="rate_call_'+index+'" type="hidden" value="0" ><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">\n\
            <div class="form-group">\n\
                <label for="country_sel">Country</label>\n\
                <select onchange="country_selected('+index+')" data-live-search="true" id="country_sel_'+index+'" name="country_sel" class="selectpicker form-control" style="width:100%">'+cties_options+'</select>\n\
            </div>\n\
        </div>\n\
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-2">\n\
            <div class="form-group">\n\
                <label for="international_call_name">Description</label>\n\
                <input autocomplete="off" id="international_call_name_'+index+'" name="international_call_name_'+index+'" type="text" class="form-control" placeholder="Item description" value="" >\n\
            </div>\n\
        </div>\n\
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">\n\
            <div class="form-group">\n\
                <label for="international_call_duration_s">Call Duration (Seconds)</label>\n\
                <input onkeyup="international_call_duration_s_changed('+index+')" autocomplete="off" id="international_call_duration_s_'+index+'" name="international_call_duration_s_'+index_inter_call+'" type="text" value="" class="form-control only_numeric" placeholder="" > \n\
            </div>\n\
        </div>\n\
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">\n\
            <div class="form-group">\n\
                <label for="international_call_duration_m">Call Duration (Minutes)</label>\n\
                <input autocomplete="off" id="international_call_duration_m_'+index+'" name="international_call_duration_m_'+index+'" type="text" value="" class="form-control only_numeric" placeholder="" > \n\
            </div>\n\
        </div>\n\
        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1" style="display:none;">\n\
            <div class="form-group">\n\
                <label for="international_call_cost">Call Cost</label>\n\
                <input autocomplete="off" id="international_call_cost_'+index+'" name="international_call_cost_'+index+'" type="text" value="0" class="form-control only_numeric" placeholder="" > \n\
            </div>\n\
        </div>\n\
        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">\n\
            <div class="form-group">\n\
                <label for="international_call_price">Call Price</label>\n\
                <input autocomplete="off" id="international_call_price_'+index+'" name="international_call_price_'+index+'" type="text" value="0" class="form-control only_numeric" placeholder="" > \n\
            </div>\n\
        </div>\n\
        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">\n\
            <div class="form-group">\n\
                <label for="international_call_value">&nbsp;</label>\n\
                <button id="addCustomItemBtn" onclick="addInternationalCallToPOS('+index+')" type="button" class="btn btn-primary" style="width: 100%; ">Add</button>                           \n\
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

    $("#show_mobile_international_call_modal").remove();
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
            cties_options += "<option selected value=" + val.country_id + ">" + val.country_txt + " " + val.rate + "/Minute</option>";
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

        $("#mobile_international_call_modal").remove();
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