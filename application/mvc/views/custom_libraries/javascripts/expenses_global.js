function show_expenses_global(){
    $(".sk-circle").center();
    $(".sk-circle-layer").show(); 
    var table_name = "modal_all_expenses_table";
    var modal_name = "modal_all_expenses__";
    var modal_title = "Expenses";
    
    var content =
    '<div class="modal large" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title"><i class="icon-expenses"></i>&nbsp;'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\''+modal_name+'\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <table style="width:100%" id="'+table_name+'" class="table table-striped table-bordered" cellspacing="0">\n\
                                <thead>\n\
                                    <tr>\n\
                                        <th style="width:80px;">Ref.</th>\n\
                                        <th style="width:150px;">Type</th>\n\
                                        <th>Description</th>\n\
                                        <th style="width:80px;">Date</th>\n\
                                        <th style="width:80px;">Value</th>\n\
                                        <th style="width:90px;">&nbsp;</th>\n\
                                        <th style="width:50px;">enable delete</th>\n\
                                    </tr>\n\
                                </thead>\n\
                                <tfoot>\n\
                                    <tr>\n\
                                        <th>Ref.</th>\n\
                                        <th>Type</th>\n\
                                        <th>Description</th>\n\
                                        <th>Date</th>\n\
                                        <th>Value</th>\n\
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

    });
    
    $('#'+modal_name).on('shown.bs.modal', function (e) {
        
        $('#'+table_name).show();
        
        var _table__var =null;
        
        var search_fields = [0,1,2,3,4];
        var index = 0;
        $('#'+table_name+' tfoot th').each( function () {

            if(jQuery.inArray(index, search_fields) !== -1){
                var title = $(this).text();
                $(this).html( '<input id="idf_'+index+'" style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder="'+title+'" />' );
                index++;
            }
        });

        _table__var = $('#'+table_name).DataTable({
            ajax: {
                url: "?r=expenses&f=getExpenses&p0="+current_store_id+"&p1=today",
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
                { "targets": [1], "searchable": true, "orderable": false, "visible": true },
                { "targets": [2], "searchable": true, "orderable": false, "visible": true },
                { "targets": [3], "searchable": true, "orderable": false, "visible": true },
                { "targets": [4], "searchable": true, "orderable": false, "visible": true },
                { "targets": [5], "searchable": true, "orderable": false, "visible": true },
                { "targets": [6], "searchable": false, "orderable": false, "visible": false },
            ],
            scrollCollapse: true,
            paging: true,
            bPaginate: false,
            bLengthChange: false,
            bFilter: true,
            bInfo: false,
            bAutoWidth: true,
            dom: '<"toolbar_global_expenses">frtip',
            initComplete: function(settings, json) {
                $("div.toolbar_global_expenses").html('\n\
                <div class="row">\n\
                    <div class="col-lg-2 col-md-2 col-sm-12" >\n\
                        <div style="width:100%" class="btn-group" role="group" aria-label="">\n\
                            <button style="width:100%" onclick="addGlobalExpense([])" type="button" class="btn btn-primary "><i class="glyphicon glyphicon-plus"></i>&nbsp;Add Expense</button>\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-lg-2 col-md-2 col-sm-12" >\n\
                        <div style="width:100%" class="btn-group" role="group" aria-label="">\n\
                            <button style="width:100%" onclick="showExpenseCategories()" type="button" class="btn btn-primary "><i class="glyphicon glyphicon-plus"></i>&nbsp;Types</button>\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-lg-2 col-md-2 col-sm-12" >\n\
                        <input id="expenses_date_picker" class="form-control" type="text" placeholder="Select date" style="cursor:pointer;width:180px;">\n\
                    </div>\n\
                    <div class="col-lg-6 col-md-6 col-sm-6" >\n\
                        <div class="btn-group" role="group" aria-label="" style="width:100%">\n\
                            <div class="btn-group" id="buttons" style="float:right"></div>\n\
                        </div>\n\
                    </div>\n\
                    </div>\n\
                ');
                
                var start = moment();
                var end = moment();
                        
                $('#expenses_date_picker').daterangepicker({
                    //dateLimit:{month:12},
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
                
                
                /*
                $('#expenses_date_picker').daterangepicker({
                    dateLimit:{month:12},
                    locale: {
                        format: 'YYYY-MM-DD'
                    },
                });*/

                $('#expenses_date_picker').on('apply.daterangepicker', function(ev) {
                    ev.preventDefault();
                    ev.stopPropagation();
                });
                
                $('#expenses_date_picker').change(function() {
                    var table = $('#modal_all_expenses_table').DataTable();
                    table.ajax.url('?r=expenses&f=getExpenses&p0='+current_store_id+"&p1="+$("#expenses_date_picker").val()).load(function () {
                        
                    },false);
                });
                
                
                var buttons = new $.fn.dataTable.Buttons(_table__var, {
                    buttons: [
                      {
                            extend: 'excel',
                            text: 'Export excel',
                            className: 'exportExcel',
                            filename: 'Expenses ',
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
                $(nRow).addClass(aData[0]);
            },
            fnDrawCallback: setGlobalExpensesOptions,
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
    $('#'+modal_name).on('hide.bs.modal', function (e) {
        $("#"+modal_name).remove();
    });
    $('#'+modal_name).modal('show');
}

function edit_expense(id){
    var _data = [];
    $.getJSON("?r=expenses&f=get_expense&p0="+id, function (data) {
        _data = data;
    }).done(function () {
        addGlobalExpense(_data);
    }); 
}

function update_diff_v(){
    $("#difference_inv").html(mask_clean($("#expense_val").val()));
    $("#cash_usd").val(0);
    cash_changed_usd($("#cash_usd"));
}

function addGlobalExpense(data) {
    $(".sk-circle").center();
    $(".sk-circle-layer").show(); 
    var options = "";
    $.getJSON("?r=expenses&f=gettypes", function (data) {
        var index = 0;

        $.each(data, function (key, val) {
            if (index == 0) {
                options += "<option selected value=" + val.id + ">" + val.name + "</option>";
            } else {
                options += "<option value=" + val.id + ">" + val.name + "</option>";
            }
            index++;
        });
    }).done(function () {
        
        var cash_info="";
        if(typeof usd_but_show_lbp_priority !== 'undefined') {
            if(usd_but_show_lbp_priority==1){
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
            }
        }
        
        var content =
            '<div class="modal" data-backdrop="static"  id="add_new_expenses" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <form id="add_new_expenses_form" action="" method="post" enctype="multipart/form-data" >\n\
                    <input id="id_to_edit" name="id_to_edit" type="hidden" value="0" />\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title" id="exampleModalLongTitle"><i class="icon-expenses"></i>&nbsp;Add expense</h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <span style="display:none" id="difference_inv"></span>\n\
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">\n\
                                <div class="form-group">\n\
                                    <label for="expense_type">Expense type</label>&nbsp;<span onclick="addExpenseCategories()" style="cursor:pointer; font-size:14px; font-weight:bold;color:#286090">Add expense type</span>\n\
                                    <select id="expense_type" data-live-search="true" name="expense_type" class="selectpicker form-control" >' + options + '</select>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">\n\
                                <div class="form-group">\n\
                                    <label>Date</label>\n\
                                    <input id="expense_date" name="expense_date" type="text" class="form-control">\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">\n\
                                <div class="form-group">\n\
                                    <label for="expense_type">Value</label>\n\
                                    <input oninput="update_diff_v()" autocomplete="off" id="expense_val" name="expense_val" value="0" type="text" class="form-control med_input" placeholder="Expense value">\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">\n\
                                <div class="form-group">\n\
                                    <label for="expense_description">Description</label>\n\
                                    <input autocomplete="off" id="expense_description" name="expense_description" type="text" class="form-control" placeholder="Expense description">\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">\n\
                                <div class="form-group">\n\
                                    <label for="expense_description">Include in Profit Calculation</label>\n\
                                    <input checked id="reflected_to_profit" name="reflected_to_profit" type="checkbox" class="form-control" style="width:25px;">\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
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

        //$('#add_new_expenses').remove();
        $('#add_new_expenses').modal('hide');    
        
        $('body').append(content);
        submitGlobalExpense();
        
        $('#add_new_expenses').on('show.bs.modal', function (e) {   
        });

        $('#add_new_expenses').on('shown.bs.modal', function (e) {
            $('#expense_date').datepicker({autoclose:true});
            $('#expense_date').datepicker( "setDate", new Date() );
            
            $('#expense_date').datepicker().on('changeDate', function(ev) {

            }).on('hide show', function(event) {
                event.preventDefault();
                event.stopPropagation();
            });

            $('.selectpicker').selectpicker();
            $(".only_numeric").numeric();
            
            
            if(data.length>0){
                $("#id_to_edit").val(data[0].id);
                $("#expense_type").selectpicker("val",data[0].type_id);
                $("#expense_val").val(data[0].value);
                //$('#expense_date').datepicker( "setVal", data[0].date.split(' ')[0] );
                
                //alert(data[0].date.split(' ')[0]);
                $('#expense_date').datepicker( "setDate", data[0].date.split(' ')[0] );
                
                if(data[0].reflected_to_profit==0){
                    $("#reflected_to_profit").prop('checked', false);
                }

                $("#expense_description").val(data[0].description);
                
                $("#action_btn_e").html("Update");
            }
            cleaves_id("expense_val",5);
            $(".sk-circle-layer").hide();
            
            
            set_current_cash_var(2);
            cleaves_id("cash_lbp",0);
            cleaves_id("cash_usd",5);
            cash_changed_usd($("#cash_usd"));
        
        });
        
        $('#add_new_expenses').on('hide.bs.modal', function (e) {
            $("#add_new_expenses").remove();
        });
        
        $('#add_new_expenses').modal('show');    
    });
}

function print_expense(id){
    var width = 500;
    var height = 600;
    var left = (screen.width - width) / 2;
    var top = (screen.height - height) / 2;
    window.open("?r=printing&f=print_expense&p0="+id, '_blank', 'width=' + width + ', height=' + height + ', left=' + left + ', top=' + top);
}

function setGlobalExpensesOptions(){
    var table = $('#modal_all_expenses_table').DataTable();
    var p = table.rows({ page: 'current' }).nodes();
    for (var k = 0; k < p.length; k++){
        var index = table.row(p[k]).index();
        if(table.cell(index,6).data()==1){
            table.cell(index,5).data('<i style="cursor:pointer;font-size:17px;"  class="glyphicon glyphicon-print" title="Print" onclick="print_expense('+parseInt(table.cell(index, 0).data().split('-')[1])+')"></i>&nbsp;&nbsp;<i style="cursor:pointer;font-size:17px;"  class="glyphicon glyphicon-edit" title="Edit" onclick="edit_expense('+parseInt(table.cell(index, 0).data().split('-')[1])+')"></i>&nbsp;&nbsp;<i style="cursor:pointer;font-size:17px;"  class="glyphicon glyphicon-trash red" title="Delete" onclick="delete_expenses('+parseInt(table.cell(index, 0).data().split('-')[1])+')"></i>');
        }
    }
}

function delete_expenses(id){
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
            $.getJSON("?r=expenses&f=delete_expense&p0="+id, function (data) {
        
            }).done(function () {
                var table = $('#modal_all_expenses_table').DataTable();
                table.ajax.url('?r=expenses&f=getExpenses&p0='+current_store_id+"&p1="+$("#expenses_date_picker").val()).load(function () {
                    $(".sk-circle-layer").hide();
                },false);
            }); 
        }
    });
}

function submitGlobalExpense() {
    $("#add_new_expenses_form").on('submit', (function (e) {
        e.preventDefault();
        if($("#expense_val").val()=="") $("#expense_val").val(0);
        if (!emptyInput("expense_description")) {
            $(".sk-circle-layer").show();
            $.ajax({
                url: "?r=expenses&f=add_new_expense&p0="+current_store_id,
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function (data)
                {
                    $('#add_new_expenses').modal('hide');
                    var table = $('#modal_all_expenses_table').DataTable();
                    table.ajax.url('?r=expenses&f=getExpenses&p0='+current_store_id+"&p1="+$("#expenses_date_picker").val()).load(function () {
                        table.page('last').draw(false);
                        $(".sk-circle-layer").hide();
                    },false);
                }
            });
        }
    }));
}

function addExpenseCategories(){
    var content =
        '<div class="modal" id="add_new_expenses_category" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <form id="add_new_expenses_category_form" action="" method="post" enctype="multipart/form-data" >\n\
                <input id="id_to_edit" name="id_to_edit" type="hidden" value="0" />\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title" id="exampleModalLongTitle"><i class="icon-expenses"></i>&nbsp;Add Expense Type</h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="form-group" style="width:220px">\n\
                        <div class="row">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                <label for="expense_type">Expense Type Name</label>\n\
                                <div class="inner-addon left-addon addon_item_icon"><input style="width:220px;" id="category_val" name="category_val" value="" type="text" class="form-control" placeholder="Name"></div>\n\
                            </div>\n\
                        </div>\n\
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
    $('#add_new_expenses_category').modal('hide');
    $('body').append(content);
    
    submitNewCategory();
    
    $('#add_new_expenses_category').on('show.bs.modal', function (e) {   
    });

    $('#add_new_expenses_category').on('shown.bs.modal', function (e) {

    });
    
    $('#add_new_expenses_category').on('hide.bs.modal', function (e) {
        $('#add_new_expenses_category').remove();
    });
    
    $('#add_new_expenses_category').modal('show');
}

function submitNewCategory() {
    $("#add_new_expenses_category_form").on('submit', (function (e) {
        e.preventDefault();
      
        if (!emptyInput("category_val")) {
            $(".sk-circle-layer").show();
            $.ajax({
                url: "?r=expenses&f=add_new_category",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function (data)
                {
                    $(".sk-circle-layer").hide();
                    
    
                    if($('#show_expenses_category_table').length>0){
                         var table = $('#show_expenses_category_table').DataTable();
                        table.ajax.url("?r=expenses&f=getExpensesTypes&p0="+$("#expenses_date_picker").val()).load(function () {
                            $('#add_new_expenses_category').modal('hide'); 
                            table.page('last').draw('page');
                        },false);
                    }else{
                        $("#expense_type").append("<option value='"+data.category_id+"'>"+data.category_name+"</option>");
                        $("#expense_type").selectpicker('refresh');
                        $("#expense_type").selectpicker('val', data.category_id);
                        $('#add_new_expenses_category').modal('hide');   
                        
                    }  
                }
            });
        }
    }));
}

function showExpenseCategories(){
    var modal_name = "show_expenses_category";
    var  table_name = "show_expenses_category_table";
    var content =
        '<div class="modal" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <form id="show_expenses_category_form" action="" method="post" enctype="multipart/form-data" >\n\
                <input id="id_to_edit" name="id_to_edit" type="hidden" value="0" />\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title" id="exampleModalLongTitle"><i class="icon-expenses"></i>&nbsp;Expenses Types<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\''+modal_name+'\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="row">\n\
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">\n\
                            <button style="width:100%" onclick="addExpenseCategories()" type="button" class="btn btn-primary "><i class="glyphicon glyphicon-plus"></i>&nbsp;Add New Type</button>\n\
                        </div>\n\
                    </div>\n\
                    <div class="row">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                <table style="width:100%" id="'+table_name+'" class="table table-striped table-bordered" cellspacing="0">\n\
                                    <thead>\n\
                                        <tr>\n\
                                            <th>Id</th>\n\
                                            <th>Name</th>\n\
                                            <th>Total</th>\n\
                                            <th style="width:50px;">&nbsp;</th>\n\
                                        </tr>\n\
                                    </thead>\n\
                                    <tfoot>\n\
                                        <tr>\n\
                                            <th>ID</th>\n\
                                            <th>Name</th>\n\
                                            <th>&nbsp;</th>\n\
                                            <th>&nbsp;</th>\n\
                                        </tr>\n\
                                    </tfoot>\n\
                                    <tbody></tbody>\n\
                                </table>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                <form/>\n\
            </div>\n\
        </div>\n\
    </div>';
    $('#'+modal_name).remove();
    $('body').append(content);

    $('#'+modal_name).on('show.bs.modal', function (e) {   
    });

    $('#'+modal_name).on('shown.bs.modal', function (e) {
        var _table__var =null;
        
        var search_fields = [0,1];
        var index = 0;
        $('#'+table_name+' tfoot th').each( function () {

            if(jQuery.inArray(index, search_fields) !== -1){
                var title = $(this).text();
                $(this).html( '<input id="idf_'+index+'" style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder="'+title+'" />' );
                index++;
            }
        });

        _table__var = $('#'+table_name).DataTable({
            ajax: {
                url: "?r=expenses&f=getExpensesTypes&p0="+$("#expenses_date_picker").val(),
                type: 'POST',
                error:function(xhr,status,error) {
                },
            },
            responsive: true,
            orderCellsTop: true,
            scrollX: true,
            scrollY: "55vh",
            iDisplayLength: 10,
            aoColumnDefs: [
                { "targets": [0], "searchable": true, "orderable": true, "visible":  false },
                { "targets": [1], "searchable": false, "orderable": false, "visible": true },
                { "targets": [2], "searchable": false, "orderable": false, "visible": true },
                { "targets": [3], "searchable": false, "orderable": false, "visible": true,"className": "dt-center" },
            ],
            scrollCollapse: true,
            paging: true,
            bPaginate: false,
            bLengthChange: false,
            bFilter: true,
            bInfo: false,
            bAutoWidth: true,
            dom: '<"toolbar_global_expensestypes">frtip',
            initComplete: function(settings, json) {
                //$("div.toolbar_global_expensestypes").html('\n\
                    //'+$("#expenses_date_picker").val()+'\n\
                //');
                
                $(".sk-circle-layer").hide();
            },
            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $(nRow).addClass(aData[0]);
            },
            fnDrawCallback: setGlobalExpensesTypesOptions,
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

    $('#'+modal_name).on('hide.bs.modal', function (e) {
        $("#"+modal_name).remove();
    });

    $('#'+modal_name).modal('show'); 
}

function setGlobalExpensesTypesOptions(){
    var table = $('#show_expenses_category_table').DataTable();
    var p = table.rows({ page: 'current' }).nodes();
    for (var k = 0; k < p.length; k++){
        var index = table.row(p[k]).index();
        table.cell(index,3).data('<i style="cursor:pointer;font-size:17px;"  class="glyphicon glyphicon-trash red" title="Delete" onclick="delete_expenses_type('+parseInt(table.cell(index, 0).data())+')"></i>');
    }
}

function delete_expenses_type(id){
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
            $.getJSON("?r=expenses&f=delete_expense_type&p0="+id, function (data) {
        
            }).done(function () {
                var table = $('#show_expenses_category_table').DataTable();
                table.ajax.url("?r=expenses&f=getExpensesTypes").load(function () {
                    $(".sk-circle-layer").hide();
                },false);
            }); 
        }
    });
}

function update_name(id){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    $.getJSON("?r=expenses&f=update_expense_type_name&p0="+id+"&p1="+$("#et_"+id).val(), function (data) {

    }).done(function () {
        $(".sk-circle-layer").hide();
        var table = $('#modal_all_expenses_table').DataTable();
        table.ajax.url('?r=expenses&f=getExpenses&p0='+current_store_id+"&p1="+$("#expenses_date_picker").val()).load(function () {
            
        },false);
        
    }); 
}