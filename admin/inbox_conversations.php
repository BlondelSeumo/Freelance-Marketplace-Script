<?php


@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{
	
?>


<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1><i class="menu-icon fa fa-comments"></i> Inbox Messages</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li class="active">Inbox Messages</li>
                        </ol>
                    </div>
                </div>
            </div>
    
    </div>


<div class="container">
    
                 <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">All Messages</h4>
                        </div>
                        <div class="card-body">
                  <table id="bootstrap-data-table" class="table table-striped table-bordered links-table">
                    <thead>
                      <tr>
                        <th>Sender</th>
                        <th>Receiver</th>
                        <th>Message Content</th>
                        <th>Attachment</th>
                        <th>Updated</th>
                      </tr>
                    </thead>
                    <tbody>
                        
                      <?php 
                        
                        $per_page = 10;
    
                         if(isset($_GET["inbox_conversations"])){
                             
                            $page = $input->get("inbox_conversations");

                            if($page == 0){ $page = 1; }

                         }else {
                             
                            $page = 1;
                         }
    
                        // page starts from 0 and multiply per page
    
                        $start_from = ($page-1)* $per_page;
                    
                        $get_inbox_sellers = $db->query("select * from inbox_sellers where not message_status='empty' order by 1 DESC LIMIT :limit OFFSET :offset","",array("limit"=>$per_page,"offset"=>$start_from));
        
                        while($row_inbox_sellers = $get_inbox_sellers->fetch()){
                            
                        $sender_id = $row_inbox_sellers->sender_id;
                        $receiver_id = $row_inbox_sellers->receiver_id;
                        $message_id = $row_inbox_sellers->message_id;
                        $message_group_id = $row_inbox_sellers->message_group_id;
                        
    
                        $select_inbox_message = $db->select("inbox_messages",array("message_id" => $message_id));
                        $row_inbox_message = $select_inbox_message->fetch();
                        $message_file = $row_inbox_message->message_file;
                        $message_desc = substr(strip_tags($row_inbox_message->message_desc),0,170);
                        $message_date = $row_inbox_message->message_date;


                        $select_sender = $db->select("sellers",array("seller_id" => $sender_id));
                        
                        $row_sender = $select_sender->fetch();

                        $seller_user_name = $row_sender->seller_user_name;


                        $select_sender = $db->select("sellers",array("seller_id" => $receiver_id));

                        $row_sender = $select_sender->fetch();

                        $receiver_user_name = $row_sender->seller_user_name;
                            
                      ?>
                        
                        <tr onclick="location.href='index?single_inbox_message=<?= $message_group_id; ?>'">
                            
                        <td><?= $seller_user_name; ?></td>
                        <td><?= $receiver_user_name; ?></td>
                        <td width="300">
                            
                            <?php
                            
                               if(strlen($message_desc) > 120 ){
                                   
                                   echo $message_desc." ...";
                               }else {
                                   
                                   echo $message_desc;
                               }
                            
                            
                            
                            
                            ?>
                            
                            
                            
                        </td>
                            
                        <td>
                            <?php
                            
                            if(empty($message_file)){
                            
                            echo "No File Attachment";
                                
                            }else{
                            
                            ?>
                            
                            <a href="#" class="text-primary">
                            
                                <i class="fa fa-download"></i> <?= $message_file; ?>
                            
                            </a>
                            
                            <?php } ?>
                            
                            
                            
                        </td>
                            
                        
                        <td><?= $message_date; ?></td>
                        
                        
                        
                        </tr>
                      
                      
                      <?php } ?>
                    
                    </tbody>
                      
                      
                    </table>
                            
                    <div class="d-flex justify-content-center">
                        
                    <ul class="pagination">
                        
                    <?php

                    $query = $db->query("select * from inbox_sellers where not message_status='empty' order by 1 DESC");

                    $total_records = $query->rowCount();
                    
                    $total_pages = ceil($total_records / $per_page);


                    echo "<li class='page-item'><a href='index?inbox_conversations=1' class='page-link'>First Page</a></li>";

                    echo "<li class='page-item ".(1 == $page ? "active" : "")."'><a class='page-link' href='index?inbox_conversations=1'>1</a></li>";
                    
                    $i = max(2, $page - 5);
                    
                    if ($i > 2)
                    
                        echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";
                    
                    for (; $i < min($page + 6, $total_pages); $i++) {
                                
                        echo "<li class='page-item"; if($i == $page){ echo " active "; } echo "'><a href='index?inbox_conversations=".$i."' class='page-link'>".$i."</a></li>";

                    }
                    
                    if ($i != $total_pages and $total_pages > 1){echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";}

                    if($total_pages > 1){echo "<li class='page-item ".($total_pages == $page ? "active" : "")."'><a class='page-link' href='index?inbox_conversations=$total_pages'>$total_pages</a></li>";}

                    echo "<li class='page-item'><a href='index?inbox_conversations=$total_pages' class='page-link'>Last Page </a></li>";

                    ?>
            
                    </ul>
            
                    </div>
                            
                </div>
                        
        </div>
                     
    </div>
    
</div>

<?php } ?>

<div class="clearfix"></div>
