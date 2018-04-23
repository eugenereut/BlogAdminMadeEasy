<article>
<header>
<h2>Блог</h2>
</header>
<section>
<header>
<h1><?php if (!empty($data['PostName'])) {echo $data['PostName'];} ?></h1>
<time><?php if (!empty($data['PostDate'])) {echo $data['PostDate'];} ?></time>
<br><br>
<div id="attachedbookcases"><?php if (!empty($data['PostBcSh'])) {echo $data['PostBcSh'];} ?></div>
</header>
<div class="postbody">
  <?php if (!empty($data['PostBody'])) {echo $data['PostBody'];} ?>
</div>
</section>
<div class="postnav">
  <a class="prev" href="/post?idpt=<?php if (!empty($data['NextPost'])) {echo $data['NextPost'][1];} ?>">
  <h3>Следующий текст</h3>
  <span><?php if (!empty($data['NextPost'])) {echo $data['NextPost'][0];} ?></span>
  </a>
</div>
</article>
<footer>
<div>© Авторские права 2010—2018, Священник Яков Кротов</div>
</footer>
