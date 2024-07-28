var delivery_data=[];
function prepare_delivery_data(){
    $(".sk-circle").center();
    $(".sk-circle-layer").show(); 
    $.getJSON("?r=deliveries&f=prepare_delivery_data", function (data) {
        delivery_data = data;
    }).done(function () {
        show_delivery();
    });
}


function refresh_deliveries(){
    var table_name = "modal_all_delivery_table";
    if ($.fn.DataTable.isDataTable('#modal_all_delivery_table')) {
       /* var table = $('#'+table_name).DataTable();
        $(".sk-circle").center();
        $(".sk-circle-layer").show(); 
        table.ajax.url("?r=deliveries&f=get_delivery_details&p0=0&p1=0&p2=0&p3=0&p4=0&p5=0&p6=0&p7=0&p8=0&p9=0&p10=0").load(function () {
            table.page('last').draw(false);
            $(".sk-circle-layer").hide();
        }, false);*/
    } else {
        
        $('#'+table_name).show();

         var _cards_table__var =null;

         var search_fields = [0,1,2,3,4,5,6,7,8,9];
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
                 url: "?r=deliveries&f=get_delivery_details&p0=0&p1=0&p2=0&p3=0&p4=0&p5=0&p6=0&p7=0&p8=0&p9=0&p10=0",
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
                 { "targets": [6], "searchable": true, "orderable": true, "visible": true },
                 { "targets": [7], "searchable": true, "orderable": true, "visible": true },
                 { "targets": [8], "searchable": true, "orderable": true, "visible": true },
                 { "targets": [9], "searchable": true, "orderable": true, "visible": true },
             ],
             scrollCollapse: true,
             paging: true,
             bPaginate: false,
             bLengthChange: false,
             bFilter: true,
             bInfo: false,
             bAutoWidth: true,
             dom: '<"toolbar_delivery">frtip',
             initComplete: function(settings, json) {
                  $("div.toolbar_delivery").html('\n\
                     \n\
                     ');        

                     _cards_table__var.columns.adjust().draw();

                 $(".sk-circle-layer").hide();
             },
             fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                 $(nRow).addClass(aData[0]);
             },
             fnDrawCallback: setDeliveryOptions,
         });

         $('#'+table_name).DataTable().on('mousedown',"tr", function ( e, dt, type, indexes ) { 
             $('.selected').removeClass("selected");
             $(this).addClass('selected');
         });

         $('#'+table_name).on('click', 'td', function () {
             //if ($(this).index() == 3) {
                 //return false;
             //}
         });

         $('#'+table_name).DataTable().columns().every( function () {
             var that = this;
             $( 'input', this.footer() ).on( 'keyup change', function () {
                 search_in_datatable(this.value,that.index(),100,table_name);
             } );
         } );
    }
    
     
}

function refresh_companies(){
    var table_name = "delivery_companies_table";
    

    // Check if the DataTable is initialized
    if ($.fn.DataTable.isDataTable('#delivery_companies_table')) {
        /*var table = $('#'+table_name).DataTable();
        $(".sk-circle").center();
        $(".sk-circle-layer").show(); 
        table.ajax.url("?r=deliveries&f=get_delivery_companies&p0=0&p1=0&p2=0&p3=0&p4=0&p5=0&p6=0&p7=0&p8=0&p9=0&p10=0").load(function () {
            table.page('last').draw(false);
            $(".sk-circle-layer").hide();
        }, false);*/
    } else {
        $('#' + table_name).show();

        var _table__var = null;

        var search_fields = [0, 1, 2, 3, 4, 5,6,7,8];
        var index = 0;
        $('#' + table_name + ' tfoot th').each(function () {
            if (jQuery.inArray(index, search_fields) !== -1) {
                var title = $(this).text();
                $(this).html('<input id="idf_' + index + '" style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder="' + title + '" />');
                index++;
            }
        });

        _table__var = $('#' + table_name).DataTable({
            ajax: {
                url: "?r=deliveries&f=get_delivery_companies&p0=0&p1=0&p2=0&p3=0&p4=0&p5=0&p6=0&p7=0&p8=0&p9=0&p10=0",
                type: 'POST',
                error: function (xhr, status, error) {
                },
            },
            responsive: true,
            orderCellsTop: true,
            scrollX: true,
            scrollY: "55vh",
            iDisplayLength: 100,
            aoColumnDefs: [
                {"targets": [0], "searchable": true, "orderable": true, "visible": true},
                {"targets": [1], "searchable": true, "orderable": true, "visible": true},
                {"targets": [2], "searchable": true, "orderable": true, "visible": true},
                {"targets": [3], "searchable": true, "orderable": true, "visible": true},
                {"targets": [4], "searchable": true, "orderable": true, "visible": true},
                {"targets": [5], "searchable": true, "orderable": true, "visible": true},
                {"targets": [6], "searchable": true, "orderable": true, "visible": true},
                {"targets": [7], "searchable": true, "orderable": true, "visible": true},
                {"targets": [8], "searchable": true, "orderable": true, "visible": true},
                {"targets": [9], "searchable": true, "orderable": false, "visible": true},
            ],
            scrollCollapse: true,
            paging: true,
            bPaginate: false,
            bLengthChange: false,
            bFilter: true,
            bInfo: false,
            bAutoWidth: true,
            dom: '<"toolbar_delivery_companies">frtip',
            initComplete: function (settings, json) {
                $("div.toolbar_delivery_companies").html('\n\
                \n\
                ');
                $(".sk-circle-layer").hide();
            },
            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $(nRow).addClass(aData[0]);
            },
            fnDrawCallback: setDeliveryCompaniesOptions,
        });
        
        $('#'+table_name).DataTable().on('mousedown',"tr", function ( e, dt, type, indexes ) { 
             $('.selected').removeClass("selected");
             $(this).addClass('selected');
         });

         $('#'+table_name).on('click', 'td', function () {
             //if ($(this).index() == 3) {
                 //return false;
             //}
         });

         $('#'+table_name).DataTable().columns().every( function () {
             var that = this;
             $( 'input', this.footer() ).on( 'keyup change', function () {
                search_in_datatable(this.value,that.index(),100,table_name);
             } );
         } );
    }
}

function edit_del_ref(invoice_id,object){
    var parent=$(object).parent();
    $(object).remove();
    var old_val=$(parent).html();
    $(parent).html('<input id="delref_'+invoice_id+'" onchange="changeref('+invoice_id+')" style="color:#000;width:100%;height:23px;" type="text" value="'+old_val+'">');
    
    //alert(invoice_id);
}

function show_delivery(){
    
    
    var table_name = "modal_all_delivery_table";
    var modal_name = "modal_all_delivery____";
    
    var content =
    '<div class="modal large" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-body">\n\
                    <i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\''+modal_name+'\')"></i>\n\
                    <ul class="nav nav-tabs" id="del_tabs">\n\
                        <li class="active"><a data-toggle="tab" href="#deliveries">Deliveries</a></li>\n\
                        <li><a data-toggle="tab" href="#companies">Companies</a></li>\n\
                        <li><a data-toggle="tab" href="#delivery_logs">Logs</a></li>\n\
                    </ul>\n\
                    <div class="tab-content">\n\
                        <div id="deliveries" class="tab-pane fade in active">\n\
                            <div class="row">\n\
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                    <table style="width:100%" id="'+table_name+'" class="table table-striped table-bordered" cellspacing="0">\n\
                                        <thead>\n\
                                            <tr>\n\
                                                <th>Reference</th>\n\
                                                <th>Company</th>\n\
                                                <th>Created At</th>\n\
                                                <th>Customer</th>\n\
                                                <th>Invoice</th>\n\
                                                <th>Amount</th>\n\
                                                <th>Fees</th>\n\
                                                <th>Total</th>\n\
                                                <th>Status</th>\n\
                                                <th>&nbsp;</th>\n\
                                            </tr>\n\
                                        </thead>\n\
                                        <tfoot>\n\
                                            <tr>\n\
                                                <th>Reference</th>\n\
                                                <th>Company</th>\n\
                                                <th>Created At</th>\n\
                                                <th>Customer</th>\n\
                                                <th>Invoice</th>\n\
                                                <th>Amount</th>\n\
                                                <th>Fees</th>\n\
                                                <th>Total</th>\n\
                                                <th>Status</th>\n\
                                                <th>&nbsp;</th>\n\
                                            </tr>\n\
                                        </tfoot>\n\
                                        <tbody></tbody>\n\
                                    </table>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div id="companies" class="tab-pane fade">\n\
                          <div class="row">\n\
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                    <table style="width:100%" id="delivery_companies_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                        <thead>\n\
                                            <tr>\n\
                                                <th style="width:70px;">ID</th>\n\
                                                <th>Company</th>\n\
                                                <th>Contact name</th>\n\
                                                <th>Phone</th>\n\
                                                <th>Starting Balance</th>\n\
                                                <th>Balance</th>\n\
                                                <th style="width:70px;">Pending</th>\n\
                                                <th style="width:70px;">Closed</th>\n\
                                                <th style="width:70px;">Rejected</th>\n\
                                                <th>&nbsp;</th>\n\
                                            </tr>\n\
                                        </thead>\n\
                                        <tfoot>\n\
                                            <tr>\n\
                                                <th>ID</th>\n\
                                                <th>Company</th>\n\
                                                <th>Contact name</th>\n\
                                                <th>Phone</th>\n\
                                                <th>Starting Balance</th>\n\
                                                <th>Balance</th>\n\
                                                <th>Pending</th>\n\
                                                <th>Closed</th>\n\
                                                <th>Rejected</th>\n\
                                                <th>&nbsp;</th>\n\
                                            </tr>\n\
                                        </tfoot>\n\
                                        <tbody></tbody>\n\
                                    </table>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div id="delivery_logs" class="tab-pane fade">\n\
                          <h3>Logs</h3>\n\
                          <p>This is the settings tab content.</p>\n\
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
        
        refresh_deliveries();
        
        
        // Function to be called when tab is changed
        function tabChanged(tabId) {
            
            if(tabId=="#companies"){
                refresh_companies();
            }
            
            if(tabId=="#deliveries"){
                refresh_deliveries();
                
            }
            
            //alert("Tab changed to: " + tabId);
            // Add your custom logic here
        }

        // Event listener for tab change
        $('#del_tabs a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var targetTab = $(e.target).attr("href"); // Get the target tab's ID
            tabChanged(targetTab); // Call the function with the target tab's ID
        });
       
    });
    $('#'+modal_name).on('hide.bs.modal', function (e) {
        $("#"+modal_name).remove();
    });
    $('#'+modal_name).modal('show');
}

function setDeliveryOptions(){
    var table = $('#modal_all_delivery_table').DataTable();
    var p = table.rows({ page: 'current' }).nodes();
    
    for (var k = 0; k < p.length; k++){
        var index = table.row(p[k]).index();

    }   
}

function setDeliveryCompaniesOptions(){
    var table = $('#delivery_companies_table').DataTable();
    var p = table.rows({ page: 'current' }).nodes();
    
    for (var k = 0; k < p.length; k++){
        var index = table.row(p[k]).index();
        
        table.cell(index, 9).data('<button onclick="open_stmt('+table.cell(index, 0).data()+')" type="button" class="btn btn-primary btn-xs" style="width:100%;font-size:14px !important;">Statement</button>');

    }
}


function show_all_delivery_pos(){
    $(".sk-circle").center();
    $(".sk-circle-layer").show(); 
    var table_name = "modal_all_delivery_table";
    var modal_name = "modal_all_delivery____";
    var modal_title = "Pending Deliveries";
    
    var content =
    '<div class="modal large" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
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
                                        <th style="width:100px;">Invoice nb</th>\n\
                                        <th style="width:100px;">Reference</th>\n\
                                        <th>Customer</th>\n\
                                        <th style="width:100px;">Invoice Amount</th>\n\
                                        <th style="width:100px;">Delivery fees</th>\n\
                                        <th style="width:100px;">Total Amount</th>\n\
                                        <th style="width:70px;">status</th>\n\
                                        <th style="width:60px;">&nbsp;</th>\n\
                                        <th style="width:70px;">Cashin</th>\n\
                                        <th style="width:60px;">Customer id</th>\n\
                                        <th style="width:60px;">&nbsp;</th>\n\
                                    </tr>\n\
                                </thead>\n\
                                <tfoot>\n\
                                    <tr>\n\
                                        <th>Invoice nb</th>\n\
                                        <th>Reference</th>\n\
                                        <th>Name</th>\n\
                                        <th>Total Amount</th>\n\
                                        <th>Delivery fees</th>\n\
                                        <th>Total Amount</th>\n\
                                        <th>&nbsp;</th>\n\
                                        <th>&nbsp;</th>\n\
                                        <th>&nbsp;</th>\n\
                                        <th>&nbsp;</th>\n\
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
    </div>';
    $('#'+modal_name).modal('hide');
    $("body").append(content);
    $('#'+modal_name).on('show.bs.modal', function (e) {

    });
    
    $('#'+modal_name).on('shown.bs.modal', function (e) {
        
        $('#'+table_name).show();
        
        var _cards_table__var =null;
        
        var search_fields = [0,1,2,3,4,5];
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
                url: "?r=invoice&f=get_deliveries&p0=1",
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
                { "targets": [6], "searchable": true, "orderable": true, "visible": true },
                { "targets": [7], "searchable": true, "orderable": false, "visible": false },
                { "targets": [8], "searchable": true, "orderable": false, "visible": true },
                { "targets": [9], "searchable": true, "orderable": false, "visible": false },
                { "targets": [10], "searchable": true, "orderable": false, "visible": true },
            ],
            scrollCollapse: true,
            paging: true,
            bPaginate: false,
            bLengthChange: false,
            bFilter: true,
            bInfo: false,
            bAutoWidth: true,
            dom: '<"toolbar_cuurency">frtip',
            initComplete: function(settings, json) {
                
                 $("div.toolbar_cuurency").html('\n\
                    <div class="col-lg-2 col-md-2 col-sm-12 pl2 pr2" >\n\
                        <div class="btn-group" role="group" aria-label="" style="width:100%">\n\
                            <select data-live-search="true" data-width="100%" id="filter_delivery_status" class="selectpicker" onchange="refresh_delivery_table()">\n\
                                <option value="0">All Deliveries</option>\n\
                                <option value="1" selected>Pending</option>\n\
                                <option value="2">Delivering</option>\n\
                                <option value="3">Delivered</option>\n\
                            </select>\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-lg-2 col-md-2 col-sm-12 pl2 pr2" >\n\
                        <div class="btn-group" role="group" aria-label="" style="width:100%">\n\
                            <button style="width:100%;" onclick="bulk_payment_for_delivery()" type="button" class="btn btn-primary ">BULK PAYMENT</button>\n\
                        </div>\n\
                    </div>\n\
                    ');   
                
                
                $(".selectpicker").selectpicker();
                $(".sk-circle-layer").hide();
            },
            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $(nRow).addClass(aData[0]);
            },
            fnDrawCallback: setDeliveryOptions,
        });
        
        $('#'+table_name).DataTable().on('mousedown',"tr", function ( e, dt, type, indexes ) { 
            $('.selected').removeClass("selected");
            $(this).addClass('selected');
        });
        
        $('#'+table_name).on('click', 'td', function () {
            //if ($(this).index() == 3) {
                //return false;
            //}
        });
        
        $('#'+table_name).DataTable().columns().every( function () {
            var that = this;
            $( 'input', this.footer() ).on( 'keyup change', function () {
                search_in_datatable(this.value,that.index(),500,table_name);
            } );
        } );
       
    });
    $('#'+modal_name).on('hide.bs.modal', function (e) {
        $("#"+modal_name).remove();
    });
    $('#'+modal_name).modal('show');
}

function status_changed(invoice_id){
    $(".sk-circle").center();
    $(".sk-circle-layer").show(); 
    $.getJSON("?r=invoice&f=status_changed&p0=" + invoice_id+"&p1="+$("#delivery_"+invoice_id).val(), function (data) {

    }).done(function () {
         refresh_delivery_table();
    });
}

function refresh_delivery_table(){
     $(".sk-circle").center();
    $(".sk-circle-layer").show(); 
    var table_details = $("#modal_all_delivery_table").DataTable();
        table_details.ajax.url("?r=invoice&f=get_deliveries&p0="+$("#filter_delivery_status").val()).load(function () {
            $(".sk-circle-layer").hide(); 
        },false);
}




function set_delivery_as_paid(customer_id,invoice_id){
    if(customer_id>0){
        $(".sk-circle").center();
        $(".sk-circle-layer").show();
        var _data=[];
        $.getJSON("?r=invoice&f=get_invoice_price&p0=" + invoice_id, function (data) {
            _data=data;
        }).done(function () {
             $(".sk-circle-layer").hide();
             add_customer_payment_new(customer_id,_data.price,invoice_id,0);
        });
        
        
        //addCustomerPaymentDetails(customer_id,0,[],"pos");
    }else{
        swal("Customer not found");
    }  
}

function set_delivery_as_done(id){
    swal({
        title: "Are you sure?",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Hide",
        closeOnConfirm: true
    },
    function(isConfirm){
       if(isConfirm){
            $(".sk-circle").center();
            $(".sk-circle-layer").show();

            $.getJSON("?r=invoice&f=set_delivery_as_done&p0="+id, function (data) {

            }).done(function () {
                var table_details = $("#modal_all_delivery_table").DataTable();
                table_details.ajax.url("?r=invoice&f=get_deliveries").load(function () {
                    $(".sk-circle-layer").hide(); 
                },false);
            });
       }
    }); 
}

function changeref(id){
    swal({
        title: "Are you sure?",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, change it!",
        closeOnConfirm: true
    },
    function(isConfirm){
       if(isConfirm){
           $(".sk-circle").center();
            $(".sk-circle-layer").show(); 
           $.getJSON("?r=invoice&f=change_delivery_reference&p0="+id+"&p1="+$("#delref_"+id).val(), function (data) {

            }).done(function () {
                $(".sk-circle-layer").hide();
            });
       }
    }); 
    
}

function setDeliveryOptions(){
    var table = $('#modal_all_delivery_table').DataTable();
    var p = table.rows({ page: 'current' }).nodes();
    
    for (var k = 0; k < p.length; k++){
        var index = table.row(p[k]).index();
        
        var delv_options='\
        <select  onchange="status_changed('+parseInt(table.cell(index, 0).data().split('-')[1])+')" data-width="100%" data-container="body"  id="delivery_'+parseInt(table.cell(index, 0).data().split('-')[1])+'" class="selectpicker delselect" style="background-color:#000 !important;">\n\
            <option value="1" title="Pending">Pending</option>\n\
            <option value="2" title="Delivering">Delivering</option>\n\
            <option value="3" title="Delivered">Delivered</option>\n\
        </select>\n\
        ';
        table.cell(index, 6).data(delv_options);
        $("#delivery_"+parseInt(table.cell(index, 0).data().split('-')[1])).selectpicker();
        $("#delivery_"+parseInt(table.cell(index, 0).data().split('-')[1])).selectpicker("val",table.cell(index, 7).data());
        
        table.cell(index, 8).data('<button onclick="set_delivery_as_paid('+table.cell(index, 9).data()+','+parseInt(table.cell(index, 0).data().split('-')[1])+')" type="button" class="btn btn-default btn-sm" style="width:100%;font-size:14px !important;">Payment</button>');
        
        table.cell(index, 10).data('\
            <button onclick="set_delivery_as_done('+parseInt(table.cell(index, 0).data().split('-')[1])+')" type="button" class="btn btn-default btn-sm" style="width:100%;font-size:14px !important;">Hide</button>');
        
        refreshcolors();
    }
}

function refreshcolors(){
    var table = $('#modal_all_delivery_table').DataTable();
    var p = table.rows({ page: 'current' }).nodes();
    for (var k = 0; k < p.length; k++){
        var index = table.row(p[k]).index();

        if(table.cell(index, 7).data()==1){
            $(table.row(p[k]).nodes()).addClass('delivery_pending');
        }else if(table.cell(index, 7).data()==2){
            $(table.row(p[k]).nodes()).addClass('delivery_delivering');
        }else if(table.cell(index, 7).data()==3){
            $(table.row(p[k]).nodes()).addClass('delivery_delivered');
        } 
    }
}


