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
class deliveries extends Controller
{
    public $settings_info = NULL;
    public function __construct()
    {
        $this->checkAuth();
        $this->settings_info = self::getSettings();
    }
    public function prepare_delivery_data()
    {
        echo json_encode(array());
    }
    public function submitbulk_payment()
    {
        $invoice_model = $this->model("invoice");
        $customers_model = $this->model("customers");
        $step = filter_input(INPUT_POST, "bulk_payment_step", FILTER_SANITIZE_NUMBER_INT);
        $ids = filter_input(INPUT_POST, "del_ids", self::conversion_php_version_filter());
        $ids_array = array();
        if (0 < strlen($ids)) {
            $ids_array = array_unique(explode(",", $ids));
        }
        $ids_array_tmp = array();
        $payments_array_tmp = array();
        for ($i = 0; $i < count($ids_array); $i++) {
            $tmp = explode("/", $ids_array[$i]);
            if (count($tmp) == 1) {
                array_push($ids_array_tmp, $tmp[0]);
            }
            if (count($tmp) == 2) {
                array_push($ids_array_tmp, $tmp[0]);
                $payments_array_tmp[md5($tmp[0])] = $tmp[1];
            }
        }
        $ids_array = $ids_array_tmp;
        $invoice_details = $invoice_model->get_all_invoices_by_delivery_code($ids_array);
        if ($step == 0) {
            $return = array();
            $invoice_already_paid = $invoice_model->get_all_invoices_already_paid_code($ids_array);
            $return["total_invoices"] = number_format(count($invoice_details));
            $return["invoices_not_exists"] = number_format(count($ids_array) - count($invoice_details) - count($invoice_already_paid));
            $return["invoices_not_exists_details"] = $invoice_model->get_all_invoices_code_not_exist($ids_array);
            $return["invoices_already_paid"] = number_format(count($invoice_already_paid));
            $return["data"] = array();
            $total_amount = 0;
            for ($i = 0; $i < count($invoice_details); $i++) {
                if (isset($payments_array_tmp[md5($invoice_details[$i]["delivery_ref"])])) {
                    $total_amount += $payments_array_tmp[md5($invoice_details[$i]["delivery_ref"])];
                } else {
                    $total_amount += $invoice_details[$i]["amount"];
                }
                $cname = "";
                if (0 < $invoice_details[$i]["customer_id"]) {
                    $cresult = $customers_model->getCustomersById($invoice_details[$i]["customer_id"]);
                    if (0 < count($cresult)) {
                        $cname = $cresult[0]["name"] . " " . $cresult[0]["last_name"];
                    }
                }
                array_push($return["data"], array($invoice_details[$i]["id"], $invoice_details[$i]["delivery_ref"], $cname, number_format($invoice_details[$i]["amount"], 2)));
            }
            $return["total_amount"] = number_format($total_amount, 2);
            echo json_encode($return);
        } else {
            $payment_model = $this->model("payments");
            for ($i = 0; $i < count($invoice_details); $i++) {
                $payment = array();
                $payment["id_to_edit"] = 0;
                $payment["customer_id"] = $invoice_details[$i]["customer_id"];
                $payment["invoice_id"] = $invoice_details[$i]["id"];
                $payment["quotation_id"] = 0;
                $amount_to_pay = $invoice_details[$i]["amount"];
                if (isset($payments_array_tmp[md5($invoice_details[$i]["delivery_ref"])])) {
                    $amount_to_pay = $payments_array_tmp[md5($invoice_details[$i]["delivery_ref"])];
                }
                $payment["value"] = $amount_to_pay;
                $payment["note"] = "";
                $payment["payment_method"] = 1;
                $payment["creation_date"] = date("Y-m-d");
                $payment["bank_id"] = 0;
                $payment["reference_nb"] = "";
                $payment["owner"] = "";
                $payment["voucher"] = "";
                $payment["picture"] = "";
                $payment["vendor_id"] = $_SESSION["id"];
                $payment["currency_id"] = 1;
                $payment["rate_value"] = 1;
                $payment["rate"] = 1;
                $payment["store_id"] = $_SESSION["store_id"];
                if (isset($_SESSION["cashbox_id"])) {
                    $payment["cashbox_id"] = $_SESSION["cashbox_id"];
                } else {
                    $payment["cashbox_id"] = 0;
                }
                $payment["cash_in_usd"] = $amount_to_pay;
                $payment["cash_in_lbp"] = 0;
                $payment["returned_usd"] = 0;
                $payment["returned_lbp"] = 0;
                $payment["to_returned_usd"] = 0;
                $payment["to_returned_lbp"] = 0;
                $payment["p_rate"] = $this->settings_info["usdlbp_rate"];
                $id = $payment_model->add_payment_to_customer_new($payment);
                if (0 < $id) {
                    if ($amount_to_pay == $invoice_details[$i]["amount"]) {
                        $invoice_model->setAutoClosedInvoice($invoice_details[$i]["id"]);
                        $invoice_model->status_changed($invoice_details[$i]["id"], 3);
                    } else {
                        if (0 < $invoice_details[$i]["customer_id"]) {
                            self::autoCloseInvoices($invoice_details[$i]["customer_id"]);
                            $invoice_info = $invoice_model->get_invoice_by_id($invoice_details[$i]["id"]);
                            if ($invoice_info[0]["auto_closed"] == 1) {
                                $invoice_model->setAutoClosedInvoice($invoice_details[$i]["id"]);
                                $invoice_model->status_changed($invoice_details[$i]["id"], 3);
                            }
                        }
                    }
                    $global_logs = $this->model("global_logs");
                    $logs_info = array();
                    $logs_info["operator_id"] = $_SESSION["id"];
                    $logs_info["related_to_item_id"] = 0;
                    $logs_info["description"] = "Auto Payment(" . $id . ") using delivery reference " . $invoice_details[$i]["delivery_ref"];
                    $logs_info["log_type"] = 2;
                    $logs_info["other_info"] = $id;
                    $global_logs->add_global_log($logs_info);
                }
            }
            echo json_encode(array());
        }
    }
    public function get_delivery_companies($_p0, $_p1, $_p2, $_p3, $_p4, $_p5, $_p6, $_p7, $_p8, $_p9, $_p10)
    {
        $data_array["data"] = array();
        for ($i = 0; $i < 50; $i++) {
            $tmp = array();
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
    public function get_delivery_details($_p0, $_p1, $_p2, $_p3, $_p4, $_p5, $_p6, $_p7, $_p8, $_p9, $_p10)
    {
        $data_array["data"] = array();
        for ($i = 0; $i < 50; $i++) {
            $tmp = array();
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
}

?>