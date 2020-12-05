<?php

@session_start();

if(!isset($_SESSION['admin_email'])){     
      echo "<script>window.open('../../login','_self');</script>";     
}else{

if(isset($_GET['delete_post'])){
      
    $delete_id = $input->get('delete_post');
    $delete_post = $db->delete("posts",array('id' => $delete_id));
    
    if($delete_post){

        $delete_meta = $db->delete("posts_meta",array('post_id' => $delete_id));;

        $insert_log = $db->insert_log($admin_id,"post",$delete_id,"deleted");
        echo "<script>

            swal({
            type: 'success',
            text: 'Post Deleted Successfully!',
            timer: 3000,
            onOpen: function(){
            swal.showLoading()
            }
            }).then(function(){
                window.open('index?posts','_self');
            });

        </script>";
    }

}

?>

<?php } ?>