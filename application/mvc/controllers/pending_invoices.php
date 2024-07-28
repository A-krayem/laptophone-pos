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
class pending_invoices extends controller
{
    public function save($_p0)
    {
        $auto_hold = filter_var($_p0, FILTER_SANITIZE_NUMBER_INT);
        $data = array();
        $data["note"] = filter_input(INPUT_POST, "note", self::conversion_php_version_filter());
        $data["location"] = filter_input(INPUT_POST, "location", self::conversion_php_version_filter());
        $data["CurrentActive"] = filter_input(INPUT_POST, "CurrentActive", FILTER_SANITIZE_NUMBER_INT);
        $data["fullData"] = $_POST["fullData"];
        for ($i = 0; $i < count($data["fullData"]["ALlitems"]); $i++) {
            $data["fullData"]["ALlitems"][$i]["description"] = preg_replace("/'/", "", $data["fullData"]["ALlitems"][$i]["description"]);
        }
        $pendingInvoices = $this->model("pendingInvoices");
        if (0 < $data["CurrentActive"]) {
            $pendingInvoices->delete($data["CurrentActive"]);
        }
        if ($auto_hold == 0) {
            $insertId = $pendingInvoices->save($data["fullData"], $data["note"], $data["location"]);
        } else {
            $insertId = $pendingInvoices->save_auto_hold($data["fullData"], $data["note"], $data["location"]);
        }
        echo json_encode(array("success" => 0 < $insertId, "id" => $insertId));
    }
    public function getAll()
    {
        $pendingInvoices = $this->model("pendingInvoices");
        $pendingArray = $pendingInvoices->getAll();
        $returnArray["data"] = array();
        for ($i = 0; $i < count($pendingArray); $i++) {
            $tmp = array();
            array_push($tmp, $pendingArray[$i]["id"]);
            if ($pendingArray[$i]["is_current_temp"] == 1) {
                array_push($tmp, "<b class='text-info'>" . $pendingArray[$i]["note"] . "</b>");
                array_push($tmp, "<b class='text-info'>" . $pendingArray[$i]["location"] . "</b>");
            } else {
                array_push($tmp, $pendingArray[$i]["note"]);
                array_push($tmp, $pendingArray[$i]["location"]);
            }
            array_push($tmp, $pendingArray[$i]["created_by"]);
            array_push($tmp, self::date_time_format_custom($pendingArray[$i]["creation_date"]));
            $tmp_ar = json_decode($pendingArray[$i]["data"], true);
            array_push($tmp, $tmp_ar["totalQty"]);
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($tmp, "");
            array_push($returnArray["data"], $tmp);
        }
        echo json_encode($returnArray);
    }
    public function delete($_pending_id)
    {
        $pending_id = filter_var($_pending_id, FILTER_SANITIZE_NUMBER_INT);
        $pendingInvoices = $this->model("pendingInvoices");
        $deleted = $pendingInvoices->delete($pending_id);
        echo json_encode(array("success" => $deleted));
    }
    public function get($_pending_id)
    {
        $pending_id = filter_var($_pending_id, FILTER_SANITIZE_NUMBER_INT);
        $pendingInvoices = $this->model("pendingInvoices");
        $data["pendingInvoice"] = $pendingInvoices->get($pending_id);
        $data["pendingInvoice"]["data"] = json_decode($data["pendingInvoice"]["data"], true);
        echo json_encode($data);
    }
}

?>