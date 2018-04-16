<article>
<header>
  <ul class="themas">
  <li><a href=""><h2>Афоризмы: история</h2></a></li>
  <li><a href=""><h2>Введение в историю</h2></a></li>
  <li><a href=""><h2>История Церкви</h2></a></li>
  <li><a href=""><h2>Ср. Время.</h2></a></li> / <li><a id="myBtn">все тексты</a></li>
</ul>
Исторические этюды по хронологии
</header>
<?php
if (!empty($data['Posts'])) {
  foreach ($data['Posts'] as $value) {
    echo '<section class="post">
    <header>
    <a href="/post?idpt='.$value['PostID'].'" class="title parastyle">' . $value['PostName'] . '</a>
    <time>' . $value['PostDate'] . '</time>
    </header>
    <div class="postbody">' . $value['PostBody'] . '<p><a href="/post?idpt='.$value['PostID'].'" class="readmore">Читать дальше…</a></p>
    </div>
    </section>';
  }
} ?>
<div class="pagination">
<span class="prev">« Туда</span>
<span class="currentpg">1</span>
<a href="">2</a>
<a href="">3</a>
<a href="" class="next">Сюда »</a>
</div>
</article>
<!-- services Modal window -->
<!-- The Modal -->
<div id="myModal" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
  <span class="close">&times;</span>
    <h2>История или Прошлое · Все тексты</h2>
    <p><a href="writing.html">Три слома человечества.</a></p>
    <p><a href="">Время и пространство: от крестьянской архаики до современности.</a></p>
    <p><a href="">Искусство как приватизированная история.</a></p>
    <p><a href="">Давидград: что построил царь Давид.</a></p>
    <p><a href="">С чего начинается общество: от мегалитов к микросхемам.</a></p>
    <p><a href="">XI век до р.Х. (условно). Иисус Навин: исход вовнутрь.</a></p>
    <p><a href="">930 год до р.Х.: пенис царя  Соломона.</a></p>
    <p><a href="">IX век до р.Х. Пророк Илия.</a></p>
    <p><a href="">716 год до р.Х.: Езекия или Царь Мыльного Пузыря.</a></p>
    <p><a href="">702 год: История Шевнаяху: гробница пуста, а воскресения не было.</a></p>
    <p><a href="">690 год до р.Х.: Манассия: царь на верёвочке.</a></p>
    <p><a href="">642 год до р.Х,: Амон или Секс в 13 лет.</a></p>
    <p><a href="">609 год до р.Х.: Иосия: плюсы ранней смерти?</a></p>
    <p><a href="">597 год до р.Х.: Иоаким или Жар пророчеств.</a></p>
    <p><a href="">Иехония: в новую жизнь  — &nbsp;с новым именем.</a></p>
    <p><a href="">Иехония:  прощение как двигатель истории.</a></p>
    <p><a href="">587 год: история Абдул Малика, падения Иерусалима и подъёма Иеремии.</a></p>
    <p><a href="">На реках Вавилонских: возможна ли вера после Плена.</a></p>
    <p><a href="">570 год до р.Х: Салафииль: асимметрия греха и добра.</a></p>
    <p><a href="">550 год до р.Х.: Зоровавель, ровесник Эзопа.</a></p>
    <p><a href="">Сократ, Платон и Христос.</a></p>
    <br>
    <div class="pagination">
    <span class="prev">« Туда</span>
    <span class="currentpg">1</span>
    <a href="">2</a>
    <a href="">3</a>
    <a href="" class="next">Сюда »</a>
    </div>
  </div>
</div>
<script>
// Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal
btn.onclick = function() {
modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
if (event.target == modal) {
    modal.style.display = "none";
}
}
</script>
<footer>
<div>© Авторские права 2010—2018, Священник Яков Кротов</div>
</footer>
