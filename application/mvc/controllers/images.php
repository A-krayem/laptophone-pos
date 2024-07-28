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
class images extends Controller
{
    public $licenseExpired = false;
    public $settings_info = NULL;
    public function __construct()
    {
        $this->checkAuth();
        $this->licenseExpired = self::licenseExpired();
        $this->settings_info = self::getSettings();
    }
    public function delete_item_images($_image_id)
    {
        self::giveAccessTo();
        $image_id = filter_var($_image_id, FILTER_SANITIZE_NUMBER_INT);
        $items = $this->model("items");
        $items->delete_item_images($image_id);
        $images_info = $items->get_images_info($image_id);
        $imageFilePath = "data/images_items/" . $images_info[0]["name"];
        if (file_exists($imageFilePath)) {
            unlink($imageFilePath);
        }
        echo json_encode(array());
    }
    public function uploadimages($_item_id)
    {
        self::giveAccessTo();
        $folderPath = "data/images_items";
        $size = self::getFolderSize($folderPath);
        $bytesValue = $this->settings_info["max_upload_images_size_gb"] * pow(2, 30);
        if ($bytesValue < $size) {
            echo json_encode(array(0));
            exit;
        }
        $item_id = filter_var($_item_id, FILTER_SANITIZE_NUMBER_INT);
        $items = $this->model("items");
        $target_dir = "data/images_items/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 511, true);
        }
        for ($i = 0; $i < count($_FILES["itemsimages"]["name"]); $i++) {
            $name = $item_id . "-" . time() . "-" . basename($_FILES["itemsimages"]["name"][$i]);
            $target_file = $target_dir . $name;
            if (move_uploaded_file($_FILES["itemsimages"]["tmp_name"][$i], $target_file)) {
                $items->image_uploaded($item_id, $name);
            }
        }
        echo json_encode(array(1));
    }
    public function getFolderSize($folderPath)
    {
        $totalSize = 0;
        $dirIterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folderPath, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST);
        foreach ($dirIterator as $file) {
            $totalSize += $file->getSize();
        }
        return $totalSize;
    }
    public function formatSizeUnits($bytes)
    {
        $units = array("B", "KB", "MB", "GB", "TB");
        for ($i = 0; 1024 <= $bytes && $i < 4; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, 2) . " " . $units[$i];
    }
    public function show_images($_items_id)
    {
        self::giveAccessTo();
        $items_id = filter_var($_items_id, FILTER_SANITIZE_NUMBER_INT);
        $data_array = array();
        $data_array["data"] = array();
        $folderPath = "data/images_items";
        $size = self::getFolderSize($folderPath);
        $data_array["max_pictures_storage"] = number_format($this->settings_info["max_upload_images_size_gb"], 2) . " GB";
        $data_array["current_pictures_storage"] = self::formatSizeUnits($size);
        $items = $this->model("items");
        $info = $items->get_images_of_item($items_id);
        $item_info = $items->get_item($items_id);
        for ($i = 0; $i < count($info); $i++) {
            $tmp = array();
            array_push($tmp, $info[$i]["id"]);
            array_push($tmp, $item_info[0]["description"]);
            array_push($tmp, $info[$i]["creation_date"]);
            array_push($tmp, "<a href=\"data/images_items/" . $info[$i]["name"] . "\" data-lightbox=\"image-1\"><img style=\"cursor:pointer\" src=\"data/images_items/" . $info[$i]["name"] . "\" class=\"img-thumbnail\" alt=\"\"></a>");
            array_push($tmp, "");
            array_push($data_array["data"], $tmp);
        }
        echo json_encode($data_array);
    }
}

?>