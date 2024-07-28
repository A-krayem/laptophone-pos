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
class garageModel
{
    public function getAllClientsCards()
    {
        $query = "select * from garage_clients_cards where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllClientsCards_sales_person()
    {
        $query = "select id,sales_person from invoices where other_branche=0 and sales_person!=0 and sales_person is not null and id in (select invoice_id from garage_clients_cards where invoice_id is not null and invoice_id!=0)";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getcars_for_type_head()
    {
        $query = "select id,company as name from garage_clients_cards group by company";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getcartype_for_type_head()
    {
        $query = "select id,car_type as name from garage_clients_cards group by car_type";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getcarmodel_for_type_head()
    {
        $query = "select id,model as name from garage_clients_cards group by model";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function assign_card_to_invoice($invoice_id, $card_id)
    {
        $query = "update garage_clients_cards set invoice_id=" . $invoice_id . " where id=" . $card_id;
        my_sql::query($query);
    }
    public function getAllClientsPendingsCards($customer_id)
    {
        $query = "select * from garage_clients_cards where deleted=0 and invoice_id=0 and client_id=" . $customer_id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllClientsCards_DUEOIL()
    {
        $query = "select * from garage_clients_cards where deleted=0 and oil_next_change_date is not null  and date('" . my_sql::datetime_now() . "')>=date(oil_next_change_date)";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function add_new_card($info)
    {
        $query = "INSERT INTO garage_clients_cards(client_id,date_time_in,code,company,car_type,model,color,odometer,car,date_time_out,problem_description,deleted,creation_date,invoice_id,oil_changed_date,oil_next_change_date,oil_note) VALUES (" . $info["customers_list"] . ", '" . $info["date_in"] . "', '" . $info["code"] . "', '" . $info["company"] . "', '" . $info["car_type"] . "', '" . $info["car_model"] . "', " . $info["car_color"] . ", '" . $info["car_odometer"] . "', '" . $info["car_c"] . "', '" . $info["date_out"] . "', '" . $info["problem_description"] . "', 0,'" . my_sql::datetime_now() . "', " . $info["card_invoice"] . "," . $info["oil_changed_date"] . "," . $info["oil_next_change_date"] . ",'" . $info["oil_note"] . "');";
        my_sql::query($query);
        return my_sql::get_mysqli_insert_id();
    }
    public function get_garage_card_id($id)
    {
        $query = "select * from garage_clients_cards where deleted=0 and id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function update_card($info)
    {
        $query = "update garage_clients_cards set client_id=" . $info["customers_list"] . ",date_time_in='" . $info["date_in"] . "' ,code='" . $info["code"] . "' ,company='" . $info["company"] . "' ,car_type='" . $info["car_type"] . "' ,model='" . $info["car_model"] . "' ,color=" . $info["car_color"] . " ,odometer='" . $info["car_odometer"] . "' ,car='" . $info["car_c"] . "' ,date_time_out='" . $info["date_out"] . "' ,problem_description='" . $info["problem_description"] . "',invoice_id= " . $info["card_invoice"] . ",oil_changed_date=" . $info["oil_changed_date"] . ",oil_next_change_date= " . $info["oil_next_change_date"] . ",oil_note= '" . $info["oil_note"] . "' where id = " . $info["id_to_edit"];
        my_sql::query($query);
        return my_sql::get_mysqli_insert_id();
    }
    public function delete_card($id)
    {
        $query = "update garage_clients_cards set deleted=1 where id=" . $id;
        my_sql::query($query);
        return my_sql::get_mysqli_rows_num();
    }
}

?>