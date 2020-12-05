<?php

	require_once("includes/db.php");

	if(isset($_POST['proposal_id'])){

		$seller_id = $input->post('seller_id');
		$proposal_id = $input->post('proposal_id');
		$proposal_qty = $input->post('proposal_qty');

		$update_cart = $db->update("cart",array("proposal_qty"=>$proposal_qty),array("seller_id"=>$seller_id,"proposal_id"=>$proposal_id));

	}