function show_cashback_report(){
    $(".sk-circle").center();
    $(".sk-circle-layer").show(); 
    var table_name = "modal_all_cashback_table";
    var modal_name = "modal_all_cashback____";
    
    var modal_title = "Cashback report";
    
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
                                        <th style="width:80px;">Customer id</th>\n\
                                        <th >Customer name</th>\n\
                                        <th style="width:100px;">Nb of invoices</th>\n\
                                        <th style="width:110px;">Total Amount</th>\n\
                                        <th style="width:110px;">Cashback Value</th>\n\
                                        <th style="width:110px;">Cashback Paid</th>\n\
                                        <th style="width:110px;">Cashback Remain</th>\n\
                                        <th style="width:130px;color:#d43f3a ">Paid and cancelled</th>\n\
                                        <th style="width:160px;">&nbsp;</th>\n\
                                    </tr>\n\
                                </thead>\n\
                                <tfoot>\n\
                                    <tr>\n\
                                        <th>Customer id</th>\n\
                                        <th>Customer name</th>\n\
                                        <th>Number of invoices</th>\n\
                                        <th>Number of customers</th>\n\
                                        <th>Cashback Value</th>\n\
                                        <th>Cashback paid</th>\n\
                                        <th>Cashback Remain</th>\n\
                                        <th>Cancelled Payments</th>\n\
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
                url: "?r=cashback&f=show_all_cashback_customers&p0=0&p1=0&p2=0&p3=0",
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
                { "targets": [0], "searchable": true, "orderable": true, "visible":  false },
                { "targets": [1], "searchable": false, "orderable": false, "visible": true },
                { "targets": [2], "searchable": false, "orderable": false, "visible": true },
                { "targets": [3], "searchable": false, "orderable": false, "visible": true },
                { "targets": [4], "searchable": false, "orderable": false, "visible": true },
                { "targets": [5], "searchable": false, "orderable": false, "visible": true },
                { "targets": [6], "searchable": false, "orderable": false, "visible": true },
                { "targets": [7], "searchable": false, "orderable": false, "visible": true,"className": "dt-red" },
                { "targets": [8], "searchable": false, "orderable": false, "visible": true },
            ],
            scrollCollapse: true,
            paging: true,
            bPaginate: false,
            bLengthChange: false,
            bFilter: true,
            bInfo: false,
            bSort:false,
            bAutoWidth: true,
            dom: '<"toolbar_cashback">frtip',
            initComplete: function(settings, json) {
                 $("div.toolbar_cashback").html('\n\
                    <div class="row">\n\
                        <div class="col-lg-6 col-md-6 col-xs-6" style="padding-left:15px;padding-right:5px;">\n\
                            &nbsp;\n\
                        </div>\n\
                        <div class="col-lg-6 col-md-6 col-sm-6" >\n\
                            <div class="btn-group" role="group" aria-label="" style="width:100%">\n\
                                <div class="btn-group" id="buttons" style="float:right"></div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    ');    
                
                var buttons = new $.fn.dataTable.Buttons(_cards_table__var, {
                    buttons: [
                      {
                            extend: 'excel',
                            text: 'Export excel',
                            className: 'exportExcel',
                            filename: 'Cashback report ',
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
                
                $(".sk-circle-layer").hide();
            },
            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $(nRow).addClass("t_"+aData[0]);
            },
            fnDrawCallback: setcashbackOptions,
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
                search_in_datatable_global(this.value,that.index(),100,table_name);
            } );
        } );
       
    });
    $('#'+modal_name).on('hide.bs.modal', function (e) {
        $("#"+modal_name).remove();
    });
    $('#'+modal_name).modal('show');
}

function setcashbackOptions(){
    var table = $('#modal_all_cashback_table').DataTable();
    var p = table.rows({ page: 'current' }).nodes();
    for (var k = 0; k < p.length; k++){
        var index = table.row(p[k]).index();
        table.cell(index,8).data('<button onclick="add_cashback_for_customer('+table.cell(index,0).data()+')" type="button" class="btn btn-xs btn-default" style="width:70px">Add payment</button>&nbsp;&nbsp;<button onclick="show_cahback_payments('+table.cell(index,0).data()+')" type="button" class="btn btn-xs btn-default" style="width:90px">Payments details</button>');
    }
}

function setcashpaidOptions(){
    var table = $('#modal_all_cashback_payments_table').DataTable();
    var p = table.rows({ page: 'current' }).nodes();
    for (var k = 0; k < p.length; k++){
        var index = table.row(p[k]).index();
        table.cell(index,4).data('<i style="cursor:pointer;font-size:17px;"  class="glyphicon glyphicon-trash red" title="Delete" onclick="delete_cashback_payment('+parseInt(table.cell(index, 0).data())+')"></i>');
    }
}

function delete_cashback_payment(id){
    
    swal({
        title: "Are you sure?",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, Delete it.",
        closeOnConfirm: true,
        cancelButtonText: "Cancel",
    },
    function(isConfirm){
        if(isConfirm){
            $.getJSON("?r=cashback&f=delete_cashback_payment&p0="+id, function (data) {
        
            }).done(function () {
                var table = $('#modal_all_cashback_payments_table').DataTable();
                table.ajax.url("?r=cashback&f=show_all_cashback_for_customers&p0="+$("#customer_id_").val()+"&p1=0&p2=0&p3=0").load(function () {
                    $(".sk-circle-layer").hide();
                }, false);


                var table__ = $('#modal_all_cashback_table').DataTable();
                table__.ajax.url("?r=cashback&f=show_all_cashback_customers&p0=0&p1=0&p2=0&p3=0").load(function () {
                    $(".sk-circle-layer").hide();
                }, false);
            }); 
        }
    });
    
    
    
}


function show_cahback_payments(id){
   $(".sk-circle").center();
    $(".sk-circle-layer").show(); 
    var table_name = "modal_all_cashback_payments_table";
    var modal_name = "modal_all_cashback_payments____";
    
    var modal_title = "All Payments";
    
    var content =
    '<div class="modal small" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\''+modal_name+'\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <input type="hidden" name="customer_id_" id="customer_id_" value="'+id+'" />\n\
                            <table style="width:100%" id="'+table_name+'" class="table table-striped table-bordered" cellspacing="0">\n\
                                <thead>\n\
                                    <tr>\n\
                                        <th>Payments ID</th>\n\
                                        <th>Payments value</th>\n\
                                        <th style="width:130px;">Date</th>\n\
                                        <th style="width:150px;">By employee</th>\n\
                                        <th style="width:40px;">&nbsp;</th>\n\
                                    </tr>\n\
                                </thead>\n\
                                <tfoot>\n\
                                    <tr>\n\
                                        <th>Payments ID</th>\n\
                                        <th>Payments value</th>\n\
                                        <th>Date</th>\n\
                                        <th>By employee</th>\n\
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

    });
    
    $('#'+modal_name).on('shown.bs.modal', function (e) {
        
        $('#'+table_name).show();
        
        var _cards_table__var =null;
        
        var search_fields = [0,1,2,3];
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
                url: "?r=cashback&f=show_all_cashback_for_customers&p0="+id+"&p1=0&p2=0&p3=0",
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
                { "targets": [0], "searchable": true, "orderable": true, "visible":  false },
                { "targets": [1], "searchable": false, "orderable": false, "visible": true },
                { "targets": [2], "searchable": false, "orderable": false, "visible": true },
                { "targets": [3], "searchable": false, "orderable": false, "visible": true },
                { "targets": [4], "searchable": false, "orderable": false, "visible": true },
            ],
            scrollCollapse: true,
            paging: true,
            bPaginate: false,
            bLengthChange: false,
            bFilter: true,
            bInfo: false,
            bSort:false,
            bAutoWidth: true,
            dom: '<"toolbar_cashbackpaid">frtip',
            initComplete: function(settings, json) {
                 $("div.toolbar_cashbackpaid").html('\n\
                    <div class="row">\n\
                        <div class="col-lg-6 col-md-6 col-xs-6" style="padding-left:15px;padding-right:5px;">\n\
                            &nbsp;\n\
                        </div>\n\
                        <div class="col-lg-6 col-md-6 col-sm-6" >\n\
                            <div class="btn-group" role="group" aria-label="" style="width:100%">\n\
                                <div class="btn-group" id="buttons_p" style="float:right"></div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    ');    
                
                var buttons = new $.fn.dataTable.Buttons(_cards_table__var, {
                    buttons: [
                      {
                            extend: 'excel',
                            text: 'Export excel',
                            className: 'exportExcel',
                            filename: 'Cashback paid report',
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

               }).container().appendTo($('#buttons_p'));

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
                
                $(".sk-circle-layer").hide();
            },
            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $(nRow).addClass("t_"+aData[0]);
            },
            fnDrawCallback: setcashpaidOptions,
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
                search_in_datatable_global(this.value,that.index(),100,table_name);
            } );
        } );
       
    });
    $('#'+modal_name).on('hide.bs.modal', function (e) {
        $("#"+modal_name).remove();
    });
    $('#'+modal_name).modal('show');
}

function add_cashback_for_customer(id){
    $(".sk-circle").center();
    $(".sk-circle-layer").show(); 
    var modal_name = "modal_add_cashback____";
    
    var modal_title = "Add cashback";
    
    var content =
    '<div class="modal small" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <form id="addcashback_form" action="" method="post" enctype="multipart/form-data" >\n\
                    <input type="hidden" name="customer_id" id="customer_id" value="'+id+'" />\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\''+modal_name+'\')"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">\n\
                                <div class="form-group">\n\
                                    <label for="cashback_value">Value</label>\n\
                                    <input required style="width:100%" autocomplete="off" id="cashback_value" name="cashback_value" value="" type="text" class="form-control med_input">\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-footer">\n\
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\n\
                        <button id="action_btn" type="submit" class="btn btn-primary">Add</button>\n\
                    </div>\n\
                </form>\n\
            </div>\n\
        </div>\n\
    </div>';
    $("#"+modal_name).remove();
    $("body").append(content);
    
    submitcashback_form("modal_add_cashback____");
    
    $('#'+modal_name).on('show.bs.modal', function (e) {

    });
    
    $('#'+modal_name).on('shown.bs.modal', function (e) {
        
        cleaves_id("cashback_value",0);
        $("#cashback_value").focus().val(0);
         $(".sk-circle-layer").hide();

       
    });
    $('#'+modal_name).on('hide.bs.modal', function (e) {
        $("#"+modal_name).remove();
    });
    $('#'+modal_name).modal('show');
}


function submitcashback_form(modalname){
    $("#addcashback_form").on('submit', (function (e) {
        e.preventDefault();
        
        $("#cashback_value").val($("#cashback_value").val().replace(/[^0-9\.]/g, ''));

        $(".sk-circle").center();
        $(".sk-circle-layer").show(); 
        $.ajax({
            url: "?r=cashback&f=add_new_cashback",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            success: function (data)
            {
                var table = $('#modal_all_cashback_table').DataTable();
                table.ajax.url("?r=cashback&f=show_all_cashback_customers&p0=0&p1=0&p2=0&p3=0").load(function () {
                    $('#'+modalname).modal('hide');
                    $(".t_"+data[0]).addClass("selected");
                    $(".sk-circle-layer").hide();
                }, false);
 
            }
        });
    }));
}