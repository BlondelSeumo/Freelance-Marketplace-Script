<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
  echo "<script>window.open('login','_self');</script>";
}else{
  $proposal_id = $input->get('removetoprated_proposal');
  $updateProposal = $db->update("proposals",array("proposal_toprated"=>0),array("proposal_id"=>$proposal_id));
  if($updateProposal){
    $deleteTopRated = $db->delete("top_proposals",array("proposal_id"=>$proposal_id));
    if($deleteTopRated){
      echo "<script>alert('Congrats, Proposal Has Been Removed From The Top Rated Listing Successfully.');</script>";
      echo "<script>window.open('index?view_proposals','_self');</script>";
    }
  }
}