var Modal = {
	/**
	* show the dialog with the post
	*/
		show:
		function show(){
	$('#overlay').fadeIn('fast', function(){$('#overlay').show();});
			if(!$('#modal').is(':visible'))
			$('#modal').css('left','-260px')
					   
					   .stop()
						   .animate({'left':'50%'}, 0).fadeIn('slow');
		},
	
	/**
	* hide the dialog
	*/
	hide:
		function hide(){
			$('#modal').fadeOut('fast', function(){
					$(this).hide();
					$('#overlay').fadeOut('slow', function(){$('#overlay').hide();initNavigation();});
				});
			
				$("#model_details .message").empty();
				
		},
	
	/**
	 * set the title of the alert
	 */
	setHeader:
		function (str)
		{
			$("#modal_header").html(str);
		},
		
	/**
	 * set the title of the alert
	 */
	setTitle:
		function (str)
		{
			$("#model_details .title").html(str);
		},
	
	/**
	 * set the title of the alert
	 */
	setMessage:
		function (str)
		{
			$("#model_details .message").html(str);
		}
	
};
	

$(document).ready(function (){
	/**
	* clicking on the cross hides the dialog
	*/
	$('#modal .close').bind('click',function(){
		Modal.hide();
	});
	
	/**
	* clicking on the next on the dialog
	*/
	$('#modal .next').bind('click',function(e){
		if(current == total){ 
			e.preventDefault();
			return;
		}	
		$('#latest_post').empty().addClass('loading');
		$('#friendsList li:nth-child('+ parseInt(current+1) +')').find('a').trigger('click');
		e.preventDefault();
	});
	
	/**
	* clicking on the prev on the dialog
	*/
	$('#modal .prev').bind('click',function(e){										
		if(current == 1){ 
			e.preventDefault();
			return;
		}	
		$('#latest_post').empty().addClass('loading');
		$('#friendsList li:nth-child('+ parseInt(current-1) +')').find('a').trigger('click');
		e.preventDefault();
	});
});
