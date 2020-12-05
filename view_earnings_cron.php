<?php
require_once("includes/db.php");

$update_proposals = $db->update("proposals",array("proposal_views" => 0));
$update_seller_accounts = $db->update("seller_accounts",array("month_earnings" => 0));
