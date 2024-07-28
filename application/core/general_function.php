<?php

class general_function {
 
    public function __USORT_TIMESTAMP(&$array){
        usort($array, function($a, $b) {
            return $a['timestamp'] - $b['timestamp'];
        }); 
    }   

}
