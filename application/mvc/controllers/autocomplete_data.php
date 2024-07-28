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
class autocomplete_data extends Controller
{
    public $licenseExpired = false;
    public function cashinout()
    {
        $this->checkAuth();
        $this->licenseExpired = self::licenseExpired();
    }
    public function getitems_for_type_head()
    {
        self::giveAccessTo();
        $items = $this->model("items");
        $info = $items->getitems_for_type_head();
        echo json_encode($info);
    }
    public function getcars_for_type_head()
    {
        self::giveAccessTo(array(2, 4));
        $garage = $this->model("garage");
        $info = $garage->getcars_for_type_head();
        echo json_encode($info);
    }
    public function getcartype_for_type_head()
    {
        self::giveAccessTo(array(2, 4));
        $garage = $this->model("garage");
        $info = $garage->getcartype_for_type_head();
        echo json_encode($info);
    }
    public function getcarmodel_for_type_head()
    {
        self::giveAccessTo(array(2, 4));
        $garage = $this->model("garage");
        $info = $garage->getcarmodel_for_type_head();
        echo json_encode($info);
    }
    public function getsup_name_for_type_head()
    {
        self::giveAccessTo(array(2, 4));
        $suppliers = $this->model("suppliers");
        $info = $suppliers->getsup_name_for_type_head();
        echo json_encode($info);
    }
    public function getfirstname_for_type_head()
    {
        self::giveAccessTo(array(2, 4));
        $customers = $this->model("customers");
        $info = $customers->getfirstname_for_type_head();
        echo json_encode($info);
    }
    public function getmiddlename_for_type_head()
    {
        self::giveAccessTo(array(2, 4));
        $customers = $this->model("customers");
        $info = $customers->getmiddlename_for_type_head();
        echo json_encode($info);
    }
    public function getlastname_for_type_head()
    {
        self::giveAccessTo(array(2, 4));
        $customers = $this->model("customers");
        $info = $customers->getlastname_for_type_head();
        echo json_encode($info);
    }
}

?>