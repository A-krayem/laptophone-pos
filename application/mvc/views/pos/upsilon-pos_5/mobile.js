function mobileSection(){
    lockMainPos = true;
    var content =
    '<div class="modal" data-backdrop="static" id="mobile_section_modal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title" '+dir_+'>Mobile Shop<i style="float:'+float_+';font-size:35px" class="glyphicon glyphicon-remove" onclick="closeModal(\'mobile_section_modal\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body" id="noBarcodeItems">\n\
                    <div class="row">\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mobile_section">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mobile_section_inside" onclick="addCustomItemModal()"><i class="glyphicon glyphicon-cog" style="margin-top: 10px;float:left; font-size:30px;"></i>Repairing Devices</div>\n\
                        </div>\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mobile_section">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mobile_section_inside" onclick="showAvailablePhones(\'c\')"><i class="glyphicon glyphicon-transfer" style="margin-top: 10px;float:left; font-size:30px;"></i>Credit transfers</div>\n\
                        </div>\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mobile_section">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mobile_section_inside" onclick="showAvailablePhones(\'d\')"><i class="icon-calendar blueButton_icon" style="margin-top: 10px;float:left; font-size:30px;"></i>Days </div>\n\
                        </div>\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mobile_section">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mobile_section_inside" onclick="quick_stock_report()"><i class="glyphicon icon-store" style="margin-top: 10px;float:left; font-size:30px;"></i>Quick Report</div>\n\
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
                    $("#transfer_operators_section_" + val.operator_id).append("<div class='col-lg-3 col-md-3 col-sm-12 col-xs-12 PKG' onclick='addTransferItemToInvoice(" + val.id + ","+id_device+")'><div class='PKG_C' ><p style='background-color: " + val.base_color + " !important; font-size:16px;'><span style='text-decoration:underline'>" + val.operator_name + " " + val.qty + "$ - " + val.price + " " + default_currency_symbol + "</span> <br/>"+val.description+"</p></div></div>");
                }else{
                    /* days and credits */
                     if(val.operator_id == operator_id_p){

                        if(action=="alfaTopUp"){
                            if (val.description.indexOf("TOP") >= 0){
                                $("#transfer_operators_section_topup_" + val.operator_id).append("<div class='col-lg-3 col-md-3 col-sm-12 col-xs-12 PKG' onclick='addTransferItemToInvoice(" + val.id + ","+id_device+")' style='padding-left:5px;padding-right:5px;'><div class='PKG_C' ><p style='background-color: " + val.base_color + " !important;'><span style='text-decoration:underline'>" + val.operator_name+ "" + " "+val.days+" days and " + val.qty + "$ - " + val.price + " " + default_currency_symbol + "</span> <br/>"+val.description+"</p></div></div>");
                            }
                        }else{

                            if (val.description.indexOf("TOP") == -1){ 
                                $("#transfer_operators_section_days_" + val.operator_id).append("<div class='col-lg-3 col-md-3 col-sm-12 col-xs-12 PKG' onclick='addTransferItemToInvoice(" + val.id + ","+id_device+")' style='padding-left:5px;padding-right:5px;'><div class='PKG_C' ><p style='background-color: " + val.base_color + " !important;'><span style='text-decoration:underline'>" + val.operator_name+ "" + " "+val.days+" days and " + val.qty + "$ - " + val.price + " " + default_currency_symbol + "</span> <br/>"+val.description+"</p></div></div>");
                            }
                        }
                     }
                }
            }else if(val.type==1){
                $("#transfer_operators_section_sim_" + val.operator_id).append("<div class='col-lg-3 col-md-3 col-sm-12 col-xs-12 PKG' onclick='addSIMItemToInvoice(" + val.id + ","+id_device+")'><div class='PKG_C' ><p style='background-color: " + val.base_color + " !important;'>" + val.operator_name + " SIM and " + val.qty + "$ <br/>" + val.price + " " + default_currency_symbol + "</p></div></div>");
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
        if(days>0){
            if (description.indexOf("TOP") >=0){ 
                info["description"] = days+" days and "+qty+"$ "+operator_name+ " TOPUP"; 
            }else{
                info["description"] = days+" days and "+qty+"$ "+operator_name; 
            }
            
        }else{
            info["description"] = qty+"$ "+operator_name;
        }
        
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
                    <h3 class="modal-title" '+dir_+'>Quick Report<i style="float:'+float_+';font-size:35px" class="glyphicon glyphicon-remove" onclick="closeModal(\'quick_stock_report_Modal\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
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
                                <thead>\n\
                                    <tr>\n\
                                        <th>Item ID</th>\n\
                                        <th>Description</th>\n\
                                        <th>Barcode</th>\n\
                                        <th>Quantity</th>\n\
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
    });
    $('#quick_stock_report_Modal').on('hide.bs.modal', function (e) {
        $("#quick_stock_report_Modal").remove();
    });
    $('#quick_stock_report_Modal').modal('show');
}