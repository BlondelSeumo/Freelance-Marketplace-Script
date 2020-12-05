<?php

   $processing_fee = processing_fee($_SESSION['c_sub_total']);

   $select_proposals = $db->select("proposals",array("proposal_id" => $_SESSION['c_proposal_id']));
   $row_proposals = $select_proposals->fetch();
   $proposal_url = $row_proposals->proposal_url;
   $proposal_seller_id = $row_proposals->proposal_seller_id;

   $select_proposal_seller = $db->select("sellers",array("seller_id"=>$proposal_seller_id));
   $row_proposal_seller = $select_proposal_seller->fetch();
   $proposal_seller_user_name = $row_proposal_seller->seller_user_name;

   $reference_no = mt_rand();

   $data = [];
   $data['type'] = "proposal";
   $data['reference_no'] = $reference_no;
   $data['content_id'] = $_SESSION['c_proposal_id'];
   $data['name'] = $row_proposals->proposal_title;
   $data['desc'] = "Proposal Payment";
   $data['qty'] = $_SESSION['c_proposal_qty'];
   $data['price'] = $_SESSION['c_proposal_price'];
   $data['delivery_id'] = $_SESSION['c_proposal_delivery'];
   $data['revisions'] = $_SESSION['c_proposal_revisions'];
   $data['sub_total'] = $_SESSION['c_sub_total'];
   $data['total'] = $_SESSION['c_sub_total'] + $processing_fee;

   if(isset($_SESSION['c_proposal_extras'])){
      $data['extras'] = base64_encode(serialize($_SESSION['c_proposal_extras']));
   }

   if(isset($_SESSION['c_proposal_minutes'])){
      $data['minutes'] = $_SESSION['c_proposal_minutes'];
   }
