function enter_transfer_number(){
    var title = "";
    title = "Warehouse and Branch Transfer";
    
    var content =
        '<div class="modal large" data-keyboard="false" data-backdrop="static" id="enter_transfer_numberModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title">'+title+'<i style="float:right;font-size:30px" class="glyphicon glyphicon-remove" onclick="closeModal(\'enter_transfer_numberModal\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="row">\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">\n\
                            &nbsp;\n\
                        </div>\n\
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">\n\
                            <input type="text" class="form-control" id="search_transfer" placeholder="Warehouse Transfer Number">\n\
                        </div>\n\
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">\n\
                            <button  style="width:100%" class="btn btn-primary" type="button" onclick="show_to_confirm_transfer()">Search</button>\n\
                        </div>\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">\n\
                            &nbsp;\n\
                        </div>\n\
                    </div>\n\
                    <div class="row" style="margin-top:20px;">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <table style="width:100%" id="branch_transfer_details_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                <thead>\n\
                                    <tr>\n\
                                        <th style="width:110px;">Creation Date</th>\n\
                                        <th>From</th>\n\
                                        <th>To</th>\n\
                                        <th style="width:50px;">Item ID</th>\n\
                                        <th>Item Description</th>\n\
                                        <th>Color</th>\n\
                                        <th>Size</th>\n\
                                        <th style="width:50px;">TRS Qty</th>\n\
                                        <th style="width:70px;">Current Qty</th>\n\
                                        <th style="width:50px;">Price</th>\n\
                                        <th style="width:60px;">Total Price</th>\n\
                                        <th style="width:60px;">Total Cost</th>\n\
                                        <th style="width:60px;">&nbsp;</th>\n\
                                        <th style="width:40px;">&nbsp;</th>\n\
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
    
    $("#enter_transfer_numberModal").remove();
    $("body").append(content);
    
    $('#enter_transfer_numberModal').on('shown.bs.modal', function (e) {
        $("#search_transfer").focus();
        
        
        var stores_from_options="";
        stores_from_options+="<option value='0'>ALL (from)</option>";
        for(var i=0;i<stores_from.length;i++){
            stores_from_options+="<option value='"+stores_from[i].id+"'>"+stores_from[i].name+"</option>";
        }
        
        var stores_to_options="";
        stores_to_options+="<option value='0'>ALL (to)</option>";
        for(var i=0;i<stores_to.length;i++){
            stores_to_options+="<option value='"+stores_to[i].id+"'>"+stores_to[i].name+"</option>";
        }
        
        
        $('#branch_transfer_details_table').dataTable({
            ajax: "?r=transfer&f=getAllBranchTransfers&p0=thismonth&p1=0&p2=0",
                orderCellsTop: true,
                aoColumnDefs: [
                    { "targets": [12], "searchable": false, "orderable": false, "visible": true },
                    { "targets": [13], "searchable": false, "orderable": false, "visible": true },
                ],
                scrollY: '60vh',
                scrollCollapse: true,
                paging: true,
                select: true,
                bSort: false,
                dom: '<"toolbar_transfer_datefilter">frtip',
                initComplete: function( settings ) {
                    $("div.toolbar_transfer_datefilter").html('\n\
                    <div class="row">\n\
                        <div class="col-lg-2 col-md-2 col-xs-2" style="padding-left:15px;padding-right:5px;">\n\
                            <div class="btn-group" role="group" aria-label="" style="width:100% !important;">\n\
                                <input id="transfer_datefilter" class="form-control" type="text" placeholder="Select dat" style="cursor:pointer;width:100%;" />\n\
                            </div>\n\
                        </div>\n\
                        <div class="col-lg-2 col-md-2 col-xs-2" style="padding-left:15px;padding-right:5px;">\n\
                            <div class="btn-group" role="group" aria-label="" style="width:100% !important;">\n\
                                <select id="transfer_from" class="form-control" placeholder="" onchange="refrsh_bt_tr()" >\n\
                                    '+stores_from_options+'\n\
                                </select>\n\
                            </div>\n\
                        </div>\n\
                        <div class="col-lg-2 col-md-2 col-xs-2" style="padding-left:15px;padding-right:5px;">\n\
                            <div class="btn-group" role="group" aria-label="" style="width:100% !important;">\n\
                                <select id="transfer_to" class="form-control" placeholder="" onchange="refrsh_bt_tr()">\n\
                                    '+stores_to_options+'\n\
                                </select>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    ');
                    /*
                    $('#cashstatementDate').daterangepicker({
                        dateLimit:{month:12},
                        locale: {
                            format: 'YYYY-MM-DD'
                        },
                    });*/
                    
                    var start = moment();
                    var end = moment();

                    $('#transfer_datefilter').daterangepicker({
                        dateLimit:{month:12},
                        startDate: start,
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

                    $('#transfer_datefilter').on('apply.daterangepicker', function(ev) {
                        ev.preventDefault();
                        ev.stopPropagation();
                    });

                    $( "#transfer_datefilter" ).change(function() {
                        refrsh_bt_tr();
                    });
                    
                    
                    $("#transfer_from").selectpicker();
                    $("#transfer_to").selectpicker()
                    
                    
                },
                fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    $(nRow).addClass(aData[0]);
                },
            }); 
        });

        $('#enter_transfer_numberModal').on('hidden.bs.modal', function (e) {
            $("#enter_transfer_numberModal").remove();
        }); 

        $('#enter_transfer_numberModal').modal('show');
}

function refrsh_bt_tr(){
    var table = $('#branch_transfer_details_table').DataTable();
    table.ajax.url("?r=transfer&f=getAllBranchTransfers&p0="+$("#transfer_datefilter").val()+"&p1="+$("#transfer_from").val()+"&p2="+$("#transfer_to").val()).load(function () {

    }, false);
}

function show_to_confirm_transfer(){
    if($("#search_transfer").val()==""){
        return;
    }
    
    $(".sk-circle").center();
    $(".sk-circle-layer").show(); 
    
    var transfer_id=$("#search_transfer").val();
    
    var _data = [];
    $.getJSON("?r=transfer&f=show_to_confirm_transfer&p0="+transfer_id, function (data) {
        _data = data;
    }).done(function () {
        $(".sk-circle-layer").hide(); 
        if(_data.found==0){
            swal("The transfer cannot be located or is not synchronized.");
            return;
        }
        
        //$('#enter_transfer_numberModal').modal('hide');
        var title = "";
        title = "Confirm Transfer #"+transfer_id;

        var content =
            '<div class="modal large" data-keyboard="false" data-backdrop="static" id="ctrsModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title">'+title+'<i style="float:right;font-size:30px" class="glyphicon glyphicon-remove" onclick="closeModal(\'ctrsModal\')"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="display:none" id="confirm_btn_container">\n\
                                <button style="width: 100%;" type="button" class="btn btn-success" onclick="confirm_transfer('+transfer_id+')">Confirm</button>\n\
                            </div>\n\
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="display:none" id="confirmed_date">\n\
                                \n\
                            </div>\n\
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="display:none" id="confirmed_by">\n\
                                \n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                <table style="width:100%" id="transfer_details_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                    <thead>\n\
                                        <tr>\n\
                                            <th style="width:110px;">Creation Date</th>\n\
                                            <th>From</th>\n\
                                            <th>To</th>\n\
                                            <th style="width:80px;">Item ID</th>\n\
                                            <th>Item Description</th>\n\
                                            <th>Item Color</th>\n\
                                            <th>Item Size</th>\n\
                                            <th style="width:50px;">TRS Qty</th>\n\
                                            <th style="width:70px;">Current Qty</th>\n\
                                            <th style="width:50px;">Price</th>\n\
                                            <th style="width:60px;">Total Price</th>\n\
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

        $("#ctrsModal").remove();
        $("body").append(content);

        $('#ctrsModal').on('shown.bs.modal', function (e) {
            if(_data.transfer_details[0].confirmed_by_receiver_id==0){
                $("#confirm_btn_container").show();
                $("#confirmed_date").hide();
                $("#confirmed_by").hide();
                
                
            }
       
            if(_data.transfer_details[0].confirmed_by_receiver_id>0){
                $("#confirmed_date").html("<b>Confirmed Date:</b> "+_data.transfer_details[0].confirmed_by_receiver_id_date);
                $("#confirmed_date").show();
                
                $("#confirmed_by").html("<b>Confirmed By:</b> "+_data.by+" ("+_data.transfer_details[0].confirmed_by_receiver_id+")");
                $("#confirmed_by").show();
            }
        
            var jsonString={"data":[]};
            for(var i=0;i<_data.transfer_details.length;i++){
                const fruits =  [_data.transfer_details[i].creation_date, _data.transfer_details[i].from_store, _data.transfer_details[i].to_store, _data.transfer_details[i].item_id, _data.transfer_details[i].item_name, _data.transfer_details[i].color_name, _data.transfer_details[i].size_name, parseFloat(_data.transfer_details[i].qty), parseFloat(_data.transfer_details[i].cqty), parseFloat(_data.transfer_details[i].unit_price), parseFloat(_data.transfer_details[i].total_price)]; 
                jsonString.data.push(fruits);
            }            
            $('#transfer_details_table').DataTable({
                "data" : jsonString.data,
            });
            
            

        });

        $('#ctrsModal').on('hidden.bs.modal', function (e) {
            $("#ctrsModal").remove();
        }); 

        $('#ctrsModal').modal('show');
    }).fail(function() { 
       $(".sk-circle-layer").hide(); 
    })
    
    
}

function confirm_transfer(id){
    swal({
        title: "Are you sure that you received the stock transfer #"+id+"?",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes",
        closeOnConfirm: true,
        cancelButtonText: "Cancel",
    },
    function(isConfirm){
        if(isConfirm){
           $(".sk-circle-layer").show();
            $.getJSON("?r=transfer&f=confirm_transfer&p0="+id, function (data) {
                
            }).done(function () {
                $("#confirm_btn_container").hide();
                $("#confirmed_date").show();
                $("#confirmed_by").show();
                
                
                $(".sk-circle-layer").hide();
            }).fail(function() { 
                $(".sk-circle-layer").hide(); 
             });
        }
    });
}

function cancel_branch_transfer(id,store_id){
   swal({
        title: "Are you sure?",
        type: 'warning' ,
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "CANCEL TRANSFER",
        cancelButtonText: "CANCEL",
        closeOnConfirm: true,
        closeOnCancel: true
    },
    function(isConfirm) {
        if (isConfirm) {
            
            $(".sk-circle").center();
            $(".sk-circle-layer").show();
            $.getJSON("?r=transfer&f=cancel_branch_transfer&p0="+id+"&p1="+store_id, function (data) {

             }).done(function () {
                var table = $('#branch_transfer_details_table').DataTable();
                table.ajax.url("?r=transfer&f=getAllBranchTransfers&p0="+$("#transfer_datefilter").val()+"&p1="+$("#transfer_from").val()+"&p2="+$("#transfer_to").val()).load(function () {
                   
                }, false);
             })
             .fail(function() {
                  $(".sk-circle-layer").hide();
             })
             .always(function() {
                $(".sk-circle-layer").hide();  
             });
         }
         $(".inp_rv").removeAttr("disabled");
     });
}

function received_branch_transfer(id,store_id){
    swal({
        title: "Are you sure?",
        type: 'warning' ,
        showCancelButton: true,
        confirmButtonClass: "btn-success",
        confirmButtonText: "RECEIVED",
        cancelButtonText: "CANCEL",
        closeOnConfirm: true,
        closeOnCancel: true
    },
    function(isConfirm) {
        if (isConfirm) {
            
            $(".sk-circle").center();
            $(".sk-circle-layer").show();
            $.getJSON("?r=transfer&f=confirm_branch_transfer&p0="+id+"&p1="+store_id, function (data) {

             }).done(function () {
                var table = $('#branch_transfer_details_table').DataTable();
                table.ajax.url("?r=transfer&f=getAllBranchTransfers&p0="+$("#transfer_datefilter").val()+"&p1="+$("#transfer_from").val()+"&p2="+$("#transfer_to").val()).load(function () {
                   
                }, false);
             })
             .fail(function() {
                  $(".sk-circle-layer").hide();
             })
             .always(function() {
                $(".sk-circle-layer").hide();  
             });
         }
         $(".inp_rv").removeAttr("disabled");
     });
    }
