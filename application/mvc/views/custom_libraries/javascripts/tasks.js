var gaction = 0;
var users__=[];
function show_tasks(action){
    $(".sk-circle").center();
    $(".sk-circle-layer").show(); 
    var _data = [];
    $.getJSON("?r=tasks&f=get_task_needed", function (data) {
        _data = data;
    }).done(function () {
        $(".sk-circle-layer").hide();
        
        
        var users_oprions_from = "<option value='0'>All</option>";  
        $.each(_data.users, function (key, val) {
            users__.push(val);
            //if(val.me==1){
                users_oprions_from += "<option value='"+val.id+"'>"+val.username+"</option>";   
            //}
        });
        
        var users_oprions_to = "<option value='0'>All</option>";   
        $.each(_data.users, function (key, val) {
            users_oprions_to += "<option value='"+val.id+"'>"+val.username+"</option>";   
        });
        
        var status_oprions = "<option value='0'>All</option>";   
        status_oprions += "<option value='1'>Pending</option>";
        status_oprions += "<option value='2'>Done</option>";   
        
        gaction = action;
        /* action = 0 => all */
        /* action = 1 => only due date */
        $(".sk-circle").center();
        $(".sk-circle-layer").show(); 
        var table_name = "modal_all_tasks_table";
        var modal_name = "modal_all_tasks____";

        var modal_title = "Notes";
        if(gaction==1){
            modal_title = "Note alert";
        }


        var content =
        '<div class="modal large" data-backdrop="static" id="'+modal_name+'" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title">'+modal_title+'&nbsp;<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\''+modal_name+'\')"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                <table style="width:100%" id="'+table_name+'" class="table table-striped table-bordered" cellspacing="0">\n\
                                    <thead>\n\
                                        <tr>\n\
                                            <th style="width:80px;">Tasks ID</th>\n\
                                            <th style="width:80px;">From </th>\n\
                                            <th style="width:80px;">To</th>\n\
                                            <th style="width:80px;">Due Date</th>\n\
                                            <th style="width:100px;">Alert before</th>\n\
                                            <th>Note</th>\n\
                                            <th style="width:60px;">Status</th>\n\
                                            <th style="width:80px;">Action</th>\n\
                                            <th>&nbsp;</th>\n\
                                            <th style="width:80px;">&nbsp;</th>\n\
                                            <th style="width:80px;">set_done_shift_id</th>\n\
                                            <th style="width:80px;">to me</th>\n\
                                        </tr>\n\
                                    </thead>\n\
                                    <tfoot>\n\
                                        <tr>\n\
                                            <th>Tasks ID</th>\n\
                                            <th>From </th>\n\
                                            <th>To</th>\n\
                                            <th>Due Date</th>\n\
                                            <th>Alert before</th>\n\
                                            <th>Note</th>\n\
                                            <th>Status</th>\n\
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
        $("#"+modal_name).remove();
        $("body").append(content);
        $('#'+modal_name).on('show.bs.modal', function (e) {

        });

        $('#'+modal_name).on('shown.bs.modal', function (e) {

            $('#'+table_name).show();

            var _cards_table__var =null;

            var search_fields = [0,1,2,3,4,5,6];
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
                    url: "?r=tasks&f=get_tasks&p0="+action+"&p1=all&p2=0&p3=0&p4=0",
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
                    { "targets": [1], "searchable": true, "orderable": true, "visible": true },
                    { "targets": [2], "searchable": true, "orderable": true, "visible": true },
                    { "targets": [3], "searchable": true, "orderable": true, "visible": true },
                    { "targets": [4], "searchable": true, "orderable": true, "visible": true },
                    { "targets": [5], "searchable": true, "orderable": true, "visible": true },
                    { "targets": [6], "searchable": true, "orderable": true, "visible": true },
                    { "targets": [7], "searchable": false, "orderable": false, "visible": true },
                    { "targets": [8], "searchable": false, "orderable": false, "visible": false },
                    { "targets": [9], "searchable": false, "orderable": false, "visible": false },
                    { "targets": [10], "searchable": false, "orderable": false, "visible": false },
                    { "targets": [11], "searchable": false, "orderable": false, "visible": false },
                ],
                scrollCollapse: true,
                paging: true,
                bPaginate: false,
                bLengthChange: false,
                bFilter: true,
                bInfo: false,
                bSort:true,
                bAutoWidth: true,
                dom: '<"toolbar_tasks">frtip',
                initComplete: function(settings, json) {
                     $("div.toolbar_tasks").html('\n\
                        <div class="row">\n\
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="padding-left:15px;padding-right:5px;">\n\
                                <div class="btn-group" role="group" aria-label="" style="width:100%">\n\
                                    <label for="date_r">&nbsp;</label>\n\
                                    <button onclick="add_new_task([])" type="button" class="btn btn-info" style="width:100%"><i class="glyphicon glyphicon-plus"></i>&nbsp;Add New Note</button>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="padding-left:15px;padding-right:5px;">\n\
                                <div class="btn-group" role="group" aria-label="" style="width:100%">\n\
                                    <label for="date_r">Due Date</label>\n\
                                    <input id="date_r" class="form-control date_s" type="text" style="width:100%" onchange="refresh_tasks()" />\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-2 col-md-2 col-xs-2" style="padding-left:15px;padding-right:5px;">\n\
                                <div class="btn-group" role="group" aria-label="" style="width:100%">\n\
                                    <label for="from_">From</label>\n\
                                    <select data-width="100%" id="from_" class="selectpicker" onchange="refresh_tasks()">\n\
                                        '+users_oprions_from+'\n\
                                    </select>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-2 col-md-2 col-xs-2" style="padding-left:15px;padding-right:5px;">\n\
                                <div class="btn-group" role="group" aria-label="" style="width:100%">\n\
                                    <label for="to_">To</label>\n\
                                    <select data-width="100%" id="to_" class="selectpicker" onchange="refresh_tasks()">\n\
                                         '+users_oprions_to+'\n\
                                    </select>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-2 col-md-2 col-xs-2" style="padding-left:15px;padding-right:5px;">\n\
                                <div class="btn-group" role="group" aria-label="" style="width:100%">\n\
                                    <label for="status_">Status</label>\n\
                                    <select id="status_" data-width="100%"  class="selectpicker" onchange="refresh_tasks()">\n\
                                        '+status_oprions+'\n\
                                    </select>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" >\n\
                                <div class="btn-group" role="group" aria-label="" style="width:100%">\n\
                                    <div class="btn-group" id="buttons" style="float:right"></div>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        ');   

                    $(".selectpicker").selectpicker();

                    var buttons = new $.fn.dataTable.Buttons(_cards_table__var, {
                        buttons: [
                          {
                                extend: 'excel',
                                text: 'Export excel',
                                className: 'exportExcel',
                                filename: 'Tasks ',
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

                   $('.date_s').daterangepicker({
                        locale: {
                            format: 'YYYY-MM-DD'
                        },
                    });

                    $('.date_s').on('apply.daterangepicker', function(ev) {
                        ev.preventDefault();
                        ev.stopPropagation();
                    });

                    $(".sk-circle-layer").hide();
                },
                fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    $(nRow).addClass("t_"+aData[0]);
                },
                fnDrawCallback: setTasksOptions,
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
        
    }).fail(function() {
        swal("Check your internet connection");
    }).always(function() {
        $(".sk-circle-layer").hide();
        
    });;; 
    
    
}

function refresh_tasks(){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var table = $('#modal_all_tasks_table').DataTable();
    table.ajax.url("?r=tasks&f=get_tasks&p0=0&p1="+$("#date_r").val()+"&p2="+$("#from_").val()+"&p3="+$("#to_").val()+"&p4="+$("#status_").val()+"").load(function () {
        $(".sk-circle-layer").hide();
    }, false);
}

function setTasksOptions(){
    var table = $('#modal_all_tasks_table').DataTable();
    var p = table.rows({ page: 'current' }).nodes();
    for (var k = 0; k < p.length; k++){
        var index = table.row(p[k]).index();
        
        var pd = '';
        
        if(table.cell(index,8).data()==1 && table.cell(index,11).data()==1){
            pd = '<i style="cursor:pointer;font-size:17px;" title="Done" class="glyphicon glyphicon glyphicon-ok" onclick="change_task_status('+parseInt(table.cell(index, 0).data())+',2)" ></i>';
        }
        
        if(table.cell(index,9).data()==1 && table.cell(index,8).data()==1){
            pd += '&nbsp;&nbsp;<i style="cursor:pointer;font-size:17px;"  class="glyphicon glyphicon-edit" title="Edit" onclick="edit_task('+parseInt(table.cell(index, 0).data())+')"></i>';
        }
        
        if(table.cell(index,9).data()==1 && table.cell(index,8).data()==1){
            pd += '&nbsp;&nbsp;<i style="cursor:pointer;font-size:17px;"  class="glyphicon glyphicon-trash red" title="Delete" onclick="delete_task('+parseInt(table.cell(index, 0).data())+')"></i>';
        }
        
    
        if(table.cell(index,8).data()==2 && table.cell(index,10).data()==1){
            pd += '&nbsp;&nbsp;<i style="cursor:pointer;font-size:17px;"  class="glyphicon glyphicon-edit" title="Edit response" onclick="edit_response('+parseInt(table.cell(index, 0).data())+')"></i>';
        }
        
        
        if(table.cell(index,8).data()==2 && table.cell(index,10).data()==1){
            pd += '&nbsp;&nbsp;<i style="cursor:pointer;font-size:17px;" class="glyphicon glyphicon glyphicon-arrow-left" title="Undo" onclick="undo_done('+parseInt(table.cell(index, 0).data())+')"></i>';
        }
        
        table.cell(index,7).data(pd);
    }
}

function delete_fav(task_id){
    swal({
        title: "Delete Favorite?",
        html:false,
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Delete",
        closeOnConfirm: true,
        cancelButtonText: "Cancel"
    },
    function(isConfirm){
        if(isConfirm){
            $(".sk-circle").center();
            $(".sk-circle-layer").show();
            var _data = [];
            $.getJSON("?r=tasks&f=delete_fav&p0="+task_id, function (data) {
                _data = data;
            }).done(function () {
                var table = $('#favnotes').DataTable();
                table.ajax.url("?r=tasks&f=get_favnotes").load(function () {
                    $(".sk-circle-layer").hide();
                }, false);
            }); 
        }
    });
}

function undo_done(task_id){
    swal({
        title: "Undo note?",
        html:false,
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Undo",
        closeOnConfirm: true,
        cancelButtonText: "Cancel"
    },
    function(isConfirm){
        if(isConfirm){
            $(".sk-circle").center();
            $(".sk-circle-layer").show();
            var _data = [];
            $.getJSON("?r=tasks&f=undo_status&p0="+task_id, function (data) {
                _data = data;
            }).done(function () {
                var table = $('#modal_all_tasks_table').DataTable();
                table.ajax.url("?r=tasks&f=get_tasks&p0=0&p1="+$("#date_r").val()+"&p2="+$("#from_").val()+"&p3="+$("#to_").val()+"&p4="+$("#status_").val()+"").load(function () {
                    $(".sk-circle-layer").hide();
                }, false);
            }); 
        }
    });
}

function edit_response(task_id){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var _data = [];
     $.getJSON("?r=tasks&f=get_task_by_id&p0="+task_id, function (data) {
         _data = data;
     }).done(function () {
         $(".sk-circle-layer").hide();
         swal({
            title: "Update response",
            html:true,
            text: "<input style='width:100%' type='text' id='note_text' value='"+_data[0].leaved_note+"' placeholder='' />",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Update",
            closeOnConfirm: true,
            cancelButtonText: "Cancel"
        },
        function(isConfirm){
            if(isConfirm){
                $(".sk-circle").center();
                $(".sk-circle-layer").show();
                var _data = [];
                $.getJSON("?r=tasks&f=set_task_status&p0="+task_id+"&p1=2&p2="+$("#note_text").val(), function (data) {
                    _data = data;
                }).done(function () {
                    //$("#pending_tasks").html(_data[0]);
                    var table = $('#modal_all_tasks_table').DataTable();
                    table.ajax.url("?r=tasks&f=get_tasks&p0=0&p1="+$("#date_r").val()+"&p2="+$("#from_").val()+"&p3="+$("#to_").val()+"&p4="+$("#status_").val()+"").load(function () {
                        $(".sk-circle-layer").hide();
                    }, false);
                }); 
            }
        });
     });
     
     setTimeout(function(){$("#note_text").focus()},300);
}


function change_task_status(task_id,status){
    var btn_text = "Yes";
    if(status==2){
        btn_text = "Set as done";
    }
    swal({
        title: "Are you sure?",
        html:true,
        text: "<input style='width:100%' type='text' id='note_text' value='' placeholder='Leave a note' />",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: btn_text,
        closeOnConfirm: true,
        cancelButtonText: "Cancel"
    },
    function(isConfirm){
        if(isConfirm){
            
            $(".sk-circle").center();
            $(".sk-circle-layer").show();
            var _data = [];
            $.getJSON("?r=tasks&f=set_task_status&p0="+task_id+"&p1="+status+"&p2="+$("#note_text").val(), function (data) {
                _data = data;
            }).done(function () {
                $("#pending_tasks").html(_data[0]);
                var table = $('#modal_all_tasks_table').DataTable();
                table.ajax.url("?r=tasks&f=get_tasks&p0=0&p1="+$("#date_r").val()+"&p2="+$("#from_").val()+"&p3="+$("#to_").val()+"&p4="+$("#status_").val()+"").load(function () {
                    $(".sk-circle-layer").hide();
                }, false);
            }); 
            
        }
    });
    
    setTimeout(function(){$("#note_text").focus()},100);
}

function delete_task(task_id){
    swal({
        title: "Are you sure?",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, Delete It",
        closeOnConfirm: true,
        cancelButtonText: "Cancel",
    },
    function(isConfirm){
        if(isConfirm){
            $(".sk-circle").center();
            $(".sk-circle-layer").show();
            var _data = [];
            $.getJSON("?r=tasks&f=delete_task&p0="+task_id, function (data) {
                _data = data;
            }).done(function () {
                $("#pending_tasks").html(_data[0]);
                var table = $('#modal_all_tasks_table').DataTable();
                table.ajax.url("?r=tasks&f=get_tasks&p0=0&p1="+$("#date_r").val()+"&p2="+$("#from_").val()+"&p3="+$("#to_").val()+"&p4="+$("#status_").val()).load(function () {
                    $(".sk-circle-layer").hide();
                }, false);
            }); 
        }
    });
}

function edit_task(task_id){
    var _data=[];
    $.getJSON("?r=tasks&f=get_task_by_id&p0="+task_id, function (data) {
        _data = data;
    }).done(function () {
        add_new_task(_data);
    }); 
}


function add_new_task(data){ 
    var modal_name = "modal_new_tasks____";
    var modal_title = "Add new note";
    
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    
    var _data = [];
    $.getJSON("?r=tasks&f=get_task_needed", function (data) {
        _data = data;
    }).done(function () {
        $(".sk-circle-layer").hide();
        
        var users_oprions = "";   
        $.each(_data.users, function (key, val) {
            var sel="";
            if(val.me==1){
                sel="";
            }
            users_oprions += "<option value='"+val.id+"'>"+val.username+"</option>";   
        });

    
        var content =
        '<div class="modal medium" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <form id="addtask_form" action="" method="post" enctype="multipart/form-data" >\n\
                        <input type="hidden" name="id_to_edit" id="id_to_edit" value="0" />\n\
                        <div class="modal-header"> \n\
                            <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\''+modal_name+'\')"></i></h3>\n\
                        </div>\n\
                        <div class="modal-body">\n\
                            <div class="row">\n\
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">\n\
                                    <div class="form-group">\n\
                                        <label for="task_due_date">Due Date</label>\n\
                                        <input required style="width:100%" autocomplete="off" id="task_due_date" name="task_due_date" value="" type="text" class="form-control datepicker_task">\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">\n\
                                    <div class="form-group">\n\
                                        <label for="task_due_date">Alert before x days</label>\n\
                                        <input required style="width:100%" autocomplete="off" id="task_bd" name="task_bd" value="0" type="number" class="form-control">\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">\n\
                                    <div class="form-group">\n\
                                        <label for="select_bank">Note To</label>\n\
                                        <select multiple name="select_to[]" id="select_to" class="selectpicker form-control" style="width:100%;">\n\
                                            '+users_oprions+'\n\
                                        </select>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                    <div class="form-group">\n\
                                        <label for="task_description">Note</label>\n\
                                        <textarea required style="width:100%" autocomplete="off" id="task_description" name="task_description" value="0" type="text" class="form-control"></textarea>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                    <label for="task_description">Favorite</label><br/>\n\
                                    <input id="fav" name="fav" type="checkbox" style="width:20px; height:20px;" />\n\
                                </div>\n\
                            </div>\n\
                            <div class="row" style="margin-top:20px;">\n\
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                    <table style="width:100%" id="favnotes" class="table table-striped table-bordered" cellspacing="0">\n\
                                        <thead>\n\
                                            <tr>\n\
                                                <th style="width:80px;">ID</th>\n\
                                                <th>Note</th>\n\
                                                <th style="width:40px;">Delete</th>\n\
                                            </tr>\n\
                                        </thead>\n\
                                        <tbody></tbody>\n\
                                    </table>\n\
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
        submittask_form("modal_new_tasks____");
        $('#'+modal_name).on('show.bs.modal', function (e) {


        });

        $('#'+modal_name).on('shown.bs.modal', function (e) {
            
            if(data.length>0){
                $("#id_to_edit").val(data[0].id);
                $("#task_bd").val(data[0].remind_before);
                $("#task_description").val(data[0].description);
                
                $('.datepicker_task').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                });
                
                
                
                $("#select_to").selectpicker("val",data[0].note_to);
                
                
                var ut = $( "#select_to option:selected" ).text();
                $("#select_to").empty();
                $("#select_to").append("<option value='"+data[0].note_to+"'>"+ut+"</option>");
                $("#select_to").selectpicker("refresh");
                $("#select_to").selectpicker("val",data[0].note_to);
                
                
                $("#task_due_date").datepicker("setDate",data[0].due_date);
                
                
                if(data[0].fav==1){
                    $( "#fav" ).prop( "checked", true );
                }
                
                $('#action_btn').html("Update");
                
            }else{
                $('.datepicker_task').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                });
                
                var tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                $(".datepicker_task").datepicker( "setDate" , tomorrow );

                $("#select_to").selectpicker();
            }


            $('.datepicker_task').datepicker().on('changeDate', function(ev) {
                
            }).on('hide show', function(event) {
                event.preventDefault();
                event.stopPropagation();
            });
            
            
            /* favorite notes */   
            var favnotes = $('#favnotes').DataTable({
                ajax: {
                    url: "?r=tasks&f=get_favnotes",
                    type: 'POST',
                    error:function(xhr,status,error) {
                    },
                },
                responsive: true,
                orderCellsTop: true,
                scrollX: true,
                scrollY: "55vh",
                iDisplayLength: 5,
                aoColumnDefs: [
                    { "targets": [0], "searchable": true, "orderable": true, "visible":  false },
                    { "targets": [1], "searchable": true, "orderable": true, "visible": true },
                    { "targets": [2], "searchable": true, "orderable": true, "visible": true },
                ],
                scrollCollapse: true,
                paging: true,
                bPaginate: false,
                bLengthChange: false,
                bFilter: true,
                bInfo: false,
                bSort:true,
                bAutoWidth: true,
                dom: '<"toolbar_tasks">frtip',
                initComplete: function(settings, json) {

                $('#favnotes').DataTable().on('mousedown',"tr", function ( e, dt, type, indexes ) { 
                    $('.selected').removeClass("selected");
                    $(this).addClass('selected');
                });

                $('#favnotes').on('click', 'td', function () {
                    //if ($(this).index() == 3) {
                        //return false;
                    //}
                });

                $('#favnotes').DataTable().columns().every( function () {
                    var that = this;
                    $( 'input', this.footer() ).on( 'keyup change', function () {
                        //search_in_datatable(this.value,that.index(),100,table_name);
                    } );
                } );
                }
            });
            /* End favorites notes */ 
            
        });
        $('#'+modal_name).on('hide.bs.modal', function (e) {
            $("#"+modal_name).remove();
        });
        $('#'+modal_name).modal('show');
    });  
}

function use_fav(id){
    $("#task_description").val($("#idfav_"+id).html());
}


function submittask_form(modalname){
    $("#addtask_form").on('submit', (function (e) {
        e.preventDefault();
        
        if($("#select_to").val()==0){
            swal("Select note to first");
            return;
        }

        $(".sk-circle").center();
        $(".sk-circle-layer").show(); 
        $.ajax({
            url: "?r=tasks&f=add_new_ctask",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            success: function (data)
            {
                var table = $('#modal_all_tasks_table').DataTable();
                table.ajax.url("?r=tasks&f=get_tasks&p0="+gaction+"&p1="+$("#date_r").val()+"&p2="+$("#from_").val()+"&p3="+$("#to_").val()+"&p4="+$("#status_").val()+"").load(function () {
                    table.page('last').draw(false);
                    $('#modal_new_tasks____').modal('hide');
                    $(".t_"+data[0]).addClass("selected");
                    $(".sk-circle-layer").hide();
                }, false);
 
            }
        });
    }));
}