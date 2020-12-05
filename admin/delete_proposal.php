<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login','_self');</script>";
}else{
	if(isset($_GET['delete_proposal'])){
		$delete_id = $input->get('delete_proposal');
		$page = (isset($_GET['page']))?"=".$input->get('page'):"";
		$update_proposal = $db->update("proposals",array("proposal_status"=>'deleted'),array("proposal_id"=>$delete_id));
		@$deleteTopRated = $db->delete("top_proposals",array("proposal_id"=>$delete_id));
	    if($update_proposal){
			$insert_log = $db->insert_log($admin_id,"proposal",$delete_id,"deleted");
			echo "<script>
			swal({
			type: 'success',
			text: 'One Proposal Has Been Deleted Successfully!',
			timer: 3000,
			onOpen: function(){
			swal.showLoading()
			}
			}).then(function(){
				window.open('index?view_proposals_trash','_self');
			});
			</script>";
		}
	}
?>
<?php } ?>