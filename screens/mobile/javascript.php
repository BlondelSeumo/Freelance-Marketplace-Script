<script>
$(document).ready(function(){

	<?php if(!empty($proposal_video)){ ?>
    $(".carousel-indicators").hide();
    $("#myCarousel").on('slide.bs.carousel', function(event){
			var eq = event.to;
			if(eq == 0){
				$(".carousel-indicators").hide();
			}else{
				$(".carousel-indicators").show();
			}
    });
  <?php } ?>

	<?php if(isset($_SESSION['seller_user_name'])){ ?>
	$(document).on("click", "#favorite_<?= $proposal_id; ?>", function(event){
		event.preventDefault();
		var seller_id = "<?= $login_seller_id; ?>";
		var proposal_id = "<?= $proposal_id; ?>";
		$.ajax({
		  type: "POST",
		  url: "../../includes/add_delete_favorite",
		  data: { seller_id: seller_id, proposal_id: proposal_id, favorite: "add_favorite" },
		  success: function(){
		  $("#favorite_<?= $proposal_id; ?>").attr({id: "unfavorite_<?= $proposal_id; ?>",});
		  $("#unfavorite_<?= $proposal_id; ?>").html("<i class='fa fa-heart fa-2x dil'></i>");
		  }
		});
	});

	$(document).on("click", "#unfavorite_<?= $proposal_id; ?>", function(event){
		event.preventDefault();
		var seller_id = "<?= $login_seller_id; ?>";
		var proposal_id = "<?= $proposal_id; ?>";
		$.ajax({
	    type: "POST",
	    url: "../../includes/add_delete_favorite",
	    data: { seller_id: seller_id, proposal_id: proposal_id, favorite: "delete_favorite" },
	    success: function(){
	    $("#unfavorite_<?= $proposal_id; ?>").attr({id: "favorite_<?= $proposal_id; ?>"});
	    $("#favorite_<?= $proposal_id; ?>").html("<i class='fa fa-heart fa-2x dil1'></i>");
	  }
		});
	});
	<?php } ?>

	$(document).on("click", ".seller-info .see-more", function(event){
		$(this).text("See less").addClass("see-less").removeClass("see-more");;
		$(".seller-info").addClass("show");
	});

	$(document).on("click", ".seller-info .see-less", function(event){
		$(this).text("See more").addClass("see-more").removeClass("see-less");;
		$(".seller-info").removeClass("show");
	});

	$(".gig-info-desc .see-more").click(function(){
		text = $(this).text();
		if (text === "Read more") {
		  $(this).text("Read less");
		}else{
		  $(this).text("Read more");
		}
		$(".gig-info-desc").toggleClass("show");
	});

	$(".faq-wrap header").click(function(){
		$(".faq-wrap").toggleClass("show");
	});

	$(".reviews-package header h2, .reviews-package header .ficon").click(function(){
		$(".reviews-package").toggleClass("show");
	});

	$(".filter-dd select").change(function(){
	  var value = $(this).val();
	  if(value == "all"){
			$("#all").show();
			$("#good").hide();
			$("#bad").hide();
	  }else if(value == "good"){
			$("#all").hide();
			$("#good").show();
			$("#bad").hide();
	  }else{
		  $("#all").hide();
			$("#good").hide();
			$("#bad").show();
	  }
	});

});
</script>