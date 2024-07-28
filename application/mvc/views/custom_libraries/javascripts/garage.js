function addCustomer_(){
    addCustomer('add',[],0);
}

function edit_card_client(id){
    var result = [];
    $.getJSON("?r=garage&f=get_garage_card_id&p0="+id, function (data) {
        result = data;
    }).done(function () {
        add_new_card(id,result);
    });
}

function add_new_card(id,data){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var colors_ = "";
    var customers_ = "";
    var oil_change_date_interval_by_day =0;
    $.getJSON("?r=garage&f=get_garage_needed_info", function (data) {
        $.each(data.colors, function (key, val) {
            colors_+='<option value='+val.id+'>'+val.name+'</option>';
        });
        customers_+='<option data-subtext="" value="0">Select Customer</option>';
        $.each(data.customers, function (key, val) {
            customers_+='<option data-subtext="'+val.phone+'" value='+val.id+'>'+val.name+'</option>';
        });
        oil_change_date_interval_by_day = data.oil_change_date_interval_by_day;
    }).done(function () {
        var mh_tr = "Add Customer Card";
        if(data.length>0){
            mh_tr = "Update Customer Card";
        }

        var content =
        '<div class="modal" data-backdrop="static" id="garage_card_modal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <form id="garage_card_form" action="" method="post" enctype="multipart/form-data" >\n\
                    <input id="id_to_edit" name="id_to_edit" type="hidden" value="'+id+'" />\n\
                    <div class="modal-content">\n\
                        <div class="modal-header" style="padding-top:5px;padding-bottom:5px;">\n\
                            <h3 class="modal-title">'+mh_tr+'<i style="float:right;font-size:30px; cursor:pointer" class="glyphicon glyphicon-remove" onclick="shrinkage_close()"></i></h3>\n\
                        </div>\n\
                        <div class="modal-body">\n\
                            <div class="row">\n\
                                <div class="col-xs-12">\n\
                                    <div class="panel panel-primary" style="margin-bottom:5px;" >\n\
                                        <div class="panel-heading" style="padding-top:2px;padding-bottom:2px;"><b>Card Section</b></div>\n\
                                        <div class="panel-body" style="padding-top:2px;padding-bottom:2px;">\n\
                                            <div class="row">\n\
                                                <div class="col-xs-3">\n\
                                                    <div class="form-group">\n\
                                                        <label for="customers_list">Customer Name</label><span onclick="addCustomer_()" style="cursor:pointer; font-size:14px; font-weight:bold;color:#286090">&nbsp;Add new client</span>\n\
                                                        <select data-live-search="true" id="customers_list" name="customers_list" class="selectpicker form-control" onchange="" style="width:100%">'+customers_+'</select>\n\
                                                    </div>\n\
                                                </div>\n\
                                                <div class="col-xs-3">\n\
                                                    <div class="form-group">\n\
                                                        <label for="card_invoice">Invoice ID&nbsp;<span onclick="show_details_invoice_garage()" style="cursor:pointer; font-size:14px; font-weight:bold;color:#286090">Details</span></label>\n\
                                                        <select data-live-search="true" id="card_invoice" name="card_invoice" class="selectpicker form-control" style="width:100%"></select>\n\
                                                    </div>\n\
                                                </div>\n\
                                                <div class="col-xs-6">\n\
                                                    <div class="form-group">\n\
                                                        <label for="code">Code</label>\n\
                                                        <input id="code" name="code" class="form-control" style="width:100%" />\n\
                                                    </div>\n\
                                                </div>\n\
                                            </div>\n\
                                            <div class="row">\n\
                                                <div class="col-xs-3">\n\
                                                    <div class="form-group">\n\
                                                        <label for="date_in">Date/Time In</label>\n\
                                                        <input id="date_in" name="date_in" class="datepicker form-control" style="width:100%" />\n\
                                                    </div>\n\
                                                </div>\n\
                                                <div class="col-xs-3">\n\
                                                    <div class="form-group">\n\
                                                        <label for="date_out">Date/Time Out</label>\n\
                                                        <input id="date_out" name="date_out" class="datepicker form-control" style="width:100%" />\n\
                                                    </div>\n\
                                                </div>\n\
                                                <div class="col-xs-3">\n\
                                                    <div class="form-group">\n\
                                                        <label for="company">Car</label>\n\
                                                        <input  id="company" name="company" class="form-control" style="width:100%" />\n\
                                                    </div>\n\
                                                </div>\n\
                                                <div class="col-xs-3">\n\
                                                    <div class="form-group">\n\
                                                        <label for="car_type">Car Type</label>\n\
                                                        <input id="car_type" name="car_type" class="form-control" style="width:100%" />\n\
                                                    </div>\n\
                                                </div>\n\
                                                <div class="col-xs-3">\n\
                                                    <div class="form-group">\n\
                                                        <label for="car_model">Model</label>\n\
                                                        <input id="car_model" name="car_model" class="form-control" style="width:100%" />\n\
                                                    </div>\n\
                                                </div>\n\
                                                <div class="col-xs-3">\n\
                                                    <div class="form-group">\n\
                                                        <label for="item_text_color">Color &nbsp;<span onclick="addColor()" style="cursor:pointer; font-size:14px; font-weight:bold;color:#286090">Add Color</span></label>\n\
                                                        <select data-live-search="true" id="item_text_color" name="item_text_color" class="selectpicker form-control" style="width:100%">'+colors_+'</select>\n\
                                                    </div>\n\
                                                </div>\n\
                                                <div class="col-xs-3">\n\
                                                    <div class="form-group">\n\
                                                        <label for="car_odometer">Odometer</label>\n\
                                                        <input id="car_odometer" name="car_odometer" class="form-control" style="width:100%" />\n\
                                                    </div>\n\
                                                </div>\n\
                                                <div class="col-xs-3">\n\
                                                    <div class="form-group">\n\
                                                        <label for="car_c">Plate #</label>\n\
                                                        <input id="car_c" name="car_c" class="form-control" style="width:100%" />\n\
                                                    </div>\n\
                                                </div>\n\
                                                <div class="col-xs-12">\n\
                                                    <div class="form-group">\n\
                                                        <label for="problem_description">Problem Description</label>\n\
                                                        <textarea rows="4" id="problem_description" value="" name="problem_description" class="form-control" placeholder=""></textarea>\n\
                                                    </div>\n\
                                                </div>\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="row" style="margin-top:5px;">\n\
                                <div class="col-xs-12">\n\
                                    <div class="panel panel-danger" style="margin-bottom:5px;" >\n\
                                        <div class="panel-heading" style="padding-top:2px;padding-bottom:2px;"><b>Oil Section</b></div>\n\
                                        <div class="panel-body" style="padding-top:2px;padding-bottom:2px;">\n\
                                            <div class="col-xs-3">\n\
                                                <div class="form-group">\n\
                                                    <label for="oil_changed_date">Change Oil Date</label>\n\
                                                    <input id="oil_changed_date" name="oil_changed_date" class="datepicker form-control" style="width:100%" />\n\
                                                </div>\n\
                                            </div>\n\
                                            <div class="col-xs-3">\n\
                                                <div class="form-group">\n\
                                                    <label for="oil_next_change_date">Next Change Date</label>\n\
                                                    <input id="oil_next_change_date" name="oil_next_change_date" class="datepicker form-control" style="width:100%" />\n\
                                                </div>\n\
                                            </div>\n\
                                            <div class="col-xs-6">\n\
                                                <div class="form-group">\n\
                                                    <label for="oil_note">Oil Note</label>\n\
                                                    <input id="oil_note" name="oil_note" class="form-control" style="width:100%" />\n\
                                                </div>\n\
                                            </div>\n\
                                        </div>\n\
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
        $("#garage_card_modal").remove();
        $("body").append(content);

        $("#garage_card_modal").centerWH();

        $('#garage_card_modal').on('show.bs.modal', function (e) {
        });

        $('#garage_card_modal').on('shown.bs.modal', function (e) {
            $('#customers_list').selectpicker({showSubtext:true});
            $('#card_invoice').selectpicker({showSubtext:true});
            $('#item_text_color').selectpicker();
            
            
            $('.datepicker').datepicker({autoclose:true,format: 'yyyy-mm-dd'});
            $("#date_in").datepicker( "setDate", new Date() );
            $("#date_out").datepicker( "setDate", new Date() );
            
            
            $("#date_in").datepicker().on('changeDate', function(ev) {
                //$("#date_out").datepicker( "setDate", $('#date_in').val() );
            }).on('hide show', function(event) {
                event.preventDefault();
                event.stopPropagation();
            });
            
            $("#date_out").datepicker().on('changeDate', function(ev) {
                //$("#date_out").datepicker( "setDate", $('#date_in').val() );
            }).on('hide show', function(event) {
                event.preventDefault();
                event.stopPropagation();
            });
            
            $("#oil_changed_date").datepicker().on('changeDate', function(ev) {
                
                var date2 = $("#oil_changed_date").datepicker('getDate', '+'+parseInt(oil_change_date_interval_by_day)+'d'); 
                date2.setDate(date2.getDate()+parseInt(oil_change_date_interval_by_day)); 
                $("#oil_next_change_date").datepicker('setDate', date2);
  
            }).on('hide show', function(event) {
                event.preventDefault();
                event.stopPropagation();
            });
            
            $("#oil_next_change_date").datepicker().on('changeDate', function(ev) {
                //$("#date_out").datepicker( "setDate", $('#date_in').val() );
            }).on('hide show', function(event) {
                event.preventDefault();
                event.stopPropagation();
            });

            $(".sk-circle-layer").hide();
            //$('#stores_list_source').selectpicker();

            if(data.length>0){
                $("#problem_description").val(data[0].problem_description);
                $("#customers_list").selectpicker('val', data[0].client_id);
                
                $("#item_text_color").selectpicker('val', data[0].color);
                
                if(data[0].date_time_in!="0000-00-00 00:00:00"){
                    $("#date_in").datepicker( "setDate", data[0].date_time_in.split(' ')[0]);
                }
                
                if(data[0].date_time_out!="0000-00-00 00:00:00"){
                    $("#date_out").datepicker( "setDate", data[0].date_time_out.split(' ')[0]);
                }
                
                garage_customer_changed(data[0].invoice_id);
                
                $("#company").val(data[0].company);
                $("#code").val(data[0].code);
                $("#car_type").val(data[0].car_type);
                $("#car_model").val(data[0].model);
                
                $("#car_odometer").val(data[0].odometer);
                $("#car_c").val(data[0].car);
                
                if(data[0].oil_changed_date!=null && data[0].oil_changed_date!="")
                    $("#oil_changed_date").datepicker( "setDate", data[0].oil_changed_date.split(' ')[0]);
                
                if(data[0].oil_next_change_date!=null && data[0].oil_next_change_date!="")
                    $("#oil_next_change_date").datepicker( "setDate", data[0].oil_next_change_date.split(' ')[0]);
                
                $("#oil_note").val(data[0].oil_note);
                
                $("#btn_tr_sub").html('Update');
                //$("#stores_list").selectpicker('val', data[0].to_store_id);
                //$("#stores_list_source").selectpicker('val', data[0].from_store_id);
            }else{
                car_autocomplete("company");
                getcartype_for_type_head("car_type");
                getcarmodel_for_type_head("car_model");
            }
            
            
            
        });

        $('#garage_card_modal').on('hide.bs.modal', function (e) {
            $('#garage_card_modal').remove();
        });

        submitCard(data);

        $('#garage_card_modal').modal('show');  
    });
}

function show_details_invoice_garage(){
    if($("#card_invoice").val()!=null && $("#card_invoice").val()!=0){
        show_details_invoice($("#card_invoice").val());
    }else{
        swal("Unavailable invoice");
        $(".sweet-alert").css('background-color', '#e5e5e5');
    }
}

function garage_customer_changed(id){
    if($("#customers_list").val()==0){
        $("#card_invoice").empty(); 
        $("#card_invoice").selectpicker('refresh');
    }else{
        var _invoices = "";
        $.getJSON("?r=garage&f=getInvoicesOfCustomers&p0="+$("#customers_list").val(), function (data) {
            $.each(data, function (key, val) {
                _invoices+='<option value='+val.id+' data-subtext="'+val.total_value+'">'+val.id_label+'</option>';
            });
        }).done(function () {
            $("#card_invoice").empty();
            $("#card_invoice").append(_invoices);
            $("#card_invoice").selectpicker('refresh');
            if(id>0){
                $("#card_invoice").selectpicker('val', id);
            }
             $("#card_invoice").prop('disabled',true);
        });
    }
}

function submitCard(data_){
    $("#garage_card_form").on('submit', (function (e) {
        e.preventDefault();
        $(".sk-circle-layer").show();
            $.ajax({
                url: "?r=garage&f=add_new_garage_card",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function (data)
                {
                    if($('#garage_cards_table').length>0){
                        var table = $('#garage_cards_table').DataTable();
                        table.ajax.url("?r=garage&f=getAllClientsCards&p0=0").load(function () {
                            if(data_.length>0){
                                table.row('.' + pad_customer_card(data_[0].id), {page: 'current'}).select();
                            }else{
                                table.page('last').draw(false);
                                table.row(':last', {page: 'current'}).select();
                                $(".dataTables_scrollBody").scrollTop($('.dataTables_scrollBody')[0].scrollHeight);
                            } 

                        },false); 
                    }
                    if($('#garage_oil_report_table').length>0){
                        var table = $('#garage_oil_report_table').DataTable();
                        table.ajax.url("?r=garage&f=getAllClientsCards&p0="+$("#oil_due_date_filter").val()).load(function () {
                            $(".tab_toolbar button.blueB").addClass("disabled");    
                        },false); 
                    }
                    
                    if($('#modal_all_clients_card_table').length>0){
                        var table = $('#modal_all_clients_card_table').DataTable();
                        table.ajax.url("?r=garage&f=getAllClientsCards&p0=0").load(function () {
                      
                            if(data_.length>0){
                                $("."+pad_customer_card(data.id)).addClass('selected');
                                //table.row('.' + data_[0].id, {page: 'current'}).select();
                            }else{
                                table.page('last').draw(false);
                                $("."+pad_customer_card(data.id)).addClass('selected');
                                $(".dataTables_scrollBody").scrollTop($('.dataTables_scrollBody')[0].scrollHeight);
                            } 
                        },false); 
                    }
                    
                    $(".sk-circle-layer").hide();
                    $('#garage_card_modal').modal('hide'); 
                }
            });
    }));
}

function show_all_cards(){
    $(".sk-circle").center();
    $(".sk-circle-layer").show(); 
    var table_name = "modal_all_clients_card_table";
    var modal_name = "modal_all_clients_card____";
    var modal_title = "All Cards";
    
    var content =
    '<div class="modal large" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="cards_close()"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <table style="width:100%" id="'+table_name+'" class="table table-striped table-bordered" cellspacing="0">\n\
                                <thead>\n\
                                    <tr>\n\
                                        <th style="width:80px;">CC ID</th>\n\
                                        <th style="width:150px;">Client</th>\n\
                                        <th style="width:70px;">Phone</th>\n\
                                        <th>Problem Description</th>\n\
                                        <th style="width:100px;">Code</th>\n\
                                        <th style="width:100px;">Car</th>\n\
                                        <th style="width:100px;">Car type</th>\n\
                                        <th style="width:70px;">Model</th>\n\
                                        <th style="width:70px;">Color</th>\n\
                                        <th style="width:70px;">Odo</th>\n\
                                        <th style="width:100px;">Plate</th>\n\
                                        <th style="width:50px;"></th>\n\
                                    </tr>\n\
                                </thead>\n\
                                <tfoot>\n\
                                    <tr>\n\
                                        <th>CC ID</th>\n\
                                        <th>Clinet</th>\n\
                                        <th>Phone</th>\n\
                                        <th>Problem Description</th>\n\
                                        <th>Code</th>\n\
                                        <th>Car</th>\n\
                                        <th>Car type</th>\n\
                                        <th>Model</th>\n\
                                        <th>Color</th>\n\
                                        <th>Odometer</th>\n\
                                        <th>Plate</th>\n\
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

    });
    
    $('#'+modal_name).on('shown.bs.modal', function (e) {
        
        $('#'+table_name).show();
        
        var _cards_table__var =null;
        
        var search_fields = [0,1,2,3,4,5,6,7,8,9,10];
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
                url: "?r=pos&f=getAllClientsCards&p0=0",
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
                { "targets": [1], "searchable": true, "orderable": true, "visible": true },
                { "targets": [2], "searchable": true, "orderable": true, "visible": true },
                { "targets": [3], "searchable": true, "orderable": true, "visible": true },
                { "targets": [4], "searchable": true, "orderable": true, "visible": true },
                { "targets": [5], "searchable": true, "orderable": true, "visible": true },
                { "targets": [6], "searchable": true, "orderable": true, "visible": true },
                { "targets": [7], "searchable": true, "orderable": true, "visible": true },
                { "targets": [8], "searchable": true, "orderable": true, "visible": true },
                { "targets": [9], "searchable": true, "orderable": true, "visible": true },
                { "targets": [10], "searchable": true, "orderable": true, "visible": true },
                { "targets": [11], "searchable": true, "orderable": false, "visible": true },
            ],
            scrollCollapse: true,
            paging: true,
            bPaginate: false,
            bLengthChange: false,
            bFilter: true,
            bInfo: false,
            bAutoWidth: true,
            dom: '<"toolbar_cards">frtip',
            initComplete: function(settings, json) {
                $("div.toolbar_cards").html('<div class="row">\n\
                    <div class="col-lg-2 col-md-2 col-xs-12">\n\
                        <div class="form-group" style="width:100%" >\n\
                            <button style="width:100%" onclick="add_new_card(0,[])" type="button" class="btn btn-primary "><i class="glyphicon glyphicon-plus"></i>Add New Client</button>\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-lg-2 col-md-2 col-xs-12">\n\
                        <div class="form-group" style="width:100%" >\n\
                            <select style="width:100%" id="cards_emty" name="cards_emty" class="selectpicker form-control" onchange="card_pendings_changed()" style="width:100%"><option value="0">All Cards</option><option value="1">Pending</option></select>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
                ');        
                
                $(".selectpicker").selectpicker();
                $(".sk-circle-layer").hide();
            },
            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $(nRow).addClass(aData[0]);
            },
            fnDrawCallback: setCards,
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

function card_pendings_changed(){
    $(".sk-circle").center();
    $(".sk-circle-layer").show(); 
    var table = $('#modal_all_clients_card_table').DataTable();
    table.ajax.url("?r=pos&f=getAllClientsCards&p0="+$("#cards_emty").val()).load(function () {
        $(".sk-circle-layer").hide(); 
    },false); 
}

function setCards(){
    var table = $('#modal_all_clients_card_table').DataTable();
    var p = table.rows({ page: 'current' }).nodes();
    for (var k = 0; k < p.length; k++){
        var index = table.row(p[k]).index();
        table.cell(index, 11).data('<i class="glyphicon glyphicon-edit shortcut" title="Edit" onclick="edit_card_client('+parseInt(table.cell(index, 0).data().split("-")[1])+')"></i>');
    }
}

function shrinkage_close(){
    $('#garage_card_modal').modal('toggle');
}

function cards_close(){
    $('#modal_all_clients_card____').modal('toggle');
}