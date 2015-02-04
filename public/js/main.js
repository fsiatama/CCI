jQuery(function($) {

	var is_msie = (navigator.appVersion.indexOf("MSIE")!=-1) ? true : false;

	if ( $('#full-slider').length > 0 ) {
		$('footer').hide();
		$( '.navbar' ).addClass( 'bottom' )
		
		$('#full-slider').maximage({
			cycleOptions: {
				fx: 		'fade',
				speed: 		1000, // Has to match the speed for CSS transitions in jQuery.maximage.css (lines 30 - 33)
				timeout: 	0,
				prev: 		'#slider_prev',
				next: 		'#slider_next',
				pause: 		1,

				before: function(last,current){
					if(!is_msie) {
						// Start HTML5 video when you arrive
						if(jQuery(current).find('video').length > 0) jQuery(current).find('video')[0].play();
					}
				},

				after: function(last,current){
					if(!is_msie) {
						// Pauses HTML5 video when you leave it
						if(jQuery(last).find('video').length > 0) jQuery(last).find('video')[0].pause();
					}
				}
			},

			onFirstImageLoaded: function(){
				jQuery('#cycle-loader').hide();
				jQuery('#full-slider').fadeIn('fast');
			}

		});
	}

	$('#loginForm').on('submit', function(event){
		var $form = $(this);
		var $btn = $('#loginFormSubmit');
		$btn.button('loading');
		$.ajax({
			type:"POST"
			,url:$form.attr('action')
			,data:{
				email: $("#inputEmail").val(),
				password: $.md5($("#inputPassword").val())
			}
			,dataType:"json"
			,success:function(data){
				if(data.success){
					$form[0].reset();
					window.location.replace(data.url);
				}
				else{
					$("#modal-error-msg").html(data.error);
					$('#errorModal').modal('show');
				}
			}
		}).always(function(){
			$btn.button('reset');
		});
 
		event.preventDefault();
	});
});
