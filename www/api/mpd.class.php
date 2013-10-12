<?php
/*
 * Base MPDControl Class
 * Provide functions for connecting and retrieving raw data from MPD
 * From here: http://demonastery.org/2010/05/mpd-class-for-php/
 * Thank you, Zane!
 */
class MPDControl {
	var $hostname, $port, $timeout, $socket, $errno, $errstr;
	function MPDControl($hostname = 'localhost', $port = '6600', $timeout = 3) {
		$this->hostname = $hostname;
		$this->port = $port;
		$this->timeout = $timeout;
	}

	function connect() {
		$this->socket = @fsockopen($this->hostname, $this->port, $this->errno, $this->errstr, $this->timeout);
		if (!$this->socket) {
			return false;
		} else {
			$this->getData();
			return true;
		}
	}

	function disconnect() {
		$this->sendRaw('close');
		fclose($this->socket);
	}

	function getData() {
		$lines = Array();
		$line = '';
		while ((substr($line, 0, 2) != 'OK') && (substr($line, 0, 3) != 'ACK')) {
			$line = fgets($this->socket, 256);
			$lines[] = $line;
		}
		unset($lines[count($lines)-1]);
		return $lines;
	}

	function sendRaw($string) {
		fwrite($this->socket, $string."\n");
	}
}

class ExtendedMPDControl extends MPDControl {
	
	function getPlaylist() {
		$this->sendRaw('playlistinfo');
		$buffer = $this->getData();
		$playpos = -1;
		$url = $songTitle = $id = $pos = '';
		$playlist = Array();
		foreach ($buffer as $line) {
			$line = explode(': ', $line);
			switch ($line[0]) {
				case 'file':
					$url = trim($line[1]);
					break;
				case 'Title':
					$songTitle = trim($line[1]);
					break;
				case 'Pos':
					$pos = trim($line[1]);
					break;
				case 'Id': # End of the station
					$id = trim($line[1]);
					$playlist[$playpos+1] = Array(
						'url' => $url, 'songTitle' => $songTitle, 
						'pos' => $pos, 'id' => $id);
					$playpos = $pos;
					$url = $songTitle = $title = $id = $pos = '';
					break;
			}
		}
		return $playlist;
	}

	function getStatus() {
		$this->sendRaw('status');
		$buffer = $this->getData();
		$volume = $state = $song = $songId = $nextSong = $nextSongId ='';
		$status = Array();
		foreach ($buffer as $line) {
			$line = explode(': ', $line);
			switch ($line[0]) {
				case 'volume':
					$volume = trim($line[1]);
					break;
				case 'state':
					$state = trim($line[1]);
					break;
				case 'song':
					$song = trim($line[1]);
					break;
				case 'songid':
					$songId = trim($line[1]);
					break;
				case 'nextsong':
					$nextSong = trim($line[1]);
					break;
				case 'nextsongid':
					$nextSongId = trim($line[1]);
					break;
			}
		}

		$this->sendRaw('currentsong');
		$buffer = $this->getData();
		$songTitle = '';
		foreach ($buffer as $line) {
			$line = explode(': ', $line);
			switch ($line[0]) {
				case 'Title':
					$songTitle = trim($line[1]);
					break;
			}
		}
		
		$status = [ 'volume' => $this->getVol(), 'state' => $state,
					'song' => $song, 'songId' => $songId, 'songTitle' => $songTitle,
					'nextSong' => $nextSong, 'nextSongid' => $nextSongId];
		return $status;
	}
	
	function getVol() {
		exec('amixer sget Speaker', $output);
		$vol = trim(explode(' ', $output[5])[5]);
		return $vol;
	}

	function setVol($vol) {
		exec('amixer sset Speaker '.$vol);
	}
}
