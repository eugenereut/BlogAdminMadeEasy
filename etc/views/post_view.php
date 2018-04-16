<article>
<header>
<h2>Блог</h2>
<ul class="themas">
<li><a href="history.html"><h2>История или Прошлое</h2></a></li>
<li><a href="">Введение в историю</a></li>
<li><a href="">История Церкви</a></li> / <li><a id="myBtn">все тексты</a></li>
</ul>
</header>
<section>
<header>
<h1><?php if (!empty($data['PostName'])) {echo $data['PostName'];} ?></h1>
<time><?php if (!empty($data['PostDate'])) {echo $data['PostDate'];} ?></time>
</header>
<div class="postbody">
  <?php if (!empty($data['PostBody'])) {echo $data['PostBody'];} ?>
</div>
</section>
<div class="postnav">
<div>
<a class="prev" href="">
<h3>Следующий пост</h3>
<span>Время и Пространство: От Крестьянской Архаики До Современности</span>
</a>
</div>
</div>
</article>
<footer>
<div>© Авторские права 2010—2018, Священник Яков Кротов</div>
</footer>
