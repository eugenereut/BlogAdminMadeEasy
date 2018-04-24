<article>
<header>
  <ul class="themas">
    <li><h2 style="color: #d41c1c">Delete Post</h2></li><li> | </li><li><a href="/setuppost?idpt=<?php echo $data['iDPost']; ?>"><span>back</span></a></li>
  </ul>
<?php
  if (!empty($data['houston'])) {
    echo '<p style="color: #ec3c3c">'.$data['houston'].'</p>';
  } ?>
</header>
<section class="third">
<div class="deletepost">
  <form action="" method="post">
    <input type="hidden" value="<?php echo $data['iDPost']; ?>" name="iDPost">
    <div class="deletewrap">
      <div style="padding: 15px 0 5px">
        <h3 style="color: #d41c1c">
          <?php	if (!empty($data['PostName'])) { echo "Блог будет полностью удален с вебсайта!";} else { echo "Блог удален с вебсайта!"; }?>
        </h3></div>
       <div style="padding: 15px 0 5px"><?php	if (!empty($data['PostName'])) { echo $data['PostName'];} ?></div>
       <time><?php	if (!empty($data['PostDate'])) { echo $data['PostDate'];} else { echo date('D M d Y'); }?></time>
       <div style="padding: 15px 0 5px"><?php echo $data['PostBcSh']; ?></div>
      <div>
        <label><input type="checkbox" name="agreetodelete" value="Yes"> I agree to delete.</label>
        <div><input value="Delete Post" name="DeletePost" type="submit"></div>
      </div>
    </div>
  </form>
</div>
</section>
</article><br><br>
<footer>
  <div>© Авторские права 2010—2018, Священник Яков Кротов</div>
</footer>
