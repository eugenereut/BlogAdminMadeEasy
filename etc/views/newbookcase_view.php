<article>
<header>
<h2>Add Bookcases and Shelves</h2>
<?php
  if (!empty($data['houston'])) {
    echo '<p style="color: red">'.$data['houston'].'</p>';
  } ?>
</header>
<section class="third">
<div><h2>New Bookcase</h2><br>all bookcases go to the left menu</div>
  <div class="newbookcases">
  <form action="" method="post">
    <div class="selectwrap">
      <input value="" name="bookcase" placeholder="name bookcase" required="" type="text">
    </div>
    <div class="inputwrap">
      <input value="" name="aboutbookcase" placeholder="comment" type="text">
      <input value="Submit" name="Submitbookcase" type="submit">
    </div>
  </form>
</div>
</section>
<section class="third">
  <div><h2>New Shelves</h2><br>all shelves go to the top menu</div>
  <div class="newbookcases">
    <form action="" method="post">
      <div class="selectwrap">
        <select name="selectbookcase">
          <?php
          if (!empty($data['select_bookcases'])) {
            foreach ($data['select_bookcases'] as $value) {
              echo $value['option'];
            }
          } ?>
        </select>
      </div>
        <div class="inputwrap">
        <input value="" name="shelve" placeholder="name shelve" required="" type="text">
        <input value="Submit" name="Submitshelve" type="submit">
      </div>
    </form>
  </div>
</section>
<br><br>
<header>
<h2>List of Bookcases and Shelves</h2>
</header>
<?php
if (!empty($data['bookcase'])) {
  foreach ($data['bookcase'] as $value) {
    echo '<section class="third">
    <div><h2><a href="/updatebookcase?idbc='.$value['Record'].'">' . $value['NameBookcase'] . '</a></h2>
    <br>' . $value['AboutBC'] . '</div>
    <div class="listbookcases"><ul class="shelvenav">' . $value['Shelve'] . '</ul></div>
    </section>';
  }
} ?>
</article><br><br>
<footer>
<div>© Авторские права 2010—2018, Священник Яков Кротов</div>
</footer>
