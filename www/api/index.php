<?php

if (isset($_REQUEST['m'])) {

	include('radiator.php');
	$db	= new SQLite3('../../radiator.sqlite3');
	$db->busyTimeout(5000);

	include('mpd.class.php');
	$mpd = new ExtendedMPDControl();
	$mpd->connect();

	$response = "";

	switch ($_REQUEST['m']) {
		case 'init':
			$playlist = $mpd->getPlaylist();
			$stations = selectFromDb('SELECT * FROM stations ORDER BY stations.id DESC');
			foreach ($playlist as $k => $plSt) {
				foreach ($stations as $st) {
					if ($plSt['url'] == $st['url']) {
						$playlist[$k]['stationName'] = $st['name'];
					};
				};
			};
			$response = [ 'playlist' => $playlist, 'status' => $mpd->getStatus() ];
			break;

		case 'next':
			if ($mpd->getStatus()['nextSong']) {
				$mpd->sendRaw('next');
			} else {
				$mpd->sendRaw('play 0');
			}
			break;

		case 'prev':
			if ($mpd->getStatus()['song'] == '0') {
				$pos = count($mpd->getPlaylist()) - 1;
				$mpd->sendRaw('play '.$pos);
			} else {
				$mpd->sendRaw('previous');
			}
			break;

		case 'status':
			$response = [ 'status' => $mpd->getStatus() ];
			break;

		case 'play':
			$mpd->sendRaw('play');
			break;

		case 'pause':
			$mpd->sendRaw('pause');
			break;

		case 'setvol':
			$mpd->setVol($_REQUEST['v']);
			break;

		case 'bookmark':
			exec('sudo python /home/pi/radiator/bookmark.py');
			break;
		
		default:
			$response = "error";
			break;
	};
	echo json_encode($response);
	exit();
};

?>