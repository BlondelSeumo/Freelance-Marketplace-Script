<?php 

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{


if(isset($_GET['delete_order_report'])){
	
$report_id = $input->get('delete_order_report');
	
$delete_report = $db->delete("reports",array('id' => $report_id)); 
	
if($delete_report){
	
$insert_log = $db->insert_log($admin_id,"order_report",$report_id,"deleted");


echo "<script>alert('One Order Report Has Been Deleted Successfully.');</script>";
	
echo "<script>window.open('index?order_reports','_self');</script>";
	
}
	

}

?>

<?php } ?>