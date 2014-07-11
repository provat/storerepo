jQuery(document).ready(function() {
  jQuery('.gallery').magnificPopup({
    type:'inline',
    gallery: {
      enabled: true, // set to true to enable gallery
      
    },
    callbacks: {
      buildControls: function() {
    	  //this.contentContainer.append(this.arrowLeft.add(this.arrowRight));
    	  this.contentContainer.prepend(this.arrowLeft);
    	  this.contentContainer.append(this.arrowRight);
      },  

    },

    midClick: true // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
  });

});