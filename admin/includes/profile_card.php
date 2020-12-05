<div class="col-md-4">
   <div class="card">
      <div class="card-header">
            <strong class="card-title mb-3">Profile Card</strong>
        </div>
        <div class="card-body">
            <div class="mx-auto d-block">
                
               <?php if(!empty($admin_image)){ ?>
                  <img class="rounded-circle mx-auto d-block" src="<?= getImageUrl("admins",$admin_image); ?>" alt="Card image cap" width="145px">
                
               <?php }else{ ?>
                
                  <img class="rounded-circle mx-auto d-block" src="admin_images/empty-image.png" alt="Empty image">
                
               <?php } ?>
                
               <h5 class="text-sm-center mt-2 mb-1"><?= strtoupper($admin_name); ?></h5>
               <div class="location text-sm-center">
                  <i class="fa fa-map-marker"></i> <?= strtoupper($admin_country); ?>
               </div>

            </div>
            <hr>
         <div class="card-text text-sm-center">
            <span class="badge badge-success"><?= strtoupper($admin_job); ?></span>
         </div>
      </div>
   </div>
</div>