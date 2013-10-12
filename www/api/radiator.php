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
	return exec('sudo python /home/pi/radiator/radiatorDaemon.py restart');
}

function createStation() {
	global $db;
	$db->query("INSERT INTO stations (url, name) VALUES ('${_REQUEST['url']}', '${_REQUEST['name']}' )");
	updateStations();
	restartDaemon();
	header('Location: /api/settings.php');
}

function delete() {
	global $db;
	$db->query("DELETE FROM ${_REQUEST['obj']}s WHERE id = ${_REQUEST['id']}");
	if ($_REQUEST['obj'] == 'bookmark') {
		unlink('../bookmarks/'. $_REQUEST['sample'] .'.mp3' );
		header('Location: /api/bookmarks.php');
	}else{
		updateStations();
		restartDaemon();
		header('Location: /api/settings.php');
	}
}

function deleteAll() {
	global $db;
	$db->query("DELETE FROM ${_REQUEST['obj']}s ");
	if ($_REQUEST['obj'] == 'bookmark') {
		$files = glob('/home/pi/radiator/www/bookmarks/*');
		foreach($files as $file){
			if(is_file($file))
				unlink($file);
		}
		header('Location: /api/bookmarks.php');
	}else{
		updateStations();
		restartDaemon();
		header('Location: /api/settings.php');
	}	
}

?>