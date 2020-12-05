$(document).ready(function(){

	$(function(){
	  $('[data-toggle="tooltip"]').tooltip();
	});
	$(function(){
	  $('[data-toggle="popover"]').popover();
	});

	//// Categories Dropdown Code
	$(".top-nav-item").mouseover(function(){
		$(".body-sub-width").addClass("display-none");
		$(".top-nav-item").removeClass("active");
		var node_id = $(this).data('node-id');
		$(this).addClass("active");
		$(".body-sub-width[data-node-id="+node_id+"]").removeClass("display-none");
	});

	$(".ui-toolkit .cat-nav").mouseleave(function(){
		$(".top-nav-item").removeClass("active");
		$(".body-sub-width").addClass("display-none");
	});

	$('#mobilemenu').click(function(){
		$('html body').css('overflow','hidden');
		$('.cat-mobile').show();
		$('.mobile-subnav').addClass("display-none");
		$(".mobile-catnav-back-btn").addClass("display-none");
		$("#mobile-sub-catnav-header-title").text("");
		$("#mobile-catnav-header-title").removeClass("display-none");
		$(".mobile-cat-nav").addClass("slideInUp slower");
		$(".mobile-topnav").removeClass("display-none").addClass("slideInUp slower");
		if($(window).width() <= 421){
		$(".mobile-catnav-wrapper").css({"top" : "44px"});
		}else if($(window).width() <= 639){
		$(".mobile-catnav-wrapper").css({"top" : "60px"});
		}
	});

	$(".cat-mobile .mobile-topnav ul li").click(function(){
		$(".top-nav-item").removeClass("active");
		var u_id = $(this).data('uid');
		var name = $(this).data('name');
		$("#mobile-sub-catnav-header-title").text(name);
		$("#mobile-catnav-header-title").addClass("display-none");
		$(".mobile-catnav-back-btn").removeClass("display-none");
		$(".mobile-topnav").addClass("display-none");
		$("#mobile-sub-catnav-content-"+u_id).removeClass("display-none").addClass("slideInRight slower");
		$("#mobile-sub-catnav-content-"+u_id+" ul").removeClass("display-none");
	});

	$(".cat-mobile .mobile-catnav-back-btn").click(function(){
		$('.mobile-subnav').addClass("display-none");
		$(".mobile-catnav-back-btn").addClass("display-none");
		$("#mobile-sub-catnav-header-title").text("");
		$("#mobile-catnav-header-title").removeClass("display-none");
		$(".mobile-topnav").removeClass("slideInUp display-none").addClass("slideInLeft slower");
	});

	$('.cat-mobile .overlay-close').click(function(){
		$('.cat-mobile').hide();
		$('html body').removeAttr('style');
	});


	/// Mobile Category Menu Code Enye ////

	/// Mobile User Menu Code Starts ////
	$("#usermenu, .bell, .message").click(function(){
		if($(window).width() <= 629){
			$('html body').css('overflow','hidden');
			$('.user-mobile').show();
			$('.mobile-subnav').addClass("display-none");
			$('.mobile-tertiarynav').addClass("display-none");
			$(".mobile-catnav-back-btn").addClass("display-none");
			$(".user-mobile #mobile-sub-catnav-header-title").text("");
			$(".user-mobile #mobile-catnav-header-title").removeClass("display-none");
			$(".mobile-cat-nav").addClass("slideInUp slower");
			$(".mobile-topnav").removeClass("display-none").addClass("slideInUp slower");
			if($(window).width() <= 421){
				$(".mobile-catnav-wrapper").css({"top" : "44px"});
			}else if($(window).width() <= 639){
				$(".mobile-catnav-wrapper").css({"top" : "60px"});
			}
		}
	});

	$(".user-mobile .mobile-topnav ul li").click(function(){
		$(".top-nav-item").removeClass("active");
		var u_id = $(this).data('uid');
		var name = $(this).data('name');
		$(".user-mobile #mobile-sub-catnav-header-title").text(name);
		$(".user-mobile #mobile-catnav-header-title").addClass("display-none");
		$(".user-mobile .mobile-catnav-back-btn").removeClass("display-none");
		$(".user-mobile .mobile-topnav").addClass("display-none");
		$(".user-mobile #mobile-sub-catnav-content-"+u_id).removeClass("display-none").addClass("slideInRight slower");
		$(".user-mobile #mobile-sub-catnav-content-"+u_id+" ul").removeClass("display-none");
	});

	$(".user-mobile .mobile-catnav-back-btn").click(function(){	var subnav_id = $(this).attr('data-subnav-id');
		if(subnav_id == "0"){
		$('.user-mobile .mobile-subnav').addClass("display-none");
		$('.user-mobile .mobile-tertiarynav').addClass("display-none");
		$(".user-mobile .mobile-catnav-back-btn").addClass("display-none");
		$(".user-mobile #mobile-sub-catnav-header-title").text("");
		$(".user-mobile #mobile-catnav-header-title").removeClass("display-none");
		$(".user-mobile .mobile-topnav").removeClass("slideInUp display-none").addClass("slideInLeft slower");
		}else{
		$(".user-mobile #mobile-sub-catnav-header-title").text("Dashboard");
		$('.user-mobile .mobile-tertiarynav').addClass("display-none");
		$(this).attr("data-subnav-id","0");
		$(".user-mobile #"+subnav_id).removeClass("display-none").addClass("slideInRight slower");
		}
	});

	$(".user-mobile .mobile-subnav ul li").click(function(){
		$(".top-nav-item").removeClass("active");
		var u_id = $(this).data('uid');
		var name = $(this).data('name');
		var parent_id = $(this).parent().parent().attr('id');
		$(".user-mobile #mobile-sub-catnav-header-title").text(name);
		$(".user-mobile #mobile-catnav-header-title").addClass("display-none");
		$(".user-mobile .mobile-catnav-back-btn").removeClass("display-none");
		$(".user-mobile .mobile-catnav-back-btn").attr("data-subnav-id",parent_id);
		$(".user-mobile .mobile-subnav").addClass("display-none");
		$(".user-mobile #mobile-tertiary-nav-"+u_id).removeClass("display-none").addClass("slideInRight slower");
		$(".user-mobile #mobile-tertiary-nav-"+u_id+" ul").removeClass("display-none");
	});

	$('.user-mobile .overlay-close').click(function(){
		$('.user-mobile').hide();
		$('html body').removeAttr('style');
	});

	/// Mobile User Menu Code Ends ////

	// Cache selectors
	var lastId,
	topMenu = $("#mainNav"),
	topMenuHeight = topMenu.outerHeight() + 1,

	// All list items
	menuItems = topMenu.find("a"),

	// Anchors corresponding to menu items
	scrollItems = menuItems.map(function () {
	  var item = $($(this).attr("href"));
	  if (item.length) {return item;}
	});

	// Bind click handler to menu items

	// so we can get a fancy scroll animation
	menuItems.click(function (e) {
	  var href = $(this).attr("href"),
	  offsetTop = href === "#" ? 0 : $(href).offset().top - topMenuHeight + 1;
	  $('html, body').stop().animate({scrollTop: offsetTop },850);
	  // e.preventDefault();
	});

	// Bind to scroll
	$(window).scroll(function(){
	  var scrollTop = $(window).scrollTop();
	  if(scrollTop > 0){
	  	// Get container scroll position
		  var fromTop = $(this).scrollTop() + topMenuHeight;
		  // Get id of current scroll item
		  var cur = scrollItems.map(function () {
		    if ($(this).offset().top < fromTop)
		    return this;
		  });
		  // Get the id of the current element
		  cur = cur[cur.length - 1];
		  var id = cur && cur.length ? cur[0].id : "";
		  if(lastId !== id){
		    lastId = id;
		    // Set/remove active class
		    menuItems.parent().removeClass("selected").end().filter('*[href="#' + id + '"]').parent().addClass("selected");
		  }
		}else{
			$('.mp-gig-top-nav li:eq(0)').addClass("selected");
		}
	});

});