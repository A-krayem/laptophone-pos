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
class App
{
    protected $controller = "login";
    protected $methode = "_default";
    protected $params = array();
    public function __construct()
    {
        $url = array();
        $userT = $this->controller;
        if (isset($_GET["r"])) {
            $userT = $_GET["r"];
        }
        if (isset($_GET["f"])) {
            $url[0] = $_GET["f"];
        }
        if (file_exists("./application/mvc/controllers/" . $userT . ".php")) {
            $this->controller = $userT;
            unset($userT);
        }
        require_once "./application/mvc/controllers/" . $this->controller . ".php";
        $this->controller = new $this->controller();
        if (isset($url[0]) && method_exists($this->controller, $url[0])) {
            $this->methode = $url[0];
            unset($url[0]);
        }
        foreach ($_GET as $key => $value) {
            if ($key[0] == "p") {
                array_push($this->params, $value);
            }
        }
        $ReflectionFoo = new ReflectionClass($this->controller);
        if ($ReflectionFoo->getMethod($this->methode)->getNumberOfParameters() == count($this->params)) {
            call_user_func_array(array($this->controller, $this->methode), $this->params);
            return NULL;
        }
        exit;
    }
}

?>