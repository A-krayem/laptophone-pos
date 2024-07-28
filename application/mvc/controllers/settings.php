<?php
class settings extends controller
{
    
    public $settings_info = null;
    
    public function __construct() {
        $this->checkAuth();
        $this->settings_info = self::getSettings(); 
    }
    
    public function get_settings()
    {
        $settings = $this->model("settings");
        
        if($this->settings_info["usd_but_show_lbp_priority"]==0){
            $invoice_f=["auto_print","invoice_footer","footer_direction","print_invoice_lbp_format","default_print_paper","invoice_note"];

        }
        if($this->settings_info["usd_but_show_lbp_priority"]==1){
            $invoice_f=["auto_print","invoice_footer","footer_direction","show_usd_in_invoice","show_barcode_in_invoice","default_print_paper","invoice_note"];
        }

        
        $settingsEditorConfig = [
            "tabs" => [
                [
                    "title" => "STORE INFORMATION",
                    "sections" => [
                        ["title" => "<b>Store Info</b>", "settings" => ["shop_name","phone_nb","address","invoice_pdf_MOF"]]
                    ]
                ],
                [
                    "title" => "POS",
                    "sections" => [
                        ["title" => "<b>Invoice</b>", "settings" => ["enable_invoice_discount","enable_invoice_freight","payment_full","payment_credit_card","payment_later","sound_play","force_select_sales_persion_on_pos","enable_invoice_tax","apply_vat_on_sales_invoice","default_due_date_invoice"]],
                        ["title" => "<b>Invoice Printing</b>", "settings" => $invoice_f],
                        ["title" => "<b>Others</b>", "settings" => ["pos_show_quotation","pos_show_suppliers_stmt","pos_hide_stock","pos_show_delivery","enable_delivery_pos","enable_operations","quick_access_col","enable_add_item_from_pos","pos_disable_edit_payment"]],
                        ["title" => "<b>Clients</b>", "settings" => ["enable_delete_customer_on_pos","pos_show_clients_stmt","disable_delete_payment_on_pos"]]
                    ]
                ],
                [
                    "title" => "STATEMENTS",
                    "sections" => [
                        ["title" => "<b>Customers</b>", "settings" => ["arabic_stmt_and_invoice"]],
                    ]
                ],
                [
                    "title" => "PURCHASE INVOICE",
                    "sections" => [
                        ["title" => "<b>Configuration</b>", "settings" => ["default_charge_type"]],
                    ]
                ],
                [
                    "title" => "SYSTEM",
                    "sections" => [
                        ["title" => "<b>VAT</b>", "settings" => ["vat"]],
                        ["title" => "<b>Mobile Shop</b>", "settings" => ["mobile_shop","alfa_sms_fees","touch_sms_fees"]],
                        ["title" => "<b>Wholesale</b>", "settings" => ["enable_wholasale"]],
                        ["title" => "<b>Items</b>", "settings" => ["expiry_interval_days"]],
                    ]
                ],
                [
                    "title" => "SECURITY",
                    "sections" => [
                        ["title" => "<b>PASSWORDS SECURITY NOTIFICATIONS</b>", "settings" => ["notification_for_password_security_check"]],
                    ]
                ],
            ],
            "settings" => [
                "enable_add_item_from_pos"=> ["name" => "Enable Add Items from Pos", "type" => "boolean",],
                "force_select_sales_persion_on_pos" => ["name" => "Force Select Sales Person", "type" => "boolean","note"=>"Enforce Mandatory Selection of Sales Representative upon Invoice Saving"],
                "vat" => ["name" => "Tax - Example: 11% set value to <b>11</b>", "type" => "number", "note" => "Assign a value of 0 to deactivate."],
                "alfa_sms_fees" => ["name" => "ALFA SMS Fee", "type" => "number", "note" => "Deduction of SMS fee upon credit transfer"],
                "touch_sms_fees" => ["name" => "TOUCH SMS Fee", "type" => "number", "note" => "Deduction of SMS fee upon credit transfer"],
                "payment_full" => ["name" => "Cash Payment", "type" => "boolean",],
                "disable_delete_payment_on_pos" => ["name" => "Disable Delete Payments", "type" => "boolean","note"=>"Prohibit the vendor from deleting client payments."],
                "pos_disable_edit_payment" => ["name" => "Disable Edit Payments Details", "type" => "boolean","note"=>"Prohibit the vendor from making adjustments to cash IN/OUT within payments."],
                "payment_credit_card" => ["name" => "Credit Card Payment", "type" => "boolean",],
                "payment_later" => ["name" => "Debt or Later Payment", "type" => "boolean",],
                "auto_print" => ["name" => "Ask for Print", "type" => "optionList", "options" => [["name" => "Disable", "value" => "0"], ["name" =>"Enable", "value" => "2"]]],
                "mobile_shop" => ["name" => "Enable Mobile Shop", "type" => "boolean","note"=>"For Mobile Shop"],
                "sound_play" => ["name" => "Activate Sound For The Scan", "type" => "boolean",],
                "enable_delete_customer_on_pos" => ["name" => "Enable Delete Client on POS", "type" => "boolean",],
                "shop_name" => ["name" => "Your Store Name", "type" => "text",],
                "phone_nb" => ["name" => "Phone", "type" => "text",],
                "address" => ["name" => "Address", "type" => "text",],
                "enable_invoice_discount" => ["name" => "Enable Invoice Discount", "type" => "boolean",],
                "enable_invoice_freight" => ["name" => "Enable Invoice Freight", "type" => "boolean",],
                "pos_hide_stock" => ["name" => "Hide Stock", "type" => "boolean",],
                "pos_show_quotation" => ["name" => "Show Quotation", "type" => "boolean",],
                "pos_show_delivery" => ["name" => "Show Delivery List", "type" => "boolean",],
                "enable_delivery_pos" => ["name" => "Enable Invoice Delivery", "type" => "boolean",],
                "enable_operations" => ["name" => "Enable Money Operations", "type" => "boolean",],
                "pos_show_clients_stmt" => ["name" => "Show Clients Statements", "type" => "boolean",],
                "pos_show_suppliers_stmt" => ["name" => "Show Suppliers Statements", "type" => "boolean",],
                "quick_access_col" => ["name" => "Split Screen", "type" => "boolean",],
                "enable_wholasale" => ["name" => "Enable Wholesale", "type" => "boolean","note"=>"&nbsp;"],
                "enable_invoice_tax" => ["name" => "Activate Invoice Tax Editing", "type" => "boolean","note"=>"You have the option to manually input tax on the invoice."],
                "enable_discount_password" => ["name" => "Enable Invoice Discount", "type" => "boolean",],
                "invoice_pdf_MOF" => ["name" => "TIN - Tax Identification Number", "type" => "text",],
                "expiry_interval_days" => ["name" => "Alert for Expiry Date Before (Days)", "type" => "number",],
                "invoice_footer" => ["name" => "Invoice Footer", "type" => "text",],
                "footer_direction" => ["name" => "Footer Text Direction", "type" => "optionList", "options" => [["name" => "Right To Left", "value" => "rtl"], ["name" =>"Left To Right", "value" => "ltr"]]],
                "invoice_note" => ["name" => "Show Invoice Note", "type" => "boolean",],
                "apply_vat_on_sales_invoice" => ["name" => "Auto Apply Tax", "type" => "boolean","note"=>"Automatically calculate tax on invoice if applicable."],
                "default_print_paper" => ["name" => "Default Printing Page Size", "type" => "optionList", "options" => [["name" => "POS 8CM", "value" => "0"], ["name" => "A4", "value" => "1"]], "note" => "Formatted print version after saving the invoice"],
                "arabic_stmt_and_invoice" => ["name" => "Statemet Language", "type" => "optionList", "options" => [["name" => "English", "value" => "0"], ["name" => "Arabic", "value" => "1"]], "note" => ""],
                "default_currency_symbol" => ["name" => "Default Currency Symbol", "type" => "optionList", "options" => [["name" => "USD", "value" => "USD"], ["name" => "LBP", "value" => "LBP"]], "note" => "Test Note 2 longer longer longer longer"],
                "plu_prefix" => ["name" => "Plu Prefix", "type" => "number",],
                "default_due_date_invoice" => ["name" => "Default Due Date", "type" => "number","note"=>"Specify the Default Due Date for Invoice Reminders"],
                "default_charge_type" => ["name" => "Default Charge Type", "type" => "optionList", "options" => [["name" => "Amount", "value" => "1"], ["name" => "Percentage", "value" => "2"]], "note" => "Set the default charge type fot purchase invoices"],
                "notification_for_password_security_check" => ["name" => "Password Security Checks", "type" => "boolean", "note" => "Display an alert for the super admin if the passwords doesn't meet security standards."]
            ]
        ];
        
        
        if($this->settings_info["usd_but_show_lbp_priority"]==0){
            $settingsEditorConfig["settings"]["print_invoice_lbp_format"] = ["name" => "Receipt Currency", "type" => "optionList", "options" => [["name" => "Only LBP", "value" => "print_invoice_lbp_8cm_1"],["name" => "Only USD", "value" => "print_invoice_usd_8cm"]]];
           $settingsEditorConfig["settings"]["show_usd_in_invoice"] = [];
           $settingsEditorConfig["settings"]["show_barcode_in_invoice"]=[];

        }
        if($this->settings_info["usd_but_show_lbp_priority"]==1){
            $settingsEditorConfig["settings"]["print_invoice_lbp_format"] = [];
            $settingsEditorConfig["settings"]["show_usd_in_invoice"] = ["name" => "Receipt Currency", "type" => "optionList", "options" => [["name" => "Show USD and LBP", "value" => "1"],["name" => "Show Only USD", "value" => "2"],["name" => "Show Only LBP", "value" => "0"]]];
            $settingsEditorConfig["settings"]["show_barcode_in_invoice"] = ["name" => "
Display Barcode on Receipt", "type" => "optionList", "options" => [["name" => "HIDE", "value" => "0"],["name" => "SHOW", "value" => "1"]]];
        }

        
        $settingsToGet = array_keys($settingsEditorConfig["settings"]);
        $oldValues = $settings->getAllSettingsByKeys($settingsToGet);
        $settingsEditorConfig["oldValues"] = array();
        
        foreach ($oldValues as $oldValue){
           
            $settingsEditorConfig["oldValues"][$oldValue["name"]] = $oldValue;
            if($oldValue["name"]=="vat"){
                if($settingsEditorConfig["oldValues"][$oldValue["name"]]["value"]!=0){
                    $settingsEditorConfig["oldValues"][$oldValue["name"]]["value"]=round(($settingsEditorConfig["oldValues"][$oldValue["name"]]["value"]-1)*100,2);
                }else{
                    $settingsEditorConfig["oldValues"][$oldValue["name"]]["value"]=0;
                }
            }
           
        }
        echo json_encode($settingsEditorConfig);
    }
    
    /*
    public function get_settings()
    {
        $settings = $this->model("settings");
        $settingsEditorConfig = [
            "tabs" => [
                [
                    "title" => "Home",
                    "sections" => [
                        ["title" => "Main Settings", "settings" => ["vat", "payment_full", "default_currency_symbol"]],
                        ["title" => "Secondary Settings", "settings" => ["pos_path", "plu_prefix"]]
                    ]
                ],
                [
                    "title" => "Test",
                    "sections" => [
                        ["title" => "Expiery Settings", "settings" => ["show_expiry_date_alert_before_days"]],
                    ]
                ]
            ],
            "settings" => [
                "vat" => ["name" => "Vat", "type" => "number", "note" => "Test Note"],
                "payment_full" => ["name" => "payment_full", "type" => "boolean",],
                "default_currency_symbol" => ["name" => "Default Currency Symbol", "type" => "optionList", "options" => [["name" => "USD", "value" => "USD"], ["name" => "LBP", "value" => "LBP"]], "note" => "Test Note 2 longer longer longer longer"],
                "pos_path" => ["name" => "Pos Path", "type" => "text",],
                "plu_prefix" => ["name" => "Plu Prefix", "type" => "number",],
                "show_expiry_date_alert_before_days" => ["name" => "Show Expiery Date Before", "type" => "number"]
            ]
        ];
        $settingsToGet = array_keys($settingsEditorConfig["settings"]);
        $oldValues = $settings->getAllSettingsByKeys($settingsToGet);
        $settingsEditorConfig["oldValues"] = array();
        foreach ($oldValues as $oldValue)
            $settingsEditorConfig["oldValues"][$oldValue["name"]] = $oldValue;
        echo json_encode($settingsEditorConfig);
    }*/
    
    public function update()
    {

        //For testing use the line below
        // $_SESSION["hide_critical_data"] = 0;
        if ($_SESSION["hide_critical_data"]) {
            echo json_encode(['success' => false]);
            exit();
        }

        
        $value = filter_input(INPUT_POST, "value", self::conversion_php_version_filter());
        $setting_key = filter_input(INPUT_POST, "key", self::conversion_php_version_filter());
        
        if($setting_key=="vat"){
            if($value!=0){
                $value= round(1+$value/100,2);
            }else{
                $value=0;
            }  
        }
        
        $settings = $this->model("settings");
        $settings->update($setting_key, $value);
        echo json_encode(["success" => true]);
    }
}
