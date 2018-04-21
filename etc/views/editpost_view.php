<style type="text/css" media="all">
	@import "/common/vendor/widgEditor/css/widgEditor.css";
</style>
<article>
<header>
	<ul class="themas">
	  <li><h2>Edit Post</h2></li><li> | </li><li><a href="/setuppost?idpt=<?php echo $data['iDPost']; ?>"><span>back</span></a></li>
	</ul>
<?php if (!empty($data['houston'])) { echo '<p style="color: red">'.$data['houston'].'</p>'; } ?>
</header>
<form action=""  method="post">
<input type="hidden" value="<?php echo $data['iDPost']; ?>" name="iDPost">
<section class="third">
<div><input type="text" name="Datefrom" class="form-control text-center" id="datefrom" value="<?php	if (!empty($data['PostDate'])) { echo $data['PostDate'];} else { echo date('D M d Y'); }?>" required></div>
<div class="newbookcases">
    <div class="inputwrap">
      <input value="<?php echo $data['PostName']; ?>" name="Headerpost" placeholder="Post name" type="text" required>
    </div>
</div>
</section>
<section>
  <div class="formpostbody widgContainer">
    <fieldset class="newpostarea">
      <textarea id="noise" name="Bodypost" class="widgEditor nothing">
        <?php
        if (!empty($data['PostBody'])) {
          echo $data['PostBody'];
        } else {
          echo 'Text goes here!';
        } ?>
      </textarea>
    </fieldset>
    <fieldset class="submit">
	    <div class="newbookcases"><input value="Edit post" name="Editpost" type="submit"></div>
    </fieldset>
</div>
</section>
</form>
</article>
<br><br>
<footer>
  <div>© Авторские права 2010—2018, Священник Яков Кротов</div>
</footer>
<script src="/common/vendor/widgEditor/scripts/widgEditor.js"></script>
<script src="/common/js/pickmeup.min.js"></script>
<script src="/common/js/overview.js"></script>
