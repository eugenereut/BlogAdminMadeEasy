<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo $title['title']; ?></title>
	<link href="/common/images/favicon.ico" rel="shortcut icon" type="image/x-icon">
	<link rel="stylesheet" href="/common/css/screen.css" type="text/css">
	<!-- Custom Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Playfair+Display" rel="stylesheet">
</head>
<body>
	<header>
	<a href="/" class="logo">СЯК<span>тексты и книги</span></a>
	<nav role="main">
		<?php
		if (!empty($title['leftmenu'])) {
			foreach ($title['leftmenu'] as $value) {
				echo $value['menustr'];
			}
		} ?>
	<br>
	<!-- span style="color: #ccc;">Указатели:</span>
	<a href=""><span>предметный</span></a>
	<a href=""><span>именной</span></a>
	<a href=""><span>географический</span></a -->
	<br>
	<a href="/writing"><span>add text</span></a>
	<a href="/newbookcase"><span>add bookcases</span></a>
	</nav>
	<ul class="social">
	<li><a href=""><img src="/common/images/mail.svg" alt="Email"></a></li>
	</ul>
	</header>
	<?php include 'etc/views/'.$content_view; unset($data); ?>
</body>
</html>
