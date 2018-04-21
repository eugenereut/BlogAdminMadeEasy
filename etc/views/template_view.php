<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo $title['title']; ?></title>
	<!-- link href="/common/images/favicon.ico" rel="shortcut icon" type="image/x-icon" -->
	<link rel="apple-touch-icon-precomposed" sizes="57x57" href="/common/images/favicons/apple-touch-icon-57x57.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/common/images/favicons/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/common/images/favicons/apple-touch-icon-114x114.png">
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="/common/images/favicons/apple-touch-icon-144x144.png">
	<link rel="apple-touch-icon-precomposed" sizes="120x120" href="/common/images/favicons/apple-touch-icon-120x120.png">
	<link rel="apple-touch-icon-precomposed" sizes="152x152" href="/common/images/favicons/apple-touch-icon-152x152.png">
	<link rel="icon" type="image/png" href="/common/images/favicons/favicon-32x32.png" sizes="32x32">
	<link rel="icon" type="image/png" href="/common/images/favicons/favicon-16x16.png" sizes="16x16">

	<link rel="stylesheet" href="/common/css/screen.css" type="text/css">
	<link rel="stylesheet" type="text/css" href="/common/css/pickmeup-white.css">
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
	<a href="/writing"><span>[+] add post</span></a>
	<a href="/allposts"><span>all blog posts</span></a>
	<a href="/newbookcase"><span>[+] add bookcase</span></a>
	</nav>
	<ul class="social">
	<li><a href=""><img src="/common/images/mail.svg" alt="Email"></a></li>
	</ul>
	</header>
	<?php include 'etc/views/'.$content_view; unset($data); ?>
</body>
</html>
