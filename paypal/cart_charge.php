<?php

   $reference_no = mt_rand();
   $sub_total = 0;
   $select_cart =  $db->select("cart",array("seller_id" => $login_seller_id));
   $count_cart = $select_cart->rowCount();
   while($row_cart = $select_cart->fetch()){
      $proposal_id = $row_cart->proposal_id;
      $proposal_price = $row_cart->proposal_price;
      $proposal_qty = $row_cart->proposal_qty;
      $delivery_id = $row_cart->delivery_id;
      $revisions = $row_cart->revisions;
      if($videoPlugin == 1){
         $video = $row_cart->video;
      }

      $get_extras = $db->select("cart_extras",array("seller_id"=>$login_seller_id,"proposal_id"=>$proposal_id));
      while($row_extra = $get_extras->fetch()){
         $price = $row_extra->price;
         $proposal_price += $price;
      }

      $cart_total = $proposal_price * $proposal_qty;
      $sub_total += $cart_total;

      $o_data = [
         "reference_no"=>$reference_no,
         "buyer_id"=>$login_seller_id,
         "content_id"=>$proposal_id,
         "price"=>$proposal_price,
         "qty"=>$proposal_qty,
         "delivery_id"=>$delivery_id,
         "revisions"=>$revisions,
         "total"=>$cart_total,
         "type"=>'cart_item',
      ];

      if($videoPlugin == 1){
         if($video == 1){
            $o_data['video'] = 1;
         }
      }

      $db->insert("temp_orders",$o_data);
      $insert_id = $db->lastInsertId();

      $get_extras = $db->select("cart_extras",array("seller_id"=>$login_seller_id,"proposal_id"=>$proposal_id));
      while($row_extra = $get_extras->fetch()){
         $name = $row_extra->name;
         $price = $row_extra->price;
         $insert_extra = $db->insert("temp_extras",array("reference_no"=>$reference_no,"buyer_id"=>$login_seller_id,"item_id"=>$insert_id,"proposal_id"=>$proposal_id,"name"=>$name,"price"=>$price));
      }
      
   }

   $processing_fee = processing_fee($sub_total);

   $data = [];
   $data['type'] = "cart";
   $reference_no2 = mt_rand();
   $data['reference_no'] = $reference_no2;
   $data['content_id'] = $reference_no;
   $data['name'] = "All Cart Proposals Payment";
   $data['desc'] = "Cart Payment";
   $data['qty'] = 1;
   $data['price'] = $sub_total;
   $data['sub_total'] = $sub_total;
   $data['total'] = $sub_total+$processing_fee;

   $data['cancel_url'] = "$site_url/cancel_payment?reference_no=$reference_no2";
   $data['redirect_url'] = "$site_url/paypal_order?reference_no=$reference_no2";