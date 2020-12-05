<script type="text/javascript">
$(document).ready(function(){

var showMessages;
var typeStatus;

<?php if(isset($_GET["single_message_id"])){ ?>
  msgHeader(<?= $input->get("single_message_id"); ?>);
<?php } ?>

$(document).on('click', '.closeMsg', function(e){
  event.preventDefault();
  $(".specfic.col-md-3").show();
  $(".specfic.col-md-12").hide();
});

$(document).on('click', '.message-recipients', function(e){
  var message_group_id = $(this).data("id");
  
  if($("#msgHeader").html() != ""){
    websocket.close();
    console.log("connected closed");
  }

  addRemoveSelected(this);
  msgHeader(message_group_id);
});

function msgHeader(message_group_id){
  $("#wait").addClass("loader");
  $.ajax({
    method:'POST',
    url: "includes/msgHeader",
    data: {message_group_id:message_group_id},
    success: function(data){
      $("#msgHeader").html(data);
      showSingle(message_group_id);
    }
  });
}

function showSingle(message_group_id){
  $.ajax({
  method: "POST",
  url: "includes/showSingle",
  data: {message_group_id:message_group_id},
  success: function(server_response){
    $("#selectConversation").hide();
    $("#msgHeader").removeClass("d-none");
    $("#showSingle").html(server_response);
    $("#wait").removeClass("loader");
    if ( $(window).width() > 767) {
     // Add your javascript for large screens here 
    }else {
      $('.specfic.col-md-3').hide();
      $('.specfic.col-md-9,.specfic.col-md-12').show();
      $('.specfic.col-md-9').attr("class","specfic col-md-12");
      $('#msgSidebar').hide();
    }
  }
  });
}

function addRemoveSelected(select){
  $(".col-md-3 .message-recipients").removeClass("selected");
  $(select).addClass("selected");
}

$('#all').click(function(){
  $(".inboxHeader .dropdown-toggle").html("All Conversations");
  $(".dropdown-menu a").attr('class','dropdown-item');
  $("#all").attr('class','dropdown-item active');
  $(".message-recipients").show();
  $(".unreadMsg").addClass("d-none");
  $(".archivedMsg").addClass("d-none");
  $(".starredMsg").addClass("d-none");
});
$('#unread').click(function(){
  $(".inboxHeader .dropdown-toggle").html("Unread");
  $(".dropdown-menu a").attr('class','dropdown-item');
  $("#unread").attr('class','dropdown-item active');
  $(".message-recipients").hide();
  $(".unread").show();
  $(".unreadMsg").removeClass("d-none");
  $(".archivedMsg").addClass("d-none");
  $(".starredMsg").addClass("d-none");
}); 
$('#starred').click(function(){
  $(".inboxHeader .dropdown-toggle").html("Starred");
  $(".dropdown-menu a").attr('class','dropdown-item');
  $("#starred").attr('class','dropdown-item active');
  $(".message-recipients").hide();
  $(".starred").show();
  $(".archivedMsg").addClass("d-none");
  $(".unreadMsg").addClass("d-none");
  $(".starredMsg").removeClass("d-none");
}); 
$('#archived').click(function(){
  $(".inboxHeader .dropdown-toggle").html("Unread");
  $(".dropdown-menu a").attr('class','dropdown-item');
  $("#archived").attr('class','dropdown-item active');
  $(".message-recipients").hide();
  $(".archived").show();
  $(".unreadMsg").addClass("d-none");
  $(".starredMsg").addClass("d-none");
  $(".archivedMsg").removeClass("d-none");
}); 
$('.search-icon').click(function(){
  $(".search-bar").removeClass("d-none");
  $(".inboxHeader .float-left").addClass("d-none");
  $(".inboxHeader .float-right").addClass("d-none");
});
$('.search-bar input').on('keyup', function() {
  var searchVal = $(this).val();
  var filterItems = $('[data-username]');
  if ( searchVal != '' ) {
    filterItems.addClass('d-none');
    $('[data-username*="' + searchVal.toLowerCase() + '"]').removeClass('d-none');
  } else {
    filterItems.removeClass('d-none');
  }
});
$('.search-bar a').click(function(){
  $(".search-bar").addClass("d-none");
  $(".search-bar input").val("");
  $(".float-left").removeClass("d-none");
  $(".float-right").removeClass("d-none");
  $('[data-username]').removeClass('d-none');
});

});
</script>