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
$query = "select * from optic_new_1.customer";
$result = my_sql::fetch_assoc(my_sql::query($query));
echo "<!DOCTYPE html>\n<!--\nTo change this license header, choose License Headers in Project Properties.\nTo change this template file, choose Tools | Templates\nand open the template in the editor.\n-->\n<html>\n    <head>\n        <title>IMEI Checker</title>\n        <meta charset=\"UTF-8\">\n        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n        <style type=\"text/css\">\n            table tr td{\n                border: 1px solid #ccc;\n            }\n        </style>\n    </head>\n    <body>\n        <table>\n            <tr>\n                <td>tmp_code</td>\n                <td>First name</td>\n                <td>Middle name</td>\n                <td>Last name</td>\n                <td>Address</td>\n                <td>Phone</td>\n                <td>Pd</td>\n                <td>Note</td>\n                <td>Note1</td>\n                <td>Note2</td>\n            </tr>\n            ";
for ($i = 0; $i < count($result); $i++) {
    $pd = addslashes($result[$i]["Pd"]);
    $Note2 = addslashes($result[$i]["Note2"]);
    $Note1 = addslashes($result[$i]["Note1"]);
    $Note = addslashes($result[$i]["Note"]);
    $Phone = addslashes($result[$i]["Phone"]);
    $Address = addslashes($result[$i]["Address"]);
    $query__ = "insert into customers (id,name,middle_name,last_name,address,phone,note,note1,note2,pd,creation_date,created_by)" . "values(" . $result[$i]["CustomerId"] . ",'" . $result[$i]["FirstName"] . "','" . $result[$i]["MiddleName"] . "','" . $result[$i]["LastName"] . "','" . $Address . "','" . $Phone . "','" . $Note . "','" . $Note1 . "','" . $Note2 . "','" . $pd . "',now(),1)";
    $result__ = my_sql::query($query__);
    echo "            <tr style=\"\">\n                <td>";
    if (!$result__) {
        echo $query__;
        exit;
    }
    echo $result[$i]["CustomerId"];
    echo "</td>\n               \n                \n                <td>";
    echo $result[$i]["FirstName"];
    echo "</td>\n                <td>";
    echo $result[$i]["MiddleName"];
    echo "</td>\n                <td>";
    echo $result[$i]["LastName"];
    echo "</td>\n                <td>";
    echo $result[$i]["Address"];
    echo "</td>\n                <td>";
    echo $result[$i]["Phone"];
    echo "</td>\n                <td>";
    echo $result[$i]["Pd"];
    echo "</td>\n                <td>";
    echo $result[$i]["Note"];
    echo "</td>\n                <td>";
    echo $result[$i]["Note1"];
    echo "</td>\n                <td>";
    echo $result[$i]["Note2"];
    echo "</td>\n            </tr>\n            ";
}
echo "            \n        </table>\n        \n    </body>\n</html>\n";

?>