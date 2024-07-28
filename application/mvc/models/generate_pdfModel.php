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
class generate_pdfModel
{
    public function generate_pdf_invoice($invoice_id, $main_dir, $website)
    {
        $mpdf = new Mpdf\Mpdf();
        $ch = curl_init();
        $actual_link = $website . "index.php?r=nosession&f=get_client_invoice&p0=" . $invoice_id;
        curl_setopt($ch, CURLOPT_URL, $actual_link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $message = curl_exec($ch);
        curl_close($ch);
        $mpdf->WriteHTML($message);
        $mpdf->Output($main_dir . "/data/invoice_" . $invoice_id . ".pdf", "F");
    }
    public function getStoresById($id)
    {
        $query = "select * from store where id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
    public function getInvoiceById($id)
    {
        $query = "select * from invoices where id=" . $id;
        $result = my_sql::fetch_assoc(my_sql::query($query));
        return $result;
    }
}

?>