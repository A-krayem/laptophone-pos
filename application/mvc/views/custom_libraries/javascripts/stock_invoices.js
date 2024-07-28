var NEW_PI_GEN = 0;
function generate_purshace_invoice(){
    $.confirm({
        title: 'Creating new Purchase Invoice?',
        content: '',
        animation: 'zoom',
        closeAnimation: 'zoom',
        animateFromElement:false,
        buttons: {
            CREATE: {
                btnClass: 'btn-success',
                action: function(){
                    $(".sk-circle-layer").show();
                    NEW_PI_GEN = 1;
                    var pi_nid = null;
                    $.getJSON("?r=stock&f=generate_purshace_invoice", function (data) {
                        pi_nid = data.id;
                    }).done(function () {
                        var table = $('#stock_invoices').DataTable();
                        table.ajax.url("?r=stock&f=getStockInvoices&p0="+$("#suppliers_list").val()+"&p1="+$("#payment_status_list").val()+"&p2="+$("#piDate").val()).load(function () {
                            $(".sk-circle-layer").hide();
                            $(".tab_toolbar button.blueB").addClass("disabled");
                            table.row('.' + PadSTKINV(pi_nid), {page: 'current'}).select();
                            receive_stock(pi_nid);
                        }, false);
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
function getStockInvoices(){
    $('#stock_invoices').show();
    var stock_invoices = null;
    $(".sk-circle").center();
    $(".sk-circle-layer").show();

    var search_fields = [0,1,2,3,4,5,6,7,8];
    var exclude_fields = [];
    var index = 0;
    $('#stock_invoices tfoot th').each( function () {
        if(jQuery.inArray(index, search_fields) !== -1){
            if(jQuery.inArray(search_fields[index], exclude_fields)==-1){
                var title = $(this).text();
                $(this).html( '<div class="inner-addon left-addon"><input style="width: 100% !important;" class="form-control input_sm_search" type="text" placeholder=" '+title+'" /></div>' );
            }
        }
        index++;
    });
         
    stock_invoices = $('#stock_invoices').dataTable({
        ajax: "?r=stock&f=getStockInvoices&p0=0&p1=0&p2=current",
        orderCellsTop: true,
        aoColumnDefs: [
            { "targets": [0], "searchable": true, "orderable": true, "visible": true},
            { "targets": [1], "searchable": true, "orderable": true, "visible": true },
            { "targets": [2], "searchable": true, "orderable": true, "visible": true},
            { "targets": [3], "searchable": true, "orderable": true, "visible": true},
            { "targets": [4], "searchable": true, "orderable": true, "visible": true},
            { "targets": [5], "searchable": true, "orderable": true, "visible": true},
            { "targets": [6], "searchable": true, "orderable": true, "visible": true},
            { "targets": [7], "searchable": true, "orderable": true, "visible": true},
            { "targets": [8], "searchable": true, "orderable": true, "visible": true},
            { "targets": [9], "searchable": true, "orderable": false, "visible": false},
            { "targets": [10], "searchable": true, "orderable": false, "visible": true},
            { "targets": [11], "searchable": true, "orderable": false, "visible": false},
            { "targets": [12], "searchable": true, "orderable": false, "visible": false},
        ],
        order: [[0, 'desc' ]],
        scrollY: '47vh',
        scrollCollapse: true,
        "paging": true,           // Enable pagination
        "pageLength": 50, 
        "lengthChange": true,
        "lengthMenu": [50, 100, 250, 500], // Page length options 
        //dom: '<"toolbar">frtlip',
        dom: '<"row"<"col-sm-12 col-md-12"<"toolbar">>><"row"<"col-sm-12 col-md-6"f>>' +
        '<"row"<"col-sm-12"tr>>' +
        '<"row"<"col-sm-6 col-md-6"i><"col-sm-6 col-md-6"p>>',
        initComplete: function(settings) {
            var table = $('#stock_invoices').DataTable();
            table.row(':eq(0)', { page: 'current' }).select();
            
            var suppliers_option = '<option value="0" title="All Suppliers">All Suppliers</option>';
            suppliers_option += '<option value="-1" title="All Deleted">All Deleted</option>';
            for(var i=0;i<all_suppliers.length;i++){
                suppliers_option+='<option value='+all_suppliers[i].id+' title="'+all_suppliers[i].name+'">'+all_suppliers[i].name+'</option>';
            }
                        
            $("div.toolbar").html('\n\
                <div class="row">\n\
                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">\n\
                        <div class="row">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                <h3><i class="glyphicon glyphicon-list-alt" style="font-size:26px;"></i>&nbsp;Purchase Invoice <a style="font-size:16px;" href="templates/template_pi.xlsx" download="template_pi.xlsx">Download Template</a></h3>\n\
                            </div>\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="padding-left:2px;padding-right:2px;">\n\
                                        <div class="btn-group tab_toolbar" role="group" style="width:100%;">\n\
                                            <button style="width:100%;" onclick="generate_purshace_invoice()" type="button" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i>&nbsp;New PI</button>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="padding-left:2px;padding-right:2px;">\n\
                                        <div class="btn-group" role="group" aria-label="" style="width:100%">\n\
                                            <input id="piDate" class="form-control" type="text" placeholder="Select date" style="cursor:pointer;width:100%;" />\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="padding-left:2px;padding-right:2px;">\n\
                                        <div class="form-group" style="width:100%">\n\
                                            <select id="suppliers_list" class="selectpicker" onchange="supplier_changed(1)" style="width:100%">\n\
                                                '+suppliers_option+'\n\
                                            </select>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"  style="padding-left:2px;padding-right:2px;">\n\
                                        <div class="form-group tab_toolbar" style="width:100%">\n\
                                            <button style="width:100%" id="add_supplier_payment" onclick="add_supplier_payment(\'stock_invoice\')" type="button" class="btn btn-primary"><i class="icon-payment"></i>&nbsp;Payment</button>\n\
                                        </div>\n\
                                    </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">\n\
                        &nbsp;\n\
                    </div>\n\
                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-4 pl2" style="margin-top:37px;">\n\
                        <div class="btn-group" role="group" aria-label="" style="width:100%">\n\
                            <div class="btn-group" id="buttons" style="float:right"></div>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
                <div class="row" style="margin-top:5px;">\n\
                    <div class="col-md-2 col-sm-3" style="padding-right:2px;">\n\
                        <div class="panel panel-info">\n\
                            <div class="panel-heading">\n\
                                <div class="row">\n\
                                    <div class="col-xs-12 col-sm-12 text-left">\n\
                                        <b class="announcement-heading dollar" id="total_pi_value">0</b>\n\
                                        <p class="announcement-text" style="margin-bottom:0px;">Total PI</p>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-md-2 col-sm-3" style="padding-left:2px;padding-right:2px;">\n\
                        <div class="panel panel-info">\n\
                            <div class="panel-heading">\n\
                                <div class="row">\n\
                                    <div class="col-xs-12 col-sm-12 text-left">\n\
                                        <b class="announcement-heading dollar" id="total_payments">0</b>\n\
                                        <p class="announcement-text" style="margin-bottom:0px;">Total Payments</p>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-md-2 col-sm-3" style="padding-left:2px;padding-right:2px;">\n\
                        <div class="panel panel-info">\n\
                            <div class="panel-heading">\n\
                                <div class="row">\n\
                                    <div class="col-xs-12 col-sm-12 text-left">\n\
                                        <b class="announcement-heading dollar" id="total_pi_vat_value">0</b>\n\
                                        <p class="announcement-text" style="margin-bottom:0px;">Total TAX</p>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
            ');
            
            var buttons = new $.fn.dataTable.Buttons(table, {
                    buttons: [
                      {
                            extend: 'excel',
                            text: 'Export excel',
                            className: 'exportExcel',
                            filename: 'Purchase Invoices ',
                            customize: _customizeExcelOptions,
                            exportOptions: {
                                modifier: {
                                    page: 'all'
                                },
                                columns: [ 0,1,2,3,4,5,6,7,8,9 ]
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
                    sheet.childNodes[0].childNodes[1].innerHTML = sheet.childNodes[0].childNodes[1].innerHTML;

                    $('row c[r^="A'+(clR.length+2)+'"]', sheet).attr('s', '48');

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
            
            $('.selectpicker').selectpicker();
            
            var table = $('#stock_invoices').DataTable();

            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose:true,
            });
            
            $(".datepicker").datepicker( "setDate", new Date() ).attr('readonly','readonly');

            if(table.rows().count()==0){
                $(".tab_toolbar button.blueB").addClass("disabled");
                $("#btn_move_to_store").addClass("disabled");
            }
            
            $('#stock_invoices').on('click', 'td', function () {
                if ($(this).index() == 9 || $(this).index() == 10 || $(this).index() == 11 ) {
                    return false;
                }
           });
           
            var defaultStart = moment().startOf('month');
            var end = moment(); 
           /*$('#piDate').daterangepicker({
                dateLimit:{month:12},
                startDate: defaultStart,
                endDate: end,
                locale: {
                    format: 'YYYY-MM-DD'
                },
            });*/


            $('#piDate').daterangepicker({
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

            $('#piDate').on('apply.daterangepicker', function(ev) {
                ev.preventDefault();
                ev.stopPropagation();
            });
            
            $( "#piDate" ).change(function() {
                daterange_changed();
            });
            update_pi_info();

            $(".sk-circle-layer").hide();
            
            
            

        },
        fnDrawCallback: updateRows,
        fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            $(nRow).addClass(aData[0]);
            
            
        }
    });


    $('#stock_invoices').on( 'page.dt', function () {
        $(".selected").removeClass("selected");
        $(".tab_toolbar button.blueB").addClass("disabled");
        
    } );
    
    $('#stock_invoices').DataTable().on('mousedown',"tr", function ( e, dt, type, indexes ) { 
        $(".tab_toolbar button.blueB").removeClass("disabled");
        $('.selected').removeClass("selected");
        $(this).addClass('selected');
    });

    $('#stock_invoices').DataTable().columns().every( function () {
        var that = this;
        $( 'input', this.footer() ).on( 'keyup change', function () {
            
            $(".tab_toolbar button.blueB").addClass("disabled");
            $("#btn_move_to_store").addClass("disabled");
            //$("#edit_received_invoice_stock").addClass("disabled");
            $(".selected").removeClass("selected");
            
            if ( that.search() !== this.value ) {
                that.search( this.value ).draw();
            }
        } );
    } );

    $('#stock_invoices').DataTable().on( 'select', function ( e, dt, type, indexes ) {
        if (type === 'row') {
           $(".tab_toolbar button.blueB").removeClass("disabled");
           //check_if_moved_to_store();
        }
    } );

    $('#stock_invoices').DataTable().on( 'deselect', function ( e, dt, type, indexes ) {
        if (type === 'row') {
            $(".tab_toolbar button.blueB").addClass("disabled");
            $("#btn_move_to_store").addClass("disabled");
            //$("#edit_received_invoice_stock").addClass("disabled");
        }
    });

    $('#stock_invoices').DataTable().search( '' ).columns().search( '' ).draw();
    
}

function show_details(po_id){
    if(!$("#show_details").hasClass("disabled")){
        
        //var dt = $('#stock_invoices').DataTable();
        //var id = dt.rows({ selected: true }).data()[0][0];
        //var po_id = parseInt(id.split('-')[1]);
        
        $.getJSON("?r=stock&f=getStockInvoiceDetailById&p0="+po_id, function (data) {
            $("#pi_id_v").html(data[0].id);
            $("#pi_ref").html(data[0].invoice_reference);
            $("#receive_invoice_date").html(data[0].receive_invoice_date);
            $("#delivery_date").html(data[0].delivery_date);
            
            $("#subtotal").html(data[0].subtotal);
            $("#discount").html(data[0].discount);
            $("#invoice_tax").html(data[0].invoice_tax);
            $("#total").html(data[0].total);
            
        }).done(function () {
            
        });
        
        var content =
        '<div class="modal" data-keyboard="false" data-backdrop="static" id="po_modal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title">Purchase Invoice Details<i style="float:right;font-size:30px; cursor:pointer" class="glyphicon glyphicon-remove" onclick="close_po_modal()"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-lg-3">\n\
                                <b>Purchase Invoice Id:</b>&nbsp;<span id="pi_id_v" style="font-size:14px;">-</span>\n\
                            </div>\n\
                            <div class="col-lg-3">\n\
                                <b>Invoice number:</b>&nbsp;<span id="pi_ref" style="font-size:14px;">-</span>\n\
                            </div>\n\
                            <div class="col-lg-3">\n\
                                 <b>Invoice Date:</b>&nbsp;<span id="receive_invoice_date" style="font-size:14px;">-</span>\n\
                            </div>\n\
                            <div class="col-lg-3">\n\
                                <b>Delivery Date:</b>&nbsp;<span id="delivery_date" style="font-size:14px;">-</span>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-lg-3">\n\
                                <b>Subtotal:</b>&nbsp;<span id="subtotal" style="font-size:14px;">-</span>\n\
                            </div>\n\
                            <div class="col-lg-3">\n\
                                <b>Discount:</b>&nbsp;<span id="discount" style="font-size:14px;">-</span>\n\
                            </div>\n\
                            <div class="col-lg-3">\n\
                                <b>Total TAX:</b>&nbsp;<span id="invoice_tax" style="font-size:14px;">-</span>\n\
                            </div>\n\
                            <div class="col-lg-3">\n\
                                <b>Total:</b>&nbsp;<span id="total" style="font-size:14px;">-</span>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-lg-12">\n\
                                <table id="po_modal_invoice_detail" class="table table-striped table-bordered " cellspacing="0">\n\
                                    <thead>\n\
                                        <tr>\n\
                                            <th style="width:60px;">Item</th><th>Description</th><th style="width:50px;">Qty</th><th style="width:110px;">Cost/u</th><th style="width:60px;">Disc. 1</th><th style="width:60px;">Disc. 2</th><th style="width:60px;">TAX</th><th style="width:60px;">Disc. %</th><th style="width:110px;">Total Cost</th>\n\
                                        </tr>\n\
                                    </thead>\n\
                                    <tfoot><tr><th>Item</th><th>Description</th><th>Qty</th><th>Cost/u</th><th>Disc. 1</th><th>Disc. 2</th><th>TAX</th><th>Discount %</th><th>Total Cost</th></tr></tfoot>\n\
                                </table>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>';
        $("#po_modal").remove();
        $("body").append(content);

        $("#po_modal").centerWH();

        $('#po_modal').on('show.bs.modal', function (e) {
            var search_fields = [0,1,2,3,4,5,6,7,8];
            var index = 0;
            $('#po_modal_invoice_detail tfoot th').each( function () {
                if(jQuery.inArray(index, search_fields) !== -1){
                    var title = $(this).text();
                    $(this).html( '<div class="inner-addon left-addon"><input style="width: 100% !important;" class="form-control input-sm" type="text" placeholder=" '+title+'" /></div>' );
                    index++;
                }
            });
            var table = $('#po_modal_invoice_detail').DataTable({
                ajax: "?r=stock&f=get_purchase_invoice_by_id&p0="+po_id,
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
        $('#po_modal').on('hide.bs.modal', function (e) {
            $("#po_modal").remove();
        });
        $('#po_modal').modal('show');
    }
}

function close_po_modal(){
    $('#po_modal').modal('toggle');
}

function updateRows(){
    var table = $('#stock_invoices').DataTable();
        var p = table.rows({ page: 'current' }).nodes();
        for (var k = 0; k < p.length; k++){
            var index = table.row(p[k]).index();
            
            if(table.cell(index, 12).data()==0){

                var details = '<i title="Show PI" class="glyphicon glyphicon-list-alt trash_icon" onclick="show_details(\''+parseInt(table.cell(index, 0).data().split("-")[1])+'\')" style="font-size:18px;" ></i>&nbsp;';

                var delete_ic = "";
                var edit_ic = "<i title='Edit'  class='glyphicon glyphicon-edit' onclick='edit_received_invoice_stock_new("+parseInt(table.cell(index, 0).data().split("-")[1])+")' style='font-size:18px;cursor:pointer' ></i>&nbsp;";
                
                
                if(table.cell(index, 11).data()==0){
                    delete_ic = '<i title="Delete PI" class="glyphicon glyphicon glyphicon-trash trash_icon redandsize" onclick="delete_pi(\''+parseInt(table.cell(index, 0).data().split("-")[1])+'\')"></i>&nbsp;';
                }
                
               
                if(table.cell(index, 11).data()==1){
                    table.cell(index, 10).data(edit_ic+details+delete_ic+'<i title="Locked" class="glyphicon glyphicon glyphicon-lock trash_icon"></i>&nbsp;<i title="Print" class="glyphicon glyphicon-print trash_icon" onclick="print_pi(\''+parseInt(table.cell(index, 0).data().split("-")[1])+'\')"></i>');
                }else{
                    table.cell(index, 10).data(edit_ic+details+delete_ic+'<i title="Lock" class="glyphicon icon-unlock trash_icon" onclick="lock_pi(\''+parseInt(table.cell(index, 0).data().split("-")[1])+'\')"></i>&nbsp;<i title="Print" class="glyphicon glyphicon-print trash_icon" onclick="print_pi(\''+parseInt(table.cell(index, 0).data().split("-")[1])+'\')"></i>');
                }
            }
        }
}

function print_pi(id){
    w=window.open('?r=stock&f=print_pi&p0='+id); 
}

function delete_pi(pi_id){
    $.getJSON("?r=stock&f=check_if_moved_to_store&p0="+pi_id, function (data) {
        if(data[0]==0){
            
            
            $.confirm({
                title: 'Are you sure?',
                content: '<span class="text-danger">Purchase invoice will be deleted for ever.</span>',
                animation: 'zoom',
                closeAnimation: 'zoom',
                animateFromElement:false,
                buttons: {
                    DELETE: {
                        btnClass: 'btn-danger',
                        action: function(){
                            $.getJSON("?r=stock&f=delete_purchase_invoice&p0="+pi_id, function (data) {

                            }).done(function () {
                                $("#btn_move_to_store").addClass("disabled");
                                var table = $('#stock_invoices').DataTable();
                                table.ajax.url("?r=stock&f=getStockInvoices&p0="+$("#suppliers_list").val()+"&p1="+$("#payment_status_list").val()+"&p2="+$("#piDate").val()).load(function () {
                                    table.row('.' + PadSTKINV(pi_id), {page: 'current'}).select();
                                    update_pi_info();
                                }, false);
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
    }).done(function () {

    });
    
}

function lock_pi(id){
    swal({
        title: "Are you sure?",
        text: "Publish",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes",
        closeOnConfirm: true,
        cancelButtonText: "Cancel",
    },
    function(isConfirm){
        if(isConfirm){
            $.getJSON("?r=stock&f=lock_pi_set&p0="+id, function (data) {

            }).done(function () {
                var table = $('#stock_invoices').DataTable();
                table.ajax.url("?r=stock&f=getStockInvoices&p0="+$("#suppliers_list").val()+"&p1="+$("#payment_status_list").val()+"&p2="+$("#piDate").val()).load(function () {
                    table.row('.' + PadSTKINV(id), {page: 'current'}).select();
                }, false);
            });
        }
    });
}

function move_to_store(){
    if(!$("#btn_move_to_store").hasClass("disabled")){
        var dt = $('#stock_invoices').DataTable();
        var id = dt.rows({ selected: true }).data()[0][0];
        var id_int = parseInt(id.split('-')[1]);

        swal({
            title: "Are you sure?",
            text: "Items quantities will be changed in the stock",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes",
            closeOnConfirm: true,
            cancelButtonText: "Cancel",
        },
        function(isConfirm){
            if(isConfirm){
                $.getJSON("?r=stock&f=move_to_store&p0="+id_int, function (data) {
                    
                }).done(function () {
                    //$("#btn_move_to_store").addClass("disabled");
                    //$("#edit_received_invoice_stock").addClass("disabled");
                    
                    var table = $('#stock_invoices').DataTable();
                    table.ajax.url("?r=stock&f=getStockInvoices&p0="+$("#suppliers_list").val()+"&p1="+$("#payment_status_list").val()+"&p2="+$("#piDate").val()).load(function () {
                        table.row('.' + PadSTKINV(id_int), {page: 'current'}).select();
                        update_pi_info();
                    }, false);
                }); 
            }
        });

    }
}

function check_if_moved_to_store(){
    var dt = $('#stock_invoices').DataTable();
    var id = dt.rows({ selected: true }).data()[0][0];
    var id_int = parseInt(id.split('-')[1]);
    $.getJSON("?r=stock&f=check_if_moved_to_store&p0="+id_int, function (data) {
        if(data[0]==0){
            //$("#btn_move_to_store").removeClass("disabled");
            //$("#edit_received_invoice_stock").removeClass("disabled");
            
        }else{
            //$("#btn_move_to_store").addClass("disabled");
            //$("#edit_received_invoice_stock").addClass("disabled");
        }
    }).done(function () {

    }); 
}

function payment_status_changed(){
    $(".tab_toolbar button.blueB").addClass("disabled");
    $(".sk-circle-layer").show();
    current_supplier_id = $("#suppliers_list").val();
    var table = $('#stock_invoices').DataTable();
    table.ajax.url("?r=stock&f=getStockInvoices&p0="+$("#suppliers_list").val()+"&p1="+$("#payment_status_list").val()+"&p2="+$("#piDate").val()).load(function () {
        $(".sk-circle-layer").hide();
        $(".tab_toolbar button.blueB").addClass("disabled");
        //$("#btn_move_to_store").addClass("disabled");
        //$("#edit_received_invoice_stock").addClass("disabled");
    }, false);
}

function supplier_changed_pos(){
    var table = $('#pos_supplier_payment').DataTable();
    table.ajax.url("?r=suppliers&f=get_supplier_statement&p0="+$("#suppliers_list").val()).load(function () {

    });
}

function daterange_changed(){
    current_supplier_id = $("#suppliers_list").val();
    var table = $('#stock_invoices').DataTable();
    table.ajax.url("?r=stock&f=getStockInvoices&p0="+$("#suppliers_list").val()+"&p1="+$("#payment_status_list").val()+"&p2="+$("#piDate").val()).load(function () {
        $(".sk-circle-layer").hide();
        $(".tab_toolbar button.blueB").addClass("disabled");
        update_pi_info();
    }, false);
}

function supplier_changed(refresh){
    if(refresh==1){
        $(".tab_toolbar button.blueB").addClass("disabled");
        $(".sk-circle-layer").show();
        current_supplier_id = $("#suppliers_list").val();
        var table = $('#stock_invoices').DataTable();
        table.ajax.url("?r=stock&f=getStockInvoices&p0="+$("#suppliers_list").val()+"&p1="+$("#payment_status_list").val()+"&p2="+$("#piDate").val()).load(function () {
            $(".sk-circle-layer").hide();
            $(".tab_toolbar button.blueB").addClass("disabled");
            update_pi_info();
            //$("#btn_move_to_store").addClass("disabled");
            //$("#edit_received_invoice_stock").addClass("disabled");
        }, false);
    }
}

function edit_received_invoice_stock_new(id_int){

    NEW_PI_GEN = 0;
    receive_stock(id_int);

    
  
}

function edit_received_invoice_stock(){
    if($("#edit_received_invoice_stock").hasClass("disabled")==false){
        NEW_PI_GEN = 0;
        $(".sk-circle").center();
        $(".sk-circle-layer").show();
        var dt = $('#stock_invoices').DataTable();
        
        var id = dt.row('.selected', 0).data()[0];
        var id_int = parseInt(id.split('-')[1]);
       
        receive_stock(id_int);
    }
}

function updateRows_Sup_CMP(){
    
    var table = $('#pos_supplier_payment').DataTable();
    var p = table.rows({ page: 'current' }).nodes();
     var dlt = '';
    for (var k = 0; k < p.length; k++){
        var index = table.row(p[k]).index();
       
        table.cell(index,6).data('<button onclick="show_supplier_stmt('+table.cell(index,0).data()+',1)" style="width:100%;font-weight:bold;" type="button" class="btn btn-default btn-xs">STMT '+MAIN_CURRENCY+'</button>');
        table.cell(index,7).data('<button onclick="show_supplier_stmt('+table.cell(index,0).data()+',2)" style="width:100%;font-weight:bold;" type="button" class="btn btn-default btn-xs">STMT LBP</button>');
        table.cell(index,8).data('<button onclick="add_supplier_payment_complex('+table.cell(index,0).data()+',\''+table.cell(index,1).data()+'\')" style="width:100%;font-weight:bold;" type="button" class="btn btn-default btn-xs">Add</button>'); 

    }
}

function show_supplier_stmt(supplier_id,currency){
    w=window.open('?r=suppliers&f=print_supplier_statement&p0='+supplier_id+'&p1='+currency+'&p2=today'); 
}

function delete_payment(pay_id){
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
           $(".sk-circle").center();
            $(".sk-circle-layer").show();
           $.getJSON("?r=suppliers&f=delete_supplier_payment&p0="+pay_id, function (data) {

            }).done(function () {
                
                if($('#suppliers_statement_table').length>0){
                    var table = $('#suppliers_statement_table').DataTable();
                    table.ajax.url("?r=suppliers&f=get_supplier_statement&p0="+$("#suppliers_list").val()).load(function () {
                        table.row('.' + PadSUPPAY(pay_id), {page: 'last'}).select();
                        $(".sk-circle-layer").hide();
                    },false);
                }
                
                if($('#pos_supplier_payment').length>0){
                    var table = $('#pos_supplier_payment').DataTable();
                    table.ajax.url("?r=suppliers&f=get_supplier_statement&p0="+$("#suppliers_list").val()).load(function () {
                        $(".sk-circle-layer").hide();
                    },false);
                }
                
                
            });
       }
    }); 
}

function addBank(action){
    var content =
        '<div class="modal fade" data-keyboard="false" id="add_new_bank_modal" tabindex="-1" role="dialog" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <form id="add_new_bank_form" action="" method="post" enctype="multipart/form-data" >\n\
                <input id="id_to_edit" name="id_to_edit" type="hidden" value="0" />\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title"><i class="glyphicon glyphicon-plus"></i>&nbsp;Add new bank</h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="form-group">\n\
                       <input id="bank_name" name="bank_name" type="text" class="form-control" placeholder="Bank name" />\n\
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
    $('#add_new_bank_modal').remove();
    $('body').append(content);
    submitNewBank(action);

    $('#add_new_bank_modal').on('show.bs.modal', function (e) {
    });
    
    $('#add_new_bank_modal').on('shown.bs.modal', function (e) {
        $("#bank_name").focus();
    });

    $('#add_new_bank_modal').on('hide.bs.modal', function (e) {
        $('#add_new_bank_modal').remove();
    });
    
    $('#add_new_bank_modal').modal('show');
}

function submitNewBank(action){
    $("#add_new_bank_form").on('submit', (function (e) {
        e.preventDefault();
        if (!emptyInput("bank_name")) {
            $(".sk-circle-layer").show();
            $.ajax({
                url: "?r=settings_info&f=add_new_bank",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function (data)
                {
                    if(action == "supplier_payment"){
                        $("#bank_source").append("<option value='"+data.id+"'>"+data.name+"</option>");
                        $("#bank_source").selectpicker('refresh');
                        $("#bank_source").selectpicker('val', data.id);
                        $('#add_new_bank_modal').modal('hide');
                        $(".sk-circle-layer").hide();
                    }
                }
            });
        }
    }));
}

function update_diff_v_sup(){
    $("#difference_inv").html(mask_clean($("#payment_value").val()));
    $("#cash_usd").val(0);
    cash_changed_usd($("#cash_usd"));
}

function _add_supplier_payment_new(id,supplier_name){
    var cash_info="";
    
    cash_info= '<div id="cash_info_container" style="padding-left:2px;">\n\
                    <div class="panel panel-default">\n\
                        <div class="panel-heading" style="padding-top:5px;padding-bottom:5px;"><b>Cash Info</b></div>\n\
                        <div class="panel-body" style="padding:10px;">\n\
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-left:2px;padding-right:2px;">\n\
                                <div class="form-group" style="margin-bottom:3px;">\n\
                                    <label for="cash_usd">IN USD </label><span id="to_return_c_usd" style="float:right">&nbsp;&nbsp;&nbsp;</span>\n\
                                    <input onkeyup="cash_changed_usd(this)" autocomplete="off" id="cash_usd" name="cash_usd" type="text" class="form-control med_input" placeholder="">\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-left:2px;padding-right:2px;">\n\
                                <div class="form-group" style="margin-bottom:3px;">\n\
                                    <label for="cash_usd">OUT USD</label>\n\
                                    <input style="display:none" readonly onkeyup="" autocomplete="off" id="r_cash_usd" name="r_cash_usd" type="text" class="form-control med_input" placeholder="">\n\
                                    <input onkeyup="r_cash_usd_action_changed(this)" autocomplete="off" id="r_cash_usd_action" name="r_cash_usd_action" type="text" class="form-control med_input" placeholder="">\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-left:2px;padding-right:2px;">\n\
                                <div class="form-group" style="margin-bottom:3px;">\n\
                                    <label for="cash_usd">IN LBP </label><span id="to_return_c_lbp" style="float:right">&nbsp;&nbsp;&nbsp;</span>\n\
                                    <input onkeyup="cash_changed_lbp(this)"  autocomplete="off" id="cash_lbp" name="cash_lbp" type="text" class="form-control med_input" placeholder="">\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-left:2px;padding-right:2px;">\n\
                                <div class="form-group" style="margin-bottom:3px;">\n\
                                    <label for="cash_usd">OUT LBP</label>\n\
                                    <input style="display:none" readonly onkeyup="" autocomplete="off" id="r_cash_lbp" name="r_cash_lbp" type="text" class="form-control med_input" placeholder="">\n\
                                    <input onkeyup="r_cash_lbp_action_changed(this)" autocomplete="off" id="r_cash_lbp_action" name="r_cash_lbp_action" type="text" class="form-control med_input" placeholder="">\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                </div>';
    
    var content =
            '<div class="modal" data-backdrop="static"  id="add_new_sup_payment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <form id="add_new_sup_pay_form" action="" method="post" enctype="multipart/form-data" >\n\
                    <input id="id_to_edit" name="id_to_edit" type="hidden" value="0" />\n\
                    <input id="supplier_id" name="supplier_id" type="hidden" value="'+id+'" />\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title" id="exampleModalLongTitle"><i class="icon-expenses"></i>&nbsp;Add Payment For '+supplier_name+'</h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <span style="display:none" id="difference_inv"></span>\n\
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="padding-left:18px;">\n\
                                <div class="form-group">\n\
                                    <label for="payment_value">Payment Value (USD)</label>\n\
                                    <input oninput="update_diff_v_sup()" autocomplete="off" id="payment_value" name="payment_value" value="0" type="text" class="form-control med_input" placeholder="Value">\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="padding-left:18px;">\n\
                                <div class="form-group">\n\
                                    <label for="payment_date">Value Date</label>\n\
                                    <input autocomplete="off" id="payment_date" name="payment_date" value="" type="text" class="form-control med_input" placeholder="">\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-left:18px;">\n\
                                <div class="form-group">\n\
                                    <label for="expense_type">Note</label>\n\
                                    <input autocomplete="off" id="payment_note" name="payment_note" value="" type="text" class="form-control med_input" placeholder="">\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row" style="margin-top:10px;">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                '+cash_info+'\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-footer">\n\
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\n\
                        <button id="action_btn_e" type="submit" class="btn btn-primary">Add</button>\n\
                    </div>\n\
                    <form/>\n\
                </div>\n\
            </div>\n\
        </div>';

        $('#add_new_sup_payment').remove();
        $('body').append(content);
        submitSupPayment_new();
        
        $('#add_new_sup_payment').on('show.bs.modal', function (e) {   
        });

        $('#add_new_sup_payment').on('shown.bs.modal', function (e) {
            $('#payment_date').datepicker({autoclose:true,format: 'yyyy-mm-dd'});
            $('#payment_date').datepicker( "setDate", new Date() );
            
            $('#payment_date').datepicker().on('changeDate', function(ev) {

            }).on('hide show', function(event) {
                event.preventDefault();
                event.stopPropagation();
            });

            
            
            
            cleaves_id("payment_value",5);
            $(".sk-circle-layer").hide();
            
            
            set_current_cash_var(2);
            cleaves_id("cash_lbp",0);
            cleaves_id("cash_usd",5);
            cash_changed_usd($("#cash_usd"));
        
        });
        
        $('#add_new_sup_payment').on('hide.bs.modal', function (e) {
            $("#add_new_sup_payment").remove();
        });
        
        $('#add_new_sup_payment').modal('show'); 
}

function submitSupPayment_new(){
   $("#add_new_sup_pay_form").on('submit', (function (e) {
        e.preventDefault();
        
        $("#payment_value").val($("#payment_value").val().replace(/,/g , ''));
        $(".sk-circle-layer").show();
        $.ajax({
            url: "?r=suppliers&f=add_supplier_payment_new",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            success: function (data)
            {
                $(".sk-circle-layer").hide();
                //supplier_changed_pos();

                var table = $('#pos_supplier_payment').DataTable();
                table.ajax.url("?r=suppliers&f=get_supplier_statement_new_version&p0=0").load(function () {
                    $(".dataTables_scrollBody").scrollTop($('.dataTables_scrollBody')[0].scrollHeight);
                },false);
                $('#add_new_sup_payment').modal('hide');

            }
        });
        
    }));
}

function add_supplier_payment_complex(id_int_sup,supplier_name){
    if(typeof usd_but_show_lbp_priority !== 'undefined') {
        if(usd_but_show_lbp_priority==1){
            _add_supplier_payment_new(id_int_sup,supplier_name);
        }else{
            var all_invoices = null;
            var supplier_option = "<option value='"+id_int_sup+"' title='"+supplier_name+"'>"+supplier_name+"</option>";
            _add_supplier_payment(0,id_int_sup,supplier_option,all_invoices,"complex_stmt",[],supplier_name);
        }
        return;
    }
    var all_invoices = null;
    var supplier_option = "<option value='"+id_int_sup+"' title='"+supplier_name+"'>"+supplier_name+"</option>";
    _add_supplier_payment(0,id_int_sup,supplier_option,all_invoices,"complex_stmt",[],supplier_name);
}

function add_supplier_payment(page){
    var id_int = null;
    var id_int_sup = null;
    var supplier_id = null;
    var supplier_name = "";
    if(page=="admin_sup"){
        if($("#add_supplier_payment").hasClass("disabled")==false){
            var dt = $('#stock_invoices').DataTable();
            var id = dt.row('.selected', 0).data()[0];
            var id_int = parseInt(id.split('-')[1]);
             var supplier_option = "";

            $.getJSON("?r=stock&f=get_supplier_of_invoice&p0="+id_int, function (data) {
                supplier_id = data[0].supplier_id;
                supplier_name = data[0].supplier_name;
                supplier_option = "<option value='"+supplier_id+"' title='"+supplier_name+"'>"+supplier_name+"</option>";
            }).done(function () {
                _add_supplier_payment(0,supplier_id,supplier_option,"",page,[],supplier_name);
                //$(".sk-circle-layer").hide();
            });
        }
    }else if(page=="stock_invoice"){
            if($("#suppliers_list").val()==0 || $("#suppliers_list").val()==-1){
                $.alert({
                    animation: 'zoom',
                    closeAnimation: 'zoom',
                    animateFromElement:false,
                    title: 'Alert!',
                    content: 'You must select a supplier first!',
                });
                return;
            }
            
            if($("#suppliers_list").val()>0){
                supplier_id = $("#suppliers_list").val();
                supplier_name = $( "#suppliers_list option:selected" ).text();
                supplier_option = "<option value='"+supplier_id+"' title='"+supplier_name+"'>"+supplier_name+"</option>";
                _add_supplier_payment(0,supplier_id,supplier_option,"",page,[],supplier_name);
            }else{
                
                
                var dt = $('#stock_invoices').DataTable();
                if(dt.rows().count()==0){
                    swal("No supplier selected");
                    return;
                }
                var id = dt.row('.selected', 0).data()[0];
                var id_int = parseInt(id.split('-')[1]);
                var supplier_option = "";

                $.getJSON("?r=stock&f=get_supplier_of_invoice&p0="+id_int, function (data) {
                    supplier_id = data[0].supplier_id;
                    supplier_name = data[0].supplier_name;
                    supplier_option = "<option value='"+supplier_id+"' title='"+supplier_name+"'>"+supplier_name+"</option>";
                }).done(function () {
                    _add_supplier_payment(0,supplier_id,supplier_option,"",page,[],supplier_name);
                    //$(".sk-circle-layer").hide();
                });
            }
           
        
    }else if(page=="sup_pay"){
        var dt = $('#suppliers_table').DataTable();
        var id = dt.rows({ selected: true }).data()[0][0];
        id_int_sup = parseInt(id.split('-')[1]);
     
        var supplier_name = dt.rows({ selected: true }).data()[0][1];
        
        var supplier_option = "<option value='"+id_int_sup+"' title='"+supplier_name+"'>"+supplier_name+"</option>";
        
        var all_invoices = null;

        $.getJSON("?r=stock&f=getStockInvoices&p0="+id_int_sup+"&p1=0&p2="+$("#piDate").val(), function (data) {
            $.each(data.data, function (key, val) {
                all_invoices+="<option value='"+PadSTKINV(val[0])+"' title='"+val[0]+"'>"+val[0]+"</option>";;
                //payment_options+="<option value='"+val.id+"' title='"+val.method_name+"'>"+val.method_name+"</option>";
            });
        }).done(function () {
            if(all_invoices==null){
                swal("There is no invoices for this supplier");
            }else{
                _add_supplier_payment(id_int,id_int_sup,supplier_option,all_invoices,page,[],supplier_name);
            }
            
        });
        
    }else if(page=="sup_stm"){
        
        id_int_sup = $("#suppliers_list").val();
        var supplier_name = $("#suppliers_list option:selected").text();
        var supplier_option = "<option value='"+id_int_sup+"' title='"+supplier_name+"'>"+supplier_name+"</option>";
        var all_invoices = null;
        $.getJSON("?r=stock&f=getStockInvoices&p0="+id_int_sup+"&p1=0&p2="+$("#piDate").val(), function (data) {
            $.each(data.data, function (key, val) {
                all_invoices+="<option value='"+PadSTKINV(val[0])+"' title='"+val[0]+"'>"+val[0]+"</option>";;
            });
        }).done(function () {
            _add_supplier_payment(id_int,id_int_sup,supplier_option,all_invoices,page,[],supplier_name);
        });
        
    }else if(page=="POS"){
        $(".sk-circle").center();
        $(".sk-circle-layer").show();
        var all_suppliers = [];
        var suppliers_option = '<option value="0" title="All Suppliers">Select Supplier</option>';
        var selected = "";
        $.getJSON("?r=suppliers&f=get_suppliers", function (data) {
            $.each(data, function (key, val) {
                if(val.name!="none"){
                    suppliers_option+='<option value='+val.id+' title="'+val.name+'">'+val.name+'</option>';
                }
            });
        }).done(function () {
            
            var modal_name = "modal_pos_sup_payment____";
            var modal_title = "Supplier Payment";
            var table_name = "pos_supplier_payment";
            var content =
            '<div class="modal large" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
                <div class="modal-dialog" role="document">\n\
                    <div class="modal-content">\n\
                        <div class="modal-header"> \n\
                            <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\''+modal_name+'\')"></i></h3>\n\
                        </div>\n\
                        <div class="modal-body" style="height:550px;">\n\
                            <div class="row">\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">\n\
                                    <div style="width:100%" class="btn-group" role="group" aria-label="">\n\
                                        <select data-width="100%" data-live-search="true" id="suppliers_list" class="selectpicker" onchange="supplier_changed_pos()">\n\
                                            '+suppliers_option+'\n\
                                        </select>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3">\n\
                                    <div class="btn-group" role="group" aria-label="">\n\
                                        <button onclick="add_supplier_paymenr_from_pos()" type="button" class="btn btn-primary blueB"><i class="glyphicon glyphicon-plus"></i>&nbsp;Add Payment</button>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="row">\n\
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                    <table style="width:100%" id="'+table_name+'" class="table table-striped table-bordered" cellspacing="0">\n\
                                        <thead>\n\
                                            <tr>\n\
                                                <th style="width: 120px;">Date</th>\n\
                                                <th style="width: 150px;">Ref. Invoice</th>\n\
                                                <th style="width: 100px;">Ref. Payment</th>\n\
                                                <th style="width: 60px;">Method</th>\n\
                                                <th>Description</th>\n\
                                                <th style="width: 100px;">Charges</th>\n\
                                                <th style="width: 100px;">Payment</th>\n\
                                                <th style="width: 100px;">Remain</th>\n\
                                                <th style="width: 80px;">Deleted flag</th>\n\
                                                <th style="width: 150px;">Original Currency</th>\n\
                                                <th style="width: 30px;"></th>\n\
                                                <th style="width: 30px;"></th>\n\
                                            </tr>\n\
                                        </thead>\n\
                                        <tfoot>\n\
                                            <tr>\n\
                                                <th>Date</th>\n\
                                                <th>Ref. Invoice</th>\n\
                                                <th>Ref. Payment</th>\n\
                                                <th>Method</th>\n\
                                                <th>Description</th>\n\
                                                <th>Charges</th>\n\
                                                <th>Payment</th>\n\
                                                <th>Remain</th>\n\
                                                <th>Deleted flag</th>\n\
                                                <th>Original Currency</th>\n\
                                                <th></th>\n\
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
                $("#suppliers_list").selectpicker();
                $(".sk-circle-layer").hide();
                
                $('#'+table_name).show();
        
                var _sup_pay_pos__var =null;

                var search_fields = [0,1,2,3,4,5,6,7,8,9];
                var index = 0;
                $('#'+table_name+' tfoot th').each( function () {

                    if(jQuery.inArray(index, search_fields) !== -1){
                        var title = $(this).text();
                        $(this).html( '<input id="idf_'+index+'" style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder="'+title+'" />' );
                        index++;
                    }
                });
                
                _sup_pay_pos__var = $('#'+table_name).DataTable({
                    ajax: {
                        url: "?r=suppliers&f=get_supplier_statement&p0="+$("#suppliers_list").val(),
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
                       { "targets": [0], "searchable": true, "orderable": false, "visible": true },
                        { "targets": [1], "searchable": true, "orderable": false, "visible": true },
                        { "targets": [2], "searchable": true, "orderable": false, "visible": true },
                        { "targets": [3], "searchable": true, "orderable": false, "visible": true,sClass: "alignCenter" },
                        { "targets": [4], "searchable": true, "orderable": false, "visible": true },
                        { "targets": [5], "searchable": true, "orderable": false, "visible": true },
                        { "targets": [6], "searchable": true, "orderable": false, "visible": true },
                        { "targets": [7], "searchable": true, "orderable": false, "visible": true },
                        { "targets": [8], "searchable": true, "orderable": false, "visible": false },
                        { "targets": [9], "searchable": true, "orderable": false, "visible": true },
                        { "targets": [10], "searchable": true, "orderable": false, "visible": true,sClass: "alignCenter" },
                        { "targets": [11], "searchable": true, "orderable": false, "visible": true,sClass: "alignCenter" },
                    ],
                    scrollCollapse: true,
                    paging: true,
                    bPaginate: false,
                    ordering: false,
                    bLengthChange: false,
                    bFilter: true,
                    bInfo: false,
                    bAutoWidth: true,
                    dom: '<"toolbar">frtip',
                    initComplete: function(settings, json) {

                 

                        $(".sk-circle-layer").hide();
                    },
                    fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                        $(nRow).addClass(aData[0]);
                    },
                    //fnDrawCallback: updateRows_Sup_CMP,
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
                
                
                
        
            });

            $('#'+modal_name).on('shown.bs.modal', function (e) {

            });
            $('#'+modal_name).on('hide.bs.modal', function (e) {
                $("#"+modal_name).remove();
            });
            $('#'+modal_name).modal('show');
        });
    }else if(page=="POSCOMPLEX"){
        var defc = 0;
        $(".sk-circle").center();
        $(".sk-circle-layer").show();
        var all_suppliers = [];
        var suppliers_option = '<option value="0" title="All Suppliers">Select Supplier</option>';
        var selected = "";
        $.getJSON("?r=suppliers&f=get_suppliers_with_info", function (data) {
            $.each(data.suppliers, function (key, val) {
                if(val.name!="none"){
                    suppliers_option+='<option value='+val.id+' title="'+val.name+'">'+val.name+'</option>';
                }
            });
            defc = data.currency;
        }).done(function () {
            var modal_name = "modal_pos_sup_payment____";
            var modal_title = "Suppliers Statements";
            var table_name = "pos_supplier_payment";
            var content =
            '<div class="modal large" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
                <div class="modal-dialog" role="document">\n\
                    <div class="modal-content">\n\
                        <div class="modal-header"> \n\
                            <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\''+modal_name+'\')"></i></h3>\n\
                        </div>\n\
                        <div class="modal-body" style="height:550px;">\n\
                            <div class="row">\n\
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                    <table id="'+table_name+'" class="table table-striped table-bordered" cellspacing="0" style="width:100%">\n\
                                        <thead>\n\
                                            <tr>\n\
                                                <th style="width:80px;">supplier_id</th>\n\
                                                <th>Supplier Name</th>\n\
                                                <th style="width:120px;">Contact name</th>\n\
                                                <th style="width:140px;">Phone</th>\n\
                                                <th style="width:160px;">Balance</th>\n\
                                                <th style="width:160px;">Balance LBP</th>\n\
                                                <th style="width:70px;">STMT</th>\n\
                                                <th style="width:70px;">STMT LBP</th>\n\
                                                <th style="width:70px;">Payment</th>\n\
                                            </tr>\n\
                                        </thead>\n\
                                        <tfoot>\n\
                                            <tr>\n\
                                                <th>supplier_id</th>\n\
                                                <th>Name</th>\n\
                                                <th>Contact name</th>\n\
                                                <th>Phone</th>\n\
                                                <th>Balance USD</th>\n\
                                                <th>Balance LBP</th>\n\
                                                <th></th>\n\
                                                <th></th>\n\
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
                $("#suppliers_list").selectpicker();
                $(".sk-circle-layer").hide();
                
                $('#'+table_name).show();
        
                var _sup_pay_pos__var =null;
                
                
                var show_lbp=false;
                if(defc==2){
                    show_lbp=true;
                }

                var search_fields = [0,1,2,3,4,5];
                var index = 0;
                $('#'+table_name+' tfoot th').each( function () {

                    if(jQuery.inArray(index, search_fields) !== -1){
                        var title = $(this).text();
                        $(this).html( '<input id="idf_'+index+'" style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder="'+title+'" />' );
                        index++;
                    }
                });
                
                _sup_pay_pos__var = $('#'+table_name).DataTable({
                    ajax: {
                        url: "?r=suppliers&f=get_supplier_statement_new_version&p0=0",
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
                       { "targets": [0], "searchable": true, "orderable": false, "visible": false },
                        { "targets": [1], "searchable": true, "orderable": false, "visible": true },
                        { "targets": [2], "searchable": true, "orderable": false, "visible": true },
                        { "targets": [3], "searchable": true, "orderable": false, "visible": true },
                        { "targets": [4], "searchable": true, "orderable": false, "visible": true },
                        { "targets": [5], "searchable": true, "orderable": false, "visible": show_lbp },
                        { "targets": [6], "searchable": true, "orderable": false, "visible": true },
                        { "targets": [7], "searchable": true, "orderable": false, "visible": show_lbp },
                        { "targets": [8], "searchable": true, "orderable": false, "visible": true },
                    ],
                    scrollCollapse: true,
                    paging: true,
                    bPaginate: false,
                    ordering: false,
                    bLengthChange: false,
                    bFilter: true,
                    bInfo: false,
                    bAutoWidth: true,
                    dom: '<"toolbar">frtip',
                    initComplete: function(settings, json) {

                 

                        $(".sk-circle-layer").hide();
                    },
                    fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                        $(nRow).addClass(aData[0]);
                    },
                    fnDrawCallback: updateRows_Sup_CMP,
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
                
                
                
        
            });

            $('#'+modal_name).on('shown.bs.modal', function (e) {

            });
            $('#'+modal_name).on('hide.bs.modal', function (e) {
                $("#"+modal_name).remove();
            });
            $('#'+modal_name).modal('show');
        });
    }
}

function add_supplier_paymenr_from_pos(){
    var id_int_sup = $("#suppliers_list").val();
    var supplier_name = $("#suppliers_list option:selected").text();
    var supplier_option = "<option value='"+id_int_sup+"' title='"+supplier_name+"'>"+supplier_name+"</option>";
    var all_invoices = null;
    $.getJSON("?r=stock&f=getStockInvoices&p0="+id_int_sup+"&p1=0&p2="+$("#piDate").val(), function (data) {
        $.each(data.data, function (key, val) {
            all_invoices+="<option value='"+PadSTKINV(val[0])+"' title='"+val[0]+"'>"+val[0]+"</option>";;
        });
    }).done(function () {
        _add_supplier_payment(0,id_int_sup,supplier_option,all_invoices,'POS',[],supplier_name);
    });
}

function _edit_upplier_payment(id){
    //var supplier_option = "";
    var info = [];
    $.getJSON("?r=suppliers&f=get_suppliers_payment_by_id&p0="+id, function (data) {
        info = data;
    }).done(function () {
        _add_supplier_payment(id,$("#suppliers_list").val(),"","","sup_pay",info,"");
    });
    
}

function delete_picture_cheque(id){           
    swal({
        title: "Are you sure?",
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
            $.getJSON("?r=suppliers&f=delete_cheque_picture&p0="+id, function (data) {
                var upload_picture_section = '<div class="form-group">\n\
                    <label for="supplier_id_for_cheque">Picture</label>\n\
                    <input type="hidden" value="'+$("#suppliers_list").val()+'" class="form-control"  id="supplier_id_for_cheque" name="supplier_id_for_cheque">\n\
                    <input accept=".png,.jpg,.jpeg" type="file" value="" class="form-control"  id="cheque_picture" name="cheque_picture">\n\
                </div>';
                $("#pic_upload").empty();
                $("#pic_upload").append(upload_picture_section);
            }).done(function () {
                $(".sk-circle-layer").hide();
            }); 
        }
    });

}

function _add_supplier_payment(id_int,supplier_id,suppliers_options,all_invoices,page,info,supplier_name){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var payment_options = "";
    var banks_options = "";
    var currencies_options = "";
    var number_of_decimal_points = 0;
    
    var upload_picture_section = '<div class="form-group">\n\
        <label for="supplier_id_for_cheque">Picture</label>\n\
        <input type="hidden" value="'+supplier_id+'" class="form-control"  id="supplier_id_for_cheque" name="supplier_id_for_cheque">\n\
        <input accept=".png,.jpg,.jpeg" type="file" value="" class="form-control"  id="cheque_picture" name="cheque_picture">\n\
    </div>';
    if(info.length>0 && info[0].payment_picture!=null){
        upload_picture_section = "<div class='row'>\n\
            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>\n\
                <div class='form-group'>\n\
                    <label for='delivery_date'>Picture <a href='"+info[0].payment_picture+"' data-toggle='lightbox' id='open-image' data-title='&nbsp;' data-footer='&nbsp;'>Show</a></label>\n\
                    <button id='delete_pi_pic' onclick='delete_picture_cheque("+id_int+")' type='button' class='btn btn-danger' style='width:100%'>Delete</button>\n\
                </div>\n\
            </div>\n\
        </div>";
    }
    
    $.getJSON("?r=settings_info&f=get_info", function (data) {
        $.each(data.payments_method, function (key, val) {
            payment_options+="<option value='"+val.id+"' title='"+val.method_name+"'>"+val.method_name+"</option>";
        });
        $.each(data.banks, function (key, val) {
            banks_options+="<option value='"+val.id+"' title='"+val.name+"'>"+val.name+"</option>";
        });
        $.each(data.currencies, function (key, val) {
            currencies_options+="<option value='"+val.id+"' title='"+val.name+" ("+val.symbole+")'>"+val.name+" ("+val.symbole+")</option>";
        });
        number_of_decimal_points = data.settings[0].number_of_decimal_points;
    }).done(function () {
        if($("#add_supplier_payment").hasClass("disabled")==false){
 
            if(supplier_id==null || supplier_id==0){
                swal("There is no supplier for this invoice");
                $(".sk-circle-layer").hide();
            }else{
                var content =
                    '<div class="modal medium" data-backdrop="static" data-keyboard="false" id="add_supplier_payment_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">\n\
                    <div class="modal-dialog" role="document">\n\
                        <div class="modal-content">\n\
                            <form id="add_supplier_payment_form" action="" method="post" enctype="multipart/form-data" >\n\
                            <input id="id_to_edit" name="id_to_edit" type="hidden" value="'+id_int+'" />\n\
                            <div class="modal-header"> \n\
                                <h3 class="modal-title" id="exampleModalLongTitle"><i class="icon-payment"></i>&nbsp;Add Supplier Payment ('+supplier_name+')</h3>\n\
                            </div>\n\
                            <div class="modal-body">\n\
                                <div class="row">\n\
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">\n\
                                        <div class="form-group">\n\
                                            <label for="payment_method">Payment Method</label>\n\
                                            <select onchange="payment_method_supplier_changed()" id="payment_method" name="payment_method" class="selectpicker form-control" >'+payment_options+'</select>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">\n\
                                        <div class="form-group">\n\
                                            <label for="payment_value">Payment Value</label>\n\
                                            <div class="inner-addon"><input autocomplete="off" id="payment_value" name="payment_value" value="0" type="text" class="form-control med_input"></div>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">\n\
                                        <div class="form-group">\n\
                                            <label for="payment_date">Value Date</label>\n\
                                            <div class="inner-addon"><input id="payment_date" name="payment_date" type="text" class="form-control datepicker med_input"></div>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">\n\
                                        <div class="form-group">\n\
                                            <label for="payment_currency">Currency</label>\n\
                                            <select data-live-search="true" id="payment_currency" name="payment_currency" class="selectpicker form-control" >'+currencies_options+'</select>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display:none">\n\
                                        <div class="form-group">\n\
                                            <label for="payment_note">Rate</label>\n\
                                            <div class="inner-addon"><input autocomplete="off" id="" name="currency_rate" type="text" class="form-control med_input" placeholder="Rate"></div>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pl2" id="currency_rate_container">\n\
                                        <div class="form-group">\n\
                                            <label for="inv_rate" style="width:100%">Rate</label>\n\
                                            <div class="input-group">\n\
                                                <span class="input-group-addon" style="width:40px;"><b>1 USD </b>= </span>\n\
                                                    <input type="text" class="form-control cleavesf3" name="currency_rate" id="currency_rate" value="1500" placeholder="" style="padding-left:5px;padding-right:5px;font-weight:bold;" />\n\
                                                <span class="input-group-addon" style="width:40px;"><b>LBP</b></span>\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="row">\n\
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 bank_input credit_card_input" style="display:none">\n\
                                        <div class="form-group">\n\
                                            <label for="bank_source">Bank</label>&nbsp;&nbsp;<span onclick="addBank(\'supplier_payment\')" style="cursor:pointer; font-size:14px; font-weight:bold;color:#286090">Add new bank</span>\n\
                                            <select data-live-search="true" id="bank_source" name="bank_source" class="selectpicker form-control" >'+banks_options+'</select>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 bank_input credit_card_input" style="display:none">\n\
                                        <div class="form-group">\n\
                                            <label for="payment_note">Reference Number</label>\n\
                                            <div class="inner-addon"><input autocomplete="off" id="reference" name="reference" type="text" class="form-control med_input" placeholder="Reference"></div>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 bank_input" style="display:none">\n\
                                        <div class="form-group">\n\
                                            <label for="payment_note">Owner</label>\n\
                                            <div class="inner-addon"><input autocomplete="off" id="payment_owner" name="payment_owner" type="text" class="form-control med_input" placeholder="Owner"></div>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 credit_card_input" style="display:none">\n\
                                        <div class="form-group">\n\
                                            <label for="payment_note">Voucher Number</label>\n\
                                            <div class="inner-addon"><input autocomplete="off" id="voucher_nb" name="voucher_nb" type="text" class="form-control med_input" placeholder="Voucher"></div>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 bank_input credit_card_input" style="display:none" id="pic_upload">\n\
                                        '+upload_picture_section+'\n\
                                    </div>\n\
                                </div>\n\
                                <div class="row">\n\
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="display:none">\n\
                                        <div class="form-group">\n\
                                            <label for="supplier_id">Suppliers</label>\n\
                                            <select data-live-search="true" onchange="getInvoicesOfSupplier()" id="supplier_id" name="supplier_id" class="selectpicker form-control" >'+suppliers_options+'</select>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="display:none">\n\
                                        <div class="form-group">\n\
                                            <label for="invoice_order_id">Invoice Order</label>\n\
                                            <select data-live-search="true" onchange="invoicesOfSupplierChanged()" id="invoice_order_id" name="invoice_order_id" class="selectpicker form-control" >'+all_invoices+'</select>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                        <div class="form-group">\n\
                                            <label for="payment_note">Note</label>\n\
                                            <div class="inner-addon"><input autocomplete="off" id="payment_note" name="payment_note" type="text" class="form-control" placeholder="Note"></div>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="modal-footer">\n\
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">\n\
                                    <div class="form-group">\n\
                                    &nbsp;\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">\n\
                                    <div class="form-group">\n\
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width:100%;color: #000;">Cancel</button>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">\n\
                                    <div class="form-group">\n\
                                        <button id="sbm_btn" id="action_btn" type="submit" class="btn btn-primary" style="width:100%; color: #fff;">Add</button>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            </form>\n\
                        </div>\n\
                    </div>\n\
                </div>';


                $('#add_supplier_payment_modal').remove();
                $('body').append(content);
                
                
                $('.datepicker').datepicker({autoclose:true,format: 'yyyy-mm-dd'});
                
                $(".datepicker").datepicker( "setDate", new Date() );
                $('.datepicker').datepicker().on('changeDate', function(ev) {

                }).on('hide show', function(event) {
                    event.preventDefault();
                    event.stopPropagation();
                });
            
            
                $('.selectpicker').selectpicker();

                $("#supplier_id").selectpicker('val', supplier_id);

                if(id_int!=null){
                    $("#invoice_order_id").append("<option value='"+id_int+"'>"+id_int+"</option>");
                    $("#invoice_order_id").selectpicker('refresh');
                    $("#invoice_order_id").selectpicker('val', id_int);
                }

                submit_supplier_payment(page);
                
                //$('#add_supplier_payment_modal').modal('show');
                
                $('#add_supplier_payment_modal').on('shown.bs.modal', function (e) {
                    //$(".mask_format").mask("#,##0.00", {reverse: true});
                    
                    
                    
                    
                    $("#currency_rate_container").hide();
    
                    //update_rate();
                    
                    $('#open-image').click(function (e) {
                        e.preventDefault();
                        $(this).ekkoLightbox();
                    });
                    
                    //cheque_picture_form_submit();
                    
                    if(info.length>0){
                        $("#payment_method").selectpicker('val', info[0].payment_method);
                        $("#payment_method").prop("disabled","disabled");
                        payment_method_supplier_changed();
                        
                        //format_input_number(info[0].payment_value,"#payment_value",2,0);
                        
                        $('#payment_date').datepicker( "setDate", info[0].payment_date.split(" ")[0]);
                        $("#payment_date").prop("disabled","disabled");
                        
                        $("#payment_value").val(parseFloat(info[0].payment_value));
                        $("#payment_value").prop("disabled","disabled");
                        
                        $("#payment_currency").selectpicker('val', info[0].payment_currency);
                        $("#payment_currency").prop("disabled","disabled");
                        //update_rate();
                        
                        $("#currency_rate").val(parseFloat(info[0].usd_to_lbp));
                        
                        if(parseFloat(info[0].usd_to_lbp)==0){
                            $("#currency_rate_container").hide();
                        }
                        $("#currency_rate").prop("disabled","disabled");
                        
                        
                        $("#supplier_id").append("<option value='"+$("#suppliers_list").val()+"'>"+$("#suppliers_list option:selected").text()+"</option>");
                        $("#supplier_id").selectpicker('refresh');    
                        $("#supplier_id").prop("disabled","disabled");
                        
                        $("#invoice_order_id").append("<option value='"+info[0].invoice_order_id+"'>"+info[0].invoice_order_id+"</option>");
                        $("#invoice_order_id").selectpicker('val', info[0].invoice_order_id);
                        $("#invoice_order_id").prop("disabled","disabled");
                        
                        
                        
                        $("#payment_note").val(info[0].payment_note);
                        $("#payment_note").prop("disabled","disabled");
       
                        $("#bank_source").selectpicker('val', info[0].bank_id);
                        $("#bank_source").prop("disabled","disabled");
                        
                        $("#reference").val(info[0].reference);
                        $("#reference").prop("disabled","disabled");
                        
                        $("#payment_owner").val(info[0].payment_owner);
                        $("#payment_owner").prop("disabled","disabled");
                        
                        $("#voucher_nb").val(info[0].voucher);
                        $("#voucher_nb").prop("disabled","disabled");
                        
                        $("#sbm_btn").html('Update pictures');
                    }
                    cleaves_id("payment_value",5);
                    cleaves_id("currency_rate",5);
                     $(".sk-circle-layer").hide();
                }); 
                
                $('#add_supplier_payment_modal').on('hide.bs.modal', function (e) {
                    $('#add_supplier_payment_modal').remove();
                });
                $('#add_supplier_payment_modal').modal('show');
            }
        }  
    });
}

function submit_supplier_payment(page) {
    $("#add_supplier_payment_form").on('submit', (function (e) {
        e.preventDefault();
        var error = 0;
        if($("#supplier_id").val()==0){error=1;$("#supplier_id").selectpicker('setStyle', 'btn-danger');};
        //if($("#invoice_order_id").val()==0){error=2;$("#invoice_order_id").selectpicker('setStyle', 'btn-danger');};
        
        if(error==0){
            //$("#payment_value").unmask("#.##0");
            //$("#payment_value").unmask("#,##0.00");
            $("#payment_value").val($("#payment_value").val().replace(/,/g , ''));
            $("#currency_rate").val($("#currency_rate").val().replace(/,/g , ''));
            $(".sk-circle-layer").show();
            $.ajax({
                url: "?r=suppliers&f=add_supplier_payment",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function (data)
                {
              
                    if(page=="sup_pay"){
                        var table = $('#suppliers_table').DataTable();
                        table.ajax.url("?r=suppliers&f=getSuppliersPayments").load(function () {
                            table.row('.' + pad(data[0],5), {page: 'current'}).select();
                        },false);
                        $(".sk-circle-layer").hide();    
                       // var table = $('#suppliers_table').DataTable();
                        //table.ajax.url('?r=suppliers&f=getSuppliersPayments').load(function () {
                            //$(".tab_toolbar button.blueB").addClass("disabled");
                            //table.row('.' + pad(data[0],5), {page: 'current'}).select();
                        //},false);
                    }else if(page=="sup_stm"){
                        var table = $('#suppliers_statement_table').DataTable();
                        table.ajax.url("?r=suppliers&f=get_supplier_statement&p0="+$("#suppliers_list").val()).load(function () {
                            table.page('last').draw(false);
                            table.row(':last', {page: 'current'}).select();
                            $(".dataTables_scrollBody").scrollTop($('.dataTables_scrollBody')[0].scrollHeight);
                            $(".sk-circle-layer").hide();
                        },false);
                    }else if(page=="stock_invoice"){
                        $(".sk-circle-layer").hide();
                    }else if(page=="POS"){
                        supplier_changed_pos();
                        $(".sk-circle-layer").hide();
                    }else if(page=="admin_direct"){
                        var table = $('#suppliers_table').DataTable();
                        table.ajax.url('?r=suppliers&f=getSuppliers&p0='+$("#all_remain").val()).load(function () {
                            $(".sk-circle-layer").hide();
                        }, false);
                    } else if(page=="complex_stmt"){
                        
                        var table = $('#suppliers_statement_table').DataTable();
                        table.ajax.url("?r=suppliers&f=get_supplier_statement_new_version&p0=0").load(function () {
                            $(".sk-circle-layer").hide();
                        }, false);
                        $(".sk-circle-layer").hide();
                    }else{
                        if($("#pos_supplier_payment").length>0){
                            var table = $('#pos_supplier_payment').DataTable();
                            table.ajax.url("?r=suppliers&f=get_supplier_statement_new_version&p0=0").load(function () {
                                $(".sk-circle-layer").hide();
                            }, false);
                            $(".sk-circle-layer").hide();
                        }
                    }  
                    
                    
                    if($("#total_pi_value").length>0){
                        update_pi_info();
                    }
                    
                    $('#add_supplier_payment_modal').modal('hide');
                    
                }
            });
        }
    }));
}

function invoicesOfSupplierChanged(){
    var invoice_order_id = $("#invoice_order_id").val();
    if(invoice_order_id != 0){$("#invoice_order_id").selectpicker('setStyle', 'btn-danger', 'remove');};
}

function getInvoicesOfSupplier(){
    var supplier_id = $("#supplier_id").val();

    if(supplier_id != 0){$("#supplier_id").selectpicker('setStyle', 'btn-danger', 'remove');};
    
    $.getJSON("?r=suppliers&f=getInvoicesOfSupplier&p0="+supplier_id, function (data) {
        $("#invoice_order_id").empty();
        $("#invoice_order_id").append("<option value='0' title='Select Invoice Order'>Select Invoice Order</option>");
        $.each(data, function (key, val) {
            $("#invoice_order_id").append("<option value='"+val.id+"' title='"+PadSTKINV(val.id)+"'>"+PadSTKINV(val.id)+"</option>");
        });
        $('#invoice_order_id').selectpicker('refresh');
    }).done(function () {
        
    });  
}

function update_pi_info(){
    $.getJSON("?r=stock&f=get_pi_info&p0="+$("#suppliers_list").val()+"&p1=0&p2="+$("#piDate").val(), function (data) {
        $("#total_pi_value").html(data.all_pi_value);
        $("#total_pi_vat_value").html(data.all_pi_vat_value);
        $("#total_payments").html(data.total_payments);
    }).done(function () {
        
    });  
}


function ReadExcelSheetPI() {  
     var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xlsx|.xls)$/;  
     /*Checks whether the file is a valid excel file*/  
     if (regex.test($("#excelfile").val().toLowerCase())) {  
         var xlsxflag = false; /*Flag for checking whether excel is .xls format or .xlsx format*/  
         if ($("#excelfile").val().toLowerCase().indexOf(".xlsx") > 0) {  
             xlsxflag = true;  
         }  
         /*Checks whether the browser supports HTML5*/  
         if (typeof (FileReader) != "undefined") {  
             var reader = new FileReader();  
             reader.onload = function (e) {  
                 var data = e.target.result;  
                 /*Converts the excel data in to object*/  
                 if (xlsxflag) {  
                     var workbook = XLSX.read(data, { type: 'binary' });  
                 }  
                 else {  
                     var workbook = XLS.read(data, { type: 'binary' });  
                 }  
                 /*Gets all the sheetnames of excel in to a variable*/  
                 var sheet_name_list = workbook.SheetNames;  
  
                 var cnt = 0; /*This is used for restricting the script to consider only first sheet of excel*/  
                 sheet_name_list.forEach(function (y) { /*Iterate through all sheets*/  
                     /*Convert the cell value to Json*/  
                     if (xlsxflag) {  
                         var exceljson = XLSX.utils.sheet_to_json(workbook.Sheets[y]);  
                     }  
                     else {  
                         var exceljson = XLS.utils.sheet_to_row_object_array(workbook.Sheets[y]);
                     }  
                     if (exceljson.length > 0 && cnt == 0) {  
                         //BindTable(exceljson, '#exceltable');  
                         fetchExcel(exceljson);
                         cnt++;  
                     }  
                 });  
                 $('#exceltable').show();  
             }  
             if (xlsxflag) {/*If excel file is .xlsx extension than creates a Array Buffer from excel*/  
                 reader.readAsArrayBuffer($("#excelfile")[0].files[0]);  
             }  
             else {  
                 reader.readAsBinaryString($("#excelfile")[0].files[0]);  
             }  
         }  
         else {  
             alert("Sorry! Your browser does not support HTML5!");  
         }  
     }  
     else {  
         alert("Please upload a valid Excel file!");  
     }  
 }  
 
function fetchExcel(jsondata){
    if(jsondata.length>0){
        importing(jsondata,0);
    }
}

var last_item_id=0;
function importing(jsondata,index){
    var is_composite=0;
    
    if(jsondata[index].box.toLowerCase()=="yes"){
        is_composite=1;
    }
    
    if(jsondata[index].box.toLowerCase()=="yes"){
        
    }else{
        last_item_id=0;
    }
    
    //console.log(jsondata[index].box.toLowerCase());
    
    

    if(jsondata[index].unit_cost=="" || typeof  jsondata[index].unit_cost=='undefined'){
        jsondata[index].unit_cost="0";
    }
    if(jsondata[index].retail_price=="" || typeof  jsondata[index].retail_price=='undefined' ){
        jsondata[index].retail_price="0";
    }
    if(jsondata[index].wholesale=="" || typeof jsondata[index].wholesale=='undefined'){
        jsondata[index].wholesale="0";
    }
    if(jsondata[index].second_wholesale=="" || typeof jsondata[index].second_wholesale=='undefined'){
        jsondata[index].second_wholesale="0";
    }
    if(jsondata[index].image_link=="" || typeof jsondata[index].image_link=='undefined'){
        jsondata[index].image_link="";
    }
    
    if(jsondata[index].free_qty=="" || typeof jsondata[index].free_qty=='undefined'){
        jsondata[index].free_qty=0;
    }
    
    if(jsondata[index].discount=="" || typeof jsondata[index].discount=='undefined'){
        jsondata[index].discount=0;
    }
    
    if(jsondata[index].vat=="" || typeof jsondata[index].vat=='undefined'){
        jsondata[index].vat=0;
    }
    
 
    $('body').append('\
        <form id="pi_import_form">\n\
            <input type="hidden" name="id_to_edit" id="id_to_edit" value="0">\n\
            <input type="hidden" name="based_on_sku" id="based_on_sku" value="0">\n\
            <input type="hidden" name="based_on_barcode" id="based_on_barcode" value="1">\n\
            <input type="hidden" name="item_desc" id="item_desc" value="'+jsondata[index].description+'">\n\
            <input type="hidden" name="item_alias" id="item_alias" value="">\n\
            <input type="hidden" name="another_description" id="another_description" value="">\n\
            <input type="hidden" name="item_barcode" id="item_barcode" value="'+jsondata[index].barcode+'">\n\
            <input type="hidden" name="item_barcode_second" id="item_barcode_second" value="">\n\
            <input type="hidden" name="item_pcat_text" id="item_pcat_text" value="'+jsondata[index].category+'">\n\
            <input type="hidden" name="item_cat_text" id="item_cat_text" value="'+jsondata[index].subcategory+'">\n\
            <input type="hidden" name="supplier_id" id="supplier_id" value="1">\n\
            <input type="hidden" name="item_cost" id="item_cost" value="'+jsondata[index].unit_cost+'">\n\
            <input type="hidden" name="item_vat" id="item_vat" value="0">\n\
            <input type="hidden" name="discount" id="discount" value="0">\n\
            <input type="hidden" name="item_vat_on_sale" id="item_vat_on_sale" value="0">\n\
            <input type="hidden" name="vat_on_sale" id="vat_on_sale" value="0">\n\
            <input type="hidden" name="item_disc" id="item_disc" value="0">\n\
            <input type="hidden" name="item_final_price" id="item_final_price" value="'+jsondata[index].retail_price+'">\n\
            <input type="hidden" name="lack_warning" id="lack_warning" value="3">\n\
            <input type="hidden" name="item_unit_measure" id="item_unit_measure" value="">\n\
            <input type="hidden" name="item_size_pi" id="item_size_pi" value="'+jsondata[index].size+'">\n\
            <input type="hidden" name="item_color" id="item_color" value="">\n\
            <input type="hidden" name="material_id" id="material_id" value="1">\n\
            <input type="hidden" name="expiry_date" id="expiry_date" value="">\n\
            <input type="hidden" name="is_composite" id="expiry_date" value="'+is_composite+'">\n\
            <input type="hidden" name="composite_item_id" id="composite_item_id" value="'+last_item_id+'">\n\
            <input type="hidden" name="composite_item_qty" id="composite_item_qty" value="'+parseFloat(jsondata[index].qty)+'">\n\
            <input type="hidden" name="text_color_pi" id="text_color_pi" value="'+jsondata[index].color+'">\n\
            <input type="hidden" name="item_final_wholesale_price" id="item_final_wholesale_price" value="'+jsondata[index].wholesale+'">\n\
            <input type="hidden" name="item_final_sec_wholesale_price" id="item_final_sec_wholesale_price" value="'+jsondata[index].second_wholesale+'">\n\
            <input type="hidden" name="supplier_ref" id="supplier_ref" value="">\n\
            <input type="hidden" name="official_or_not" id="official_or_not" value="">\n\
            <input type="hidden" name="item_sku" id="item_sku" value="'+jsondata[index].reference+'">\n\
            <input type="hidden" name="is_official" id="is_official" value="">\n\
            <input type="hidden" name="image_link" id="image_link" value="'+jsondata[index].image_link+'">\n\
        </form>'); 
    $("#pi_import_form").on('submit', (function (e) {
       e.preventDefault();
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
            
                if(is_composite==0){
                    last_item_id=data.id;
                }else{
                    last_item_id=0;
                }
                
                
                
                
                
                
                $("#pi_import_form").remove();
                
                if($(".check_uniq_"+data.id).length==0 && is_composite==0){
                    var data_item = [{"qty":parseFloat(jsondata[index].qty),"free_qty":parseFloat(jsondata[index].free_qty),"discount":parseFloat(jsondata[index].discount),"vat":(jsondata[index].vat),"unit_cost":parseFloat(jsondata[index].unit_cost.replace(/,\s?/g, ""))}];
                     addItemToInvoice(data.id,data_item);
                }
                
                if(index<jsondata.length-1){
                    index = index+1;
                    importing(jsondata,index);
                }
                
                
               
            }
        });
   }));
   $("#pi_import_form").submit();
}