<?php

if($reason == "message_spam"){

  $message = "has used some <span class='text-danger'>spam words</span> in conversation.";

  $url = "index.php?single_inbox_message=$content_id";

}elseif($reason == "order_spam"){
	
  $message = "has used some <span class='text-danger'>spam words</span> in order conversation.";
	
  $url = "index.php?single_order=$content_id";

}elseif($reason == "proposal_report"){

	$message = "has reported the sideline.";

	$url = "index?proposal_reports";

}elseif($reason == "message_report"){

	$message = "has reported about his conversation.";

	$url = "index?message_reports";

}elseif($reason == "order_report"){

	$message = "has reported about order conversation.";
	$url = "index?order_reports";

}elseif($reason == "order_report"){

   $message = "has reported about order conversation.";
   $url = "index?order_reports";

}elseif($reason == "payout_request"){

   $message = "has just sent you an payout request";
   $url = "index?payouts&status=pending";

}