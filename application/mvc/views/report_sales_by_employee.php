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
echo " Sales per vendor</title>\r\n        <meta charset=\"UTF-8\">\r\n        <meta charset=\"UTF-8\">\r\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n        <link rel=\"icon\" type=\"image/png\" href=\"resources/favicon.png\">\r\n        \r\n        <script src=\"libraries/jquery-3.1.1.min.js\" type=\"text/javascript\"></script>\r\n\r\n        <script src=\"libraries/bootstrap-3.3.7-dist/js/bootstrap.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-3.3.7-dist/css/bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        <script src=\"libraries/bootstrap-plugins/DataTables-1.10.13/media/js/jquery.dataTables.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-plugins/DataTables-1.10.13/media/js/dataTables.bootstrap.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/DataTables-1.10.13/media/css/dataTables.bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n\r\n        \r\n        <link href=\"libraries/bootstrap-plugins/export/buttons.dataTables.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        <script src=\"libraries/bootstrap-plugins/export/dataTables.buttons.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-plugins/export/jszip.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-plugins/export/vfs_fonts.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-plugins/export/buttons.html5.min.js\" type=\"text/javascript\"></script>\r\n        \r\n        <script src=\"libraries/bootstrap-plugins/DataTables-1.10.13/extensions/Select/js/dataTables.select.min.js\" type=\"text/javascript\"></script>\r\n        <!-- <script src=\"libraries/bootstrap-plugins/Buttons-1.2.4/js/dataTables.buttons.min.js\" type=\"text/javascript\"></script>-->\r\n        <script src=\"libraries/bootstrap-plugins/Buttons-1.2.4/js/buttons.bootstrap.min.js\" type=\"text/javascript\"></script> \r\n        <link href=\"libraries/bootstrap-plugins/Buttons-1.2.4/css/buttons.bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        \r\n        <link href=\"libraries/bootstrap-plugins/datepicker-master/dist/css/bootstrap-datepicker.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        <script src=\"libraries/bootstrap-plugins/datepicker-master/dist/js/bootstrap-datepicker.min.js\" type=\"text/javascript\"></script>\r\n        \r\n        \r\n        <!-- Include Date Range Picker -->\r\n        <script src=\"libraries/bootstrap-plugins/daterangepicker-master/moment.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-plugins/daterangepicker-master/daterangepicker.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/daterangepicker-master/daterangepicker.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        \r\n        \r\n        \r\n        <script src=\"application/mvc/views/custom_libraries/javascripts/global_functions.js?rnd=";
echo self::generateRandomStringComplex();
echo "\" type=\"text/javascript\"></script>\r\n        <script src=\"application/mvc/views/custom_libraries/javascripts/global.js?rnd=";
echo self::generateRandomStringComplex();
echo "\" type=\"text/javascript\"></script>\r\n\r\n        <script src=\"libraries/bootstrap-plugins/bootstrap-select-master/bootstrap-select-master/dist/js/bootstrap-select.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/bootstrap-select-master/bootstrap-select-master/dist/css/bootstrap-select.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        <link href=\"application/mvc/views/custom_libraries/css/global.css?rnd=";
echo self::generateRandomStringComplex();
echo "\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        \r\n         <script src=\"libraries/bootstrap-plugins/bootstrap-select-master/bootstrap-select-master/dist/js/bootstrap-select.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/bootstrap-select-master/bootstrap-select-master/dist/css/bootstrap-select.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        \r\n        <script src=\"libraries/numeric.js\" type=\"text/javascript\"></script>\r\n        \r\n        <script src=\"libraries/bootstrap-plugins/libs/bootstrap-confirmation.min.js\" type=\"text/javascript\"></script>\r\n        \r\n        <script src=\"libraries/bootstrap-plugins/bootstrap-sweetalert-master/dist/sweetalert.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/bootstrap-sweetalert-master/dist/sweetalert.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n       \r\n        <link href=\"application/mvc/views/custom_libraries/svgs/font/style.css?rnd=";
echo self::generateRandomStringComplex();
echo "\" rel=\"stylesheet\" type=\"text/css\"/>\r\n\r\n        <style type=\"text/css\">\r\n            .container,.panel {\r\n                height:100%;\r\n                width: 100%;\r\n                padding: 0px !important;\r\n                margin: 0px !important;\r\n            }\r\n            \r\n\r\n            \r\n            .table>tfoot>tr>th{\r\n                font-size: 12px !important;\r\n                background-color: #F3F3F3;\r\n                padding: 2px;\r\n                border: none !important;\r\n            }\r\n            \r\n            .dataTables_filter{ display: none; }\r\n            \r\n            .input-sm{\r\n                \r\n                font-size: 14px !important;\r\n            }\r\n            \r\n            .selected{\r\n                background-color: #337ab7 !important;\r\n                color: #ffffff !important;\r\n            }\r\n            \r\n            .search_filter{\r\n                width: 100% !important;\r\n                color: #000;\r\n            }\r\n            \r\n            \r\n            ::-webkit-input-placeholder { /* Chrome/Opera/Safari */\r\n                color: #337ab7;\r\n                opacity: 0.9;\r\n            }\r\n            ::-moz-placeholder { /* Firefox 19+ */\r\n                color: #337ab7;\r\n                opacity: 0.9;\r\n            }\r\n            :-ms-input-placeholder { /* IE 10+ */\r\n                color: #337ab7;\r\n                opacity: 0.9;\r\n            }\r\n            :-moz-placeholder { /* Firefox 18- */\r\n                color: #337ab7;\r\n                opacity: 0.9;\r\n            }\r\n            \r\n            /* enable absolute positioning */\r\n            .inner-addon { \r\n                position: relative; \r\n            }\r\n\r\n            /* style icon */\r\n            .inner-addon .glyphicon {\r\n              position: absolute;\r\n              padding: 6px;\r\n              padding-top: 8px;\r\n              pointer-events: none;\r\n            }\r\n\r\n            /* align icon */\r\n            .left-addon .glyphicon  { left:  0px;}\r\n            .right-addon .glyphicon { right: 0px;}\r\n\r\n            /* add padding  */\r\n            \r\n            .dataTables_wrapper .dataTables_paginate .paginate_button a {\r\n        padding: 5px 10px;\r\n        font-size: 0.8em;\r\n      }\r\n\r\n            \r\n            .phone-input{\r\n                    margin-bottom:8px;\r\n            }\r\n            \r\n            .confirmation {\r\n                width: 160px;\r\n            }\r\n            \r\n            .list-group-item{\r\n                border: none !important;\r\n            }\r\n            \r\n            div.toolbar h3{\r\n                margin-top: 0px !important;\r\n            }\r\n            \r\n            #add_new_expenses input{\r\n                font-size: 16px !important;\r\n            }\r\n            \r\n            .table-nonfluid {\r\n                width: auto !important;\r\n             }\r\n             \r\n             tbody .dt-center{\r\n                 text-align: center !important;\r\n                 font-family: serif;\r\n             }\r\n             \r\n             .panel > .panel-heading{\r\n                padding: 1px 6px  !important;\r\n            }\r\n             \r\n        </style>\r\n\r\n        <script type=\"text/javascript\">\r\n            var report__by_day = null;\r\n            \r\n            var current_store_id = ";
echo $_SESSION["store_id"];
echo ";\r\n            var current_vendor_id = null;\r\n            var stores = [];\r\n            var vendors = [];\r\n            \r\n            var currency = \"";
echo $data["currency"];
echo "\";\r\n           \r\n            \$(document).ready(function () {\r\n                \$(\".sk-circle\").center();\r\n                \$(\".sk-circle-layer\").show();\r\n                \$.getJSON(\"?r=reports&f=getVendors\", function (data) {\r\n                    \$.each(data, function (key, val) {\r\n                        if(current_vendor_id==null){\r\n                            current_vendor_id = val.id;\r\n                        }\r\n                        vendors.push({id:val.id,username:val.username});\r\n                    });\r\n                }).done(function () {\r\n                    init();\r\n                });\r\n            });\r\n            \r\n            function show_commisison_details(){\r\n                var content =\r\n                '<div class=\"modal large\" id=\"commission_detailsModal\" tabindex=\"-1\" aria-labelledby=\"commission_detailsModalLabel\" aria-hidden=\"true\">\\n\\\r\n                    <div class=\"modal-dialog\">\\n\\\r\n                      <div class=\"modal-content\">\\n\\\r\n                        <div class=\"modal-header\">\\n\\\r\n                          <h3 class=\"modal-title\" id=\"commission_detailsModalLabel\">Commission Details<i style=\"float:right;font-size:35px\" class=\"glyphicon glyphicon-remove\" onclick=\"closeModal(\\'commission_detailsModal\\')\"></i></h3>\\n\\\r\n                        </div>\\n\\\r\n                        <div class=\"modal-body\">\\n\\\r\n                            <div class=\"row mt-2\">\\n\\\r\n                                  <div class=\"col-lg-12\">\\n\\\r\n                                  <table id=\"commission_details_modal_table\" class=\"table table-striped\" style=\"width:100%\">\\n\\\r\n                                      <thead>\\n\\\r\n                                          <tr>\\n\\\r\n                                              <th>Invoice ID</th>\\n\\\r\n                                              <th>Amount</th>\\n\\\r\n                                              <th>Commisison Percentage</th>\\n\\\r\n                                              <th>Commisison Amount</th>\\n\\\r\n                                          </tr>\\n\\\r\n                                      </thead>\\n\\\r\n                                      <tbody>\\n\\\r\n                                      </tbody>\\n\\\r\n                                  </table>\\n\\\r\n                              </div>\\n\\\r\n                          </div>\\n\\\r\n                      </div>\\n\\\r\n                    </div>\\n\\\r\n                  </div>';\r\n                \$(\"#commission_detailsModal\").modal('hide');\r\n                \$(\"body\").append(content);\r\n                \$('#commission_detailsModal').on('show.bs.modal', function (e) {\r\n\r\n                });\r\n\r\n                \$('#commission_detailsModal').on('shown.bs.modal', function (e) { \r\n\r\n                    \$('#commission_details_modal_table').DataTable({\r\n                        ajax: {\r\n                           url: \"?r=reports&f=get_all_commission_details&p0=\"+\$(\"#vendors_list\").val()+\"&p1=\"+\$(\"#salesDate\").val(),\r\n                           type: 'POST',\r\n                           error:function(xhr,status,error) {\r\n\r\n                           },\r\n                       },\r\n                       \"pagingType\": \"full_numbers\", // Add pagination styles\r\n                       \"scrollY\": \"300px\", // Set the maximum height for the body\r\n                       \"lengthMenu\": [[50, 100, 250, -1], [50, 100, 250, \"All\"]], // Define page length menu\r\n                       \"order\": [[0, \"asc\"]], // Set default sorting column and order\r\n                       \"language\": {\r\n                         \"search\": \"Search records:\",\r\n                       }\r\n                   });\r\n                });\r\n\r\n                \$('#commission_detailsModal').on('hide.bs.modal', function (e) {\r\n                    \$(\"#commission_detailsModal\").remove();\r\n                });\r\n                \$('#commission_detailsModal').modal('show');\r\n            }\r\n            \r\n            function init(){\r\n                \$('#items_table').show();\r\n                \r\n                \$(\".sk-circle\").center();\r\n                \$(\".sk-circle-layer\").show();\r\n                \r\n                var search_fields = [0,1,2,3,4,5,6,7,8,9,10,11];\r\n                var index = 0;\r\n                \$('#report__by_day tfoot th').each( function () {\r\n                    if(jQuery.inArray(index, search_fields) !== -1){\r\n                        var title = \$(this).text();\r\n                        \$(this).html( '<div class=\"inner-addon left-addon\"><input style=\"width: 100% !important;\" class=\"form-control input_sm_search\" type=\"text\" placeholder=\" '+title+'\" /></div>' );\r\n                        index++;\r\n                    }\r\n                });\r\n                \r\n\r\n\r\n                \r\n                var total_amount=0;\r\n                var total_commission=0;\r\n                \r\n                report__by_day = \$('#report__by_day').dataTable({\r\n                    ajax: {\r\n                        url: \"?r=reports&f=getReportByEmployee&p0=\"+current_store_id+\"&p1=thismonth&p2=\"+current_vendor_id,\r\n                        type: 'POST',\r\n                        error:function(xhr,status,error) {\r\n                        },\r\n                        dataSrc: function (json) {\r\n                            \r\n                            if(\$(\"#total_amount\").length>0){\r\n                                \$(\"#total_amount\").html(json.total_amount);\r\n                               \$(\"#total_commission\").html(json.total_commission);\r\n\r\n                            }else{\r\n                                total_amount=json.total_amount;\r\n                                 total_commission=json.total_commission;\r\n                            }\r\n                            \r\n                            return json.data;\r\n                        }\r\n                    },\r\n                    orderCellsTop: true,\r\n                    aoColumnDefs: [\r\n                        { \"targets\": [0], \"searchable\": true, \"orderable\": true, \"visible\": true },\r\n                        { \"targets\": [1], \"searchable\": true, \"orderable\": true, \"visible\": true },\r\n                        { \"targets\": [2], \"searchable\": true, \"orderable\": true, \"visible\": true,\"className\": \"dt-center\"  },\r\n                        { \"targets\": [3], \"searchable\": true, \"orderable\": true, \"visible\": true,\"className\": \"dt-center\" },\r\n                        { \"targets\": [4], \"searchable\": true, \"orderable\": true, \"visible\": true },\r\n                        { \"targets\": [5], \"searchable\": true, \"orderable\": true, \"visible\": true },\r\n                        { \"targets\": [6], \"searchable\": true, \"orderable\": true, \"visible\": true },\r\n                        { \"targets\": [7], \"searchable\": true, \"orderable\": true, \"visible\": true,\"className\": \"dt-center\"  },\r\n                        { \"targets\": [8], \"searchable\": true, \"orderable\": true, \"visible\": true,\"className\": \"dt-center\"  },\r\n                        { \"targets\": [9], \"searchable\": true, \"orderable\": true, \"visible\": true,\"className\": \"dt-center\"  },\r\n                        { \"targets\": [10], \"searchable\": true, \"orderable\": true, \"visible\": true,\"className\": \"dt-center\" },\r\n                        { \"targets\": [11], \"searchable\": true, \"orderable\": true, \"visible\": true,\"className\": \"dt-center\" },\r\n                    \r\n                    ],\r\n                    \r\n                    scrollY: '47vh',\r\n                    scrollCollapse: true,\r\n                    \"paging\": true,           // Enable pagination\r\n                    \"pageLength\": 50, \r\n                    \"lengthChange\": true,\r\n                    \"lengthMenu\": [50, 100, 250, 500], // Page length options \r\n                    //dom: '<\"toolbar\">frtlip',\r\n                    dom: '<\"row\"<\"col-sm-12 col-md-12\"<\"toolbar\">>><\"row\"<\"col-sm-12 col-md-6\"f>>' +\r\n                    '<\"row\"<\"col-sm-12\"tr>>' +\r\n                    '<\"row\"<\"col-sm-4 col-md-4\"l><\"col-sm-4 col-md-4\"i><\"col-sm-4 col-md-4\"p>>',\r\n                    initComplete: function( settings ) {\r\n                        var table = \$('#report__by_day').DataTable();\r\n                        table.row(':eq(0)', { page: 'current' }).select();\r\n                        \r\n                        var vendors_option = \"\";\r\n                        for(var i=0;i<vendors.length;i++){\r\n                            vendors_option+='<option value='+vendors[i].id+' title=\"'+vendors[i].username+'\">'+vendors[i].username+'</option>';\r\n                        }\r\n                        \r\n                        \$(\"div.toolbar\").html('<h3><i class=\"glyphicon glyphicon-list-alt\" style=\"font-size:26px;\"></i>&nbsp;Sales by vendor</h3>\\n\\\r\n                            <div class=\"row\">\\n\\\r\n                                <div class=\"col-lg-2 col-md-2 col-sm-2\" style=\"padding-right:5px;\">\\n\\\r\n                                    <div class=\"btn-group\" role=\"group\" aria-label=\"\" style=\"width:100%\">\\n\\\r\n                                        <input id=\"salesDate\" class=\"form-control datepicker\" type=\"text\" placeholder=\"Select date\" style=\"cursor:pointer; width:100%\">\\n\\\r\n                                    </div>\\n\\\r\n                                </div>\\n\\\r\n                                <div class=\"col-lg-2 col-md-2 col-sm-2\" style=\"padding-left:5px;\">\\n\\\r\n                                    <div class=\"btn-group\" role=\"group\" aria-label=\"\" style=\"width:100%\">\\n\\\r\n                                        <select id=\"vendors_list\" class=\"selectpicker\" onchange=\"vendor_changed()\">\\n\\\r\n                                            '+vendors_option+'\\n\\\r\n                                        </select>\\n\\\r\n                                    </div>\\n\\\r\n                                </div>\\n\\\r\n                                <div class=\"col-lg-2 col-md-2 col-sm-2\" style=\"padding-left:15px;padding-right:5px;\">\\n\\\r\n                                    <button style=\"width:100%\" onclick=\"refresh_commissions()\" type=\"button\" class=\"btn btn-primary\" title=\"Adjust the current commission percentage for selected vendor with a current value of zero.\">Refresh Commission</button>\\n\\\r\n                                </div>\\n\\\r\n                                <div class=\"col-lg-4 col-md-4 col-sm-4\" style=\"padding-left:15px;padding-right:5px;\">\\n\\\r\n                                    &nbsp;\\n\\\r\n                                </div>\\n\\\r\n                                <div class=\"col-lg-2 col-md-2 col-sm-2\"  >\\n\\\r\n                                    <div class=\"btn-group\" role=\"group\" aria-label=\"\" style=\"width:100%\">\\n\\\r\n                                        <div class=\"btn-group\" id=\"buttons\" style=\"float:right;\"></div>\\n\\\r\n                                    </div>\\n\\\r\n                                </div>\\n\\\r\n                            </div>\\n\\\r\n                            <div class=\"row\" style=\"margin-top:5px;\">\\n\\\r\n                                <div class=\"col-md-2 col-sm-3\" style=\"padding-right:2px;\">\\n\\\r\n                                    <div class=\"panel panel-info\">\\n\\\r\n                                        <div class=\"panel-heading\">\\n\\\r\n                                            <div class=\"row\">\\n\\\r\n                                                <div class=\"col-xs-12 col-sm-12 text-left\">\\n\\\r\n                                                    <b class=\"announcement-heading dollar\" id=\"total_amount\">0 <small class=\"currency\">'+currency+'</small></b>\\n\\\r\n                                                    <p class=\"announcement-text\" style=\"margin-bottom:0px;\">Amount</p>\\n\\\r\n                                                </div>\\n\\\r\n                                            </div>\\n\\\r\n                                        </div>\\n\\\r\n                                    </div>\\n\\\r\n                                </div>\\n\\\r\n                                <div class=\"col-md-2 col-sm-3\" style=\"padding-left:2px;padding-right:2px;\">\\n\\\r\n                                    <div class=\"panel panel-info\">\\n\\\r\n                                        <div class=\"panel-heading\">\\n\\\r\n                                            <div class=\"row\">\\n\\\r\n                                                <div class=\"col-xs-12 col-sm-12 text-left\">\\n\\\r\n                                                    <b class=\"announcement-heading dollar\" id=\"total_commission\">0 <small class=\"currency\">'+currency+'</small></b>\\n\\\r\n                                                    <p class=\"announcement-text\" style=\"margin-bottom:0px;\">Commission</p>\\n\\\r\n                                                </div>\\n\\\r\n                                            </div>\\n\\\r\n                                        </div>\\n\\\r\n                                    </div>\\n\\\r\n                                </div>\\n\\\r\n                            </div>\\n\\\r\n                            ');\r\n    \r\n    \r\n    \r\n                        var buttons = new \$.fn.dataTable.Buttons(table, {\r\n                            buttons: [\r\n                              {\r\n                                    extend: 'excel',\r\n                                    text: 'Export excel',\r\n                                    className: 'exportExcel',\r\n                                    filename: 'Sales Items ',\r\n                                    exportOptions: {\r\n                                        modifier: {\r\n                                            page: 'all'\r\n                                        },\r\n                                    },\r\n                                    customize: _customizeExcelOptions,\r\n                              }\r\n                            ]\r\n                            \r\n                       }).container().appendTo(\$('#buttons'));\r\n                       \r\n                       function _customizeExcelOptions(xlsx) {\r\n                            var sheet = xlsx.xl.worksheets['sheet1.xml'];\r\n                            var clR = \$('row', sheet);\r\n                            //var r1 = Addrow(clR.length+2, [{key:'A',value: \"Total Sales\"},{key:'B',value: \$(\"#total_sales_total\").html()}]);\r\n                            //var r2 = Addrow(clR.length+3, [{key:'A',value: \"Total profit\"},{key:'B',value: \$(\"#total_profit\").html()}]);\r\n                            //var r3 = Addrow(clR.length+4, [{key:'A',value: \"Total Expenses\"},{key:'B',value: \$(\"#total_expenses\").html()}]);\r\n                            //var r4 = Addrow(clR.length+5, [{key:'A',value: \"Total Invoices Discounts\"},{key:'B',value: \$(\"#tm_discount\").html()}]);\r\n                            //var r5 = Addrow(clR.length+6, [{key:'A',value: \"Total Credit Notes\"},{key:'B',value: \$(\"#total_credit_notes\").html()}]);\r\n                            //sheet.childNodes[0].childNodes[1].innerHTML = sheet.childNodes[0].childNodes[1].innerHTML + r1+ r2+ r3+ r4 + r5;\r\n                            \r\n                            //\$('row c[r^=\"A'+(clR.length+2)+'\"]', sheet).attr('s', '48');\r\n                            //\$('row c[r^=\"A'+(clR.length+3)+'\"]', sheet).attr('s', '48');\r\n                            //\$('row c[r^=\"A'+(clR.length+4)+'\"]', sheet).attr('s', '48');\r\n                            //\$('row c[r^=\"A'+(clR.length+5)+'\"]', sheet).attr('s', '48');\r\n                            //\$('row c[r^=\"A'+(clR.length+6)+'\"]', sheet).attr('s', '48');\r\n                            \r\n                            function Addrow(index, data) {\r\n                                var msg = '<row r=\"' + index + '\">'\r\n                                for (var i = 0; i < data.length; i++) {\r\n                                    var key = data[i].key;\r\n                                    var value = data[i].value;\r\n                                    msg += '<c t=\"inlineStr\" r=\"' + key + index + '\">';\r\n                                    msg += '<is>';\r\n                                    msg += '<t>' + value + '</t>';\r\n                                    msg += '</is>';\r\n                                    msg += '</c>';\r\n                                }\r\n                                msg += '</row>';\r\n                                return msg;\r\n                            }\r\n                        }\r\n                       \r\n    \r\n                        \$('.selectpicker').selectpicker();\r\n                        \r\n                        var defaultStart = moment().startOf('month');\r\n                        var end = moment();\r\n                        \r\n                        \$('#salesDate').daterangepicker({\r\n                            //dateLimit:{month:12},\r\n                            startDate: defaultStart,\r\n                            endDate: end,\r\n                            locale: {\r\n                                format: 'YYYY-MM-DD'\r\n                            },\r\n                            ranges: {\r\n                                'Today': [moment(), moment()],\r\n                                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],\r\n                                'Last 7 Days': [moment().subtract(6, 'days'), moment()],\r\n                                'Last 30 Days': [moment().subtract(29, 'days'), moment()],\r\n                                'This Month': [moment().startOf('month'), moment().endOf('month')],\r\n                                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]\r\n                             }\r\n                        });\r\n                        \r\n                        \$(\"#salesDate\").change(function() {\r\n                            report_day_changed();\r\n                        });\r\n                        \r\n                        onchange=\"report_day_changed()\";\r\n                        \r\n                        \r\n                        if(table.rows().count()==0)\r\n                            \$(\"#tab_toolbar button.blueB\").addClass(\"disabled\");\r\n                            \r\n                            \r\n                            \$(\"#total_amount\").html(total_amount);\r\n                            \$(\"#total_commission\").html(total_commission);\r\n\r\n                           \r\n                                 \r\n                        \r\n                        \$(\".sk-circle-layer\").hide();\r\n                        \r\n                    },\r\n                    fnDrawCallback: updateRows,\r\n                    fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {\r\n                        \$(nRow).addClass(aData[0]);\r\n                    },\r\n                });\r\n                \r\n          \r\n                \$('#report__by_day').on( 'page.dt', function () {\r\n                    \$(\".selected\").removeClass(\"selected\");\r\n                    \$(\"#tab_toolbar button.blueB\").addClass(\"disabled\");\r\n                } );\r\n                \r\n                \$('#report__by_day').DataTable().columns().every( function () {\r\n                    var that = this;\r\n                    \$( 'input', this.footer() ).on( 'keyup change', function () {\r\n                        if ( that.search() !== this.value ) {\r\n                            that.search( this.value ).draw();\r\n                        }\r\n                    } );\r\n                } );\r\n                \r\n                \$('#report__by_day').DataTable().on( 'select', function ( e, dt, type, indexes ) {\r\n                    if (type === 'row') {\r\n \r\n                       \$(\"#tab_toolbar button.blueB\").removeClass(\"disabled\");\r\n                    }\r\n                } );\r\n                \r\n                \$('#report__by_day').DataTable().on( 'deselect', function ( e, dt, type, indexes ) {\r\n                    if (type === 'row') {\r\n                        \$(\"#tab_toolbar button.blueB\").addClass(\"disabled\");\r\n                    }\r\n                });\r\n  \r\n                \$('#report__by_day').DataTable().search( '' ).columns().search( '' ).draw();\r\n            }\r\n            \r\n            function updateRows(){\r\n                var table = \$('#report__by_day').DataTable();\r\n                var p = table.rows({ page: 'current' }).nodes();\r\n                for (var k = 0; k < p.length; k++){\r\n                    var index = table.row(p[k]).index();\r\n                    //table.cell(index, 2).data('<button onclick=\"show_details_invoice(\\''+parseInt(table.cell(index, 1).data().split(\"-\")[1])+'\\')\" type=\"button\" class=\"btn btn-xs btn-info btn-xss\" style=\"width:100% !important;\">Invoice Details</button>');\r\n\r\n                }\r\n            }\r\n            \r\n            function updateTable(){\r\n                \$(\".sk-circle-layer\").show();\r\n                var table = \$('#report__by_day').DataTable();\r\n                table.ajax.url(\"?r=reports&f=getReportByEmployee&p0=\"+current_store_id+\"&p1=\"+\$(\"#salesDate\").val()+\"&p2=\"+\$(\"#vendors_list\").val()).load(function () {\r\n                    \$(\".sk-circle-layer\").hide();\r\n                }, false);\r\n            }\r\n            \r\n            function refresh_commissions(){\r\n                \$(\".sk-circle-layer\").show();\r\n                \$.getJSON(\"?r=invoice&f=refresh_commissions&p0=\"+\$(\"#vendors_list\").val(), function (data) {\r\n        \r\n                }).done(function () {\r\n                    \$(\".sk-circle-layer\").hide();\r\n                });\r\n            }\r\n            \r\n            \r\n            function vendor_changed(){\r\n               updateTable();\r\n            }\r\n            \r\n            function store_changed(){\r\n                updateTable();\r\n            }\r\n            \r\n            function report_day_changed(){\r\n                updateTable();\r\n            }\r\n   \r\n            \r\n        </script>\r\n    </head>\r\n    <body>\r\n        \r\n        <!-- Navbar fixed top -->\r\n        ";
include "application/mvc/views/topMenu.php";
echo "        <div class=\"container\" >\r\n            <div class=\"panel panel-default\">\r\n                <div class=\"panel-body\" style=\"margin-top: 50px;\">\r\n                    <div class=\"row\"  >\r\n                        <div class=\"col-lg-12\" >           \r\n                            <table id=\"report__by_day\" class=\"table table-striped table-bordered \" cellspacing=\"0\">\r\n                                <thead>\r\n                                    <tr>\r\n                                        <th style=\"width: 40px;\">Ref.</th>\r\n                                        <th style=\"width: 60px;\">Invoice ID</th>\r\n                                        <th style=\"width: 50px;\">Amount</th>\r\n                                        <th style=\"width: 60px;\">INV DISC</th>\r\n                                        <th>Customer</th>\r\n                                        <th>Description</th>\r\n                                        <th style=\"width: 45px;\">Date</th>\r\n                                        <th style=\"width: 30px;\">QTY</th>\r\n                                        <th style=\"width: 50px;\">Price/U</th>\r\n                                        <th style=\"width: 40px;\">DISC/U</th>\r\n                                        <th style=\"width: 30px;\">TAX</th>\r\n                                        <th style=\"width: 40px;\">T.A.</th>\r\n                                    </tr>\r\n                                </thead>\r\n                                <tfoot>\r\n                                    <tr>\r\n                                        <th>Ref.</th>\r\n                                        <th>Invoice ID</th>\r\n                                        <th>Amount</th>\r\n                                        <th>INV DISC</th>\r\n                                        <th>Customer</th>\r\n                                        <th>Description</th>\r\n                                        <th>Date</th>\r\n                                        <th>QTY</th>\r\n                                        <th>Unit price</th>\r\n                                        <th>Discount</th>\r\n                                        <th>TAX</th>\r\n                                        <th>T.A.</th>\r\n                                    </tr>\r\n                                </tfoot>\r\n                                <tbody></tbody>\r\n                            </table>\r\n                        </div>\r\n                    </div>\r\n                </div>\r\n            </div>\r\n        </div>\r\n        \r\n        <div class=\"sk-circle-layer\">\r\n            <div class=\"sk-circle\">\r\n                <div class=\"sk-circle1 sk-child\"></div>\r\n                <div class=\"sk-circle2 sk-child\"></div>\r\n                <div class=\"sk-circle3 sk-child\"></div>\r\n                <div class=\"sk-circle4 sk-child\"></div>\r\n                <div class=\"sk-circle5 sk-child\"></div>\r\n                <div class=\"sk-circle6 sk-child\"></div>\r\n                <div class=\"sk-circle7 sk-child\"></div>\r\n                <div class=\"sk-circle8 sk-child\"></div>\r\n                <div class=\"sk-circle9 sk-child\"></div>\r\n                <div class=\"sk-circle10 sk-child\"></div>\r\n                <div class=\"sk-circle11 sk-child\"></div>\r\n                <div class=\"sk-circle12 sk-child\"></div>\r\n            </div>\r\n        </div>\r\n    </body>\r\n</html>\r\n\r\n";

?>