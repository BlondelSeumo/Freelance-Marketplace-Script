<script>
	var enable_websocket = "<?= $enable_websocket; ?>";
	var seller_user_name ="<?= $_SESSION['seller_user_name']; ?>";
	var seller_image ="<?= $login_seller_image; ?>";	

	if(enable_websocket == 1){
		var websocket = new WebSocket("<?= $websocket_address; ?>/?message_group_id=<?= $message_group_id; ?>"); 
		websocket.onopen = function(event) { 
			console.log('onopen event');
			//console.log(event)		
		}
		websocket.onmessage = function(event) {
			console.log('onmessage event');			
			var data = JSON.parse(event.data);
			if(data.type == 'chat'){				
				//console.log(data);
				ShowSingleMessage(data);
			}
		};
		
		websocket.onerror = function(event){
			console.log('onerror event');			
		};
		websocket.onclose = function(event){
			console.log('onclose event');
			var reason;
			//alert(event.code);   
	        // See http://tools.ietf.org/html/rfc6455#section-7.4.1
        if (event.code == 1000)
            reason = "Normal closure, meaning that the purpose for which the connection was established has been fulfilled.";
        else if(event.code == 1001)
            reason = "An endpoint is \"going away\", such as a server going down or a browser having navigated away from a page.";
        else if(event.code == 1002)
            reason = "An endpoint is terminating the connection due to a protocol error";
        else if(event.code == 1003)
            reason = "An endpoint is terminating the connection because it has received a type of data it cannot accept (e.g., an endpoint that understands only text data MAY send this if it receives a binary message).";
        else if(event.code == 1004)
            reason = "Reserved. The specific meaning might be defined in the future.";
        else if(event.code == 1005)
            reason = "No status code was actually present.";
        else if(event.code == 1006)
           reason = "The WebSocket connection was closed abnormally, e.g., without sending or receiving a Close control frame";
        else if(event.code == 1007)
            reason = "An endpoint is terminating the connection because it has received data within a message that was not consistent with the type of the message (e.g., non-UTF-8 [http://tools.ietf.org/html/rfc3629] data within a text message).";
        else if(event.code == 1008)
            reason = "An endpoint is terminating the connection because it has received a message that \"violates its policy\". This reason is given either if there is no other sutible reason, or if there is a need to hide specific details about the policy.";
        else if(event.code == 1009)
           reason = "An endpoint is terminating the connection because it has received a message that is too big for it to process.";
        else if(event.code == 1010) // Note that this status code is not used by the server, because it can fail the WebSocket handshake instead.
            reason = "An endpoint (client) is terminating the connection because it has expected the server to negotiate one or more extension, but the server didn't return them in the response message of the WebSocket handshake. <br /> Specifically, the extensions that are needed are: " + event.reason;
        else if(event.code == 1011)
            reason = "A WebSocket server is terminating the connection because it encountered an unexpected condition that prevented it from fulfilling the request.";
        else if(event.code == 1015)
            reason = "The WebSocket connection was closed due to a failure to perform a TLS handshake (e.g., the server certificate can't be verified).";
        else
            reason = "Unknown reason";
        console.log(reason);
			//console.log(event)		
		}; 
	}


var height = 0;
$(".col-md-8 .messages .inboxMsg").each(function(i, value){
	height += parseInt($(this).height());
});
height += 20000;
$(".col-md-8 .messages").animate({scrollTop: height});

var login_seller_id = "<?= $login_seller_id; ?>";
var seller_id = "<?= $seller_id; ?>";
var message_group_id = "<?= $message_group_id ?>";

var scroll = 0;

$(document).off('submit').on('submit','#insert-message-form', function(event){
	event.preventDefault();
	sendMessage();
	$(this).off('submit'); 
});

function sendMessage(){
	$("#send-msg").prop("disabled", true);
	$("#send-msg").html("<i class='fa fa-spinner fa-pulse fa-lg fa-fw'></i>");
	message = $('.emojionearea-editor').html();
	if(message==""){
   		swal({
	   		type: 'warning',
	   		text: 'Message can\'t be empty!',
	 	});
		$("#send-msg").prop("disabled", false);
		$("#send-msg").html("Send");
	}else{
		file = $('#fileVal').val();
		$.ajax({
			method: "POST",
			url: "insert_inbox_message",
			dataType: 'json',
			data: {single_message_id: message_group_id, message: message, file: file},
			success: function(data){
				var message_date = data.message_date;	
				var message_file = data.message_file;
				$('#message').val('');
				$('#fileVal').val('');
				$(".emojionearea-editor").html("");
				$('.files').html('');
				$("#send-msg").prop("disabled", false);
				$("#send-msg").html("Send");
				scroll = 1;				
				if(enable_websocket == 1){
					var messageJSON = {
						message_group_id: message_group_id,
						type: 'chat',
						seller_user_name: seller_user_name,
						seller_image: seller_image,
						message: message,
						file: file,
						message_date: message_date,
						message_file: message_file
					};
					websocket.send(JSON.stringify(messageJSON));
				}				
				height += 2000;
				$(".col-md-8 .messages").animate({scrollTop: height});
			}
		});
	}
}

$(document).on('change','#file', function(){
	var form_data = new FormData();
	var name = document.getElementById('file').files[0];
	form_data.append("file", name);
	$.ajax({
		url:"upload_file",
		method:"POST",
		data:form_data,
		contentType:false,
		cache:false,
		processData:false,
	}).done(function(data){
		if(data=="Your File Format Extension Is Not Supported."){
			alert(data);
		}else{
			var file = "<span class='border rounded p-1'>"+name.name+"</span>";
			$(".files").removeClass("d-none").append(file);
			$("#fileVal").val(data);
		}
	});
});

$("#send-offer").click(function(){
	receiver_id = "<?= $seller_id; ?>";
	message = $("#message").val();
	file = $("#file").val();
	if(file == ""){
		message_file = file;
	}else{
		message_file = document.getElementById("file").files[0].name;
	}
	$.ajax({
		method: 'POST',
		url: 'send_offer_modal',
		data: {receiver_id: receiver_id, message: message, file: message_file}
	}).done(function(data){
		$("#send-offer-div").html(data);
	});
});

// Javascript Jquery Code To Reload User Typing Status Every half second Code Starts ///

var seller_id = "<?= $seller_id; ?>";

setInterval(function(){
	$.ajax({
		method: "POST",
		url: "seller_typing_status",
		data: {seller_id : seller_id, message_group_id: message_group_id}
	}).done(function(data){
		if(data == "typing"){
			$(".typing-status").removeClass("invisible");
			$('.typing-status').html("<b class='text-success'><?= $seller_user_name; ?></b> is typing ...");
		}else{
			$(".typing-status").addClass("invisible");
			$('.typing-status').html("Dummy Text");
		}

	});
}, 500);

// Javascript Jquery Code To Reload User Typing Status Every half second Code Ends //

function display_messages(message_group_id){
	$.ajax({
		method: "POST",
		url: "includes/display_messages",
		data: {message_group_id: message_group_id}
	}).done(function(data){
		$('.specfic .messages').empty();
		$('.specfic .messages').append(data);
		
		if(scroll == 1){
			height += 2000;
			$(".col-md-8 .messages").animate({scrollTop: height});
			scroll = 0;
		}
	});
}

function ShowSingleMessage(data){
	var username;
	var user_image;
	var li = '<li href="#" class="inboxMsg media inboxMsg">';
	if(data.seller_user_name == seller_user_name){
		username = seller_user_name;
		user_image = seller_image;
		user_name_text = 'Me';
	}else{
		username = data.seller_user_name;
		user_image = data.seller_image;
		user_name_text = username;
	}
	li += `<a href="../${username}">`;
	li += `<img src="${user_image}" class="rounded-circle mr-3" width="40"></a>`;	
	li += '<div class="media-body"><h6 class="mt-0 mb-1">';	
	li += `<a href="../${username} "> ${user_name_text} </a>`;
	li += `<small class="text-muted"> ${data.message_date} </small>`;
	li += '<small>|<a href="#" data-toggle="modal" data-target="#report-modal" class="text-muted">';
	li += '<small><i class="fa fa-flag"></i> Report</small></a></small></h6>';
	li += `${data.message}`;
	if(data.message_file != ""){
		var fileExtension;		
		fileExtension = data.file.substr((data.file.lastIndexOf('.') + 1));
		if(fileExtension == 'jpeg' || fileExtension =='jpg' || fileExtension =='gif' || fileExtension == 'png'){
			li += "<br>";
			li += `<img src=${data.message_file} class="img-thumbnail" width="100">`;
			li += `<a href=${data.message_file} download="" class="d-block mt-2 ml-1">
					<i class="fa fa-download"></i> ${data.file} </a>`;
			//li += "<br>";
		}else{
			li += "<br>";
			li += `<a href=${data.message_file} download="" class="d-block mt-2 ml-1">
					<i class="fa fa-download"></i> ${data.file} </a>`;
			//li += "<br>";
		}
	}
	li += `</div></li>`;
	$('.messages').append(li);

	height += 2000;
	$(".col-md-8 .messages").animate({scrollTop: height});

}

if(enable_websocket == 0){
	setInterval(function(){
		$.ajax({
			method: "POST",
			url: "includes/display_messages",
			data: {message_group_id: message_group_id}
		}).done(function(data){
			$('.specfic .messages').empty();
			$('.specfic .messages').append(data);
			
			if(scroll == 1){
				height += 2000;
				$(".col-md-8 .messages").animate({scrollTop: height});
				scroll = 0;
			}

		});
	}, 2000);
}

</script>