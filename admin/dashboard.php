<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1><i class="menu-icon fa fa-dashboard"></i> Dashboard</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
                    <li class="active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<?php if($a_admins == 0){ ?>

<div class="container">
    <div class="row">
        <?php include("includes/profile_card.php"); ?>
    </div>
</div>

<?php }else{ ?>

<div class="container">
    
    <div class="row">
        
        <div class="col-xl-3 col-lg-6 col-md-6">
            
         <div class="card text-white border-primary mb-xl-0 mb-lg-3 mb-sm-3 mb-2">
             
             <div class="card-header bg-primary">
                 
                 <div class="row">
                     
                     <div class="col-3">
                         
                     <i class="fa fa-table fa-5x"></i>
                     
                     </div>
                     
                     <div class="col-9 text-right">
                         
                        <div class="huge">
                            <?= $count_proposals; ?> <span class="badge badge-danger">Proposals</span>
                        </div> Pending Approval
                     
                     </div>
                 
                 </div>
             
             </div>
             
             <a href="index?view_proposals">
                 
                 <div class="card-footer">
                     
                     <span class="float-left text-primary">View Details</span>
                     
                     <span class="float-right text-primary">
                        <i class="fa fa-arrow-circle-o-right"></i>
                     </span>
                     
                     <div class="clearfix"></div>
                 
                 </div>
             
             </a>
            
          </div>    
            
        </div>
        
        
      <div class="col-xl-3 col-lg-6 col-md-6">
            
         <div class="card text-white border-success mb-xl-0 mb-lg-3 mb-sm-3 mb-2">
             
             <div class="card-header bg-success">
                 
                 <div class="row">
                     
                     <div class="col-3">
                         
                         <i class="fa fa-users fa-5x"></i>
                     
                     </div>
                     
                     <div class="col-9 text-right">
                         
                         <div class="huge"><?= $count_sellers; ?></div>
                         
                         <div class="text-caption">Users</div>
                     
                     </div>
                 
                 </div>
             
             </div>
             
             <a href="index?view_sellers">
                 
                 <div class="card-footer">
                     
                     <span class="float-left text-success">
                         
                         View Details
                     
                     </span>
                     
                     <span class="float-right text-success">
                         
                         <i class="fa fa-arrow-circle-o-right"></i>
                     
                     </span>
                     
                     <div class="clearfix"></div>
                 
                 </div>
            
             </a>
            
        </div>    
            
    </div>
        
        
      <div class="col-xl-3 col-lg-6 col-md-6">
            
         <div class="card text-white border-warning mb-xl-0 mb-lg-3 mb-sm-3 mb-2">
             
             <div class="card-header bg-warning">
                 
                 <div class="row">
                     
                     <div class="col-3">
                         
                         <i class="fa fa-shopping-cart fa-5x"></i>
                     
                     
                     </div>
                     
                     <div class="col-9 text-right">
                         
                         
                         <div class="huge"><?= $count_orders; ?> </div>
                         
                         <div class="text-caption">Active <br>Orders</div>
                     
                     
                     </div>
                 
                 
                 
                 </div>
             
             
             </div>
             
             <a href="index?view_orders">
                 
                 <div class="card-footer">
                     
                     <span class="float-left text-warning">View Details</span>
                     
                     <span class="float-right text-warning">
                        <i class="fa fa-arrow-circle-o-right"></i>
                     </span>
                     
                     <div class="clearfix"></div>
                 
                 </div>
             
             </a>
            
        </div>    
            
    </div>
        
      <div class="col-xl-3 col-lg-6 col-md-6">
            
         <div class="card text-white border-danger mb-xl-0 mb-lg-3 mb-sm-3 mb-2">
             
             <div class="card-header bg-danger">
                 
                 <div class="row">
                     
                     <div class="col-3">
                        <i class="fa fa-phone-square fa-5x"></i>
                     </div>
                     
                     <div class="col-9 text-right">
                         
                        <div class="huge"><?= $count_support_tickets; ?> </div>
                         
                        <div class="text-caption">Support Requests</div>
                     
                     </div>
                 
                 </div>
             
             </div>
             
             <a href="index?view_support_requests">
                 
                 <div class="card-footer">
                     
                     <span class="float-left text-danger">View Details</span>
                     
                     <span class="float-right text-danger">
                        <i class="fa fa-arrow-circle-o-right"></i>
                     </span>
                     
                     <div class="clearfix"></div>
                     
                 </div>
             
             </a>
            
        </div>    
            
    </div>

</div>
    
    
<div class="row mt-5">
    
    <div class="col-md-8">
        
       <div class="card">
        
        <div class="card-header">
            <strong class="card-title"> Website Statistics</strong>
        </div>
        <div class="card-body">
            <table class="table table-bordered links-table">
              <thead>
                <tr>
                  <th scope="col">Summary:</th>
                  <th scope="col">Results:</th>
                </tr>
              </thead>
              <tbody>
                <tr onclick="location.href='index?view_support_requests'">
                  <td>Open Support Requests</td>
                  <td><span class="badge badge-success"><?= $count_support_tickets; ?></span></td>
                </tr>
                  
                  <tr onclick="location.href='index?view_proposals'">
                  <td>Proposals/Services Awaiting Approval</td>
                  <td><span class="badge badge-success"><?= $count_proposals; ?></span></td>
                </tr>
                  
                  <tr onclick="location.href='index?buyer_requests'">
                  <td>Buyer Requests Awaiting Approval</td>
                  <td><span class="badge badge-success"><?= $count_requests; ?></span></td>
                </tr>
                  
                  <tr onclick="location.href='index?view_referrals'">
                  <td>Referrals Awaiting Approval</td>
                  <td><span class="badge badge-success"><?= $count_referrals; ?></span></td>
                </tr>
                
              </tbody>
            </table>
        </div>
    </div>
    
    
        </div>
    
    
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
                                    <div class="location text-sm-center"><i class="fa fa-map-marker"></i> <?= strtoupper($admin_country); ?></div>
                                </div>
                                <hr>
                                <div class="card-text text-sm-center">
                                    <span class="badge badge-success"><?= strtoupper($admin_job); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

    
    
</div>
    
   
    
</div>

<?php } ?>