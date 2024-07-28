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
ini_set("max_execution_time", 320000);
require_once "../config/define.php";
require_once "../config/my_sql.php";
require_once "../application/core/lib/my_sql.php";
$query = "select * from optic_new_1.`info`";
$result = my_sql::fetch_assoc(my_sql::query($query));
echo "<!DOCTYPE html>\n<!--\nTo change this license header, choose License Headers in Project Properties.\nTo change this template file, choose Tools | Templates\nand open the template in the editor.\n-->\n<html>\n    <head>\n        <title>OPTIC DATA</title>\n        <meta charset=\"UTF-8\">\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n        <style type=\"text/css\">\n            table tr td{\n                border: 1px solid #ccc;\n            }\n        </style>\n    </head>\n    <body>\n        <table>\n            <tr>\n                <td>Cl_ReyeSph</td>\n                <td>Cl_ReyeCyl</td>\n                <td>Cl_ReyeAxis</td>\n                <td>Cl_ReyePrism</td>\n                <td>Cl_LeyeSph</td>\n                <td>Cl_LeyeCyl</td>\n                <td>Cl_LeyeAxis</td>\n                <td>Cl_LeyePrism</td>\n                <td>Cl_Doctor</td>\n                <td>Cl_Date</td>\n            </tr>\n            ";
for ($i = 0; $i < count($result); $i++) {
    $result[$i]["PrescriptionDate"] = str_replace(array("/", "\\"), "", $result[$i]["PrescriptionDate"]);
    $date = date("Y-m-d", strtotime($result[$i]["PrescriptionDate"]));
    $type = "";
    if ($result[$i]["Type"] == "far") {
        $type = "distance";
    }
    if ($result[$i]["Type"] == "near") {
        $type = "near";
    }
    if ($result[$i]["Type"] == "lens") {
        $type = "lens";
    }
    $REyeCyl = addslashes($result[$i]["REyeCyl"]);
    $REyeSph = addslashes($result[$i]["REyeSph"]);
    $REyeAxis = addslashes($result[$i]["REyeAxis"]);
    $REyePrism = addslashes($result[$i]["REyePrism"]);
    $LEyeSph = addslashes($result[$i]["LEyeSph"]);
    $LEyeCyl = addslashes($result[$i]["LEyeCyl"]);
    $LEyeAxis = addslashes($result[$i]["LEyeAxis"]);
    $LEyePrism = addslashes($result[$i]["LEyePrism"]);
    $Doctor = addslashes($result[$i]["Doctor"]);
    $query__ = "insert into optic_details (r_eye_sph,r_eye_cyl,r_eye_axis,r_eye_prism,l_eye_sph,l_eye_cyl,l_eye_axis,l_eye_prism,doctor,date,client_id,type,creation_date)" . "values('" . $REyeSph . "','" . $REyeCyl . "','" . $REyeAxis . "','" . $REyePrism . "'," . "'" . $LEyeSph . "','" . $LEyeCyl . "','" . $LEyeAxis . "','" . $LEyePrism . "','" . $Doctor . "','" . $date . "','" . $result[$i]["CustomerId"] . "','" . $type . "',now())";
    $result__ = my_sql::query($query__);
    echo "            <tr style=\"\">\n                <td>";
    if (!$result__) {
        echo $query__;
        exit;
    }
    echo $result[$i]["REyeSph"];
    echo "</td>\n                <td>";
    echo $result[$i]["REyeCyl"];
    echo "</td>\n                <td>";
    echo $result[$i]["REyeAxis"];
    echo "</td>\n                <td>";
    echo $result[$i]["REyePrism"];
    echo "</td>\n                <td>";
    echo $result[$i]["LEyeSph"];
    echo "</td>\n                <td>";
    echo $result[$i]["LEyeCyl"];
    echo "</td>\n                <td>";
    echo $result[$i]["LEyeAxis"];
    echo "</td>\n                <td>";
    echo $result[$i]["LEyePrism"];
    echo "</td>\n                <td>";
    echo $result[$i]["Doctor"];
    echo "</td>\n                <td>";
    echo $result[$i]["PrescriptionDate"];
    echo "</td>\n            </tr>\n            ";
}
echo "            \n        </table>\n        \n    </body>\n</html>\n";

?>