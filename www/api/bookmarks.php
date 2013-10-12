<?php

$db	= new SQLite3('../../radiator.sqlite3');
$db->busyTimeout(5000);
require 'radiator.php';

if (isset($_REQUEST['act'])) {
	switch ($_REQUEST['act']) {
		case 'create':
			createStation();
			break;
			
		case 'del':
			delete();
			break;

		case 'delall':
			deleteAll();
			break;
		
		default:
			header('Location: /');	
			break;
	}
};
$bookmarks = selectFromDb('SELECT bookmarks.id, bookmarks.name, bookmarks.sample, stations.name AS station_name
							FROM bookmarks
							LEFT  JOIN stations
							ON stations.id=bookmarks.station_id
							ORDER BY bookmarks.id DESC;');

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=0.75">
	<meta name="author" content="Grindel">
	<link rel="shortcut icon" href="/favicon.png">

	<title>Raditor Pi</title>

	<!-- Bootstrap core CSS -->
	<link href="../css/bootstrap.min.css" rel="stylesheet">

	<!-- Custom styles for this template -->
	<link href="../css/radiator.css" rel="stylesheet">
</head>

<body>
	<div class="container" style="max-width: 600px;">
		<div class="row header">
			<div class="col-xs-12">
				<h1>Radiator</h1>
				<ul class="nav nav-pills pull-right">
					<li>
						<a href="/" class="btn btn-default">
							<span class="glyphicon glyphicon-arrow-left"></span>
						</a>
					</li>
					<li>
						<a href="api/settings.php" class="btn btn-default">
							<span class="glyphicon glyphicon-cog"></span>
						</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<? if (!empty($bookmarks[0])) { ?>

				<h2>Закладки</h2>
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Станция</th>
							<th>Трек</th>
							<th><span data-obj="bookmark" title="Удалить все закладки!" class="remove_all glyphicon glyphicon-remove"></span></th>
						</tr>
					</thead>
					<tbody>
						<? foreach ($bookmarks as $bookmark) { ?>
						<tr>
							<td>
								<?=$bookmark['station_name']?>
							</td>
							<td class="sample">
								<? if ($bookmark['sample']) { ?>
								<audio src="../bookmarks/<?=$bookmark['sample']?>.mp3"></audio>
								<span class="switch glyphicon glyphicon-play"></span>
								<? } ?>
								<span><a href="http://vk.com/audio?q=<?=urlencode($bookmark['name'])?>" target="_blank" title="Искать Вконтакте"><?=$bookmark['name']?>
								</a></span>
							</td>
							<td>
								<span data-obj="bookmark" data-sample="<?=$bookmark['sample']?>" data-id="<?=$bookmark['id']?>" title="Удалить закладку" class="remove glyphicon glyphicon-remove"></span>
							</td>
						</tr>
						<? } ?>
					</tbody>
				</table>

				<? } ?>
			</div>
		</div>
	</div>
	<script src="../js/jquery.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script src="../js/settings.js"></script>
</body>
</html>