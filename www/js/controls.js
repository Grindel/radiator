$(function(){

	var status, playlist = {};

	$('#volume').slider({ range: 'min', max: 100,step: 1, change: setVol });
	$('#bookmark').click( function () { bookmark();	});
	$('#next').click( function () { switchSt('next'); });
	$('#prev').click( function () { switchSt('prev');	});
	$('#play').click( function () { play();	});
	$('#pause').click( function () { pause();});
	$('#controls .control').click( function () {
		var it = $(this);
		it.toggleClass('active');
		setTimeout(function () { it.toggleClass('active'); }, 100)
	} );

	$.ajax({ //Init
		url: '/api/?m=init',
		dataType : "json",
		success: function (data) {
			playlist = data['playlist'];
			status = data['status'];
			updateView();
			$('#volume').slider( 'value', status['volume'])
			$("#controls").show();
			setInterval(updateStatus, 1200);
		} 
	});

	function updateView (){
		if (!status['song'])
			return;
		$('#station_name').html(playlist[status['song']]['stationName']);
		if (status['songTitle'])
			$('#song_title').html(status['songTitle']);
		if (status['state'] == 'play') {
			$('#play').hide();
			$('#pause').show();
		}else{
			$('#play').show();
			$('#pause').hide();
		};
	};

	function updateStatus (){
		$.ajax({
			url: '/api/?m=status',
			dataType : "json",
			success: function (data) {
				status = data['status'];
				if (status['state'] == 'stop') {
					play ();
				};
				updateView();
				$("#controls").show();
			} 
		});
	}

	function play () {
		$.ajax({
			url: '/api/?m=play',
			success: function () {
				setTimeout( updateStatus, 250);	
			} 
		});
	}

	function pause () {
		$.ajax({
			url: '/api/?m=pause',
			success: function () {
				setTimeout( updateStatus, 250);	
			} 
		});
	}

	function switchSt(cmd) {
		$.ajax({
			url: '/api/?m=' + cmd,
			success: function () {
				setTimeout( updateStatus, 250);	
			} 
		});
	}

	function bookmark () {
		$.ajax({
			url: '/api/?m=bookmark',
		});
	}

	function setVol () {
		$.ajax({ url: '/api/?m=setvol&v=' + $( "#volume" ).slider( "value" ) });
	}
});