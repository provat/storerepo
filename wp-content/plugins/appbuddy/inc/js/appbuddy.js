Hammer.plugins.fakeMultitouch();

(function(window, document, $, undefined){
	'use strict';
	

	var a, appbuddy = {
	
	  selectors: {
	  	body: $('body')
	  },
	
	  init: function() {
	    a = this.selectors;
	    this.uiEvents();
	  },
	
	  uiEvents: function() {
	  
	    a.body.on("click", '.destructive', function(event) {
			$(event.target).parents('#attach-image-sheet').removeClass('active').addClass('hide');
			$('#cam-status').html('');
			$('#image-status').html('');
			$('#attach-image').val('');
	    });
	    
		a.body.on( 'click', '#attach-photo', function( event ) {
			$('#attach-image-sheet').removeClass('hide').addClass('active');
		});
			
		a.body.on( 'click', '.activity-image', function( event ) {
			var image = event.target;	
			$(this).clone()
					.appendTo('body')
					.wrap('<div class="image-pop"></div>')
					.removeClass('activity-image')
			.addClass('close-pop');
				
		});
		
		a.body.on( 'click', '.close-pop', function( event ) {
			$(event.target).parent().remove();
		});
		
		a.body.on( 'click', '.add-activity-image', function( event ) {
			event.preventDefault();
			$('#whats-new-form-in-modal').toggleClass( 'hide' );
			$('#activity_add_media').toggleClass( 'show' );
		});
		
		a.body.on( 'click', '#activity-post-form .io-modal-close', function( event ) {
			event.preventDefault();
			$('#whats-new-form-in-modal').removeClass( 'hide' );
			$('#activity_add_media').removeClass( 'show' );
			$('.ajax-spinner').hide();
			$('#cam-status').html('');
			$('#image-status').html('');
			$('#attach-image').val('');
		});
		
		a.body.on( 'click', '#ab-submit-image', function( event ) {
			event.preventDefault();
			$('.ajax-spinner').show();
			
		});
		
			   
	  }
	
	};
	$(document).bind( 'load_ajax_content_done',  appbuddy.init() );

	// attach ajaxify class to specific bp elements
	var ajaxLink = function() {
		
		$('#main').on('click', '.activity-header a, .activity-inner a, .item-avatar a, .item-title a, .pagination-links a, .item-list-tabs a, #item-buttons a.group-button, #send-private-message a, .message-title a, #message-recipients a, table.notifications a, #member-list li h5 a, #members-list li h5 a, #admins-list li h5 a, .activity-meta a.view', function(event) {
		
			var $self = $(this);
			var $href = $(this).attr('href');
		
			if( $($self).attr('target') === '_blank' ) {
				event.preventDefault();
				window.open($href, '_blank');
			} else {
					
			  $self.addClass('ajaxify'); 
			}
			
			
			
		});
		
		$('#main').on('click', '#post-mention a', function(event) {
				event.preventDefault();
		
				var $self = $(this);
				var $href = $(this).attr('href');
				var $user = getURLParameter($href, 'r');
				
				var UpClasses   = 'slide-in-up-add ng-animate slide-in-up slide-in-up-add-active';
				var downClasses = 'slide-in-up-remove slide-in-up-remove-active';
				$('#whats-new').val( '@' + $user );
				$('#activity-post-form').css('display', 'block').removeClass(downClasses).addClass(UpClasses);
		
		});	
		
		$('.ac-form').on('touchmove', function(e) {
			e.preventDefault();
		});
	
	};
	$(document).on( 'ready', ajaxLink ).bind( 'load_ajax_content_done', ajaxLink );
	
	function getURLParameter(url, name) {
	    return (RegExp(name + '=' + '(.+?)(&|$)').exec(url)||[,null])[1];
	}

})(window, document, jQuery);
