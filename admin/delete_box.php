<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
      
echo "<script>window.open('login','_self');</script>";
      
}else{


if(isset($_GET['delete_box'])){
      
$delete_id = $input->get('delete_box');

$delete_box = $db->delete("section_boxes",array('box_id' => $delete_id));
            
if($delete_box){

$insert_log = $db->insert_log($admin_id,"box",$delete_id,"deleted");

echo "<script>

      swal({
      type: 'success',
      text: 'Box Deleted Successfully!',
      timer: 3000,
      onOpen: function(){
      swal.showLoading()
      }
      }).then(function(){

      window.open('index?layout_settings','_self');

      });

</script>";

}


   
}

?>

<?php } ?>