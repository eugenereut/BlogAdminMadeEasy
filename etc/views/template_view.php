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
  <!-- link href="/common/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" -->
	<link href="https://fonts.googleapis.com/css?family=Playfair+Display" rel="stylesheet">
</head>
<body>
	<header>
	<a href="" class="logo">С/Я/К<span>тексты и книги</span></a>
	<nav role="main">
	<a href="/"><span>История или Прошлое</span></a>
	<a href=""><span>Жизнь или Настоящее</span></a>
	<a href=""><span>Политика или Будущее</span></a>
	<a href=""><span>Вера или Вечное</span></a>
	<a href=""><span>Афоризмы</span></a>
	<a href=""><span>Манифесты</span></a>
	<a href=""><span>Переводы</span></a>
	<a href=""><span>Блог</span></a><br>
	<span style="color: #ccc;">Указатели:</span>
	<a href=""><span>предметный</span></a>
	<a href=""><span>именной</span></a>
	<a href=""><span>географический</span></a>
	</nav>
	<ul class="social">
	<li><a href=""><img src="/common/images/mail.svg" alt="Email"></a></li>
	</ul>
	</header>
	<?php include 'etc/views/'.$content_view; unset($data); ?>
	<!-- jQuery -->
	<!-- script
			  src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
			  integrity="sha256-3edrmyuQ0w65f8gfBsqowzjJe2iM6n0nKciPUp8y+7E="
			  crossorigin="anonymous"></script -->
	<!-- Bootstrap Core JavaScript -->
	<!-- script src="/common/vendor/js/bootstrap.min.js"></script -->
</body>
</html>
