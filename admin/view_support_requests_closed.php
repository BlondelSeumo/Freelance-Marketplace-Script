<?php
@session_start();

if(!isset($_SESSION['admin_email'])){

echo "<script>window.open('login','_self');</script>";

}else{

$count_tickets_closed = $db->count("support_tickets",["status" => "close"]);

if(isset($_GET['view_support_requests_closed'])){
    
    $page = $input->get('view_support_requests_closed');
    if($page == 0){ 
        $page = 1; 
    }
    
}else{
    
    $page = 1;
    
}

if(isset($_GET['enquiry_id'])){
    $enquiry_search = $input->get('enquiry_id');
}else{
    $enquiry_search = "";
}

?>

<div class="breadcrumbs">
<div class="col-sm-4">
<div class="page-header float-left">
<div class="page-title">
    <h1><i class="menu-icon fa fa-phone-square"></i> Support Settings</h1>
</div>
</div>
</div>
<div class="col-sm-8">
<div class="page-header float-right">
<div class="page-title">
    <ol class="breadcrumb text-right">
        <li class="active">View All Request</li>
    </ol>
</div>
</div>
</div>

</div>

<div class="container">

<div class="row"><!--- 2 row Starts --->

<div class="col-lg-12"><!--- col-lg-12 Starts --->

<div class="card"><!--- card Starts --->

<div class="card-header"><!--- card-header Starts --->
    <div class="row">
        <div class="col-lg-9 col-md-8">
            <h4 class="h4">View Support Requests</h4>
        </div>

        <div class="col-lg-3 col-md-4">
            <div class="form-group row mb-0 pb-0"><!--- form-group row Starts --->

                <select name="ticket_status" class="form-control form-control-sm float-right">
                    <option value="closed">Closed</option>
                    <option value="open">Open</option>
                </select>

            </div><!--- form-group row Ends --->
        </div>
    </div>
</div>
<!--- card-header Ends --->

<div class="card-body"><!--- card-body Starts --->

    <div class="row"><!--- row Starts --->

        <div class="offset-lg-4 col-md-4"><!--- offset-lg-4 col-md-4 Starts --->

            <form action="" method="get">

                <input type="hidden" name="view_support_requests_closed" value="">

                <div class="input-group mb-3 mt-3 mt-lg-0">
                  <input type="text" name="search" class="form-control" placeholder="Search Ticket By Number" value="<?php if(isset($_GET['search'])){ echo $input->get('search'); } ?>">
                  <div class="input-group-append">
                    <button class="btn btn-success" type="submit"><i class="fa fa-search"></i></button>
                  </div>
                </div>

            </form>

        </div><!--- offset-lg-4 col-md-4 Ends --->

    </div><!--- row Ends ---->

    <div>
        <span class="text-muted mr-2">

        <?php 

        $count_support_tickets = $db->query("select * from support_tickets where status!='open'")->rowCount();

        ?>
        <a href="index?view_support_requests_closed">
            All (<?= $count_support_tickets; ?>)
        </a>
        </span>

        <?php

        $get_enquiry_types = $db->select("enquiry_types");

        while($row_enquiry_types = $get_enquiry_types->fetch()){

        $enquiry_id = $row_enquiry_types->enquiry_id;
        $enquiry_title = $row_enquiry_types->enquiry_title;

        // $count_enquiry_support_tickets = $db->count("support_tickets",["enquiry_id" =>$enquiry_id,"status" => "close"]);
        $count_enquiry_support_tickets = $db->query("select * from support_tickets where enquiry_id=:enquiry_id AND status!='open'",[":enquiry_id"=>$enquiry_id])->rowCount();

        ?>

        <span class="mr-2">|</span>

        <span class="mr-2">
            <a href="index?view_support_requests_closed&enquiry_id=<?= $enquiry_id; ?>" class="text-black">
                <?= $enquiry_title; ?> (<?= $count_enquiry_support_tickets; ?>)
            </a>
        </span>

        <?php } ?>

    </div>

    <div class="clearfix"></div>
    <div class="clearfix"></div>

    <div class="table-responsive mt-4"><!--- table-responsive mt-4 Starts --->

        <table class="table table-bordered links-table"><!--- table table-bordered table-hover links-table Starts --->

            <thead><!--- thead Starts --->

                <tr>

                    <th>Ticket Number:</th>

                    <th>Enquiry Type:</th>

                    <th>Sender Username:</th>

                    <th>Sender Email:</th>

                    <th>Subject:</th>

                    <th>Status:</th>

                </tr>

            </thead><!--- thead Ends --->

            <tbody><!--- tbody Starts --->

            <?php

                    $per_page = 8;

                    /// Page will start from 0 and multiply by per page

                    $start_from = ($page-1) * $per_page;

                    $enquiry = "%$enquiry_search%";

                    if(isset($_GET['search'])){ $search = $input->get('search'); }else{ $search = ""; }

                    $get_support_tickets = $db->query("select * from support_tickets where status!='open' AND enquiry_id LIKE :enquiry AND number like :search order by 1 DESC LIMIT :limit OFFSET :offset",[':enquiry'=>$enquiry,"search"=>"%$search%"],array("limit"=>$per_page,"offset"=>$start_from));

                    while($row_support_tickets = $get_support_tickets->fetch()){

                    $ticket_id = $row_support_tickets->ticket_id;
                    $number = $row_support_tickets->number;
                    $sender_id = $row_support_tickets->sender_id;
                    $subject = $row_support_tickets->subject;
                    $status = $row_support_tickets->status;
                    $enquiry_id = $row_support_tickets->enquiry_id;

                    $select_sender = $db->select("sellers",array("seller_id" => $sender_id));
                    $row_sender = $select_sender->fetch();
                    $sender_user_name = $row_sender->seller_user_name;
                    $sender_email = $row_sender->seller_email;

                    $get_enquiry_types = $db->select("enquiry_types",array("enquiry_id" => $enquiry_id));
                    $row_enquiry_types = $get_enquiry_types->fetch();
                    $enquiry_title = $row_enquiry_types->enquiry_title;

                    // $number = mt_rand();
                    // $db->update("support_tickets",["number" => $number],["ticket_id"=>$ticket_id]);

                ?>

                    <tr onclick="location.href='index?single_request=<?= $ticket_id; ?>'">

                        <td>
                            #<?= $number; ?>
                        </td>

                        <td>
                            <?= $enquiry_title; ?>
                        </td>

                        <td>
                            <?= $sender_user_name; ?>
                        </td>

                        <td>
                            <?= $sender_email; ?>
                        </td>

                        <td>
                            <?= $subject; ?>
                        </td>

                        <td>

                        <button class="btn btn-success"><?= ucwords($status); ?></button>

                        </td>

                    </tr>

                <?php } ?>

            </tbody>
            <!--- tbody Ends --->

        </table>
        <!--- table table-bordered table-hover links-table Ends --->

    </div>
    <!--- table-responsive mt-4 Ends --->


    <div class="d-flex justify-content-center"><!--- d-flex justify-content-center Starts --->

        <ul class="pagination"><!--- pagination Starts --->

        <?php 

        /// Now Select All From Data From Table

        // $query = $db->select("support_tickets",array("status"=>'open'));
        
        $query = $db->query("select * from support_tickets where status!='open' AND enquiry_id LIKE :enquiry AND number like :search",[':enquiry'=>$enquiry,"search"=>"%$search%"]);

        /// Count The Total Records 

        $total_records = $query->rowCount();

        /// Using ceil function to divide the total records on per page

        $total_pages = ceil($total_records / $per_page);

        echo "<li class='page-item'><a href='index?view_support_requests_closed=1&enquiry_id=$enquiry_search&search=$search' class='page-link'>First Page</a></li>";

        echo "<li class='page-item ".(1 == $page ? "active" : "")."'><a class='page-link' href='index?view_support_requests_closed=1&enquiry_id=$enquiry_search&search=$search'>1</a></li>";
        
        $i = max(2, $page - 5);
        
        if ($i > 2)
        
            echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";
        
        for (; $i < min($page + 6, $total_pages); $i++) {
                    
            echo "<li class='page-item"; if($i == $page){ echo " active "; } echo "'><a href='index?view_support_requests_closed=".$i."&enquiry_id=$enquiry_search&search=$search' class='page-link'>".$i."</a></li>";

        }
        
        if ($i != $total_pages and $total_pages > 1){echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";}

        if($total_pages > 1){echo "<li class='page-item ".($total_pages == $page ? "active" : "")."'><a class='page-link' href='index?view_support_requests_closed=$total_pages&enquiry_id=$enquiry_search&search=$search'>$total_pages</a></li>";}

        echo "<li class='page-item'><a href='index?view_support_requests_closed=$total_pages&enquiry_id=$enquiry_search&search=$search' class='page-link'>Last Page </a></li>";
        ?>

        </ul>
        <!--- pagination Ends --->

    </div>
    <!--- d-flex justify-content-center Ends --->


</div>
<!--- card-body Ends --->

</div>
<!--- card Ends --->

</div>
<!--- col-lg-12 Ends --->

</div>
<!--- 2 row Ends --->

</div>

<script>

$(document).ready(function(){

    $("select[name='ticket_status']").change(function(){
       
        var status = $(this).val();
        
        if(status == 'open'){
            window.open('index?view_support_requests','_self');
        }

    });   

});

</script>

<?php } ?>