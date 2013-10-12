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

$stations = selectFromDb('SELECT * FROM stations ORDER BY stations.id DESC');

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
						<a href="/api/bookmarks.php" class="btn btn-default">
							<span class="glyphicon glyphicon-bookmark"></span>
						</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<h2>Станции</h2>
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Название</th>
							<th>URL</th>
							<th><span data-obj="station" title="Удалить все станции!" class="remove_all glyphicon glyphicon-remove"></span>
							</th>
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
		</div>
		<hr>
	</div>
	<script src="../js/jquery.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script src="../js/settings.js"></script>
</body>
</html>