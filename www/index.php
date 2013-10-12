<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1"/>
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="format-detection" content="telephone=no">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="icon.png" />
	<link rel="apple-touch-startup-image" href="startup.png">
	<meta name="author" content="Grindel">
	<link rel="shortcut icon" href="favicon.png">

	<title>Raditor</title>

	<!-- Bootstrap core CSS -->
	<link href="css/bootstrap.min.css" rel="stylesheet">

	<!-- Custom styles for this template -->
	<link href="css/radiator.css" rel="stylesheet">

</head>

<body>
	<div class="container" style="max-width: 600px;">
		<div class="row header">
			<div class="col-xs-12">
				<h1>Radiator</h1>
				<ul class="nav nav-pills pull-right">
					<li>
						<a href="/api/bookmarks.php" class="btn btn-default">
							<span class="glyphicon glyphicon-bookmark"></span>
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
			<div id="controls" class="col-xs-12" style="display: none;">
				<div class="row">
					<div class="col-xs-12">
						<!-- <span id="refresh" class="glyphicon glyphicon-refresh control pull-right"></span> -->
						<a id="bookmark" class="glyphicon glyphicon-bookmark control pull-right"></a>
					</div>
				</div>
				<div class="row info">
					<div class="col-xs-12">
						<h2 id="station_name"></h2>
						<h3 id="song_title"></h3>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-4">
						<a id="prev" class="glyphicon glyphicon-backward control"></a>
					</div>
					<div class="col-xs-4">
						<a id="play" class="glyphicon glyphicon-play control"></a>
						<a id="pause" class="glyphicon glyphicon-pause control"></a>
					</div>
					<div class="col-xs-4">
						<a id="next" class="glyphicon glyphicon-forward control"></a>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12">
						<div class="slider-wrapper">
							<div id="volume"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<hr>
	</div>
	<script src="js/jquery.js"></script>
	<script src="js/jquery-ui-1.10.3.custom.min.js"></script>
	<script src="js/jquery.ui.touch-punch.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/controls.js"></script>
</body>
</html>