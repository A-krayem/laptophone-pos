<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ PHP 5.6
 * @ Decoder version: 1.0.4
 * @ Release: 02/06/2020
 *
 * @ ZendGuard Decoder PHP 5.6
 */

// Decoded file for php version 53.
echo "<!DOCTYPE html>\r\n<!--\r\nTo change this license header, choose License Headers in Project Properties.\r\nTo change this template file, choose Tools | Templates\r\nand open the template in the editor.\r\n-->\r\n<html>\r\n    <head>\r\n        <title>";
echo $_SESSION["page_title"];
echo " Clients - Financial overview</title>\r\n        <meta charset=\"UTF-8\">\r\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n        <link rel=\"icon\" type=\"image/png\" href=\"resources/favicon.png\">\r\n        \r\n        <script src=\"libraries/jquery-3.1.1.min.js\" type=\"text/javascript\"></script>\r\n\r\n        <script src=\"libraries/bootstrap-3.3.7-dist/js/bootstrap.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-3.3.7-dist/css/bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        <script src=\"libraries/bootstrap-plugins/DataTables-1.10.13/media/js/jquery.dataTables.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-plugins/DataTables-1.10.13/media/js/dataTables.bootstrap.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/DataTables-1.10.13/media/css/dataTables.bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n\r\n        <link href=\"libraries/bootstrap-plugins/export/buttons.dataTables.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        <script src=\"libraries/bootstrap-plugins/export/dataTables.buttons.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-plugins/export/jszip.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-plugins/export/vfs_fonts.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-plugins/export/buttons.html5.min.js\" type=\"text/javascript\"></script>\r\n        \r\n        <script src=\"libraries/bootstrap-plugins/DataTables-1.10.13/extensions/Select/js/dataTables.select.min.js\" type=\"text/javascript\"></script>\r\n\r\n        \r\n        <script src=\"libraries/bootstrap-plugins/Buttons-1.2.4/js/buttons.bootstrap.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/Buttons-1.2.4/css/buttons.bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        \r\n        <script src=\"application/mvc/views/custom_libraries/javascripts/global_functions.js?rnd=";
echo self::generateRandomStringComplex();
echo "\" type=\"text/javascript\"></script>\r\n                <script src=\"application/mvc/views/custom_libraries/javascripts/global.js?rnd=";
echo self::generateRandomStringComplex();
echo "\" type=\"text/javascript\"></script>\r\n\r\n        <script src=\"libraries/bootstrap-plugins/bootstrap-select-master/bootstrap-select-master/dist/js/bootstrap-select.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/bootstrap-select-master/bootstrap-select-master/dist/css/bootstrap-select.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        <link href=\"application/mvc/views/custom_libraries/css/global.css?rnd=";
echo self::generateRandomStringComplex();
echo "\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        \r\n        <script src=\"libraries/bootstrap-plugins/libs/bootstrap-confirmation.min.js\" type=\"text/javascript\"></script>\r\n        \r\n        <script src=\"libraries/bootstrap-plugins/bootstrap-sweetalert-master/dist/sweetalert.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/bootstrap-sweetalert-master/dist/sweetalert.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        <script src=\"libraries/bootstrap-plugins/bootstrap3-typeahead/bootstrap3-typeahead.min.js\" type=\"text/javascript\"></script>\r\n\r\n       <script src=\"libraries/jquery.mask.min.js\" type=\"text/javascript\"></script>\r\n\r\n                \r\n        <link href=\"libraries/bootstrap-plugins/datepicker-master/dist/css/bootstrap-datepicker.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        <script src=\"libraries/bootstrap-plugins/datepicker-master/dist/js/bootstrap-datepicker.min.js\" type=\"text/javascript\"></script>\r\n        \r\n        \r\n        <link href=\"application/mvc/views/custom_libraries/svgs/font/style.css?rnd=";
echo self::generateRandomStringComplex();
echo "\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        <script src=\"application/mvc/views/custom_libraries/javascripts/autocomplete.js?rnd=";
echo self::generateRandomStringComplex();
echo "\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/numeric.js\" type=\"text/javascript\"></script>\r\n        <script src=\"application/mvc/views/custom_libraries/javascripts/stock_invoices.js\" type=\"text/javascript\"></script>\r\n        \r\n        <script src=\"libraries/cleave.js-master/dist/cleave.min.js\" type=\"text/javascript\"></script>\r\n        <style type=\"text/css\">\r\n            .container,.panel {\r\n                height:100%;\r\n                width: 100%;\r\n                padding: 0px !important;\r\n                margin: 0px !important;\r\n            }\r\n            \r\n\r\n            \r\n            .table>tfoot>tr>th{\r\n                font-size: 12px !important;\r\n                background-color: #F3F3F3;\r\n                padding: 2px;\r\n                border: none !important;\r\n            }\r\n            \r\n            .dataTables_filter{ display: none; }\r\n            \r\n            .input-sm{\r\n                \r\n            }\r\n            \r\n            .selected{\r\n                background-color: #337ab7 !important;\r\n                color: #ffffff !important;\r\n            }\r\n            \r\n            .search_filter{\r\n                width: 100% !important;\r\n                color: #000;\r\n            }\r\n            \r\n            \r\n            ::-webkit-input-placeholder { /* Chrome/Opera/Safari */\r\n                color: #337ab7;\r\n                opacity: 0.9;\r\n            }\r\n            ::-moz-placeholder { /* Firefox 19+ */\r\n                color: #337ab7;\r\n                opacity: 0.9;\r\n            }\r\n            :-ms-input-placeholder { /* IE 10+ */\r\n                color: #337ab7;\r\n                opacity: 0.9;\r\n            }\r\n            :-moz-placeholder { /* Firefox 18- */\r\n                color: #337ab7;\r\n                opacity: 0.9;\r\n            }\r\n            \r\n            /* enable absolute positioning */\r\n            .inner-addon { \r\n                position: relative; \r\n            }\r\n\r\n            /* style icon */\r\n            .inner-addon .glyphicon {\r\n              position: absolute;\r\n              padding: 6px;\r\n              padding-top: 8px;\r\n              pointer-events: none;\r\n            }\r\n\r\n            /* align icon */\r\n            .left-addon .glyphicon  { left:  0px; display: none}\r\n            .right-addon .glyphicon { right: 0px;}\r\n\r\n            /* add padding  */\r\n            .left-addon input  { padding-left:  5px; }\r\n            .right-addon input { padding-right: 30px; }\r\n            \r\n            .phone-input{\r\n                    margin-bottom:8px;\r\n            }\r\n            \r\n            .confirmation {\r\n                width: 160px;\r\n            }\r\n            \r\n            .list-group-item{\r\n                border: none !important;\r\n            }\r\n            \r\n            div.toolbar h3{\r\n                margin-top: 0px !important;\r\n            }\r\n            \r\n            .panel > .panel-heading{\r\n                 padding: 1px 6px  !important;\r\n            }\r\n            \r\n            .dataTables_length{\r\n                margin-top: 5px !important;\r\n            }\r\n        </style>\r\n\r\n        <script type=\"text/javascript\">\r\n            var customers_table = null;\r\n            var total_s_r_debit = 0;\r\n            var total_s_r_credit = 0;\r\n            var total_s_r_balance = 0;\r\n            \r\n            var delivery_plugin = ";
echo $this->settings_info["delivery_items_plugin"];
echo ";\r\n            \r\n            var denieaccess = ";
echo $_SESSION["hide_critical_data"];
echo ";\r\n            var all_currencies = [];\r\n            \r\n            \$(document).ready(function () {\r\n                \$.getJSON(\"?r=settings_info&f=get_needed_data\", function (data) {\r\n                    all_currencies = [];\r\n                    \$.each(data.currencies, function (key, val) {\r\n                        all_currencies.push({id:val.id,name:val.name,symbole:val.symbole,system_default:val.system_default,rate_to_system_default:val.rate_to_system_default});\r\n                    }); \r\n                }).done(function () {\r\n                    init();\r\n                });\r\n            });\r\n            \r\n            function init(){\r\n                \$('#items_table').show();\r\n                \r\n                var delivery_column_visible = 0;\r\n                if(delivery_plugin==1){\r\n                    delivery_column_visible=1;\r\n                }  \r\n                \r\n                \$(\".sk-circle\").center();\r\n                \$(\".sk-circle-layer\").show();\r\n                \r\n                var search_fields = [0,1,2,3,4,5,6,7];\r\n                var index = 0;\r\n                \$('#customers_table tfoot th').each( function () {\r\n                    if(jQuery.inArray(index, search_fields) !== -1){\r\n                        var title = \$(this).text();\r\n                        \$(this).html( '<div class=\"inner-addon left-addon\"><input style=\"width: 100% !important;\" class=\"form-control input_sm_search\" type=\"text\" placeholder=\" '+title+'\" /></div>' );\r\n                        index++;\r\n                    }\r\n                });\r\n                \r\n                customers_table = \$('#customers_table').dataTable({\r\n                    //ajax: \"?r=customers&f=getSuppliers\",\r\n                    ajax: {\r\n                        url: \"?r=customers&f=getClientsOverview&p0=0\",\r\n                        type: 'POST',\r\n                        error:function(xhr,status,error) {\r\n                            \r\n                        },\r\n                        dataSrc: function (json) {\r\n                            total_s_r_debit = json.debit;\r\n                            total_s_r_credit = json.credit;\r\n                            total_s_r_balance = json.balance;\r\n                            \$(\"#tr_s_debit\").html(total_s_r_debit);\r\n                            \$(\"#tr_s_credit\").html(total_s_r_credit);\r\n                            \$(\"#tr_s_balance\").html(total_s_r_balance);\r\n                            return json.data;\r\n                        }\r\n                    },\r\n                    orderCellsTop: true,\r\n                    aoColumnDefs: [\r\n                        { \"targets\": [0], \"searchable\": true, \"orderable\": true, \"visible\": true },\r\n                        { \"targets\": [1], \"searchable\": true, \"orderable\": true, \"visible\": true },\r\n                        { \"targets\": [2], \"searchable\": true, \"orderable\": true, \"visible\": true },\r\n                        { \"targets\": [3], \"searchable\": true, \"orderable\": true, \"visible\": true },\r\n                        { \"targets\": [4], \"searchable\": true, \"orderable\": true, \"visible\": true },\r\n                        { \"targets\": [5], \"searchable\": true, \"orderable\": true, \"visible\": true },\r\n                        { \"targets\": [6], \"searchable\": true, \"orderable\": true, \"visible\": true },\r\n                        { \"targets\": [7], \"searchable\": true, \"orderable\": true, \"visible\": true },\r\n                        { \"targets\": [8], \"searchable\": true, \"orderable\": false, \"visible\": false },\r\n                    ],\r\n                    scrollY: '47vh',\r\n                    scrollCollapse: true,\r\n                    \"paging\": true,           // Enable pagination\r\n                    \"pageLength\": 50, \r\n                    \"lengthChange\": true,\r\n                    \"lengthMenu\": [50, 100, 250, 500], // Page length options \r\n                    //dom: '<\"toolbar\">frtlip',\r\n                    dom: '<\"row\"<\"col-sm-12 col-md-12\"<\"toolbar\">>><\"row\"<\"col-sm-12 col-md-6\"f>>' +\r\n                    '<\"row\"<\"col-sm-12\"tr>>' +\r\n                    '<\"row\"<\"col-sm-4 col-md-4\"l><\"col-sm-4 col-md-4\"i><\"col-sm-4 col-md-4\"p>>',\r\n                    initComplete: function( settings ) {\r\n                        var table = \$('#customers_table').DataTable();\r\n                        table.row(':eq(0)', { page: 'current' }).select();\r\n                        \r\n                        \$(\"div.toolbar\").html('<h3><i class=\"glyphicon glyphicon-list-alt\"></i>&nbsp;Clients - Financial Overview</h3>\\n\\\r\n                            <div class=\"row\">\\n\\\r\n                                <div class=\"col-lg-2 col-md-2 col-xs-2\" style=\"padding-left:15px;padding-right:5px;\">\\n\\\r\n                                    <div class=\"btn-group\" role=\"group\" aria-label=\"\" style=\"width:100%\">\\n\\\r\n                                        <div class=\"btn-group filter\" role=\"group\" aria-label=\"\" style=\"width:100% !important;\">\\n\\\r\n                                            <select data-live-search=\"true\" data-width=\"100%\" id=\"all_remain\" class=\"selectpicker\" onchange=\"remain_changed()\">\\n\\\r\n                                                <option value=\"0\" title=\"All balances\">All balances</option>\\n\\\r\n                                                <option value=\"1\" title=\"Positive (Debit) balances\">Positive (Debit) balances</option>\\n\\\r\n                                                <option value=\"2\" title=\"Negative (Credit) balances\">Negative (Credit) balances</option>\\n\\\r\n                                                <option value=\"3\" title=\"Zero balances\">Zero balances</option>\\n\\\r\n                                            </select>\\n\\\r\n                                        </div>\\n\\\r\n                                    </div>\\n\\\r\n                                </div>\\n\\\r\n                                <div class=\"col-lg-10 col-md-10 col-xs-10\" >\\n\\\r\n                                    <div class=\"btn-group\" role=\"group\" aria-label=\"\" style=\"width:100%; \">\\n\\\r\n                                        <div class=\"btn-group\" id=\"buttons\"  style=\"width:100%;\"></div>\\n\\\r\n                                    </div>\\n\\\r\n                                </div>\\n\\\r\n                            </div>\\n\\\r\n                            <div class=\"row\" style=\"margin-top:5px;\">\\n\\\r\n                                <div class=\"col-md-2 col-sm-3\" style=\"padding-right:2px;\">\\n\\\r\n                                    <div class=\"panel panel-info\">\\n\\\r\n                                        <div class=\"panel-heading\">\\n\\\r\n                                            <div class=\"row\">\\n\\\r\n                                                <div class=\"col-xs-12 col-sm-12 text-left\">\\n\\\r\n                                                    <b class=\"announcement-heading dollar\" id=\"tr_s_debit\">0</b>\\n\\\r\n                                                    <p class=\"announcement-text\" style=\"margin-bottom:0px;\">Debit</p>\\n\\\r\n                                                </div>\\n\\\r\n                                            </div>\\n\\\r\n                                        </div>\\n\\\r\n                                    </div>\\n\\\r\n                                </div>\\n\\\r\n                                <div class=\"col-md-2 col-sm-3\" style=\"padding-right:2px;\">\\n\\\r\n                                    <div class=\"panel panel-info\">\\n\\\r\n                                        <div class=\"panel-heading\">\\n\\\r\n                                            <div class=\"row\">\\n\\\r\n                                                <div class=\"col-xs-12 col-sm-12 text-left\">\\n\\\r\n                                                    <b class=\"announcement-heading dollar\" id=\"tr_s_credit\">0</b>\\n\\\r\n                                                    <p class=\"announcement-text\" style=\"margin-bottom:0px;\">Credit</p>\\n\\\r\n                                                </div>\\n\\\r\n                                            </div>\\n\\\r\n                                        </div>\\n\\\r\n                                    </div>\\n\\\r\n                                </div>\\n\\\r\n                                <div class=\"col-md-2 col-sm-3\" style=\"padding-right:2px;\">\\n\\\r\n                                    <div class=\"panel panel-info\">\\n\\\r\n                                        <div class=\"panel-heading\">\\n\\\r\n                                            <div class=\"row\">\\n\\\r\n                                                <div class=\"col-xs-12 col-sm-12 text-left\">\\n\\\r\n                                                    <b class=\"announcement-heading dollar\" id=\"tr_s_balance\">0</b>\\n\\\r\n                                                    <p class=\"announcement-text\" style=\"margin-bottom:0px;\">Balance</p>\\n\\\r\n                                                </div>\\n\\\r\n                                            </div>\\n\\\r\n                                        </div>\\n\\\r\n                                    </div>\\n\\\r\n                                </div>\\n\\\r\n                            </div>\\n\\\r\n                        ');\r\n    \r\n    \r\n                        \$(\"#tr_s_debit\").html(total_s_r_debit);\r\n                        \$(\"#tr_s_credit\").html(total_s_r_credit);\r\n                        \$(\"#tr_s_balance\").html(total_s_r_balance);\r\n                        \r\n                        \$(\"#all_remain\").selectpicker();\r\n    \r\n    \r\n                        var buttons = new \$.fn.dataTable.Buttons(table, {\r\n                            buttons: [\r\n                              {\r\n                                    extend: 'excel',\r\n                                    text: 'Export excel',\r\n                                    className: 'exportExcel',\r\n                                    filename: 'Clients',\r\n                                    customize: _customizeExcelOptions,\r\n                                    exportOptions: {\r\n                                        modifier: {\r\n                                            page: 'all'\r\n                                        },\r\n                                        format: {\r\n                                            //body: function ( data, row, column, node ) {\r\n                                                // Strip \$ from salary column to make it numeric\r\n                                                //return column === 5 ? \$(\"#id_\"+parseInt(table.cell(row,0).data().split('-')[1])).val() : data; //table.cell(row,0).data().split('-')[1]\r\n                                            //}\r\n                                        }\r\n                                    }\r\n                              }\r\n                            ]\r\n\r\n                       }).container().appendTo(\$('#buttons'));\r\n\r\n                      function _customizeExcelOptions(xlsx) {\r\n                            var sheet = xlsx.xl.worksheets['sheet1.xml'];\r\n                            var clR = \$('row', sheet);\r\n                            var r1 = \"\";//Addrow(clR.length+2, [{key:'A',value: \"Total Remain: \"},{key:'B',value: \$(\"#tr_s\").html()}]);\r\n                            //var r2 = Addrow(clR.length+3, [{key:'A',value: \"Total profit\"},{key:'B',value: \$(\"#total_profit\").html()}]);\r\n                            //var r3 = Addrow(clR.length+4, [{key:'A',value: \"Total Expenses\"},{key:'B',value: \$(\"#total_expenses\").html()}]);\r\n                            //var r4 = Addrow(clR.length+5, [{key:'A',value: \"Total Invoices Discounts\"},{key:'B',value: \$(\"#tm_discount\").html()}]);\r\n                            //var r5 = Addrow(clR.length+6, [{key:'A',value: \"Total Credit Notes\"},{key:'B',value: \$(\"#total_credit_notes\").html()}]);\r\n                            sheet.childNodes[0].childNodes[1].innerHTML = sheet.childNodes[0].childNodes[1].innerHTML + r1;\r\n\r\n                            \$('row c[r^=\"A'+(clR.length+2)+'\"]', sheet).attr('s', '48');\r\n                            //\$('row c[r^=\"A'+(clR.length+3)+'\"]', sheet).attr('s', '48');\r\n                            //\$('row c[r^=\"A'+(clR.length+4)+'\"]', sheet).attr('s', '48');\r\n                            //\$('row c[r^=\"A'+(clR.length+5)+'\"]', sheet).attr('s', '48');\r\n                            //\$('row c[r^=\"A'+(clR.length+6)+'\"]', sheet).attr('s', '48');\r\n\r\n                            function Addrow(index, data) {\r\n                                var msg = '<row r=\"' + index + '\">'\r\n                                for (var i = 0; i < data.length; i++) {\r\n                                    var key = data[i].key;\r\n                                    var value = data[i].value;\r\n                                    msg += '<c t=\"inlineStr\" r=\"' + key + index + '\">';\r\n                                    msg += '<is>';\r\n                                    msg += '<t>' + value + '</t>';\r\n                                    msg += '</is>';\r\n                                    msg += '</c>';\r\n                                }\r\n                                msg += '</row>';\r\n                                return msg;\r\n                            }\r\n                        }\r\n                        \r\n                        \$('#customers_table').DataTable().columns.adjust().draw();\r\n                        \r\n                        \$(\".sk-circle-layer\").hide();      \r\n                    },\r\n                    fnDrawCallback: setCustomersOptions,\r\n                    fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {\r\n                        \$(nRow).addClass(aData[0]);\r\n                    },\r\n                });\r\n                \r\n                \$('#customers_table').on( 'page.dt', function () {\r\n                    \$(\".selected\").removeClass(\"selected\");\r\n                    \$(\"#tab_toolbar button.blueB\").addClass(\"disabled\");\r\n                } );\r\n                \r\n                \$('#customers_table').DataTable().columns().every( function () {\r\n                    //\$('.selected').removeClass(\"selected\");\r\n                    var that = this;\r\n                    \$( 'input', this.footer() ).on( 'keyup change', function () {\r\n                        \$('.selected').removeClass(\"selected\");\r\n                        if ( that.search() !== this.value ) {\r\n                            that.search( this.value ).draw();\r\n                        }\r\n                    } );\r\n                } );\r\n                \r\n                \$('#customers_table').DataTable().on('mousedown',\"tr\", function ( e, dt, type, indexes ) { \r\n                    \$('.selected').removeClass(\"selected\");\r\n                    \$(this).addClass('selected');\r\n                    \$(\"#tab_toolbar button.blueB\").removeClass(\"disabled\");\r\n                });\r\n\r\n            };\r\n            \r\n            function remain_changed(){\r\n                \$(\".sk-circle-layer\").show();\r\n                var table = \$('#customers_table').DataTable();\r\n                table.ajax.url(\"?r=customers&f=getClientsOverview&p0=\"+\$(\"#all_remain\").val()).load(function () {\r\n                    \$(\".sk-circle-layer\").hide();\r\n                }, false);\r\n            }\r\n            \r\n            function addSupplier_s(source){\r\n                addSupplier(source);\r\n            }\r\n           \r\n            function showDetails_s(){\r\n                if(\$(\"#tab_toolbar button.blueB\").hasClass(\"disabled\")==false){  \r\n                    var dt = \$('#customers_table').DataTable();\r\n                    var id = dt.rows({ selected: true }).data()[0][0];\r\n                    showDetails(id);\r\n                }\r\n            }\r\n            \r\n            function edit_s(id){\r\n                editSupplier(id);\r\n            }\r\n            \r\n            function delete_s(){\r\n                var dt = \$('#customers_table').DataTable();\r\n                var id = dt.rows({ selected: true }).data()[0][0];\r\n                alert(id);\r\n            }\r\n            \r\n            function setCustomersOptions(){\r\n                var table = \$('#customers_table').DataTable();\r\n                var p = table.rows({ page: 'current' }).nodes();\r\n                var dlt = '';\r\n                for (var k = 0; k < p.length; k++){\r\n                    var index = table.row(p[k]).index();\r\n                    //table.cell(index,8).data('<button onclick=\"show_supplier_stmt('+parseInt(table.cell(index,0).data().split('-')[1])+',1)\" style=\"width:100%;font-weight:bold;padding-top:0px !important;padding-bottom:0px !important;\" type=\"button\" class=\"btn btn-default btn-xs\">STMT '+MAIN_CURRENCY+'</button>');\r\n                }\r\n            }\r\n            \r\n            function delete_supplier(id){\r\n                swal({\r\n                    title: \"Are you sure?\",\r\n                    text: \"\",\r\n                    type: \"warning\",\r\n                    showCancelButton: true,\r\n                    confirmButtonClass: \"btn-danger\",\r\n                    confirmButtonText: \"Delete now\",\r\n                    cancelButtonText: \"Cancel\",\r\n                    closeOnConfirm: true\r\n                  },\r\n                function(isconfirm){\r\n                    if(isconfirm){\r\n                        var dt = \$('#customers_table').DataTable();\r\n                        \$.getJSON(\"?r=suppliers&f=delete_suppliers&p0=\"+id , function (data) {\r\n                            if(!data.status){\r\n                                swal(\"Unable to delete!\", \"Please contact system administrator\", \"warning\");\r\n                            }else{\r\n                                dt.row('.selected').remove().draw( false );\r\n                            }\r\n                        }).done(function () {\r\n\r\n                        });\r\n                    }else{\r\n                        \r\n                    }\r\n                });\r\n                \r\n            }\r\n            \r\n            function add_supplier_payment_direct(){\r\n                var supplier_name = \"\";\r\n\r\n                var dt = \$('#customers_table').DataTable();\r\n                var id = dt.row('.selected', 0).data()[0];\r\n                var supplier_id = parseInt(id.split('-')[1]);\r\n                var supplier_option = \"\";\r\n                \r\n                var supplier_id = parseInt(id.split('-')[1]);\r\n                supplier_name = \"\";\r\n                supplier_option = \"<option value='\"+supplier_id+\"' title='\"+supplier_name+\"'>\"+supplier_name+\"</option>\";\r\n       \r\n                _add_supplier_payment(0,supplier_id,supplier_option,\"\",\"admin_direct\",[]);\r\n            \r\n            }\r\n            \t\r\n        </script>\r\n    </head>\r\n    <body>\r\n        \r\n        <!-- Navbar fixed top -->\r\n        ";
include "application/mvc/views/topMenu.php";
echo "        <div class=\"container\" >\r\n            <div class=\"panel panel-default\">\r\n                <div class=\"panel-body\" style=\"margin-top: 50px;\">\r\n                    <div class=\"row\"  >\r\n                        <div class=\"col-lg-12\" >           \r\n                            <table id=\"customers_table\" class=\"table table-striped table-bordered\" cellspacing=\"0\" style=\"width:100%\">\r\n                                <thead>\r\n                                    <tr>\r\n                                        <th style=\"width: 40px;\">Ref.</th>\r\n                                        <th>Client Name</th>\r\n                                        <th style=\"width: 100px;\">Phone</th>\r\n                                        <th style=\"width: 110px;\">Starting Balance</th>\r\n                                        <th style=\"width: 110px;\">Total Invoices</th>\r\n\r\n                                        \r\n                                        <th style=\"width: 110px;\">Total Paid</th>\r\n                                        <th style=\"width: 110px;\">Total Credit Notes</th>\r\n                                        <th style=\"width: 110px;\">Balance</th>\r\n                                        <th style=\"width: 80px;\">&nbsp;</th>\r\n                                        \r\n                                    </tr>\r\n                                </thead>\r\n                                <tfoot>\r\n                                    <tr>\r\n                                        <th>Ref.</th>\r\n                                        <th>Client Name</th>\r\n                                        <th>Phone</th>\r\n                                        <th>Starting Balance</th>\r\n                                        <th>Total Invoices</th>\r\n                                        \r\n\r\n                                        \r\n                                        <th>Total Paid</th>\r\n                                        <th>Total Credit Notes</th>\r\n                                        <th>Balance</th>\r\n                                        <th>&nbsp;</th>\r\n                                    </tr>\r\n                                </tfoot>\r\n                                <tbody></tbody>\r\n                            </table>\r\n                        </div>\r\n                    </div>\r\n                </div>\r\n            </div>\r\n        </div>\r\n        \r\n        <div class=\"sk-circle-layer\">\r\n            <div class=\"sk-circle\">\r\n                <div class=\"sk-circle1 sk-child\"></div>\r\n                <div class=\"sk-circle2 sk-child\"></div>\r\n                <div class=\"sk-circle3 sk-child\"></div>\r\n                <div class=\"sk-circle4 sk-child\"></div>\r\n                <div class=\"sk-circle5 sk-child\"></div>\r\n                <div class=\"sk-circle6 sk-child\"></div>\r\n                <div class=\"sk-circle7 sk-child\"></div>\r\n                <div class=\"sk-circle8 sk-child\"></div>\r\n                <div class=\"sk-circle9 sk-child\"></div>\r\n                <div class=\"sk-circle10 sk-child\"></div>\r\n                <div class=\"sk-circle11 sk-child\"></div>\r\n                <div class=\"sk-circle12 sk-child\"></div>\r\n            </div>\r\n        </div>\r\n    </body>\r\n</html>\r\n\r\n";

?>