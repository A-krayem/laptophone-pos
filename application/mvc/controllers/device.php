<?php

class device extends Controller {

    public $licenseExpired = false;
    
    public function __construct() {
        $this->checkAuth();
    }
    
    public function defaultp(){
        $data=[];
       $this->view("device/device",$data);
    }
    
    public function defaultp1(){
        $data=[];
       $this->view("device/device_new",$data);
    }

}
