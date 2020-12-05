<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
  echo "<script>window.open('login','_self');</script>";
}else{

  if(isset($_GET['delete_notification'])){

    $id = $input->get('delete_notification');
    $admin_notifications = $db->delete("admin_notifications",array('id' => $id));
    if($admin_notifications){
      echo "<script>
      swal({
        type: 'success',
        text: 'Alert deleted successfully!',
        timer: 3000,
        onOpen: function(){
         swal.showLoading();
        }
      }).then(function(){
        // Read more about handling dismissals
        window.open('index?view_notifications','_self');
      });
      </script>";
    }

  }

} 