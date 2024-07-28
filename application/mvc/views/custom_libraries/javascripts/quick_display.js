function quick_show_invoices_dashboard(status){
    var daterange=$("#date_range").val();
    quick_show_invoices(daterange,status);
}

function quick_show_invoices(daterange,status){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var modal_name = "quick_invoices";
    var modal_table_="quick_invoices_table";
    var content =
    '<div class="modal large" data-backdrop="static" id="'+modal_name+'" role="dialog">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header" style="padding-top:5px;padding-bottom:5px;"> \n\
                    <h3 class="modal-title">Invoices<i style="font-size:35px;float:right" class="glyphicon glyphicon-remove" onclick="closeModal(\''+modal_name+'\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body" style="padding-top:0px;">\n\
                    <input id="quick_daterange" value="'+daterange+'" type="hidden" />\n\
                    <input id="quick_status" value="'+status+'" type="hidden" />\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <table id="'+modal_table_+'" class="table table-striped table-bordered" cellspacing="0">\n\
                                <thead>\n\
                                    <tr>\n\
                                        <th style="width: 65px !important;">Ref.</th>\n\
                                        <th style="width: 115px !important;">Date</th>\n\
                                        <th>Client name</th>\n\
                                        <th style="width: 90px !important;">Subtotal</th>\n\
                                        <th style="width: 90px !important;">Invoice Disc.</th>\n\
                                        <th style="width: 90px !important;">Tax</th>\n\
                                        <th style="width: 90px !important;">Freight</th>\n\
                                        <th style="width: 90px !important;">Total</th>\n\
                                        <th style="width: 90px !important;">Profit</th>\n\
                                        <th style="width: 60px !important;">Method</th>\n\
                                        <th style="width: 60px !important;">&nbsp;</th>\n\
                                        <th style="width: 60px !important;">&nbsp;</th>\n\
                                        <th style="width: 60px !important;">&nbsp;</th>\n\
                                    </tr>\n\
                                </thead>\n\
                                <tfoot>\n\
                                    <tr>\n\
                                        <th>Ref.</th>\n\
                                        <th>Date</th>\n\
                                        <th>Client name</th>\n\
                                        <th>Subtotal</th>\n\
                                        <th>Inv. Disc.</th>\n\
                                        <th>Tax</th>\n\
                                        <th>Freight</th>\n\
                                        <th>Total</th>\n\
                                        <th>Profit</th>\n\
                                        <th>Method</th>\n\
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
    $("#"+modal_name).remove();
    $("body").append(content);
    $('#'+modal_name).on('show.bs.modal', function (e) {
        
        var items_search = null;
        var search_fields = [0,1,2,3,4,5,6,7,8,9];
        var index = 0;
        $('#'+modal_table_+' tfoot th').each( function () {
            if(jQuery.inArray(index, search_fields) !== -1){
                var title = $(this).text();
                $(this).html( '<input style="width: 100% !important;padding-left:0px;"  class="form-control input-sm" type="text" placeholder=" '+title+'" />' );
                index++;
            }
        });           
        items_search = $('#'+modal_table_).DataTable({
            ajax: {
                url: "?r=quick_display&f=get_all_invoices_quick&p0="+daterange+"&p1="+status,
                type: 'POST',
                error:function(xhr,status,error) {
                    logged_out_warning();
                },
            },
            responsive: true,
            orderCellsTop: true,
            scrollY: "70vh",
            iDisplayLength: 100,
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
                { "targets": [9], "searchable": true, "orderable": false, "visible": true },
                { "targets": [10], "searchable": true, "orderable": false, "visible": false },
                 { "targets": [11], "searchable": true, "orderable": false, "visible": false },
                 { "targets": [12], "searchable": true, "orderable": false, "visible": true }
            ],
            scrollCollapse: true,
            paging: false,
            bPaginate: false,
            bLengthChange: false,
            bFilter: true,
            bInfo: false,
            bSort:false,
            bAutoWidth: true,
            dom: '<"toolbar_invces">frtip',
            initComplete: function(settings, json) {                
                $(".sk-circle-layer").hide();
                
                $("div.toolbar_invces").html('\n\
                <div class="row" style="margin-top:5px;">\n\
                    <div class="col-lg-12 col-md-12 col-sm-12" >\n\
                        <div class="btn-group" role="group" aria-label="" style="width:100%">\n\
                            <div class="btn-group" id="buttons" style="float:right"></div>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
                ');  
                
                
                var buttons = new $.fn.dataTable.Buttons(items_search, {
                    buttons: [
                      {
                            extend: 'excel',
                            text: 'Export excel',
                            className: 'exportExcel',
                            filename: 'Sales ',
                            customize: _customizeExcelOptions,
                            exportOptions: {
                                modifier: {
                                    page: 'all'
                                },
                                //columns: [ 0,1,2,3,4,5,6,7 ]
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
            },
            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $(nRow).addClass(aData[0]);
                
                
                
            },
            fnDrawCallback: update_quick_invoices,
        });
        
        $('#'+modal_table_).DataTable().on('mousedown',"tr", function ( e, dt, type, indexes ) { 
            $('.selected').removeClass("selected");
            $(this).addClass('selected');
        });

        
        $('#'+modal_table_).DataTable().columns().every( function () {
            var that = this;
            $( 'input', this.footer() ).on( 'keyup change', function () {
                items_search.keys.disable();
                if ( that.search() !== this.value ) {
                    that.search( this.value ).draw();
                }
                items_search.keys.enable();
            } );
        });
    });

    $('#'+modal_name).on('shown.bs.modal', function (e) {

    });
    $('#'+modal_name).on('hide.bs.modal', function (e) {
        $("#"+modal_name).remove();
    });
    $('#'+modal_name).modal('show');
}

function update_quick_invoices(){
    var table = $('#quick_invoices_table').DataTable();
    var p = table.rows({ page: 'current' }).nodes();
    for (var k = 0; k < p.length; k++){
        var index = table.row(p[k]).index();
        table.cell(index,10).data('<i title="print" class="glyphicon glyphicon-print" onclick="print_sheet(\''+parseInt(table.cell(index, 0).data().split("-")[1])+'\')" style="font-size:18px;cursor:pointer" ></i>&nbsp;&nbsp;<i title="Edit" class="glyphicon glyphicon-edit" onclick="edit_manual_invoice(\''+parseInt(table.cell(index, 0).data().split("-")[1])+'\')" style="font-size:18px;cursor:pointer" ></i>');
    }
}


function quick_show_suppliers_payments(){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var daterange=$("#date_range").val();
    var modal_name = "quick_invoices";
    var modal_table_="quick_invoices_table";
    var content =
    '<div class="modal large" data-backdrop="static" id="'+modal_name+'" role="dialog">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header" style="padding-top:5px;padding-bottom:5px;"> \n\
                    <h3 class="modal-title">Suppliers Payments<i style="font-size:35px;float:right" class="glyphicon glyphicon-remove" onclick="closeModal(\''+modal_name+'\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body" style="padding-top:0px;">\n\
                    <input id="quick_daterange" value="'+daterange+'" type="hidden" />\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <table id="'+modal_table_+'" class="table table-striped table-bordered" cellspacing="0">\n\
                                <thead>\n\
                                    <tr>\n\
                                        <th style="width: 65px !important;">Ref.</th>\n\
                                        <th style="width: 300px !important;">Supplier name</th>\n\
                                        <th style="width: 120px !important;">Amount</th>\n\
                                        <th style="width: 120px !important;">Date</th>\n\
                                        <th>Note</th>\n\
                                    </tr>\n\
                                </thead>\n\
                                <tfoot>\n\
                                    <tr>\n\
                                        <th>Ref.</th>\n\
                                        <th>Supplier name</th>\n\
                                        <th>Amount</th>\n\
                                        <th>Date</th>\n\
                                        <th>Note</th>\n\
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
        
        var items_search = null;
        var search_fields = [0,1,2,3,4];
        var index = 0;
        $('#'+modal_table_+' tfoot th').each( function () {
            if(jQuery.inArray(index, search_fields) !== -1){
                var title = $(this).text();
                $(this).html( '<input style="width: 100% !important;padding-left:0px;"  class="form-control input-sm" type="text" placeholder=" '+title+'" />' );
                index++;
            }
        });           
        items_search = $('#'+modal_table_).DataTable({
            ajax: {
                url: "?r=quick_display&f=get_all_payments_quick&p0="+daterange,
                type: 'POST',
                error:function(xhr,status,error) {
                    
                },
            },
            responsive: true,
            orderCellsTop: true,
            scrollY: "70vh",
            iDisplayLength: 100,
            aoColumnDefs: [
                { "targets": [0], "searchable": true, "orderable": true, "visible": true },
                { "targets": [1], "searchable": true, "orderable": true, "visible": true },
                { "targets": [2], "searchable": true, "orderable": true, "visible": true },
                { "targets": [3], "searchable": true, "orderable": true, "visible": true },
                { "targets": [4], "searchable": true, "orderable": true, "visible": true },
            ],
            scrollCollapse: true,
            paging: false,
            bPaginate: false,
            bLengthChange: false,
            bFilter: true,
            bInfo: false,
            bSort:false,
            bAutoWidth: true,
            dom: '<"toolbar_invces">frtip',
            initComplete: function(settings, json) {                
                $(".sk-circle-layer").hide();
                
                $("div.toolbar_invces").html('\n\
                <div class="row" style="margin-top:5px;">\n\
                    <div class="col-lg-12 col-md-12 col-sm-12" >\n\
                        <div class="btn-group" role="group" aria-label="" style="width:100%">\n\
                            <div class="btn-group" id="buttons" style="float:right"></div>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
                ');  
                
                
                var buttons = new $.fn.dataTable.Buttons(items_search, {
                    buttons: [
                      {
                            extend: 'excel',
                            text: 'Export excel',
                            className: 'exportExcel',
                            filename: 'Supplier Payments ',
                            customize: _customizeExcelOptions,
                            exportOptions: {
                                modifier: {
                                    page: 'all'
                                },
                                //columns: [ 0,1,2,3,4,5,6,7 ]
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
            },
            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $(nRow).addClass(aData[0]);
                
                
                
            },
            //fnDrawCallback: update_quick_invoices,
        });
        
        $('#'+modal_table_).DataTable().on('mousedown',"tr", function ( e, dt, type, indexes ) { 
            $('.selected').removeClass("selected");
            $(this).addClass('selected');
        });

        
        $('#'+modal_table_).DataTable().columns().every( function () {
            var that = this;
            $( 'input', this.footer() ).on( 'keyup change', function () {
                items_search.keys.disable();
                if ( that.search() !== this.value ) {
                    that.search( this.value ).draw();
                }
                items_search.keys.enable();
            } );
        });
    });

    $('#'+modal_name).on('shown.bs.modal', function (e) {

    });
    $('#'+modal_name).on('hide.bs.modal', function (e) {
        $("#"+modal_name).remove();
    });
    $('#'+modal_name).modal('show');
}

function quick_show_customers_payments(){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var daterange=$("#date_range").val();
    var modal_name = "quick_invoices";
    var modal_table_="quick_invoices_table";
    var content =
    '<div class="modal large" data-backdrop="static" id="'+modal_name+'" role="dialog">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header" style="padding-top:5px;padding-bottom:5px;"> \n\
                    <h3 class="modal-title">Clients Payments<i style="font-size:35px;float:right" class="glyphicon glyphicon-remove" onclick="closeModal(\''+modal_name+'\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body" style="padding-top:0px;">\n\
                    <input id="quick_daterange" value="'+daterange+'" type="hidden" />\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <table id="'+modal_table_+'" class="table table-striped table-bordered" cellspacing="0">\n\
                                <thead>\n\
                                    <tr>\n\
                                        <th style="width: 65px !important;">Ref.</th>\n\
                                        <th style="width: 300px !important;">Client name</th>\n\
                                        <th style="width: 300px !important;">Collected By</th>\n\
                                        <th style="width: 120px !important;">Amount</th>\n\
                                        <th style="width: 120px !important;">Date</th>\n\
                                        <th>Note</th>\n\
                                    </tr>\n\
                                </thead>\n\
                                <tfoot>\n\
                                    <tr>\n\
                                        <th>Ref.</th>\n\
                                        <th>Client name</th>\n\
                                        <th>Collected By</th>\n\
                                        <th>Amount</th>\n\
                                        <th>Date</th>\n\
                                        <th>Note</th>\n\
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
        
        var items_search = null;
        var search_fields = [0,1,2,3,4];
        var index = 0;
        $('#'+modal_table_+' tfoot th').each( function () {
            if(jQuery.inArray(index, search_fields) !== -1){
                var title = $(this).text();
                $(this).html( '<input style="width: 100% !important;padding-left:0px;"  class="form-control input-sm" type="text" placeholder=" '+title+'" />' );
                index++;
            }
        });           
        items_search = $('#'+modal_table_).DataTable({
            ajax: {
                url: "?r=quick_display&f=get_all_customers_payments_quick&p0="+daterange,
                type: 'POST',
                error:function(xhr,status,error) {
                    
                },
            },
            responsive: true,
            orderCellsTop: true,
            scrollY: "60vh",
            iDisplayLength: 100,
            aoColumnDefs: [
                { "targets": [0], "searchable": true, "orderable": true, "visible": true },
                { "targets": [1], "searchable": true, "orderable": true, "visible": true },
                { "targets": [2], "searchable": true, "orderable": true, "visible": true },
                { "targets": [3], "searchable": true, "orderable": true, "visible": true },
                { "targets": [4], "searchable": true, "orderable": true, "visible": true },
                { "targets": [5], "searchable": true, "orderable": true, "visible": true },
            ],
            scrollCollapse: true,
            paging: false,
            bPaginate: false,
            bLengthChange: false,
            bFilter: true,
            bInfo: false,
            bSort:false,
            bAutoWidth: true,
            dom: '<"toolbar_invces">frtip',
            initComplete: function(settings, json) {                
                $(".sk-circle-layer").hide();
                
                $("div.toolbar_invces").html('\n\
                <div class="row" style="margin-top:5px;">\n\
                    <div class="col-lg-12 col-md-12 col-sm-12" >\n\
                        <div class="btn-group" role="group" aria-label="" style="width:100%">\n\
                            <div class="btn-group" id="buttons" style="float:right"></div>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
                ');  
                
                
                var buttons = new $.fn.dataTable.Buttons(items_search, {
                    buttons: [
                      {
                            extend: 'excel',
                            text: 'Export excel',
                            className: 'exportExcel',
                            filename: 'Supplier Payments ',
                            customize: _customizeExcelOptions,
                            exportOptions: {
                                modifier: {
                                    page: 'all'
                                },
                                //columns: [ 0,1,2,3,4,5,6,7 ]
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
            },
            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $(nRow).addClass(aData[0]);
                
                
                
            },
            //fnDrawCallback: update_quick_invoices,
        });
        
        $('#'+modal_table_).DataTable().on('mousedown',"tr", function ( e, dt, type, indexes ) { 
            $('.selected').removeClass("selected");
            $(this).addClass('selected');
        });

        
        $('#'+modal_table_).DataTable().columns().every( function () {
            var that = this;
            $( 'input', this.footer() ).on( 'keyup change', function () {
                items_search.keys.disable();
                if ( that.search() !== this.value ) {
                    that.search( this.value ).draw();
                }
                items_search.keys.enable();
            } );
        });
    });

    $('#'+modal_name).on('shown.bs.modal', function (e) {

    });
    $('#'+modal_name).on('hide.bs.modal', function (e) {
        $("#"+modal_name).remove();
    });
    $('#'+modal_name).modal('show');
}



function quick_show_expenses(){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var daterange=$("#date_range").val();
    var modal_name = "quick_expenses";
    var modal_table_="quick_expenses_table";
    var content =
    '<div class="modal large" data-backdrop="static" id="'+modal_name+'" role="dialog">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header" style="padding-top:5px;padding-bottom:5px;"> \n\
                    <h3 class="modal-title">Expenses<i style="font-size:35px;float:right" class="glyphicon glyphicon-remove" onclick="closeModal(\''+modal_name+'\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body" style="padding-top:0px;">\n\
                    <input id="quick_daterange" value="'+daterange+'" type="hidden" />\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <table id="'+modal_table_+'" class="table table-striped table-bordered" cellspacing="0">\n\
                                <thead>\n\
                                    <tr>\n\
                                        <th style="width: 65px !important;">Ref.</th>\n\
                                        <th style="width: 200px !important;">Type</th>\n\
                                        <th style="width: 120px !important;">Amount</th>\n\
                                        <th style="width: 120px !important;">Date</th>\n\
                                        <th>Description</th>\n\
                                    </tr>\n\
                                </thead>\n\
                                <tfoot>\n\
                                    <tr>\n\
                                        <th>Ref.</th>\n\
                                        <th>Type</th>\n\
                                        <th>Amount</th>\n\
                                        <th>Date</th>\n\
                                        <th>Description</th>\n\
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
        
        var items_search = null;
        var search_fields = [0,1,2,3,4];
        var index = 0;
        $('#'+modal_table_+' tfoot th').each( function () {
            if(jQuery.inArray(index, search_fields) !== -1){
                var title = $(this).text();
                $(this).html( '<input style="width: 100% !important;padding-left:0px;"  class="form-control input-sm" type="text" placeholder=" '+title+'" />' );
                index++;
            }
        });           
        items_search = $('#'+modal_table_).DataTable({
            ajax: {
                url: "?r=quick_display&f=get_all_expenses_quick&p0="+daterange,
                type: 'POST',
                error:function(xhr,status,error) {
                    
                },
            },
            responsive: true,
            orderCellsTop: true,
            scrollY: "60vh",
            iDisplayLength: 100,
            aoColumnDefs: [
                { "targets": [0], "searchable": true, "orderable": true, "visible": true },
                { "targets": [1], "searchable": true, "orderable": true, "visible": true },
                { "targets": [2], "searchable": true, "orderable": true, "visible": true },
                { "targets": [3], "searchable": true, "orderable": true, "visible": true },
                { "targets": [4], "searchable": true, "orderable": true, "visible": true },
            ],
            scrollCollapse: true,
            paging: false,
            bPaginate: false,
            bLengthChange: false,
            bFilter: true,
            bInfo: false,
            bSort:false,
            bAutoWidth: true,
            dom: '<"toolbar_invces">frtip',
            initComplete: function(settings, json) {                
                $(".sk-circle-layer").hide();
                
                $("div.toolbar_invces").html('\n\
                <div class="row" style="margin-top:5px;">\n\
                    <div class="col-lg-12 col-md-12 col-sm-12" >\n\
                        <div class="btn-group" role="group" aria-label="" style="width:100%">\n\
                            <div class="btn-group" id="buttons" style="float:right"></div>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
                ');  
                
                
                var buttons = new $.fn.dataTable.Buttons(items_search, {
                    buttons: [
                      {
                            extend: 'excel',
                            text: 'Export excel',
                            className: 'exportExcel',
                            filename: 'Supplier Payments ',
                            customize: _customizeExcelOptions,
                            exportOptions: {
                                modifier: {
                                    page: 'all'
                                },
                                //columns: [ 0,1,2,3,4,5,6,7 ]
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
            },
            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $(nRow).addClass(aData[0]);
                
                
                
            },
            //fnDrawCallback: update_quick_invoices,
        });
        
        $('#'+modal_table_).DataTable().on('mousedown',"tr", function ( e, dt, type, indexes ) { 
            $('.selected').removeClass("selected");
            $(this).addClass('selected');
        });

        
        $('#'+modal_table_).DataTable().columns().every( function () {
            var that = this;
            $( 'input', this.footer() ).on( 'keyup change', function () {
                items_search.keys.disable();
                if ( that.search() !== this.value ) {
                    that.search( this.value ).draw();
                }
                items_search.keys.enable();
            } );
        });
    });

    $('#'+modal_name).on('shown.bs.modal', function (e) {

    });
    $('#'+modal_name).on('hide.bs.modal', function (e) {
        $("#"+modal_name).remove();
    });
    $('#'+modal_name).modal('show');
}