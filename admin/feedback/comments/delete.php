<?php

@session_start();

if(!isset($_SESSION['admin_email'])){     
      echo "<script>window.open('../../login','_self');</script>";     
}else{

if(isset($_GET['delete_comment'])){
      
    $delete_id = $input->get('delete_comment');
    $delete_comm = $db->delete("comments",array('id' => $delete_id));
    
    if($delete_comm){

        $insert_log = $db->insert_log($admin_id,"comment",$delete_id,"deleted");

        echo "<script>

            swal({
            type: 'success',
            text: 'One Comment Has Been Deleted Successfully!',
            timer: 3000,
            onOpen: function(){
            swal.showLoading()
            }
            }).then(function(){
                window.open('index?comments','_self');
            });

        </script>";
    }

}

?>

<?php } ?>