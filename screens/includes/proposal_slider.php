<style>
.proposal-slider .item img{opacity:0.3;height:68px;width: 100%; border-radius: 0px;}
.proposal-slider .item.active img{opacity:1;border:0.5px solid #ccc;}

.slide-left img,
.slide-right img{
   margin-top: 19px;
}

.slide-left,
.slide-right{
   background-color: #fff;
   position: absolute;
   z-index: 4;
   top: 50%;
   width: 50px;
   height: 50px;
   right: 20px;
   margin-top: -24px;
   text-align: center;
   opacity: 1;
   -webkit-box-shadow: 0 2px 5px 0 rgba(0,0,0,.15);
   border-radius: 31px;
}

.slide-left:hover,
.slide-right:hover{
   opacity: 1;
}

.slide-right{
   left: 20px;
}

</style>

<?php $jwplayer_code = ""; ?>

<div id="myCarousel" class="carousel slide">
  <ol class="carousel-indicators">
		<?php if(!empty($proposal_video)){ ?>
		<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
		<?php } ?>
		<li data-target="#myCarousel" data-slide-to="1" <?php if(empty($proposal_video)){ echo "class='active'"; } ?>></li>
		<?php if(!empty($proposal_img2)){ ?>
		<li data-target="#myCarousel" data-slide-to="2"></li>
		<?php } ?>
		<?php if(!empty($proposal_img3)){ ?>
		<li data-target="#myCarousel" data-slide-to="3"></li>
		<?php } ?>
		<?php if(!empty($proposal_img4)){ ?>
		<li data-target="#myCarousel" data-slide-to="4"></li>
		<?php } ?>
	</ol>
	<div class="carousel-inner">
		<?php if(!empty($proposal_video)){ ?>
		<div class="carousel-item active">
			<?php if($proposal_video_type == "uploaded"){ ?>
				<?php if(!empty($jwplayer_code)){ ?>
				<script type="text/javascript" src="<?= $jwplayer_code; ?>"></script>
				<div class="d-block w-100" id="player"></div>
                <?php
                   $show_video = str_replace("\r", "", $show_video);
                   $show_video = str_replace("\n", "", $show_video);
                ?>
				<script type="text/javascript">
					var player = jwplayer('player');
					player.setup({
						file: "<?= $show_video; ?>",
						image: "<?= $show_img1; ?>"
					});
				</script>
				<?php }else{ ?>
				<video class="embed-responsive embed-responsive-16by9"  style="background-color:black;" controls>
					<source class="embed-responsive-item" src="<?= $show_video; ?>" type="video/mp4">
					<source src="<?= $show_video; ?>" type="video/ogg">
				</video>
				<?php } ?>
			<?php }elseif($proposal_video_type == "embedded"){ ?>
			<div class="embed-responsive embed-responsive-16by9">
			  <?= $proposal_video; ?>
			</div>
			<?php } ?>
		</div>
		<?php } ?>
		<div class="carousel-item <?php if(empty($proposal_video)){ echo "active"; }?>">
			<img class="d-block w-100" src="<?= $show_img1; ?>">
			<div data-action="img-1" class="slide-fullscreen">Full Screen</div>
		</div>
		<?php if(!empty($proposal_img2)){ ?>
		<div class="carousel-item">

            <?php if($img_2_extension == "mp3" or $img_2_extension == "wav"){ ?>
        
                <div class="audio-player" style="background:url('<?= $show_img1; ?>');">
                    <div class="player-1">
                        <audio controls crossorigin class="d-none embed-responsive embed-responsive-16by9">
                            <source src="<?= $show_img2; ?>" type="audio/mpeg">
                        </audio>
                    </div>
                </div>

            <?php }else{ ?>
                <img class="d-block w-100" src="<?= $show_img2; ?>" />
                <div data-action="img-2" class="slide-fullscreen">Full Screen</div>
            <?php } ?>

		</div>
		<?php } ?>
		<?php if(!empty($proposal_img3)){ ?>
		<div class="carousel-item"><!-- carousel-item Starts -->

            <?php if($img_3_extension == "mp3" or $img_3_extension == "wav"){ ?>

                <div class="audio-player" style="background:url('<?= $show_img1; ?>');">
                    <div class="player-2">
                        <audio controls crossorigin class="d-none embed-responsive embed-responsive-16by9">
                            <source src="<?= $show_img3; ?>" type="audio/mpeg">
                        </audio>
                    </div>
                </div>

            <?php }else{ ?>
                <img class="d-block w-100" src="<?= $show_img3; ?>">
                <div data-action="img-3" class="slide-fullscreen">Full Screen</div>
            <?php } ?>

		</div><!-- carousel-item Ends -->
		<?php } ?>
		<?php if(!empty($proposal_img4)){ ?>
		<div class="carousel-item"><!-- carousel-item Starts -->

            <?php if($img_4_extension == "mp3" or $img_4_extension == "wav"){ ?>

                <div class="audio-player" style="background:url('<?= $show_img1; ?>');">
                    <div class="player-3">
                        <audio controls crossorigin class="d-none embed-responsive embed-responsive-16by9">
                            <source src="<?= $show_img4; ?>" type="audio/mpeg">
                        </audio>
                    </div>
                </div>

            <?php }else{ ?>
                <img class="d-block w-100" src="<?= $show_img4; ?>">
                <div data-action="img-4" class="slide-fullscreen">Full Screen</div>
            <?php } ?>

		</div><!-- carousel-item Ends -->
		<?php } ?>
	</div>
	<a class="carousel-control-prev slide-nav slide-right" href="#myCarousel" data-slide="prev">
		<!--<span class="carousel-control-prev-icon carousel-icon"></span>-->
      <img src="../../images/svg/next.svg" width="20" style="transform: scaleX(-1);">
	</a>
	<a class="carousel-control-next slide-nav slide-left" href="#myCarousel" data-slide="next">
		<!--<span class="carousel-control-next-icon carousel-icon"></span>-->
		<img src="../../images/svg/next.svg" width="20">
	</a>
</div>

<?php if($deviceType != "phone"){ ?>

<div class="card mb-0 rounded-0 border-0"><!-- card Starts -->
    <div class="card-body proposal-slider pb-0 pt-2 pl-0"><!-- card-body Starts -->
        <div class="owl-carousel owl-theme"><!--- owl-carousel owl-theme Starts --->
            <?php if(!empty($proposal_video)){ ?>
                
                <div class="item active" data-position="0">
                   <a href="#">
                   	<img src="../../images/youtube.jpg" alt="youtube.jpg">
                   </a>
                </div>

                <div class="item" data-position="1">
                    <a><img src="<?= $show_img1; ?>" alt="<?= $proposal_img1; ?>"></a>
                </div>

                <?php if(!empty($proposal_img2)){ ?>
                <div class="item" data-position="2">
                    <a>
                        <?php if($img_2_extension == "mp3" or $img_2_extension == "wav"){ ?>
                            <img src="../proposal_files/audio.jpg" alt="<?= $proposal_img2; ?>">
                        <?php }else{ ?>
                            <img src="<?= $show_img2; ?>" alt="<?= $proposal_img2; ?>">
                        <?php } ?>
                    </a>                    
                </div>
                <?php } ?>

                <?php if(!empty($proposal_img3)){ ?>
                <div class="item" data-position="3">

                    <a>
                        <?php if($img_3_extension == "mp3" or $img_3_extension == "wav"){ ?>
                            <img src="../proposal_files/audio.jpg" alt="<?= $proposal_img2; ?>">
                        <?php }else{ ?>
                            <img src="<?= $show_img3; ?>" alt="<?= $proposal_img3; ?>">
                        <?php } ?>
                    </a>    

                </div>
                <?php } ?>

                <?php if(!empty($proposal_img4)){ ?>
                <div class="item" data-position="4">

                    <a>
                        <?php if($img_4_extension == "mp3" or $img_4_extension == "wav"){ ?>
                            <img src="../proposal_files/audio.jpg" alt="<?= $proposal_img4; ?>">
                        <?php }else{ ?>
                            <img src="<?= $show_img4; ?>" alt="<?= $proposal_img4; ?>">
                        <?php } ?>
                    </a>

                </div>
                <?php } ?>

            <?php }else{ ?>

                <div class="item active" data-position="0">
                  <a><img src="<?= $show_img1; ?>" alt="<?= $proposal_img1; ?>"></a>
                </div>
                
                <?php if(!empty($proposal_img2)){ ?>
                <div class="item" data-position="1">
                  
                    <a>
                        <?php if($img_2_extension == "mp3" or $img_2_extension == "wav"){ ?>
                            <img src="../proposal_files/audio.jpg" alt="<?= $proposal_img2; ?>">
                        <?php }else{ ?>
                            <img src="<?= $show_img2; ?>" alt="<?= $proposal_img2; ?>">
                        <?php } ?>
                    </a>

                </div>
                <?php } ?>
                
                <?php if(!empty($proposal_img3)){ ?>
                <div class="item" data-position="2">

                    <a>
                        <?php if($img_3_extension == "mp3" or $img_3_extension == "wav"){ ?>
                            <img src="../proposal_files/audio.jpg" alt="<?= $proposal_img3; ?>">
                        <?php }else{ ?>
                            <img src="<?= $show_img3; ?>" alt="<?= $proposal_img3; ?>">
                        <?php } ?>
                    </a>

                </div>
                <?php } ?>
                
                <?php if(!empty($proposal_img4)){ ?>
                <div class="item" data-position="3">

                    <a>
                        <?php if($img_4_extension == "mp3" or $img_4_extension == "wav"){ ?>
                            <img src="../proposal_files/audio.jpg" alt="<?= $proposal_img4; ?>">
                        <?php }else{ ?>
                            <img src="<?= $show_img4; ?>" alt="<?= $proposal_img4; ?>">
                        <?php } ?>
                    </a>

                </div>
                <?php } ?>

            <?php } ?>

        </div><!--- owl-carousel owl-theme Ends --->
    </div><!-- card-body Ends -->
</div><!-- card rounded-0 mb-3 Ends -->

<script type="text/javascript">
	
$(document).ready(function(){

    $(".proposal-slider .owl-carousel").owlCarousel({
        margin: 20,
        nav: !0,
        mouseDrag: !1,
        touchDrag: !1,
        responsive: {
            0: {
              items: 2
            },
            600: {
              items: 4
            },
            900: {
              items: 5
            },
            1e3: {
              items: 6
            }
        }
    });

	/// Proposal Slider Code Starts ///
	$(".proposal-slider .item").click(function(){
		var eq = $(this).data('position');
        $(".proposal-slider .item").removeClass("active");
		$(".carousel-item").removeClass("active");
		$(".carousel-item:eq("+ eq +")").addClass("active");
		$(".proposal-slider .item:eq("+ eq +")").addClass("active");
	});

	// Slide Track COde Starts

	$("#myCarousel").on('slide.bs.carousel', function(event){
		var eq = event.to;
		$(".proposal-slider .item").removeClass("active");
		$(".proposal-slider .item:eq("+ eq +")").addClass("active");
	});

});

</script>

<?php } ?>