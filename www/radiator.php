<?php

function selectFromDb($q) {
	global $db;
	$r = $db->query($q);
	$return = new ArrayObject();
	while ($row = $r->fetchArray(SQLITE3_ASSOC)) {
    	$return->append($row);	
	}
	return $return;
}

function updateStations() {
	global $db;
	return $db->query("UPDATE conf SET value = '". time() ."' WHERE key = 'stationsV';");
}

function restartDaemon(){
	return exec('sudo python /home/pi/www/radiator/radiatorDaemon.py restart');
}

function createStation() {
	global $db;
	$db->query("INSERT INTO stations (url, name) VALUES ('${_REQUEST['url']}', '${_REQUEST['name']}' )");
	updateStations();
	restartDaemon();
	header('Location: /');
}

function delete() {
	global $db;
	$db->query("DELETE FROM ${_REQUEST['obj']}s WHERE id = ${_REQUEST['id']}");
	if ($_REQUEST['obj'] == 'bookmark') {
		unlink('bookmarks/'. $_REQUEST['sample'] .'.mp3' );
	}else{
		updateStations();
		restartDaemon();
	}
	header('Location: /');
}

function deleteAll() {
	global $db;
	$db->query("DELETE FROM ${_REQUEST['obj']}s ");
	if ($_REQUEST['obj'] == 'bookmark') {
		$files = glob('/home/pi/www/radiator/bookmarks/*');
		foreach($files as $file){
			if(is_file($file))
				unlink($file);
		}
	}else{
		updateStations();
		restartDaemon();
	}
	header('Location: /');	
}

?>