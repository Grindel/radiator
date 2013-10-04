<?php

$db	= new SQLite3('radiator.sqlite3');
require 'radiator.php';

if (isset($_REQUEST['act'])) {
	switch ($_REQUEST['act']) {
		case 'create':
			createStation();
			break;
			
		case 'delete':
			delete();
			break;
		
		default:
			header('Location: /');	
			break;
	}
};

$stations = selectFromDb('SELECT * FROM stations');
$bookmarks = selectFromDb('SELECT * FROM bookmarks');

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
				table.<table class="table striped">
					<thead>
						<tr>
							<th>Название</th>
							<th>URL</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Statition[name]</td>
							<td>Statioin[url]</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-md-6">
				<h2>Закладки</h2>
				table.<table class="table striped">
					<thead>
						<tr>
							<th>Трек</th>
							<th>Отрывок</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<a href="http://vk.com/audio?q=Bookmark[name]" target="_blank" title="Искать Вконтакте">	Bookmark[name]
								</a>
							</td>
							<td>
								<audio src="bookmarks/Bookmark[sample].mp3"></audio>
								<span class="glyphicon glyphicon-play"></span>
								<span class="glyphicon glyphicon-stop"></span>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<script src="//code.jquery.com/jquery.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>