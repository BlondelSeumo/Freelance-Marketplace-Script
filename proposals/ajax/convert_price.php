<?php

session_start();
require_once("../../includes/db.php");

$amount = $input->post("amount");

echo showPrice($amount,'','no');