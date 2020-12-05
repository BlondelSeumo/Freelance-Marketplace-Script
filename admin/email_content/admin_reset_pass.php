<form action="" method="post"><!--- form Starts --->
   
   <input type="hidden" name="file_name" value="admin_reset_pass.php">

   <div class="form-group">
      <textarea name="content" class="form-control" rows="15"><?= get_file("admin_reset_pass.php"); ?></textarea>
   </div>

   <div class="form-group mb-0">
      <a href="#" class="btn btn-success float-left preview-email">
         <i class="fa fa-eye"></i> Preview
      </a>
      <button type="submit" name="update" class="btn btn-success float-right"> 
         <i class="fa fa-floppy-o"></i> Save Changes
      </button>
   </div>

</form><!--- form Ends --->