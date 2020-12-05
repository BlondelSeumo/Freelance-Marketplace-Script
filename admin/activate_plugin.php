<?php 
@session_start();
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login','_self');</script>";
}else{

	if(isset($_GET['activate_plugin'])){

		$id = $input->get('activate_plugin');
		$update_plugin = $db->update("plugins",['status'=>1],['id' => $id]);
		
		if($update_plugin){

			$plugins = $db->select("plugins",['id'=>$id]);
			$plugin = $plugins->fetch();
			$folder = $plugin->folder;

			if($folder == "paymentGateway"){
	        
	        	$get_sellers = $db->query("select * from sellers WHERE NOT EXISTS (SELECT * FROM seller_settings WHERE sellers.seller_id = seller_settings.seller_id)");
	        	while($row_sellers = $get_sellers->fetch()){
	         		$seller_id = $row_sellers->seller_id;
					$db->insert("seller_settings",array("seller_id"=>$seller_id));  
	       		}
			
			}elseif($folder == "videoPlugin"){

				$get_proposals = $db->query("select * from proposals WHERE NOT EXISTS (SELECT * FROM proposal_videosettings WHERE proposals.proposal_id = proposal_videosettings.proposal_id)");
				while($row_proposals = $get_proposals->fetch()){
					$proposal_id = $row_proposals->proposal_id;
					$db->insert("proposal_videosettings",array("proposal_id"=>$proposal_id,"enable"=>0));  
				}
			
			}

			echo "<script>alert('One Plugin has been Activated Successfully.');</script>";
			
			echo "<script>window.open('index?plugins','_self');</script>";

		}
	}

}