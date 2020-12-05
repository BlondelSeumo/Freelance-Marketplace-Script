<?php

@session_start();

if(!isset($_SESSION['admin_email'])){     
      echo "<script>window.open('../../login','_self');</script>";     
}else{

if(isset($_GET['delete_idea'])){
      
    $delete_id = $input->get('delete_idea');
    $delete_post = $db->delete("ideas",array('id' => $delete_id));
    
    if($delete_post){

        $insert_log = $db->insert_log($admin_id,"idea",$delete_id,"deleted");

        echo "<script>

            swal({
            type: 'success',
            text: 'One Idea Has Been Deleted Successfully!',
            timer: 3000,
            onOpen: function(){
            swal.showLoading()
            }
            }).then(function(){
                window.open('index?ideas','_self');
            });

        </script>";
    }

}

?>

<?php } ?>