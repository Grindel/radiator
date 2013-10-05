<?php

function selectFromDb($q) {
	global $db;
	$r 	= $db->query($q);
	$return = new ArrayObject();
	while ($row = $r->fetchArray(SQLITE3_ASSOC)) {
    	$return->append($row);	
	}
	return $return;
}

function createStation() {
	global $db;
	$db->query("INSERT INTO stations (url, name) VALUES ('${_REQUEST['url']}', '${_REQUEST['name']}' )");
	header('Location: /');
}

function delete() {
	global $db;
	$db->query("DELETE FROM ${_REQUEST['obj']}s WHERE id = ${_REQUEST['id']}");	
	if ($_REQUEST['obj'] == 'bookmark') {
		unlink('bookmarks/'. $_REQUEST['sample'] .'.mp3' );
	};
	header('Location: /');
}


?>