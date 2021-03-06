appcamera.init = function(){
    var $ = jQuery;
    appcamera.el = {
        $body  : $('body'),
        $title : $('#appp_cam_post_title'),
        $file  : $('#appp_cam_file')
    };

    appcamera.el.$body.on( 'change', '#appp_cam_file', function( event ){
        event.preventDefault();
        var $self = $(this);
        var title = appcamera.el.$title.val();

        if ( title && title.trim() )
            return;

        var val   = $self.val();
        var parts = val ? val.split('.') : false;

        if ( parts[0] ) {
            parts = parts[0].split('\\');
            val = parts[ parts.length - 1 ];
            appcamera.el.$title.val( val );
        }
    });
};

appcamera.capturePhoto = function() {
    // Retrieve image file location from specified source
    window.navigator.camera.getPicture(
        appcamera.uploadPhoto,
        function(message) {
            /*alert('No photo was uploaded.');*/
            if ( typeof apppresser.log == 'function' ) {
                apppresser.log( 'No photo was taken from the camera.', 'appp-camera.js, line 35' );
            }
        },
        {
            quality         : 30,
            destinationType : window.navigator.camera.DestinationType.FILE_URI,
            correctOrientation: true,
             targetWidth: 600,
			 targetHeight: 600
        }
    );
};

appcamera.photoLibrary = function() {
    // Retrieve image file location from specified source
    window.navigator.camera.getPicture(
        appcamera.uploadPhoto,
        function(message) {
            /*alert('No photo was uploaded.');*/
            if ( typeof apppresser.log == 'function' ) {
                apppresser.log( 'No photo was added from the library.', 'appp-camera.js, line 53' );
            }
        },
        {
            quality         : 30,
            destinationType : window.navigator.camera.DestinationType.FILE_URI,
            sourceType      : window.navigator.camera.PictureSourceType.PHOTOLIBRARY,
            correctOrientation: true,
            targetWidth: 600,
			targetHeight: 600
        }
    );
};

appcamera.statusDom = function() {
    appcamera.statusDomEl = appcamera.statusDomEl ? appcamera.statusDomEl : document.getElementById('cam-status');
    return appcamera.statusDomEl;
};


appcamera.uploadPhoto = function(imageURI) {
	
    var options      = new FileUploadOptions();
    options.fileKey  = 'appp_cam_file';
    options.fileName = imageURI ? imageURI.substr( imageURI.lastIndexOf('/') + 1 ) : '';
    options.mimeType = 'image/jpeg';

    var params = {};
    var form_fields = [];
    var form_values = [];
    var iterator;
    var form_elements = document.appp_camera_form.elements;
    
    console.log(form_elements);

    for( iterator = 0; iterator < form_elements.length; iterator++ ){
        form_fields[iterator] = form_elements[iterator].name;
        form_values[iterator] = form_elements[iterator].value;
    }

    params.form_fields = JSON.stringify(form_fields);
    params.form_values = JSON.stringify(form_values);

    document.getElementById('appp_cam_post_title').value = '';
    options.params = params;

    var ft = new FileTransfer();

    ft.upload( imageURI, encodeURI(document.URL), appcamera.win, appcamera.fail, options);

    ft.onprogress = function(progressEvent) {
        if ( progressEvent.lengthComputable ) {
            appcamera.statusDom().innerHTML = '<progress id="progress" value="1" max="100"></progress>';
            var perc = Math.floor(progressEvent.loaded / progressEvent.total * 100);
            document.getElementById('progress').value = perc;
        } else {
            if ( appcamera.statusDom().innerHTML == '') {
                appcamera.statusDom().innerHTML = appcamera.msg.loading;
            } else {
                appcamera.statusDom().innerHTML += '.';
            }
        }
    };
    
};

appcamera.win = function(r) {

    console.log('Code = ' + r.responseCode);
    console.log('Response = ' + r.response);
    console.log('Sent = ' + r.bytesSent);
  
    var msg = appcamera.msg.moderation;
    var action = document.getElementById('appp_action').value;

    if ( ! appcamera.moderation_on ) {

        // var type = jQuery('#appp_post_type_label').val();
        // type = type ? type : appcamera.msg.default_type;

        msg = appcamera.msg.success;
    }
    
    appcamera.statusDom().innerHTML= '<p>'+ msg +'</p>';

};

appcamera.fail = function(error) {
    // alert('An error has occurred: Code = ' + error.code);
    console.log('upload error source ' + error.source);
    console.log('upload error target ' + error.target);
    appcamera.statusDom().innerHTML= '<p>'+ appcamera.msg.error +'= '+ error.code +'</p>';
};

appcamera.attachPhoto = function() {
    // Retrieve image file location from specified source
    window.navigator.camera.getPicture(
        appcamera.uploadAttachPhoto,
        function(message) {
            /*alert('No photo was uploaded.');*/
            if ( typeof apppresser.log == 'function' ) {
                apppresser.log( 'No photo was added from the library.', 'appp-camera.js, line 53' );
            }
        },
        {
            quality         : 30,
            destinationType : window.navigator.camera.DestinationType.FILE_URI,
            correctOrientation: true
        }
    );
};

appcamera.attachLibrary = function() {
    // Retrieve image file location from specified source
    window.navigator.camera.getPicture(
        appcamera.uploadAttachPhoto,
        function(message) {
            /*alert('No photo was uploaded.');*/
            if ( typeof apppresser.log == 'function' ) {
                apppresser.log( 'No photo was added from the library.', 'appp-camera.js, line 53' );
            }
        },
        {
            quality         : 30,
            destinationType : window.navigator.camera.DestinationType.FILE_URI,
            sourceType      : window.navigator.camera.PictureSourceType.PHOTOLIBRARY,
            correctOrientation: true
        }
    );
};

appcamera.uploadAttachPhoto = function(imageURI) {
	
    var options      = new FileUploadOptions();
    options.fileKey  = 'appp_cam_file';
    options.fileName = imageURI ? imageURI.substr( imageURI.lastIndexOf('/') + 1 ) : '';
    options.mimeType = 'image/jpeg';

    var params = {};
    params.action = 'upload_image';

    options.params = params;
    
    var ft = new FileTransfer();

    ft.upload( imageURI, ajaxurl, appcamera.attachWin, appcamera.fail, options);
    
    ft.onprogress = function(progressEvent) {
        if ( progressEvent.lengthComputable ) {
            appcamera.statusDom().innerHTML = '<progress id="progress" value="1" max="100"></progress>';
            var perc = Math.floor(progressEvent.loaded / progressEvent.total * 100);
            document.getElementById('progress').value = perc;
        } else {
            if ( appcamera.statusDom().innerHTML == '') {
                appcamera.statusDom().innerHTML = appcamera.msg.loading;
            } else {
                appcamera.statusDom().innerHTML += '.';
            }
        }
    };
    
};

appcamera.attachWin = function(r) {

    console.log('Code = ' + r.responseCode);
    console.log('Response = ' + r.response);
    console.log('Sent = ' + r.bytesSent);

    var action = document.getElementById('appp_action').value;

    if ( action == 'attach' ) {
        msg = 'Image attached';
    }

    appcamera.statusDom().innerHTML= '<p>'+ msg +'</p>';
    
    var img = '<img src="'+ JSON.parse(r.response) +'">';
    
    document.getElementById('attach-image').value = JSON.parse(r.response);
    jQuery('#attach-image-sheet').removeClass('active').addClass('hide');
    jQuery('#image-status').html(img);

};


jQuery(document).ready( appcamera.init ).bind( 'load_ajax_content_done', appcamera.init );
