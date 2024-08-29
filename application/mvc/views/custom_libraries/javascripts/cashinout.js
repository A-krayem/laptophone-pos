var CASH_IN_OUT_TYPES = [];
var currency_nb_in_out = 0;

function cashinout_types_changed(){
    var current_type = $("#operationtype").val();
    for(var i=0;i<CASH_IN_OUT_TYPES.length;i++){
        if(CASH_IN_OUT_TYPES[i].id==current_type){
            $('#cashtype').selectpicker('val', CASH_IN_OUT_TYPES[i].in_out);
        }
    }
}

function cinout_v_changed(){
    if(currency_nb_in_out==1){
        $("#value_lbp").val($("#value").val().replace(/,/g , ''));
        cleaves_id("value_lbp",5);

    }
}

function add_cashinout(){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var currencies_options = "";
    var cashinout_types = '';
    var cashinout_types_filter = "";
    var services_cinout_name = "Services";
    currency_nb_in_out = 0;
    CASH_IN_OUT_TYPES = [];
    var hide_services = "display:block;";
    $.getJSON("?r=settings_info&f=get_cashin_out_needed_data", function (data) {
        $.each(data.currencies, function (key, val) {
            currencies_options+='<option value='+val.id+'>'+val.name+' ('+val.symbole+')</option>';
            currency_nb_in_out++;
        });
        
        cashinout_types_filter = '<option value=0>All Operations</option>';
        $.each(data.cashinout_types, function (key, val) {
            CASH_IN_OUT_TYPES.push(val);
            cashinout_types+='<option value='+val.id+'>'+val.name+'</option>';
            cashinout_types_filter+='<option value='+val.id+'>'+val.name+'</option>';
        });
        $.each(data.cashinout_types_even_deleted, function (key, val) {
            if(val.group==1 && val.deleted==1){
                hide_services="display:none;";
                services_cinout_name = "Cashin/Out";
            }
        });
    }).done(function () {
        var cashtype_options = "";
        cashtype_options += "<option selected value='0'>Select</option>";
        cashtype_options += "<option value='1'>Pay Cash</option>";
        cashtype_options += "<option value='2'>Topup</option>";
        
        var operationtype_options = "";

        var modal_name = "modal_cashinout_modal__";
        var modal_title = "Wish Money Operations";
        var content =
        '<div class="modal large" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <form id="cashinout_form" action="" method="post" enctype="multipart/form-data" >\n\
                        <input id="id_to_edit" name="id_to_edit" value="0" type="hidden" />\n\
                        <div class="modal-header" style="padding-top:5px;padding-bottom:5px;">\n\
                            <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\''+modal_name+'\')"></i></h3>\n\
                        </div>\n\
                        <div class="modal-body" style="padding-top:2px;">\n\
                            <div class="row" style="margin-top:5px;">\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pl2">\n\
                                    <div class="form-group" style="margin-bottom:5px;">\n\
                                        <input id="cashinoutDate" class="form-control" type="text" placeholder="Select dat" style="cursor:pointer;width:100%;" />\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="row">\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pr2" style="background-color:#ccc">\n\
                                    <div class="form-group" style="margin-bottom:5px;">\n\
                                        <label for="operationtype">Operation Type</label>\n\
                                        <select data-live-search="true" id="operationtype" name="operationtype" class="selectpicker form-control" style="width:100%" onchange="cashinout_types_changed()">'+cashinout_types+'</select>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 plr2" style="background-color:#ccc">\n\
                                    <div class="form-group" style="margin-bottom:5px;">\n\
                                        <label for="op_ref">Operation Reference</label>\n\
                                        <input value="" required id="op_ref" name="op_ref" class="form-control" style="width:100%" />\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 plr2" style="background-color:#ccc">\n\
                                    <div class="form-group" style="margin-bottom:5px;">\n\
                                        <label for="cashtype">Cash Type</label>\n\
                                        <select data-live-search="true" id="cashtype" name="cashtype" class="selectpicker form-control" onchange="" style="width:100%">'+cashtype_options+'</select>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 plr2" style="background-color:#ccc">\n\
                                    <div class="form-group" style="margin-bottom:5px;">\n\
                                        <label for="cashtype">Currency</label>\n\
                                        <select data-live-search="true" id="cash_currency" name="cash_currency" class="selectpicker form-control" onchange="" style="width:100%">'+currencies_options+'</select>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 plr2" style="display:none">\n\
                                    <div class="form-group" style="margin-bottom:5px;">\n\
                                        <label for="code">Rate</label>\n\
                                        <input value="0" required id="rate" name="rate" class="form-control mask_format mask_font_size" style="width:100%" />\n\
                                        <input id="rate_clean" name="rate_clean" type="hidden" />\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 plr2" style="background-color:#ccc;padding-left:5px;padding-right:5px;">\n\
                                    <div class="row">\n\
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 pr2">\n\
                                            <div class="form-group" style="margin-bottom:5px;">\n\
                                                <label for="code">Amount</label>\n\
                                                <input autocomplete="off" required onchange="cinout_v_changed()" id="value" name="value" class="form-control med_input" style="width:100%" />\n\
                                                <input id="value_clean" name="value_clean" type="hidden" />\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 plr2">\n\
                                            <div class="form-group" style="margin-bottom:5px;">\n\
                                                <label for="code">Cash (LBP)</label>\n\
                                                <input autocomplete="off" required id="value_lbp" name="value_lbp" class="form-control med_input" style="width:100%" />\n\
                                                <input id="value_lbp_clean" name="value_lbp_clean" type="hidden" />\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 pl2">\n\
                                            <div class="form-group" style="margin-bottom:5px;">\n\
                                                <label for="code">Cash (USD)</label>\n\
                                                <input autocomplete="off" required id="value_usd" name="value_usd" class="form-control med_input" style="width:100%" />\n\
                                                <input id="value_usd_clean" name="value_usd_clean" type="hidden" />\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 pl2" style="background-color:#ccc;padding-left:5px;padding-right:5px;">\n\
                                    <div class="form-group" style="margin-bottom:5px;">\n\
                                        <label for="cinout">&nbsp;</label>\n\
                                        <button id="cinout" style="width:100%" type="submit" class="btn btn-info" onclick="">Add</button>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="row">\n\
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="min-height:380px;max-height:380px;">\n\
                                    <table style="width:100%" id="cashinout_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                        <thead>\n\
                                            <tr>\n\
                                                <th style="width:120px;">ID</th>\n\
                                                <th style="width:150px;">Operation Type</th>\n\
                                                <th>Operation Reference</th>\n\
                                                <th style="width:50px;">Type</th>\n\
                                                <th style="width:50px;">Currency</th>\n\
                                                <th style="width:100px;">Rate</th>\n\
                                                <th style="width:80px;">User</th>\n\
                                                <th style="width:120px;">Date</th>\n\
                                                <th>Note</th>\n\
                                                <th style="width:80px;">Amount</th>\n\
                                                <th style="width:80px;">Cash (LBP)</th>\n\
                                                <th style="width:80px;">Cash (USD)</th>\n\
                                                <th style="width:30px;"></th>\n\
                                            </tr>\n\
                                        </thead>\n\
                                        <tbody></tbody>\n\
                                    </table>\n\
                                </div>\n\
                            </div>\n\
                            <div class="row" style="margin-top:5px;">\n\n\
\n\                             <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pr2">\n\
                                    <div class="form-group" style="margin-bottom:5px;">\n\
                                        <select data-live-search="true" id="operationtype_filter" name="operationtype_filter" class="selectpicker form-control" style="width:100%" onchange="update_cashin_out_info()">'+cashinout_types_filter+'</select>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">\n\
                                    <table class="transfer_table">\n\
                                        <tr style="height:30px;">\n\
                                            <td style="width:100%;padding-left:2px;text-align:center"><b class="in">Staring Shift USD</b></br><input onchange="update_starting_usd()" style="height:21px;" value="0" type="text" class="transfer_table_val" id="starting_usd" /></td>\n\
                                        </tr>\n\
                                        <tr style="height:30px;">\n\
                                            <td style="width:100%;padding-left:2px;text-align:center;"><b class="out">Starting Shift LBP</b></br><input onchange="update_starting_lbp()"  style="height:21px;" value="0" type="text" class="transfer_table_val" id="starting_lbp" /></td>\n\
                                        </tr>\n\
                                    </table>\n\
                                    <table class="transfer_table" style="margin-left:10px;">\n\
                                        <tr style="height:30px;">\n\
                                            <td style="width:100%;padding-left:2px;text-align:center;"><b class="in">Total Cash IN USD</b></br><span class="transfer_table_val" id="total_cash_in_usd">0</span></td>\n\
                                        </tr>\n\
                                        <tr style="height:30px;">\n\
                                            <td style="width:100%;padding-left:2px;text-align:center"><b class="in">Total Cash IN LBP</b></br><span class="transfer_table_val" id="total_cash_in_lbp">0</span></td>\n\
                                        </tr>\n\
                                    </table>\n\
                                    <table class="transfer_table">\n\
                                        <tr style="height:30px;">\n\
                                            <td style="width:100%;padding-left:2px;text-align:center;"><b class="out">Total Cash OUT USD</b></br><span class="transfer_table_val" id="total_cash_out_usd">0</span></td>\n\
                                        </tr>\n\
                                        <tr style="height:30px;">\n\
                                            <td style="width:100%;padding-left:2px;text-align:center"><b class="out">Total Cash OUT LBP</b></br><span class="transfer_table_val" id="total_cash_out_lbp">0</span></td>\n\
                                        </tr>\n\
                                    </table>\n\
                                    <table class="transfer_table">\n\
                                        <tr style="height:30px;">\n\
                                            <td style="width:100%;padding-left:2px;text-align:center"><b class="in">Total USD</b></br><span class="transfer_table_val" id="total__usd">0</span></td>\n\
                                        </tr>\n\
                                        <tr style="height:30px;">\n\
                                            <td style="width:100%;padding-left:2px;text-align:center"><b class="in">Total LBP</b></br><span class="transfer_table_val" id="total__lbp">0</span></td>\n\
                                        </tr>\n\
                                    </table>\n\
                                    <table class="transfer_table" style="margin-left:10px;float:right;">\n\
                                        <tr style="height:30px;">\n\
                                            <td style="width:100%;padding-left:2px;text-align:center;"><b class="out">Total Amount OUT USD</b></br><span class="transfer_table_val" id="total_amount_out_usd">0</span></td>\n\
                                        </tr>\n\
                                        <tr style="height:30px;">\n\
                                            <td style="width:100%;padding-left:2px;text-align:center"><b class="out">Total Amount OUT LBP</b></br><span class="transfer_table_val" id="total_amount_out_lbp">0</span></td>\n\
                                        </tr>\n\
                                    </table>\n\
                                    <table class="transfer_table" style="float:right;" >\n\
                                        <tr style="height:30px;">\n\
                                            <td style="width:100%;padding-left:2px;text-align:center"><b class="in">Total Amount IN USD</b></br><span class="transfer_table_val" id="total_amount_in_usd">0</span></td>\n\
                                        </tr>\n\
                                        <tr style="height:30px;">\n\
                                            <td style="width:100%;padding-left:2px;text-align:center"><b class="in">Total Amount IN LBP</b></br><span class="transfer_table_val" id="total_amount_in_lbp">0</span></td>\n\
                                        </tr>\n\
                                    </table>\n\
                                </div>\n\
                            </div>\n\
                            <div class="row" style="background-color:#ccc;display:none">\n\
                                <div class="col-lg-2 col-md-2 col-xs-2" style="padding-left:5px;padding-right:5px;text-align:center;padding-top:5px;">\n\
                                    <b style="font-size:24px;">'+services_cinout_name+'</b>\n\
                                </div>\n\
                                <div class="col-lg-1 col-md-1 col-xs-1" style="padding-left:5px;padding-right:5px;text-align:center;padding-top:5px;">\n\
                                    <b>IN USD</b> <br/><span style="font-size:16px;" id="service_in_usd">-</span>\n\
                                </div>\n\
                                <div class="col-lg-1 col-md-1 col-xs-1" style="padding-left:5px;padding-right:5px;text-align:center;padding-top:5px;">\n\
                                    <b>IN LBP</b> <br/><span style="font-size:16px;" id="service_in_lbp">-</span>\n\
                                </div>\n\
                                <div class="col-lg-1 col-md-1 col-xs-1" style="padding-left:5px;padding-right:5px;text-align:center;padding-top:5px;">\n\
                                    <b>OUT USD</b> <br/><span style="font-size:16px;" id="service_out_usd">-</span>\n\
                                </div>\n\
                                <div class="col-lg-1 col-md-1 col-xs-1" style="padding-left:5px;padding-right:5px;text-align:center;padding-top:5px;">\n\
                                    <b>OUT LBP</b> <br/><span style="font-size:16px;" id="service_out_lbp">-</span>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-xs-1" style="padding-left:5px;padding-right:5px;text-align:center;padding-top:5px;">\n\
                                    <b>Balance USD</b> <br/><span style="font-size:16px;" id="service_balance_usd">-</span>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-xs-1" style="padding-left:5px;padding-right:5px;text-align:center;padding-top:5px;">\n\
                                    <b>Balance LBP</b> <br/><span style="font-size:16px;" id="service_balance_lbp">-</span>\n\
                                </div>\n\
                            </div>\n\
                            <div class="row" style="'+hide_services+';display:none"">\n\
                                <div class="col-lg-2 col-md-2 col-xs-2" style="padding-left:5px;padding-right:5px;text-align:center;padding-top:5px;">\n\
                                    <b style="font-size:24px;">Transfers</b>\n\
                                </div>\n\
                                <div class="col-lg-1 col-md-1 col-xs-1" style="padding-left:5px;padding-right:5px;text-align:center;padding-top:5px;">\n\
                                    <b>IN USD</b> <br/><span style="font-size:16px;" id="transfer_in_usd">-</span>\n\
                                </div>\n\
                                <div class="col-lg-1 col-md-1 col-xs-1" style="padding-left:5px;padding-right:5px;text-align:center;padding-top:5px;">\n\
                                    <b>IN LBP</b> <br/><span style="font-size:16px;" id="transfer_in_lbp">-</span>\n\
                                </div>\n\
                                <div class="col-lg-1 col-md-1 col-xs-1" style="padding-left:5px;padding-right:5px;text-align:center;padding-top:5px;">\n\
                                    <b>OUT USD</b> <br/><span style="font-size:16px;" id="transfer_out_usd">-</span>\n\
                                </div>\n\
                                <div class="col-lg-1 col-md-1 col-xs-1" style="padding-left:5px;padding-right:5px;text-align:center;padding-top:5px;">\n\
                                    <b>OUT LBP</b> <br/><span style="font-size:16px;" id="transfer_out_lbp">-</span>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-xs-1" style="padding-left:5px;padding-right:5px;text-align:center;padding-top:5px;">\n\
                                    <b>Balance USD</b> <br/><span style="font-size:16px;" id="transfer_balance_usd">-</span>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-xs-1" style="padding-left:5px;padding-right:5px;text-align:center;padding-top:5px;">\n\
                                    <b>Balance LBP</b> <br/><span style="font-size:16px;" id="transfer_balance_lbp">-</span>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </form>\n\
                </div>\n\
            </div>\n\
        </div>';
        $("#"+modal_name).remove();
        $("body").append(content);
        submitcashinout_form(modal_name);


        $('#'+modal_name).on('show.bs.modal', function (e) {

        });

        $('#'+modal_name).on('shown.bs.modal', function (e) {
            //$('#wasting_barcode').focus();
            $("#cashtype").selectpicker();
            $("#cash_currency").selectpicker();
            $("#operationtype").selectpicker();
            
            $("#operationtype_filter").selectpicker();
            
            
            $(".sk-circle-layer").hide();
            
            $(".mask_format").mask("#,##0.00", {reverse: true});
            $(".mask_format_lbp").mask("#,##0", {reverse: true});


            var table_name = "cashinout_table";
            var _cards_table__var =null;

                var search_fields = [];
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
                        url: "?r=cashinout&f=get_all_cashinout&p0=today&p1=0&p2=0",
                        type: 'POST',
                        error:function(xhr,status,error) {
                        },
                    },
                    //order: [[1, 'asc']],
                    responsive: true,
                    orderCellsTop: true,
                    scrollX: true,
                    scrollY: "50vh",
                    iDisplayLength: 100,
                    aoColumnDefs: [
                        { "targets": [0], "searchable": false, "orderable": true,"visible": false },
                        { "targets": [1], "searchable": false, "orderable": true,"visible": true },
                        { "targets": [2], "searchable": true, "orderable": true, "visible": true },
                        { "targets": [3], "searchable": true, "orderable": true, "visible": true },
                        { "targets": [4], "searchable": true, "orderable": true, "visible": true },
                        { "targets": [5], "searchable": true, "orderable": true, "visible": false },
                        { "targets": [6], "searchable": true, "orderable": true, "visible": true },
                        { "targets": [7], "searchable": true, "orderable": true, "visible": true },
                        { "targets": [8], "searchable": true, "orderable": true, "visible": false},//,"className": "dt-center"
                        { "targets": [9], "searchable": true, "orderable": true, "visible": true},
                        { "targets": [10], "searchable": true, "orderable": true, "visible": true},
                        { "targets": [11], "searchable": true, "orderable": false, "visible": true,"className": "dt-center"},
                    ],
                    scrollCollapse: true,
                    paging: false,
                    bPaginate: false,
                    bLengthChange: false,
                    bFilter: true,
                    bInfo: false,
                    bSort:false,
                    bAutoWidth: true,
                    dom: '<"toolbarcashinout">frtip',
                    initComplete: function(settings, json) { 

                        /*$("div.toolbarcashinout").html('\n\
                        <div class="row">\n\
                            <div class="col-lg-2 col-md-2 col-xs-2" style="padding-left:15px;padding-right:5px;">\n\
                                <div class="btn-group" role="group" aria-label="" style="width:100% !important;">\n\
                                    <input id="cashinoutDate" class="form-control" type="text" placeholder="Select dat" style="cursor:pointer;width:100%;" />\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        ');*/
                        
                        $('#cashinoutDate').daterangepicker({
                            //dateLimit:{month:12},
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

                        $( "#cashinoutDate" ).change(function() {
                            refresh_cashinout_table(0);
                        });

                        cleaves_id("value",5);
                        cleaves_id("value_lbp",5);
                        cleaves_id("value_usd",5);
                        
                        cleaves_id("starting_lbp",0);
                        cleaves_id("starting_usd",5);
                        
                        
                        
                        

                        update_cashin_out_info();
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
                            table.cell(index,12).data('<i class="glyphicon glyphicon-trash" onclick="delete_cashin_out(\''+parseInt(table.cell(index, 0).data())+'\')" style="font-size:18px;cursor:pointer" ></i>');
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
    });
    
}

function mask_value_format_cashinout(){
    var format = "#,##0.";
    for(var i=0;i<2;i++){
        format+="0";
    }
    return format;
}

function parseNumberCustom(number_string) {
    return number_string.replace(/[^0-9\.]/g, '');
}

function mask_clean_value_format_cashinout(val){
    if(val==""){
        val = 0;
    }
    return parseFloat(val.replace(/[^0-9\.]/g, ''));
}


function update_starting_usd(){
    $.getJSON("?r=cashinout&f=update_starting_usd&p0="+$("#starting_usd").val().replace(/,/g , ''), function (data) {
        
    }).done(function () {
        update_cashin_out_info()
    });
}

function update_starting_lbp(){
    $.getJSON("?r=cashinout&f=update_starting_lbp&p0="+$("#starting_lbp").val().replace(/,/g , ''), function (data) {
        
    }).done(function () {
        update_cashin_out_info()
    });
}

function submitcashinout_form(modalname){
    $("#cashinout_form").on('submit', (function (e) {
        e.preventDefault();
        
        if($("#cashtype").val()==0){
            alert("Select Cash Type First");
            return;
        }
        
        $(".sk-circle-layer").show();
        $('#value_clean').val($('#value').val().replace(/,/g , ''));
        $('#value_lbp_clean').val($('#value_lbp').val().replace(/,/g , ''));
        $('#value_usd_clean').val($('#value_usd').val().replace(/,/g , ''));
        $('#rate_clean').val( mask_clean_value_format_cashinout($('#rate').val()));

        $.ajax({
            url: "?r=cashinout&f=add_new_cashinout",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            success: function (data)
            {
                //$('#rate').val('');
                $('#value').val('');
                $('#value_lbp').val('');
                $('#value_usd').val('');
                refresh_cashinout_table(1);
                update_cashin_out_info();
                $(".sk-circle-layer").hide();
                //alert("DONE");
            }
        });
    }));
}

function refresh_cashinout_table(last_row){
    var table = $('#cashinout_table').DataTable();
    table.ajax.url("?r=cashinout&f=get_all_cashinout&p0="+$( "#cashinoutDate" ).val()+"&p1=0&p2=0").load(function () { 
        if(last_row==1){
            table.page('last').draw(false);
        }
        setTimeout(function(){
            $("#modal_cashinout_modal__ .dataTables_scrollBody").scrollTop($('#modal_cashinout_modal__ .dataTables_scrollBody')[0].scrollHeight);
            $('#cashinout_table tr:last').addClass('selected');
            $(".sk-circle-layer").hide();
        },20);
         
    },false);
}


function delete_cashin_out(id){
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
            $.getJSON("?r=cashinout&f=delete_cashinout&p0="+id, function (data) {
        
            }).done(function () {
                $(".sk-circle-layer").hide();
                refresh_cashinout_table(0);
                refresh_cashinout_table_report();
                update_cashin_out_info();
            }); 
        }
    });
}

function update_cashin_out_info(){
    $.getJSON("?r=cashinout&f=update_cashin_out_info&p0="+$("#operationtype_filter").val()+"&p1=1", function (data) {
        $("#transfer_in_lbp").html(data.transfer_in_lbp);
        $("#transfer_in_usd").html(data.transfer_in_usd);
        $("#transfer_out_lbp").html(data.transfer_out_lbp);
        $("#transfer_out_usd").html(data.transfer_out_usd);
        $("#transfer_balance_lbp").html(data.transfer_balance_lbp);
        $("#transfer_balance_usd").html(data.transfer_balance_usd);
        
        
        $("#service_in_lbp").html(data.service_in_lbp);
        $("#service_in_usd").html(data.service_in_usd);
        $("#service_out_lbp").html(data.service_out_lbp);
        $("#service_out_usd").html(data.service_out_usd);
        $("#service_balance_lbp").html(data.service_balance_lbp);
        $("#service_balance_usd").html(data.service_balance_usd);
     
        $("#total_amount_in_usd").html(data.total_amount_in_usd);
        $("#total_amount_in_lbp").html(data.total_amount_in_lbp);
        
        
        $("#total_amount_out_usd").html(data.total_amount_out_usd);
        $("#total_amount_out_lbp").html(data.total_amount_out_lbp);
        
        
        $("#total_cash_in_usd").html(data.total_cash_in_usd);
        $("#total_cash_out_usd").html(data.total_cash_out_usd);
        
        $("#total_cash_in_lbp").html(data.total_cash_in_lbp);
        $("#total_cash_out_lbp").html(data.total_cash_out_lbp);
        
        
        $("#starting_usd").val(data.starting_usd_amount);
        $("#starting_lbp").val(data.starting_lbp_amount);
        cleaves_id("starting_usd",0);
        cleaves_id("starting_lbp",0);
        
        if($("#operationtype_filter").val()==0){
            $("#total__usd").html(data.ending_usd_amount);
            $("#total__lbp").html(data.ending_lbp_amount);
        }else{
            $("#total__usd").html("-");
            $("#total__lbp").html("-");
        }
        
        
        
    }).done(function () {
        $(".sk-circle-layer").hide();
    }); 
}

function show_cashin_out_report(){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var currencies_options = "";
    var cashinout_types = "";
    CASH_IN_OUT_TYPES = [];
    $.getJSON("?r=settings_info&f=get_cashin_out_needed_data", function (data) {
        currencies_options+='<option selected value="0">ALL</option>';
        $.each(data.currencies, function (key, val) {
            currencies_options+='<option value='+val.id+'>'+val.name+' ('+val.symbole+')</option>';
        });
        cashinout_types+='<option selected value="0">ALL</option>';
        $.each(data.cashinout_types, function (key, val) {
            CASH_IN_OUT_TYPES.push(val);
            cashinout_types+='<option value='+val.id+'>'+val.name+'</option>';
        });
    }).done(function () {
        var cashtype_options = "";
        cashtype_options += "<option selected value='0'>ALL</option>";
        cashtype_options += "<option value='1'>IN</option>";
        cashtype_options += "<option value='2'>OUT</option>";
        
        var modal_name = "modal_cashinout_report_modal__";
        var modal_title = "Money Operations";
        var content =
        '<div class="modal large" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <form id="cashinout_form" action="" method="post" enctype="multipart/form-data" >\n\
                        <input id="id_to_edit" name="id_to_edit" value="0" type="hidden" />\n\
                        <div class="modal-header">\n\
                            <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\''+modal_name+'\')"></i></h3>\n\
                        </div>\n\
                        <div class="modal-body">\n\
                            <div class="row">\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">\n\
                                    <div class="form-group" style="margin-bottom:5px;">\n\
                                        <input id="cashinoutDate" class="form-control" type="text" placeholder="Select dat" style="cursor:pointer;width:100%;" />\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">\n\
                                    <div class="form-group" style="margin-bottom:5px;">\n\
                                        <select data-live-search="true" id="shift_id" name="shift_id" class="selectpicker form-control" onchange="refresh_cashinout_table_report()" style="width:100%"></select>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">\n\
                                    <div class="form-group" style="margin-bottom:5px;">\n\
                                        <select data-live-search="true" id="operationtype" name="operationtype" class="selectpicker form-control" style="width:100%" onchange="refresh_cashinout_table_report()">'+cashinout_types+'</select>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="display:none">\n\
                                    <div class="form-group" style="margin-bottom:5px;">\n\
                                        <label for="cashtype">Cash Type</label>\n\
                                        <select data-live-search="true" id="cashtype" name="cashtype" class="selectpicker form-control" onchange="refresh_cashinout_table_report()" style="width:100%">'+cashtype_options+'</select>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="row">\n\
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                    <table style="width:100%" id="cashinout_report_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                        <thead>\n\
                                            <tr>\n\
                                                <th style="width:120px;">ID</th>\n\
                                                <th>Operation Type</th>\n\
                                                <th>Reference</th>\n\
                                                <th style="width:80px;">Cash Type</th>\n\
                                                <th style="width:100px;">Currency</th>\n\
                                                <th style="width:100px;">Rate</th>\n\
                                                <th style="width:80px;">User</th>\n\
                                                <th style="width:140px;">Date</th>\n\
                                                <th>Note</th>\n\
                                                <th style="width:100px;">Amount</th>\n\
                                                <th style="width:100px;">Cash in (LBP)</th>\n\
                                                <th style="width:100px;">Cash in (USD)</th>\n\
                                                <th style="width:60px;"></th>\n\
                                            </tr>\n\
                                        </thead>\n\
                                        <tbody></tbody>\n\
                                    </table>\n\
                                </div>\n\
                            </div>\n\
                            <div class="row">\n\
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">\n\
                                    <table style="width:100%;border:1px solid #CCC">\n\
                                        <tr>\n\
                                            <td colspan="2" style="width:100%;text-align:center;border-bottom:1px solid #CCC;text-align:center;color:#fff;background-color:#646464 !important"><b style="font-size:14px;">Cash USD</b></td>\n\
                                        </tr>\n\
                                        <tr>\n\
                                            <td style="font-size:16px;width:50%;text-align:center"><b>Total IN</b>: <span id="total_in_usd"></span></td>\n\
                                            <td style="font-size:16px;text-align:center"><b>Total OUT</b>: <span id="total_out_usd"></span></td>\n\
                                        </tr>\n\
                                        <tr>\n\
                                            <td colspan="2" style="font-size:16px;text-align:center"><b>Balance</b>: <span id="total_usd"></span></td>\n\
                                        </tr>\n\
                                    </table>\n\
                                </div>\n\
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">\n\
                                    <table style="width:100%;border:1px solid #CCC">\n\
                                        <tr>\n\
                                            <td colspan="2" style="width:100%;text-align:center;border-bottom:1px solid #CCC;text-align:center;color:#fff;background-color:#646464 !important"><b style="font-size:14px;">Cash LBP</b></td>\n\
                                        </tr>\n\
                                        <tr>\n\
                                            <td style="font-size:16px;width:50%;text-align:center"><b>Total IN</b>: <span id="total_in_lbp"></span></td>\n\
                                            <td style="font-size:16px;text-align:center"><b>Total OUT</b>: <span id="total_out_lbp"></span></td>\n\
                                        </tr>\n\
                                        <tr>\n\
                                            <td colspan="2" style="font-size:16px;text-align:center"><b>Balance</b>: <span id="total_lbp"></span></td>\n\
                                        </tr>\n\
                                    </table>\n\
                                </div>\n\
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">\n\
                                    <table style="width:100%;border:1px solid #CCC">\n\
                                        <tr>\n\
                                            <td colspan="2" style="width:100%;text-align:center;border-bottom:1px solid #CCC;text-align:center;color:#fff;background-color:#646464 !important"><b style="font-size:14px;">Amount USD</b></td>\n\
                                        </tr>\n\
                                        <tr>\n\
                                            <td style="font-size:16px;width:50%;text-align:center"><b>Total IN</b>: <span id="total_in_usd_amount"></span></td>\n\
                                            <td style="font-size:16px;text-align:center"><b>Total OUT</b>: <span id="total_out_usd_amount"></span></td>\n\
                                        </tr>\n\
                                        <tr>\n\
                                            <td colspan="2" style="font-size:16px;text-align:center"><b>Balance</b>: <span id="total_usd_amount"></span></td>\n\
                                        </tr>\n\
                                    </table>\n\
                                </div>\n\
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">\n\
                                    <table style="width:100%;border:1px solid #CCC">\n\
                                        <tr>\n\
                                            <td colspan="2" style="width:100%;text-align:center;border-bottom:1px solid #CCC;text-align:center;color:#fff;background-color:#646464 !important"><b style="font-size:14px;">Amount LBP</b></td>\n\
                                        </tr>\n\
                                        <tr>\n\
                                            <td style="font-size:16px;width:50%;text-align:center"><b>Total IN</b>: <span id="total_in_lbp_amount"></span></td>\n\
                                            <td style="font-size:16px;text-align:center"><b>Total OUT</b>: <span id="total_out_lbp_amount"></span></td>\n\
                                        </tr>\n\
                                        <tr>\n\
                                            <td colspan="2" style="font-size:16px;text-align:center"><b>Balance</b>: <span id="total_lbp_amount"></span></td>\n\
                                        </tr>\n\
                                    </table>\n\
                                </div>\n\
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 stbl" style="margin-top:15px;display:none">\n\
                                    <table style="width:100%;border:1px solid #CCC">\n\
                                        <tr>\n\
                                            <td colspan="2" style="width:100%;text-align:center;border-bottom:1px solid #CCC;text-align:center;color:#fff;background-color:#646464 !important"><b style="font-size:14px;">Starting Cashbox LBP</b></td>\n\
                                        </tr>\n\
                                        <tr>\n\
                                            <td style="font-size:16px;width:50%;text-align:center"><b>Amount</b>: <span id="starting_lbp_amount"></span></td>\n\
                                        </tr>\n\
                                    </table>\n\
                                </div>\n\
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 stbl" style="margin-top:15px;display:none">\n\
                                    <table style="width:100%;border:1px solid #CCC">\n\
                                        <tr>\n\
                                            <td colspan="2" style="width:100%;text-align:center;border-bottom:1px solid #CCC;text-align:center;color:#fff;background-color:#646464 !important"><b style="font-size:14px;">Starting Cashbox USD</b></td>\n\
                                        </tr>\n\
                                        <tr>\n\
                                            <td style="font-size:16px;width:50%;text-align:center"><b>Amount</b>: <span id="starting_usd_amount"></span></td>\n\
                                        </tr>\n\
                                    </table>\n\
                                </div>\n\
                            </div>\n\
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
            
            $("#cashtype").selectpicker();
            $("#cash_currency").selectpicker();
            $("#operationtype").selectpicker();
            
           
            $('#cashinoutDate').daterangepicker({
                //dateLimit:{month:12},
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

            $( "#cashinoutDate" ).change(function() {
                refresh_cashinout_table_report();
            });

            var table_name = "cashinout_report_table";
            var _cards_table__var =null;

            var search_fields = [];
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
                    url: "?r=cashinout&f=get_all_cashinout_report&p0=today&p1=0&p2=0&p3=0",
                    type: 'POST',
                    error:function(xhr,status,error) {
                    },
                },
                //order: [[1, 'asc']],
                responsive: true,
                orderCellsTop: true,
                scrollX: true,
                scrollY: "55vh",
                iDisplayLength: 10,
                aoColumnDefs: [
                    { "targets": [0], "searchable": false, "orderable": true,"visible": false },
                    { "targets": [1], "searchable": false, "orderable": true,"visible": true },
                    { "targets": [2], "searchable": false, "orderable": true,"visible": true },
                    { "targets": [3], "searchable": true, "orderable": true, "visible": true },
                    { "targets": [4], "searchable": true, "orderable": true, "visible": true },
                    { "targets": [5], "searchable": true, "orderable": true, "visible": true },
                    { "targets": [6], "searchable": true, "orderable": true, "visible": false },
                    { "targets": [7], "searchable": true, "orderable": true, "visible": true },
                    { "targets": [8], "searchable": true, "orderable": true, "visible": true },
                    { "targets": [9], "searchable": true, "orderable": true, "visible": false},//,"className": "dt-center"
                    { "targets": [10], "searchable": true, "orderable": false, "visible": true},
                    { "targets": [11], "searchable": true, "orderable": false, "visible": true},
                    { "targets": [12], "searchable": true, "orderable": false, "visible": true,"className": "dt-center"},

                ],
                scrollCollapse: true,
                paging: true,
                bPaginate: false,
                bLengthChange: false,
                bFilter: true,
                bInfo: false,
                bAutoWidth: true,
                dom: '<"toolbarcashinout">frtip',
                initComplete: function(settings, json) {

                    update_cashin_out_info_report();
                    $(".sk-circle-layer").hide();
                    
                    get_get_cashin_out_shifts();
                    
                },
                fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    $(nRow).addClass(aData[0]);
                },
                 fnDrawCallback: function(){
                    var table = $('#'+table_name).DataTable();
                    var p = table.rows({ page: 'current' }).nodes();
                    for (var k = 0; k < p.length; k++){
                        var index = table.row(p[k]).index();
                        table.cell(index,12).data('<i class="glyphicon glyphicon-trash" onclick="delete_cashin_out(\''+parseInt(table.cell(index, 0).data())+'\')" style="font-size:18px;cursor:pointer" ></i>');
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
        $(".sk-circle-layer").hide();
    }); 
}

function get_get_cashin_out_shifts(){
    var _data=[];
    $.getJSON("?r=settings_info&f=get_get_cashin_out_shifts&p0="+$("#cashinoutDate").val(), function (data) {
        _data=data;
    }).done(function () {
        
        $("#shift_id").empty();
         $("#shift_id").append("<option value='0' selected>All shifts</option>");
        for(var i=0;i<_data.length;i++){
            $("#shift_id").append("<option value='"+_data[i].id+"'>Open date: "+_data[i].date+"</option>");
        }
        $("#shift_id").selectpicker("refresh");
        
    });
}

function refresh_cashinout_table_report(){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var table = $('#cashinout_report_table').DataTable();
    table.ajax.url("?r=cashinout&f=get_all_cashinout_report&p0="+$("#cashinoutDate").val()+"&p1="+$("#operationtype").val()+"&p2="+$("#cashtype").val()+"&p3="+$("#shift_id").val()).load(function () { 
         $(".sk-circle-layer").hide(); 
        update_cashin_out_info_report();
    },false);
}

function update_cashin_out_info_report(){
    
    
    $.getJSON("?r=cashinout&f=update_cashin_out_info_report&p0="+$("#cashinoutDate").val()+"&p1="+$("#operationtype").val()+"&p2="+$("#cashtype").val()+"&p3="+$("#shift_id").val(), function (data) {
        $("#total_in_usd").html(data.total_in_usd);
        $("#total_out_usd").html(data.total_out_usd);
        
        $("#total_in_lbp").html(data.total_in_lbp);
        $("#total_out_lbp").html(data.total_out_lbp);
        
        $("#total_usd").html(data.total_usd);
        $("#total_lbp").html(data.total_lbp);
        
        
        $("#total_in_usd_amount").html(data.total_in_usd_amount);
        $("#total_out_usd_amount").html(data.total_out_usd_amount);
        
        $("#total_in_lbp_amount").html(data.total_in_lbp_amount);
        $("#total_out_lbp_amount").html(data.total_out_lbp_amount);
        
        $("#total_usd_amount").html(data.total_usd_amount);
        $("#total_lbp_amount").html(data.total_lbp_amount);
        
        //alert($("#shift_id").val());
        if($("#shift_id").val()==0 || $("#shift_id").val()==null){
            $(".stbl").hide();
        }else{
            $(".stbl").show();
        }
        
        
        $("#starting_lbp_amount").html(data.starting_lbp_amount);
        $("#starting_usd_amount").html(data.starting_usd_amount);
        
    }).done(function () {
        //$(".sk-circle-layer").hide();
    });
}