<article>
<header>
  <ul class="themas">
    <li><h2>Update Shelve</h2></li><li> | </li><li><a href="/newbookcase"><span>back</span></a></li>
  </ul>
<?php
  if (!empty($data['houston'])) {
    echo '<p style="color: red">'.$data['houston'].'</p>';
  } ?>
</header>
<section class="third">
<div><h2><?php echo $data['NameBookcase']; ?></h2><br><?php echo $data['NameShelve']; ?></div>
<div class="newbookcases">
  <form action="" method="post">
    <div class="selectwrap">
      <select name="selectbookcase">
        <?php
        if (!empty($data['select_bookcases'])) {
          //foreach ($data['select_bookcases'] as $value) {
            echo $data['select_bookcases'];
          //}
        } ?>
      </select>
    </div>
      <div class="inputwrap">
      <input value="<?php echo $data['NameShelve']; ?>" name="shelve" placeholder="name shelve" required="" type="text">
      <input value="Update" name="Updateshelve" type="submit">
    </div>
    <div class="deletewrap">
      <label><input type="checkbox" name="agreetodelete" value="Yes"> I agree to delete.</label>
      <div><input value="Delete" name="Deleteshelve" type="submit"></div>
    </div>
    </div>
  </form>
</div>
</section>

</article><br><br>
<footer>
  <div>© Авторские права 2010—2018, Священник Яков Кротов</div>
</footer>
