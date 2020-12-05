
	$('.sm').on('focusin','#sm-search',function(){
		$('.sm .search-box').addClass('focus');
	});
	$('.sm').on('focusout','#sm-search',function(){
		val = $(this).val();
		if(val ==""){
			$('.sm .search-box').removeClass('focus');
		}
	})

	$(document).on('click', 'a.support-que', function(){
			elm = $(this);
			
	$('.popup-support-wrap').fadeToggle();
	if(elm.hasClass('open')){
		elm.removeClass('open').addClass('closing');
		setTimeout(function(){
				elm.removeClass('closing').addClass('close');
	
			
		},200)
	}else{
		elm.toggleClass('open close');
	}
		
	});
	
	var _ajax = "";
	var _results = "";
	var _settimeout = "";
	$(document).on('keyup','#sm-search',function(){
		
		var elm = $(this);
		
		get_articles(elm.val(),'')
	});
	
	$(document).on('click','.sm-back',function(){
		
		
		get_articles('','')
		$('.search-results').animate({'left':'0%'});
	});
	
	$(document).on('click','.search-single .home-link',function(){
		
		var id = $(this).data('id');
				
		$('.search-single .img.imgtop').html('');
		$('.search-single .img.imgright').html('');
		$('.search-single .img.imgbottom').html('');
	
		$('.search-single .sm-content').html(_rec.article_body);
		$('.search-single .sm-category').html('');
		$('.search-single .sm-title').html('');
		
		get_articles('',id);
		$('.search-results').animate({'left':'0%'});
	});
	
	
	function get_articles(_val,_cat){

		_val = _val.replace(/<\/?[^>]+(>|$)/g, "");

		if(_ajax){
			_ajax.abort();
		}
		if(_settimeout!=""){
			clearTimeout(_settimeout);
		}
		$('.search-results ul').html('');
		
		if(_val){
			
			$('.search-articles h3').html('Search result for '+_val+':');
		}
		
		if(!_cat && !_val){
			$('.search-articles h3').html('All Articles:');
		}
		
		_settimeout = setTimeout(function(){
		
		_ajax = $.ajax({
				url: site_url+'/search-knowledge',
				data: 'cat='+_cat+'&q='+_val,
				type:'post',
				success: function(res){
					out = $.parseJSON(res);
					_results = out.results;
					_html = '';
					
					if(out.count>0){
						if(_cat)
					$('.search-articles h3').html(out.results[0].article_cat_title +' :');
				
						for(o in out.results){
		
							_html += '<li><a href="#" data-id="'+o+'"><i class="fa fa-file-text-o"></i> '+out.results[o].article_heading+' <i class="fa fa-angle-right pull-right"></i></a></li>';
				
						}
						$('.search-results ul').html(_html);
					}else{
						$('.search-articles h3').html(out.message);
						$('.search-results ul').html(_html);
						
					}
				}
			});
		},
		1000);
		
	}
	
	get_articles('','')
	$(document).on('click','.search-results ul a',function(e){
		e.preventDefault();
		var id = parseInt($(this).data('id'));
		_rec= _results[id];
		$('.search-single .breadcrumbs a').data('id',_rec.cat_id);
		
		
		if(_rec.top_image)
		$('.search-single .img.imgtop').html('<img src="'+site_url+'/article/article_images/'+_rec.top_image+'" /><br>');
		
		if(_rec.right_image)
		$('.search-single .img.imgright').html('<img src="'+site_url+'/article/article_images/'+_rec.right_image+'" />');
		if(_rec.bottom_image)
		$('.search-single .img.imgbottom').html('<img src="'+site_url+'/article/article_images/'+_rec.bottom_image+'" />');
	
		$('.search-single .sm-content').html(_rec.article_body);
		$('.search-single .sm-category').html(_rec.article_cat_title);
		$('.search-single .sm-title').html(_rec.article_heading);
		
		$('.search-results').animate({'left':'-100%'});
		$('#sm-search').val('').trigger('focusout');
		
	});