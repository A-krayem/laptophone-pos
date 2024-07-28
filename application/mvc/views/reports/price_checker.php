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
echo "<!DOCTYPE html>\n<html lang=\"en\">\n<head>\n    <meta charset=\"UTF-8\">\n    <link rel=\"icon\" type=\"image/png\" href=\"resources/favicon.png\">\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n    \n    <script src=\"libraries/jquery-3.1.1.min.js\" type=\"text/javascript\"></script>\n    <script src=\"application/mvc/views/reports/bootstrap-5.3.2-dist/popper.min.js\"></script>\n    <script src=\"application/mvc/views/reports/bootstrap-5.3.2-dist/js/bootstrap.min.js\" type=\"text/javascript\"></script>\n    \n    \n    <link href=\"application/mvc/views/reports/bootstrap-5.3.2-dist/css/bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\n    \n    <title>Dashboard</title>\n    \n    <style type=\"text/css\">\n     \n    </style>\n    \n    <script type=\"text/javascript\">\n        \n        \n    \$(document).ready(function () {\n        \n        \$(\"#barcode\").focus();\n        \n        \$('#barcode').on('blur', function(event) {\n            event.preventDefault();\n            \$(\"#barcode\").focus();\n        });\n        \n        \n        \n    });\n    \n    var tm=null;\n    function get_info(){\n        if(tm!=null){\n            clearInterval(tm);\n        }\n        tm = setTimeout(function(){\n            var _data=[];\n            \$.getJSON(\"?r=price_checker&f=price_checker&p0=\"+\$(\"#barcode\").val(), function (data) {\n                _data=data;\n            }).done(function () {\n                \$(\"#barcode\").val(\"\");\n                if(_data.length>0){\n                    \$(\"#description\").html(_data[0].description);\n                    \$(\"#selling_price\").html(_data[0].selling_price);\n                    \$(\"#selling_price_lbp\").html(_data[0].selling_price_lbp);\n                    \n                    \$(\"#usd_lbp_rate\").html(_data[0].usd_lbp_rate);\n                    \n                    \$(\"#quantity\").html(_data[0].quantity);\n                    \n                    \n                    \n                    \$(\"#barcodetxt\").html(_data[0].barcode);\n                }else{\n                    \$(\"#usd_lbp_rate\").html(\"-\");\n                    \$(\"#description\").html(\"-\");\n                    \$(\"#selling_price\").html(\"-\");\n                    \$(\"#selling_price_lbp\").html(\"-\");\n                    \$(\"#quantity\").html(\"-\");\n                    \$(\"#barcodetxt\").html(\"-\");\n                }\n                \n                \n                \n            }); \n        },500);\n    }\n    \n    </script>\n    \n    \n</head>\n<body>\n\n    <nav class=\"navbar navbar-expand-lg navbar-light bg-light\">\n        <div class=\"container-fluid\">\n            <a class=\"navbar-brand\" href=\"#\">PRICE CHECKER</a>\n            <button class=\"navbar-toggler\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#navbarSupportedContent\" aria-controls=\"navbarSupportedContent\" aria-expanded=\"false\" aria-label=\"Toggle navigation\">\n                <span class=\"navbar-toggler-icon\"></span>\n            </button>\n        </div>\n    </nav>\n    \n    <div class=\"container-fluid\">\n\n            <div class=\"row mt-3\">\n                <div class=\"col-3\">\n                    &nbsp;                \n                </div>\n                <div class=\"col-6\">\n                    <input placeholder=\"Scan Barcode for item info\" class=\"form-control p-2 m-2 text-black shadow rounded-2\" id=\"barcode\" oninput=\"get_info()\" />\n                </div>\n                <div class=\"col-3\">\n                    &nbsp;                \n                </div>\n            </div>\n        \n            ";
if ($data["description"]) {
    echo "                <div class=\"row mt-4\">\n\n                    <div class=\"col-12 text-center\">\n                        <h5><b>ITEM DESCRIPTION / وصف المادة</b></h5>                \n                    </div>\n                    <div class=\"col-12 text-center\">\n                        <h4 id=\"description\" class=\"text-info\">-</h4>   \n                    </div>\n                </div>\n            ";
}
echo "        \n            ";
if ($data["barcode"]) {
    echo "            <div class=\"row mt-4\">\n          \n                <div class=\"col-12 text-center\">\n                    <h5><b>BARCODE / باركود</b></h5>                \n                </div>\n                <div class=\"col-12 text-center\">\n                    <h4 id=\"barcodetxt\" class=\"text-info\">-</h4>   \n                </div>\n            </div>\n            ";
}
echo "        \n            ";
if ($data["price"]) {
    echo "            <div class=\"row mt-4\">\n                <div class=\"col-12  text-center\">\n                    <h5><b>PRICE / السعر</b> <b>بالدولار الامريكي</b></h5>                \n                </div>\n                <div class=\"col-12  text-center\">\n                    <h4 id=\"selling_price\" class=\"text-info\">-</h4>   \n                </div>\n            </div>\n            ";
}
echo "        \n            ";
if ($data["rate"]) {
    echo "            <div class=\"row mt-4\">\n                <div class=\"col-12  text-center\">\n                    <h5><b>EXCHANGE RATE</b> / <b> سعر الصرف</b></h5>                \n                </div>\n                <div class=\"col-12  text-center\">\n                    <h4 id=\"usd_lbp_rate\" class=\"text-info\">-</h4>   \n                </div>\n            </div>\n            ";
}
echo "        \n            ";
if ($data["price_lbp"]) {
    echo "            <div class=\"row mt-4\">\n                <div class=\"col-12  text-center\">\n                    <h5><b>PRICE / السعر </b> <b>بالليرة البنانية</b></h5>                \n                </div>\n                <div class=\"col-12  text-center\">\n                    <h4 id=\"selling_price_lbp\" class=\"text-info\">-</h4>   \n                </div>\n            </div>\n            ";
}
echo "        \n            ";
if ($data["qty"]) {
    echo "            <div class=\"row mt-4\">\n                <div class=\"col-12 text-center\">\n                    <h5><b>AVAILABLE QUANTITY / الكمية المتوفرة</b></h5>                \n                </div>\n                <div class=\"col-12 text-center\">\n                    <h4 id=\"quantity\" class=\"text-info\">-</h4>   \n                </div>\n            </div>\n            ";
}
echo "        \n            \n        \n        \n    </div>\n    \n\n\n</body>\n</html>\n";

?>