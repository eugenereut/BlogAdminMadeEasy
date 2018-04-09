<article>
<header>
<ul class="themas">
  <li><h2>Update Bookcase</h2></li><li> | </li><li><a href="/newbookcase"><span>back</span></a></li>
</ul>
<?php
  if (!empty($data['houston'])) {
    echo '<p style="color: red">'.$data['houston'].'</p>';
  } ?>
</header>
<section class="third">
<div><h2><?php echo $data['NameBookcase']; ?></h2><br><?php echo $data['AboutBC']; ?></div>
<div class="newbookcases">
  <form action="" method="post">
    <div class="selectwrap">
      <input value="<?php echo $data['NameBookcase']; ?>" name="bookcase" placeholder="name bookcase" required="" type="text">
    </div>
    <div class="inputwrap">
      <input value="<?php echo $data['AboutBC']; ?>" name="aboutbookcase" placeholder="comment" type="text">
      <input value="Update" name="Updatebookcase" type="submit">
    </div>
    <div class="deletewrap">
      <label><input type="checkbox" name="agreetodelete" value="Yes"> I agree to delete.</label>
      <div><input value="Delete" name="Deletebookcase" type="submit"></div>
    </div>
  </form>
</div>
</section>
</article><br><br>
<footer>
  <div>© Авторские права 2010—2018, Священник Яков Кротов</div>
</footer>
