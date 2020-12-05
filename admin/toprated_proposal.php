<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
  echo "<script>window.open('login','_self');</script>";
}else{
  $proposal_id = $input->get('toprated_proposal');
  $updateProposal = $db->update("proposals",array("proposal_toprated"=>1),array("proposal_id"=>$proposal_id));
  if($updateProposal){
    $insertTopRated = $db->insert("top_proposals",array("proposal_id"=>$proposal_id));
    if($insertTopRated){
      echo "<script>alert('Congrats, Proposal Has Been Top Rated On The Website Successfully.');</script>";
      echo "<script>window.open('index?view_proposals','_self');</script>";
    }
  }
}