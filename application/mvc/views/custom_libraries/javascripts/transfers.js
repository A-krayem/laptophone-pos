function add_transfer(id,data){
    
    if(data.length && data[0].submit_transfer==1){
        swal("Cannot Edit, Transfer Confirmed");
        return;
    }
    
    var customer_type_options="";
    for(var i=0;i<clients_type.length;i++){
        if(clients_type[i].id==1){
            customer_type_options += "<option selected value=" + clients_type[i].id + ">" + clients_type[i].name + "</option>";
        }else{
            customer_type_options += "<option value=" + clients_type[i].id + ">" + clients_type[i].name + "</option>";
        }
    }
    
    
    var mh_tr = "Add Transfer";
    if(data.length>0){
        mh_tr = "Update Transfer";
    }
    
    stores_options_totally = "";
    for(var i=0;i<all_stores_totally.length;i++){
        sel = "";
        if(i==0) sel = "selected";
        stores_options_totally += "<option "+sel+" value=" + all_stores_totally[i].id + ">" + all_stores_totally[i].name + "</option>";
    };
    
    var content =
    '<div class="modal" data-backdrop="static" id="transfer_modal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <form id="transfer_form" action="" method="post" enctype="multipart/form-data" >\n\
                <input id="id_to_edit" name="id_to_edit" type="hidden" value="'+id+'" />\n\
                <div class="modal-content">\n\
                    <div class="modal-header">\n\
                        <h3 class="modal-title">'+mh_tr+'<i style="float:right;font-size:30px; cursor:pointer" class="glyphicon glyphicon-remove" onclick="shrinkage_close()"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-md-4 col-lg-4 col-xs-4 col-sm-4 pr2">\n\
                                <div class="form-group">\n\
                                    <label for="stores_list_source">From Store</label>\n\
                                    <select id="stores_list_source" name="stores_list_source" class="selectpicker form-control" style="width:100%">' + stores_options_totally + '</select>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-md-4 col-lg-4 col-xs-4 col-sm-4 plr2">\n\
                                <div class="form-group">\n\
                                    <label for="stores_list">To Store</label>\n\
                                    <select id="stores_list" name="stores_list" class="selectpicker form-control" style="width:100%">' + stores_options_totally + '</select>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-md-4 col-lg-4 col-xs-4 col-sm-4 pl2">\n\
                                <div class="form-group">\n\
                                    <label for="pricing_type">Pricing</label>\n\
                                    <select id="pricing_type" name="pricing_type" class="selectpicker form-control" style="width:100%">' + customer_type_options + '</select>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-xs-12">\n\
                                <div class="form-group">\n\
                                    <label for="transfer_description">Description</label>\n\
                                    <input id="transfer_description" value="" name="transfer_description" type="text" class="form-control" placeholder="Description"/>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-footer">\n\
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\n\
                        <a id="btn_tr_sub" onclick="$(this).closest(\'form\').submit()" type="submit" class="btn btn-primary">Add</a>\n\
                    </div>\n\
                </div>\n\
            </form>\n\
        </div>\n\
    </div>';
    $("#transfer_modal").remove();
    $("body").append(content);

    $("#transfer_modal").centerWH();

    $('#transfer_modal').on('show.bs.modal', function (e) {
    });
    
    $('#transfer_modal').on('shown.bs.modal', function (e) {
        $('#stores_list').selectpicker();
        $('#stores_list_source').selectpicker();
        $('#pricing_type').selectpicker();
       
        
        
        if(data.length>0){
            $("#transfer_description").val(data[0].description);
            $("#btn_tr_sub").html('Update');
             $("#stores_list").selectpicker('val', data[0].to_store_id);
             $("#stores_list_source").selectpicker('val', data[0].from_store_id);
        }
    });

    $('#transfer_modal').on('hide.bs.modal', function (e) {
        $('#transfer_modal').remove();
    });
    
    submitTransfer(data);
    
    $('#transfer_modal').modal('show');  
}


function duplicate_transfer(id){
    
    
    var mh_tr = "Duplicate Transfer";
  
    
    stores_options_totally = "";
    for(var i=0;i<all_stores_totally.length;i++){
        sel = "";
        if(i==0) sel = "selected";
        stores_options_totally += "<option "+sel+" value=" + all_stores_totally[i].id + ">" + all_stores_totally[i].name + "</option>";
    };
    
    var content =
    '<div class="modal" data-backdrop="static" id="transfer_modal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <form id="duplicate_form" action="" method="post" enctype="multipart/form-data" >\n\
                <input id="id_to_duplicate" name="id_to_duplicate" type="hidden" value="'+id+'" />\n\
                <div class="modal-content">\n\
                    <div class="modal-header">\n\
                        <h3 class="modal-title">'+mh_tr+'<i style="float:right;font-size:30px; cursor:pointer" class="glyphicon glyphicon-remove" onclick="shrinkage_close()"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-xs-6">\n\
                                <div class="form-group">\n\
                                    <label for="stores_list_source">From Store</label>\n\
                                    <select id="stores_list_source" name="stores_list_source" class="selectpicker form-control" style="width:100%">' + stores_options_totally + '</select>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-xs-6">\n\
                                <div class="form-group">\n\
                                    <label for="stores_list">To Store</label>\n\
                                    <select id="stores_list" name="stores_list" class="selectpicker form-control" style="width:100%">' + stores_options_totally + '</select>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-xs-12">\n\
                                <div class="form-group">\n\
                                    <label for="transfer_description">Description</label>\n\
                                    <input id="transfer_description" value="" name="transfer_description" type="text" class="form-control" placeholder="Description"/>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-footer">\n\
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\n\
                        <a id="btn_tr_sub" onclick="$(this).closest(\'form\').submit()" type="submit" class="btn btn-primary">Add</a>\n\
                    </div>\n\
                </div>\n\
            </form>\n\
        </div>\n\
    </div>';
    $("#transfer_modal").remove();
    $("body").append(content);

    $("#transfer_modal").centerWH();

    $('#transfer_modal').on('show.bs.modal', function (e) {
    });
    
    $('#transfer_modal').on('shown.bs.modal', function (e) {
        $('#stores_list').selectpicker();
        $('#stores_list_source').selectpicker();
 
       
    });

    $('#transfer_modal').on('hide.bs.modal', function (e) {
        $('#transfer_modal').remove();
    });
    
    submit_duplicate_Transfer();
    
    $('#transfer_modal').modal('show');  
}


function submit_duplicate_Transfer(){
    $("#duplicate_form").on('submit', (function (e) {
        e.preventDefault();
        if (!emptyInput("transfer_description")) {
            $(".sk-circle-layer").show();
            $.ajax({
                url: "?r=transfer&f=duplicate_transfer",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function (data_)
                {
                  
                 
                    
                    var table = $('#transfers_table').DataTable();
                    table.ajax.url("?r=transfer&f=getAllTransfers&p0="+$("#date_filter").val()).load(function () {
                        if(data_.length>0){
                            table.row('.' + pad_transfer(data_[0].id), {page: 'current'}).select();
                        }else{
                            table.page('last').draw(false);
                            table.row(':last', {page: 'current'}).select();
                            $(".dataTables_scrollBody").scrollTop($('.dataTables_scrollBody')[0].scrollHeight);

                           
                        } 
                        $(".sk-circle-layer").hide();
                        $('#transfer_modal').modal('hide'); 
                        
                    },false);
                    
                     
                }
            });
        }
    }));
}


    function transfer_action(id){
        $(".sk-circle-layer").show();
        current_transfer_id = id;
        var store_name = "";
        var store_name_source = "";
        var submited = 0;
        var from_store_id = 0;
        $.getJSON("?r=transfer&f=get_transfer_by_id&p0="+id, function (data) {
            store_name = data[0].store_name;
            store_name_source = data[0].store_name_source;
            submited = data[0].submit_transfer;
            from_store_id = data[0].from_store_id;
        }).done(function () {
            var content =
            '<div class="modal" data-backdrop="static" id="transfer_details_modal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
                <div class="modal-dialog" role="document">\n\
                    <div class="modal-content">\n\
                        <div class="modal-header">\n\
                            <div class="row">\n\
                                <div class="col-lg-12 col-md-12 col-xs-12" style="padding-left:15px;padding-right:5px;">\n\
                                    <h3 class="modal-title">Transfers<i style="float:right;font-size:30px; cursor:pointer" class="glyphicon glyphicon-remove" onclick="transfers_details__close()"></i></h3>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="modal-body" style="padding-bottom:0px !important;">\n\
                            <div class="row">\n\
                                <div class="col-lg-6 col-md-6 col-xs-6" style="padding-left:15px;padding-right:5px;">\n\
                                    <b>Transfer From</b> '+store_name_source+'\n\
                                    <table id="transfers_details_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                        <thead>\n\
                                            <tr>\n\
                                                <th style="width: 43px !important;">Item Id</th>\n\
                                                <th style="width: 60px !important;">Barcode</th>\n\
                                                <th>Description</th>\n\
                                                <th style="width: 25px !important;">Qty</th>\n\
                                                <th style="width: 20px !important;"></th>\n\
                                            </tr>\n\
                                        </thead>\n\
                                        <tfoot>\n\
                                            <tr>\n\
                                                <th>Item Id</th>\n\
                                                <th>Barcode</th>\n\
                                                <th>Description</th>\n\
                                                <th>Qty</th>\n\
                                                <th></th>\n\
                                            </tr>\n\
                                        </tfoot>\n\
                                        <tbody></tbody>\n\
                                    </table>\n\
                                </div>\n\
                                <div class="col-lg-6 col-md-6 col-xs-6">\n\
                                    <b>Transfer To</b> <span id="transfer_to_name">'+store_name+'</span> \n\
                                    <table id="destination_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                        <thead>\n\
                                            <tr>\n\
                                                <th>Transaction Id</th>\n\
                                                <th style="width: 43px !important;">Item Id</th>\n\
                                                <th style="width: 60px !important;">Barcode</th>\n\
                                                <th>Description</th>\n\
                                                <th style="width: 90px !important;">Qty</th>\n\
                                                <th style="width: 20px !important;">&nbsp;</th>\n\
                                            </tr>\n\
                                        </thead>\n\
                                        <tfoot>\n\
                                            <tr>\n\
                                                <th>Transaction Id</th>\n\
                                                <th>Item Id</th>\n\
                                                <th>Barcode</th>\n\
                                                <th>Description</th>\n\
                                                <th>Qty</th>\n\
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
            $("#transfer_details_modal").remove();
            $("body").append(content);

            $("#transfer_details_modal").centerWH();

            $('#transfer_details_modal').on('show.bs.modal', function (e) {
            });

            $('#transfer_details_modal').on('shown.bs.modal', function (e) {
                //$(".sk-circle-layer").show();
                prepare_table_transfer_details(submited,from_store_id);
                prepare_table_destination(submited);
            });

            $('#transfer_details_modal').on('hide.bs.modal', function (e) {
                $('#transfer_details_modal').remove();
            });

            $('#transfer_details_modal').modal('show');  
        });
        
    }
    
    function prepare_table_destination(submited){
        var search_fields = [0,1,2,3];
        var index = 0;
        $('#destination_table tfoot th').each( function () {
            if(jQuery.inArray(index, search_fields) !== -1){
                var title = $(this).text();
                $(this).html( '<div class="inner-addon left-addon"><input id="idf_'+index+'" style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder="'+title+'" /></div>' );
                index++;
            }
        });
        
        var destination_table = $('#destination_table').dataTable({
            ajax: "?r=transfer&f=getAllItemsInTransferDetails&p0="+current_transfer_id,
            responsive: true,
            orderCellsTop: true,
            bLengthChange: true,
            iDisplayLength: 100,
            aoColumnDefs: [
                { "targets": [0], "searchable": true, "orderable": true, "visible": false },
                { "targets": [1], "searchable": true, "orderable": true, "visible": true },
                { "targets": [2], "searchable": true, "orderable": true, "visible": true },
                { "targets": [3], "searchable": true, "orderable": false, "visible": true  },
                { "targets": [4], "searchable": true, "orderable": false, "visible": true,"className": "dt-center" },
                { "targets": [5], "searchable": true, "orderable": false, "visible": true,"className": "dt-center" }
            ],
            scrollY: '45vh',
            scrollCollapse: true,
            paging: true,
            order: [[ 0, "asc" ]],
            dom: '<"toolbar_destination_store">frtip',
            initComplete: function( settings ) {
                //$(".sk-circle-layer").hide();
                
                var table = $('#destination_table').DataTable();
                //table.row(':eq(0)', { page: 'current' }).select();
                
                var deleted_all_display = "block";
                if(submited==1){
                    deleted_all_display = "none";
                }
                
                $("div.toolbar_destination_store").html('\n\
                <div class="row">\n\
                    <div class="col-lg-3 col-md-3 col-xs-3" style="display:'+deleted_all_display+';">\n\
                        <div class="btn-group" role="group" aria-label="" style="width:100% !important;">\n\
                            <button onclick="delete_all_items_in_transfer_list()" type="button" class="btn btn-danger btn-xs">Delete All</button>\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-lg-9 col-md-9 col-sm-9" >\n\
                        <div class="btn-group" id="buttons" style="float:right"></div>\n\
                    </div>\n\
                </div>\n\
                <div class="row">\n\
                    <div class="col-lg-4 col-md-4 col-xs-12" style="padding-left:15px;padding-right:5px;">\n\
                         <div class="btn-group" role="group" aria-label="" style="width:100% !important;">\n\
                            &nbsp;\n\
                         </div>\n\
                    </div>\n\
                    <div class="col-lg-4 col-md-4 col-xs-12" style="padding-left:5px;padding-right:5px;">\n\
                         <div class="btn-group" role="group" aria-label="" style="width:100% !important;">\n\
                            &nbsp;\n\
                         </div>\n\
                     </div>\n\
                </div>\n\
                ');
                
                var buttons = new $.fn.dataTable.Buttons(destination_table, {
                    buttons: [
                      {
                            extend: 'excel',
                            title: 'Transfer to '+$("#transfer_to_name").html(),
                            text: 'Export excel',
                            className: 'exportExcel btn-xs',
                            filename: 'Transfer to '+$("#transfer_to_name").html(),
                            customize: _customizeExcelOptions,
                            exportOptions: {
                                modifier: {
                                    page: 'all'
                                },
                                columns: [ 0,1,2,3,4],
                                format: {
                                    body: function ( data, row, column, node ) {
                                        // Strip $ from salary column to make it numeric
                                        return column === 4 ? $("#transfer_deial_"+parseInt(table.cell(row,0).data())).val() : data; //table.cell(row,0).data().split('-')[1]
                                    }
                                }
                            }
                      }
                    ]

               }).container().appendTo($('#buttons'));

              function _customizeExcelOptions(xlsx) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    var clR = $('row', sheet);
                    //var r1 = Addrow(clR.length+2, [{key:'A',value: "Total Lost"},{key:'B',value: $("#total_lost").html()}]);
                    //var r2 = Addrow(clR.length+3, [{key:'A',value: "Total profit"},{key:'B',value: $("#total_profit").html()}]);
                    //var r3 = Addrow(clR.length+4, [{key:'A',value: "Total Expenses"},{key:'B',value: $("#total_expenses").html()}]);
                    //var r4 = Addrow(clR.length+5, [{key:'A',value: "Total Invoices Discounts"},{key:'B',value: $("#tm_discount").html()}]);
                    //var r5 = Addrow(clR.length+6, [{key:'A',value: "Total Credit Notes"},{key:'B',value: $("#total_credit_notes").html()}]);
                    //sheet.childNodes[0].childNodes[1].innerHTML = sheet.childNodes[0].childNodes[1].innerHTML + r1;

                    //$('row c[r^="A'+(clR.length+2)+'"]', sheet).attr('s', '48');
                    //$('row c[r^="A'+(clR.length+3)+'"]', sheet).attr('s', '48');
                    //$('row c[r^="A'+(clR.length+4)+'"]', sheet).attr('s', '48');
                    //$('row c[r^="A'+(clR.length+5)+'"]', sheet).attr('s', '48');
                    //$('row c[r^="A'+(clR.length+6)+'"]', sheet).attr('s', '48');

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
                
                $("#destination_table .only_numeric").numeric({ negative : false});
            },
            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $(nRow).addClass(aData[0]);
            },
            fnDrawCallback: function(){
                var table = $('#destination_table').DataTable();
                var p = table.rows({ page: 'current' }).nodes();
                for (var k = 0; k < p.length; k++){
                    var index = table.row(p[k]).index();
                    if(submited==0){
                        table.cell(index, 5).data('<i onclick="delete_transfer_details_item('+parseInt(table.cell(index, 0).data())+','+index+')" class="glyphicon glyphicon glyphicon-trash delete_icon"></i>');
                    }else{
                        table.cell(index, 5).data('<i class="glyphicon glyphicon glyphicon-trash delete_icon"></i>');
                    }
                }
                //$("#transfers_details_table .only_numeric").numeric({ negative : true});
            },
        });
        
        $('#destination_table').DataTable().columns().every( function () {
            var that = this;
            $('input', this.footer()).on( 'keyup change', function () {
                if ( that.search() !== this.value ) {
                    that.search( this.value ).draw();
                }
            } );
        } );
        
        $('#destination_table tbody').on( 'mouseenter', 'tr', function () {
            $(".selected_highlight_transfer").removeClass("selected_highlight_transfer");
            $(this).addClass("selected_highlight_transfer");
        } );
    }
    
    function update_table_transfer_details(from_store_id){
        var table = $('#transfers_details_table').DataTable();
        $(".sk-circle-layer").show();
        table.ajax.url("?r=items&f=getAllItemsForTransfer&p0="+from_store_id+"&p1="+$("#categories_list").val()+"&p2="+$("#subcategories_list").val()+"&p3=0&p4="+$("#suppliers_list").val()).load(function () {
            $(".sk-circle-layer").hide();
        },false);
    }
    
    function add_pi_to_transfer(){
        var modal_name = "modal_pitrr_modal__";
        var modal_title = "All PI";
        var content =
        '<div class="modal small" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                        <div class="modal-header">\n\
                            <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\''+modal_name+'\')"></i></h3>\n\
                        </div>\n\
                        <div class="modal-body">\n\
                            <div class="row">\n\
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                    <table style="width:100%" id="allpitr_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                        <thead>\n\
                                            <tr>\n\
                                                <th>id</th>\n\
                                                <th>Supplier name</th>\n\
                                                <th style="width:100px;">PI number</th>\n\
                                                <th style="width:50px;"></th>\n\                                        \n\
                                            </tr>\n\
                                        </thead>\n\
                                        <tbody></tbody>\n\
                                        <tfoot>\n\
                                            <tr>\n\
                                                <th>id</th>\n\
                                                <th>Name</th>\n\
                                                <th>Reference</th>\n\
                                                <th></th>\n\
                                            </tr>\n\
                                        </tfoot>\n\
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

            var table_name = "allpitr_table";
            var _cards_table__var =null;

            var search_fields = [0,1,2];
            var index = 0;
            $('#'+table_name+' tfoot th').each( function () {
                if(jQuery.inArray(index, search_fields) !== -1){
                    var title = $(this).text();
                    $(this).html( '<input id="idfsh1_'+index+'" style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder="'+title+'" />' );
                    index++;
                }
            });

            _cards_table__var = $('#'+table_name).DataTable({
                ajax: {
                    url: "?r=stock&f=getStockInvoices_for_transfer",
                    type: 'POST',
                    error:function(xhr,status,error) {
                    },
                },
                //order: [[1, 'asc']],
                responsive: true,
                orderCellsTop: true,
                scrollX: true,
                scrollY: "55vh",
                iDisplayLength: 50,
                aoColumnDefs: [
                    { "targets": [0], "searchable": true, "orderable": true,"visible": false },
                    { "targets": [1], "searchable": true, "orderable": true,"visible": true },
                    { "targets": [2], "searchable": true, "orderable": true,"visible": true },
                    { "targets": [3], "searchable": true, "orderable": true, "visible": true,"className": "dt-center" },
                ],
                bSort:false,
                scrollCollapse: true,
                paging: true,
                bPaginate: false,
                bLengthChange: false,
                bFilter: true,
                bInfo: false,
                bAutoWidth: true,
                dom: '<"toolbar_shortutsall">frtip',
                initComplete: function(settings, json) {  
                    $(".sk-circle-layer").hide();
                },
                fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    $(nRow).addClass(aData[0]);
                },
                 fnDrawCallback: function(){
                    var table = $('#'+table_name).DataTable();
                    var p = table.rows({ page: 'current' }).nodes();
                    for (var k = 0; k < p.length; k++){
                        var index = table.row(p[k]).index();
                        table.cell(index,3).data('<button onclick="transfer_pi(\''+parseInt(table.cell(index, 0).data())+'\')" type="button" class="btn btn-default btn-xs" style="font-size:13px;cursor:pointer;width:100%">Add to transfer</button>');
                    }
                 },
            });

            $('#'+table_name).DataTable().on('mousedown',"tr", function ( e, dt, type, indexes ) { 
                $('#allpitr_table .selected').removeClass("selected");
                $(this).addClass('selected');
            });


            $('#allshortcuts_table').DataTable().columns().every( function () {
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
    
    function transfer_pi(id){
        $(".sk-circle").show();
        $(".sk-circle-layer").show();
        $.getJSON("?r=transfer&f=transfer_pi&p0="+current_transfer_id+"&p1="+id, function (data) {

        }).done(function () {
            var table = $('#destination_table').DataTable();
            table.ajax.url("?r=transfer&f=getAllItemsInTransferDetails&p0="+current_transfer_id).load(function () {
                $(".sk-circle-layer").hide();
            },false); 
        });
    }
    
    function add_shortcuts_to_transfer(){
        var modal_name = "modal_shorcutsall_modal__";
        var modal_title = "All Shortcuts";
        var content =
        '<div class="modal small" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                        <input id="id_to_edit" name="id_to_edit" value="0" type="hidden" />\n\
                        <input id="counter_type" name="counter_type" value="2" type="hidden" />\n\
                        <div class="modal-header">\n\
                            <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\''+modal_name+'\')"></i></h3>\n\
                        </div>\n\
                        <div class="modal-body">\n\
                            <div class="row">\n\
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                    <table style="width:100%" id="allshortcuts_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                        <thead>\n\
                                            <tr>\n\
                                                <th>id</th>\n\
                                                <th>Name</th>\n\
                                                <th>Qty</th>\n\
                                                <th style="width:100px;"></th>\n\
                                                <th style="width:50px;"></th>\n\                                        \n\
                                            </tr>\n\
                                        </thead>\n\
                                        <tbody></tbody>\n\
                                        <tfoot>\n\
                                            <tr>\n\
                                                <th>id</th>\n\
                                                <th>Name</th>\n\
                                                <th>Qty</th>\n\
                                                <th></th>\n\
                                                <th></th>\n\
                                            </tr>\n\
                                        </tfoot>\n\
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

            var table_name = "allshortcuts_table";
            var _cards_table__var =null;

            var search_fields = [0,1];
            var index = 0;
            $('#'+table_name+' tfoot th').each( function () {
                if(jQuery.inArray(index, search_fields) !== -1){
                    var title = $(this).text();
                    $(this).html( '<input id="idfsh1_'+index+'" style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder="'+title+'" />' );
                    index++;
                }
            });

            _cards_table__var = $('#'+table_name).DataTable({
                ajax: {
                    url: "?r=shortcuts&f=get_all_shortcuts",
                    type: 'POST',
                    error:function(xhr,status,error) {
                    },
                },
                //order: [[1, 'asc']],
                responsive: true,
                orderCellsTop: true,
                scrollX: true,
                scrollY: "55vh",
                iDisplayLength: 50,
                aoColumnDefs: [
                    { "targets": [0], "searchable": true, "orderable": true,"visible": false },
                    { "targets": [1], "searchable": true, "orderable": true,"visible": true },
                    { "targets": [2], "searchable": true, "orderable": true,"visible": true },
                    { "targets": [3], "searchable": true, "orderable": false,"visible": true },
                    { "targets": [4], "searchable": true, "orderable": false, "visible": false,"className": "dt-center" },
                ],
                scrollCollapse: true,
                paging: true,
                bPaginate: false,
                bLengthChange: false,
                bFilter: true,
                bInfo: false,
                bAutoWidth: true,
                dom: '<"toolbar_shortutsall">frtip',
                initComplete: function(settings, json) {  
                    /*
                    $("div.toolbar_shortutsall").html('\n\
                    <div class="row">\n\
                        <div class="col-lg-2 col-md-2 col-xs-12" style="padding-left:15px;padding-right:5px;">\n\
                            <div class="btn-group" role="group" aria-label="" style="width:100% !important;">\n\
                                <button onclick="_add_shortcut_for_selected_items(0)" type="button" class="btn btn-primary btn-sm">Add new shortcut</button>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    ');*/

                    $(".sk-circle-layer").hide();
                },
                fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    $(nRow).addClass(aData[0]);
                },
                 fnDrawCallback: function(){
                    var table = $('#'+table_name).DataTable();
                    var p = table.rows({ page: 'current' }).nodes();
                    for (var k = 0; k < p.length; k++){
                        var index = table.row(p[k]).index();
                        table.cell(index,3).data('<button onclick="add_shortcut_to_transfer(\''+parseInt(table.cell(index, 0).data())+'\')" type="button" class="btn btn-default btn-xs" style="font-size:13px;cursor:pointer;width:100%">Add to transfer</button>');
                        //table.cell(index,3).data('<i class="glyphicon glyphicon-trash red" onclick="delete_shortcut(\''+parseInt(table.cell(index, 0).data())+'\')" style="font-size:18px;cursor:pointer" ></i>');
                    }
                 },
            });

            $('#'+table_name).DataTable().on('mousedown',"tr", function ( e, dt, type, indexes ) { 
                $('#allshortcuts_table .selected').removeClass("selected");
                $(this).addClass('selected');
            });


            $('#allshortcuts_table').DataTable().columns().every( function () {
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
        //current_transfer_id
    }
    
    function add_shortcut_to_transfer(shortcut_id){
        $(".sk-circle").show();
        $(".sk-circle-layer").show();
        $.getJSON("?r=transfer&f=add_shortcut_to_transfer&p0="+current_transfer_id+"&p1="+shortcut_id, function (data) {

        }).done(function () {
            var table = $('#destination_table').DataTable();
            table.ajax.url("?r=transfer&f=getAllItemsInTransferDetails&p0="+current_transfer_id).load(function () {
                $(".sk-circle-layer").hide();
            },false); 
        });
    }

    function prepare_table_transfer_details(submited,from_store_id){
        var search_fields = [0,1,2,3];
        var index = 0;
        $('#transfers_details_table tfoot th').each( function () {
            if(jQuery.inArray(index, search_fields) !== -1){
                var title = $(this).text();
                $(this).html( '<div class="inner-addon left-addon"><input id="idf_'+index+'" style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder="'+title+'" /></div>' );
                index++;
            }
        });
                
        var transfer_dt_table = $('#transfers_details_table').dataTable({
            ajax: "?r=items&f=getAllItemsForTransfer&p0="+from_store_id+"&p1=0&p2=0&p3=0&p4=0",
            responsive: true,
            orderCellsTop: true,
            bLengthChange: true,
            iDisplayLength: 100,
            aoColumnDefs: [
                { "targets": [0], "searchable": true, "orderable": true, "visible": true },
                { "targets": [1], "searchable": true, "orderable": true, "visible": true },
                { "targets": [2], "searchable": true, "orderable": true, "visible": true },
                { "targets": [3], "searchable": true, "orderable": true, "visible": true },
                { "targets": [4], "searchable": true, "orderable": false, "visible": true },
            ],
            scrollY: '45vh',
            scrollCollapse: true,
            paging: true,
            order: [[ 0, "asc" ]],
            dom: '<"toolbar_transfer">frtip',
            initComplete: function( settings ) {
                //var table = $('#transfer_dt_table').DataTable();
                //table.row(':eq(0)', { page: 'current' }).select();
                
                var suppliers_options = "";
                suppliers_options+='<option value=0 title="All Suppliers">All Sppliers</option>';
                for(var i=0;i<all_suppliers.length;i++){
                    suppliers_options+='<option value='+all_suppliers[i].id+' title="'+all_suppliers[i].name+'">'+all_suppliers[i].name+'</option>';
                }
                
                var categories_parents_options = "";
                categories_parents_options+='<option value=0 title="All Categories">All Categories</option>';
                for(var i=0;i<categories_parents.length;i++){
                    categories_parents_options+='<option value='+categories_parents[i].id+' title="'+categories_parents[i].name+'">'+categories_parents[i].name+'</option>';
                }
                
                var categories_options = "";
                categories_options+='<option value=0 title="All Sub-Categories">All Sub-Categories</option>';
                for(var i=0;i<categories.length;i++){
                    categories_options+='<option value='+categories[i].id+' title="'+categories[i].name+'">'+categories[i].name+'</option>';
                }
                
                $("div.toolbar_transfer").html('\n\
                <div class="row">\n\
                    <div class="col-lg-4 col-md-4 col-xs-12" style="padding-left:15px;padding-right:5px;">\n\
                        <div class="btn-group" role="group" aria-label="" style="width:100% !important;">\n\
                            <select data-live-search="true" data-width="100%" id="suppliers_list" class="selectpicker" onchange="suppliers_list_changed('+from_store_id+')">\n\
                                '+suppliers_options+'\n\
                            </select>\n\
                        </div>\n\
                    </div>\n\
                     <div class="col-lg-4 col-md-4 col-xs-12" style="padding-left:5px;padding-right:5px;">\n\
                         <div class="btn-group" role="group" aria-label="" style="width:100% !important;">\n\
                             <select data-live-search="true" data-width="100%" id="categories_list" class="selectpicker" onchange="categories_list_changed('+from_store_id+')">\n\
                                 '+categories_parents_options+'\n\
                             </select>\n\
                         </div>\n\
                     </div>\n\
                     <div class="col-lg-4 col-md-4 col-xs-12" style="padding-left:5px;padding-right:15px;">\n\
                         <div class="btn-group" role="group" aria-label="" style="width:100% !important;">\n\
                             <select data-live-search="true" data-width="100%" id="subcategories_list" class="selectpicker" onchange="subcategories_list_changed('+from_store_id+')">\n\
                                 '+categories_options+'\n\
                             </select>\n\
                         </div>\n\
                     </div>\n\
                </div>\n\
                <div class="row">\n\
                    <div class="col-lg-4 col-md-4 col-xs-12" style="padding-left:15px;padding-right:5px;">\n\
                         <div class="btn-group" role="group" aria-label="" style="width:100% !important;">\n\
                            <button style="width:100% !important;" onclick="add_all_to_transfer()" type="button" class="btn btn-info">Add All items in table To Transfer</button>\n\
                         </div>\n\
                    </div>\n\
                    <div class="col-lg-4 col-md-4 col-xs-12" style="padding-left:5px;padding-right:5px;">\n\
                         <div class="btn-group" role="group" aria-label="" style="width:100% !important;">\n\
                            <button style="width:100% !important;" onclick="add_shortcuts_to_transfer()" type="button" class="btn btn-info">Add shortcuts to transfer</button>\n\
                         </div>\n\
                     </div>\n\
                    <div class="col-lg-4 col-md-4 col-xs-12" style="padding-left:5px;padding-right:15px;">\n\
                         <div class="btn-group" role="group" aria-label="" style="width:100% !important;">\n\
                            <button style="width:100% !important;" onclick="add_pi_to_transfer()" type="button" class="btn btn-info">Add PI to transfer</button>\n\
                         </div>\n\
                     </div>\n\
                </div>\n\
                <div class="row">\n\
                    <div class="col-lg-4 col-md-4 col-xs-12" style="padding-left:15px;padding-right:5px;">\n\
                         <input oninput="add_to_transfet_by_barcode()" value="" id="trbarc" type="text" class="form-control " placeholder="Barcode" style="width:100%" />\n\
                    </div>\n\
                    <div class="col-lg-4 col-md-4 col-xs-12" style="padding-left:5px;padding-right:5px;">\n\
                         &nbsp;\n\
                     </div>\n\
                    <div class="col-lg-4 col-md-4 col-xs-12" style="padding-left:5px;padding-right:15px;">\n\
                         &nbsp;\n\
                     </div>\n\
                </div>\n\
                ');

                $('.selectpicker').selectpicker();

                $("div.toolbar1").html('\n\
                <div class="row" style="margin-top:5px">\n\
                </div>\n\
                ');
                

                $(".sk-circle-layer").hide();
            },
            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $(nRow).addClass(aData[0]);
            },
            fnDrawCallback: function(){
                var table = $('#transfers_details_table').DataTable();
                var p = table.rows({ page: 'current' }).nodes();
                for (var k = 0; k < p.length; k++){
                    var index = table.row(p[k]).index();
                    if(submited==0){
                        //table.cell(index, 4).data('<button onclick="add_item_to_transfer('+parseInt(table.cell(index, 0).data().split("-")[1])+','+index+')" type="button" class="btn btn-xs btn-xxs btn-info" style="width:100%">Add To Transfer</button>');
                        table.cell(index, 4).data('<i class="glyphicon glyphicon-arrow-right trns_logo" onclick="add_item_to_transfer('+parseInt(table.cell(index, 0).data().split("-")[1])+','+index+')"></i>');
                    }else{
                        table.cell(index, 4).data('<i class="glyphicon glyphicon-arrow-right trns_logo_dis"></i>');

                        //table.cell(index, 4).data('<button type="button" class="btn btn-xs btn-xxs btn-info disabled" style="width:100%">Add To Transfer</button>');
                    }
                    //table.cell(index, 8).data('<button onclick="" type="button" class="btn btn-xs btn-xxs btn-danger " style="width:100%">Edit Qty</button>');

                   //if(  $("#id_"+parseInt(table.cell(index, 0).data().split("-")[1])).length==0  ){
                        //table.cell(index,5).data('<input value="'+table.cell(index,5).data()+'" class="form-control input-sm only_numeric" style="width:48%;" id="id_'+parseInt(table.cell(index, 0).data().split("-")[1])+'" type="text">&nbsp;<button onclick="change_qty_sh('+parseInt(table.cell(index, 0).data().split("-")[1])+','+index+')" type="button" class="btn btn-xs btn-xxs btn-info" style="width:48%">Set</button>');
                   //}
                }
                $("#transfers_details_table .only_numeric").numeric({ negative : true});
            },
        });
        
        $('#transfers_details_table tbody').on( 'mouseenter', 'tr', function () {
            $(".selected_highlight_transfer").removeClass("selected_highlight_transfer");
            $(this).addClass("selected_highlight_transfer");
        } );
        
        
        $('#transfers_details_table').DataTable().columns().every( function () {
            var that = this;
            $('input', this.footer()).on( 'keyup change', function () {
                if ( that.search() !== this.value ) {
                    that.search( this.value ).draw();
                }
            } );
        } );
        
        var table = $('#transfers_details_table').DataTable();
        $('#transfers_details_table tbody').on( 'dblclick', 'td', function () {
           // var rowIdx = table.cell( this ).index().row;
            //var colIdx = table.cell( this ).index().column;
            //table.cell(this).data('<input value="'+table.cell(this).data()+'" class="form-control input-sm" id="\'id_'+parseInt(table.cell(index, 0).data().split("-")[1])+'\'" type="text">');
        } );
        
       
    }


function delete_all_items_in_transfer_list(){
    swal({
        title: "Are you sure?",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, Delete All!",
        closeOnConfirm: true,
        cancelButtonText: "Cancel",
    },
    function(isConfirm) {
        if (isConfirm) {
            $(".sk-circle-layer").show();
            $.getJSON("?r=transfer&f=delete_all_items_in_transfer_list&p0="+current_transfer_id, function (data) {
               
            }).done(function () {
                var table = $('#destination_table').DataTable();
                table.ajax.url("?r=transfer&f=getAllItemsInTransferDetails&p0="+current_transfer_id).load(function () {
                    $(".sk-circle-layer").hide();
                },false); 
            });
        }
    });
}


function add_all_to_transfer(){
    swal({
        title: "Are you sure?",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, Add All!",
        closeOnConfirm: true,
        cancelButtonText: "Cancel",
    },
    function(isConfirm) {
        if (isConfirm) {
            $(".sk-circle-layer").show();
            $.getJSON("?r=transfer&f=add_all_to_transfer&p0="+$("#suppliers_list").val()+"&p1="+$("#categories_list").val()+"&p2="+$("#subcategories_list").val()+"&p3="+current_transfer_id, function (data) {
               
            }).done(function () {
                var table = $('#destination_table').DataTable();
                table.ajax.url("?r=transfer&f=getAllItemsInTransferDetails&p0="+current_transfer_id).load(function () {
                    $(".sk-circle-layer").hide();
                },false); 
            });
        }
    });
}

function group_changed(id){
    update_transfers_details_table(id);
}

function update_total_lost(id){
    $.getJSON("?r=shrinkage&f=get_shrinkage_info&p0="+id, function (data) {
        $("#total_lost").html(data.total_lost);
    }).done(function () {

    });
}
function categories_list_changed(from_store_id){
    //current_category_id = $("#categories_list").val();
     
     update_subcategories_list();
     update_table_transfer_details(from_store_id);
}

function update_subcategories_list(){
    $('#subcategories_list').empty();
    $('#subcategories_list').append('<option value=0 title="All Sub-Categories">All Sub-Categories</option>');
    current_subcategory_id = 0;
    for(var i=0;i<categories.length;i++){
        
        if(categories[i].parent==$("#categories_list").val() || $("#categories_list").val()==0){
           $('#subcategories_list').append('<option value='+categories[i].id+' title="'+categories[i].name+'">'+categories[i].name+'</option>');
        }
    }
    $('#subcategories_list').selectpicker('refresh');
}


function subcategories_list_changed(from_store_id){
    current_subcategory_id = $("#subcategories_list").val();
    update_table_transfer_details(from_store_id);
}

function suppliers_list_changed(from_store_id){
    current_supplier_id = $("#suppliers_list").val();
    update_table_transfer_details(from_store_id);
    //update_transfers_details_table(id);
}

function update_transfers_details_table(id){
    var table = $('#transfers_details_table').DataTable();
    $(".sk-circle-layer").show();
    table.ajax.url("?r=shrinkage&f=getAllShrinkagesDetails&p0="+id+"&p1="+$("#group_list").val()+"&p2="+$("#suppliers_list").val()+"&p3="+$("#subcategories_list").val()).load(function () {
        $(".sk-circle-layer").hide();
    },false);
}

function add_item_to_transfer(id){
    if(current_transfer_id!=0){
        $(".sk-circle-layer").show();
        $.getJSON("?r=transfer&f=add_to_transfer_list&p0="+id+"&p1="+current_transfer_id, function (data) {

        }).done(function () {
            $("#trbarc").val("");
            var table = $('#destination_table').DataTable();
            table.ajax.url("?r=transfer&f=getAllItemsInTransferDetails&p0="+current_transfer_id).load(function () {
                
                
                $("#destination_table").parent().scrollTop($("#destination_table").parent()[0].scrollHeight);
                $(".sk-circle-layer").hide();
                            
            },false); 
        }).fail(function() {
            
            $(".sk-circle-layer").hide();
        }).always(function() {
            
        });
    }
}

var bc_s=null;
function add_to_transfet_by_barcode(){
    if(bc_s!=null){
        clearTimeout(bc_s);
    }
    
    bc_s=setTimeout(function(){
        
        var _data=[];
        $.getJSON("?r=items&f=get_item_id_by_barcode&p0="+$("#trbarc").val(), function (data) {
            _data=data;
        }).done(function () {
            if(_data==0){
                
            }else{
                add_item_to_transfer(_data);
            }
        }).fail(function() {

        }).always(function() {
            
        });
        
    },500);
}
            

function change_qty_sh(id,index){
   $.getJSON("?r=shrinkage&f=change_qty_sh&p0="+id+"&p1="+$("#id_"+id).val(), function (data) {
       var table = $('#transfers_details_table').DataTable();
       table.cell(index, 8).data(data[0].checked_date);
       table.cell(index, 9).data(decimal_round(data[0].avg_cost,5)+" "+default_currency_symbol);
       table.cell(index, 10).data(data[0].total_cost);
       
    }).done(function () {

    });
}

function submitTransfer(data_){
    $("#transfer_form").on('submit', (function (e) {
        e.preventDefault();
        if (!emptyInput("transfer_description")) {
            $(".sk-circle-layer").show();
            $.ajax({
                url: "?r=transfer&f=add_new_transfer",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function (data)
                {
                    if(data.id>0){
                        setTimeout(function(){
                            transfer_action(data.id);
                        },500);
                    }
                    
                    var table = $('#transfers_table').DataTable();
                    table.ajax.url("?r=transfer&f=getAllTransfers&p0="+$("#date_filter").val()).load(function () {
                        if(data_.length>0){
                            table.row('.' + pad_transfer(data_[0].id), {page: 'current'}).select();
                        }else{
                            table.page('last').draw(false);
                            table.row(':last', {page: 'current'}).select();
                            $(".dataTables_scrollBody").scrollTop($('.dataTables_scrollBody')[0].scrollHeight);

                            var sdata = table.row('.selected', 0).data();
                           
                        } 
                        $(".sk-circle-layer").hide();
                        $('#transfer_modal').modal('hide'); 
                        
                    },false);     
                }
            });
        }
    }));
}

function shrinkage_close(){
    $('#transfer_modal').modal('toggle');
}

function transfers_details__close(){
    $('#transfer_details_modal').modal('toggle');
    refresh_transfers_table();
}

function qty_tr_minus(id){
    if(parseFloat($("#transfer_deial_"+id).val())>0){
        $.getJSON("?r=transfer&f=qty_tr_minus&p0="+id, function (data) {

        }).done(function () {
            $("#transfer_deial_"+id).val( parseFloat($("#transfer_deial_"+id).val())-1 );
        });
    }
}

function qty_tr_plus(id){
    $.getJSON("?r=transfer&f=qty_tr_plus&p0="+id, function (data) {
       
    }).done(function () {
        $("#transfer_deial_"+id).val( parseFloat($("#transfer_deial_"+id).val())+1 );
    });
}

function update_tr_qty(id){
    $.getJSON("?r=transfer&f=update_tr_qty&p0="+id+"&p1="+$("#transfer_deial_"+id).val(), function (data) {
       
    }).done(function () {

    });
}

function delete_transfer_details_item(id){
    $(".sk-circle-layer").show();
    $.getJSON("?r=transfer&f=delete_transfer_details_item&p0="+id, function (data) {
       
    }).done(function () {
        var table = $('#destination_table').DataTable();
        table.ajax.url("?r=transfer&f=getAllItemsInTransferDetails&p0="+current_transfer_id).load(function () {
            $("#destination_table").parent().scrollTop($("#destination_table").parent()[0].scrollHeight);
            $(".sk-circle-layer").hide();
        },false); 
    });
}

function submit_transfer(id){
    swal({
        title: "Confirm Transfer",
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
            $.getJSON("?r=transfer&f=submit_transfer&p0="+id, function (data) {
                
            }).done(function () {
                transfers_details__close();
            });
        }
        $(".sweet-alert").remove();
        $(".sweet-overlay").remove();
    });
}


function print_barcodes_transfer(transfer_id){
    if (typeof print_barcode_in_browser !== 'undefined' && print_barcode_in_browser=="1") {
        window.open("index.php?r=items&f=print_barcode_of_transfer_id_manual&p0="+transfer_id, '_blank');
        return;
    }
    
    swal({
        title: "Print Barcodes",
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
            
            $.getJSON("?r=items&f=print_barcode_of_transfer_id&p0=" + transfer_id, function (data) {

            }).done(function () {

            });      
            
        }
        $(".sweet-alert").remove();
        $(".sweet-overlay").remove();
    });
}

