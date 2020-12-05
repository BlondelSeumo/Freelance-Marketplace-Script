<?php

session_start();

include("includes/db.php");

include("functions/functions.php");

switch($_REQUEST['zAction']){
	
	default:
	
	get_tag_proposals();
	
	break;
	
	case "get_tag_pagination":
	
	get_tag_pagination();
	
	break;
	
}


?>