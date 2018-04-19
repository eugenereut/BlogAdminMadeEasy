<article>
<header>
<?php
if (!empty($data['ShelvesMenu'])) {
  echo '<ul class="themas">';
  foreach ($data['ShelvesMenu'] as $value) {
    echo $value['SrtSh'];
  }
  echo '<li> | <a id="myBtn">все тексты</a></li></ul>';
}

if (!empty($data['Aboutbookcase'])) {echo "<em>" . $data['Aboutbookcase'] ."</em>";}
?>
</header>
<?php
if (!empty($data['Posts'])) {
  foreach ($data['Posts'] as $value) {
    echo '<section class="post">
    <header>
    <a href="/post?idpt='.$value['PostID'].'" class="title parastyle">' . $value['PostName'] . '</a>
    <time>' . $value['PostDate'] . '</time>
    <br><br>
    <div id="attachedbookcases">' . $value['PostBcSh'] . '</div>
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
    <h2><?php if (!empty($data['ShelveName'])){echo $data['ShelveName'];}?> · Все тексты</h2>
    <?php
    if (!empty($data['All_inmodal_window'])) {
      foreach ($data['All_inmodal_window'] as $value) {
        echo '<p><a href="/post?idpt='.$value['PostID'].'">' . $value['PostName'] . '</a></p>';
      }
    } ?>
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
