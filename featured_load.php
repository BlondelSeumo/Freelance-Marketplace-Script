<?php

session_start();

require_once("includes/db.php");

require_once("functions/functions.php");

switch($_REQUEST['zAction']){
	
	default:
		get_featured_proposals();
	break;
	
	case "get_featured_pagination":
		get_featured_pagination();
	break;

}

?>