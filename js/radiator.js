$(document).ready(function () {

	$('.sample').find('.switch').on('click', function(){
		var thisSwitch  = $( this );
		var audio = thisSwitch.prev().get(0);
		thisSwitch.toggleClass('glyphicon-play');
		thisSwitch.toggleClass('glyphicon-stop');
		if (audio.paused)
			audio.play();
		else
			audio.pause();
	});

	$('.remove').on('click', function (){
		if (confirm("Вы уверены?"))
			var link = '/?act=del&obj='+ $(this).data('obj') +'&id='+ $(this).data('id')
			if ($(this).data('obj') == 'bookmark')
				link += '&sample=' + $(this).data('sample') 
			window.location.href = link;
	});


});