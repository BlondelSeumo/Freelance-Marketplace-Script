<?php
session_start();
require_once("includes/db.php");
require_once("functions/functions.php");

switch($_REQUEST['zAction']){
	default:
	get_freelancers();
	break;
	case "get_freelancer_pagination":
	get_freelancer_pagination();
	break;
}
