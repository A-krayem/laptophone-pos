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
class countriesModel
{
    public function getCountries()
    {
        $query = "select * from countries where disabled=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function get_default()
    {
        $query = "select * from countries where default_selection=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result[0]["id"];
    }
    public function getTotalLocations()
    {
        $query = "select count(id) as num from cities";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function update_points($city, $points)
    {
        $query = "update cities set delivery_points=" . $points . " where id=" . $city;
        my_sql::query($query);
    }
    public function get_points($city)
    {
        $query = "select delivery_points from cities where id=" . $city;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getCountriesEvenDeleted()
    {
        $query = "select * from countries where disabled=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function add_new_area($country_id, $area_name)
    {
        $query = "insert into areas(name,country_id) value('" . $area_name . "'," . $country_id . ")";
        my_sql::fetch_assoc(my_sql::query($query));
        $last_insert_id = my_sql::get_mysqli_insert_id();
        return $last_insert_id;
    }
    public function add_new_district($area_id, $district_name)
    {
        $query = "insert into districts(name,area_id) value('" . $district_name . "'," . $area_id . ")";
        my_sql::fetch_assoc(my_sql::query($query));
        $last_insert_id = my_sql::get_mysqli_insert_id();
        return $last_insert_id;
    }
    public function add_new_city($district_id, $city_name)
    {
        $query = "insert into cities(name,district_id) value('" . $city_name . "'," . $district_id . ")";
        my_sql::fetch_assoc(my_sql::query($query));
        $last_insert_id = my_sql::get_mysqli_insert_id();
        return $last_insert_id;
    }
    public function getAreas()
    {
        $query = "select * from areas order by name asc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAreasByCountryId($country_id)
    {
        $query = "select * from areas where country_id=" . $country_id . " order by name asc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getDistricts($area_id)
    {
        $query = "select * from districts where area_id=" . $area_id . " order by name asc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllDistricts()
    {
        $query = "select * from districts order by name asc ";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllCities()
    {
        $query = "select * from cities where deleted=0 order by name asc ";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getCitiesByDistrictId($district_id)
    {
        $query = "select * from cities where district_id=" . $district_id . " and deleted=0 order by name asc";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllInfoLocation($city_id)
    {
        $query = "select ct.id as city_id,ct.district_id as district_id,ds.area_id as area_id,ctr.id as country_id,ctr.country_name as country_name,ct.name as city_name,ds.name as district_name,ar.name as area_name from cities ct,districts ds,areas ar,countries ctr where ct.deleted=0 and ds.id=ct.district_id and ar.id=ds.area_id and ar.country_id=ctr.id and ct.id=" . $city_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function allInfoLocation()
    {
        $query = "select ct.id as city_id,ct.district_id as district_id,ds.area_id as area_id,ctr.id as country_id,ctr.country_name as country_name,ct.name as city_name,ct.delivery_points as delivery_points,ds.name as district_name,ar.name as area_name from cities ct,districts ds,areas ar,countries ctr where ct.deleted=0 and ds.id=ct.district_id and ar.id=ds.area_id and ar.country_id=ctr.id";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getDefaultCityLocation()
    {
        $query = "select ct.id as city_id,ct.district_id as district_id,ds.area_id as area_id,ctr.id as country_id,ctr.country_name as country_name,ct.name as city_name,ct.delivery_points as delivery_points,ds.name as district_name,ar.name as area_name from cities ct,districts ds,areas ar,countries ctr where ct.deleted=0 and ds.id=ct.district_id and ar.id=ds.area_id and ar.country_id=ctr.id and ct.default_selected=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
}

?>