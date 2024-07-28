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
class queries extends Controller
{
    public $settings_info = NULL;
    public function __construct()
    {
    }
    public function _default()
    {
    }
    public function chkq()
    {
        $queries = $this->model("queries");
        $file = fopen("versions/queries.live.1.1.0", "r");
        if ($file) {
            while (($query = fgets($file)) !== false) {
                $result = $queries->execute($query);
                if ($result) {
                    echo "<b>Executed:</b> " . $query . "<br>";
                } else {
                    echo "<b style='color:red'>Error execution:</b> " . $query . "<br>";
                }
            }
            fclose($file);
        } else {
            echo "Failed to open the text file.";
        }
    }
}

?>