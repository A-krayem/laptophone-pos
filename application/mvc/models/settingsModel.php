<?php

class settingsModel
{

    public function get_settings()
    {
        $query = "select * from settings";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }

    public function get_settings_by_name($name)
    {
        $query = "select * from settings where name='" . $name . "'";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }

    public function get_barcode_settings()
    {
        $query = "select * from barcode_params";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }

    public function get_barcode_local_settings()
    {
        $query = "select * from barcode_params";
        $result = my_sql::fetch_assoc(my_sql::query_local($query));
        return $result;
    }

    public function get_settings_local()
    {
        $query = "select * from settings";
        $result = my_sql::fetch_assoc(my_sql::query_local($query));
        return $result;
    }

    public function update_value($printer_name, $param_name)
    {
        $query = "update settings set value='" . $printer_name . "' where name='" . $param_name . "'";

        my_sql::query($query);
    }

    public function update_value_with_sync($printer_name, $param_name)
    {
        $query = "update settings set value='" . $printer_name . "' where name='" . $param_name . "'";
        my_sql::query($query);
        if (my_sql::get_mysqli_rows_num() > 0) {
            my_sql::global_query_sync($query);
        }
    }


    public function enable_disable($setting_name, $value)
    {
        $query = "update settings set value='" . $value . "' where name='" . $setting_name . "'";
        my_sql::query($query);
    }

    public function update_local_barcode_value($value, $param_name)
    {
        $query = "update barcode_params set value='" . $value . "' where name='" . $param_name . "'";
        my_sql::query_local($query);
    }

    public function update_local_value($printer_name, $param_name)
    {
        $query = "update settings set value='" . $printer_name . "' where name='" . $param_name . "'";
        my_sql::query_local($query);
    }

    public function get_payment_status()
    {
        $query = "select * from payment_status where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }

    public function get_payment_method()
    {
        $query = "select * from payment_methods where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }

    public function get_banks()
    {
        $query = "select * from banks where deleted=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }

    public function add_new_bank($info)
    {
        $query = "insert into banks(name) values('" . $info["bank_name"] . "')";
        my_sql::query($query);
        return my_sql::get_mysqli_insert_id();
    }

    public function get_all_payment_method()
    {
        $query = "select * from payment_methods";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }

    public function get_all_payment_method_by_id($id)
    {
        $query = "select * from payment_methods where id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }

    public function setCurrencySymbole()
    {
        $query = "select * from currencies where system_default=1";
        $result = my_sql::fetch_assoc(my_sql::query($query));

        $query = "update settings set value='" . $result[0]["symbole"] . "' where name='default_currency_symbol'";
        my_sql::query($query);
    }


    public function getCurrencies()
    {
        $query = "select * from currencies where disabled=0";
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getAllSettingsByKeys($keys)
    {
        return my_sql::fetch_assoc(my_sql::query("SELECT * FROM settings where name in ('" . implode("','", $keys) . "')"));
    }
    public function update($setting_key, $value)
    {
        $old =    my_sql::fetch_assoc(my_sql::query("SELECT * FROM settings where name='$setting_key'"))[0];
        $name = $old["name"];
        $setting_id = $old["id"];
        $oldValue = $old["value"];
        $createdBy = $_SESSION["id"];
        my_sql::query("INSERT into settings_log(settings_id,settings_name,created_by,creation_date,old_value,new_value) values('$setting_id','$name',$createdBy,now(),'$oldValue','$value')");
        my_sql::query("UPDATE settings set value='$value' where name='$setting_key'");
    }
}
