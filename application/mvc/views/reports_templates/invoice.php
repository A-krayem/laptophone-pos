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
echo "<!doctype html>\r\n<html lang=\"en\">\r\n<head>\r\n\t<meta charset=\"UTF-8\">\r\n\t<title>Tahir Taous Invoice</title>\r\n        \r\n        <script src=\"libraries/jquery-3.1.1.min.js\" type=\"text/javascript\"></script>\r\n\r\n        <script src=\"libraries/bootstrap-3.3.7-dist/js/bootstrap.min.js\" type=\"text/javascript\"></script>\r\n        <link href=\"libraries/bootstrap-3.3.7-dist/css/bootstrap.min.css\" rel=\"stylesheet\" type=\"text/css\"/>\r\n\t\r\n        \r\n        <style>\r\n\tbody, h1, h2, h3, h4, h5, h6{\r\n\t\t\r\n\t}\r\n        \r\n        .container{\r\n            width: 21cm;\r\n        }\r\n        \r\n        h4{\r\n            font-size: 17px !important;\r\n            font-weight: bold;\r\n        }\r\n        table td{\r\n            font-size: 12px !important;\r\n            font-weight: bold;\r\n        }\r\n        \r\n        table th{\r\n            font-size: 14px !important;\r\n        }\r\n        \r\n        .table{\r\n            margin-top: 16px;\r\n        }\r\n\t</style>\r\n</head>\r\n<body>\r\n\r\n\t<div class=\"container\">\r\n            <!--<div class=\"row\">\r\n                <div class=\"col-xs-6\"><img style=\"width:120px;\" src=\"resources/Adidas_Logo.png\" /></div>\r\n                    <div class=\"col-xs-6 text-right\">&nbsp;</div>\r\n            </div>-->\r\n\t\t<div class=\"row\">\r\n                    \r\n                    <div class=\"col-xs-6\">\r\n                        ";
if (0 < strlen($this->settings_info["invoice_logo"])) {
    echo "<img style='width:120px;' src='resources/" . $this->settings_info["invoice_logo"] . "' />";
}
echo "                        \r\n                        <h1>\r\n                          ";
if ($this->settings_info["invoice_pdf_show_shopname"] == 1) {
    echo $this->settings_info["shop_name"];
}
echo "                        </h1>\r\n\r\n                        ";
if (0 < strlen($this->settings_info["invoice_pdf_MOF"])) {
    echo "<span>" . $this->settings_info["invoice_pdf_MOF"] . "</span><br/>";
}
echo "\r\n                        ";
if (0 < strlen($this->settings_info["invoice_pdf_address"])) {
    echo "<span>" . $this->settings_info["invoice_pdf_address"] . "</span><br/>";
}
echo "\r\n                        ";
if (0 < strlen($this->settings_info["invoice_pdf_phones"])) {
    echo "<span>" . $this->settings_info["invoice_pdf_phones"] . "</span><br/>";
}
echo "\r\n                    </div>\r\n                    <div class=\"col-xs-6 text-right\">\r\n                      <h1>INVOICE</h1>\r\n                      <small>Invoice ref. #";
echo self::idFormat_invoice_print($data["invoice"][0]["id"]);
echo "</small><br/>\r\n                      <small>Date: ";
echo self::idFormat_invoice_print($data["invoice"][0]["creation_date"]);
echo "</small>\r\n\r\n                    </div>\r\n\t\t</div>\r\n            \r\n            \r\n            <div class=\"row\" style=\"margin-top: 50px;\">\r\n                    <div class=\"col-xs-6\">\r\n                        <b>To: </b> ";
if (!is_null($data["customer"])) {
    echo ucwords($data["customer"][0]["name"]);
}
echo "<br/>\r\n                        <b>Address: </b> ";
if (!is_null($data["customer"][0]["address"])) {
    echo ucwords($data["customer"][0]["address"]);
}
echo "<br/>\r\n                        ";
if (!is_null($data["customer"][0]["mof"]) && $data["customer"][0]["mof"] != "-" && $data["customer"][0]["mof"] != "") {
    echo "<b>MOF: </b>" . ucwords($data["customer"][0]["mof"]);
}
echo "                    </div>\r\n                </div>\r\n                <!--\r\n\t\t  <div class=\"row\">\r\n\t\t    <div class=\"col-xs-5\">\r\n\t\t      <div class=\"panel panel-default\">\r\n\t\t              <div class=\"panel-heading\">\r\n\t\t                <h4>From: <a href=\"#\">Tahir Taous</a></h4>\r\n\t\t              </div>\r\n\t\t              <div class=\"panel-body\">\r\n\t\t                <p>\r\n\t\t                  House # A-3717, Street 2<br>\r\n\t\t                  Majeed Colony Sector 2 Landhi <br>\r\n\t\t                  Karachi, Pakistan <br>\r\n\t\t                </p>\r\n\t\t              </div>\r\n\t\t            </div>\r\n\t\t    </div>\r\n\t\t    <div class=\"col-xs-5 col-xs-offset-2 text-right\">\r\n\t\t      <div class=\"panel panel-default\">\r\n\t\t              <div class=\"panel-heading\">\r\n\t\t                <h4>To : <a href=\"#\"> SitePoint.Com</a></h4>\r\n\t\t              </div>\r\n\t\t              <div class=\"panel-body\">\r\n\t\t                <p>\r\n\t\t                  Level 3, 48 Cambridge Street, <br>\r\n\t\t                  Collingwood VIC 3066 <br>\r\n\t\t                  Australia <br>\r\n\t\t                </p>\r\n\t\t              </div>\r\n\t\t            </div>\r\n\t\t    </div>\r\n\t\t  </div> --><!-- / end client details section -->\r\n\r\n                  <table class=\"table table-bordered\">\r\n                      <thead>\r\n                          <tr>\r\n                              <th style=\"width: 75px;\">Item</th>\r\n                              <th style=\"width: 200px;\">Description</th>\r\n                              <th style=\"width: 40px;\">Qty</th>\r\n                              <th>Price/u</th>\r\n                              <th style=\"width: 40px;\">Disc.</th>\r\n                              <th>Total/u</th>\r\n                              <th style=\"width: 40px;\">VAT</th>\r\n                              <th>Total</th>\r\n                          </tr>\r\n                      </thead>\r\n                      <tbody>\r\n                            ";
$total_after_vat = 0;
for ($i = 0; $i < count($data["invoice_items"]); $i++) {
    $item_info = $data["items_instance"]->get_item($data["invoice_items"][$i]["item_id"]);
    echo "                          <tr>\r\n                              <td>";
    echo self::idFormat_item($data["invoice_items"][$i]["item_id"]);
    echo "</td>\r\n                              <td>";
    echo $item_info[0]["description"];
    echo "</td>\r\n                              <td class=\"text-right\">";
    echo floor($data["invoice_items"][$i]["qty"]);
    echo "</td>\r\n                              <td class=\"text-right\">";
    echo number_format($data["invoice_items"][$i]["selling_price"], 2) . " " . $this->settings_info["default_currency_symbol"];
    echo "</td>\r\n                              <td class=\"text-right\">";
    echo floor($data["invoice_items"][$i]["discount"]) . "%";
    echo "</td>\r\n                              <td class=\"text-right\">";
    echo number_format($data["invoice_items"][$i]["selling_price"] * (1 - $data["invoice_items"][$i]["discount"] / 100), 2) . " " . $this->settings_info["default_currency_symbol"];
    echo "</td>\r\n\r\n                              <td class=\"text-right\">";
    if ($data["invoice_items"][$i]["vat"] == 0) {
        echo "-";
    } else {
        echo ($data["invoice_items"][$i]["vat_value"] - 1) * 100 . " %";
    }
    echo "</td>\r\n\r\n                              <td class=\"text-right\">\r\n                                  ";
    $fn = $data["invoice_items"][$i]["final_price_disc_qty"];
    if ($data["invoice_items"][$i]["vat"] == 1) {
        $fn = $fn * $data["invoice_items"][$i]["vat_value"];
    }
    echo number_format($fn, 2) . " " . $this->settings_info["default_currency_symbol"];
    echo "                              </td>\r\n                              \r\n                          </tr>\r\n                          ";
    $total_after_vat += $fn;
}
echo "                      </tbody>\r\n                  </table>\r\n\r\n\t\t<div class=\"row text-right\">\r\n\t\t\t<div class=\"col-xs-6 col-xs-offset-6\">\r\n                            <p>\r\n                                <strong>\r\n                                        Total : ";
echo number_format($total_after_vat, 2) . " " . $this->settings_info["default_currency_symbol"];
echo " <br>\r\n                                </strong>\r\n                            </p>\r\n\t\t\t</div>\r\n\t\t</div>\r\n                <div class=\"row text-right\">\r\n                        <div class=\"col-xs-12\">\r\n\t\t\t\t<strong>\r\n\t\t\t\t\t";
echo convertnumber(number_format($total_after_vat, 2, ".", "")) . " " . $this->settings_info["default_currency_symbol"] . " Only";
echo " <br>\r\n\t\t\t\t</strong>\r\n\t\t\t</div>\r\n\t\t</div>\r\n\r\n\t</div>\r\n\r\n</body>\r\n</html>";
function convertNumber($number)
{
    return $number;
}
function convertGroup($index)
{
    switch ($index) {
        case 11:
            return " decillion";
        case 10:
            return " nonillion";
        case 9:
            return " octillion";
        case 8:
            return " septillion";
        case 7:
            return " sextillion";
        case 6:
            return " quintrillion";
        case 5:
            return " quadrillion";
        case 4:
            return " trillion";
        case 3:
            return " billion";
        case 2:
            return " million";
        case 1:
            return " thousand";
        case 0:
            return "";
    }
}
function convertThreeDigit($digit1, $digit2, $digit3)
{
    $buffer = "";
    if ($digit1 == "0" && $digit2 == "0" && $digit3 == "0") {
        return "";
    }
    if ($digit1 != "0") {
        $buffer .= convertDigit($digit1) . " hundred";
        if ($digit2 != "0" || $digit3 != "0") {
            $buffer .= " and ";
        }
    }
    if ($digit2 != "0") {
        $buffer .= convertTwoDigit($digit2, $digit3);
    } else {
        if ($digit3 != "0") {
            $buffer .= convertDigit($digit3);
        }
    }
    return $buffer;
}
function convertTwoDigit($digit1, $digit2)
{
    if ($digit2 == "0") {
        switch ($digit1) {
            case "1":
                return "ten";
            case "2":
                return "twenty";
            case "3":
                return "thirty";
            case "4":
                return "forty";
            case "5":
                return "fifty";
            case "6":
                return "sixty";
            case "7":
                return "seventy";
            case "8":
                return "eighty";
            case "9":
                return "ninety";
        }
    } else {
        if ($digit1 == "1") {
            switch ($digit2) {
                case "1":
                    return "eleven";
                case "2":
                    return "twelve";
                case "3":
                    return "thirteen";
                case "4":
                    return "fourteen";
                case "5":
                    return "fifteen";
                case "6":
                    return "sixteen";
                case "7":
                    return "seventeen";
                case "8":
                    return "eighteen";
                case "9":
                    return "nineteen";
            }
        } else {
            $temp = convertDigit($digit2);
            switch ($digit1) {
                case "2":
                    return "twenty-" . $temp;
                case "3":
                    return "thirty-" . $temp;
                case "4":
                    return "forty-" . $temp;
                case "5":
                    return "fifty-" . $temp;
                case "6":
                    return "sixty-" . $temp;
                case "7":
                    return "seventy-" . $temp;
                case "8":
                    return "eighty-" . $temp;
                case "9":
                    return "ninety-" . $temp;
            }
        }
    }
}
function convertDigit($digit)
{
    switch ($digit) {
        case "0":
            return "zero";
        case "1":
            return "one";
        case "2":
            return "two";
        case "3":
            return "three";
        case "4":
            return "four";
        case "5":
            return "five";
        case "6":
            return "six";
        case "7":
            return "seven";
        case "8":
            return "eight";
        case "9":
            return "nine";
    }
}

?>