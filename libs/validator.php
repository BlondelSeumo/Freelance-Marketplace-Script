<?php 

Class Validator{
	private $data;
	private $errors = array();
	private $messages = array();

	public function __construct($data = array(), $rules = array(), $messages = array()){
		$this->data = $data;
		$this->messages = $messages;
		foreach ($rules as $field_name => $field_rules) {
			$rules = explode("|", $field_rules);
			foreach ($rules as $rule) {
				// if(preg_match("/\b(message:)\b/i", $rule)){
				// $message = explode("message:", $rule);
				// print_r($rule);
				// $this->$rule($field_name,$message[0]);
				// }else{
				$this->$rule($field_name);
				// }
			}
		}
	}

	public function required($field_name){
		if(preg_match("/(file|image)/", $field_name)){
			$check = @$_FILES[$field_name]['name'];
		}else { 
			$check = @$this->data[$field_name]; 
		}
		if(empty($check)){
			if(empty($this->messages[$field_name])){
				$f_name = str_replace("_"," ",$field_name);
				$this->errors[$field_name] = $f_name . " is required.";
			}else{ 
				$this->errors[$field_name] = $this->messages[$field_name]; 
			}
		}
	}

	function filterName($field_name,$text=''){
		// Sanitize user name
		$name = filter_var(trim($this->data[$field_name]), FILTER_SANITIZE_STRING);

		// Validate user name
		if(!filter_var($name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
		  $this->errors[$field_name] = $this->data[$field_name] . " is not a valid name.";
		}
	}

	public function email($field_name,$text=''){
		if(!filter_var($this->data[$field_name], FILTER_VALIDATE_EMAIL)){
			$this->errors[$field_name] = $this->data[$field_name] . " is not a valid email address.";
		}
	}

	public function number($field_name,$text=''){
		if(!is_numeric($this->data[$field_name])){
			$f_name = str_replace("_"," ",$field_name);
			$this->errors[$field_name] = "Only Numbers Are Allowed In " . ucfirst($f_name) . " Field.";
		}
	}

	public function run(){
		if(empty($this->errors)){
			return true;
		}else{
			return false;
		}
	}

	public function get_all_errors(){
		return $this->errors;
	}

}

?>