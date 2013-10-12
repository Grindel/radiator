$(document).ready(function () {

	$('.sample').find('.switch').on('click', function(){
		var thisButton  = $( this );
		var audio = thisButton.prev().get(0);
		toggleButton(thisButton);
		if (audio.paused) {
			pause($('audio'));
			audio.play();
			$(audio).on('ended', function() { toggleButton(thisButton); });
		} else {
			audio.pause();
		};
	});

	$('.remove').on('click', function (){
		if (confirm("Вы уверены?")){
			var link = '/?act=del&obj='+ $(this).data('obj') +'&id='+ $(this).data('id')
			if ($(this).data('obj') == 'bookmark')
				link += '&sample=' + $(this).data('sample') 
			window.location.href = link;
		};	
			
	});

	$('.remove_all').on('click', function (){
		if (confirm("Будут удалны все объекты! Вы уверены?")){
			window.location.href = '/?act=delall&obj='+ $(this).data('obj');
		};
	});

	function pause(audio){
		audio.each(function(){
			if (!this.paused) {
				button = $(this).next();
				toggleButton(button);
				this.pause()
			}
		});
	}

	function toggleButton (button) {
		button.toggleClass('glyphicon-play');
		button.toggleClass('glyphicon-pause');
	}
});