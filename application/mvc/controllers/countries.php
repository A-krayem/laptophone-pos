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
class countries extends Controller
{
    public $licenseExpired = false;
    public $settings_info = array();
    public function __construct()
    {
        $this->checkAuth();
        $this->licenseExpired = self::licenseExpired();
        $this->settings_info = self::getSettings();
    }
    public function getCountries()
    {
        $countries = $this->model("countries");
        $currency = $this->model("currency");
        $info["countries"] = $countries->getCountries();
        $info["suppliers_complex_stmt"] = $this->settings_info["suppliers_complex_stmt"];
        $info["currency_counnt"] = $_SESSION["currency_counnt"];
        $info["default_currency"] = $currency->getDefaultActiveCurrency();
        echo json_encode($info);
    }
    public function get_districts_by_area_id($area_id)
    {
        $countries = $this->model("countries");
        $info = $countries->getDistricts($area_id);
        echo json_encode($info);
    }
    public function get_area_by_country_id($country_id)
    {
        $countries = $this->model("countries");
        $info = $countries->getAreasByCountryId($country_id);
        echo json_encode($info);
    }
    public function get_cities_by_district_id($district_id)
    {
        $countries = $this->model("countries");
        $info = $countries->getCitiesByDistrictId($district_id);
        echo json_encode($info);
    }
    public function add_new_area($_country_id)
    {
        $countries = $this->model("countries");
        $area_name = filter_input(INPUT_POST, "area_name", self::conversion_php_version_filter());
        $country_id = filter_var($_country_id, FILTER_SANITIZE_NUMBER_INT);
        $id = $countries->add_new_area($country_id, $area_name);
        $info = array();
        $info["id"] = $id;
        $info["name"] = $area_name;
        echo json_encode($info);
    }
    public function add_new_district($_area_id)
    {
        $countries = $this->model("countries");
        $district_name = filter_input(INPUT_POST, "district_name", self::conversion_php_version_filter());
        $area_id = filter_var($_area_id, FILTER_SANITIZE_NUMBER_INT);
        $id = $countries->add_new_district($area_id, $district_name);
        $info = array();
        $info["id"] = $id;
        $info["name"] = $district_name;
        echo json_encode($info);
    }
    public function add_new_city($_district_id)
    {
        $countries = $this->model("countries");
        $city_name = filter_input(INPUT_POST, "city_name", self::conversion_php_version_filter());
        $district_id = filter_var($_district_id, FILTER_SANITIZE_NUMBER_INT);
        $id = $countries->add_new_city($district_id, $city_name);
        $info = array();
        $info["id"] = $id;
        $info["name"] = $city_name;
        echo json_encode($info);
    }
}

?>