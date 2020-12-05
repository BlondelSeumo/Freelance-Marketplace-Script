
<?php if($seller_id == $login_seller_id){ ?>

<?php if($order_status == "progress" or $order_status == "revision requested" or $order_status == "delivered"){ ?>

<div id="deliver-order-modal" class="modal fade">
  <!--- deliver-order-modal Starts --->
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"> Deliver Your Order Now </h5>
        <button class="close" data-dismiss="modal"> <span>&times;</span> </button>
      </div>
      <div class="modal-body">
        <form method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label class="font-weight-bold" > Message </label>
            <textarea name="delivered_message" placeholder="Type Your Message Here..." class="form-control mb-2" required="" rows="3"></textarea>
          </div>
          <?php if($enable_watermark == 1){ ?>
          <div class="form-group">
            <label for="">Enable Watermark : </label>
            <input type="checkbox" name="enable_watermark" value="1" style="position: relative; top: 2px;">
          </div>
          <?php } ?>
          <div class="form-group mb-0">
            <input type="file" name="delivered_file" class="mt-1 mb-2"> 
            <input type="submit" name="submit_delivered" value="Deliver Order" class="btn btn-success float-right">
          </div>

        </form>
        <?php
          if(isset($_POST['submit_delivered'])){

            if($order_status == "progress" or $order_status == "revision requested" or $order_status == "delivered"){

            $d_message = $input->post('delivered_message');
            $d_file = $_FILES['delivered_file']['name'];
            $d_file_tmp = $_FILES['delivered_file']['tmp_name'];
            $allowed = array('jpeg','jpg','gif','png','tif','avi','mpeg','mpg','mov','rm','3gp','flv','mp4','zip','rar','mp3','wav','docx','csv','xls','xlsx','pptx','pdf','txt','psd','xd','txt');
            $file_extension = pathinfo($d_file, PATHINFO_EXTENSION);
            if(!in_array($file_extension,$allowed) & !empty($d_file)){
              echo "<script>alert('{$lang['alert']['extension_not_supported']}')</script>";
              echo "<script>window.open('order_details?order_id=$order_id','_self')</script>";
            }else{
              
              if(!empty($d_file)){
                
                $d_file = pathinfo($d_file, PATHINFO_FILENAME);

                if(isset($_POST['enable_watermark'])){
                  $watermark = 1;
                  $watermark_file = $d_file."_".time()."_watermark.$file_extension";
                  $d_file = $d_file."_".time().".$file_extension";
                  watermarkImage("order_files/$watermark_file",$d_file_tmp);
                  uploadToS3("order_files/$d_file",$d_file_tmp);
                }else{
                  $watermark = 0;
                  $watermark_file = "";
                  $d_file = $d_file."_".time().".$file_extension";
                  uploadToS3("order_files/$d_file",$d_file_tmp);
                }
              
              }else{
                $watermark = 0;
                $watermark_file = "";
              }

              $last_update_date = date("h:m: F d Y");
              
              $update_messages = $db->update("order_conversations",array("status" => "message"),array("order_id" => $order_id,"status" => "delivered"));

              $insert_delivered_message = $db->insert("order_conversations",array("order_id" => $order_id,"sender_id" => $seller_id,"message" => $d_message,"watermark" => $watermark,"watermark_file" => $watermark_file,"file" => $d_file,"isS3"=>$enable_s3,"date" => $last_update_date,"status" => "delivered"));

              if($insert_delivered_message){
                $insert_notification = $db->insert("notifications",array("receiver_id" => $buyer_id,"sender_id" => $seller_id,"order_id" => $order_id,"reason" => "order_delivered","date" => $n_date,"status" => "unread"));
                $order_auto_complete = $row_general_settings->order_auto_complete;
                $date_time = date("M d, Y H:i:s");
                $complete_time = date("M d, Y H:i:s",strtotime($date_time." + $order_auto_complete days"));
                $update_order = $db->update("orders",array("order_status" => "delivered","complete_time" => $complete_time),array("order_id" => $order_id));

                $data = [];
                $data['template'] = "order_delivered";
                $data['to'] = $buyer_email;
                $data['subject'] = "$site_name: Congrats! $login_seller_user_name has delivered your order.";
                $data['user_name'] = $buyer_user_name;
                $data['seller_user_name'] = $login_seller_user_name;
                $data['message'] = $d_message;
                $data['order_id'] = $order_id;
                send_mail($data);

                if($notifierPlugin == 1){ 
                  $smsText = str_replace('{seller_user_name}',$login_seller_user_name,$lang['notifier_plugin']['order_delivered']);
                  sendSmsTwilio("",$smsText,$buyer_phone);
                }

                echo "<script>window.open('order_details?order_id=$order_id','_self')</script>";

              }
            }

            }else{
              echo "<script>window.open('order_details?order_id=$order_id','_self')</script>";
            }
            
          }
          ?>
      </div>
    </div>
  </div>
</div>

<?php } ?>

<?php }elseif($buyer_id == $login_seller_id){ ?>
<div id="revision-request-modal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"> Submit Your Revision Request Here </h5>
        <button class="close" data-dismiss="modal"> <span>&times;</span> </button>
      </div>
      <div class="modal-body">

        <?php if($order_revisions != "unlimited" AND $order_revisions == $order_revisions_used){ ?>
          
          <?php if($order_revisions == 0){ ?>
            <p class="lead">This Order Has No Revisions.</p>
          <?php }else{ ?>
            <p class="lead">You have exhausted all your revision requests for this order. <b><?= $seller_user_name; ?></b> only provisioned <?= $order_revisions; ?> revisions for this order</p>
          <?php } ?>

        <?php }else{ ?>

        <form method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label class="font-weight-bold" > Request Message </label>
            <textarea name="revison_message" placeholder="Type Your Message Here..." class="form-control mb-2" required=""></textarea>
          </div>
          <div class="form-group clearfix">
            <input type="file" name="revison_file" class="mt-1">
            <input type="submit" name="submit_revison" value="Submit Request" class="btn btn-success float-right">
          </div>
        </form>

        <?php } ?>

        <?php
          if(isset($_POST['submit_revison'])){
            $revison_message = $input->post('revison_message');
            $revison_file = $_FILES['revison_file']['name'];
            $revison_file_tmp = $_FILES['revison_file']['tmp_name'];
            $allowed = array('jpeg','jpg','gif','png','tif','avi','mpeg','mpg','mov','rm','3gp','flv','mp4', 'zip','rar','mp3','wav','docx','csv','xls','xlsx','pptx','pdf','txt');
            $file_extension = pathinfo($revison_file, PATHINFO_EXTENSION);
            if(!in_array($file_extension,$allowed) & !empty($revison_file)){
              echo "<script>alert('{$lang['alert']['extension_not_supported']}')</script>";
              echo "<script>window.open('order_details?order_id=$order_id','_self')</script>";
            }else{

              if(!empty($revison_file)){

                $revison_file = pathinfo($revison_file, PATHINFO_FILENAME);
                $revison_file = $revison_file."_".time().".$file_extension";
                uploadToS3("order_files/$revison_file",$revison_file_tmp);
                
              }
              
              // move_uploaded_file($revison_file_tmp,"order_files/$revison_file");

              $last_update_date = date("h:i: F d, Y");
              $update_messages = $db->update("order_conversations",array("status"=>"message"),array("order_id" => $order_id,"status" => "delivered"));
              
              $insert_revision_message = $db->insert("order_conversations",array("order_id"=>$order_id,"sender_id"=>$buyer_id,"message"=>$revison_message,"file"=>$revison_file,"isS3"=>$enable_s3,"date"=>$last_update_date,"status" =>"revision"));

              if($insert_revision_message){
                
                $insert_notification = $db->insert("notifications",array("receiver_id" => $seller_id,"sender_id" => $buyer_id,"order_id" => $order_id,"reason" => "order_revision","date" => $n_date,"status" => "unread"));

                $update_order = $db->update("orders",array("order_status"=>"revision requested"),array("order_id"=>$order_id));
                
                if($order_revisions != "unlimited"){
                  $update_order = $db->query("update orders set order_revisions_used=order_revisions_used+1 Where order_id='$order_id'");
                }

                $data = [];
                $data['template'] = "revision_requested";
                $data['to'] = $seller_email;
                $data['subject'] = "$site_name - Revision Requested By $buyer_user_name";
                $data['user_name'] = $seller_user_name;
                $data['seller_user_name'] = $seller_user_name;
                $data['buyer_user_name'] = $buyer_user_name;
                $data['order_id'] = $order_id;
                send_mail($data);

                if($notifierPlugin == 1){ 
                  $smsText = str_replace('{buyer_user_name}',$buyer_user_name,$lang['notifier_plugin']['order_revision']);
                  sendSmsTwilio("",$smsText,$seller_phone);
                }

                echo "<script>window.open('order_details?order_id=$order_id','_self')</script>";

              }
            }
          }
          ?>
      </div>
    </div>
  </div>
</div>
<?php } ?>
