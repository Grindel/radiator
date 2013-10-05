<?php

$db	= new SQLite3('radiator.sqlite3');
require 'radiator.php';

if (isset($_REQUEST['act'])) {
	switch ($_REQUEST['act']) {
		case 'create':
			createStation();
			break;
			
		case 'del':
			delete();
			break;
		
		default:
			header('Location: /');	
			break;
	}
};

$stations = selectFromDb('SELECT * FROM stations');
$bookmarks = selectFromDb('SELECT bookmarks.id, bookmarks.name, bookmarks.sample, stations.name AS station_name
							FROM bookmarks
							LEFT  JOIN stations
							ON stations.id=bookmarks.station_id;');

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="Grindel">
	<link rel="shortcut icon" href="favicon.png">

	<title>Raditor Pi</title>

	<!-- Bootstrap core CSS -->
	<link href="css/bootstrap.min.css" rel="stylesheet">

	<!-- Custom styles for this template -->
	<link href="css/radiator.css" rel="stylesheet">
</head>

<body>
	<div class="container">

		<div class="page-header">
			<h1>Radiator панель управления</h1>
			<p class="lead">Сдесь можно добавлять радиостанции и просматривать добавленные закладки.</p>
		</div>
		<div class="row">
			<div class="col-md-6">
				<h2>Станции</h2>
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Название</th>
							<th>URL</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<? foreach ($stations as $station) { ?>
						<tr class="station">
							<td><?=$station['name']?></td>
							<td><?=$station['url']?></td>
							<td>
								<span data-obj="station" data-id="<?=$station['id']?>" title="Удалить станцию" class="remove glyphicon glyphicon-remove"></span>
							</td>
						</tr>
						<? } ?>
					</tbody>
				</table>
				<h3>Добавить станцию</h3>
				<form class="form-inline" role="form" method="post">
					<input type="hidden" name="act" value="create">
					<div class="form-group">
						<input type="text" name="name" class="form-control" placeholder="Название">
					</div>
					<div class="form-group">
						<input type="text" name="url" class="form-control" placeholder="url">
					</div>
					<button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span></button>
				</form>
			</div>
			<? if (!empty($bookmarks[0])) { ?>
			<div class="col-md-6">
				<h2>Закладки</h2>
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Станция</th>
							<th>Трек</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<? foreach ($bookmarks as $bookmark) { ?>
						<tr>
							<td>
								<?=$bookmark['station_name']?>
							</td>
							<td class="sample">
								<audio src="bookmarks/<?=$bookmark['sample']?>.mp3"></audio>
								<span class="switch glyphicon glyphicon-play"></span>
								<span><a href="http://vk.com/audio?q=<?=$bookmark['name']?>" target="_blank" title="Искать Вконтакте"><?=$bookmark['name']?>
								</a></span>
							</td>
							<td>
								<span data-obj="bookmark" data-sample="<?=$bookmark['sample']?>" data-id="<?=$bookmark['id']?>" title="Удалить закладку" class="remove glyphicon glyphicon-remove"></span>
							</td>
						</tr>
						<? } ?>
					</tbody>
				</table>
			</div>
			<? } ?>
		</div>
		<hr>
	</div>
	<script src="js/jquery.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/radiator.js"></script>
</body>
</html>