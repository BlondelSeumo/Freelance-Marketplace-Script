(function($, window, i){
	$.fn.slidesbg = function(options) {
		var options = $.extend({
			dataSlide: "",
			namespace: "slidesbg",
			navSelector: ".slidesbg-slider-nav",
			nav: true,
			customNav: "",
			pagination: true,
			offsetTop: {
				pagination: false,
				nav: false
			},
			autoPlay: true,
			delay: 4000,
			speed: 300,
			start: 0,
			parallax: false,
			overlay: false,
			fullscreen: false,
			keyboard: false,
			inbody: false,
			beforeAct: function() {},
			complete: function() {}
		},options);

		return this.each(function() {
			i++;

			var $this = $(this),
				slidesbgID = options.namespace+i;

			// Memulai membuat element utama slidesbg.js
			$this.prepend($("<div />", {
				id: slidesbgID
			})
			.css({
				position: "absolute"
			})
			.attr("data-slidesbg-now", options.start))
			.find("#"+slidesbgID).append($("<div />", {
				'class': 'slidesbg--bg'
			})
			.css({
				backgroundImage: "url("+options.dataSlide[options.start]+")",
				backgroundSize: 'cover',
				backgroundRepeat: 'no-repeat',
				backgroundPosition: 'center'
			})
			);

			if(options.fullscreen == true) {
				$this.css({
					height: "100vh"
				})
			}

			$("#"+slidesbgID).find(".slidesbg--bg").css({
				width: $this.width(),
				height: $this.height()
			});

			if(options.overlay == true) {
				$("#"+slidesbgID).prepend("<div class=\"slidesbg-overlay\"></div>");
				$("#"+slidesbgID).find(".slidesbg-overlay").css({
					width: $this.width(),
					height: $this.height(),
					position: "absolute"
				})
			}

			$("#"+slidesbgID+" .slidesbg--bg, #"+slidesbgID+" .slidesbg-overlay").wrapAll("<div class=\"slidesbg--group\"></div>");
			if(options.inbody == true) {
				$("#"+slidesbgID).find(".slidesbg--group").css({
					left: $this.offset().left,
					top: $this.offset().top,
					position: 'absolute'
				})

				$("#"+slidesbgID).find(".slidesbg--bg").css({
					backgroundSize: 'cover',
					backgroundAttachment: 'fixed'
				});
				$("#"+slidesbgID).find(".slidesbg-overlay").css({
					position: "absolute"
				});
				$("#"+slidesbgID).find(".slidesbg--group").css({
					zIndex: -1
				});
			}

			if(options.offsetTop.nav == false) {
				var getTopNav = 0;
			}else{
				var getTopNav = options.offsetTop.nav;
			}

			if(options.nav == true) {
				if(!options.customNav) {
					var navSlider = "<div class=\"slidesbg-slider-nav\">";
						navSlider += "<a class=\"prev-slide\">&lsaquo;</a>";
						navSlider += "<a class=\"next-slide\">&rsaquo;</a>";
						navSlider += "</div>";
					$("#"+slidesbgID).prepend(navSlider);

					$("#"+slidesbgID).find(".slidesbg-slider-nav").css({
						position: 'absolute',
						right: 10
					});
					var navS = "#"+slidesbgID+" "+options.navSelector;
				}else{
					var navS = options.customNav;
				}

				var $navNext = $(navS).find(".next-slide"),
					$navPrev = $(navS).find(".prev-slide");

				$navNext.click(function(e) {
					nextSlide();
					e.preventDefault();
				});

				$navPrev.click(function(e) {
					prevSlide();
					e.preventDefault();
				});
			}
	
			if(options.autoPlay == true) {
				var AP = setInterval(function(){
					nextSlide();
				},options.delay);
			}

			var nextSlide = function() {
				nowSlide =  $("#"+slidesbgID).attr("data-slidesbg-now");
				cnowSlide = Number(nowSlide)+Number(1);
				moveSlide(cnowSlide);
			}

			var prevSlide = function() {
				nowSlide =  $("#"+slidesbgID).attr("data-slidesbg-now");
				cnowSlide = Number(nowSlide)-Number(1);
				moveSlide(cnowSlide);
			}

			var moveSlide = function(to) {
				nowSlide =  $("#"+slidesbgID).attr("data-slidesbg-now");
				var getSlide;

				if(to >= options.dataSlide.length) {
					to = 0;
				}

				if(to <= -1) {
					to = options.dataSlide.length-1;
				}

				$("#"+slidesbgID).attr("data-slidesbg-now",to);

				getSlide = options.dataSlide[to];

				$("#"+slidesbgID).find(".slidesbg--bg").slideDown(options.speed, 0.7, function(){
					$(this).css({
						"background-image": "url("+getSlide+")"
					});
					options.complete.call();
				}).slideDown(options.speed,1);

				options.beforeAct.call();

				changeActivePage(to);
			}

			if(options.pagination == true) {
				var paginationEl = "<div class=\"slidesbg-pagination\">";
					paginationEl += "<ul>"
				for(var bull=0; bull<=options.dataSlide.length-1; bull++) {
					paginationEl += "<li><a data-slidesbg-to=\""+bull+"\"></a></li>";
				}
					paginationEl += "</ul>"
					paginationEl += "</div>";

				$("#"+slidesbgID).append(paginationEl);
				if(options.offsetTop.pagination == false) {
					var pageTop = $("#"+slidesbgID).height()-50;
				}else{
					var pageTop = options.offsetTop.pagination;
				}
				$("#"+slidesbgID).find(".slidesbg-pagination").css({
					top: 415
				});

				if(options.inbody == true) {
					$("#"+slidesbgID).find(".slidesbg-pagination").removeAttr("style");
					if(options.offsetTop.pagination == false) {
						var getTopPage = $(window).height()-50;
					}else{
						var getTopPage = options.offsetTop.pagination;
					}
					$("#"+slidesbgID).find(".slidesbg-pagination").css({
						top: getTopPage
					});
				}

				$("#"+slidesbgID).find(".slidesbg-pagination li a").eq($("#"+slidesbgID).attr("data-slidesbg-now")).addClass("active");

				$("#"+slidesbgID).find(".slidesbg-pagination li a").click(function(){
					moveSlide($(this).attr("data-slidesbg-to"));
				});

				var changeActivePage = function(slide) {
					$("#"+slidesbgID).find(".slidesbg-pagination li a").removeClass("active");
					$("#"+slidesbgID).find(".slidesbg-pagination li a").eq(slide).addClass("active");
				}
			}

			if(options.nav == true || options.pagination == true) {
				$("#"+slidesbgID+" .slidesbg-pagination, #"+slidesbgID+" .slidesbg-slider-nav").wrapAll("<div class=\"slidesbg--controls\"></di>");
				$("#"+slidesbgID).find(".slidesbg--controls").css({
					position: "absolute",
					width: $this.width(),
					top:getTopNav
				})
			}

			if(!options.dataSlide) {
				alert("Can't find some images for slide header.");
			}

			if(options.parallax == true) {
				$("#"+slidesbgID).find(".slidesbg--group .slidesbg--bg").css({
					"background-attachment": "fixed"
				})
			}
	
			if(options.keyboard == true) {
				$(window).keydown(function(e) {
					if((e.keydown || e.which) == 37) {
						prevSlide();
					}

					if((e.keydown || e.which) == 39) {
						nextSlide();
					}
				})
			}


			$(window).resize(function(){
				$("#"+slidesbgID).find(".slidesbg--group").css({
					top: $this.offset().top,
					left: $this.offset().left
				});


				$("#"+slidesbgID).find(".slidesbg--group .slidesbg--bg").css({
					width: $this.width(),
					height: $this.height()
				});

				$("#"+slidesbgID).find(".slidesbg-pagination").css({
					top: $("#"+slidesbgID).height()-50
				})

				if(options.inbody == true) {
					$("#"+slidesbgID).find(".slidesbg-pagination").css({
						top: getTopPage
					})
				}

				$("#"+slidesbgID).find(".slidesbg--controls").css({
					width: $this.width()
				})

				$("#"+slidesbgID).find(".slidesbg-overlay").css({
					width: $this.width(),
					height: $this.height()
				});
			});
   		})
	}
})(jQuery, this, 0);