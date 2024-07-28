
var request_in_queue = 0;
function create_new_delivery(){
    swal({
        title: "Create New Delivery Sheet",
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
            var latest_id = 0;
            $.getJSON("?r=delivery_items&f=create_new_delivery", function (data) {
                latest_id = data.id;
            }).done(function () {
                var table = $('#delivery_table').DataTable();
                $(".sk-circle-layer").show();
                table.ajax.url("?r=delivery_items&f=getAllDeliveries&p0="+$("#date_filter").val()).load(function () {
                    //table.page('first').draw(false);
                    table.row(':last', {page: 'current'}).select();
                    $(".dataTables_scrollBody").scrollTop($('.dataTables_scrollBody')[0].scrollHeight);
                    $(".sk-circle-layer").hide();
                },false);
                edit_delivery(latest_id,"");

            });
        }
        $(".sweet-alert").remove();
        $(".sweet-overlay").remove();
    });
}

function edit_delivery(id,action){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var suppliers_ = "";
    var delivery_sheet_info_supplier_id =0;
    
    var disable_supplier = "";
    var hide_add_new_supplier = "display:block;";
    $.getJSON("?r=delivery_items&f=get_delivery_needed_info&p0="+id, function (data) {
        suppliers_+='<option data-subtext="" value="0">Select Supplier</option>';
        $.each(data.suppliers, function (key, val) {
            suppliers_+='<option value='+val.id+'>'+val.name+'</option>';
        });
        delivery_sheet_info_supplier_id = data.delivery_sheet_info[0].supplier_id;
        if(delivery_sheet_info_supplier_id>0){
            disable_supplier = "disabled";
            hide_add_new_supplier = "display:none;";
        }
    }).done(function () {

        var content =
        '<div class="modal" data-backdrop="static" id="delivery_items_modal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <form id="garage_card_form" action="" method="post" enctype="multipart/form-data" >\n\
                    <input id="id_to_edit" name="id_to_edit" type="hidden" value="'+id+'" />\n\
                    <div class="modal-content">\n\
                        <div class="modal-header">\n\
                            <h3 class="modal-title">Edit Sheet<i style="float:right;font-size:30px; cursor:pointer" class="glyphicon glyphicon-remove" onclick="delivery_close()"></i></h3>\n\
                        </div>\n\
                        <div class="modal-body" style="padding-bottom:5px !important;">\n\
                            <div class="row">\n\
                                <div class="col-lg-12">\n\
                                    <div class="row">\n\
                                        <div class="col-lg-3 col-md-3">\n\
                                            <div class="form-group">\n\
                                                <label for="suppliers_list">Supplier Name</label>&nbsp;<span onclick="addSupplier(\'delivery_item\')" style="cursor:pointer; font-size:14px; font-weight:bold;color:#286090;'+hide_add_new_supplier+'">Add new supplier</span>\n\
                                                <select '+disable_supplier+' data-live-search="true" onchange="supplier_changed_delivery()" id="suppliers_list" name="suppliers_list" class="selectpicker form-control" onchange="garage_customer_changed(0)" style="width:100%">'+suppliers_+'</select>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-lg-3 col-md-3">\n\
                                            <label for="add_package">&nbsp;</label>\n\
                                            <div class="form-group">\n\
                                                <button onclick="add_new_delivery_package()" id="add_package" type="button" class="btn btn-primary">Add New Delivery Package</button>\n\
                                                <button onclick="save_data()"  type="button" class="btn btn-success">Save</button>\n\
                                                <span class="updating" id="updating_flag">Updating...</span>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-lg-3 col-md-3">\n\
                                            <label for="add_package">&nbsp;</label>\n\
                                            <div class="form-group">\n\
                                                <button onclick="hide('+id+')"  type="button" class="btn btn-success">Hide All Paid Records</button>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-lg-3 col-md-3">\n\
                                            <label for="add_package">&nbsp;</label>\n\
                                            <div class="form-group">\n\
                                                <i onclick="print_sheet('+id+')" class="glyphicon glyphicon-print print_size pull-right"></i>\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="row">\n\
                                <div class="col-lg-12 col-md-12">\n\
                                    <table id="delivery_items_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                        <thead>\n\
                                            <tr>\n\
                                                <th style="width: 60px !important; font-size:14px !important">ID</th>\n\
                                                <th style="width: 200px !important; font-size:14px !important">Customer</th>\n\
                                                <th style="font-size:14px !important">Address</th>\n\
                                                <th style="width: 90px !important; font-size:14px !important">Phone</th>\n\
                                                <th style="width: 75px !important; font-size:14px !important">Sending Date</th>\n\
                                                <th style="width: 70px !important; font-size:14px !important">WB Number</th>\n\
                                                <th style="width: 70px !important; font-size:14px !important">Collection</th>\n\
                                                <th style="width: 90px !important; font-size:14px !important">Delivery Charge</th>\n\
                                                <th style="width: 80px !important; font-size:14px !important">Pickapp Share</th>\n\
                                                <th style="width: 60px !important; font-size:14px !important">Our Share</th>\n\
                                                <th style="width: 65px !important; font-size:14px !important">Net Amount</th>\n\
                                                <th style="width: 25px !important; font-size:14px !important">COD</th>\n\
                                                <th style="width: 80px !important; font-size:14px !important">Paid (Sup.)</th>\n\
                                                <th style="width: 20px !important;"></th>\n\
                                            </tr>\n\
                                        </thead>\n\
                                        <tbody></tbody>\n\
                                    </table>\n\
                                </div>\n\
                            </div>\n\
                            <div class="row">\n\
                                <div class="col-lg-3 col-md-3">\n\
                                    \n\
                                </div>\n\
                                <div class="col-lg-3 col-md-3">\n\
                                    \n\
                                </div>\n\
                                <div class="col-lg-3 col-md-3">\n\
                                    \n\
                                </div>\n\
                                <div class="col-lg-3 col-md-3">\n\
                                    Total <b>Net Amount</b> to print: <span id="tnamount_to_print">0</span>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                </form>\n\
            </div>\n\
        </div>';
        $("#delivery_items_modal").remove();
        $("body").append(content);

        $("#delivery_items_modal").centerWH();

        $('#delivery_items_modal').on('show.bs.modal', function (e) {
        });

        $('#delivery_items_modal').on('shown.bs.modal', function (e) {
            $('#suppliers_list').selectpicker({showSubtext:true});
            //$('#card_invoice').selectpicker({showSubtext:true});
            //$('#item_text_color').selectpicker();        

            $(".sk-circle-layer").hide();
            //$('#stores_list_source').selectpicker();

            if(delivery_sheet_info_supplier_id>0){
                $('#suppliers_list').selectpicker('val', delivery_sheet_info_supplier_id);
                $('#suppliers_list').selectpicker('refresh');
            }
            
            
            /* table */
            var search_fields = [0,1,2,3,4,5,6,7,8,9,10];
            var index = 0;
            $('#delivery_items_table tfoot th').each( function () {
                if(jQuery.inArray(index, search_fields) !== -1){
                    var title = $(this).text();
                    $(this).html( '<div class="inner-addon left-addon"><input id="idf_'+index+'" style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder="'+title+'" /></div>' );
                    index++;
                }
            });
            
            var delivery_items_table = $('#delivery_items_table').DataTable({
                ajax: "?r=delivery_items&f=get_all_delivery_items&p0="+id,
                responsive: true,
                orderCellsTop: true,
                bLengthChange: true,
                iDisplayLength: 50,
                ordering:false,
                aoColumnDefs: [
                    { "targets": [0], "searchable": true, "orderable": false, "visible": false },
                    { "targets": [1], "searchable": true, "orderable": false, "visible": true },
                    { "targets": [2], "searchable": true, "orderable": false, "visible": true },
                    { "targets": [3], "searchable": true, "orderable": false, "visible": true },
                    { "targets": [4], "searchable": true, "orderable": false, "visible": true },
                    { "targets": [5], "searchable": true, "orderable": false, "visible": true },
                    { "targets": [6], "searchable": true, "orderable": false, "visible": true },
                    { "targets": [7], "searchable": true, "orderable": false, "visible": true },
                    { "targets": [8], "searchable": true, "orderable": false, "visible": true},
                    { "targets": [9], "searchable": true, "orderable": false, "visible": true},
                    { "targets": [10], "searchable": true, "orderable": false, "visible": true},
                    { "targets": [11], "searchable": true, "orderable": false, "visible": true, "className": "dt-center"},
                    { "targets": [12], "searchable": true, "orderable": false, "visible": true,"className": "dt-center" },
                    { "targets": [13], "searchable": true, "orderable": false, "visible": true,"className": "dt-center" },
                ],
                //deferRender:    true,
                //scroller:       true,
                //scroller:true,
                scrollY: "300px",
                scrollCollapse: false,
                paging: false,
                order: [[ 0, "asc" ]],
                dom: '<"toolbar_sh">frtip',
                initComplete: function( settings ) {
                    var table = $('#delivery_items_table').DataTable();
                    table.row(':eq(0)', { page: 'current' }).select();
                    
                    
                    $('.selectpicker').selectpicker();
                    
                    $('.datepicker').datepicker({
                        format: 'yyyy-mm-dd',
                        autoclose: true,
                    });
                    
                    $('.datepicker').datepicker().on('changeDate', function(ev) {
                        update_sending_date(this);
                    }).on('hide show', function(event) {
                        event.preventDefault();
                        event.stopPropagation();
                    });
                    
                    $(".only_numeric").numeric({ negative : false});
                    
                   
                    

                    //alert($("#delivery_items_modal .dataTables_scrollBody div").length);
                    $("#delivery_items_modal.dataTables_scrollBody div:first-child").remove();
                    //$(".sk-circle-layer").hide();
                },
                fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    $(nRow).addClass(aData[0]);
                },
                //fnDrawCallback: updateDeliveriesRows_,
                fnDrawCallback: function( settings ) {
                    updateDeliveriesRows_(action);
                }
            });
            
            
            $("#delivery_items_table").on("mousedown", "tr", function(event) {
                $('#delivery_items_table .selected').removeClass("selected");
                var dt = $('#delivery_items_table').DataTable();
                var index = dt.row(this).index();
                dt.row(index).select(index);
            });
            
            
            function updateDeliveriesRows_(action){
                var table = $('#delivery_items_table').DataTable();
                var p = table.rows({ page: 'current' }).nodes();
                
                for (var k = 0; k < p.length; k++){
                    var index = table.row(p[k]).index();
                    //table.cell(index, 9).data('<button onclick="delete_delivery_item('+parseInt(table.cell(index, 0).data())+',this)" type="button" class="btn btn-xs btn-danger btn-xss" style="width:100% !important">Delete</button>');
                    table.cell(index, 13).data('<i onclick="delete_delivery_item('+parseInt(table.cell(index, 0).data())+',this)" class="glyphicon glyphicon-trash trash_size"></i>');

                    var customer_id = $("#csi_"+table.cell(index, 0).data()).attr("data-value-select");
                    $('#csi_'+table.cell(index, 0).data()).selectpicker('val', customer_id);
                }
                
                if (action == "search" && $("#delivery_items_table tbody tr").length>1) {
                    select_wb();
                }
                
            }
            
            working_flag();
        });

        $('#delivery_items_modal').on('hide.bs.modal', function (e) {
            $('#delivery_items_modal').remove();
        });

        //submitCard(data);

        $('#delivery_items_modal').modal('show');  
    });
}

function select_wb(){
    setTimeout(function(){
        $('#delivery_items_table tr.selected').removeClass("selected");
        var dt = $('#delivery_items_table').DataTable();
        dt.rows().every( function (rowIdx, tableLoop, rowLoop) {
            var data = this.data();
            if($("#wb_"+data[0]).val()===$("#input_wb_number").val()){
                
                //if()
               
                dt.row(rowIdx).select(rowIdx);
                $('#delivery_items_modal .dataTables_scrollBody').animate({
                    scrollTop: $("."+data[0]).position().top
                }, 500);
                
                /*
                alert($('#delivery_items_modal .dataTables_scrollBody').length);
                $('#delivery_items_modal .dataTables_scrollBody').animate({
                    scrollTop: $('#delivery_items_modal .dataTables_scrollBody').eq(20).offset().top
                }, 800);*/
                //$("#delivery_items_table")
                //alert($("#delivery_items_table").height());
                //setTimeout(function(){
                    //$("#delivery_items_table").next().css({height:$("#delivery_items_table").height()});
                    //dt.row(rowIdx).scrollTo();
                    //dt.row(rowIdx).select(rowIdx);
                //},500);
            }
        }); 
    },1000);
    
}

function hide(id){
    $(".sk-circle-layer").show();
    $.getJSON("?r=delivery_items&f=hide&p0="+id, function (data) {
        
    }).done(function () {
        swal("Done");
        var table = $('#delivery_items_table').DataTable();
        table.ajax.url("?r=delivery_items&f=get_all_delivery_items&p0="+$("#id_to_edit").val()).load(function () {
            $('.selectpicker').selectpicker();
            
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
            });

            $('.datepicker').datepicker().on('changeDate', function(ev) {
                update_sending_date(this);
            }).on('hide show', function(event) {
                event.preventDefault();
                event.stopPropagation();
            });
            
            get_sum_of_print_sheet($("#id_to_edit").val());
                    
            $(".only_numeric").numeric({ negative : false});
            $(".sk-circle-layer").hide();
        },false);
    }); 
}

function customer_changed_delivery(delivery_item_id){
     $(".sk-circle-layer").show();
    $.getJSON("?r=delivery_items&f=customer_changed_delivery&p0="+delivery_item_id+"&p1="+$("#csi_"+delivery_item_id).val(), function (data) {
        
        if(data.length>0){
            $("#pho_"+delivery_item_id).html(data[0].phone);
            $("#adr_"+delivery_item_id).html(data[0].address);
        }else{
            $("#pho_"+delivery_item_id).html("");
            $("#adr_"+delivery_item_id).html("");
        }
    }).done(function () {
        $(".sk-circle-layer").hide();
    }); 
}

function delete_delivery_item(id,object){
    swal({
        title: "Delete Package",
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
            $(".sk-circle-layer").show();
            $.getJSON("?r=delivery_items&f=delete_delivery_item&p0="+id, function (data) {
            }).done(function () {
                var table = $('#delivery_items_table').DataTable();
                /*
                
                table.ajax.url("?r=delivery_items&f=get_all_delivery_items&p0="+$("#id_to_edit").val()).load(function () {
                    table.row('.' + id, {page: 'current'}).select();
                     $('.selectpicker').selectpicker();
                   
                });*/
                $(".sk-circle-layer").hide();
                table.row( $(object).parents('tr') ).remove().draw();
            });
        }
        $(".sweet-alert").remove();
        $(".sweet-overlay").remove();
    });

    
}

function supplier_changed_delivery(){
    $(".sk-circle-layer").show();
    $.getJSON("?r=delivery_items&f=update_supplier_delivery&p0="+$("#id_to_edit").val()+"&p1="+$("#suppliers_list").val(), function (data) {
    }).done(function () {
        var table = $('#delivery_table').DataTable();
        var sdata = table.row('.selected', 0).data();
        table.ajax.url("?r=delivery_items&f=getAllDeliveries&p0="+$("#date_filter").val()).load(function () {
            table.row('.' + sdata[0], {page: 'current'}).select();
        },false);
        $(".sk-circle-layer").hide();
    });
}

function add_new_delivery_package(){
    $(".sk-circle-layer").show();
    $.getJSON("?r=delivery_items&f=add_new_delivery_package&p0="+$("#id_to_edit").val(), function (data) {
    }).done(function () {
        var table = $('#delivery_items_table').DataTable();
       // var sdata = table.row('.selected', 0).data();
        table.ajax.url("?r=delivery_items&f=get_all_delivery_items&p0="+$("#id_to_edit").val()).load(function () {
            //table.row('.' + sdata[0], {page: 'current'}).select();
            table.row(':last', {page: 'current'}).select();
            $("#delivery_items_modal .dataTables_scrollBody").scrollTop($('#delivery_items_modal .dataTables_scrollBody')[0].scrollHeight);
            $('.selectpicker').selectpicker();
            
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
            });

            $('.datepicker').datepicker().on('changeDate', function(ev) {
                update_sending_date(this);
            }).on('hide show', function(event) {
                event.preventDefault();
                event.stopPropagation();
            });
                    
            $(".only_numeric").numeric({ negative : false});
                    
            $(".sk-circle-layer").hide();
        },false);
        //
    });
}

function collection_changed(delivery_item_id){
    //$(".sk-circle-layer").show();
    request_in_queue++;
    $.getJSON("?r=delivery_items&f=collection_changed&p0="+delivery_item_id+"&p1="+$("#cl_"+delivery_item_id).val(), function (data) {
    }).done(function () {
        request_in_queue--;
        netamount_changed(delivery_item_id);
        //$(".sk-circle-layer").hide();
    });
}
function deliverycharge_changed(delivery_item_id){
    //$(".sk-circle-layer").show();
    request_in_queue++;
    $.getJSON("?r=delivery_items&f=deliverycharge_changed&p0="+delivery_item_id+"&p1="+$("#dc_"+delivery_item_id).val(), function (data) {
    }).done(function () {
        request_in_queue--;
        //$(".sk-circle-layer").hide();
    });
}
function netamount_changed(delivery_item_id){
    //$(".sk-circle-layer").show();
    request_in_queue++;
    $.getJSON("?r=delivery_items&f=netamount_changed&p0="+delivery_item_id+"&p1="+$("#na_"+delivery_item_id).val(), function (data) {
    }).done(function () {
        request_in_queue--;
        //$(".sk-circle-layer").hide();
    });
}


function pickappshare_changed(delivery_item_id){
    //alert("pickappshare_changed");
    //$(".sk-circle-layer").show();
    request_in_queue++;
    $.getJSON("?r=delivery_items&f=pickappshare_changed&p0="+delivery_item_id+"&p1="+$("#pas_"+delivery_item_id).val(), function (data) {
    }).done(function () {
        request_in_queue--;
        ourshare_changed(delivery_item_id);
        //$(".sk-circle-layer").hide();
    });
}

function ourshare_changed(delivery_item_id){
    //alert("ourshare_changed");
    //$(".sk-circle-layer").show();
    request_in_queue++;
    $.getJSON("?r=delivery_items&f=ourshare_changed&p0="+delivery_item_id+"&p1="+$("#ours_"+delivery_item_id).val(), function (data) {
    }).done(function () {
        request_in_queue--;
        //$(".sk-circle-layer").hide();
    });
}

function wb_changed_count(wb){
    //$(".sk-circle-layer").show();
    var _data = [];
    request_in_queue++;
    $.getJSON("?r=delivery_items&f=wb_changed_count&p0="+wb, function (data) {
        _data = data;
    }).done(function () {
        request_in_queue--;
        if(_data.count[0]>1){
            alert("WB number already exist");
        }
        //$(".sk-circle-layer").hide();
    });
}

function wb_changed(delivery_item_id){
    //$(".sk-circle-layer").show();
    request_in_queue++;
    $.getJSON("?r=delivery_items&f=wb_changed&p0="+delivery_item_id+"&p1="+$("#wb_"+delivery_item_id).val(), function (data) {
    
    }).done(function () {
        request_in_queue--;
        wb_changed_count($("#wb_"+delivery_item_id).val());
        //$(".sk-circle-layer").hide();
    });
}

function update_sending_date(date_object){
    //$(".sk-circle-layer").show();
    request_in_queue++;
    var tmp_id = $(date_object).attr("id").split('_');
    $.getJSON("?r=delivery_items&f=update_sending_date&p0="+tmp_id[1]+"&p1="+$(date_object).val(), function (data) {
    }).done(function () {
        request_in_queue--;
        //$(".sk-circle-layer").hide();
    });
}

function delivered_changed(package_id){
    var x = document.getElementById("de_"+package_id).checked;
    var status = 0;
    if(x){
        status=1;
    }
    $.getJSON("?r=delivery_items&f=package_delivered&p0="+package_id+"&p1="+status, function (data) {
    }).done(function () {
    });
}


function cusname_changed(delivery_item_id){
    //$(".sk-circle-layer").show();
    request_in_queue++;
    $.getJSON("?r=delivery_items&f=cusname_changed&p0="+delivery_item_id+"&p1="+$("#cusname_"+delivery_item_id).val(), function (data) {
    }).done(function () {
        request_in_queue--;
        //$(".sk-circle-layer").hide();
    });
}
function cusaddr_changed(delivery_item_id){
    //$(".sk-circle-layer").show();
    request_in_queue++;
    $.getJSON("?r=delivery_items&f=cusaddr_changed&p0="+delivery_item_id+"&p1="+$("#cusaddr_"+delivery_item_id).val(), function (data) {
    }).done(function () {
        request_in_queue--;
        //$(".sk-circle-layer").hide();
    });
}
function cusphone_changed(delivery_item_id){
    //$(".sk-circle-layer").show();
    request_in_queue++;
    $.getJSON("?r=delivery_items&f=cusphone_changed&p0="+delivery_item_id+"&p1="+$("#cusphone_"+delivery_item_id).val(), function (data) {
    }).done(function () {
        request_in_queue--;
        //$(".sk-circle-layer").hide();
    });
}

function paid_changed(package_id,sheet_id){
    var x = document.getElementById("paidsup_"+package_id).checked;
    var status = 0;
    if(x){
        status=1;
    }
    request_in_queue++;
    $.getJSON("?r=delivery_items&f=package_supplier_paid&p0="+package_id+"&p1="+status, function (data) {
        
    }).done(function () {
        request_in_queue--;
        get_sum_of_print_sheet(sheet_id);
    });
}

function delivery_close(){
    //$('.selectpicker').selectpicker('hide');
    //setTimeout(function(){
        $('#delivery_items_modal').modal('toggle');
        
        if($('#delivery_table').length>0){
            var table = $('#delivery_table').DataTable();
            $(".sk-circle-layer").show();
            var sdata = table.row('.selected', 0).data();
            table.ajax.url("?r=delivery_items&f=getAllDeliveries&p0="+$("#date_filter").val()).load(function () {
                //table.row('.' + sdata[0], {page: 'current'}).select();
                //$(".dataTables_scrollBody").scrollTop($('.dataTables_scrollBody')[0].scrollHeight);
                $(".sk-circle-layer").hide();
            },false);
        }
        
        if($('#all_packages_table').length>0){
            var table = $('#all_packages_table').DataTable();
            //var sdata = table.row('.selected', 0).data();
            table.ajax.url("?r=delivery_items&f=getAllPackages&p0="+$("#date_filter").val()).load(function () {
                //table.row('.' + sdata[1], {page: 'current'}).select();
            },false);
            $(".sk-circle-layer").hide();
        }            
    //},100);
}

function add_customer_delivery(){
    addCustomer('add',[],0);
}

function get_sum_of_print_sheet(sheet_id){
    var total = 0;
    $.getJSON("?r=delivery_items&f=get_sum_of_print_sheet&p0="+sheet_id+"&p1=0", function (data) {
        total = data.sum_net_amount;
    }).done(function () {
        $("#tnamount_to_print").html(total);
    });
}

function print_sheet(sheet_id){
    w=window.open('?r=delivery_items&f=print_sheet&p0='+sheet_id+'&p1=0'); 
}

function print_all_sheet(sheet_id){
    w=window.open('?r=delivery_items&f=print_sheet&p0='+sheet_id+'&p1=1'); 
}

function search_wb_number(){
    var info = [];
    if($("#input_wb_number").val().length>0){
        $(".sk-circle-layer").show();
        $.getJSON("?r=delivery_items&f=search_wb_number&p0="+$("#input_wb_number").val(), function (data) {
            info = data;
        }).done(function () {
            if(info.length>0){
                edit_delivery(info[0].delivery_id,"search");
            }else{
                $(".sk-circle-layer").hide();
                swal("Not Found");
            }
        });
    }
}

var tmp_time_out = null;
function update_auto_ourshare_value(id){
    if(parseInt($("#pas_"+id).val().replace(/\,/g,''))>0){
        $("#ours_"+id).val( parseInt($("#dc_"+id).val().replace(/\,/g,''))-parseInt($("#pas_"+id).val().replace(/\,/g,'')));
        
        tmp_time_out = setTimeout(function(){
            ourshare_changed(id);
        },500);
    }else{
        $("#ours_"+id).val(0);
    }
     
}

function update_auto_netamount_value(id){
    var collection_ = parseInt($("#cl_"+id).val().replace(/\,/g,''));
    var deliv_charge_ = parseInt($("#dc_"+id).val().replace(/\,/g,''));
    if(isNaN(collection_)){
        collection_ = 0;
    }
    if(isNaN(deliv_charge_)){
        deliv_charge_ = 0;
    }
    $("#na_"+id).val(collection_-deliv_charge_);
    setTimeout(function(){
        netamount_changed(id);
    },500); 
}

function save_data(){
    $(".sk-circle-layer").show();
    //console.log("QUEUE: "+request_in_queue);
    setTimeout(function(){
        if(request_in_queue==0){
            swal("Saved");
            $(".sk-circle-layer").hide();
        }else{
            save_data();
        }
    },500);
}

function runScript(e){
    if (e.keyCode == 13) {
        search_wb_number();
        return false;
    }
}

function working_flag(){
    setInterval(function(){
        if(request_in_queue==0){
            $("#updating_flag").hide();
        }else{
            $("#updating_flag").show();
        }
    },1000);
}