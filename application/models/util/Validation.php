<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Validation extends CI_Model {

    public function valid_string_post($data = null, $minlength = 0, $maxlength = 0) {
        
        //null
        if ($data == null) return null;

        //is string
        if (!is_string($data)) return null;

        //string length
        if ($maxlength != 0 && $minlength > strlen($data) && $maxlength < strlen($data)) return null;

        $data = stripslashes(strip_tags(trim($data)));
        $data = $this->security->xss_clean($data);

        return $data;
    }

    public function valid_int_post($data = null, $min = 0, $max = 0) {
        
        //null
        if ($data === null) return null;
        //is integer
        if (!is_integer($data)) return null;

        //string length
        if ($max != 0 && $min > $data && $max < $data) return null;

        return $data;
    }

    public function valid_date_post($data = null) {
        
        //null
        if ($data == null) return null;

        //is date
        if ( !(DateTime::createFromFormat('Y-m-d', $data) !== FALSE) ) return null;

        return $data;
    }

    public function valid_time_post($data = null) {
        
        //null
        if ($data == null) return null;

        //is time
        if ( !(DateTime::createFromFormat('H:i', $data) !== FALSE) ) return null;

        return $data;
    }
	
}
