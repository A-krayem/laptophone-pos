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
echo "<!DOCTYPE HTML>\r\n<html>\r\n    <head>\r\n        <title>VI Report</title>\r\n        <meta charset=\"UTF-8\">\r\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n        <link rel=\"icon\" type=\"image/png\" href=\"resources/favicon.png\">\r\n        <script src=\"libraries/jquery-3.1.1.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-3.3.7-dist/js/bootstrap.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-3.3.7-dist/css/bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n\r\n        <!-- Include Date Range Picker -->\r\n        <script src=\"libraries/bootstrap-plugins/daterangepicker-master/moment.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-plugins/daterangepicker-master/daterangepicker.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/daterangepicker-master/daterangepicker.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        \r\n        \r\n        <script src=\"libraries/bootstrap-plugins/bootstrap-sweetalert-master/dist/sweetalert.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/bootstrap-sweetalert-master/dist/sweetalert.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n\r\n        <style type=\"text/css\">\r\n\r\n            @page { margin: 0 }\r\n            body { \r\n                margin: 0 ;\r\n            }\r\n            \r\n            .invtable tr th{\r\n               border: 1px solid #ccc; \r\n               padding-left: 5px;\r\n            }\r\n        </style>\r\n        <script type=\"text/javascript\">\r\n            \r\n            function row_select(object){\r\n                \$(\".bgselected\").removeClass(\"bgselected\");\r\n                \$(object).addClass(\"bgselected\");\r\n            }\r\n            \r\n            \$(document).ready(function() {\r\n                var start = moment();\r\n                var end = moment();\r\n                \r\n                var __start = \"\";\r\n                var __end= \"\";\r\n                \r\n                ";
if ($_GET["p0"] != "today") {
    $daterange = $_GET["p0"];
    $date_range[0] = NULL;
    $date_range[1] = NULL;
    $date_range_tmp = explode(" - ", $daterange);
    $date_range[0] = date("Y-m-d", strtotime(trim($date_range_tmp[0])));
    $date_range[1] = date("Y-m-d", strtotime(trim($date_range_tmp[1])));
    $__start = strtotime($date_range[0]);
    $__end = strtotime($date_range[1]);
}
echo "                    \r\n                ";
if ($_GET["p0"] != "today") {
    echo "  \r\n                start = moment.unix(";
    echo $__start;
    echo ").format(\"YYYY-MM-DD\");\r\n                end =  moment.unix(";
    echo $__end;
    echo ").format(\"YYYY-MM-DD\");\r\n                ";
}
echo "          \r\n                \r\n                \$('#date_filter').daterangepicker({\r\n                    dateLimit:{month:24},\r\n                    startDate: start,\r\n                    endDate: end,\r\n                    locale: {\r\n                        format: 'YYYY-MM-DD'\r\n                    },\r\n                    ranges: {\r\n                        'Today': [moment(), moment()],\r\n                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],\r\n                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],\r\n                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],\r\n                        'This Month': [moment().startOf('month'), moment().endOf('month')],\r\n                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]\r\n                     }\r\n                });\r\n\r\n                \$(\"#date_filter\").change(function() {\r\n                    refresh_report();\r\n                    update_dt();\r\n                });\r\n                \r\n                update_dt();\r\n            });\r\n            \r\n            function update_dt(){\r\n                var tmpval = \$(\"#date_filter\").val();\r\n                tmpval = \$(\"#date_filter\").val().split(\" - \");\r\n                \r\n                \$(\"#sdate\").html(tmpval[0]);\r\n                \$(\"#edate\").html(tmpval[1]);\r\n            }\r\n            \r\n            function refresh_report(){\r\n                window.location.replace(\"index.php?r=vi&f=generate_vi&p0=\"+\$(\"#date_filter\").val());\r\n            }\r\n            \r\n            \r\n        </script>\r\n    </head>\r\n    <body>\r\n        <table style=\"margin-top: 10px; margin-left: 5px;\">\r\n            <tr>\r\n                <th style=\"width: 100px;\">\r\n                    <input autocomplete=\"off\" id=\"date_filter\" class=\"tohide\" type=\"text\" placeholder=\"\" style=\"cursor:pointer;width:200px;margin-bottom: 5px;\" value=\"\" />\r\n                </th>\r\n            </tr>\r\n        </table>\r\n        <table style=\"margin-top: 10px; margin-left: 5px;\" class=\"invtable\">\r\n            <tr>\r\n                <th style=\"width: 100px;\">\r\n                    Invoice ID\r\n                </th>\r\n                <th style=\"width: 120px;\">\r\n                    Invoice Date\r\n                </th>\r\n                <th style=\"width: 100px;\">\r\n                    Amount\r\n                </th>\r\n                <th style=\"width: 100px;\">\r\n                    Discount\r\n                </th>\r\n                <th style=\"width: 120px;\">\r\n                    Total Amount\r\n                </th>\r\n            </tr>\r\n        </table>\r\n    </body>\r\n</html>";

?>