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
echo "<!DOCTYPE html>\r\n<!--\r\nTo change this license header, choose License Headers in Project Properties.\r\nTo change this template file, choose Tools | Templates\r\nand open the template in the editor.\r\n-->\r\n<html>\r\n    <head>\r\n        <title>U-POS</title>\r\n        <meta charset=\"UTF-8\">\r\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n        <link rel=\"icon\" type=\"image/png\" href=\"resources/favicon.png\">\r\n        \r\n        <meta name=\"viewport\" content=\"width=device-width, user-scalable=no\">\r\n        \r\n        <meta http-equiv=\"cache-control\" content=\"max-age=0\" />\r\n        <meta http-equiv=\"cache-control\" content=\"no-cache\" />\r\n        <meta http-equiv=\"expires\" content=\"0\" />\r\n        <meta http-equiv=\"expires\" content=\"Tue, 01 Jan 1980 1:00:00 GMT\" />\r\n        <meta http-equiv=\"pragma\" content=\"no-cache\" />\r\n        \r\n        <link rel=\"icon\" href=\"upsilon-logo.jpg\">\r\n  \r\n        <link href=\"application/mvc/views/pos/upsilon-pos/css.css?rnd=";
echo self::generateRandomStringComplex();
echo "\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        <link href=\"libraries/bootstrap-3.3.7-dist/css/bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        \r\n        <script src=\"libraries/jquery-3.1.1.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-3.3.7-dist/js/bootstrap.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"application/mvc/views/pos/upsilon-pos/pos.js?rnd=";
echo self::generateRandomStringComplex();
echo "\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/touche.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"application/mvc/views/custom_libraries/javascripts/global.js?rnd=";
echo self::generateRandomStringComplex();
echo "\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-plugins/bootstrap3-typeahead/bootstrap3-typeahead.min.js\" type=\"text/javascript\"></script>\r\n        \r\n        <link href=\"libraries/bootstrap-plugins/datepicker-master/dist/css/bootstrap-datepicker.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        <script src=\"libraries/bootstrap-plugins/datepicker-master/dist/js/bootstrap-datepicker.min.js\" type=\"text/javascript\"></script>\r\n        \r\n        <script src=\"libraries/bootstrap-plugins/bootstrap-select-master/bootstrap-select-master/dist/js/bootstrap-select.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/bootstrap-select-master/bootstrap-select-master/dist/css/bootstrap-select.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        \r\n        <link href=\"application/mvc/views/custom_libraries/svgs/font/style.css?rnd=";
echo self::generateRandomStringComplex();
echo "\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        <script src=\"libraries/numeric.js?rnd=";
echo self::generateRandomStringComplex();
echo "\" type=\"text/javascript\"></script>\r\n        \r\n        <script src=\"libraries/jQuery-Scanner-Detection-master/jQuery-Scanner-Detection-master/jquery.scannerdetection.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/jquery.mask.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/bootstrap-sweetalert-master/dist/sweetalert.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        <script src=\"libraries/bootstrap-plugins/bootstrap-sweetalert-master/dist/sweetalert.min.js\" type=\"text/javascript\"></script>\r\n        \r\n        <script src=\"application/mvc/views/pos/upsilon-pos/purchases.js?rnd=";
echo self::generateRandomStringComplex();
echo "\" type=\"text/javascript\"></script>\r\n        <script src=\"application/mvc/views/pos/upsilon-pos/expenses.js?rnd=";
echo self::generateRandomStringComplex();
echo "\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/jquery.mask.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/jquery-play-sound-master/jquery.playSound.js\" type=\"text/javascript\"></script>\r\n        \r\n        <link href=\"libraries/touchKeyboard-master/jqbtk.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        \r\n        <script src=\"libraries/bootstrap-plugins/DataTables-1.10.13/media/js/jquery.dataTables.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-plugins/DataTables-1.10.13/media/js/dataTables.bootstrap.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/DataTables-1.10.13/media/css/dataTables.bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        \r\n        \r\n        \r\n        <script src=\"libraries/touchKeyboard-master/jqbtk.js\" type=\"text/javascript\"></script>\r\n        <style type=\"text/css\">\r\n            html,body {\r\n                font-family: '";
echo $data["settings"]["language"];
echo "', en !important;\r\n            }\r\n            @font-face {\r\n                ";
if ($data["settings"]["language"] == "ar") {
    echo "                    font-family: ";
    echo $data["settings"]["language"];
    echo ";\r\n                    src: url(application/mvc/views/custom_libraries/font/MoGpUcTu_oZLf0bsrG2xFQ.woff2);\r\n                ";
}
echo "            }\r\n            \r\n            @font-face {\r\n                font-family: \"en\";\r\n                src: url(application/mvc/views/custom_libraries/font/Abel.woff2);\r\n            }\r\n            \r\n            #header div{\r\n                ";
if ($data["settings"]["language"] == "ar") {
    echo "                    text-align: right !important;\r\n                ";
}
echo "            }\r\n            \r\n            #p_items div{\r\n                ";
if ($data["settings"]["language"] == "ar") {
    echo "                    text-align: right !important;\r\n               \r\n                ";
}
echo "            }\r\n            \r\n            .purchases div{\r\n                ";
if ($data["settings"]["quick_access_col"] == 1) {
    echo "                    ";
    if ($data["settings"]["language"] == "ar") {
        echo "                        font-size: 22px;\r\n                        height: 36px;\r\n                    ";
    } else {
        echo "                        font-size: 16px; \r\n                        height: 30px;\r\n                    ";
    }
    echo "                ";
} else {
    echo "                    ";
    if ($data["settings"]["language"] == "ar") {
        echo "                        font-size: 22px;\r\n                        height: 36px;\r\n                    ";
    } else {
        echo "                        font-size: 22px;\r\n                        height: 36px;\r\n                    ";
    }
    echo "                ";
}
echo "            }\r\n            \r\n            .payValue{\r\n                ";
if ($data["settings"]["quick_access_col"] == 1) {
    echo "                    ";
    if ($data["settings"]["language"] == "ar") {
        echo "                        font-size: 1.2vw !important; \r\n                    ";
    } else {
        echo "                        font-size: 1.5vw !important; \r\n                    ";
    }
    echo "                ";
} else {
    echo "                    ";
    if ($data["settings"]["language"] == "ar") {
        echo "                        font-size: 1.8vw !important; \r\n                    ";
    } else {
        echo "                        font-size: 1.7vw !important; \r\n                    ";
    }
    echo "                ";
}
echo "            }\r\n            \r\n            .blueButton{\r\n                ";
if ($data["settings"]["quick_access_col"] == 1) {
    echo "                    ";
    if ($data["settings"]["language"] == "ar") {
        echo "                        font-size: 1.3vw !important; \r\n                    ";
    } else {
        echo "                        font-size: 1.5vw !important; \r\n                    ";
    }
    echo "                ";
} else {
    echo "                    ";
    if ($data["settings"]["language"] == "ar") {
        echo "                        font-size: 1.8vw !important; \r\n                    ";
    } else {
        echo "                        font-size: 2.0vw !important; \r\n                    ";
    }
    echo "                ";
}
echo "            }\r\n            \r\n            \r\n            .grayButton{\r\n                ";
if ($data["settings"]["quick_access_col"] == 1) {
    echo "                    ";
    if ($data["settings"]["language"] == "ar") {
        echo "                        font-size: 1.3vw !important; \r\n                    ";
    } else {
        echo "                        font-size: 1.5vw !important; \r\n                    ";
    }
    echo "                ";
} else {
    echo "                    ";
    if ($data["settings"]["language"] == "ar") {
        echo "                        font-size: 1.8vw !important; \r\n                    ";
    } else {
        echo "                        font-size: 2.0vw !important; \r\n                    ";
    }
    echo "                ";
}
echo "            }\r\n            \r\n            .purchasedItemsListDelete{\r\n                ";
if ($data["settings"]["language"] == "ar") {
    echo "                    float: right !important;\r\n                ";
} else {
    echo "                    float: left !important;\r\n                ";
}
echo "            }\r\n            \r\n            .func {\r\n                ";
if ($data["settings"]["language"] == "ar") {
    echo "                    direction: rtl !important;\r\n                ";
}
echo "            }\r\n            \r\n            #header div{\r\n                ";
if ($data["settings"]["language"] == "ar") {
    echo "                    font-size: 20px !important;\r\n                ";
}
echo "            }\r\n            \r\n            .purchases div{\r\n                ";
if ($data["settings"]["language"] == "ar") {
    echo "                    font-size: 18px !important;\r\n                    padding-top: 5px !important;\r\n                ";
}
echo "            }\r\n        </style>\r\n        <script type=\"text/javascript\">\r\n            var inv = null;\r\n            var store_id = null;\r\n            \r\n            var lang = \"";
echo $data["settings"]["language"];
echo "\";\r\n            \r\n            var settings_pf = null;\r\n            var settings_pp = null;\r\n            var settings_pl = null;\r\n            var settings_cc = null;\r\n            var auto_print = null;\r\n            var default_currency_symbol = null;\r\n            var lockMainPos = false;\r\n            var cashBox = null;\r\n            var cashBoxTotal = null;\r\n            \r\n            var invoicesOnHold = [];\r\n            var invoicesOnHold_Index = 1;\r\n            var pull_ = \"\";\r\n            var opull_ = \"\";\r\n            var dir_ = \"\";\r\n            var float_ = \"right\";\r\n            var ofloat_ = \"left\";\r\n            var direction_ = \"direction:ltr;\";\r\n            var licenseExpired = false;\r\n            var enable_wholasale = false;\r\n            \r\n            var JS_LG_SEARCH = \"";
echo LG_SEARCH;
echo "\";\r\n            var LG_MANUAL_BARCODE = \"";
echo LG_MANUAL_BARCODE;
echo "\";\r\n            var LG_ADD = \"";
echo LG_ADD;
echo "\";\r\n            var LG_CANCEL = \"";
echo LG_CANCEL;
echo "\";\r\n            var LG_ARE_YOU_SURE = \"";
echo LG_ARE_YOU_SURE;
echo "\";\r\n            var LG_YES = \"";
echo LG_YES;
echo "\";\r\n            var LG_ADD_EXPENSE = \"";
echo LG_ADD_EXPENSE;
echo "\";\r\n            var LG_SALES_ITEM = \"";
echo LG_SALES_ITEM;
echo "\";\r\n            var LG_EXPENSES = \"";
echo LG_EXPENSES;
echo "\";\r\n            var LG_SALES = \"";
echo LG_SALES;
echo "\";\r\n            var LG_TOTAL_QTY = \"";
echo LG_TOTAL_QTY;
echo "\";\r\n            var LG_SETTOTALQTY = \"";
echo LG_SETTOTALQTY;
echo "\";\r\n            var LG_PAY = \"";
echo LG_PAY;
echo "\";\r\n            var LG_PHONE = \"";
echo LG_PHONE;
echo "\";\r\n            var LG_CUSTOMER_MAME = \"";
echo LG_CUSTOMER_MAME;
echo "\";\r\n            var LG_ADDRESS = \"";
echo LG_ADDRESS;
echo "\";\r\n            var LG_TOTAL_AMOUNT = \"";
echo LG_TOTAL_AMOUNT;
echo "\";\r\n            var LG_TOTAL_AFTER_INVOICE_DISCOUNT = \"";
echo LG_TOTAL_AFTER_INVOICE_DISCOUNT;
echo "\";\r\n            \r\n            var LG_LATER_PAYMENT = \"";
echo LG_LATER_PAYMENT;
echo "\";\r\n            var LG_CREDIT_CARD = \"";
echo LG_CREDIT_CARD;
echo "\";\r\n            var LG_CASH = \"";
echo LG_CASH;
echo "\";\r\n            \r\n            \r\n            var user_role =  ";
echo $_SESSION["role"];
echo ";\r\n            \r\n                licenseExpired = false;\r\n                \r\n            \r\n            \r\n            \$(document).ready(function () {\r\n\r\n                if(lang==\"ar\"){\r\n                    pull_ =\"pull-right\";\r\n                    opull_ = \"pull-left\";\r\n                    dir_ = \"dir='rtl'\";\r\n                    float_ = \"left\";\r\n                    ofloat_ = \"right\";\r\n                    direction_ = \"direction:rtl;\";\r\n                }\r\n                \r\n                inv = new Invoice();\r\n                \$.getJSON(\"?r=pos&f=getSettingsForPos\", function (data) {\r\n                     settings_pf = data.payment_full;\r\n                     settings_pl = data.payment_later;\r\n                     settings_cc = data.payment_credit_card;\r\n                     enable_wholasale = data.enable_wholasale;\r\n            \r\n                     if(settings_pl==1)\r\n                        \$(\"#addPaymentSection\").show();\r\n                     \r\n                     auto_print = data.auto_print;\r\n                     default_currency_symbol = data.default_currency_symbol;\r\n                }).done(function () {\r\n                     \$.getJSON(\"?r=pos&f=getStoreId\", function (data) {\r\n                        store_id = data.store_id;\r\n                        cashBox =  data.cashbox;\r\n                        cashBoxTotal =  data.cashBoxTotal;\r\n                    }).done(function () {\r\n                        if(cashBox == 0){\r\n                            setCashbox();\r\n                        }else{\r\n                            //\$(\"#cashboxTotal\").html(cashBoxTotal);\r\n                            \$(\"#cashboxContainer\").show();\r\n                     \r\n                        }\r\n                        init();\r\n                    }); \r\n                    \r\n                }); \r\n\r\n                \$(\"#date_time\").html(getDate_time());\r\n                setInterval(function(){\$(\"#date_time\").html(getDate_time());},1000);\r\n                \r\n                \r\n                setTimeout(function(){\r\n                    tuneHeight();\r\n                },100);\r\n                \r\n                /*\r\n                var tt = 1;\r\n                setInterval(function(){\r\n                    if(tt<100){\r\n                        inv.getItemById(tt);\r\n                        tt++;\r\n                    }\r\n                },1000);*/\r\n                \r\n                //setTimeout(function(){\r\n                    //testPLU(\"2700010005006\");\r\n                    //testPLU(\"029000021594\");\r\n                    \r\n                //},1000); \r\n            });\r\n            \r\n            \r\n            function testPLU(plucode){\r\n                inv.getItemByBarcode(plucode);\r\n            }\r\n            \r\n            \$( window ).resize(function() {\r\n                tuneHeight();\r\n            });\r\n            \r\n            function getDate_time(){\r\n                var currentdate = new Date();\r\n                \r\n                var monthNames = [\"January\", \"February\", \"March\", \"April\", \"May\", \"June\",\r\n                    \"July\", \"August\", \"September\", \"October\", \"November\", \"December\"\r\n                ];\r\n                \r\n                var month = currentdate.getMonth()+1;\r\n                if(parseInt(month)<10) month=\"0\"+month;\r\n                \r\n                \r\n                var hours = currentdate.getHours()+1;\r\n                if(parseInt(hours)<10) hours=\"0\"+hours;\r\n                \r\n                var minutes = currentdate.getMinutes()+1;\r\n                if(parseInt(minutes)<10) minutes=\"0\"+minutes;\r\n                \r\n                var seconds = currentdate.getSeconds()+1;\r\n                if(parseInt(seconds)<10) seconds=\"0\"+seconds;\r\n                \r\n                return datetime = currentdate.getDate() + \" \" + month\r\n                + \"th\" + \" \" + monthNames[currentdate.getMonth()+1]+ \" \" \r\n                + currentdate.getFullYear() + \"&nbsp;&nbsp;&nbsp;&nbsp;\"  \r\n                + hours + \":\"  \r\n                + minutes + \":\" \r\n                + seconds;\r\n            }\r\n            \r\n            function tuneHeight(){\r\n                \$(\".container-fluid\").show();\r\n                var viewportHeight = \$(window).height();\r\n                \$(\"#p_items\").height((viewportHeight-\$(\"#company_header\").height()-\$(\".rowC\").height()-\$(\".row\").height()-\$(\".rowC2\").height()-\$(\".row\").height()-\$(\".rowC3\").height()-\$(\".rowC5\").height()-20)+\"px\"); //\$(\".rowC3\").height()\r\n                ///alert((viewportHeight+\"-\"+\$(\"#company_header\").height()+\"-\"+\$(\".rowC\").height()+\"-\"+\$(\".row\").height()+\"-\"+\$(\".rowC2\").height()+\"-\"+\$(\".row\").height()+\"-\"+\$(\".rowC3\").height()+\"-\"+\$(\".rowC5\").height()+\"-\"+20)+\"px\");\r\n            }\r\n            \r\n            function closeCashbox(){\r\n                swal({\r\n                    title: LG_ARE_YOU_SURE,\r\n                    text: \"\",\r\n                    type: \"warning\",\r\n                    showCancelButton: true,\r\n                    confirmButtonClass: \"btn-danger\",\r\n                    confirmButtonText: LG_YES,\r\n                    closeOnConfirm: true,\r\n                    cancelButtonText: LG_CANCEL,\r\n                },\r\n                function(){\r\n                    setTimeout(function(){\r\n                        swal({\r\n                            title: \"Backup\",\r\n                            text: \"Create a Full Database Backup before closing cashbox?\",\r\n                            type: \"warning\",\r\n                            showCancelButton: true,\r\n                            confirmButtonClass: \"btn-info\",\r\n                            confirmButtonText: \"Backup now\",\r\n                            cancelButtonText: \"Close cashbox without backup\",\r\n                            closeOnConfirm: false\r\n                          },\r\n                        function(isconfirm){\r\n                            if(isconfirm){\r\n                                var status = null;\r\n                                swal(\"Don't press anything, Please wait...\");\r\n                                \$.getJSON(\"?r=pos&f=backupNow\", function (data) {\r\n                                    status = data.status;\r\n                                }).done(function () {\r\n                                    if(status==0){\r\n                                        swal({\r\n                                            title: \"Backup Failed\",\r\n                                            text: \"Please contact system provider\",\r\n                                            type: \"warning\",\r\n                                            showCancelButton: false,\r\n                                            confirmButtonClass: \"btn-danger\",\r\n                                            confirmButtonText: \"Ok\",\r\n                                            closeOnConfirm: true\r\n                                        },\r\n                                        function(){\r\n                                            \$.getJSON(\"?r=pos&f=closeCashbox\", function (data) {\r\n\r\n                                            }).done(function () {\r\n                                                window.location = \"?r=pos&f=logout\";\r\n                                            });\r\n                                        });\r\n                                    }else{\r\n                                        setTimeout(function(){\r\n                                            \$.getJSON(\"?r=pos&f=closeCashbox\", function (data) {\r\n\r\n                                            }).done(function () {\r\n                                                window.location = \"?r=pos&f=logout\";\r\n                                            });\r\n                                        },300);\r\n                                    }\r\n                                });\r\n                            }else{\r\n                                setTimeout(function(){\r\n                                    \$.getJSON(\"?r=pos&f=closeCashbox\", function (data) {\r\n\r\n                                    }).done(function () {\r\n                                        window.location = \"?r=pos&f=logout\";\r\n                                    });\r\n                                },300);\r\n                            }\r\n\r\n                        });\r\n                    },300);\r\n                }); \r\n            }\r\n            \r\n            function submitCashbox(){\r\n                var cashbox_value = \$(\"#cashbox_\").unmask().val();\r\n                \$('#cashbox_').mask('000,000,000,000,000 '+default_currency_symbol, {reverse: true});\r\n                \$.getJSON(\"?r=pos&f=setCashbox&p0=\"+cashbox_value, function (data) {\r\n                    \r\n                }).done(function () {\r\n                    //\$('#cashboxModal').modal('toggle');\r\n                    //\$(\"#cashboxContainer\").show();\r\n                    window.location = \"?r=pos\";\r\n                }); \r\n            }\r\n            \r\n            function setCashbox(){\r\n                var content =\r\n                    '<div class=\"modal fade\" id=\"cashboxModal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"payment_info__\" aria-hidden=\"true\">\\n\\\r\n                    <div class=\"modal-dialog\" role=\"document\">\\n\\\r\n                        <div class=\"modal-content\">\\n\\\r\n                            <div class=\"modal-header\"> \\n\\\r\n                                <h3 class=\"modal-title\"><i class=\" icon-cashbox\"></i>&nbsp;Set cashbox<i style=\"float:right;font-size:30px\" class=\"glyphicon glyphicon-remove\" onclick=\"removecashboxModal()\"></i></h3>\\n\\\r\n                            </div>\\n\\\r\n                            <div class=\"modal-body\"><input autocomplete=\"off\" id=\"cashbox_\" value=\"0\" name=\"cashbox_\" type=\"text\" class=\"form-control only_numeric\" style=\"font-size:45px !important; height:60px;\"></div>\\n\\\r\n                            <div class=\"modal-footer\">\\n\\\r\n                                    <button onclick=\"submitCashbox()\" type=\"button\" class=\"btn btn-secondary\" style=\"width:210px;font-size:22px !important;\">Set cashbox</button> \\n\\\r\n                            </div>\\n\\\r\n                        </div>\\n\\\r\n                    </div>\\n\\\r\n                </div>';\r\n                \$(\"#cashboxModal\").remove();\r\n                \$(\"body\").append(content);\r\n                \$(\".only_numeric\").numeric();\r\n                \$(\"#cashboxModal\").centerWH();\r\n                \$('#cashboxModal').modal('toggle');\r\n                \r\n                \$('#cashboxModal').on('hidden.bs.modal', function (e) {\r\n                    \$('#cashboxModal').remove();\r\n                });\r\n                \r\n                setTimeout(function(){\r\n                    \$(\"#cashbox_\").select();\r\n                    \$('#cashbox_').mask('000,000,000,000,000 '+default_currency_symbol, {reverse: true});\r\n                    \$(\"#cashbox_\").select();\r\n                },500);\r\n            }\r\n\r\n            function removecashboxModal(){\r\n                \$('#cashboxModal').modal('toggle');\r\n            }\r\n            \r\n            function initScannerInput(){\r\n                \$(document).scannerDetection({\r\n                    timeBeforeScanTest: 200, // wait for the next character for upto 200ms\r\n                    endChar: [13], // be sure the scan is complete if key 13 (enter) is detected\r\n                    avgTimeByChar: 40, // it's not a barcode if a character takes longer than 40ms\r\n                    ignoreIfFocusOn: 'input', // turn off scanner detection if an input has focus\r\n                    onComplete: function(barcode, qty){ \r\n                        if(cashBox == 0){\r\n                            setCashbox();\r\n                        }else{\r\n                            inv.getItemByBarcode(barcode); \r\n                        }\r\n                    }, // main callback function\r\n                    scanButtonKeyCode: 116, // the hardware scan button acts as key 116 (F5)\r\n                    scanButtonLongPressThreshold: 5, // assume a long press if 5 or more events come in sequence\r\n                    //52850003285445285000328544onScanButtonLongPressed: showKeyPad, // callback for long pressing the scan button\r\n                    onError: function(string){}\t\r\n                });\r\n            }\r\n            \r\n            function init(){\r\n                \$(\".func\").on('click', function (e) {\r\n                        if(cashBox == 0){\r\n                            setCashbox();\r\n                        }else{\r\n                            inv.getItemById(\$(this).attr('id'));\r\n                        }\r\n                        \r\n                    });\r\n                    keyboardEvents();\r\n                    \r\n                    \$(\"*\").disableSelection();\r\n                    \r\n                    initScannerInput();\r\n                    getCustomItems();\r\n            }\r\n            \r\n            function getCustomItems(){\r\n                \$.getJSON(\"?r=pos&f=get_custom_items&p0=\"+store_id, function (data) {\r\n                    \$.each(data, function (key, val) {\r\n                        \$(\"#r_content\").append(\"<div class='col-lg-4 col-md-4 col-sm-4 col-xs-4 funcContainer'><div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 func' id='\"+val.item_id+\"'>\"+val.description+\" <p>\"+val.selling_price+\"</p></div></div>\");\r\n                    });\r\n                }).done(function () {\r\n                    \$(\".func\").on('click', function (e) {\r\n                        if(cashBox == 0){\r\n                            setCashbox();\r\n                        }else{\r\n                            inv.getItemById(\$(this).attr('id'));\r\n                        }\r\n                    });\r\n                });\r\n            }\r\n            \r\n            function goToPage(page){\r\n                swal({\r\n                    title: LG_ARE_YOU_SURE,\r\n                    text: \"\",\r\n                    type: \"warning\",\r\n                    showCancelButton: true,\r\n                    confirmButtonClass: \"btn-danger\",\r\n                    confirmButtonText: LG_YES,\r\n                    closeOnConfirm: true,\r\n                    cancelButtonText: LG_CANCEL,\r\n                },\r\n                function(){\r\n                   window.location = \"?r=\"+page;\r\n                });\r\n            }\r\n\r\n            function holdInvoice(){\r\n                if(inv.getData().length>0){\r\n                    var timestamp = Math.floor(Date.now() / 1000);\r\n                    invoicesOnHold.push({\"ID\":timestamp,\"totalQty\":inv.getTotalQtyItems(),\"ALlitems\":inv.getData(),\"Nb\":invoicesOnHold_Index});\r\n                    invoicesOnHold_Index++;\r\n                    \$(\"#holdBtn\").addClass(\"disabledOnHold\");\r\n                    \$(\"#recallBtn\").removeClass(\"disabledOnHold\");\r\n                    inv.reset();\r\n                    \$(\"#recall_nb\").html(invoicesOnHold.length);\r\n                    \$(\"#pay\").addClass(\"disabledPay\");\r\n                }\r\n            }\r\n            \r\n            function recallInvoiceNow(id){\r\n                var invoicesOnHold_tmp = [];\r\n                for(var y=0;y<invoicesOnHold.length;y++){\r\n                    if(invoicesOnHold[y].ID==id){\r\n                        inv.setItems(invoicesOnHold[y].ALlitems);\r\n                    }else{\r\n                        invoicesOnHold_tmp.push(invoicesOnHold[y]);\r\n                    }\r\n                }\r\n                invoicesOnHold = invoicesOnHold_tmp;\r\n                if(invoicesOnHold.length==0){\r\n                    \$(\"#recallBtn\").addClass(\"disabledOnHold\");\r\n                    invoicesOnHold_Index = 1;\r\n                }\r\n                \$(\"#recall_nb\").html(invoicesOnHold.length);\r\n                \$('#RecallModel').modal('toggle');\r\n            }\r\n            \r\n            function _recallInvoice(){\r\n                if(!\$(\"#recallBtn\").hasClass(\"disabledOnHold\")){\r\n                    var invoices_boxes = \"<div class='row'>\";\r\n                    for(var i=0;i<invoicesOnHold.length;i++){\r\n                        invoices_boxes+=\"<div class='col-lg-4 col-md-4 col-sm-4 col-xs-4 invoice_to_call_cont' ><div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 invoice_to_call' onclick='recallInvoiceNow(\"+invoicesOnHold[i].ID+\")'>Pending (\"+invoicesOnHold[i].Nb+\")<br/>\"+invoicesOnHold[i].totalQty+\" items</div></div>\";\r\n                    }\r\n                    invoices_boxes += \"</div>\";\r\n                    var content =\r\n                        '<div class=\"modal fade\" id=\"RecallModel\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"payment_info__\" aria-hidden=\"true\">\\n\\\r\n                        <div class=\"modal-dialog\" role=\"document\">\\n\\\r\n                            <div class=\"modal-content\">\\n\\\r\n                                <div class=\"modal-header\"> \\n\\\r\n                                    <h3 class=\"modal-title\"><i class=\"icon-invoice\"></i>&nbsp;Pending invoices<i style=\"float:right;font-size:30px\" class=\"glyphicon glyphicon-remove\" onclick=\"removeRecallModal()\"></i></h3>\\n\\\r\n                                </div>\\n\\\r\n                                <div class=\"modal-body\">'+invoices_boxes+'</div>\\n\\\r\n                                <div class=\"modal-footer\">\\n\\\r\n                                </div>\\n\\\r\n                            </div>\\n\\\r\n                        </div>\\n\\\r\n                    </div>';\r\n                    \$(\"#RecallModel\").remove();\r\n                    \$(\"body\").append(content);\r\n                    \$(\"#RecallModel\").centerWH();\r\n                    \$('#RecallModel').modal('toggle');\r\n\r\n                    \$('#RecallModel').on('hidden.bs.modal', function (e) {\r\n                        \$('#RecallModel').remove();\r\n                    });\r\n                }\r\n            }\r\n            \r\n            function recallInvoice(){\r\n                if(inv.getData().length>0){\r\n                    swal({\r\n                        title: \"You want to hold the current invoice?\",\r\n                        text: \"\",\r\n                        type: \"warning\",\r\n                        showCancelButton: true,\r\n                        confirmButtonClass: \"btn-danger\",\r\n                        confirmButtonText: LG_YES,\r\n                        closeOnConfirm: true\r\n                    },\r\n                    function(isConfirm){\r\n                       if(isConfirm){\r\n                           holdInvoice();\r\n                           _recallInvoice();\r\n                       }\r\n                    });\r\n                }else{\r\n                    _recallInvoice();\r\n                }\r\n\r\n            }\r\n            \r\n            function removeRecallModal(){\r\n                \$('#RecallModel').modal('toggle');\r\n            }\r\n\r\n        </script>\r\n    </head>\r\n    <body style=\"padding-top: 0px;\">\r\n        ";
$code = self::mc_decrypt($global_settings["activation_code"], ENCRYPTION_KEY_1);
$code_exploded = explode("_", $code);
if ($code_exploded[1] - $global_settings["show_expiry_date_alert_before_days"] < time()) {
    echo "        <div style=\"position: absolute; right: 350px; top: 3px; padding-top: 1px; padding-bottom: 1px; padding-left: 5px; padding-right: 5px; text-align: center; font-size: 16px; color: #fff; background-color: #ffa500; border-radius: 5px; z-index: 9999999; height: 25px;\">\r\n            ";
    echo "<b>Warning: Expiration date on</b> " . date("l jS \\of F Y h:i:s A", $code_exploded[1]);
    echo "                    </div>\r\n        ";
}
echo "\r\n        <div class=\"container-fluid\" style=\"display: none\">\r\n         \r\n            <div id=\"company_header\" class=\"row\" style=\"margin-top: 0px;\">\r\n                <div class=\"col-lg-2 col-md-3 col-sm-4 col-xs-4\" style=\"padding-right: 3px; font-family: en;\">\r\n                    <b style=\"font-size: 20px;\">&upsih;</b><span style=\"font-size: 16px;\">Software Solutions</span>\r\n                </div>\r\n                <div class=\"col-lg-2 col-md-3 col-sm-4 col-xs-4\" style=\"font-size: 20px;\">\r\n                    Welcome <b style=\"color: rgb(217, 83, 79) !important\">";
echo $_SESSION["username"];
echo "</b>\r\n                </div>\r\n                <div class=\"col-lg-8 col-md-6 col-sm-4 col-xs-4\" style=\"font-size: 20px;\">\r\n                    <span id=\"date_time\" style=\"float: right; font-weight: bold\"></span>\r\n                </div>\r\n            </div>\r\n           \r\n            <div class=\"row\" style=\"margin-top: 0px;\">\r\n                <div class=\"";
if ($data["settings"]["quick_access_col"] == 1) {
    echo "col-lg-8 col-md-8 col-sm-8 col-xs-8";
} else {
    echo "col-lg-12 col-md-12 col-sm-12 col-xs-12";
}
echo "\" id=\"l_content\">\r\n                    <div class=\"row rowC\">\r\n                        <div class=\"col-lg-3 col-md-3 col-sm-3 col-xs-3 blueButton ";
if ($data["settings"]["language"] == "ar") {
    echo "pull-right";
}
echo "\" onclick=\"showNoBarcodeItems('only_barcoded')\">\r\n                            <i class=\"glyphicon glyphicon-briefcase blueButton_icon\" style=\"margin-top: 3px; ";
if ($data["settings"]["language"] == "ar") {
    echo "float:right";
}
echo "\"></i>&nbsp;";
echo LG_BARCODED;
echo "                        </div>\r\n                        <div class=\"col-lg-3 col-md-3 col-sm-3 col-xs-3 blueButton ";
if ($data["settings"]["language"] == "ar") {
    echo "pull-right";
}
echo "\" onclick=\"searchBarcode()\">\r\n                            <i class=\"glyphicon glyphicon-barcode blueButton_icon\" style=\"margin-top: 3px; ";
if ($data["settings"]["language"] == "ar") {
    echo "float:right";
}
echo "\"></i>&nbsp;";
echo LG_MANUALBARCODE;
echo "                        </div>\r\n                        <div class=\"col-lg-3 col-md-3 col-sm-3 col-xs-3 blueButton ";
if ($data["settings"]["language"] == "ar") {
    echo "pull-right";
}
echo "\" onclick=\"showNoBarcodeItems('only_non_barcode')\">\r\n                            <i class=\"glyphicon glyphicon-barcode blueButton_icon\" style=\"margin-top: 3px; ";
if ($data["settings"]["language"] == "ar") {
    echo "float:right";
}
echo "\"></i>&nbsp;";
echo LG_NOBARCODE;
echo "                        </div>\r\n                        <div class=\"col-lg-3 col-md-3 col-sm-3 col-xs-3 grayButton ";
if ($data["settings"]["language"] == "ar") {
    echo "pull-right";
}
echo "\" style=\"background-color: #d9534f !important;\" onclick=\"goToPage('pos&f=logout')\">\r\n                           <i class=\"glyphicon glyphicon-log-out blueButton_icon\" style=\" margin-top: 5px; ";
if ($data["settings"]["language"] == "ar") {
    echo "float:right";
}
echo "\"></i> ";
echo LG_EXIT;
echo "                        </div>\r\n                    </div>\r\n                    <div class=\"row rowC1\">\r\n                        <div class=\"col-lg-3 col-md-3 col-sm-3 col-xs-3 grayButton mdisable disableBtn ";
if ($data["settings"]["language"] == "ar") {
    echo "pull-right";
}
echo "\" onclick=\"keyPlus()\">\r\n                            <i class=\"glyphicon glyphicon-plus blueButton_icon\" style=\"margin-top: 3px; ";
if ($data["settings"]["language"] == "ar") {
    echo "float:right";
}
echo "\"></i>";
echo LG_ADDQTY;
echo "                        </div>\r\n                        <div class=\"col-lg-3 col-md-3 col-sm-3 col-xs-3  grayButton mdisable disableBtn ";
if ($data["settings"]["language"] == "ar") {
    echo "pull-right";
}
echo "\" onclick=\"keyMinus()\">\r\n                            <i class=\"glyphicon glyphicon-minus blueButton_icon\" style=\"margin-top: 3px; ";
if ($data["settings"]["language"] == "ar") {
    echo "float:right";
}
echo "\"></i>";
echo LG_REDUCEQTY;
echo "                        </div>\r\n                        <div class=\"col-lg-3 col-md-3 col-sm-3 col-xs-3  grayButton mdisable disableBtn ";
if ($data["settings"]["language"] == "ar") {
    echo "pull-right";
}
echo "\" onclick=\"ManualQty()\">\r\n                            <i class=\"icon-qty blueButton_icon\" style=\"margin-top: 3px; ";
if ($data["settings"]["language"] == "ar") {
    echo "float:right";
}
echo "\"></i>";
echo LG_SETTOTALQTY;
echo "                        </div>\r\n                        <div class=\"col-lg-3 col-md-3 col-sm-3 col-xs-3 grayButton mdisable disableBtn ";
if ($data["settings"]["language"] == "ar") {
    echo "pull-right";
}
echo "\" onclick=\"deleteItem()\">\r\n                            <i class=\"glyphicon glyphicon-trash blueButton_icon\" style=\"margin-top: 3px; ";
if ($data["settings"]["language"] == "ar") {
    echo "float:right";
}
echo "\"></i>&nbsp;";
echo LG_DELETE;
echo "                        </div>\r\n                    </div>\r\n                    \r\n                    <div class=\"row rowC5\" id=\"header\">\r\n                        <div style=\"padding-left: 15px;\" class=\"col-lg-4 col-md-4 col-sm-4 col-xs-4 ";
if ($data["settings"]["language"] == "ar") {
    echo "pull-right";
}
echo "\">";
echo LG_DESCRIPTION;
echo "</div>\r\n                        <div style=\"padding-left: 5px; padding-right: 0px;\" class=\"col-lg-2 col-md-2 col-sm-2 col-xs-2 ";
if ($data["settings"]["language"] == "ar") {
    echo "pull-right";
}
echo "\">";
echo LG_QTY;
echo "</div>\r\n                        <div style=\"padding-left: 5px;\" class=\"col-lg-1 col-md-1 col-sm-1 col-xs-1 ";
if ($data["settings"]["language"] == "ar") {
    echo "pull-right";
}
echo "\">";
echo LG_DISCOUNT;
echo "</div>\r\n                        <div style=\"padding-left: 5px;\" class=\"col-lg-2 col-md-2 col-sm-2 col-xs-2 ";
if ($data["settings"]["language"] == "ar") {
    echo "pull-right";
}
echo "\">";
echo LG_UNITPRICE;
echo "</div>   \r\n                        <div style=\"padding-left: 5px;\" class=\"col-lg-3 col-md-3 col-sm-3 col-xs-3 ";
if ($data["settings"]["language"] == "ar") {
    echo "pull-right";
}
echo "\" style=\"text-align: center\">";
echo LG_TOTAL;
echo "</div>   \r\n                    </div>\r\n                    \r\n                    <div class=\"row\">\r\n                        <div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\" id=\"p_items\"></div>\r\n                    </div>\r\n                    \r\n                    <div class=\"row rowC2\" style=\"margin-top: 5px;\">\r\n                        <div class=\"col-lg-2 col-md-2 col-sm-2 col-xs-2 grayButton ";
if ($data["settings"]["language"] == "ar") {
    echo "pull-right";
}
echo "\" style=\"background-color: rgb(165, 197, 54) !important; font-weight: bold;\" onclick=\"showCashBox()\" id=\"cashboxTotal\">\r\n                            ";
echo LG_CASHBOX;
echo "                        </div>\r\n                        <!--\r\n                        <div class=\"col-lg-3 col-md-3 col-sm-3 col-xs-3 grayButton\"  onclick=\"addQuantity()\">\r\n                            <i class=\"glyphicon glyphicon-plus blueButton_icon\" style=\"margin-top: 3px;\"></i>&nbsp;Add quantity\r\n                        </div>\r\n                        \r\n                        <div class=\"col-lg-3 col-md-3 col-sm-3 col-xs-3 grayButton\" onclick=\"showInstantReport()\">\r\n                            <i class=\"icon-report blueButton_icon\" style=\" margin-top: 5px;\"></i>&nbsp;Instant Report\r\n                        </div>\r\n                        -->\r\n                        <div class=\"col-lg-2 col-md-2 col-sm-2 col-xs-2 grayButton ";
if ($data["settings"]["language"] == "ar") {
    echo "pull-right";
}
echo "\" onclick=\"showPurchasedItem(null,null)\">\r\n                            <i class=\"icon-selling blueButton_icon\" style=\"margin-top: 4px;";
if ($data["settings"]["language"] == "ar") {
    echo "float:right";
}
echo "\"></i>&nbsp;";
echo LG_SALES;
echo "                        </div>\r\n                        <div class=\"col-lg-2 col-md-2 col-sm-2 col-xs-2 grayButton ";
if ($data["settings"]["language"] == "ar") {
    echo "pull-right";
}
echo "\" onclick=\"purchases()\">\r\n                            <i class=\"icon-invoice blueButton_icon\" style=\"margin-top: 4px;";
if ($data["settings"]["language"] == "ar") {
    echo "float:right";
}
echo "\"></i>&nbsp;";
echo LG_INVOICES;
echo "                        </div>\r\n                        <div id=\"recallBtn\" class=\"col-lg-2 col-md-2 col-sm-2 col-xs-2 grayButton disabledOnHold ";
if ($data["settings"]["language"] == "ar") {
    echo "pull-right";
}
echo "\" onclick=\"recallInvoice()\" style=\"direction: ";
if ($data["settings"]["language"] == "ar") {
    echo "rtl";
} else {
    echo "ltr";
}
echo "\">\r\n                            <i class=\"glyphicon glyphicon-list blueButton_icon\" style=\"margin-top: 4px;";
if ($data["settings"]["language"] == "ar") {
    echo "float:right";
}
echo ";\"></i>&nbsp;";
echo LG_RECALL;
echo "&nbsp;<span id=\"recall_nb\" ";
if ($data["settings"]["language"] == "ar") {
    echo "dir='rtl'";
}
echo ">(0)</span>\r\n                        </div>\r\n                                                <div class=\"col-lg-2 col-md-2 col-sm-2 col-xs-2 grayButton ";
if ($data["settings"]["language"] == "ar") {
    echo "pull-right";
}
echo "\" onclick=\"addPayment()\" id=\"addPaymentSection\">  <!-- add to class \"grayButton\" onclick=\"holdInvoice()\" -->\r\n                             <i class=\"icon-payment blueButton_icon\" style=\"margin-top: 4px;";
if ($data["settings"]["language"] == "ar") {
    echo "float:right";
}
echo "\"></i>&nbsp;";
echo LG_PAYMENT;
echo "                        </div>\r\n                                                \r\n                        \r\n                        <!--\r\n                        <div  class=\"col-lg-3 col-md-3 col-sm-3 col-xs-3 divPad5 ";
if ($data["settings"]["language"] == "ar") {
    echo "pull-right";
}
echo "\" style=\"padding-left: 18px; padding-right: 18px;\">\r\n                            <div class=\"row\">\r\n                                <div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12 grayButton\" style=\"text-align: center; background-color: #4a9bc0; color:#fff; border-radius: 2px;\">\r\n                                    <span style=\"font-size: 22px; font-weight: bold\">";
echo LG_TOTAL;
echo "</span>\r\n                                    <span style=\"font-size: 24px; color: #fff; font-weight: bold\" id=\"totalPrice\">0</span>\r\n                                </div>\r\n                            </div>\r\n                        </div>\r\n                        -->\r\n                        <div class=\"col-lg-2 col-md-2 col-sm-2 col-xs-2 grayButton ";
if ($data["settings"]["language"] == "ar") {
    echo "pull-right";
}
echo "\" style=\"background-color: #4a9bc0 !important; \">  <!-- add to class \"grayButton\" onclick=\"holdInvoice()\" -->\r\n                            \r\n                            <span class=\"payValue\" style=\"color: #fff; font-weight: bold;\" id=\"totalPrice\">0</span>\r\n                        </div>\r\n                        \r\n                    </div>\r\n                        <!--\r\n                        <div class=\"col-lg-3 col-md-3 col-sm-3 col-xs-3\" style=\"padding-left: 0px; padding-right: 0px\" >\r\n                            <button id=\"pay\" onclick=\"pay()\" type=\"button\" class=\"btn btn-default disabled\" style=\"width: 100%; background-color: #a5c536; color: #fff; font-size: 20px;font-weight: bold; margin-top: 1px; height: 50px;\">PAY</button> \r\n                        </div>\r\n                        -->\r\n                        <div class=\"row rowC3\" style=\"height: 47px;\">\r\n                        <div class=\"col-lg-2 col-md-2 col-sm-2 col-xs-2 grayButton ";
if ($data["settings"]["language"] == "ar") {
    echo "pull-right";
}
echo "\"  id=\"cashboxContainer\" onclick=\"closeCashbox()\">\r\n                            ";
echo LG_CLOSECASHBOX;
echo "                        </div>\r\n                        <div class=\"col-lg-2 col-md-2 col-sm-2 col-xs-2 grayButton ";
if ($data["settings"]["language"] == "ar") {
    echo "pull-right";
}
echo "\"  onclick=\"addExpense()\">\r\n                             <i class=\"icon-expenses blueButton_icon\" style=\"margin-top: 4px;";
if ($data["settings"]["language"] == "ar") {
    echo "float:right";
}
echo "\"></i>&nbsp;";
echo LG_EXPENSES;
echo "                        </div>\r\n                        <div class=\"col-lg-2 col-md-2 col-sm-2 col-xs-2 grayButton ";
if ($data["settings"]["language"] == "ar") {
    echo "pull-right";
}
echo "\" onclick=\"searchInvoice()\">\r\n                            <i class=\"glyphicon glyphicon-search blueButton_icon\" style=\"margin-top: 4px;";
if ($data["settings"]["language"] == "ar") {
    echo "float:right";
}
echo "\"></i>&nbsp;";
echo LG_SEARCH;
echo "                        </div>\r\n                        <div id=\"holdBtn\" class=\"col-lg-2 col-md-2 col-sm-2 col-xs-2 grayButton disabledOnHold ";
if ($data["settings"]["language"] == "ar") {
    echo "pull-right";
}
echo "\" onclick=\"holdInvoice()\">  <!-- add to class \"grayButton\" onclick=\"holdInvoice()\" -->\r\n                             <i class=\"icon-hold blueButton_icon\" style=\"margin-top: 4px;";
if ($data["settings"]["language"] == "ar") {
    echo "float:right";
}
echo "\"></i>&nbsp;";
echo LG_HOLD;
echo "                        </div>\r\n                        \r\n                       \r\n                        <div class=\"col-lg-2 col-md-2 col-sm-2 col-xs-2 grayButton ";
if ($data["settings"]["language"] == "ar") {
    echo "pull-right";
}
echo "\">  <!-- add to class \"grayButton\" onclick=\"holdInvoice()\" -->\r\n                            &nbsp;\r\n                        </div>\r\n                       \r\n                        <div id=\"pay\" class=\"col-lg-2 col-md-2 col-sm-2 col-xs-2 grayButton enabledPay disabledPay ";
if ($data["settings"]["language"] == "ar") {
    echo "pull-right";
}
echo "\" onclick=\"pay()\" style=\"float: right\" >\r\n                            ";
echo LG_PAY;
echo "                        </div>\r\n                        <!--\r\n                        <div id=\"recallBtn\" class=\"col-lg-2 col-md-2 col-sm-2 col-xs-2 grayButton disabledOnHold ";
if ($data["settings"]["language"] == "ar") {
    echo "pull-right";
}
echo "\" onclick=\"recallInvoice()\">\r\n                            <i class=\"glyphicon glyphicon-list blueButton_icon\" style=\"margin-top: 4px;";
if ($data["settings"]["language"] == "ar") {
    echo "float:right";
}
echo ";\"></i>&nbsp;";
echo LG_RECALL;
echo "&nbsp;<span id=\"recall_nb\">0</span>\r\n                        </div>\r\n                        -->\r\n                    </div>\r\n                   \r\n                </div>\r\n                \r\n                ";
if ($data["settings"]["quick_access_col"] == 1) {
    echo "                <div class=\"col-lg-4 col-md-4 col-sm-12 col-xs-12\" id=\"pos_right_column\">\r\n                    <div class=\"row\" id=\"r_content\">\r\n                        \r\n                    </div>\r\n                </div>\r\n                ";
}
echo "            </div>\r\n        </div>\r\n    </body>\r\n</html>\r\n\r\n";

?>