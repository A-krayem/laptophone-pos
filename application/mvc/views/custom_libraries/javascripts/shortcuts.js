function add_item_to_shortcut(item_id,shortcut_id){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    $.getJSON("?r=shortcuts&f=add_item_to_shortcut&p0="+item_id+"&p1="+shortcut_id+"&p2="+$("#shit_"+item_id).val(), function (data) {

    }).done(function () {
        $(".sk-circle-layer").hide();
        
        var info = $('#shortcuts_add_items_table').DataTable().page.info();
        if(info.recordsDisplay>1){
            $('#shortcuts_add_items_table').DataTable().row("."+item_id).remove().draw();
        }else{
            $('#modal_add_items_to_shortcut_modal__').modal('hide');
        }
        
        $('#shortcuts_details_table').DataTable().ajax.url("?r=shortcuts&f=get_all_items_in_shortcut&p0="+shortcut_id).load(function () {
           
        }, false);
        
    }); 
}

function import_shortcuts_to_stock(item_id){
    show_all_shortcuts(item_id,$("#item_id").val());
}

function set_group_as_shortcut(item_id){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var _data = [];
    $.getJSON("?r=shortcuts&f=set_group_as_shortcut&p0="+item_id, function (data) {
        _data=data;
    }).done(function () {
        show_shortcut_details(_data[0]);
        $(".sk-circle-layer").hide();
    }); 
}

function add_items_to_shortcut(shortcut_id){
    var modal_name = "modal_add_items_to_shortcut_modal__";
    var modal_title = "Add items to shortcut";
    var content =
    '<div class="modal large" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header">\n\
                    <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\''+modal_name+'\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <input type="hidden" value="'+shortcut_id+'" id="shortcut_id_to_add_item" />\n\
                            <table style="width:100%" id="shortcuts_add_items_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                <thead>\n\
                                    <tr>\n\
                                        <th style="width:50px;">Items id</th>\n\
                                        <th style="width:80px;">Barcode</th>\n\
                                        <th>Description</th>\n\
                                        <th style="width:30px;">Qty</th>\n\
                                        <th style="width:30px;"></th>\n\
                                    </tr>\n\
                                </thead>\n\
                                <tbody></tbody>\n\
                                <tfoot>\n\
                                    <tr>\n\
                                        <th>Items id</th>\n\
                                        <th>Barcode</th>\n\
                                        <th>Description</th>\n\
                                        <th>&nbsp;</th>\n\
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
        
        var table_name = "shortcuts_add_items_table";
        var _cards_table__var =null;

        var search_fields = [0,1,2];
        var index = 0;
        $('#'+table_name+' tfoot th').each( function () {
            if(jQuery.inArray(index, search_fields) !== -1){
                var title = $(this).text();
                $(this).html( '<input id="idfsh4_'+index+'" style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder="'+title+'" />' );
                index++;
            }
        });

        _cards_table__var = $('#'+table_name).DataTable({
            ajax: {
                url: "?r=shortcuts&f=get_all_items_to_add_to_shortcut&p0="+shortcut_id,
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
                { "targets": [2], "searchable": true, "orderable": true, "visible": true },
                { "targets": [3], "searchable": true, "orderable": true, "visible": true },
                { "targets": [4], "searchable": true, "orderable": false, "visible": true,"className": "dt-center" },
            ],
            scrollCollapse: true,
            paging: true,
            bPaginate: false,
            bLengthChange: false,
            bFilter: true,
            bInfo: false,
            bAutoWidth: true,
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
                    table.cell(index,4).data('<i class="glyphicon glyphicon-plus" onclick="add_item_to_shortcut('+table.cell(index,0).data()+','+shortcut_id+')" style="font-size:18px;cursor:pointer" ></i>');
                }
             },
        });

        $('#'+table_name).DataTable().on('mousedown',"tr", function ( e, dt, type, indexes ) { 
            $('#shortcuts_add_items_table .selected').removeClass("selected");
            $(this).addClass('selected');
        });


        $('#'+table_name).DataTable().columns().every( function () {
            var that = this;
            $( 'input', this.footer() ).on( 'keyup change', function () {
                search_in_datatable_shortcut(this.value,that.index(),100,table_name);
            } );
        } );
 
    });
    $('#'+modal_name).on('hide.bs.modal', function (e) {
        $("#"+modal_name).remove();
    });
    $('#'+modal_name).modal('show');
}

function delete_item_from_shortcut(shortcut_id,id){
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
            $(".sk-circle").center();
            $(".sk-circle-layer").show();
            $.getJSON("?r=shortcuts&f=delete_item_from_shortcut&p0="+id, function (data) {
        
            }).done(function () {
                $('#shortcuts_details_table').DataTable().ajax.url("?r=shortcuts&f=get_all_items_in_shortcut&p0="+shortcut_id).load(function () {
                    $(".sk-circle-layer").hide();
                }, false);
            }); 
        }
    });
}

function delete_shortcut(shortcut_id){
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
            $(".sk-circle").center();
            $(".sk-circle-layer").show();
            $.getJSON("?r=shortcuts&f=delete_shortcut&p0="+shortcut_id, function (data) {
        
            }).done(function () {
                $('#allshortcuts_table').DataTable().ajax.url("?r=shortcuts&f=get_all_shortcuts").load(function () {
                    $(".sk-circle-layer").hide();
                }, false);
            }); 
        }
    });
}

function _add_shortcut_for_selected_items(to_add_to_shortut){
    var modal_name = "modal_shorcut_name_modal__";
    var modal_title = "Shortcut name";
    var content =
    '<div class="modal small" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <form id="shortcut_name_form" action="" method="post" enctype="multipart/form-data" >\n\
                    <input id="id_to_edit" name="id_to_edit" value="0" type="hidden" />\n\
                    <input id="to_add_to_shortut" name="to_add_to_shortut" value="'+to_add_to_shortut+'" type="hidden" />\n\
                    <div class="modal-header">\n\
                        <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\''+modal_name+'\')"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                <div class="form-group">\n\
                                    <input required id="shortcut_name" name="shortcut_name" value="" type="text" class="form-control" placeholder="Shortcut name">\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-footer" style="padding-top:0px;">\n\
                        <button id="action_btn" type="submit" class="btn btn-default btn-sm" style="width:100px;font-size:14px;">Add</button>\n\
                    </div>\n\
                </form>\n\
            </div>\n\
        </div>\n\
    </div>';
    $("#"+modal_name).remove();
    $("body").append(content);

    submitShortcutName(modal_name,0);

    $('#'+modal_name).on('show.bs.modal', function (e) {

    });

    $('#'+modal_name).on('shown.bs.modal', function (e) {

    });

    $('#'+modal_name).on('hide.bs.modal', function (e) {
        $("#"+modal_name).remove();
    });

    $('#'+modal_name).modal('show');
}

function set_all_price(table_name){
    var table = $('#'+table_name).DataTable();
    var p = table.rows('.selected').nodes();

    var to_add_to_amount = "";
    for (var k = 0; k < p.length; k++){
        var index = table.row(p[k]).index();
        if(k<p.length-1){
            to_add_to_amount += parseInt(table.cell(index, 0).data().split('-')[1])+",";
        }else{
            to_add_to_amount += parseInt(table.cell(index, 0).data().split('-')[1]);
        }
    
    }
    
    swal({
        title: "Set items price",
        html:true,
        text: "<input autocomplete='off' class='addvalue' type='text' id='addprice' value='' style='width;100%;color:#000' />",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Add",
        cancelButtonText: "Cancel",
        closeOnConfirm: true
      },
    function(isconfirm){
        if(isconfirm){
            set_price_to_all(to_add_to_amount,$("#addprice").val());
        }
    });
    
    setTimeout(function(){
        cleaves_class(".addvalue",2);
        $("#addprice").focus();
    },100);
}

function add_all_qty(table_name){
    var table = $('#'+table_name).DataTable();
    var p = table.rows('.selected').nodes();

    var to_add_to_amount = "";
    for (var k = 0; k < p.length; k++){
        var index = table.row(p[k]).index();
        if(k<p.length-1){
            to_add_to_amount += parseInt(table.cell(index, 0).data().split('-')[1])+",";
        }else{
            to_add_to_amount += parseInt(table.cell(index, 0).data().split('-')[1]);
        }
    
    }
    
    swal({
        title: "Add qty",
        html:true,
        text: "<input autocomplete='off' class='addvalue' type='text' id='addqty' value='' style='width;100%;color:#000' />",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Add",
        cancelButtonText: "Cancel",
        closeOnConfirm: true
      },
    function(isconfirm){
        if(isconfirm){
            add_all_qty___(to_add_to_amount,$("#addqty").val());
        }
    });
    
    setTimeout(function(){
        cleaves_class(".addvalue",2);
        $("#addqty").focus();
    },100);
}

function add_all_qty___(items,qty){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    $.ajax({
        type: 'POST',
        url: '?r=items&f=add_qty_to_all',
        dataType: 'json',
        data: {items: items,amount:qty.replace(/,\s?/g, "")},
        success: function(msg) {
          
            var table = $('#items_table').DataTable();
            table.ajax.url('?r=items&f=getAllItems&p0='+current_store_id+"&p1="+$("#suppliers_list").val()+"&p2="+$("#categories_list").val()+"&p3="+$("#subcategories_list").val()+"&p4="+$("#items_boxes_list").val()+"&p5="+$("#stock_status").val()).load(function () {
                 $(".sk-circle-layer").hide();
                 $(".selected").removeClass("selected");
            },false);
                            
            
        },
    }).fail(function() {
         
    })
    .always(function() {
       
    });
}


function set_price_to_all(items,amount){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    $.ajax({
        type: 'POST',
        url: '?r=items&f=set_price_to_all',
        dataType: 'json',
        data: {items: items,amount:amount.replace(/,\s?/g, "")},
        success: function(msg) {
            var table = $('#items_table').DataTable();
     
            table.ajax.url('?r=items&f=getAllItems&p0='+current_store_id+"&p1="+$("#categories_list").val()+"&p2="+$("#subcategories_list").val()+"&p3="+$("#items_boxes_list").val()+"&p4="+$("#suppliers_list").val()+"&p5="+$("#stock_status").val()).load(function () {
                 $(".sk-circle-layer").hide();
                 $(".selected").removeClass("selected");
            });
            
        },
    }).fail(function() {
         
    })
    .always(function() {
       
    });
}

function add_to_price(table_name){
    var table = $('#'+table_name).DataTable();
    var p = table.rows('.selected').nodes();

    var to_add_to_amount = "";
    for (var k = 0; k < p.length; k++){
        var index = table.row(p[k]).index();
        if(k<p.length-1){
            to_add_to_amount += parseInt(table.cell(index, 0).data().split('-')[1])+",";
        }else{
            to_add_to_amount += parseInt(table.cell(index, 0).data().split('-')[1]);
        }
    
    }
    
    swal({
        title: "Add % to original items prices",
        html:true,
        text: "<input autocomplete='off' class='addvalue' type='text' id='addvtoamount' value='' style='width;100%;color:#000' /><br/><br/>Add % to cost also<input style='width:20px;height:20px;margin-left:20px;' type='checkbox' id='checkbo_forcost'/>",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Add",
        cancelButtonText: "Cancel",
        closeOnConfirm: true
      },
    function(isconfirm){
        if(isconfirm){
            update_value_to_price(to_add_to_amount,$("#addvtoamount").val());
        }
    });
    
    setTimeout(function(){
        cleaves_class(".addvalue",2);
        $("#addvtoamount").focus();
    },100);
}

function update_value_to_price(items,amount){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    
    var ch=0;
    if($("#checkbo_forcost").prop("checked")==true){
        ch=1;
    }
    
    $.ajax({
        type: 'POST',
        url: '?r=items&f=add_value_to_price',
        dataType: 'json',
        data: {items: items,amount:amount.replace(/,\s?/g, ""),cost_also: ch},
        success: function(msg) {
          
            var table = $('#items_table').DataTable();
            table.ajax.url('?r=items&f=getAllItems&p0='+current_store_id+"&p1="+$("#suppliers_list").val()+"&p2="+$("#categories_list").val()+"&p3="+$("#subcategories_list").val()+"&p4="+$("#items_boxes_list").val()+"&p5="+$("#stock_status").val()).load(function () {
                 $(".sk-circle-layer").hide();
                 $(".selected").removeClass("selected");
            },false);
                            
            
        },
    }).fail(function() {
         
    })
    .always(function() {
       
    });
}

function add_shortcut_for_selected_items(table_name){
    if($("#btn_shortcuts").hasClass("disabled")==false){
        var table = $('#'+table_name).DataTable();
        var p = table.rows('.selected').nodes();
        if(p.length<=1){
            show_all_shortcuts(0,0);
            return;
        }
        var to_add_to_shortut = "";
        for (var k = 0; k < p.length; k++){
            var index = table.row(p[k]).index();
            if(k<p.length-1){
                to_add_to_shortut += parseInt(table.cell(index, 0).data().split('-')[1])+",";
            }else{
                to_add_to_shortut += parseInt(table.cell(index, 0).data().split('-')[1]);
            }
        }
        
        _add_shortcut_for_selected_items(to_add_to_shortut);
    }
}


function submitShortcutName(modal_name,open_list) {
    $("#shortcut_name_form").on('submit', (function (e) {
        e.preventDefault();
        $(".sk-circle").center();
        $(".sk-circle-layer").show();
        $.ajax({
            url: "?r=shortcuts&f=add_new_shortcut",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            success: function (data)
            {
                $(".sk-circle-layer").hide();
                $('#'+modal_name).modal('hide');
                if(open_list==1){
                    show_shortcut_details(data.id)
                }
                
                if($("#allshortcuts_table").length>0){
                    $('#allshortcuts_table').DataTable().ajax.url("?r=shortcuts&f=get_all_shortcuts").load(function () {
                        $('#allshortcuts_table').DataTable().page('last').draw('page');
                        $('#allshortcuts_table tr:last').addClass('selected');
                        $(".sk-circle-layer").hide();
                    }, false);
                }
            }
        });
    }));
}

function stock_value_add(shortcut_id){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    $.getJSON("?r=shortcuts&f=import_shortcut_to_stock&p0="+shortcut_id+"&p1="+$("#stock_value").val()+"&p2=0", function (data) {

    }).done(function () {
        $('#modal_add_to_stock_to_shortcut_modal__').modal('hide');
        $(".sk-circle-layer").hide();
        
        if($('#group_items_table').length>0){
            var table = $('#group_items_table').DataTable();
            table.ajax.url("?r=items&f=get_group&p0="+$("#item_id").val()).load(function () {
                
            },false);
        }
    }); 
}

function shortcut_import_to_stock(shortcut_id){
    var modal_name = "modal_add_to_stock_to_shortcut_modal__";
    var modal_title = "Import to stock";
    var content =
    '<div class="modal small" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header">\n\
                    <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\''+modal_name+'\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="row">\n\
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">\n\
                            <div class="form-group">\n\
                                <label for="stock_value">&nbsp;</label>\n\
                                <input required id="stock_value" class="form-control sh_stock" type="text" style="width:100%;" />\n\
                            </div>\n\
                        </div>\n\
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">\n\
                            <div class="form-group">\n\
                                <label for="stock_value_add">&nbsp;</label>\n\
                                <button onclick="stock_value_add('+shortcut_id+')" type="button" class="btn btn-primary" style="width:100%;">Add</button>\n\
                            </div>\n\
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
        $("#stock_value").focus();
        cleaves_class(".sh_stock",2);
    });
    $('#'+modal_name).on('hide.bs.modal', function (e) {
        $("#"+modal_name).remove();
    });
    $('#'+modal_name).modal('show');
}

function show_all_shortcuts(for_item_id,item_id){
    var disp = "";
    if(for_item_id>0){
        disp = "display:none;";
    }
    
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
                                            <th style="width:50px;"></th>\n\
                                            <th style="width:50px;"></th>\n\
                                            <th style="width:50px;"></th>\n\                                        \n\
                                        </tr>\n\
                                    </thead>\n\
                                    <tbody></tbody>\n\
                                    <tfoot>\n\
                                        <tr>\n\
                                            <th>id</th>\n\
                                            <th>Name</th>\n\
                                            <th></th>\n\
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
        
        var _URL_="";
        if(item_id==0){
            _URL_="?r=shortcuts&f=get_all_shortcuts";
        }else{
            _URL_="?r=shortcuts&f=get_all_shortcuts_by_group&p0="+item_id;
        }

        _cards_table__var = $('#'+table_name).DataTable({
            ajax: {
                url: _URL_,
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
                { "targets": [2], "searchable": true, "orderable": false,"visible": true },
                { "targets": [3], "searchable": true, "orderable": false,"visible": true },
                { "targets": [4], "searchable": true, "orderable": false, "visible": true,"className": "dt-center" },
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

                $("div.toolbar_shortutsall").html('\n\
                <div class="row" style="'+disp+'">\n\
                    <div class="col-lg-2 col-md-2 col-xs-12" style="padding-left:15px;padding-right:5px;">\n\
                        <div class="btn-group" role="group" aria-label="" style="width:100% !important;">\n\
                            <button onclick="_add_shortcut_for_selected_items(0)" type="button" class="btn btn-primary btn-sm">Add new shortcut</button>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
                ');

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
                    table.cell(index,2).data('<button onclick="show_shortcut_details(\''+parseInt(table.cell(index, 0).data())+'\')" type="button" class="btn btn-default btn-xs" style="font-size:13px;cursor:pointer;width:100%">Show items</button>');
                    table.cell(index,3).data('<button onclick="shortcut_import_to_stock(\''+parseInt(table.cell(index, 0).data())+'\')" type="button" class="btn btn-default btn-xs" style="font-size:13px;cursor:pointer;width:100%">Import to stock</button>');
                    table.cell(index,4).data('<i class="glyphicon glyphicon-trash red" onclick="delete_shortcut(\''+parseInt(table.cell(index, 0).data())+'\')" style="font-size:18px;cursor:pointer" ></i>');
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
                search_in_datatable_shortcut(this.value,that.index(),100,table_name);
            } );
        } );
 
    });
    $('#'+modal_name).on('hide.bs.modal', function (e) {
        $("#"+modal_name).remove();
    });
    $('#'+modal_name).modal('show');
}

var tmo = null;
function search_in_datatable_shortcut(val,index,delay,table_name){
    clearTimeout(tmo);
    tmo = setTimeout(function(){
        $('#'+table_name).DataTable().columns(index).search(val).draw();
    },delay); 
}


function show_shortcut_details(shortcut_id){
    var modal_name = "modal_shorcuts_modal__";
    var modal_title = "Shortcuts";
    var content =
    '<div class="modal medium" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <form id="generate_form" action="" method="post" enctype="multipart/form-data" >\n\
                    <input id="id_to_edit" name="id_to_edit" value="0" type="hidden" />\n\
                    <input id="shortcut_id" name="shortcut_id" value="'+shortcut_id+'" type="hidden" />\n\
                    <div class="modal-header">\n\
                        <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\''+modal_name+'\')"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                <table style="width:100%" id="shortcuts_details_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                    <thead>\n\
                                        <tr>\n\
                                            <th style="width:50px;">Items id</th>\n\
                                            <th style="width:80px;">Barcode</th>\n\
                                            <th>Description</th>\n\
                                            <th style="width:90px;">Qty in shortcut</th>\n\
                                            <th style="width:30px;"></th>\n\                                        \n\
                                        </tr>\n\
                                    </thead>\n\
                                    <tbody></tbody>\n\
                                    <tfoot>\n\
                                        <tr>\n\
                                            <th>Items id</th>\n\
                                            <th>Barcode</th>\n\
                                            <th>Description</th>\n\
                                            <th>&nbsp;</th>\n\
                                            <th></th>\n\
                                        </tr>\n\
                                    </tfoot>\n\
                                </table>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-footer">\n\
                    </div>\n\
                </form>\n\
            </div>\n\
        </div>\n\
    </div>';
    $("#"+modal_name).remove();
    $("body").append(content);
            
    $('#'+modal_name).on('show.bs.modal', function (e) {

    });
    
    $('#'+modal_name).on('shown.bs.modal', function (e) {
        
        var table_name = "shortcuts_details_table";
        var _cards_table__var =null;

        var search_fields = [0,1,2];
        var index = 0;
        $('#'+table_name+' tfoot th').each( function () {
            if(jQuery.inArray(index, search_fields) !== -1){
                var title = $(this).text();
                $(this).html( '<input id="idfsh2_'+index+'" style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder="'+title+'" />' );
                index++;
            }
        });

        _cards_table__var = $('#'+table_name).DataTable({
            ajax: {
                url: "?r=shortcuts&f=get_all_items_in_shortcut&p0="+shortcut_id,
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
                { "targets": [2], "searchable": true, "orderable": true, "visible": true },
                { "targets": [3], "searchable": true, "orderable": true, "visible": true },
                { "targets": [4], "searchable": true, "orderable": false, "visible": true,"className": "dt-center" },
            ],
            scrollCollapse: true,
            paging: true,
            bPaginate: false,
            bLengthChange: false,
            bFilter: true,
            bInfo: false,
            bAutoWidth: true,
            dom: '<"toolbar_shortuts_details">frtip',
            initComplete: function(settings, json) {  
                $("div.toolbar_shortuts_details").html('\n\
                <div class="row">\n\
                    <div class="col-lg-2 col-md-2 col-xs-12" style="padding-left:15px;padding-right:5px;">\n\
                        <div class="btn-group" role="group" aria-label="" style="width:100% !important;">\n\
                            <button onclick="add_items_to_shortcut('+shortcut_id+')" type="button" class="btn btn-primary btn-sm">Add Item</button>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
                ');
                /*$("div.toolbar").html('\n\
                <div class="row">\n\
                    <div class="col-lg-2 col-md-2 col-xs-12" style="padding-left:15px;padding-right:5px;">\n\
                        <div class="btn-group" role="group" aria-label="" style="width:100% !important;">\n\
                            <select disabled data-live-search="true" data-width="100%" id="set_status_all" class="selectpicker" onchange="set_status_all()">\n\
                                '+order_status_options+'\n\
                            </select>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
                ');*/

                //$(".selectpicker").selectpicker();
                //$('#set_status_all').val(status);
                //$('#set_status_all').selectpicker('refresh');
                cleaves_class(".sitem_class",2);
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
                    table.cell(index,4).data('<i class="glyphicon glyphicon-trash red" onclick="delete_item_from_shortcut('+$("#shortcut_id").val()+',\''+parseInt(table.cell(index, 0).data())+'\')" style="font-size:18px;cursor:pointer" ></i>');
                }
             },
        });

        $('#'+table_name).DataTable().on('mousedown',"tr", function ( e, dt, type, indexes ) { 
            $('#shortcuts_details_table .selected').removeClass("selected");
            $(this).addClass('selected');
        });


        $('#'+table_name).DataTable().columns().every( function () {
            var that = this;
            $( 'input', this.footer() ).on( 'keyup change', function () {
                search_in_datatable_shortcut(this.value,that.index(),100,table_name);
            } );
        } );
 
    });
    $('#'+modal_name).on('hide.bs.modal', function (e) {
        $("#"+modal_name).remove();
    });
    $('#'+modal_name).modal('show');
}

function sitem_changed(id){
    $.getJSON("?r=shortcuts&f=update_item_qty_shortcut&p0="+id+"&p1="+$("#sitem_"+id).val(), function (data) {

    }).done(function () {
        
    });
}

