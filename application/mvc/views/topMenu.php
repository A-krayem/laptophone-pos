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
if ($_SESSION["centralize"] == 0 || WAREHOUSE_CONNECTED == 1) {
    echo "<!-- <p id=\"copyrights\">&copy; 2024 U-Postock<p> -->\r\n<nav class=\"navbar navbar-default navbar-fixed-top\">\r\n    <div class=\"container-fluid\">\r\n        <div class=\"navbar-header\">\r\n            <button type=\"button\" class=\"navbar-toggle collapsed\" data-toggle=\"collapse\" data-target=\"#navbar\" aria-expanded=\"false\" aria-controls=\"navbar\">\r\n                <span class=\"sr-only\">Toggle navigation</span>\r\n                <span class=\"icon-bar\"></span>\r\n                <span class=\"icon-bar\"></span>\r\n                <span class=\"icon-bar\"></span>\r\n            </button>\r\n        </div>\r\n        \r\n        ";
    if ($_SESSION["ptype"] != 1) {
        echo "        <a href=\"?r=dashboard\" id=\"reg_dashboard\" class=\"hide-on-small\" style=\"float: right; font-size: 20px; cursor: pointer; margin-top: 5px; text-decoration: none\">";
        echo $_SESSION["page_title"];
        echo " Dashboard</a>\r\n        ";
    } else {
        echo "        <a href=\"#\" style=\"float: right; font-size: 28px; cursor: pointer; margin-top: 5px; text-decoration: none\">UPSILON</a>\r\n        ";
    }
    echo "        \r\n        <span class=\"hide-on-small\" style=\" position: absolute; right: 15px; top: 30px;font-size: 14px;text-decoration: none\">Version <b>";
    echo $_SESSION["upsilon_version"];
    echo "</b></span>\r\n\r\n        <div id=\"navbar\" class=\"navbar-collapse collapse\">\r\n            <ul class=\"nav navbar-nav\">\r\n                \r\n                <li class=\"dropdown\">\r\n                    <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">Inventory<span class=\"caret\"></span></a>\r\n                    <ul class=\"dropdown-menu\">\r\n                        <li class=\"dropdown-header\">Items</li>\r\n                        <li><a href=\"?r=categories\"><i class=\"glyphicon glyphicon-modal-window\"></i>Categories/Subcategories</a></li>\r\n\r\n                        <li><a href=\"?r=items\"><i class=\"glyphicon glyphicon-briefcase\"></i>Create Items</a></li>\r\n                        <li><a href=\"?r=items&f=items_view\" style=\"display: none\"><i class=\"glyphicon glyphicon-briefcase\"></i>Create Items Ajax</a></li>\r\n\r\n                        \r\n                        <!-- \r\n                        <li class=\"dropdown-header\">Stock</li>\r\n                        <li><a href=\"?r=store\"><i class=\"glyphicon icon-qty\"></i>";
    if ($_SESSION["role"] == 1) {
        echo "Add/Edit Quantity/Expiry";
    } else {
        echo "Set Expiry";
    }
    echo "</a></li>\r\n                        -->\r\n                        ";
    if ($_SESSION["ptype"] != 1 && $_SESSION["role"] == 1) {
        echo "                        \r\n                        ";
        if ($_SESSION["global_admin_exist"] == 0) {
            echo "                            <li class=\"dropdown-header\">Purchase Invoice</li>\r\n                            <li><a href=\"?r=stock&f=receive_stock\"><i class=\"glyphicon glyphicon-list-alt\"></i>Purchase invoice</a></li>\r\n                        ";
        }
        echo "                        \r\n                        <li class=\"dropdown-header\">Shrinkage</li>\r\n                        <li><a href=\"?r=shrinkage&f=shrinkage_mng\"><i class=\"glyphicon glyphicon glyphicon-equalizer\"></i>Add shrinkage</a></li>\r\n                            <!--<li class=\"dropdown-header\">Warehouses</li>\r\n                            <li><a href=\"?r=warehouses\"><i class=\"glyphicon glyphicon-home\"></i>Add/Edit Warehouses</a></li>\r\n                            -->\r\n                        ";
    }
    echo "                    </ul>\r\n                </li>\r\n                \r\n                ";
    if ($_SESSION["global_admin_exist"] == 0 && $_SESSION["ptype"] != 1) {
        echo "                <li class=\"dropdown\">\r\n                    <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">Suppliers<span class=\"caret\"></span></a>\r\n                    <ul class=\"dropdown-menu\">\r\n                        <li class=\"dropdown-header\">Suppliers</li>\r\n                        <li><a href=\"?r=suppliers\"><i class=\"glyphicon glyphicon-user\"></i>Add/Edit suppliers</a></li>\r\n\r\n                        ";
        if (ENABLE_INVENTORY_SYSTEM && $_SESSION["role"] == 1) {
            echo "                        \r\n                            ";
            if ($global_settings["suppliers_complex_stmt"] == 1) {
                echo "                                <li><a href=\"?r=suppliers&f=overview\"><i class=\"glyphicon glyphicon-list-alt\"></i>Financial overview</a></li>\r\n                                <li><a href=\"?r=suppliers&f=suppliers_statement_newversion&p0=0\"><i class=\"icon-payment\"></i>Statement of accounts</a></li>\r\n\r\n                            ";
            } else {
                echo "                                 <li><a href=\"?r=suppliers&f=overview\"><i class=\"glyphicon glyphicon-list-alt\"></i>Financial overview</a></li>\r\n                                <li><a href=\"?r=suppliers&f=suppliers_statement&p0=0\"><i class=\"icon-payment\"></i>Statement of accounts</a></li>\r\n                            ";
            }
            echo "                            \r\n                        ";
        }
        echo "                       ";
        if ($_SESSION["role"] == 1) {
            echo "<li><a href=\"?r=debit_note&f=debit_notes\"><i class=\"icon-payment\"></i>Debit note</a></li>";
        }
        echo "\r\n                    </ul>\r\n                </li>\r\n                ";
    }
    echo "                \r\n                <li class=\"dropdown\">\r\n                    <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">Clients<span class=\"caret\"></span></a>\r\n                    <ul class=\"dropdown-menu\">\r\n                        <!-- <li class=\"dropdown-header\">Customers</li>  -->\r\n                        <li class=\"dropdown-header\">Clients</li>\r\n                        <li><a href=\"?r=customers\"><i class=\"glyphicon glyphicon-user\"></i>Add/Edit clients</a></li>\r\n                        <li><a href=\"?r=customers&f=customers_overview\"><i class=\"glyphicon glyphicon-list-alt\"></i>Financial overview</a></li>\r\n                        <!-- <li><a href=\"?r=customers&f=payments\"><i class=\"icon-payment\"></i>All customers debts</a></li> -->\r\n                        <li><a href=\"?r=customers&f=statements&p0=0\"><i class=\"icon-payment\"></i>Statement of accounts</a></li>\r\n                        ";
    if ($_SESSION["role"] == 1 && $_SESSION["ptype"] != 1) {
        echo "<li><a href=\"?r=credit_note&f=credit_notes\"><i class=\"icon-payment\"></i>Credit note</a></li>";
    }
    echo "                        ";
    if ($global_settings["payment_later"] == 1) {
        echo "<li><a href=\"?r=reports&f=debts_payments\"><i class=\"glyphicon glyphicon-list-alt\"></i>Debts Payments</a></li>";
    }
    echo "\r\n                    </ul>\r\n                </li>\r\n                \r\n                ";
    if ($_SESSION["ptype"] != 1) {
        echo "                <li class=\"dropdown\">\r\n                    <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">Employees<span class=\"caret\"></span></a>\r\n                    <ul class=\"dropdown-menu\">\r\n                        <li class=\"dropdown-header\">Employees</li>\r\n                        <li><a href=\"?r=employees\"><i class=\"glyphicon glyphicon-user\"></i>Add/Edit</a></li>\r\n                        <!-- <li><a href=\"?r=employees&f=employees_attendance\"><i class=\"glyphicon glyphicon-time\"></i>Attendance</a></li> -->\r\n                    </ul>\r\n                </li>\r\n                ";
    }
    echo "                \r\n                <li class=\"dropdown\">\r\n                    <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">Sales invoices<span class=\"caret\"></span></a>\r\n                    <ul class=\"dropdown-menu\">\r\n                        <li class=\"dropdown-header\">Sales invoices</li>\r\n                        <li><a href=\"?r=invoice&f=all_invoice\"><i class=\"icon-invoice\"></i>Create / All invoices</a></li>\r\n                        ";
    if ($global_settings["payment_later"] == 1) {
        echo "                            <li><a href=\"?r=invoice&f=invoice_must_pay\"><i class=\"icon-invoice\"></i>Invoices due date</a></li>\r\n                            <li><a href=\"?r=invoices_customers\"><i class=\"icon-invoice\"></i>Invoices of a client</a></li>\r\n                        ";
    }
    echo "                        ";
    if ($_SESSION["role"] == 1 && $_SESSION["ptype"] != 1) {
        echo "<li class=\"dropdown-header\">Items</li>\r\n                        <li><a href=\"?r=invoice&f=all_sold_items\"><i class=\"icon-invoice\"></i>Sold items</a></li>";
    }
    echo "                        <li><a href=\"?r=items&f=manual_cost\"><i class=\"glyphicon glyphicon-briefcase\"></i>Custom items</a></li>\r\n\r\n                    </ul>\r\n                </li>\r\n\r\n                <li class=\"dropdown\">\r\n                    <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">Reports<span class=\"caret\"></span></a>\r\n                    <ul class=\"dropdown-menu\">\r\n                        \r\n                        <li class=\"dropdown-submenu\">\r\n                            <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\"><i class=\"glyphicon icon-store\"></i>Stock</a>\r\n                            <ul class=\"dropdown-menu\">\r\n                            ";
    if ($_SESSION["role"] == 1 && $_SESSION["ptype"] != 1) {
        echo "<li><a href=\"?r=reports&f=report_lack_of_items\"><i class=\"glyphicon glyphicon-th-list\" style=\"left: 2px !important;\"></i>Low level stock</a></li>";
    }
    echo "                            ";
    if ($_SESSION["role"] == 1) {
        echo "<li><a href=\"?r=reports&f=report_stock\"><i class=\"icon-store\" style=\"left: 2px !important;\"></i>Available in stock</a></li>";
    }
    echo "                            ";
    if ($_SESSION["role"] == 1 && $_SESSION["ptype"] != 1) {
        echo "<li style=\"display: none\"><a href=\"?r=reports&f=report_stock_movement\"><i class=\"glyphicon glyphicon-indent-right\" style=\"left: 2px !important;\"></i>Stock movement (Monthly)</a></li>";
    }
    echo "                            ";
    if ($_SESSION["role"] == 1 && $_SESSION["ptype"] != 1) {
        echo "<li><a href=\"?r=reports&f=report_stock_movement_all_time\"><i class=\"glyphicon glyphicon-indent-right\" style=\"left: 2px !important;\"></i>Stock movement (Item) </a></li>";
    }
    echo "                            ";
    if ($_SESSION["role"] == 1 && $_SESSION["ptype"] != 1) {
        echo "<li><a href=\"?r=reports&f=report_stock_movement_all_time_by_group\"><i class=\"glyphicon glyphicon-indent-right\" style=\"left: 2px !important;\"></i>Stock movement-By Group</a></li>";
    }
    echo "                            <li><a href=\"?r=reports&f=report_stock_expired\"><i class=\"glyphicon glyphicon-time\"></i>Expired</a></li> \r\n                            <li><a href=\"?r=reports&f=report_stock_wasting\"><i class=\"glyphicon glyphicon-trash\"></i>Wasting</a></li>                           \r\n                            </ul>\r\n                        </li>\r\n                        \r\n                        <li class=\"dropdown-submenu\">\r\n                            <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\"><i class=\"glyphicon icon-profits\"></i>Sales</a>\r\n                            <ul class=\"dropdown-menu\">\r\n                                <li><a href=\"?r=reports&f=report_sales_by_day\"><i class=\"glyphicon icon-profits\"></i>Sales/Profits (Inv./Items)</a></li>\r\n                                <li><a href=\"?r=reports&f=report_sales_by_day_items\"><i class=\"glyphicon icon-profits\"></i>Sales/Profits (Items)</a></li>\r\n                                ";
    if ($_SESSION["role"] == 1) {
        echo "<li><a href=\"?r=reports&f=report_sales_by_employee\"><i class=\"glyphicon glyphicon-list-alt\"></i>Sales per Vendor</a></li>\r\n                                ";
        if ($_SESSION["ptype"] != 1) {
            echo "<li><a href=\"?r=reports&f=report_sales_by_salesperson\"><i class=\"glyphicon glyphicon-list-alt\"></i>Sales per Salesperson</a></li>";
        }
        echo "                                <li><a href=\"?r=reports&f=report_returning_items\"><i class=\"glyphicon glyphicon-refresh\"></i>Returned items</a></li>";
    }
    echo "                                ";
    if ($_SESSION["ptype"] != 1) {
        echo "<li><a href=\"?r=reports&f=best_seller\"><i class=\"glyphicon icon-payment\"></i>Top Selling Products</a></li>";
    }
    echo "                                \r\n                                    \r\n                                ";
    if ($global_settings["invoice_taxable_enabled"] == 1) {
        echo "   \r\n                                    <li><a href=\"?r=reports&f=var_report\"><i class=\"glyphicon glyphicon-list-alt\"></i>TAX report</a></li>\r\n                                 ";
    }
    echo "\r\n                            </ul>\r\n                        </li>\r\n                        \r\n                        ";
    if ($global_settings["mobile_shop"] == 1) {
        echo "                         <li class=\"dropdown-submenu\">\r\n                            <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\"><i class=\"icon-smartphone\"></i>Mobile store</a>\r\n                            <ul class=\"dropdown-menu\">\r\n                                <li><a href=\"?r=reports&f=report_sales_by_day_credit_transfers\"><i class=\"glyphicon icon-smartphone\"></i>Credit transfers</a></li>\r\n                                <li><a href=\"?r=reports&f=report_sales_by_day_mobiledays\"><i class=\"glyphicon icon-smartphone\"></i>Days</a></li>\r\n                                <li><a href=\"?r=reports&f=report_lost_mobiledays\"><i class=\"glyphicon icon-smartphone\"></i>Lost SMS</a></li>\r\n                            </ul>\r\n                        </li> \r\n                        ";
    }
    echo "                        \r\n                        <li class=\"dropdown-header\">Expenses</li>\r\n                        <li><a href=\"?r=reports&f=report_expenses\"><i class=\"icon-expenses\"></i>Expenses</a></li>\r\n                        <li class=\"dropdown-header\">Cashbox</li>\r\n                        <li><a href=\"?r=reports&f=report_cashbox\"><i class=\"glyphicon glyphicon-object-align-vertical\"></i>Cashbox by days</a></li>\r\n                        ";
    if ($_SESSION["ptype"] != 1) {
        echo "<li class=\"dropdown-header\">Clients</li>\r\n                        <li><a href=\"?r=reports&f=top_customers_payments\"><i class=\"glyphicon glyphicon-user\"></i>Top clients</a></li>";
    }
    echo "                        \r\n                         <li class=\"dropdown-header\">Logs</li>\r\n                        <li><a href=\"?r=logs&f=user_customers_logs\"><i class=\"glyphicon glyphicon-align-justify\"></i>Clients logs</a></li> \r\n\r\n                    </ul>\r\n                </li>\r\n                ";
    if ($_SESSION["ptype"] != 1) {
        echo "                <li class=\"dropdown\">\r\n                    <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">Discounts<span class=\"caret\"></span></a>\r\n                    <ul class=\"dropdown-menu\">\r\n                        <li class=\"dropdown-header\">Set discounts</li>\r\n                        <li><a href=\"?r=discounts&f=discount_by_categories\"><i class=\"glyphicon glyphicon-chevron-down\"></i>By category</a></li>\r\n                        <li><a href=\"?r=discounts&f=discount_by_groups\"><i class=\"glyphicon glyphicon-chevron-down\"></i>By group</a></li>\r\n                    </ul>\r\n                </li>\r\n                ";
    }
    echo "                \r\n                <!-- \r\n                <li class=\"dropdown\">\r\n                    <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">SMS<span class=\"caret\"></span></a>\r\n                    <ul class=\"dropdown-menu\">\r\n                        <li class=\"dropdown-header\">SMS</li>\r\n                        <li><a href=\"?r=sms&f=list_of_sms\"><i class=\"glyphicon glyphicon-envelope\"></i>List of SMS</a></li>\r\n                    </ul>\r\n                </li> -->\r\n                \r\n                \r\n                \r\n                ";
    if ($global_settings["mobile_shop"] == 1) {
        echo "                <li class=\"dropdown\">\r\n                    <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">Mobile store<span class=\"caret\"></span></a>\r\n                    <ul class=\"dropdown-menu\">\r\n                        <li class=\"dropdown-header\">Credits and devices</li>\r\n                        <li><a href=\"?r=mobile_store&f=mobile_devices\"><i class=\"icon-smartphone\"></i>Devices</a></li>\r\n                        <li><a href=\"?r=mobile_store&f=mobile_dollars_pkg\"><i class=\"icon-smartphone\"></i>Add credits packages</a></li>\r\n                        <li><a href=\"?r=mobile_store&f=mobile_days_pkg\"><i class=\"icon-smartphone\"></i>Add days packages</a></li>\r\n                        <li style=\"display: none\"><a href=\"?r=mobile_store&f=mobile_sim_pkg\"><i class=\"icon-smartphone\"></i>Add SIM packages</a></li>\r\n                        \r\n                        ";
        if ($global_settings["disable_international_calls"] == 0) {
            echo "                        <li class=\"dropdown-header\">International Calls</li>\r\n                        <li><a href=\"?r=mobile_store&f=international_calls\"><i class=\"icon-smartphone\"></i>International calls</a></li>\r\n                        <li><a href=\"?r=mobile_store&f=international_calls_balance\"><i class=\"icon-smartphone\"></i>International calls balance</a></li>\r\n                        ";
        }
        echo "                        \r\n                    </ul>\r\n                </li>\r\n                ";
    }
    echo "                \r\n                ";
    if ($global_settings["garage_car_plugin"] == 1) {
        echo "                <li class=\"dropdown\">\r\n                    <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">Garage<span class=\"caret\"></span></a>\r\n                    <ul class=\"dropdown-menu\">\r\n                        <li class=\"dropdown-header\">Clients Cards</li>\r\n                        <li><a href=\"?r=garage&f=cards\"><i class=\"glyphicon glyphicon-file\"></i>Clients cards</a></li>\r\n                        <li class=\"dropdown-header\">Oil</li>\r\n                        <li><a href=\"?r=garage&f=oil_report\"><i class=\"glyphicon glyphicon-tint\"></i>Oil reports</a></li>\r\n                    </ul>\r\n                </li>\r\n                ";
    }
    echo "                \r\n                ";
    if ($global_settings["delivery_items_plugin"] == 1) {
        echo "                <li class=\"dropdown\">\r\n                    <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">Delivery<span class=\"caret\"></span></a>\r\n                    <ul class=\"dropdown-menu\">\r\n                        <li class=\"dropdown-header\">Delivery</li>\r\n                        <li><a href=\"?r=delivery_items&f=delivery\"><i class=\"glyphicon glyphicon-road\"></i>Create delivery sheets</a></li>\r\n                        <li><a href=\"?r=delivery_items&f=all_packages\"><i class=\"glyphicon glyphicon-folder-close\"></i>All packages</a></li>\r\n                        <li class=\"dropdown-header\">Reports</li>\r\n                        <li><a href=\"?r=delivery_items&f=supplier_statement\"><i class=\"icon-payment\"></i>Statement</a></li>\r\n                        <li><a href=\"?r=delivery_items&f=report\"><i class=\"icon-payment\"></i>Report</a></li>\r\n                    </ul>\r\n                </li>\r\n                ";
    }
    echo "                \r\n                ";
    if ($global_settings["quick_access_col"] == 1) {
        echo "                <li class=\"dropdown\">\r\n                    <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">Pos items<span class=\"caret\"></span></a>\r\n                    <ul class=\"dropdown-menu\">\r\n                        <li class=\"dropdown-header\">POS management</li>\r\n                        <li><a href=\"?r=pos&f=posItems\"><i class=\"glyphicon glyphicon-briefcase\"></i>Add items to POS interface</a></li>\r\n                    </ul>\r\n                </li>\r\n                ";
    }
    echo "                \r\n                \r\n                <li class=\"dropdown\">\r\n                    <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">Settings<span class=\"caret\"></span></a>\r\n                    <ul class=\"dropdown-menu\">\r\n                        <li class=\"dropdown-header\">Profile</li>\r\n                        <li><a href=\"?r=dashboard&f=logout\"><i class=\"icon-logout\"></i>Logout</a></li>\r\n                        <li class=\"dropdown-header\">System settings</li>\r\n                       \r\n                        <li class=\"dropdown-submenu\">\r\n                            <a  href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\" ><i class=\"glyphicon glyphicon-cog\"></i>Parameters</a>\r\n                            <ul class=\"dropdown-menu\">\r\n                                <li><a href=\"?r=settings_info&f=parameters&p0=invoice_printer\"><i class=\"glyphicon glyphicon-print\"></i>Invoice printer</a></li>\r\n                                <li><a href=\"?r=settings_info&f=parameters&p0=barcode_printer\"><i class=\"glyphicon glyphicon glyphicon-print\"></i>Barcode printer</a></li>\r\n                                <li><a href=\"?r=settings_info&f=parameters&p0=passwords\"><i class=\"glyphicon glyphicon glyphicon glyphicon-lock\"></i>POS settings</a></li>\r\n                                <li><a href=\"?r=settings_info&f=parameters&p0=others\"><i class=\"glyphicon  glyphicon-cog\"></i>Others</a></li>\r\n                                <li><a href=\"?r=size&f=sizes_mng\" style=\"display: none\"><i class=\"glyphicon glyphicon-cog\"></i>Items sizes</a></li>\r\n                            </ul>\r\n                        </li>\r\n                        \r\n                        \r\n                        \r\n                        ";
    if (!self::is_on_server()) {
        echo "                        <li class=\"dropdown-header\">License</li>\r\n                            <li><a href=\"?r=license\"><i class=\"icon-key\"></i>License info</a></li>\r\n                            \r\n                            <li class=\"dropdown-header\">Database</li>\r\n                        <li><a href=\"?r=dashboard&f=backup\"><i class=\"glyphicon glyphicon-tasks\"></i>Backup</a></li>\r\n                       \r\n                        ";
    }
    echo "                        \r\n                        \r\n\r\n                     ";
    if ($_SESSION["role"] == 1 && $_SESSION["hide_critical_data"] == 0 && defined("OMT_CLIENT") && OMT_CLIENT == false) {
        echo "<li class=\"dropdown-header\">System Users</li>\r\n                        \r\n                        \r\n                            ";
        if ($_SESSION["hide_critical_data"] == 0) {
            echo "                                    ";
            if (defined("OMT_CLIENT") && OMT_CLIENT == false) {
                echo "                                        <li><a href=\"?r=employees&f=system_users\"><i class=\"glyphicon glyphicon-user\"></i>Users</a></li>\r\n                                    ";
            }
            echo "                                ";
        }
        echo "                        ";
    }
    echo "                    </ul>\r\n                </li>\r\n            </ul>\r\n        </div> \r\n    </div>\r\n</nav>\r\n\r\n";
    if ($this->licenseExpired) {
        echo "<div style=\"position: absolute; left: 3px; bottom: 10px; padding-top: 1px; padding-bottom: 1px; padding-left: 5px; padding-right: 5px; text-align: center; font-size: 16px; color: #fff; background-color: red; border-radius: 5px; z-index: 9999999\">\r\n    ";
        echo "<b>Software license expired " . $this->licenseExpired . "</b>";
        echo "</div>\r\n";
    } else {
        if (!self::is_on_server()) {
            $code = self::mc_decrypt($global_settings["activation_code"], ENCRYPTION_KEY_1);
            $code_exploded = explode("_", $code);
            if ($code_exploded[1] - $global_settings["show_expiry_date_alert_before_days"] < time()) {
                echo "        <div style=\"position: absolute; left: 3px; bottom: 10px; padding-top: 1px; padding-bottom: 1px; padding-left: 5px; padding-right: 5px; text-align: center; font-size: 16px; color: #000; background-color: #ffa500; border-radius: 5px; z-index: 9999999\">\r\n            ";
                echo "<b>Warning: Expiration date at</b><br/> " . date("l jS \\of F Y h:i:s A", $code_exploded[1]);
                echo "        </div>\r\n\r\n        ";
            }
        }
    }
    echo "\r\n\r\n\r\n";
} else {
    echo "<nav class=\"navbar navbar-default navbar-fixed-top\">\r\n    <div class=\"container-fluid\">\r\n        <div class=\"navbar-header\">\r\n            <button type=\"button\" class=\"navbar-toggle collapsed\" data-toggle=\"collapse\" data-target=\"#navbar\" aria-expanded=\"false\" aria-controls=\"navbar\">\r\n                <span class=\"sr-only\">Toggle navigation</span>\r\n                <span class=\"icon-bar\"></span>\r\n                <span class=\"icon-bar\"></span>\r\n                <span class=\"icon-bar\"></span>\r\n            </button>\r\n        </div>\r\n        \r\n        \r\n        <a href=\"?r=dashboard\" class=\"hidden-on-small-screen\" style=\"float: right; font-size: 20px; cursor: pointer; margin-top: 5px; text-decoration: none\">Warehouse - ";
    echo $_SESSION["currency_symbol"];
    echo "</a>\r\n        <span class=\"hidden-on-small-screen\" style=\" position: absolute; right: 15px; top: 30px;font-size: 14px;text-decoration: none\">Version ";
    echo $_SESSION["upsilon_version"];
    echo "</span>\r\n\r\n        \r\n        <div id=\"navbar\" class=\"navbar-collapse collapse\">\r\n            <ul class=\"nav navbar-nav\">\r\n                <li class=\"dropdown\">\r\n                    <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">Inventory<span class=\"caret\"></span></a>\r\n                    <ul class=\"dropdown-menu\">\r\n                        <li class=\"dropdown-header\">Items</li>\r\n                        <li><a href=\"?r=categories\"><i class=\"glyphicon glyphicon-modal-window\"></i>Category/Subcategory</a></li>\r\n                        <li><a href=\"?r=items\"><i class=\"glyphicon glyphicon-briefcase\"></i>Create items</a></li>\r\n                        <li class=\"dropdown-header\">Purchase Invoice</li>\r\n                        <li><a href=\"?r=stock&f=receive_stock\"><i class=\"glyphicon glyphicon-list-alt\"></i>Purchase invoice</a></li>\r\n                        <li class=\"dropdown-header\">Transfers</li>\r\n                        <li><a href=\"?r=transfer\"><i class=\"glyphicon glyphicon-transfer\"></i>Add transfer</a></li>\r\n                        <li class=\"dropdown-header\">Shrinkage</li>\r\n                        <li><a href=\"?r=shrinkage&f=shrinkage_mng\"><i class=\"glyphicon glyphicon glyphicon-equalizer\"></i>Add shrinkage</a></li>\r\n                    </ul>\r\n                </li>\r\n                <li class=\"dropdown\">\r\n                    <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">Suppliers<span class=\"caret\"></span></a>\r\n                    <ul class=\"dropdown-menu\">\r\n                        <li class=\"dropdown-header\">Suppliers</li>\r\n                        <li><a href=\"?r=suppliers\"><i class=\"glyphicon glyphicon-user\"></i>Add/Edit suppliers</a></li>\r\n\r\n                        ";
    if (ENABLE_INVENTORY_SYSTEM && $_SESSION["role"] == 1) {
        echo "                            ";
        if ($global_settings["suppliers_complex_stmt"] == 1) {
            echo "                                <li><a href=\"?r=suppliers&f=overview\"><i class=\"glyphicon glyphicon-list-alt\"></i>Financial overview</a></li>\r\n                                <li><a href=\"?r=suppliers&f=suppliers_statement_newversion&p0=0\"><i class=\"icon-payment\"></i>Statement of accounts</a></li>\r\n\r\n                            ";
        } else {
            echo "                                <li><a href=\"?r=suppliers&f=overview\"><i class=\"glyphicon glyphicon-list-alt\"></i>Financial overview</a></li>\r\n                                <li><a href=\"?r=suppliers&f=suppliers_statement&p0=0\"><i class=\"icon-payment\"></i>Statement of accounts</a></li>\r\n                            ";
        }
        echo "                        ";
    }
    echo "                       ";
    if ($_SESSION["role"] == 1) {
        echo "<li><a href=\"?r=debit_note&f=debit_notes\"><i class=\"icon-payment\"></i>Debit note</a></li>";
    }
    echo "\r\n                    </ul>\r\n                </li>\r\n                \r\n                <li class=\"dropdown\">\r\n                    <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">Discounts<span class=\"caret\"></span></a>\r\n                    <ul class=\"dropdown-menu\">\r\n                        <li class=\"dropdown-header\">Set discounts</li>\r\n                        <li><a href=\"?r=discounts&f=discount_by_categories\"><i class=\"glyphicon glyphicon-chevron-down\"></i>Discounts by categories</a></li>\r\n                        <li><a href=\"?r=discounts&f=discount_by_groups\"><i class=\"glyphicon glyphicon-chevron-down\"></i>Discounts by group</a></li>\r\n                    </ul>\r\n                </li>\r\n                \r\n                <li class=\"dropdown\">\r\n                    <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">Reports<span class=\"caret\"></span></a>\r\n                    <ul class=\"dropdown-menu\">\r\n                        <li><a href=\"?r=reports&f=report_lack_of_items\"><i class=\"glyphicon glyphicon-th-list\"></i>Low level stock</a></li>\r\n                        <li><a href=\"?r=reports&f=report_stock\"><i class=\"icon-store\"></i>Available in stock</a></li>\r\n                        <li><a href=\"?r=reports&f=report_stock_expired\"><i class=\"glyphicon glyphicon-time\"></i>Expired</a></li>\r\n                        ";
    if ($global_settings["payment_later"] == 1) {
        echo "<li><a href=\"?r=reports&f=debts_payments\"><i class=\"glyphicon glyphicon-list-alt\"></i>Debts payments</a></li>";
    }
    echo "                        <li><a href=\"?r=cashboxes&f=all_cashboxes\"><i class=\"glyphicon glyphicon-compressed\"></i>Opened cashboxes</a></li>\r\n                    </ul>\r\n                </li>\r\n                \r\n                <li class=\"dropdown\">\r\n                    <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">SMS<span class=\"caret\"></span></a>\r\n                    <ul class=\"dropdown-menu\">\r\n                        <li class=\"dropdown-header\">SMS</li>\r\n                        <li><a href=\"?r=sms&f=list_of_sms\"><i class=\"glyphicon glyphicon-envelope\"></i>List of SMS</a></li>\r\n                    </ul>\r\n                </li>\r\n                \r\n                <li class=\"dropdown\">\r\n                    <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">Settings<span class=\"caret\"></span></a>\r\n                    <ul class=\"dropdown-menu\">\r\n                        <li class=\"dropdown-header\">Profile</li>\r\n                        <li><a href=\"?r=dashboard&f=logout\"><i class=\"icon-logout\"></i>Logout</a></li>\r\n                        <li class=\"dropdown-header\">Database</li>\r\n                        <li><a href=\"?r=dashboard&f=backup\"><i class=\"glyphicon glyphicon-tasks\"></i>Backup</a></li>\r\n                        \r\n                        <li class=\"dropdown-header\">System settings</li>\r\n                        <li class=\"dropdown-submenu\">\r\n                            <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\"><i class=\"glyphicon glyphicon-cog\"></i>Parameters</a>\r\n                            <ul class=\"dropdown-menu\">\r\n                                <li><a href=\"?r=settings_info&f=parameters&p0=invoice_printer\"><i class=\"glyphicon glyphicon-print\"></i>Invoice printer</a></li>\r\n                                <li><a href=\"?r=settings_info&f=parameters&p0=barcode_printer\"><i class=\"glyphicon glyphicon-print\"></i>Barcode printer</a></li>\r\n                                <li><a href=\"?r=settings_info&f=parameters&p0=others\"><i class=\"glyphicon  glyphicon-cog\"></i>Others</a></li>\r\n                                <li style=\"display: none\"><a href=\"?r=size&f=sizes_mng\"><i class=\"glyphicon glyphicon-cog\"></i>Items sizes</a></li>\r\n\r\n                            </ul>\r\n                        </li>\r\n                        <li class=\"dropdown-header\">License</li>\r\n                        <li><a href=\"?r=license\"><i class=\"icon-key\"></i>License info</a></li>\r\n                        ";
    if ($_SESSION["role"] == 1 && $_SESSION["hide_critical_data"] == 0) {
        echo "<li class=\"dropdown-header\">System users</li>\r\n                        \r\n                        \r\n                            ";
        if ($_SESSION["hide_critical_data"] == 0) {
            echo "                                <li><a href=\"?r=employees&f=system_users\"><i class=\"glyphicon glyphicon-user\"></i>Users</a></li>\r\n                            ";
        }
        echo "                        ";
    }
    echo "                    </ul>\r\n                </li>\r\n            </ul>\r\n        </div>\r\n    </div>\r\n</nav>\r\n";
}

?>