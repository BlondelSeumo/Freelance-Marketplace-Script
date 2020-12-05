	<div id="report-modal" class="modal fade"><!-- report-modal modal fade Starts -->
	<div class="modal-dialog"><!-- modal-dialog Starts -->
	<div class="modal-content"><!-- modal-content Starts -->
	<div class="modal-header p-2 pl-3 pr-3"><!-- modal-header Starts -->
	Report This Message
	<button class="close" data-dismiss="modal">
	<span> &times; </span>
	</button>
	</div><!-- modal-header Ends -->
	<div class="modal-body"><!-- modal-body p-0 Starts -->
	
	<h6>Why do you wish to report this message?.</h6>
	
	<form action="" method="post">
	
	<input type="hidden" name="message_group_id" value="<?= $message_group_id; ?>">

	<div class="form-group mt-3"><!--- form-group Starts --->
	
	<select class="form-control float-right" name="reason" required="">
	<option value="">Select Reason</option>
	<option>The user asked for payment or wanted to communicate outside of <?= $site_name; ?>.</option>
	<option>The user behaved inappropriately</option>
	<option>The user sent spam</option>
	<option>Other</option>
	</select>
	
	</div><!--- form-group Ends --->
	
	<br>
	<br>

	<div class="form-group mt-1 mb-3"><!--- form-group Starts --->
	<label> Additional Information </label>
	<textarea name="additional_information" rows="3" class="form-control" required=""></textarea>
	</div><!--- form-group Ends --->
	<button type="submit" name="submit_report" class="float-right btn btn-sm btn-success">
	Submit Report
	</button>
	</form>
	
	</div><!-- modal-body p-0 Ends -->
	</div><!-- modal-content Ends -->
	</div><!-- modal-dialog Ends -->
	</div><!-- report-modal modal fade Ends -->