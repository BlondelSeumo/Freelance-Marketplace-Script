<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
      
echo "<script>window.open('login','_self');</script>";
      
}else{

?>

<?php

if(isset($_GET['delete_card'])){
      
$delete_id = $input->get('delete_card');

$delete_card = $db->delete("home_cards",array('card_id' => $delete_id));
            
if($delete_card){

$insert_log = $db->insert_log($admin_id,"card",$delete_id,"deleted");

echo "<script>

      swal({
      type: 'success',
      text: 'card Deleted Successfully!',
      timer: 3000,
      onOpen: function(){
      swal.showLoading()
      }
      }).then(function(){

      window.open('index?layout_settings','_self');

      })

      </script>";

}
      
}

?>

<?php } ?>