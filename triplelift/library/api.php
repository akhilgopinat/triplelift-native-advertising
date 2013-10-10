<?php

class Triplelift_np_admin_api {
    
    protected $_token;
    
    function __construct() {
    }
    
    function authenticate_username($username, $password) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, TRIPLELIFT_NP_API_URL.'login/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array('username' => $username, 'password' => $password)));
        $result = curl_exec($ch);
        $auth_out = json_decode($result);
        if (isset($auth_out->status) && $auth_out->status) {
            return array('token' => $auth_out->token, 'member_id' => $auth_out->member->id);
        } else {
            return false;
        } 
    }

    function authenticate_token($token) {
        $this->_token = $token;
        $out = $this->do_get('member/');			
        if (isset($out->status) && $out->status == true) {
            return true;
        } else {
            return false;
        }
    }

    function do_get($path, $result_to_assoc = false) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, TRIPLELIFT_NP_API_URL.$path);
		if (isset($this->_token)) {
	        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Auth-token: '.$this->_token));
		}
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        $result = curl_exec($ch);
        return json_decode($result, $result_to_assoc);
    }
    
    function do_post($path, $data_as_array, $result_to_assoc = false) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, TRIPLELIFT_NP_API_URL.$path);
		if (isset($this->_token)) {
        	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Auth-token: '.$this->_token));
		}
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_as_array));
        $result = curl_exec($ch);
        return json_decode($result, $result_to_assoc);
    }
    
}
