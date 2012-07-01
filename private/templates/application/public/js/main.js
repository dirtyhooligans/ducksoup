var new_user_message = "First time message";

$(document).ready(function (){
	
});


function init_main()
{
	//alert('init_main');
}

function sendAlert(msg)
{
	alert('FROM FLASH: ' + msg);
}

function sysStatus(msg)
{
	if (! msg )
	{
		$("#system-status-cont").fadeOut("slow");
	}
	else
	{
		$("#system-status-cont .msg").html(msg);
		$("#system-status-cont").fadeIn("fast");
	}
}





