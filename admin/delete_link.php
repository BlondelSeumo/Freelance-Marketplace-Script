<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{

?>

<?php

if(isset($_GET['delete_link'])){
	
$delete_id = $input->get('delete_link');

$delete_link = $db->delete("footer_links",array('link_id' => $delete_id));
	
if($delete_link){

$insert_log = $db->insert_log($admin_id,"footer_link",$delete_id,"deleted");

echo "<script>
      
      swal({
      type: 'success',
      text: 'Link deleted successfully!',
      timer: 3000,
      onOpen: function(){
      swal.showLoading()
      }
      }).then(function(){

        // Read more about handling dismissals
        window.open('index?layout_settings','_self')

      });

    </script>";
	
}
	
}

?>

<?php } ?>