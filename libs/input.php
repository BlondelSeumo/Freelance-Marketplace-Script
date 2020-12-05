<?php

class Input{
	
	public function secure($val){
	  return (is_array($val))?array_map(array($this, 'secure'),$val):htmlspecialchars($val, ENT_COMPAT, 'UTF-8');
	}

	public function get($key=''){
		if(!empty($key)){ 
			if(is_array($_GET[$key])){
				$array = $this->secure($_GET[$key]);
				return filter_var_array($array, FILTER_SANITIZE_STRING);
			}else{
				return htmlspecialchars(filter_input(INPUT_GET, $key), ENT_COMPAT, 'UTF-8');
			}
		}else{
		 $values = [];
	    foreach($_GET as $key => $value){
	      $values["$key"] = htmlspecialchars(filter_input(INPUT_GET, $key), ENT_COMPAT, 'UTF-8');
	    }
    	return $values;
    }
	}

	public function post($data=''){
		if(@is_array($_POST[$data]) or empty($data)){
			if(empty($data)){
				$array = $_POST;
			}else{
				$array = $_POST[$data];
			}
			// $array = call_user_func_array('mb_convert_encoding',array($array,'HTML-ENTITIES','UTF-8'));
			$array = filter_var_array($array, FILTER_SANITIZE_STRING); 
			return $array;
		}else{
			$val = htmlspecialchars(filter_input(INPUT_POST,$data), ENT_COMPAT, 'UTF-8'); 
			return $val;
		}
	}

}

$input = new input();