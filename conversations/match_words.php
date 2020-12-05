<?php

include("../includes/db.php");

$line = $input->post('value');
$get_words = $db->select("spam_words");
while($row_words = $get_words->fetch()){
	$name = $row_words->word;
	if(preg_match("/\b($name)\b/i", $line)){
		echo "match";
		break;
	}
}