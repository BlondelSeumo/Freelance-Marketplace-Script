<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
  echo "<script>window.open('login','_self');</script>";
}else{
  
  $proposal_id = $input->get('remove_feature_proposal');
  $page = (isset($_GET['page']))?"=".$input->get('page'):"";

  $updateProposal = $db->update("proposals",array("proposal_featured"=>"no"),["proposal_id"=>$proposal_id]);
  if($updateProposal){
    @$delete = $db->delete("featured_proposals",["proposal_id"=>$proposal_id]);
    if($delete){
      echo "<script>alert('This Proposal Has Been Removed From The Featured Listing Successfully.');</script>";
      echo "<script>window.open('index?view_proposals$page','_self');</script>";
    }
  }

}