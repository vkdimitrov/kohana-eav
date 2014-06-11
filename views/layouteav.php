<?php defined('SYSPATH') or die('No direct script access.');
?>
<!DOCTYPE html>
<html lang="bg">
	<head>
		<title>EAV module</title>
	</head>
	<body>
		<style>
		body {
			color: #333333;
			font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
			font-size: 14px;
			line-height: 1.42857;
		}
		table {
			max-width: 100%;
			border-collapse: collapse;
			border-spacing: 0;
		}
		td.center{
			text-align:center; 
			vertical-align:middle;
		}

		</style>
		<? #View::factory('sections/header');?>
		<?php
		if(isset($messages) && !empty($messages))
		{
			echo '<div id="messages" class="container">';
			foreach($messages as $message) 
				echo View::factory('sections/message', array('data' => $message));
			echo '</div>';
		}
		?>
		<?=$content?>
		<?#View::factory('sections/footer');?>
	</body>
</html>