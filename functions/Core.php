<?php

class Core{
	public $db;
	public $input;
	public $adminLanguage;
	public $admin_id;

	function __construct(){
		global $db;
		global $input;
		global $adminLanguage;
		global $admin_id;
		$this->db = $db;
		$this->input = $input;
		$this->adminLanguage = $adminLanguage;
		$this->admin_id = $admin_id;
	}

	function checkPlugin($plugin,$site=""){
		if($plugin=="videoPlugin" and $site=="site"){
			$videoPlugin = $this->db->select("plugins",array("folder"=>$plugin,"status"=>1))->rowCount();
			if($videoPlugin!=0){
				$get_general_settings = $this->db->select("general_settings");   
				$row_general_settings = $get_general_settings->fetch();
				$opentok_api_key = $row_general_settings->opentok_api_key;
				$opentok_api_secret = $row_general_settings->opentok_api_secret;
				if(!empty($opentok_api_key) and !empty($opentok_api_secret)){
					return 1;
				}else{
					return 0;
				}
			}
		}else{
			return $this->db->select("plugins",array("folder"=>$plugin,"status"=>1))->rowCount();
		}
	}

}