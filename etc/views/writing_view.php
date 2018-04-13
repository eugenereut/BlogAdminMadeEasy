<style type="text/css" media="all">
	@import "/common/vendor/widgEditor/css/widgEditor.css";
</style>
<article>
<header>
<h2>New Post</h2>
<?php if (!empty($data['houston'])) { echo '<p style="color: red">'.$data['houston'].'</p>'; } ?>
</header>
<form action=""  method="post">
<section class="third">
<div><input type="text" name="Datefrom" class="form-control text-center" id="datefrom" value="<?php echo date('D M d Y');?>" required></div>
<div class="newbookcases">
    <div class="inputwrap">
      <input value="<?php echo $data['Headerpost']; ?>" name="Headerpost" placeholder="Post name" type="text" required>
    </div>
</div>
</section>
<section>
  <div class="formpostbody widgContainer">
    <fieldset class="newpostarea">
      <textarea id="noise" name="Bodypost" class="widgEditor nothing">
        <?php
        if (!empty($data['Bodypost'])) {
          echo $data['Bodypost'];
        } else {
          echo 'Text goes here!';
        } ?>
      </textarea>
    </fieldset>
    <fieldset class="submit">
      <input value="Add new post" name="Addnewpost" type="submit">
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
