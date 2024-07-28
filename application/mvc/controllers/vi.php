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
class vi extends Controller
{
    public function __construct()
    {
        $this->checkAuth();
        self::giveAccessTo();
    }
    public function generate_vi($_daterange)
    {
        $data = array();
        $this->view("print_templates/a4/vi", $data);
    }
}

?>