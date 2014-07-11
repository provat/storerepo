window.ats_object = window.ats_object || { ajax_url : apppCore.ajaxurl };

(function(window, document, $, undefined) {
	var url = ats_object.ajax_url;	
	
	window.AppTest = {};
	AppTest.ajaxloaded   = false;
	
	AppTest.testCall = function(e) {		
		//console.log("Hurray");
		
		if(pageId == 2) {
			$(".ajax-spinner").show();
			$.ajax({
				type: 'POST',
				dataType: "json",
				url: url,
				data: {
					'action': 'ats_load_home',					
				},
				success: function( response ) {
					$(".ajax-spinner").hide();
					$(".scroll-content").html(response.content);
					var page_no = parseInt(response.paged) + 1;
	                $("#page_no").val(page_no);
	                $("#last_page").val(response.lpage);
				}
			});
			
			window.pageId = 0;
		}	
		console.log(window);		
	};
	

	AppTest.init = function() {		
		AppTest.testCall();
	};
	
	$(document).ready( AppTest.init ).bind( 'load_ajax_content_done', function() {
		AppTest.ajaxloaded   = true;
		AppTest.init();
	});
	
	
	/**
	 * Load more content
	 */
	var loading  = false;
	$("#main").scroll(function() {
		var fromtop = $(this).scrollTop();		
		
        height = $(this).find('#content').innerHeight() - $(this).innerHeight();        

	    if ((height - fromtop) < 40) {
	    	loadApppMoreContent();
	    	//alert("Load more content");
	    }
	});
	
	function loadApppMoreContent() {
		var page= $("#page_no").val();
		
		if($("#last_page").val()==2 && loading==false) {
			
			loading = true;
			$(".ajax-spinner").show();
			$.ajax({
				type: 'POST',
				dataType: "json",
				url: url,
				data: {
					'action': 'ats_load_home',
					page: page,
				},
				success: function( response ) {		
					$(".ajax-spinner").hide();
					jQuery(".scroll-content").append(response.content);
					var page_no = parseInt(response.paged) + 1;
	                $("#page_no").val(page_no);
	                $("#last_page").val(response.lpage);
					loading = false;
				}
			});
		}
	}
	
})(window, document, jQuery);