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
echo " System users</title>\r\n        <meta charset=\"UTF-8\">\r\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n        <link rel=\"icon\" type=\"image/png\" href=\"resources/favicon.png\">\r\n\r\n        <script src=\"libraries/jquery-3.1.1.min.js\" type=\"text/javascript\"></script>\r\n\r\n        <script src=\"libraries/bootstrap-3.3.7-dist/js/bootstrap.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-3.3.7-dist/css/bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        <script src=\"libraries/bootstrap-plugins/DataTables-1.10.13/media/js/jquery.dataTables.min.js\" type=\"text/javascript\"></script>\r\n        <script src=\"libraries/bootstrap-plugins/DataTables-1.10.13/media/js/dataTables.bootstrap.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/DataTables-1.10.13/media/css/dataTables.bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n\r\n        <script src=\"libraries/bootstrap-plugins/DataTables-1.10.13/extensions/Select/js/dataTables.select.min.js\" type=\"text/javascript\"></script>\r\n\r\n      \r\n        <script src=\"libraries/bootstrap-plugins/Buttons-1.2.4/js/dataTables.buttons.min.js\" type=\"text/javascript\"></script>\r\n        \r\n        <script src=\"libraries/bootstrap-plugins/Buttons-1.2.4/js/buttons.bootstrap.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/Buttons-1.2.4/css/buttons.bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        \r\n        <script src=\"application/mvc/views/custom_libraries/javascripts/global.js?rnd=";
echo self::generateRandomStringComplex();
echo "\" type=\"text/javascript\"></script>\r\n        <script src=\"application/mvc/views/custom_libraries/javascripts/employees.js?rnd=";
echo self::generateRandomStringComplex();
echo "\" type=\"text/javascript\"></script>\r\n\r\n        <script src=\"libraries/bootstrap-plugins/bootstrap-select-master/bootstrap-select-master/dist/js/bootstrap-select.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/bootstrap-select-master/bootstrap-select-master/dist/css/bootstrap-select.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        <link href=\"application/mvc/views/custom_libraries/css/global.css?rnd=";
echo self::generateRandomStringComplex();
echo "\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        \r\n        <script src=\"libraries/bootstrap-plugins/libs/bootstrap-confirmation.min.js\" type=\"text/javascript\"></script>\r\n        \r\n        <link href=\"libraries/bootstrap-plugins/datepicker-master/dist/css/bootstrap-datepicker.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        <script src=\"libraries/bootstrap-plugins/datepicker-master/dist/js/bootstrap-datepicker.min.js\" type=\"text/javascript\"></script>\r\n        \r\n        <script src=\"application/mvc/views/custom_libraries/javascripts/jquery-confirm.min.js\"\r\n        type=\"text/javascript\"></script>\r\n    <link href=\"application/mvc/views/custom_libraries/css/jquery-confirm.min.css\" rel=\"stylesheet\" type=\"text/css\" />\r\n    \r\n        <script src=\"libraries/bootstrap-plugins/bootstrap-sweetalert-master/dist/sweetalert.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-plugins/bootstrap-sweetalert-master/dist/sweetalert.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        <script src=\"libraries/numeric.js\" type=\"text/javascript\"></script>\r\n        \r\n        <link href=\"application/mvc/views/custom_libraries/svgs/font/style.css?rnd=";
echo self::generateRandomStringComplex();
echo "\" rel=\"stylesheet\" type=\"text/css\"/>\r\n        \r\n        <script src=\"application/mvc/views/custom_libraries/javascripts/system_users.js?rnd=";
echo self::generateRandomStringComplex();
echo "\" type=\"text/javascript\"></script>\r\n                <script src=\"libraries/cleave.js-master/dist/cleave.min.js\" type=\"text/javascript\"></script>\r\n        <style type=\"text/css\">\r\n            .container,.panel {\r\n                height:100%;\r\n                width: 100%;\r\n                padding: 0px !important;\r\n                margin: 0px !important;\r\n            }\r\n            \r\n            \r\n            .table>tfoot>tr>th{\r\n                font-size: 12px !important;\r\n                background-color: #F3F3F3;\r\n                padding: 2px;\r\n                border: none !important;\r\n            }\r\n            \r\n            .dataTables_filter { display: none; }\r\n            \r\n            .input-sm{\r\n                height: 25px !important;\r\n            }\r\n            \r\n            .selected{\r\n                background-color: #337ab7 !important;\r\n                color: #ffffff !important;\r\n            }\r\n            \r\n            .search_filter{\r\n                width: 100% !important;\r\n                color: #000;\r\n            }\r\n            \r\n            \r\n            ::-webkit-input-placeholder { /* Chrome/Opera/Safari */\r\n                color: #337ab7;\r\n                opacity: 0.9;\r\n            }\r\n            ::-moz-placeholder { /* Firefox 19+ */\r\n                color: #337ab7;\r\n                opacity: 0.9;\r\n            }\r\n            :-ms-input-placeholder { /* IE 10+ */\r\n                color: #337ab7;\r\n                opacity: 0.9;\r\n            }\r\n            :-moz-placeholder { /* Firefox 18- */\r\n                color: #337ab7;\r\n                opacity: 0.9;\r\n            }\r\n            \r\n            /* enable absolute positioning */\r\n            .inner-addon { \r\n                position: relative; \r\n            }\r\n\r\n            /* style icon */\r\n            .inner-addon .glyphicon {\r\n              position: absolute;\r\n              padding: 6px;\r\n              pointer-events: none;\r\n            }\r\n\r\n            /* align icon */\r\n            .left-addon .glyphicon  { left:  0px;}\r\n            .right-addon .glyphicon { right: 0px;}\r\n\r\n            /* add padding  */\r\n \r\n            .right-addon input { padding-right: 30px; }\r\n            \r\n            .phone-input{\r\n                    margin-bottom:8px;\r\n            }\r\n            \r\n            .confirmation {\r\n                width: 160px;\r\n            }\r\n            \r\n            .list-group-item{\r\n                border: none !important;\r\n            }\r\n            \r\n            div.toolbar h3{\r\n                margin-top: 0px !important;\r\n            }\r\n            \r\n            .dataTable>tfoot>tr>th{\r\n                /* width: 50px !important; */\r\n            }\r\n            \r\n            .item_icon{\r\n                font-size: 18px;\r\n            }\r\n        </style>\r\n\r\n        <script type=\"text/javascript\">\r\n            var employee_table = null;\r\n            \r\n            var omt_version = ";
echo OMT_VERSION;
echo ";\r\n            \r\n            var max_users = ";
echo $data["max_users"];
echo ";\r\n            var current_users = ";
echo $data["current_users"];
echo ";\r\n            \r\n            var new_multi_branches_enabled = ";
echo $data["enable_new_multibranches"];
echo ";\r\n            \r\n           \r\n            \$(document).ready(function () {\r\n                \r\n                \$('#employee_table').show();\r\n                \r\n                \$(\".sk-circle\").center();\r\n                \$(\".sk-circle-layer\").show();\r\n                \r\n                var search_fields = [0,1,2,3,4,5];\r\n                var index = 0;\r\n                \$('#employee_table tfoot th').each( function () {\r\n                    if(jQuery.inArray(index, search_fields) !== -1){\r\n                        var title = \$(this).text();\r\n                        \$(this).html( '<div class=\"inner-addon left-addon\"><input style=\"width: 100% !important;\"  class=\"form-control input_sm_search\" type=\"text\" placeholder=\" '+title+'\" /></div>' );\r\n                        index++;\r\n                    }\r\n                });\r\n                \r\n                employee_table = \$('#employee_table').dataTable({\r\n                    ajax: \"?r=employees&f=getAllUsers\",\r\n                    orderCellsTop: true,\r\n                    aoColumnDefs: [\r\n                        { \"targets\": [0], \"searchable\": true, \"orderable\": true, \"visible\": true },\r\n                        { \"targets\": [1], \"searchable\": true, \"orderable\": false, \"visible\": true },\r\n                        { \"targets\": [2], \"searchable\": true, \"orderable\": false, \"visible\": true },\r\n                        { \"targets\": [3], \"searchable\": true, \"orderable\": false, \"visible\": true },\r\n                        { \"targets\": [4], \"searchable\": true, \"orderable\": false, \"visible\": true },\r\n                        { \"targets\": [5], \"searchable\": true, \"orderable\": false, \"visible\": true },\r\n                    ],\r\n                    select:true,\r\n                    scrollY: '47vh',\r\n                    scrollCollapse: true,\r\n                    \"paging\": true,           // Enable pagination\r\n                    \"pageLength\": 50, \r\n                    \"lengthChange\": true,\r\n                    \"lengthMenu\": [50, 100, 250, 500], // Page length options \r\n                    //dom: '<\"toolbar\">frtlip',\r\n                    dom: '<\"row\"<\"col-sm-12 col-md-12\"<\"toolbar\">>><\"row\"<\"col-sm-12 col-md-6\"f>>' +\r\n                    '<\"row\"<\"col-sm-12\"tr>>' +\r\n                    '<\"row\"<\"col-sm-6 col-md-6\"i><\"col-sm-6 col-md-6\"p>>',\r\n                    initComplete: function( settings ) {\r\n                        var table = \$('#employee_table').DataTable();\r\n                        table.row(':eq(0)', { page: 'current' }).select();\r\n                        \r\n                        \$(\"div.toolbar\").html('<h3><i class=\"glyphicon glyphicon-user\"></i>&nbsp;System Users</h3><div id=\"tab_toolbar\" class=\"btn-group\" role=\"group\" aria-label=\"\">\\n\\\r\n                            <button onclick=\"addUser_()\" type=\"button\" class=\"btn btn-default \"><i class=\"glyphicon glyphicon-plus\"></i>&nbsp;Add User</button>\\n\\\r\n                        </div>\\n\\\r\n                        ');\r\n    \r\n                        if(table.rows().count()==0)\r\n                            \$(\"#tab_toolbar button.blueB\").addClass(\"disabled\");\r\n                        \r\n                        \r\n                        \$(\".sk-circle-layer\").hide();\r\n                        \r\n     \r\n                    },\r\n                    fnDrawCallback: function(){\r\n                        var table = \$('#employee_table').DataTable();\r\n                        var p = table.rows({ page: 'current' }).nodes();\r\n                        for (var k = 0; k < p.length; k++){\r\n                            var index = table.row(p[k]).index();\r\n                            table.cell(index, 5).data('<i onclick=\"editUser('+parseInt(table.cell(index, 0).data().split(\"-\")[1])+')\" title=\"Edit\" class=\"glyphicon glyphicon-edit edit_icon\"></i>&nbsp;&nbsp;<i onclick=\"deleteUser('+parseInt(table.cell(index, 0).data().split(\"-\")[1])+')\" title=\"Delete\" class=\"glyphicon glyphicon-trash trash_icon red\"></i>');\r\n                        }\r\n                    },\r\n                    fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {\r\n                        \$(nRow).addClass(aData[0]);\r\n                    },\r\n                });\r\n                \r\n                \$('#employee_table').on( 'page.dt', function () {\r\n                    //var table = \$('#suppliers_table').DataTable();\r\n                    //table.row(':eq(0)', { page: 'current' }).select();\r\n                    //\$(\"#suppliers_table .row:first\").addClass(\"selected\");\r\n                    \$(\"#tab_toolbar button.blueB\").addClass(\"disabled\");\r\n                });\r\n                \r\n                \$('#employee_table').DataTable().columns().every( function () {\r\n                    var that = this;\r\n                    \$( 'input', this.footer() ).on( 'keyup change', function () {\r\n                        if ( that.search() !== this.value ) {\r\n                            that.search( this.value ).draw();\r\n                        }\r\n                    } );\r\n                } );\r\n                \r\n                \$('#employee_table').DataTable().on( 'select', function ( e, dt, type, indexes ) {\r\n                    if (type === 'row') {\r\n                        \$('#contacts').popover('hide');\r\n                        \$(\"#tab_toolbar button.blueB\").removeClass(\"disabled\");\r\n                    }\r\n                } );\r\n                \r\n                \$('#employee_table').DataTable().on( 'deselect', function ( e, dt, type, indexes ) {\r\n                    if (type === 'row') {\r\n                        \$(\"#tab_toolbar button.blueB\").addClass(\"disabled\");\r\n                    }\r\n                });\r\n\r\n            });\r\n            \r\n            function addUser_(){\r\n                if(current_users>=max_users && max_users!=-1){\r\n                    swal(\"You've reached the maximum allowable user limit (\"+max_users+\")\");\r\n                }else{\r\n                    addUser();\r\n                }\r\n                \r\n            }\r\n            \r\n            function editUser_(){\r\n                if(\$(\"#tab_toolbar button.blueB\").hasClass(\"disabled\")==false){\r\n                    var dt = \$('#employee_table').DataTable();\r\n                    var id = dt.rows({ selected: true }).data()[0][0];\r\n                    editUser(id);\r\n                } \r\n            }\r\n            \r\n            function deleteUser(id){\r\n                \r\n                \$.confirm({\r\n                    title: 'Delete User!',\r\n                    content: 'Are you sure?',\r\n                    animation: 'zoom',\r\n                    closeAnimation: 'zoom',\r\n                    animateFromElement:false,\r\n                    buttons: {\r\n                        DELETE: {\r\n                            btnClass: 'btn-danger',\r\n                            action: function(){\r\n                                \$(\".sk-circle-layer\").show();\r\n                                \$.getJSON(\"?r=employees&f=delete_user&p0=\"+parseInt(id) , function (data) {\r\n                                    if(!data.status){\r\n                                        \$(\".sk-circle-layer\").hide();\r\n                                        swal(\"Unable to delete!\", \"Please contact system administrator\", \"warning\");\r\n                                    }else{\r\n                                        location.reload();\r\n                                    }\r\n                                }).done(function () {\r\n\r\n                                });\r\n                            }\r\n                        },\r\n                        CANCEL: {\r\n                            btnClass: 'btn-default any-other-class', // multiple classes.\r\n                            action: function(){\r\n\r\n                            }\r\n                        },\r\n                    }\r\n                });\r\n                \r\n                \r\n            }\r\n            \r\n           \r\n            \t\r\n        </script>\r\n    </head>\r\n    <body>\r\n        \r\n        <!-- Navbar fixed top -->\r\n        ";
include "application/mvc/views/topMenu.php";
echo "        <div class=\"container\">\r\n            <div class=\"panel panel-default\">\r\n                <div class=\"panel-body\" style=\"margin-top: 50px;\">\r\n                    <div class=\"row\"  >\r\n                        <div class=\"col-lg-8 col-md-8 col-sm-8\" >    \r\n                            ";
if ($data["max_users"] <= $data["current_users"] && $data["max_users"] != -1) {
    echo "                            <label class=\"text-danger\">You've reached the maximum allowable user limit (";
    echo $data["max_users"];
    echo ").</label>\r\n                            ";
} else {
    if ($data["max_users"] != -1) {
        echo "                                    <label class=\"text-success\">";
        echo $data["current_users"] . "/" . $data["max_users"];
        echo " users as been created</label>\r\n                                ";
    }
}
echo "                            <table id=\"employee_table\" class=\"table table-striped table-bordered\" cellspacing=\"0\">\r\n                                <thead>\r\n                                    <tr>\r\n                                        <th style=\"width: 80px !important;\">ID</th>\r\n                                        <th>Username</th>\r\n                                        <th>Employee</th>\r\n                                        <th>Password</th>\r\n                                        <th>Commission</th>\r\n                                        <th style=\"width: 60px;\">&nbsp;</th>\r\n                                    </tr>\r\n                                </thead>\r\n                                <tfoot>\r\n                                    <tr>\r\n                                        <th>ID</th>\r\n                                        <th>Username</th>\r\n                                        <th>Employee</th>\r\n                                        <th>Password</th>\r\n                                        <th>Commission</th>\r\n                                        <th>&nbsp;</th>\r\n                                    </tr>\r\n                                </tfoot>\r\n                                <tbody></tbody>\r\n                            </table>\r\n                        </div>\r\n                        <div class=\"col-lg-4 col-md-4 col-sm-4\" >\r\n                            <p style=\"margin-top: 100px;\">To be a strong password</p>\r\n                            <ul >\r\n                                <li>\r\n                                    Minimum length 8 characters.\r\n                                </li>\r\n                                <li>\r\n                                   At least one uppercase letter.\r\n                                </li>\r\n                                <li>\r\n                                    At least one lowercase letter.\r\n                                </li>\r\n                                <li>\r\n                                    At least one digit.\r\n                                </li>\r\n                                <li>\r\n                                   At least one special character.\r\n                                </li>\r\n                            </ul>\r\n                        </div>\r\n                    </div>\r\n                </div>\r\n            </div>\r\n            \r\n        </div>\r\n        \r\n        \r\n        \r\n        <div class=\"sk-circle-layer\">\r\n            <div class=\"sk-circle\">\r\n                <div class=\"sk-circle1 sk-child\"></div>\r\n                <div class=\"sk-circle2 sk-child\"></div>\r\n                <div class=\"sk-circle3 sk-child\"></div>\r\n                <div class=\"sk-circle4 sk-child\"></div>\r\n                <div class=\"sk-circle5 sk-child\"></div>\r\n                <div class=\"sk-circle6 sk-child\"></div>\r\n                <div class=\"sk-circle7 sk-child\"></div>\r\n                <div class=\"sk-circle8 sk-child\"></div>\r\n                <div class=\"sk-circle9 sk-child\"></div>\r\n                <div class=\"sk-circle10 sk-child\"></div>\r\n                <div class=\"sk-circle11 sk-child\"></div>\r\n                <div class=\"sk-circle12 sk-child\"></div>\r\n            </div>\r\n        </div>\r\n    </body>\r\n</html>\r\n\r\n";

?>