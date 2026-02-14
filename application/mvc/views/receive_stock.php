<?php
/* Converted from echo-heavy decoded file to editable PHP/HTML template.
   Logic/output preserved as much as possible. */
?>
<?php

?>
<!DOCTYPE html>

<html>
<head>
    <title><?php echo $_SESSION["page_title"]; ?> PI</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="resources/favicon.png">

    <script src="libraries/jquery-3.1.1.min.js" type="text/javascript"></script>

    <script src="libraries/bootstrap-3.3.7-dist/js/bootstrap.min.js" type="text/javascript"></script>
    <link href="libraries/bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <script src="libraries/bootstrap-plugins/DataTables-1.10.13/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="libraries/bootstrap-plugins/DataTables-1.10.13/media/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
    <link href="libraries/bootstrap-plugins/DataTables-1.10.13/media/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css"/>

    <link href="application/mvc/views/custom_libraries/css/select2.min.css?v=2" rel="stylesheet" type="text/css" />
    <script src="application/mvc/views/custom_libraries/javascripts/select2.full.min.js" type="text/javascript"></script>

    <script src="libraries/bootstrap-plugins/DataTables-1.10.13/extensions/Select/js/dataTables.select.min.js" type="text/javascript"></script>
    <script src="application/mvc/views/custom_libraries/javascripts/unique_items.js?rnd=<?php echo self::generateRandomStringComplex(); ?>" type="text/javascript"></script>
    <link href="libraries/bootstrap-plugins/datepicker-master/dist/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css"/>
    <script src="libraries/bootstrap-plugins/datepicker-master/dist/js/bootstrap-datepicker.min.js" type="text/javascript"></script>

    <script src="application/mvc/views/custom_libraries/javascripts/global_functions.js?rnd=<?php echo self::generateRandomStringComplex(); ?>" type="text/javascript"></script>
    <script src="application/mvc/views/custom_libraries/javascripts/global.js?rnd=<?php echo self::generateRandomStringComplex(); ?>" type="text/javascript"></script>

    <script src="libraries/bootstrap-plugins/bootstrap-select-master/bootstrap-select-master/dist/js/bootstrap-select.min.js" type="text/javascript"></script>
    <link href="libraries/bootstrap-plugins/bootstrap-select-master/bootstrap-select-master/dist/css/bootstrap-select.min.css" rel="stylesheet" type="text/css"/>
    <link href="application/mvc/views/custom_libraries/css/global.css?rnd=<?php echo self::generateRandomStringComplex(); ?>" rel="stylesheet" type="text/css"/>
    <script src="application/mvc/views/custom_libraries/javascripts/autocomplete.js?rnd=<?php echo self::generateRandomStringComplex(); ?>" type="text/javascript"></script>
    <script src="libraries/jquery.mask.min.js" type="text/javascript"></script>

    <script src="libraries/bootstrap-plugins/libs/bootstrap-confirmation.min.js" type="text/javascript"></script>

    <script src="libraries/bootstrap-plugins/bootstrap-sweetalert-master/dist/sweetalert.min.js" type="text/javascript"></script>
    <link href="libraries/bootstrap-plugins/bootstrap-sweetalert-master/dist/sweetalert.css" rel="stylesheet" type="text/css"/>
    <script src="libraries/numeric.js" type="text/javascript"></script>
    <script src="application/mvc/views/custom_libraries/javascripts/store_items.js?rnd=<?php echo self::generateRandomStringComplex(); ?>" type="text/javascript"></script>

    <link href="application/mvc/views/custom_libraries/svgs/font/style.css?rnd=<?php echo self::generateRandomStringComplex(); ?>" rel="stylesheet" type="text/css"/>

    <script src="application/mvc/views/custom_libraries/javascripts/stock_invoices.js?rnd=<?php echo self::generateRandomStringComplex(); ?>" type="text/javascript"></script>

    <script src="libraries/bootstrap-plugins/bootstrap3-typeahead/bootstrap3-typeahead.min.js" type="text/javascript"></script>

    <link href="libraries/bootstrap-plugins/bootstrap-colorpicker-master/dist/css/bootstrap-colorpicker.min.css" rel="stylesheet" type="text/css"/>
    <script src="libraries/bootstrap-plugins/bootstrap-colorpicker-master/dist/js/bootstrap-colorpicker.min.js" type="text/javascript"></script>

    <link href="libraries/bootstrap-plugins/export/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <script src="libraries/bootstrap-plugins/export/dataTables.buttons.min.js" type="text/javascript"></script>
    <script src="libraries/bootstrap-plugins/export/jszip.min.js" type="text/javascript"></script>
    <script src="libraries/bootstrap-plugins/export/vfs_fonts.js" type="text/javascript"></script>
    <script src="libraries/bootstrap-plugins/export/buttons.html5.min.js" type="text/javascript"></script>

    <script src="libraries/bootstrap-plugins/Buttons-1.2.4/js/buttons.bootstrap.min.js" type="text/javascript"></script>
    <link href="libraries/bootstrap-plugins/Buttons-1.2.4/css/buttons.bootstrap.min.css" rel="stylesheet" type="text/css"/>


    <link href="libraries/bootstrap-plugins/lightbox-master/dist/ekko-lightbox.css" rel="stylesheet" type="text/css"/>
    <script src="libraries/bootstrap-plugins/lightbox-master/dist/ekko-lightbox.min.js" type="text/javascript"></script>
    <script src="application/mvc/views/custom_libraries/javascripts/autocomplete.js?rnd=<?php echo self::generateRandomStringComplex(); ?>" type="text/javascript"></script>
    <script src="libraries/excelToJson/xlsx.core.min.js"></script>
    <script src="libraries/excelToJson/xls.core.min.js"></script>
    <script src="libraries/cleave.js-master/dist/cleave.min.js" type="text/javascript"></script>

    <script src="libraries/bootstrap-plugins/daterangepicker-master/moment.min.js" type="text/javascript"></script>
    <script src="libraries/bootstrap-plugins/daterangepicker-master/daterangepicker.js" type="text/javascript"></script>
    <link href="libraries/bootstrap-plugins/daterangepicker-master/daterangepicker.css" rel="stylesheet" type="text/css"/>
    <script src="application/mvc/views/custom_libraries/javascripts/jquery-confirm.min.js"
            type="text/javascript"></script>
    <link href="application/mvc/views/custom_libraries/css/jquery-confirm.min.css" rel="stylesheet" type="text/css" />


    <style type="text/css">

        .container,.panel {
            height:100%;
            width: 100%;
            padding: 0px !important;
            margin: 0px !important;
        }



        .table>tfoot>tr>th{
            font-size: 12px !important;
            background-color: #F3F3F3;
            padding: 2px;
            border: none !important;
        }

        .dataTables_filter { display: none; }

        .input-sm{
            height: 25px !important;
        }

        .dt-center{
            text-align: center !important;
        }

        .selected{
            background-color: #337ab7 !important;
            color: #ffffff !important;
        }

        .search_filter{
            width: 100% !important;
            color: #000;
        }

        ._readony{
            background-color: #f6f5f5;
            border-style: ridge !important;
        }


        ::-webkit-input-placeholder { /* Chrome/Opera/Safari */
            color: #337ab7;
            opacity: 0.9;
        }
        ::-moz-placeholder { /* Firefox 19+ */
            color: #337ab7;
            opacity: 0.9;
        }
        :-ms-input-placeholder { /* IE 10+ */
            color: #337ab7;
            opacity: 0.9;
        }
        :-moz-placeholder { /* Firefox 18- */
            color: #337ab7;
            opacity: 0.9;
        }

        /* enable absolute positioning */
        .inner-addon {
            position: relative;
        }

        /* style icon */
        .inner-addon .glyphicon {
            position: absolute;
            padding: 6px;
            pointer-events: none;
        }

        /* align icon */
        .left-addon .glyphicon  { left:  0px;}
        .right-addon .glyphicon { right: 0px;}


        .phone-input{
            margin-bottom:8px;
        }

        .confirmation {
            width: 160px;
        }

        .list-group-item{
            border: none !important;
        }

        div.toolbar h3{
            margin-top: 0px !important;
        }

        .dataTable>tfoot>tr>th{
            /* width: 50px !important; */
        }

        .item_icon{
            font-size: 18px;
        }
        .addon_item_icon input{
            padding-left: 30px !important;
        }

        #receive_stockModal .modal-dialog{
            width: 99% !important;
            max-height: 550px;
        }

        #receive_stockModal .modal-footer{
            padding-top: 5px !important;
            padding-bottom: 5px !important;
        }

        #receive_stockModal .modal-header{
            padding-top: 5px !important;
            padding-bottom: 5px !important;
        }

        #receive_stockModal input{
            padding-top: 0px !important;
            padding-bottom: 0px !important;
        }
        #receive_stockModal .form-control{
            height: 28px !important;
            padding-left: 2px !important;
            padding-right: 2px !important;
            border-radius: 0px !important;
        }



        #receive_stockModal .bootstrap-select>.dropdown-toggle{
            padding-left: 2px !important;
            padding-right: 4px !important;
        }

        #receive_stockModal .bootstrap-select.btn-group .dropdown-toggle .caret{
            right: 2px !important;
        }

        #receive_stockModal .btn{
            border-radius: 0px !important;
        }

        .currency_label{
            font-size: 12px !important;
        }

        #receive_stockModal button{
            padding-bottom: 2px !important;
        }


        #po_modal .modal-body{
            padding-top: 5px !important;
            padding-bottom: 5px !important;
        }


        #receive_stockModal button{

        }

        #stock_invoices button{
            width: 100% !important;
        }

        .stock_entry_header{
            overflow-y: scroll;
            margin-top: 5px;
        }

        .stock_entry_header div{
            padding-left: 0px !important;
            padding-right: 0px !important;
            border: none !important;
            background-color: #337ab7;
            color: #ffffff;
            border-right: 1px solid #fff;
            font-size: 17px;
        }

        .stock_entry div{
            padding-left: 1px !important;
            padding-right: 1px !important;
            font-size: 16px;
        }

        .stock_entry:hover{
            background-color: #ccc;
        }

        .stock_entry input{
            height: 23px;
            font-size: 15px;
            width: 100%;
            text-align: left;
            margin-top: 1px !important;
            margin-bottom: 1px !important;
        }

        select{
            height: 23px;
            font-size: 14px;
            width: 100%;
            margin-top: 1px !important;
            margin-bottom: 1px !important;
        }

        /* add padding  */


        .modal-body .row{

        }

        .plr2{
            padding-left: 1px !important;
            padding-right: 1px !important;
        }

        .modal-body{
            padding: 18px !important;
            padding-top: 5px !important;
        }

        #items_body{
            max-height: 330px !important;
            overflow-y: scroll;
        }

        #exTab1 .tab-content {
            background-color: transparent;
            padding : 15px 15px;
            border-bottom: 1px solid #CCC;
            border-left: 1px solid #CCC;
            border-right: 1px solid #CCC;
            border-top: 1px solid #CCC;
        }

        #exTab1 .nav-pills > li > a {
            border-radius: 4px 4px 0 0;
            border: 1px solid #CCC;
            border-bottom: 0px solid #CCC;
            padding-top: 5px;
            padding-bottom: 5px;
        }

        /* add padding  */
        .left-addon input  { padding-left:  5px; }
        .right-addon input { padding-right: 5px; }

        .trash_icon{
            font-size: 18px;
            cursor: pointer;
        }

        .form-group{
            margin-bottom: 3px !important;
        }

        .btn {
            font-size: 13px !important;
        }

        ul.typeahead.dropdown-menu {
            max-height: 300px;
            overflow: auto;
        }

        label{
            margin-bottom: 0px !important;
            font-size: 15px !important;
        }

        .bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn){
            width: 100% !important;
        }

        .pi_info_box{
            background-color: #8a8a8a;
            padding-top: 5px;
            color: #fff;
            height: 35px;
            font-size: 16px;
            border-style: ridge !important;
            padding-left: 5px !important;
        }

        .tot_bcg{
            background-color: antiquewhite;
            border-style: ridge !important;
        }

        /* align icon */
        .left-addon .glyphicon  { left:  0px;}
        .right-addon .glyphicon { right: 0px;}

        .tooltip-inner{
            padding: 0px !important;
        }

        .selected .glyphicon-trash{
            color: #fff !important;
        }

        .panel > .panel-heading{
            padding: 1px 6px  !important;
        }

    </style>

    <script type="text/javascript">
        var all_items = [];
        var hide_critical_data=<?php echo $_SESSION["hide_critical_data"]; ?>;

        var main_currency = '<?php echo $_SESSION["currency_symbol"]; ?>';
        var only_1_currency=0;
        if(main_currency!="USD" && main_currency!="LBP"){
            only_1_currency=1;
        }

        var hide_cost=<?php echo $_SESSION["hide_critical_data"]; ?>;


        var mobile_shop = 0;
        <?php

        if ($data["mobile_shop"] == 1) {

        ?>
        mobile_shop = 1;
        <?php

        }

        ?>

        var current_pi_id = 0;

        var omt_version = <?php echo OMT_VERSION; ?>;

        var Total_more = 0;
        var Total_more_not_applied_on_items_cost = 0;

        var all_items_index = 0;
        var all_items_index_array = [all_items_index];

        var all_warehouses = [];
        var all_stores = [];
        var all_suppliers = [];
        var all_currencies = [];
        var payment_status = [];
        var payment_methods = [];

        var currency_system_name = "";

        var default_currency_symbol = null;
        var vatValue = null;
        var apply_vat_sales_item = null;
        var receive_stock_data = [];


        var suppliers = [];
        var current_supplier_id = null;

        var  ptype_hide = ";display:block;";
        <?php

        if ($_SESSION["ptype"] == 1) {

        ?>
        ptype_hide = ";display:none;";
        <?php

        }

        ?>

        var enable_wholasale = <?php echo $data["enable_wholasale"]; ?>;

        function calculate_pi_more_per_item(){
            var total_items_qty = 0;
            var average = 0;

            $(".qqty").each(function( i ) {
                total_items_qty+= parseFloat($(this).val());
            });

            if(total_items_qty>0)
                average = Total_more/total_items_qty;

            //alert(total_items_qty);
            $(".more_val").val(parseFloat(average).toFixed(get_decimal_to_fix($("#currency_id").val())));
        }

        function calculate_invoice_more_not_applied(){
            Total_more_not_applied_on_items_cost = 0;
            $.getJSON("?r=stock&f=get_pi_more_data&p0="+current_pi_id, function (data) {
                $.each(data, function (key, val) {
                    if(val.type_id==1 && val.apply_to_pi==0){
                        Total_more_not_applied_on_items_cost-=parseFloat(val.value);
                    }
                    if(val.type_id==2 && val.apply_to_pi==0){
                        Total_more_not_applied_on_items_cost+=parseFloat(val.value);
                    }
                });
            }).done(function () {
                //alert(Total_more_not_applied_on_items_cost);
                total_invoice_values();
            });
        }

        function show_pi_logs(id){
            var content =
                '<div class="modal large" data-backdrop="static" id="pi_logs_modal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
                    <div class="modal-dialog" role="document">\n\
                        <form id="transfer_form" action="" method="post" enctype="multipart/form-data" >\n\
                            <div class="modal-content">\n\
                                <div class="modal-header">\n\
                                    <h3 class="modal-title">Logs<i style="float:right;font-size:30px; cursor:pointer" class="glyphicon glyphicon-remove" onclick="close_pilog_modal()"></i></h3>\n\
                                </div>\n\
                                <div class="modal-body">\n\
                                    <div class="row">\n\
                                        <div class="col-lg-12 col-md-12 col-xs-12">\n\
                                            <table id="log_pi_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                                <thead>\n\
                                                    <tr>\n\
                                                        <th style="width:90px;">Creation Date</th>\n\
                                                        <th style="width:70px;">Changed By</th>\n\
                                                        <th>Item</th>\n\
                                                        <th>Description</th>\n\
                                                    </tr>\n\
                                                </thead>\n\
                                                <tfoot>\n\
                                                    <tr>\n\
                                                        <th>Creation Date</th>\n\
                                                        <th>Changed By</th>\n\
                                                        <th>Item</th>\n\
                                                        <th>Descriptionth>\n\
                                                    </tr>\n\
                                                </tfoot>\n\
                                                <tbody></tbody>\n\
                                            </table>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                        </form>\n\
                    </div>\n\
                </div>';
            $("#pi_logs_modal").modal("hide");
            $("body").append(content);

            $("#pi_logs_modal").centerWH();

            $('#pi_logs_modal').on('show.bs.modal', function (e) {
            });

            $('#pi_logs_modal').on('shown.bs.modal', function (e) {
                var search_fields = [0,1,2,3];
                var index = 0;
                $('#log_pi_table tfoot th').each( function () {
                    if(jQuery.inArray(index, search_fields) !== -1){
                        var title = $(this).text();
                        $(this).html( '<div class="inner-addon left-addon"><input id="idf_'+index+'" style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder="'+title+'" /></div>' );
                        index++;
                    }
                });

                $('#log_pi_table').dataTable({
                    ajax: "?r=stock&f=get_log_pi&p0="+id,
                    responsive: true,
                    orderCellsTop: true,
                    bLengthChange: true,
                    iDisplayLength: 100,
                    aoColumnDefs: [
                        { "targets": [0], "searchable": true, "orderable": true, "visible": true },
                        { "targets": [1], "searchable": true, "orderable": true, "visible": true },
                        { "targets": [2], "searchable": true, "orderable": true, "visible": true },
                        { "targets": [3], "searchable": true, "orderable": true, "visible": true },
                    ],
                    scrollY: '45vh',
                    scrollCollapse: true,
                    paging: true,
                    order: [[ 0, "asc" ]],
                    dom: '<"toolbar_log_pi">frtip',
                    initComplete: function( settings ) {
                    },
                    fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                        $(nRow).addClass(aData[0]);
                    },
                    fnDrawCallback: function(){

                    },
                });

                $('#log_pi_table tbody').on( 'mouseenter', 'tr', function () {
                    $(".selected").removeClass("selected");
                    $(this).addClass("selected");
                } );


                $('#log_pi_table').DataTable().columns().every( function () {
                    var that = this;
                    $('input', this.footer()).on( 'keyup change', function () {
                        if ( that.search() !== this.value ) {
                            that.search( this.value ).draw();
                        }
                    } );
                } );
            });

            $('#pi_logs_modal').on('hide.bs.modal', function (e) {
                $('#pi_logs_modal').remove();
            });


            $('#pi_logs_modal').modal('show');
        }

        function close_pilog_modal(){
            $('#pi_logs_modal').modal('hide');
        }

        function init(){
            $(".sk-circle").center();
            $(".sk-circle-layer").show();
            $.getJSON("?r=settings_info&f=get_needed_data", function (data) {
                default_currency_symbol = data.default_currency_symbol;
                vatValue = data.vat;
                apply_vat_sales_item = data.apply_vat_sales_item;

                all_suppliers = [];
                $.each(data.suppliers, function (key, val) {
                    if(current_supplier_id==null){
                        current_supplier_id = 0;
                    }
                    all_suppliers.push({id:val.id,name:val.name});
                });

                payment_status = [];
                $.each(data.payment_status, function (key, val) {
                    payment_status.push({id:val.id,name:val.status_name});
                });

                all_warehouses = [];
                $.each(data.warehouses, function (key, val) {
                    all_warehouses.push({'id':val.id,'location':val.location});
                });

                all_items = [];
                $.each(data.all_items, function (key, val) {
                    all_items.push({'id':val.id,'description':val.description,'barcode':val.barcode,'unit_cost':val.buying_cost,'vat':val.vat});
                });

                all_stores = [];
                $.each(data.stores, function (key, val) {
                    all_stores.push({id:val.id,name:val.name});
                });

                all_currencies = [];
                $.each(data.currencies, function (key, val) {
                    if(val.system_default==1){
                        currency_system_name = val.symbole;
                    }
                    all_currencies.push({id:val.id,name:val.name,symbole:val.symbole,system_default:val.system_default,rate_to_system_default:val.rate_to_system_default,pi_decimal:val.pi_decimal});
                });
            }).done(function () {
                getStockInvoices();
            });
        }

        function get_decimal_format(currency_id){
            for(var i=0;i<all_currencies.length;i++){
                if(currency_id == all_currencies[i].id){
                    if(all_currencies[i].pi_decimal==0){
                        return "#,##0";
                    }else if(all_currencies[i].pi_decimal==1){
                        return "#,##0.0";
                    }else if(all_currencies[i].pi_decimal==2){
                        return "#,##0.00";
                    }else if(all_currencies[i].pi_decimal==3){
                        return "#,##0.000";
                    }
                }
            }
        }

        function get_decimal_format_nb(currency_id){
            for(var i=0;i<all_currencies.length;i++){
                if(currency_id == all_currencies[i].id){
                    if(all_currencies[i].pi_decimal==0){
                        return 0;
                    }else if(all_currencies[i].pi_decimal==1){
                        return 1;
                    }else if(all_currencies[i].pi_decimal==2){
                        return 2;
                    }else if(all_currencies[i].pi_decimal==3){
                        return 3;
                    }
                }
            }
        }

        function get_decimal_to_fix(currency_id){
            for(var i=0;i<all_currencies.length;i++){
                if(currency_id == all_currencies[i].id){
                    return all_currencies[i].pi_decimal;
                }
            }
        }


        $(document).ready(function () {
            init();
            /*
            $(".sk-circle").center();
            $(".sk-circle-layer").show();
            $.getJSON("?r=settings_info&f=getSettingsInfo", function (data) {
                default_currency_symbol = data.default_currency_symbol;
                vatValue = data.vat;
            }).done(function () {
                $.getJSON("?r=suppliers&f=get_suppliers", function (data) {
                    $.each(data, function (key, val) {
                        if(current_supplier_id==null){
                            current_supplier_id = 0;
                        }
                        suppliers.push({id:val.id,name:val.name});
                    });
                }).done(function () {
                    init();
                });
            });
            */
        });

        //function init(){
        //get_payment_status();
        //}

        function getAllReceiveStockData(){
            receive_stock_data = [];
            for(var i=0;i<all_items_index_array.length;i++){
                if($("#row_"+all_items_index_array[i]).length>0){

                    var _qty = $("#qty_"+all_items_index_array[i]).val().replace(/,/g , '');
                    if(_qty=="" || _qty==null)
                        _qty = 0;

                    var _fqty = $("#fqty_"+all_items_index_array[i]).val().replace(/,/g , '');
                    if(_fqty=="" || _fqty==null)
                        _fqty = 0;

                    var _expdt= $("#expiry_hidden_"+all_items_index_array[i]).val();
                    if(_expdt=="" || _expdt==null)
                        _expdt = 0;

                    var _unit_cost = $("#uc_"+all_items_index_array[i]).val().replace(/,/g , '');
                    if(_unit_cost=="" || _unit_cost==null)
                        _unit_cost = 0;
                    else
                        _unit_cost = $("#uc_"+all_items_index_array[i]).val().replace(/,/g , '');

                    var _unit_discount = $("#disc_"+all_items_index_array[i]).val();
                    if(_unit_discount=="" || _unit_discount==null)
                        _unit_discount = 0;
                    else
                        _unit_discount = $("#disc_"+all_items_index_array[i]).val();

                    var _unit_discount_2 = $("#disc2_"+all_items_index_array[i]).val();
                    if(_unit_discount_2=="" || _unit_discount_2==null)
                        _unit_discount_2 = 0;
                    else
                        _unit_discount_2 = $("#disc2_"+all_items_index_array[i]).val();


                    var _unit_discount_after_vat = $("#discAfterVat_"+all_items_index_array[i]).val();
                    if(_unit_discount_after_vat=="" || _unit_discount_after_vat==null)
                        _unit_discount_after_vat = 0;
                    else
                        _unit_discount_after_vat = $("#discAfterVat_"+all_items_index_array[i]).val();


                    var _vat = 0;
                    if($("#vat_"+all_items_index_array[i]).is(':checked')){
                        _vat=1;
                    }
                    var _supplier_item_ref = $("#supref_"+all_items_index_array[i]).val();

                    var _print_barcode = $("#print_bar_"+all_items_index_array[i]).val();

                    var _charge = $("#charge_"+all_items_index_array[i]).val();


                    var _final_unit_cost = $("#unit_cost_after_discount_and_vat_"+all_items_index_array[i]).val().replace(/,/g , '');

                    receive_stock_data.push({item_id:$("#item_id_"+all_items_index_array[i]).val(),store_id:$("#store_id_"+all_items_index_array[i]).val(),qty:_qty,fqty:_fqty,unit_cost:_unit_cost,index:all_items_index_array[i],vat:_vat,supplier_item_ref:_supplier_item_ref,unit_discount:_unit_discount,unit_discount_2:_unit_discount_2,new_item:0,print_barcode:_print_barcode,final_unit_cost:_final_unit_cost,discount_after_vat:_unit_discount_after_vat,expiry_date:_expdt,charge:_charge});

                }

                if($("#nrow_"+all_items_index_array[i]).length>0){
                    var _qty = $("#qty_"+all_items_index_array[i]).val().replace(/,/g , '');
                    if(_qty=="" || _qty==null)
                        _qty = 0;

                    var _fqty = $("#fqty_"+all_items_index_array[i]).val().replace(/,/g , '');
                    if(_fqty=="" || _fqty==null)
                        _fqty = 0;



                    var _expdt = $("#expiry_hidden_"+all_items_index_array[i]).val();
                    if(_expdt=="" || _expdt==null)
                        _expdt = 0;



                    var _unit_cost = $("#uc_"+all_items_index_array[i]).val().replace(/,/g , '');
                    if(_unit_cost=="" || _unit_cost==null)
                        _unit_cost = 0;
                    else
                        _unit_cost = $("#uc_"+all_items_index_array[i]).val().replace(/,/g , '');

                    var _unit_discount = $("#disc_"+all_items_index_array[i]).val();
                    if(_unit_discount=="" || _unit_discount==null)
                        _unit_discount = 0;
                    else
                        _unit_discount = $("#disc_"+all_items_index_array[i]).val();

                    var _unit_discount_2 = $("#disc2_"+all_items_index_array[i]).val();
                    if(_unit_discount_2=="" || _unit_discount_2==null)
                        _unit_discount_2 = 0;
                    else
                        _unit_discount_2 = $("#disc2_"+all_items_index_array[i]).val();


                    var _unit_discount_after_vat = $("#discAfterVat_"+all_items_index_array[i]).val();
                    if(_unit_discount_after_vat=="" || _unit_discount_after_vat==null)
                        _unit_discount_after_vat = 0;
                    else
                        _unit_discount_after_vat = $("#discAfterVat_"+all_items_index_array[i]).val();


                    var _vat = 0;
                    if($("#vat_"+all_items_index_array[i]).is(':checked')){
                        _vat=1;
                    }
                    var _supplier_item_ref = $("#supref_"+all_items_index_array[i]).val();

                    var _print_barcode = $("#print_bar_"+all_items_index_array[i]).val();

                    var _final_unit_cost = $("#unit_cost_after_discount_and_vat_"+all_items_index_array[i]).val().replace(/,/g , '');
                    var _charge = $("#charge_"+all_items_index_array[i]).val();

                    receive_stock_data.push({item_id:$("#item_id_"+all_items_index_array[i]).val(),store_id:$("#store_id_"+all_items_index_array[i]).val(),qty:_qty,fqty:_fqty,unit_cost:_unit_cost,index:all_items_index_array[i],vat:_vat,supplier_item_ref:_supplier_item_ref,unit_discount:_unit_discount,unit_discount_2:_unit_discount_2,new_item:1,print_barcode:_print_barcode,final_unit_cost:_final_unit_cost,discount_after_vat:_unit_discount_after_vat,expiry_date:_expdt,charge:_charge});

                }
            }
            return receive_stock_data;
        }

        function getAllReceiveStock(close_modal){
            if($("#supplier_id").val()==0){
                swal("Supplier not selected");
                return;
            }

            //if($("#invoice_reference").val()==""){
            //swal("PI number is empty");
            //return;
            //}

            var tmp_action_type = $("#action_type").val();
            if($("#receive_stock_btn").hasClass("disabled")==false && $("#items_body div.row").length>0){
                $(".sk-circle-layer").show();
                if(close_modal==1){
                    $("#receive_stock_btn").addClass("disabled");
                }

                receive_stock_data = getAllReceiveStockData();

                $.ajax({
                    type: 'POST',
                    url: '?r=stock&f=receive_stock_data',
                    dataType: 'json',
                    data: {'items': receive_stock_data,'supplier_id':$("#supplier_id").val(),'invoice_date':$("#invoice_date").val(),'delivery_date':$("#delivery_date").val(),'invoice_subtotal':$("#invoice_subtotal").val().replace(/,/g , ''),'invoice_discount':$("#invoice_discount").val().replace(/,/g , ''),'invoice_total':$("#invoice_total").val().replace(/,/g , ''),'invoice_tax':$("#invoice_tax").val().replace(/,/g , ''),'action_type':$("#action_type").val(),'payment_status':$("#payment_status").val(),'invoice_reference':$("#invoice_reference").val(),'autofill_id':$("#autofill_id").val(),'charge_type_id':$("#charge_type_id").val(),'currency_id':$("#currency_id").val(),'cur_rate':$("#cur_rate").val().replace(/,/g , '')},
                    success: function(msg) {
                        if(close_modal==1){
                            var p_ttl = $("#invoice_total").val();
                            var p_cur_id = $("#currency_id").val();

                            $('.tooltip_exp').tooltip('destroy');

                            $('#receive_stockModal').modal('toggle');
                            var table = $('#stock_invoices').DataTable();

                            table.ajax.url("?r=stock&f=getStockInvoices&p0="+$("#suppliers_list").val()+"&p1="+$("#payment_status_list").val()+"&p2="+$("#piDate").val()).load(function () {
                                if(tmp_action_type==0){
                                    table.page('first').draw(false);
                                    table.row(':first', {page: 'current'}).select();
                                }else{
                                    table.row('.' + PadSTKINV(msg), {page: 'current'}).select();
                                }
                                update_pi_info();

                                $(".sk-circle-layer").hide();

                                if(NEW_PI_GEN==1){
                                    /*add_supplier_payment('stock_invoice');
                                    setTimeout(function(){
                                        $("#payment_value").val(p_ttl);
                                        $("#payment_currency").selectpicker('val',p_cur_id);
                                    },1000);*/
                                }

                            },false);


                        }else{
                            $('.tooltip_exp').tooltip('destroy');
                        }
                    },
                }).fail(function() {
                    $(".sk-circle-layer").hide();
                    $("#receive_stock_btn").removeClass("disabled");
                    swal("Saving Error, please check your connection");
                }).always(function() {
                });
            }else{
                if($("#items_body div.row").length==0){
                    swal("Empty Invoice");
                }
            }
        }

        function updateSuppliers(){
            all_suppliers = [];
            $.getJSON("?r=suppliers&f=get_suppliers", function (data) {
                $.each(data, function (key, val) {
                    all_suppliers.push({id:val.id,name:val.name});
                });
            }).done(function () {

            });
        }

        function updateAllItems(item_id){
            $(".sk-circle").center();
            $(".sk-circle-layer").show();
            all_items = [];
            $.getJSON("?r=items&f=getAllItems_", function (data) {
                $.each(data, function (key, val) {
                    all_items.push({'id':val.id,'description':val.description,'barcode':val.barcode,'unit_cost':val.buying_cost,'vat':val.vat});
                });
            }).done(function () {

                $.get("?r=items&f=get_items_names_without_boxes", function(data){
                    var search_by_name_typehead = $('#search_by_name').typeahead();
                    search_by_name_typehead.data('typeahead').source = data;

                },'json')
                    .done(function(){
                        $('#newItem').modal('hide');
                        $(".sk-circle-layer").hide();
                        if(item_id>0){
                            var ind = addItemToInvoice(item_id,[]);
                            //updateOtherInfo(ind);
                        }
                    })
                    .fail(function() {
                    })
                    .always(function() {
                    });

            });
        }


        function getAllItems(){
            $(".sk-circle").center();
            $(".sk-circle-layer").show();
            all_items = [];
            $.getJSON("?r=items&f=getAllItems_", function (data) {
                $.each(data, function (key, val) {
                    all_items.push({'id':val.id,'description':val.description,'barcode':val.barcode,'unit_cost':val.buying_cost,vat:val.vat});
                });
            }).done(function () {

                getStockInvoices();
            });
        }

        function closeReceiveStock(moved_stock){
            //alert(moved_stock);
            if(moved_stock==0){
                if($("#items_body div.row").length>0){
                    swal({
                            title: "Are you sure?",
                            text: "All changes will be discarded",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonClass: "btn-danger",
                            confirmButtonText: "Yes",
                            closeOnConfirm: true,
                            cancelButtonText: "Cancel",
                        },
                        function(isConfirm){
                            if(isConfirm){
                                $('#receive_stockModal').modal('toggle');
                            }
                        });
                }else{
                    $('#receive_stockModal').modal('toggle');
                }
            }else{
                $('#receive_stockModal').modal('toggle');
            }
        }

        function getOptionOfItemsId(item_id){
            var options="";
            for(var i=0;i<all_items.length;i++){
                if(item_id==all_items[i].id){
                    options+="<option value='"+all_items[i].id+"' title='"+all_items[i].id+"'>"+all_items[i].id+"</option>";
                }
            }
            return options;
        }

        function getOptionsOfPaymentStatus(){
            var options = "";
            for(var i=0;i<payment_status.length;i++){
                options+="<option value='"+payment_status[i].id+"' title='"+payment_status[i].name+"'>"+payment_status[i].name+"</option>";
            }
            return options;
        }

        function getOptionsSuppliers(){
            var options = "<option value='0'>Select Supplier</option>";
            for(var i=0;i<all_suppliers.length;i++){
                options+="<option value='"+all_suppliers[i].id+"' title='"+all_suppliers[i].name+"'>"+all_suppliers[i].name+"</option>";
            }
            return options;
        }

        function getOptionsCurrencies(){
            var options = "";
            var selected= "";
            for(var i=0;i<all_currencies.length;i++){
                selected= "";
                if(all_currencies[i].system_default==1){
                    selected= "selected";
                }
                options+="<option "+selected+" value='"+all_currencies[i].id+"' title='"+all_currencies[i].name+" ("+all_currencies[i].symbole+")'>"+all_currencies[i].name+" ("+all_currencies[i].symbole+")</option>";
            }
            return options;
        }

        function getOptionsOfStores(){
            var options = "";
            for(var i=0;i<all_stores.length;i++){
                options+="<option value='"+all_stores[i].id+"' title='"+all_stores[i].name+"'>"+all_stores[i].name+"</option>";
            }
            return options;
        }

        function getOptionsOfWarehouses(){
            var options = "";
            for(var i=0;i<all_warehouses.length;i++){
                options+="<option value='"+all_warehouses[i].id+"' title='"+all_warehouses[i].location+"'>"+all_warehouses[i].location+"</option>";
            }
            return options;
        }

        function updateOtherInfo(index_row,item_id){

            $.getJSON("?r=items&f=get_item_by_id__&p0="+item_id, function (data) {
                $("#description_"+index_row).val(data[0].description);
                if(data[0].vat==1){
                    $("#vat_"+index_row).attr('checked', true);
                }
            }).done(function () {

            });
        }

        function deleteItem(index){

            $.confirm({
                title: 'Remove item from PI?',
                content: '',
                animation: 'zoom',
                closeAnimation: 'zoom',
                animateFromElement:false,
                buttons: {
                    REMOVE: {
                        btnClass: 'btn-danger',
                        action: function(){
                            if($("#items_body div.row").length>0){
                                if($("#action_type").val()==0){
                                    $('#ic_exp_'+index).tooltip("destroy");
                                    $("#row_"+index).remove();


                                    total_invoice_values();
                                }else{
                                    if($("#nrow_"+index).length>0){
                                        $('#ic_exp_'+index).tooltip('destroy');
                                        $("#nrow_"+index).remove();


                                        total_invoice_values();
                                    }else{
                                        $.getJSON("?r=stock&f=delete_item_from_invoice_order&p0="+index, function (data) {

                                        }).done(function () {
                                            $('#ic_exp_'+index).tooltip('destroy');
                                            $("#row_"+index).remove();

                                            total_invoice_values();
                                        });
                                    }


                                }
                            }
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

        function define_new_items(ac){
            if(!$("#receive_stockModal button").hasClass("disabled")){
                addItem(ac);
            }
        }



        function total_invoice_values(){

            if($("#autofill_id").val()=="1"){
                var total = 0;
                var discount_value = 0;
                var discount_value_2 = 0;
                var tmp = 0;
                var vat_value = 0;

                var total_discount_after_vat = 0;

                var dataItems = getAllReceiveStockData();
                for(var i=0;i<dataItems.length;i++){
                    total+=(dataItems[i].qty*dataItems[i].unit_cost);
                    //alert(dataItems[i].qty*dataItems[i].unit_cost*(dataItems[i].unit_discount/100));
                    discount_value+=(dataItems[i].qty*dataItems[i].unit_cost*(dataItems[i].unit_discount/100));
                    discount_value_2+=((dataItems[i].qty*dataItems[i].unit_cost-discount_value)*(dataItems[i].unit_discount_2/100));

                    //discount_value_2+=(discount_value*(dataItems[i].unit_discount_2/100));

                    if(dataItems[i].vat ==1){
                        if(dataItems[i].unit_discount>0){
                            tmp=((dataItems[i].qty*dataItems[i].unit_cost-dataItems[i].qty*dataItems[i].unit_cost*(dataItems[i].unit_discount/100))*(vatValue-1));
                            vat_value+=tmp;
                        }else{
                            tmp=(dataItems[i].qty*dataItems[i].unit_cost*(vatValue-1));
                            vat_value+=tmp;
                        }
                    }

                    //alert(((dataItems[i].qty*dataItems[i].unit_cost-discount_value-discount_value_2+vat_value)*(dataItems[i].discount_after_vat/100)));
                    if(dataItems[i].discount_after_vat>0){
                        total_discount_after_vat+=((dataItems[i].qty*dataItems[i].unit_cost-discount_value-discount_value_2+vat_value)*(dataItems[i].discount_after_vat/100));
                    }

                }


                $("#invoice_subtotal").val(total);

                $("#invoice_discount").val(0);

                $("#invoice_tax").val(vat_value);

                // HEREEEEEEEE
                var tmptot = 0;
                var tmp_id =0;
                $(".total_sum").each(function( index ) {

                    tmp_id = $(this).attr("id").split("_");

                    tmptot+=parseFloat($(this).val().replace(/,/g , '')*$("#qty_"+tmp_id[6]).val().replace(/,/g , ''));
                });
                tmptot+=Total_more_not_applied_on_items_cost;

                $("#invoice_total").val(tmptot);

                $(".mask_format").trigger('input');
                cleaves_class(".cleavesf",3);
            }

        }



        function calculate_unit_cost(id){
            $("#uc_"+id).val($("#tc_"+id).cleanVal()/$("#qty_"+id).val().replace(/,/g , ''));
            total_invoice_values();
            update_total_cost_after_disc_vat();
        }

        function calculate_total_cost(id){
            $("#tc_"+id).val($("#uc_"+id).cleanVal()*$("#qty_"+id).val().replace(/,/g , ''));
            $(".mask_format").trigger('input');
            cleaves_class(".cleavesf",3);
        }

        function update_total_cost_after_disc_vat(id){


            if ($('#vat_'+id).is(':checked')) {
                if(apply_vat_sales_item==1){
                    var t = $("#uc_"+id).val().replace(/,/g , '');
                    t = t - (t*parseFloat($("#disc_"+id).val()/100));
                    t = t*vatValue;
                    t = t + $("#more_"+id).val().replace(/,/g , '');
                    $("#unit_cost_after_discount_and_vat_"+id).val(t);
                    calculate_total_cost(id);
                }else{
                    $("#uc_"+id).val(($("#uc_"+id).val().replace(/,/g , '')*vatValue).toFixed(2));
                    $("#uc_"+id).trigger('input');

                    var t = $("#uc_"+id).val().replace(/,/g , '');
                    t = t - (t*parseFloat($("#disc_"+id).val()/100));
                    t = t + $("#more_"+id).val().replace(/,/g , '');
                    $("#unit_cost_after_discount_and_vat_"+id).val(t);
                    calculate_total_cost(id);
                }
            }else{
            }
            $(".mask_format").trigger('input');
            cleaves_class(".cleavesf",3);
        }

        var calculate_tmout = null;
        function calculate(id){
            if($("#auty_pb_qty").is(':checked')){
                $("#print_bar_"+id).val($("#qty_"+id).val().replace(/,/g , ''));
            }

            $("#tc_"+id).val((parseFloat($("#uc_"+id).val().replace(/,/g , '')))*parseFloat($("#qty_"+id).val().replace(/,/g , '')));
            var t = $("#uc_"+id).val().replace(/,/g , '');
            t = t*(1-parseFloat($("#disc_"+id).val()/100));

            t = t*(1-parseFloat($("#disc2_"+id).val()/100));

            if ($('#vat_'+id).is(':checked')) {

                t = t*vatValue;
            }

            if ($('#discAfterVat_'+id).val()!="" && $('#discAfterVat_'+id).val()>0) {
                t = t - (t*parseFloat($('#discAfterVat_'+id).val()/100));
            }

            calculate_pi_more_per_item(id);

            t = t + parseFloat($("#more_"+id).val());

            var charge=parseFloat($("#charge_"+id).val());
            if (isNaN(charge)) {
                charge=0;
            }

            if($('#charge_type_id').val()==1){
                t = t + parseFloat(charge);
            }else{
                t = t + t*parseFloat(charge)/100;
            }



            $("#unit_cost_after_discount_and_vat_"+id).val(t);

            //$(".mask_format").trigger('input');
            window.clearTimeout(calculate_tmout);
            calculate_tmout = setTimeout(function(){
                total_invoice_values();
            },50);

        }

        function addItemToInvoice(item_id,data){
            var latest_cost = 0;
            var free_qty = 0;
            var disc_1 = 0;
            var latest_discount = 0;
            var latest_discount_2 = 0;

            var vat = 0;
            var old_index = null;
            $.getJSON("?r=items&f=get_latest_cost_of_item&p0="+item_id, function (data) {
                if(data.length>0){
                    latest_cost = data[0].cost;
                    latest_discount = parseFloat(data[0].discount_percentage);
                    latest_discount_2 = parseFloat(data[0].discount_percentage_2);
                }
            }).done(function () {
                var items = '';
                old_index = all_items_index;

                var tmp_qty = 0;
                var unit_cost = 0;
                if(data.length>0){
                    tmp_qty = data[0].qty;
                    latest_cost = data[0].unit_cost;

                    free_qty=data[0].free_qty;
                    vat=data[0].vat;

                    disc_1=data[0].discount;
                    if(disc_1>0){
                        latest_discount=disc_1;
                    }

                }



                items+='<div class="row stock_entry item_id_'+all_items_index+' check_uniq_'+item_id+'" id="nrow_'+all_items_index+'">\n\
                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">\n\
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3" style="text-align:center"><i title="Edit" class="glyphicon glyphicon-edit" style="cursor:pointer;font-size:15px;margin-top:5px;" onclick="edit_item_o('+item_id+')"></i></div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="text-align:center"><i title="Duplicate" class="glyphicon glyphicon-duplicate" style="cursor:pointer;font-size:15px;margin-top:5px;" onclick="editItem(\'IT-'+item_id+'\',1)"></i></div>\n\
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3" style="text-align:center"><input type="hidden" name="expiry_hidden_'+all_items_index+'" id="expiry_hidden_'+all_items_index+'" value="" /><i id="ic_exp_'+all_items_index+'" class="glyphicon glyphicon-time tooltip_exp" style="cursor:pointer;font-size:15px;margin-top:5px;" onclick="show_expiry('+item_id+')" data-container="body" data-container="body" data-toggle="tooltip_expiry_'+item_id+'" data-placement="top"></i></div>\n\
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3 itcodes" style="text-align:center"><i onclick="create_unique_items_for_item('+item_id+', 0)" title="Item Codes" class="glyphicon glyphicon-equalizer iconpic "></i></div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="display:none"><select style="width:100%" id="item_id_'+all_items_index+'" onchange="updateOtherInfo('+all_items_index+')"><option value="'+item_id+'" title="'+item_id+'">'+item_id+'</option></select></div>\n\
                        </div>\n\
                        <div style="display:none" class="col-lg-1 col-md-1 col-sm-1 col-xs-1"><input type="text" value="" id="supref_'+all_items_index+'" /></div>\n\
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" id="desc_'+all_items_index+'"><div class="col-lg-10 col-md-10 col-sm-10 col-xs-10"><input disabled="disabled" type="text" value="" id="description_'+all_items_index+'" /></div><div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"></div></div>\n\
                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"><div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><input  type="text" oninput="calculate('+all_items_index+')" name="qty_'+all_items_index+'" id="qty_'+all_items_index+'" value="'+tmp_qty+'" class="qqty cleavesf" /></div><div class="col-lg-5 col-md-5 col-sm-5 col-xs-5"><input type="text" name="fqty_'+all_items_index+'" id="fqty_'+all_items_index+'" value="'+free_qty+'" class="qqty cleavesf" /></div></div>\n\
                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"><input class="cleavesf tot_bcg" type="text" onkeyup="calculate('+all_items_index+')" name="uc_'+all_items_index+'" id="uc_'+all_items_index+'" value="'+latest_cost+'" /></div>\n\
                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"><input class="cleavesf _readony tot_bcg" readonly type="text" name="tc_'+all_items_index+'" id="tc_'+all_items_index+'" value="0" /></div>\n\
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">\n\
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"><input type="number" oninput="calculate('+all_items_index+')" name="disc_'+all_items_index+'" min="0" max="100" id="disc_'+all_items_index+'" value="'+latest_discount+'" /></div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="display:none"><input type="number" oninput="calculate('+all_items_index+')" name="disc2_'+all_items_index+'" min="0" max="100" id="disc2_'+all_items_index+'" value="'+latest_discount_2+'" /></div>\n\
                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1" style="text-align:center"><input type="checkbox" onchange="calculate('+all_items_index+')" name="vat" id="vat_'+all_items_index+'" style="height:21px;"></div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="text-align:center"><input type="number" oninput="calculate('+all_items_index+')" min="0" max="100" name="discAfterVat_'+all_items_index+'" id="discAfterVat_'+all_items_index+'" value="0" class="disavat" /></div>\n\
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="text-align:center"><input readonly="readonly" type="text" oninput="" id="more_'+all_items_index+'" value="0" style="width:100%" class="more_val" /></div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"><input type="number" oninput="calculate('+all_items_index+')" name="charge_'+all_items_index+'" id="charge_'+all_items_index+'" value="0" /></div>\n\                            \n\
                        </div>\n\
                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"><input readonly="readonly" type="text" class="cleavesf tot_bcg _readony total_sum" name="unit_cost_after_discount_and_vat_'+all_items_index+'" id="unit_cost_after_discount_and_vat_'+all_items_index+'" value="0" /></div>\n\
                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2" style="text-align:center">\n\
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8" style="text-align:center"><input type="number" value="0" class="print_bar" id="print_bar_'+all_items_index+'" /></div>\n\
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="text-align:center"><i class="glyphicon glyphicon-trash trash_icon" onclick="deleteItem('+all_items_index+')" style="margin-top:2px;"></i></div>\n\
                        </div>\n\
                    </div>';
                $("#items_body").append(items);

                if(vat==1){
                    $("#vat_"+all_items_index).prop("checked", true);
                }

                if(mobile_shop==0){
                    $(".itcodes").hide();
                }

                if(hide_cost==1){
                    $('#uc_'+all_items_index).hide();
                    $('#unit_cost_after_discount_and_vat_'+all_items_index).hide();
                    $('#tc_'+all_items_index).hide();
                }

                //$("#qty_"+all_items_index).numeric();
                $("#disc_"+all_items_index).numeric();
                $("#discAfterVat_"+all_items_index).numeric();

                cleaves_class(".cleavesf",3);

                calculate(all_items_index);

                $('#items_body').scrollTop($('#items_body')[0].scrollHeight);

                all_items_index++;
                all_items_index_array.push(all_items_index);


                if(hide_cost==1){
                    $('#uc_'+all_items_index).hide();
                    $('#unit_cost_after_discount_and_vat_'+all_items_index).hide();
                    $('#tc_'+all_items_index).hide();
                }


                updateOtherInfo(old_index,item_id);

                return old_index;
            });
        }


        function edit_item_o(id){
            editItem("00-"+id,0);
        }

        function addItemToInvoiceToUpdate(infoStock){
            var tmp_expiry_date = "";
            if(infoStock.expiry_date=="0000-00-00"){
                tmp_expiry_date="";
            }else{
                tmp_expiry_date=infoStock.expiry_date;
            }
            var items = '';
            items+='<div class="row stock_entry check_uniq_'+infoStock.item_id+'" id="row_'+infoStock.id+'">\n\
                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">\n\
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3" style="text-align:center"><i title="Edit" class="glyphicon glyphicon-edit" style="cursor:pointer;font-size:15px;margin-top:5px;" onclick="edit_item_o('+infoStock.item_id+')"></i></div>\n\
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="text-align:center"><i title="Duplicate" class="glyphicon glyphicon-duplicate" style="cursor:pointer;font-size:15px;margin-top:5px;" onclick="editItem(\'IT-'+infoStock.item_id+'\',1)"></i></div>\n\
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3" style="text-align:center"><input type="hidden" name="expiry_hidden_'+infoStock.id+'" id="expiry_hidden_'+infoStock.id+'" value="'+tmp_expiry_date+'" /><i id="ic_exp_'+infoStock.id+'" class="glyphicon glyphicon-time tooltip_exp" style="cursor:pointer;font-size:15px;margin-top:5px;" onclick="show_expiry('+infoStock.id+')" data-container="body" data-toggle="tooltip_expiry_'+infoStock.id+'" data-placement="top"></i></div>\n\
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3 itcodes" style="text-align:center"><i onclick="create_unique_items_for_item('+infoStock.item_id+', 0)" title="Item Codes" class="glyphicon glyphicon-equalizer iconpic"></i></div>\n\
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="display:none"><select style="width:100%" id="item_id_'+infoStock.id+'" onchange="updateOtherInfo('+infoStock.id+')"><option value="'+infoStock.item_id+'" title="'+infoStock.item_id+'">'+infoStock.item_id+'</option></select></div>\n\
                    </div>\n\
                    <div style="display:none" class="col-lg-1 col-md-1 col-sm-1 col-xs-1"><input type="text" value="" id="supref_'+infoStock.id+'" /></div>\n\
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"><div class="col-lg-10 col-md-10 col-sm-10 col-xs-10"  id="desc_'+infoStock.id+'"></div><div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 returned_debit">'+infoStock.returned_debit+'</div></div>\n\
                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"><div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><input type="text" oninput="calculate('+infoStock.id+')" name="qty_'+infoStock.id+'" id="qty_'+infoStock.id+'" value="0" class="qqty cleavesf" /></div><div class="col-lg-5 col-md-5 col-sm-5 col-xs-5"><input type="text" oninput="" name="" id="fqty_'+infoStock.id+'" value="0" class="qqty cleavesf" /></div></div>\n\
                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"><input class="cleavesf tot_bcg" type="text" onkeyup="calculate('+infoStock.id+')" name="uc_'+infoStock.id+'" id="uc_'+infoStock.id+'" value="0" /></div>\n\
                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"><input class="cleavesf tot_bcg _readony" type="text" readonly name="tc_'+infoStock.id+'" id="tc_'+infoStock.id+'" value="0" /></div>\n\
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">\n\
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"><input type="number" oninput="calculate('+infoStock.id+')" name="disc_'+infoStock.id+'" min="0" max="100" id="disc_'+infoStock.id+'" value="0" /></div>\n\
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="display:none"><input type="number" oninput="calculate('+infoStock.id+')" name="disc2_'+infoStock.id+'" min="0" max="100" id="disc2_'+infoStock.id+'" value="0" /></div>\n\
                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1" style="text-align:center"><input type="checkbox" onchange="calculate('+infoStock.id+')" name="vat" id="vat_'+infoStock.id+'" style="height:21px;"></div>\n\
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="text-align:center"><input type="number" oninput="calculate('+infoStock.id+')" min="0" max="100" name="discAfterVat_'+infoStock.id+'" id="discAfterVat_'+infoStock.id+'" value="0" class="disavat" /></div>\n\
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="text-align:center"><input readonly="readonly" type="text" oninput="" id="more_'+infoStock.id+'" value="0" style="width:100%" class="more_val" /></div>\n\
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"><input type="number" oninput="calculate('+infoStock.id+')" name="charge_'+infoStock.id+'" id="charge_'+infoStock.id+'" value="0" /></div>\n\
                    </div>\n\
                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"><input readonly="readonly" class="cleavesf tot_bcg _readony total_sum" type="text" name="unit_cost_after_discount_and_vat_'+infoStock.id+'" id="unit_cost_after_discount_and_vat_'+infoStock.id+'" value="0" /></div>\n\
                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2" style="text-align:center">\n\
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8" style="text-align:center"><input type="number" value="0" class="print_bar" id="print_bar_'+infoStock.id+'" /></div>\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="text-align:center;"><i class="glyphicon glyphicon-trash trash_icon del_c" onclick="deleteItem('+infoStock.id+')" style="margin-top:2px;"></i></div>\n\
                    </div>\n\
                </div>';
            $("#items_body").append(items);
            //$("#qty_"+infoStock.id).numeric();
            //$("#disc_"+all_items_index).numeric();
            //$("#discAfterVat_"+all_items_index).numeric();


            if(mobile_shop==0){
                $(".itcodes").hide();
            }




            $('#items_body').scrollTop($('#items_body')[0].scrollHeight);

            $('#desc_'+infoStock.id).html(infoStock.description);

            $('#item_id_'+infoStock.id).val(infoStock.item_id);
            $('#qty_'+infoStock.id).val(infoStock.qty);
            $('#fqty_'+infoStock.id).val(infoStock.fqty);


            $(".mask_format").mask(get_decimal_format($("#currency_id").val()), {reverse: true});
            cleaves_class(".cleavesf",3);


            $('#uc_'+infoStock.id).val(parseFloat(infoStock.cost));

            if(hide_cost==1){
                $('#uc_'+infoStock.id).hide();
                $('#unit_cost_after_discount_and_vat_'+infoStock.id).hide();
                $('#tc_'+infoStock.id).hide();
            }



            $('#disc_'+infoStock.id).val(parseFloat(infoStock.discount));
            $('#disc2_'+infoStock.id).val(parseFloat(infoStock.discount_2));
            $('#charge_'+infoStock.id).val(parseFloat(infoStock.charge));

            $('#discAfterVat_'+infoStock.id).val(parseFloat(infoStock.discount_av));

            $('#supref_'+infoStock.id).val(infoStock.supplier_item_ref);

            $('#store_id_'+infoStock.id).val(infoStock.store_id);

            $("#vat_"+infoStock.id).click(function() {

            });

            if(infoStock.vat==1){
                $('#vat_'+infoStock.id).prop( "checked", true );
            }

            calculate(infoStock.id);

            all_items_index_array.push(infoStock.id);
        }

        function autofill(){
            if($("#autofill_id").val()=="0"){
                $("#invoice_subtotal").prop('readonly', false);
                $("#invoice_discount").prop('readonly', false);
                $("#invoice_tax").prop('readonly', false);
                $("#invoice_total").prop('readonly', false);
            }else{
                total_invoice_values();
                $("#invoice_subtotal").prop('readonly', true);
                $("#invoice_discount").prop('readonly', true);
                $("#invoice_tax").prop('readonly', true);
                $("#invoice_total").prop('readonly', true);

            }
        }

        function submit_upload(){
            $("#pi_picture_form").submit();
        }

        function expiry_changed(id){
            $("#expiry_hidden_"+id).val($("#pcker_tooltip_"+id).val());
        }

        function show_expiry(id){
            $('[data-toggle="tooltip_expiry_'+id+'"]').attr('data-original-title', "<input onchange='expiry_changed("+id+")' class='expiry_tooltip' name='' id='pcker_tooltip_"+id+"' type='text' />").tooltip({trigger: 'manual',html:true}).tooltip('toggle');
            $('[data-toggle="tooltip_expiry_'+id+'"]').on('shown.bs.tooltip', function () {
                $("#pcker_tooltip_"+id).datepicker({autoclose:true,format: 'yyyy-mm-dd'});
                if($("#expiry_hidden_"+id).val().length>0){
                    $("#pcker_tooltip_"+id).datepicker("setDate" , $("#expiry_hidden_"+id).val());
                }
            });
        }


        function delete_picture_pi(id){
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
                        $.getJSON("?r=stock&f=delete_picture_pi&p0="+id, function (data) {
                            var upload = '\n\
                                <form id="pi_picture_form" action="" method="post" enctype="multipart/form-data" >\n\
                                <div class="form-group" >\n\
                                    <label for="mof">PI Picture</label>\n\
                                    <input type="hidden" value="'+id+'" class="form-control"  id="pi_id_for_pic" name="pi_id_for_pic">\n\
                                    <input onchange="submit_upload()" accept=".png,.jpg,.jpeg" type="file" value="" class="form-control"  id="pi_picture" name="pi_picture">\n\
                                </div>\n\
                            </form>';
                            $("#pic_upload").empty();
                            $("#pic_upload").append(upload);
                            update_pi_picture_submit();
                        }).done(function () {
                            $(".sk-circle-layer").hide();
                        });
                    }
                });
        }

        function import_items_of_supplier(){
            if($("#supplier_id").val()==0){
                swal("Select Supplier First");
            }else{
                $(".sk-circle-layer").show();
                $.getJSON("?r=items&f=import_items_of_supplier&p0="+$("#supplier_id").val(), function (data) {
                    $.each(data, function (key, val) {
                        if($(".check_uniq_"+val.item_id).length==0){
                            addItemToInvoice(val.item_id,[]);
                        }
                    });
                }).done(function () {
                    $(".sk-circle-layer").hide();
                });
            }
        }

        function add_discount_after_vat(){
            swal({
                    title: "Enter Discount Value",
                    html: true ,
                    text: '<input class="form-control" value="" type="text" id="pass"/>',
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Ok",
                    cancelButtonText: "Cancel",
                    closeOnConfirm: true,
                    closeOnCancel: true
                },
                function(isConfirm) {
                    if (isConfirm) {
                        $( ".disavat" ).each(function( index ) {
                            $("#discAfterVat_"+$(this).attr("id").split('_')[1]).val($("#pass").val());
                            calculate($(this).attr("id").split('_')[1]);
                        });

                    }
                });
            setTimeout(function(){ $("#pass").numeric();$("#pass").focus(); },200);
        }


        function update_currency_label_to(){
            for(var i=0;i<all_currencies.length;i++){
                if(all_currencies[i].id!=$("#currency_id").val() ){
                    //$("#pi_to_cur").html(all_currencies[i].symbole);
                }
            }
        }

        function update_currency_pi(pi_id){

            if($("#currency_id").val()!=1 && $("#currency_id").val()!=2){
                for(var i=0;i<all_currencies.length;i++){
                    if(all_currencies[i].id==$("#currency_id").val()){
                        $("#pi_to_cur").html(all_currencies[i].symbole);
                    }
                }
            }else{
                $("#pi_to_cur").html("LBP");
            }

            $(".sk-circle-layer").show();
            $.getJSON("?r=stock&f=update_currency_pi&p0="+pi_id+"&p1="+$("#currency_id").val(), function (data) {

                $("#cur_rate").val(data);
            }).done(function () {
                $(".mask_format").mask(get_decimal_format($("#currency_id").val()), {reverse: true});
                cleaves_class(".cleavesf",3);
                $(".sk-circle-layer").hide();
            });
        }

        function receive_stock_modal(action_type,btn_action_name,info,infoStock){
            var btn_disabled = "";
            var input_read_only = "";
            if(action_type>0){
                //btn_disabled="disabled";
                //input_read_only="readonly";
            }

            var hide_save = "display:block;";
            if(info.moved_to_stock==1){
                hide_save = "display:none;";
            }


            if(info["deleted"]==1){
                hide_save = "display:none;";
            }

            var upload = "\n\
                    <div class='row'>\n\
                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>\n\
                            <div class='row form-group'>\n\
                                <label for='delivery_date'>PI Picture <a href='"+info.pi_pic_path+"' data-toggle='lightbox' id='open-image' data-title='&nbsp;' data-footer='&nbsp;'>Show</a></label>\n\
                                <button id='delete_pi_pic' onclick='delete_picture_pi("+action_type+")' type='button' class='btn btn-danger' style='width:100%'>Delete</button>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                ";
            if(info.pi_pic_exist==0){
                upload = '\n\
                        <form id="pi_picture_form" action="" method="post" enctype="multipart/form-data" >\n\
                        <div class="form-group" >\n\
                            <label for="mof">PI Picture</label>\n\
                            <input type="hidden" value="'+action_type+'" class="form-control"  id="pi_id_for_pic" name="pi_id_for_pic">\n\
                            <input onchange="submit_upload()" accept=".png,.jpg,.jpeg" type="file" value="" class="form-control"  id="pi_picture" name="pi_picture">\n\
                        </div>\n\
                    </form>';
            }
            var content =
                '<div class="modal" data-keyboard="false" data-backdrop="static" id="receive_stockModal" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
                    <div class="modal-dialog" role="document">\n\
                        <div class="modal-content">\n\
                            <div class="modal-header"> \n\
                                <h3 class="modal-title"><i class="glyphicon glyphicon-plus"></i>Purchase Invoice</h3> <input type="file" class="form-control-file" id="excelfile" onchange="ReadExcelSheetPI()">\n\
                            </div>\n\
                            <div class="modal-body">\n\
                                <input id="action_type" value="'+action_type+'" name="action_type" type="hidden" />\n\
                                <div class="row">\n\
                                    <div class="col-lg-2 col-md-6 col-sm-6 col-xs-6 plr2">\n\
                                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">\n\
                                            <div class="row form-group">\n\
                                                <label for="supplier_id">Suppliers</label>&nbsp;<span class="linkadd" onclick="addSupplier(\'receive_stock\')">Add New</span>\n\
                                                <select data-live-search="true" id="supplier_id" class="form-control selectpicker" data-size="10" onchange="supplier_changed()">'+getOptionsSuppliers()+'</select>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div style="'+hide_save+'" class="col-lg-5 col-md-5 col-sm-5 col-xs-5">\n\
                                            <div class="row form-group plr2">\n\
                                                <label for="supplier_id">&nbsp;</label>\n\
                                                <button type="button" class="btn '+btn_disabled+'" onclick="import_items_of_supplier()" style="width:100%; font-size:16px !important;padding:3px;"><i class="glyphicon glyphicon-plus"></i>&nbsp;Import</button>\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 plr2" style="margin-bottom:1px;">\n\
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">\n\
                                            <div class="row form-group plr2">\n\
                                                <label for="invoice_reference">PI number</label>\n\
                                                <input id="invoice_reference" value="" name="invoice_reference" type="text" class="form-control"/>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">\n\
                                            <div class="row form-group plr2">\n\
                                                <label for="invoice_date">PI Date</label>\n\
                                                <input id="invoice_date" value="" name="invoice_date" type="text" class="form-control datepicker"/>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">\n\
                                            <div class="row form-group plr2">\n\
                                                <label for="delivery_date">Received</label>\n\
                                                <input id="delivery_date" value="" name="delivery_date" type="text" class="form-control datepicker"/>\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 plr2" id="ri_rate" style="display:none" >\n\
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"><div class="row form-group plr2">\n\
                                            <label for="currency_id">Currency</label>\n\
                                            <select onchange="update_currency_pi('+action_type+')" id="currency_id" class="form-control selectpicker" data-size="10" onchange="currency_pi_changed()">'+getOptionsCurrencies()+'</select>\n\
                                        </div></div>\n\
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 pl2 pr2">\n\
                                            <div class="form-group" style="width:100%">\n\
                                                <label for="inv_rate" style="width:100%">Rate</label>\n\
                                                <div class="input-group" style="width:100%">\n\
                                                    <span class="input-group-addon" style="width:40px;"><b id="pi_from_cur">1 USD </b>= </span>\n\
                                                        <input type="text" class="form-control cleavesf" name="cur_rate" id="cur_rate" value="1500" placeholder="" style="padding-left:5px;padding-right:5px;font-weight:bold;" />\n\
                                                    <span class="input-group-addon" style="width:40px;"><b id="pi_to_cur">LBP</b></span>\n\
                                                </div>\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 plr2" id="pic_upload">'+upload+'</div>\n\
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">\n\
                                        <div class="form-group">\n\
                                            <label for="delivery_date">&nbsp;</label>\n\
                                            <button type="button" class="btn" onclick="show_pi_logs('+action_type+')" style="width:100%; font-size:16px !important;padding:3px;">Show Logs</button>\n\
                                        </div>\n\
                                    </div>\n\
                               </div>\n\
                               <div class="row">\n\
                                    <div style="'+hide_save+'" class="col-lg-3 col-md-8 col-sm-8 col-xs-8">\n\
                                        <div class="row">\n\
                                         <label for="autofill_id">Search Item to add</label>\n\
                                            <input '+input_read_only+' type="text" class="form-control input-md" id="search_by_name" style="height:29px;" placeholder="Search Name - Barcode - Id - SKU" />\n\
                                        </div>\n\
                                    </div>\n\
                                    <div style="'+hide_save+'" class="col-lg-2 col-md-4 col-sm-4 col-xs-4">\n\
                                        <div class="row">\n\
                                            <label for="autofill_id">&nbsp;</label>\n\
                                            <button type="button" class="btn '+btn_disabled+'" onclick="define_new_items(\'receive_stock\')" style="width:100%; font-size:16px !important;padding:3px;"><i class="glyphicon glyphicon-plus"></i>&nbsp;Define New Item</button>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-1 col-md-2 col-sm-2 col-xs-2">\n\
                                        <div class="row form-group plr2">\n\
                                                <label for="autofill_id">Autofill</label>\n\
                                                <select id="autofill_id" class="form-control selectpicker" data-size="10" onchange="autofill()"><option value="0">Disable</option><option value="1" selected>Enable</option></select>\n\
                                            </div>\n\
                                    </div>\n\
                                    <div class="col-lg-1 col-md-2 col-sm-2 col-xs-2">\n\
                                        <div class="row form-group plr2">\n\
                                                <label for="charge_type_id">Charge Type</label>\n\
                                                <select id="charge_type_id" class="form-control selectpicker" data-size="10" onchange=""><option value="1">Amount</option><option value="2">Percentage</option></select>\n\
                                            </div>\n\
                                    </div>\n\
                                    <div class="col-lg-5 col-md-10 col-sm-10 col-xs-10" style="padding-left:0px;">\n\
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">\n\
                                            <div class="row form-group plr2">\n\
                                                <label for="invoice_cost">Subtotal&nbsp;</label><label class="currency_label">('+default_currency_symbol+')</label></label>\n\
                                                <input id="invoice_subtotal" value="0" name="invoice_subtotal" type="text" class="form-control tot_bcg cleavesf" style="font-weight:bold;" />\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="display:none">\n\
                                            <div class="row form-group plr2">\n\
                                                <label for="invoice_discount">Discount&nbsp;</label><label class="currency_label">('+default_currency_symbol+')</label>\n\
                                                <input id="invoice_discount" value="0" name="invoice_discount" type="text" class="form-control tot_bcg cleavesf" style="font-weight:bold;"/>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">\n\
                                            <div class="row form-group plr2">\n\
                                                <label for="invoice_tax">Total VAT&nbsp;</label><label class="currency_label">('+default_currency_symbol+')</label></label>\n\
                                                <input id="invoice_tax" value="0" name="invoice_tax" type="text" class="form-control tot_bcg cleavesf" style="font-weight:bold;" />\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">\n\
                                            <div class="row form-group plr2">\n\
                                                <label for="invoice_discount">Total&nbsp;</label><label class="currency_label">('+default_currency_symbol+')</label></label>\n\
                                                <input id="invoice_total" value="0" name="invoice_total" type="text" class="form-control tot_bcg cleavesf" style="font-weight:bold;" />\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">\n\
                                           <div class="row">\n\
                                                <label for="autofill_id">&nbsp;</label>\n\
                                                <button type="button" class="btn" onclick="pi_more('+action_type+')" style="width:100%; font-size:16px !important;padding:3px;">On Invoice</button>\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="row">\n\
                                </div>\n\
                                <div class="row stock_entry_header">\n\
                                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">Item</div>\n\
                                    <div style="display:none" class="col-lg-1 col-md-1 col-sm-1 col-xs-1">SKU</div>\n\
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"><div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">Description</div><div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">Rtn.</div></div>\n\
                                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">\n\
                                        \n\
                                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">Qty</div>\n\
                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">Free</div>\n\
                                    </div>\n\
                                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">U. Cost</div>\n\
                                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">T. Cost</div>\n\
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">\n\
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="text-align:left">Disc 1 %</div>\n\
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="text-align:center;display:none">Disc 2 %</div>\n\
                                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1" style="text-align:left">VAT</div>\n\
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="text-align:left">Disc. %</div>\n\
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="text-align:left;">More</div>\n\
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="text-align:left">Charge</div>\n\
                                    </div>\n\
                                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">F. U. Cost</div>\n\
                                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">\n\
                                        <div class="col-lg-6 col-md-6 col-sm-1 col-xs-1">P.B.&nbsp;<input onchange="auto_qty_changed()" title="Auto Qty" type="checkbox" name="vat" id="auty_pb_qty"></div>\n\
                                        <div class="col-lg-6 col-md-6 col-sm-1 col-xs-1">&nbsp;</div>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="row">\n\
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="items_body">\n\
                                    </div>\n\
                                </div>\n\
                                <div class="row" style="margin-top:5px;display:none;">\n\
                                    <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11">\n\
                                        <div class="row">\n\
                                            &nbsp;\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">\n\
                                        <div class="row">\n\
                                            <button type="button" class="btn" onclick="add_discount_after_vat()" style="width:100%; font-size:16px !important;padding:3px;">Disc. After VAT</button>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="modal-footer">\n\
                                <div class="row">\n\
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">\n\
                                    </div>\n\
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">\n\
                                    </div>\n\
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">\n\
                                    </div>\n\
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">\n\
                                    </div>\n\
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">\n\
                                        <button type="button" class="btn btn-primary"  style="width:100%; font-size:22px !important;padding:0px;'+hide_save+'" id="receive_stock_btn" onclick="getAllReceiveStock(1)">'+btn_action_name+'</button>\n\
                                    </div>\n\
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">\n\
                                        <button type="button" class="btn btn-danger"  style="width:100%; font-size:22px !important;padding:0px;" onclick="closeReceiveStock('+info.moved_to_stock+')">Close</button>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                </div>';
            $("#receive_stockModal").remove();
            $("body").append(content);

            $("#receive_stockModal").centerWH();

            $(".selectpicker").selectpicker({style: 'btn-default'});
            if(info["supplier_id"]!=null){
                $('#supplier_id').selectpicker('val', info["supplier_id"]);
            }


            $('.datepicker').datepicker({autoclose:true,format: 'yyyy-mm-dd'});

            if(info["receive_invoice_date"]!=null){
                $('#invoice_date').datepicker( "setDate", info["receive_invoice_date"]);
            }else{
                $('#invoice_date').datepicker( "setDate", new Date());
            }
            if(info["delivery_date"]!=null){
                $('#delivery_date').datepicker( "setDate", info["delivery_date"]);
            }else{
                $('#delivery_date').datepicker( "setDate", new Date());
            }

            if(info["invoice_reference"]!=null){
                $('#invoice_reference').val(info["invoice_reference"]);
            }


            $('#currency_id').selectpicker('val', info["currency_id"]);
            update_currency_label_to();




            var cur_to_round=1/info["cur_rate"];

            //alert(info["cur_rate"]);
            //("#cur_rate").val( (Math.round(cur_to_round * 1000) / 1000) );
            $("#cur_rate").val(info["cur_rate"]);



            $(".only_numeric").numeric({ negative : false});

            $('.datepicker').datepicker().on('changeDate', function(ev) {

            }).on('hide show', function(event) {
                event.preventDefault();
                event.stopPropagation();
            });



            $('#receive_stockModal').on('shown.bs.modal', function (e) {

                $('#open-image').click(function (e) {
                    e.preventDefault();
                    $(this).ekkoLightbox();
                });

                update_pi_picture_submit();


                if(info["moved_to_stock"]==1){
                    $("#receive_stockModal input").attr("disabled","disabled");
                    $("#receive_stockModal select").prop('disabled', 'disabled');
                    $("#delete_pi_pic").prop('disabled', 'disabled');
                }

                if(only_1_currency==1){
                    $("#ri_rate").hide();
                }


            });

            $('#receive_stockModal').on('show.bs.modal', function (e) {

                $(".mask_format").mask(get_decimal_format($("#currency_id").val()), {reverse: true});
                cleaves_class(".cleavesf",3);

                $("#autofill_id").val(info["auto_filled"]);
                $("#autofill_id").selectpicker('refresh');

                $("#charge_type_id").val(info["charge_type"]);
                $("#charge_type_id").selectpicker('refresh');


                if(info["auto_filled"]=="1"){
                    $("#invoice_subtotal").prop('readonly', true);
                    $("#invoice_discount").prop('readonly', true);
                    $("#invoice_tax").prop('readonly', true);
                    $("#invoice_total").prop('readonly', true);
                }


                //$('#cur_rate').val(info["cur_rate"]);

                for(var i=0;i<infoStock.length;i++){
                    addItemToInvoiceToUpdate(infoStock[i]);
                }

                if(info["moved_to_stock"]==1){
                    $(".del_c").hide();
                }

                type_ahead("?r=items&f=get_items_names_without_boxes","search_by_name");

                setTimeout(function(){
                    calculate_pi_more_per_item();
                },500);

                if(info["auto_filled"]==1){

                    total_invoice_values();
                }else{
                    //$("#invoice_subtotal").val(info["subtotal"]);
                    //$("#invoice_discount").val(info["discount"]);
                    //alert(get_decimal_to_fix($("#currency_id").val()));

                    $("#invoice_total").val(parseFloat(info["total"]));
                    $("#invoice_subtotal").val(parseFloat(info["subtotal"]));

                    $("#invoice_discount").val(parseFloat(info["discount"]));
                    $("#invoice_tax").val(parseFloat(info["invoice_tax"]));
                    //$("#invoice_total").val(info["total"]);

                    //$("#invoice_tax").val(info["invoice_tax"]);
                    $(".mask_format").trigger('input');
                    cleaves_class(".cleavesf",3);
                }


            });
            $('#receive_stockModal').on('hide.bs.modal', function (e) {
                $('.tooltip_exp').tooltip('destroy');
                $('#receive_stockModal').remove();

                all_items_index = 0;
                all_items_index_array = [all_items_index];
            });
            $('#receive_stockModal').modal('show');
        }

        function auto_qty_changed(){
            if($("#auty_pb_qty").is(':checked')){
                $(".print_bar").each(function( index ) {
                    var print_b = $(this).attr("id").split("_")[2];
                    $(this).val($("#qty_"+print_b).val());
                });
            }else{
                $(".print_bar").each(function( index ) {
                    $(this).val(0);

                });
            }
        }

        function update_pi_picture_submit(){
            $("#pi_picture_form").on('submit', (function (e) {
                e.preventDefault();
                $.ajax({
                    url: "?r=stock&f=update_pi_picture",
                    type: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: 'json',
                    success: function (data)
                    {
                        swal("Picture Uploaded");
                    }
                });
            }));
        }

        function currency_pi_changed(){
            for(var i=0;i<all_currencies.length;i++){
                if($("#currency_id").val()==all_currencies[i]["id"]){
                    if(all_currencies[i].system_default==1){
                        $("#cur_rate").val("1");
                        //$("#cur_rate").prop('readonly', true);
                    }else{

                        $("#cur_rate").val(all_currencies[i]["rate_to_system_default"]);
                        //$("#cur_rate").prop('readonly', false);
                    }
                    $(".currency_label").html("("+all_currencies[i]["symbole"]+")");
                    $(".mask_format").mask(get_decimal_format($("#currency_id").val()), {reverse: true});
                    cleaves_class(".cleavesf",3);
                    return;
                }
            }

        }

        function receive_stock(inv_id){
            var action_type = 0;
            var btn_action_name = "Add";
            var info =[];
            info["invoice_date"] = null;
            info["delivery_date"] = null;
            info["supplier_id"] = null;
            info["subtotal"] = null;

            info["discount"] = null;
            info["discount_av"] = null;
            info["total"] = null;
            //info["paid_status"] = null;
            info["invoice_tax"] = null;
            var infoStock = [];
            if(inv_id>0){
                action_type = inv_id;
                btn_action_name = "Save";

                $.getJSON("?r=stock&f=getStockInvoicesById&p0="+inv_id, function (data) {

                    Total_more = 0;
                    Total_more_not_applied_on_items_cost = 0;

                    current_pi_id = inv_id;




                    $.each(data.pi_more_data, function (key, val) {
                        if(val.type_id==1 && val.apply_to_pi==1){
                            Total_more-=parseFloat(val.value);
                        }
                        if(val.type_id==2 && val.apply_to_pi==1){
                            Total_more+=parseFloat(val.value);
                        }

                        if(val.type_id==1 && val.apply_to_pi==0){
                            Total_more_not_applied_on_items_cost-=parseFloat(val.value);
                        }
                        if(val.type_id==2 && val.apply_to_pi==0){
                            Total_more_not_applied_on_items_cost+=parseFloat(val.value);
                        }
                    });


                    $.each(data.invoice_info, function (key, val) {
                        info["receive_invoice_date"] = val.receive_invoice_date;
                        info["delivery_date"] = val.delivery_date;
                        info["supplier_id"] = val.supplier_id;
                        info["subtotal"] = val.subtotal;
                        info["invoice_reference"] = val.invoice_reference;
                        info["currency_id"] = val.currency_id;

                        vatValue = val.vat;

                        info["discount"] = val.discount;

                        info["total"] = val.total;
                        info["paid_status"] = val.paid_status;
                        info["invoice_tax"] = val.invoice_tax;
                        info["auto_filled"] = val.auto_filled;
                        info["charge_type"] = val.charge_type;


                        info["cur_rate"] = val.cur_rate;

                        info["pi_pic_exist"] = val.pi_pic_exist;
                        info["pi_pic_path"] = val.pi_pic_path;


                        info["moved_to_stock"] = val.moved_to_stock;

                        info["deleted"] = val.deleted;




                    });
                    $.each(data.invoice_items, function (key, val) {

                        infoStock.push({id:val.id,item_id:val.item_id,qty:parseFloat(val.qty),cost:val.cost,description:val.description,vat:val.vat,supplier_item_ref:val.supplier_ref,discount:val.discount_percentage,discount_2:val.discount_percentage_2,store_id:val.location_id,discount_av:val.discount_after_vat,returned_debit:parseFloat(val.returned_debit),fqty:parseFloat(val.fqty),expiry_date:val.expiry_date,charge:val.charge});
                    });
                }).done(function () {
                    $(".sk-circle-layer").hide();
                    receive_stock_modal(action_type,btn_action_name,info,infoStock);
                });
            }else{
                receive_stock_modal(action_type,btn_action_name,info,infoStock);
            }

        }



        function parseFloatOpts(num, decimal, thousands) {
            var bits = num.split(decimal, 2),
                ones = bits[0].replace(new RegExp('\\' + thousands, 'g'), '');
            ones = parseFloat(ones, 10);
            decimal = parseFloat('0.' + bits[1], 10);
            return ones + decimal;
        }

        function type_ahead(url,input_id){
            $(".sk-circle-layer").show();
            $.get(url, function(data){
                $(".sk-circle-layer").hide();
                var $input = $("#"+input_id);
                $input.typeahead({
                    source: data,
                    autoSelect: true,
                    fitToElement:false,
                    items:"all",
                    scrollHeight:0,
                    minLength:2
                });

                $input.change(function() {
                    var current = $input.typeahead("getActive");
                    if (current) {
                        //alert(current.name +"=="+ $input.val());
                        // Some item from your model is active!
                        if (current.name == $input.val()) {
                            //alert("HERE");
                            // This means the exact match is found. Use toLowerCase() if you want case insensitive match.
                            ///$("#customer_id").val(current.id);
                            setTimeout(function(){
                                //alert("sas");
                                //alert(current.id);
                                //alert($(".item_id_"+current.id).length);
                                if($(".check_uniq_"+current.id).length==0){
                                    //getAllReceiveStock(0);

                                    var ind = addItemToInvoice(current.id,[]);
                                    $("#search_by_name").val("");

                                    //updateOtherInfo(ind);
                                    //$("#item_id_"+ind).val(current.id);

                                }

                            },100);

                        }else{
                            ///$("#customer_id").val(0);
                            // This means it is only a partial match, you can either add a new item
                            // or take the active if you don't want new items
                        }
                    } else {
                        ///$("#customer_id").val(0);
                        // Nothing is active so it is a new value (or maybe empty value)
                    }
                });

            },'json')
                .done(function(){
                })
                .fail(function() {
                })
                .always(function() {
                });
        }

        function pi_more(pi_id){
            $(".sk-circle-layer").show();
            var option_more_type = '';
            $.getJSON("?r=stock&f=get_all_pi_para", function (data) {
                $.each(data, function (key, val) {
                    option_more_type += '<option value="'+val.id+'" title="'+val.description+'">'+val.description+'</option>'
                });
            }).done(function () {
                $(".sk-circle-layer").hide();
                var modal_name = "modal_pi_mote_modal__";
                var modal_title = "More";
                var content =
                    '<div class="modal large" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
                        <div class="modal-dialog" role="document">\n\
                            <div class="modal-content">\n\
                                <form id="pi_more_form" action="" method="post" enctype="multipart/form-data" >\n\
                                    <input id="id_to_edit" name="id_to_edit" value="'+pi_id+'" type="hidden" />\n\
                                    <input id="id_to_edit_more" name="id_to_edit_more" value="0" type="hidden" />\n\
                                    <div class="modal-header">\n\
                                        <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\''+modal_name+'\')"></i></h3>\n\
                                    </div>\n\
                                    <div class="modal-body">\n\
                                        <div class="row">\n\
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="padding-right:5px;">\n\
                                                <label for="pi_more_value">Value</label>\n\
                                                <input value="0" required name="pi_more_value" id="pi_more_value" class="col-md-2 form-control cleavesf" type="text" placeholder="" style="cursor:pointer">\n\
                                            </div>\n\
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="padding-left:5px;padding-right:5px;">\n\
                                                <label for="pi_more_value">Type</label>\n\
                                                <select name="pimore_type" id="selectpicker_pimore" class="form-control selectpicker" data-size="10">'+option_more_type+'</select>\n\
                                            </div>\n\
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="padding-left:5px;padding-right:5px;">\n\
                                                <label for="pi_more_value">Apply To final cost</label>\n\
                                                <select name="apply_to_items" id="apply_to_items" class="form-control selectpicker" data-size="10"><option selected value="0" title="No">No</option></select>\n\
                                            </div>\n\
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="padding-left:5px;padding-right:5px;">\n\
                                                <label for="pi_more_description">Note</label>\n\
                                                <input value="" required name="pi_more_description" id="pi_more_description" class="col-md-2 form-control" type="text" placeholder="">\n\
                                            </div>\n\
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="padding-left:5px;">\n\
                                                <label for="action_btn_">&nbsp;</label>\n\
                                                <button style="width:100%" id="action_btn_m" type="submit" class="btn btn-primary">Add</button>\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="modal-footer" style=" text-align: left;">\n\
                                        <div class="row">\n\
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                                <table style="width:100%" id="pi_more_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                                    <thead>\n\
                                                        <tr>\n\
                                                            <th style="width:50px;">ID</th>\n\
                                                            <th style="width:120px;">Type</th>\n\
                                                            <th style="width:120px;">Value</th>\n\
                                                            <th style="width:120px;">Apply To Cost</th>\n\
                                                            <th>Note</th>\n\
                                                            <th style="width:80px;"></th>\n\
                                                        </tr>\n\
                                                    </thead>\n\
                                                    <tbody></tbody>\n\
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

                submitMorePI(pi_id);

                $('#'+modal_name).on('show.bs.modal', function (e) {

                });

                $('#'+modal_name).on('shown.bs.modal', function (e) {
                    $('#selectpicker_pimore').selectpicker();
                    $('#apply_to_items').selectpicker();

                    //$(".mask_format").mask(get_decimal_format($("#currency_id").val()), {reverse: true});

                    //pi_more_value

                    cleaves_class(".cleavesf",3);
                    var table_name = "pi_more_table";
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
                            url: "?r=stock&f=get_pi_more&p0="+pi_id,
                            type: 'POST',
                            error:function(xhr,status,error) {
                            },
                        },
                        //order: [[1, 'asc']],
                        responsive: true,
                        orderCellsTop: true,
                        scrollX: true,
                        scrollY: "55vh",
                        iDisplayLength: 100,
                        aoColumnDefs: [
                            { "targets": [0], "searchable": false, "orderable": true,"visible": false },
                            { "targets": [1], "searchable": false, "orderable": true,"visible": true },
                            { "targets": [2], "searchable": false, "orderable": false,"visible": true },
                            { "targets": [3], "searchable": false, "orderable": false, "visible": true },
                            { "targets": [4], "searchable": false, "orderable": false, "visible": true },
                            { "targets": [5], "searchable": true, "orderable": false, "visible": true,"className": "dt-center"},
                        ],
                        scrollCollapse: true,
                        paging: true,
                        bPaginate: false,
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
                        fnDrawCallback: function(){

                            var table = $('#'+table_name).DataTable();
                            var p = table.rows({ page: 'current' }).nodes();
                            for (var k = 0; k < p.length; k++){
                                var index = table.row(p[k]).index();
                                table.cell(index,5).data('<i class="glyphicon glyphicon-edit" onclick="edit_pi_more(\''+parseInt(table.cell(index, 0).data())+'\','+pi_id+')" style="font-size:18px;cursor:pointer" ></i>&nbsp;<i class="glyphicon glyphicon-trash red" onclick="delete_pi_more(\''+parseInt(table.cell(index, 0).data())+'\','+pi_id+')" style="font-size:18px;cursor:pointer" ></i>');
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



        function edit_pi_more(id,pi_id){
            $(".sk-circle").center();
            $(".sk-circle-layer").show();
            $.getJSON("?r=stock&f=get_pi_more_by_id&p0="+id+"&p1="+pi_id, function (data) {
                $("#pi_more_value").val(data[0].value);
                $("#pi_more_value").trigger("input");

                $('#selectpicker_pimore').selectpicker('val', data[0].type_id);
                $('#selectpicker_pimore').selectpicker('refresh');

                $('#apply_to_items').selectpicker('val', data[0].apply_to_pi);
                $('#apply_to_items').selectpicker('refresh');

                $("#pi_more_description").val(data[0].note);

                $("#id_to_edit_more").val(id);


                $('#action_btn_m').html("Update");
            }).done(function () {
                $(".sk-circle-layer").hide();
            });
        }

        function delete_pi_more(id,pi_id){
            swal({
                    title: "Are you sure?",
                    text: "This item will be deleted",
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
                        $.getJSON("?r=stock&f=delete_pi_more&p0="+id, function (data) {

                        }).done(function () {
                            $('#pi_more_table').DataTable().ajax.url("?r=stock&f=get_pi_more&p0="+pi_id).load(function () {
                                $(".sk-circle-layer").hide();
                                calculate_invoice_more_not_applied();
                            }, false);
                        });
                    }
                });
        }

        function submitMorePI(pi_id) {
            $("#pi_more_form").on('submit', (function (e) {
                e.preventDefault();

                $(".sk-circle").center();
                $(".sk-circle-layer").show();
                $.ajax({
                    url: "?r=stock&f=add_more_pi",
                    type: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: 'json',
                    success: function (data)
                    {
                        $(".sk-circle-layer").hide();

                        $("#id_to_edit_more").val(0);

                        $("#pi_more_value").val(0);
                        $("#pi_more_value").trigger("input");

                        $("#pi_more_description").val("");

                        $('#action_btn_m').html("Add");

                        $('#pi_more_table').DataTable().ajax.url("?r=stock&f=get_pi_more&p0="+pi_id).load(function () {
                            calculate_invoice_more_not_applied();
                        }, false);
                    }
                });
            }));
        }

    </script>
</head>
<body>

<?php

include "application/mvc/views/topMenu.php";

?>
<div class="container" >
    <div class="panel panel-default">
        <div class="panel-body" style="margin-top: 40px;">
            <div class="row">
                <div class="col-lg-12" >
                    <table id="stock_invoices" class="table table-striped table-bordered " cellspacing="0">
                        <thead>
                        <tr>
                            <th style="width: 60px;">Invoice ID</th>
                            <th style="width: 90px;">Invoice Date</th>
                            <th >Supplier</th>
                            <th style="width: 100px;">Subtotal</th>
                            <th style="width: 80px;">Discount</th>
                            <th style="width: 100px;">Total VAT</th>
                            <th style="width: 100px;">Total</th>
                            <th style="width: 100px;">Total QTY</th>
                            <th style="width: 120px;">Sup. Invoice ref.</th>

                            <th style="width: 70px;">Rates</th>
                            <th style="width: 120px;"></th>
                            <th style="width: 35px;"></th>
                            <th style="width: 35px;"></th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>Invoice ID</th>
                            <th>Invoice Date</th>
                            <th>Supplier</th>
                            <th>Subtotal</th>
                            <th>Discount</th>
                            <th>Total VAT</th>
                            <th>Total</th>
                            <th>Total QTY</th>
                            <th>Sup. Invoice ref.</th>
                            <th>Rate</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </tfoot>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="sk-circle-layer">
    <div class="sk-circle">
        <div class="sk-circle1 sk-child"></div>
        <div class="sk-circle2 sk-child"></div>
        <div class="sk-circle3 sk-child"></div>
        <div class="sk-circle4 sk-child"></div>
        <div class="sk-circle5 sk-child"></div>
        <div class="sk-circle6 sk-child"></div>
        <div class="sk-circle7 sk-child"></div>
        <div class="sk-circle8 sk-child"></div>
        <div class="sk-circle9 sk-child"></div>
        <div class="sk-circle10 sk-child"></div>
        <div class="sk-circle11 sk-child"></div>
        <div class="sk-circle12 sk-child"></div>
    </div>
</div>
</body>
</html>

