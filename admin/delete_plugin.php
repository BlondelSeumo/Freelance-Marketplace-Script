<?php 
@session_start();
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login','_self');</script>";
}else{

	function delete_files($target) {
	    if(is_dir($target)){
	        $files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned
	        foreach( $files as $file ){
	            delete_files( $file );      
	        }
	        @rmdir( $target );
	    } elseif(is_file($target)) {
	        unlink( $target );  
	    }
	}

	if(isset($_GET['delete_plugin'])){
		$id = $input->get('delete_plugin');
		$folder = $input->get('folder');
		$delete_plugin = $db->delete("plugins",array('id' => $id));
		if($delete_plugin){
			if(file_exists("../plugins/$folder/uninstall.php")) {
				include("../plugins/$folder/uninstall.php");
			}
			delete_files("../plugins/$folder");
			echo "<script>alert('One Plugin has been Deleted.');</script>";
			echo "<script>window.open('index?plugins','_self');</script>";
		}
	}

}