var available_brances=0;
function multi_branches_management(){
    var modal_name = "multiple_branches"
    var modal_title = "Multiple Branches"
        
    var content =
        `<div div class= "modal large" data-backdrop="static" id = "${modal_name}" tabindex = "-1" role = "dialog" aria - labelledby="payment_info__" aria - hidden="true" >
            <div class="modal-dialog" style="margin-top:0!important" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">${modal_title}<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\'${modal_name}\')"></i></h3>
                    </div>

                    <div class="modal-body" style="padding-top:2px;">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="background-color:rgb(252 223 83)">
                                <b>Note:</b> To enable multistore functionality or to increase the branches limit, please contact support to upgrade your account. Additional charges will apply.
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <table id="mutiple_branches_table" class="table table-striped table-bordered" cellspacing="0" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Created by</th>
                                            <th>Creation date</th>
                                            <th>Branch name</th>
                                            <th>Location</th>
                                            <th>Stock value</th>
                                            <th>Stock profit</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div> `;
    $('#'+modal_name).modal('hide');
    $("body").append(content);
    
    $('#'+modal_name).on('shown.bs.modal', function (e) {
          
        var table_name = "mutiple_branches_table";
        var _cards_table__var =null;

      

        _cards_table__var = $('#'+table_name).DataTable({
            ajax: {
                url: "?r=multiple_branches&f=get_all_multiple_branches",
                type: 'POST',
                error:function(xhr,status,error) {
                },
                dataSrc: function(json) {
                    if($("#b_available_brances").length>0){
                        $("#b_available_brances").html(json.b_available_brances);
                        b_available_brances=0;

                        $("#b_branches_limit").html(json.b_branches_limit);
                        b_branches_limit=0;

                        $("#b_total_stock_value").html(json.b_total_stock_value+" <small class='currency'>"+currency+"</small>");
                        b_total_stock_value=0;
                        
                         $("#b_total_stock_profit").html(json.b_total_stock_profit+" <small class='currency'>"+currency+"</small>");
                        b_total_stock_profit=0;
                    }else{
  
                        b_available_brances=json.b_available_brances;
                        b_branches_limit=json.b_branches_limit;
                        b_total_stock_value=json.b_total_stock_value;
                        b_total_stock_profit=json.b_total_stock_profit;
                    }
                    return json.data;
                }
            },

            //order: [[1, 'asc']],
            responsive: true,
            orderCellsTop: true,
            scrollX: true,
            scrollY: "50vh",
            iDisplayLength: 100,
            aoColumnDefs: [
                { "targets": [0], "searchable": false, "orderable": true,"visible": true },
                { "targets": [1], "searchable": false, "orderable": true,"visible": true },
                { "targets": [2], "searchable": false, "orderable": true,"visible": true },
                { "targets": [3], "searchable": false, "orderable": true,"visible": true },
                { "targets": [4], "searchable": false, "orderable": true,"visible": true },
                { "targets": [5], "searchable": false, "orderable": true,"visible": true },
                { "targets": [6], "searchable": false, "orderable": true,"visible": false },
                { "targets": [7], "searchable": false, "orderable": true,"visible": true }

            ],
            scrollCollapse: true,
            paging: false,
            bPaginate: false,
            bLengthChange: false,
            bFilter: true,
            bInfo: false,
            bSort:false,
            bAutoWidth: true,
            dom: '<"toolbar_mb">frtip',
            initComplete: function(settings, json) { 
                $("div.toolbar_mb").html('\n\
                <div class="row" style="margin-top:5px;">\n\
                    <div class="col-md-2 col-sm-3" style="padding-right:2px;">\n\
                        <button onclick="add_new_branch(0)" type="button" class="btn btn-primary">Add New Branch</button>\n\
                    </div>\n\
                </div>\n\
                <div class="row" style="margin-top:5px;">\n\
                    <div class="col-md-2 col-sm-3" style="padding-right:2px;">\n\
                        <div class="panel panel-info">\n\
                            <div class="panel-heading">\n\
                                <div class="row">\n\
                                    <div class="col-xs-12 col-sm-12 text-left">\n\
                                        <b class="announcement-heading" id="b_available_brances">0</b>\n\
                                        <p class="announcement-text" style="margin-bottom:0px;">Available branches</p>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-md-2 col-sm-3" style="padding-left:2px;padding-right:2px;">\n\
                        <div class="panel panel-danger">\n\
                            <div class="panel-heading">\n\
                                <div class="row">\n\
                                    <div class="col-xs-12 col-sm-12 text-left">\n\
                                        <b class="announcement-heading" id="b_branches_limit">0</b>\n\
                                        <p class="announcement-text" style="margin-bottom:0px;">Branches limit</p>\n\
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
                                        <b class="announcement-heading" id="b_total_stock_value">0 <small class="currency">'+currency+'</small></b>\n\
                                        <p class="announcement-text" style="margin-bottom:0px;">Total stock value</p>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-md-2 col-sm-3" style="padding-left:2px;padding-right:2px;display:none">\n\
                        <div class="panel panel-success">\n\
                            <div class="panel-heading">\n\
                                <div class="row">\n\
                                    <div class="col-xs-12 col-sm-12 text-left">\n\
                                        <b class="announcement-heading" id="b_total_stock_profit">0 <small class="currency">'+currency+'</small></b>\n\
                                        <p class="announcement-text" style="margin-bottom:0px;">Total stock profit</p>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
                ');
                
                $("#b_available_brances").html(b_available_brances);
                $("#b_branches_limit").html(b_branches_limit);
                $("#b_total_stock_value").html(b_total_stock_value+" <small class='currency'>"+currency+"</small>");
                $("#b_total_stock_profit").html(b_total_stock_profit+" <small class='currency'>"+currency+"</small>");
            },
            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $(nRow).addClass(aData[0]);
            },
            fnDrawCallback: function(){

                var table = $('#'+table_name).DataTable();
                var p = table.rows({ page: 'current' }).nodes();
                for (var k = 0; k < p.length; k++){
                    var index = table.row(p[k]).index();
                    table.cell(index,7).data('<button type="button" class="btn btn-primary btn-xs" onclick="show_items_branch_transfer('+table.cell(index,0).data()+')">Show Items</button>&nbsp;&nbsp;<button type="button" class="btn btn-danger btn-xs" onclick="deleted_branch('+table.cell(index,0).data()+')">Delete</button>');
                }
             },
        });

        $('#'+table_name).DataTable().on('mousedown',"tr", function ( e, dt, type, indexes ) { 
            $('.selected').removeClass("selected");
            $(this).addClass('selected');
        });


        $('#'+table_name).on('click', 'td', function () {
            if ($(this).index() == 4 || $(this).index() == 5) {
                //return false;
            }
        });

    });
    $('#'+modal_name).on('hide.bs.modal', function (e) {
        $("#"+modal_name).remove();
    });
    $('#'+modal_name).modal('show');
}

function deleted_branch(id){
    $.confirm({
        title: 'Delete branch!',
        content: 'Are you sure?',
        buttons: {
            DELETE: {
                btnClass: 'btn-danger',
                action: function(){
                    
                    $(".sk-circle").center();
                    $(".sk-circle-layer").show();
                    var _data=[];
                    $.getJSON("?r=multiple_branches&f=deleted_branch&p0="+id, function (data) {
                        _data=data;
                    }).done(function () {
                        if(_data==-1){
                            $.alert({
                                title: 'Alert!',
                                content: 'This branch cannot be deleted because it contains inventory',
                            });
                            $(".sk-circle-layer").hide();
                        }else{
                            var table = $('#mutiple_branches_table').DataTable();
                            table.ajax.url("?r=multiple_branches&f=get_all_multiple_branches").load(function () {
                                $(".sk-circle-layer").hide();
                            },false);
                        }
                        
                        
                       
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

function add_new_branch(id){
    var new_cat_btn_txt="Add";
    var content =
        '<div class="modal" data-backdrop="static" data-keyboard="false" id="add_new_branch_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">\n\
        <div class="modal-dialog modal-sm" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title" id="">Branch Name<i style="float:right;font-size:30px" class="glyphicon glyphicon-remove" onclick="closeModal(\'add_new_branch_modal\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <div class="form-group">\n\
                                <label>Branch Name</label>\n\
                                    <input id="branch_name" name="branch_name" type="text" class="form-control" value="">\n\
                                </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <div class="form-group">\n\
                                <label>Location</label>\n\
                                    <input id="branch_location" name="branch_location" type="text" class="form-control" value="">\n\
                                </div>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
                <div class="modal-footer">\n\
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\n\
                    <button id="submit_new_branch" onclick="submit_new_branch()" type="submit" class="btn btn-primary">' + new_cat_btn_txt + '</button>\n\
                </div>\n\
            </div>\n\
        </div>\n\
    </div>';

    $('#add_new_branch_modal').modal('hide');
    $('body').append(content);

    $('#add_new_branch_modal').on('show.bs.modal', function (e) {
        

    });



    $('#add_new_branch_modal').on('hide.bs.modal', function (e) {
        $("#add_new_branch_modal").remove();
    });

    $('#add_new_branch_modal').modal('show');
}


function submit_new_branch(){
    var formData = new FormData();
    
    if($("#branch_name").val()==""){
        $("#branch_name").addClass("error");
        return;
    }else{
        $("#branch_name").removeClass("error");
    }
    
    formData.append("branch_name", $("#branch_name").val());
    formData.append("branch_location", $("#branch_location").val());
    
    $("#submit_new_branch").prop("disabled", true);
    $(".sk-circle-layer").show();
    $.ajax({
        url: "?r=multiple_branches&f=submit_new_branch",
        type: "POST",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        dataType: 'json',
        success: function (data)
        {
            if(data==-1){
                
                $('#add_new_branch_modal').modal('hide');
                $("#submit_new_branch").prop("disabled", false);
                $(".sk-circle-layer").hide();
                
                
                $.alert({
                    title: 'Alert!',
                    content: 'You cannot create a new branch because you have exceeded the branch limit.<br/><br/>To increase your limit, please reach out to our support team. <br/><br/><b>Kindly note that additional fees may apply.</b>',
                });
            }else{
                $('#add_new_branch_modal').modal('hide');
                var table = $('#mutiple_branches_table').DataTable();
                table.ajax.url("?r=multiple_branches&f=get_all_multiple_branches").load(function () {
                    $("#submit_new_branch").prop("disabled", false);
                    $(".sk-circle-layer").hide();
                },false);
            }
            
        }
    });
}


function show_items_branch_transfer(id){
    var content =
     '<div class="modal large80" data-keyboard="false" data-backdrop="static" id="showItemsB" tabindex="-1" role="dialog" aria-hidden="true" >\n\
         <div class="modal-dialog" role="document">\n\
             <div class="modal-content">\n\
                <input type="hidden" id="current_br_id" value="'+id+'" />\n\
                 <div class="modal-header"> \n\
                     <h3 class="modal-title">Items<i style="font-size:30px;float:right;cursor:pointer" class="glyphicon glyphicon-remove" onclick="modal_close(\'showItemsB\')"></i></h3>\n\
                 </div>\n\
                 <div class="modal-body">\n\
                     <table style="width:100%" id="items_by_cat_table_details_branches" class="table table-striped table-bordered" cellspacing="0">\n\
                         <thead>\n\
                             <tr>\n\
                                 <th style="width: 40px !important;">Ref.</th>\n\
                                 <th>Name</th>\n\
                                 <th style="width: 80px !important;">Barcode</th>\n\
                                 <th style="width: 50px !important;">Cost</th>\n\
                                 <th style="width: 50px !important;">Price</th>\n\
                                 <th style="width: 40px !important;">Dis. %</th>\n\
                                 <th style="width: 20px !important;">Qty</th>\n\
                                 <th style="width: 40px !important;">Size</th>\n\
                                 <th style="width: 40px !important;">Color</th>\n\
                                 <th style="width: 40px !important;">&nbsp;</th>\n\
                             </tr>\n\
                         </thead>\n\
                         <tfoot>\n\
                             <tr>\n\
                                 <th>Ref.</th>\n\
                                 <th>Name</th>\n\
                                 <th>Barcode</th>\n\
                                 <th>Cost</th>\n\
                                 <th>Price</th>\n\
                                 <th>Discount</th>\n\
                                 <th>Qty</th>\n\
                                 <th>Size</th>\n\
                                 <th>Color</th>\n\
                                 <th>&nbsp;</th>\n\
                             </tr>\n\
                         </tfoot>\n\
                         <tbody></tbody>\n\
                     </table>\n\
                 </div>\n\
                 <div class="modal-footer">\n\
                 </div>\n\
             </div>\n\
         </div>\n\
     </div>';
     $("#showItemsB").remove();
     $("body").append(content);

     $("#showItemsB").centerWH();

     $('#showItemsB').on('show.bs.modal', function (e) {
         var items_by_cat_table_details_branches = null;
         var search_fields = [0,1,2,3,4,5,6,7,8];
         var index = 0;
         $('#items_by_cat_table_details_branches tfoot th').each( function () {
             if(jQuery.inArray(index, search_fields) !== -1){
                 var title = $(this).text();
                 $(this).html( '<div class="inner-addon left-addon"><input style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder=" '+title+'" /></div>' );
                 index++;
             }
         });

        items_by_cat_table_details_branches = $('#items_by_cat_table_details_branches').DataTable({
             ajax: "?r=multiple_branches&f=get_all_items_in_branch&p0="+id,
             orderCellsTop: true,
             select: true,
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
             ],
             scrollY: '44vh',
             scrollCollapse: true,
             paging: true,
             bPaginate: false,
             bLengthChange: false,
             bFilter: true,
             bInfo: false,
             dom: '<"toolbarcat">frtip',
             fnDrawCallback: updateRowsDetails_items_lst,
             fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                 $(nRow).addClass(aData[0]);
             },
             initComplete: function(settings, json) {
                 items_by_cat_table_details_branches.cell( ':eq(0)' ).focus();

                 $("div.toolbarcat").html('\n\
                     <div class="row" id="tab_toolbar">\n\
                         <div class="col-lg-12 col-md-12 col-sm-12" >\n\
                             <div class="btn-group" role="group" aria-label="" style="width:100%">\n\
                                 <div class="btn-group" id="buttons_cat" style="float:right"></div>\n\
                             </div>\n\
                         </div>\n\
                     </div>\n\
                 ');

                 var buttons = new $.fn.dataTable.Buttons(items_by_cat_table_details_branches, {
                 buttons: [
                   {
                         extend: 'excel',
                         text: 'Export excel',
                         className: 'exportExcel',
                         filename: 'Items',
                         customize: _customizeExcelOptions,
                         exportOptions: {
                             modifier: {
                                 page: 'all'
                             },
                             columns: [ 0,1,2,3,4,5,6,7,8 ]
                             //format: {
                                 //body: function ( data, row, column, node ) {
                                     // Strip $ from salary column to make it numeric
                                     ///return column === 6 ? data.replace( /[L.L.,]/g, '' ) : data;
                                 //}
                             //}
                         }
                   }
                 ]

            }).container().appendTo($('#buttons_cat'));

            function _customizeExcelOptions(xlsx) {
                var sheet = xlsx.xl.worksheets['sheet1.xml'];
                 var clR = $('row', sheet);
                 //var r1 = Addrow(clR.length+2, [{key:'A',value: "Total Credit Notes"},{key:'B',value: total}]);
                 //sheet.childNodes[0].childNodes[1].innerHTML = sheet.childNodes[0].childNodes[1].innerHTML + r1;

                 //$('row c[r^="A'+(clR.length+2)+'"]', sheet).attr('s', '48');

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



             }
         });

         $('#items_by_cat_table_details_branches tbody').on( 'click', 'tr', function () {
             if ($(this).hasClass('selected') ) {
                 return false;
             }else {
                 //items_by_cat_table_details_branches.$('tr.selected').removeClass('selected');
                 //$(this).addClass('selected');
             }
         } );

         $('#items_by_cat_table_details_branches').on('key-focus.dt', function(e, datatable, cell){
             $(items_by_cat_table_details_branches.row(cell.index().row).node()).addClass('selected');
         });

         $('#items_by_cat_table_details_branches').on('key-blur.dt', function(e, datatable, cell){
             $(items_by_cat_table_details_branches.row(cell.index().row).node()).removeClass('selected');
         });

         $('#items_by_cat_table_details_branches').on('key.dt', function(e, datatable, key, cell, originalEvent){
             if(key === 13){
                 //alert(key);
             }
         }); 

         $('#items_by_cat_table_details_branches').DataTable().columns().every( function () {
             var that = this;
             $( 'input', this.footer() ).on( 'keyup change', function () {
                 items_by_cat_table_details_branches.keys.disable();
                 if ( that.search() !== this.value ) {
                     that.search( this.value ).draw();
                 }
                 items_by_cat_table_details_branches.keys.enable();
             } );
         } );
     });

     $('#showItemsB').on('hide.bs.modal', function (e) {
         $('#showItemsB').remove();
     });
     $('#showItemsB').modal('show');
}

function updateRowsDetails_items_lst(){
    var table = $('#items_by_cat_table_details_branches').DataTable();
    var p = table.rows({ page: 'current' }).nodes();
    for (var k = 0; k < p.length; k++){
        var index = table.row(p[k]).index();
        table.cell(index,9).data('<i onclick="transfer_between_m_branches('+$("#current_br_id").val()+',0,'+parseInt(table.cell(index,0).data().split('-')[1])+')" title="Transfer to branch" class="glyphicon glyphicon-transfer iconpic"></i>');
    }
}

function from_branch_changed(item_id,object){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    
    var selectedText = $('#from_branch option:selected').text();
    $("#av_qty_from").html("("+selectedText+")");
    
    var _data=[];
    $.getJSON("?r=multiple_branches&f=get_available&p0="+item_id+"&p1="+$(object).val(), function (data) {
        _data=data;
    }).done(function () {
        $(".sk-circle-layer").hide();
        $("#av_qty_t").val(_data.avqty);
    });
}

function transfer_between_m_branches(from_branch_id,to_branch_id,item_id){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var _data=[];
    $.getJSON("?r=multiple_branches&f=get_branches&p0="+item_id+"&p1="+from_branch_id, function (data) {
        _data=data;
    }).done(function () {
        $(".sk-circle-layer").hide();
        var option_branches = "";
        option_branches += "<option value='0'>Main Branch</option>";
        for(var i=0;i<_data.b.length;i++){
            option_branches += "<option value='"+_data.b[i].id+"'>"+_data.b[i].bn+"</option>";
        }
        
        var option_branches_to = "";
        option_branches_to += "<option value='0'>Main Branch</option>";
        for(var i=0;i<_data.b.length;i++){
            option_branches_to += "<option value='"+_data.b[i].id+"'>"+_data.b[i].bn+"</option>";
        }
        
        var new_cat_btn_txt="Transfer";
        var content =
            '<div class="modal" data-backdrop="static" data-keyboard="false" id="transfer_branch_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">\n\
            <div class="modal-dialog modal-sm" role="document">\n\
                <div class="modal-content">\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title" id="">Transfer Items<i style="float:right;font-size:30px" class="glyphicon glyphicon-remove" onclick="closeModal(\'transfer_branch_modal\')"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                <label style="color:#2c7696">'+_data.item_desc+'</label>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row" style="margin-top:5px;">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                <label>From Branch</label>\n\
                                <div class="form-group" style="margin-bottom:5px;">\n\
                                    <select data-live-search="true" id="from_branch" name="from_branch" class="selectpicker form-control" style="width:100%" onchange="from_branch_changed('+item_id+',this)">'+option_branches+'</select>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row" style="margin-top:5px;">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                <div class="form-group" style="margin-bottom:0px;">\n\
                                    <label>Available Quantity <span class="av_qty_from" id="av_qty_from"></span></label>\n\
                                    <input readonly id="av_qty_t" name="av_qty_t" type="number" class="form-control" value="'+_data.item_av_qty+'">\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row" style="margin-top:25px;">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                <label>To Branch</label>\n\
                                <div class="form-group" style="margin-bottom:5px;">\n\
                                    <select data-live-search="true" id="to_branch" name="to_branch" class="selectpicker form-control" style="width:100%" onchange="">'+option_branches_to+'</select>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row" style="margin-top:5px;">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                <div class="form-group" style="margin-bottom:0px;">\n\
                                    <label>Transfer Quantity</label>\n\
                                    <input id="tr_qty_t" name="tr_qty_t" type="number" class="form-control" value="">\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-footer">\n\
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\n\
                        <button id="submit_transfer_branch" onclick="submit_transfer_branch('+item_id+')" type="button" class="btn btn-primary">' + new_cat_btn_txt + '</button>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>';

        $('#transfer_branch_modal').modal('hide');
        $('body').append(content);

        $('#transfer_branch_modal').on('show.bs.modal', function (e) {
            $(".selectpicker").selectpicker();
            $("#from_branch").selectpicker("val",from_branch_id);
            $("#to_branch").selectpicker("val",to_branch_id);
            
            var selectedText = $('#from_branch option:selected').text();
            $("#av_qty_from").html("("+selectedText+")");
        });

        $('#transfer_branch_modal').on('hide.bs.modal', function (e) {
            $("#transfer_branch_modal").remove();
        });

        $('#transfer_branch_modal').modal('show');
    });
    
}

function setToZeroIfNaN(input) {
    if (isNaN(input)) {
        return 0;
    }
    return input;
}

function submit_transfer_branch(item_id){
    var av_qty = setToZeroIfNaN(parseFloat($("#av_qty_t").val()));
    var qty_to_transfer = setToZeroIfNaN(parseFloat($("#tr_qty_t").val()));
    
    if(qty_to_transfer>av_qty){
        $.alert({
            title: 'Alert!',
            content: 'Stock quantity transferred cannot exceed available inventory',
        });
        return;
    }
    if(qty_to_transfer==0){
        $.alert({
            title: 'Alert!',
            content: 'Transfer quantity must be more than zero',
        });
        return;
    }
    
    if($("#from_branch").val()==$("#to_branch").val()){
        $.alert({
            title: 'Alert!',
            content: 'Unable to transfer stock quantity within the same branch',
        });
        return;
    }
    
    $("#submit_transfer_branch").prop("disabled",true);
    
    var formData = new FormData();
    formData.append("qty_to_transfer", qty_to_transfer);
    formData.append("from_branch", parseInt($("#from_branch").val()));
    formData.append("to_branch",  parseInt($("#to_branch").val()));
    formData.append("item_id",  item_id);
    $(".sk-circle-layer").show();
    $.ajax({
        url: "?r=multiple_branches&f=submit_transfer_branch",
        type: "POST",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        dataType: 'json',
        success: function (data)
        {
            $("#submit_transfer_branch").prop("disabled",false);
            $("#tr_qty_t").val(0);
            from_branch_changed(item_id,$("#from_branch"));
            
            $("#transfer_branch_modal").modal("hide");
            
            if($('#items_by_cat_table_details').length>0){
                $('#add_items_to_store').modal('hide');
                var dt = $('#parent_categories_table').DataTable();
                var sdata = dt.row('.selected', 0).data();

                var dt = $('#categories_table').DataTable();
                var sdata_category = dt.row('.selected', 0).data();

                var table_details = $("#items_by_cat_table_details").DataTable();
                table_details.ajax.url("?r=items&f=get_all_items_of_sub_category&p0="+parseInt(sdata_category[0].split('-')[1])+"&p1="+current_store_id).load(function () {
                     $(".sk-circle-layer").hide();
                },false);

                $(".sk-circle-layer").hide();
            }
            
            
            if($('#items_by_cat_table_details_branches').length>0){
                var table = $('#items_by_cat_table_details_branches').DataTable();
                table.ajax.url("?r=multiple_branches&f=get_all_items_in_branch&p0="+$("#current_br_id").val()).load(function () {
                    $(".sk-circle-layer").hide();
                },false);
            }
            
  
            if($('#items_table').length>0){
                update_table_data();
            }
             
        }
    });
    
}