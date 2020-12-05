<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
echo "<script>window.open('login','_self');</script>";
}else{
	if(isset($_GET['delete_language'])){
		$language_id = $input->get('delete_language');
		$delete_meta = $db->delete("child_cats_meta",array('language_id' => $language_id));
		$delete_meta =  $db->delete("cats_meta",array('language_id' => $language_id));
		$delete_meta =  $db->delete("contact_support_meta",array('language_id' => $language_id));
		$delete_meta =  $db->delete("footer_links",array('language_id' => $language_id));
		$delete_meta =  $db->delete("knowledge_bank",array('language_id' => $language_id));
		$delete_meta =  $db->delete("article_cat",array('language_id' => $language_id));
		$delete_meta = $db->delete("section_boxes",array('language_id' => $language_id));
		$delete_meta = $db->delete("home_section",array('language_id' => $language_id));
		$delete_meta = $db->delete("seller_levels_meta",array('language_id' => $language_id));
		if($delete_meta){
			$delete_language = $db->delete("languages",array('id' => $language_id));
			if($delete_language){
				$insert_log = $db->insert_log($admin_id,"language",$language_id,"deleted");
				// $path = "../languages/$file";	
				// if(unlink($path)){
					echo "<script>alert('One language has been deleted successfully.');</script>";
					echo "<script>window.open('index?view_languages','_self');</script>";
				// }
			}
		}
	}
}