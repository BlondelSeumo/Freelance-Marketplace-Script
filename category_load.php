<?php

session_start();
require_once("includes/db.php");
require_once("functions/functions.php");

switch($_REQUEST['zAction']){
	
	default:
		get_category_proposals();
	break;
	
	case "get_category_pagination":
		get_category_pagination();
	break;

}